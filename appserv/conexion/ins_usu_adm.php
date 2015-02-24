<?PHP
include_once('conexion.php');
include_once("../clase/multiConexion.class.php");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion(50);

$per_consulta = "SELECT ape_ano, ape_per FROM acasperiadm WHERE ape_estado='X'";
$rowPerConsulta = $conexion->ejecutarSQL($configuracion,$accesoOracle, $per_consulta,"busqueda");

$ano = $rowPerConsulta[0][0];
$per = $rowPerConsulta[0][1];

$confec = "SELECT to_char(SYSDATE, 'dd/mm/yyyy') FROM dual";

$rows = $conexion->ejecutarSQL($configuracion,$accesoOracle, $confec,"busqueda");
$fecha = $rows[0][0];

$conhor = "SELECT to_char(SYSDATE,'hh24:mi:ss') FROM dual";
$rows = $conexion->ejecutarSQL($configuracion,$accesoOracle, $conhor,"busqueda");
$hora = $rows[0][0];

$ins_usuAdm="INSERT ";
$ins_usuAdm.="INTO ";
$ins_usuAdm.="acrecbanasplog ";
$ins_usuAdm.="(";
$ins_usuAdm.="rba_ape_ano, ";
$ins_usuAdm.="rba_ape_per, ";
$ins_usuAdm.="rba_ref_pago, ";
$ins_usuAdm.="rba_nro_iden, ";
$ins_usuAdm.="rba_fecha, ";
$ins_usuAdm.="rba_terminal";
$ins_usuAdm.=") ";
$ins_usuAdm.="VALUES ";
$ins_usuAdm.="(";
$ins_usuAdm.="'".$ano."', ";
$ins_usuAdm.="'".$per."', ";
$ins_usuAdm.="'".$registro[0][4]."', ";
$ins_usuAdm.="'".$registro[0][0]."', ";
$ins_usuAdm.="'".$fecha."', ";
$ins_usuAdm.="'".$_SERVER['REMOTE_ADDR']."'";
$ins_usuAdm.=")";
//echo $ins_usuAdm;
$rowins_usuAdm = $conexion->ejecutarSQL($configuracion,$oci_conecta, $ins_usuAdm,"busqueda");
/*if(isset($rowins_usuAdm)){
echo "se grabo correctamente";
}
else
{
echo "no se grabo";
}*/
?>