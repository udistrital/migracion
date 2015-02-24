<!--
/**********************************************************************************   
ValidaAcasp
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function ValidaInscripcion(){
  //1. Carrera en la que se inscribe.
  if(isNaN(document.forms["transferencia"].elements["CraCodT"].value)) { 
     alert("Carrera a la que se transfiere:, el dato debe ser numérico");
	 document.forms["transferencia"].elements["CraCodT"].focus();
	 return 0;
  }
  if(document.forms["transferencia"].elements["CraCodT"].value=="0"){
	 alert("Debe seleccionar una carrera."); 
	 document.forms["transferencia"].elements["CraCodT"].focus();
	 return 0;
  }
  //2. Universidad de donde viene.
  if(document.forms["transferencia"].elements["UdPro"].value=="0"){
	 alert("Debe digitar e nombre de la Universidad de donde viene."); 
	 document.forms["transferencia"].elements["UdPro"].focus();
	 return 0;
  }
  //3. Carrera que venía cursando.
  if(document.forms["transferencia"].elements["CraCur"].value=="0"){
	 alert("Debe digitar e nombre de la carrera que venía cursando."); 
	 document.forms["transferencia"].elements["CraCur"].focus();
	 return 0;
  }  
  //4. Último semestre cursado.
  if(isNaN(document.forms["transferencia"].elements["LastSem"].value)){
	 alert("'Último semestre cursado:', el dato debe ser numérico");
	 document.forms["transferencia"].elements["LastSem"].focus();
	 return 0;
  }
  if(document.forms["transferencia"].elements["LastSem"].value=="0"){
	 alert("Debe digitar el último semestre cursado."); 
	 document.forms["transferencia"].elements["LastSem"].focus();
	 return 0;
  }
  //5. Departamento de nacimiento.
  if(isNaN(document.forms["transferencia"].elements["DptoNac"].value)){
	 alert("'Departamento:', el dato debe ser numérico");
	 document.forms["transferencia"].elements["DptoNac"].focus();
	 return 0;
  }
  if(document.forms["transferencia"].elements["DptoNac"].value=="0"){
	 alert("Debe seleccionar un departamento."); 
	 document.forms["transferencia"].elements["DptoNac"].focus();
	 return 0;
  }
  //6. Ciudad de nacimiento.
  if(isNaN(document.forms["transferencia"].elements["CiudadNac"].value)){
	 alert("'Ciudad:', el dato debe ser numérico");
	 document.forms["transferencia"].elements["CiudadNac"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["CiudadNac"].value==""){
	 alert("Debe seleccionar una ciudad de nacimiento."); 
	 document.forms["transferencia"].elements["CiudadNac"].focus(); 
	 return 0;
	}
  //7. Fecha de nacimiento.
  if(document.forms["transferencia"].elements["FechaNac"].value==""){
	 alert("Debe ingresar una fecha de nacimiento."); 
	 document.forms["transferencia"].elements["FechaNac"].focus(); 
	 return 0;
  }
  //8. Documento actual.
  if(isNaN(document.forms["transferencia"].elements["DocActual"].value)){
	 alert("'Documento actual:', el dato debe ser numérico");
	 document.forms["transferencia"].elements["DocActual"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["DocActual"].value==""){
	 alert("Debe ingresar el numero de su documento actual."); 
	 document.forms["transferencia"].elements["DocActual"].focus(); 
	 return 0;
  }
  //9. Documento Icfes.
  if(isNaN(document.forms["transferencia"].elements["DocIcfes"].value)){
	 alert("'Documento de identidad con el que presentó el ICFES:', el dato debe ser numérico");
	 document.forms["transferencia"].elements["DocIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["DocIcfes"].value==""){
	 alert("Debe ingresar el numero del documento con que presentó el ICFES."); 
	 document.forms["transferencia"].elements["DocIcfes"].focus(); 
	 return 0;
  }
  //10. Icfes.
  if(isNaN(document.forms["transferencia"].elements["NroIcfes"].value)){
	 alert("'Número del registro del icfes (SNP):', el dato debe ser numérico");
	 document.forms["transferencia"].elements["NroIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["NroIcfes"].value==""){
	 alert("Debe ingresar el número del registro del icfes."); 
	 document.forms["transferencia"].elements["NroIcfes"].focus(); 
	 return 0;
  }
  if(isNaN(document.forms["transferencia"].elements["CNroIcfes"].value)){
	 alert("'Número del registro del icfes (SNP):', el dato debe ser numérico");
	 document.forms["transferencia"].elements["CNroIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["CNroIcfes"].value==""){
	 alert("Debe ingresar el número del registro del icfes."); 
	 document.forms["transferencia"].elements["CNroIcfes"].focus(); 
	 return 0;
  }
    
//DATOS SOCIO-ECONÓMICOS
  //12. Dirección actual de residencia.
  if(document.forms["transferencia"].elements["dir"].value==""){
	 alert("Debe digitar la dirección actual de residencia."); 
	 document.forms["transferencia"].elements["dir"].focus(); 
	 return 0;
  }
  //14. Teléfono de residencia.
  if(document.forms["transferencia"].elements["tel"].value==""){
	 alert("Debe ingresar el número de teléfono de residencia."); 
	 document.forms["transferencia"].elements["tel"].focus(); 
	 return 0;
  }
  //15. Localidad del colegios
  if(document.forms["transferencia"].elements["LocCol"].value==""){
	 alert("Debe ingresar la localidad del colegio."); 
	 document.forms["transferencia"].elements["LocCol"].focus(); 
	 return 0;
  }
  document.forms["transferencia"].submit();
}
function ValidaSNP(){
	   //Compara SNP
  if(document.forms["transferencia"].elements["TipoIcfes"].value != document.forms["transferencia"].elements["CVTipoIcfes"].value){
	 alert("El Número del registro del icfes (SNP) es diferente."); 
	 document.forms["transferencia"].elements["TipoIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["NroIcfes"].value != document.forms["transferencia"].elements["CNroIcfes"].value){
	 alert("El Número del registro del icfes (SNP) es diferente."); 
	 document.forms["transferencia"].elements["TipoIcfes"].focus(); 
	 return 0;
  }
}
// -->