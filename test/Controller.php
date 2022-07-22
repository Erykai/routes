<?php

namespace Erykai\Routes;

use stdClass;

/**
 * Example object define routes
 */
class Controller
{
    /**
     * @return bool
     */
    public function home(): bool
    {
        echo "home";
        return true;
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        $json = file_get_contents('php://input');
        $login = json_decode($json);
        if (empty($login->email) || empty($login->password)) {
            echo 203 . " Email ou senha invalido";
            return false;
        }

        if(!filter_var($login->email, FILTER_VALIDATE_EMAIL)){
            echo 203 . " Email invalido";
            return false;
        }

        $user = new stdClass();
        $user->email = "webav.com.br@gmail.com";
        $user->password = "10203040";

        if($user->email !== $login->email){
            echo 203 . " Email ou senha invalido";
            return false;
        }

        if($user->password !== $login->password){
            echo 203 . " Email ou senha invalido";
            return false;
        }
        $middleware = new Middleware();
        echo $middleware->create($user->email);
        return true;
    }

    /**
     * @param array|null $data
     * @return bool
     */
    public function post(?array $data): bool
    {
        var_dump($data, $_POST, file_get_contents('php://input'));
        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function postPut(array $data): bool
    {
        var_dump($data, file_get_contents('php://input'));
        return true;

    }

    /**
     * @param array $data
     * @return bool
     */
    public function postDelete(array $data): bool
    {
        var_dump($data, file_get_contents('php://input'));
        return true;

    }
}