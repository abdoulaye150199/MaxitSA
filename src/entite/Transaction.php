<?php
namespace App\Entite;

class Transaction
{
    private int $id;
    private TypeTransaction $type;
    private Compte $compte;
    private float $montant;
    private \DateTime $date;

    public function __construct(
        int $id, 
        TypeTransaction $type, 
        Compte $compte, 
        float $montant, \DateTime $date)
    {
        $this->id = $id;
        $this->type = $type;
        $this->compte = $compte;
        $this->montant = $montant;
        $this->date = $date;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getType(): TypeTransaction { return $this->type; }
    public function setType(TypeTransaction $type): void { $this->type = $type; }
    public function getCompte(): Compte { return $this->compte; }
    public function setCompte(Compte $compte): void { $this->compte = $compte; }
    public function getMontant(): float { return $this->montant; }
    public function setMontant(float $montant): void { $this->montant = $montant; }
    public function getDate(): \DateTime { return $this->date; }
    public function setDate(\DateTime $date): void { $this->date = $date; }
}