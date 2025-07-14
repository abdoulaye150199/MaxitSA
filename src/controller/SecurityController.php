<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Session;
use App\Core\Validator;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    private UserRepository $userRepository;
    protected Session $session; 

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->session = App::getInstance()->getDependency('session');
    }

    public function login()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = Validator::validateLogin($_POST);

            if (empty($errors)) {
                $authenticatedUser = $this->userRepository->findByCode($_POST['code']);
                
                if ($authenticatedUser) {
                    $this->session->set('user_id', $authenticatedUser->getId());
                    $this->session->set('user', [
                        'id' => $authenticatedUser->getId(),
                        'nom' => $authenticatedUser->getNom(),
                        'prenom' => $authenticatedUser->getPrenom(),
                        'numero' => $authenticatedUser->getNumero(),
                        'type' => $authenticatedUser->getTypeUserValue()
                    ]);

                    if ($authenticatedUser->getTypeUserValue() === 'serviceClient') {
                        $this->redirect('/service-commercial');
                    } else {
                        $this->redirect('/accueil');
                    }
                    return;
                }
                $error = "Code secret incorrect";
            }
            
            $this->layout = 'base.login.html.layout.php';
            return $this->renderView('login', [
                'error' => $error,
                'errors' => $errors ?? []
            ]);
        }

        $this->layout = 'base.login.html.layout.php';
        return $this->renderView('login', ['errors' => []]);
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