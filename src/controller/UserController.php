<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Service\UserService;
use App\Repository\TransactionRepository;
use App\Repository\CompteRepository;
use App\Core\App;
use App\Core\Validator;

class UserController extends AbstractController
{
    private UserService $userService;
    private TransactionRepository $transactionRepository;
    private CompteRepository $compteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userService = App::getDependency('userService');
        $this->transactionRepository = App::getDependency('transactionRepository');
        $this->compteRepository = App::getDependency('compteRepository');
    }

    public function store()
    {
        $this->layout = 'base.login.html.layout.php'; // Définir explicitement le layout
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userService->register($_POST);
            
            if ($result['success']) {
                $this->redirect($result['redirect'] ?? '/code-secret');
                return;
            } else {
                return $this->renderHtml('sign', ['errors' => $result['errors']]);
            }
        }
        
        return $this->renderHtml('sign');
    }

    public function codeSecret()
    {
      
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registerData = $this->session->get('register_data');
            
            if (Validator::validateUserSession($registerData)) {
                $this->redirect('/sign');
                return;
            }

            $errors = Validator::validateCodeSecretRegistration($_POST['code_secret'] ?? '');
            if (!empty($errors)) {
                return $this->renderHtml('code_secret', ['errors' => $errors]);
            }

            $userData = array_merge($registerData, ['code' => $_POST['code_secret']]);

            $result = $this->userService->createUser($userData);

            if ($result) {
                $this->session->remove('register_data');
                $this->redirect('/login');
                return;
            }
            
            return $this->renderHtml('code_secret', [
                'errors' => ['general' => 'Erreur lors de la création du compte']
            ]);
        }

        $this->renderHtml('code_secret');
    }

    public function index()
    {
        $this->layout = 'base.solde.html.layout.php';
        $latestTransactions = $this->transactionRepository->findLatestTransactions(10);
        
        return $this->renderHtml('accueil', [
            'latestTransactions' => $latestTransactions
        ]);
    }

    public function create() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}

    public function nouveauCompte()
    {
        // Vérifier si l'utilisateur est connecté
        $user = $this->session->get('user');
        if (!$user) {
            return $this->redirect('/login');
        }

        // Définir le layout correct
        $this->layout = 'base.solde.html.layout.php';
        
        // Rendre la vue compte.html.php
        return $this->renderHtml('compte');
    }

    public function createCompte()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/compte/nouveau');
        }

        $user = $this->session->get('user');
        if (!Validator::validateUserExists($user)) {
            return $this->redirect('/login');
        }

        try {
            // Nettoyer le numéro de téléphone
            $numeroTelephone = str_replace(['+221', ' '], '', $_POST['numero_telephone'] ?? '');
            
            $data = [
                'numero_telephone' => $numeroTelephone,
                'code_secret' => $_POST['code_secret'] ?? '',
                'montant_initial' => !empty($_POST['montant_initial']) ? (float)$_POST['montant_initial'] : 0,
                'id_client' => $user['id']
            ];

            // Validation
            if (empty($data['code_secret']) || !preg_match('/^[0-9]{4}$/', $data['code_secret'])) {
                return $this->renderHtml('compte', [
                    'errors' => ['code_secret' => 'Le code secret doit contenir exactement 4 chiffres']
                ]);
            }

            // Vérifier si l'utilisateur a déjà un compte avec ce numéro
            if ($this->compteRepository->findByNumero($numeroTelephone)) {
                return $this->renderHtml('compte', [
                    'errors' => ['numero_telephone' => 'Ce numéro est déjà utilisé pour un compte']
                ]);
            }

            if ($this->compteRepository->createSecondaryCompte($data)) {
                return $this->redirect('/accueil');
            }

            return $this->renderHtml('compte', [
                'errors' => ['general' => 'Erreur lors de la création du compte']
            ]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return $this->renderHtml('compte', [
                'errors' => ['general' => 'Erreur lors de la création du compte']
            ]);
        }
    }

    public function createSecondaryAccount()
    {
        $data = [
            'numero_telephone' => $_POST['numero_telephone'],
            'code_secret' => $_POST['code_secret'],
            'montant_initial' => (float)($_POST['montant_initial'])
        ];

        // Validation
        $errors = Validator::validateSecondaryAccount($data);
        if (!empty($errors)) {
            return $this->renderHtml('compte', ['errors' => $errors]);
        }

        // Récupérer l'ID du client connecté
        $user = $this->session->get('user');
        
        // Créer le compte secondaire
        $compteData = [
            'numero_telephone' => $data['numero_telephone'],
            'code_secret' => $data['code_secret'],
            'montant_initial' => $data['montant_initial'],
            'id_client' => $user['id']
        ];

        if ($this->compteRepository->createSecondaryCompte($compteData)) {
            $this->redirect('/accueil');
        }
    }

    public function solde()
    {
        // Vérifier si l'utilisateur est connecté
        $user = $this->session->get('user');
        if (!$user) {
            return $this->redirect('/login');
        }

        $this->layout = 'base.solde.html.layout.php';
        
        // Récupérer le compte principal de l'utilisateur
        $compte = $this->compteRepository->findByUserId($user['id']);
        
        return $this->renderHtml('solde', [
            'compte' => $compte
        ]);
    }
}