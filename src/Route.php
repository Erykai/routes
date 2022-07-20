<?php

namespace Erykai\Routes;

class Route
{
    protected string $error;
    protected string $method;
    protected string $request;
    protected string $namespace;
    protected bool $notFound = true;

    public function __construct()
    {
        $this->method = filter_var($_SERVER['REQUEST_METHOD']);
        $this->request = filter_var($_SERVER['REQUEST_URI']);
    }

    public function namespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function get(string $callback, string $response): void
    {
        if($callback === $this->request){
            $this->notFound = false;
            if ($this->method === "GET") {
                $classMethod = explode("@", $response);
                [$class, $method] = $classMethod;
                $class = $this->namespace ."\\" . $class;
                if (class_exists($class)) {
                    $Class = new $class;
                    if(method_exists($Class, $method)){
                        $Class->$method();
                    }else{
                        $this->error = "o metodo " . $method . " não existe";
                    }
                }else{
                    $this->error = "a classe " . $class . " não existe";
                }
            }
        }
    }

    public function post(string $callback, string $response): void
    {
        if ($this->method === "POST") {
            echo "POST";
        }
    }

    public function put(string $callback, string $response): void
    {
        if ($this->method === "PUT") {
            echo "PUT";
        }
    }

    public function delete(string $callback, string $response): void
    {
        if ($this->method === "DELETE") {
            echo "DELETE";
        }
    }

    public function error(): bool|string
    {
        if(!empty($this->error)) {
            return $this->error;
        }
        return false;
    }

    public function exec(): void
    {
        if($this->notFound){
            $this->error = 'PAGINA NÃO EXISTE OU FOI REMOVIDA!';
        }
    }

}