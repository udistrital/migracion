
<?php
/**
 * Funcion adminInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");

/**
 * Clase funcion_adminInscripcionGrupoCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class funcion_adminInscripcionGrupoCoordinador extends funcionGeneral {

    /**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionGrupoCoordinador
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion) {
        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $this->sql = new sql_adminInscripcionGrupoCoordinador();
        $this->formulario = "adminInscripcionGrupoCoordinador";

        /**
         * Intancia para crear la conexion ORACLE
         */
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        /**
         * Instancia para crear la conexion General
         */
        $this->acceso_db = $this->conectarDB($configuracion, "");
        /**
         * Instancia para crear la conexion de MySQL
         */
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        /**
         * Datos de sesion
         */
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    }

    /**
     * Funcion que crea el buscador de espacios academicos
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $planEstudioCoor plan de estudio
     * @param int $codProyecto Codigo del proyecto curricular
     */
    function buscador($configuracion, $planEstudioCoor, $codProyecto) {
?>
        <script>
            function mostrar_div(elemento) {

                if(elemento.value=="cod") {
                    document.getElementById("campo_palabra").style.display = "none";
                    document.getElementById("campo_codigo").style.display = "block";
                    document.forms[0].palabraEA.value='';
                }else if(elemento.value=="palab") {
                    document.getElementById("campo_codigo").style.display = "none";
                    document.getElementById("campo_palabra").style.display = "block";
                    document.forms[0].codigoEA.value='';
                }else {
                    document.getElementById("campo_codigo").style.display = "block";
                }

            }
        </script>
<?
        if ($planEstudioCoor) {
?>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div align="center">
                    <table class="sigma_borde centrar" width="100%">
                        <caption class="sigma centrar">
                            SELECCIONE LA OPCI&Oacute;N PARA BUSCAR EL ESPACIO ACAD&Eacute;MICO
                        </caption>
                        <tr class="sigma">
                            <td class="sigma derecha" width="20%">
                                C&oacute;digo<br>
                                Espacio Acad&eacute;mico
                            </td>
                            <td class="sigma centrar" width="2%">
                                <input type="radio" name="codigorad" value="cod" checked onclick="javascript:mostrar_div(this)"><br>
                                <input type="radio" name="codigorad" value="palab" onclick="javascript:mostrar_div(this)">
                            </td>
                            <td  class="sigma centrar">
                                <div align="center" id="campo_codigo">
                                    <table class="sigma centrar" width="80%" border="0">
                                        <tr>
                                            <td class="sigma centrar" colspan="2">
                                                <font size="1">Digite el c&oacute;digo del Espacio Académico que desea buscar</font><br>
                                                <input type="text" name="codigoEA" value="" size="6" maxlength="6">
                                            </td>
                                            <td class="sigma centrar" rowspan="2">
                                                <input type="hidden" name="opcion" value="buscador">
                                                <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                                <input type="hidden" name="planEstudioCoor" value="<? echo $planEstudioCoor ?>">
                                                <input type="hidden" name="codProyecto" value="<? echo $codProyecto ?>">
                                                <small><input class="boton" type="submit" value=" Buscar "></small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div align="center" id="campo_palabra" style="display:none">
                                    <table class="sigma centrar"  width="80%" border="0" >
                                        <tr>
                                            <td class="sigma centrar" colspan="3">
                                                <font size="1">Digite el nombre del Espacio Académico que desea buscar</font><br>
                                                <input type="text" name="palabraEA" value="" size="30" maxlength="30">
                                            </td>
                                            <td class="sigma centrar" rowspan="2">
                                                <input type="hidden" name="opcion" value="buscador">
                                                <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                                <input type="hidden" name="planEstudioCoor" value="<? echo $planEstudioCoor ?>">
                                                <input type="hidden" name="codProyecto" value="<? echo $codProyecto ?>">
                                                <small><input class="boton" type="submit" value=" Buscar "></small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>

                        </tr></table>
                </div>


            </form>
<?
        }
    }

    /**
     * Funcion que muestra los espacios academicos que se encuentran activos
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @global int $_REQUEST['planEstudio'] plan de estudio
     * @global int $_REQUEST['codProyecto'] Codigo del proyecto curricular
     */
    function consultarGrupos($configuracion) {
        if ($_REQUEST['planEstudio'] && $_REQUEST['codProyecto']) {
            $planEstudio = $_REQUEST['planEstudio'];
            $codProyecto = $_REQUEST['codProyecto'];
        } else {
            $cadena_sql = $this->sql->cadena_sql($configuracion, "datos_coordinador", $this->usuario); //echo $cadena_sql;exit;
            $resultado_datosCoordinador = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            $planEstudio = $resultado_datosCoordinador[0][2];
            $codProyecto = $resultado_datosCoordinador[0][0];
        }

        if ($_REQUEST['opcion'] == 'verProyectos' && count($resultado_datosCoordinador) > 1) {
            exit;
        }

        $this->buscador($configuracion, $planEstudio, $codProyecto);
        $nivel = -1;
        $espacio = 0;
        $variablesPlan = array($planEstudio, $codProyecto);

        $cadena_sql = $this->sql->cadena_sql($configuracion, "espacios_activos", $variablesPlan); //echo $cadena_sql;exit;
        $resultado_espacios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        $cadena_sql = $this->sql->cadena_sql($configuracion, "ano_periodo", ''); //echo $cadena_sql;exit;
        $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        ?>
      <table width="100%">
        <tr>
          <td class="centrar">
            <font color="red"><b>
                RECUERDE QUE LAS INSCRIPCIONES DE SEGUNDA LENGUA (ILUD) <br>SE PUEDEN REALIZAR A TRAVÉS DE "INSCRIPCI&Oacute;N POR ESTUDIANTE"
                <br> EN LA OPCI&Oacute;N REGISTRO &Aacute;GIL DE ESPACIOS ACAD&Eacute;MICOS</b></font>
          </td>
        </tr>
      </table>
      <?
        if (is_array($resultado_espacios)) {
?>
            <table class="sigma_borde centrar" width="100%">
                <caption class="sigma centrar">
                    Espacios Academicos activos para el periodo <? echo $resultado_periodo[0][0] . " - " . $resultado_periodo[0][1] ?>
                </caption>
<?
            for ($p = 0; $p < count($resultado_espacios); $p++) {
            //for($i=0;$i<6;$i++)
                if ($resultado_espacios[$p][5] != $nivel) {
                    if ($resultado_espacios[$p][5] == '0') {
?>
                            <th class="sigma_a centrar" colspan="11">
                                Electivas
                            </th>

    <?
                        $nivel = $resultado_espacios[$p][5];
                    } else {
    ?>
                        <th class="sigma_a centrar" colspan="11">
                            Nivel <? echo $resultado_espacios[$p][5] ?>
                        </th>
    <?
                        $nivel = $resultado_espacios[$p][5];
                    }
                }
                if ($resultado_espacios[$p][0] != $espacio) {
    ?>
                    <table class="sigma contenidotabla">
                        <tr>
                            <th class="sigma centrar" width="10%">C&oacute;digo</th>
                            <th class="sigma centrar" width="30%" colspan="6">Nombre Espacio Acad&eacute;mico</th>
                            <th class="sigma centrar" width="10%">Nro Cr&eacute;ditos</th>
                            <th class="sigma centrar" width="10%">H.T.D</th>
                            <th class="sigma centrar" width="10%">H.T.C</th>
                            <th class="sigma centrar" width="10%">H.T.A</th>
                        </tr>

    <?
                    $cadena_sql = $this->sql->cadena_sql($configuracion, "datos_espacio", $resultado_espacios[$p][0]); //echo $cadena_sql;exit;
                    $resultado_espaciosDesc = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    ?>

                        <tr>
                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][1] ?></font></th>
                            <th class="sigma centrar" colspan="6"><font size="2"><? echo $resultado_espaciosDesc[0][2] ?></font></th>
                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][3] ?></font></th>
                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][4] ?></font></th>
                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][5] ?></font></th>
                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][6] ?></font></th>
                        </tr>
                        <tr>
                            <th class="sigma centrar">Nro Grupo</th>
                            <th class="sigma centrar" width="12">Lunes</th>
                            <th class="sigma centrar" width="12">Martes</th>
                            <th class="sigma centrar" width="12">Miercoles</th>
                            <th class="sigma centrar" width="12">Jueves</th>
                            <th class="sigma centrar" width="12">Viernes</th>
                            <th class="sigma centrar" width="12">Sabado</th>
                            <th class="sigma centrar" width="12">Domingo</th>
                            <th class="sigma centrar">Nro Cupos</th>
                            <th class="sigma centrar">Disponibles</th>
                            <th class="sigma centrar">Administrar</th>
                        </tr>
<?
                    $espacio = $resultado_espacios[$p][0];
                }
                $variablesInscritos = array($espacio, $resultado_espacios[$p][1]);
                $variables = array($resultado_espacios[$p][0], $resultado_espacios[$p][2], '', $resultado_espacios[$p][1]);
                $cadena_sql_horarios = $this->sql->cadena_sql($configuracion, "horario_grupos", $variables); //echo $cadena_sql_horarios;exit;
                $resultado_horarios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios, "busqueda");

                $cadena_sql = $this->sql->cadena_sql($configuracion, "espacio_grupoInscritos", $variablesInscritos); //echo $cadena_sql;exit;
                $resultado_inscritos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
?>
                <tr>
                    <td class="cuadro_plano centrar"><? echo $resultado_espacios[$p][1] ?></td>
<?
                $this->mostrarHorario($configuracion, $resultado_horarios);
?>
                    </td>
                    <td class="cuadro_plano centrar"><? echo $resultado_espacios[$p][3] ?></td>
                    <td class="cuadro_plano centrar"><? echo ($resultado_espacios[$p][3] - $resultado_inscritos[0][0]) ?></td>
                    <td class="cuadro_plano centrar">
        <?
                if (is_array($resultado_horarios)) {
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                    $variable = "pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=" . $resultado_espaciosDesc[0][1];
                    $variable.="&nombreEspacio=" . $resultado_espaciosDesc[0][2];
                    $variable.="&nroCreditos=" . $resultado_espaciosDesc[0][3];
                    $variable.="&nroGrupo=" . $resultado_espacios[$p][1];
                    $variable.="&planEstudio=" . $planEstudio;
                    $variable.="&codProyecto=" . $codProyecto;
                    $variable.="&clasificacion=" . $resultado_espaciosDesc[0][7];

                    //var_dump($_REQUEST);exit;
                    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $configuracion);
        ?>
                        <a href="<? echo $pagina . $variable ?>" >
                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/kate.png" width="20" height="20" border="0">
                    </a>
                <?
                } else {
                    echo "Falta horario";
                }
                ?>
            </td>
        </tr>
                <?
            }
        }
    }

    /**
     * Funcion que muestra el horario oraganizandolo por dias
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param array $resultado_horarios Contiene datos como el dia, hora, sede y salon
     */
    function mostrarHorario($configuracion, $resultado_horarios) {
        if (is_array($resultado_horarios)) {
            for ($i = 1; $i < 8; $i++) {
                ?><td class='cuadro_plano centrar'><?
                for ($k = 0; $k < count($resultado_horarios); $k++) {

                    if ($resultado_horarios[$k][0] == $i && $resultado_horarios[$k][0] == $resultado_horarios[$k + 1][0] && $resultado_horarios[$k + 1][1] == ($resultado_horarios[$k][1] + 1) && $resultado_horarios[$k + 1][3] == ($resultado_horarios[$k][3])) {
                        $l = $k;
                        while ($resultado_horarios[$k][0] == $i && $resultado_horarios[$k][0] == $resultado_horarios[$k + 1][0] && $resultado_horarios[$k + 1][1] == ($resultado_horarios[$k][1] + 1) && $resultado_horarios[$k + 1][3] == ($resultado_horarios[$k][3])) {

                            $m = $k;
                            $m++;
                            $k++;
                        }
                        $dia = "<strong>" . $resultado_horarios[$l][1] . "-" . ($resultado_horarios[$m][1] + 1) . "</strong>";
                        echo $dia . "<br>";
                        unset($dia);
                    } elseif ($resultado_horarios[$k][0] == $i && $resultado_horarios[$k][0] != $resultado_horarios[$k + 1][0]) {
                        $dia = "<strong>" . $resultado_horarios[$k][1] . "-" . ($resultado_horarios[$k][1] + 1) . "</strong>";
                        echo $dia . "<br>";
                        unset($dia);
                        $k++;
                    } elseif ($resultado_horarios[$k][0] == $i && $resultado_horarios[$k][0] == $resultado_horarios[$k + 1][0] && $resultado_horarios[$k + 1][3] != ($resultado_horarios[$k][3])) {
                        $dia = "<strong>" . $resultado_horarios[$k][1] . "-" . ($resultado_horarios[$k][1] + 1) . "</strong>";
                        echo $dia . "<br>";
                        unset($dia);
                    } elseif ($resultado_horarios[$k][0] != $i) {

                    }
                }
            }
        } else {
            echo "<td class='cuadro_plano centrar' colspan='7'>No tiene horario registrado</td>";
        }
    }

    /**
     * Funcion que muestra el espacio academico seleccionado, dependiendo del codigo digitado en el buscador
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @global int $_REQUEST['planEstudioCoor'] plan de estudio del coordinador
     * @global int $_REQUEST['codProyecto'] Codigo del proyecto curricular
     * @global int $_REQUEST['codEspacio'] Codigo del espacio academico digitado
     * @global string $_REQUEST['palabraEA'] Nombre del espacio academico digitado
     */
    function consultarGruposSeleccionado($configuracion) {
        $atributos[0]['inicio'] = "pagina=adminInscripcionCoordinador";
        $atributos[1]['inicio'] = "&opcion=mostrar";
        $atributos[2]['inicio'] = "&planEstudio=" . $_REQUEST['planEstudioCoor'];
        $atributos[3]['inicio'] = "&codProyecto=" . $_REQUEST['codProyecto'];
        $atributos[0]['atras'] = "pagina=adminInscripcionCoordinador";
        $atributos[1]['atras'] = "&opcion=mostrar";
        $atributos[2]['atras'] = "&planEstudio=" . $_REQUEST['planEstudioCoor'];
        $atributos[3]['atras'] = "&codProyecto=" . $_REQUEST['codProyecto'];

        $this->encabezado($configuracion, $atributos);

        $nivel = 0;
        $espacio = 0;

        if ($_REQUEST['codEspacio']) {
            if (!is_numeric($_REQUEST['codEspacio'])) {
                echo "<script>alert('El código del espacio académico debe ser numerico')</script>";
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variables = "pagina=adminInscripcionGrupoCoordinador";
                $variables.="&opcion=consultar";
                $variables.="&planEstudio=" . $_REQUEST['planEstudioCoor'];
                $variables.="&codProyecto=" . $_REQUEST['codProyecto'];

                include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variables = $this->cripto->codificar_url($variables, $configuracion);
                echo "<script>location.replace('" . $pagina . $variables . "')</script>";
            }
            $variableCodigo = array($_REQUEST['planEstudioCoor'], $_REQUEST['codEspacio'], $_REQUEST['codProyecto']);
            $cadena_sql = $this->sql->cadena_sql($configuracion, "espacios_seleccionadoCodigo", $variableCodigo); //echo $cadena_sql;exit;
            $resultado_espacios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); //var_dump($resultado_planEstudio);exit;

            $this->buscador($configuracion, $_REQUEST['planEstudioCoor'], $_REQUEST['codProyecto']);


            if (is_array($resultado_espacios)) {
                for ($p = 0; $p < count($resultado_espacios); $p++) {
                    if ($resultado_espacios[$p][5] != $nivel) {
 ?>
                            <table class="sigma_borde centrar" width="100%">
                                <th class="sigma_a centrar" colspan="11">
                                    Nivel <? echo $resultado_espacios[$p][5] ?>
                                </th>

        <?
                        $nivel = $resultado_espacios[$p][5];
                    }

                    if ($resultado_espacios[$p][0] != $espacio) {
        ?>
                                <table class="sigma contenidotabla centrar">

                                    <tr>
                                        <th class="sigma centrar" width="10%">C&oacute;digo</th>
                                        <th class="sigma centrar" width="30%" colspan="6">Nombre Espacio Acad&eacute;mico</th>
                                        <th class="sigma centrar" width="10%">Nro Cr&eacute;ditos</th>
                                        <th class="sigma centrar" width="10%">H.T.D</th>
                                        <th class="sigma centrar" width="10%">H.T.C</th>
                                        <th class="sigma centrar" width="10%">H.T.A</th>
                                    </tr>

        <?
                        $cadena_sql = $this->sql->cadena_sql($configuracion, "datos_espacio", $resultado_espacios[$p][0]); //echo $cadena_sql;exit;
                        $resultado_espaciosDesc = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        ?>

                                    <tr>
                                        <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][1] ?></font></th>
                                        <th class="sigma centrar" colspan="6"><font size="2"><? echo $resultado_espaciosDesc[0][2] ?></font></th>
                                        <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][3] ?></font></th>
                                        <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][4] ?></font></th>
                                        <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][5] ?></font></th>
                                        <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][6] ?></font></th>
                                    </tr>

                                    <tr>
                                        <th class="sigma centrar">Nro Grupo</th>
                                        <th class="sigma centrar" width="12">Lunes</th>
                                        <th class="sigma centrar" width="12">Martes</th>
                                        <th class="sigma centrar" width="12">Miercoles</th>
                                        <th class="sigma centrar" width="12">Jueves</th>
                                        <th class="sigma centrar" width="12">Viernes</th>
                                        <th class="sigma centrar" width="12">Sabado</th>
                                        <th class="sigma centrar" width="12">Domingo</th>
                                        <th class="sigma centrar">Nro Cupos</th>
                                        <th class="sigma centrar">Disponibles</th>
                                        <th class="sigma centrar">Administrar</th>
                                    </tr>
<?
                        $espacio = $resultado_espacios[$p][0];
                    }

                    $variables = array($resultado_espacios[$p][0], $resultado_espacios[$p][2], '', $resultado_espacios[$p][1]);
                    $cadena_sql_horarios = $this->sql->cadena_sql($configuracion, "horario_grupos", $variables); //echo $cadena_sql_horarios;exit;
                    $resultado_horarios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios, "busqueda");
?>
                    <tr>
                        <td class="cuadro_plano centrar"><? echo $resultado_espacios[$p][1] ?></td>
                    <?
                    $this->mostrarHorario($configuracion, $resultado_horarios);
                    ?>
                        </td>
                        <td class="cuadro_plano centrar"><? echo $resultado_espacios[$p][3] ?></td>
                        <td class="cuadro_plano centrar"><? echo ($resultado_espacios[$p][3] - $resultado_espacios[$p][4]) ?></td>
                        <td class="cuadro_plano centrar">
<?
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                    $variable = "pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&codEspacio=" . $resultado_espaciosDesc[0][1];
                    $variable.="&nombreEspacio=" . $resultado_espaciosDesc[0][2];
                    $variable.="&nroCreditos=" . $resultado_espaciosDesc[0][3];
                    $variable.="&nroGrupo=" . $resultado_espacios[$p][1];
                    $variable.="&planEstudio=" . $_REQUEST['planEstudioCoor'];
                    $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
                    $variable.="&clasificacion=" . $resultado_espaciosDesc[0][7];
                    //var_dump($_REQUEST);exit;
                    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $configuracion);
?>
                            <a href="<? echo $pagina . $variable ?>">
                                <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/kate.png" width="20" height="20" border="0">
                            </a>
                        </td>
                    </tr>
                    <?
                }
            } else {
                    ?>
                    <table class="contenidotabla">
                        <tr>
                            <td class="centrar">
                                No hay grupos habilitados en el periodo actual que corresponda con su busqueda
                            </td>
                        </tr>
                    </table>
                        <?
                    }
                } else if ($_REQUEST['palabraEA']) {
                    $nombreEspacio = strtr(strtoupper($_REQUEST['palabraEA']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");

                    $variableCodigo = array($_REQUEST['planEstudioCoor'], $nombreEspacio, $_REQUEST['codProyecto']);
                    $cadena_sql = $this->sql->cadena_sql($configuracion, "espacios_seleccionadoPalabra", $variableCodigo); //echo $cadena_sql;exit;
                    $resultado_espacios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); //var_dump($resultado_planEstudio);exit;

                    $this->buscador($configuracion, $_REQUEST['planEstudioCoor'], $_REQUEST['codProyecto']);


                    if (is_array($resultado_espacios)) {
                        for ($p = 0; $p < count($resultado_espacios); $p++) {
                            if ($resultado_espacios[$p][5] != $nivel) {
 ?>
                        <table class="sigma_borde centrar" width="100%">
                            <th class="sigma_a centrar" colspan="11">
                                Nivel <? echo $resultado_espacios[$p][5] ?>
                            </th>

<?
                                $nivel = $resultado_espacios[$p][5];
                            }

                            if ($resultado_espacios[$p][0] != $espacio) {
?>
                                    <table class="sigma contenidotabla centrar">

                                        <tr>
                                            <th class="sigma centrar" width="10%">C&oacute;digo</th>
                                            <th class="sigma centrar" width="30%" colspan="6">Nombre Espacio Acad&eacute;mico</th>
                                            <th class="sigma centrar" width="10%">Nro Cr&eacute;ditos</th>
                                            <th class="sigma centrar" width="10%">H.T.D</th>
                                            <th class="sigma centrar" width="10%">H.T.C</th>
                                            <th class="sigma centrar" width="10%">H.T.A</th>
                                        </tr>

<?
                                $cadena_sql = $this->sql->cadena_sql($configuracion, "datos_espacio", $resultado_espacios[$p][0]); //echo $cadena_sql;exit;
                                $resultado_espaciosDesc = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
?>

                                        <tr>
                                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][1] ?></font></th>
                                            <th class="sigma centrar" colspan="6"><font size="2"><? echo $resultado_espaciosDesc[0][2] ?></font></th>
                                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][3] ?></font></th>
                                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][4] ?></font></th>
                                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][5] ?></font></th>
                                            <th class="sigma centrar"><font size="2"><? echo $resultado_espaciosDesc[0][6] ?></font></th>
                                        </tr>

                                        <tr>
                                            <th class="sigma centrar">Nro Grupo</th>
                                            <th class="sigma centrar" width="12">Lunes</th>
                                            <th class="sigma centrar" width="12">Martes</th>
                                            <th class="sigma centrar" width="12">Miercoles</th>
                                            <th class="sigma centrar" width="12">Jueves</th>
                                            <th class="sigma centrar" width="12">Viernes</th>
                                            <th class="sigma centrar" width="12">Sabado</th>
                                            <th class="sigma centrar" width="12">Domingo</th>
                                            <th class="sigma centrar">Nro Cupos</th>
                                            <th class="sigma centrar">Disponibles</th>
                                            <th class="sigma centrar">Administrar</th>
                                        </tr>
                        <?
                                $espacio = $resultado_espacios[$p][0];
                            }

                            $variables = array($resultado_espacios[$p][0], $resultado_espacios[$p][2], '', $resultado_espacios[$p][1]);
                            $cadena_sql_horarios = $this->sql->cadena_sql($configuracion, "horario_grupos", $variables); //echo $cadena_sql_horarios;exit;
                            $resultado_horarios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios, "busqueda");
                        ?>
                                <tr>
                                    <td class="cuadro_plano centrar"><? echo $resultado_espacios[$p][1] ?></td>
<?
                            $this->mostrarHorario($configuracion, $resultado_horarios);
?>
                                    </td>
                                    <td class="cuadro_plano centrar"><? echo $resultado_espacios[$p][3] ?></td>
                                    <td class="cuadro_plano centrar"><? echo ($resultado_espacios[$p][3] - $resultado_espacios[$p][4]) ?></td>
                                    <td class="cuadro_plano centrar">
<?
                            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                            $variable = "pagina=adminConsultarInscripcionGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&codEspacio=" . $resultado_espaciosDesc[0][1];
                            $variable.="&nombreEspacio=" . $resultado_espaciosDesc[0][2];
                            $variable.="&nroCreditos=" . $resultado_espaciosDesc[0][3];
                            $variable.="&nroGrupo=" . $resultado_espacios[$p][1];
                            $variable.="&planEstudio=" . $_REQUEST['planEstudioCoor'];
                            $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
                            $variable.="&clasificacion=" . $resultado_espaciosDesc[0][7];
                            //var_dump($_REQUEST);exit;
                            include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                            $this->cripto = new encriptar();
                            $variable = $this->cripto->codificar_url($variable, $configuracion);
?>
                                    <a href="<? echo $pagina . $variable ?>">
                                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/kate.png" width="20" height="20" border="0">
                                    </a>
                                </td>
                            </tr>
<?
                        }
                    } else {
?>
                            <table class="contenidotabla">
                                <tr>
                                    <td class="centrar">
                                        No hay grupos habilitados en el periodo actual que corresponda con su busqueda
                                    </td>
                                </tr>
                            </table>
                            <?
                        }
                    } else if ($_REQUEST['codEspacio'] == '' && $_REQUEST['palabraEA'] == '') {
                        echo "<script>alert('Digite el nombre o código del espacio académico')</script>";
                        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                        $variable = "pagina=adminInscripcionCoordinador";
                        $variable.="&opcion=mostrar";
                        $variable.="&planEstudio=" . $_REQUEST['planEstudioCoor'];
                        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
                        $variable = $this->cripto->codificar_url($variable, $configuracion);

                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    }
                }

    /**
     * Funcion que crea los iconos de navegacion dentro del sistema
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param <type> $configuracion
     * @param <type> $atributos
     */
    function encabezado($configuracion, $atributos) {
                            ?>
                            <table class="contenidotabla centrar">
                                <tr>
                                    <td width="33%" class="centrar">
                                    <?
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variable = $atributos[0]['atras'];
                                    $variable.=$atributos[1]['atras'];
                                    $variable.=$atributos[2]['atras'];
                                    $variable.=$atributos[3]['atras'];
                                    $variable = $this->cripto->codificar_url($variable, $configuracion);
                                    ?>
                                        <a href="<? echo $pagina . $variable ?>">
                                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/atras.png" width="35" height="35" border="0">
                                        </a>
                                    </td>
                                    <td width="33%" class="centrar">
                            <?
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variable = $atributos[0]['inicio'];
                                    $variable.=$atributos[1]['inicio'];
                                    $variable.=$atributos[2]['inicio'];
                                    $variable.=$atributos[3]['inicio'];
                                    $variable = $this->cripto->codificar_url($variable, $configuracion);
                            ?>
                                                <a href="<? echo $pagina . $variable ?>">
                                                    <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0">
                                                </a>
                                            </td>
                                            <td width="33%" class="centrar">
                                                <a href="history.forward()">
                                                    <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/continuar.png" width="35" height="35" border="0">
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                            <?
                                }

    }
    ?>