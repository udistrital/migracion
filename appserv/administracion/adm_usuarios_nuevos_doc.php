<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');;
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_genera_clave.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);

$redir = 'adm_principal.php';

$QryDoc = "SELECT doc_nro_iden
	FROM acdocente
	WHERE doc_estado = 'A'
	AND EXISTS(SELECT car_doc_nro_iden
	FROM acasperi, accarga
	WHERE ape_ano = car_ape_ano
	AND ape_per = car_ape_per
	AND ape_estado = 'A'
	AND acdocente.doc_nro_iden = car_doc_nro_iden
	AND car_estado = 'A')
	AND NOT EXISTS(SELECT cla_codigo
	FROM geclaves
	WHERE acdocente.doc_nro_iden = cla_codigo
	AND cla_tipo_usu = 30) 
	AND NOT EXISTS(SELECT emp_nro_iden
	FROM peemp
	WHERE acdocente.doc_nro_iden = emp_nro_iden
	AND emp_estado <> 'R')";

$RowDoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDoc,"busqueda");

if(!is_array($RowDoc))
{
	echo "<script>location.replace('$redir?error_login=33')</script>";
	exit;
}
else
{
	 $i=0;
	 $t=1;
	 $tip=30;
	 $est='A';
	 while(isset($RowDoc[$i][0]))
	 {
		$largo = rand(6,9);
		$psw = genera_clave($largo);
		$ins="INSERT ";
		$ins.="INTO ";
		$ins.="geclaves ";
		$ins.="VALUES ";
		$ins.="(";
		$ins.="'".$RowDoc[$i][0]."', ";
		$ins.="'".$psw."', ";
		$ins.="'".$tip."', ";
		$ins.="'$est'";
		$ins.=")";
		$row_ins = $conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");
		$tot = $tot+$t;
	$i++;
	}
	$cont = $tot;
	if(isset($row_ins))
	{
		$msg = $cont.' Registros Insertados.';
	}
	else
	{
		$msg = $cont.' Registro Insertado.';
	}
	print'<br><br><br><br><br><table border="0" align="center" width="500">
	<tr><td width="300" align="center"><b><font size="4" face="Tahoma" color="#FF0000">'.$msg.'</font></b></td>
	</tr></table>';
}
?>