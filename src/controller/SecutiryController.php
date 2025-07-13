<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App; // Ajout du bon namespace pour App
use App\Core\Session;
use App\Repository\UserRepository;
use App\Entite\Utilisateur;

class SecutiryController extends AbstractController {
    
    private UserRepository $userRepository;
    protected Session $session;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository(App::getInstance()->getDependency('database'));
        $this->session = App::getInstance()->getDependency('session');
    }

    public function login()
    {
        $error = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validate($_POST, [
                'code' => [
                    'required' => 'Le code secret est obligatoire.',
                    'codeSecret' => 'Le code secret doit contenir exactement 4 chiffres.'
                ]
            ]);

            if (empty($errors)) {
                $user = $this->userRepository->findByCode($_POST['code']); // Changé selectByCode en findByCode
                
                if ($user) {
                    $this->session->set('user_id', $user->getId());
                    $this->session->set('user', [
                        'id' => $user->getId(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'type' => $user->getTypeUserValue()
                    ]);

                    $this->session->set('code', $_POST['code']);
                    $this->redirect('/accueil');
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

    public function sign() {
        $this->layout = 'base.login.html.layout.php';
        $this->renderView('sign');
    }

    public function code_secret() {
        $this->renderView('code_secret');
    }

    public function index() {}
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}