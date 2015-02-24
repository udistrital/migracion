<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(51);
fu_cabezote("CONSULTA DE HORARIOS");
?>
<html>
<head>
<title>Semestres</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>

<body>
<form method="POST" action="est_horarios_cra.php" target="hor">
  <p align="center"><span class="Estilo5">Seleccione El Semestre:</span>
  <select size="1" name="semestre">
  <option value="0">0</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
  <option value="8">8</option>
  <option value="9">9</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
  </select>
  <input type="submit" value="Consultar" name="B1" style="cursor:pointer" title="Ejecutar la consulta"></p>
</form>
</body>
</html>