<?php
namespace App\Core\Abstract;

use App\Core\App;
use App\Core\Session;
use App\Core\Validator;

abstract class AbstractController {

    protected string $layout = 'base.solde.html.layout.php';
    protected Session $session; 

    abstract public function index();
    abstract public function create();
    abstract public function store();
    abstract public function show();
    abstract public function edit();
    abstract public function destroy();

    public function __construct() 
    {
        // Fix: Assign the session to the protected property
        $this->session = App::getDependency('session');
    }

    protected function renderHtml(string $view, array $data = [], bool $useLayout = true) 
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../../../templates/views/' . $view . '.html.php';
        $content = ob_get_clean();

        if ($useLayout) {
            $ContentForLayout = $content;
            require __DIR__ . '/../../../templates/layouts/' . $this->layout;
        } else {
            echo $content;
        }
    }

    public function solde()
    {
        $this->renderHtml('solde');
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

   
}