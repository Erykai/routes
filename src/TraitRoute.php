<?php

namespace Erykai\Routes;

/**
 * Trait Controller Router
 */
trait TraitRoute
{
    /**
     * @param string $response
     * @param string $verb
     */
    public function controller(string $response, string $verb): void
    {
        foreach ($this->getPatterns() as $key => $pattern) {
            preg_match('#' . $pattern . '#', $this->getRequest(), $router);
            preg_match_all('~{([^}]*)}~', $this->route[$key], $keys);
            array_shift($router);

            if (!empty($router)) {
                $data[] = array_combine($keys[1], $router);
                $this->route[$key] = str_replace($keys[0], $router, $this->route[$key]);
            }
                if (array_reverse($this->route)[0] === $this->getRequest() || $this->getRequest() === '') {
                    $this->setNotFound(false);
                    if ($this->getMethod() === $verb) {
                        $classMethod = explode("@", $response);
                        [$class, $method] = $classMethod;
                        $class = $this->getNamespace() . "\\" . $class;
                        if (class_exists($class)) {
                            $Class = new $class;
                            if (method_exists($Class, $method)) {

                                if (!empty($data[1])) {
                                    $data = end($data);
                                }
                                $data['query'] = $this->getQuery();
                                $Class->$method($data);
                            } else {
                                $this->setError(405);
                            }
                        } else {
                            $this->setError(501);
                        }
                    }
                    break;
                }
        }
    }
}