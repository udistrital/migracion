<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/ProgressBar.class.php");

class funciones_registroCancelarInscripcionEstudiantesInactivos extends funcionGeneral {

    function __construct($configuracion, $sql) {

        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");


        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;


        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroCancelarInscripcionEstudiantesInactivos";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    /**
     * Muestra el formulario para seleccionar los estudiantes o asignaturas que se desean
     * cancelar.
     * @param <array> $configuracion
     */
    function verReporte($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $id_estado=$_REQUEST['id_estado'];

        $this->encabezadoModulo($configuracion);

        $variablesConsulta=array($id_estado,$codProyecto);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantes_asignaturas", $variablesConsulta);
        $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        ?>
<table class="contenidotabla">
    <tr>
        <td class="centrar">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminConsultarInscripcionEstudiantesInactivos";
            $ruta.="&opcion=reporte";
            $ruta.="&codProyecto=".$codProyecto;
            $ruta.="&planEstudio=".$planEstudio;
            $ruta.="&nombreProyecto=".$nombreProyecto;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?echo $pagina.$ruta?>">
                <img src='<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png' width="30" height="30" border='0'><br>Inicio
            </a>
        </td>
    </tr>
</table>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class="contenidotabla cuadro_plano centrar">

        <?
        if(is_array($resultado_estudiantes)) {
        ?>
        <tr class="cuadro_plano">
            <td class="cuadro_brownOscuro centrar" colspan="9">
                ESTUDIANTES EN ESTADO <b><?echo $id_estado?></b> QUE TIENEN ASIGNATURAS INSCRITAS
            </td>
        </tr>
        <tr class="cuadro_brownOscuro centrar">
            <td class='cuadro_plano centrar' width='5%'>
                <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                TODOS
                <br>
                <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'registroCancelarInscripcionEstudiantesInactivos');">
            </td>
            <td class='cuadro_plano centrar' width='10%'>Cod Estudiante</td>
            <td class='cuadro_plano centrar' width='20%'>Nombre</td>
            <td class='cuadro_plano centrar' colspan='6' width='75%'>
                <table class='contenidotabla centrar' border='0'>
                    <td class='centrar' width='10%'>Cod E.A.</td>
                    <td class='centrar' width='45%'>Nombre E.A.</td>
                    <td class='centrar' width='10%'>Grupo</td>
                    <td class='centrar' width='43%'>Proyecto Curricular</td>
                    <td class='centrar' width='8%'>Borrar</td>
                </table>
            </td>

        </tr>
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="10" class="centrar">
                <input type="hidden" name="opcion" value="cancelarEstudiantes">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                <input type="hidden" name="id_estado" value="<?echo $id_estado?>">
                <input type="hidden" name="totalEstudiantes" value="<?echo $est?>">
                <input type="button" name="borrar" value="Borrar" onclick="submit()" onkeypress="javascript:if(event.keyCode==13){return false;}">
            </td>
        </tr>
            <?
            $est=1;
            
            for($i=0;$i<count($resultado_estudiantes);$i++) {
                if($resultado_estudiantes[$i-1][0]!=$resultado_estudiantes[$i][0])
                    {
                        $asig=0;
                        if($est%2==0) {$color="#FFFDD0";}else {$color="#FFFFFF";}
            ?>
        <tr bgcolor='<?echo $color?>'>
            <td class='centrar' width='5%'><input type='checkbox' name='codEstudiante<?echo $est?>' value='<?echo $resultado_estudiantes[$i][0]?>'></td>
            <td width='15%' class='centrar' rowspan="<?echo $asig?>"><?echo $resultado_estudiantes[$i][0]?></td>
            <td width='25%' class='izquierda' rowspan="<?echo $asig?>"><?echo $resultado_estudiantes[$i][1]?></td>
            <td class='centrar' colspan='6' width='75%'>
                <table class='contenidotabla' border='0'>
                    <td class='centrar' width='10%'><?echo $resultado_estudiantes[$i][2]?></td>
                    <td class='centrar' width='45%'><?echo $resultado_estudiantes[$i][3]?></td>
                    <td class='centrar' width='8%'><?echo $resultado_estudiantes[$i][4]?></td>
                    <td class='centrar' width='43%'><?echo $resultado_estudiantes[$i][5]." - ".$resultado_estudiantes[$i][6]?></td>
                    <td class='centrar' width='8%'>
                                            <?
                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
                                            $ruta.="&opcion=cancelarAsignatura";
                                            $ruta.="&codProyecto=".$codProyecto;
                                            $ruta.="&planEstudio=".$planEstudio;
                                            $ruta.="&nombreProyecto=".$nombreProyecto;
                                            $ruta.="&id_estado=".$id_estado;
                                            $ruta.="&codEstudiante=".$resultado_estudiantes[$i][0];
                                            $ruta.="&codEspacio=".$resultado_estudiantes[$i][2];
                                            $ruta.="&grupo=".$resultado_estudiantes[$i][4];
                                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                            $asig++;
                                            ?>
                        <a href='<?echo $pagina.$ruta?>'>
                            <img src='<?echo $configuracion['site'].$configuracion['grafico']?>/cancelar.gif' border='0'>
                        </a>
                    </td>
                </table>
            </td><?
                   $est++;
                      }else
                        {
                          ?>
                            <tr bgcolor='<?echo $color?>'>
                                <td width='5%'></td>
                                <td width='15%'></td>
                                <td width='25%'></td>
                                <td class='centrar' colspan='6' width='75%'>
                                    <table class='contenidotabla' border='0'>
                                        <td class='centrar' width='10%'><?echo $resultado_estudiantes[$i][2]?></td>
                                        <td class='centrar' width='45%'><?echo $resultado_estudiantes[$i][3]?></td>
                                        <td class='centrar' width='8%'><?echo $resultado_estudiantes[$i][4]?></td>
                                        <td class='centrar' width='43%'><?echo $resultado_estudiantes[$i][5]." - ".$resultado_estudiantes[$i][6]?></td>
                                        <td class='centrar' width='8%'>
                                        <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
                                                                $ruta.="&opcion=cancelarAsignatura";
                                                                $ruta.="&codProyecto=".$codProyecto;
                                                                $ruta.="&planEstudio=".$planEstudio;
                                                                $ruta.="&nombreProyecto=".$nombreProyecto;
                                                                $ruta.="&id_estado=".$id_estado;
                                                                $ruta.="&codEstudiante=".$resultado_estudiantes[$i][0];
                                                                $ruta.="&codEspacio=".$resultado_estudiantes[$i][2];
                                                                $ruta.="&grupo=".$resultado_estudiantes[$i][4];
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                $asig++;
                                                                ?>
                                            <a href='<?echo $pagina.$ruta?>'>
                                                <img src='<?echo $configuracion['site'].$configuracion['grafico']?>/cancelar.gif' border='0'>
                                            </a>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                            <?
                        }
                }
                    ?>
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="10" class="centrar">
                <input type="hidden" name="opcion" value="cancelarEstudiantes">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                <input type="hidden" name="id_estado" value="<?echo $id_estado?>">
                <input type="hidden" name="totalEstudiantes" value="<?echo $est?>">
                <input type="button" name="borrar" value="Borrar" onclick="submit()" onkeypress="javascript:if(event.keyCode==13){return false;}">
            </td>
        </tr>
            <?
                }else {
                    ?>
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="5">No existen registros de estudiantes con estado <b><?echo $id_estado?></b> que tengan registros de espacios acad&eacute;micos</td>
        </tr>
            <?
        }

                ?>

    </table>
</form>
        <?

    }

    /**
     * Muestra el encabezado del modulo
     * @param <array> $configuracion
     */
    function encabezadoModulo($configuracion) {
        ?>
<table class="contenidotabla centrar">
    <tr>
        <td class="centrar" colspan="3">
            <B>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<BR>ADMINISTRACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS</B><br>
            <hr>
        </td>
    </tr>
</table>

        <?
    }

    /**
     * Cancela los espacios académicos a los estudiantes seleccionados
     * Elimina los registros de la base de datos de oracle, y cambia el estado
     * en la base de datos de MySQL.
     * Actualiza los cupos una vez elimine al estudiante
     * Llama a la funcion para generar el reporte de las cancelaciones realizadas
     * 
     * @param <array> $configuracion
     */
    function cancelarEstudiantes($configuracion) 
    {
        

        $codProyecto=$_REQUEST['codProyecto'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $id_estado=$_REQUEST['id_estado'];
        $totalEstudiantes=$_REQUEST['totalEstudiantes'];
        $j=1;

        if($_REQUEST['seleccionado']==NULL)
        {
            echo "<script>alert('Seleccione los estudiantes que desea cancelar las inscripciones')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
            $ruta.="&opcion=cancelar";
            $ruta.="&codProyecto=".$codProyecto;
            $ruta.="&nombreProyecto=".$nombreProyecto;
            $ruta.="&planEstudio=".$planEstudio;
            $ruta.="&id_estado=".$id_estado;
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            echo "<script>location.replace('".$pagina.$ruta."')</script>";
            exit;
        }

        for($i=1;$i<$totalEstudiantes;$i++) {
            if(isset($_REQUEST['codEstudiante'.$i])&&$_REQUEST['codEstudiante'.$i]!=NULL) {
                $codEstudiante[$j]=$_REQUEST['codEstudiante'.$i];
                $j++;
            }
        }
        

        $bar=new ProgressBar("Consultando inscripciones...");
        $bar->setAutohide(true);
    
        $bar->setForegroundColor('#F3E3A4');
        $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $annio=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        sleep(1);
        $bar->setBarLength(500);
        
        $bar->initialize($j+1);

        for($h=1;$h<$j;$h++) {
            $variablesConsulta=array($id_estado,$codProyecto,$codEstudiante[$h]);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiante_inscripciones", $variablesConsulta);
            $resultado_inscripciones=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $reporte[$h]['codigo']=$codEstudiante[$h];
           

            $cadena_sql=$this->sql->cadena_sql($configuracion,"cancelarEspaciosOracle", $variablesConsulta);
            $resultado_cancelacionOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

            $bar->setMessage("Cancelando inscripciones de $h estudiantes");
            $bar->increase();
            $variablesConsulta2=array($id_estado,$codProyecto,$codEstudiante[$h],$annio,$periodo);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"cancelarEspaciosMySQL", $variablesConsulta2);
            $resultado_cancelacionMySQL=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            if(is_array($resultado_inscripciones))
                {
                    for($p=0;$p<count($resultado_inscripciones);$p++)
                    {
                        $reporte[$h]['nombreEst']=$resultado_inscripciones[0][1];
			if(!isset($reporte[$h]['asignaturas']))
			{
				$reporte[$h]['asignaturas']=$resultado_inscripciones[$p][2].",".$resultado_inscripciones[$p][3].",".$resultado_inscripciones[$p][4].",";
			}else{
				$reporte[$h]['asignaturas'].=$resultado_inscripciones[$p][2].",".$resultado_inscripciones[$p][3].",".$resultado_inscripciones[$p][4].",";
				}
                        

                        $variablesConsultaIns=array($resultado_inscripciones[$p][2],$resultado_inscripciones[$p][4],$annio,$periodo);

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variablesConsultaIns);
                        $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                        $variablesRegistro=array($this->usuario,date('YmdGis'),'43','Cancelo espacio académico estudiante inactivo ',$annio."-".$periodo.", ".$resultado_inscripciones[$p][2].",".$resultado_inscripciones[$p][4],$codEstudiante[$h]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
                    }
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_estudiante", $codEstudiante[$h]);
                        $resultado_datosEst=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $reporte[$h]['nombreEst']=$resultado_datosEst[0][1];
                        $reporte[$h]['asignaturas'].=",NO EXISTEN REGISTROS DE ESPACIOS ACAD&Eacute;MICOS PARA EL PERIODO ACTUAL,,";
                    }

        }
        sleep(1);
        $bar->increase();
        $bar->setMessage("Generando Reporte...");
        $bar->setAutohide(true);
        sleep(1);
        $bar->stop();
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
        $ruta.="&opcion=cancelar";
        $ruta.="&codProyecto=".$codProyecto;
        $ruta.="&nombreProyecto=".$nombreProyecto;
        $ruta.="&planEstudio=".$planEstudio;
        $ruta.="&id_estado=".$id_estado;
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        $regresar=$pagina.$ruta;
        $this->reporteCancelacion($configuracion, $reporte,$j,$regresar);

    }

    /**
     * Genera el reporte de las cancelaciones realizadas por el coordinador
     * 
     * @param <array> $configuracion
     * @param <array> $reporte
     * @param <int> $totalEstudiantes
     * @param <url> $regresar
     */
    function reporteCancelacion($configuracion,$reporte,$totalEstudiantes,$regresar) {
        $this->encabezadoModulo($configuracion);
        ?>
<table class="contenidotabla centrar">
    <tr class="cuadro_brownOscuro centrar">
        <td class="centrar" colspan="5">
            Se han eliminado las siguientes inscripciones satisfactoriamente
        </td>
    </tr>

        <?
        for($i=1;$i<$totalEstudiantes;$i++) {
            ?>
    <tr class="cuadro_brownOscuro centrar">
        <td colspan="5">Estudiante: <?echo $reporte[$i]['codigo']." - ".$reporte[$i]['nombreEst']?></td>
    </tr>
    <tr class="cuadro_brown centrar">
        <td class="centrar" colspan="2">Espacio Acad&eacute;mico</td><td class="centrar">Grupo</td>
    </tr>
                <?
                $asig=explode(",", $reporte[$i]['asignaturas']);

                for($k=0;$k<count($asig);$k++) {
                if($k%3==0) {
                    ?>
    <tr>
        <td width="10%">
                    <?echo $asig[$k]?>
        </td>
        <td>
                        <?echo $asig[$k+1]?>
        </td>
        <td class="centrar">
                        <?echo $asig[$k+2]?>
        </td>
    </tr>
                        <?
                }

                        }
        }
        ?>
    <tr class="centrar">
        <td colspan="5">
            <a href="<?echo $regresar?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" alt="Continuar" border="0" width="30" height="30"><br>Continuar
            </a>
        </td>
    </tr>
</table>
            <?
        }

        /**
         * Funcion que permite cancelar la asignatura seleccionada por el coordinador
         * recibe como parametros el código del estudiante, código de espacio académico
         * 
         * @param <array> $configuracion
         */
        function cancelarAsignatura($configuracion) {
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $id_estado=$_REQUEST['id_estado'];
        $codEstudiante=$_REQUEST['codEstudiante'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $codEspacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['grupo'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $annio=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $variablesConsulta=array($id_estado,$codProyecto,$codEstudiante,$codEspacio);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiante_inscripcionesCancelar", $variablesConsulta);
        $resultado_inscripciones=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $reporte[1]['codigo']=$codEstudiante;
        $reporte[1]['nombreEst']=$resultado_inscripciones[0][1];
        $reporte[1]['asignaturas']=$resultado_inscripciones[0][2].",".$resultado_inscripciones[0][3].",".$resultado_inscripciones[0][4].",";

        $variablesConsulta=array($codEstudiante,$codEspacio,$grupo,$annio,$periodo);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"cancelarAsignaturaOracle", $variablesConsulta);
        $resultado_cancelaOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

        if($resultado_cancelaOracle==true) {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"cancelarAsignaturaMySQL", $variablesConsulta);
            $resultado_cancelaMySQL=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            $variablesConsultaIns=array($codEspacio,$grupo,$annio,$periodo);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variablesConsultaIns);
            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

            $variablesRegistro=array($this->usuario,date('YmdGis'),'43','Cancelo espacio académico estudiante inactivo ',$annio."-".$periodo.", ".$codEspacio.",".$grupo,$codEstudiante);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
            $ruta.="&opcion=cancelar";
            $ruta.="&codProyecto=".$codProyecto;
            $ruta.="&nombreProyecto=".$nombreProyecto;
            $ruta.="&planEstudio=".$planEstudio;
            $ruta.="&id_estado=".$id_estado;
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            $regresar=$pagina.$ruta;
            $this->reporteCancelacion($configuracion, $reporte,2,$regresar);
        }else {
            echo "<script>alert('La base de datos se encuentra ocupada, por favor intente mas tarde')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
            $ruta.="&opcion=cancelar";
            $ruta.="&codProyecto=".$codProyecto;
            $ruta.="&nombreProyecto=".$nombreProyecto;
            $ruta.="&planEstudio=".$planEstudio;
            $ruta.="&id_estado=".$id_estado;
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            echo "<script>location.replace('".$pagina.$ruta."')</script>";
        }
    }
}

?>
