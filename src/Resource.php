<?php

namespace Erykai\Routes;

/**
 *
 */
class Resource
{
    /**
     * @var bool|string
     */
    protected bool|string $error = false;
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
    protected array $patterns;
    /**
     * @var bool
     */
    protected bool $notFound = true;

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    protected function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    protected function getRoute(): array
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    protected function setRoute(string $route): void
    {
        $this->route[] = $route;
    }

    /**
     * @return array
     */
    protected function getPatterns(): array
    {
        return $this->patterns;
    }

    /**
     * create pattern
     */
    protected function setPatterns(): void
    {
        $this->patterns = preg_replace('~{([^}]*)}~', "([^/]+)", $this->getRoute());
    }

    /**
     * @return string
     */
    protected function getMethod(): string
    {
        return $this->method;
    }

    /**
     * define method global server
     */
    protected function setMethod(): void
    {
        $this->method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT);
    }

    /**
     * @return string
     */
    protected function getRequest(): string
    {
        return $this->request;
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
     * @return array|null
     */
    protected function getQuery(): ?array
    {
        return $this->query;
    }

    /**
     * @param array|null $query
     */
    protected function setQuery(?array $query): void
    {
        $this->query = $query;
    }


    /**
     * @return bool|int
     */
    protected function getError(): bool|int
    {
        return $this->error;
    }

    /**
     * @param int $error
     */
    protected function setError(int $error): void
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isNotFound(): bool
    {
        return $this->notFound;
    }

    /**
     * @param bool $notFound
     */
    public function setNotFound(bool $notFound): void
    {
        $this->notFound = $notFound;
    }
}