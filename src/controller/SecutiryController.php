<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Validator;

class SecutiryController extends AbstractController {
    public function login()
    {
        $error = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyer les erreurs précédentes
            Validator::clearErrors();
            
            $code = $_POST['code'] ?? '';

            // Validation
            Validator::validate('required', $code, 'code', 'Le code secret est obligatoire.');
            Validator::validate('codeSecret', $code, 'code', 'Le code secret doit contenir exactement 4 chiffres.');

            $errors = Validator::getErrors();

            if (Validator::isValid()) {
                $repo = new \App\Repository\UserRepository();
                $user = $repo->selectByCode($code);
                
                if ($user) {
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'type' => $user->getTypeUser()->value
                    ];

                    $this->session->set('code', $code);
                    header('Location: /accueil');
                    exit;
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