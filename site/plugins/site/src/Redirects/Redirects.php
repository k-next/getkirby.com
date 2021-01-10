<?php

namespace Kirby\Redirects;

use Kirby\Http\Router;

/**
 * Creates redirect routes (using the `go()` helper) that only 
 * take over if no actual page/route has been matched.
 * 
 * The redirects get defined in `site/config/redirects.php` in an array 
 * with the old pattern as key and the target page/URL as value. Placeholders
 * can be used in the key and referenced via $1, $2, $3 in the target string. 
 * Instead of a target string, a callback function returning that stirng can 
 * also be used.
 *
 * @author    Nico Hoffmann <nico@getkirby.com>
 * @link      https://getkirby.com
 * @license   MIT
 */
class Redirects
{

    public static function go($path, $method)
    {
        // load redirects definition
        $root      = kirby()->root('config');
        $redirects = require $root . '/redirects.php';

        // turn redirects into routes array
        $routes = array_map(function($from, $to) {
            return [
                'pattern' => $from,
                'action'  => function (...$parameters) use ($to) {

                    // resolve callback for target
                    if (is_callable($to) === true) {
                        $to = $to(...$parameters);
                    }

                    // fill placeholders
                    foreach ($parameters as $i => $parameter) {
                        $to = str_replace('$' . ($i + 1), $parameter, $to);
                    }

                    return go($to);
                }
            ];
        }, array_keys($redirects), $redirects);

        // run router on redirects routes
        try {
            $router = new Router($routes);
            return $router->call($path, $method);
        } catch (Throwable $e) {
            return site()->errorPage();
        }
    }
}