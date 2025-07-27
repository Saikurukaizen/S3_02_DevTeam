<?php
declare(strict_types=1);

/**
 * Sistema de detección automática de ambiente
 * 
 * Este archivo detecta automáticamente el tipo de servidor y ambiente
 * donde el sistema está corriendo, sin depender de estructura de carpetas
 * o configuraciones manuales.
 * 
 * Funciona en cualquier instalación PHP posible:
 * - XAMPP, WAMP, MAMP
 * - PHP Built-in Server
 * - Apache, Nginx, IIS
 * - Docker, Cloud, Shared Hosting
 * 
 * @author Dev Team
 * @version 2.0
 */
class Environment 
{
    /**
     * @var string Tipo de servidor detectado
     */
    public static string $serverType = '';
    
    /**
     * @var int Puerto del servidor
     */
    public static int $port = 80;
    
    /**
     * @var string URL base del sistema
     */
    public static string $baseUrl = '';
    
    /**
     * @var bool Indica si es ambiente local
     */
    public static bool $isLocal = false;
    
    /**
     * @var string Document root del servidor
     */
    public static string $documentRoot = '';
    
    /**
     * Función principal que detecta el ambiente actual
     * Usa apenas variables $_SERVER que existen en cualquier instalación PHP
     * 
     * @return void
     */
    public static function detect(): void 
    {
        // Detecta el puerto del servidor - funciona en cualquier ambiente
        self::$port = (int) ($_SERVER['SERVER_PORT'] ?? 80);
        
        // Detecta el tipo de servidor por el software usado
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        
        // Identifica si es servidor de desarrollo PHP nativo
        // mb_stripos: case insensitive + soporte multibyte
        if (mb_stripos($serverSoftware, 'PHP') !== false && mb_stripos($serverSoftware, 'Development') !== false) {
            self::$serverType = 'php-builtin';
        }
        // Identifica si es Apache (XAMPP, WAMP, etc)
        elseif (mb_stripos($serverSoftware, 'Apache') !== false) {
            self::$serverType = 'apache';
        }
        // Identifica si es Nginx
        elseif (mb_stripos($serverSoftware, 'nginx') !== false) {
            self::$serverType = 'nginx';
        }
        // Identifica si es IIS (Windows)
        elseif (mb_stripos($serverSoftware, 'IIS') !== false) {
            self::$serverType = 'iis';
        }
        // Cualquier otro servidor
        else {
            self::$serverType = 'other';
        }
        
        // Detecta si es ambiente local o produccion
        // mb_stripos: case insensitive + multibyte para hosts internacionales
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        self::$isLocal = (
            mb_stripos($host, 'localhost') !== false ||
            mb_stripos($host, '127.0.0.1') !== false ||
            mb_stripos($host, '::1') !== false ||
            mb_stripos($host, '.local') !== false
        );
        
        // Monta la URL base del sistema
        $protocol = self::isHttps() ? 'https' : 'http';
        
        // Verifica se HTTP_HOST já inclui a porta
        $hostHasPort = strpos($host, ':') !== false;
        
        // Solo agrega la porta si HTTP_HOST no la incluye y no es puerto padrão
        $portPart = '';
        if (!$hostHasPort && self::$port != 80 && self::$port != 443) {
            $portPart = ':' . self::$port;
        }
        
        self::$baseUrl = $protocol . '://' . $host . $portPart;
        
        // Define el document root - donde el servidor sirve archivos
        self::$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        
        // Agrega el camino del proyecto si no esta en la raiz
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $projectPath = dirname($scriptName);
        
        // Normaliza separadores para URLs (siempre barras normales)
        // SCRIPT_NAME siempre usa / pero dirname() puede retornar \ en Windows
        $projectPath = str_replace('\\', '/', $projectPath);
        
        // Verifica si no esta en la raiz del servidor
        if ($projectPath !== '/' && $projectPath !== '') {
            self::$baseUrl .= $projectPath;
        }
    }
    
    /**
     * Detecta si la conexión es HTTPS
     * Funciona en cualquier servidor web
     * 
     * @return bool True si es HTTPS
     */
    private static function isHttps(): bool 
    {
        return (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            ($_SERVER['SERVER_PORT'] ?? 80) == 443 ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        );
    }
    
    /**
     * Retorna información del entorno para depuración
     * Útil para desarrolladores verificar si la detección funcionó
     * 
     * @return array Información del ambiente
     */
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
    
    /**
     * Função de conveniência para obtener URL completa de un archivo
     * 
     * @param string $path Camino relativo al archivo
     * @return string URL completa
     */
    public static function url(string $path = ''): string 
    {
        // Si path no comienza con /, agrega una
        if (!empty($path) && $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        return self::$baseUrl . $path;
    }
    
    /**
     * Alias para assets (CSS, JS, imágenes)
     * 
     * @param string $path Camino relativo al asset
     * @return string URL completa del asset
     */
    public static function asset(string $path = ''): string 
    {
        return self::url($path);
    }
    
    /**
     * Verifica si está en ambiente de desarrollo local
     * 
     * @return bool True si es ambiente local
     */
    public static function isLocal(): bool 
    {
        return self::$isLocal;
    }
    
    /**
     * Verifica si está en ambiente de producción
     * 
     * @return bool True si es ambiente de producción
     */
    public static function isProduction(): bool 
    {
        return !self::$isLocal;
    }
    
    /**
     * Obtiene el tipo de servidor detectado
     * 
     * @return string Tipo de servidor
     */
    public static function getServerType(): string 
    {
        return self::$serverType;
    }
}

// Auto-ejecuta la detección APENAS si está corriendo vía web server
// Evita problemas cuando incluido en scripts CLI o contextos no-web
if (php_sapi_name() !== 'cli' && php_sapi_name() !== 'phpdbg') {
    Environment::detect();
}

// Definir constantes para compatibilidad con código existente
define('ENVIRONMENT', Environment::getServerType());
define('BASE_URL', Environment::url());

/**
 * Helper function para compatibilidad con views existentes
 * 
 * @param string $path Camino relativo
 * @return string URL completa
 */
function url(string $path = ''): string {
    return Environment::url($path);
}

?>
