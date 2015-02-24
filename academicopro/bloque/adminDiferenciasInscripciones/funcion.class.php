
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

class funcion_adminDiferenciasInscripciones extends funcionGeneral
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
	    $this->sql=new sql_adminDiferenciasInscripciones();
	    $this->log_us= new log();
            $this->formulario="adminDiferenciasInscripciones";


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion Oracle Prod
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

            //Conexion MySQL Prod
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");


	    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

            //echo $this->usuarioSesion[0][0];

	}

        #Entrada al bloque para ver estudiantes con promedio menor a 3
	function seleccionPlan($configuracion)
        {
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
            $html=new html();
            //$cadenas=new cadenas();

            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaPlanEstudioMysql","");
            $planes_1=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
                //echo $planes_1;
                $i=0;
                    while(isset($planes_1[$i][0]))
                    {
                            $planes[$i][0]=$planes_1[$i][0];
                            $planes[$i][1]=utf8_decode($planes_1[$i][0].' - '.$planes_1[$i][1]);
                    $i++;
                    }

//                //Genera un formulario para pedir datos de la pre-inscripción

                ?>
                    <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td colspan="2">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>

                           </td>
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                <h4>Diferencias de registros MYSQL y ORACLE</h4>
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
                                                <input type='hidden' name='opcion' value='diferencias'>
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



        function diferencias($configuracion)
        {
            //muestra las diferencias entre los planes de estudio de ORACLE y MySQL
            $planestudio=$_REQUEST['planEstudio'];

            //Busca datos desde ORACLE
            $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesRegistroMysql",$planestudio);
            $resultado_estudiantesRegistroMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if($resultado_estudiantesRegistroMysql==true){


            for($i=0;$i<count($resultado_estudiantesRegistroMysql);$i++)
            {
                    $cadena_sqlMysql=$this->sql->cadena_sql($configuracion,"registrosMysql",$resultado_estudiantesRegistroMysql[$i][0]);
                    $resultado_RegistroMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sqlMysql,"busqueda" );

                    $cadena_sqlOracle=$this->sql->cadena_sql($configuracion,"registrosOracle",$resultado_estudiantesRegistroMysql[$i][0]);
                    $resultado_RegistroOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sqlOracle,"busqueda" );

                    ?>
                    <table class="cuadro_plano color centrar" width="100%">
                        <tr>
                            <td class="centrar" colspan="6">
                                <?
                                if(count($resultado_RegistroMysql) == count($resultado_RegistroOracle))
                                    {
                                        ?>
                                            <font color="blue">C&oacute;digo Estudiante:<?echo $resultado_estudiantesRegistroMysql[$i][0]?></font>
                                        <?
                                    }else if(count($resultado_RegistroMysql) != count($resultado_RegistroOracle))
                                        {
                                        ?>
                                            <font color="red">C&oacute;digo Estudiante:<?echo $resultado_estudiantesRegistroMysql[$i][0]?></font>
                                        <?
                                        }
                                ?>
                                
                            </td>
                        </tr>
                        <tr>
                            <td  class="cuadro_color centrar" colspan="3">
                                Registros Mysql
                            </td>
                            <td  class="cuadro_color centrar" colspan="3">
                                Registros Oracle
                            </td>
                        </tr>
                        <tr class="cuadro_color">
                            <td class="centrar">Carrera</td><td class="centrar">Espacio Acad</td><td class="centrar">Grupo</td>
                            <td class="centrar">Carrera</td><td class="centrar">Asignatura</td><td class="centrar">Grupo</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <table class="contenido_tabla centrar" width="100%">
                                    <?
                                        for($j=0;$j<count($resultado_RegistroMysql);$j++)
                                        {
                                            ?>
                                            <tr>
                                            <td class="cuadro_plano centrar">
                                                <?echo $resultado_RegistroMysql[$j][0]?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?echo $resultado_RegistroMysql[$j][1]?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?echo $resultado_RegistroMysql[$j][2]?>
                                            </td>
                                            </tr>
                                            <?
                                        }
                                    ?>
                                </table>
                            </td>
                            <td colspan="3">
                                <table class="contenido_tabla centrar" width="100%">
                                <?
                                        for($h=0;$h<count($resultado_RegistroOracle);$h++)
                                        {
                                            ?>
                                            <tr>
                                            <td class="cuadro_plano centrar">
                                                <?echo $resultado_RegistroOracle[$h][0]?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?echo $resultado_RegistroOracle[$h][1]?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?echo $resultado_RegistroOracle[$h][2]?>
                                            </td>
                                            </tr>
                                            <?
                                        }
                                ?>
                                </table>
                            </td>
                        </tr>
                    </table><br>


                    <?

            }

            }
        }

}
?>
