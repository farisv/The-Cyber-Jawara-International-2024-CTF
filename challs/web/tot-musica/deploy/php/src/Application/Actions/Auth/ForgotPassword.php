<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

use Illuminate\Database\Capsule\Manager as DB;

use App\Models\User;

class ForgotPassword
{
    protected LoggerInterface $logger;

    protected Request $request;

    protected Response $response;

    protected array $args;

    private $flash;

    private $body;

    private $state;

    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->flash = $container->get('flash');
        $this->state = $container->get('state');
        $container->get(DB::class);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            $this->body = $request->getParsedBody() ?: $this->args;
            if ((empty($this->body['username'])) and (!is_array($this->body['username']))){
                $this->flash->addMessage('message', 'Parameter username is required');
                $response
                    ->withHeader('Location', '/login')
                    ->withStatus(302);
            }
            
            return $this->action();
        } catch (\Exception $e) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }
    }

    protected function action(): Response
    {
        try {
            $query = User::query();
            $res = $query->get()
                ->where('username', '=', $this->body['username'])->first();
            if (empty($res)) {
                $this->flash->addMessage('message', 'Username does not exists');
                return $this->response
                    ->withHeader('Location', '/register')
                    ->withStatus(302);
            } else {
                $this->state->store('user_name', $res['username']);
                $this->logger->info($res['username'] . " is requesting a password reset");
                return $this->response
                    ->withHeader('Location', '/forgot-password')
                    ->withStatus(302);
            }
        } catch (\Exception $e) {
            return $this->response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }
    }
}
