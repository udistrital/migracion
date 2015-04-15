<?
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'class_nombres.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$nombre = new Nombres;

fu_tipo_user(4);
$est = $_REQUEST['estado'];


$QryEst = "SELECT EST_COD, EST_NOMBRE,EST_ESTADO_EST
	FROM ACEST X
	WHERE EST_CRA_COD = ".$_SESSION['carrera']."
	AND EST_ESTADO_EST = '$est'
	and exists(select ins_est_cod
	from acasperi,acins
	where ins_est_cod = x.est_cod
	and ins_cra_cod = x.est_cra_cod
	and ape_ano = ins_ano
	and ape_per = ins_per
	and ape_estado = 'A')
	ORDER BY 1";
$RowEst = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEst,"busqueda");

if(!is_array($RowEst))
{
   $nro="";
   $b_deltbl='';
}
else
{
 	 $nro=1;
	 $b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar Asignaturas" border="0">';
}


?>
<html>
<head>
<title>Reporte</title>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<style type="text/css">
<!--
.Estilo5 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo6 {
	font-size: 14px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body>
<p align="center" class="Estilo6">LISTADO DE ESTUDIANTES EN ESTADO<br><? echo $nombre->rescataNombre($est, "NombreEstado"); print' (';  print $est; print')'; ?><br>
  CON ASIGNATURAS INSCRITAS</p>
  <p align="center" class="Estilo5">Los estudiantes en los estados A y B, son los &uacute;nicos que deben tener asignaturas inscritas.</p>
  <p align="center" class="Estilo5">Recuerde que al borrar estos registros ser&aacute;n eliminados de la tabla de inscripci&oacute;n de asignaturas, y las notas no ser&aacute;n procesadas.</p>	
<table width="634" border="1" align="center" cellpadding="1" cellspacing="0">
  <tr>
    <td width="24" align="center"><span class="Estilo5">N&deg;</span></td>
    <td width="119" align="center"><span class="Estilo5">C&oacute;digo</span></td>
    <td width="389" align="center"><span class="Estilo5">Nombre</span></td>
    <td width="42" align="center"><span class="Estilo5">Estado</span></td>
	<td width="42" align="center"><span class="Estilo5">Borrar</span></td>
  </tr>
<?php
$i=0;
while(isset($RowEst[$i][0]))
{
	print'<tr>
	<td align="right"><span class="Estilo5">'.$i.'</span></td>
	<td align="right"><span class="Estilo5">'.$RowEst[$i][0].'</span></td>
	<td align="left"><span class="Estilo5">'.$RowEst[$i][1].'</span></td>
	<td align="center"><span class="Estilo5">'.$RowEst[$i][2].'</span></td>
	<td align="center"><a href="prog_borra_inscripcion.php?estcod='.$RowEst[$i][0].'&estado='.$_REQUEST['estado'].'" onMouseOver="link();return true;" onClick="link();return true;">
	'.$b_deltbl.'</a></td>
	</tr>';
	$i++;
}
?>
</table>
<table width="592"  border="0" align="center" cellpadding="0" cellspacing="0">
  	<tr>
    		<td align="center"><input name="button" type="button" style="cursor: hand" onClick="javascript:window.print();" value="Imprimir">
		</td>
    		<td align="center"><br>
    			<form action="prog_borra_asiins_por_estado.php" method="post" name="bs">
				<input name="estado" type="hidden" value="<? print $est; ?>">
			</form>
		</td>
  </tr>
</table>
</body>
</html>