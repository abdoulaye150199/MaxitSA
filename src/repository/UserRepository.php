<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Core\Database;
use App\Entite\Utilisateur;
use PDO;

class UserRepository extends AbstractRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function selectAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = Utilisateur::fromArray($row);
        }
        return $users;
    }

    public function insert($user): bool
    {
        try {
            $query = 'INSERT INTO users (code, nom, prenom, type_user, adresse, numero_carte_identite, photo_identite_recto, photo_identite_verso, numero) 
                      VALUES (:code, :nom, :prenom, :type_user, :adresse, :numero_carte_identite, :photo_identite_recto, :photo_identite_verso, :numero)';
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':code' => $user->getCodeSecret(),
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':type_user' => $user->getTypeUser()->value,
                ':adresse' => $user->getAdresse(),
                ':numero_carte_identite' => $user->getNumeroIdentite(),
                ':photo_identite_recto' => $user->getPhotoCNIrecto(),
                ':photo_identite_verso' => $user->getPhotoCNIverso(),
                ':numero' => $user->getNumero(),
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur insertion utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE code = :code');
        return $stmt->execute([':code' => $id]);
    }

    public function selectById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function selectBy(array $filter): array
    {
        return [];
    }

    public function selectByCode($code)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE code = :code');
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function update($user): bool
    {
        $query = 'UPDATE users SET nom = :nom, prenom = :prenom, type_user = :type_user, adresse = :adresse, numero_carte_identite = :numero_carte_identite, photo_identite_recto = :photo_identite_recto, photo_identite_verso = :photo_identite_verso, numero = :numero WHERE code = :code';
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':code' => $user->getCodeSecret(),
            ':nom' => $user->getNom(),
            ':prenom' => $user->getPrenom(),
            ':type_user' => $user->getTypeUser()->value,
            ':adresse' => $user->getAdresse(),
            ':numero_carte_identite' => $user->getNumeroIdentite(),
            ':photo_identite_recto' => $user->getPhotoCNIrecto(),
            ':photo_identite_verso' => $user->getPhotoCNIverso(),
            ':numero' => $user->getNumero(),
        ]);
    }
}