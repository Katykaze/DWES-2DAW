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
<h1>Compra de Productos</h1>
<form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <?php
    include("functions.php");
    $conn = connection();
    $products = getNamesOfProduct($conn);
    $clients = getDnies($conn);
    $products = getNamesOfProduct($conn);
    ?>
    <br/><br/>
    <div class="container">
        <label for="name">DNI Clientes
            <input type="text" name="dni">
        </label>
        <br/><br/>
        <label for="name">Producto
            <select name="products">
                <?php foreach ($products as $product => $value) : ?>
                    <option value="<?php echo $value['ID_PRODUCTO'] ?>"> <?php echo $value['NOMBRE'] ?> </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <br/><br/>
    <div class="container">
        <label for="name">Cantidad
            <input type="text" name="cantidad" id="cantidad" value="">
        </label>
        <br/><br/>
    </div>
    <div class="container">
        <input type="submit" name="submit" id="submit" value="Comprar">
    </div>
</form>
</body>

</html>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $product = $_POST['products'];
    $quantity = test_input($_POST['cantidad']);

    if (isset($_POST["submit"])) {
        // $nif = $_POST['dnies'];
        $dni = $_POST['dni'];
        $product = $_POST['products'];
        $quantity = $_POST['cantidad'];
        if (!isDniClient($conn, $dni)) {
            echo "No existe registro del dni Introducido";
        } else {
            if (isQuantityEnough($conn, $product, $quantity)) {
                performPurchase($conn, $product, $quantity);
                trackPurchase($conn, $dni, $product, $quantity); // Falla si ya hay una compra con el mismo DNI en el mismo día y el mismo producto

                echo "</br>Actualizado tabla Almacena con nueva cantidad";
                echo "</br>Actualizado tabla Compra con nueva registro";
            }
        }
    } else {
        echo "Por favor, introduzca y elija valores correcto </br>";
    }
    $conn = closeConn($conn);
}
?>