<?php

function partida()
{
    //recogemos la información pasada por formulario, usamos las funciones correspondientes
    $dados = obtenerNumeroDeDadosFormulario();
    $arrayNombreJugadores = obtenerNombresDeJugadores();
    $numeroDeJugadores = obtenerNumeroDeJugadores($arrayNombreJugadores);
    //validamos la partida usando función validar. Si no funciona, recargamos la página
    if (!esInicioDePartidaValido($arrayNombreJugadores, $dados)) {

        echo ("<script>window.location='p01_dados.html'</script>");
    } else {
        //lanzamos los dados para que comienza la partida
        $todosLosDados = tirarDadosJugadores($arrayNombreJugadores, $dados);
        imprimirTablaJugadoresYDados($arrayNombreJugadores, $numeroDeJugadores, $todosLosDados);
        //imprimimos las puntuaciones y ganador o ganadores
        //si hay jugadores con puntuaciones similares son varios jugadores si no, solo hay un jugador
        $puntuacionessumaDeTodosLosDadosDeJugadores = array();
        $puntuacionessumaDeTodosLosDadosDeJugadores = sumaDeTodosLosDadosDeJugadores($todosLosDados, $dados);
        $contadorRepes = 0;
        $ganador = ganador($puntuacionessumaDeTodosLosDadosDeJugadores);
        if (count($puntuacionessumaDeTodosLosDadosDeJugadores) > count(array_unique($puntuacionessumaDeTodosLosDadosDeJugadores))) {
            for ($i = 0; $i < count($puntuacionessumaDeTodosLosDadosDeJugadores); $i++) {
                if ($puntuacionessumaDeTodosLosDadosDeJugadores[$i] > $ganador || $puntuacionessumaDeTodosLosDadosDeJugadores[$i] == $ganador) {
                    $contadorRepes++;
                    echo "Ganador = " . $arrayNombreJugadores[$i] . "<br>";
                }
            }
            echo "¡Hay " . $contadorRepes . "ganadores!";
        }
    }
}
//-----------------------> funciones <-------------------------------------------------
function imprimirTablaJugadoresYDados($arrayNombreJugadores, $numeroDeJugadores, $todosLosDados)
{
    echo "<h1> RESULTADO JUEGO </h1>";
    echo  "<table border ='1'>";
    for ($i = 0; $i < $numeroDeJugadores; $i++) {
        echo "<tr>";
        echo "<td>";
        echo "Los dados de " . $arrayNombreJugadores[$i] . " son: ";
        echo "</td>";
        $cadaDado = str_replace(' ', '', implode(" ", $todosLosDados[$i]));
         for ($k = 0; $k < strlen($cadaDado); $k++) {
             echo "<td><img src='./images/$cadaDado[$k].PNG'WIDTH='50' HEIGHT='80'></td>";
         }
        echo "</tr>";
    }
    echo "</table>";
}
function sumaDeTodosLosDadosDeJugadores($todosLosDados, $dados)
{
    $arrayNombreJugadores = obtenerNombresDeJugadores();
    $puntuacionessumaDeTodosLosDadosDeJugadores = array();
    //vamos a averiguar la suma de los dados y los almacenamos en array
    for ($i = 0; $i < count($todosLosDados); $i++) {
        $sumaDeTodosLosDadosDeJugadores = 0;
        echo  $arrayNombreJugadores[$i] . " = ";
        $variableRepetida = 0;
        $contadorRepes = 1; //iniciamos en 1 porque cuando se hace ++ ya contamos con el inicial
        for ($j = 0; $j < count($todosLosDados[$i]); $j++) {
            $sumaDeTodosLosDadosDeJugadores += $todosLosDados[$i][$j];
            if ($variableRepetida == $todosLosDados[$i][$j]) {
                $contadorRepes++;
                if ($contadorRepes == $dados) { //controlando que es la misma cantidad de dados y de repeticion de numero, solo así sumamos 100
                    $sumaDeTodosLosDadosDeJugadores = 100;
                }
            }
            $variableRepetida = $todosLosDados[$i][$j];
        }
        array_push($puntuacionessumaDeTodosLosDadosDeJugadores, $sumaDeTodosLosDadosDeJugadores);
        echo $puntuacionessumaDeTodosLosDadosDeJugadores[$i] . "<br>";
    }
    return $puntuacionessumaDeTodosLosDadosDeJugadores;
}
function tirarDadosJugadores($jugadores, $dados)
{
    $arrayDados = array();
    for ($i = 0; $i < count($jugadores); $i++) {
        if (!empty($jugadores[$i])) {
            $dadosJugador = array();
            $dadosJugador = dadosPorJugador($dados);
            array_push($arrayDados, $dadosJugador);
        }
    }
    return $arrayDados;
}
function dadosPorJugador($dados)
{
    $dadosJugador = array();
    for ($j = 0; $j < $dados; $j++) {
        array_push($dadosJugador, crearDado());
    }
    return $dadosJugador;
}
function crearDado()
{
    $valor = rand(1, 6);
    return $valor;
}
function ganador($puntuacionessumaDeTodosLosDadosDeJugadores) //
{
    $arrayNombreJugadores = obtenerNombresDeJugadores();
    $sumaDeTodosLosDadosDeJugadoresGanador = 0;
    for ($i = 0; $i < count($puntuacionessumaDeTodosLosDadosDeJugadores); $i++) {
        if ($puntuacionessumaDeTodosLosDadosDeJugadores[$i] > $sumaDeTodosLosDadosDeJugadoresGanador) {
            $sumaDeTodosLosDadosDeJugadoresGanador = $puntuacionessumaDeTodosLosDadosDeJugadores[$i];
            $ganador = $arrayNombreJugadores[$i];
        }
    }
    echo "ha ganado " . $ganador . "<br>";
    echo "Hay un ganador <br>";
    return $sumaDeTodosLosDadosDeJugadoresGanador;
}

function esInicioDePartidaValido($arrayNombreJugadores, $cantidadDados) //esInicioDePartidaValido
{
    $contadorJugadores = 0;
    $valido = true;
    for ($i = 0; $i < count($arrayNombreJugadores); $i++) {
        if (!empty($arrayNombreJugadores[$i])) {
            $contadorJugadores++;
        }
    }
    if ($contadorJugadores > 4 || $contadorJugadores < 2) {
        $valido = false;
    }
    if ($valido && ($cantidadDados < 1 || $cantidadDados > 10)) {
        $valido = false;
    }
    return $valido;
}

function obtenerNumeroDeJugadores($arrayNombreJugadores)
{
    $numeroDeJugadores = 0;
    for ($i = 0; $i < count($arrayNombreJugadores); $i++) {
        if (!empty($arrayNombreJugadores[$i])) {
            $numeroDeJugadores++;
        }
    }
    return $numeroDeJugadores;
}
//-------------------------> Recoger la información del formulario <-------------------------------------
function obtenerNombresDeJugadores() //obtenerNombresDeJugadoresNombresDeJugadores
{
    $arrayNombreJugadores = array();
    $nombreJugador1 = test_input($_POST["jug1"]);
    $nombreJugador2 = test_input($_POST["jug2"]);
    $nombreJugador3 = test_input($_POST["jug3"]);
    $nombreJugador4 = test_input($_POST["jug4"]);
    array_push($arrayNombreJugadores, $nombreJugador1, $nombreJugador2, $nombreJugador3, $nombreJugador4);
    return $arrayNombreJugadores;
}

function obtenerNumeroDeDadosFormulario()
{
    $cantidadDados = test_input($_POST["numdados"]); //obtenerNombresDeJugadoresNumeroDeDadosFormulario
    return $cantidadDados;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
