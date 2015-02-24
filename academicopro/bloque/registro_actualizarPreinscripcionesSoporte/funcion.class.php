
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

//@ Esta clase permite actualizar los registros de preinscripciones de estudiantes con espacios que no deben preinscribir

class funcion_registroActualizarPreinscripcionesSoporte extends funcionGeneral {

    private $configuracion;
    private $ano;
    private $periodo;
    private $todas;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");
        $this->configuracion = $configuracion;
        $this->fechas = new validar_fechas();
        $this->cripto = new encriptar();
        $this->procedimientos = new procedimientos();
        //$this->tema = $tema;
        $this->sql = new sql_registroActualizarPreinscripcionesSoporte($configuracion);
        $this->log_us = new log();
        $this->formulario = "registro_actualizarPreinscripcionesSoporte";
        $this->validacion = new validarInscripcion();
        $this->verificar = "control_vacio(" . $this->formulario . ",'codEspacioAgil')";
        $this->sesion = new sesiones($configuracion);
        $obj_sesion = $this->sesion;

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Conexion sga
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");
        $this->nivelUsuario = $obj_sesion->rescatar_valor_sesion($configuracion, "nivelUsuario");
        //Conexion Oracle dependiendo del usuario
        if ($this->nivelUsuario[0][0] == 4 || $this->nivelUsuario[0][0] == 28) {
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        } elseif ($this->nivelUsuario[0][0] == 51 || $this->nivelUsuario[0][0] == 80) {
            $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
            $this->codEstudiante = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        } elseif ($this->nivelUsuario[0][0] == 52) {
            $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");
            $this->codEstudiante = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        }
        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $cadena_sql = $this->sql->cadena_sql("periodoPreinscripciones", '');
        $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->ano = $resultado_periodo[0]['ANO'];
        $this->periodo = $resultado_periodo[0]['PERIODO'];
        $this->arregloTabla = array();
        $this->arregloPreinscritos = array();
        $this->cierre = array();
    }

    /**
     * Funcion que muestra el formulario de seleccion de facultad a actualizar preinscripcion por demanda de espacios que no se deben cursar.
     */
    function mostrarFormularioFacultad() {
        $facultades = array(array('codFacultad' => 23, 'nombreFacultad' => 'Facultad de Medio Ambiente y Recursos Naturales'),
            array('codFacultad' => 24, 'nombreFacultad' => 'Facultad de Ciencias y Educaci&oacute;n'),
            array('codFacultad' => 32, 'nombreFacultad' => 'Facultad de Tecnolog&iacute;a - Polit&eacutecnica / Tecnol&oacute;gica'),
            array('codFacultad' => 33, 'nombreFacultad' => 'Facultad de Ingenier&iacute;a'),
            array('codFacultad' => 101, 'nombreFacultad' => 'Facultad de Artes-ASAB')
        );
        $this->crearInicioSeccion(' Actualizar Preinscripciones para estudiantes con espacios que no deben preinscribir ');
        foreach ($facultades as $facultad) {
            $boton1 = '';
            $boton2 = 'disabled';
            $this->crearInicioSeccion("Facultad " . $facultad['codFacultad'] . " - " . $facultad['nombreFacultad']);
            $arregloBotones1 = array('codFacultad' => $facultad['codFacultad'],
                'boton1' => $boton1,
                'boton2' => $boton2,
                'action' => 'registro_actualizarPreinscripcionesSoporte',
                'opcion' => 'consultar',
                'nombreBoton' => 'Actualizar');
            $this->mostrarBotonesCargaProyecto($arregloBotones1);
            $this->crearFinSeccion();
        }
        $this->crearFinSeccion();
        ?><br><br><?
    }

    /**
     * Funcion que permite crear el inicio de una seccion
     * @param type $mensaje
     */
    function crearInicioSeccion($mensaje) {
        ?><fieldset>
            <legend><? echo $mensaje; ?></legend><?
    }

    /**
     * Funcion que permite crear el fin de una seccion
     */
    function crearFinSeccion() {
        ?></fieldset><?
    }

    /**
     *  Funcion que geenera botones para inactivacion de preinscripciones
     * @param type $arregloBotones
     */
    function mostrarBotonesCargaproyecto($arregloBotones) {
        ?>
        <table>
            <tr>                  
                <td>
                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='registro_cargarDatosEstudiantesInscripciones' id="<? echo $this->formulario ?>">
                        <input type="hidden" name="action" value="<? echo $arregloBotones['action'] ?>">
                        <input type="hidden" name="opcion" value="<? echo $arregloBotones['opcion']; ?>">
                        <input type="hidden" name="codFacultad" value="<? echo $arregloBotones['codFacultad']; ?>">
                        <input type="submit" value="<? echo $arregloBotones['nombreBoton']; ?>" <? echo $arregloBotones['boton1']; ?>>
                    </form>       
                </td>
            </tr>
        </table>
        <?
    }

    /**
     * Esta funcion realiza el proceso de busqueda de espacios que no deben estar preinscritos y luego los inactiva
     * Utiliza los metodos consultarProyectosFacultad,consultarEspaciosInscritosPreinscripcion,consultarDatosEstudiante,inactivarPreinscripcionErronea, presentarReportePreinscripciones,
     *  presentarEstudianteCreditos, presentarEstudianteHoras, finTabla, finalizarTablaFinal
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST ($codFacultad)
     */
    function consultar() {
        $codProyecto = (isset($_REQUEST['codProyecto']) ? $_REQUEST['codProyecto'] : '');
        $codFacultad = (isset($_REQUEST['codFacultad']) ? $_REQUEST['codFacultad'] : '');
        if (!is_numeric($codFacultad)) {
            echo "Por favor regrese y seleccione facultad";
            exit;
        }
        //consulta los proyectos de la facultad con registros de carga de inscripciones
        $proyectos = $this->consultarProyectosFacultad($codFacultad);
        if (is_array($proyectos)) {
            foreach ($proyectos as $proyecto) {
                $codProyecto = $proyecto['CODIGO_CARRERA'];
                $variablesInscritos = array('ano' => $this->ano,
                    'periodo' => $this->periodo,
                    'codProyecto' => $codProyecto);
                //consultar preinscripciones por demanda del proyecto
                $resultadoInscritos = $this->consultarEspaciosInscritosPreinscripcion($variablesInscritos);
                if (is_array($resultadoInscritos) && !empty($resultadoInscritos)) {
                    //extraer codigos de estudiantes con espacios preinscritos
                    $estudiantes = '';
                    foreach ($resultadoInscritos as $key => $estudiante) {
                        $estudiantes[] = $estudiante['COD_ESTUDIANTE'];
                    }
                    //consultar datos de estudiantes en tabla de carga
                    $estudiantes = array_unique($estudiantes);
                    $espacios = '';
                    $preinscritosPorBorrar = '';
                    foreach ($estudiantes as $key => $codEstudiante) {
                        $datosEstudiante = $this->consultarDatosEstudiante($codEstudiante);
                        //extraer datos de espacios por cursar
                        $espacios = json_decode($datosEstudiante['ESPACIOS_POR_CURSAR'], true);
                        unset($datosEstudiante);
                        //comparar espacios preinscritos que no esten en espacios por cursar
                        $borrar = 1;
                        foreach ($resultadoInscritos as $preinscritos) {
                            if ($preinscritos['COD_ESTUDIANTE'] == $codEstudiante) {
                                if (is_array($espacios) && !empty($espacios)) {
                                    foreach ($espacios as $porCursar) {
                                        //si esta no lo borra
                                        if ($preinscritos['ASI_CODIGO'] == $porCursar['CODIGO']) {
                                            $borrar = 0;
                                            break;
                                        }
                                    }
                                    if ($borrar == 1) {
                                        //si el preinscrito no esta en los espacios por cursar se inactiva
                                        $inactivo = $this->inactivarPreinscripcionErronea($preinscritos);
                                        $preinscritos['inactivo'] = $inactivo;
                                        $preinscritosPorBorrar[] = $preinscritos;
                                    } else {
                                        $borrar = 1;
                                    }
                                } else {
                                    $inactivo = $this->inactivarPreinscripcionErronea($preinscritos);
                                    $preinscritos['inactivo'] = $inactivo;
                                    $preinscritosPorBorrar[] = $preinscritos;
                                }
                            }
                        }
                        unset($espacios);
                    }
                    unset($estudiantes);
                    //presenta reporte de inactivaciones por proyecto
                    $this->presentarReportePreinscripciones($preinscritosPorBorrar, $codProyecto);
                    $preinscritosPorBorrar = '';
                } else {
                    $this->presentarReportePreinscripciones($preinscritosPorBorrar, $codProyecto);
                    $preinscritosPorBorrar = '';
                }
                unset($resultadoInscritos);
            }
        }
        ?>
        <table ><hr>
            <tr>
                <td class='sin_inscripciones' >
                    Total espacios preinscritos inactivados: <? echo $this->todas; ?>.
                </td>
            </tr>
        </table>
        <?
    }

    /**
     *  Funcion que permite consultar los proyectos que tienen registros de carga en la tabla de inscripciones
     * @param type $codFacultad
     * @return type
     */
    function consultarProyectosFacultad($codFacultad) {
        $cadena_sql = $this->sql->cadena_sql("proyectosFacultad", $codFacultad);
        $resultadoProyectosFacultad = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultadoProyectosFacultad;
    }

    /**
     * Funcion que permite consultar los datos de cada estudiante en la tabla de carga.
     * Retorna el arreglo
     * @param type $codEstudiante
     * @return type
     */
    function consultarDatosEstudiante($codEstudiante) {
        $variables = array('codEstudiante' => $codEstudiante,
            'ano' => $this->ano,
            'periodo' => $this->periodo);
        $cadena_sql = $this->sql->cadena_sql("carga", $variables);
        $registroCreditosGeneral = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $registroCreditosGeneral[0];
    }

    /**
     * Funcion para inactivar un registro de preinscripcion por demanda.
     * Utiliza los metodos actualizarEstadoPreinscripcion, registrarEvento
     * Retorna el resultado de la inactivacion
     * @param type $datosPreinscripcion
     * @return type
     */
    function inactivarPreinscripcionErronea($datosPreinscripcion) {
        //Utiliza metodo para actualizar el estado de una preinscripcion
        $inactivar = $this->actualizarEstadoPreinscripcion($datosPreinscripcion);
        //crea las variables para el registro del evento
        $variablesRegistro = array('usuario' => $this->usuario,
            'evento' => '56',
            'descripcion' => 'Inactiva Preinscripcion',
            'registro' => $datosPreinscripcion['ANO'] . "-" . $datosPreinscripcion['PERIODO'] . " EA: " . $datosPreinscripcion['ASI_CODIGO'] . ", Estud: " . $datosPreinscripcion['COD_ESTUDIANTE'] . ", Perdido: " . $datosPreinscripcion['PERDIDO'],
            'afectado' => $datosPreinscripcion['COD_ESTUDIANTE']);
        if ($inactivar == 'SI') {
            //registra el evento cuando realiza la inactivacion
            $this->procedimientos->registrarEvento($variablesRegistro);
        } else {
            
        }
        return $inactivar;
    }

    /**
     * Funcion que inactiva una preinscripcion.
     * Retorna el resultado de la inactivacion con Si o NO
     * @param type $datosPreinscripcion
     * @return string
     */
    function actualizarEstadoPreinscripcion($datosPreinscripcion) {
        $variables = array('ANO' => $datosPreinscripcion['ANO'],
            'PERIODO' => $datosPreinscripcion['PERIODO'],
            'COD_ESTUDIANTE' => $datosPreinscripcion['COD_ESTUDIANTE'],
            'ASI_CODIGO' => $datosPreinscripcion['ASI_CODIGO'],
            'CARRERA' => $datosPreinscripcion['CARRERA'],
            'PERDIDO' => $datosPreinscripcion['PERDIDO'],
            'ESTADO' => $datosPreinscripcion['ESTADO']);
        $cadena_sql = $this->sql->cadena_sql("inactivarPreinscripcion", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        $registrado = $this->totalAfectados($this->configuracion, $this->accesoOracle);
        if ($registrado < 1 || $resultado === FALSE) {
            return 'NO';
        } else {
            return 'SI';
        }
    }

    /**
     * Funcion que sirve para valor default del bloque
     */
    function nuevoRegistro() {
        
    }

    /**
     * Funcion que consulta los espacios preinscritos del proyecto.
     * Retorna el arreglo
     * @param type $datosProyecto
     * @return type
     */
    function consultarEspaciosInscritosPreinscripcion($datosProyecto) {
        $variables = array('codProyecto' => $datosProyecto['codProyecto'],
            'ano' => $datosProyecto['ano'],
            'periodo' => $datosProyecto['periodo']);
        $cadena_sql = $this->sql->cadena_sql("consultaPreinscripcionesEstudiante", $variables);
        return $resultadoInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    /**
     * Funcion que presenta el reporte de las inactivaciones realizadas.
     * @param type $resultadoInscritos
     * @param type $codProyecto
     */
    function presentarReportePreinscripciones($resultadoInscritos, $codProyecto) {
        ?>
        <?
        if (is_array($resultadoInscritos)) {
            ?>
            <div class="Horario centrar" ><b><? echo "PREINSCRIPCIONES PARA BORRAR " . $this->ano . "-" . $this->periodo . ".  PROYECTO " . $codProyecto; ?></b></div>
            <table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                <thead class='sigma'>
                <th class='niveles centrar' width="30">N&uacute;mero</th>
                <th class='niveles centrar' width="30">Cod. Estudiante</th>
                <th class='niveles centrar' width="30">Cod.Espacio</th>
                <th class='niveles centrar' width="150">Nombre Espacio Acad&eacute;mico</th>
                <th class='niveles centrar' width="25">Perdido</th>
                <th class='niveles centrar' width="25">Inactivada</th>
            <?
            //recorre cada uno del los grupos
            $totalInscritos = count($resultadoInscritos);
            for ($j = 0; $j < $totalInscritos; $j++) {
                ?>
                    <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                        <td class='cuadro_plano centrar'><? echo ($j+1); ?></td>
                        <td class='cuadro_plano centrar'><? echo$resultadoInscritos[$j]['COD_ESTUDIANTE']; ?></td>
                        <td class='cuadro_plano centrar'><? echo $resultadoInscritos[$j]['ASI_CODIGO']; ?></td>
                        <td class='cuadro_plano '><? echo htmlentities($resultadoInscritos[$j]['NOMBRE']); ?></td>
                <?
                if ($resultadoInscritos[$j]['PERDIDO'] == 'N') {
                    $color = 'color:#FF0000';
                } else {
                    $color = '';
                }
                ?>
                        <td class='cuadro_plano centrar' style="<? echo $color ?>"><? echo $resultadoInscritos[$j]['PERDIDO']; ?></td>
                        <td class='cuadro_plano centrar'><? echo (isset($resultadoInscritos[$j]['inactivo']) ? $resultadoInscritos[$j]['inactivo'] : ''); ?></td>
                <?
            }
            $this->todas+=$j;
        } else {
            ?>
                <table ><hr>
                    <tr>
                        <td class='sin_inscripciones' >
                            No hay registros de estudiantes con espacios no perdidos y preinscritos en <? echo $codProyecto; ?>.
                        </td>
                    </tr>

        <? } ?>
            </table>
        <?
    }

}
?>
