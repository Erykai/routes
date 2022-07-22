# Erykai routes
Package responsible for routing the app, including middleware, complete for creating routes for api rest.

## Installation

Composer:

```bash
"erykai/routes": "1.0.*"
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
$route->exec();

if ($route->error()) {
    var_dump($route->error());
}
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
$route->get('/post/{id}', 'Controller@post');
$route->get('/post/{id}/{slug}', 'Controller@post');
$route->post('/login', 'Controller@login');
$route->post('/create/post', 'Controller@post', true);
$route->put('/edit/post', 'Controller@postPut', true);
$route->delete('/delete/post', 'Controller@postDelete', true);
$route->exec();

if ($route->error()) {
    var_dump($route->error());
}
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
