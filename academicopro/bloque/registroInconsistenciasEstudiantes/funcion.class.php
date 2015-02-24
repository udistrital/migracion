
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");


#Realiza la preparacion del formulario para la validacion de javascript 

?>

<?
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_registroInconsistenciasEstudiantes extends funcionGeneral
{
    
 	//@ Método costructor que crea el objeto sql de la clase sql_noticia
	function __construct($configuracion)
            {
	    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
	    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
	    include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	    $this->cripto=new encriptar();
	    $this->tema=$tema;
	    $this->sql=new sql_registroInconsistenciasEstudiantes();
	    $this->log_us= new log();
            $this->formulario="registroInconsistenciasEstudiantes";


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

	
	    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links  
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

            //echo $this->usuarioSesion[0][0];

	}

        #Entrada al bloque para seleccionar el plan de estudios
	function seleccionPlan($configuracion)
        {
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
            $html=new html();
            $cadenas=new cadenas();

            $cadena_sql=$this->sql->cadena_inconsistencias_sql($configuracion,"planEstudio",$this->usuario);
            $planes_1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            if($planes_1)
            {
                //$planes=$planes_1;
                $i=0;
                    while(isset($planes_1[$i][0]))
                    {
                            $planes[$i][0]=$planes_1[$i][0].'-'.$planes_1[$i][1];
                            $planes[$i][1]=$planes_1[$i][1].' - '.$planes_1[$i][2];
                    $i++;
                    }
            }
            elseif($this->usuario != null)
            {

                $cadena_sql=$this->sql->cadena_inconsistencias_sql($configuracion,"planEstudioAdmin","");
                $planes_1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
                $i=0;
                    while(isset($planes_1[$i][0]))
                    {
                            $planes[$i][0]=$planes_1[$i][0].'-'.$planes_1[$i][1];
                            $planes[$i][1]=$planes_1[$i][1].' - '.$planes_1[$i][2];
                    $i++;
                    }
            }
            else
            {
                echo "No se puede hacer nada";
            }
//                //Genera un formulario para seleccionar el plan de estudios

                ?>
                    <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td colspan="2">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>

                           </td>
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                <h4>Reporte de inconsistencias de Estudiantes en Cr&eacute;ditos</h4>
                                          <hr noshade class="hr">
                              <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                          </td>
                        </tr>
                    </table>

                    <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                        <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
                            <tr>
                                <td>
                                    <table align='center'>
                                        <tr  class='bloquecentralencabezado'>
                                            <td align='center'>
                                                <p>Seleccione el plan de estudios</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <table align='center'>
                                        <tr class='cuadro_color'>
                                            <td align='center'>
                                            <?
                                                //arma un cuadro de lista para mostrar los planes de estudio asociados al coordinador
                                                $tab=1;
                                                $mi_cuadro=$html->cuadro_lista($planes,'planEstudio',$configuracion,0,0,FALSE,$tab++,"id_plan");
                                                echo $mi_cuadro;
                                            ?>
                                            </td>
                                        </tr>
                                   </table>
                                    <table align='center'>
                                        <tr>
                                            <td>
                                                <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                                <input type='hidden' name='opcion' value='seleccionar'>
                                                <input value="Continuar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>


               
            <?
            
        }


	function seleccion($configuracion)
        {
                //selecciona la opcion de busqueda: estudiantes con planes no creditos y estudiantes con asignaturas
                ?>
                  <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td colspan="2">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>

                           </td>
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                <h4>Reporte de inconsistencias de Estudiantes en Cr&eacute;ditos</h4>
                                          <hr noshade class="hr">
                              <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                          </td>
                        </tr>
                       <tr align="center">
                            <td align='left'class="bloquecentralencabezado" width="80%">
                                Estudiantes con Planes de Estudios que no corresponden a Cr&eacute;ditos Acad&eacute;micos
                            </td>
                           <td class="bloquecentralencabezado">
                            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                                <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                <input type='hidden' name='opcion' value='planEstudios'>
                                <input type='hidden' name='planEstudio' value='<?echo $_REQUEST['planEstudio']?>'>
                                <input type='hidden' name='id_solicitud' value='<?echo $id_solicitud?>'>
<!--                                <input value="Ver Reporte" name="aceptar" tabindex='<?// echo $tab++ ?>' type="submit"/>-->
                                <input type="image" name="Ver Reporte" value="Ver Reporte" width="48" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/kchart_chrt.png" >
                              </form>
                           </td>
                        </tr>
                            

                        <tr align="center">
                            <td align='left'class="bloquecentralencabezado" width="80%">
                                Ver Reporte de Estudiantes que no se encuentran registrados como Cr&eacute;ditos Acad&eacute;micos
                            </td>
                           <td class="bloquecentralencabezado">
                            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                                <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                <input type='hidden' name='opcion' value='registrados'>
                                <input type='hidden' name='planEstudio' value='<?echo $_REQUEST['planEstudio']?>'>
                                <input type='hidden' name='id_solicitud' value='<?echo $id_solicitud?>'>
<!--                                <input value="Ver Reporte" name="aceptar" tabindex='<? //echo $tab++ ?>' type="submit"/>-->
                                <input type="image" name="Ver Reporte" value="Ver Reporte" width="48" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/kchart_chrt.png" >
                              </form>
                           </td>
                        </tr>

                        <tr align="center">
                            <td align='left'class="bloquecentralencabezado" width="80%">
                                Estudiantes con registros de asignaturas que no corresponden a Cr&eacute;ditos Acad&eacute;micos
                            </td>
                           <td class="bloquecentralencabezado">
                            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                                <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                <input type='hidden' name='opcion' value='asignaturas'>
                                <input type='hidden' name='planEstudio' value='<?echo $_REQUEST['planEstudio']?>'>
                                <input type='hidden' name='id_solicitud' value='<?echo $id_solicitud?>'>
                                <input type="image" name="Ver Reporte" value="Ver Reporte" width="48" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/kchart_chrt.png" >
                              </form>
                           </td>
                        </tr>
            <?

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variablesPag="pagina=registroInconsistenciasEstudiantes";
                            $variablesPag.="&opcion=seleccionarPlan";
                            $variablesPag.="&planEstudio=".$_REQUEST["planEstudio"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variablesPag=$this->cripto->codificar_url($variablesPag,$configuracion);


             ?>                   <br><br><tr class="centrar">
                                    <td colspan="3">
                                        <a href="<?= $pagina.$variablesPag ?>" >
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
                                        <font size="2"><b>Seleccionar otro Plan de Estudios</b></font>
                                        </a>
                                    </td>
                                </tr>
                                </table>








            <?

        }

        function planEstudios($configuracion)
        {
            //Muestra los estudiantes que no estan registrados en un plan de estudios de creditos
            $cra=explode("-", $_REQUEST['planEstudio']);
            $this->cadena_sql=$this->sql->cadena_inconsistencias_sql($configuracion,"consultarEstudiantes",$cra[0]);
            $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
            if ($resultado_estudiantes)
            {
                //muestra el encabezado
                ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                        <thead class='texto_subtitulo centrar'>
                        <td><center>Reporte de Estudiantes de Cr&eacute;ditos con Planes de Estudios que no corresponden a Cr&eacute;ditos Acad&eacute;micos</center></td>
                        </thead>
                     <table class='contenidotabla'>
                     <thead class='cuadro_color centrar'>
                                    <td class='cuadro_plano centrar'>Nº</td>
                                    <td class='cuadro_plano centrar'>C&oacute;digo Estudiante</td>
                                    <td class='cuadro_plano centrar'>Nombre Estudiante</td>
                                    <td class='cuadro_plano centrar'>Proyecto curricular</td>
                                    <td class='cuadro_plano centrar'>Plan de Estudios</td>
                     </thead>

                <?

                for($i=0; $i<count($resultado_estudiantes); $i++)
                {
                    //muestra el listado de los estudiantes
                    ?>
                        <tr>
                            <tr>
                                <td class='cuadro_plano centrar'><?echo $i+1;?></td>
                                <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td>
                                <td class='cuadro_plano'><?echo $resultado_estudiantes[$i][1]?></td>
                                <td class='cuadro_plano centrar' ><?echo $resultado_estudiantes[$i][3]?></td>
                               <td class='cuadro_plano centrar'><?
                                if(is_null($resultado_estudiantes[$i][4]) or $resultado_estudiantes[$i][4]='%-%')
                                {
                                    echo "No registra";?></td>
                                <?
                                }
                                else
                                {
                                    echo $resultado_estudiantes[$i][4]?></td>
                                <?
                                }
                                ?>
                                
                            </tr>
                     </tr>
<?
                }
                ?></table>
                
<?
            }
            else
            {
                $mensaje="Todos los estudiantes de Cr&eacute;ditos se encuentran inscritos en el plan de estudios correcto.";
                $this->sinProblema($configuracion, $mensaje);

            }
            $this->pie($configuracion);
        }

        function registrados($configuracion)
        {
            $cra=explode("-", $_REQUEST['planEstudio']);
            $this->cadena_sql=$this->sql->cadena_inconsistencias_sql($configuracion,"consultarNoCreditos",$cra[0]);
            $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
            if ($resultado_estudiantes)
            {
                ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                        <thead class='texto_subtitulo centrar'>
                        <td><center>Reporte de Estudiantes que no se encuentran registrados como Cr&eacute;ditos Acad&eacute;micos</center></td>
                        </thead>
                     <table class='contenidotabla'>
                     <thead class='cuadro_color centrar'>
                                    <td class='cuadro_plano centrar'>Nº</td>
                                    <td class='cuadro_plano centrar'>C&oacute;digo Estudiante</td>
                                    <td class='cuadro_plano centrar'>Nombre Estudiante</td>
                                    <td class='cuadro_plano centrar'>Proyecto curricular</td>
                                    <td class='cuadro_plano centrar'>Plan de Estudios</td>
                                    <td class='cuadro_plano centrar'>¿Es de cr&eacute;ditos?</td>
                     </thead>

                <?

                for($i=0; $i<count($resultado_estudiantes); $i++)
                {
                    ?>
                        <tr>
                            <tr>
                                <td class='cuadro_plano centrar'><?echo $i+1;?></td>
                                <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td>
                                <td class='cuadro_plano'><?echo $resultado_estudiantes[$i][1]?></td>
                                <td class='cuadro_plano centrar' ><?echo $resultado_estudiantes[$i][2]?></td>
                                <td class='cuadro_plano centrar'><?
                                //echo "****".trim($resultado_estudiantes[$i][4]);
                                //exit;
                                
                                if(is_null($resultado_estudiantes[$i][3]) || $resultado_estudiantes[$i][3]=='%-%')
                                {
                                    echo "No registra";?></td>
                                <?
                                }
                                else
                                {
                                    echo $resultado_estudiantes[$i][3];?></td>
                                <?
                                }
                                ?>
                                <td class='cuadro_plano centrar'><?
                                //$cadena=strstr($resultado_estudiantes[$i][4], 'N');
                                if(trim($resultado_estudiantes[$i][4])=='N')
                                {
                                    echo "No";?></td>
                                <?
                                }
                                elseif(trim($resultado_estudiantes[$i][4])=='%S%')
                                {
                                    echo "S&iacute;";?></td>
                                <?
                                }
                                else
                                {
                                    echo $resultado_estudiantes[$i][4];?></td>
                                <?
                                }

                                ?>
                            </tr>
                        </tr>
                <?
                }
                ?></table>
                </table>
            <?
            }
            else
            {
                $mensaje="Todos los estudiantes se encuentran registrados como Cr&eacute;ditos Acad&eacute;micos.";
                $this->sinProblema($configuracion, $mensaje);

            }
            $this->pie($configuracion);
        }

        function asignaturas($configuracion)
        {
            //muestra los estudiantes que tiene registradas asignaturas no creditos
            $cra=explode("-", $_REQUEST['planEstudio']);
            $this->cadena_sql=$this->sql->cadena_inconsistencias_sql($configuracion,"consultarAsignaturas",$cra[0]);
            $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
            if ($resultado_estudiantes)
            {
                ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                        <thead class='texto_subtitulo centrar'>
                        <td><center>Reporte de Estudiantes con registros de asignaturas que no corresponden a Cr&eacute;ditos Acad&eacute;micos</center></td>
                        </thead>
                     <table class='contenidotabla'>
                     <thead class='cuadro_color centrar'>
                                    <td class='cuadro_plano centrar'>Nº</td>
                                    <td class='cuadro_plano centrar'>C&oacute;digo Estudiante</td>
                                    <td class='cuadro_plano centrar'>Nombre Estudiante</td>
                                    <td class='cuadro_plano centrar'>Plan de Estudios del Estudiante</td>
                                    <td class='cuadro_plano centrar'>C&oacute;digo Asignatura</td>
                                    <td class='cuadro_plano centrar'>Nombre Asignatura</td>
                                    <td class='cuadro_plano centrar'>Nº de Cr&eacute;ditos</td>
                     </thead>

                <?

                for($i=0; $i<count($resultado_estudiantes); $i++)
                {
                    ?>
                        <tr>
                            <tr>
                                <td class='cuadro_plano centrar'><?echo $i+1;?></td>
                                <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td>
                                <td class='cuadro_plano'><?echo $resultado_estudiantes[$i][1]?></td>
                                <td class='cuadro_plano centrar' ><?echo $resultado_estudiantes[$i][2]?></td>
                                <td class='cuadro_plano centrar' ><?echo $resultado_estudiantes[$i][3]?></td>
                                <td class='cuadro_plano centrar' ><?echo $resultado_estudiantes[$i][4]?></td>
                                <td class='cuadro_plano centrar'><?
                                if(is_null($resultado_estudiantes[$i][6]) || $resultado_estudiantes[$i][6]=='%-%')
                                {
                                    echo "No registra";?></td>
                                <?
                                }
                                else
                                {
                                    echo $resultado_estudiantes[$i][6];?></td>
                                <?
                                }
                                ?>
                            </tr>
                        </tr>
                <?
                }
                ?></table>
                
            <?
            }
            else
            {
                $mensaje="Todos los estudiantes de Cr&eacute;ditos tienen registrados Espacios Acad&eacute;micos del plan de estudios de Cr&eacute;ditos.";
                $this->sinProblema($configuracion, $mensaje);
            }
            $this->pie($configuracion);
        }


    function sinProblema($configuracion, $mensaje)
    {
    ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                    <thead class='texto_subtitulo centrar'>
                        <td><center><?echo $mensaje ?></center></td>
                    </thead>

    <?

    }

    function pie($configuracion)
    {
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variablesPag="pagina=registroInconsistenciasEstudiantes";
                            $variablesPag.="&opcion=seleccionar";
                            $variablesPag.="&planEstudio=".$_REQUEST["planEstudio"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variablesPag=$this->cripto->codificar_url($variablesPag,$configuracion);


             ?>                   <br><tr class="centrar">
                                    <td colspan="3">
                                        <a href="<?= $pagina.$variablesPag ?>" >
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
                                        <font size="2"><b>Regresar</b></font>
                                        </a>
                                    </td>
                                </tr>
                                </table>
<?
            exit;


    }

}
?>
