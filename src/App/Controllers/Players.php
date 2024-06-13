<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PlayersRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class Players
{
    public function __construct(private PlayersRepository $repository,
                                private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'nombres' => ['required'],
            'apellidos' => ['required'],
            'fechaNacimiento' => ['required'],
            'genero' => ['required'],
            'posicion' => ['required'],
            'idEquipo' => ['required']
        ]);
    }

    public function show(Request $request, Response $response, string $id): Response
    {
        $team = $request->getAttribute('player');

        $body = json_encode($team);
    
        $response->getBody()->write($body);
    
        return $response;        
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $this->validator = $this->validator->withData($body);

        if ( ! $this->validator->validate()) {

            $response->getBody()
                     ->write(json_encode($this->validator->errors()));

            return $response->withStatus(422);

        }

        $id = $this->repository->create($body);

        $body = json_encode([
            'message' => 'the player was created successfully',
            'id' => $id
        ]);

        $response->getBody()->write($body);

        return $response->withStatus(201);
    }
}