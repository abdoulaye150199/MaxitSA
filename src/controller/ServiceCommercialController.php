<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\UserRepository;
use App\Repository\TransactionRepository;
use App\Repository\CompteRepository;
use App\Core\Database;
use App\Core\App;
use App\Core\Validator;

class ServiceCommercialController extends AbstractController
{
    private UserRepository $userRepository;
    private TransactionRepository $transactionRepository;
    private CompteRepository $compteRepository;

    public function __construct(UserRepository $userRepository, Database $database)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->transactionRepository = new TransactionRepository($database->getPdo());
        $this->compteRepository = new CompteRepository($database);
    }

    public function index()
    {
        $this->layout = 'base.service.html.layout.php';
        return $this->renderView('service-commercial/dashboard');
    }

    public function searchAccount()
    {
        $this->layout = 'base.service.html.layout.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = Validator::validateSearchAccount($_POST);
            
            if (!empty($errors)) {
                return $this->renderView('service-commercial/dashboard', [
                    'error' => reset($errors)
                ]);
            }

            $numero = $_POST['numero'];
            $user = $this->userRepository->findByPhone($numero);
            
            if (!$user) {
                return $this->renderView('service-commercial/dashboard', [
                    'error' => 'Aucun compte trouvé pour ce numéro'
                ]);
            }

            // Get the account details
            $compte = $this->compteRepository->findByNumero($numero);
            if (!$compte) {
                return $this->renderView('service-commercial/dashboard', [
                    'error' => 'Aucun compte associé à ce numéro'
                ]);
            }

            $transactions = $this->transactionRepository->findTransactionsByPhone($numero, 10);
            
            return $this->renderView('service-commercial/account-details', [
                'user' => $user,
                'numero' => $numero,
                'solde' => $compte->getSolde(),
                'transactions' => $transactions
            ]);
        }

        return $this->renderView('service-commercial/dashboard');
    }

    public function allTransactions()
    {
        $this->layout = 'base.service.html.layout.php';
        
        $numero = $_GET['numero'] ?? '';
        $dateFilter = $_GET['date'] ?? '';
        $typeFilter = $_GET['type'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        if (empty($numero)) {
            return $this->redirect('/service-commercial');
        }

        // Récupérer toutes les transactions avec filtres
        $transactions = $this->transactionRepository->findTransactionsByPhoneWithFilters(
            $numero, 
            $dateFilter, 
            $typeFilter, 
            $limit, 
            $offset
        );

        $totalTransactions = $this->transactionRepository->countTransactionsByPhoneWithFilters(
            $numero, 
            $dateFilter, 
            $typeFilter
        );

        $totalPages = ceil($totalTransactions / $limit);

        return $this->renderView('service-commercial/all-transactions', [
            'numero' => $numero,
            'transactions' => $transactions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'dateFilter' => $dateFilter,
            'typeFilter' => $typeFilter
        ]);
    }

    // Méthodes abstraites requises
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}