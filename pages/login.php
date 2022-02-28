<?php

include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select = mysqli_query(
        $conn,
        "SELECT * FROM `user_info` WHERE email = '$email' AND password = '$pass'"
    )
        or die('query failed ungry');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('location:../index.php');
    } else {
        $message[] = 'email ou senha incorreto!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" type="img/x-icon" href="./img/kalangos.ico">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
        }
    }
    ?>

    <div class="form-container">
        <form action="" method="post">
            <h3>Faça seu login</h3>
            <input type="email" name="email" required placeholder="digite seu email" class="box">
            <input type="pasword" name="password" required placeholder="digite sua senha" class="box">
            <input type="submit" name="submit" value="entre agora" class="btn">
            <p>não tem uma conta? <a href="registro.php"> cadatre-se agora</a></p>
        </form>
    </div>
</body>

</html>