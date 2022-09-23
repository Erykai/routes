# Erykai routes
[![Maintainer](http://img.shields.io/badge/maintainer-@alexdeovidal-blue.svg?style=flat-square)](https://instagram.com/alexdeovidal)
[![Source Code](http://img.shields.io/badge/source-erykai/routes-blue.svg?style=flat-square)](https://github.com/erykai/routes)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/erykai/routes.svg?style=flat-square)](https://packagist.org/packages/erykai/routes)
[![Latest Version](https://img.shields.io/github/release/erykai/routes.svg?style=flat-square)](https://github.com/erykai/routes/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/erykai/routes.svg?style=flat-square)](https://scrutinizer-ci.com/g/erykai/routes)
[![Total Downloads](https://img.shields.io/packagist/dt/erykai/routes.svg?style=flat-square)](https://packagist.org/packages/erykai/routes)

Package responsible for routing the app, including middleware, complete for creating routes for api rest.

## Installation

Composer:

```bash
"erykai/routes": "1.1.*"
```

Terminal

```bash
composer require erykai/routes
```

Create .htaccess Apache

```apacheconf
RewriteEngine on
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteRule ^(.*)$ index.php?route=$1
```

OR nginx

```apacheconf
location / {
  if ($script_filename !~ "-f"){
    rewrite ^(.*)$ /index.php?route=$1 break;
  }
}
```

Create index.php

```php
use Erykai\Routes\Route;

require "vendor/autoload.php";

$route = new Route();
$route->namespace('Erykai\Routes');

$route->get('/', 'Controller@home');
$route->get('/post', 'Controller@post');
$route->get('/post/{id}', 'Controller@post');
$route->get('/post/{id}/{slug}', 'Controller@post');
$route->post('/login', 'Controller@login');
$route->post('/create/post', 'Controller@post');
$route->put('/edit/post', 'Controller@postPut');
$route->delete('/delete/post', 'Controller@postDelete');
//create all get, post, put and delete
$route->default('/user', 'Controller');
$route->exec();

var_dump($route->response());
```

Router and Middleware key JWT index.php

```php
use Erykai\Routes\Route;

require "vendor/autoload.php";

const KEY_JWT = '1AAAJ@90jjkhgO```˜˜˜IHJN';

$route = new Route();
$route->namespace('Erykai\Routes');

$route->get('/', 'Controller@home');
$route->get('/post', 'Controller@post');
$route->get('/post/{id}', 'Controller@post', true);
$route->get('/post/{id}/{slug}', 'Controller@post', true);
$route->post('/login', 'Controller@login');
$route->post('/create/post', 'Controller@post', true);
$route->put('/edit/post', 'Controller@postPut', true);
$route->delete('/delete/post', 'Controller@postDelete', true);
//create all get, post, put and delete
$route->default('/user', 'Controller', [true,false,false,false]);
$route->exec();

var_dump($route->response());
```

Router and Middleware key JWT and type Response index.php

```php
use Erykai\Routes\Route;

require "vendor/autoload.php";

const KEY_JWT = '1AAAJ@90jjkhgO```˜˜˜IHJN';

$route = new Route();
$route->namespace('Erykai\Routes');

$route->get('/', 'Controller@home', response: "json");
$route->get('/post', 'Controller@post');
$route->get('/post/{id}', 'Controller@post', true, "object");
$route->get('/post/{id}/{slug}', 'Controller@post', true, "array");
$route->post('/login', 'Controller@login', response: "json");
$route->post('/create/post', 'Controller@post', true, "json");
$route->put('/edit/post', 'Controller@postPut', true, "json");
$route->delete('/delete/post', 'Controller@postDelete', true, "json");
//create all get, post, put and delete
$route->default('/user', 'Controller', [true,false,false,false], "json");
$route->exec();

var_dump($route->response());
```

Create Controller Class Controller.php

```php
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
    public function home(?array $data, string $response): bool
    {
        echo "home";
        var_dump($response);
        return true;
    }

    /**
     * @return bool
     */
    public function login(?array $data, string $response): bool
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
    public function post(?array $data, string $response): bool
    {
        var_dump($data, $_POST, file_get_contents('php://input'));
        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function postPut(?array $data, string $response): bool
    {
        var_dump($data, file_get_contents('php://input'));
        return true;

    }

    /**
     * @param array $data
     * @return bool
     */
    public function postDelete(?array $data, string $response): bool
    {
        var_dump($data, file_get_contents('php://input'));
        return true;

    }
}
```

## Contribution

All contributions will be analyzed, if you make more than one change, make the commit one by one.

## Support


If you find faults send an email reporting to webav.com.br@gmail.com.

## Credits

- [Alex de O. Vidal](https://github.com/alexdeovidal) (Developer)
- [All contributions](https://github.com/erykai/routes/contributors) (Contributors)

## License

The MIT License (MIT). Please see [License](https://github.com/erykai/routes/LICENSE) for more information.
