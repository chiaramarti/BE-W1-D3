<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestione Utenti</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
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

  // Calcola il numero totale di utenti nel database
  $total_users_stmt = $pdo->query("SELECT COUNT(*) FROM users");
  $total_users = $total_users_stmt->fetchColumn();

  // Imposta il numero di righe per pagina
  $rows_per_page = 5;

  // Calcola il numero totale di pagine
  $total_pages = ceil($total_users / $rows_per_page);

  // Ottieni il numero della pagina corrente (se non specificato, impostalo a 1)
  $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

  // Calcola l'offset per la query SQL
  $offset = ($current_page - 1) * $rows_per_page;

  // Se è stata inviata una richiesta di ricerca
  if (isset($_GET['search'])) {
    // Filtra e valida il termine di ricerca
    $search_term = trim($_GET['search']);
    $search_term = htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8');

    // Query per cercare gli utenti che corrispondono al termine di ricerca
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE ? OR surname LIKE ? OR email LIKE ?");
    $stmt->execute(["%$search_term%", "%$search_term%", "%$search_term%"]);
    $users = $stmt->fetchAll();
  } else {
    // Query per ottenere solo le righe per la pagina corrente
    $stmt = $pdo->prepare("SELECT * FROM users LIMIT :offset, :rows_per_page");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':rows_per_page', $rows_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll();
  }
  ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">Gestione Utenti</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <!-- Bottone per aggiungere utente -->
          <li class="nav-item">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Aggiungi Utente</button>
          </li>
        </ul>
      </div>
      <form class="d-flex" action="index.php" method="GET">
        <input class="form-control me-2" type="search" placeholder="Cerca utente..." aria-label="Search" name="search">
        <button class="btn btn-outline-primary" type="submit">Cerca</button>
      </form>
    </div>
  </nav>

  <div class="container mt-4">
    <h2>Elenco Utenti</h2>
    <!-- Tabella degli utenti -->
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Nome</th>
          <th scope="col">Cognome</th>
          <th scope="col">Email</th>
          <th scope="col">Azioni</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) : ?>
          <tr>
            <th scope="row"><?php echo $user['id']; ?></th>
            <td><?php echo $user['name']; ?></td>
            <td><?php echo $user['surname']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td>
              <!-- Pulsanti di modifica ed eliminazione -->
              <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Modifica</a>
              <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo utente?')">Elimina</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Paginazione Bootstrap -->
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
          <li class="page-item <?php if ($i === $current_page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>

  <!-- Modale di aggiunta utente -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Aggiungi Utente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="add_process.php" method="POST">
            <div class="mb-3">
              <label for="inputNome" class="form-label">Nome</label>
              <input type="text" class="form-control" id="inputNome" name="name" required>
            </div>
            <div class="mb-3">
              <label for="inputCognome" class="form-label">Cognome</label>
              <input type="text" class="form-control" id="inputCognome" name="surname" required>
            </div>
            <div class="mb-3">
              <label for="inputEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="inputEmail" name="email" required>
            </div>
            <div class="mb-3">
              <label for="inputPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="inputPassword" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script>
    // Funzione per gestire la conferma di eliminazione
    function confirmDelete(id) {
      if (confirm("Sei sicuro di voler eliminare questo utente?")) {
        window.location.href = "delete_user.php?id=" + id;
      }
    }
  </script>

</body>

</html>