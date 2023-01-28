<?php
jugar();
function jugar()
{

    $arrayNombresJugadores = recopilarNombreJugadores();
    crearCartones(recopilarCantidadCartones(), $arrayNombresJugadores);
    //creamos bombo
    $bombo = creaBombo();
    //echo "<pre>"; var_dump($bombo); echo "</pre>";
    imprimirBolas($bombo);
    //creamos jugadores con sus cartones
    $jugadores = crearJugadores(recopilarCantidadCartones(), $arrayNombresJugadores);
    //$jugadores=crearJugadores(crearCartones());
    imprimirCartones($jugadores);
    buscarGanador($jugadores, $bombo);
}

function buscarGanador($jugadores, $bombo)
{
    $nombreGanador = "";
    $bola = 0;
    $ganador = false;
    for ($i = 0; $i < count($bombo) && !$ganador; $i++) {
        $bola = $bombo[$i];
        foreach ($jugadores as $nombreJugador => $cartones) {
            for ($k = 0; $k < count($cartones) && !$ganador; $k++) {
                $posicionCartonGanador=-1;
                $posicionNumeroAcertado = -1;
                for ($j = 0; $j < count($cartones[$k]); $j++) {
                    if ($jugadores[$nombreJugador][$k][$j] == $bola) {
                        /*
                            En caso de que el número $j del cartón sea igual al número $bola del bombo
                            nos quedamos con su posición para luego eliminarlo
                        */
                        $posicionNumeroAcertado = $j;
                    }
                }
                /*
                    Si $posicionNumeroAcertado tiene un valor distinto de -1 signficia que hay que 
                    eliminar dicha posición del cartón
                */
                if($posicionNumeroAcertado != -1){
                    // Se usa unset para eliminar una posición pero hay que tener en cuenta que
                    // no reordena el array (re-indexing). Habrá posiciones nulas
                    unset($jugadores[$nombreJugador][$k][$posicionNumeroAcertado]);
                    // Hacemos re-indexing al array
                    $jugadores[$nombreJugador][$k] = array_values($jugadores[$nombreJugador][$k]);
                }
                // El ganador se obtiene en cuanto algún cartón no tiene elementos (longitud es cero)
                if (count($cartones[$k]) == 0) {
                    
                    $nombreGanador = $nombreJugador;
                    echo "Nombre del Ganador es " . $nombreGanador."tiene una longitud 0 de su array carton <br>";
                    echo "el numero de su carton es ".$k;
                    //echo "<pre>"; var_dump($jugadores[$nombreJugador]);echo "</pre>";
                    $ganador = true;
                }
            }
            //echo "<pre>";
            //echo "El carton del jugador ".$nombreJugador." es:<br>";
            //var_dump($jugadores[$nombreJugador]);
            //echo "</pre>";
        }
    }
}
function crearJugadores($numeroCartones, $arrayNombresJugadores)
{

    $jugadores = array();
    for ($i = 0; $i < count($arrayNombresJugadores); $i++) {
        $jugadores[$arrayNombresJugadores[$i]] = crearCartones($numeroCartones);
    }
    echo "<pre>";
    //var_dump($jugadores);
    echo "</pre>";
    return $jugadores;
}
function crearCartones($numeroCartones)
{
    //crer array con posiciones i

    $cartones = array();
    for ($i = 0; $i < $numeroCartones; $i++) {
        array_push($cartones, crearCarton());
    }
    //echo "<pre>"; var_dump($cartones); echo "</pre>";
    return $cartones;
}
//---------------funciones inciales basicas: crear carton, crear el jugador, y crear el bombo
function crearCarton()
{
    $carton = range(1, 60);
    shuffle($carton);
    return array_slice($carton, 0, 15);
    //array_slice nos permite quedarnos con 15 numeros de los 60 que habiamos puesto, de esta manera, no se repiten
}
function creaBombo()
{
    $bombo = range(1, 60);
    shuffle($bombo);
    return $bombo;
}
function imprimirCartones($jugadores)
{
    $numCarton=0;
    foreach ($jugadores as $keys => $values) {
        echo $keys . "<br>";
        echo "<table border=1px>";
        echo "<tr>";
        foreach ($values as $carton) {
            
            echo "<td>"; //fila todos cartones
            echo "numero carton".$numCarton++;
            echo "<table border='2' cellspacing='5'>";//tabla por carton
            echo "<tr>";
            foreach ($carton as $numero) {
                //echo $numero;
                echo "<td style='width:150px';> $numero</td>";
            }
            echo "</tr>";
            echo "</table>";
            echo "</td>";
        }
        echo "</tr>";
        echo "</table>";
    }
}
function imprimirBombo($bombo)
{
    for ($i = 0; $i < count($bombo); $i++) {
        echo $bombo[$i] . " ";
    }
}
function imprimirBolas($bombo){
    for ($i = 0; $i < count($bombo); $i++) {
        $numero= $bombo[$i];
        echo "<img src='./images/$numero.PNG'>";

    }

}
function recopilarNombreJugadores()
{
    $arrayNombresJugadores = array();
    foreach ($_POST as $keys => $values) {
        if (!empty($values) && str_contains($keys, "nombre")) {
            array_push($arrayNombresJugadores, $values);
        }
    }
    return $arrayNombresJugadores;
}
function recopilarCantidadCartones()
{
    $numeroCartones = test_input($_POST["cartones"]);
    if (!empty($numeroCartones)) {
        return $numeroCartones;
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
