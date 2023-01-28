<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Consulta de Stock</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        include("functions.php");
        $conn = connection();
        $products = getNamesOfProduct($conn);
        ?>
        <label for="products">Products</label>
        <select name="products">
            <?php foreach ($products as $product => $value) : ?>
                <option value="<?php echo $value['ID_PRODUCTO'] ?>"> <?php echo $value['NOMBRE'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <label for="name">Cantidad y Alamacén</label>
        <br /><br />
        <input type="submit" name="submit" id="submit" value="Consultar stock">
    </form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"])) {
        $product = $_POST['products'];
        $resultados = getTotalProducts($conn, $product);
        $total = 0;

        echo '</br>';

        foreach ($resultados as $resultado => $value) {
            echo "Hay " . $value['CANTIDAD'] . " en la localidad de " . $value['LOCALIDAD'];
            echo "</br>";

            $total += $value['CANTIDAD'];
        }
        $conn=closeConn($conn);
        echo '</br>TOTAL: ' . $total;
    } else {
        echo "Por favor introduza y seleccione valores correctos";
    }
}
?>