<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\UserRepository;
use App\Repository\TransactionRepository;
use App\Core\Database;
use App\Core\App;

class ServiceCommercialController extends AbstractController
{
    private UserRepository $userRepository;
    private TransactionRepository $transactionRepository;

    public function __construct(UserRepository $userRepository, Database $database)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->transactionRepository = new TransactionRepository($database->getPdo());
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
            $numero = $_POST['numero'] ?? '';
            
            if (empty($numero)) {
                return $this->renderView('service-commercial/dashboard', [
                    'error' => 'Veuillez saisir un numéro de téléphone'
                ]);
            }

            // Rechercher le compte par numéro
            $user = $this->userRepository->findByPhone($numero);
            
            if (!$user) {
                return $this->renderView('service-commercial/dashboard', [
                    'error' => 'Aucun compte trouvé pour ce numéro'
                ]);
            }

            // Récupérer les 10 dernières transactions
            $transactions = $this->transactionRepository->findTransactionsByPhone($numero, 10);
            
            // Simuler le solde (à adapter selon votre logique métier)
            $solde = 1500; // À remplacer par la vraie logique de calcul du solde

            return $this->renderView('service-commercial/account-details', [
                'user' => $user,
                'numero' => $numero,
                'solde' => $solde,
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