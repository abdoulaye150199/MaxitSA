<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\UserRepository;
use App\Entite\Utilisateur;
use App\Entite\TypeUser;
use App\Core\Validator;

class UserController extends AbstractController
{
    public function store()
    {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyer les erreurs précédentes
            Validator::clearErrors();
            
            // Récupérer les données
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $telephone = $_POST['telephone'] ?? '';
            $adresse = $_POST['adresse'] ?? '';
            $numero_carte_identite = $_POST['numero_carte_identite'] ?? '';

            // Validation
            Validator::validate('required', $nom, 'nom', 'Le nom est obligatoire.');
            Validator::validate('required', $prenom, 'prenom', 'Le prénom est obligatoire.');
            Validator::validate('required', $telephone, 'telephone', 'Le téléphone est obligatoire.');
            Validator::validate('telephoneValide', $telephone, 'telephone');
            Validator::validate('required', $adresse, 'adresse', 'L\'adresse est obligatoire.');
            Validator::validate('required', $numero_carte_identite, 'numero_carte_identite', 'Le numéro de carte d\'identité est obligatoire.');
            Validator::validate('CNIValide', $numero_carte_identite, 'numero_carte_identite');

            $errors = Validator::getErrors();

            if (Validator::isValid()) {
                $_SESSION['register_data'] = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'telephone' => $telephone,
                    'adresse' => $adresse,
                    'numero_carte_identite' => $numero_carte_identite,
                    'photo_identite_recto' => $_FILES['photo_identite_recto'] ?? null,
                    'photo_identite_verso' => $_FILES['photo_identite_verso'] ?? null,
                ];
                header('Location: /code-secret');
                exit;
            }
        }

        $this->layout = 'base.login.html.layout.php';
        $this->renderView('sign', ['errors' => $errors]);
    }

    public function codeSecret()
    {
        $this->layout = 'base.login.html.layout.php';
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyer les erreurs précédentes
            Validator::clearErrors();
            
            $registerData = $_SESSION['register_data'] ?? null;
            $codeSecret = $_POST['code_secret'] ?? '';

            if (!$registerData) {
                header('Location: /sign');
                exit;
            }

            // Validation
            Validator::validate('required', $codeSecret, 'code_secret', 'Le code secret est obligatoire.');
            Validator::validate('codeSecret', $codeSecret, 'code_secret');

            $errors = Validator::getErrors();

            if (Validator::isValid()) {
                // Gère l'upload des fichiers ici si besoin
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
                $result = $repo->insert($user);

                if ($result) {
                    unset($_SESSION['register_data']);
                    header('Location: /login');
                    exit;
                } else {
                    $errors['general'] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                }
            }
        }

        $this->renderView('code_secret', ['errors' => $errors]);
    }

    public function index()
    {
        $this->layout = 'base.solde.html.layout.php';
        $repo = new \App\Repository\TransactionRepository();
        $transactions = $repo->getLastTransactions(10); // À créer si besoin
        $this->renderView('accueil', ['transactions' => $transactions]);
    }
    public function create() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}