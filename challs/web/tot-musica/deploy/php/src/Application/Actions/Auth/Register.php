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

class Register
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
            if (
                (empty($this->body['username']) or empty($this->body['password']) or empty($this->body['full_name']) or empty($this->body['email'])) 
                and 
                (!is_array($this->body['username']) or !is_array($this->body['password']) or !is_array($this->body['full_name']) or !is_array($this->body['email']))
            ) {
                $this->flash->addMessage('message', 'Parameter username, password, full_name, or email is required');
                return $response
                    ->withHeader('Location', '/register')
                    ->withStatus(302);
            }

            if ((strlen($this->body['username']) < 8) or (strlen($this->body['password']) < 8)) {
                $this->flash->addMessage('message', 'Length of the username and password should be more than 8 chars');
                return $response
                    ->withHeader('Location', '/register')
                    ->withStatus(302);
            }
            return $this->action();
        } catch (\Exception $e) {
            return $response
                ->withHeader('Location', '/register')
                ->withStatus(302);
        }
    }

    protected function action(): Response
    {
        $query = User::query();
        $res = $query->get()
            ->where('username', '=', $this->body['username'])->first();
        if (!empty($res)) {
            $this->flash->addMessage('message', 'Username is already exists');
            return $this->response
                ->withHeader('Location', '/register')
                ->withStatus(302);
        } else {
            try{
                $res = User::create(['username' => $this->body['username'], 'full_name' => $this->body['full_name'], 'email' => $this->body['email'], 'passwd' => md5($this->body['password']), 'roles' => 0]);
                $this->state->store('user_id', $res['id']);
                $this->state->store('user_name', $res['username']);
                $this->state->store('user_role', 0);
                return $this->response
                    ->withHeader('Location', '/songs')
                    ->withStatus(302);
            }catch(Exception $e){
                return $this->response
                    ->withHeader('Location', '/songs')
                    ->withStatus(302);
            }
        }
    }
}
