<?php

include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

    $select = mysqli_query(
        $conn,
        "SELECT * FROM `user_info` WHERE email = '$email' AND password = '$pass'"
    )
        or die('query failed ungry');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'usuário já cadastrado';
    } else {
        mysqli_query(
            $conn,
            "INSERT INTO `user_info`(name, email, password) 
        VALUES ('$name','$email','$pass')"
        )
            or die('query failed');
        $message[] = 'usuário cadastrado com sucesso"';
        header('location:login.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
            <h3>Registre Agora</h3>
            <input type="text" name="name" required placeholder="digite seu nome" class="box">
            <input type="email" name="email" required placeholder="digite seu email" class="box">
            <input type="pasword" name="password" required placeholder="digite sua senha" class="box">
            <input type="pasword" name="cpassword" required placeholder="repita sua senha" class="box">
            <input type="submit" name="submit" value="registre agora" class="btn">
            <p>já tem uma conta? <a href="login.php"> login agora</a></p>
        </form>
    </div>
</body>

</html>