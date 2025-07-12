<?php
namespace App\Core\Abstract;

use App\Core\App;

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
        $this->session = App::session();
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

    /**
     * Valider les données avec le service de validation
     */
    protected function validate(array $data, array $rules): array
    {
        $validator = App::validator();
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule => $message) {
                $validator::validate($rule, $value, $field, $message);
            }
        }

        return $validator::getErrors();
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}