<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/env.php';

use App\Core\Database;

function insertDefaultData(PDO $pdo, string $driver): void {
    echo "\n=== Insertion des données par défaut ===\n";

    $typeTransactions = [['DEPOT'], ['RETRAIT'], ['PAIEMENT']];
    $typeComptes = [['PRINCIPAL'], ['SECONDAIRE']];

    try {
        $pdo->beginTransaction();

        // Insertion des types de transaction
        if ($driver === 'pgsql') {
            $stmt = $pdo->prepare("INSERT INTO type_transaction (libelle) VALUES (?) ON CONFLICT (libelle) DO NOTHING");
        } else {
            $stmt = $pdo->prepare("INSERT IGNORE INTO type_transaction (libelle) VALUES (?)");
        }
        
        foreach ($typeTransactions as $type) {
            $stmt->execute($type);
            echo "✅ Type de transaction créé: {$type[0]}\n";
        }

        // Insertion des types de compte
        if ($driver === 'pgsql') {
            $stmt = $pdo->prepare("INSERT INTO type_compte (libelle) VALUES (?) ON CONFLICT (libelle) DO NOTHING");
        } else {
            $stmt = $pdo->prepare("INSERT IGNORE INTO type_compte (libelle) VALUES (?)");
        }
        
        foreach ($typeComptes as $type) {
            $stmt->execute($type);
            echo "✅ Type de compte créé: {$type[0]}\n";
        }

        $pdo->commit();
        echo "✅ Données par défaut insérées.\n\n";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Erreur lors de l'insertion des données par défaut : " . $e->getMessage() . "\n";
        throw $e;
    }
}

function insertMockData(PDO $pdo): void {
    try {
        $pdo->beginTransaction();
        
        echo "\n=== Insertion des données de test ===\n";

        // 1. Ajouter des utilisateurs
        $users = [
            [
                password_hash('0000', PASSWORD_DEFAULT),  // code fixe pour le service commercial
                'Service',                                // nom  
                'Commercial',                            // prenom
                '772917770',                            // numero
                'Dakar',                                // adresse
                'serviceClient',                        // type_user
                null,                                   // photo_identite_recto
                null,                                   // photo_identite_verso
                null                                    // numero_carte_identite
            ],
            [
                '$2y$10$jo2e01TSO21tuKIbo4jzXuDszJJ9e2re6HD7iN9lbyXswsnORXoPu',                  // code
                'Abdoulaye Diallo',                     // nom
                'Abdoulaye',                           // prenom
                '776444556',                           // numero
                '10500',                               // adresse
                'client',                              // type_user
                'photo_identite_recto_687522865684.png',  // photo_identite_recto
                'photo_identite_verso_687522865684.png',  // photo_identite_verso
                '19390252039939'                       // numero_carte_identite
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO users (
                code, nom, prenom, numero, adresse, type_user, 
                photo_identite_recto, photo_identite_verso, numero_carte_identite
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($users as $user) {
            $stmt->execute($user);
            echo "✅ Utilisateur créé: {$user[1]} {$user[2]}\n";
        }

        // 2. Ajouter des comptes
        $userIds = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);
        $typeCompteIds = $pdo->query("SELECT id_type FROM type_compte")->fetchAll(PDO::FETCH_COLUMN);

        $comptes = [
            [
                'P-0001',           // numero_compte
                '772917770',        // numero_telephone
                password_hash('1234', PASSWORD_DEFAULT),  // code_secret
                50000.00,           // solde
                'true',             // est_principal (as string for PostgreSQL)
                $userIds[0],        // id_client
                $typeCompteIds[0],  // id_type_compte
                'Principale'        // type_compte
            ],
            [
                'S-0001',          // numero_compte
                '776444556',       // numero_telephone
                password_hash('5678', PASSWORD_DEFAULT),  // code_secret
                8000.00,           // solde
                'false',           // est_principal (as string for PostgreSQL)
                $userIds[1],       // id_client
                $typeCompteIds[1], // id_type_compte
                'Secondaire'       // type_compte
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO compte (
                numero_compte, 
                numero_telephone, 
                code_secret, 
                solde, 
                est_principal, 
                id_client, 
                id_type_compte, 
                type_compte
            ) VALUES (?, ?, ?, ?, ?::boolean, ?, ?, ?)
        ");

        foreach ($comptes as $compte) {
            $stmt->execute($compte);
            echo "✅ Compte créé: {$compte[0]}\n";
        }

        // 3. Ajouter des transactions
        // Récupérer les vrais IDs des comptes et types de transaction
        $compteIds = $pdo->query("SELECT id FROM compte ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
        $typeTransactionIds = $pdo->query("SELECT id_type FROM type_transaction ORDER BY id_type")->fetchAll(PDO::FETCH_COLUMN);

        // Utiliser les IDs réels des comptes
        $transactions = [
            [10000.00, $compteIds[0], $typeTransactionIds[0]],  // Dépôt sur le premier compte
            [2500.00, $compteIds[0], $typeTransactionIds[1]],   // Retrait sur le premier compte
            [1200.00, $compteIds[0], $typeTransactionIds[2]],   // Paiement sur le premier compte
            [4500.00, $compteIds[1], $typeTransactionIds[0]],   // Dépôt sur le second compte
            [3000.00, $compteIds[1], $typeTransactionIds[1]]    // Retrait sur le second compte
        ];

        $stmt = $pdo->prepare("INSERT INTO transaction (montant, compte_id, type_transaction) VALUES (?, ?, ?)");

        foreach ($transactions as $trx) {
            $stmt->execute($trx);
            echo "✅ Transaction créée: {$trx[0]} FCFA pour le compte ID {$trx[1]}\n";
        }

        $pdo->commit();
        echo "\n✅ Toutes les données de test ont été insérées avec succès!\n";
        
        echo "\n=== Codes secrets des comptes ===\n";
        echo "Service Commercial (connexion): 0000\n";  // Mise à jour du message
        echo "Service Commercial (P-0001): 1234\n";
        echo "Abdoulaye Diallo (S-0001): 5678\n";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Erreur lors du seeding: " . $e->getMessage() . "\n";
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
    insertDefaultData($pdo, $_ENV['DB_DRIVER']);
    // Puis insérer les données de test
    insertMockData($pdo);
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
