<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\TeamsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class Teams
{
    public function __construct(private TeamsRepository $repository,
                                private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'nombreEquipo' => ['required'],
            'institucion' => ['required'],
            'departamento' => ['required'],
            'municipio' => ['required'],
            'direccion' => ['required'],
            'telefono' => ['required']
        ]);
    }

    public function show(Request $request, Response $response, string $id): Response
    {
        $team = $request->getAttribute('team');

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
            'message' => 'the team was created successfully',
            'id' => $id
        ]);

        $response->getBody()->write($body);

        return $response->withStatus(201);
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        $body = $request->getParsedBody();

        $this->validator = $this->validator->withData($body);

        if ( ! $this->validator->validate()) {

            $response->getBody()
                     ->write(json_encode($this->validator->errors()));

            return $response->withStatus(422);

        }

        $rows = $this->repository->update((int) $id, $body);

        $body = json_encode([
            'message' => 'The team was updated successfully',
            'rows' => $rows
        ]);

        $response->getBody()->write($body);

        return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $rows = $this->repository->delete($id);

        $body = json_encode([
            'message' => 'The team was deleted successfully',
            'rows' => $rows
        ]);

        $response->getBody()->write($body);

        return $response;
    }
}