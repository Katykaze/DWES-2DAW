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
    include("functions.php");
    $conn = connection();
    $clients = getDnies($conn);
    $products = getNamesOfProduct($conn);
    ?>
    <div class="container">
        <label for="name">Usuario</label>
        <select name="dnies">
            <?php foreach ($clients as $client => $value) : ?>
                <option value="<?php echo $value['NIF'] ?>">
                    <?php echo $value['NIF'] ?> </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br/><br/>
    <div class="container">
        <label for="">Desde</label>
        <input type="text" name="date1" id="date1">
    </div>
    <br/><br/>
    <div class="container">
        <label for="">Hasta</label>
        <input type="text" name="date2" id="date2">
    </div>
    <br/><br/>
    <div class="container">
        <input type="submit" name="submit" id="submit">
    </div>

</form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"]) && !empty($_POST['date1']) && !empty($_POST['date2'])) {

        $result = getPurchaseInfo($conn, $_POST['DNI'], test_input($_POST['date1']), test_input($_POST['date2']));
        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        printPurchaseInfo($result);
        $conn = closeConn($conn);

    } else {
        echo "Por favor rellene y seleccione los campos necesarios <br>.";
    }
}
?>