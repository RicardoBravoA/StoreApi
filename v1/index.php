<?php
/**
 * Created by PhpStorm.
 * User: Carlos Leonardo Camilo Vargas Huamán
 * Date: 25/04/16
 * Time: 12:43 AM
 */

require '../libs/vendor/autoload.php';

session_start();

// Instantiate the app
$config = require __DIR__ . '/../src/settings.php';

$app = new \Slim\App(["settings"=>$config]);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register routes
require __DIR__ . '/../src/routes.php';

$app->run();