<?php
//stránka
$title = "Ověření";

//zahlaví
include_once('./temp/heading.php');

//Propojení
require_once('./temp/db_con.php');
//funkce
require_once('./temp/function.php');

// Přijetí e-mailové adresy a ověřovacího kódu z odkazu
$email = $_GET['email'];
$verification_code = $_GET['code'];

if(isset($email)&&isset($verification_code)){
    $query = "SELECT * FROM users WHERE email=?";    
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        //z databáze code 
        $verCodeDat = $row["verCode"];

        if($verCodeDat === $verification_code){
            $query = "UPDATE users SET verify = 1 WHERE email = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute(); 
        }
    }
   echo "<section class='container justify-content-center'>
   <h1 class='text-white'>Váš účet byl <span class='colored-text'>aktivován</span></h1> <br>
   <a class='button width: 50%' href='login.php?email=$email'>Přihlásit se</a>
   </section>";
 
}else{
    echo "<section class='container justify-content-center'> 
    <h4 class='text-white'>Olmlouváme se, ale nasala někde chyba. Snažíme se ji opravit. Prosím, zksute to za chvílí.<h4>
    </section>";
}
?>
