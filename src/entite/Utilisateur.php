<?php
namespace App\Entite;

use App\Entite\TypeUser;
use App\Entite\Compte;
use App\Entite\Transaction;

class Utilisateur
{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $numero;
    private string $adresse;
    private ?string $photoCNI_recto = null;
    private ?string $photoCNI_verso = null;
    private ?string $code_secret = null;
    private ?string $numero_identite = null;
    private TypeUser $type_user;

    private array $comptes = [];

    private array $transactions = [];

    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        string $numero,
        string $adresse,
        TypeUser $type_user,
        ?string $photoCNI_recto = null,
        ?string $photoCNI_verso = null,
        ?string $code_secret = null,
        ?string $numero_identite = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numero = $numero;
        $this->adresse = $adresse;
        $this->type_user = $type_user;
        $this->photoCNI_recto = $photoCNI_recto;
        $this->photoCNI_verso = $photoCNI_verso;
        $this->code_secret = $code_secret;
        $this->numero_identite = $numero_identite;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }

    public function getNumero(): string { return $this->numero; }
    public function setNumero(string $numero): void { $this->numero = $numero; }

    public function getAdresse(): string { return $this->adresse; }
    public function setAdresse(string $adresse): void { $this->adresse = $adresse; }

    public function getPhotoCNIrecto(): ?string { return $this->photoCNI_recto; }
    public function setPhotoCNIrecto(?string $photo): void { $this->photoCNI_recto = $photo; }

    public function getPhotoCNIverso(): ?string { return $this->photoCNI_verso; }
    public function setPhotoCNIverso(?string $photo): void { $this->photoCNI_verso = $photo; }

    public function getCodeSecret(): ?string { return $this->code_secret; }
    public function setCodeSecret(?string $code): void { $this->code_secret = $code; }

    public function getNumeroIdentite(): ?string { return $this->numero_identite; }
    public function setNumeroIdentite(?string $num): void { $this->numero_identite = $num; }

    public function getTypeUser(): TypeUser { return $this->type_user; }
    public function setTypeUser(TypeUser $type): void { $this->type_user = $type; }

    public function getComptes(): array { return $this->comptes; }
    public function setComptes(array $comptes): void { $this->comptes = $comptes; }

    public function getTransactions(): array { return $this->transactions; }
    public function setTransactions(array $transactions): void { $this->transactions = $transactions; }

    public static function fromArray(array $data): self
    {
        $typeUser = TypeUser::from($data['type_user'] ?? 'client');

        return new self(
            $data['id'] ?? 0,
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['numero'] ?? '',
            $data['adresse'] ?? '',
            $typeUser,
            $data['photo_identite_recto'] ?? null,
            $data['photo_identite_verso'] ?? null,
            $data['code'] ?? null,
            $data['numero_carte_identite'] ?? null
        );
    }
}
