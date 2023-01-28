<?php
include("functions.php");
if (!userCookieExists() ) {
    echo "<script>location.href='comlogincli.php';</script>";
}
?>
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
    <h1>Consulta de Compras</h1>
    <br/><br/>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        $conn = connection();
        echo '<h1>Consulta tus Compras !</h1>';
        ?>
        <br/><br/>
        <div class="container">
            <label for="">Desde
            <input type="text" name="date1" id="date1">
            </label>
        </div>
        <br/><br/>
        <div class="container">
            <label for="">Hasta
            <input type="text" name="date2" id="date2">
            </label>
        </div>
        <br/><br/>
        <div class="container">
            <input type="submit" name="submit" id="submit">
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
    if (isset($_POST["submit"]) && !empty($_POST['date1']) && !empty($_POST['date2'])) {
        //aqui iria  $_POST['DNI'];
        echo $_SESSION['DNI'];
        $result = getPurchaseInfo($conn, $_SESSION['DNI'], test_input($_POST['date1']), test_input($_POST['date2']));
        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        printPurchaseInfo($result);
        $conn = closeConn($conn);

    }else if (isset($_POST["logout"])) {
        closeCookieBasket();
        closeCookieLogOut();
        echo "<script>location.href='comlogincli.php';</script>";
    } else {
        echo "Por favor rellene y seleccione los campos necesarios <br>.";
    }
}
?>