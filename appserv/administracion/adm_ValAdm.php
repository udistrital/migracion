<?PHP
session_name($usuarios_sesion);
session_start();

$QryValAdm = OCIParse($oci_conecta, "SELECT 'S' FROM geclaves
									  WHERE cla_codigo = ".$_SESSION["usuario_login"]."
										AND cla_tipo_usu = 20
										AND cla_estado = 'A'");
OCIExecute($QryValAdm) or die(ora_errorcode());
$RowValAdm = OCIFetch($QryValAdm);

if(OCIresult($QryValAdm, 1) != 'S'){
   OCIFreeCursor($QryValAdm);
   OCILogOff($oci_conecta);
   session_destroy();
   die('<p align="center"><b><font color="#FF0000"><u>Sesión Cerrada!</u></font></b></p>');
   exit;
}
?>