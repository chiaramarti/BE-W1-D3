<?php
// Verifica se sono stati inviati i dati del modulo di aggiunta utente tramite il metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Recupera i dati dal modulo di aggiunta utente
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query per inserire il nuovo utente nel database
    $sql = "INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $surname, $email, $password]);

    // Reindirizza alla pagina principale dopo l'aggiunta dell'utente
    header("Location: index.php");
    exit();
} else {
    // Se non sono stati inviati dati tramite il metodo POST, reindirizza alla pagina principale
    header("Location: index.php");
    exit();
}
