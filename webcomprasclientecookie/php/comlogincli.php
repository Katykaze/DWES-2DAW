<?php
include 'functions.php';
if (userCookieExists()) {
    echo "<script>location.href='../index_cliente.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/BOOTSTRAP.MIN.CSS">
    <title>Document</title>
</head>

<body>
<h2>Login de Usuario</h2>
<form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <br><br>
    <label for="user">Usuario</label>
    <input type="text" name="user" id="user">
    <br><br>
    <label for="password">Contraseña</label>
    <input type="text" name="password" id="password">
    <br><br>
    <input type="submit" name="submit" id="submit" value="Acceder">
</form>
</body>

</html>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connection();
    if (isset($_POST["submit"])) {
        $user = test_input($_POST['user']);
        $password = test_input($_POST['password']);
        echo $user;
        echo $password;
        $result = getDniIfExists($conn, $user, $password);
        if ($result === 0) {
            echo "No existe registro de este usuario";
        } else {
            createUserCookie($result);
            //aqui creamos cookie de carrito de compra
            //la creo nada mas iniciar sesion
            if (!basketCookieExists()) {
                createBasketCookie();
            }
            $link = "<script>window.open('../index_cliente.php')</script>";
            echo $link;
        }
        $conn = closeConn($conn);
    } else {
        echo "Por favor, introduzca un Valor correcto </br>";
    }

}
?>
