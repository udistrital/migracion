<?PHP
print'<form name="form1">
        <select name="menu1" style="width:200">
          <option value="ay_rep_pagos.php">Cargar Archivo Pagos</option>
          <option value="ay_tab_pagos.php">Cargar Tabla Pagos</option>
		  <option value="ay_upd_snp.php">Consulta y Modificación de Snp</option>
          <option value="ay_add_can.php">Encriptar Claves</option>
        </select>
        <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo(\'menu1\',\'parent\',0)" style="width:30" class="button">
      </form>';
?>