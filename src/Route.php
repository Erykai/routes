<?php

namespace Erykai\Routes;

/**
 * Class Route define route
 */
class Route extends Resource
{

    /**
     * @param string $namespace
     */
    public function namespace(string $namespace): void
    {
        $this->setNamespace($namespace);
    }

    /**
     * @param string $callback
     * @param string $controller
     * @param bool $middleware
     * @param string $type
     */
    public function get(string $callback, string $controller, bool $middleware = false, string $type = "object"): void
    {
        $this->callback($callback,$controller,"GET",$middleware, $type);
    }

    /**
     * @param string $callback
     * @param string $controller
     * @param bool $middleware
     * @param string $type
     */
    public function post(string $callback, string $controller, bool $middleware = false, string $type = "object"): void
    {
        $this->callback($callback,$controller,"POST",$middleware,$type);
    }

    /**
     * @param string $callback
     * @param string $controller
     * @param bool $middleware
     * @param string $type
     */
    public function put(string $callback, string $controller, bool $middleware = false, string $type = "object"): void
    {
        $this->callback($callback,$controller,"PUT",$middleware,$type);
    }

    /**
     * @param string $callback
     * @param string $controller
     * @param bool $middleware
     * @param string $type
     */
    public function delete(string $callback, string $controller, bool $middleware = false, string $type = "object"): void
    {
        $this->callback($callback,$controller,"DELETE",$middleware,$type);
    }

    /**
     * @param string $callback
     * @param string $controller
     * @param array|false[] $middleware
     * @param string $type
     */
    public function default(string $callback, string $controller, array $middleware = [false,false,false,false], $type = "object"): void
    {
        $this->get($callback,"$controller@read",$middleware[0], $type);
        $this->get("$callback/{id}","$controller@read",$middleware[0],$type);
        $this->post($callback,"$controller@store",$middleware[1],$type);
        $this->put("$callback/{id}","$controller@edit",$middleware[2],$type);
        $this->delete("$callback/{id}","$controller@destroy",$middleware[3],$type);
    }


    /**
     * @return object
     */
    public function response(): object
    {
        return $this->getResponse();
    }

    /**
     *  response
     */
    public function exec(): void
    {
        if(!$this->controller()){
            header("Refresh:0");
        }
        if ($this->isNotFound()) {
            $this->setResponse(
                404,
                "error",
                "this path does not exist or has been removed"
            );
        }
    }

}