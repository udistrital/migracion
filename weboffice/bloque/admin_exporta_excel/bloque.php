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
$accesoOracle=$conexion->recursodb($configuracion,"moodle");
$enlace=$accesoOracle->conectar_db();

$acceso_db=$conexion->recursodb($configuracion,"");
$enlace=$acceso_db->conectar_db();

//Rescatar los datos generales
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
$datoBasico=new datosGenerales();

if($_REQUEST["craCod"]){
$valor[0]=$_REQUEST["craCod"];
$valor[1]=$_REQUEST["asiCod"];
$valor[2]=$_REQUEST["grupo"];

$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"inscritos");
$resultado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
$total=count($resultado);

if(!is_array($resultado))
{	
	
	$cadena="<p>En la actualidad no existen inscritos en este grupo.</p>";
	alerta::sin_registro($configuracion,$cadena);	
}
else
{
	$nombreEspacio=str_replace(" ", "_", $resultado[0][7]);
        header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=listado_Inscripcion_".$nombreEspacio.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo "<table class='formulario' align='center'>
			<tr  class='bloquecentralencabezado'>
					<td colspan='4'>
						
					</td>
				</tr>
			<tr  class='bloquecentralencabezado'>
					<td colspan='5' align='center'>
						<p><h2>Listado de inscritos</h2></p>
					</td>
				</tr>
			<tr  class='bloquecentralencabezado'>
					<td colspan='5' align='center'>
						<p>Carrera: ".$resultado[0][4]."</p>
					</td>
				</tr>
			<tr  class='bloquecentralencabezado'>
					<td colspan='5' align='center'>
						<p>Asignatura: ".$resultado[0][5]." - ".htmlentities($resultado[0][7])."</p>
					</td>
				</tr>
			<tr  class='bloquecentralencabezado'>
					<td colspan='5' align='center'>
						<p>Grupo: ".$resultado[0][6]."</p>
					</td>
				</tr>
			<tr class='cuadro_color'>
				<th class='cuadro_plano centrar''>
					No
				</th>
				<th class='cuadro_plano centrar''>
					C&oacute;digo
				</th>
				<th class='cuadro_plano centrar''>
					Doc. Identidad
				</th>
				<th class='cuadro_plano centrar''>
					Nombre
				</th>
				<th class='cuadro_plano centrar''>
					Apellido
				</th>
				<th class='cuadro_plano centrar''>
					E-mail
				</th>

			</tr>";
			
			for($i=0;$i<$total;$i++)
			{	$j=$i+1;
				echo "<tr>";
				echo "<td class='cuadro_plano centrar'>".$j."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
				echo "<td class='cuadro_plano centrar'>".htmlentities($resultado[$i][1])."</td>";
				echo "<td class='cuadro_plano centrar'>".htmlentities($resultado[$i][2])."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
				
				
			echo "</tr>";	
				
			}
	echo "</table>";
    }
}
else{
    $valor=$_REQUEST['asiCod'];

    $cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"inscritosAsig");
    $resultadoIns=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
    $total=count($resultadoIns);

    	$nombreEspacio=str_replace(" ", "_", $resultadoIns[0][5]);
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=listado_Inscripcion_".$nombreEspacio.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo "<table class='formulario' align='center'>
			<tr  class='bloquecentralencabezado'>
					<td colspan='4'>

					</td>
				</tr>
			<tr  class='bloquecentralencabezado'>
					<td colspan='5' align='center'>
						<p><h2>Listado de inscritos</h2></p>
					</td>
				</tr>
			
			<tr  class='bloquecentralencabezado'>
					<td colspan='7' align='center'>
						<p>Asignatura: ".$valor." - ".htmlentities($resultadoIns[0][5])."</p>
					</td>
				</tr>
			<tr class='cuadro_color'>
				<th class='cuadro_plano centrar''>
					No
				</th>
				<th class='cuadro_plano centrar''>
					C&oacute;digo
				</th>
				<th class='cuadro_plano centrar''>
					Doc. Identidad
				</th>
				<th class='cuadro_plano centrar''>
					Nombre
				</th>
				<th class='cuadro_plano centrar''>
					Apellido
				</th>
				<th class='cuadro_plano centrar''>
					Carrera
				</th>
				<th class='cuadro_plano centrar''>
					E-mail
				</th>
				<th class='cuadro_plano centrar''>
					Grupo
				</th>

			</tr>";

			for($i=0;$i<$total;$i++)
			{	$j=$i+1;
				echo "<tr>";
				echo "<td class='cuadro_plano centrar'>".$j."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultadoIns[$i][0]."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultadoIns[$i][7]."</td>";
				echo "<td class='cuadro_plano centrar'>".htmlentities($resultadoIns[$i][1])."</td>";
				echo "<td class='cuadro_plano centrar'>".htmlentities($resultadoIns[$i][2])."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultadoIns[$i][4]."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultadoIns[$i][3]."</td>";
				echo "<td class='cuadro_plano centrar'>".$resultadoIns[$i][6]."</td>";

			echo "</tr>";

			}
	echo "</table>";
    

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
				$cadena_sql="SELECT est_cod,";
                                $cadena_sql.=" substr(EST_NOMBRE,INSTR(EST_NOMBRE,' ',1,2)+1),";
                                $cadena_sql.=" substr(EST_NOMBRE,1,INSTR(EST_NOMBRE,' ',1,2)-1),";
                                $cadena_sql.=" eot_email_ins,";
                                $cadena_sql.=" cra_nombre,";
                                $cadena_sql.=" asi_cod,";
                                $cadena_sql.=" cur_grupo,";
                                $cadena_sql.=" asi_nombre,";
                                $cadena_sql.=" est_nro_iden";
                                $cadena_sql.=" FROM acins";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" INNER JOIN acest ON est_cod=ins_est_cod";
                                $cadena_sql.=" INNER JOIN acestotr ON eot_cod=est_cod";
                                $cadena_sql.=" INNER JOIN accra ON cra_cod = est_cra_cod";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_asi_cod=ins_asi_cod";
                                $cadena_sql.=" AND cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" where ape_estado = 'A'";
                                $cadena_sql.=" AND asi_ind_catedra = 'S'";
                                $cadena_sql.=" AND ins_estado = 'A'";
                                $cadena_sql.=" AND ins_cra_cod='".$valor[0]."' ";
				$cadena_sql.=" AND ins_asi_cod='".$valor[1]."' ";
				$cadena_sql.=" AND cur_id='".$valor[2]."' ";
				$cadena_sql.=" ORDER BY asi_cod";
				break;

		case "inscritosAsig":
				//Oracle
				$cadena_sql="SELECT est_cod,";
                                $cadena_sql.=" substr(EST_NOMBRE,INSTR(EST_NOMBRE,' ',1,2)+1),";
                                $cadena_sql.=" substr(EST_NOMBRE,1,INSTR(EST_NOMBRE,' ',1,2)-1),";
                                $cadena_sql.=" eot_email_ins,";
                                $cadena_sql.=" cra_nombre,";
                                $cadena_sql.=" asi_nombre,";
                                $cadena_sql.=" cur_grupo,";
                                $cadena_sql.=" est_nro_iden";
                                $cadena_sql.=" FROM acins";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" INNER JOIN acest ON est_cod=ins_est_cod";
                                $cadena_sql.=" INNER JOIN acestotr ON eot_cod=est_cod";
                                $cadena_sql.=" INNER JOIN accra ON cra_cod = est_cra_cod";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_asi_cod=ins_asi_cod";
                                $cadena_sql.=" AND cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" where ape_estado = 'A'";
                                $cadena_sql.=" AND asi_ind_catedra = 'S'";
                                $cadena_sql.=" AND ins_estado = 'A'";
                                $cadena_sql.=" AND ins_asi_cod='".$valor."'";
				$cadena_sql.=" order by est_cod ";
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
