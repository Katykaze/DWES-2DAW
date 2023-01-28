<?php
include 'functions.php';
if (isUserLogged()) {
    echo "<script>location.href='../index_cliente.php';</script>";
}//else{
    //si no está logeado y se ha creado la sesion esta se eliminara
    //de esta manera al cerrar sesion se elimina la sesion
    //cerrarSesion();
//}
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
        $result = getDniIfExists($conn, $user, $password);
        if ($result === 0) {
            echo "No existe registro de este usuario";
        } else {
            //session_start();
            //necesario abrir la sesión porque la he cerrado.
            $_SESSION['DNI'] = $result;
            $_SESSION['userName'] = $user;

            if(!isset($_SESSION['cart'])){
                //creamos el carrito de la compra dentro de la sesion, con key llamada cart
                $_SESSION['cart'] = array();
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
