<?php
declare(strict_types=1);

class Environment 
{
    public static string $serverType = '';
    public static int $port = 80;
    public static string $baseUrl = '';
    public static bool $isLocal = false;
    public static string $documentRoot = '';

    public static function detect(): void 
    {
        self::$port = (int) ($_SERVER['SERVER_PORT'] ?? 80);
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        if (mb_stripos($serverSoftware, 'PHP') !== false && mb_stripos($serverSoftware, 'Development') !== false) {
            self::$serverType = 'php-builtin';
        }
        elseif (mb_stripos($serverSoftware, 'Apache') !== false) {
            self::$serverType = 'apache';
        }
        elseif (mb_stripos($serverSoftware, 'nginx') !== false) {
            self::$serverType = 'nginx';
        }
        elseif (mb_stripos($serverSoftware, 'IIS') !== false) {
            self::$serverType = 'iis';
        }
        else {
            self::$serverType = 'other';
        }
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        self::$isLocal = (
            mb_stripos($host, 'localhost') !== false ||
            mb_stripos($host, '127.0.0.1') !== false ||
            mb_stripos($host, '::1') !== false ||
            mb_stripos($host, '.local') !== false
        );       
        $protocol = self::isHttps() ? 'https' : 'http';
        $hostHasPort = strpos($host, ':') !== false;
        $portPart = '';

        if (!$hostHasPort && self::$port != 80 && self::$port != 443) {
            $portPart = ':' . self::$port;
        }       
        self::$baseUrl = $protocol . '://' . $host . $portPart;
        self::$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $projectPath = dirname($scriptName);
        $projectPath = str_replace('\\', '/', $projectPath);
        
        if ($projectPath !== '/' && $projectPath !== '') {
            self::$baseUrl .= $projectPath;
        }
    }

    private static function isHttps(): bool 
    {
        return (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            ($_SERVER['SERVER_PORT'] ?? 80) == 443 ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        );
    }

    public static function getInfo(): array 
    {
        return [
            'server_type' => self::$serverType,
            'port' => self::$port,
            'base_url' => self::$baseUrl,
            'is_local' => self::$isLocal,
            'document_root' => self::$documentRoot,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'http_host' => $_SERVER['HTTP_HOST'] ?? 'Unknown'
        ];
    }

    public static function url(string $path = ''): string 
    {
        if (!empty($path) && $path[0] !== '/') {
            $path = '/' . $path;
        }       
        return self::$baseUrl . $path;
    }

    public static function asset(string $path = ''): string 
    {
        return self::url($path);
    }
    
    public static function isLocal(): bool 
    {
        return self::$isLocal;
    }
    
    public static function isProduction(): bool 
    {
        return !self::$isLocal;
    }
    
    public static function getServerType(): string 
    {
        return self::$serverType;
    }
}

if (php_sapi_name() !== 'cli' && php_sapi_name() !== 'phpdbg') {
    Environment::detect();
}

define('ENVIRONMENT', Environment::getServerType());
define('BASE_URL', Environment::url());

function url(string $path = ''): string {
    return Environment::url($path);
}

?>
