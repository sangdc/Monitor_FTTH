<?php
/**
 * Environment Configuration Helper
 * Load and manage .env variables
 */
class EnvConfig {
    private static $config = [];
    private static $loaded = false;

    public static function load($envFile = '.env') {
        if (self::$loaded) {
            return;
        }

        $envPath = __DIR__ . '/../../' . $envFile;
        
        if (!file_exists($envPath)) {
            throw new Exception('.env file not found');
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                $value = trim($value, '"\'');
                
                self::$config[$key] = $value;
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }

        self::$loaded = true;
    }

    public static function get($key, $default = null) {
        if (!self::$loaded) {
            self::load();
        }
        return self::$config[$key] ?? $default;
    }

    public static function isApiEnabled() {
        return filter_var(self::get('API_ENABLED', 'true'), FILTER_VALIDATE_BOOLEAN);
    }

    public static function verifyApiKey($providedKey) {
        if (!self::isApiEnabled()) {
            return false;
        }
        $validKey = self::get('API_KEY');
        return hash_equals($validKey, $providedKey);
    }
}
