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
            
            if (empty($code)) {
                $errors['code'] = 'Le code secret est obligatoire.';
            } elseif (!preg_match('/^[0-9]{4}$/', $code)) {
                $errors['code'] = 'Le code secret doit contenir exactement 4 chiffres.';
            }

            if (empty($errors)) {
                // Debug log
                error_log('Tentative de connexion avec le code: ' . $code);
                
                $user = $this->userRepository->findByCode($code);
                
                if ($user) {
                    // Debug log
                    error_log('Utilisateur trouvé - Type: ' . $user->getTypeUserValue());
                    
                    $userData = [
                        'id' => $user->getId(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'numero' => $user->getNumero(),
                        'type' => $user->getTypeUserValue()
                    ];
                    
                    $this->session->set('user', $userData);
                    error_log('Session user data: ' . print_r($userData, true));

                    if ($user->getTypeUserValue() === 'serviceClient') {
                        $this->redirect('/service-commercial');
                        return;
                    }
                    
                    $this->redirect('/accueil');
                    return;
                }
                
                // Debug log
                error_log('Aucun utilisateur trouvé pour le code: ' . $code);
                $error = "Code secret incorrect";
            }
        }

        $this->layout = 'base.login.html.layout.php';
        return $this->renderView('login', [
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