<?php
session_start();

$loggedIn = isset($_SESSION['id']);
if ($loggedIn) {
    $title = "Přátelé";
    // Header
    require_once('./temp/headingUser.php');

    // Propojení s databází
    require_once('./temp/db_con.php');
    require_once('./temp/function.php');

    // Získání uživatelova ID
    $user_id = $_SESSION['id'];

    // Přidání kamaráda
    if (isset($_POST['add_friend'])) {
        $user_id = $_SESSION['id'];
        $friend_friend_code = $_POST['friend_friend_code'];

        // Získání ID uživatele na základě friendCode
        $sql_select_user = "SELECT id FROM users WHERE friendCode='$friend_friend_code'";
        $result_user = $con->query($sql_select_user);
        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            $friend_id = $row_user['id'];

            // Kontrola, zda již jsou uživatelé kamarádi
            $sql_check_friendship = "SELECT * FROM friends_relationships WHERE (user_id = '$user_id' AND friend_id = '$friend_id') OR (user_id = '$friend_id' AND friend_id = '$user_id')";
            $result_check_friendship = $con->query($sql_check_friendship);
            if ($result_check_friendship->num_rows > 0) {
                echo "<div class='container mt-3'>";
                echo "<div class='alert alert-warning' role='alert'>";
                echo "Už jste s tímto uživatelem přátelé.";
                echo "</div>";
                echo "</div>";
            } else {
                // Vložení záznamu do tabulky friends_relationships pro oba uživatele
                $sql_insert_relationship_1 = "INSERT INTO friends_relationships (user_id, friend_id) VALUES ('$user_id', '$friend_id')";
                $sql_insert_relationship_2 = "INSERT INTO friends_relationships (user_id, friend_id) VALUES ('$friend_id', '$user_id')";
                
                if ($con->query($sql_insert_relationship_1) === TRUE && $con->query($sql_insert_relationship_2) === TRUE) {
                    echo "<div class='container mt-3'>";
                    echo "<div class='alert alert-success' role='alert'>";
                    echo "Kamarád úspěšně přidán.";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<div class='container mt-3'>";
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo "Chyba při přidávání kamaráda: " . $con->error;
                    echo "</div>";
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='container mt-3'>";
            echo "<div class='alert alert-danger' role='alert'>";
            echo "Kamarád nenalezen.";
            echo "</div>";
            echo "</div>";
        }
    }

    // Odstranění kamaráda
    if (isset($_POST['remove_friend'])) {
        $friend_id = $_POST['friend_id'];

        // Odstranění záznamu z tabulky friends_relationships pro oba uživatele
        $sql_delete_friend_1 = "DELETE FROM friends_relationships WHERE (user_id = '$user_id' AND friend_id = '$friend_id')";
        $sql_delete_friend_2 = "DELETE FROM friends_relationships WHERE (user_id = '$friend_id' AND friend_id = '$user_id')";
        
        if ($con->query($sql_delete_friend_1) === TRUE && $con->query($sql_delete_friend_2) === TRUE) {
            echo "<div class='container mt-3'>";
            echo "<div class='alert alert-success' role='alert'>";
            echo "Kamarád úspěšně odebrán.";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='container mt-3'>";
            echo "<div class='alert alert-danger' role='alert'>";
            echo "Chyba při odstraňování kamaráda: " . $con->error;
            echo "</div>";
            echo "</div>";
        }
    }

    // Formulář pro vyhledávání a přidání kamaráda
    echo "<div class='container mt-5'>
    <div class='row justify-content-center'>
    <div class='col-md-6'>
    <div class='card'>
    <div class='card-body'>
    <h5 class='card-title'>Přidat kamaráda</h5>
    <form method='post'><div class='mb-3'>
    <label for='friend_friend_code' class='form-label'>Friend Friend Code</label>
    <input type='text' class='form-control' id='friend_friend_code' name='friend_friend_code'></div>
    <button type='submit' name='add_friend' class='btn' style='background-color: #442358; color: white;'>Přidat kamaráda</button>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>";


    // Získání seznamu uživatelů, kteří jsou přáteli aktuálně přihlášeného uživatele, s vyloučením uživatele samotného
    $query_friends = "SELECT users.id, users.username, users.profileImg, scores.score FROM users INNER JOIN scores ON users.id = scores.idUsers WHERE users.id IN (SELECT friend_id FROM friends_relationships WHERE user_id = $user_id) AND users.id != $user_id";
    $query_followers = "SELECT users.id, users.username, users.profileImg, scores.score FROM users INNER JOIN scores ON users.id = scores.idUsers WHERE users.id IN (SELECT user_id FROM followers_relationships WHERE followed_user_id = $user_id)";

    $result_friends = mysqli_query($con, $query_friends);

    if (mysqli_num_rows($result_friends) > 0) {
        echo "<div class='container mt-5'>";
        echo "<h3 class='mb-3 text-white'>Seznam kamarádů</h3>";
        echo "<div class='row justify-content-center'>";

        // Výpis kamarádů ze seznamu friends_relationships
        while ($row = mysqli_fetch_assoc($result_friends)) {
            echo "<div class='col-md-3 mb-4'>
            <div class='card text-center'>
            <div class='card-body'>
            <img src='" . $row['profileImg'] . "' class='rounded-circle mb-3' alt='Profilový obrázek' style='width: 150px; height: 150px;'>
            <h6 class='card-title'>" . $row['username'] . "</h6>
            <p class='card-text'>Skóre: " . $row['score'] . "</p>
            <form method='post'>
            <input type='hidden' name='friend_id' value='" . $row['id'] . "'>
            <button type='submit' name='remove_friend' class='btn btn-danger'>Odebrat kamaráda</button>
            </form>
            </div>
            </div>
            </div>";
        }

        echo "</div>";
        echo "</div>";
    } else {
        echo '<p class="text-center mt-3 text-white">Nemáte žádné kamarády.</p>';
    }
} else {
    header('location: block.php');
}

$con->close();
?>
