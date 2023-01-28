<?php
include("functions.php");
if (!isUserLogged()) {
    echo "<script>location.href='comlogincli.php';</script>";
}
echo '<pre<'; var_dump($_SESSION); echo '</pre>';?>
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/index.css">
        <link rel="stylesheet" href="../css/BOOTSTRAP.MIN.CSS">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <title>Document</title>
    </head>

    <body>
    <h1>Compra de Productos</h1>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        $conn = connection();
        $products = getNamesOfProduct($conn);
        echo '<h1>LLena tu carrito de la Compra ' . $_SESSION['userName'] . ' !</h1>';
        ?>
        <br/><br/>
        <div class="container">
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
        <i class="bi bi-cart-plus" style="font-size: 40px"></i>
            <input type="submit" name="carrito" id="carrito" value="Añadir al Carrito">
        </div>
        <div class="container">
            <input type="submit" name="submit" id="submit" value="Comprar">
        </div>
        <div class="container">
        <input type="submit" name="logout" value="Cerrar Sesion"/>
        </div>
    </form>
    <br/><br/>
    </body>
    </html>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = $_POST['products'];
    $quantity = test_input($_POST['cantidad']);
    $conn = connection();
    $basket=$_SESSION['cart'];
    if (isset($_POST["carrito"])) {
        //comprobamos si en el carrito existe ya el producto, su id es  la key del array asociativo
        //si es asi se suma la cantidad
        //si no se mete el producto nuevo añadido
        if(array_key_exists($product, $basket)){
            $basket[$product] += intval($quantity);
            echo 'Producto añadido al carrito';
        }else{
            $basket[$product]=$quantity;
        }
        $_SESSION['cart'] = $basket;
        echo '<pre>'; var_dump($basket); echo '</pre>';

    } else if (isset($_POST["submit"])) {
       echo '<pre>'; var_dump($basket); echo '</pre>';
        foreach ($basket as $key => $value) {
            if (isQuantityEnough($conn, $key, $value)) {
                performPurchase($conn, $key, $value);
                trackPurchase($conn, $_SESSION['DNI'], $key, $value);
                //una vez hecha la compra dejamos el carrito en blanco asignándole un array vacio
                $_SESSION['cart']=array();
                echo "</br>Actualizado tabla Almacena con nueva cantidad";
                echo "</br>Actualizado tabla Compra con nueva registro";
                echo "</br>Carrito Limpio ";

            }
        }
        $conn = closeConn($conn);

    } else if(isset($_POST["logout"])){
        cerrarSesion();
        echo "<script>location.href='comlogincli.php';</script>";
    }
}

?>