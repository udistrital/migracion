
function browser(){
		var brow = "";
        if(document.layers){ brow="NN4";}    
        if(document.all){brow="ie"}
        if(!document.all && document.getElementById){brow="NN6";}
	return brow;
}
function eval()	{//validar y mantener c
     if (document.all.item("submit1").value == "Entrar") {
		 	
			var btn = val(document.trae_con.vin);
			var txtnull = document.forms.item("trae_con").text2.value
				if (btn == null || txtnull == "") {alert("Seleccione Estudiante, Docente o Coordinador y/o digite c�digo de usuario"); 
					}
				
				else {
					if (isValid(txtnull,'0123456789') == false){
						alert("Digite solo valores num�ricos.");
					}
					else {
						document.all.item("submit1").value = "Cerrar"
						parent.fra_registro.document.forms.item("trae_con").action = "validar_usuario2.php" 
						parent.fra_registro.document.forms.item("trae_con").target = "iframe_i"
						parent.fra_registro.document.forms.item("trae_con").submit()
						if (btn == 1) {
							parent.fra_formato.location.href = "pag_instrucciones_e.htm"; 
							parent.fra_registro.document.all.item("iframe_i").scrolling="No"
							}
						if (btn == 2) {
							parent.fra_formato.location.href = "pag_instrucciones_d.htm"; 
							parent.fra_registro.document.all.item("iframe_i").scrolling="No"
							}
						if (btn == 3) {
							parent.fra_formato.location.href = "pag_instrucciones_c.htm"; 
							parent.fra_registro.document.all.item("iframe_i").scrolling="Yes"
							}
						}
					}
		}
	else {
		parent.fra_registro.location.href = "pag_registro.php"
		parent.fra_formato.location.href = "pag_termina.htm"
		parent.fra_operaciones.location.href = "pag_blanca.htm"
		}
//---
	}    
 function fmtoeval()
    {
		parent.fra_registro.document.forms.item("trae_con").action = "evaluacion.php" 
		parent.fra_registro.document.forms.item("submit1").target = "fra_registro"
		parent.fra_registro.document.forms.item("trae_con").submit()
	} 
function evalredir() {
	parent.fra_registro.document.forms.item("trae_con").action = "validar_usuario.asp?DELAY_TIME=3" 
	parent.fra_registro.document.forms.item("submit1").target = "fra_registro"
	parent.fra_registro.document.forms.item("trae_con").submit()
}
function cerrar_c() {
			parent.fra_formato.location.href = "pag_bienvenidos.htm"; 
			parent.fra_operaciones.location.href = "pag_blanca.htm";
			parent.fra_registro.document.forms.item("updt_salir").action = "pag_registro.asp"    
	 	    parent.fra_registro.document.forms.item("updt_salir").target = "fra_registro"
        	parent.fra_registro.document.forms.item("updt_salir").submit()		
}
function updatelist(vin){
			parent.fra_formato.location.href = "pag_instrucciones_c.htm"; 
			parent.fra_operaciones.location.href = "pag_blanca.htm";
	 	   // parent.fra_registro.document.forms.item("usuario").target = "fra_registro"
			parent.fra_registro.document.forms.item("updt_salir").action = "validar_usuario2.php"
	 	    parent.fra_registro.document.forms.item("updt_salir").target = "fra_registro"
        	parent.fra_registro.document.forms.item("updt_salir").submit()
}
//--------------------------------
  function val(btn) {
// Radio Button Validation
// copyright Stephen Chapman, 15th Nov 2004
	var cnt = -1;
	for (var i=0; i < btn.length; i++) {
	   if (btn[i].checked) {cnt = i; i = btn.length;}
	   }
	if (cnt > -1) return btn[cnt].value;
	else return null;
}  
  //--------------------
function borrarinfo() {
  parent.fra_registro.document.all.item("iframe_i")="pag_blanca.htm"
//--------------
}

function activar_grabar(n,num){
	var x=String(n);
	var brw = browser();
	if (brw=="NN4"){
		if (document.boton_grabar.value >= 1){
			parent.fra_operaciones.location.href = "pag_operaciones.php"
			//parent.fra_registro.document.all.item("iframe_m")="pag_blanca.htm"
			document.boton_grabar.value = 0
		}
		parent.fra_operaciones.document.validaformato.value = x
	}else if (brw=="ie"){
		if (document.all.item("boton_grabar").value >= 1){
			parent.fra_operaciones.location.href = "pag_operaciones.php";
			//parent.fra_registro.document.all.item("iframe_m")="pag_blanca.htm"
			document.all.item("boton_grabar").value = 0;
		}
		parent.fra_operaciones.document.all.item("validaformato").value = x;
	}else if (brw=="NN6"){
		var btn = document.getElementById("b");
		var vform = parent.fra_operaciones.document.getElementById("vrform")
		if (btn.value >= 1){
			parent.fra_operaciones.location.href = "pag_operaciones.php";
			//parent.fra_registro.document.all.item("iframe_m")="pag_blanca.htm"
			btn.value = 0;
		}
		vform.value= x;
	}
 }  
	
function selecvin() {
		parent.fra_registro.document.forms.item("trae_con").vinculacion.value ="true"
}

function isValid(parm,val) {
  if (parm == "") return true;
  for (i=0; i<parm.length; i++) {
    if (val.indexOf(parm.charAt(i),0) == -1) return false;
  }
  return true;
}
function ctatxt(msg) {
	if (msg.length > 255){
		alert("El campo para sus observaciones est� limitado a 255 caracteres");
		document.all.item("r14").value = msg.substr(0,255);
	}
}