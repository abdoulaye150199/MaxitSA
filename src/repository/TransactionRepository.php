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

    public function findLatestTransactions(int $limit = 10)
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
                LIMIT :limit";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
}