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
<h2>Registro Usuario</h2>
<form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <br><br>
    <label for="dni">DNI</label>
    <input type="text" name="dni" id="dni">
    <br><br>
    <label for="dni">NOMBRE</label>
    <input type="text" name="name" id="name">
    <br><br>
    <label for="dni">Apellido</label>
    <input type="text" name="apellido" id="apellido">
    <br><br>
    <input type="submit" name="submit" id="submit" value="Registrarme">
</form>
</body>

</html>


<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connection();
    if (isset($_POST["submit"])) {
        $nif = test_input($_POST['dni']);
        $nombre = test_input($_POST['name']);
        $apellido = test_input($_POST['apellido']);
        if (!isDniClient($conn, $nif)) {
            createUser($conn, $nif, $nombre, $apellido);
        } else {
            echo 'Ya existe un registro de usuario con este DNI';
        }

        $conn = closeConn($conn);
    } else {
        echo "Por favor, introduzca un calor correcto </br>";
    }

}
?>
