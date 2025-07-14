<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Database;
use App\Service\Interfaces\UserServiceInterface;
use App\Service\Interfaces\ValidationServiceInterface;
use App\Repository\TransactionRepository;
use App\Repository\CompteRepository; // Add this import
use App\Core\Validator;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private ValidationServiceInterface $validationService;
    private TransactionRepository $transactionRepository;
    private CompteRepository $compteRepository; // Add this property

    public function __construct(
        UserServiceInterface $userService,
        ValidationServiceInterface $validationService,
        Database $database
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->validationService = $validationService;
        $this->transactionRepository = new TransactionRepository($database->getPdo());
        $this->compteRepository = new CompteRepository($database); // Initialize CompteRepository
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
        $data = [
            'numero_telephone' => $_POST['numero_telephone'] ?? '',
            'code_secret' => $_POST['code_secret'] ?? '',
            'montant_initial' => (float)($_POST['montant_initial'] ?? 0)
        ];

        $errors = Validator::validateSecondaryAccount($data);

        if (!empty($errors)) {
            return $this->renderView('compte', ['errors' => $errors]);
        }

        // Récupérer l'ID du client connecté
        $user = $this->session->get('user');
        if (!$user || !isset($user['id'])) {
            $this->redirect('/login');
            return;
        }

        // Créer le compte secondaire
        $compteData = [
            'numero_telephone' => $data['numero_telephone'],
            'code_secret' => $data['code_secret'],
            'montant_initial' => $data['montant_initial'],
            'id_client' => $user['id']
        ];

        if ($this->compteRepository->createSecondaryCompte($compteData)) {
            $this->setFlash('success', 'Compte secondaire créé avec succès');
            $this->redirect('/accueil');
            return;
        }

        return $this->renderView('compte', [
            'errors' => ['general' => 'Erreur lors de la création du compte']
        ]);
    }
}