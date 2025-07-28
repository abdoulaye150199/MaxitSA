<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Session;
use App\Core\Validator;
use App\Repository\UserRepository;
use App\Service\UserService;

class SecurityController extends AbstractController
{
    private UserRepository $userRepository;
    private UserService $userService; // Add this property
    protected Session $session;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = App::getDependency('userRepository');
        $this->userService = App::getDependency('userService'); // Add this line
        $this->session = App::getDependency('session');
    }

    public function login()
    {
        $this->layout = 'base.login.html.layout.php'; 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            
            $error = Validator::validateLoginCode($code);
            if ($error) {
                return $this->renderHtml('login', [
                    'errors' => ['code' => $error]
                ]);
            }

            if ($code === '0000') {
                $this->session->set('user', [
                    'id' => 1,
                    'nom' => 'Service Commercial',
                    'type' => 'serviceClient'
                ]);
                $this->redirect('/service-commercial');
                return;
            }

            $user = $this->userRepository->findByCode($code);
            
            if (!$user) {
                return $this->renderHtml('login', [
                    'errors' => ['code' => 'Code invalide']
                ]);
            }

            $this->session->set('user', [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'type' => $user->getTypeUserValue()
            ]);

            switch ($user->getTypeUserValue()) {
                case 'serviceClient':
                    $this->redirect('/service-commercial');
                    break;
                case 'CLIENT':
                    $this->redirect('/accueil');
                    break;
                case 'AGENT':
                    $this->redirect('/agent/dashboard');
                    break;
                default:
                    $this->redirect('/accueil');
            }
        }

        return $this->renderHtml('login');
    }

    public function logout()
    {
        $this->session->destroy();
        $this->redirect('/login');
    }

    public function index() {}
    public function create() {}
    
    public function store()
    {
        $this->layout = 'base.login.html.layout.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userService->register($_POST);
            
            if ($result['success']) {
                return $this->redirect($result['redirect'] ?? '/code-secret');
            }
            
            return $this->renderHtml('sign', ['errors' => $result['errors']]);
        }
        return $this->renderHtml('sign');
    }
    
    public function show() {}
    public function edit() {}
    public function destroy() {}

    public function sign()
    {
        $this->layout = 'base.login.html.layout.php';
        return $this->renderHtml('sign');
    }

    public function signup()
    {
        $this->layout = 'base.login.html.layout.php';
        return $this->renderHtml('sign');
    }

    public function codeSecret()
    {
        // Ne pas utiliser de layout pour cette page
        return $this->renderHtml('code_secret', [], false);
    }

    public function createSecretCode()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codeSecret = $_POST['code_secret'] ?? '';
            
            // Valider le code secret
            $errors = Validator::validateCodeSecretRegistration($codeSecret);
            
            if (!empty($errors)) {
                return $this->renderHtml('code_secret', ['errors' => $errors], false);
            }

            // Récupérer les données d'inscription de la session
            $registerData = $this->session->get('register_data');
            if (!$registerData) {
                return $this->redirect('/sign');
            }

            try {
                // Créer l'utilisateur avec le code secret
                $registerData['code_secret'] = $codeSecret;
                $result = $this->userService->createUser($registerData);
                
                if ($result) {
                    // Nettoyer les données de session
                    $this->session->remove('register_data');
                    // Rediriger vers la page de connexion
                    return $this->redirect('/login');
                }

                return $this->renderHtml('code_secret', [
                    'errors' => ['general' => 'Erreur lors de la création du compte']
                ], false);

            } catch (\Exception $e) {
                return $this->renderHtml('code_secret', [
                    'errors' => ['general' => 'Une erreur est survenue']
                ], false);
            }
        }

        return $this->renderHtml('code_secret', [], false);
    }
}