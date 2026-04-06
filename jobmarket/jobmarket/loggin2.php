<?php

session_start();
include('connection.php');

// tady je potreba nastavit pristup k databazi

$conn = mysqli_connect($server, $uzivatel, $heslo, $databaze);
// Kontrola připojení
if ($conn->connect_error) {
    die("Chyba připojení: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leadername = $_POST['leadername'];
    $password = $_POST['password'];

    $query = "SELECT * FROM login2 WHERE leadername='$leadername' AND password='$password'";
    // tady se nastavuje, z jake tabulky budeme cist v tomto pripade login2
    // dana tabulka obsahuje dva parametry, a to leadername a password
    $result1 = mysqli_query($conn, $query);


    if (mysqli_num_rows($result1) == 1) {

        $query = "SELECT * FROM login2 WHERE leadername='$leadername' AND password='$password' and active=1";
        // tady se nastavuje, z jake tabulky budeme cist v tomto pripade login2
        // dana tabulka obsahuje dva parametry, a to leadername a password
        $result2 = mysqli_query($conn, $query);
        if (mysqli_num_rows($result2) == 1) {




            $row = mysqli_fetch_assoc($result2);

            // Uložíme do session
            $_SESSION['id_leader'] = $row['id_leader'];
            $_SESSION['leadername'] = $row['leadername'];



            header("location: leader/menu2.php");


            // tady se nastavuje, kam má program skocit po uspesnem prihlaseni

        } else
            $error = "Účet je platný, ale není aktvní";



    } else
        $error = "Účet není platný";
}


// Uzavření připojení
$conn = null;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Loggin leader</title>
</head>

<body>
    <link rel="stylesheet" href="./CSS/style.css">
    <h2>Login Vedoucího</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="leadername">Username:</label>
            <input type="varchar" id="leadername" name="leadername" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

        </div>
        <br>
        <div>
            <input type="submit" name="submit" value="Login">
        </div>
        <?php if (isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>
    </form>
</body>

</html>