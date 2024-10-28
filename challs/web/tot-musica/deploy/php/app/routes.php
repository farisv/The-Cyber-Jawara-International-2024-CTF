<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Models\Song;

use App\Application\Actions\Auth\Login;
use App\Application\Actions\Auth\Register;
use App\Application\Actions\Auth\ForgotPassword;

use App\Application\Actions\Song\SearchSong;

return function (App $app) {
    
    $app->get('/', function (Request $request, Response $response) {
        return $response
        ->withHeader('Location', '/login')
        ->withStatus(302);
    });

    $app->get('/login', function (Request $request, Response $response) {
        $message = $this->get('flash')->getFirstMessage('message') ?? '';
        return $this->get('renderer')->render($response, 'auth/login.html', ['message' => $message]);
    });

    $app->get('/register', function (Request $request, Response $response) {
        $message = $this->get('flash')->getFirstMessage('message') ?? '';
        return $this->get('renderer')->render($response, 'auth/register.html', ['message' => $message]);
    });

    $app->get('/forgot-password', function (Request $request, Response $response) {
        $message = $this->get('flash')->getFirstMessage('message') ?? '';
        return $this->get('renderer')->render($response, 'auth/forgot-password.html', ['message' => $message]);
    });

    $app->group('/auth', function (Group $group) {
        $group->post('/login', Login::class);
        $group->post('/register', Register::class);

        $group->any('/forgot-password[{username}]', ForgotPassword::class);
    });

    $app->group('/songs', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            $message = $this->get('flash')->getFirstMessage('message') ?? '';
            return $this->get('renderer')->render($response, 'songs/index.html', ['message' => $message]);

        });

        $group->get('/search', SearchSong::class);
    });
};
