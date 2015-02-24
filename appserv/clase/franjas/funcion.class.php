<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------
  |				Control Versiones 				    	|
  |bloque: admin_proyecto							    	|
  ----------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |              Detalle            |
  ----------------------------------------------------------------------------------------
  | 13/01/2012 | Marcela Morales  	| 0.0.0.1     |                                 |
  ----------------------------------------------------------------------------------------
 */

include_once("../funcionGeneral.class.php");
//include_once("archivoFranja.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/archivoFranja.class.php");

date_default_timezone_set("America/Bogota");

class adminFranja extends funcionGeneral {

    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        echo "hola";exit;
        
        $this->cripto = new encriptar();
        $this->log_us = new log();
        $this->tema = "";
        $this->sql = $sql;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        $this->archivoFranja = new archivoFranja();

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }

    function ingreso($configuracion) {
        //echo "<br> en nuevo registro ";
        $this->comparacion_ingreso($configuracion, $registro, $this->tema, "");
    }

    /* __________________________________________________________________________________________________

      Metodos especificos
      __________________________________________________________________________________________________ */

///////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     form_proyecto                                                                //
// Descripción: funcion que muestra el formulario para registrar o editar un proyecto.       //
// Parametros de entrada:   variable $configuracion, arreglo $registro, $tema  y $estilo.    //
// Valores de salida:       No retorna ningun valor. Muestra formulario de registro y edicion//
///////////////////////////////////////////////////////////////////////////////////////////////

    function comparacion_ingreso($configuracion, $registro, $tema, $estilo) {

        $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";

        /*         * ************************************************************************************************** */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");        
        $id_usuario = $this->usuario;

        $html = new html();
        $tab = 1;
        $this->formulario = "admin_planGeneral";
        $this->verificar .= "control_vacio(" . $this->formulario . ",'codigo')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'nombre')";
        $this->verificar .= "&& control_vacio(" . $this->formulario . ",'descripcion')";

        /////////////////////////////////////////////////////////////////////////
        //Aqui crea el archivo con las franjas que se asignaron a cada facultad// 
        /////////////////////////////////////////////////////////////////////////

        $consulta_franja = $this->sql->cadena_sql($configuracion, $this->acceso_db, "consultar_franja", $_REQUEST["perfil"]);
        //echo "<br><br> consulta franja ".$consulta_franja;
        $franjas = $this->ejecutarSQL($configuracion, $this->acceso_db, $consulta_franja, "busqueda");
        //echo "<br><br> franjas <br>";var_dump($franjas);        
        $ruta = "bloque/admin_planGeneral/temporal";   
        $nombre_archivo = date(YmdHis);
        
        $this->eliminarArchivosAnteriores($ruta);
        $this->archivoFranja->crear_archivo($configuracion, $franjas, $ruta."/".$nombre_archivo);

        ///////////////////////////////////////////////////////////////////////////
        //Aqui después de obtener el codigo de la facultad y la fecha del sistema//
        //     se lee el archivo de las franjas y se realiza la comparación      // 
        ///////////////////////////////////////////////////////////////////////////

        $this->realizarComparacion($ruta."/".$nombre_archivo, $facultad, $codigo);
    }

// fin function form_proyecto
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     realizarComparacion                                                                             //
// Descripción: funcion que compara si el codigo de la facultad y la fecha son validas                          //
// Parametros de entrada:   $nombre_archivo= contiene el nombre de el archivo que se creo con las franjas       //                                         
//                          $facultad= contiene el codigo de la facultad a la que pertenece el usuario          // 
// Valores de salida:       Dice si el usuario puede ingresar o no                                   //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function realizarComparacion($nombre_archivo, $facultad, $codigo) {

        $fecha_actual = date(YmdHis);
        //echo "<br> fecha actual " . $fecha_actual;

        $datos_archivo = $this->archivoFranja->leer_archivo($configuracion, $nombre_archivo);
        $cantidad_franjas = count($datos_archivo);
        /****************hace falta pasarle el código correcto************/
        $consulta_codigo = $this->sql->cadena_sql($configuracion, $this->acceso_db, "consulta_codigo", "20102096023");
        //$consulta_codigo = $this->sql->cadena_sql($configuracion, $this->acceso_db, "consulta_codigo", $codigo);
        $codigo = $this->ejecutarSQL($configuracion, $this->acceso_db, $consulta_codigo, "busqueda");


        for ($i = 0; $i < $cantidad_franjas; $i++) {

            $fecha_inicio = substr($datos_archivo[$i], 0, 14);
            $fecha_fin = substr($datos_archivo[$i], 15, 14);
            $cod_facultad = substr($datos_archivo[$i], 30, 3);

            /*echo "<br><br> fecha inicio " . $fecha_inicio;
            echo "<br> fecha fin " . $fecha_fin;
            echo "<br> facultad " . $cod_facultad;*/

            if ($no == 0) {
                if ($fecha_inicio > $fecha_actual) {
                    echo "<br> El usuario no puede ingresar! ";
                } else {
                    if ($fecha_fin >= $fecha_actual) {
                        if ($codigo[0][0] == $cod_facultad) {
                            echo "<br>El usuario puede ingresar! ";
                            $no = 1;
                        }
                        else
                            echo "<br>El usuario no puede ingresar! ";
                    }else {
                        echo "<br>El usuario no puede ingresar! ";
                    }
                }
            } else {
                $i = $cantidad_franjas;
            }
        }
    }

// fin function realizar_comparaciones
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion:     eliminarArchivosAnteriores                                                              //
// Descripción: funcion que elimina los archivos de graficas anteriores a la fecha actual               //
// Parametros de entrada:   variable $nombre_directorio, se debe armar con la variable de configuración //
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    function eliminarArchivosAnteriores($nombre_directorio) {

        $fgeneracion = date(Ymd);                           //Devuelve la fecha actual

        if (is_dir($nombre_directorio))
            $directorio = opendir($nombre_directorio);      //Abre un gestor de directorio
        if (!$directorio)
            return false;

        //echo "<br> nombre_directorio ";var_dump($nombre_directorio);

        while ($archivo = readdir($directorio)) {           //retorna el nombre del próximo archivo del directorio
            //echo "<br> archivo ".$archivo;
            $pos = strpos($archivo, $fgeneracion);
            if ($pos === false) {
                if ($archivo != "." && $archivo != "..") {
                    if (!is_dir($nombre_directorio . "/" . $archivo))
                        unlink($nombre_directorio . "/" . $archivo);
                    else
                        eliminarArchivosAnteriores($nombre_directorio . '/' . $archivo);
                }
            }
        }
        closedir($directorio);
    }

}

// fin de la clase
?>

