<?php
namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Core\Database;

class AccueilController {
    private $transactionRepository;

    public function __construct() {
        $database = Database::getInstance();
        $this->transactionRepository = new TransactionRepository($database->getPdo());
    }

    public function index() {
        try {
            $latestTransactions = $this->transactionRepository->findLatestTransactions();
            $solde = $this->transactionRepository->getSolde();
            
            // Make sure $latestTransactions is always an array
            if (!is_array($latestTransactions)) {
                $latestTransactions = [];
            }

            require_once __DIR__ . '/../../templates/views/accueil.html.php';
            
        } catch (\PDOException $e) {
            // Log error and show friendly message
            error_log($e->getMessage());
            $latestTransactions = [];
            $solde = 0;
            require_once __DIR__ . '/../../templates/views/accueil.html.php';
        }
    }
}