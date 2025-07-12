<?php

namespace App\Repository;

use App\Core\Database;
use App\Entite\Utilisateur;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapToEntity($row) : null;
    }

    public function findByCode(string $code): ?Utilisateur
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE code = :code');
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapToEntity($row) : null;
    }

    public function findByPhone(string $phone): ?Utilisateur
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE numero = :numero');
        $stmt->execute([':numero' => $phone]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapToEntity($row) : null;
    }

    public function create(Utilisateur $user): bool
    {
        try {
            $query = 'INSERT INTO users (
                code, 
                nom, 
                prenom, 
                numero, 
                adresse, 
                type_user, 
                photo_identite_recto, 
                photo_identite_verso, 
                numero_carte_identite
            ) VALUES (
                :code, 
                :nom, 
                :prenom, 
                :numero, 
                :adresse, 
                :type_user, 
                :photo_identite_recto, 
                :photo_identite_verso, 
                :numero_carte_identite
            )';
            
            $stmt = $this->pdo->prepare($query);
            
            return $stmt->execute([
                ':code' => $user->getCode(),
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':numero' => $user->getNumero(),
                ':adresse' => $user->getAdresse(),
                ':type_user' => $user->getTypeUserValue(),
                ':photo_identite_recto' => $user->getPhotoIdentiteRecto(),
                ':photo_identite_verso' => $user->getPhotoIdentiteVerso(),
                ':numero_carte_identite' => $user->getNumeroCarteIdentite()
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur insertion utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function update(Utilisateur $user): bool
    {
        try {
            $query = 'UPDATE users SET code = :code, nom = :nom, prenom = :prenom, 
                      numero = :numero, adresse = :adresse, type_user = :type_user, 
                      photo_identite_recto = :photo_identite_recto, 
                      photo_identite_verso = :photo_identite_verso, 
                      numero_carte_identite = :numero_carte_identite 
                      WHERE id = :id';
            
            $stmt = $this->pdo->prepare($query);
            
            return $stmt->execute([
                ':id' => $user->getId(),
                ':code' => $user->getCode(),
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':numero' => $user->getNumero(),
                ':adresse' => $user->getAdresse(),
                ':type_user' => $user->getTypeUser(),
                ':photo_identite_recto' => $user->getPhotoIdentiteRecto(),
                ':photo_identite_verso' => $user->getPhotoIdentiteVerso(),
                ':numero_carte_identite' => $user->getNumeroCarteIdentite()
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Erreur suppression utilisateur: " . $e->getMessage());
            return false;
        }
    }

    private function mapToEntity(?array $data): ?Utilisateur
    {
        if (!$data) {
            return null;
        }
        
        return new Utilisateur(
            (int)$data['id'],
            $data['code'] ?? '',
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['numero'] ?? '',
            $data['adresse'] ?? '',
            $data['type_user'] ?? 'client',
            $data['photo_identite_recto'] ?? null,
            $data['photo_identite_verso'] ?? null,
            $data['numero_carte_identite'] ?? null
        );
    }
}