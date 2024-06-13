<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class PlayersRepository
{
    public function __construct(private Database $database)
    {
    }

    public function getAll(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM jugadores');
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|bool
    {
        $sql = 'SELECT *
                FROM jugadores
                WHERE idJugador = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): string
    {
        $sql = 'INSERT INTO jugadores (nombres, apellidos, fechaNacimiento, genero, posicion, idEquipo)
                VALUES (:nombres, :apellidos, :fechaNacimiento, :genero, :posicion, :idEquipo)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nombres', $data['nombres'], PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $data['apellidos'], PDO::PARAM_STR);
        $stmt->bindValue(':fechaNacimiento', $data['fechaNacimiento'], PDO::PARAM_STR);
        $stmt->bindValue(':genero', $data['genero'], PDO::PARAM_STR);
        $stmt->bindValue(':posicion', $data['posicion'], PDO::PARAM_STR);
        $stmt->bindValue(':idEquipo', $data['idEquipo'], PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

}