<?php

namespace App\Repository;

class TransactionRepository {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion;
    }

    public function findAllPaginated(int $limit, int $offset)
    {
        $query = "SELECT 
                    t.id as id_transaction,
                    t.montant,
                    t.date as date_transaction,
                    t.type_transaction,
                    c.numero as numero_telephone
                FROM transaction t
                JOIN compte c ON t.compte_id = c.id
                ORDER BY t.date DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findLatestTransactions(int $limit = 10): array
    {
        try {
            $query = "SELECT 
                        t.id as id_transaction,
                        t.montant,
                        t.date as date_transaction,
                        tt.libelle as type_transaction,
                        c.numero as numero_telephone
                    FROM transaction t
                    JOIN compte c ON t.compte_id = c.id
                    JOIN type_transaction tt ON t.type_transaction = tt.id_type 
                    ORDER BY t.date DESC
                    LIMIT :limit";

            $stmt = $this->connexion->prepare($query);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des transactions: " . $e->getMessage());
            return [];
        }
    }

    public function countAll(): int
    {
        $query = "SELECT COUNT(*) FROM transaction";
        return (int)$this->connexion->query($query)->fetchColumn();
    }

    public function findTransactionsByPhone(string $phone, int $limit = 10)
    {
        $query = "SELECT 
                    t.id as id_transaction,
                    t.montant,
                    t.date as date_transaction,
                    t.type_transaction,
                    c.numero as numero_telephone,
                    u.nom,
                    u.prenom
                FROM transaction t
                JOIN compte c ON t.compte_id = c.id
                JOIN users u ON c.id_client = u.id
                WHERE c.numero = :phone
                ORDER BY t.date DESC
                LIMIT :limit";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':phone', $phone, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findTransactionsByPhoneWithFilters(string $phone, string $dateFilter = '', string $typeFilter = '', int $limit = 15, int $offset = 0)
    {
        $whereConditions = ["c.numero = :phone"];
        $params = [':phone' => $phone];

        if (!empty($dateFilter)) {
            $whereConditions[] = "DATE(t.date) = :date_filter";
            $params[':date_filter'] = $dateFilter;
        }

        if (!empty($typeFilter)) {
            $whereConditions[] = "t.type_transaction = :type_filter";
            $params[':type_filter'] = $typeFilter;
        }

        $whereClause = implode(' AND ', $whereConditions);

        $query = "SELECT 
                    t.id as id_transaction,
                    t.montant,
                    t.date as date_transaction,
                    t.type_transaction,
                    c.numero as numero_telephone,
                    u.nom,
                    u.prenom
                FROM transaction t
                JOIN compte c ON t.compte_id = c.id
                JOIN users u ON c.id_client = u.id
                WHERE {$whereClause}
                ORDER BY t.date DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->connexion->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_STR);
        }
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countTransactionsByPhoneWithFilters(string $phone, string $dateFilter = '', string $typeFilter = '')
    {
        $whereConditions = ["c.numero = :phone"];
        $params = [':phone' => $phone];

        if (!empty($dateFilter)) {
            $whereConditions[] = "DATE(t.date) = :date_filter";
            $params[':date_filter'] = $dateFilter;
        }

        if (!empty($typeFilter)) {
            $whereConditions[] = "t.type_transaction = :type_filter";
            $params[':type_filter'] = $typeFilter;
        $whereClause = implode(' AND ', $whereConditions);
        }
        $query = "SELECT COUNT(*) 
                FROM transaction t
                JOIN compte c ON t.compte_id = c.id
                WHERE {$whereClause}";
        
        $stmt = $this->connexion->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_STR);
        }
        
        $stmt->execute();
        
        return (int)$stmt->fetchColumn();
    }
    public function findAllWithFilters(string $dateFilter = '', string $typeFilter = '', int $limit = 15, int $offset = 0)
    {
        $whereConditions = [];
        $params = [];
        
        if (!empty($dateFilter)) {
            $whereConditions[] = "DATE(t.date) = :date_filter";
            $params[':date_filter'] = $dateFilter;
        }
        
        if (!empty($typeFilter)) {
            $whereConditions[] = "tt.libelle = :type_filter";
            $params[':type_filter'] = $typeFilter;
        }
        
        $whereClause = !empty($whereConditions) 
            ? 'WHERE ' . implode(' AND ', $whereConditions)
            : '';
        
        $query = "SELECT 
                    t.id as id_transaction,
                    t.montant,
                    t.date as date_transaction,
                    tt.libelle as type_transaction,
                    c.numero as numero_telephone
                FROM transaction t
                JOIN compte c ON t.compte_id = c.id
                JOIN type_transaction tt ON t.type_transaction = tt.id_type 
                {$whereClause}
                ORDER BY t.date DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->connexion->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_STR);
        }
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countAllWithFilters(string $dateFilter = '', string $typeFilter = ''): int
    {
        $whereConditions = [];
        $params = [];
        
        if (!empty($dateFilter)) {
            $whereConditions[] = "DATE(t.date) = :date_filter";
            $params[':date_filter'] = $dateFilter;
        }
        
        if (!empty($typeFilter)) {
            $whereConditions[] = "tt.libelle = :type_filter";
            $params[':type_filter'] = $typeFilter;
        }
        
        $whereClause = !empty($whereConditions) 
            ? 'WHERE ' . implode(' AND ', $whereConditions)
            : '';
        
        $query = "SELECT COUNT(*) 
            FROM transaction t
            JOIN compte c ON t.compte_id = c.id
            JOIN type_transaction tt ON t.type_transaction = tt.id_type 
            {$whereClause}";
    
        $stmt = $this->connexion->prepare($query);
    
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_STR);
        }
    
        $stmt->execute();
    
        return (int)$stmt->fetchColumn();
    }
    public function findTransactionsByCompte(int $compteId, int $limit = 10): array
    {
        try {
            $query = "SELECT 
                        t.id as id_transaction,
                        t.montant,
                        t.date as date_transaction,
                        t.type_transaction,
                        c.numero_telephone,
                        c.numero_compte
                    FROM transaction t
                    JOIN compte c ON t.compte_id = c.id
                    WHERE t.compte_id = :compte_id
                    ORDER BY t.date DESC
                    LIMIT :limit";

            $stmt = $this->connexion->prepare($query);
            $stmt->bindValue(':compte_id', $compteId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des transactions du compte: " . $e->getMessage());
            return [];
        }
    }
}