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

    public function create()
    {
    }

    public function store()
    {
    }

    public function show()
    {
    }

    public function edit()
    {

    }

    public function destroy()
    {
    }
}