<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Session;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    private UserRepository $userRepository;
    protected Session $session; // Changed from private to protected to match parent class

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->session = App::getInstance()->getDependency('session');
    }

    public function login()
    {
        $error = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            
            // Validation du code
            if (empty($code)) {
                $errors['code'] = 'Le code secret est obligatoire.';
            } elseif (!preg_match('/^[0-9]{4}$/', $code)) {
                $errors['code'] = 'Le code secret doit contenir exactement 4 chiffres.';
            }

            if (empty($errors)) {
                $authenticatedUser = $this->userRepository->findByCode($code);
                
                if ($authenticatedUser) {
                    $this->session->set('user_id', $authenticatedUser->getId());
                    $this->session->set('user', [
                        'id' => $authenticatedUser->getId(),
                        'nom' => $authenticatedUser->getNom(),
                        'prenom' => $authenticatedUser->getPrenom(),
                        'numero' => $authenticatedUser->getNumero(),
                        'type' => $authenticatedUser->getTypeUserValue()
                    ]);

                    // Redirection selon le type d'utilisateur
                    if ($authenticatedUser->getTypeUserValue() === 'serviceCommercial') {
                        $this->redirect('/service-commercial');
                    } else {
                        $this->redirect('/accueil');
                    }
                    return;
                } else {
                    $error = "Code secret incorrect";
                }
            }
        }

        $this->layout = 'base.login.html.layout.php';
        $this->renderView('login', [
            'error' => $error,
            'errors' => $errors
        ]);   
    }

    public function logout()
    {
        $this->session->destroy();
        $this->redirect('/login');
    }

    public function index() {}
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}