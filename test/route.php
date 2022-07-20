<?php

use Erykai\Routes\Route;

require "vendor/autoload.php";

$route = new Route();

$route->namespace('Erykai\Routes');

$route->get('/cadastro/name', 'Controller@name');
$route->post('/cadastro/post', 'Controller@post');
$route->put('/cadastro/post', 'Controller@post');
$route->delete('/cadastro/post', 'Controller@post');

$route->exec();

if ($route->error()) {
    echo $route->error();
}