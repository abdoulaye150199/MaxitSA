<?php
namespace App\Repository;

use App\Core\Database;
use PDO;

class TransactionRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getLastTransactions($limit = 10)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM transaction ORDER BY date DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}