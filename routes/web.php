<?php
/**
 * Set your routes here
 */

$route->get('/', function() {
    echo "Página inicial";
});

$route->get('teste/{id}/teste/{id2}', function($id, $id2) {
    echo "Teste variáveis {$id} e {$id2}!";
});


$route->get('teste/{id}', function($id) {
    echo "Primerio teste {$id}!";
});

$route->get('teste', function() {
    echo "Tudo ok";
});

$route->get('teste-controller', 'App\Http\Controller\TestController@index');
