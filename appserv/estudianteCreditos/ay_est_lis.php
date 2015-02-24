<?PHP
print'<form name="form1">
        <select name="menu1" style="width:200">
          <option value="ay_update_dat.php">Actualizaci&oacute;n de Datos</option>
          <option value="ay_adicion.php">Adici&oacute;n de Asignaturas</option>
          <option value="ay_add_can.php">Adici&oacute;n y Cancelaci&oacute;n</option>
          <option value="ay_asi_ins.php">Registro de Asignaturas</option>
          <option value="../generales/ay_clave.php">Cambiar mi Clave</option>
          <option value="ay_conhor.php">Consulta de Horarios</option>
          <option value="ay_correogr.php">Contactar Docentes</option>
          <option value="ay_notas.php">Hist&oacute;rico de Notas</option>
          <option value="ay_horario.php">Horario</option>
          <option value="ay_inf_diferido.php">Informaci&oacute;n de Diferido</option>
		  <option value="ay_lis_asi.php">Listado de Asignaturas</option>
		  <option value="ay_notaspar.php">Notas Parcioles</option>
		  <option value="ay_semaforo.php">Plan de Estudio</option>
        </select>
        <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo(\'menu1\',\'parent\',0)" style="width:30" class="button">
      </form>';
?>