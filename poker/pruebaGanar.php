<?php

jugarPartida();
//funcion validar diferente compatible-----------------------------
function obtenerNombreJugadores(){
    $arrayNombreJugadores = array();
    foreach ($_POST as $key => $values) {
        if (substr($key,0,3)=="nom" && !empty(test_input($values))) {
            array_push($arrayNombreJugadores, $values);
        }
    }
    //echo "<pre>";var_dump($arrayNombreJugadores);echo "</pre>";
    return $arrayNombreJugadores; //retorna un array con los nombres de los jugadores cogidos por el formulario
}

function jugarPartida()
{
    $todoslosJugadoresConCartas = jugadoresYcartas();
    $puntuacionesJugadores = calcularPuntuacion($todoslosJugadoresConCartas);
    //echo "<pre>";var_dump($puntuacionesJugadores);echo "</pre>";
    //ganador 
    $ganadores = "";
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
    } else if ($puntuacionGanador == 2) {
        echo "Gana Jugada de Trio <br>";
    } else if ($puntuacionGanador == 3) {
        echo "Gana Jugada de Doble Pareja <br>";
    } else if ($puntuacionGanador == 4) {
        echo "Jugada de  Pareja  <br>";
    } else {
        echo "Nadie ha sacado nada o han sacado pareja.No se reparte bote <br>";
    }
    //segundo filtro de ganadores
    $ganadorSegundoFiltro = "";
    if ($contadorGanadores > 1) {
        $array = explode(",", $ganadores);
        echo "<pre>";
        //var_dump($puntuacionesJugadores);
        echo "</pre>";
        $puntuacionesJugadoresSegundaRonda = calcularSegundaPuntuacion($todoslosJugadoresConCartas);
        for ($i = 0; $i < count($array); $i++) {
            $ganador = $array[$i];
            foreach ($puntuacionesJugadoresSegundaRonda as $key => $values) {
                if (str_contains($ganador, $key)) {
                    if (str_contains($values, "AS")) {
                        $ganadorSegundoFiltro = $key;
                    } else if (str_contains($values, "K")) {
                        $ganadorSegundoFiltro = $ganador;
                    } else if (str_contains($values, "Q")) {
                        $ganadorSegundoFiltro = $ganador;
                    } else if (str_contains($values, "J")) {
                        $ganadorSegundoFiltro = $ganador;
                    }
                }
            }
        }

        echo $ganadorSegundoFiltro . "<------------ ganador segundo filtro";
    }
}
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
        echo "<pre>";
        var_dump($repeticiones);
        echo "</pre>";
        //modificar la key de este array para identificarlos con la putuacion 
        //primer filtro de ganadores
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
    //echo "<pre>";var_dump($puntuacionesJugadores);echo "</pre>";
    return $puntuacionesJugadores; //retorna array asociativo de las puntuaciones de jugadores cuya key es la letra y el valor lo que ha sacado de puntuacion
}
function calcularSegundaPuntuacion($todosLosJugadoresConCartas)
{
    $puntuacionesJugadoresSegundaRonda = array();
    foreach ($todosLosJugadoresConCartas as $key => $values) {
        $arrayTemporalPaloCartas = array();
        foreach ($values as $carta) {
            $paloCarta = $carta[0];
            if ($paloCarta == "1") {
                $paloCarta = "A"; //para asi poder ordenar por claves de mayor a menor con ksort()
            }
            array_push($arrayTemporalPaloCartas, $paloCarta);
        }
        $repeticionesPalos = array_count_values($arrayTemporalPaloCartas);

        ksort($repeticionesPalos);

        if ($repeticionesPalos[array_key_first($repeticionesPalos)] == "1") {
            $puntuacionesJugadoresSegundaRonda[$key] = "Palo Ganador AS";
        } else if ($repeticionesPalos[array_key_first($repeticionesPalos)] == "K") {
            $puntuacionesJugadoresSegundaRonda[$key] = "Palo Ganador K";
        } else if ($repeticionesPalos[array_key_first($repeticionesPalos)] == "Q") {
            $puntuacionesJugadoresSegundaRonda[$key] = "Palo Ganador Q";
        } else {
            $puntuacionesJugadoresSegundaRonda[$key] = "Palo Ganador J";
        }
    }
    echo "<pre>";
    //var_dump($puntuacionesJugadoresSegundaRonda);
    echo "</pre>";
    return $puntuacionesJugadoresSegundaRonda;
}
function jugadoresYcartas()
{
    $jugadoresYcartas = array();
    $nombresJugadores = ["Angi", "Joe", "Angel", "Catalina"];
    $arrayDeCartas = leerDirectorioCartas();
    shuffle($arrayDeCartas);
    $cartasRepartidas = repartirCartas(4, $arrayDeCartas);
    for ($i = 0; $i < count($nombresJugadores); $i++) {
        $jugadoresYcartas[$nombresJugadores[$i]] = $cartasRepartidas[$i];
    }
    imprimirCartasPorJugadores($jugadoresYcartas);
    // echo "<pre>";
    // var_dump($jugadoresYcartas);
    // echo "</pre>";
    return $jugadoresYcartas;
}
function leerDirectorioCartas()
{
    $directorio = 'images';
    $cartasEnArray  = scandir($directorio);
    unset($cartasEnArray[0], $cartasEnArray[1]);
    return $cartasEnArray; //array de imagenes eliminando las primers posiciones que indican directorio
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
