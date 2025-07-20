<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/env.php';

use App\Core\Database;

function insertDefaultData(PDO $pdo): void {
    try {
        $pdo->beginTransaction();

        // Insertion types de transaction
        $typeTransactions = ['DEPOT', 'RETRAIT', 'TRANSFERT'];
        foreach ($typeTransactions as $type) {
            $stmt = $pdo->prepare("INSERT INTO type_transaction (libelle) VALUES (?) ON DUPLICATE KEY UPDATE libelle = VALUES(libelle)");
            $stmt->execute([$type]);
        }

        $pdo->commit();
        echo "✅ Données par défaut insérées.\n";

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function insertMockData(PDO $pdo): void {
    try {
        $pdo->beginTransaction();

        // Création des utilisateurs
        $users = [
            [
                'code' => password_hash('0000', PASSWORD_DEFAULT),
                'nom' => 'Service',
                'prenom' => 'Commercial',
                'numero' => '772917770',
                'adresse' => 'Dakar',
                'type_user' => 'serviceClient',
                'photo_identite_recto' => null,
                'photo_identite_verso' => null,
                'numero_carte_identite' => null
            ],
            [
                'code' => password_hash('1234', PASSWORD_DEFAULT),
                'nom' => 'Abdoulaye',
                'prenom' => 'Diallo',
                'numero' => '776444556',
                'adresse' => 'Dakar',
                'type_user' => 'client',
                'photo_identite_recto' => 'photo_recto_1.jpg',
                'photo_identite_verso' => 'photo_verso_1.jpg',
                'numero_carte_identite' => '12345678'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO users (code, nom, prenom, numero, adresse, type_user, photo_identite_recto, photo_identite_verso, numero_carte_identite) VALUES (:code, :nom, :prenom, :numero, :adresse, :type_user, :photo_identite_recto, :photo_identite_verso, :numero_carte_identite)");
        
        foreach ($users as $user) {
            $stmt->execute($user);
        }

        // Création des comptes
        $comptes = [
            [
                'numero' => 'P-0001',
                'numero_telephone' => '772917770',
                'solde' => 100000,
                'user_id' => 1,
                'type_compte' => 'Principal',
                'est_principal' => true
            ],
            [
                'numero' => 'S-0001',
                'numero_telephone' => '776444556',
                'solde' => 50000,
                'user_id' => 2,
                'type_compte' => 'Secondaire',
                'est_principal' => false
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO compte (numero, numero_telephone, solde, user_id, type_compte, est_principal) VALUES (:numero, :numero_telephone, :solde, :user_id, :type_compte, :est_principal)");

        foreach ($comptes as $compte) {
            $stmt->execute($compte);
        }

        // Création des transactions
        $transactions = [
            [
                'montant' => 100000,
                'compte_id' => 1,
                'type_transaction' => 'DEPOT',
                'reference' => 'DEP' . time() . '1',
                'description' => 'Dépôt initial'
            ],
            [
                'montant' => 50000,
                'compte_id' => 2,
                'type_transaction' => 'TRANSFERT',
                'reference' => 'TR' . time() . '1',
                'description' => 'Transfert vers compte secondaire',
                'compte_destinataire' => 'S-0001'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO transaction (montant, compte_id, type_transaction, reference, description, compte_destinataire, statut) VALUES (:montant, :compte_id, :type_transaction, :reference, :description, :compte_destinataire, 'Réussie')");

        foreach ($transactions as $transaction) {
            $stmt->execute($transaction);
        }

        $pdo->commit();
        echo "✅ Données de test insérées avec succès!\n";

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Exécution du seeder
try {
    echo "Connexion à la base de données...\n";
    $db = Database::getInstance();
    $pdo = $db->getPdo();
    echo "Connexion réussie.\n";
    
    // Insérer d'abord les données par défaut
    insertDefaultData($pdo);
    // Puis insérer les données de test
    insertMockData($pdo);
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
