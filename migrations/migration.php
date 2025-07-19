<?php

require_once __DIR__ . '/../vendor/autoload.php';

function prompt(string $message): string {
    echo $message;
    return trim(fgets(STDIN));
}

function writeEnvIfNotExists(array $config): void {
    $envPath = dirname(__DIR__) . '/.env';
    if (!file_exists($envPath)) {
        $env = <<<ENV
# Configuration de la base de données
DB_DSN="{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}"
DB_DRIVER={$config['driver']}
DB_HOST={$config['host']}
DB_PORT={$config['port']}
DB_NAME={$config['dbname']}
DB_USER={$config['user']}
DB_PASSWORD={$config['pass']}

ENV;
        file_put_contents($envPath, $env);
        echo ".env généré avec succès à la racine du projet.\n";
    } else {
        echo "Le fichier .env existe déjà, aucune modification.\n";
    }
}

function createTables(PDO $pdo, string $driver): void {
    echo "\n=== Création des tables $driver ===\n";

    if ($driver === 'pgsql') {
        createPostgreSQLTables($pdo);
    } else {
        createMySQLTables($pdo);
    }

    echo "✅ Toutes les tables ont été créées.\n";
}

function createPostgreSQLTables(PDO $pdo): void {
    // Table type_transaction
    $pdo->exec("CREATE TABLE IF NOT EXISTS type_transaction (
        id_type SERIAL PRIMARY KEY,
        libelle VARCHAR(50) NOT NULL UNIQUE
    )");

    // Table type_compte
    $pdo->exec("CREATE TABLE IF NOT EXISTS type_compte (
        id_type SERIAL PRIMARY KEY,
        libelle VARCHAR(50) NOT NULL UNIQUE
    )");

    // Table users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        code VARCHAR(255) NOT NULL,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        numero VARCHAR(20) NOT NULL UNIQUE,
        adresse TEXT NOT NULL,
        type_user VARCHAR(50) NOT NULL DEFAULT 'client',
        photo_identite_recto VARCHAR(255),
        photo_identite_verso VARCHAR(255),
        numero_carte_identite VARCHAR(50),
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table compte
    $pdo->exec("CREATE TABLE IF NOT EXISTS compte (
        id SERIAL PRIMARY KEY,
        numero_compte VARCHAR(10) NOT NULL UNIQUE,
        numero_telephone VARCHAR(20) NOT NULL,
        code_secret VARCHAR(255) NOT NULL,
        solde DECIMAL(15,2) DEFAULT 0.00,
        est_principal BOOLEAN DEFAULT false,
        id_client INTEGER NOT NULL,
        id_type_compte INTEGER NOT NULL,
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        type_compte VARCHAR(50) CHECK (type_compte IN ('Principale', 'Secondaire')),
        FOREIGN KEY (id_client) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (id_type_compte) REFERENCES type_compte(id_type)
    )");

    // Table transaction
    $pdo->exec("CREATE TABLE IF NOT EXISTS transaction (
        id SERIAL PRIMARY KEY,
        montant DECIMAL(15,2) NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        compte_id INTEGER NOT NULL,
        type_transaction INTEGER NOT NULL,
        FOREIGN KEY (compte_id) REFERENCES compte(id) ON DELETE CASCADE,
        FOREIGN KEY (type_transaction) REFERENCES type_transaction(id_type)
    )");
}

function createMySQLTables(PDO $pdo): void {
    $pdo->exec("SET NAMES utf8mb4");
    
    // Table type_transaction
    $pdo->exec("CREATE TABLE IF NOT EXISTS type_transaction (
        id_type INT AUTO_INCREMENT PRIMARY KEY,
        libelle VARCHAR(50) NOT NULL UNIQUE,
        INDEX idx_libelle (libelle)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Table type_compte
    $pdo->exec("CREATE TABLE IF NOT EXISTS type_compte (
        id_type INT AUTO_INCREMENT PRIMARY KEY,
        libelle VARCHAR(50) NOT NULL UNIQUE,
        INDEX idx_libelle (libelle)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Table users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(255) NOT NULL,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        numero VARCHAR(20) NOT NULL UNIQUE,
        adresse LONGTEXT NOT NULL,
        type_user VARCHAR(50) NOT NULL DEFAULT 'client',
        photo_identite_recto VARCHAR(255),
        photo_identite_verso VARCHAR(255),
        numero_carte_identite VARCHAR(50),
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_numero (numero),
        INDEX idx_type_user (type_user)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Table compte
    $pdo->exec("CREATE TABLE IF NOT EXISTS compte (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero_compte VARCHAR(10) NOT NULL UNIQUE,
        numero_telephone VARCHAR(20) NOT NULL,
        code_secret VARCHAR(255) NOT NULL,
        solde DECIMAL(15,2) DEFAULT 0.00,
        est_principal BOOLEAN DEFAULT false,
        id_client INT NOT NULL,
        id_type_compte INT NOT NULL,
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        type_compte VARCHAR(50),
        INDEX idx_numero_compte (numero_compte),
        INDEX idx_numero_telephone (numero_telephone),
        FOREIGN KEY (id_client) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (id_type_compte) REFERENCES type_compte(id_type),
        CHECK (type_compte IN ('Principale', 'Secondaire'))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Table transaction
    $pdo->exec("CREATE TABLE IF NOT EXISTS transaction (
        id INT AUTO_INCREMENT PRIMARY KEY,
        montant DECIMAL(15,2) NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        compte_id INT NOT NULL,
        type_transaction INT NOT NULL,
        INDEX idx_compte (compte_id),
        INDEX idx_type (type_transaction),
        FOREIGN KEY (compte_id) REFERENCES compte(id) ON DELETE CASCADE,
        FOREIGN KEY (type_transaction) REFERENCES type_transaction(id_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function testDatabaseConnection(string $driver, string $host, string $port, string $user, string $pass, string $dbName = null): array {
    try {
        $dsn = $dbName 
            ? "$driver:host=$host;port=$port;dbname=$dbName"
            : "$driver:host=$host;port=$port";
        
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        
        return ['success' => true, 'error' => null];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// === Script principal ===
echo "=== Configuration de la base de données ===\n";

$driver = strtolower(prompt("Quel SGBD utiliser ? (mysql / pgsql) [pgsql] : ")) ?: "pgsql";

if (!in_array($driver, ['mysql', 'pgsql'])) {
    echo "❌ SGBD non supporté. Utilisez 'mysql' ou 'pgsql'.\n";
    exit(1);
}

$host = prompt("Hôte [127.0.0.1] : ") ?: "127.0.0.1";
$port = prompt("Port [" . ($driver === 'pgsql' ? "5432" : "3306") . "] : ") ?: ($driver === 'pgsql' ? "5432" : "3306");
$user = prompt("Utilisateur [" . ($driver === 'pgsql' ? "postgres" : "root") . "] : ") ?: ($driver === 'pgsql' ? "postgres" : "root");
$pass = prompt("Mot de passe : ");
$dbName = prompt("Nom de la base à créer : ");

if (empty($dbName)) {
    echo "❌ Le nom de la base de données est obligatoire.\n";
    exit(1);
}

try {
    echo "\n=== Test de connexion au serveur ===\n";
    
    // Test de connexion sans base spécifique
    $initialDb = $driver === 'pgsql' ? 'postgres' : null;
    $connectionTest = testDatabaseConnection($driver, $host, $port, $user, $pass, $initialDb);
    
    if (!$connectionTest['success']) {
        echo "❌ Détails de l'erreur : " . $connectionTest['error'] . "\n";
        
        // Messages d'aide spécifiques
        if ($driver === 'mysql') {
            echo "\n💡 Solutions possibles pour MySQL :\n";
            echo "   • Vérifiez que MySQL est démarré : sudo systemctl status mysql\n";
            echo "   • Démarrez MySQL : sudo systemctl start mysql\n";
            echo "   • Installez MySQL : sudo apt install mysql-server\n";
            echo "   • Vérifiez le port (défaut: 3306)\n";
            echo "   • Testez manuellement : mysql -u $user -p\n";
        } else {
            echo "\n💡 Solutions possibles pour PostgreSQL :\n";
            echo "   • Vérifiez que PostgreSQL est démarré : sudo systemctl status postgresql\n";
            echo "   • Démarrez PostgreSQL : sudo systemctl start postgresql\n";
            echo "   • Installez PostgreSQL : sudo apt install postgresql postgresql-contrib\n";
            echo "   • Vérifiez le port (défaut: 5432)\n";
            echo "   • Testez manuellement : sudo -u postgres psql\n";
        }
        
        throw new Exception("Impossible de se connecter au serveur $driver");
    }
    echo "✅ Connexion au serveur $driver réussie.\n";

    // Connexion à une base par défaut
    $dsn = $initialDb ? "$driver:host=$host;port=$port;dbname=$initialDb" : "$driver:host=$host;port=$port";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "\n=== Création de la base de données ===\n";

    if ($driver === 'mysql') {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Base MySQL \"$dbName\" créée ou déjà existante.\n";
    } elseif ($driver === 'pgsql') {
        // Vérifier si la base existe
        $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
        $stmt->execute([$dbName]);
        $exists = $stmt->fetch();
        
        if (!$exists) {
            $pdo->exec("CREATE DATABASE \"$dbName\" WITH ENCODING 'UTF8' LC_COLLATE='fr_FR.UTF-8' LC_CTYPE='fr_FR.UTF-8'");
            echo "✅ Base PostgreSQL \"$dbName\" créée.\n";
        } else {
            echo "ℹ️  Base PostgreSQL \"$dbName\" déjà existante.\n";
        }
    }

    // Connexion à la base nouvellement créée
    $pdo = new PDO("$driver:host=$host;port=$port;dbname=$dbName", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Test de connexion à la nouvelle base
    $finalTest = testDatabaseConnection($driver, $host, $port, $user, $pass, $dbName);
    if (!$finalTest['success']) {
        throw new Exception("Impossible de se connecter à la base $dbName : " . $finalTest['error']);
    }

    // Création des tables et insertion des données par défaut
    createTables($pdo, $driver);

    // Écriture du fichier .env
    writeEnvIfNotExists([
        'driver' => $driver,
        'host' => $host,
        'port' => $port,
        'user' => $user,
        'pass' => $pass,
        'dbname' => $dbName
    ]);

    echo "\n🎉 Migration terminée avec succès !\n";
    echo "📝 Base de données : $dbName\n";
    echo "🔧 SGBD : " . strtoupper($driver) . "\n";
    echo "🌐 Hôte : $host:$port\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "💡 Vérifiez vos paramètres de connexion et que le serveur $driver est démarré.\n";
    exit(1);
}