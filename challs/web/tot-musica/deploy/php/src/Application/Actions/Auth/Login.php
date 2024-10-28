<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Capsule\Manager as DB;

use App\Models\User;
use Exception;

class Login
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
            $this->body = $request->getParsedBody();
            if ((empty($this->body['username']) or empty($this->body['password'])) and (!is_array($this->body['username']) or !is_array($this->body['password']))) {
                $this->flash->addMessage('message', 'Parameter username and password is required');
                return $response
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
            
            // if($this->body['username'] === 'uta'){
            //     if(gethostbyname('bot') !== $_SERVER['REMOTE_ADDR']){
            //         $this->flash->addMessage('message', 'Username / Password is wrong');
            //         return $this->response
            //             ->withHeader('Location', '/login')
            //             ->withStatus(302);
            //     }
            // }

            $query = User::query();
            $res = $query->get()
                ->where('username', '=', $this->body['username'])
                ->where('passwd', '=', md5($this->body['password']))->first();
            if (empty($res)) {
                $this->flash->addMessage('message', 'Username / Password is wrong');
                return $this->response
                    ->withHeader('Location', '/login')
                    ->withStatus(302);
            } else {
                $this->state->store('user_id', $res['id']);
                $this->state->store('user_name', $res['username']);
                $this->state->store('user_role', $res['roles']);
                return $this->response
                    ->withHeader('Location', '/songs')
                    ->withStatus(302);
            }
        } catch (Exception $e) {
            return $this->response
                ->withHeader('Location', '/songs')
                ->withStatus(302);
        }
    }
}
