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
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM compte WHERE numero_telephone = :numero');
            $stmt->execute([':numero' => $data['numero_telephone']]);
            if ($stmt->fetchColumn() > 0) {
                error_log('Numéro de téléphone déjà utilisé');
                return false;
            }

            // Insérer le nouveau compte
            $query = "INSERT INTO compte (
                numero_telephone, 
                code_secret, 
                solde, 
                est_principal,
                id_client,
                id_type_compte
            ) VALUES (
                :numero_telephone,
                :code_secret,
                :solde,
                false,
                :id_client,
                2
            )";

            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':numero_telephone' => $data['numero_telephone'],
                ':code_secret' => password_hash($data['code_secret'], PASSWORD_DEFAULT),
                ':solde' => $data['montant_initial'],
                ':id_client' => $data['id_client']
            ]);
        } catch (\PDOException $e) {
            error_log('Erreur création compte secondaire: ' . $e->getMessage());
            return false;
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
}