<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;

class TransferController extends AbstractController
{
    public function index()
    {
        return $this->renderHtml('transfert');
    }

    // Méthodes requises par AbstractController
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}