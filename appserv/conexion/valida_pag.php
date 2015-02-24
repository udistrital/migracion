<?PHP

require_once('conexion.php');

$variables=array();
foreach($_REQUEST as $key=>$value)
	{$variables[$key]=$_REQUEST[$key];}
//echo "validacion ".$_SESSION['usuario_login'];

$url = explode("?",$_SERVER['HTTP_REFERER']);

//echo $url."mmm<br>"; exit; 
//var_dump($url); //exit;
//echo strstr($url[0],'lov_departamento');

$validar='S'; //Para iniciar la validación, dejarlo en S
//excluye las paginas de admisiones de la validacion de sesiones
if(strstr($url[0],'admisiones') || strstr($url[0],'aspirantes') || strstr($url[0],'resultados') || strstr($url[0],'instructivo'))
        {$validar='N';}

if($validar=="S")
        { include('../clase/valida_pagina.class.php');}


foreach($variables as $cod=>$value)
	{$_REQUEST[$cod]=$variables[$cod];}

session_name($usuarios_sesion);
session_start();
if(!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
   session_destroy();
   die('<p align="center"><b><font color="#FF0000"><u>Sesion Cerrada!</u></font></b></p>');
   exit;
}
?>
