<!--
/**********************************************************************************   
ValidaReingreso
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOS� DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarr�s Cuello
*********************************************************************************/
function ValidaInscripcion(){
	//1. Documento de identidad
    if(isNaN(document.forms["reingreso"].elements["DocActual"].value)) { 
     alert("'Documento de identidad:', el dato debe ser num�rico");
	 document.forms["reingreso"].elements["DocActual"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["DocActual"].value=="0"){
	 alert("Debe digitar el n�mero de su documento de identidad."); 
	 document.forms["reingreso"].elements["DocActual"].focus();
	 return 0;
  }
  //2. C�digo de estudiante en la Universidad Distrital:
  if(isNaN(document.forms["reingreso"].elements["EstCod"].value)) { 
     alert("'C�digo de estudiante en la Universidad Distrital::', el dato debe ser num�rico");
	 document.forms["reingreso"].elements["EstCod"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["tel"].value==""){
	 alert("Debe ingresar un n�mero de tel�fono."); 
	 document.forms["reingreso"].elements["tel"].focus(); 
	 return 0;
  }
  if(document.forms["reingreso"].elements["EstCod"].value==""){
	 alert("Debe digitar el c�digo de estudiante."); 
	 document.forms["reingreso"].elements["EstCod"].focus();
	 return 0;
  }
  //3. C�digo de estudiante en la Universidad Distrital:
  if(isNaN(document.forms["reingreso"].elements["ConEstCod"].value)) { 
     alert("'Confirme el c�digo de estudiante en la Universidad Distrital:', el dato debe ser num�rico");
	 document.forms["reingreso"].elements["ConEstCod"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["ConEstCod"].value==""){
	 alert("Debe digitarla confirmaci�n del c�digo de estudiante."); 
	 document.forms["reingreso"].elements["ConEstCod"].focus();
	 return 0;
  }
  document.forms["reingreso"].submit();
}
function ComparaEstCod(){
  if(document.forms["reingreso"].elements["EstCod"].value != document.forms["reingreso"].elements["ConEstCod"].value){
	 alert("El c�digo de estudiante y su confirmaci�n, son diferentes."); 
	 document.forms["reingreso"].elements["ConEstCod"].focus(); 
	 return 0;
  }

}
// -->