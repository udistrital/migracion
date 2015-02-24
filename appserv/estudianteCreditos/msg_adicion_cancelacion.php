<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
?>
<html>
<head>
<title>Proceso de Adición y Cancelación</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?PHP fu_cabezote("ADICIONAR Y CANCELAR ASIGNATURAS"); ?>
<div align="center">
  <table width="600" height="370" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%"><span class="Estilo9"><p align="center">ATENCI&Oacute;N      
        <p align="justify">El proceso para &quot;Adicionar y Cancelar&quot; asignaturas, estará disponible a partir del próximo semestre.<br>
          <br>
          Para poder adicionar o cancelar asignaturas, es necesario haber oficializado matricula y encontrase en estado A o B, activo o prueba acad&eacute;mica respectivamente.</span></td>
    </tr>
  </table>
</div>
<?PHP fu_pie(); ?>
</body>
</html>