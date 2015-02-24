<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
include_once("sql.class.php");
include_once("interfaz.class.php");

class EspacioFisico extends funcionGeneral {

    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {

        $this->cripto = new encriptar();
        $this->sql = $sql;
        $this->interfaz = new Interfaz($configuracion);
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

    function desplegarFormularioRegistro($configuracion, $espacio, $valoresIngresados) {

        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        $this->interfaz->desplegarFormularioRegistro($configuracion, $atributos, $espacio, $valoresIngresados);
    }

    function verificarInformacion($configuracion, $espacioFisico, $tipo) {

        foreach ($espacioFisico as $key => $value) {

            $valorIngresado.=$key . "=" . $value . ";";
        }

        $valorIngresado = rtrim($valorIngresado, ";");
        $espacio = $espacioFisico['espacio'];
        $flag = 1;

        if ($espacio == "1") {
            $insertar = "insertarFacultad";
            $existeInfo = "existeInfoFacultad";
        }if ($espacio == "2") {
            $insertar = "insertarSede";
            $existeInfo = "existeInfoSede";
        }if ($espacio == "3") {
            $insertar = "insertarEdificio";
            $existeInfo = "existeInfoEdificio";
        }if ($espacio == "4") {
            $insertar = "insertarEspacioFisicoAcademico";
            $existeInfo = "existeInfoEspacioFisicoAcademico";
        }

        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        $cant_atributos = count($atributos);

        for ($i = 0; $i < $cant_atributos; $i++) {

            $nom_atributo = $atributos[$i]['NOM_ID'];
            $datos[$i] = $espacioFisico[$nom_atributo];
            $datos[$nom_atributo] = $espacioFisico[$nom_atributo];

            if ($atributos[$i]['OBL'] == '0') {
                $datos[$i] = "0";
                $datos[$nom_atributo] = "0";
            }

            if ($datos[$i] == '') {

                $flag = 0;

                echo "<script>alert ('Debe completar todos los campos')</script>";
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variable = "pagina=adminEspacioFisico";
                $variable.="&opcion=registrar";
                $variable.="&espacio=" . $espacio;
                $variable.="&valorIngresado=" . $valorIngresado;
                $variable = $this->cripto->codificar_url($variable, $configuracion);

                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            } else if ($atributos[$i]['DIS'] == 1) {

                $cadena_existe = $this->sql->cadena_sql($configuracion, "existeInfo", $datos[$i], $atributos[$i]['NOM_BD'], $espacio);
                $registro_existe = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_existe, "busqueda");

                if ($registro_existe) {

                    $flag = 0;

                    echo "<script>alert ('El registro " . $datos[$i] . " para el campo \"" . $atributos[$i]['NOM_FORM'] . "\" ya existe ')</script>";
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";

                    $variable = "pagina=adminEspacioFisico";
                    $variable.="&opcion=registrar";
                    $variable.="&espacio=" . $espacio;
                    $variable.="&valorIngresado=" . $valorIngresado;
                    $variable = $this->cripto->codificar_url($variable, $configuracion);

                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                }
            }
        }

        if ($flag == 1) {

            $cadena_insercion = $this->sql->cadena_sql($configuracion, "insertarEspacio", $datos, $atributos, $espacio);
            return $cadena_insercion;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     desplegarInformacionConsulta                                                      //
    // Descripción: Esta función hace un llamado a la funcion desplegarInformacionConsulta que lista  //
    //              los diferentes tipos de espacio.                                                  //   
    // Parametros de entrada: $configuracion: Datos básicos del sistema                               //    
    //                        $espacio: Id del espacio elegido inicialmente (Facultad, Sede, Edificio //
    //                        o Espacio Fïsico Académico)                                             //
    // Valores de salida:     No retorna valores                                                      //
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    function desplegarInformacionConsulta($configuracion, $espacio) {

        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");        
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        $cadena_sql = $this->sql->cadena_sql($configuracion, "listarEspacios", "", $atributos, $espacio);        

        if ($espacio == 1 || $espacio == 2 || $espacio == 3) {
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->interfaz->desplegarListado($configuracion, $atributos, $espacio, $resultados, "EliminarModificar");
        } else if ($espacio == 4) {
            $this->interfaz->desplegarBuscarEspacio($configuracion, $atributos, $espacio);
        }
    }

    function desplegarInformacionConsultaEFA($configuracion, $codEspacio) {

        //Se pasa el valor 4 para la variable por que los espacios físicos académicos corresponden al 4 en la BD y
        //en esta función no se utilizan mas espacios 

        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", 4, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        $cadenaDatos = $this->sql->cadena_sql($configuracion, "infoEFA", $codEspacio, $atributos, "");
        $espacioFisicoA = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaDatos, "busqueda");

        $this->interfaz->desplegarInformacionEFA($configuracion, $atributos, $espacioFisicoA);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     desplegarInformacionEspacio                                                       //
    // Descripción: Esta función despliega en forma detallada la información actual del espacio que el//
    //              usuario haya seleccionado con el fin de modificarlo.                              //
    // Parametros de entrada: $configuracion: Datos básicos del sistema                               //    
    //                        $datosEspacio: Datos principales del registro a modificar               //
    //                        $espacio: Id del tipo de espacio elegido inicialmente (Facultad, Sede,  //
    //                        Edificio o Espacio Fïsico Académico)                                    //
    // Valores de salida:     No retorna valores                                                      //
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    function desplegarInformacionEspacio($configuracion, $datosEspacio, $espacio) {
        
        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");        
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        foreach ($datosEspacio as $key => $value) {

            $datosEspacio_aux = explode("=", $value);
            $espacioFisico[$key] = array($datosEspacio_aux[0] => $datosEspacio_aux[1]);
            $datosEspacio[$key] = $datosEspacio_aux;
        }

        if ($espacio == 1) {
            $cadena_sql = $this->sql->cadena_sql($configuracion, "infoFacultad", $espacioFisico, $datosEspacio, "");
        }if ($espacio == 2) {
            $cadena_sql = $this->sql->cadena_sql($configuracion, "infoSede", $espacioFisico, $datosEspacio, $atributos);
        }if ($espacio == 3) {
            $cadena_sql = $this->sql->cadena_sql($configuracion, "infoEdificio", $espacioFisico, $datosEspacio, $atributos);
        }if ($espacio == 4) {
            $cadena_sql = $this->sql->cadena_sql($configuracion, "infoEspacioFisicoAcademico", $espacioFisico, $datosEspacio, $atributos);
        }
        
        $resultado = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->interfaz->desplegarFormularioModificación($configuracion, $atributos, $espacio, $resultado);
    }

    function verificarInformacionModificada($configuracion, $espacioFisico) {

        $espacio = $espacioFisico['espacio'];
        $flag = 1;
        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        $cant_atributos = count($atributos);

        for ($i = 0; $i < $cant_atributos; $i++) {

            $nom_atributo = $atributos[$i]['NOM_BD'];
            $datos[$i] = $espacioFisico[$nom_atributo];
            $datos[$nom_atributo] = $espacioFisico[$nom_atributo];

            if ($atributos[$i]['OBL'] == '0') {
                $datos[$i] = "0";
                $datos[$nom_atributo] = "0";
            }

            if ($datos[$i] == '') {

                $flag = 0;

                echo "<script>alert ('Debe completar todos los campos')</script>";
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variable = "pagina=adminEspacioFisico";
                $variable.="&opcion=registrar";
                $variable.="&espacio=" . $espacio;
                $variable = $this->cripto->codificar_url($variable, $configuracion);

                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            } else if ($atributos[$i]['DIS'] == 1) {

                $cadena_existeInfo = $this->sql->cadena_sql($configuracion, "existeInfo", $datos[$i], $atributos[$i]['NOM_BD'], $espacio);
                $registro_existe = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_existe, "busqueda");

                if ($registro_existe) {

                    $flag = 0;

                    echo "<script>alert ('El registro " . $datos[$i] . " para el campo \"" . $atributos[$i]['NOM_FORM'] . "\" ya existe ')</script>";
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";

                    $variable = "pagina=adminEspacioFisico";
                    $variable.="&opcion=registrar";
                    $variable.="&espacio=" . $espacio;
                    $variable = $this->cripto->codificar_url($variable, $configuracion);

                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                }
            }
        }

        if ($flag == 1) {

            $cadena_insercion = $this->sql->cadena_sql($configuracion, $insertar, $datos, $atributos, "");
            return $cadena_insercion;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     listarEliminados                                                                  //
    // Descripción: Esta función hace un llamado a la funcion desplegarListado de la clase Interfaz,  //
    //              tras hacer una consulta de los registros que aparecen como inactivos en la BD.    //     
    // Parametros de entrada: $configuracion: Datos básicos del sistema                               //    
    //                        $espacio: Id del espacio elegido inicialmente (Facultad, Sede, Edificio //
    //                        o Espacio Fïsico Académico)                                             //
    // Valores de salida:     No retorna valores                                                      //
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    function listarEliminados($configuracion, $espacio) {

        $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
        $atributos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");

        //El dato espacio se pasa al final del llamado a cadena_sql por estándar de como se ha manejado con 
        //los otros llamados a dicha función, de segundo el array atributos ya que en esta posición se pasan
        //los valores que van a ser reemplazados por el nombre de los campos.
        $cadenaEliminados = $this->sql->cadena_sql($configuracion, "listarEliminados", "", $atributos, $espacio);
        $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaEliminados, "busqueda");

        $this->interfaz->desplegarListado($configuracion, $atributos, $espacio, $resultados, "Recuperar");
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     verificarAsignacionHorarios                                                       //
    // Descripción: Esta función verifica que no existan registros en la tabla achorario_2012 para    //
    //              el espacio físico que el usuario desea inactivar.                                 //  
    // Parametros de entrada: $configuracion: Datos básicos del sistema                               //    
    //                        $espacio: Id del espacio a inactivar                                    //
    // Valores de salida:     Retorna un booleano según si se puede o no eliminar el espacio físico   //
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    function verificarAsignacionHorarios($configuracion, $idEspacio) {
        
        $cadenaPeriodoActivo = $this->sql->cadena_sql($configuracion, "periodoActivo", "", "", "");
        $periodoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaPeriodoActivo, "busqueda");
        
        $validaciones[0]=$idEspacio;
        $validaciones[1]=$periodoActivo[0];
        $validaciones[2]=$periodoActivo[1];
        
        $cadenaHorarios = $this->sql->cadena_sql($configuracion, "listarHorarios", $validaciones, "", "");
        $asignacion = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaHorarios, "busqueda");

        $cadenaPlanDocente = $this->sql->cadena_sql($configuracion, "planTrabajoDocente", $validaciones, "", "");
        $asignacionPlanDocente = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaPlanDocente, "busqueda");

        if ($asignacion == FALSE && $asignacionPlanDocente == FALSE) {
            $return = TRUE;
        } else {
            $return = FALSE;
        }

        return $return;
    }

}
?>


