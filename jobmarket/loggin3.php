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
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM login3 WHERE username='$username' AND password='$password'";
    // tady se nastavuje, z jake tabulky budeme cist v tomto pripade users
    // dana tabulka obsahuje dva parametry, a to username a password
    $result1 = mysqli_query($conn, $query);


    if (mysqli_num_rows($result1) == 1) {

        $query = "SELECT * FROM login3 WHERE username='$username' AND password='$password' and active=1";
        // tady se nastavuje, z jake tabulky budeme cist v tomto pripade users
        // dana tabulka obsahuje dva parametry, a to username a password
        $result2 = mysqli_query($conn, $query);
        if (mysqli_num_rows($result2) == 1) {

            header("location: manager/menu3.php");


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
    <title>Loggin manager</title>
</head>

<body>
    <link rel="stylesheet" href="./CSS/style.css">
    <h2>Login Managera</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="username">Username:</label>
            <input type="varchar" id="username" name="username" required>
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