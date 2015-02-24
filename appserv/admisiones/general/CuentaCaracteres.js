<!--
/**********************************************************************************   
ValidaAcasp
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*	Uso: <textarea rows="3" cols="70" name="obs" onKeyDown="ConTex(this.form.obs,this.form.contador);" onKeyUp="ConTex(this.form.obs,this.form.contador);"></textarea>
*		 Sólo puede digitar <input type="text" name="contador" size="2" value="500" style="text-align:center; border:0; height:auto" readonly> caracteres.
*********************************************************************************/
function ConTex(Char, ConChar) {
  var Limite=500;
  if(Char.value.length > Limite) 
     Char.value = Char.value.substring(0, Limite);
  else 
     ConChar.value = Limite - Char.value.length;
}
// -->