<?php
jugarPartidaPoker();
function jugarPartidaPoker()
{
    $arrayNombreJugadores = obtenerNombreJugadores();
    $bote = obtenerCantidadDeBoteDelFormulario();
    if (count($arrayNombreJugadores) < 4 || count($arrayNombreJugadores) > 8 || empty($bote)) {
        echo ("<script>window.location='pokerldv.html'</script>");
    } else {
        $arrayDeCartas = leerDirectorioCartas();
        shuffle($arrayDeCartas);
        $cartasRepartidas = repartirCartas(count($arrayNombreJugadores), $arrayDeCartas);
        $jugadoresYCartas = array();
        for ($i = 0; $i < count($arrayNombreJugadores); $i++) {
            $jugadoresYCartas[$arrayNombreJugadores[$i]] = $cartasRepartidas[$i];
        }
        //echo "<pre>";var_dump($jugadoresYCartas);echo "</pre>";

        imprimirCartasPorJugadores($jugadoresYCartas);
        $puntuacionesJugadores = calcularPuntuacion($jugadoresYCartas);
        echo "<pre>";
        var_dump($puntuacionesJugadores);
        echo "</pre>";
        $ganadores = "";
        //ganador 
        $contadorGanadores = 0;
        $puntuacionGanador = min($puntuacionesJugadores);
        foreach ($puntuacionesJugadores as $keys => $values) {
            if ($values == $puntuacionGanador) {
                $contadorGanadores++;
                $ganadores .= $keys . " ";
            }
        }
        //echo $puntuacionGanador."<br>";
        echo "Ganador/es =  " . $ganadores . " <br>";
        echo "Hay un total de Ganador/es " . $contadorGanadores . "<br>";

        if ($puntuacionGanador == 1) {
            echo "Gana Jugada de Poker <br>";
            echo "Se reparte todo el bote. Toca " . $bote / $contadorGanadores . " euros por cada jugador";
        } else if ($puntuacionGanador == 2) {
            echo "Gana Jugada de Trio <br>";
            echo "Se reparte el 70% del bote. Toca " . (70 * $bote / 100) / $contadorGanadores . " euros por cada jugador";
        } else if ($puntuacionGanador == 3) {
            echo "Gana Jugada de Doble Pareja <br>";
            echo "Se reparte el 50% del bote. Toca " . (50 * $bote / 100) / $contadorGanadores . " euros por cada jugador";
        } else if ($puntuacionGanador == 4) {
            echo "Jugada de  Pareja  <br>";
            echo "No se reparte el bote. Se lo queda la banca. ";
        } else {
            echo "Nadie ha sacado nada o han sacado pareja.No se reparte bote <br>";
        }
        crearDocumentoTxt($jugadoresYCartas, $puntuacionesJugadores);
        //leerFichero();
        $file = file("jugadoresYcartas2.txt");
        $parametro = "katherine";
        $posicionInicial = buscarFila($file, $parametro);
        var_dump(explode("###",$posicionInicial));
        //$campo = encontrarPosicionesCampos($file, $posicionInicial);
        //echo $campo;
    }
}

/*
    Puntuaciones:
    el de menor puntuacion es el que gana
    1 - Poker
    2 - Trio
    3 - Doble Pareja
    4 - Pareja
    5 - Nada
*/
function calcularPuntuacion($todosLosJugadoresConCartas)
{
    $puntuacionesJugadores = array();
    foreach ($todosLosJugadoresConCartas as $key => $values) {
        //echo $key;
        $arrayTemporalCartas = array();
        foreach ($values as $carta) {
            $letraCarta = substr($carta, 0, 1);
            array_push($arrayTemporalCartas, $letraCarta);
        }
        $repeticiones = array_count_values($arrayTemporalCartas); // nos devuelve como key la letra de la carta y como values la cantidad que aparece
        //de todas las cartas de los jugadores
        //echo "<pre>";var_dump($repeticiones);echo "</pre>";
        //modificar la key de este array para identificarlos con la putuacion 
        $longitud = count($repeticiones);
        if ($longitud == 1) {
            $puntuacionesJugadores[$key] = 1;
        } else if ($longitud == 3) { //porque una pareja seria 1 +1 carta diferente + 1 carta diferente
            $puntuacionesJugadores[$key] = 4;
        } else if ($longitud == 4) { //longitud de 4 porque todas son diferentes
            $puntuacionesJugadores[$key] = 5;
        } else if ($longitud == 2) { //si aparece 2 quiere decir que o es doble pareja o trio
            if ($repeticiones[array_key_first($repeticiones)] == 2) { //si son 4 cartas y las primeras son dos, solo puede ser doble pareja
                $puntuacionesJugadores[$key] = 3;
            } else {
                $puntuacionesJugadores[$key] = 2; //seria trio
            }
        }
    }
    return $puntuacionesJugadores; //retorna array asociativo de las puntuaciones de jugadores cuya key es la letra y el valor lo que ha sacado de puntuacion
}


function repartirCartas($numeroDeJugadores, $arrayDeCartas)
{
    $cartasRepartidas = array();

    for ($i = 0; $i < $numeroDeJugadores; $i++) {
        //aprovechando la posicion y sabiendo que es de 4 en 4 => 4,8,12,16
        array_push($cartasRepartidas, array_slice($arrayDeCartas, $i * 4, 4));
    }
    //echo "<pre>";var_dump($cartasRepartidas);echo "</pre>";
    return $cartasRepartidas; //devuelve array indexado con todas las cartas de cada jugador (por posicion)
}

function leerDirectorioCartas()
{
    $directorio = 'images';
    $cartasEnArray  = scandir($directorio);
    unset($cartasEnArray[0], $cartasEnArray[1]);
    return $cartasEnArray; //array de imagenes eliminando las primers posiciones que indican directorio
}
function obtenerNombreJugadores()
{
    $arrayNombreJugadores = array();

    foreach ($_POST as $key => $values) {
        if (str_contains($key, "nombre") && !empty(test_input($values))) {
            array_push($arrayNombreJugadores, $values);
        }
    }
    //echo "<pre>";var_dump($arrayNombreJugadores);echo "</pre>";
    return $arrayNombreJugadores; //retorna un array con los nombres de los jugadores cogidos por el formulario
}
function obtenerCantidadDeBoteDelFormulario()
{
    $bote = test_input($_POST["bote"]);
    if (!empty($bote)) {
        return $bote; //nos da el numero en cantidad de bote
    }
}
function imprimirCartasPorJugadores($todosLosJugadorConCartas)
{
    foreach ($todosLosJugadorConCartas as $key => $values) {
        echo $key . "<br>";
        foreach ($values as $carta) {
            echo "<img src='./images/$carta'WIDTH='100' HEIGHT='100'>";
        }
        echo "<br>";
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


//----------- funcion para incluir los jugadores  con sus cartas en un archivo txt---------------------------
function crearDocumentoTxt($jugadoresYCartas, $puntuacionesJugadores)
{
    $puntuaciones = array();
    foreach ($puntuacionesJugadores as $key => $value) {
        array_push($puntuaciones, $value);
    }
    //echo "<pre>";var_dump($puntuaciones);echo "</pre>";
    $titulos = ["Nombre", "Carta1", "Carta2", "Carta3", "Carta4", "Puntuacion"];
    $file = fopen("jugadoresYCartas.txt", "a");
    for ($i = 0; $i < count($titulos); $i++) {
        fwrite($file, $titulos[$i] . " ");
    }
    $stringbreak = "\n";
    fwrite($file, $stringbreak);
    foreach ($jugadoresYCartas as $key => $values) {
        fwrite($file, $key . " "); 
            for ($i = 0; $i < count($values); $i++) {
                fwrite($file, $values[$i] . " ");
                fwrite($file, $puntuaciones[$i]);
            } 
        //---introducir aqui $puntuaciones[$i]
        fwrite($file, $stringbreak);
    }
    fclose($file);
}
//funcion para leer los jugadores y jugadas sin haberlas creado
function leerFichero()
{
    $file = fopen("jugadoresYCartas2.txt", "r");
    while (!feof($file)) {
        $fila = fgets($file);
        echo $fila;
    }
}
//funcion para encontrar campos : nombre, carta1,carta2,carta3,carta4,puntuacion
function buscarFila($file, $parametro)
{
    for ($i = 0; $i < count($file); $i++) {
        if (str_contains($file[$i], $parametro)) {
            return $file[$i];
        }
    }
    
}
