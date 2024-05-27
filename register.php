<?php
//stránka
$title = "Register";

error_reporting(E_ALL);


//zahlaví
include_once('./temp/heading.php');

//Propojení
require_once('./temp/db_con.php');
//funkce
require_once('./temp/function.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';


if (isset($_POST["submit"])) {
    // Kontrola vyplnění polí
    if (isset($_POST["email"], $_POST["password"], $_POST["password2"], $_POST["username"]) &&
    !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && !empty($_POST["username"])) {
    
        // Získání dat z formuláře
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password2 = $_POST["password2"];
        $username = $_POST["username"];
        
        // Pole pro ukládání chyb
        $errores = [];

        $query = "SELECT * FROM users WHERE email=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        //kotrola emailu
        if ($result->num_rows === 0){

       

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username))  {
                $errores[] = "Uživatelské jméno obsahuje nepovolené znaky.";
            }
            elseif (!preg_match('/^[a-zA-Z0-9_@]+$/', $password)){
                $errores[] = "Heslo obsahuje nepovolené znaky.";
            }

            if ($password !== $password2) {
                $errores[] = "Hesla se neshodují.";

            }

            if (empty($errores)) {

                
                    // Bezpečné hashování hesla
                    $hashPassword = password_hash(secouredPass($password), PASSWORD_BCRYPT, ['cost' => 12]);
                    $gen_friednCode = generevoani_friendCode($con);
                    $profileImgAuto="./profileImg/auto/Group3AutoIMG987654321.svg";
                    $verification_code = md5(uniqid(rand(), true));

                    // Vložení nového uživatele do databáze
                    $query = "INSERT INTO users (email, pass, username, friendCode, profileImg, verCode) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("ssssss", $email, $hashPassword, $username, $gen_friednCode, $profileImgAuto, $verification_code);
                    $stmt->execute();

                    // Odeslání potvrzovacího e-mailu
                    
                    try {
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'zmpfrenzel@gmail.com'; // Váš e-mail
                        $mail->Password = 'ovdzcpnkkedtpqbu'; // Vaše heslo
                        $mail->SMTPSecure = 'ssl'; // Použijte TLS, ne SSL
                        $mail->Port = 465; // Port pro TLS

                        $mail->setFrom('zmpfrenzel@gmail.com', 'Bezpecne Na Netu');
                        $mail->addAddress($email, $username);
                        $mail->Subject = "Aktivace Uctu {$username}";
                        $mail->Body = "Děkujeme za registraci účtu {$username}. Váš učet aktivujete po kliknutím na následující odkaz: https:// zmp-frenzel.jednoduse.cz/verify.php?code=".$verification_code .'&email='.$email;

                        //pro noramlní web: https://bezpecne-na-netu.cekuj.net/verify.php?code="
                        // pro localhost: http://localhost/zmp/verify.php?code="

                        $mail->send();
                        echo 'E-mail byl úspěšně odeslán na adresu: ' . $email;
                        header("location: emailVer.php?email=" . $email);
                        
                        
                    } catch (Exception $e) {
                        //echo 'Došlo k chybě při odesílání e-mailu: ', $mail->ErrorInfo;
                        echo "chyba";
                    }
            } else {
                foreach($errores as $one_error){
                    echo $one_error;  
                }
                        
            }
        
        }else{
            echo"<p class='text-white'>Tato emailova adresa je už přihlášená</p>";
        }
    }
}
?>

<sesion id='register-section' class='container d-flex  align-items-center flex-column full-section color'>

<h2 class='display-3 text-white fw-bold p-4'>Registrovat <span class='colored-text'>se</span></h2>
<form action='register.php' method='post' class='d-flex flex-column align-items-center'>

    <input  type='email' 
            placeholder='Email' 
            name='email' 
            class='form-input p-2'><br>
    <input  type='password' 
            placeholder='heslo' 
            name='password' 
            class='form-input p-2'><br>
    <input  type='password' 
            placeholder='znova heslo' 
            name='password2' 
            class='form-input p-2'><br>
    <input  type='text' 
            name='username' 
            placeholder='username' 
            class='form-input p-2'<br>
    <input  type='submit' 
            name='submit' 
            value='Odeslat' 
            class='button m-4 align-items-center' >
</form>
<h5 class='text-white mt-2'>Už máte účet?</h5>
            <a href='./login.php' class='mt-2 login-icon'><img src='./img/register.svg' alt='logo'></a>

</section>
