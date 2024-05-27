<?php

session_start();
//stránka
$title = "nastavení";

//zahlaví
include_once ('./temp/headingUser.php');

//Propojení
require_once ('./temp/db_con.php');

//funkce
require_once ('./temp/function.php');

$username = $_SESSION["username"];
$idUsers = $_SESSION["id"];

//kontrola zda je uživatel přihlášen
$loggedIn = isset ($_SESSION['id']);
if ($loggedIn) {
    //narhání Profilového obrázku
    echo "<session class='container txt-color d-flex align-items-center flex-column full-section' id='settings-sections'>
        <form action='settings.php' method='post' enctype='multipart/form-data'>
            <h3 class='text-white'>Změnit profilový obrázek</h3><br>
            <input class='text-white' type='file' name='fileToUpload'><br> 
            <input type='submit' value='Nahrát obrázek' name='submitImg' class='button mt-3'>
        </form>";

    // Kontrola připojení
    if ($con->connect_error) {
        die ("Connection failed: " . $con->connect_error);
    }

    // Zpracování nahrávaného souboru
    if (isset ($_POST["submitImg"])) {
        // Adresář pro ukládání nahrávaných souborů
        $target_dir = "./profileImg/";

        // Kontrola, zda byl vybrán soubor
        if (empty ($_FILES["fileToUpload"]["name"])) {
            echo "<p class='text-white mt-3'>Musíte vybrat soubor.</p>";
        } else {
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Povolené formáty obrázků 
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                echo "<p class='text-white'>Omlouváme se, ale jsou povoleny jenom JPG, JPEG nebo PNG.</p>";
            } else {

                // Nahrání nového souboru
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // Uložení cesty k obrázku do databáze
                    $sql = "UPDATE users SET profileImg = '$target_file' WHERE id = '$idUsers'";
                    if ($con->query($sql)) {
                        echo "<p class='text-white'>Soubor se úspěšně nahrál.</p>";
                        $_SESSION["profileImg"] = $target_file;
                    } else {
                        echo "Error: " . $sql . "<br>" . $con->error;
                    }
                } else {
                    echo "<p class='text-white'>Omlouváme se ale nastala chyba v nahrárvaní souboru do databáze.</p>";
                }
            }
        }
    }

    echo "
        <form id='change-username-form' action='settings.php' method='post' class='mt-5' enctype='multipart/form-data'>
        <h3 class='text-white'>Změnit Uživatelské jméno</h3><br>
        <input id='form-set-section' class='p-2 mz-2 form-input' type='text' placeholder='Nové uživatelské jméno' name='newUsername'><br> 
        <input type='submit' value='Změnit jméno' name='submitName' class='button mt-3'> 
        </form>";


    if (isset ($_POST["submitName"])) {
        //ového uživatelského jména z formuláře
        $newUsername = $_POST["newUsername"];

        // Aktualizace uživatelského jména v databázi
        $sql = "UPDATE users SET username = '$newUsername' WHERE id = '$idUsers'";
        if ($con->query($sql)) {
            echo "<p class='text-white mt-4'>Uživatelské jméno bylo úspěšně změněno.</p>";
            $_SESSION["username"] = $newUsername;
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }

    $con->close();
    echo "</session>";


} else {
    header('location: block.php');
}



?>