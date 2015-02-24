<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'class_tiempo_carga.php'); 
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>

<?php
$cod_consul = "SELECT req_cod,asi_nombre,req_sem
		FROM ACREQ, ACASI 
		WHERE req_cod = asi_cod 
		AND req_cra_cod =".$_REQUEST['cracod']."
		AND req_asi_cod =".$_REQUEST['asicod']."
		AND req_estado = 'A'
		ORDER BY req_cod";


$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
$row =$consulta;

$asicod = $_REQUEST['asicod'];
require_once(dir_script.'NombreAsignatura.php');

fu_cabezote("REQUISITOS");

  if(is_array($consulta)){
    ?>
    <p>&nbsp;</p>

    <table border="1" width="60%" align="center" cellspacing="0" cellpadding="3">
    <caption><? echo '<span class="Estilo5">REQUISITOS DE:</span> '.$_REQUEST['asicod'].' - '.$Asignatura; ?></caption>
    <tr class="tr">
    <td align="center">C&oacute;digo</td>
    <td align="center">Nombre asignatura</td>
    <td align="center">Sem.</td>
    </tr>
    <?php
    $i=0;
    while(isset($consulta[$i][0]))
    {
	    echo'<tr><td align="right">'.$consulta[$i][0].'</td>
	    <td align="left">'.$consulta[$i][1].'</td>
	    <td align="left">'.$consulta[$i][2].'</td></tr>';
    $i++;
    }

    ?>

    </table>

    <?php
  }else{
    echo '<br/><br/>';
    echo '<center>';
    echo '<caption><span class="Estilo5">LA ASIGNATURA</span> '.$_REQUEST['asicod'].' - '.$Asignatura.' NO TIENE PREREQUISITOS</span></caption>';
    echo '</center>';
  }
?>
</BODY>
</HTML>