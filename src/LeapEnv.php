<?php
declare(strict_types=1);

namespace FlatLeap;

class LeapEnv
{
    private static $vars = [];
    private static $loaded = false;

    /**
     * Load environment variables from a .env file
     *
     * @param string $path Path to the .env file
     * @return bool True if loaded successfully, false otherwise
     */
    public static function load($path)
    {
        if (!file_exists($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            // Skip comments and empty lines
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE format
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes if present
                if (
                    (substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
                ) {
                    $value = substr($value, 1, -1);
                }

                // Store in static array
                self::$vars[$key] = $value;

                // Also set as environment variable
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }

        self::$loaded = true;
        return true;
    }

    /**
     * Get an environment variable value
     *
     * @param string $key Variable name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (isset(self::$vars[$key])) {
            return self::$vars[$key];
        }

        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return $default;
    }

    /**
     * Check if environment has been loaded
     *
     * @return bool
     */
    public static function isLoaded()
    {
        return self::$loaded;
    }

    /**
     * Get all loaded environment variables
     *
     * @return array
     */
    public static function all()
    {
        return self::$vars;
    }
}
