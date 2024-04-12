<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiorna Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-4">
        <h2>Aggiorna Utente</h2>
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

        // Verifica se è stato ricevuto l'ID dell'utente da aggiornare
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Query per ottenere le informazioni dell'utente
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if ($user) {
                // Mostra il modulo per l'aggiornamento dell'utente
                echo "<form action='update_process.php' method='POST'>";
                echo "<input type='hidden' name='id' value='{$user['id']}'>";
                echo "<div class='mb-3'>";
                echo "<label for='inputNome' class='form-label'>Nome</label>";
                echo "<input type='text' class='form-control' id='inputNome' name='name' value='{$user['name']}'>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='inputCognome' class='form-label'>Cognome</label>";
                echo "<input type='text' class='form-control' id='inputCognome' name='surname' value='{$user['surname']}'>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='inputEmail' class='form-label'>Email</label>";
                echo "<input type='email' class='form-control' id='inputEmail' name='email' value='{$user['email']}'>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='inputPassword' class='form-label'>Password</label>";
                echo "<input type='password' class='form-control' id='inputPassword' name='password' value='{$user['password']}'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Aggiorna</button>";
                echo "</form>";
            } else {
                echo "<p class='text-danger'>Utente non trovato.</p>";
            }
        } else {
            echo "<p class='text-danger'>ID utente non fornito.</p>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>