<?php

namespace App\Repository;

use App\Core\Database;
use App\Repository\Interfaces\CompteRepositoryInterface;
use App\Entite\Compte; // Changed from App\Entity\Compte
use App\Entite\TypeCompte; // Add this for type enum support

class CompteRepository implements CompteRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getPdo();
    }

    public function createSecondaryCompte(array $data): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Nettoyer le numéro de téléphone
            $numeroTelephone = str_replace(['+221', ' '], '', $data['numero_telephone']);

            // Générer le numéro de compte secondaire
            $lastNumber = $this->getLastAccountNumber('S');
            $numeroCompte = sprintf("S-%04d", $lastNumber + 1);

            $query = "INSERT INTO compte (
                numero_compte,
                numero_telephone, 
                code_secret, 
                solde, 
                est_principal,
                id_client,
                id_type_compte,
                type_compte
            ) VALUES (
                :numero_compte,
                :numero_telephone,
                :code_secret,
                :solde,
                false,
                :id_client,
                2,
                'Secondaire'
            )";

            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute([
                ':numero_compte' => $numeroCompte,
                ':numero_telephone' => $numeroTelephone,
                ':code_secret' => password_hash($data['code_secret'], PASSWORD_DEFAULT),
                ':solde' => $data['montant_initial'] ?? 0,
                ':id_client' => $data['id_client']
            ]);

            if ($result) {
                $this->pdo->commit();
                return true;
            }

            $this->pdo->rollBack();
            return false;

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log('Erreur création compte secondaire: ' . $e->getMessage());
            return false;
        }
    }

    private function getLastAccountNumber(string $prefix): int
    {
        try {
            $query = "SELECT COALESCE(MAX(CAST(SUBSTRING(numero_compte FROM 3) AS INTEGER)), 0)
                      FROM compte 
                      WHERE numero_compte LIKE :prefix";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':prefix' => $prefix . '-%']);
            
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('Erreur récupération dernier numéro: ' . $e->getMessage());
            return 0;
        }
    }

    public function findByNumero(string $numero): ?Compte
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM compte WHERE numero_telephone = :numero');
            $stmt->execute([':numero' => $numero]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }

            $compte = new Compte();
            $compte->setId($data['id_compte']);
            $compte->setNumeroTelephone($data['numero_telephone']);
            $compte->setSolde($data['solde']);
            $compte->setTypeCompte($data['id_type_compte'] === 1 ? TypeCompte::PRINCIPALE : TypeCompte::SECONDAIRE);
            
            return $compte;
        } catch (\PDOException $e) {
            error_log('Erreur recherche compte: ' . $e->getMessage());
            return null;
        }
    }
    
    public function findByNumeroCompte(string $numeroCompte): ?Compte
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM compte WHERE numero_compte = :numero_compte');
            $stmt->execute([':numero_compte' => $numeroCompte]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }

            // Créer et hydrater l'objet Compte
            $compte = new Compte();
            $compte->setId($data['id']);
            $compte->setNumeroCompte($data['numero_compte']);
            $compte->setNumeroTelephone($data['numero_telephone']);
            $compte->setSolde($data['solde']);
            $compte->setTypeCompte($data['id_type_compte'] === 1 ? TypeCompte::PRINCIPALE : TypeCompte::SECONDAIRE);
            
            return $compte;
        } catch (\PDOException $e) {
            error_log('Erreur recherche compte par numéro: ' . $e->getMessage());
            return null;
        }
    }

    public function findByUserId(int $userId): ?Compte
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM compte 
                WHERE id_client = :user_id 
                AND est_principal = true
                LIMIT 1
            ');
            
            $stmt->execute([':user_id' => $userId]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }

            // Créer et hydrater l'objet Compte
            $compte = new Compte();
            $compte->setId($data['id']);
            $compte->setNumeroCompte($data['numero_compte']);
            $compte->setNumeroTelephone($data['numero_telephone']);
            $compte->setSolde($data['solde']);
            $compte->setTypeCompte($data['id_type_compte'] === 1 ? TypeCompte::PRINCIPALE : TypeCompte::SECONDAIRE);
            
            return $compte;
        } catch (\PDOException $e) {
            error_log('Erreur recherche compte par user ID: ' . $e->getMessage());
            return null;
        }
    }

    public function findAllByUserId(int $userId): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM compte 
                WHERE id_client = :user_id
                ORDER BY est_principal DESC, date_creation DESC
            ');
            
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            error_log('Erreur findAllByUserId: ' . $e->getMessage());
            return [];
        }
    }
}