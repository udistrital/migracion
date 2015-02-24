<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");


fu_tipo_user(51);


	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);




?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY style="margin:0">

<?php

fu_cabezote("REGISTRO DE ASIGNATURAS");

$estcod = $_SESSION['usuario_login'];
$carrera = $_SESSION['carrera'];
$nivel = $_SESSION["usuario_nivel"];

$estados = "'A','B','H','J','L','T','V'";
//require_once(dir_script.'msql_est_asi_ins.php');
$consulta = "SELECT est_cod,
			est_nombre,
			est_nro_iden,
			cra_cod,
			cra_nombre,
			TRUNC(Fa_Promedio_Nota(est_cod),2),
			asi_cod,
			asi_nombre,
			ins_gr
		FROM ACCRA, ACEST,ACINS, ACASI,ACASPERI
	WHERE cra_cod = est_cra_cod
		AND est_cod     = ins_est_cod
		AND est_cra_cod = ins_cra_cod
		AND est_estado_est IN($estados)
		AND asi_cod     = ins_asi_cod
		AND ape_ano     = ins_ano
		AND ape_per     = ins_per
		AND ape_estado  = 'A'
		AND ins_estado  = 'A'
		AND est_cod = ".$_SESSION['usuario_login']."
order by ins_asi_cod";

$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");

//echo $consulta;

	if(isset($registro[0][0])){
		echo'<p></p><table border="0" width="90%" align="center" cellpadding="2">
		      <tr>
			<td align="right">'.$registro[0][0].'</td>
			<td ><strong>'.$registro[0][1].'</strong></td>
			<td align="right">Identificaci&oacute;n:</td>
			<td align="left">'.$registro[0][2].'</td>
		      </tr>
		      <tr>
			<td align="right">'.$registro[0][3].'</td>
			<td>'.$registro[0][4].'</td>
			<td align="right">Promedio:</td>
			<td align="left">'.$registro[0][5].'</td>
		      </tr>
		      <tr>
			<td align="right">&nbsp;</td>
			<td>
			<p align="right">&nbsp;</td>
			<td align="right"><strong>Per&iacute;odo Acad&eacute;mico:</strong></td>
			<td align="left">'.$ano.'-'.$per.'</td>
		      </tr></table>';
		?>
		  <table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
			  <tr class="tr">
				    <td align="center">C&oacute;digo</td>
					<td align="center">Asignatura</td>
				    <td align="center">Gru</td>
			  </tr>
		<?php
		  
			$i=0;

			/*echo "<pre>";
			var_dump($registro);
			echo "</pre>";*/

		   	while(isset($registro[$i][0]))
			{
		     		echo '<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'"><td align="right">
			      	  <a href="est_asi_hor.php?asicod='.$registro[$i][6].'&asigr='.$registro[$i][8].'" target="asihor" onMouseOver="link();return true;" onClick="link();return true;" title="Ver horario de la asignatura">'.$registro[$i][6].'</a></td>
			 	  <td align="left">
				  <a href="est_asi_doc_responsable.php?asicod='.$registro[$i][6].'&asigr='.$registro[$i][8].'" target="asihor" onMouseOver="link();return true;" onClick="link();return true;" title="Ver docente responsable">'.$registro[$i][7].'</a></td> 
		     		  <td align="center">'.$registro[$i][8].'</td></tr>'; 
		  	$i++;
			}


		?>
		</table><br>
		<?PHP
		$printh = "javascript:popUpWindow('print_est_horario.php?estcod=$estcod', 'yes', 0, 0, 900, 500)";
		require_once(dir_script.'msg_doc_no_valido.php');
		print'<center><input type="submit" value="Imprimir Horario" onClick="'.$printh.'" title="Imprimir el horario de clase" style="width:200;cursor:pointer"></center>';

		
	}else{
		echo "<br><br><br><center><div class='aviso_mensaje'><br>Actualmente no tiene asignaturas inscritas<br><br></div><br></center>";
		
	}?>	

</BODY>
</HTML>
