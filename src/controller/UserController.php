<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Database; // Ajout de l'import manquant
use App\Service\Interfaces\UserServiceInterface;
use App\Service\Interfaces\ValidationServiceInterface;
use App\Repository\TransactionRepository;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private ValidationServiceInterface $validationService;
    private $transactionRepository;

    public function __construct(
        UserServiceInterface $userService,
        ValidationServiceInterface $validationService,
        Database $database
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->validationService = $validationService;
        $this->transactionRepository = new TransactionRepository($database->getPdo());
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userService->register($_POST);
            
            if ($result['success']) {
                $this->redirect($result['redirect'] ?? '/code-secret'); // Add fallback redirect
                return;
            } else {
                $this->layout = 'base.login.html.layout.php';
                $this->renderView('sign', ['errors' => $result['errors']]);
                return;
            }
        }

        $this->layout = 'base.login.html.layout.php';
        $this->renderView('sign');
    }

    public function codeSecret()
    {
        $this->layout = 'base.login.html.layout.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registerData = $this->session->get('register_data');
            
            if (!$registerData) {
                $this->redirect('/sign');
                return;
            }

            if (empty($_POST['code_secret'])) {
                return $this->renderView('code_secret', [
                    'errors' => ['code_secret' => 'Le code secret est requis']
                ]);
            }

            if (!preg_match('/^[0-9]{4}$/', $_POST['code_secret'])) {
                return $this->renderView('code_secret', [
                    'errors' => ['code_secret' => 'Le code doit contenir exactement 4 chiffres']
                ]);
            }

            // Fusionner les données d'inscription avec le code secret
            $userData = array_merge($registerData, ['code' => $_POST['code_secret']]);
            
            // Créer l'utilisateur
            $result = $this->userService->createUser($userData);

            if ($result) {
                $this->session->remove('register_data');
                $this->redirect('/login');
                return;
            }
            
            return $this->renderView('code_secret', [
                'errors' => ['general' => 'Erreur lors de la création du compte']
            ]);
        }

        $this->renderView('code_secret');
    }

    public function index()
    {
        $latestTransactions = $this->transactionRepository->findLatestTransactions(10);
        
        $this->layout = 'base.solde.html.layout.php';
        return $this->renderView('accueil', [
            'latestTransactions' => $latestTransactions
        ]);
    }

    public function create() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}

    public function nouveauCompte()
    {
        $this->layout = 'base.solde.html.layout.php';
        return $this->renderView('compte');
    }

    public function createCompte()
    {
        $numero = $_POST['numero_telephone'] ?? '';
        $code = $_POST['code_secret'] ?? '';
        $montant = $_POST['montant_initial'] ?? '';
        
        $errors = [];
        
        // Validation
        if (empty($numero) || !preg_match('/^[0-9]{9}$/', $numero)) {
            $errors['numero_telephone'] = 'Numéro de téléphone invalide';
        }
        
        if (empty($code) || !preg_match('/^[0-9]{4}$/', $code)) {
            $errors['code_secret'] = 'Le code secret doit contenir 4 chiffres';
        }
        
        if (empty($montant) || $montant < 500) {
            $errors['montant_initial'] = 'Le montant initial doit être d\'au moins 500 FCFA';
        }
        
        if (!empty($errors)) {
            return $this->renderView('compte', ['errors' => $errors]);
        }
        
        // TODO: Créer le compte secondaire dans la base de données
        
        // Rediriger vers la page d'accueil avec un message de succès
        $this->redirect('/accueil');
    }
}