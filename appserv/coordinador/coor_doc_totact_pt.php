<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

/*$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");*/

fu_tipo_user(4);

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

	
	
	echo '<HTML>';
	echo '<HEAD><TITLE>Coordinador</TITLE>';
	echo '<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">';
	echo '<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">';
	echo '<link href="../script/estilo.css" rel="stylesheet" type="text/css">';
	echo '<script language="JavaScript" src="../script/BorraLink.js"></script> ';
	echo '</HEAD>';
	echo '<BODY><BR><BR>';



	$qry_cra = "SELECT cra_cod, cra_abrev
		FROM accra
		WHERE cra_emp_nro_iden = ".$_SESSION["usuario_login"]."
		AND cra_estado = 'A'
		ORDER BY cra_cod ASC";
	
	$row_cra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cra,"busqueda");

	echo '<div align="center">';
	echo '<form name="LisCra" method="post" action="'.$_SERVER['PHP_SELF'].'">';
	echo '<br><select size="1" name="cracod">';
	echo '<option value="" selected>Seleccione el Proyecto Curricular</option>';


$cracod = $row_cra[0][0];

$i=0;
while(isset($row_cra[$i][0]))
{
	echo'<option value="'.$row_cra[$i][0].'">'.$row_cra[$i][0].'--'.$row_cra[$i][1].'</option>\n';
$i++;
}
   
echo'</select>';


echo '<br><INPUT TYPE="Submit" VALUE="Consultar" style="cursor:pointer" title="Ejecutar la Consulta">';
echo '</form></div>';



	include_once(dir_script.'class_nombres.php');
	$NomCra = new Nombres;



	if($_REQUEST['cracod']){
		$QryDocPtsn = "SELECT ape_ano anno,
				  ape_per periodo,
				  cra_cod cod_cra,
				  cra_nombre carrera,
				  'PLANTA' tipo,
				  1 cod_actividad,
				  'CARGA LECTIVA' actividad,
				  SUM((SELECT COUNT(hor_asi_cod)
				       FROM accarga, accurso, achorario
				       WHERE acasperi.ape_ano = car_ape_ano
					  AND acasperi.ape_per = car_ape_per
					  AND accra.cra_cod = car_cra_cod
					  AND acdocente.doc_nro_iden = car_doc_nro_iden
					  AND car_estado = 'A'
					  AND car_ape_ano = cur_ape_ano
					  AND car_ape_per = cur_ape_per
					  AND car_cur_asi_cod = cur_asi_cod
					  AND car_cur_nro = cur_nro
					  AND car_cra_cod = cur_cra_cod
					  AND cur_estado = 'A'
					  AND cur_ape_ano = hor_ape_ano
					  AND cur_ape_per = hor_ape_per
					  AND cur_asi_cod = hor_asi_cod
					  AND cur_nro = hor_nro
					  AND hor_estado = 'A')) total
				FROM acasperi, gedep, accra, acdocente, acdoctipvin
				WHERE ape_estado = 'A'
				  AND dep_cod = cra_dep_cod
				  AND cra_cod = ".$_REQUEST['cracod']."
				  AND ape_ano = dtv_ape_ano
				  AND ape_per = dtv_ape_per
				  AND cra_cod = dtv_cra_cod
				  AND doc_nro_iden = dtv_doc_nro_iden
				  AND dtv_tvi_cod IN (1,6)
				  AND EXISTS (SELECT car_doc_nro_iden
					      FROM accarga
					      WHERE acasperi.ape_ano = car_ape_ano
						 AND acasperi.ape_per = car_ape_per
						 AND accra.cra_cod = car_cra_cod
						 AND acdocente.doc_nro_iden = car_doc_nro_iden
						 AND car_estado = 'A')
				GROUP BY ape_ano,
				  ape_per,
				  cra_cod,
				  cra_nombre
				UNION
				SELECT ape_ano anno,
				  ape_per periodo,
				  cra_cod cod_cra,
				  cra_nombre carrera,
				  'VINESPECIAL' tipo,
				  1 cod_actividad,
				  'CARGA LECTIVA' actividad,
				  SUM((SELECT COUNT(hor_asi_cod)
				       FROM accarga, accurso, achorario
				       WHERE acasperi.ape_ano = car_ape_ano
					  AND acasperi.ape_per = car_ape_per
					  AND accra.cra_cod = car_cra_cod
					  AND acdocente.doc_nro_iden = car_doc_nro_iden
					  AND car_estado = 'A'
					  AND car_ape_ano = cur_ape_ano
					  AND car_ape_per = cur_ape_per
					  AND car_cur_asi_cod = cur_asi_cod
					  AND car_cur_nro = cur_nro
					  AND car_cra_cod = cur_cra_cod
					  AND cur_estado = 'A'
					  AND cur_ape_ano = hor_ape_ano
					  AND cur_ape_per = hor_ape_per
					  AND cur_asi_cod = hor_asi_cod
					  AND cur_nro = hor_nro
					  AND hor_estado = 'A')) total
				FROM acasperi, gedep, accra, acdocente, acdoctipvin
				WHERE ape_estado = 'A'
				  AND dep_cod = cra_dep_cod
				  AND cra_cod = ".$_REQUEST['cracod']."
				  AND ape_ano = dtv_ape_ano
				  AND ape_per = dtv_ape_per
				  AND cra_cod = dtv_cra_cod
				  AND doc_nro_iden = dtv_doc_nro_iden
				  AND dtv_tvi_cod NOT IN (1,6)
				  AND EXISTS (SELECT car_doc_nro_iden
					      FROM accarga
					      WHERE acasperi.ape_ano = car_ape_ano
						 AND acasperi.ape_per = car_ape_per
						 AND accra.cra_cod = car_cra_cod
						 AND acdocente.doc_nro_iden = car_doc_nro_iden
						 AND car_estado = 'A')
				GROUP BY ape_ano,
				  ape_per,
				  cra_cod,
				  cra_nombre
				UNION 
				SELECT ape_ano anno,
				  ape_per periodo,
				  cra_cod cod_cra,
				  cra_nombre carrera,
				  'PLANTA' tipo,
				  dac_cod,
				  dac_nombre,
				  (COUNT(dpt_dac_cod))
				FROM acasperi, gedep, accra, acdocente, acdocactividad, acdocplantrabajo, acdoctipvin
				WHERE ape_estado = 'A'
				  AND dep_cod = cra_dep_cod
				  AND cra_cod = ".$_REQUEST['cracod']."
				  AND ape_ano = dpt_ape_ano
				  AND ape_per = dpt_ape_per
				  AND doc_nro_iden = dpt_doc_nro_iden
				  AND dac_cod = dpt_dac_cod
				  AND dpt_estado = 'A'
				  AND ape_ano = dtv_ape_ano
				  AND ape_per = dtv_ape_per
				  AND cra_cod = dtv_cra_cod
				  AND doc_nro_iden = dtv_doc_nro_iden
				  AND dtv_tvi_cod IN (1,6)
				  AND EXISTS (SELECT car_doc_nro_iden
					      FROM accarga
					      WHERE acasperi.ape_ano = car_ape_ano
						 AND acasperi.ape_per = car_ape_per
						 AND accra.cra_cod = car_cra_cod
						 AND acdocente.doc_nro_iden = car_doc_nro_iden
						 AND car_estado = 'A')
				GROUP BY ape_ano,
				  ape_per,
				  cra_cod,
				  cra_nombre,
				  dac_cod,
				  dac_nombre
				UNION
				SELECT ape_ano anno,
				  ape_per periodo,
				  cra_cod cod_cra,
				  cra_nombre carrera,
				  'VINESPECIAL' tipo,
				  dac_cod,
				  dac_nombre,
				  (COUNT(dpt_dac_cod))
				FROM acasperi, gedep, accra, acdocente, acdocactividad, acdocplantrabajo, acdoctipvin
				WHERE ape_estado = 'A'
				  AND dep_cod = cra_dep_cod
				  AND cra_cod = ".$_REQUEST['cracod']."
				  AND ape_ano = dpt_ape_ano
				  AND ape_per = dpt_ape_per
				  AND doc_nro_iden = dpt_doc_nro_iden
				  AND dac_cod = dpt_dac_cod
				  AND dpt_estado = 'A'
				  AND ape_ano = dtv_ape_ano
				  AND ape_per = dtv_ape_per
				  AND cra_cod = dtv_cra_cod
				  AND doc_nro_iden = dtv_doc_nro_iden
				  AND dtv_tvi_cod NOT IN (1,6)
				  AND EXISTS (SELECT car_doc_nro_iden
					      FROM accarga
					      WHERE acasperi.ape_ano = car_ape_ano
						 AND acasperi.ape_per = car_ape_per
						 AND accra.cra_cod = car_cra_cod
						 AND acdocente.doc_nro_iden = car_doc_nro_iden
						 AND car_estado = 'A')
				GROUP BY ape_ano,
				  ape_per,
				  cra_cod,
				  cra_nombre,
				  dac_cod,
				  dac_nombre
				ORDER BY 1,2,3,5,6 ASC";

		//echo $QryDocPtsn;
	
		$RowDocPtsn = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocPtsn,"busqueda");
	
	
		print '<p></p><table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
		  <tr class="tr"><td colspan="2">PLANTA</td></tr>
		  <tr class="tr">
			<td align="center" width="70%">Actividad</td>
			<td align="center" width="30%" >Total Horas</td>		
		  </tr>'; 
		$i=0;
		while(isset($RowDocPtsn[$i][0]))
		{
			if($RowDocPtsn[$i][4]=="PLANTA"){
			print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
			
				<td align="left">'.$RowDocPtsn[$i][6].'</td>
		
				<td align="center">'.$RowDocPtsn[$i][7].'</td>
		
	

			</tr>';

			}
		$i++;
		}
		print '</table>';
		
				
		echo '<hr>';
		echo '<br><table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
		  <tr class="tr"><td colspan="2">ESPECIAL</td></tr>
		  <tr class="tr">
			<td align="center" width="70%">Actividad</td>
			<td align="center" width="30%" >Total Horas</td>		
		  </tr>'; 
		$i=0;
		while(isset($RowDocPtsn[$i][0]))
		{ //echo $RowDocPtsn[$i][4];
			if($RowDocPtsn[$i][4]=="VINESPECIAL"){
			echo '<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
			
				<td align="left">'.$RowDocPtsn[$i][6].'</td>
		
				<td align="center">'.$RowDocPtsn[$i][7].'</td>
		
	

			</tr>';

			}
			$i++;
		}
		echo '</table><p></p>';	
	
	}
?>
</body>
</html>
