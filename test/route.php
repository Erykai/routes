<?php

use Erykai\Routes\Route;

require "vendor/autoload.php";

const KEY_JWT = '1AAAJ@90jjkhgO```˜˜˜IHJN';

$route = new Route();
$route->namespace('Erykai\Routes');

$route->get('/', 'Controller@home');
$route->get('/post', 'Controller@post');
$route->get('/post/{id}', 'Controller@post');
$route->get('/post/{id}/{slug}', 'Controller@post');

$route->post('/login', 'Controller@login');
$route->post('/create/post', 'Controller@post', true);


$route->put('/edit/post', 'Controller@postPut', true);

$route->delete('/delete/post', 'Controller@postDelete', true);

$route->exec();

if ($route->error()) {
    var_dump($route->error());
}