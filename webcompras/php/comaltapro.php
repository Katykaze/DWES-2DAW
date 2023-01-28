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
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name">
        <br /><br />

        <label for="price">Precio</label>
        <input type="text" name="price" id="price">
        <br /><br />
        <?php
        include("functions.php");
        $conn = connection();
        $categories = getNamesOfCategories($conn);

        ?>
        <div>
            <!-- añadir como value el id_categoria -->
            <label for="catego">Listado de Categorías:</label>
            <select name="categories">
                <?php foreach ($categories as $categorie => $value) : ?>
                    <option value="<?php echo $value['ID_CATEGORIA'] ?>"> <?php echo $value['NOMBRE'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="submit" name="submit" id="submit" value="Dar de alta">
    </form>
</body>

</html>


<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"])) {
        addProduct($conn, test_input($_POST['name']), $_POST['categories'], test_input($_POST['price']));
        $conn=closeConn($conn);
    }else{
        echo "Por favor, introduzca valores correctos";
    }
}
?>