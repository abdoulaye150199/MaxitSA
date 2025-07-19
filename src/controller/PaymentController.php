<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;

class PaymentController extends AbstractController
{
    public function index()
    {
     
        return $this->renderHtml('paiement'); 
    }

    // Required abstract methods
    public function create() {}
    public function store() {}
    public function show() {}
    public function edit() {}
    public function destroy() {}
}