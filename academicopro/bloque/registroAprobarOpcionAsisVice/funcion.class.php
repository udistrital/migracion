<?php
/*--------------------------------------------------------------------------------------------------------------------------
@ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
if (!isset($GLOBALS["autorizado"])) {
    include ("../index.php");
    exit;
}
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
//@ Clase que contiene los mÃ©todos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_registroAprobarOpcionAsisVice extends funcionGeneral {
    //@ MÃ©todo costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion)
    {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $this->tema = $tema;
        $this->sql = new sql_registroAprobarOpcionAsisVice();
        $this->log_us = new log();
        $this->formulario = "registroAprobarOpcionAsisVice";
        //Conexion ORACLE
        $this->accesoOracle = $this->conectarDB($configuracion, "oraclesga");
        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");
        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    } #Cierre de constructor

    function formularioComentarioAprobar($configuracion)
    {
        $codEspacio = $_REQUEST['codEspacio'];
        $planEstudio = $_REQUEST['planEstudio'];
        $nivel = $_REQUEST['nivel'];
        $creditos = $_REQUEST['creditos'];
        $htd = $_REQUEST['htd'];
        $htc = $_REQUEST['htc'];
        $hta = $_REQUEST['hta'];
        $clasificacion = $_REQUEST['clasificacion'];
        $nombreEspacio = $_REQUEST['nombreEspacio'];
        $nombreGeneral = $_REQUEST['nombreGeneral'];
        $idEncabezado = $_REQUEST['idEncabezado'];
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo");
        $periodoActivo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscar_id", $planEstudio);
        $resultado_proyecto = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
        $codProyecto = $resultado_proyecto[0][10];
        $nombreProyecto = $resultado_proyecto[0][7];
        $variables = array($codEspacio, $planEstudio, $codProyecto, $this->usuario, date('YmdHis'), $nombreGeneral, $nivel, $creditos, $htd, $htc, $hta, $clasificacion, $nombreEspacio, $idEncabezado);
        $cadena_sql = $this->sql->cadena_sql($configuracion, "cambiarEstadoAsociacion", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");
        $variablesRegistro = array($this->usuario, date('YmdHis'), '23', 'Aprobo Asociación de Espacio Academico', $periodoActivo[0][0]."-".$periodoActivo[0][1].",". $codEspacio . ", 0, 0, " . $planEstudio . ", ", $planEstudio);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
        $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
        $cadena_sql = $this->sql->cadena_sql($configuracion, "comentarioAutomatico", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");
        $this->aprobarEspacioAcademico($configuracion, $variables);
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
?>
<table class='contenidotabla centrar' border="0" background="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

            <tr align="center">
                <td class="centrar" colspan="2">
                    <h4>LA ASOCIACI&Oacute;N DEL ESPACIO ACAD&Eacute;MICO HA SIDO APROBADO</h4>
                    <h4>Este Espacio Acad&eacute;mico es opci&oacute;n de <? echo $nombreGeneral ?></h4>
                    <hr noshade class="hr">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="40%">Fecha:</td><td class="cuadro_plano"><? echo date('d/m/Y') ?></td>
            </tr>
             <tr>
                <td class="cuadro_plano">Plan de Estudios:</td><td class="cuadro_plano"><? echo $planEstudio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">C&oacute;digo del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><? echo $codEspacio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nombre del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><? echo $nombreEspacio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Cr&eacute;ditos:</td><td class="cuadro_plano"><? echo $creditos ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Directo:</td><td class="cuadro_plano"><? echo $htd ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Cooperativo:</td><td class="cuadro_plano"><? echo $htc ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Autonomo:</td><td class="cuadro_plano"><? echo $hta ?></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar">
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
                    <font size="2">Comentario de aprobaci&oacute;n del espacio acad&eacute;mico <? echo $nombreEspacio ?></font><br>
                <textarea cols="70" rows="7" name="comentario"></textarea>
            </td>
            </tr>
</table>
<table class='contenidotabla centrar' border="0">
            <tr>

                <td class="centrar" width="50%">
                       <input type="hidden" name="codEspacio" value="<? echo $codEspacio ?>">
                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">
                        <input type="hidden" name="codProyecto" value="<? echo $codProyecto ?>">
                        <input type="hidden" name="clasificacion" value="<? echo $clasificacion ?>">
                        <input type="hidden" name="nombreEspacio" value="<? echo $nombreEspacio ?>">
                        <input type="hidden" name="creditos" value="<? echo $creditos ?>">
                        <input type="hidden" name="nivel" value="<? echo $nivel ?>">
                        <input type="hidden" name="htd" value="<? echo $htd ?>">
                        <input type="hidden" name="htc" value="<? echo $htc ?>">
                        <input type="hidden" name="hta" value="<? echo $hta ?>">
                        <input type="hidden" name="nombreGeneral" value="<? echo $nombreGeneral ?>">
                        <input type="hidden" name="idEncabezado" value="<? echo $idEncabezado ?>">
                        <input type="hidden" name="opcion" value="enviarformulario">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="Confirmado" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="35" height="35"><br>Enviar
                </td>
                </form>
                <td class="centrar" width="50%">
                 <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>

                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">

                        <input type="hidden" name="opcion" value="mostrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="cancelar" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="35" height="35"><br>Cancelar
                 </form>
                </td>
            </tr>
        </table>

        <?
    }
    function encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto)
    {

?>

<table class='contenidotabla centrar' background="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

    <tr align="center">
        <td class="centrar">
            <h4>MODULO PARA LA ADMINISTRACI&Oacute;N DE PLANES DE ESTUDIO</h4>
            <hr noshade class="hr">

        </td>
    </tr>

</table><?
    }
    function enviarComentario($configuracion)
    {
        $planEstudio = $_REQUEST['planEstudio'];
        $codEspacio = $_REQUEST['codEspacio'];
        $comentario = $_REQUEST['comentario'];
        $codProyecto = $_REQUEST['codProyecto'];
        $clasificacion = $_REQUEST['clasificacion'];
        $nombreEspacio = $_REQUEST['nombreEspacio'];
        $creditos = $_REQUEST['creditos'];
        $nombreGeneral = $_REQUEST['nombreGeneral'];
        $idEncabezado = $_REQUEST['idEncabezado'];
        if ($comentario == '') {
            echo '<script>alert("No existe comentario; para enviar un comentario puede hacerlo en la configuración del plan de estudio")</script>';
            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
            $variables = "pagina=adminAprobarEspacioPlan";
            $variables.= "&opcion=mostrar";
            $variables.= "&planEstudio=" . $planEstudio;
            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variables = $this->cripto->codificar_url($variables, $configuracion);
            echo "<script>location.replace('" . $pagina . $variables . "')</script>";
        } else {
            $variables = array($codEspacio, $planEstudio, $codProyecto, $this->usuario, date('YmdHis'), $comentario); //var_dump($variables);exit;
            $cadena_sql = $this->sql->cadena_sql($configuracion, "comentarioEscrito", $variables); //echo $cadena_sql;exit;
            $resultadoComentarioEspacio = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");
            echo "<script>alert('Comentario registrado correctamente para el espacio acadÃ©mico " . $codEspacio . "')</script>";
            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
            $variables = "pagina=adminAprobarEspacioPlan";
            $variables.= "&opcion=mostrar";
            $variables.= "&planEstudio=" . $planEstudio;
            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variables = $this->cripto->codificar_url($variables, $configuracion);
            echo "<script>location.replace('" . $pagina . $variables . "')</script>";
        }
    }
    function aprobarEspacioAcademico($configuracion, $variables)
    {
        $codEspacio = $variables[0];
        $planEstudio = $variables[1];
        $nivel = $variables[6];
        $creditos = $variables[7];
        $htd = $variables[8];
        $htc = $variables[9];
        $hta = $variables[10];
        $clasificacion = $variables[11];
        $nombreEspacio = $variables[12];
        $nombreGeneral = $variables[5];
        $idEncabezado = $variables[13];
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo");
        $periodoActivo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

        $cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacio", $variables); //echo $cadena_sql;exit;
        $resultadoEspacioAprobado = $this->accesoGestion->ejecutarAcceso($cadena_sql, "busqueda");
        //var_dump($resultadoEspacioAprobado[0][0]);exit;
        if ($resultadoEspacioAprobado[0][0] == 0) {
            #Actualiza la aprobacion de los espacios academicos
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "aprobarEspacio", $variables); //echo $this->cadena_sql;exit;
            $registroEspaciosPlan = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
            $totalEspacios = $this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
            //Vericar que se ejecuto la aprobacion de cada uno de los espacios academicos
            //var_dump($registroEspaciosPlan);exit;
            //            $registroEspaciosPlan=true;
            if ($registroEspaciosPlan == true) {
                //Busca los datos del espacio academico que se va a aprobar, para poder pasarlos a oracle
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosEspacio", $variables);
                $registrodatosEspacios = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                //Busca los datos de la carrera
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosNumeroCarreras", $variables);//echo $this->cadena_sql;exit;
                $numeroCarreras = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosCarrera", $variables);
                $registrodatosCarrera = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                //Si carga el espacio academico en Oracle en la tabla acasi, debemos cargarlo ahora en acpen
                if ($clasificacion == '3' || $clasificacion == '4') {
                    $electivo = 'S';
                } else {
                    $electivo = 'N';
                }
                $nombreEspacio = strtr(strtoupper($registrodatosEspacios[0][0]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                //Arreglo para tener los datos a cargar en ACASI (Oracle)
                $variableAcasi = array($codEspacio, $nombreEspacio, $registrodatosCarrera[0][0], 'A', 'S', 'N');
                // Arreglo para tener los datos a cargar en ACPEN (Oracle)
                for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                $variableAcpen[$a] = array($registrodatosCarrera[$a][1], $codEspacio, $nivel, $electivo, $htd, $htc, 'A', $creditos, $planEstudio, $hta);
                }
                //buscar datos en ACASI
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacioAcasi", $variableAcasi); //echo $this->cadena_sql;exit;
                $busquedaAcasi = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
                //Si existe el espacio academico en acasi, se busca en acpen si esta registrado con el plan de estudio
                if (is_array($busquedaAcasi)) {
                    //buscar datos en ACPEN
                  for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacioAcpen", $variableAcpen[$a]); //echo $this->cadena_sql;exit;
                    $busquedaAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
                    if (is_array($busquedaAcpen)) {break;}
                  }
                    //Si existen datos en acpen con el plan de estudios estipulado, se envia mensaje de que no se puede cargar el espacio academico por que ya esta cargado
                    if (is_array($busquedaAcpen)) {
                        //$this->cadena_sql=$this->sql->cadena_sql($configuracion,"DesaprobarEspacio",$variables);
                        //$cambiarEstadoEspacio=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadoAprobadoAsociacion", $variables); //echo $this->cadena_sql;exit;
                        $estadoAprobadoAsociacion = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
?>
                                            <table class='contenidotabla centrar' border="0" width="100%">
                                                <tr align="center">
                                                    <td class="centrar" colspan="4">
                                                        <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 102</h4>
                                                        <hr noshade class="hr">

                                                    </td>
                                                </tr>
                                                                    <?
                        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                        $variables = "pagina=adminAprobarEspacioPlan";
                        $variables.= "&opcion=mostrar";
                        $variables.= "&planEstudio=" . $planEstudio;
                        include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                        $this->cripto = new encriptar();
                        $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                                                <tr align="center">
                                                    <td class="centrar" colspan="4">
                                                        <a href="<? echo $pagina . $variables ?>" class="centrar">
                                                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table><?
                        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                        $variables = "pagina=adminAprobarEspacioPlan";
                        $variables.= "&opcion=mostrar";
                        $variables.= "&planEstudio=" . $planEstudio;
                        include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                        $this->cripto = new encriptar();
                        $variables = $this->cripto->codificar_url($variables, $configuracion);
                        echo "<script>location.replace('" . $pagina . $variables . "')</script>";
                    } else {
                      for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cargarEspacioAcpen", $variableAcpen[$a]); //echo $this->cadena_sql;exit;
                        $registroEspaciosCargadoAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");

                        $variableAcpen[$a]['clasificacion']=$clasificacion;
                        $this->cadena_sql=  $this->sql->cadena_sql($configuracion, 'registrarClasificacion', $variableAcpen[$a]);//echo $this->cadena_sql;exit;
                        $registroClasificacion = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");

                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadocargarEspacio", $variableAcpen[$a]);
                        $registroEstadoCargado = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadoAprobadoAsociacion", $variables); //echo $this->cadena_sql;exit;
                        $estadoAprobadoAsociacion = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                        //Arreglo para registrar en el log de eventos
                        $variablesRegistro = array($this->usuario, date('YmdHis'), '18', 'Aprobo Espacio Academico Existente', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ". $codEspacio . ", 0, 0, " . $planEstudio . ", ", $planEstudio);
                        //$this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
                        $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                        }
                        if ($registroEspaciosCargadoAcpen == true) {
?>
                                            <table class='contenidotabla centrar' border="0" width="100%">
                                                <tr align="center">
                                                    <td class="centrar" colspan="4">
                                                        <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> HA SIDO APROBADO Y CARGADO CORRECTAMENTE</h4>
                                                        <hr noshade class="hr">

                                                    </td>
                                                </tr>
                                                                    <?
                            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                            $variables = "pagina=adminAprobarEspacioPlan";
                            $variables.= "&opcion=mostrar";
                            $variables.= "&planEstudio=" . $planEstudio;
                            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                            $this->cripto = new encriptar();
                            $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                                                <tr align="center">
                                                    <td class="centrar" colspan="4">
                                                        <a href="<? echo $pagina . $variables ?>" class="centrar">
                                                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table><?
                        } else {
                            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variables);
                            $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                            if ($cambiarEstadoEspacio == true) { ?>
                                                <table class='contenidotabla centrar' border="0" width="100%">
                                                    <tr align="center">
                                                        <td class="centrar" colspan="4">
                                                            <h6>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 101 </h6>
                                                            <hr noshade class="hr">

                                                        </td>
                                                    </tr><?
                                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                $variables = "pagina=adminAprobarEspacioPlan";
                                $variables.= "&opcion=mostrar";
                                $variables.= "&planEstudio=" . $planEstudio;
                                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                $this->cripto = new encriptar();
                                $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                                                    <tr align="center">
                                                        <td class="centrar" colspan="4">
                                                            <a href="<? echo $pagina . $variables ?>" class="centrar">
                                                                <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            <?
                            }
                        }
                    }
                } else {
                  for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                    //buscar datos en ACPEN
                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacioAcpen", $variableAcpen[$a]); //echo $this->cadena_sql;exit;
                    $busquedaAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
                    if (is_array($busquedaAcpen)) {break;}
                  }

                    if (is_array($busquedaAcpen)) {
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variables);
                        $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
?>
                        <table class='contenidotabla centrar' border="0" width="100%">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 102</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr>
                                                                    <?
                        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                        $variables = "pagina=adminAprobarEspacioPlan";
                        $variables.= "&opcion=mostrar";
                        $variables.= "&planEstudio=" . $planEstudio;
                        include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                        $this->cripto = new encriptar();
                        $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <a href="<? echo $pagina . $variables ?>" class="centrar">
                                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                    </a>
                                </td>
                            </tr>
                        </table>
                                            <?
                        $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                        $variables = "pagina=adminAprobarEspacioPlan";
                        $variables.= "&opcion=mostrar";
                        $variables.= "&planEstudio=" . $planEstudio;
                        include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                        $this->cripto = new encriptar();
                        $variables = $this->cripto->codificar_url($variables, $configuracion);
                        echo "<script>location.replace('" . $pagina . $variables . "')</script>";
                        break;
                    } else {
                      //Cargar datos en ACASi
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cargarEspacioAcasi", $variableAcasi); //echo $this->cadena_sql;exit;
                        $registroEspaciosCargadoAcasi = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
                        if ($registroEspaciosCargadoAcasi == true) {
                            //Cargar datos en ACPEN
                          for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cargarEspacioAcpen", $variableAcpen[$a]); //echo $this->cadena_sql;exit;
                            $registroEspaciosCargadoAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
                            //$registroEspaciosCargadoAcpen=true;
                            if ($registroEspaciosCargadoAcpen == true) {
                                $variableAcpen[$a]['clasificacion']=$clasificacion;
                                $this->cadena_sql=  $this->sql->cadena_sql($configuracion, 'registrarClasificacion', $variableAcpen[$a]);//echo $this->cadena_sql;exit;
                                $registroClasificacion = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
                                //Si se registra en acpen se envia mensaje notificando de que se aprobo correctamente
                                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadocargarEspacio", $variableAcpen[$a]); //echo $this->cadena_sql;exit;
                                $registroEstadoCargado = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadoAprobadoAsociacion", $variables); //echo $this->cadena_sql;exit;
                                $estadoAprobadoAsociacion = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                                //Arreglo para registrar en el log de eventos
                                $variablesRegistro = array($this->usuario, date('YmdHis'), '10', 'Aprobo Espacio Academico', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ". $codEspacio . ", 0, 0, " . $planEstudio . ", ", $planEstudio);
                                //$this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
                                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
                                $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                            }
                          }
                          if ($registroEspaciosCargadoAcpen == true) {
?>
                              <table class='contenidotabla centrar' border="0" width="100%">
                                  <tr align="center">
                                      <td class="centrar" colspan="4">
                                          <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> HA SIDO APROBADO Y CARGADO CORRECTAMENTE</h4>
                                          <hr noshade class="hr">

                                      </td>
                                  </tr>
                                                                    <?
                                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                $variables = "pagina=adminAprobarEspacioPlan";
                                $variables.= "&opcion=mostrar";
                                $variables.= "&planEstudio=" . $planEstudio;
                                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                $this->cripto = new encriptar();
                                $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                                <tr align="center">
                                    <td class="centrar" colspan="4">
                                        <a href="<? echo $pagina . $variables ?>" class="centrar">
                                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                        </a>
                                    </td>
                                </tr>
                            </table>
                                            <?
                            }
                        } else {
                            //Si el espacio no se puede cargar en acpen se debe borrar de acasi y cambiar el estado sga_planEstudio_espacio
                            //Borrar datos en ACASI del espacio que se queria aprobar
                            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "borrarEspacioAcasi", $variableAcasi);
                            $borrarEspacioAcasi = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
                            if ($borrarEspacioAcasi == true) {
                                //Cambiar Estado de aprobacion sga_planEstudio_espacio
                                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variables);
                                $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                                if ($cambiarEstadoEspacio == true) { ?>
                                  <table class='contenidotabla centrar' border="0" width="100%">
                                      <tr align="center">
                                          <td class="centrar" colspan="4">
                                              <h6>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 101</h6>
                                              <hr noshade class="hr">

                                          </td>
                                      </tr><?
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variables = "pagina=adminAprobarEspacioPlan";
                                    $variables.= "&opcion=mostrar";
                                    $variables.= "&planEstudio=" . $planEstudio;
                                    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                    $this->cripto = new encriptar();
                                    $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                                    <tr align="center">
                                        <td class="centrar" colspan="4">
                                            <a href="<? echo $pagina . $variables ?>" class="centrar">
                                                <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                            <?
                                }
                            }
                        }
                    }
                }
            } else {
?>
                <table class='contenidotabla centrar' border="0" width="100%">
                    <tr align="center">
                        <td class="centrar" colspan="4">
                            <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 104</h4>
                            <hr noshade class="hr">

                        </td>
                    </tr>
                                        <?
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variables = "pagina=adminAprobarEspacioPlan";
                $variables.= "&opcion=mostrar";
                $variables.= "&planEstudio=" . $planEstudio;
                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variables = $this->cripto->codificar_url($variables, $configuracion);
?>
                <tr align="center">
                    <td class="centrar" colspan="4">
                        <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                        </a>
                    </td>
                </tr>
            </table><?
            }
        }
    }
    function formularioComentarioNoAprobar($configuracion)
    {
        $codEspacio = $_REQUEST['codEspacio'];
        $planEstudio = $_REQUEST['planEstudio'];
        $nivel = $_REQUEST['nivel'];
        $creditos = $_REQUEST['creditos'];
        $htd = $_REQUEST['htd'];
        $htc = $_REQUEST['htc'];
        $hta = $_REQUEST['hta'];
        $clasificacion = $_REQUEST['clasificacion'];
        $nombreEspacio = $_REQUEST['nombreEspacio'];
        $nombreGeneral = $_REQUEST['nombreGeneral'];
        $idEncabezado = $_REQUEST['idEncabezado'];
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo");
        $periodoActivo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscar_id", $planEstudio);
        $resultado_proyecto = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
        $codProyecto = $resultado_proyecto[0][10];
        $nombreProyecto = $resultado_proyecto[0][7];
        $variables = array($codEspacio, $planEstudio, $codProyecto, $this->usuario, date('YmdHis'), $nombreGeneral, $nivel, $creditos, $htd, $htc, $hta, $clasificacion, $nombreEspacio, $idEncabezado);
        $cadena_sql = $this->sql->cadena_sql($configuracion, "cambiarEstadoAsociacionNoAprobado", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");
        $variablesRegistro = array($this->usuario, date('YmdHis'), '24', 'No Aprobo Asociación de Espacio Academico', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ". $codEspacio . ", 0, 0, " . $planEstudio . ", ", $planEstudio);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
        $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
        $cadena_sql = $this->sql->cadena_sql($configuracion, "comentarioAutomaticoNoAprobo", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");
        $this->desaprobarEspacioAcademico($configuracion, $variables);
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
?>
<table class='contenidotabla centrar' border="0" background="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

            <tr align="center">
                <td class="centrar" colspan="2">
                    <h4>LA ASOCIACI&Oacute;N DEL ESPACIO ACAD&Eacute;MICO HA SIDO DESAPROBADA</h4>
                    <h4>Este Espacio Acad&eacute;mico es opci&oacute;n de <? echo $nombreGeneral ?></h4>
                    <hr noshade class="hr">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="40%">Fecha:</td><td class="cuadro_plano"><? echo date('d/m/Y') ?></td>
            </tr>
             <tr>
                <td class="cuadro_plano">Plan de Estudios:</td><td class="cuadro_plano"><? echo $planEstudio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">C&oacute;digo del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><? echo $codEspacio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nombre del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><? echo $nombreEspacio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Cr&eacute;ditos:</td><td class="cuadro_plano"><? echo $creditos ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Directo:</td><td class="cuadro_plano"><? echo $htd ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Cooperativo:</td><td class="cuadro_plano"><? echo $htc ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Autonomo:</td><td class="cuadro_plano"><? echo $hta ?></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar">
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
                    <font size="2">Comentario de no aprobaci&oacute;n del espacio acad&eacute;mico <? echo $nombreEspacio ?></font><br>
                <textarea cols="70" rows="7" name="comentario"></textarea>
            </td>
            </tr>
</table>
<table class='contenidotabla centrar' border="0">
            <tr>

                <td class="centrar" width="50%">
                       <input type="hidden" name="codEspacio" value="<? echo $codEspacio ?>">
                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">
                        <input type="hidden" name="codProyecto" value="<? echo $codProyecto ?>">
                        <input type="hidden" name="clasificacion" value="<? echo $clasificacion ?>">
                        <input type="hidden" name="nombreEspacio" value="<? echo $nombreEspacio ?>">
                        <input type="hidden" name="creditos" value="<? echo $creditos ?>">
                        <input type="hidden" name="nivel" value="<? echo $nivel ?>">
                        <input type="hidden" name="htd" value="<? echo $htd ?>">
                        <input type="hidden" name="htc" value="<? echo $htc ?>">
                        <input type="hidden" name="hta" value="<? echo $hta ?>">
                        <input type="hidden" name="nombreGeneral" value="<? echo $nombreGeneral ?>">
                        <input type="hidden" name="idEncabezado" value="<? echo $idEncabezado ?>">
                        <input type="hidden" name="opcion" value="enviarformulario">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="Confirmado" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="35" height="35"><br>Enviar
                </td>
                </form>
                <td class="centrar" width="50%">
                 <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>

                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">

                        <input type="hidden" name="opcion" value="mostrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="cancelar" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="35" height="35"><br>Cancelar
                 </form>
                </td>
            </tr>
        </table>

        <?
    }
    function desaprobarEspacioAcademico($configuracion, $variables)
    {
        $codEspacio = $variables[0];
        $planEstudio = $variables[1];
        $nivel = $variables[6];
        $creditos = $variables[7];
        $htd = $variables[8];
        $htc = $variables[9];
        $hta = $variables[10];
        $clasificacion = $variables[11];
        $nombreEspacio = $variables[12];
        $nombreGeneral = $variables[5];
        $idEncabezado = $variables[13];
        $cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacio", $variables); //echo $cadena_sql;exit;
        $resultadoEspacioAprobado = $this->accesoGestion->ejecutarAcceso($cadena_sql, "busqueda");
        //var_dump($resultadoEspacioAprobado[0][0]);exit;
        if ($resultadoEspacioAprobado[0][0] == 0) {
            #Actualiza la aprobacion de los espacios academicos
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "desaprobarEspacio2", $variables); //echo $this->cadena_sql;exit;
            $registroEspaciosPlan = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
            $totalEspacios = $this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
            //Vericar que se ejecuto la aprobacion de cada uno de los espacios academicos

        }
    }
    function formularioComentarioAprobarEncabezado($configuracion)
    {
        
        $id_encabezado = $_REQUEST['id_encabezado'];
        $encabezado_nombre = $_REQUEST['encabezado_nombre'];
        $planEstudio = $_REQUEST['planEstudio'];
        $codProyecto = $_REQUEST['codProyecto'];
        $clasificacion = $_REQUEST['clasificacion'];
        $nroCreditos = $_REQUEST['nroCreditos'];
        $nivel = $_REQUEST['nivel'];
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo");
        $periodoActivo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscar_id", $planEstudio);//echo $this->cadena_sql;exit;
        $resultado_proyecto = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

        $codProyecto = $resultado_proyecto[0][10];
        $nombreProyecto = $resultado_proyecto[0][7];

        $variables = array($id_encabezado, $planEstudio, $codProyecto, $this->usuario, date('YmdHis'), $encabezado_nombre, $nivel, $nroCreditos, $clasificacion);
        $cadena_sql = $this->sql->cadena_sql($configuracion, "cambiarEstadoAsociacionEncabezado", $variables);//echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");

        $variablesRegistro = array($this->usuario, date('YmdHis'), '23', 'Aprobo Encabezado', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ". $id_encabezado . ", 0, 0, " . $planEstudio . ", ".$codProyecto."", $planEstudio);

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
        $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");

        $cadena_sql = $this->sql->cadena_sql($configuracion, "comentarioAutomatico", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");
        
        //$this->aprobarEspacioAcademico($configuracion, $variables);
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
?>
<table class='contenidotabla centrar' border="0" background="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

            <tr align="center">
                <td class="centrar" colspan="2">
                    <h4>LA ASOCIACI&Oacute;N DEL ESPACIO ACAD&Eacute;MICO HA SIDO APROBADO</h4>
                    <h4><? echo strtoupper($encabezado_nombre)?></h4>
                    <hr noshade class="hr">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="40%">Fecha:</td><td class="cuadro_plano"><? echo date('d/m/Y') ?></td>
            </tr>
             <tr>
                <td class="cuadro_plano">Plan de Estudios:</td><td class="cuadro_plano"><? echo $planEstudio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nombre del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><? echo $encabezado_nombre ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Cr&eacute;ditos:</td><td class="cuadro_plano"><? echo $nroCreditos?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nivel:</td><td class="cuadro_plano"><? echo $nivel?></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar">
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
                    <font size="2">Comentario de aprobaci&oacute;n del espacio acad&eacute;mico <? echo $encabezado_nombre?></font><br>
                <textarea cols="70" rows="7" name="comentario"></textarea>
            </td>
            </tr>
</table>
<table class='contenidotabla centrar' border="0">
            <tr>

                <td class="centrar" width="50%">
                       <input type="hidden" name="codEspacio" value="<? echo $codEspacio ?>">
                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">
                        <input type="hidden" name="codProyecto" value="<? echo $codProyecto ?>">
                        <input type="hidden" name="clasificacion" value="<? echo $clasificacion ?>">
                        <input type="hidden" name="nombreEspacio" value="<? echo $nombreEspacio ?>">
                        <input type="hidden" name="creditos" value="<? echo $creditos ?>">
                        <input type="hidden" name="nivel" value="<? echo $nivel ?>">
                        <input type="hidden" name="htd" value="<? echo $htd ?>">
                        <input type="hidden" name="htc" value="<? echo $htc ?>">
                        <input type="hidden" name="hta" value="<? echo $hta ?>">
                        <input type="hidden" name="nombreGeneral" value="<? echo $nombreGeneral ?>">
                        <input type="hidden" name="idEncabezado" value="<? echo $idEncabezado ?>">
                        <input type="hidden" name="opcion" value="enviarformulario">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="Confirmado" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="35" height="35"><br>Enviar
                </td>
                </form>
                <td class="centrar" width="50%">
                 <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>

                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">

                        <input type="hidden" name="opcion" value="mostrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="cancelar" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="35" height="35"><br>Cancelar
                 </form>
                </td>
            </tr>
        </table>

        <?
    }
    function formularioComentarioNoAprobarEncabezado($configuracion)
    {
        $id_encabezado = $_REQUEST['id_encabezado'];
        $encabezado_nombre = $_REQUEST['encabezado_nombre'];
        $planEstudio = $_REQUEST['planEstudio'];
        $codProyecto = $_REQUEST['codProyecto'];
        $clasificacion = $_REQUEST['clasificacion'];
        $nroCreditos = $_REQUEST['nroCreditos'];
        $nivel = $_REQUEST['nivel'];
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo");
        $periodoActivo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscar_id", $planEstudio);
        $resultado_proyecto = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

        $codProyecto = $resultado_proyecto[0][10];
        $nombreProyecto = $resultado_proyecto[0][7];

        $variables = array($id_encabezado, $planEstudio, $codProyecto, $this->usuario, date('YmdHis'), $encabezado_nombre, $nivel, $nroCreditos, $clasificacion);

        $cadena_sql = $this->sql->cadena_sql($configuracion, "cambiarEstadoAsociacionNoAprobadoEncabezado", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");

        $variablesRegistro = array($this->usuario, date('YmdHis'), '24', 'No Aprobo Encabezado', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ". $id_encabezado . ", 0, 0, " . $planEstudio . ", ", $planEstudio);

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
        $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");

        $cadena_sql = $this->sql->cadena_sql($configuracion, "comentarioAutomaticoNoAprobo", $variables); //echo $cadena_sql;exit;
        $resultadoComentarioNoAprobar = $this->accesoGestion->ejecutarAcceso($cadena_sql, "");

        //$this->desaprobarEspacioAcademico($configuracion, $variables);
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
?>
<table class='contenidotabla centrar' border="0" background="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

            <tr align="center">
                <td class="centrar" colspan="2">
                    <h4>LA ASOCIACI&Oacute;N DEL ESPACIO ACAD&Eacute;MICO HA SIDO DESAPROBADA</h4>
                    <h4>Este Espacio Acad&eacute;mico es opci&oacute;n de <? echo $nombreGeneral ?></h4>
                    <hr noshade class="hr">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="40%">Fecha:</td><td class="cuadro_plano"><? echo date('d/m/Y') ?></td>
            </tr>
             <tr>
                <td class="cuadro_plano">Plan de Estudios:</td><td class="cuadro_plano"><? echo $planEstudio ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nombre del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><? echo $encabezado_nombre ?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Cr&eacute;ditos:</td><td class="cuadro_plano"><? echo $nroCreditos?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nivel:</td><td class="cuadro_plano"><? echo $nivel?></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar">
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
                    <font size="2">Comentario de no aprobaci&oacute;n del espacio acad&eacute;mico <? echo $nombreEspacio ?></font><br>
                <textarea cols="70" rows="7" name="comentario"></textarea>
            </td>
            </tr>
</table>
<table class='contenidotabla centrar' border="0">
            <tr>

                <td class="centrar" width="50%">
                       <input type="hidden" name="codEspacio" value="<? echo $codEspacio ?>">
                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">
                        <input type="hidden" name="codProyecto" value="<? echo $codProyecto ?>">
                        <input type="hidden" name="clasificacion" value="<? echo $clasificacion ?>">
                        <input type="hidden" name="nombreEspacio" value="<? echo $nombreEspacio ?>">
                        <input type="hidden" name="creditos" value="<? echo $creditos ?>">
                        <input type="hidden" name="nivel" value="<? echo $nivel ?>">
                        <input type="hidden" name="htd" value="<? echo $htd ?>">
                        <input type="hidden" name="htc" value="<? echo $htc ?>">
                        <input type="hidden" name="hta" value="<? echo $hta ?>">
                        <input type="hidden" name="nombreGeneral" value="<? echo $nombreGeneral ?>">
                        <input type="hidden" name="idEncabezado" value="<? echo $idEncabezado ?>">
                        <input type="hidden" name="opcion" value="enviarformulario">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="Confirmado" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="35" height="35"><br>Enviar
                </td>
                </form>
                <td class="centrar" width="50%">
                 <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>

                        <input type="hidden" name="planEstudio" value="<? echo $planEstudio ?>">

                        <input type="hidden" name="opcion" value="mostrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="image" value="cancelar" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="35" height="35"><br>Cancelar
                 </form>
                </td>
            </tr>
        </table>

        <?
    }
}
?> 