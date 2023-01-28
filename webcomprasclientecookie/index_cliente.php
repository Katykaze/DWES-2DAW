<?php
include 'php/functions.php';
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/BOOTSTRAP.MIN.CSS">
        <title>Title</title>
    </head>
    <body>
    <h1>Portal de compras web</h1>

    <?php
    echo '<h1>Bienvenido a tu Portal Web !</h1>';
    //var_dump($_SESSION);
    //print_r($_SESSION);
    //var_dump($_COOKIE);
    ?>
    <div class="container">
        <ul>
            <li><a href="php/comconscom.php">Consulta de Compras</a></li>

            <li><a href="php/compro.php">Compra de Productos</a></li>


        </ul>
    </div>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="container">
            <input type="submit" name="logout" value="Cerrar Sesion"/>
        </div>
    </form>

    </body>
    </html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["logout"])) {
        echo $_COOKIE["basket"];
        echo $_COOKIE["name"];
        closeCookieLogOut();
        closeCookieBasket();
        echo "<script>location.href='php/comlogincli.php';</script>";
    }
}
?>