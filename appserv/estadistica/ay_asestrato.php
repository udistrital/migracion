<html>
<head>
<title>Ayuda</title>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="90%" align="center" background="../img/fondo_ay.png">
  <tr>
    <td width="49" rowspan="3" class="td"><br>
    <img src="../img/ay.gif" width="30" height="30"></td>
    <td width="786" valign="middle"><span class="Estilo1"><br>
    &nbsp;&nbsp;&nbsp;POBLACI�N DE ASPIRANTES POR ESTRATO</span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	<p align="justify">Seleccione de la lista desplegable el per�odo acad�mico deseado y haga clic en el bot�n �Consultar�.</p>
  <p align="justify"><b>Informaci�n desplegada.</b></p>
  <p align="justify">Titulo de la informaci�n con a�o y per�odo consultado.</p>
  <p align="justify">Cuadro de estratos y n�mero de aspirantes.</p>
  <p align="justify">Gr�fica de estratos con n�mero de aspirante por cada uno y porcentaje.</p>
  <p align="justify">Haga clic en el bot�n �Imprimir� si desea una copia impresa de la informaci�n desplegada.</p>
  <br>
    </td>
  </tr>
</table>
<br>
<table width="90%" align="center" class="tb">
  <tr>
    <td width="75%" align="left" valign="middle">
     <form name="form1">
        <select name="menu1" style="width:200" disabled>
          <option value="ay_update_dat.php">Actualizaci&oacute;n Datos</option>
		  <option value="../generales/ay_clave.php">Cambiar mi Clave</option>
          <option value="ay_carga.php">Carga Acad&eacute;mica</option>
          <option value="ay_cursos.php">Cursos</option>
          <option value="ay_correogr.php">Envio Correos</option>
          <option value="ay_horario.php">Horarios</option>
		  <option value="ay_lis_clase.php">Lista de Clase y Notas</option>
		  <option value="ay_lisclase.php">Lista de Clase</option>
		  <option value="ay_observaciones.php">Observaciones Evaluaci&oacute;n</option>
		  <option value="ay_obs_notas.php">Obs. de Notas</option>
		  <option value="ay_obs_notaspar.php">Obs. de Notas Parciales</option>
		  <option value="ay_ges_actividad.php">Plan de Trabajo</option>
		  <option value="ay_semaforo.php">Plan de Estudio</option>
        </select>
        <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo('menu1','parent',0)" style="width:25" class="button" disabled>
      </form>
    </td>
    <td width="25%" align="right" valign="middle">
	<form name="form2">
	<input type="button" name="Submit" value="Cerrar" onClick="javascript:window.close();" class="button">
	</form>
	</td>
  </tr>
</table>
</body>
</html>