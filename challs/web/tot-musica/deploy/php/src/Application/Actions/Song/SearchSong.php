<?php

declare(strict_types=1);

namespace App\Application\Actions\Song;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Frameworks\SearchFilter as Filter;
use App\Frameworks\Renderer;
use App\Models\Song;

class SearchSong
{
    protected LoggerInterface $logger;

    protected Request $request;

    protected Response $response;

    protected array $args;

    private $flash;

    private $params;

    private $state;

    private $renderer;

    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->flash = $container->get('flash');
        $this->renderer = $container->get('renderer');
        $this->state = $container->get('state');
        $container->get(DB::class);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->params = $this->request->getQueryParams() ?? '';

        try {
            $this->params['filter'] = $this->params['filter'] ?? '';
            return $this->action();
        } catch (\Exception $e) {
            return $response
                ->withHeader('Location', '/songs/search')
                ->withStatus(302);
        }
    }

    protected function action()
    {
        try{
            $params = $this->params['filter'];
            $user_id = $this->state->retrieve('user_id') ?? '';
            $query = Filter::applyFilters(Song::query(), $params, $user_id);
            $data = $query->get();
    
            $template_data = ['data' => $data->toJson() ?? '{}', 'username' => $this->state->retrieve('user_name')];
            $nonce = md5(random_bytes(32));
            $newResponse = $this->response->withHeader('Content-Security-Policy', "script-src 'nonce-$nonce'");
            if(!$data->isEmpty()){
                $template_data["nonce"] = "$nonce";
                return $this->renderer->render($newResponse, 'songs/search.html', $template_data);
            }else{
                return $this->renderer->render($newResponse, 'songs/search-empty.html', $template_data);
            }
        }catch (\Exception $e){
            return $this->response
                ->withHeader('Location', '/songs/search')
                ->withStatus(302);
        }
    }
}
