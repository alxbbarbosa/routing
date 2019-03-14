<?php
require_once '../bootstrap.php';


$route = new \Framework\Routing\Router;

require '../routes/web.php';

$request = $route->request();

if ($request) {
    new Framework\Routing\Dispatcher($request);
}
