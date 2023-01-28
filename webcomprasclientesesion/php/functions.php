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


// ------------ EJERCICIO 7 ------------

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
//funcion primero para controlar si puede crearse un nuevo usuario segun si ya
// está dado de alta con su dni en la base de datos
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

//si no existe registro se creará el usuario
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

//funcion llamada cuando se logea el usuario
//y comprobamos primero que su nombre y contraseña se encuentran en la base de datos
//nos devolvera el dni que usaremos para inicar la sesion
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

//funcion boolean de inicio de sesion
function isUserLogged()
{
    session_start();
    if (isset($_SESSION['DNI'])) {
        return true;
    } else {
        return false;
    }

}

//funcion para cerrar sesion
function cerrarSesion()
{
    $_SESSION = array();
    unset($_SESSION);
    session_destroy();
//// Unset all of the session variables.
//// Unset all of the session variables.
//    $_SESSION = array();
//// If it's desired to kill the session, also delete the session cookie.
//// Note: This will destroy the session, and not just the session data!
//    if (ini_get("session.use_cookies")) {
//        $params = session_get_cookie_params();
//        setcookie(session_name(), '', time() - 42000,
//            $params["path"], $params["domain"],
//            $params["secure"], $params["httponly"]
//        );
//    }
//// Finally, destroy the session.
//    session_destroy();
}

//funcion para tratar los datos
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
