<?php
if((isset($usuarios_sesion)?$usuarios_sesion:'')){
    session_name($usuarios_sesion);
}
session_start();
session_cache_limiter('nocache, private');

$_SESSION['usuario_password'] = OCIResult($QryOtr, 2);
$_SESSION["usuario_nivel"] = $_GET['u'];
?>