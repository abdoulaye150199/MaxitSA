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
            error_log("Début création compte secondaire");

            // Nettoyer le numéro de téléphone
            $numeroTelephone = str_replace(['+221', ' '], '', $data['numero_telephone']);

            // Vérifier si le numéro existe déjà
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM compte WHERE numero_telephone = ?");
            $stmt->execute([$numeroTelephone]);
            if ($stmt->fetchColumn() > 0) {
                $this->pdo->rollBack();
                error_log("Numéro déjà utilisé");
                return false;
            }

            // Générer le numéro de compte
            $lastNumber = $this->getLastAccountNumber('S');
            $numeroCompte = sprintf("S-%04d", $lastNumber + 1);

            $query = "INSERT INTO compte (
                numero,
                type_compte,
                solde,
                user_id,
                numero_telephone,
                date_creation
            ) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

            $params = [
                $numeroCompte,
                'Secondaire',
                $data['montant_initial'] ?? 0,
                $data['id_client'],
                $numeroTelephone
            ];

            error_log("Paramètres d'insertion : " . print_r($params, true));
            
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($params);

            if ($result) {
                $this->pdo->commit();
                error_log("Compte créé avec succès");
                return true;
            }

            $this->pdo->rollBack();
            error_log("Échec de la création");
            return false;

        } catch (\PDOException $e) {
            error_log("ERREUR SQL: " . $e->getMessage());
            error_log("TRACE: " . $e->getTraceAsString());
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    public function getLastAccountNumber(string $prefix): int
    {
        try {
            $query = "SELECT COALESCE(MAX(CAST(SUBSTRING(numero, 3) AS INTEGER)), 0)
                      FROM compte 
                      WHERE numero LIKE :prefix";
            
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
            $stmt = $this->pdo->prepare('
                SELECT c.*, u.* 
                FROM compte c 
                JOIN users u ON c.id_client = u.id 
                WHERE c.numero_telephone = :numero
            ');
            $stmt->execute([':numero' => $numero]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }

            // Créer l'utilisateur
            $user = new \App\Entite\Utilisateur(
                $data['id_client'],
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

            $compte = new Compte();
            $compte->setId($data['id']);
            $compte->setNumeroCompte($data['numero_compte']);
            $compte->setNumeroTelephone($data['numero_telephone']);
            $compte->setSolde($data['solde']);
            $compte->setTypeCompte($data['id_type_compte'] === 1 ? TypeCompte::PRINCIPALE : TypeCompte::SECONDAIRE);
            $compte->setUser($user);
            
            return $compte;
        } catch (\PDOException $e) {
            error_log('Erreur recherche compte: ' . $e->getMessage());
            return null;
        }
    }
    
    public function findByNumeroCompte(string $numeroCompte): ?Compte
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT c.*, u.* 
                FROM compte c 
                JOIN users u ON c.id_client = u.id 
                WHERE c.numero_compte = :numero_compte
            ');
            $stmt->execute([':numero_compte' => $numeroCompte]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }

            // Créer l'utilisateur
            $user = new \App\Entite\Utilisateur(
                $data['id_client'],
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

            // Créer et hydrater l'objet Compte
            $compte = new Compte();
            $compte->setId($data['id']);
            $compte->setNumeroCompte($data['numero_compte']);
            $compte->setNumeroTelephone($data['numero_telephone']);
            $compte->setSolde($data['solde']);
            $compte->setTypeCompte($data['id_type_compte'] === 1 ? TypeCompte::PRINCIPALE : TypeCompte::SECONDAIRE);
            $compte->setUser($user);
            
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