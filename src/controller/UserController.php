<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Service\Interfaces\UserServiceInterface;
use App\Service\Interfaces\ValidationServiceInterface;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private ValidationServiceInterface $validationService;

    public function __construct(
        UserServiceInterface $userService,
        ValidationServiceInterface $validationService
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->validationService = $validationService;
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
        $this->layout = 'base.solde.html.layout.php';
        $this->renderView('accueil');
    }

    public function create() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}