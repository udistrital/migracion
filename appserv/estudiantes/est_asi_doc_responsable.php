<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
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

</HEAD>
<BODY topmargin="2">

<?php

$asicod = $_GET['asicod'];
require_once(dir_script.'NombreAsignatura.php');

$QryDocResp = "SELECT doc_nro_iden,
		(trim(doc_nombre)||' '||trim(doc_apellido)),
		doc_email
		FROM acasperi, acdocente, accarga
		WHERE ape_ano = car_ape_ano
		AND ape_per = car_ape_per
		AND ape_estado = 'A'
		AND car_cur_asi_cod = ".$_GET['asicod']."
		AND car_cur_nro = ".$_GET['asigr']."
		AND doc_nro_iden = car_doc_nro_iden
		AND car_estado = 'A'
		ORDER BY trim(doc_apellido) ASC";


$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocResp,"busqueda");


?>
  <table border="1" width="90%" align="center" cellspacing="0" cellpadding="0">
  <caption class="button"><?php echo $Asignatura.' - Grupo: '.$_GET['asigr']; ?></caption>
  <tr align="center" class="tr">
	<td>Docente Responsable</td>
    <td>Correo Electr&oacute;nico</td>
  </tr>
<?php
$i=0;
while(isset($registro[$i][0])){
     print'<tr><td>'.$registro[$i][1].'</td> 
     <td><a href="../generales/frm_est_envia_email_doc.php?usu='.$registro[$i][0].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Enviar correo al docente">
	 '.$registro[$i][2].'</a></td></tr>'; 
     $i++;
}

?>
</table>

</BODY>
</HTML>
