<?php

############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Diana Elizabeth Ramírez                                               #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          archivoFranja.class.php
 * @author        Marcela Morales
 * @revision      Última revisión 17 de Febrero de 2012
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage
 * @package		clase
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      		0.0.0.1
 * @author			Marcela Morales
 * @author			Oficina Asesora de Sistemas
 * @link			N/D
 * @description  	Clase para generar el archivo de las franjas a partir de la información de la base de datos
 *
  /*-------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones				    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 17/02/2012 | Marcela Morales  	| 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------

 */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

class archivoFranja {

    function archivoFranja() {
        $this->funcionGeneral = new funcionGeneral();
    }

///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     crear_archivo                                                                //
// Descripción: funcion que crea los archivos.php para cargar las gráficas de resultados.    //
// Parametros de entrada:   variable $configuracion, $tipo_grafica, $titulo, nombre para     //
//                          crear el archivo $nombre_archivo y arreglo de datos $registro.   //
// Valores de salida:       No retorna valores. Crea archivo cone extension .php             //
///////////////////////////////////////////////////////////////////////////////////////////////

    function crear_archivo($configuracion, $franjas, $nombre_archivo) {
        //generamos archivo php con datos para la grafica        
        $cant_franjas = count($franjas);
        //echo "<br><br> franjas <br>";
        //var_dump($franjas);
        //echo "<br><br> cantidad_franjas " . $cant_franjas;

        //$ruta = "bloque/admin_planGeneral/temporal";
        //$ruta = "bloque/admin_planGeneral/temporal";

        $archivo = fopen($nombre_archivo . ".txt", "w+");

        if ($archivo == false) {
            die("No se ha podido crear el archivo.");
        }

        for ($i = 0; $i < $cant_franjas; $i++) {
            fputs($archivo, $franjas[$i][3] . ",");
            fputs($archivo, $franjas[$i][4].",");
            fputs($archivo, $franjas[$i][2]);
            fputs($archivo, "\n");
        }
    }

//fin funcion crear_archivo
    ///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     leer_archivo                                                                //
// Descripción: funcion que crea los archivos.php para cargar las gráficas de resultados.    //
// Parametros de entrada:   variable $configuracion, $tipo_grafica, $titulo, nombre para     //
//                          crear el archivo $nombre_archivo y arreglo de datos $registro.   //
// Valores de salida:       No retorna valores. Crea archivo cone extension .php             //
///////////////////////////////////////////////////////////////////////////////////////////////

    function leer_archivo($configuracion, $nombre_archivo) {
        
        echo "<br><br>----------------------- ";
        //$ruta = "bloque/admin_planGeneral/temporal";        
        $i=0;
        //echo "<br> archivo <br> ".$ruta.$nombre_archivo.".txt";
        
        //$gestor = @fopen($ruta."/".$nombre_archivo.".txt", "r");
        //echo "<br><br> nombre archivo ".$nombre_archivo;
        $gestor = @fopen($nombre_archivo.".txt", "r");
        if ($gestor) {
            while (($buffer = fgets($gestor)) !== false) {
                //echo "<br> buffer ";var_dump($buffer); 
                $franjas[$i]=$buffer;
                $i++;
            }
            if (!feof($gestor)) {
                echo "Error: fallo inesperado de fgets()\n";
            }
            fclose($gestor);
        }else{
            echo "<br><br> No abrió el archivo ";
        }
        
        return $franjas;
    }//fin funcion leer_archivo
}

?>
