<?php
session_start();
$title = 'Moje články';

// Hlavička
require_once ('./temp/headingUser.php');

// Propojení
require_once ('./temp/db_con.php');

// Sezení
$idUser = $_SESSION['id'];

$stmt = $con->prepare("SELECT articles.title, users.username, articles.shown, articles.id FROM articles
LEFT JOIN users ON (users.id = articles.author) WHERE articles.shown = 1 and users.id = ?");
$stmt->bind_param('s', $idUser);
$stmt->execute();
$result = $stmt->get_result();

// Obsah sekce
echo "<div class='container mt-5'>";

// Vytisknutí článků
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-warning' role='alert'>Nebyly nalezeny žádné články.</div>";
} else {
    echo "<h2 class='text-center mb-4 text-white '>Vámi napsané články</h2>";
    while ($row = $result->fetch_assoc()) {
        $article_id = $row['id'];
        echo "<div class='card mb-3'>
                <div class='card-body'>
                    <h5 class='card-title'>$row[title]</h5>
                    <p class='card-text'>Autor: $row[username]</p>
                    <a href='./idArticle.php?id=$article_id' class='btn btn-primary'>Detail</a>
                </div>
              </div>";
    }
}

echo "<h2 class='text-center my-4 text-white'>Články čekající na schválení</h2>";

// Načtení článků čekajících na schválení
$stmt = $con->prepare("SELECT articles.title, users.username, articles.shown, articles.id FROM articles
LEFT JOIN users ON (users.id = articles.author) WHERE articles.shown = 0 and articles.author = ?");
$stmt->bind_param('s', $idUser);
$stmt->execute();
$result_pending = $stmt->get_result();

// Vytisknutí článků čekajících na schválení
if (mysqli_num_rows($result_pending) == 0) {
    echo "<div class='alert alert-info' role='alert'>Nebyly nalezeny žádné články čekající na schválení.</div>";
} else {
    while ($row = $result_pending->fetch_assoc()) {
        echo "<div class='card mb-3 bg-secondary text-white'> <!-- Tmavě šedá barva pro označení čekajících článků -->
                <div class='card-body'>
                    <h5 class='card-title'>$row[title]</h5>
                    <p class='card-text'>Autor: $row[username]</p>
                </div>
              </div>";
    }
}

echo "</div>"; // konec containeru
?>
