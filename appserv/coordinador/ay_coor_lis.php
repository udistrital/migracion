<?PHP
print'<form name="form1">
        <select name="menu1" style="width:200">
          <option value="ay_update_dat.php">Actualizar Datos</option>
          <option value="ay_con_msg.php">Administraci&oacute;n Noticias</option>
		  <option value="../generales/ay_clave.php">Cambiar mi Clave</option>
          <option value="ay_correo_doc.php">Contactar Docentes</option>
          <option value="ay_dignot.php">Digitaci&oacute;n de Notas</option>
          <option value="ay_fec_notaspar.php">Fechas Notas Parciales</option>
          <option value="ay_horarios.php">Horarios</option>
          <option value="ay_ctrl_pt.php">Plan de Trabajo</option>
		  <option value="ay_pensum.php">Pensum</option>
		  <option value="ay_observaciones.php">Observaciones de Evaluación</option>
        </select>
        <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo(\'menu1\',\'parent\',0)" style="width:30" class="button">
      </form>';
?>