<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------------------------
  |				              Control Versiones                                             |
  -----------------------------------------------------------------------------------------------------------
  | fecha      |        Autor            | version     |                       Detalle                      |
  -----------------------------------------------------------------------------------------------------------
  | 28/12/2012 | Marcela Morales  	| 0.0.0.1     |                                                     |
  -----------------------------------------------------------------------------------------------------------
  | 28/01/2012 | Marcela Morales  	| 0.0.0.2     | Se añadió verificación con achorario_2012 antes de  |
  |            |                        |             |que el usuario elimine (inactive) el espacio.        |
  -----------------------------------------------------------------------------------------------------------
 */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

class FuncionAdminEspacioFisico extends funcionGeneral {

    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once("sql.class.php");
        include_once("espacioFisico.class.php");

        $this->cripto = new encriptar();
        $this->tema = $tema;
        $this->sql = $sql;
        $this->espacioFisico = new EspacioFisico($configuracion, $this->sql);

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Conexion sga
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");

        //Datos de sesion

        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }

    function nuevoRegistro($configuracion, $espacioFisico) {

        foreach ($espacioFisico as $key => $value) {

            $valorIngresado.=$key . "=" . $value . ";";
        }

        $valorIngresado = rtrim($valorIngresado, ";");

        $cadena_insercion = $this->espacioFisico->verificarInformacion($configuracion, $espacioFisico, 1);
        //echo "<br><br> cadena insercion ".$cadena_insercion;
        $resultado_insercion = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_insercion, "");
        
        echo "<br> resultado insercion ";var_dump($resultado_insercion);

        if ($resultado_insercion == true) {
            $valorIngresado = NULL;
            echo "<script>alert ('Registro Exitoso');</script>";
        } else {
            echo "<script>alert ('Fallo inserción, por favor vuelva a intentar. ');</script>";
        }

        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminEspacioFisico";
        $variable.="&opcion=registrar";
        $variable.="&espacio=" . $espacioFisico['espacio'];
        $variable.="&valorIngresado=" . $valorIngresado;
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    function modificarRegistro($configuracion, $espacio, $espacioAntiguo, $espacioNuevo) {

        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        foreach ($espacioAntiguo as $key => $value) {
            foreach ($value as $clave => $valor) {

                $diferencia[$key] = array_diff($espacioNuevo[$key], $value);

                if ($diferencia[$key]) {
                    $dif_temp[$clave] = $diferencia[$key][$clave];
                }
            }
        }

        $diferencia = $dif_temp;
        
        $cant_atr = count($atributos);

        if ($espacio == 1) {
            $cadena_modificacion = $this->sql->cadena_sql($configuracion, "modificarFacultad", $espacioAntiguo, $atributos, $diferencia);
        }if ($espacio == 2) {            
            $cadena_modificacion = $this->sql->cadena_sql($configuracion, "modificarSede", $espacioAntiguo, $atributos, $diferencia);            
        }if ($espacio == 3) {
            $cadena_modificacion = $this->sql->cadena_sql($configuracion, "modificarEdificio", $espacioAntiguo, $atributos, $diferencia);
        }if ($espacio == 4) {
            $cadena_modificacion = $this->sql->cadena_sql($configuracion, "modificarEFA", $espacioAntiguo, $atributos, $diferencia);
        }

        //echo "<br> cadena modificacion ".$cadena_modificacion;//exit;
        $resultado_modificacion = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_modificacion, "");

        if ($resultado_modificacion == true) {
            echo "<script>alert ('Registro Exitoso');</script>";
        } else {
            echo "<script>alert ('Fallo actualización, por favor vuelva a intentar. ');</script>";
        }

        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminEspacioFisico";
        $variable.="&opcion=consultar";
        $variable.="&espacio=" . $espacio;
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     eliminarRegistro                                                                  //
    // Descripción: Debido a que no se debe eliminar un registro de la BD esta función pone en estado //
    //              I el registro solicitado por el usuario.                                          //
    // Parametros de entrada: $configuracion: Datos básicos del sistema                               //
    //                        $datosEspacio: Información del espacio que se desactivará               //
    //                        $espacio: Id del espacio elegido inicialmente (Facultad, Sede, Edificio //
    //                        o Espacio Fïsico Académico)                                             //
    // Valores de salida:       Retorna resultado del estado del registro                             //
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    function eliminarRegistro($configuracion, $datosEspacio, $espacio) {

        foreach ($datosEspacio as $key => $value) {

            $datosEspacio_aux = explode("=", $value);
            $espacioFisico[$key] = array($datosEspacio_aux[0] => $datosEspacio_aux[1]);
            if ($datosEspacio_aux[0] == 'SAL_ID_ESPACIO') {
                $idEspacio = $datosEspacio_aux[1];
            }
            $datosEspacio[$key] = $datosEspacio_aux;
        }

        $permitir = $this->espacioFisico->verificarAsignacionHorarios($configuracion, $idEspacio);

        if ($permitir == TRUE) {
            $cadenaEspacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
            $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaEspacio, "busqueda");

            $cadenaEliminacion = $this->sql->cadena_sql($configuracion, "eliminarEspacio", $datosEspacio, $atributos, $espacio);
            $resultadoEliminacion = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaEliminacion, "");

            if ($resultadoEliminacion == true) {
                echo "<script>alert ('Registro Exitoso');</script>";
            } else {
                echo "<script>alert ('Fallo eliminación, por favor vuelva a intentar. ');</script>";
            }
        }else if ($permitir == FALSE) {
            
            echo "<script>alert ('Fallo eliminación, el espacio tiene horas de clase asignadas.');</script>";
            
        }

        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminEspacioFisico";
        $variable.="&opcion=consultar";
        $variable.="&espacio=" . $espacio;
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     recuperarRegistro                                                                    //
    // Descripción: Debido a que no se debe eliminar un registro de la BD esta función permite reactivar //
    //              un registro previamente "eliminado" o desactivado.                                   //
    // Parametros de entrada: $configuracion: Datos básicos del sistema                                  //
    //                        $datosEspacio: Información del espacio que se reactivará                   //
    //                        $espacio: Id del espacio elegido inicialmente (Facultad, Sede, Edificio    //
    //                        o Espacio Fïsico Académico)                                                //
    // Valores de salida:       Retorna resultado del estado del registro                                //
    ///////////////////////////////////////////////////////////////////////////////////////////////////////

    function recuperarRegistro($configuracion, $datosEspacio, $espacio) {

        foreach ($datosEspacio as $key => $value) {

            $datosEspacio_aux = explode("=", $value);
            $espacioFisico[$key] = array($datosEspacio_aux[0] => $datosEspacio_aux[1]);
            $datosEspacio[$key] = $datosEspacio_aux;
        }

        $cadenaEspacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaEspacio, "busqueda");

        $cadenaEliminacion = $this->sql->cadena_sql($configuracion, "recuperarEspacio", $datosEspacio, $atributos, $espacio);
        $resultadoEliminacion = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaEliminacion, "");

        if ($resultadoEliminacion == true) {
            echo "<script>alert ('Registro Exitoso');</script>";
        } else {
            echo "<script>alert ('Fallo eliminación, por favor vuelva a intentar. ');</script>";
        }

        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
        $variable = "pagina=adminEspacioFisico";
        $variable.="&opcion=consultar";
        $variable.="&espacio=" . $espacio;
        $variable = $this->cripto->codificar_url($variable, $configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
    }
}
?>


