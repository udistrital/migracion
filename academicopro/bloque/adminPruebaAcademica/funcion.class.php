
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

class funcion_adminPruebaAcademica extends funcionGeneral
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
	    $this->sql=new sql_adminPruebaAcademica();
	    $this->log_us= new log();
            $this->formulario="adminPruebaAcademica";


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

        #Entrada al bloque para ver estudiantes con promedio menor a 3
	function seleccion($configuracion)
        {
                ?>
                  <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td colspan="2">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>

                           </td>
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                <h4>Reporte de Situaci&oacute;n Acad&eacute;mica de Estudiantes</h4>
                                          <hr noshade class="hr">
                              <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                          </td>
                        </tr>
                       <tr>
                           <td align='center'class="bloquelateralcuerpo" width="50%">
                                Ver reporte de Promedio Acumulado de Estudiantes
                                                </td>
                            <td align='center'class="bloquelateralcuerpo" width="50%">
                                Ver Reporte de Estudiantes con Espacios Acad&eacute;micos reprobados
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <table>
                                    <tr>
                                        <td>
                                            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo  $this->formulario?>'>
                                                <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                                                <input type='hidden' name='opcion' value="reprobados">
                                                <input type='hidden' name='action' value='<? echo  $this->formulario ?>'>
                                                <input value="Reprobados" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td align='center'>
                                <table>
                                    <tr>
                                        <td>
                                            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo  $this->formulario?>'>
                                                <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                                                <input type='hidden' name='opcion' value="mostrar">
                                                <input type='hidden' name='action' value='<? echo  $this->formulario ?>'>
                                                <input value="Promedio" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

            <?

        }


        function mostrarEstudiantes($configuracion)
            {

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarEstudiantes",'');
                //$this->cadena_sql="select count(*) from mntac.acnot where not_est_cod >20092000000

                $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
                //var_dump($this->accesoOracle);
                //echo $this->cadena_sql."<br>";
                //var_dump($resultado_estudiantes);
                //exit;

                ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                        <thead class='texto_subtitulo centrar'>
                        <td><center>Estudiantes en prueba por promedio inferior a 3.0</center></td>
                        </thead>
                     <table class='contenidotabla'>
                     <thead class='cuadro_color centrar'>
                                    <td class='cuadro_plano centrar'>Nº</td>
                                    <td class='cuadro_plano centrar'>Carrera</td>
                                    <td class='cuadro_plano centrar'>Carrera Nombre</td>
                                    <td class='cuadro_plano centrar'>Codigo Estudiante</td>
                                    <td class='cuadro_plano centrar'>Nombre Estudiante</td>
                                    <td class='cuadro_plano centrar'>Plan Estudio</td>                                    
                                    <td class='cuadro_plano centrar'>Direccion</td>
                                    <td class='cuadro_plano centrar'>Telefono</td>
                                    <td class='cuadro_plano centrar'>E-mail</td>
                                    <td class='cuadro_plano centrar'>E-mail Ins</td>
                                    <td class='cuadro_plano centrar'>No Icfes</td>
                                    <td class='cuadro_plano centrar'>Puntaje</td>
                                    <td class='cuadro_plano centrar'>Colegio</td>
                                    <td class='cuadro_plano centrar'>Lugar de Nacimiento</td>
                                    <td class='cuadro_plano centrar'>Promedio Ponderado</td>
                                    <td class='cuadro_plano centrar'>Prueba Académica</td>
                                    <td class='cuadro_plano centrar'>Motivo</td>
                     </thead>

                <?
                $nota=0;
                $totalEst=0;
                $promedioBajo=0;
                for($i=0;$i<count($resultado_estudiantes);$i++)
                    {
                           if($resultado_estudiantes[$i][0]==$resultado_estudiantes[$i+1][0])
                               {
                                     $suma+=$resultado_estudiantes[$i][4];
                                     $sumaCreditos+=$resultado_estudiantes[$i][3];

                                     if((number_format($resultado_estudiantes[$i][1],1)/10) < 3)
                                        {
                                            $nota++;
                                        }

                               }else
                                   {
                                       $totalEst++;
                                       $suma+=$resultado_estudiantes[$i][4];
                                       $sumaCreditos+=$resultado_estudiantes[$i][3];

                                       if((number_format($resultado_estudiantes[$i][1],1)/10) < 3)
                                        {
                                            $nota++;
                                        }
//                                       echo $suma."<br>".$sumaCreditos;
                                       if($sumaCreditos==0)
                                           {
                                            ?>
                                            <?
                                           }else
                                               {
                                                    $resultado=$suma/$sumaCreditos;

//                                       echo number_format($resultado,2)/10;exit;
                                                   ?>
                                                        <? 
                                                                $promedioBajo++;

                                                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarEstudiantePrueba",$resultado_estudiantes[$i][0]);//echo $this->cadena_sql;exit;
                                                                $resultado_estudiantePrueba=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                                                                ?>
                                                    <tr>
                                                        <tr>
                                                            <td class='cuadro_plano centrar'><?echo $promedioBajo?></td><!--Numero estudiante-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][7]?></td><!--Carrera-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][0]?></td><!--Nombre Carrera-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td><!--Código estudiante-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][5]?></td><!--Nombre estudiante-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][6]?></td><!--Plan Estudio-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][1]?></td><!--Direccion-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][2]?></td><!--Telefono-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][3]?></td><!--Email-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][4]?></td><!--Email ins-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][5]?></td><!--Numero Icfes-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][6]?></td><!--Puntaje-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][7]?></td><!--Colegio-->
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantePrueba[0][8]?></td><!--Lugar de nacimiento-->
                                                            <?
                                                            if((number_format($resultado,1)/10)<3 && $nota>3)
                                                            {
                                                                ?>

                                                        <td class='cuadro_plano centrar'>
                                                                    <font color="#FF0000"><?echo number_format($resultado,1)/10?></font></td><!--Promedio-->
                                                        <td class='cuadro_plano centrar'>
                                                                    <font color="#FF0000">S</font></td><!--En prueba académica-->
                                                        <td class='cuadro_plano centrar'>
                                                                    <font color="#FF0000">1 | 3 </font></td><!--Motivo-->
                                                        </tr>
                                                                <?
                                                            }else if((number_format($resultado,1)/10)<3)
                                                                {
                                                                ?>

                                                                    <td class='cuadro_plano centrar'>
                                                                                <font><?echo number_format($resultado,1)/10?></font></td><!--Promedio-->
                                                                    <td class='cuadro_plano centrar'>
                                                                              <font color="#FF0000">S</font></td>
                                                                    <td class='cuadro_plano centrar'>
                                                                    <font color="#FF0000">1</font></td><!--Motivo-->
                                                                    </tr>
                                                                <?
                                                                }else if($nota>3)
                                                                {
                                                                ?>

                                                                    <td class='cuadro_plano centrar'>
                                                                                <font><?echo number_format($resultado,1)/10?></font></td><!--Promedio-->
                                                                    <td class='cuadro_plano centrar'>
                                                                             <font color="#FF0000">S</font></td>
                                                                    <td class='cuadro_plano centrar'>
                                                                    <font color="#FF0000">3</font></td><!--Motivo-->
                                                                    </tr>
                                                                <?
                                                                }else
                                                                {
                                                                ?>

                                                                    <td class='cuadro_plano centrar'>
                                                                                <font><?echo number_format($resultado,1)/10?></font></td><!--Promedio-->
                                                                    <td class='cuadro_plano centrar'>
                                                                             </td>
                                                                    <td class='cuadro_plano centrar'>
                                                                    </td><!--Motivo-->
                                                                    </tr>
                                                                <?
                                                                }
                                           }
                                       $suma=0;
                                       $sumaCreditos=0;
                                       $resultado=0;
                                       $nota=0;

                                   }
                    }
                    ?>
                 </table>
                        <tr>
                            <td class='centrar'>
                                Total de estudiantes:<b><?echo $totalEst?></b>
                            </td>
                        </tr>
                        <tr>
                            <td class='centrar'>
                                Estudiantes con promedio inferior a 3.0:<b> <?echo $promedioBajo?></b>
                            </td>
                        </tr>
</table>
<?
            }


        function estudiantesReprobados($configuracion)
            {

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesReprobados",'');
                $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                        <thead class='texto_subtitulo centrar'>
                        <td><center>ESTUDIANTES CON ESPACIOS ACAD&Eacute;MICOS REPROBADOS</center></td>
                        </thead>
                     <table class='contenidotabla'>
                     <thead class='cuadro_color centrar'>
                                    <td class='cuadro_plano centrar'>Nº</td>
                                    <td class='cuadro_plano centrar'>Codigo Estudiante</td>
                                    <td class='cuadro_plano centrar'>Nombre del estudiante</td>
                                    <td class='cuadro_plano centrar'>Plan Estudio</td>
                                    <td class='cuadro_plano centrar'>Carrera</td>
                                    <td class='cuadro_plano centrar'>Espacios Reprobados</td>
                     </thead>

                <?
                $totalEst=0;
                $prueba=0;
                $suma=1;
                for($i=0;$i<count($resultado_estudiantes);$i++)
                    {

                           if($resultado_estudiantes[$i][0]==$resultado_estudiantes[$i+1][0])
                               {
                                     $suma++;
                               }else
                                   {
                                     if($suma>=3)
                                                    {
                                                        $prueba++;

?>
                                                <tr>
                                                    <td class='cuadro_plano centrar'><?echo $prueba;?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][1]?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][5]?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][4]?></td>
                                                    <td class='cuadro_plano centrar' ><font color="#FF0000"><?echo $suma?></font></td></tr>
                                                    <?

                                                    }
                                                    else
                                                    {

                                                    }
                                       $suma=1;
                                       $totalEst++;
                                   }
                    }
                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantes",'');
                $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

?>
                 </table>
                        <tr>
                            <td class='centrar'>
                                Total de estudiantes:<b><?echo $resultado_estudiantes[0][0] ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td class='centrar'>
                                Estudiantes con tres o m&aacute;s espacios acad&eacute;micos reprobados<b> <?echo $prueba?></b>
                            </td>
                        </tr>
</table>
<?
            }


    function estudiantesProblema($configuracion)
            {
                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesProblema",'');
                $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                ?>
                <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                        <thead class='texto_subtitulo centrar'>
                        <td><center>Los siguientes estudiantes se encuentran marcados como estudiantes de cr&eacute;ditos, pero tienen asociados planes de estudio correspondientes a horas.</center></td>
                        </thead>
                     <table class='contenidotabla'>
                     <thead class='cuadro_color centrar'>
                                    <td class='cuadro_plano centrar'>Codigo Estudiante</td>
                                    <td class='cuadro_plano centrar'>Nombre Estudiante</td>
                                    <td class='cuadro_plano centrar'>Plan Estudio</td>
                                    <td class='cuadro_plano centrar'>Carrera</td>
                     </thead>

                <?
                $totalEst=0;
                for($i=0;$i<count($resultado_estudiantes);$i++)
                    {
                           if($resultado_estudiantes[$i][0]==$resultado_estudiantes[$i+1][0])
                               {
                                     $suma+=$resultado_estudiantes[$i][4];
                                     $sumaCreditos+=$resultado_estudiantes[$i][3];



                               }else
                                   {
                                       $totalEst++;
                                       $suma+=$resultado_estudiantes[$i][4];
                                       $sumaCreditos+=$resultado_estudiantes[$i][3];
//                                       echo $suma."<br>".$sumaCreditos;
                                       if($sumaCreditos==0)
                                           {
                                            ?>
                                                <tr>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][5]?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][6]?></td>
                                                    <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][7]?></td>
                                                    </tr>
                                            <?
                                           }else
                                               {
                                                    $resultado=$suma/$sumaCreditos;
//                                       echo number_format($resultado,2)/10;exit;
                                                   ?>
                                                        <tr>
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][0]?></td>
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][5]?></td>
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][6]?></td>
                                                            <td class='cuadro_plano centrar'><?echo $resultado_estudiantes[$i][7]?></td>
                                                        </tr>

<?
                                           }
                                       $suma=0;
                                       $sumaCreditos=0;
                                       $resultado=0;

                                   }
                    }
                    ?>
                 </table>
                        <tr>
                            <td class='centrar'>
                                Total de estudiantes:<b><?echo $totalEst?></b>
                            </td>
                        </tr>
</table>
<?
            }

    function reporte($configuracion)
    {
        ?>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
                <tr class='texto_subtitulo centrar'>
                    <td colspan="3"><center>ESTUDIANTES DE CR&Eacute;DITOS EN PRUEBA ACAD&Eacute;MICA PARA EL PER&Iacute;ODO ACAD&Eacute;MICO 2010 – 1.</center></td>
                </tr>
                <tr class='cuadro_color centrar'>
                    <td class='cuadro_plano centrar'>Descripci&oacute;n</td>
                    <td class='cuadro_plano centrar'>N&uacute;mero</td>
                    <td class='cuadro_plano centrar'>Porcentaje</td>
                </tr>
                <tr>
                    <td class='cuadro_plano'>Total de estudiantes</td>
                    <td class='cuadro_plano centrar'>2214</td>
                    <td class='cuadro_plano centrar'></td>
                </tr>
                <tr>
                    <td class='cuadro_plano'>Estudiantes que no alcanzaron en el per&iacute;odo 2009 – 3 el promedio acumulado exigido por la universidad, equivalente a 3.0</td>
                    <td class='cuadro_plano centrar'>981</td>
                    <td class='cuadro_plano centrar'>44,31%</td>
                </tr>
                <tr>
                    <td class='cuadro_plano'>Estudiantes que reprobaron tres (3) asignaturas o m&aacute;s del plan de estudios, durante  un mismo per&Iacute;odo acad&eacute;mico</td>
                    <td class='cuadro_plano centrar'>496</td>
                    <td class='cuadro_plano centrar'>22,40%</td>
                </tr>
                <tr>
                    <td class='cuadro_plano'>Estudiantes en Prueba Acad&eacute;mica por promedio acumulado inferior al exigido por la universidad y por reprobar tres (3) asignaturas o m&aacute;s del plan de estudios, durante  un mismo per&iacute;odo acad&eacute;mico</td>
                    <td class='cuadro_plano centrar'>470</td>
                    <td class='cuadro_plano centrar'>21,23%</td>
                </tr>
                <tr>
                    <td class='cuadro_plano'>Total de Estudiantes en Prueba Acad&eacute;mica</td>
                    <td class='cuadro_plano centrar'>1007</td>
                    <td class='cuadro_plano centrar'>45,48%</td>
                </tr>
            </table>
                        <?
    }

}
?>
