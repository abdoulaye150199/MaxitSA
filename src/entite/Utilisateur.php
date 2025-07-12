<?php
namespace App\Entite;

class Utilisateur
{
    private int $id;
    private string $code;
    private string $nom;
    private string $prenom;
    private string $numero;
    private string $adresse;
    private string $type_user;
    private ?string $photo_identite_recto;
    private ?string $photo_identite_verso;
    private ?string $numero_carte_identite;

    public function __construct(
        int $id,
        string $code,
        string $nom,
        string $prenom,
        string $numero,
        string $adresse,
        string $type_user = 'client',
        ?string $photo_identite_recto = null,
        ?string $photo_identite_verso = null,
        ?string $numero_carte_identite = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numero = $numero;
        $this->adresse = $adresse;
        $this->type_user = $type_user;
        $this->photo_identite_recto = $photo_identite_recto;
        $this->photo_identite_verso = $photo_identite_verso;
        $this->numero_carte_identite = $numero_carte_identite;
    }

    // Getters et Setters
    public function getId(): int { return $this->id; }
    public function getCode(): string { return $this->code; }
    public function setCode(string $code): void { $this->code = $code; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function getNumero(): string { return $this->numero; }
    public function setNumero(string $numero): void { $this->numero = $numero; }
    public function getAdresse(): string { return $this->adresse; }
    public function setAdresse(string $adresse): void { $this->adresse = $adresse; }
    public function getTypeUser(): TypeUser 
    {
        return TypeUser::from($this->type_user);
    }

    public function setTypeUser(TypeUser|string $type): void 
    {
        $this->type_user = is_string($type) ? $type : $type->value;
    }

    // Add this method to get the raw string value
    public function getTypeUserValue(): string 
    {
        return $this->type_user;
    }
    public function getPhotoIdentiteRecto(): ?string { return $this->photo_identite_recto; }
    public function setPhotoIdentiteRecto(?string $photo): void { $this->photo_identite_recto = $photo; }
    public function getPhotoIdentiteVerso(): ?string { return $this->photo_identite_verso; }
    public function setPhotoIdentiteVerso(?string $photo): void { $this->photo_identite_verso = $photo; }
    public function getNumeroCarteIdentite(): ?string { return $this->numero_carte_identite; }
    public function setNumeroCarteIdentite(?string $numero): void { $this->numero_carte_identite = $numero; }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['code'] ?? '',
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['numero'] ?? '',
            $data['adresse'] ?? '',
            $data['type_user'] ?? 'client',
            $data['photo_identite_recto'] ?? null,
            $data['photo_identite_verso'] ?? null,
            $data['numero_carte_identite'] ?? null
        );
    }
}
