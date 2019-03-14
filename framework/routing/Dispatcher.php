<?php
namespace Framework\Routing;

/**
 * Classe: Dispatcher
 * =============================================================================
 * Objectivo: Dispatch actions
 * 
 * 
 * 
 * =============================================================================
 * @author Alexandre Bezerra Barbosa <alxbbarbosa@hotmail.com>
 * 
 * @copyright 2015-2019 AB Babosa Serviços e Desenvolvimento ME
 * =============================================================================
 */
class Dispatcher
{

    protected $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->dispatch($route->callback);
    }

    public function dispatch($callback)
    {
        $return = null;

        if (is_callable($callback) && is_object($callback)) {
            $reflection = new \ReflectionFunction($callback);
            $arguments = $reflection->getParameters();
        } else if (!is_object($callback) && is_string($callback)) {
            $call = explode('@', $callback);

            if (count($call) == 2) {
                $obj = new $call[0];
                $method = $call[1];
                $callback = [$obj, $method];

                $reflection = new \ReflectionMethod($call[0], $call[1]);
                $arguments = $reflection->getParameters();
            } else {
                throw new \Exception("Attempting to call an invalid method");
            }
        }

        if (is_object($callback) || is_array($callback)) {

            if (count($arguments) > 0 && count($this->route->params) > 0) {

                if (count($arguments) == count($this->route->params)) {
                    $values = [];
                    foreach ($arguments as $argument) {
                        $values[$argument->name] = $this->route->values[$argument->name];
                    }
                    $return = call_user_func_array($callback, $values);
                }
            } else {
                $return = call_user_func($callback);
            }

            if (!is_null($return) && is_scalar($return) && !$this->isJSON($return)) {
                echo json_encode([$return], JSON_FORCE_OBJECT);
            } else if ($this->isJSON($return)) {
                echo $return;
            } else {
                return $return;
            }
        }
    }

    protected function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
