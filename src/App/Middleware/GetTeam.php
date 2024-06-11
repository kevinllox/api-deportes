<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Repositories\TeamsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use App\Repositories\ProductRepository;
use Slim\Exception\HttpNotFoundException;

class GetTeam
{
    public function __construct(private TeamsRepository $repository)
    {
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('id');

        $team = $this->repository->getById((int) $id);
    
        if ($team === false) {
    
            throw new HttpNotFoundException($request,message: 'TEAM not found');
    
        }

        $request = $request->withAttribute('team', $team);

        return $handler->handle($request);
    }
}