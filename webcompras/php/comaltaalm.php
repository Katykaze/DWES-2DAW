<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Alta Almacen</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for=" localidad">Localidad</label>
        <input type="text" name="localidad" id="localidad">
        <br><br>
        <input type="submit" name="submit" id="submit" value="Dar de alta">
    </form>
</body>

</html>


<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST["submit"])) {
        $localidad = test_input($_POST['localidad']);
        $conn = connection();
        addStorage($conn, $localidad);
        $conn=closeConn($conn);
    }else{
        echo "Por favor introduza un calor correcto";
    }

}
?>