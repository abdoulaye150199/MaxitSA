<?php
namespace App\Core\Abstract;

use App\Core\Abstract\Session;

abstract class AbstractController {

    protected string $layout = 'base.solde.html.layout.php';
    protected $session;

    abstract public function index();
    abstract public function create();
    abstract public function store();
    abstract public function show();
    abstract public function edit();
    abstract public function destroy();

    public function __construct() 
    {
        $this->session = Session::getInstance();
    }

    protected function renderView(string $view, array $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../../../templates/views/' . $view . '.html.php';
        $ContentForLayout = ob_get_clean();
        require __DIR__ . '/../../../templates/layouts/' . $this->layout;
    }

    public function solde()
    {
        $this->renderView('solde');
    }
}