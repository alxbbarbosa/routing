<?php
require_once '../bootstrap.php';

try {
    $route = new \Framework\Routing\Router;

    require '../routes/web.php';

    $request = $route->request();


    if ($request) {
        new Framework\Routing\Dispatcher($request);
    }
} catch (\Exception $e) {
    dd($e->getTrace());
}
