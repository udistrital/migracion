
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

class funcion_adminActualizarInscripcion extends funcionGeneral
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
	    $this->sql=new sql_adminActualizarInscripcion();
	    $this->log_us= new log();


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle produccion
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

            $this->formulario="adminActualizarInscripcion";
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
                        <h4>Actualizar Inscritos por proyecto curricular</h4>
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
                                <option value="<?echo $resultado_proyectos[$i][2]."-".$resultado_proyectos[$i][0]."-".$resultado_proyectos[$i][1]?>"><?echo $resultado_proyectos[$i][2]." - ".$resultado_proyectos[$i][1]?></option>
                                            <?
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="cuadro_plano centrar">
                        <td>

                            <input type="hidden" name="opcion" value="guardar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input name='seleccionar' value='Seleccionar' type='submit' >
                </form>
            </td>
            </tr>
            </table>

        <?
    }


	#Consulta los planes de estudio los presenta utilizando la funcion "listaPlanEstudio"
        function buscarInscripcionOracle($configuracion)
            {

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"periodo_academico",$variable);//echo $cadena_sql;exit;
                    $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle,$cadena_sql,"busqueda" );

                    $arreglo= explode("-",$_REQUEST['codProyecto']);
                    $planEstudio=$arreglo[0];
                    $codProyecto=$arreglo[1];
                    $nombreCarrera=$arreglo[2];
                    $ano=$resultado_periodo[0][0];
                    $periodo=$resultado_periodo[0][1];


                    $variable=array($codProyecto,$planEstudio,$ano,$periodo);
                    
                    #consulta estudiantes de creditos en oracle que tengan inscripciones.
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaInscripcionOracle",$variable);//echo $cadena_sql;exit;
                    $resultadoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle,$cadena_sql,"busqueda" );

                    if(is_array($resultadoInscritos))
                        {
                            #vaciar tabla sga_horario_estudiante en el periodo respectivo
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"borrarInscritos",$variable);//echo $cadena_sql;exit;
                            $BorrarEstudiante=$this->ejecutarSQL($configuracion, $this->accesoGestion,$cadena_sql,"" );

                            if($BorrarEstudiante)
                                {
                                    $this->cargarInscritos($configuracion,$resultadoInscritos);
                                }
                         }
                    else
                         {
                           echo "No se ha realizado la consulta de inscritos";
                         }

            }

        /*
         * function cargarInscritos($configuracion,$inscritos)
         * Funcion que permite cargar en la tabla sga_horario_estudiante, los registros de inscripciones que existen por proyecto curricular
         * Una vez carga los registros en Mysql, actualiza los estudiantes que reprobaron.
         */
        function cargarInscritos($configuracion,$inscritos)
            {
                $insertado=0;
                echo "Total de registros encontrados: ".count($inscritos);
                for($a=0;$a<count($inscritos);$a++)
                    {
                        $variable=array($inscritos[$a][0],$inscritos[$a][1],$inscritos[$a][2],$inscritos[$a][3],$inscritos[$a][4],$inscritos[$a][5],$inscritos[$a][6],'4');

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"insertarInscritos",$variable);//echo $cadena_sql;exit;
                        $insertarRegistro=$this->ejecutarSQL($configuracion, $this->accesoGestion,$cadena_sql,"" );
                        $registroInsertado=$this->totalAfectados($configuracion, $this->accesoGestion);//var_dump($registroInsertado);

                        if($registroInsertado>=1)
                            {
                                $insertado++;
                            }

                    }
                    echo "<br><b>Actualizando los estudiantes que ya adicionaron</b></font>";
                    
                    echo "<br><br><br><font size='3'>Total estudiantes de cr&eacute;ditos cargados: ".$insertado."</font>";

                    $this->actualizarEstudiantesAdicionaron($configuracion,$inscritos);

            }


        function actualizarEstudiantesAdicionaron($configuracion,$inscritos)
        {
            $actualizado=0;
            for($a=0;$a<count($inscritos);$a++)
            {
                //$actualizado=0;
                $variable=array($inscritos[$a][0],$inscritos[$a][1],$inscritos[$a][2],$inscritos[$a][3],$inscritos[$a][4],$inscritos[$a][5],$inscritos[$a][6]);

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_notas",$variable);//echo $this->cadena_sql;exit;
                $buscarNotas=$this->ejecutarSQL($configuracion, $this->accesoOracle,$this->cadena_sql,"busqueda" );

                if(is_array($buscarNotas))
                    {
                        $variable[7]='2';
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarInscritos",$variable);
                            $insertarRegistro=$this->ejecutarSQL($configuracion, $this->accesoGestion,$cadena_sql,"" );
                            $registroActualizado=$this->totalAfectados($configuracion, $this->accesoGestion);//var_dump($registroInsertado);

                            if($registroActualizado>=1)
                            {
                                $actualizado++;
                            }
                    }
            }
            echo "<br><br><br><font size='3'>Total asignaturas reprobadas el semestre anterior: ".$actualizado."</font>";

        }

        function cargarBloquesInscritos($configuracion)
        {
            $variable[0]='20';

            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"verEstudiantesNuevos",$variable);//echo $this->cadena_sql;exit;
            $resultadoEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle,$this->cadena_sql,"busqueda" );

            for($i=0;$i<count($resultadoEstudiantes);$i++)
            {
                $variables=array($resultadoEstudiantes[$i][1], $resultadoEstudiantes[$i][0], '205', $resultadoEstudiantes[$i][8], $resultadoEstudiantes[$i][9], $resultadoEstudiantes[$i][2],$resultadoEstudiantes[$i][3],'4');

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"insertarHorarioEstudiante",$variables);//echo $this->cadena_sql;exit;
                $resultadoEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoGestion,$this->cadena_sql,"" );
            }

            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"verEstudiantesNuevosCod",$variable);//echo $this->cadena_sql;exit;
            $resultadoEstudiantesCod=$this->ejecutarSQL($configuracion, $this->accesoOracle,$this->cadena_sql,"busqueda" );

            for($i=0;$i<count($resultadoEstudiantesCod);$i++)
            {
                $variables=array('1','205',$variable[0],$resultadoEstudiantesCod[$i][0]);

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"insertarBloquesEstudiantes",$variables);//echo $this->cadena_sql;exit;
                $resultadoInsertarEstudiantesCod=$this->ejecutarSQL($configuracion, $this->accesoGestion,$this->cadena_sql,"" );
            }

            echo "Total de estudiantes cargados:".count($resultadoEstudiantesCod);exit;

        }




}
?>
