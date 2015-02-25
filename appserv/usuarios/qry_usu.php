<?PHP

if((isset($usuarios_sesion)?$usuarios_sesion:'')){
    session_name($usuarios_sesion);
}
$status = session_status();
if($status == PHP_SESSION_NONE){
    session_start();
}
/*$QryOtr = "SELECT cla_codigo,cla_clave,cla_tipo_usu,cla_estado,cla_estado
  				  FROM geclaves
 				  WHERE cla_codigo = ".$_SESSION['usuario_login']."
				  AND cla_tipo_usu != ".$_SESSION['usuario_nivel']."
				  AND cla_estado = 'A'";
*/


	$QryOtr= "SELECT p.orden, ";
	$QryOtr.="p.codigo, ";
	$QryOtr.="p.perfil ";
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
	$QryOtr.="SELECT row_number() over() orden, ";
	$QryOtr.="usutipo_cod codigo, ";
	$QryOtr.="usutipo_tipo perfil ";
	$QryOtr.="FROM geusutipo, geclaves ";
	$QryOtr.="WHERE  usutipo_cod = cla_tipo_usu ";
	$QryOtr.="AND cla_codigo = ".$_SESSION['usuario_login']." ";
	$QryOtr.="AND cla_estado = 'A' ";
	$QryOtr.="AND cla_tipo_usu <>  ".$_SESSION['usuario_nivel']." ";
	$QryOtr.=") AS p ";
	$QryOtr.="ORDER BY orden ASC "; 
?>
