<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Información almacen</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        include("functions.php");

        $conn = connection();
        $warehouses = getWarehouse($conn);

        ?>
        <div>
            <label for="warehouse">Lista de almacenes:</label>
            <select name="warehouse">
                <?php foreach ($warehouses as $warehouse => $value) : ?>
                    <option value="<?php echo $value['NUM_ALMACEN'] ?>"> <?php echo $value['LOCALIDAD'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br />

        <input type="submit" name="submit" id="submit" value="Obtener infromación">
    </form>
</body>

</html>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"])) {
        $conn = connection();

        printWarehouseInfo($conn, $_POST['warehouse']);
        $conn=closeConn($conn);
    }else{
        echo "Por favor  seleccione una localidad";
    }
}
?>