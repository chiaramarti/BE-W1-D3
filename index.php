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

// Inserire un nuovo utente
$stmt = $pdo->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
$stmt->execute(['name' => 'chiara', 'surname' => 'martinelli', 'email' => 'cm@gmail.com', 'password' => 'asdf']);
$stmt->execute(['name' => 'nicole', 'surname' => 'maini', 'email' => 'nm@gmail.com', 'password' => 'ghjk']);

// Ottenere la lista di tutti gli utenti
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

echo '<h2>Lista Utenti:</h2>';
echo '<ul>';
foreach ($users as $row) {
    echo "<li>{$row['name']} {$row['surname']} - {$row['email']}</li>";
}
echo '</ul>';

// Funzione per ottenere i dettagli di un singolo utente
$id = 1;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

echo '<h2>Dettagli Utente:</h2>';
echo "<h3>ID: {$user['id']}</h3>";
echo "<h3>Nome: {$user['name']}</h3>";
echo "<h3>Cognome: {$user['surname']}</h3>";
echo "<h3>Email: {$user['email']}</h3>";

// Aggiornare un utente esistente
$id = 2;
$name = 'Nuovo Nome';
$surname = 'Nuovo Cognome';
$email = 'nuovaemail@gmail.com';
$password = 'nuovapassword';
$stmt = $pdo->prepare("UPDATE users SET name=:name, surname=:surname, email=:email, password=:password WHERE id=:id");
$stmt->execute(['name' => $name, 'surname' => $surname, 'email' => $email, 'password' => $password, 'id' => $id]);

// Per eliminare un utente
$idToDelete = 1;
$stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
$stmt->execute(['id' => $idToDelete]);

// Stampare la lista degli utenti rimasti
$stmt = $pdo->query("SELECT * FROM users");
$remaining_users = $stmt->fetchAll();

echo '<h2>Lista Utenti Rimasti:</h2>';
echo '<ul>';
foreach ($remaining_users as $row) {
    echo "<li>{$row['name']} {$row['surname']} - {$row['email']}</li>";
}
echo '</ul>';
?>


