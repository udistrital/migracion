<?php
//include_once('conexion.php');
require_once("../clase/funcionGeneral.class.php");
require_once('qry_usu.php');
	$conexion=new funcionGeneral();
	$registro=$conexion->ejecutarSQL($configuracion,$oci_conecta,$QryOtr,"busqueda");

	$html="<table style='width:100%; background-color:transparent;'>";
	$html.="<trheight='22px' ><td>";
	$html.="	<link href='../script/estilo.css' rel='stylesheet' type='text/css'>";
	$html.="	<div style='background-color:grey;
			font-size:14px;
			height:22px;
			width:100%;
			background-image:url(../grafico/esquema/tabs/tit_perfil.bmp);
			color:#FFD700;
			'>";
			$html.=$registro[0][2];
	$html.="	</div>";
	$html.="</td></tr>";
	
	
	
	if(isset($registro[1][0])){	
		
		$html.="<tr><td>";	
		$html.="	<form name='perfil' target='_top' action='../usuarios/conn_usuario.php' >";	
		$html.="	<select class='select_uno' name='u' id='u' onchange='document.perfil.submit() ' >";
		$html.="	<option selected>Cambiar Perfil</option>";

				$i=1;
				while(isset($registro[$i][0])){
					$html.="<option value='".$registro[$i][1]."'>".$registro[$i][2]."</option>";
					$i++;
				}
	
		$html.="	</select>";
		$html.="	</form>";
		$html.="</td></tr>";
	
	}
	
	
	
	
	
	$html.="</table>";	
	echo $html;	
   
?>
