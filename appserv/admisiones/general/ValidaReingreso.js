<!--
/**********************************************************************************   
ValidaReingreso
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function ValidaInscripcion(){
	//1. Documento de identidad
    if(isNaN(document.forms["reingreso"].elements["DocActual"].value)) { 
     alert("'Documento de identidad:', el dato debe ser numérico");
	 document.forms["reingreso"].elements["DocActual"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["DocActual"].value=="0"){
	 alert("Debe digitar el número de su documento de identidad."); 
	 document.forms["reingreso"].elements["DocActual"].focus();
	 return 0;
  }
  //2. Código de estudiante en la Universidad Distrital:
  if(isNaN(document.forms["reingreso"].elements["EstCod"].value)) { 
     alert("'Código de estudiante en la Universidad Distrital::', el dato debe ser numérico");
	 document.forms["reingreso"].elements["EstCod"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["tel"].value==""){
	 alert("Debe ingresar un número de teléfono."); 
	 document.forms["reingreso"].elements["tel"].focus(); 
	 return 0;
  }
  if(document.forms["reingreso"].elements["EstCod"].value==""){
	 alert("Debe digitar el código de estudiante."); 
	 document.forms["reingreso"].elements["EstCod"].focus();
	 return 0;
  }
  //3. Código de estudiante en la Universidad Distrital:
  if(isNaN(document.forms["reingreso"].elements["ConEstCod"].value)) { 
     alert("'Confirme el código de estudiante en la Universidad Distrital:', el dato debe ser numérico");
	 document.forms["reingreso"].elements["ConEstCod"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["ConEstCod"].value==""){
	 alert("Debe digitarla confirmación del código de estudiante."); 
	 document.forms["reingreso"].elements["ConEstCod"].focus();
	 return 0;
  }
  document.forms["reingreso"].submit();
}
function ComparaEstCod(){
  if(document.forms["reingreso"].elements["EstCod"].value != document.forms["reingreso"].elements["ConEstCod"].value){
	 alert("El código de estudiante y su confirmación, son diferentes."); 
	 document.forms["reingreso"].elements["ConEstCod"].focus(); 
	 return 0;
  }

}
// -->