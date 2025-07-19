<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Validator;
use App\Repository\CompteRepository;

class CompteController extends AbstractController
{
    private CompteRepository $compteRepository;
    private Validator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->compteRepository = App::getDependency('compteRepository');
        $this->validator = App::getDependency('validator');
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
            $user = $this->session->get('user');
            if (!$user) {
                return $this->redirect('/login');
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->redirect('/compte/nouveau');
            }

            // Nettoyer les données
            $numeroTelephone = str_replace(['+221', ' '], '', $_POST['numero_telephone'] ?? '');
            $data = [
                'numero_telephone' => $numeroTelephone,
                'code_secret' => $_POST['code_secret'] ?? '',
                'montant_initial' => !empty($_POST['montant_initial']) ? (float)$_POST['montant_initial'] : 0,
                'id_client' => $user['id']
            ];

            // Validation centralisée
            $errors = Validator::validateCompteCreation($data);
            if (!empty($errors)) {
                return $this->renderHtml('compte', ['errors' => $errors]);
            }

            // Vérifier si le numéro existe déjà
            if ($this->compteRepository->findByNumero($numeroTelephone)) {
                return $this->renderHtml('compte', [
                    'errors' => ['numero_telephone' => 'Ce numéro est déjà utilisé pour un compte']
                ]);
            }

            // S'assurer que le montant initial est au moins 500 FCFA
            if ($data['montant_initial'] < 500) {
                return $this->renderHtml('compte', [
                    'errors' => ['montant_initial' => 'Le montant initial doit être d\'au moins 500 FCFA']
                ]);
            }

            // Créer le compte
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

    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}