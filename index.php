<?php
// Connessione al database
$host = 'localhost';
$db   = 'ifoa_users';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// inserire un nuovo utente
    $sql = "INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
    $stmt = $pdo->prepare($sql);
    // $stmt->execute(['name' => 'chiara', 'surname' => 'martinelli', 'email' => 'cm@gmail.com', 'password' => 'asdf']);
    // $stmt->execute(['name' => 'nicole', 'surname' => 'maini', 'email' => 'nm@gmail.com', 'password' => 'ghjk']);


// ottenere la lista di tutti gli utenti
    $sql = "SELECT * FROM users";
    $stmt = $pdo->query($sql);
    // $users = $stmt->fetchAll();
    echo '<ul>';
    foreach ($stmt as $row){
        echo "<li>$row[name]</li>";
    };
    echo '</ul>';

// Funzione per ottenere i dettagli di un singolo utente
    $id = 0;
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    echo "<h2>$row[name]</h2>";
    echo "<h2>$row[surname]</h2>";
    echo "<h2>$row[email]</h2>";

// aggiornare un utente esistente
    $sql = "UPDATE users SET name=:name, surname=:surname, email=:email, password=:password WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'surname' => $surname, 'email' => $email, 'password' => $password, 'id' => $id]);

// per eliminare un utente
    $sql = "DELETE FROM users WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);


