<?php

namespace App\Repository;

class TransactionRepository {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion;
    }

    public function findLatestTransactions($limit = 10) {
        $query = "SELECT id, type_transaction, date, montant, user_id, compte_id 
                 FROM public.transaction 
                 ORDER BY id DESC 
                 LIMIT :limit";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSolde() {
        $query = "SELECT COALESCE(SUM(montant), 0) as solde FROM public.transaction";
        $stmt = $this->connexion->query($query);
        return $stmt->fetch(\PDO::FETCH_ASSOC)['solde'];
    }
}