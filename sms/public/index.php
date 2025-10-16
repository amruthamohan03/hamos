<?php
// Start session
session_start();

// Load configuration
require_once '../config/config.php';

// Autoload classes
spl_autoload_register(function ($className) {
    $paths = [
        '../app/controllers/',
        '../app/models/',
        '../app/core/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize core application
$app = new App();
