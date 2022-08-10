<?php

namespace Erykai\Routes;

/**
 * Class Route define route
 */
class Route extends Resource
{
    use TraitRoute;


    /**
     * @param string $namespace
     */
    public function namespace(string $namespace): void
    {
        $this->setNamespace($namespace);
    }

    /**
     * @param string $callback
     * @param string $request
     * @param bool $middleware
     * @param string $response
     */
    public function get(string $callback, string $request, bool $middleware = false, string $response = "object"): void
    {
        $this->callback($callback,$request,"GET",$middleware, $response);
    }

    /**
     * @param string $callback
     * @param string $request
     * @param bool $middleware
     * @param string $response
     */
    public function post(string $callback, string $request, bool $middleware = false, string $response = "object"): void
    {
        $this->callback($callback,$request,"POST",$middleware,$response);
    }

    /**
     * @param string $callback
     * @param string $request
     * @param bool $middleware
     * @param string $response
     */
    public function put(string $callback, string $request, bool $middleware = false, string $response = "object"): void
    {
        $this->callback($callback,$request,"PUT",$middleware,$response);
    }

    /**
     * @param string $callback
     * @param string $request
     * @param bool $middleware
     * @param string $response
     */
    public function delete(string $callback, string $request, bool $middleware = false, string $response = "object"): void
    {
        $this->callback($callback,$request,"DELETE",$middleware,$response);
    }

    public function default(string $callback, string $request, array $middleware = [false,false,false,false], $response = "object"): void
    {
        $this->get($callback,"$request@read",$middleware[0], $response);
        $this->get("$callback/{id}","$request@read",$middleware[0],$response);
        $this->post($callback,"$request@store",$middleware[1],$response);
        $this->put("$callback/{id}","$request@edit",$middleware[2],$response);
        $this->delete("$callback/{id}","$request@destroy",$middleware[3],$response);
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
        if ($this->isNotFound()) {
            $this->setResponse(
                404,
                "error",
                "this path does not exist or has been removed"
            );
        }
    }

}