<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;

class PaymentController extends AbstractController
{
    public function index()
    {
        $this->layout = 'base.solde.html.layout.php';
        return $this->renderView('paiement');
    }

    // Implémentation des méthodes abstraites requises
    public function create()
    {
        // À implémenter si nécessaire
    }

    public function store()
    {
        // À implémenter si nécessaire
    }

    public function show()
    {
        // À implémenter si nécessaire
    }

    public function edit()
    {
        // À implémenter si nécessaire
    }

    public function destroy()
    {
        // À implémenter si nécessaire
    }
}