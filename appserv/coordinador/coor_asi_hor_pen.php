<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<HTML>
<HEAD>
<title>Horario</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php 
/*$consulta = "SELECT hor_nro,
	dia_nombre,
	MIN(hor_hora)||'-'||(MAX(hor_hora) + 1),
	hor_sal_cod,
	sed_abrev  
	FROM acasi, acasperi, achorario, gedia, gesalon, gesede
	WHERE ape_ano = hor_ape_ano
	AND ape_per = hor_ape_per
	AND ape_estado = 'A'
	AND hor_asi_cod = ".$_REQUEST['asicod']."
	AND asi_cod = hor_asi_cod
	AND asi_estado = 'A'
	AND hor_estado = 'A'
	AND hor_dia_nro  = dia_cod
	AND hor_sal_cod = sal_cod
	AND hor_sed_cod = sed_cod
	GROUP BY hor_nro,dia_cod, dia_nombre, hor_sal_cod, sed_abrev
	ORDER BY hor_nro,dia_cod, MIN(hor_hora) ASC";*/

$consulta = "SELECT cur_grupo,
	dia_nombre,
	MIN(hor_hora)||'-'||(MAX(hor_hora) + 1),
	sal_nombre,
	sed_abrev  
	FROM acasi, acasperi,accursos, achorarios, gedia, gesalones, gesede
	WHERE ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND ape_estado = 'A'
	AND cur_asi_cod =  ".$_REQUEST['asicod']."
        AND cur_cra_cod = ".$_REQUEST['cracod']."
	AND asi_cod = cur_asi_cod
	AND asi_estado = 'A'
	AND hor_estado = 'A'
	AND hor_dia_nro  = dia_cod
	AND hor_sal_id_espacio = sal_id_espacio
	AND sal_sed_id = sed_id
        AND hor_id_curso=cur_id
	GROUP BY cur_grupo,dia_cod, dia_nombre, sal_nombre, sed_abrev
	ORDER BY cur_grupo,dia_cod, MIN(hor_hora) ASC";

$registros = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
$asicod = $_REQUEST['asicod'];
require_once(dir_script.'NombreAsignatura.php');
$RowAsignatura = $conexion->ejecutarSQL($configuracion,$accesoOracle,$NombreAsignatura,"busqueda");
$Asignatura=$RowAsignatura[0][0];
?>
<br>
 <div align="center" class="Estilo5">GRUPOS PROGRAMADOS DE LA ASIGNATURA:</div>
  <table width="90%" align="center" border="0" cellspacing="0" cellpadding="2">
  <caption><?php echo $Asignatura; ?></caption>
  <tr class="tr">
  	<td align="center">Grupo</td>
	<td align="center">D&iacute;a</td>
    <td align="center">Hora</td>
    <td align="center">Sal&oacute;n</td>
	<td align="center">Sede</td>
  </tr>
<?php
$i=0;
//var_dump($registro);exit;
while(isset($registros[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="center">'.$registros[$i][0].'</td>
	<td align="left">'.$registros[$i][1].'</td>
	<td align="center">'.$registros[$i][2].'</td>
	<td align="center">'.$registros[$i][3].'</td>
	<td align="center">'.$registros[$i][4].'</td></tr>'; 
$i++;
}
?>
</table>
<p align="center"><input name="cerr" type="button" value="Cerrar" style="cursor:pointer" title="Cerrar" onClick="javascript:window.close();"></p>
</BODY>
</HTML>