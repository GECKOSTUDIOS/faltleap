<?php
declare(strict_types=1);

//set session storage path
session_save_path((dirname(__FILE__) . '/storage'));
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Load Composer autoloader (handles all namespaces)
require_once __DIR__ . '/vendor/autoload.php';

use FlatLeap\LeapEnv;
use FlatLeap\LeapEngine;

// Load environment variables from .env file
$envPath = dirname(__FILE__) . '/.env';
if (!file_exists($envPath)) {
  echo "Please run install.php first on the cli (php install.php)";
  die();
}
LeapEnv::load($envPath);

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
