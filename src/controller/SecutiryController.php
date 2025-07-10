<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;

class SecutiryController extends AbstractController {
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? null;
            if ($code) {
                $repo = new \App\Repository\UserRepository();
                $user = $repo->selectByCode($code);
                if ($user) {
                    // Met l'utilisateur en session
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'type' => $user->getTypeUser()->value
                    ];
                    header('Location: /accueil');
                    exit;
                } else {
                    $error = "Code secret incorrect";
                }
            }
        }
        $this->layout = 'base.login.html.layout.php';
        $this->renderView('login', isset($error) ? ['error' => $error] : []);   
    }

    public function sign() {
        $this->layout = 'base.login.html.layout.php';
        $this->renderView('sign');
    }

    public function code_secret() {
        $this->renderView('code_secret');
    }

    // Implémentation des méthodes abstraites
    public function index() {}
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}