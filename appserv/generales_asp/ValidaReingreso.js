/**********************************************************************************   
ValidaReingreso
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOS� DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarr�s Cuello
*********************************************************************************/
function ValidaInscripcion(){
	//1. Documento de identidad
    if(isNaN(document.forms["reingreso"].elements["DocActual"].value)) { 
     alert("'Documento de identidad:', el dato debe ser numerico");
	 document.forms["reingreso"].elements["DocActual"].focus();
	 return 0;
  }
   if(document.forms["reingreso"].elements["DocActual"].value==""){
	 alert("Debe digitar el documento de identidad."); 
	 document.forms["reingreso"].elements["DocActual"].focus(); 
	 return 0;
  }
  //2. C�digo de estudiante en la Universidad Distrital:
  if(isNaN(document.forms["reingreso"].elements["EstCod"].value)) { 
     alert("'C�digo de estudiante en la Universidad Distrital::', el dato debe ser numerico");
	 document.forms["reingreso"].elements["EstCod"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["tel"].value==""){
	 alert("Debe ingresar un numero de telefono."); 
	 document.forms["reingreso"].elements["tel"].focus(); 
	 return 0;
  }
  
  //El teléfono debe ser numérico sin espacios.
  if(isNaN(document.forms["reingreso"].elements["tel"].value)){
	 alert("El número de teléfono debe de ser digitado sin especios.");
	 document.forms["reingreso"].elements["tel"].focus(); 
	 return 0;
  }
  
  if(document.forms["reingreso"].elements["EstCod"].value==""){
	 alert("Debe digitar el codigo de estudiante."); 
	 document.forms["reingreso"].elements["EstCod"].focus();
	 return 0;
  }
  //3. C�digo de estudiante en la Universidad Distrital:
  if(isNaN(document.forms["reingreso"].elements["ConEstCod"].value)) { 
     alert("'Confirme el codigo de estudiante en la Universidad Distrital:', el dato debe ser numerico");
	 document.forms["reingreso"].elements["ConEstCod"].focus();
	 return 0;
  }
  if(document.forms["reingreso"].elements["ConEstCod"].value==""){
	 alert("Debe digitarla confirmacion del codigo de estudiante."); 
	 document.forms["reingreso"].elements["ConEstCod"].focus();
	 return 0;
  }
  document.forms["reingreso"].submit();
}
function ComparaEstCod(){
  if(document.forms["reingreso"].elements["EstCod"].value != document.forms["reingreso"].elements["ConEstCod"].value){
	 alert("El codigo de estudiante y su confirmacion, son diferentes."); 
	 document.forms["reingreso"].elements["ConEstCod"].focus(); 
         document.forms["reingreso"].elements["ConEstCod"].value=""; 
	 return 0;
  }

}
// -->