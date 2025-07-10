<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\UserRepository;
use App\Entite\Utilisateur;
use App\Entite\TypeUser;
class UserController extends AbstractController
{
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['register_data'] = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'numero_carte_identite' => $_POST['numero_carte_identite'] ?? '',
                'photo_identite_recto' => $_FILES['photo_identite_recto'] ?? null,
                'photo_identite_verso' => $_FILES['photo_identite_verso'] ?? null,
            ];
            header('Location: /code-secret');
            exit;
        }
        $this->layout = 'base.login.html.layout.php';
        $this->renderView('sign');
    }

    public function codeSecret()
    {
        $this->layout = 'base.login.html.layout.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registerData = $_SESSION['register_data'] ?? null;
            $codeSecret = $_POST['code_secret'] ?? null;

            if ($registerData && $codeSecret) {
                // Gère l’upload des fichiers ici si besoin
                // move_uploaded_file($registerData['photo_identite_recto']['tmp_name'], ...);

                $user = new Utilisateur(
                    0,
                    $registerData['nom'],
                    $registerData['prenom'],
                    $registerData['telephone'],
                    $registerData['adresse'],
                    TypeUser::CLIENT,
                    $registerData['photo_identite_recto']['name'] ?? null,
                    $registerData['photo_identite_verso']['name'] ?? null,
                    $codeSecret,
                    $registerData['numero_carte_identite']
                );

                $repo = new \App\Repository\UserRepository();
                $repo->insert($user);

                unset($_SESSION['register_data']);


                header('Location: /login');
                exit;
            }
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