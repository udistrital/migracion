
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
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/codigoBarras.class.php");



#Realiza la preparacion del formulario para la validacion de javascript

?>

<?
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminConsultarCIEstudianteCoordinador extends funcionGeneral
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
	    $this->sql=new sql_adminConsultarCIEstudianteCoordinador();
	    $this->log_us= new log();
            $this->formulario="adminInscripcionCreditosEstudiante";


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


        #muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante
 	function mostrarHorarioEstudiante($configuracion)
            {
                $codigoEstudiante=$_REQUEST['codEstudiante'];

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEstudiante",$codigoEstudiante);
                $registroEstudiante=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                //var_dump ($registroEstudiante);exit;

                if(isset($registroEstudiante))
                    {
                        $this->datosEstudiante($configuracion,$registroEstudiante);

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo",'');//echo $cadena_sql;exit;
                        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $variablesInscritos=array($codigoEstudiante,$resultado_periodo[0][0],$resultado_periodo[0][1]);

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaGrupo",$variablesInscritos);//echo $cadena_sql;exit;
                        $registroGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $creditos=$this->calcularCreditos($configuracion,$registroGrupo);
                        ?>

                        <table class="contenidotabla2" align="center">
                            <tr>
                             <td colspan="2" class="derecha">
                         <?if ($creditos==0)
                                          {
                                            echo "<font size='1'><b>Cr&eacute;ditos Inscritos: 0</b></font>";
                                          }
                                          else
                                          {
                                            echo "<font size='1'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                                          }
                                          ?>
                            </td>
                           </tr>
                        </table>

                        <?

                           //   var_dump($_REQUEST);exit;
                           
                            $i=5;
                            do{

                               switch($i)
                               {

                                   case '5':
                                            //echo "Entro 5-PLAN:";
                                            //echo $_REQUEST['planEstudioGeneral'];
                                         
                                            $variablesFecha=array($_REQUEST['planEstudio'],$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechas",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                { 
                                                for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                $inicio = $registroFecha[$j][1]-date('YmdHis');
                                                $final = $registroFecha[$j][2]-date('YmdHis');
                                                if(($inicio>=0)&&($final<=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                      }else if(($inicio<=0)&&($final>=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    
                                                }
                                       break;
                                   
                                   case '4':
                                            //echo "Entro 4: Pro:";
                                            //echo $_REQUEST["codProyecto"]."-PLAN:".$_REQUEST['planEstudioGeneral'];;
                                            $variablesFecha=array($_REQUEST['codProyecto'],$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechas",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                {
                                                    for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                  $inicio = $registroFecha[$j][1]-date('YmdHis');
                                                  $final = $registroFecha[$j][2]-date('YmdHis');
                                                if(($inicio>=0)&&($final<=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($inicio<=0)&&($final>=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                }
                                       break;

                                   case '3':
                                            //echo "Entro 3";
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"facultad",$_REQUEST['codProyecto']);//echo $cadena_sql;exit;
                                            $registroFacultad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                            if(is_array($registroFacultad))
                                                {
                                                    $variablesFecha=array($registroFacultad[0][0],$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechas",$variablesFecha);//echo $cadena_sql;exit;
                                                    $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                                    if(is_array($registroFecha))
                                                    {
                                                       for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                 $inicio = $registroFecha[$j][1]-date('YmdHis');
                                                 $final = $registroFecha[$j][2]-date('YmdHis');
                                               if(($inicio>=0)&&($final<=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($inicio<=0)&&($final>=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    }
                                                }

                                            
                                       break;

                                    case '2':
                                            //echo "Entro 2";
                                            $variablesFecha=array($_REQUEST['codProyecto'],$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechasGeneral",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                    {
                                                        for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                  $inicio = $registroFecha[$j][1]-date('YmdHis');
                                                  $final = $registroFecha[$j][2]-date('YmdHis');
                                                if(($inicio>=0)&&($final<=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($inicio<=0)&&($final>=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    }
                                       break;

                                    case '1':
                                            //echo "Entro 1";
                                            $variablesFecha=array($_REQUEST['codProyecto'],$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechasGeneral",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                    {
                                                        for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                  $inicio = $registroFecha[$j][1]-date('YmdHis');
                                                  $final = $registroFecha[$j][2]-date('YmdHis');
                                                if(($inicio>=0)&&($final<=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($inicio<=0)&&($final>=0))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    }
                                       break;

                                       default:
                                           //echo "Entro 0";
                                           $band=1;
                                       $registroFecha[0][0]='0';
                                           break;

                               }
                                $i--;
                           }while($band==0);

                           //echo $registroFecha[0][0];exit;
//echo $registroFecha[0][1]."<br>".date('YmdHis')."<br>".date('YmdHis')."<br>".$registroFecha[0][2];exit;
                            $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
                            $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

                            $bander=0;
                            for($n=0;$n<count($resultado_proyectos);$n++)
                            {
                                if($registroEstudiante[0][3]==$resultado_proyectos[$n][0])
                                    {
                                        $bander=1;
                                        break;
                                    }
                            }

                            if($bander==0)
                                {
                                    $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                                    exit;
                                }

                           switch($registroFecha[0][0])
                           {
                               case '100':

                                   if($registroFecha[0][1]>=date('YmdHis'))
                                       {
                                            if(date('YmdHis')<=$registroFecha[0][2]){
                                            $this->HorarioEstudianteInscripcion($configuracion,$registroGrupo,$registroEstudiante,$_REQUEST['planEstudioGeneral'],$_REQUEST["codProyecto"]);
                                            }else
                                                {
                                                    $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                                                }
                                       }else if($registroFecha[0][1]<date('YmdHis'))
                                       {
                                           if(date('YmdHis')<=$registroFecha[0][2])
                                            {
                                                $this->HorarioEstudianteInscripcion($configuracion,$registroGrupo,$registroEstudiante,$_REQUEST['planEstudioGeneral'],$_REQUEST["codProyecto"]);
                                            }
                                            else
                                               {
                                                $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                                               }
                                       }else
                                           {
                                            $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                                           }
                                   break;

                               case '101':

                                   if(($registroFecha[0][1]>=date('YmdHis'))&&(date('YmdHis')<=$registroFecha[0][2]))
                                       {
                                            $this->HorarioEstudianteCancelacion($configuracion,$registroGrupo,$registroEstudiante,$_REQUEST['planEstudioGeneral'],$_REQUEST["codProyecto"]);
                                       }else if(($registroFecha[0][1]<date('YmdHis'))&&(date('YmdHis')<=$registroFecha[0][2]))
                                       {
                                            $this->HorarioEstudianteCancelacion($configuracion,$registroGrupo,$registroEstudiante,$_REQUEST['planEstudioGeneral'],$_REQUEST["codProyecto"]);
                                       }else
                                           {
                                            $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                                           }
                                   break;

                               case '0':
                                        $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                                       break;

                           }
                           

                           //   $creditos=$this->calcularCreditos($configuracion,$registroGrupo);
                              //var_dump($creditos);exit;
                              ?>
                                    <div align="right">
                                      
                                          </div>
<table class="contenidotabla2" align="center">
    <tr>
        <td colspan="2" class="derecha">
    <?/*if ($creditos==0)
                                          {
                                            echo "<font size='1'><b>Cr&eacute;ditos Inscritos: 0</b></font>";
                                          }
                                          else
                                          {
                                            echo "<font size='1'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                                          }*/
                                          ?>
        </td>
    </tr>
    <tr>
        <td class="cuadro_color centrar">
            Abreviatura
        </td>
        <td class="cuadro_color centrar">
            Nombre
        </td>
    </tr>
                                          <?
                                   

                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacion",'');
                                    $resultado_clasificacion=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");

                                    for($k=0;$k<count($resultado_clasificacion);$k++)
                                    {
                                        ?>
    <tr>
        <td class="cuadro_plano centrar">
            <?echo $resultado_clasificacion[$k][1]?>
        </td>
        <td class="cuadro_plano">
            <?echo $resultado_clasificacion[$k][2]?>
        </td>
    </tr>
                                        <?
                                    }

                                     ?>
</table>
                            <table class="cuadro_color centrar" width="100%">
                                <tr class="cuadro_plano centrar">
                                    <th>
                                        Observaciones
                                    </th>
                                </tr>
                                <tr class="cuadro_plano">
                                    <td>
                                        <!--<h3>Recuerde el proceso de adiciones se cerrar&aacute; el 15 de febrero de 2010 a las 11:59pm.</h3>-->
                                        <br>
                                        * Recuerde que si cancela un espacio académico, no podra adicionarlo de nuevo para el periodo actual
                                        <br>
                                        * Recuerde verificar el cruce de horarios de los espacios académicos
                                        <br>
                                        * Recuerde que si el grupo no cumple con el cupo mínimo, puede ser cancelado
                                    </td>
                                </tr>
                            </table>
                            <?


                       }
                    else{
                        echo "El código de estudiante: <strong>".$codigoEstudiante."</strong> no está inscrito en Créditos.";
                        }




            }

        #Funcion que muestra la informacion del estudiante
	function datosEstudiante($configuracion,$registro)
            {
             ?>
             <br>
            <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
                    <tr class="texto_subtitulo">
                            <td colspan="2">
                                      <?echo "Nombre: <strong>".htmlentities($registro[0][1])."</strong><br>";?>
                                      <?echo "C&oacute;digo: <strong>".$registro[0][0]."</strong><br>";?>
                                      Proyecto Curricular:
                                      <?echo "<strong>".$registro[0][3]."</strong><br>";?>
                                        Plan de Estudios:
                                      <?echo "<strong>".htmlentities($registro[0][2])." - ".htmlentities($registro[0][4])."</strong>";?>

                                <hr>
                            </td>
                    </tr>

              </table>


            <?
            }

        function HorarioEstudianteConsulta($configuracion, $resultado_grupos,$registroEstudiante)
            {


                ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){
                                        
                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                          $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                                      $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                                          
                                        ?>

                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='cuadro_plano centrar'>
                                        <th><center><?echo "Horario de Clases";?></center></th>
                                        </thead>


                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <thead class='cuadro_color'>
                                                    <td class='cuadro_plano centrar'>Cod.</td>
                                                    <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                                                        <td class='cuadro_plano centrar' width="25">Grupo </td>
                                                        <td class='cuadro_plano centrar' width="25">Cr&eacute;ditos</td>
                                                        <td class='cuadro_plano centrar' width="25">Clasificaci&oacute;n</td>
                                                        <td class='cuadro_plano centrar' width="60">Lun </td>
                                                        <td class='cuadro_plano centrar' width="60">Mar </td>
                                                        <td class='cuadro_plano centrar' width="60">Mie </td>
                                                        <td class='cuadro_plano centrar' width="60">Jue </td>
                                                        <td class='cuadro_plano centrar' width="60">Vie </td>
                                                        <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                                        <td class='cuadro_plano centrar' width="60">Dom </td>
                                                        
                                                    </thead>

                <?


                //recorre cada uno del los grupos
                for($j=0;$j<count($resultado_grupos);$j++){

                    //
                    $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                    $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                    $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                    $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                    $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                    $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                    $variables[0][8]=$resultado_grupos[$j][8];  //nombre1 del estudiante
                    $variables[0][9]=$resultado_grupos[$j][9];  //nombre2 del estudiante
                    $variables[0][10]=$resultado_grupos[$j][10];  //apellido1 del estudiante
                    $variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                    //busca la clasificacion del espacio academico
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacionEspacio",$resultado_grupos[$j][0]);//echo $cadena_sql;exit;
                    $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                    //var_dump($resultado_horarios);
                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                            <td class='cuadro_plano'><?echo htmlentities($resultado_grupos[$j][5]);?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_clasif[0][1];?></td>
                    <?

                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?

                        //Recorre el arreglo del resultado de los horarios
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                {

                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]!=$i)
                            {

                            }
                        }
                        ?></td><?
                    }

                   ?>
                        </tr>                        
                        <?}
                                        }else{?>
                                        <tr>
                                            <td class='cuadro_plano centrar'>
                                                No se encontraron datos de espacios adicionados
                                            </td>
                                        </tr>
                        <?}


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
             $codEstudiante=$registroEstudiante[0][0];
                   list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante, $planEstudioGeneral, $codProyecto, $creditosInscritos);
               ?>
             <br> 
             <table align='center' width='600' cellspacing='0' cellpadding='2'>
                  <tr>
                      <td>
                      <?
                      echo $valor1;
                      echo $valor2;
                      echo $valor3;
                      echo $valor4;
                      echo $valor5;
                      echo $valor6;
                      ?>
                      </td>
                  </tr>
             </table>

                <?



        }

        function HorarioEstudianteInscripcion($configuracion, $resultado_grupos,$registroEstudiante,$planEstudioGeneral,$codProyecto)
            {
               

                ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){

                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                          $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                                      $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                                        ?>

                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='cuadro_plano centrar'>
                                        <th><center><?echo "Horario de Clases";?></center></th>
                                        </thead>


                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <thead class='cuadro_color'>
                                                    <td class='cuadro_plano centrar'>Cod.</td>
                                                    <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                                                        <td class='cuadro_plano centrar' width="25">Grupo </td>
                                                        <td class='cuadro_plano centrar' width="25">Cr&eacute;ditos</td>
                                                        <td class='cuadro_plano centrar' width="25">Clasificaci&oacute;n</td>
                                                        <td class='cuadro_plano centrar' width="60">Lun </td>
                                                        <td class='cuadro_plano centrar' width="60">Mar </td>
                                                        <td class='cuadro_plano centrar' width="60">Mie </td>
                                                        <td class='cuadro_plano centrar' width="60">Jue </td>
                                                        <td class='cuadro_plano centrar' width="60">Vie </td>
                                                        <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                                        <td class='cuadro_plano centrar' width="60">Dom </td>
                                                        <td class='cuadro_plano centrar'>Cambiar<br>Grupo</td>
                                                        <td class='cuadro_plano centrar'>Cancelar</td>
                                                    </thead>

                <?


                //recorre cada uno del los grupos
                for($j=0;$j<count($resultado_grupos);$j++){

                    //
                    $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                    $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                    $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                    $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                    $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                    $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                    $variables[0][8]=$resultado_grupos[$j][8];  //nombre1 del estudiante
                    $variables[0][9]=$resultado_grupos[$j][9];  //nombre2 del estudiante
                    $variables[0][10]=$resultado_grupos[$j][10];  //apellido1 del estudiante
                    $variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                    //busca la clasificacion del espacio academico
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacionEspacio",$resultado_grupos[$j][0]);//echo $cadena_sql;exit;
                    $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    //var_dump($resultado_horarios);
                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                            <td class='cuadro_plano'><?echo htmlentities($resultado_grupos[$j][5]);?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_clasif[0][1];?></td>
                    <?

                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?

                        //Recorre el arreglo del resultado de los horarios
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                {

                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]!=$i)
                            {

                            }
                        }
                        ?></td><? 
                    }

                    
                    ?>
                            <td class='cuadro_plano centrar'>

                                <? 
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoCIEstudianteCoordinador";
				$variable.="&opcion=buscar";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&proyecto=".$variables[0][1];
                                $variable.="&codEspacio=".$variables[0][0];
                                $variable.="&grupo=".$variables[0][2];
                                $variable.="&planEstudio=".$variables[0][7];
                                $variable.="&nombre=".$resultado_grupos[$j][5];


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);


				?>

                                <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/reload.png"?>" border="0" width="25" height="25">
                                </a>

                                </td>
                                
                            <td class='cuadro_plano centrar'>

                                <?//echo $planEstudio;
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCIEstudianteCoordinador";
				$variable.="&opcion=cancelarCreditos";
                                $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$variables[0][6];
                                $variable.="&proyecto=".$variables[0][1];
                                $variable.="&codEspacio=".$variables[0][0];
                                $variable.="&grupo=".$variables[0][2];
                                $variable.="&planEstudio=".$variables[0][7];
                                $variable.="&nombre=".$resultado_grupos[$j][5];


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                </a>


                            </td>
                        </tr>
                        <?}
                                   $codEstudiante=$registroEstudiante[0][0];
                                   list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante, $planEstudioGeneral, $codProyecto, $creditosInscritos);
                        ?>
                        <br>
                        <table align='center' width='600' cellspacing='0' cellpadding='2'>
                            <tr>
                                <td class='cuadro_plano centrar'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;                                    
                                    ?>
                                </td>
                                <td class='cuadro_plano centrar'>

                                <?

                                $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarCIEstudianteCoordinador";
				$variable.="&opcion=espacios";
                                $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                $variable.="&creditosInscritos=".$creditosInscritos;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br>Adicionar
                                </a>
                                </td>

                            </tr>
                            <tr>
                                <td class='cuadro_plano centrar'>
                                   <? echo $valor5;
                                      echo $valor6;
                                   ?>
                                </td>
                                <td class='cuadro_plano centrar textoBlanco'>
                                    -
                                </td>
                            </tr>
                            </table>
                                      <?  }else{
                                          $codEstudiante=$registroEstudiante[0][0];
                                          list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante, $planEstudioGeneral, $codProyecto, $creditosInscritos);
                                       ?>
                                        <tr>
                                            <td class='cuadro_plano centrar'>
                                                No se encontraron datos de espacios adicionados
                                            </td>
                                        </tr>
                             <br><table align='center' width='600' cellspacing='0' cellpadding='2' >
                                <tr class="centrar">
                                <td class='cuadro_plano centrar'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    ?>
                                </td>
                                <td class='cuadro_plano centrar'>

                                <?

                                $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarCIEstudianteCoordinador";
				$variable.="&opcion=espacios";
                                $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                $variable.="&creditosInscritos=".$creditosInscritos;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br>Adicionar
                                </a>
                                </td>
                            </tr>
                            <tr>
                                <td class='cuadro_plano centrar'>
                                   <? echo $valor5;
                                      echo $valor6;
                                   ?>
                                </td>                                
                                <td class='cuadro_plano centrar textoBlanco'>-
                                </td>                                
                            </tr>
                            </table>
                        <?}


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

        function HorarioEstudianteCancelacion($configuracion, $resultado_grupos,$registroEstudiante,$planEstudioGeneral,$codProyecto)
            {
               ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){

                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                          $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                                      $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                                        ?>

                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='cuadro_plano centrar'>
                                        <th><center><?echo "Horario de Clases";?></center></th>
                                        </thead>


                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <thead class='cuadro_color'>
                                                    <td class='cuadro_plano centrar'>Cod.</td>
                                                    <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                                                        <td class='cuadro_plano centrar' width="25">Grupo </td>
                                                        <td class='cuadro_plano centrar' width="25">Cr&eacute;ditos</td>
                                                        <td class='cuadro_plano centrar' width="25">Clasificaci&oacute;n</td>
                                                        <td class='cuadro_plano centrar' width="60">Lun </td>
                                                        <td class='cuadro_plano centrar' width="60">Mar </td>
                                                        <td class='cuadro_plano centrar' width="60">Mie </td>
                                                        <td class='cuadro_plano centrar' width="60">Jue </td>
                                                        <td class='cuadro_plano centrar' width="60">Vie </td>
                                                        <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                                        <td class='cuadro_plano centrar' width="60">Dom </td>
                                                        <td class='cuadro_plano centrar'>Cancelar</td>
                                                    </thead>

                <?


                //recorre cada uno del los grupos
                for($j=0;$j<count($resultado_grupos);$j++){

                    //
                    $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                    $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                    $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                    $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                    $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                    $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                    $variables[0][8]=$resultado_grupos[$j][8];  //nombre1 del estudiante
                    $variables[0][9]=$resultado_grupos[$j][9];  //nombre2 del estudiante
                    $variables[0][10]=$resultado_grupos[$j][10];  //apellido1 del estudiante
                    $variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
                    //var_dump($resultado_horarios);

                    //busca la clasificacion del espacio academico
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacionEspacio",$resultado_grupos[$j][0]);//echo $cadena_sql;exit;
                    $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    //var_dump($resultado_horarios);
                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                            <td class='cuadro_plano'><?echo htmlentities($resultado_grupos[$j][5]);?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_clasif[0][1];?></td>
                    <?


                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?

                        //Recorre el arreglo del resultado de los horarios
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                {

                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]!=$i)
                            {

                            }
                        }
                        ?></td><?
                    }

                    
                                ?>
                            <td class='cuadro_plano centrar'>

                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCreditosEstudiante";
				$variable.="&opcion=verificar"; 
                                $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$variables[0][6];
                                $variable.="&proyecto=".$variables[0][1];
                                $variable.="&codEspacio=".$variables[0][0];
                                $variable.="&grupo=".$variables[0][2];
                                $variable.="&planEstudio=".$variables[0][7];
                                $variable.="&nombre=".$resultado_grupos[$j][5];


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                </a>


                            </td>
                        </tr>
                        <?}
                                        }else{?>
                                        <tr>
                                            <td class='cuadro_plano centrar'>
                                                No se encontraron datos de espacios adicionados
                                            </td>
                                        </tr>
                        <?}


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
             $codEstudiante=$registroEstudiante[0][0];
                   list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante, $planEstudioGeneral, $codProyecto, $creditosInscritos);
               ?>
             <br><table align='center' width='600' cellspacing='0' cellpadding='2'>
                  <tr>
                      <td>
                      <?
                      echo $valor1;
                      echo $valor2;
                      echo $valor3;
                      echo $valor4;
                      echo $valor5;
                      echo $valor6;
                      ?>
                      </td>
                  </tr>
             </table>               

                <?



        }

        function calcularCreditos($configuracion,$registroGrupo)
            {
                $suma=0;
                for($i=0;$i<count($registroGrupo);$i++)
                {
                    $suma+=$registroGrupo[$i][9];
                }

                return $suma;

            }

  function porcentajeParametros($configuracion,$codEstudiante)
    {       
            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarPlan",$codEstudiante);
            $registroPlan=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
            $planEstudiante=$registroPlan[0][1];

            $cadena_sql=$this->sql->cadena_sql($configuracion,"creditosPlan",$planEstudiante);//echo $cadena_sql;exit;
            $registroCreditosGeneral=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            
            $totalCreditos= $registroCreditosGeneral[0][0];
            $OB= $registroCreditosGeneral[0][1];
            $OC= $registroCreditosGeneral[0][2];
            $EI= $registroCreditosGeneral[0][3];
            $EE= $registroCreditosGeneral[0][4];

            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosAprobados",$codEstudiante);//secho $this->cadena_sql;exit;
            $registroEspaciosAprobados=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");            
           

            for($i=0;$i<=count($registroEspaciosAprobados);$i++)
            {
            $idEspacio= $registroEspaciosAprobados[$i][0];
            $variables=array($idEspacio, $planEstudiante);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"valorCreditosPlan",$variables);//echo $cadena_sql;exit;
            $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );          
            
           switch($registroCreditosEspacio[0][1])
            {
                case 1:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                    break;

                case 2:
                        $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                    break;

                case 3:
                        $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                    break;

                case 4:
                        $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                    break;

                case '':
                        $totalCreditosEst=$totalCreditosEst+0;
                    break;

                 }
            }
           $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;



            $porcentajeCursado=$totalCreditosEst*100/$totalCreditos;
            if($porcentajeCursado==0){$totalCreditosEst=0;}
            $porcentajeOBCursado=$OBEst*100/$OB;
            if($porcentajeOBCursado==0){$OBEst=0;}
            $porcentajeOCCursado=$OCEst*100/$OC;
            if($porcentajeOCCursado==0){$OCEst=0;}
            $porcentajeEICursado=$EIEst*100/$EI;
            if($porcentajeEICursado==0){$EIEst=0;}
            $porcentajeEECursado=$EEEst*100/$EE;
            if($porcentajeEECursado==0){$EEEst=0;}
           // $porcentajeCursado=100;

            if($totalCreditos>0)
            {
            $vista="
            <table align='center' width='600%' cellspacing='0' bgcolor='#fffffa'>
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='5'>Cr&eacute;ditos Acad&eacute;micos
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='7%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='12%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='13%'>Por Aprobar
                      </td>                                                                 
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>% Cursado
                      </td>                      
                   </tr>
                   </table>";         
                       
             $vistaOB="<table align='center' width='600%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>OB
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='7%'>".$OB."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='12%'>".$OBEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='13%'>".$FaltanOB=$OB-$OBEst."
                      </td>                      
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>";
           if($porcentajeOBCursado==0)
             {
             $vistaOB.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
             $OBEst=0;
             }
             if($porcentajeOBCursado==100)
             {
             $vistaOB.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           </table>";
             }
             if($porcentajeOBCursado!=0 AND $porcentajeOBCursado!=100)
             {
             $vistaOB.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeOBCursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalOB=100-$porcentajeOBCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
             }
             $vistaOB.="</td></tr>
                      </table>";


             $vistaOC="<table align='center' width='550%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>OC
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='7%'>".$OC."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='12%'>".$OCEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='13%'>".$FaltanOC=$OC-$OCEst."
                      </td>                      
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>";
           if($porcentajeOCCursado==0)
             {
             $vistaOC.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
             $OCEst=0;
             }
             if($porcentajeOCCursado==100)
             {
             $vistaOC.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           </table>";
             }
             if($porcentajeOCCursado!=0 AND $porcentajeOCCursado!=100)
             {
             $vistaOC.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeOCCursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalOC=100-$porcentajeOCCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
             }
             $vistaOC.="</td></tr>
                      </table>";



             $vistaEI="<table align='center' width='550%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>EI
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='7%'>".$EI."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='12%'>".$EIEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='13%'>".$FaltanEI=$EI-$EIEst."
                      </td>                      
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>";
           if($porcentajeEICursado==0)
             {
             $vistaEI.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
             $EIEst=0;
             }
             if($porcentajeEICursado==100)
             {
             $vistaEI.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%
                           </td>
                           </table>";
             }
             if($porcentajeEICursado!=0 AND $porcentajeEICursado!=100)
             {
             $vistaEI.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeEICursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalEI=100-$porcentajeEICursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
             }
             $vistaEI.="</td></tr>
                      </table>";
         

             $vistaEE="<table align='center' width='550%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>EE
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='7%'>".$EE."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='12%'>".$EEEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='13%'>".$FaltanEE=$EE-$EEEst."
                      </td>                      
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>";
           if($porcentajeEECursado==0)
             {
             $vistaEE.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
             $EEEst=0;
             }
             if($porcentajeEECursado==100)
             {
             $vistaEE.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           </table>";
             }
             if($porcentajeEECursado!=0 AND $porcentajeEECursado!=100)
             {
             $vistaEE.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeEECursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalEE=100-$porcentajeEECursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
             }
             $vistaEE.="</td></tr>
                      </table>";

             $vistaTotal="<table align='center' width='550%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='7%'>".$totalCreditos."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='12%'>".$totalCreditosEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='13%'>".$Faltan=$totalCreditos-$totalCreditosEst."
                      </td>                      
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='54%'>";
           if($porcentajeCursado==0)
             {
             $vistaTotal.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
             $totalCreditosEst=0;
             }
             if($porcentajeCursado==100)
             {
             $vistaTotal.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%
                           </td>
                           </table>";
             }
             if($porcentajeCursado!=0 AND $porcentajeCursado!=100)
             {
             $vistaTotal.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeCursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$Total=100-$porcentajeCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
             }
             $vistaTotal.="</td></tr>
                      </table>";
          
            }
            else
            {
               $vista="
            <table align='center' width='550%' cellspacing='0' bgcolor='#fffffa'>
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='5'>El Proyecto Curricular no ha definido los rangos de cr&eacute;ditos<br>para el plan de estudios
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='7%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='12%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='13%'>Por Aprobar
                      </td>                      
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>% Cursado
                      </td>
                   </tr>
                   </table>";

               $vistaOB="<table align='center' width='550%' cellspacing='0' cellpadding='2' bgcolor='#fffffa'>
                                <tr>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='7%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='12%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='13%'>-
                                </td>                                
                                <td class='bloquelateralayuda cuadro_plano centrar' bgcolor='#fffcea' width='54%'> 0%
                                </td>
                                </tr>
                             </table>";
               $vistaOC=$vistaOB;
               $vistaEI=$vistaOB;
               $vistaEE=$vistaOB;
               $vistaTotal=$vistaOB;
            }
            return array($vista, $vistaOB, $vistaOC, $vistaEI, $vistaEE, $vistaTotal);

    }

}
?>
