<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2007                                      #
#    paulo_cesar@udistrital.edu.co                                         #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
?>
<?
/****************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 5 de Noviembre de 2009

******************************************************************************
* @subpackage   preinscripcion
* @package	bloques
* @copyright    
* @version      0.1
* @author      	Milton Parra
* @link		N/D
* @description  Menu para revisar adiciones.
* @usage        
*******************************************************************************/
?><?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/revisarAdicion.class.php");
class agregarHorario
{
	//@Constructor
	function __construct()
	{
		    $this->horario = new funcionGeneral();
                    $this->revisar = new revisarAdicion;
                    
	
	}
	
	
	//
	function asignarCupo($configuracion, $conexionGestion, $conexionOracle, $cantidadCupos, $criterios)
	{
            //$cantidadCupos trae 0:cupo  1:cod EA  2:periodo EA  3:grupo 4:cupos disponibles
            //$criterios trae 0:cra  1:planest  2:año  3:período.
                    //busca asignar cupo a cada estudiante dependiendo de disponibilidad
                    //$criterios[2]=2009;//quitar para produccion
                    //$criterios[3]=3;//quitar para produccion

                    $v=0;
                    while($cantidadCupos[$v][1])
                    {
                        //actualiza el estado de los horarios registrados provisionales, dependiendo del cupo
                        $varactual[0]=$cantidadCupos[$v][1];//cod EA
                        $varactual[1]=$cantidadCupos[$v][3];//cod grupo
                        $varactual[2]=$criterios[0];//proyecto
                        $varactual[3]=$criterios[1];//planest
                        $varactual[4]=$criterios[2];//año
                        $varactual[5]=$criterios[3];//periodo
                        $varactual[6]=$cantidadCupos[$v][4];//disponibles
                        //echo "<br>".$varactual[4]."<br>";
                        $cadena_cambiarEstado=$this->cadena_agregarHorario($configuracion, "actualizarEstado", $varactual);
                        for($m=0; $m<$cantidadCupos[$v][4]; $m++)
                        {
                            $resultado_cambiarEstado=$this->horario->ejecutarSQL($configuracion, $conexionGestion, $cadena_cambiarEstado, "");
                            if($resultado_cambiarEstado)
                            {
                                $varactual[6]--;
                            }
                            else
                            {
                                $m=$cantidadCupos[$v][4];
                            }

                        }
                        $v++;
                    }
                    $c=0;
                    while($cantidadCupos[$c][1])
                    {
                        //busca la cantidad de cupos libres después de haber asignado inicialmente
                        $cuposparam[0]=$cantidadCupos[$c][1];
                        $cuposparam[1]=$cantidadCupos[$c][3];
                        $cuposparam[2]=$varactual[2];
                        $cuposparam[3]=$varactual[3];
                        $cuposparam[4]=$varactual[4];
                        $cuposparam[5]=$varactual[5];

                        $cadena_cantidadCupos=$this->cadena_agregarHorario($configuracion, "buscarCantidadCupos",$cuposparam);
                        $resultado_cantidadCupos=$this->horario->ejecutarSQL($configuracion, $conexionGestion, $cadena_cantidadCupos, "busqueda");
                        $cantidadCupos[$c][7]=$cantidadCupos[$c][4]-$resultado_cantidadCupos[0][0];
                        //echo $cuposparam[0]." - ".$cuposparam[1]." - ".$cantidadCupos[$c][0]."<br>";
                        $c++;
                    }

                    //buscar estudiantes con EA no asignados provisional
                    $varactual[7]="!= 3";//no asignados
                    $buscarSinCupos=$this->cadena_agregarHorario($configuracion, "buscarNoCupos", $varactual);
                    $resultado_sinCupos=$this->horario->ejecutarSQL($configuracion, $conexionGestion, $buscarSinCupos, "busqueda");
                    
                    //echo "<br>";

                    if($resultado_sinCupos)
                    {
                        //buscar EA registrados de cada estudiante que ya tengan asignados horarios provisionales
                        $veri=0;
                        while($resultado_sinCupos[$veri][0])
                        {
                            $varactual[7]="= 3";//asignados
                            $varactual[6]=$resultado_sinCupos[$veri][0];
                            $buscarOtrosAsignados=$this->cadena_agregarHorario($configuracion, "buscarOtrosAsignados", $varactual);
                            $resultado_otrosAsignados=$this->horario->ejecutarSQL($configuracion, $conexionGestion, $buscarOtrosAsignados, "busqueda");
                            if (!$resultado_otrosAsignados)
                            {
                                $resultado_otrosAsignados[0][0]=0;
                                $resultado_otrosAsignados[0][1]=0;
                            }
                                //revisa si hay cruce con otros EA registrados
                                $cruceOtros=$this->revisar->verificarOtroCruce($configuracion, $conexionGestion, $conexionOracle, $resultado_sinCupos[$veri], $resultado_otrosAsignados, $criterios);
//                            else
//                            {
//                                //si no hay más EA registrados
//                                echo "<br>".$resultado_sinCupos[$veri][0]." no tiene más EA registrados<br>";
//                            }
                            //busca cupos disponibles de cada EA

                            $varactual[0]=$resultado_sinCupos[$veri][1];//cod EA
                            $varactual[1]=$cruceOtros;//grupo
                            //cambia grupo mientras haya disponibilidad
                            $mov=0;
                            while($cantidadCupos[$mov][1])
                            {
                                //echo "+++++Cupos++++++".$cantidadCupos[$mov][1]." ".$cantidadCupos[$mov][3]. " ". $cantidadCupos[$mov][0]."<br>";
                                if (($cantidadCupos[$mov][1]==$varactual[0]) && ($cantidadCupos[$mov][3]==$varactual[1]) && ($cantidadCupos[$mov][7]>0))
                                {

                                    //to do si el grupo del ea ya no tiene cupo debe ir a buscar otro grupo y revisar cruce. Cambia grupo pero no estado
                                    $cadena_insertarNuevoGrupo=$this->cadena_agregarHorario($configuracion, "actualizarEstadoNuevoGrupo", $varactual);
                                    //echo $cadena_insertarNuevoGrupo;
                                    $resultado_insertarNuevoGrupo=$this->horario->ejecutarSQL($configuracion, $conexionGestion, $cadena_insertarNuevoGrupo, "");
                                    $cantidadCupos[$mov][7]--;
                                    //echo $cantidadCupos[$mov][1]." quedan ".$cantidadCupos[$mov][0]."<br>";
                                    //exit;
                                    break;

                                }
                                elseif (($cantidadCupos[$mov][1]==$varactual[0]) && ($cantidadCupos[$mov][3]==$varactual[1]) && ($cantidadCupos[$mov][7]==0))
                                {
                                    $resultado_sinCupos[$veri][2]=$varactual[1];//cod grupo
                                    $buscarOtrosAsignados=$this->cadena_agregarHorario($configuracion, "buscarOtrosAsignados", $varactual);
                                    $resultado_otrosAsignados=$this->horario->ejecutarSQL($configuracion, $conexionGestion, $buscarOtrosAsignados, "busqueda");
                                    $varactual[1]=$this->revisar->verificarOtroCruce($configuracion, $conexionGestion, $conexionOracle, $resultado_sinCupos[$veri], $resultado_otrosAsignados, $criterios);
                                }
                                else
                                {
                                    $mov++;
                                }
                            }
                            $veri++;
                        }
                        
                    }
                    else
                    {
                        //echo "<br>No hay espacios libres";
                        //exit;
                    }
                    //retorna la cantidad de cupos disponibles para cada ea y grupo
                        
                return $cantidadCupos;
                //exit;
	
	}
	
	

	//function cadena_agregarHorario($tipo,$configuracion,$dia,$mes,$anno="")
	
	function cadena_agregarHorario($configuracion, $tipo, $variable)
	{
//		if ($anno=="")
//		{
//			$anno=date("Y",time());
//		}
		switch($tipo)
		{
			
			case "actualizarEstado":

                                $cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
				$cadena_sql.="SET ";
				$cadena_sql.="horario_estado = '3' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="horario_idEspacio = ";
				$cadena_sql.="'".$variable[0]."' ";//cod EA
                                $cadena_sql.="AND ";
                                $cadena_sql.="horario_grupo = ";
                                $cadena_sql.="'".$variable[1]."' ";//cod grupo
				$cadena_sql.="AND ";
				$cadena_sql.="horario_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[2]."' ";//proyecto
				$cadena_sql.="AND ";
				$cadena_sql.="horario_idPlanEstudio = ";
				$cadena_sql.="'".$variable[3]."' ";//planest
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
				$cadena_sql.="'".$variable[4]."' ";//ano
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
				$cadena_sql.="'".$variable[5]."' ";//periodo
				$cadena_sql.="AND ";
				$cadena_sql.="horario_estado = '' ";
				$cadena_sql.="LIMIT 1";
				//$cadena_sql.="";
                                break;
				
			case "actualizarEstadoNuevoGrupo":

                                $cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
				$cadena_sql.="SET ";
				$cadena_sql.="horario_grupo = ";
                                $cadena_sql.="'".$variable[1]."', ";//cod grupo
				$cadena_sql.="horario_estado = '3' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="horario_codEstudiante = ";
				$cadena_sql.="'".$variable[6]."' ";//cod estudiante
                                $cadena_sql.="AND ";
                                $cadena_sql.="horario_idEspacio = ";
				$cadena_sql.="'".$variable[0]."' ";//cod EA
                                $cadena_sql.="AND ";
				$cadena_sql.="horario_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[2]."' ";//cra_planest
                                $cadena_sql.="AND ";
				$cadena_sql.="horario_idPlanEstudio = ";
				$cadena_sql.="'".$variable[3]."' ";//cra_planest
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
				$cadena_sql.="'".$variable[4]."' ";//año
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
				$cadena_sql.="'".$variable[5]."'";//preriodo
				//$cadena_sql.="";
                                break;


			case "buscar":
				$cadena_sql="SELECT ";
				$cadena_sql.="ASI_NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASI ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ASI_COD = ";
				$cadena_sql.="'".$variable."'";//observaciones
				//$cadena_sql.=" ";
                                break;

                        case "buscarNoCupos":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="horario_codEstudiante, ";
                                $cadena_sql.="horario_idEspacio, ";
                                $cadena_sql.="horario_grupo ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[2]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable[3]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable[5]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_estado ";
                                $cadena_sql.=$variable[7];//no asignados

                                //$cadena_sql.=" ";
                                break;

                        case "buscarOtrosAsignados":
                                $cadena_sql="SELECT ";
                                //$cadena_sql.="horario_cod_estudiante, ";
                                $cadena_sql.="horario_idEspacio, ";
                                $cadena_sql.="horario_grupo ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_codEstudiante = ";
                                $cadena_sql.="'".$variable[6]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[2]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable[3]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable[5]."' ";
                                $cadena_sql.="and ";
                                $cadena_sql.="horario_estado ";
                                $cadena_sql.=$variable[7];//no asignados

                                //$cadena_sql.=" ";
                                break;


                        case "buscarCuposEA":
                        //ORACLE
                                $cadena_sql="SELECT ";
				$cadena_sql.="CUR_NRO_CUPO, ";
				$cadena_sql.="CUR_NRO_INS, ";
				$cadena_sql.="CUR_SEMESTRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCURSO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CUR_APE_ANO = ";
				$cadena_sql.="'".$variable[2]."' ";//Año
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_APE_PER = ";
				$cadena_sql.="'".$variable[3]."' ";//Período
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_ASI_COD = ";
				$cadena_sql.="'".$variable[4]."' ";//Cod EA
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_NRO = ";
				$cadena_sql.="'".$variable[5]."' ";//Grupo
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_CRA_COD = ";
				$cadena_sql.="'".$variable[0]."'";//Cra
				//$cadena_sql.=" ";
                                break;

                        case "buscarCantidadCupos":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="count(*) ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                                $cadena_sql.="WHERE ";
				$cadena_sql.="horario_idEspacio = ";
				$cadena_sql.="'".$variable[0]."' ";//cod EA
                                $cadena_sql.="AND ";
                                $cadena_sql.="horario_grupo = ";
                                $cadena_sql.="'".$variable[1]."' ";//cod grupo
				$cadena_sql.="AND ";
				$cadena_sql.="horario_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[2]."' ";//proyecto
				$cadena_sql.="AND ";
				$cadena_sql.="horario_idPlanEstudio = ";
				$cadena_sql.="'".$variable[3]."' ";//planest
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
				$cadena_sql.="'".$variable[4]."' ";//ano
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
				$cadena_sql.="'".$variable[5]."' ";//periodo
				$cadena_sql.="AND ";
				$cadena_sql.="horario_estado = '3'";
//				$cadena_sql.=" ";
//                                $cadena_sql.=" ";
//                                $cadena_sql.=" ";
                                break;
				
			default:
				break;		
		}	
		return $cadena_sql;
	}
}

?>