<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;

class TransferController extends AbstractController
{
    public function index()
    {
        $this->layout = 'base.solde.html.layout.php';
        return $this->renderView('transfert');
    }

    // Méthodes requises par AbstractController
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}