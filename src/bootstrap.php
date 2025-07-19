<?php

/**
 * Bootstrap file for Headless CMS
 * Handles autoloading and basic configuration
 */

// Autoloader function
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $prefix = 'HeadlessCMS\\';
    $baseDir = __DIR__ . '/';
    
    // Check if the class uses our namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Not our namespace
    }
    
    // Remove the namespace prefix
    $relativeClass = substr($class, $len);
    
    // Convert namespace separators to directory separators
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Basic error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('UTC');