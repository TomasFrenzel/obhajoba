<?php
session_start();
$title = 'odpověd';
include_once('./temp/headingUser.php');
require_once('./temp/db_con.php');

$user_id = $_SESSION['id'];

//kontrola zda je uživatel přihlášený
$loggedIn = isset($_SESSION['id']);
    if($loggedIn){
        if(!empty($_POST['answer'])){
            $user_answer = $_POST['answer'];
        $correct_answer = $_POST['correct_answer'];

        $query = "SELECT score FROM scores WHERE idUsers=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        $points = $row['score'];


        if ($user_answer === $correct_answer) {
            $message = "Správná odpověď!";
            $points += 10;
        } else {
            if($points <= 0){
                $message = "Nesprávná odpověď. Správná odpověď byla: " . $correct_answer;
            }else{
                $message = "Nesprávná odpověď. Správná odpověď byla: " . $correct_answer;
                $points -= 10;
            }
        }

        $sql_update = "UPDATE scores SET score = '$points' WHERE idUsers = '$user_id'";
        $con->query($sql_update);

        $con->close();
        echo"
            <sesion class='container txt-color d-flex  align-items-center flex-column full-section'>
                <h1 class='text-white'>Vyhodnocení odpovědi</h1>
                <p class='text-white'>$message</p>
                <p class='text-white'>Vaše body jsou :<span class='colored-text'> $points</span> </p>
                <a href='quiz.php' class='button mt-3'>Další otázka</a>
                <a href='user.php' class='button mt-3'>Profil</a>
            </sesion>

        ";
        }else{
            
            echo "
            <sesion class='container txt-color d-flex  align-items-center flex-column full-section'>
            <h3>Musíte Vybrat odpověď</h3>
            <a href='quiz.php'><button class='button mt-3'>Zpět do kvízu</button></a>
            </sesion>";
        }
                
    }else{
        header('location: block.php');
    }

    
?><button></button>

