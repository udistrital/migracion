<?PHP
print'<form name="form1">
        <select name="menu1" style="width:200">
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
        <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo(\'menu1\',\'parent\',0)" style="width:30" class="button">
      </form>';
?>