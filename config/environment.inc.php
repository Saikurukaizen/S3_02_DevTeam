<?php
declare(strict_types=1);

class Environment{

    public const ENV_PHP_SERVER = 'php_server';
    public const ENV_XAMPP = 'xampp';

    public static function detectEnvironment(): string{
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $serverPort = $_SERVER['SERVER_PORT'] ?? '';
        $httpHost = $_SERVER['HTTP_HOST'] ?? '';

        if ($serverPort === '8000' || strpos($httpHost, ':8000') !== false) {
        return self::ENV_PHP_SERVER;
        }

        if (
            $serverPort === '80' ||
            $serverPort === '443' ||
            strpos($httpHost, ':80') !== false ||
            strpos($httpHost, ':443') !== false
        ) {
            return self::ENV_XAMPP;
        }
        return self::ENV_XAMPP;
    }

    public static function getBaseUrl(): string {
        $env = self::detectEnvironment();

        switch ($env) {
            case self::ENV_PHP_SERVER:
                return '';
            case self::ENV_XAMPP:
            default:
                $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
                $baseUrl = rtrim(str_replace(basename($scriptName), '', $scriptName), '/');
                return $baseUrl;          
        }
    }

    public static function url(string $path = ''): string{
        $path = ltrim($path, '/');
        return self::getBaseUrl() . '/' . $path;
    }

    public static function asset(string $path = ''): string{
        $path = ltrim($path, '/');
        return self::getBaseUrl() . '/' . $path;
    }
}


define('ENVIRONMENT', Environment::detectEnvironment());
define('BASE_URL', Environment::getBaseUrl());

?>
