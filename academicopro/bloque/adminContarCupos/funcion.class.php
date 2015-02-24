
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
$GLOBALS ["formularioMalla"]="adminMalla";
$formularioMalla= "adminMalla";
$verificarMalla="control_vacio(".$formularioMalla.",'descripcion_malla')";
$verificarMalla.="&& control_vacio(".$formularioMalla.",'nombre_malla')";
$GLOBALS["verificarMalla"]=$verificarMalla;


?>

<?
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminContarCupos extends funcionGeneral
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
	    $this->sql=new sql_adminContarCupos();
	    $this->log_us= new log();


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle produccion
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

            $this->formulario="adminContarCupos";
	    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links  
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];	
	    		
	}

        function verProyectos($configuracion) {

        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$variable);//echo $cadena_sql_proyectos;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        ?>
            <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr align="center">
                    <td class="centrar" colspan="4">
                        <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                    </td>
                </tr>
                <tr align="center">
                    <td class="centrar" colspan="4">
                        <h4>Horario de Espacios acad&eacute;micos por proyecto curricular</h4>
                        <hr noshade class="hr">

                    </td>
                </tr><br><br>
                <tr class="centrar">
                    <td>
                        Seleccione el proyecto curricular
                    </td>
                </tr>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                    <tr class="centrar">
                        <td>
                            <select name="codProyecto" id="codProyecto" style="width:380px">
                    <?

                    for($i=0;$i<count($resultado_proyectos);$i++) {
                        ?>
                                <option value="<?echo $resultado_proyectos[$i][0]?>"><?echo $resultado_proyectos[$i][2]." - ".$resultado_proyectos[$i][1]?></option>
                                            <?
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="cuadro_plano centrar">
                        <td>

                            <input type="hidden" name="opcion" value="verCupos">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input name='seleccionar' value='Seleccionar' type='submit' >
                </form>
            </td>
            </tr>
            </table>

        <?
    }


	#Consulta los planes de estudio los presenta utilizando la funcion "listaPlanEstudio"
        function contarCupos($configuracion)
            {
                    $codProyecto=$_REQUEST['codProyecto'];
                    
                     #consulta inscripciones
                     $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarInscripciones",$codProyecto);
                     $registroInscripciones=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                     $totalInscripciones=count($registroInscripciones);

                     ?>

                    <table class="contenido_tabla" width="100%" border="1">
                                                       <tr>
                                                           <td>
                                                               <?echo "Carrera "?>
                                                           </td>
                                                           <td>
                                                               <?echo "Espacio "?>
                                                           </td>
                                                           <td>
                                                               <?echo "Grupo "?>
                                                           </td>
                                                           <td>
                                                               <?echo "Inscritos<br>Reales "?>
                                                           </td>
                                                           <td>
                                                               <?echo "Inscritos<br>ACCURSO "?>
                                                           </td>
                                                           <td>
                                                               <?echo "Cupo<br>ACCURSO "?>
                                                           </td>
                                                       </tr>

                     <?

                     for($a=0; $a<$totalInscripciones;$a++)
                         {
                             #Cuenta cupos por grupo
                             $variable=array($registroInscripciones[$a][0],$registroInscripciones[$a][1],$registroInscripciones[$a][2]);
                             $this->cadena_sql=$this->sql->cadena_sql($configuracion,"contarCupos",$variable);
                             $registroCupos=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                             //echo $this->cadena_sql;
                             //exit;
                            //$totalCupos=count($registroInscripciones);

                             if(is_array($registroInscripciones))
                                 {

                                                   ?>
   
                                                       <tr>
                                                           <td>
                                                               <?echo $registroInscripciones[$a][0] //Carrera?>
                                                           </td>
                                                           <td>
                                                               <?echo $registroInscripciones[$a][1] //Espacio?>
                                                           </td>
                                                           <td>
                                                               <?echo $registroInscripciones[$a][2] //Grupo?>
                                                           </td>
                                                           <td>
                                                               <?
                                                               if($registroCupos[0][0]>$registroInscripciones[$a][4])
                                                                   {?>
                                                                   <font color='blue'><?echo $registroCupos[0][0] //Inscritos reales?></font>
                                                                   <?
                                                                   }
                                                               else
                                                                    {
                                                                        echo $registroCupos[0][0];//Inscritos reales
                                                                    }
                                                                   ?>
                                                               
                                                           </td>
                                                           <td>
                                                               <?
                                                               if($registroCupos[0][0]!=$registroInscripciones[$a][3])
                                                                   {?>
                                                                    <font color='red'><?echo $registroInscripciones[$a][3]//Inscritos ACCURSO?></font>
                                                                   <?
                                                                   }
                                                               else
                                                                    {
                                                                      echo $registroInscripciones[$a][3];//Inscritos ACCURSO
                                                                    }
                                                                   ?>     
                                                           </td>
                                                           <td>
                                                               <?
                                                               if($registroCupos[0][0]>$registroInscripciones[$a][4])
                                                                   {?>
                                                                    <font color='blue'><?echo $registroInscripciones[$a][4]//Cupo ACCURSO?></font>
                                                                   <?
                                                                   }
                                                               else
                                                                    {
                                                                      echo $registroInscripciones[$a][4];//Cupo ACCURSO
                                                                    }
                                                                   ?>               
                                                           </td>
                                                           <td>
                                                               <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                                               <input type="submit" name="actualizar" value="Actualizar">
                                                               <input type="hidden" name="opcion" value="actualizar">
                                                               <input type="hidden" name="action" value="adminContarCupos">
                                                               <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                                               <input type="hidden" name="cupoReal" value="<?echo $registroCupos[0][0]//Cupo real?>">
                                                               <input type="hidden" name="espacio" value="<?echo $registroInscripciones[$a][1] //Espacio?>">
                                                               <input type="hidden" name="grupo" value="<?echo $registroInscripciones[$a][2] //Grupo?>">
                                                               </form>
                                                           </td>
                                                       </tr>
                                                      
                                                       <?


                                }

                         }
                         ?>
                          </table>
                          <?
                }

                function actualizarCupos($configuracion)
                {

                    //var_dump($this->accesoOracle);exit;
                    $variable=array($_REQUEST['espacio'],$_REQUEST['grupo'],$_REQUEST['codProyecto']);

                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_cupo",$variable);//echo $this->cadena_sql;exit;
                        $registroCupos=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");

                        echo "<script>alert('Se ha actualizado la asignatura ".$variable[0]." y grupo ".$variable[1]."')</script>";

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminContarCupos";
                        $variable.="&opcion=verCupos";
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";

                }



}
?>
