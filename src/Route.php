<?php

namespace Erykai\Routes;

/**
 * Class Route define route
 */
class Route extends Resource
{
    use TraitRoute;

    /**
     * construct
     */
    public function __construct()
    {
        $this->setMethod();
    }

    /**
     * @param string $namespace
     */
    public function namespace(string $namespace): void
    {
        $this->setNamespace($namespace);
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function get(string $callback, string $response, bool $middleware = false): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "GET", $middleware);
        }
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function post(string $callback, string $response, bool $middleware = false): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "POST", $middleware);
        }
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function put(string $callback, string $response, bool $middleware = false): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "PUT", $middleware);
        }
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function delete(string $callback, string $response, bool $middleware = false): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "DELETE", $middleware);
        }
    }


    /**
     * @return bool|int
     */
    public function error(): bool|int
    {
        return $this->getError();
    }

    /**
     *  setError
     */
    public function exec(): void
    {
        if ($this->isNotFound()) {
            $this->setError(404);
        }
    }

}