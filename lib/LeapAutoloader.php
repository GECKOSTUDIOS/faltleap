<?php

declare(strict_types=1);

namespace FaltLeap;

/**
 * PSR-4 Autoloader for FaltLeap Framework
 * Automatically loads classes based on namespace and file location
 */
class LeapAutoloader
{
    /**
     * @var array<string, string> Namespace prefix to base directory mapping
     */
    private array $prefixes = [];

    /**
     * Register the autoloader with SPL
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Add a base directory for a namespace prefix
     *
     * @param string $prefix The namespace prefix
     * @param string $baseDir The base directory for classes in that namespace
     * @param bool $prepend If true, prepend the base directory to the stack
     */
    public function addNamespace(string $prefix, string $baseDir, bool $prepend = false): void
    {
        // Normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize the base directory with a trailing separator
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        // Initialize the namespace prefix array if needed
        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }

        // Retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            $this->prefixes[$prefix][] = $baseDir;
        }
    }

    /**
     * Load the class file for a given class name
     *
     * @param string $class The fully-qualified class name
     * @return bool True if the file was loaded, false otherwise
     */
    public function loadClass(string $class): bool
    {
        // The current namespace prefix
        $prefix = $class;

        // Work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {
            // Retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // The rest is the relative class name
            $relativeClass = substr($class, $pos + 1);

            // Try to load a mapped file for the prefix and relative class
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                return true;
            }

            // Remove the trailing namespace separator for the next iteration
            $prefix = rtrim($prefix, '\\');
        }

        // Never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class
     *
     * @param string $prefix The namespace prefix
     * @param string $relativeClass The relative class name
     * @return bool True if the file was loaded, false otherwise
     */
    private function loadMappedFile(string $prefix, string $relativeClass): bool
    {
        // Are there any base directories for this namespace prefix?
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        // Look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $baseDir) {
            // Replace namespace separators with directory separators
            // in the relative class name
            $relativePath = str_replace('\\', '/', $relativeClass);

            // Try multiple file extensions
            $extensions = ['.php', '.model.php', 'Controller.php'];

            foreach ($extensions as $ext) {
                // For .model.php, just append it
                // For Controller.php, check if class ends with Controller
                if ($ext === 'Controller.php' && !str_ends_with($relativeClass, 'Controller')) {
                    continue;
                }

                $file = $baseDir . $relativePath . $ext;

                // If the mapped file exists, require it
                if ($this->requireFile($file)) {
                    return true;
                }
            }
        }

        // Never found a mapped file
        return false;
    }

    /**
     * If a file exists, require it from the file system
     *
     * @param string $file The file to require
     * @return bool True if the file exists, false otherwise
     */
    private function requireFile(string $file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
