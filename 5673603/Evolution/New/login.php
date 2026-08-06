<?php
session_start();

$username = "admin";
$password = "qwerty";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = trim($_POST["username"]);
    $pass = trim($_POST["password"]);

    if ($user === $username && $pass === $password) {

        $_SESSION["login"] = true;
        $_SESSION["username"] = $user;

        
        header("Location: payment.php");
        exit();

    } else {

        echo "<script>
                alert('Username หรือ Password ไม่ถูกต้อง');
                window.location='index.html';
              </script>";
        exit();

    }

} else {

    header("Location: index.html");
    exit();

}
?>