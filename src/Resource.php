<?php

namespace Erykai\Routes;

/**
 *
 */
abstract class Resource
{
    use TraitRoute;
    /**
     * @var array
     */
    protected array $route;
    /**
     * @var string
     */
    protected string $method;
    /**
     * @var string
     */
    protected string $request;
    /**
     * @var array|null
     */
    protected ?array $query = null;
    /**
     * @var string
     */
    protected string $namespace;
    /**
     * @var array
     */
    protected array $namespaceArray;
    /**
     * @var array
     */
    protected array $patterns;
    /**
     * @var bool
     */
    protected bool $notFound = true;
    /**
     * @var array
     */
    protected array $callback;
    /**
     * @var array
     */
    protected array $controller;
    /**
     * @var array
     */
    protected array $middleware;
    /**
     * @var array
     */
    protected array $type;
    /**
     * @var array
     */
    protected array $verb;
    protected function callback($callback, $controller, $verb, $middleware, $type): void
    {
        if ($this->setRequest($callback)) {
            $this->setRoute($callback);
            $this->setPatterns();
            $this->controller[] = $controller;
            $this->middleware[] = $middleware;
            $this->type[] = $type;
            $this->verb[] = $verb;
            $this->namespaceArray[] = $this->getNamespace();

        }
    }
    /**
     * @param string $namespace
     */
    protected function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }
    /**
     * @param string $route
     */
    protected function setRoute(string $route): void
    {
        $this->route[] = $route;
    }
    /**
     * create pattern
     */
    protected function setPatterns(): void
    {
        $this->patterns = preg_replace('~{([^}]*)}~', "([^/]+)", $this->getRoute());
    }
    /**
     * define method global server
     */
    protected function setMethod(): void
    {
        $this->method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT);
    }
    /**
     * @param string $route
     * @return bool
     */
    protected function setRequest(string $route): bool
    {
        $uri = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT));
        $this->request = $uri['path'];

        $callbackCount = count(explode("/", $route));
        $requestCount = count(explode("/", $this->request));

        if (str_ends_with($this->request, '/')) {
            $this->request = substr($this->request, 0, -1);
        }
        if (isset($uri['query'])) {
            parse_str($uri['query'], $query);
            $this->setQuery($query);
        }

        return $callbackCount === $requestCount || $this->request === '/';

    }
    /**
     * @param array|null $query
     */
    protected function setQuery(?array $query): void
    {
        $this->query = $query;
    }
    /**
     * @param bool $notFound
     */
    protected function setNotFound(bool $notFound): void
    {
        $this->notFound = $notFound;
    }

}