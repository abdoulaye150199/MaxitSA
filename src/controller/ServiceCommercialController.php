<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\UserRepository;
use App\Repository\TransactionRepository;
use App\Repository\CompteRepository;
use App\Core\App;
use App\Core\Validator;
          
class ServiceCommercialController extends AbstractController
{
    private UserRepository $userRepository;
    private TransactionRepository $transactionRepository;
    private CompteRepository $compteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = App::getDependency('userRepository');
        $this->transactionRepository = App::getDependency('transactionRepository');
        $this->compteRepository = App::getDependency('compteRepository');
    }

    public function index()
    {
        return $this->renderHtml('service-commercial/dashboard');
    }

    public function searchAccount()
    {
        $this->layout = 'base.service.html.layout.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['numero'])) {
                return $this->renderHtml('service-commercial/dashboard', [
                    'error' => 'Le numéro de compte est requis'
                ]);
            }

            $numeroRecherche = $_POST['numero'];
            
            // Recherche par numéro de compte (P-0001 ou S-0001)
            if (preg_match('/^[PS]-\d{4}$/', $numeroRecherche)) {
                $compte = $this->compteRepository->findByNumeroCompte($numeroRecherche);
            } 
            // Recherche par numéro de téléphone
            else {
                $numero = str_replace(['+221', ' '], '', $numeroRecherche);
                if (!preg_match('/^(7[056789])[0-9]{7}$/', $numero)) {
                    return $this->renderHtml('service-commercial/dashboard', [
                        'error' => 'Format de numéro invalide',
                        'numero' => $_POST['numero']
                    ]);
                }
                $compte = $this->compteRepository->findByNumero($numero);
            }

            if (!$compte) {
                return $this->renderHtml('service-commercial/dashboard', [
                    'error' => 'Aucun compte trouvé',
                    'numero' => $_POST['numero']
                ]);
            }

            $user = $this->userRepository->findById($compte->getUser()->getId());
            $transactions = $this->transactionRepository->findTransactionsByCompte($compte->getId(), 10);

            return $this->renderHtml('service-commercial/account-details', [
                'user' => $user,
                'compte' => $compte,
                'transactions' => $transactions
            ]);
        }

        return $this->renderHtml('service-commercial/dashboard');
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

        return $this->renderHtml('service-commercial/all-transactions', [
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