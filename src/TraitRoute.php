<?php

namespace Erykai\Routes;

/**
 * Trait Controller Router
 */
trait TraitRoute
{
    /**
     * @param string $request
     * @param string $verb
     * @param bool $middleware
     * @param string $response
     * @return bool
     */
    public function controller(string $request, string $verb, bool $middleware, string $response): bool
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
                    $classMethod = explode("@", $request);
                    [$class, $method] = $classMethod;
                    $class = $this->getNamespace() . "\\" . $class;
                    if (class_exists($class)) {
                        $Class = new $class;
                        if (method_exists($Class, $method)) {

                            if (!empty($data[1])) {
                                $data = end($data);
                            }
                            $data['query'] = $this->getQuery();
                            if($middleware){
                                $Middleware = new Middleware();
                                if(!$Middleware->validate())
                                {
                                    $this->setResponse(
                                        401,
                                        "error",
                                        "this access is mandatory to inform the correct Baren Token"
                                    );
                                    return false;
                                }
                            }
                            $Class->$method($data, $response);
                            return true;
                        }
                        $this->setResponse(
                            405,
                            "error",
                            "the $request method does not exist",
                            dynamic: $request
                        );
                        return false;
                    }
                    $this->setResponse(
                        501,
                        "error",
                        "the $class class does not exist",
                        dynamic: $class
                    );
                    return false;
                }
                break;
            }
        }
        return true;
    }
}