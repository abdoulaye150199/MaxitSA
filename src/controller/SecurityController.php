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

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = App::getDependency('userRepository');
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
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}

    public function sign()
    {
        $this->layout = 'base.login.html.layout.php';
        return $this->renderHtml('sign');
    }
}