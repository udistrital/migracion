<?PHP
print'<form name="form1">
  <select name="menu1" style="width:200">
   <option value="../generales/ay_clave.php">Cambiar mi Clave</option>
   <option value="ay_desprendible.php">Desprendible de Pago</option>
  </select>
  <input type="button" name="Button1" value="Ir" onClick="MM_jumpMenuGo(\'menu1\',\'parent\',0)" style="width:30" class="button">
</form>';
?>