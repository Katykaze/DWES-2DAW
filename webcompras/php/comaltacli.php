<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Alta Cliente</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="dni">DNI</label>
        <input type="text" name="dni" id="dni">
        <br><br>
        <label for="dni">NOMBRE</label>
        <input type="text" name="name" id="name">
        <br><br>
        <label for="dni">Apellido</label>
        <input type="text" name="apellido" id="apellido">
        <br><br>
        <label for="dni">CP</label>
        <input type="text" name="cp" id="cp">
        <br><br>
        <label for="dni">Direccion</label>
        <input type="text" name="direc" id="direc">
        <br><br>
        <label for="dni">Ciudad</label>
        <input type="text" name="ciu" id="ciu">
        <br><br>
        <input type="submit" name="submit" id="submit" value="Dar de alta">
    </form>
</body>

</html>


<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connection();
    if (isset($_POST["submit"])) {
        $nif=test_input($_POST['dni']);
        $nombre=test_input($_POST['name']);
        $apellido=test_input($_POST['apellido']);
        $cp=test_input($_POST['cp']);
        $direc=test_input($_POST['direc']);
        $ciu=test_input($_POST['ciu']);
        $valido= isValidDni($nif);
        if($valido == false){
            echo "No se puede dar de alta al cliente </br>";
        }else{
            addClient($conn,$nif,$nombre,$apellido,$cp,$direc,$ciu);
        }
        $conn=closeConn($conn);
    }else{
        echo "Por favor, introduzca un calor correcto </br>";
    }
    
}
?>