<?PHP
print'<form name="form1">
        <select name="menu1" style="width:200">
		  <option value="ay_cambioclave.php">Administración Coordinadores</option>
		  <option value="ay_admdec.php">Administración Decanos</option>
		  <option value="ay_admdoc.php">Administación de Docentes</option>
		  <option value="ay_cambioclave.php">Cambio de Clave</option>
		  <option value="ay_condoc.php">Consulta de Docentes</option>
		  <option value="ay_conest.php">Consulta de Estudiantes</option>
		  <option value="ay_cornom.php">Corrección de Documento de Identidad</option>
		  <option value="ay_creausu.php">Creación de Usuarios</option>
		  <option value="ay_gestion.php">Gestión de Usuarios</option>
		  <option value="ay_perfil_usuario.php">Perfiles de Usuarios</option>
		  <option value="ay_procu.php">Proyestos Curriculares</option>
          <option value="ay_usuario.php">Usuario</option>
        </select>
        <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo(\'menu1\',\'parent\',0)" style="width:30" class="button">
      </form>';
?>