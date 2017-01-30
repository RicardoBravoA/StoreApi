<?php
/**
 * Created by PhpStorm.
 * User: Carlos Leonardo Camilo Vargas Huamán
 * Date: 27/04/16
 * Time: 05:02 PM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

spl_autoload_register(function ($classname) {
    require("../src/classes/" . $classname . ".php");
});


$app->post('/register/', function (Request $request, Response $response) {
    $mapper = new StoreMapper($this->db);

    $name = $request->getParsedBody()['name'];
    $surname = $request->getParsedBody()['surname'];
    $email = $request->getParsedBody()['email'];
    $password = $request->getParsedBody()['password'];

    $data = $mapper->register($name, $surname, $email, $password);

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});

$app->post('/login/', function (Request $request, Response $response) {
    $mapper = new StoreMapper($this->db);

    $email = $request->getParsedBody()['email'];
    $password = $request->getParsedBody()['password'];

    $data = $mapper->login($email, $password);

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});


/*
$app->get('/year/', function (Request $request, Response $response) {
    $mapper = new StoreMapper($this->db);
    $data = $mapper->getAllYear();

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});


/*
// All Stores
$app->get('/store/', function (Request $request, Response $response) {

    $mapper = new StoreMapper($this->db);
    $data = $mapper->getAllStore();

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});

// Store sales by year
$app->get('/store/year/{year}', function (Request $request, Response $response, $args) {

    $mapper = new StoreMapper($this->db);
    $data = $mapper->getSalesStoreByYear($args['year']);

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});

//Store sales by store group by year
$app->get('/store/{store_id}/year', function (Request $request, Response $response, $args) {
    $mapper = new StoreMapper($this->db);
    $data = $mapper->getSalesStore($args['store_id']);

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});

//Branch sales by store group by year
$app->get('/store/{store_id}/branch/', function (Request $request, Response $response, $args) {
    $mapper = new StoreMapper($this->db);
    $data = $mapper->getBranchSalesStore($args['store_id']);

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');
    return $newResponse;
});

// Sales by year and store
$app->get('/store/year/', function (Request $request, Response $response){

    $mapper = new StoreMapper($this->db);
    $data = $mapper->getSalesByStoreAndYear();

    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $newResponse;
});

// sales of year by store
$app->get('/store/{storeId}', function (Request $request, Response $response, $args){
    $mapper = new StoreMapper($this->db);
    $data = $mapper->getYearSaleByStore($args['storeId']);
    $response->getBody()->write(json_encode($data["response"]));
    $newResponse = $response->withStatus($data["errorCode"])
        ->withHeader('Content-Type', 'application/json; charset=utf-8');
    return $newResponse;
});
*/





