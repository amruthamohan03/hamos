<?php
/**
 * Database Configuration File
 * 
 * This file contains database connection settings
 * Separate from main config for better organization
 */

// Database Configuration
return [
    // Default database connection
    'default' => 'mysql',
    
    // Database connections
    'connections' => [
        
        // MySQL Connection
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'port'      => 3307,
            'database'  => 'malabar_db',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'options'   => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
            ]
        ],
        
        // PostgreSQL Connection (optional)
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => 'localhost',
            'port'     => 5432,
            'database' => 'my_mvc_db',
            'username' => 'postgres',
            'password' => '',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],
        
        // SQLite Connection (optional)
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => APP_ROOT . '/database/database.sqlite',
            'prefix'   => '',
        ],
        
        // Testing Database (for unit tests)
        'testing' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'port'      => 3306,
            'database'  => 'my_mvc_db_test',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ],
    ],
];