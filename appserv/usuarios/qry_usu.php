<?PHP
session_name($usuarios_sesion);
session_start();
/*$QryOtr = "SELECT cla_codigo,cla_clave,cla_tipo_usu,cla_estado,cla_estado
  				  FROM geclaves
 				  WHERE cla_codigo = ".$_SESSION['usuario_login']."
				  AND cla_tipo_usu != ".$_SESSION['usuario_nivel']."
				  AND cla_estado = 'A'";
*/


	$QryOtr= "SELECT orden, ";
	$QryOtr.="codigo, ";
	$QryOtr.="perfil ";
	$QryOtr.="FROM ";
	$QryOtr.="(   ";
	$QryOtr.="SELECT -1 orden, ";
	$QryOtr.="usutipo_cod codigo, ";
	$QryOtr.="usutipo_tipo perfil ";
	$QryOtr.="FROM geusutipo, geclaves ";
	$QryOtr.="WHERE usutipo_cod = ".$_SESSION['usuario_nivel']." ";
	$QryOtr.="AND cla_codigo = ".$_SESSION['usuario_login']." ";
	$QryOtr.="AND cla_estado = 'A' ";
	$QryOtr.="AND cla_tipo_usu = ".$_SESSION['usuario_nivel']." ";
	$QryOtr.="UNION ";
	$QryOtr.="SELECT ROWNUM orden, ";
	$QryOtr.="usutipo_cod codigo, ";
	$QryOtr.="usutipo_tipo perfil ";
	$QryOtr.="FROM geusutipo, geclaves ";
	$QryOtr.="WHERE  usutipo_cod = cla_tipo_usu ";
	$QryOtr.="AND cla_codigo = ".$_SESSION['usuario_login']." ";
	$QryOtr.="AND cla_estado = 'A' ";
	$QryOtr.="AND cla_tipo_usu <>  ".$_SESSION['usuario_nivel']." ";
	$QryOtr.=") ";
	$QryOtr.="ORDER BY orden ASC "; 
?>
