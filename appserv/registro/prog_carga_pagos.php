<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(33);

require_once('valida_inscripcion.php');

$AddAsi = OCIParse($oci_conecta, "BEGIN mntac.pra_inserta_acrecbanasp(); END;");
OCIExecute($AddAsi) or die(ora_errorcode());	
cierra_bd($AddAsi, $oci_conecta); 

print '<h3>Por favor ejecute el paso "4.Encriptar Claves".</h3>';
//if($cont > 1) $msg = 'Por favor ejecute el paso "4.Encriptar Claves" <br>'.$cont.' Registros Cargados.';
//else $msg = 'Por favor ejecute el paso "4.Encriptar Claves" <br>'.$cont.' Registro Cargado.';
//print'<h3>'.$msg.'</h3>';
?>
</body>
</html>