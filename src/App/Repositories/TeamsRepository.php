<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class TeamsRepository
{
    public function __construct(private Database $database)
    {
    }

    public function getAll(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM equipos');
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|bool
    {
        $sql = 'SELECT *
                FROM equipos
                WHERE idEquipo = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): string
    {
        $sql = 'INSERT INTO equipos (nombreEquipo, institucion, departamento, municipio, direccion, telefono)
                VALUES (:nombreEquipo, :institucion, :departamento, :municipio, :direccion, :telefono)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nombreEquipo', $data['nombreEquipo'], PDO::PARAM_STR);
        $stmt->bindValue(':institucion', $data['institucion'], PDO::PARAM_STR);
        $stmt->bindValue(':departamento', $data['departamento'], PDO::PARAM_STR);
        $stmt->bindValue(':municipio', $data['municipio'], PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $data['direccion'], PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $data['telefono'], PDO::PARAM_STR);
        if (empty($data['nombreEquipo'])) {

            $stmt->bindValue(':nombreEquipo', null, PDO::PARAM_NULL);

        } else {

            $stmt->bindValue(':nombreEquipo', $data['nombreEquipo'], PDO::PARAM_STR);

        }
        if (empty($data['institucion'])) {

            $stmt->bindValue(':institucion', null, PDO::PARAM_NULL);

        } else {

            $stmt->bindValue(':institucion', $data['institucion'], PDO::PARAM_STR);

        }
        if (empty($data['departamento'])) {

            $stmt->bindValue(':departamento', null, PDO::PARAM_NULL);

        } else {

            $stmt->bindValue(':departamento', $data['departamento'], PDO::PARAM_STR);

        }
        if (empty($data['municipio'])) {

            $stmt->bindValue(':municipio', null, PDO::PARAM_NULL);

        } else {

            $stmt->bindValue(':municipio', $data['municipio'], PDO::PARAM_STR);

        }
        if (empty($data['direccion'])) {

            $stmt->bindValue(':direccion', null, PDO::PARAM_NULL);

        } else {

            $stmt->bindValue(':direccion', $data['direccion'], PDO::PARAM_STR);

        }
        if (empty($data['telefono'])) {

            $stmt->bindValue(':telefono', null, PDO::PARAM_NULL);

        } else {

            $stmt->bindValue(':telefono', $data['telefono'], PDO::PARAM_STR);

        }
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        // Initialize arrays to hold the parts of the SQL query and the bindings
        $setClauses = [];
        $bindings = [];
    
        // Loop through the $data array to build the SET clause and bindings
        foreach ($data as $column => $value) {
            $setClauses[] = "$column = :$column";
            $bindings[":$column"] = $value;
        }
    
        // Join the SET clauses with commas
        $setClause = implode(', ', $setClauses);
    
        // Construct the final SQL query
        $sql = "UPDATE equipos SET $setClause WHERE idEquipo = :id";
        $bindings[':id'] = $id;
    
        // Get the PDO connection
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
    
        // Bind the values to the statement
        foreach ($bindings as $placeholder => $value) {
            // Determine the appropriate PDO type for each value
            $pdoType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($placeholder, $value, $pdoType);
        }
    
        // Execute the statement
        $stmt->execute();
    
        // Return the number of affected rows
        return $stmt->rowCount();
    }
    

    public function delete(string $id): int
    {
        $sql = 'DELETE FROM equipos
                WHERE idEquipo = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}