<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/env.php';

use App\Core\Database;

function insertDefaultData(PDO $pdo): void {
    try {
        $pdo->beginTransaction();

        // Vérification et création de la table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS type_transaction (
                id_type SERIAL PRIMARY KEY,
                libelle VARCHAR(50) NOT NULL UNIQUE
            )
        ");

        // Nettoyage des données existantes
        $pdo->exec("TRUNCATE type_transaction RESTART IDENTITY CASCADE");

        // Insertion des types de transaction avec la bonne syntaxe PostgreSQL
        $typeTransactions = ['DEPOT', 'RETRAIT', 'TRANSFERT'];
        
        // Utilisation de la syntaxe correcte pour PostgreSQL
        foreach ($typeTransactions as $type) {
            $stmt = $pdo->prepare("INSERT INTO type_transaction (libelle) VALUES (:libelle)");
            $stmt->bindValue(':libelle', $type, PDO::PARAM_STR);
            $stmt->execute();
        }

        $pdo->commit();
        echo "✅ Types de transaction insérés avec succès.\n";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Erreur: " . $e->getMessage() . "\n";
        throw $e;
    }
}

function insertMockData(PDO $pdo): void {
    try {
        $pdo->beginTransaction();

        // Drop and recreate tables to ensure clean state
        $pdo->exec("TRUNCATE users, compte, transaction CASCADE");

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

        // Insérer les utilisateurs
        $userIds = [];
        foreach ($users as $user) {
            $stmt = $pdo->prepare("
                INSERT INTO users 
                (code, nom, prenom, numero, adresse, type_user, photo_identite_recto, photo_identite_verso, numero_carte_identite) 
                VALUES 
                (:code, :nom, :prenom, :numero, :adresse, :type_user, :photo_identite_recto, :photo_identite_verso, :numero_carte_identite)
                RETURNING id
            ");
            
            $stmt->execute([
                ':code' => $user['code'],
                ':nom' => $user['nom'],
                ':prenom' => $user['prenom'],
                ':numero' => $user['numero'],
                ':adresse' => $user['adresse'],
                ':type_user' => $user['type_user'],
                ':photo_identite_recto' => $user['photo_identite_recto'],
                ':photo_identite_verso' => $user['photo_identite_verso'],
                ':numero_carte_identite' => $user['numero_carte_identite']
            ]);
            
            $userIds[] = $stmt->fetchColumn();
        }

        // Création des comptes
        $comptes = [
            [
                'numero' => 'P-0001',
                'numero_telephone' => '772917770',
                'solde' => 100000,
                'id_client' => $userIds[0],
                'type_compte' => 'Principal',
                'est_principal' => 't'  // PostgreSQL boolean as string
            ],
            [
                'numero' => 'S-0001', 
                'numero_telephone' => '776444556',
                'solde' => 50000,
                'id_client' => $userIds[1],
                'type_compte' => 'Secondaire',
                'est_principal' => 'f'  // PostgreSQL boolean as string
            ]
        ];

        // Insérer les comptes
        $compteIds = [];
        foreach ($comptes as $compte) {
            $stmt = $pdo->prepare("
                INSERT INTO compte 
                (numero, numero_telephone, solde, id_client, type_compte, est_principal) 
                VALUES 
                (:numero, :numero_telephone, :solde, :id_client, :type_compte, :est_principal)
                RETURNING id
            ");
            
            $stmt->execute([
                ':numero' => $compte['numero'],
                ':numero_telephone' => $compte['numero_telephone'],
                ':solde' => $compte['solde'],
                ':id_client' => $compte['id_client'],
                ':type_compte' => $compte['type_compte'],
                ':est_principal' => $compte['est_principal']
            ]);
            
            $compteIds[] = $stmt->fetchColumn();
        }

        // Création des transactions
        $transactions = [
            [
                'montant' => 100000,
                'compte_id' => $compteIds[0],
                'type_transaction' => 'DEPOT',
                'reference' => 'DEP' . time() . '1',
                'description' => 'Dépôt initial',
                'compte_destinataire' => null,  // Added missing parameter
                'statut' => 'Réussie'  // Added missing parameter
            ],
            [
                'montant' => 50000,
                'compte_id' => $compteIds[1],
                'type_transaction' => 'TRANSFERT',
                'reference' => 'TR' . time() . '1',
                'description' => 'Transfert vers compte secondaire',
                'compte_destinataire' => 'S-0001',
                'statut' => 'Réussie'  // Added missing parameter
            ]
        ];

        // Insérer les transactions
        foreach ($transactions as $transaction) {
            $stmt = $pdo->prepare("
                INSERT INTO transaction 
                (montant, compte_id, type_transaction, reference, description, compte_destinataire, statut) 
                VALUES 
                (:montant, :compte_id, :type_transaction, :reference, :description, :compte_destinataire, :statut)
            ");
            
            $stmt->execute($transaction);
        }

        $pdo->commit();
        echo "✅ Données de test insérées avec succès!\n";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Erreur: " . $e->getMessage() . "\n";
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
