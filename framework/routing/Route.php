<?php
namespace Framework\Routing;

/**
 * Classe: Route
 * =============================================================================
 * Goal: Route container
 * 
 * 
 * 
 * =============================================================================
 * @author Alexandre Bezerra Barbosa <alxbbarbosa@hotmail.com>
 * 
 * @copyright 2015-2019 AB Babosa Serviços e Desenvolvimento ME
 * =============================================================================
 */
class Route
{

    public $callback;
    public $uri;
    public $method;
    public $params;
    public $values;
    public $size;
    protected $name;

    public function __construct(string $method, string $uri, $callback)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->callback = $callback;
        $this->params = [];

        $this->parseUri($uri);
    }

    public function name(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function parseUri($uri)
    {
        $uri = explode('/', $uri);

        $this->size = count($uri);

        if (count($uri) > 1) {

            foreach ($uri as $item) {
                if (preg_match("/\{[A-Za-z0-9]{1,}\}/", $item)) {
                    $this->params[] = preg_replace("/[^A-Za-z0-9]{1,}/", "", $item);
                }
            }
        }
    }
}
