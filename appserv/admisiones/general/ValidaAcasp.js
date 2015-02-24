<!--
/**********************************************************************************   
ValidaAcasp
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOS� DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarr�s Cuello
*********************************************************************************/
function ValidaInscripcion(){
  //1. Medio de publicidad.
  if(isNaN(document.forms["acasp"].elements["MedPub"].value)){
	 alert("Por que medio se enter&oacute; de la Universidad Distrital:, el dato debe ser num&eacute;rico");
	 document.forms["acasp"].elements["MedPub"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["MedPub"].value=="0"){
	 alert("Debe ingresar el medio de publicidad."); 
	 document.forms["acasp"].elements["MedPub"].focus(); 
	 return 0;
  }
  //2. Carrera en la que se inscribe.
  if(isNaN(document.forms["acasp"].elements["CraCod"].value)) { 
     alert("C&oacute;digo de la carrera en la que se inscribe:, el dato debe ser num&eacute;rico");
	 document.forms["acasp"].elements["CraCod"].focus();
	 return 0;
  }
  
  if(document.forms["acasp"].elements["CraCod"].value=="0"){
	 alert("Debe seleccionar una carrera."); 
	 document.forms["acasp"].elements["CraCod"].focus();
	 return 0;
  }
  cartec=document.forms["acasp"].elements["CraCod"].value;
  if ((cartec=="283")||(cartec=="272")||(cartec=="273")||(cartec=="275")||(cartec=="277")||(cartec=="378")||(cartec=="279")||(cartec=="372"))
	{
		var statusConfirm = confirm("Esta carrera requiere titulo de tecnologo. Desea continuar?")
		if(statusConfirm==true)
		{
			alert("Continue...");
		}
		else
		{
			alert("La carrera seleccionada requiere un titulo de tecnologo.");
			document.forms["acasp"].elements["CraCod"].focus();
			return 0;	
		}		 
	}
  
  //3. Departamento de nacimiento.
  if(isNaN(document.forms["acasp"].elements["DptoNac"].value)){
	 alert("'Departamento:', el dato debe ser numerico");
	 document.forms["acasp"].elements["DptoNac"].focus();
	 return 0;
  }
  if(document.forms["acasp"].elements["DptoNac"].value=="0"){
	 alert("Debe seleccionar un departamento."); 
	 document.forms["acasp"].elements["DptoNac"].focus();
	 return 0;
  }
  //4. Ciudad de nacimiento.
  if(isNaN(document.forms["acasp"].elements["CiudadNac"].value)){
	 alert("'Ciudad:', el dato debe ser numerico");
	 document.forms["acasp"].elements["CiudadNac"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["CiudadNac"].value==""){
	 alert("Debe seleccionar una ciudad de nacimiento."); 
	 document.forms["acasp"].elements["CiudadNac"].focus(); 
	 return 0;
	}
  //5. Fecha de nacimiento.
  if(document.forms["acasp"].elements["FechaNac"].value==""){
	 alert("Debe ingresar una fecha de nacimiento."); 
	 document.forms["acasp"].elements["FechaNac"].focus(); 
	 return 0;
  }
  //6. Documento actual.
  if(isNaN(document.forms["acasp"].elements["DocActual"].value)){
	 alert("'Documento actual:', el dato debe ser numerico");
	 document.forms["acasp"].elements["DocActual"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["DocActual"].value==""){
	 alert("Debe ingresar el numero de su documento actual."); 
	 document.forms["acasp"].elements["DocActual"].focus(); 
	 return 0;
  }
  //7. Documento Icfes.
  if(isNaN(document.forms["acasp"].elements["DocIcfes"].value)){
	 alert("'Documento de identidad con el que presento el ICFES:', el dato debe ser numerico");
	 document.forms["acasp"].elements["DocIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["DocIcfes"].value==""){
	 alert("Debe ingresar el numero del documento con que presento el ICFES."); 
	 document.forms["acasp"].elements["DocIcfes"].focus(); 
	 return 0;
  }
  //8. Icfes.
  if(isNaN(document.forms["acasp"].elements["NroIcfes"].value)){
	 alert("'Numero del registro del icfes (SNP):', el dato debe ser numerico");
	 document.forms["acasp"].elements["NroIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["NroIcfes"].value==""){
	 alert("Debe ingresar el numero del registro del icfes."); 
	 document.forms["acasp"].elements["NroIcfes"].focus(); 
	 return 0;
  }
  if(isNaN(document.forms["acasp"].elements["CNroIcfes"].value)){
	 alert("'Numero del registro del icfes (SNP):', el dato debe ser numerico");
	 document.forms["acasp"].elements["CNroIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["CNroIcfes"].value==""){
	 alert("Debe ingresar el numero del registro del icfes."); 
	 document.forms["acasp"].elements["CNroIcfes"].focus(); 
	 return 0;
  }
    
//DATOS SOCIO-ECON�MICOS
  //12. Direcci�n actual de residencia.
  if(document.forms["acasp"].elements["dir"].value==""){
	 alert("Debe digitar la direccin actual de residencia."); 
	 document.forms["acasp"].elements["dir"].focus(); 
	 return 0;
  }
  //14. Tel�fono de residencia.
  if(document.forms["acasp"].elements["tel"].value==""){
	 alert("Debe ingresar el numero de telefono de residencia.");
	 document.forms["acasp"].elements["tel"].focus(); 
	 return 0;
  }
  //15. Valida cuenta de correo
  if(document.forms["acasp"].elements["CtaCorreo"].value==""){
	 alert("Debe ingresar un correo electronico.");
	 document.forms["acasp"].elements["CtaCorreo"].focus(); 
	 return 0;
  }	

  if((document.forms["acasp"].elements["CtaCorreo"].value.indexOf('@', 0) == -1)||(document.forms["acasp"].elements["CtaCorreo"].value.length < 5)){
      alert("Escriba una direccion de correo valida.");
      return (false);
  }

  //16. Localidad del colegios
  if(document.forms["acasp"].elements["LocCol"].value==""){
	 alert("Debe ingresar la localidad del colegio."); 
	 document.forms["acasp"].elements["LocCol"].focus(); 
	 return 0;
  }
  document.forms["acasp"].submit();
}
function ValidaSNP(){
	   //Compara SNP
  if(document.forms["acasp"].elements["TipoIcfes"].value != document.forms["acasp"].elements["CVTipoIcfes"].value){
	 alert("El Numero del registro del icfes (SNP) es diferente."); 
	 document.forms["acasp"].elements["TipoIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["acasp"].elements["NroIcfes"].value != document.forms["acasp"].elements["CNroIcfes"].value){
	 alert("El Numero del registro del icfes (SNP) es diferente."); 
	 document.forms["acasp"].elements["TipoIcfes"].focus(); 
	 return 0;
  }
}
// -->