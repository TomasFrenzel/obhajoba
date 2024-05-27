<?php
// Připojení k databázi
require_once('./temp/db_con.php');

// Kontrola, zda byl vybrán článek
if (!isset($_GET['id'])) {
    header('location: articles.php');
}

// SQL dotaz k vyhledání článku
$stmt = $con->prepare("SELECT * FROM articles WHERE id=?");
$stmt->bind_param('s', $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Název stránky
$title = $row['title'];

// Zahrnutí záhlaví
include_once('./temp/heading.php');
// Obsah stránky
echo $row['content'];


//echo'Autor článku je: '. $row['author'];
//echo'<p class="text-left mt-3 text-white">Článek byl napsaný: '. $row['date_created'].'</p>';
// Ukončení připojení k databázi
$con->close();
echo "<a class='button'href='articles.php' class=' mt-3 w-80'>Zpět</a>";

// Zahrnutí zápatí
include_once('./temp/footer.php');
?>