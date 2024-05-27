<?php
//start session
session_start();

//title
$title = "Quiz";

//zahlavi
require_once ('./temp/headingUser.php');

//Propojení
require_once ('./temp/db_con.php');

//session
$username = $_SESSION["username"];
$idUsers = $_SESSION["id"];

//kontrola zda je uživatel přihlášený
$loggedIn = isset ($_SESSION['id']);
if ($loggedIn) {
    //echo "<h1>ted se $username nachazíš v quizu :) </h1>";
    // Získání náhodné otázky z databáze
    $sql = "SELECT * FROM question ORDER BY RAND() LIMIT 1";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otazka = $row["otazka"];
        $aText = $row["aText"];
        $bText = $row["bText"];
        $cText = $row["cText"];
        $spravna = $row["spravna"];
    } else {
        echo "<p class='text-white fw-bold text-center'>Žádná otázka nebyla nalezena.</p>";
    }

    $con->close();

    //vytviření SESSIONS
    $_SESSION['otazka'] = $otazka;

    echo "
    <section class='container txt-color d-flex  align-items-center flex-column full-section'>
    <h1 class='text-white'>Bezpečnostní test</h1>
    <form action='between.php' method='post' class='text-white'>
        <p class='text-white'>$otazka</p>
        <input type='radio' name='answer' value='A' class='quiz-input text-white'> $aText <br>
        <input type='radio' name='answer' value='B' class='quiz-input text-white'> $bText <br>
        <input type='radio' name='answer' value='C' class='quiz-input text-white'> $cText <br>
        <input type='hidden' name='correct_answer' value='$spravna'>
        <input type='submit' name='submit' value='Vyhodnotit' class='button my-3 text-white'>
    </form>
    </section>";
} else {
    header('location: block.php');
}

include_once ("./temp/footer.php");
?>