<?PHP
include_once('conexion.php');
require_once("../clase/funcionGeneral.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$confec = "SELECT SYSDATE FROM dual";

$rows = $conexion->ejecutarSQL($configuracion,$oci_conecta, $confec,"busqueda");
$fecha = $rows[0][0];

$conhor = "SELECT to_char(SYSDATE,'hh24:mi:ss') FROM dual";
$rows = $conexion->ejecutarSQL($configuracion,$oci_conecta, $conhor,"busqueda");
$hora = $rows[0][0];

$ins_usu="INSERT ";
$ins_usu.="INTO ";
$ins_usu.="geconexlog ";
$ins_usu.="(";
$ins_usu.="CNX_USUARIO, ";
$ins_usu.="CNX_MAQUINA, ";
$ins_usu.="CNX_FECHA, ";
$ins_usu.="CNX_HORA";
$ins_usu.=") ";
$ins_usu.="VALUES ";
$ins_usu.="(";
$ins_usu.="'".$registro[0][0]."', ";
$ins_usu.="'".$_SERVER['REMOTE_ADDR']."', ";
$ins_usu.="'".$fecha."', ";
$ins_usu.="'".$hora."' ";
$ins_usu.=")";

$rowins_usu = $conexion->ejecutarSQL($configuracion,$oci_conecta, $ins_usu,"busqueda");
?>