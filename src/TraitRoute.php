<?php

namespace Erykai\Routes;

/**
 * Trait Controller Router
 */
trait TraitRoute
{
    /**
     * @return bool
     */
    private function duplicates(array $array)
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
     * @param $class
     * @param $method
     * @param $data
     * @param $key
     * @return bool|void
     */
    private
    function classMethod($class, $method, $data, $key)
    {
        $argument = $data;
        unset($data);
        $data['argument'] = $argument;
        $class = $this->namespaceArray[$key] . "\\" . $class;
        if (class_exists($class)) {
            $Class = new $class;
            if (method_exists($Class, $method)) {
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
                $this->setNotFound(false);
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
    public
    function controller(): bool
    {
        $this->duplicates($this->getPatterns());
        $patterns = array_unique($this->getPatterns());
        if ($this->getRequest() == "") {
            $patterns = (array)$patterns[0];
        }

        foreach ($patterns as $key => $pattern) {
            if ($this->getMethod() === $this->verb[$key]) {
                preg_match('#' . $pattern . '#', $this->getRequest(), $router);
                if (isset($router[0])) {
                    if ($router[0] === $this->getRequest()) {
                        $classMethod = explode("@", $this->controller[$key]);
                        [$class, $method] = $classMethod;
                        array_shift($router);
                        preg_match_all('~{([^}]*)}~', $this->route[$key], $keys);
                        $data = array_combine($keys[1], $router);
                        $this->classMethod($class, $method, $data, $key);
                    }
                } else {
                    if ($this->getRequest() == "") {
                        $classMethod = explode("@", $this->controller[$key]);
                        [$class, $method] = $classMethod;
                        array_shift($router);
                        preg_match_all('~{([^}]*)}~', $this->route[$key], $keys);
                        $data = array_combine($keys[1], $router);
                        $this->classMethod($class, $method, $data, $key);
                    }
                }
            }

        }
        return true;
    }
}