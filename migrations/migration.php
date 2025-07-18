<?php

namespace App\Migrations;

use App\Core\Database;

class Migration
{
    protected \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function createDatabase(): void
    {
        $dbname = $_ENV['DB_NAME'] ?? 'MaxitSA';
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
        $this->pdo->exec("USE $dbname");
    }

    public function migrate(): void
    {
        $this->createTypeTransactionTable();
        $this->createTypeCompteTable();
        $this->createUsersTable();
        $this->createCompteTable();
        $this->createTransactionTable();
        $this->insertDefaultData();
    }

    private function createTypeTransactionTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS type_transaction (
            id_type SERIAL PRIMARY KEY,
            libelle VARCHAR(50) NOT NULL UNIQUE
        )";
        $this->pdo->exec($sql);
    }

    private function createTypeCompteTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS type_compte (
            id_type SERIAL PRIMARY KEY,
            libelle VARCHAR(50) NOT NULL UNIQUE
        )";
        $this->pdo->exec($sql);
    }

    private function createUsersTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            code VARCHAR(255) NOT NULL,
            nom VARCHAR(100) NOT NULL,
            prenom VARCHAR(100) NOT NULL,
            numero VARCHAR(20) NOT NULL UNIQUE,
            adresse TEXT NOT NULL,
            type_user VARCHAR(50) NOT NULL DEFAULT 'CLIENT',
            photo_identite_recto VARCHAR(255),
            photo_identite_verso VARCHAR(255),
            numero_carte_identite VARCHAR(50),
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    private function createCompteTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS compte (
            id SERIAL PRIMARY KEY,
            numero_compte VARCHAR(10) NOT NULL UNIQUE,  /* Format P-0001 ou S-0001 */
            numero_telephone VARCHAR(20) NOT NULL,      /* Numéro de téléphone */
            code_secret VARCHAR(255) NOT NULL,
            solde DECIMAL(15,2) DEFAULT 0,
            est_principal BOOLEAN DEFAULT false,
            id_client INTEGER NOT NULL,
            id_type_compte INTEGER NOT NULL,
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_client) REFERENCES users(id),
            FOREIGN KEY (id_type_compte) REFERENCES type_compte(id_type)
        )";
        $this->pdo->exec($sql);
    }

    private function createTransactionTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS transaction (
            id SERIAL PRIMARY KEY,
            montant DECIMAL(15,2) NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            compte_id INTEGER NOT NULL,
            type_transaction INTEGER NOT NULL,
            FOREIGN KEY (compte_id) REFERENCES compte(id),
            FOREIGN KEY (type_transaction) REFERENCES type_transaction(id_type)
        )";
        $this->pdo->exec($sql);
    }

    private function insertDefaultData(): void
    {
       
        $typeTransactions = [
            ['DEPOT'],
            ['RETRAIT'],
            ['PAIEMENT']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO type_transaction (libelle) VALUES (?) ON CONFLICT (libelle) DO NOTHING");
        foreach ($typeTransactions as $type) {
            $stmt->execute($type);
        }


        $typeComptes = [
            ['PRINCIPAL'],
            ['SECONDAIRE']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO type_compte (libelle) VALUES (?) ON CONFLICT (libelle) DO NOTHING");
        foreach ($typeComptes as $type) {
            $stmt->execute($type);
        }
    }
}

