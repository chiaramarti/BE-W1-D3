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

// Verifica se è stato ricevuto l'ID dell'utente da eliminare
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query SQL per eliminare l'utente
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Reindirizza alla pagina principale
header("Location: index.php");
exit();
