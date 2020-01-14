<?php
session_start();
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');
require __DIR__ . '/functions.php';
require appPath() . '/vendor/autoload.php';
require appPath() . '/app/db/connection.php';
require appPath() . '/app/routes/index.php';
Flight::start();
