
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
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

//@ Esta clase se utiliza para el ingreso y validación de estudiante de posgrado para inscripcion por estudiante

class funcion_adminConsultarHorarioEstudianteCoordinador extends funcionGeneral {
    private $configuracion;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $this->configuracion=$configuracion;
        //$this->tema = $tema;
        $this->sql = new sql_adminConsultarHorarioEstudianteCoordinador($configuracion);
        $this->log_us = new log();
        $this->validacion = new validarInscripcion();
        $this->formulario = "admin_consultarHorarioEstudianteCoordinador";

        //Conexion ORACLE
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->verificar = "control_vacio(" . $this->formulario . ",'codEstudiante')";
        $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
        $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->ano = $resultado_periodo[0]['ANO'];
        $this->periodo = $resultado_periodo[0]['PERIODO'];
    }

#Cierre de constructor

    #muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante

    /**
     * Funcion que presenta los datos del estudiante y el horario registrado
     * $_REQUEST
     */
    function mostrarHorarioEstudiante() {

        $codigoEstudiante = $_REQUEST['codEstudiante'];
        $variables=array('codEstudiante'=>$codigoEstudiante,
                        'ano'=>  $this->ano,
                        'periodo'=>  $this->periodo);
        //consulta los datso del estudiante
        $registroEstudiante = $this->consultarDatosEstudiante($variables);

        if (isset($registroEstudiante)) {
            //presenta los datos del estudiante
            $this->datosEstudiante($registroEstudiante[0]);
            //busca los grupos y el horario inscrito por el estudinate
            $registroGrupo = $this->consultarInscripcionesEstudiante($variables);
            //presenta el horario del estudiante
            $this->HorarioEstudiante($registroGrupo,$registroEstudiante[0]);
            
        } else {
            echo "El código de estudiante: <strong>" . $codigoEstudiante . "</strong> no está registrado.";
        }
    }

    #Funcion que muestra la informacion del estudiante

    /**
     *  Funcion que presenta los datos del estudiante
     * @param type $datosEstudiante
     * 
     */
    function datosEstudiante($datosEstudiante) {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
            <tr class="texto_subtitulo">
                <td colspan="2">
                    <? echo "Nombre: <strong>" . $datosEstudiante['NOMBRE'] . "</strong><br>"; ?>
                    <? echo "C&oacute;digo: <strong>" . $datosEstudiante['CODIGO'] . "</strong><br>"; ?>
                    Proyecto Curricular:
                    <? echo "<strong>" . $datosEstudiante['CARRERA'] . " - " . $datosEstudiante['NOMBRE_CARRERA']  . "</strong><br>"; ?>
                    Plan de Estudios:
                    <? echo "<strong>" . $datosEstudiante['PLAN']. "</strong><br>"; ?>
                    Acuerdo:
                    <? echo "<strong>" . substr($datosEstudiante['ACUERDO'], -3) . " de " . substr($datosEstudiante['ACUERDO'], 0, 4) . "</strong>"; ?>

                    <hr>
                </td>
            </tr>

        </table>
        <?
    }

    /**
     * Funcion que presenta el horario registrado del estudiante
     * @param type $resultado_grupos
     * @param type $datosEstudiante
     * @param type $this->configuracion
     */
    function HorarioEstudiante($resultado_grupos,$datosEstudiante) {
        ?>
        <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
            <tbody>
                <tr>
                    <td>
                        <? if ($resultado_grupos != NULL)
                            { ?>
                            <table width="100%" border="0" align="center">
                                <thead class='cuadro_plano centrar'>
                                <th><center><? echo "Horario de Clases"; ?></center></th>
                                </thead>
                                <tr>
                                    <td>
                                        <table class='contenidotabla'>
                                            <thead class='cuadro_color'>
                                            <td class='cuadro_plano centrar' width="6%">Cod.</td>
                                            <td class='cuadro_plano centrar' width="30%">Nombre Espacio<br>Acad&eacute;mico </td>
                                            <td class='cuadro_plano centrar' width="5%">Grupo </td>
                                        <?
                                        if (trim($datosEstudiante['TIPO'])=='S')
                                        {
                                        ?>                                            
                                            <td class='cuadro_plano centrar' width="6%">Cr&eacute;ditos </td>
                                            <td class='cuadro_plano centrar' width="11%">Clasificaci&oacute;n </td>
                                        <?
                                        }
                                        ?>                                            
                                            <td class='cuadro_plano centrar' width="6%">Lun </td>
                                            <td class='cuadro_plano centrar' width="6%">Mar </td>
                                            <td class='cuadro_plano centrar' width="6%">Mie </td>
                                            <td class='cuadro_plano centrar' width="6%">Jue </td>
                                            <td class='cuadro_plano centrar' width="6%">Vie </td>
                                            <td class='cuadro_plano centrar' width="6%">S&aacute;b </td>
                                            <td class='cuadro_plano centrar' width="6%">Dom </td>
                                            </thead>

                                            <?
                                            //recorre cada uno del los grupos
                                            for ($j = 0; $j < count($resultado_grupos); $j++)
                                            {
                                                //
                                                $variables=array('CODIGO'=>$resultado_grupos[$j]['CODIGO'],
                                                                'GRUPO'=> $resultado_grupos[$j]['CURSO'],
                                                                'NOMBRE'=>$resultado_grupos[$j]['NOMBRE'],
                                                                'CREDITOS'=>(isset($resultado_grupos[$j]['CREDITOS'])?:''),
                                                                'CLASIFICACION'=>(isset($resultado_grupos[$j]['CLASIFICACION'])?$resultado_grupos[$j]['CLASIFICACION']:''),
                                                                'ANO'=>$this->ano,
                                                                'PERIODO'=>$this->periodo
                                                    );
                                                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                                                $resultado_horarios = $this->consultarHorarioEstudiante($variables);
                                                $clasificacionNumeros=array(1,2,3,4,5);
                                                $clasificacionLetras=array('OB','OC','EI','EE','CP');
                                                ?>
                                                <tr>
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['CODIGO']; ?></td>
                                                    <td class='cuadro_plano'><? echo $resultado_grupos[$j]['NOMBRE']; ?></td>
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['GRUPO']; ?></td>
                                                <?
                                                if (trim($datosEstudiante['TIPO'])=='S')
                                                {
                                                    $clasificacion=str_replace($clasificacionNumeros, $clasificacionLetras, $resultado_grupos[$j]['CLASIFICACION']);
                                                    ?>                                            
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['CREDITOS']; ?></td>
                                                    <td class='cuadro_plano centrar'><? echo $clasificacion; ?></td>
                                                    <?
                                                }
                                                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                                for ($i = 1; $i < 8; $i++)
                                                {
                                                    ?><td class='cuadro_plano centrar'><?
                                                //Recorre el arreglo del resultado de los horarios
                                                    $totalHorarios=count($resultado_horarios);
                                                    for ($k = 0; $k < $totalHorarios; $k++)
                                                    {

                                                        if ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') == ($resultado_horarios[$k]['DIA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                                            $l = $k;
                                                            while ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') == ($resultado_horarios[$k]['DIA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                                $m = $k;
                                                                $m++;
                                                                $k++;
                                                            }
                                                            $dia = "<strong>" . $resultado_horarios[$l]['DIA'] . "-" . ($resultado_horarios[$m]['DIA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$l]['NOM_SALON'];
                                                            echo $dia . "<br>";
                                                            unset($dia);
                                                        } elseif ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] != (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '')) {
                                                            $dia = "<strong>" . $resultado_horarios[$k]['DIA'] . "-" . ($resultado_horarios[$k]['DIA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['NOM_SALON'];
                                                            echo $dia . "<br>";
                                                            unset($dia);
                                                            $k++;
                                                        } elseif ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                                            $dia = "<strong>" . $resultado_horarios[$k]['DIA'] . "-" . ($resultado_horarios[$k]['DIA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['NOM_SALON'];
                                                            echo $dia . "<br>";
                                                            unset($dia);
                                                        } elseif ($resultado_horarios[$k]['HORA'] != $i) {

                                                        }
                                                    }
                                                    ?></td><?
                                                }
                                                ?>
                                                </tr>
                                                <?
                                            }
                                        if (trim($datosEstudiante['TIPO'])=='S')
                                        {?>                            
                                        <tr>
                                            <td colspan="11">
                                            <?
                                            $this->mostrar_convenciones_clasificacion();
                                            ?>
                                            </td>
                                        </tr>
                                        <?
                                        }
                                        $this->imprimirHorario();
                            } else
                                {
                                ?>
                                <tr>
                                    <td class='cuadro_plano centrar'>
                                        No se encontraron datos de espacios adicionados
                                    </td>
                                </tr>
                                <?
                                }
                            ?>
                        </table>
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>
        <?
    }

    /**
     * Funcion que presenta las convenciones de clasificacion de los espacios de creditos
     */
    function mostrar_convenciones_clasificacion() {
        ?>
        <table align="center" width="50%" >
            <tr>
                <th class="sigma centrar">
                    Abreviatura
                </th>
                <th class="sigma centrar">
                    Nombre
                </th>
            </tr>
            <?
            $resultado_clasificacion = $this->consultarClasificacion();

            for ($k = 0; $k < count($resultado_clasificacion); $k++) {
                ?>
                <tr>
                    <td class="sigma centrar">
            <? echo $resultado_clasificacion[$k][1] ?>
                    </td>
                    <td class="sigma ">
            <? echo $resultado_clasificacion[$k][2] ?>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>
        <?
    }
    
    /**
     * Funcion que permite colocar un boton para imprimir los datos básicos y el horario del estudiante
     */
    function imprimirHorario() {
        ?>
    <div id="parte2">
        <table align="center" >
            <tr>
                <td align="center" >
                    <input type="button" name="imprimir" value="Imprimir" onclick="window.print();">
                </td>
            </tr>
        </table>
    </div>    
        <?
        
    }    

    /**
     * Funcion que consulta las clasificaciones y abreviatras de los espacios de creditos
     * @return array
     */
    function consultarClasificacion() {
        $cadena_sql = $this->sql->cadena_sql("clasificacion", '');
        $resultado_clasificacion = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado_clasificacion;
    }
    
    /**
     * Funcion que consulta el horario registrado del estudiante
     * @param array $variables
     * @param array $this->configurarion
     * @return array
     */
    function consultarHorarioEstudiante($variables) {
        $this->cadena_sql = $this->sql->cadena_sql("horario_grupos", $variables);
        $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
        return $resultado_horarios;
    }
    
    /**
     * Funcion que consulta los datos del estudiante
     * @param type $datosEstudiante
     * @return type
     */
    function consultarDatosEstudiante($datosEstudiante) {
        $this->cadena_sql = $this->sql->cadena_sql("consultaEstudiante", $datosEstudiante);
        $registroEstudiante = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
        return $registroEstudiante;
        
    }
    
    /**
     * 
     * @param type $datosEstudiante
     * @return type
     */
    function consultarInscripcionesEstudiante($datosEstudiante) {
        $this->cadena_sql = $this->sql->cadena_sql("consultarInscripciones", $datosEstudiante);
        $registroInscripciones = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
        return $registroInscripciones;
    }
}
?>
