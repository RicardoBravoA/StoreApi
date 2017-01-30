<?php
/**
 * Created by PhpStorm.
 * User: Carlos Leonardo Camilo Vargas Huamán
 * Date: 27/04/16
 * Time: 04:59 PM
 */

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    return $conn;
};