<?
/*
###########################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion 
###########################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      ultima revision 2 de junio de 2007
*****************************************************************************
* @subpackage   admin_recibo
* @package	bloques
* @copyright    
* @version      0.3
* @link		N/D
* @description  Bloque principal para la administracion de medicamentoes
*
******************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//Se incluye para manejar los mensajes de error
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"coordinador");
$accesoOracle1=$conexion->recursodb($configuracion,"autoevaluadoc");
$enlace=$accesoOracle->conectar_db();

$acceso_db=$conexion->recursodb($configuracion,"");
$enlace=$acceso_db->conectar_db();

//Rescatar los datos generales
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
$datoBasico=new datosGenerales();

$valor[0]=isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
$valor[1]=isset($_REQUEST['periodonuevo'])?$_REQUEST['periodonuevo']:'';
$periodoNuevo=explode('-',$valor[1]);

$valor[2]=isset($periodoNuevo[0])?$periodoNuevo[0]:'';
$valor[3]=isset($periodoNuevo[1])?$periodoNuevo[1]:'';

//Reporte de resumen de horarios
if ($_REQUEST['opcion']=='horarios')
{
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"resumenHorarioCurso");

	$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	$total=count($resultado);
	//echo $resultado[0][0]; exit;
	if(!is_array($resultado))
	{	
		
		$cadena="En la actualidad no existen horarios ni cursos para el periodo acad&eacute;mico " .$valor[1]. ".";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=resumenHorarioCurso".$valor[0].".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<table class="sigma_borde centrar" width="100%">
			<tr class="sigma">
				<td class="sigma centrar" colspan="15">
					RES&Uacute;MEN HORARIOS Y CURSOS
				</td>
			</tr>
			
			<tr class="sigma">
				<td>
					A&ntilde;o
				</td>
				<td>
					Periodo
				</td>
				<td>
					Carrera
				</td>
				<td>
					C&oacute;digo asignatura
				</td>
				<td>
					Asignatura
				</td>
				<td>
					Grupo
				</td>
				<td>
					Inscritos
				</td>
				<td>
					Lunes
				</td>
				<td>
					Martes
				</td>
				<td>
					Mi&eacute;rcoles
				</td>
				<td>
					Jueves
				</td>
				<td>
					Viernes
				</td>
				<td>
					S&aacute;bado
				</td>
				<td>
					Domingo
				</td>
				<td>
					Semana
				</td>  
			</tr>
			<?
			for($i=0;$i<=$total;$i++)
			{
			?>
				<tr class="cuadro_color">
					<td>
						<? echo $resultado[$i][0];?>
					</td>
					<td>
						<? echo $resultado[$i][1];?>
					</td>
					<td>
						<? echo $resultado[$i][2];?>
					</td>
					<td>
						<? echo $resultado[$i][3];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][4]);?>
					</td>
					<td>
						<? echo $resultado[$i][5];?>
					</td>
					<td>
						<? echo $resultado[$i][6];?>
					</td>
					<td>
						<? echo $resultado[$i][7];?>
					</td>
					<td>
						<? echo $resultado[$i][8];?>
					</td>
					<td>
						<? echo $resultado[$i][9];?>
					</td>
					<td>
						<? echo $resultado[$i][10];?>
					</td>
					<td>
						<? echo $resultado[$i][11];?>
					</td>
					<td>
						<? echo $resultado[$i][12];?>
					</td>
					<td>
						<? echo $resultado[$i][13];?>
					</td>
					<td>
						<? echo $resultado[$i][14];?>
					</td>
					
				</tr>
			<?
			}
			?>
	</table>
	<?
	}
}

//Reporte de control de notas

$valor[4]=isset($_REQUEST["carrera"])?$_REQUEST["carrera"]:'';
$valor[5]=$_REQUEST["ano"];
$valor[6]=$_REQUEST["periodo"];
$valor[7]=isset($_REQUEST["nomcra"])?$_REQUEST["nomcra"]:'';

if ($_REQUEST['opcion']=='controlNotas')
{
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"controlnotas");

	$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	$total=count($resultado);
	//echo $resultado[0][0]; exit;
	if(!is_array($resultado))
	{	
		
		$cadena="En la actualidad no hay programaci&oacute;n de cursos para el Proyecto Curricular " .$valor[7]. ".";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=listadoControlNotas".$valor[4].".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<table class="sigma_borde centrar" width="100%">
			<tr class="sigma">
				<td class="sigma centrar" colspan="18">
					<center>CONTROL DE NOTAS DE <?echo $valor[7];?> PERIODO ACAD&Eacute;MICO <?echo $valor[5].' - '.$valor[6];?></center>
				</td>
			</tr>
			
			<tr class="sigma">
				<td>
					C&oacute;digo
				</td>
				<td>
					Asignatura
				</td>
				<td>
					Grupo
				</td>
				<td>
					Docente
				</td>
				<td>
					Identificaci&oacute;n
				</td>
				<td>
					Tel&eacute;fono
				</td>
				<td>
					Celular
				</td>
				<td>
					Correo
				</td>
				<td>
					Correo Ins.
				</td>
				<td>
					No. inscritos
				</td>
				<td>
					Par 1
				</td>
				<td>
					Par 2
				</td>
				<td>
					Par 3
				</td>
				<td>
					Par 4
				</td>
				<td>
					Par 5
				</td> 
				<td>
					Par 6
				</td>
				<td>
					Exa
				</td>
				<td>
					Def
				</td>
			</tr>
			<?
			for($i=0;$i<=$total;$i++)
			{
			?>
				<tr class="cuadro_color">
					<td>
						<? echo $resultado[$i][6];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][7]);?>
					</td>
					<td>
						<? echo $resultado[$i][19];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][1]);?>
					</td>
					<td>
						<? echo $resultado[$i][2];?>
					</td>
					<td>
						<? echo $resultado[$i][3];?>
					</td>
					<td>
						<? echo $resultado[$i][4];?>
					</td>
					<td>
						<? echo $resultado[$i][5];?>
					</td>
					<td>
						<? echo $resultado[$i][18];?>
					</td>
					<td>
						<? echo $resultado[$i][9];?>
					</td>
					<td>
						<? echo $resultado[$i][10];?>
					</td>
					<td>
						<? echo $resultado[$i][11];?>
					</td>
					<td>
						<? echo $resultado[$i][12];?>
					</td>
					<td>
						<? echo $resultado[$i][13];?>
					</td>
					<td>
						<? echo $resultado[$i][14];?>
					</td>
					<td>
						<? echo $resultado[$i][15];?>
					</td>
					<td>
						<? echo $resultado[$i][16];?>
					</td>
					<td>
						<? echo $resultado[$i][17];?>
					</td>
				</tr>
			<?
			}
			?>
	</table>
	<?
	}
}

//Reporte observaciones de estudiantes evaluación docente
unset($valor);
$valor[5]=$_REQUEST["ano"];
$valor[6]=$_REQUEST["periodo"];
$valor[7]=$_REQUEST["facultad"];

if ($_REQUEST['opcion']=='observacionesEvaldocentes')
{
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"observacionesEstudiantes");

	$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	$total=count($resultado);
	//echo $resultado[0][0]; exit;
	if(!is_array($resultado))
	{	
		
		$cadena="No hay registros de observaciones realizadas por los estudiantes a los docentes para la evaluaci&oacute;n docente correspondiente al periodo acad&eacute;mico " .$valor[5]. "-".$valor[6].".";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=observacionesEstudiantes".$valor[5]."_".$valor[6].".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<table class="sigma_borde centrar" width="100%">
			<tr class="sigma">
				<td class="sigma centrar" colspan="11">
					<center>OBSERVACIONDE DE ESTUDIANTES EVALUACI&Oacute;N DOCENTE PERIODO ACAD&Eacute;MICO <?echo $valor[5].' - '.$valor[6];?></center>
				</td>
			</tr>
			
			<tr class="sigma">
				<td>
					Cod. Fac.
				</td>
				<td>
					Facultad
				</td>
				<td>
					Cod. Cra
				</td>
				<td>
					Carrera
				</td>
				<td>
					A&ntilde;o
				</td>
				<td>
					Periodo
				</td>
				<td>
					Id docente
				</td>
				<td>
					Docente
				</td>
				<td>
					Observaci&oacute;n
				</td>
				<td>
					Cod. Curso
				</td>
				<td>
					Curso
				</td>  
			</tr>
			<?
			for($i=0;$i<=$total;$i++)
			{
			?>
				<tr class="cuadro_color">
					<td>
						<? echo $resultado[$i][0];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][1]);?>
					</td>
					<td>
						<? echo $resultado[$i][2];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][3]);?>
					</td>
					<td>
						<? echo $resultado[$i][4];?>
					</td>
					<td>
						<? echo $resultado[$i][5];?>
					</td>
					<td>
						<? echo $resultado[$i][6];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][7]);?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][8]);?>
					</td>
					<td>
						<? echo $resultado[$i][9];?>
					</td>
					<td>
						<? echo UTF8_DECODE($resultado[$i][10]);?>
					</td>
				</tr>
			<?
			}
			?>
	</table>
	<?
	}
}

/****************************************************************
*  			Funciones				*
****************************************************************/
function cadena_busqueda_recibo($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{
		case "resumenHorarioCurso":
				//Oracle
				$cadena_sql="SELECT * ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_curso_horario_resumen ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="carrera = ".$valor[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ano = ".$valor[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo = ".$valor[3]." ";
				break;
  
		case "controlnotas":
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="car_doc_nro, ";
				$cadena_sql.="doc_nombre ||' '||doc_apellido, ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_telefono, ";
				$cadena_sql.="doc_celular, ";
				$cadena_sql.="doc_email, ";
				$cadena_sql.="INP_ASI_COD, "; //6
				$cadena_sql.="asi_nombre, ";
				$cadena_sql.="INP_NRO, ";
				$cadena_sql.="INP_NRO_INS, ";
				$cadena_sql.="INP_PAR1, ";
				$cadena_sql.="INP_PAR2, ";
				$cadena_sql.="INP_PAR3, ";
				$cadena_sql.="INP_PAR4, ";
				$cadena_sql.="INP_PAR5, ";
				$cadena_sql.="INP_PAR6, ";
				$cadena_sql.="INP_EXA, ";
				$cadena_sql.="INP_DEF, ";
				$cadena_sql.="doc_email_ins, ";
                                $cadena_sql.="(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)";
				$cadena_sql.="FROM v_acinsnotpar,acasi,accargas,accursos,achorarios,acdocente";
                                $cadena_sql.=" WHERE asi_cod = inp_asi_cod";
                                $cadena_sql.=" AND inp_asi_cod=cur_asi_cod";
                                $cadena_sql.=" AND inp_nro=cur_id";
                                $cadena_sql.=" AND car_doc_nro=doc_nro_iden";
                                $cadena_sql.=" AND cur_cra_cod=inp_cra_cod";
                                $cadena_sql.=" AND hor_id_curso=cur_id";
                                $cadena_sql.=" AND car_hor_id=hor_id";
				$cadena_sql.=" AND cur_ape_ano=".$valor[5]." ";
				$cadena_sql.="AND cur_ape_per=".$valor[6]." ";
				$cadena_sql.="AND inp_cra_cod = ".$valor[4]." ORDER BY 2,3 ";
			      break;

		case "observacionesEstudiantes":
				$cadena_sql="SELECT ";
				$cadena_sql.="dep_cod CodFacultad, ";
				$cadena_sql.="dep_nombre NombreFacultad, ";
				$cadena_sql.="est_cra_cod Codcra, ";
				$cadena_sql.="cra_nombre Carrera, ";
				$cadena_sql.="epe_ape_ano Año, ";
				$cadena_sql.="epe_ape_per Periodo, ";
				$cadena_sql.="epe_doc_nro_iden Docente, ";//6
				$cadena_sql.="doc_nombre||' '||doc_apellido Nombre, ";
				$cadena_sql.="epe_observa Observacion, ";
				$cadena_sql.="epe_cur_asi_cod ,";
				$cadena_sql.="asi_nombre ";    
				$cadena_sql.="FROM ACEVAPROEST, ACEST, ACCRA, GEDEP, ACDOCENTE, ACASI ";
				$cadena_sql.="WHERE epe_est_cod=est_cod ";
				$cadena_sql.="AND est_cra_cod=cra_cod ";
				$cadena_sql.="AND cra_dep_cod=dep_cod ";
				$cadena_sql.="AND ASI_COD = EPE_CUR_ASI_COD ";
				$cadena_sql.="AND doc_nro_iden=epe_doc_nro_iden ";
				$cadena_sql.="AND epe_ape_ano=".$valor[5]." ";
				$cadena_sql.="AND epe_ape_per=".$valor[6]." ";
				$cadena_sql.="AND dep_cod=".$valor[7]." ";
				$cadena_sql.="AND epe_observa NOT IN ('NULL') ";
				$cadena_sql.="ORDER BY 1,3,7";
			      break;

		default:
			$cadena_sql="";
			break;
	}
	//echo $cadena_sql;
	return $cadena_sql;
}

function ejecutar_admin_recibo($cadena_sql,$conexion,$tipo)
{
	//echo $cadena_sql;
	$resultado= $conexion->ejecutarAcceso($cadena_sql,$tipo);
	return $resultado;
}

?>
