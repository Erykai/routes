<?php

use Erykai\Routes\Route;

require "vendor/autoload.php";

const KEY_JWT = '1AAAJ@90jjkhgO```˜˜˜IHJN';

$route = new Route();
$route->namespace('Erykai\Routes');

$route->get('/', 'Controller@home');
$route->get('/post', 'Controller@post', response: "array");
$route->get('/post/{id}', 'Controller@post', response: "object");
$route->get('/post/{id}/{slug}', 'Controller@post', response: "json");
//create all get, post, put and delete
$route->default('/usuario/cadastro', 'UserController', [false,false,true,true], "json");

$route->post('/login', 'Controller@login');
$route->post('/create/post', 'Controller@post', true);


$route->put('/edit/post', 'Controller@postPut', true);

$route->delete('/delete/post', 'Controller@postDelete', true);

$route->exec();

var_dump($route->response());