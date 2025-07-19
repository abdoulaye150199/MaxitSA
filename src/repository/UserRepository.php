<?php

namespace App\Repository;

use App\Core\Database;
use App\Entite\Utilisateur;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? $this->hydrate($data) : null;
    }

    public function findByPhone(string $phone): ?Utilisateur
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE numero = :numero');
        $stmt->execute([':numero' => $phone]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? $this->hydrate($data) : null;
    }

    public function findByCode(string $code): ?Utilisateur 
    {
        try {
            // On récupère tous les utilisateurs car on doit vérifier le hash
            $stmt = $this->pdo->prepare('SELECT * FROM users');
            $stmt->execute();
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($users as $userData) {
                // Vérifie si le code correspond au hash stocké
                if (password_verify($code, $userData['code'])) {
                    return new Utilisateur(
                        $userData['id'],
                        $userData['code'],
                        $userData['nom'],
                        $userData['prenom'],
                        $userData['numero'],
                        $userData['adresse'],
                        $userData['type_user'],
                        $userData['photo_identite_recto'],
                        $userData['photo_identite_verso'],
                        $userData['numero_carte_identite']
                    );
                }
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log("Erreur recherche utilisateur: " . $e->getMessage());
            return null;
        }
    }

    public function create(Utilisateur $user): bool
    {
        try {
            $query = 'INSERT INTO users (
                code, nom, prenom, numero, adresse, type_user, 
                photo_identite_recto, photo_identite_verso, numero_carte_identite
            ) VALUES (
                :code, :nom, :prenom, :numero, :adresse, :type_user,
                :photo_identite_recto, :photo_identite_verso, :numero_carte_identite
            )';
            
            $data = [
                ':code' => $user->getCode(),
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':numero' => $user->getNumero(),
                ':adresse' => $user->getAdresse(),
                ':type_user' => $user->getTypeUserValue(),
                ':photo_identite_recto' => $user->getPhotoIdentiteRecto(),
                ':photo_identite_verso' => $user->getPhotoIdentiteVerso(),
                ':numero_carte_identite' => $user->getNumeroCarteIdentite()
            ];
            
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($data);
        } catch (\PDOException $e) {
            error_log("Erreur insertion utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function update(Utilisateur $user): bool
    {
        try {
            $query = 'UPDATE users SET 
                code = :code,
                nom = :nom,
                prenom = :prenom,
                numero = :numero,
                adresse = :adresse,
                type_user = :type_user,
                photo_identite_recto = :photo_identite_recto,
                photo_identite_verso = :photo_identite_verso,
                numero_carte_identite = :numero_carte_identite
                WHERE id = :id';
            
            $data = [
                ':id' => $user->getId(),
                ':code' => $user->getCode(),
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':numero' => $user->getNumero(),
                ':adresse' => $user->getAdresse(),
                ':type_user' => $user->getTypeUserValue(),
                ':photo_identite_recto' => $user->getPhotoIdentiteRecto(),
                ':photo_identite_verso' => $user->getPhotoIdentiteVerso(),
                ':numero_carte_identite' => $user->getNumeroCarteIdentite()
            ];
            
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($data);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour utilisateur: " . $e->getMessage());
            return false;
        }
    }

    private function hydrate(array $data): Utilisateur
    {
        return new Utilisateur(
            $data['id'],
            $data['code'],
            $data['nom'],
            $data['prenom'],
            $data['numero'],
            $data['adresse'],
            $data['type_user'],
            $data['photo_identite_recto'],
            $data['photo_identite_verso'],
            $data['numero_carte_identite']
        );
    }
}