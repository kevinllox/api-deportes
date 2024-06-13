<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Repositories\PlayersRepository;
use App\Repositories\TeamsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use App\Repositories\ProductRepository;
use Slim\Exception\HttpNotFoundException;

class GetPlayer
{
    public function __construct(private PlayersRepository $repository)
    {
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('id');

        $player = $this->repository->getById((int) $id);
    
        if ($player === false) {
    
            throw new HttpNotFoundException($request,message: 'player not found');
    
        }

        $request = $request->withAttribute('player', $player);

        return $handler->handle($request);
    }
}