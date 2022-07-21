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
    public function get(string $callback, string $response): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "GET");
        }
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function post(string $callback, string $response): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "POST");
        }
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function put(string $callback, string $response): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "PUT");
        }
    }

    /**
     * @param string $callback
     * @param string $response
     */
    public function delete(string $callback, string $response): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller($response, "DELETE");
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