<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Repository\CompteRepository;

class CompteController extends AbstractController
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->compteRepository = App::getDependency('compteRepository');
    }

    // Méthode pour afficher la liste des comptes
    public function index()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return $this->redirect('/login');
        }

        $comptes = $this->compteRepository->findAllByUserId($user['id']);
        return $this->renderHtml('comptes/index', ['comptes' => $comptes]);
    }

    // Afficher le formulaire de création
    public function create()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return $this->redirect('/login');
        }
        return $this->renderHtml('compte');
    }

    // Traiter la création
    public function store()
    {
        // Cette méthode n'est pas utilisée car nous utilisons createCompte
        return $this->redirect('/compte/nouveau');
    }

    // Afficher un compte spécifique
    public function show()
    {
        // Cette méthode n'est pas utilisée pour le moment
        return $this->redirect('/accueil');
    }

    // Afficher le formulaire de modification
    public function edit()
    {
        // Cette méthode n'est pas utilisée pour le moment
        return $this->redirect('/accueil');
    }

    // Supprimer un compte
    public function destroy()
    {
        // Cette méthode n'est pas utilisée pour le moment
        return $this->redirect('/accueil');
    }

    // Méthodes spécifiques existantes
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
            $user = $this->session->get('user');
            if (!$user) {
                return $this->redirect('/login');
            }

            // Nettoyer le numéro de téléphone
            $numeroTelephone = str_replace(['+221', ' '], '', $_POST['numero_telephone'] ?? '');

            // Validation basique
            if (empty($numeroTelephone)) {
                return $this->renderHtml('compte', [
                    'errors' => ['numero_telephone' => 'Le numéro est requis']
                ]);
            }

            $data = [
                'numero_telephone' => $numeroTelephone,
                'montant_initial' => !empty($_POST['montant_initial']) ? (float)$_POST['montant_initial'] : 0,
                'id_client' => $user['id']
            ];

            if ($this->compteRepository->createSecondaryCompte($data)) {
                $this->session->set('flash_success', 'Compte secondaire créé avec succès');
                return $this->redirect('/accueil');
            }

            return $this->renderHtml('compte', [
                'errors' => ['general' => 'Erreur lors de la création du compte']
            ]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return $this->renderHtml('compte', [
                'errors' => ['general' => 'Une erreur inattendue est survenue']
            ]);
        }
    }
}