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

    public function countAll(): int
    {
        $query = "SELECT COUNT(*) FROM transaction";
        return (int)$this->connexion->query($query)->fetchColumn();
    }
}