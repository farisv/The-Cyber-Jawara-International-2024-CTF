<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Middleware\MethodOverrideMiddleware;

use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->addRoutingMiddleware();
    
    $app->add(function (Request $request, RequestHandlerInterface $handler) use ($app): Response {
        if ($request->getMethod() === 'OPTIONS') {
            $response = $app->getResponseFactory()->createResponse();
        } else {
            $response = $handler->handle($request);
        }
        
        $response = $response
        ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->withHeader('Pragma', 'no-cache');
        if (ob_get_contents()) {
            ob_clean();
        }
        return $response;
    });
    
    $app->add(
        function (Request $request, RequestHandlerInterface $handler) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $this->get('flash')->__construct($_SESSION);
            return $handler->handle($request);
        }
    );
};
