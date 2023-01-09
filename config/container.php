<?php

use Slim\Container;
use Slim\Csrf\Guard;
use SlimSession\Helper;
use Jenssegers\Blade\Blade;
use App\Kernel\Handler\Whoops;

return [

    /**
     * Define your container dependencies here.
     */

     //
     
    'errorHandler' => function (): Whoops {
        return new Whoops(config('app_debug'));
    },

    'phpErrorHandler' => function (): Whoops {
        return new Whoops(config('app_debug'));
    },

    'notFoundHandler' => function (): \Closure {
        return function ($request, Slim\Http\Response $response) {
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->write('<h1>Page not found.</h1>');
        };
    },

    /**
     * @link https://github.com/jenssegers/blade
     */
    'view' => function (Container $container): Blade {
        $blade = new Blade(config('views_path'), config('blade_cache_path'));

        $blade->directive('csrf', function () use ($container) {
            $nameKey = $container->get('csrf')->getTokenNameKey();
            $valueKey = $container->get('csrf')->getTokenValueKey();
            $name = $container->get('csrf')->getTokenName($nameKey);
            $value = $container->get('csrf')->getTokenValue($valueKey);
            return "<input type='hidden' name='$nameKey' value='$name' />
                <input type='hidden' name='$valueKey' value='$value' />";
        });

        return $blade;
    },

    /**
     * @link https://github.com/slimphp/Slim-Csrf/tree/0.8.3
     */
    'csrf' => function (): Guard {
        $guard = new Guard;

        // TODO make the persistent mode configurable through the 'config/app.php' file
        // and solve the blade cache problem for templates that use the '@crsf' directive
        // (for non persistence crsf tokens)
        $guard->setPersistentTokenMode(true);
        
        $guard->setFailureCallable(function ($request, $response, $next) {
            $request = $request->withAttribute("csrf_status", false);
            return $next($request, $response);
        });

        return $guard;
    },

    /**
     * @link https://github.com/bryanjhv/slim-session
     */
    'session' => function (): Helper {
        return new Helper;
    },
];
