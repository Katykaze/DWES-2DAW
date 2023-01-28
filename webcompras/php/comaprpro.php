<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Alta Producto</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="quantity">Cantidad</label>
        <input type="text" name="quantity" id="quantity">
        <br /><br />

        <?php

        include("functions.php");

        $conn = connection();
        $products = getProducts($conn);
        $warehouses = getWarehouse($conn);

        ?>
        <div>
            <label for="warehouse">Lista de almacenes:</label>
            <select name="warehouse">
                <?php foreach ($warehouses as $warehouse => $value) : ?>
                    <option value="<?php echo $value['NUM_ALMACEN'] ?>"> <?php echo $value['LOCALIDAD'] ?> </option>
                <?php endforeach; ?>
            </select>

            <br />

            <label for="product">Lista productos:</label>
            <select name="product">
                <?php foreach ($products as $product => $value) : ?>
                    <option value="<?php echo $value['ID_PRODUCTO'] ?>"> <?php echo $value['NOMBRE'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br />

        <input type="submit" name="submit" id="submit" value="Dar de alta">
    </form>
</body>

</html>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"])) {
        $conn = connection();
        if (isProduct($conn, $_POST['warehouse'], $_POST['product'])) {

            updateProduct($conn, $_POST['warehouse'], $_POST['product'], $_POST['quantity']);
        } else {
            addProductsStorage($conn, $_POST['warehouse'], $_POST['product'],  $_POST['quantity']);
        }
        $conn=closeConn($conn);
    } else {
        echo "Por favor introduza y seleccione valores correctos";
    }
}

?>