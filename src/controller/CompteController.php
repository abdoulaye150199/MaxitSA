<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Validator;
use App\Repository\CompteRepository;

class CompteController extends AbstractController
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->compteRepository = App::getDependency('compteRepository');
    }

    public function nouveauCompte()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return $this->redirect('/login');
        }

        $this->layout = 'base.solde.html.layout.php';
        return $this->renderHtml('compte');
    }

    public function createCompte()
    {
        try {
            error_log("=== Début création compte secondaire ===");
            
            $user = $this->session->get('user');
            if (!$user) {
                error_log("Erreur: Utilisateur non connecté");
                return $this->redirect('/login');
            }
            error_log("Utilisateur connecté ID: " . $user['id']);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("Erreur: Méthode non autorisée " . $_SERVER['REQUEST_METHOD']);
                return $this->redirect('/compte/nouveau');
            }

            // Nettoyer et valider les données
            $numeroTelephone = str_replace(['+221', ' '], '', $_POST['numero_telephone'] ?? '');
            error_log("Données POST brutes: " . print_r($_POST, true));
            
            $data = [
                'numero_telephone' => $numeroTelephone,
                'code_secret' => $_POST['code_secret'] ?? '',
                'montant_initial' => !empty($_POST['montant_initial']) ? (float)$_POST['montant_initial'] : 0,
                'id_client' => $user['id']
            ];
            error_log("Données traitées: " . print_r($data, true));

            // Validation du numéro de téléphone
            if (empty($data['numero_telephone'])) {
                error_log("Erreur: Numéro de téléphone vide");
                return $this->renderHtml('compte', [
                    'errors' => ['numero_telephone' => 'Le numéro de téléphone est obligatoire']
                ]);
            }

            if (!preg_match('/^(7[056789])[0-9]{7}$/', $data['numero_telephone'])) {
                error_log("Erreur: Format numéro invalide: " . $data['numero_telephone']);
                return $this->renderHtml('compte', [
                    'errors' => ['numero_telephone' => 'Format de numéro invalide']
                ]);
            }

            // Validation du code secret
            if (empty($data['code_secret'])) {
                error_log("Erreur: Code secret vide");
                return $this->renderHtml('compte', [
                    'errors' => ['code_secret' => 'Le code secret est obligatoire']
                ]);
            }

            if (!preg_match('/^[0-9]{4}$/', $data['code_secret'])) {
                error_log("Erreur: Format code secret invalide");
                return $this->renderHtml('compte', [
                    'errors' => ['code_secret' => 'Le code secret doit contenir exactement 4 chiffres']
                ]);
            }

            try {
                // Vérification du compte existant
                if ($this->compteRepository->findByNumero($numeroTelephone)) {
                    error_log("Erreur: Compte existant pour le numéro: " . $numeroTelephone);
                    return $this->renderHtml('compte', [
                        'errors' => ['numero_telephone' => 'Ce numéro est déjà utilisé pour un compte']
                    ]);
                }

                // Création du compte
                if ($this->compteRepository->createSecondaryCompte($data)) {
                    error_log("Succès: Compte créé pour le numéro: " . $numeroTelephone);
                    $this->session->set('flash_success', 'Compte secondaire créé avec succès');
                    return $this->redirect('/accueil');
                }

                error_log("Erreur: Échec création compte pour: " . $numeroTelephone);
                return $this->renderHtml('compte', [
                    'errors' => ['general' => 'Erreur lors de la création du compte']
                ]);

            } catch (\PDOException $e) {
                error_log("Erreur PDO: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                return $this->renderHtml('compte', [
                    'errors' => ['general' => 'Erreur de base de données']
                ]);
            }

        } catch (\Exception $e) {
            error_log("Erreur générale: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return $this->renderHtml('compte', [
                'errors' => ['general' => 'Une erreur inattendue est survenue']
            ]);
        }
    }

    public function index()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return $this->redirect('/login');
        }

        $comptes = $this->compteRepository->findAllByUserId($user['id']);
        
        $this->layout = 'base.solde.html.layout.php';
        return $this->renderHtml('comptes/index', [
            'comptes' => $comptes,
            'success' => $this->session->get('flash_success'),
            'error' => $this->session->get('flash_error')
        ]);
    }

    // Méthodes requises par AbstractController
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}