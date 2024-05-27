<?php
session_start();
//stránka
$title = "nastavení";

//Propojení
require_once ('./temp/db_con.php');

//funkce
require_once ('./temp/function.php');

$email = $_GET['email'];

$query = "SELECT * FROM users WHERE email=?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $verifyDat = $row["verify"];

    if($verifyDat === 1){
        echo" <!DOCTYPE html>
        <html lang='cs'>
          <head>
            <meta charset='UTF-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1.0' />
            <!-- Custom CSS import -->
            <link rel='stylesheet' href='./css/styles.css' />
            <!-- Bootstrap CSS import -->
            <link
              href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css'
              rel='stylesheet'
              integrity='sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN'
              crossorigin='anonymous' />
            <!-- Bootstrap JS import -->
            <script
              src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'
              integrity='sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL'
              crossorigin='anonymous'
              defer></script>
            <!-- Font Awesome import -->
            <link
              rel='stylesheet'
              href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'
              integrity='sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=='
              crossorigin='anonymous'
              referrerpolicy='no-referrer' />
            <link rel='shortcut icon' href='./img/maleLogo.svg' type='image/x-icon' />
            <title>Bezpečně na netu | $title</title>
          </head>
        
          <body>
          <!-- Background image -->
            <div class='background-image-container'>
              <!-- Navbar -->
              <nav class='navbar navbar-expand-md py-md-4'>
                <div
                  class='container justify-content-left justify-content-between w-100'>
                  <div class='navbar-brand img-logo-container'> <a href='index.html'>
                  <a href='index.php'> <img class='w-100' src='./img/logo4.svg
                    ' alt='Logo' /></a>
                  </div>
                  </nav>
                <section class='text-white emailver container justify-content-center'>
                <H1 class=''>Váš učet je aktivovaný</H1>

                <a href='login.php?email=$email' class='button mp-4 my-3 w-70'>Přihlást se</a>
                </section>
                </div>";

            }else{
                echo"
                <!DOCTYPE html>
        <html lang='cs'>
          <head>
            <meta charset='UTF-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1.0' />
            <!-- Custom CSS import -->
            <link rel='stylesheet' href='./css/styles.css' />
            <!-- Bootstrap CSS import -->
            <link
              href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css'
              rel='stylesheet'
              integrity='sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN'
              crossorigin='anonymous' />
            <!-- Bootstrap JS import -->
            <script
              src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'
              integrity='sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL'
              crossorigin='anonymous'
              defer></script>
            <!-- Font Awesome import -->
            <link
              rel='stylesheet'
              href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'
              integrity='sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=='
              crossorigin='anonymous'
              referrerpolicy='no-referrer' />
            <link rel='shortcut icon' href='./img/maleLogo.svg' type='image/x-icon' />
            <title>Bezpečně na netu | $title</title>
          </head>
        
          <body>
          <!-- Background image -->
            <div class='background-image-container'>
              <!-- Navbar -->
              <nav class='navbar navbar-expand-md py-md-4'>
                <div
                  class='container justify-content-left justify-content-between w-100'>
                  <div class='navbar-brand img-logo-container'> <a href='index.html'>
                  <a href='index.php'> <img class='w-100' src='./img/logo4.svg
                    ' alt='Logo' /></a>
                  </div>
                  </nav>
                <section class='text-white emailver container justify-content-center'>
                <H1 class=''>Prosím Aktivujte si váš účet</H1>
                <h2>Aktivace se provede po kliknutí na odkaz, který jsme vám poslali na váš zadaný email: $email</h2>
                </section>
                </div>";
            }





}else{
    echo"Stala se chyba";
}



?>