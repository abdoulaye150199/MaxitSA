<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\TransactionRepository;
use App\Core\Database;

class TransactionController extends AbstractController
{
    private TransactionRepository $transactionRepository;
    private const ITEMS_PER_PAGE = 10;

    public function __construct(Database $database)
    {
        parent::__construct();
        $this->transactionRepository = new TransactionRepository($database->getPdo());
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        
        $transactions = $this->transactionRepository->findAllPaginated(self::ITEMS_PER_PAGE, $offset);
        $totalTransactions = $this->transactionRepository->countAll();
        $totalPages = ceil($totalTransactions / self::ITEMS_PER_PAGE);

        $this->layout = 'base.solde.html.layout.php';
        return $this->renderView('listetransaction', [
            'transactions' => $transactions,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    // Required abstract methods implementation
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}