<?php

namespace Erykai\Routes;

class Middleware
{
    protected string $key;
    protected string $header;

    public function __construct()
    {
        $this->key = KEY_JWT;
        $this->header = base64_encode(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]));
    }

    public function create(string $email): string
    {
        $key = KEY_JWT;

        $payload = base64_encode(json_encode([
            'email' => $email,
        ]));

        $sign = hash_hmac('sha256', $this->header . "." . $payload, $this->key, true);
        $sign = base64_encode($sign);
        return $this->header . '.' . $payload . '.' . $sign;
    }

    public function validate(): bool
    {
        if(empty(getallheaders()['Authorization'])){
            return false;
        }
        $barer = str_replace('Bearer ', '', getallheaders()['Authorization']);
        $barers = explode('.', $barer);
        $payload = $barers[1];
        $sign = hash_hmac('sha256', $this->header . "." . $payload, $this->key, true);
        $sign = base64_encode($sign);
        $keyBarer = $this->header . '.' . $payload . '.' . $sign;
        return $keyBarer === $barer;
    }
}