<?php
namespace Framework\Routing;

class Router
{

    protected $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function get($uri, $callback)
    {
        $this->add('GET', $uri, $callback);
    }

    public function post($uri, $callback)
    {
        $this->add('POST', $uri, $callback);
    }

    public function request()
    {

        $route = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_STRING);

        if (is_null(filter_input(INPUT_GET, 'route', FILTER_SANITIZE_STRING))) {
            $route = '/';
        }

        if (isset($this->routes['GET']) && isset($route)) {

            $routes = $this->routes['GET'];
            $pieces = array_filter(explode('/', $route));

            if (in_array($route, array_keys($this->routes['GET']))) {
                return $this->routes['GET'][$route];
            } else {
                $size = count($pieces);
                if ($size > 1) {
                    foreach ($routes as $key => $value) {

                        if ($size == $value->size) {

                            $piecesRoute = explode('/', $value->uri);

                            $vars = $this->getVars($value->uri);
                            $tmpRoute = [];
                            $values = [];

                            foreach ($piecesRoute as $k => $v) {
                                if (!in_array($k, array_keys($vars)) && $v == $pieces[$k]) {
                                    $tmpRoute[$k] = $v;
                                } else {
                                    $tmpRoute[$k] = $pieces[$k];
                                    $values[$vars[$k]] = $pieces[$k];
                                }
                            }
                            if (count($tmpRoute) == $size) {
                                $dump = implode('/', $tmpRoute);
                                if ($dump == $route) {

                                    $toReturn = $this->routes['GET'][$value->uri];
                                    $toReturn->uri = $dump;
                                    $toReturn->values = $values;

                                    return $toReturn;
                                }
                            }
                        }
                    }
                }
            }
            echo "Error: 403";
        } else if (!isset($this->routes['GET']) && isset($route)) {
            throw new \Exception("No route was defined");
        }
    }

    protected function add($method, $uri, $callback)
    {
        $this->routes[$method][$uri] = new Route($method, $uri, $callback);
    }

    protected function getVars($uri)
    {
        $uri = explode('/', $uri);
        $vars = [];
        if (count($uri) > 1) {

            foreach ($uri as $key => $item) {
                if (preg_match("/\{[A-Za-z0-9]{1,}\}/", $item)) {
                    $vars[$key] = preg_replace("/[^A-Za-z0-9]{1,}/", "", $item);
                }
            }
        }
        return $vars;
    }
}
