<?php
   $pag = 4;
	//---------- Carga por carrera ---------'
function cargadoc($usuario, $cra)
{	
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	require_once(dir_conect.'fu_tipo_user.php');
	include_once("../clase/multiConexion.class.php");
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
	
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('evaldocente');
	$codigo = $_SESSION['usuario_login'];

	fu_tipo_user($_SESSION["usuario_nivel"]); 
	
	global $sql_ca;
	global $oci_cn;
	$sql_ca =  "SELECT DISTINCT(doc_nro_iden),
				 (LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, 
				 cra_cod,cra_nombre,tvi_cod,tvi_nombre,asi_nombre, asi_cod,cur_nro
			 FROM acdocente, acasperi, acdoctipvin, accra, actipvin,accarga,accurso,acasi
			 WHERE doc_nro_iden = $codigo AND cra_cod = :cra
				 AND ape_ano = dtv_ape_ano 
				 AND ape_per = dtv_ape_per
				 AND ape_estado = 'A' 
				 AND doc_nro_iden = dtv_doc_nro_iden  AND doc_estado = 'A'
				 AND dtv_estado = 'A' 
				 AND dtv_cra_cod = cra_cod 
				 AND dtv_tvi_cod = tvi_cod   
				 AND cur_nro = car_cur_nro
				 AND ape_ano = car_ape_ano AND ape_ano = cur_ape_ano AND ape_per = cur_ape_per 
			     AND ape_per = car_ape_per AND car_estado = 'A' AND cur_estado = 'A' 
				 AND asi_cod = cur_asi_cod 
			     AND asi_cod = car_cur_asi_cod 
			  	 AND car_doc_nro_iden = doc_nro_iden 
			  	 AND cur_cra_cod = cra_cod 
			  	 AND cur_cra_cod = car_cra_cod
				 ORDER BY tvi_cod,doc_nombre";
				 
				//---------------------------- ******************** ------------------\\
	$fmto=$fmtonum;
	$rs_ca = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_ca,"busqueda");
	if (isset($rs_ca[0][0]))
	{
		//Incluir carga del docente seg�n carrera;
		if (!is_array($rs_ca))
		{
		?>
			<TR ><TD colspan=4 width="920" align="center"><EM>------- Sin carga registrada -------</EM></EM></TD></TR>
		 <?
		}		
		else
		{
			?>
			<TR>
					<TD colspan=4 width="530" align="center"><font size=2 ><? echo "Asignatura"?> </font></TD>
					<TD colspan=4 width="250" align="center"><font size=2 ><? echo "C&oacute;digo"?> </font></TD>
					<TD colspan=4 width="137" align="center"><font size=2 ><? echo "Grupo"?> </font></TD>
			</TR> <?
			$i=0;
			while(isset($rs_ca[$i][0]))
			{
				?>
				<TR>
					<TD colspan=4 width="530"><EM><font size=2 ><? echo substr($rs_ca[$i][6],0,60)?></font></EM></TD>
					<TD colspan=4 width="250" align="center"><EM><font size=2 ><? echo substr($rs_ca[$i][7],0,12)?></font></EM></TD>
					<TD colspan=4 width="137" align="center"><EM><font size=2 ><? echo substr($rs_ca[$i][8],0,2)?></font></EM></TD>
				</TR>
				<?
				$i++;
			} 
		}
	}
}
?>
