<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\TransactionRepository;
use App\Core\App;

class TransactionController extends AbstractController
{
    private TransactionRepository $transactionRepository;
    private const ITEMS_PER_PAGE = 10; // Changé de 15 à 10 transactions par page

    public function __construct()
    {
        parent::__construct();
        $this->transactionRepository = App::getDependency('transactionRepository');
    }

    public function index()
    {
        $this->layout = 'base.solde.html.layout.php';
        
        // Valider et sécuriser la page
        $page = max(1, filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT));
        $dateFilter = $_GET['date'] ?? '';
        $typeFilter = $_GET['type'] ?? '';
        
        // Convertir le format de date si nécessaire
        if (!empty($dateFilter)) {
            $date = \DateTime::createFromFormat('d/m/Y', $dateFilter);
            if ($date) {
                $dateFilter = $date->format('Y-m-d');
            }
        }
        
        $offset = max(0, ($page - 1) * self::ITEMS_PER_PAGE);
        
        $transactions = $this->transactionRepository->findAllWithFilters(
            $dateFilter,
            $typeFilter,
            self::ITEMS_PER_PAGE,
            $offset
        );
        
        $totalTransactions = $this->transactionRepository->countAllWithFilters(
            $dateFilter,
            $typeFilter
        );
        
        $totalPages = max(1, ceil($totalTransactions / self::ITEMS_PER_PAGE));
        
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        
        return $this->renderHtml('listetransaction', [
            'transactions' => $transactions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'dateFilter' => $_GET['date'] ?? '',
            'typeFilter' => $typeFilter
        ]);
    }

    // Required abstract methods implementation
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}