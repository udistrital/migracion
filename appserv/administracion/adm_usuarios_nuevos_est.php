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

$QryAcest = "SELECT est_cod, est_ind_cred 
	FROM acest a
	WHERE est_estado_est IN('A','B','H','L','V','J','T')
	AND not exists(SELECT * FROM geclaves WHERE cla_codigo = a.est_cod)";
$RowAcest = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAcest,"busqueda");

if(!is_array($RowAcest))
{
	echo "<script>location.replace('$redir?error_login=33')</script>";
	exit;
}
else
{
	$i=0;
	$t=1;
	$tip1=51;
	$tip2=52;
	$est='A';
	while(isset($RowAcest[$i][0]))
	{
		if($RowAcest[$i][1]=='N')
		{
			$largo = rand(6,9);
			$psw = genera_clave($largo);
			$ins="INSERT ";
			$ins.="INTO ";
			$ins.="geclaves ";
			$ins.="VALUES ";
			$ins.="(";
			$ins.="'".$RowAcest[$i][0]."', ";
			$ins.="'".$psw."', ";
			$ins.="'".$tip1."', ";
			$ins.="'".$est."'";
			$ins.=")";
			
			$row_ins = $conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");
			$tot = $tot+$t;
			$i++;
		}
		else
		{
			$largo = rand(6,9);
			$psw = genera_clave($largo);
			$ins="INSERT ";
			$ins.="INTO ";
			$ins.="geclaves ";
			$ins.="VALUES ";
			$ins.="(";
			$ins.="'".$RowAcest[$i][0]."', ";
			$ins.="'".$psw."', ";
			$ins.="'".$tip2."', ";
			$ins.="'".$est."'";
			$ins.=")";
			
			$row_ins = $conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");
			$tot = $tot+$t;
			$i++;
		}
		
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