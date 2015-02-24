<?php
include "funerr.php"; 
include "funev.php";
//----------------------------------------require_once('funencab.php');?>
<HTML> 
<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
	<link href='../script/estilo.css' rel='stylesheet' type='text/css'>

</HEAD><?
	//---------------------------------------echo encab_config(); 
	$id = @$_GET["id"];
/* if (not session("ingresook")) { 
	call $handerr[9,999];
	}
*/
?>
<script language="javascript">
function valideradio(n) {
	var x = 1
	m= eval(n)
					var btn = val(parent.fra_formato.document.evaluar.r1);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r2);
					if (btn == null) {x = 0;
						}
					var btn = val(parent.fra_formato.document.evaluar.r3);
					if (btn == null) {x = 0; 
						}
		if (m != 8) {
					var btn = val(parent.fra_formato.document.evaluar.r4);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r5);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r6);
					if (btn == null) {x = 0; 
						}
		}
		if ( m != 8 && m != 14 ) {
					var btn = val(parent.fra_formato.document.evaluar.r7);
					if (btn == null) {x = 0; 
						}
		}
		if ( m != 8 && m != 14 && m!= 9) {
					var btn = val(parent.fra_formato.document.evaluar.r8);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r9);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r10);
					if (btn == null) {x = 0; 
						}	
		}
		if ( m != 8 && m != 14 && m!= 9 && m!= 11) {
					var btn = val(parent.fra_formato.document.evaluar.r11);
					if (btn == null) {x = 0; 
						}
		}
		if ( m != 8 && m != 14 && m!= 9 && m!= 11 && m!= 6){
					var btn = val(parent.fra_formato.document.evaluar.r12);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r13);
					if (btn == null) {x = 0; 
						}					
		}
		if ( m != 8 && m != 14 && m!= 9 && m!= 6 && m!= 11 && m!= 13 && m!= 16) {
					var btn = val(parent.fra_formato.document.evaluar.r14);
					if (btn == null) {x = 0; 
						}
		}
		if ( m != 8 && m != 14 && m!= 9 && m!= 6 && m!= 11 && m!= 13 && m!= 16 && m!= 12) {
					var btn = val(parent.fra_formato.document.evaluar.r15);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r16);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r17);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r18);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r19);
					if (btn == null) {x = 0; 
						}
					var btn = val(parent.fra_formato.document.evaluar.r20);
					if (btn == null) {x = 0; 
						}
				}			
			return x;
}
function browser(){
		var brow = "";
        if(document.layers){ brow="NN4";}    
        if(document.all){brow="ie"}
        if(document.all){brow="ie8"}
        if(!document.all && document.getElementById){brow="NN6";}
	return brow;
}
  function aceptar(){
  	var brw = browser();
	if (brw=="NN4"){
		//--
	}else if (brw=="ie"){
		var x = parent.fra_operaciones.document.all.item("validaformato").value
		if (x != 7){var grabe = valideradio(x);}else var grabe = 1;
   		if (grabe == 1){
			  parent.fra_formato.document.forms.item("evaluar").action = "fungrab.php"    
	 	      parent.fra_formato.document.forms.item("evaluar").target = "fra_formato"
        	  parent.fra_formato.document.forms.item("evaluar").submit()		
       		  //parent.fra_registro.document.all.item("iframe_m").src="pag_blanca.htm"
		}else { alert('Estimado usuario: Para grabar su evaluacion debe responder todas las preguntas.');}
				
	}else if (brw=="ie8"){
		var x = parent.fra_operaciones.document.all.item("validaformato").value
		if (x != 7){var grabe = valideradio(x);}else var grabe = 1;
   		if (grabe == 1){
			  parent.fra_formato.document.forms.item("evaluar").action = "fungrab.php"    
	 	      parent.fra_formato.document.forms.item("evaluar").target = "fra_formato"
        	  parent.fra_formato.document.forms.item("evaluar").submit()		
       		  //parent.fra_registro.document.all.item("iframe_m").src="pag_blanca.htm"
		}else { alert('Estimado usuario: Para grabar su evaluacion debe responder todas las preguntas.');}
				
	}else if (brw=="NN6"){
		var x = parent.fra_operaciones.document.getElementById("vrform").value;
		if (x != 7){var grabe = valideradio(x);}else var grabe = 1;
   		if (grabe == 1){
			  var feval = parent.fra_formato.document.forms.item("evaluar");
			  feval.action = "fungrab.php";
	 	      feval.target = "fra_formato";
        	  feval.submit();
       		  //parent.fra_registro.document.all.item("iframe_m").src="pag_blanca.htm"
		}else { alert('Estimado usuario: Para grabar su evaluacion debe responder todas las preguntas.');}		
	}
  }
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

</script>

<BODY oncontextmenu="return false" onkeydown="return false"> 
<center>
<TABLE WIDTH=25% BORDER=4 CELLSPACING=1 CELLPADDING=1>
	<TR  align=center >
		<TH width=12><a href="JavaScript:aceptar()"><EM>Grabar</EM></a></TH>
	</TR>
</TABLE>
		<input type="hidden" name="validaformato" id="vrform" value = 0 > 
</center>
</BODY>
</HTML>

