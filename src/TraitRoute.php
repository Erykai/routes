<?php

namespace Erykai\Routes;

use RuntimeException;

/**
 * Trait Controller Router
 */
trait TraitRoute
{
    /**
     * @var object
     */
    private object $response;
    /**
     * @param array $array
     * @return object
     */
    private function duplicates(array $array): object
    {
        if ($this->getRequest() !== "") {
            foreach ($array as $key => $item) {
                if ($this->verb[$key] !== $this->getMethod()) {
                    unset(
                        $this->controller[$key],
                        $this->middleware[$key],
                        $this->type[$key],
                        $this->verb[$key],
                        $this->route[$key],
                        $this->patterns[$key]
                    );
                }
            }
        }
        return $this;
    }
    /**
     * @return bool
     */
    private function patterns(): bool
    {
        $patterns = array_unique($this->getPatterns());
        if ($this->getRequest() === "") {
            $patterns = (array)$patterns[0];
        }
        foreach ($patterns as $key => $pattern) {
            if ($this->getMethod() === $this->verb[$key]) {
                if (preg_match('#' . $pattern . '#', $this->getRequest(), $router)) {
                    throw new RuntimeException('Error '.$pattern.' ' . $this->getRequest());
                }
                if (isset($router[0])) {
                    if ($router[0] === $this->getRequest()) {
                        $this->setClass($key, $router);
                    }
                } else if ($this->getRequest() === "") {
                    $this->setClass($key, $router);
                }
            }

        }
        return true;
    }
    /**
     * @param $key
     * @param $router
     */
    private function setClass($key, $router): void
    {
        $classMethod = explode("@", $this->controller[$key]);
        [$class, $method] = $classMethod;
        array_shift($router);
        if (preg_match_all('~{([^}]*)}~', $this->route[$key], $keys) === false) {
            throw new RuntimeException('The route ' . $this->route[$key] . ' not exist.');
        }
        $data = array_combine($keys[1], $router);
        $this->classMethod($class, $method, $data, $key);
    }
    /**
     * @param $class
     * @param $method
     * @param $data
     * @param $key
     * @return bool|void
     */
    private function classMethod($class, $method, $data, $key)
    {
        $argument = $data;
        unset($data);
        $data['argument'] = $argument;
        $class = $this->namespaceArray[$key] . "\\" . $class;
        if (class_exists($class)) {
            $Class = new $class;
            if (method_exists($Class, $method)) {
                $this->setNotFound(false);
                $data['query'] = $this->getQuery();
                if ($this->middleware[$key]) {
                    $Middleware = new Middleware();
                    if (!$Middleware->validate()) {
                        $this->setResponse(
                            401,
                            "error",
                            "this access is mandatory to inform the correct Baren Token"
                        );
                        return false;
                    }
                }
                $Class->$method($data, $this->type[$key]);
                return true;
            }
            $this->setResponse(
                405,
                "error",
                "the {$this->controller[$key]} method does not exist",
                dynamic: $this->controller[$key]
            );
            return false;
        }
    }
    /**
     * @return bool
     */
    protected function controller(): bool
    {
        $this->duplicates($this->getPatterns());
        $this->patterns();
        if (empty($this->getPatterns())) {
            return false;
        }
        return true;
    }
    /**
     * @return array
     */
    protected function getPatterns(): array
    {
        return $this->patterns;
    }
    /**
     * @return object
     */
    protected function getResponse(): object
    {
        return $this->response;
    }
    /**
     * @return string
     */
    protected function getMethod(): string
    {
        return $this->method;
    }
    /**
     * @return string
     */
    protected function getRequest(): string
    {
        return $this->request;
    }
    /**
     * @return array|null
     */
    protected function getQuery(): ?array
    {
        return $this->query;
    }
    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return $this->namespace;
    }
    /**
     * @return array
     */
    protected function getRoute(): array
    {
        return $this->route;
    }
    /**
     * @return bool
     */
    protected function isNotFound(): bool
    {
        return $this->notFound;
    }
    /**
     * @param int $code
     * @param string $type
     * @param string $message
     * @param object|null $data
     * @param string|null $dynamic
     */
    protected function setResponse(int $code, string $type, string $message, ?object $data = null, ?string $dynamic = null): void
    {
        $this->response = (object)[
            "code" => $code,
            "type" => $type,
            "message" => $message,
            "data" => $data,
            "dynamic" => $dynamic
        ];
    }

}