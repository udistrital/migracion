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
$accesoOracle=$conexion->recursodb($configuracion,"secretarioacad");
$enlace=$accesoOracle->conectar_db();

$acceso_db=$conexion->recursodb($configuracion,"");
$enlace=$acceso_db->conectar_db();

//Rescatar los datos generales
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
$datoBasico=new datosGenerales();

$valor[0]=$_REQUEST["usuario"];

if ($_REQUEST['opcion']=='acuerdo')
{
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"inscritosAcuerdo004");

	$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	$total=count($resultado);
	//echo $resultado[0][0]; exit;
	if(!is_array($resultado))
	{	
		
		$cadena="<p>En la actualidad no no existen inscritos para acogerse al acuerdo 004 de 2011.</p>";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=listadoInscripcionGrados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
                ini_set('display_errors','off');
		echo "<table class='formulario' align='center'>
				<tr  class='bloquecentralencabezado'>
						<td colspan='3'>
							
						</td>
					</tr>
				<tr  class='bloquecentralencabezado'>
						<td colspan='3' align='center'>
							<p><h2>Listado de inscritos acuerdo 004 de 2011</h2></p>
						</td>
					</tr>
				<tr class='cuadro_color'>
					<th class='cuadro_plano centrar''>
						Codigo estudiante
					</th>
					<th class='cuadro_plano centrar''>
						Nombre del estudiante
					</th>
					<th class='cuadro_plano centrar''>
						Fecha de solicitud
					</th>
				</tr>";
				
				for($i=0;$i<$total;$i++)
				{	
					echo "<tr>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
					echo "<td class='cuadro_plano centrar'>";
					//$variable="pagina=adminInscritoGrado";
					//$variable.="&opcion=formularioInscripcion";
					//$variable.="&no_pagina=true";
					//$variable.="&codigo=".$resultado[$i][1]."";
					//$variable=$cripto->codificar_url($variable,$configuracion);
					//echo $indice.$variable."'";
					//echo "target='_blank'";
					echo  $resultado[$i][1];
					echo "</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
					echo "</td>";
				
				echo "</tr>";	
					
				}
		echo "</table>";
	}
}

if ($_REQUEST['opcion']=='imprimir')
{
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"inscritos");

	$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	$total=count($resultado);
	//echo $resultado[0][0]; exit;
	if(!is_array($resultado))
	{	
		
		$cadena="<p>En la actualidad no no existen inscritos para esta ceremonia de grado.</p>";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=listadoInscripcionGrados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo "<table class='formulario' align='center'>
				<tr  class='bloquecentralencabezado'>
						<td colspan='16'>
							
						</td>
					</tr>
				<tr  class='bloquecentralencabezado'>
						<td colspan='16' align='center'>
							<p><h2>Listado de inscritos a GRADOS</h2></p>
						</td>
					</tr>
				<tr class='cuadro_color'>
					<th class='cuadro_plano centrar''>
						Id
					</th>
					<th class='cuadro_plano centrar''>
						C&oacute;digo
					</th>
					<td class='cuadro_plano centrar''>
						Nombre
					</th>
					</th>
					<th class='cuadro_plano centrar''>
						Apellido
					</th>
					<th class='cuadro_plano centrar''>
						Identificaci&oacute;n
					</th>
					<th class='cuadro_plano centrar''>
						Tipo de identificaci&oacute;n
					</th>
					<th class='cuadro_plano centrar''>
						Lugar de expedici&oacute;n
					</th>
					<th class='cuadro_plano centrar''>
						Proyecto
					</th>
					<th class='cuadro_plano centrar''>
						Trabajo de Grado
					</th>
					</th>
					<th class='cuadro_plano centrar''>
						Director
					</th>
					<th class='cuadro_plano centrar''>
						No. de acta de sustentaci&oacute;n
					</th>
					<th class='cuadro_plano centrar''>
						Direcci&oacute;n
					</th>
					</th>
					<th class='cuadro_plano centrar''>
						Tel&eacute;fono
					</th>
					<th class='cuadro_plano centrar''>
						Celular
					</th>
					<th class='cuadro_plano centrar''>
						Correo
					</th>
					<th class='cuadro_plano centrar''>
						Sexo
					</th>
				</tr>";
				
				for($i=0;$i<$total;$i++)
				{	
					echo "<tr>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
					echo "<td class='cuadro_plano centrar'>";
					//$variable="pagina=adminInscritoGrado";
					//$variable.="&opcion=formularioInscripcion";
					//$variable.="&no_pagina=true";
					//$variable.="&codigo=".$resultado[$i][1]."";
					//$variable=$cripto->codificar_url($variable,$configuracion);
					//echo $indice.$variable."'";
					//echo "target='_blank'";
					echo  $resultado[$i][1];
					echo "</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][2])."</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][3])."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][6])."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][8])."</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][9])."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][10]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][11]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][12]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][13]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][14]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][15]."</td>";
					echo "</td>";
				
				echo "</tr>";	
					
				}
		echo "</table>";
	}
}
if($_REQUEST['opcion']=='promedioEgresado')
{
	$valor[0]=$_REQUEST["usuario"];
	$valor[1]=$_REQUEST["proyecto"];
	$valor[2]=$_REQUEST["fecha"];
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"PromEgresados");

	$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	$total=count($resultado);
	//echo $resultado[0][0]; exit;
	if(!is_array($resultado))
	{	
		
		$cadena="<p>En la actualidad no no existen inscritos para esta ceremonia de grado.</p>";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=reportePomedioEgresados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo "<table class='formulario' align='center'>
				<tr  class='bloquecentralencabezado'>
						<td colspan='6'>
							
						</td>
					</tr>
				<tr  class='bloquecentralencabezado'>
						<td colspan='6' align='center'>
							<p><h2>REPORTE PROMEDIOS DE EGRESADOS</h2></p>
						</td>
					</tr>
				<tr class='cuadro_color'>
					<th class='cuadro_plano centrar''>
						C&oacute;d. Carrera
					</th>
					<th class='cuadro_plano centrar''>
						Nombre Carrera
					</th>
					<th class='cuadro_plano centrar''>
						Fecha de Grado
					</th>
					</th>
					<th class='cuadro_plano centrar''>
						C&oacute;digo Estudiante
					</th>
					<th class='cuadro_plano centrar''>
						Nombre Estudiante
					</th>
					<th class='cuadro_plano centrar''>
						Promedio
					</th>
				</tr>";
				
				for($i=0;$i<$total;$i++)
				{	
					echo "<tr>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][1])."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
					echo "<td class='cuadro_plano centrar'>".UTF8_DECODE($resultado[$i][4])."</td>";
					echo "<td class='cuadro_plano centrar'>".number_format($resultado[$i][5], 2, ',', '')."</td>";
					echo "</td>";
				
				echo "</tr>";	
					
				}
		echo "</table>";
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
	
		case "inscritos":
				//Oracle
				$cadena_sql="SELECT row_number() OVER (ORDER BY codigo) AS ROWNUM, ";
				$cadena_sql.="codigo, ";
				$cadena_sql.="nombre, ";
				$cadena_sql.="apellido, ";
				$cadena_sql.="cedula, ";
				$cadena_sql.="tipo, ";
				$cadena_sql.="municipio, ";
				$cadena_sql.="carrera, ";
				$cadena_sql.="trabajo, ";
				$cadena_sql.="director, ";
				$cadena_sql.="acta, ";				
				$cadena_sql.="direccion, ";
				$cadena_sql.="telefono, ";
				$cadena_sql.="celular, ";
				$cadena_sql.="mail, ";
				$cadena_sql.="sexo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="( "; 
				$cadena_sql.="SELECT est_cod codigo, ";
				$cadena_sql.="	INITCAP(LOWER(SUBSTR(est_nombre,INSTR(est_nombre,' ',1,2)+1))) nombre, ";
				$cadena_sql.="	INITCAP(LOWER(SUBSTR(est_nombre,1,INSTR(est_nombre,' ',1,2)-1))) apellido, ";
				$cadena_sql.="	est_nro_iden cedula, ";
				$cadena_sql.="	est_tipo_iden tipo, ";
				$cadena_sql.="	(SELECT mun_nombre ";
				$cadena_sql.="	FROM acestotr, gemunicipio ";
				$cadena_sql.="	WHERE acest.est_cod = eot_cod ";
				$cadena_sql.="	AND eot_cod_mun_exp = mun_cod) municipio, ";
				$cadena_sql.="	cra_nombre carrera, ";
				$cadena_sql.="	ing_nom_trabajo trabajo, ";
				$cadena_sql.="  dir_nombre ||' '|| dir_apellido director, ";
				$cadena_sql.="	ing_acta acta, ";
				$cadena_sql.="  est_direccion direccion, ";
				$cadena_sql.="  est_telefono telefono, ";
				$cadena_sql.="  eot_tel_cel celular, ";
				$cadena_sql.="  eot_email mail, ";
				$cadena_sql.="  CASE WHEN est_sexo::text='M' THEN 'Masculino' WHEN est_sexo::text='F'  THEN 'Femenino' END sexo ";
				$cadena_sql.="FROM acasperi, gedep, accra, acsecretario, peemp, acinsgrado, acest, acdirectorgrado, acestotr ";
				$cadena_sql.="WHERE ape_estado = 'A' ";
				$cadena_sql.="	AND dep_cod = cra_dep_cod ";
				$cadena_sql.="	AND dep_cod = sec_dep_cod ";
				$cadena_sql.="	AND sec_cod = emp_cod ";
				$cadena_sql.="	AND emp_nro_iden =".$valor[0]." ";
				$cadena_sql.="	AND sec_estado = 'A' ";
				$cadena_sql.="	AND cra_estado = 'A' ";
				$cadena_sql.=" 	AND cra_cod = ing_cra_cod ";
				$cadena_sql.="	AND ape_ano = ing_ano ";
				$cadena_sql.="	AND ape_per = ing_per ";
				$cadena_sql.="	AND ing_estado = 'A' ";
				$cadena_sql.="	AND ing_cra_cod = est_cra_cod ";
				$cadena_sql.="	AND ing_est_cod = est_cod ";
				$cadena_sql.="  AND dir_nro_iden=ing_director ";
				$cadena_sql.="  AND eot_cod=est_cod "; 
				$cadena_sql.="ORDER BY est_cod ASC ";
				$cadena_sql.=") as temporal";
				break;
		case "PromEgresados":
				//Oracle
				$cadena_sql="SELECT CRA_COD CODIGO_CARRERA, ";
				$cadena_sql.="CRA_NOMBRE CARRERA, ";
				$cadena_sql.="TO_CHAR(EGR_FECHA_GRADO, 'DD/MM/YYYY') FECHA_GRADO, ";
				$cadena_sql.="EST_COD CODIGO, ";
				$cadena_sql.="EST_NOMBRE NOMBRE, ";
				$cadena_sql.="FA_PROMEDIO_NOTA(EST_COD) PROMEDIO ";
				$cadena_sql.="FROM PEEMP, ACSECRETARIO, ACCRA, ACEGRESADO, ACEST ";
				$cadena_sql.="WHERE  EMP_NRO_IDEN = ".$valor[0]." ";
				$cadena_sql.="	AND cra_cod = ".$valor[1]." ";
				$cadena_sql.="AND EMP_COD = SEC_COD ";
				$cadena_sql.="AND SEC_ESTADO = 'A' ";
				$cadena_sql.="AND SEC_DEP_COD = CRA_DEP_COD ";
				$cadena_sql.="AND TO_CHAR(EGR_FECHA_GRADO, 'DD/MM/YYYY') = '".$valor[2]."' ";
				$cadena_sql.="AND CRA_COD = EGR_CRA_COD ";
				$cadena_sql.="AND EGR_FECHA_GRADO IS NOT NULL ";
				$cadena_sql.="AND EGR_CRA_COD = EST_CRA_COD ";
				$cadena_sql.="AND EGR_EST_COD = EST_COD ";
				$cadena_sql.="order BY CRA_COD asc, ";
				$cadena_sql.="FA_PROMEDIO_NOTA(EST_COD) desc, ";
				$cadena_sql.="EST_COD asc";
				break;
		case "inscritosAcuerdo004":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="acc_est_cod, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="acc_fecha_colicitud ";
				$cadena_sql.="FROM acacuerdo, accra, acest ";
				$cadena_sql.="WHERE acc_cra_cod  = cra_cod ";
				$cadena_sql.="	AND cra_emp_nro_iden =".$valor[0]." ";
				$cadena_sql.="	AND acc_est_cod=est_cod ";
				$cadena_sql.="ORDER BY acc_est_cod ASC ";
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
