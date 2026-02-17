<?php

declare(strict_types=1);

//set session storage path
session_save_path((dirname(__FILE__) . '/storage'));
session_start();
// Load autoloader
require_once __DIR__ . '/lib/LeapAutoloader.php';

$loader = new \FaltLeap\LeapAutoloader();
$loader->register();
$loader->addNamespace('FaltLeap', __DIR__ . '/lib');
$loader->addNamespace('FaltLeap\\Helpers', __DIR__ . '/lib/helpers');
$loader->addNamespace('App\\Controllers', __DIR__ . '/app');
$loader->addNamespace('App\\Models', __DIR__ . '/models');
$loader->addNamespace('App\\Middleware', __DIR__ . '/middleware');

use FaltLeap\LeapEnv;
use FaltLeap\LeapEngine;
use FaltLeap\LeapErrorHandler;

// Register error handler early (defaults to production mode)
$errorHandler = LeapErrorHandler::getInstance();
$errorHandler->register();

// Load environment variables from .env file
$envPath = dirname(__FILE__) . '/.env';
if (!file_exists($envPath)) {
    echo "Please run install.php first on the cli (php install.php)";
    die();
}
LeapEnv::load($envPath);

// Now that env is loaded, set debug mode from APP_DEBUG
$errorHandler->setDebug(LeapEnv::get('APP_DEBUG', 'false') === 'true');

// Create dbconfig array from environment variables for backward compatibility
$dbconfig = [
  'dbhost' => LeapEnv::get('DB_HOST'),
  'dbusername' => LeapEnv::get('DB_USERNAME'),
  'dbpassword' => LeapEnv::get('DB_PASSWORD'),
  'dbdatabase' => LeapEnv::get('DB_DATABASE'),
  'dbschema' => LeapEnv::get('DB_SCHEMA')
];

// Load routes
include "conf/router.config.php";

$engine = new LeapEngine();
$engine->start($routes);
