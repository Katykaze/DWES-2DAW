<?php

//funcion de conexion
function connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "COMPRASWEB";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}

function closeConn($conn)
{
    return $conn = null;
}

// ------------------ EJERCICIO 1 ------------------

//funcion para agregar la categoria a la base de datos
function addCategory($name, $conn)
{
    try {
        $category_code = generateCategoryId($conn);
        $sql = $conn->prepare("INSERT INTO categoria (ID_CATEGORIA,NOMBRE) VALUES (:idcategoria,:nombre)");
        $sql->bindParam(':idcategoria', $category_code);
        $sql->bindParam(':nombre', $name);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para generar el codigo de la categoria
function generateCategoryId($conn)
{
    try {
        $sql = $conn->prepare("SELECT MAX(ID_CATEGORIA) FROM CATEGORIA");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res = $sql->fetchAll();
        echo '<pre>';
        var_dump($res);
        echo '</pre>';
        $id = intval(substr($res[0]['MAX(ID_CATEGORIA)'], 2)) + 1;  // NULL + 1 = 1 en php

        $category_code = "C-" . str_pad($id, 3, '0', STR_PAD_LEFT);

        return $category_code;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}

// ------------------ EJERCICIO 2 ------------------

//funcion para agregar el producto a la base de datos
function addProduct($conn, $name, $category, $price)
{
    try {
        $category_string = $category;

        $product_code = generateProductCod($conn);
        $sql = $conn->prepare("INSERT INTO PRODUCTO (ID_PRODUCTO, NOMBRE, PRECIO, ID_CATEGORIA) VALUES (:idproducto,:nombre,:precio,:idcategoria)");
        $sql->bindParam('idproducto', $product_code);
        $sql->bindParam('nombre', $name);
        $sql->bindParam('precio', $price);
        $sql->bindParam('idcategoria', $category_string);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para generar desplegable de categorias
function getNamesOfCategories($conn)
{
    try {
        $stmt = $conn->prepare("SELECT ID_CATEGORIA,NOMBRE FROM CATEGORIA");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

//funcion para generar el codigo del producto
function generateProductCod($conn)
{
    try {
        $sql = $conn->prepare("SELECT MAX(ID_PRODUCTO) FROM PRODUCTO");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res = $sql->fetchAll();

        $id = intval(substr($res[0]['MAX(ID_PRODUCTO)'], 1)) + 1;

        $product_code = "P" . str_pad($id, 4, '0', STR_PAD_LEFT);

        return $product_code;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}

//------------ EJERCICIO 3 ------------

function addStorage($conn, $localidad)
{
    try {
        $sql = $conn->prepare("INSERT INTO ALMACEN (LOCALIDAD) VALUES (:localidad)");
        $sql->bindParam('localidad', $localidad);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

// ------------ EJERCICIO 4 ------------
function isProduct($conn, $warehouse, $product)
{
    try {
        $valid = true;

        $sql = $conn->prepare("SELECT ID_PRODUCTO FROM ALMACENA WHERE ALMACENA.ID_PRODUCTO=:product AND
        ALMACENA.NUM_ALMACEN=:warehouse");
        $sql->bindParam('product', $product);
        $sql->bindParam('warehouse', $warehouse);
        $sql->execute();
        /*$sql->setFetchMode(PDO::FETCH_NUM);
        $resultado = $sql->fetchAll();
        $resultado = $resultado[0][0];
        if ($resultado <= 0) {
            $valid = false;
        }*/
        if ($sql->rowCount() == 0) {
            $valid = false;
        }
        return $valid;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}

function addProductsStorage($conn, $warehouse, $product_id, $quantity)
{
    try {
        //he usado ignore para que si existe problemas de clave primarias, vaya a la siguiente peticion y busque los valores para aprovisionar
        $sql = $conn->prepare(
            "INSERT INTO ALMACENA (NUM_ALMACEN, ID_PRODUCTO, CANTIDAD) VALUES (:num_warehouse, :product_id, :quaintity)"
        );
        $sql->bindParam('num_warehouse', $warehouse);
        $sql->bindParam('product_id', $product_id);
        $sql->bindParam('quaintity', $quantity);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

function updateProduct($conn, $warehouse, $product_id, $quantity)
{
    try {
        $sql = $conn->prepare("SELECT CANTIDAD FROM ALMACENA WHERE ALMACENA.ID_PRODUCTO=:product_id AND
        ALMACENA.NUM_ALMACEN=:num_warehouse");
        $sql->bindParam('num_warehouse', $warehouse);
        $sql->bindParam('product_id', $product_id);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_NUM);
        $resultado = $sql->fetchAll();
        var_dump($resultado);
        $resultado = $resultado[0][0];
        $resultado = intval($resultado) + intval($quantity);

        $sql = $conn->prepare("UPDATE ALMACENA SET CANTIDAD=:resultado WHERE ALMACENA.ID_PRODUCTO=:product_id AND
        ALMACENA.NUM_ALMACEN=:num_warehouse");
        $sql->bindParam('num_warehouse', $warehouse);
        $sql->bindParam('product_id', $product_id);
        $sql->bindParam('resultado', $resultado);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para generar desplegable de los almacenes
function getWarehouse($conn)
{
    try {
        $stmt = $conn->prepare("SELECT NUM_ALMACEN,LOCALIDAD FROM ALMACEN");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

//funcion para generar desplegable de los productos
function getProducts($conn)
{
    try {
        $stmt = $conn->prepare("SELECT ID_PRODUCTO,NOMBRE FROM PRODUCTO");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

//------------ EJERCICIO 5 ------------

function getNamesOfProduct($conn)
{
    try {
        $sql = $conn->prepare("SELECT ID_PRODUCTO,NOMBRE FROM PRODUCTO");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $sql->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

// se mostrará la cantidad disponible del producto seleccionado en cada uno de los almacenes.
function getTotalProducts($conn, $product)
{
    try {
        //poner numero de almacen que es su clave identificacion
        $sql = $conn->prepare("SELECT CANTIDAD,LOCALIDAD 
                            FROM PRODUCTO,ALMACENA,ALMACEN 
                            WHERE ALMACENA.ID_PRODUCTO=PRODUCTO.ID_PRODUCTO 
                            AND ALMACENA.NUM_ALMACEN=ALMACEN.NUM_ALMACEN 
                            AND PRODUCTO.ID_PRODUCTO=:producto
                            AND ALMACENA.CANTIDAD > 0");
        $sql->bindParam('producto', $product);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $sql->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}

// ------------ EJERCICIO 6 ------------

function getWarehouseInfo($conn, $warehouse)
{
    try {
        $stmt = $conn->prepare("SELECT PRODUCTO.NOMBRE, CANTIDAD, ALMACEN.NUM_ALMACEN, ALMACEN.LOCALIDAD
                                FROM PRODUCTO, ALMACENA, ALMACEN 
                                WHERE PRODUCTO.ID_PRODUCTO = ALMACENA.ID_PRODUCTO 
                                AND ALMACENA.NUM_ALMACEN = ALMACEN.NUM_ALMACEN AND ALMACEN.NUM_ALMACEN=:warehouse");

        $stmt->bindparam('warehouse', $warehouse);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}

function printWarehouseInfo($conn, $warehouse)
{
    $res = getWarehouseInfo($conn, $warehouse);

    if (count($res) != 0) {
        echo '<br/>';
        echo 'En el almacen localizado en ' . ucfirst(strtolower($res[0]['LOCALIDAD'])) . ' contiene los siguientes productos:';
        echo '<ul>';

        foreach ($res as $product => $value) {
            if ($value['NUM_ALMACEN'] == $warehouse) {
                echo '<li>';
                echo $value['NOMBRE'] . ' -> CANTIDAD: ' . $value['CANTIDAD'];
                echo '</li>';
            }
        }

        echo '</ul>';
    } else {
        echo "En esta localidad no hay productos dados de alta";
    }
}

// ------------ EJERCICIO 7 ------------

// POR TERMINAR POR FALTA DE EXPLICACION DE COOKIES

function getDnies($conn)
{
    try {
        $sql = $conn->prepare("SELECT NIF FROM CLIENTE");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $sql->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        echo $e->getCode();
        return [];
    }
}

function getPurchaseInfo($conn, $dni, $date1, $date2)
{
    try {
        $sql = $conn->prepare("SELECT PRODUCTO.NOMBRE,PRODUCTO.PRECIO,COMPRA.FECHA_COMPRA,COMPRA.UNIDADES FROM PRODUCTO,COMPRA WHERE
        PRODUCTO.ID_PRODUCTO=COMPRA.ID_PRODUCTO AND COMPRA.NIF=:dni AND COMPRA.FECHA_COMPRA between :date1 AND :date2");
        $sql->bindParam('dni', $dni);
        $sql->bindParam('date1', $date1);
        $sql->bindParam('date2', $date2);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sql->fetchAll();
        $total = 0.0;
        foreach ($result as $key => $value) {
            $total += floatval($value['PRECIO']) * floatval($value['UNIDADES']);
        }
        //$result['total']=$total; //
        return array($result, $total);

    } catch (PDOException $e) {
        echo $e->getCode();
        return [];
    }
}

function printPurchaseInfo($result)
{
    foreach ($result[0] as $key => $value) {

        echo "Fecha de Compra: " . $value['FECHA_COMPRA'] . "</br>";
        echo "Nombre del producto: " . $value['NOMBRE'] . "</br>";
        echo "Precio del producto: " . $value['PRECIO'] . "</br></br>";

    }
    echo "El total de sus compras asciende a: " . $result[1] . " euros";
}

// ------------ EJERCICIO 8 ------------

function isValidDni($nif)
{
    $valido = true;
    $letra = substr($nif, 8);
    $numeros = substr($nif, 0, 7);
    if (strlen($nif) != 9) {
        echo "Error. La longitud no es la correcta. No es posible dar de alta</br>";
        $valido = false;
    } else if (!ctype_alpha($letra)) {
        echo "Error, el último carácter debe de ser una letra</br>";
        $valido = false;
    } else if (!is_numeric($numeros)) {
        echo "Error, debe de ser 8 digitos.</br>";
        $valido = false;
    }
    return $valido;
}

function addClient($conn, $nif, $nombre, $apellido, $cp, $direc, $ciu)
{
    try {
        $sql = $conn->prepare("INSERT INTO CLIENTE (NIF,NOMBRE,APELLIDO,CP,DIRECCION,CIUDAD) VALUES (:nif,:nombre,:apellido,:cp,
    :direccion,:ciudad)");
        $sql->bindParam('nif', $nif);
        $sql->bindParam('nombre', $nombre);
        $sql->bindParam('apellido', $apellido);
        $sql->bindParam('cp', $cp);
        $sql->bindParam('direccion', $direc);
        $sql->bindParam('ciudad', $ciu);
        $sql->execute();
        echo "Se ha dado de alta al cliente</br>";
    } catch (PDOException $e) {
        $error = $e->getCode();
        if ($error = '2300') {
            echo "DNI EXISTENTE. NO SE PUEDE DAR DE ALTA <BR>";
        }
    }
}

// ------------ EJERCICIO 9 ------------
//Compra de Productos (compro.php): el cliente podrá realizar la compra de un solo producto
// siempre que haya disponibilidad del mismo.

function isDniClient($conn, $dni)
{
    $valid = true;
    $sql = $conn->prepare("SELECT NIF FROM CLIENTE WHERE CLIENTE.NIF=:dni");
    $sql->bindParam('dni', $dni);
    $sql->execute();
    if ($sql->rowCount() == 0) {
        $valid = false;
    }
    return $valid;
    // mysql_num_rows
}

function trackPurchase($conn, $dni, $product, $quantity)
{
    $valido = true;
    try {
        $fecha = new DateTime();
        $stringFecha = $fecha->format("Y-m-d");
        $sql = $conn->prepare("INSERT INTO COMPRA (NIF,ID_PRODUCTO,FECHA_COMPRA,UNIDADES) VALUES (:nif,:id_producto,:fecha,:unidades)");
        $sql->bindParam('nif', $dni);
        $sql->bindParam('id_producto', $product);
        $sql->bindParam('fecha', $stringFecha);
        $sql->bindParam('unidades', $quantity);
        $sql->execute();
        echo "Se ha realizado su compra satisfactoriamente</br>";
    } catch (PDOException $e) {
        $valido = false;
        $error = $e->getCode();
        if ($error = '2300') {
            echo "ESTE DNI YA HA REALIZADO COMPRA <BR>";
        }
    }
    return $valido;
}

function isQuantityEnough($conn, $product, $cantidad)
{
    $valid = true;
    //$result = getTotalProducts($conn, $product);
    $total = getWarehousesTotalQuantity($conn, $product);

    if ($total == 0) {
        $valid = false;
        echo "No hay disponibilidad del procuto</br>";
    }
    if (!is_numeric($cantidad)) {
        $valid = false;
        echo "Por favor introduzca una cantidad correcta</br>";
    } else if ($cantidad > $total) {
        $valid = false;
        echo "No hay existencias suficientes</br>";
    }

    return $valid;
}

function getWarehousesTotalQuantity($conn, $product)
{
    try {
        $sql = $conn->prepare("SELECT * FROM almacena where almacena.id_producto = :id_producto");
        $sql->bindParam('id_producto', $product);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res = $sql->fetchAll();

        $total = 0;

        foreach ($res as $key => $value) {
            $total += $value['CANTIDAD'];
        }

        return $total;

    } catch (PDOException $e) {
        $error = $e->getCode();
        echo 'ERROR: ' . $error;
        return [];
    }
}

function performPurchase($conn, $product, $quantity)
{
    try {
        $sql = $conn->prepare("SELECT * FROM almacena where almacena.ID_PRODUCTO = :id_producto AND almacena.CANTIDAD > 0");
        $sql->bindParam('id_producto', $product);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);

        $warehouse = $sql->fetchAll();
    } catch (PDOException $e) {
        $error = $e->getCode();
        echo 'ERROR: ' . $error;
    }

    $i = 0;
    $cantidadAlmacen = $warehouse[$i]['CANTIDAD'];

    while ($quantity - $cantidadAlmacen > 0) {
        updateTableAlmacena($conn, $product, 0, $warehouse[$i]['NUM_ALMACEN']);

        $quantity = $quantity - $cantidadAlmacen;

        $i++;
        $cantidadAlmacen = $warehouse[$i]['CANTIDAD'];
    }

    $quantity = $cantidadAlmacen - $quantity;
    updateTableAlmacena($conn, $product, $quantity, $warehouse[$i]['NUM_ALMACEN']);
}

function updateTableAlmacena($conn, $product, $quantity, $warehouse_num)
{
    try {
        $stmt = $conn->prepare("UPDATE ALMACENA SET CANTIDAD=:new_quantity WHERE ALMACENA.ID_PRODUCTO=:producto AND ALMACENA.NUM_ALMACEN = :warehouse_num");
        $stmt->bindparam('new_quantity', $quantity);
        $stmt->bindparam('producto', $product);
        $stmt->bindparam('warehouse_num', $warehouse_num);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//SEGUNDA PARTE : SESIONES Y COOKIES----------------------------
//Ejercicio 1 -----------------------------
//si no existe registro se llamará a esta función
function createUser($conn, $nif, $nombre, $apellido)
{
    try {
        $contrasena = strtolower(strrev($apellido));
        $stmt = $conn->prepare("INSERT INTO CLIENTE (NIF,NOMBRE,APELLIDO,contrasena) VALUES (:nif,:nombre,:apellido,:contrasena)");
        $stmt->bindparam('nif', $nif);
        $stmt->bindparam('nombre', $nombre);
        $stmt->bindparam('apellido', $apellido);
        $stmt->bindparam('contrasena', $contrasena);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }

}

//EJERCICIO 2-------------------------------

//funcion cuando ya existe el usuario y quiere logearse
function getDniIfExists($conn, $user, $password)
{
    //comprobar que existe nombre y contraseña
    try {

        $stmt = $conn->prepare("SELECT NIF FROM CLIENTE WHERE NOMBRE = :user AND contrasena = :password");

        $stmt->bindParam('user', $user);
        $stmt->bindParam('password', $password);
        $stmt->execute();
        echo "a";
        if ($stmt->rowCount() == 0) {
            return 0;
        } else {
            $stmt->setFetchMode(PDO::FETCH_NUM);
            $result = $stmt->fetchAll();
            return $result[0][0];
        }

    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
        return [];
    }
}


//funcion para crear cookie para la cesta de compra
function createBasketCookie()
{

//    aqui meter serialize????

    setcookie('basket' . $_SESSION['DNI'], 'holi', time() + (86400 * 30), "/"); // 86400 segundos = 1 día


}

function basketCookieExists()
{
    if (isset($_COOKIE['basket' . $_SESSION['DNI']])) {
        return true;
    } else {
        return false;
    }
}

//funcion para control de inicio de sesion
function isUserLogged()
{
    session_start();
    if (isset($_SESSION['DNI'])) {
        return true;
    } else {
        return false;
    }

}

//funcion para tratar los datos
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
