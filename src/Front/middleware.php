<?php

namespace Front;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Application middleware
 * @param \Slim\App $app
 */
function middleware(\Slim\App $app)
{
    $app->add(function (Request $request, Response $response, callable $next) {
        $uri = $request->getUri();
        $path = $uri->getPath();
        if ($path != '/' && substr($path, -1) == '/') {
            // permanently redirect paths with a trailing slash
            // to their non-trailing counterpart
            $uri = $uri->withPath(substr($path, 0, -1));
            return $response->withRedirect((string)$uri, 301);
        }

        return $next($request, $response);
    });
}
