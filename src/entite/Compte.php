<?php

namespace App\Entite;

class Compte
{
    private int $id;
    private string $numeroTelephone;
    private Utilisateur $user;
    private float $solde;
    private TypeCompte $typeCompte;
    private \DateTime $dateCreation;
    
    private array $transactions = [];
    private string $numeroCompte;

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getNumeroTelephone(): string { return $this->numeroTelephone; }
    public function setNumeroTelephone(string $num): void { $this->numeroTelephone = $num; }
    public function getUser(): Utilisateur { return $this->user; }
    public function setUser(Utilisateur $user): void { $this->user = $user; }
    public function getSolde(): float { return $this->solde; }
    public function setSolde(float $solde): void { $this->solde = $solde; }
    public function getTypeCompte(): TypeCompte { return $this->typeCompte; }
    public function setTypeCompte(TypeCompte $type): void { $this->typeCompte = $type; }
    public function getDateCreation(): \DateTime { return $this->dateCreation; }
    public function setDateCreation(\DateTime $date): void { $this->dateCreation = $date; }
    public function getTransactions(): array { return $this->transactions; }
    public function setTransactions(array $transactions): void { $this->transactions = $transactions; }
    public function getNumeroCompte(): string 
    { 
        return $this->numeroCompte; 
    }
    
    public function setNumeroCompte(string $numeroCompte): void 
    { 
        $this->numeroCompte = $numeroCompte; 
    }
}