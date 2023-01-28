<?php
//---------------funciones inciales basicas: crear carton, crear el jugador, y crear el bombo
function crearCarton()
{
    $carton = range(1, 60);
    shuffle($carton);

    return array_slice($carton, 0, 15);
    //array_slice nos permite quedarnos con 15 numeros de los 60 que habiamos puesto, de esta manera, no se repiten
}


function crearJugador()
{
    //array de jugador, donde cada uno de ellos tendra 3 cartones, llamando a la funcion crearCarton()
    $jugador = array();
    array_push($jugador, crearCarton(), crearCarton(), crearCarton());
    //echo "----------------------cartones------------------- </br>";
   //imprimirCartones($jugador);
  
    return $jugador;
}

function obtenerBolas()
{
    $bolas = range(1, 60);
    shuffle($bolas);
    return $bolas;
}

//-----------------------------funciones principales para determinar el juego y quien gana -------------------------------
//buscamos al ganador de la partida  pasando el jugador con sus tres cartones y el bombo con las bolas
function ganador($jugador, $bolas)
{

    for ($i = 0; $i < count($jugador); $i++) { //los tres cartones del jugador
        $cont = 0; //inicializamos aqui el contador para que haya uno diferente por cada carton
        for ($j = 0; $j < count($jugador[$i]); $j++) {

            if (in_array($jugador[$i][$j], $bolas)) {
                //buscamos si ese numero del carton se encuentra en las bolas 
                //llama uun recorte de bolas, las bolas que hasta ese momento se ha llamado
                $cont++;
            }
        }
        if ($cont == 15) {
            echo "<br> el carton ganador es $i </br>";
            echo " <br>";
            return true;
        }
    }
    return false;
}


//vamos a pasar jugadores (array de trres array de jugador), las bolas, y posicion, que la inicializaremos en la partida
function juegoTerminado($jugadores, $bolas, $pos)
{
    for ($i = 0; $i < count($jugadores); $i++) {
        //llamamos a la funcion de ganador pasando cada jugador con sus cartones, y pasando las bolas que irán recortadas en función de ese contador
        //por ejempl de 0 a 15 y comprueba los cartones de todos los jugadores con ese rango
        if (ganador($jugadores[$i], array_slice($bolas, 0, $pos))) {
            //si esta en 15 va de 0 a 15
            //echo $i;
            echo "<br />";
            return $i; //nos devuelve el carton
        }
    }
    return -1; //porque puede no ganar ninguno. Esto nos valdrá para salir del juego y romper el bucle
}
//---------------funcion principal jugar que sera llamada desde el otro documento
function jugar()
{
    $jugadores = array();
    array_push($jugadores, crearJugador(), crearJugador(), crearJugador(), crearJugador());
    $bolas = obtenerBolas();
    $pos = 0;
    do {
        $ganador = juegoTerminado($jugadores, $bolas, $pos);
        $pos++;
    } while ($ganador == -1);
    echo "Ha ganado jugador $ganador </br>";
    echo "</br>";
    //mostrarCartones($jugadores[$ganador]);
    echo "</br>";
    echo "en ". ($pos-1). "jugadas </br>"; //tenemos que restar -1 ya que empieza en cero el array de bolas, por lo que si sale 54 es la posicion anterior
    echo "</br>";
    echo "El bombo es el siguiente : ";
    imprimirBolas($bolas);
    echo "</br>";
    mostrarCartones($jugadores,3);
       
    
}
//----------------funciones para imprimir

function imprimirBolas($bolas){
    for ($i = 0; $i < count($bolas); $i++) {
        $numero= $bolas[$i];
        echo "<img src='./images/$numero.PNG'>";

    }

}
function mostrarCartones($jugadores,$numCartones){
   
   
    for($i=0;$i<count($jugadores);$i++){
        echo " Jugador" .$i."<br>";
        echo "<table>"; //border='2' cellspacing='10'
         echo "<tr>";
        for($j=0;$j<$numCartones;$j++){
            echo "<td>";
            echo   " Carton numero $j";
            echo "<table border='2' cellspacing='5'>";
           $carton =$jugadores[$i][$j];
           echo "<tr>";
           $arr_espac1[0]= random_int(0,2);
           $arr_espac1[1]=random_int(3,4);
           $contador=0;
            for($k=0;$k<count($carton);$k++){
               
                if($k%5==0){
                $arr_espac1[0]= random_int(0,2)+$contador;
                $arr_espac1[1]= random_int(3,4)+$contador;    
                $contador+=5;

                echo "</tr><tr>";
                }
                echo "<td style='width:150px';> $carton[$k]</td>";
                 if($k== $arr_espac1[0] || $k== $arr_espac1[1]){
                     echo "<td style='width:150px;background-color: black;'>&nbsp;</td>";
                 }
               
            }
            echo "</tr>";
            echo "</table>";
            echo "</td>";
         }

         echo "</tr>";
         echo "</table>";

    }
   

}
