<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Service\Interfaces\UserServiceInterface;

class SecurityController extends AbstractController
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userService->login($_POST['code'] ?? '');
            
            if ($result['success']) {
                $this->session->set('user_id', $result['user']['id']);
                $this->session->set('user', $result['user']);
                $this->redirect('/accueil');
                return;
            } else {
                $this->layout = 'base.login.html.layout.php';
                $this->renderView('login', [
                    'errors' => $result['errors']
                ]);
                return;
            }
        }

        $this->layout = 'base.login.html.layout.php';
        $this->renderView('login');
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