
/**********************************************************************************   
ValidaAcasp
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOS� DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*********************************************************************************/
function ValidaInscripcion(){
  //1. Carrera en la que se inscribe.
  if(isNaN(document.forms["transferencia"].elements["CraCodT"].value)) { 
     window.alert("Carrera a la que se transfiere:, el dato debe ser num�rico");
	 document.forms["transferencia"].elements["CraCodT"].focus();
	 return 0;
  }
  if(document.forms["transferencia"].elements["CraCodT"].value=="0"){
	 window.alert("Debe seleccionar una carrera."); 
	 document.forms["transferencia"].elements["CraCodT"].focus();
	 return 0;
  }
  //2. Universidad de donde viene.
  if(document.forms["transferencia"].elements["UdPro"].value==""){
	 window.alert("Debe digitar e nombre de la Universidad de donde viene."); 
	 document.forms["transferencia"].elements["UdPro"].focus(); 
	 return 0;
  }
  
  //3. Carrera que ven�a cursando.
  if(document.forms["transferencia"].elements["CraCur"].value==""){
	 window.alert("Debe digitar e nombre de la carrera que ven�a cursando."); 
	 document.forms["transferencia"].elements["CraCur"].focus(); 
	 return 0;
  }
  //4. �ltimo semestre cursado.
  if(isNaN(document.forms["transferencia"].elements["LastSem"].value)){
	 window.alert("'�ltimo semestre cursado:', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["LastSem"].focus();
	 return 0;
  }
  if(document.forms["transferencia"].elements["LastSem"].value==""){
	 window.alert("Debe digitar el �ltimo semestre cursado."); 
	 document.forms["transferencia"].elements["LastSem"].focus(); 
	 return 0;
  }
  //5. Departamento de nacimiento.
  if(isNaN(document.forms["transferencia"].elements["DptoNac"].value)){
	 window.alert("'Departamento:', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["DptoNac"].focus();
	 return 0;
  }
  if(document.forms["transferencia"].elements["DptoNac"].value=="0"){
	 window.alert("Debe seleccionar un departamento."); 
	 document.forms["transferencia"].elements["DptoNac"].focus();
	 return 0;
  }
  //6. Ciudad de nacimiento.
  if(isNaN(document.forms["transferencia"].elements["CiudadNac"].value)){
	 window.alert("'Ciudad:', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["CiudadNac"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["CiudadNac"].value==""){
	 window.alert("Debe seleccionar una ciudad de nacimiento."); 
	 document.forms["transferencia"].elements["CiudadNac"].focus(); 
	 return 0;
	}
  //7. Fecha de nacimiento.
  if(document.forms["transferencia"].elements["FechaNac"].value==""){
	 window.alert("Debe ingresar una fecha de nacimiento."); 
	 document.forms["transferencia"].elements["FechaNac"].focus(); 
         document.forms["transferencia"].elements["FechaNac"].value=""; 
	 return 0;
  }
	fechacomp=("01/01/1998");
		fechanac=document.forms["transferencia"].elements["FechaNac"].value
		var xMonth=fechanac.substring(3, 4);  
		var xDay=fechanac.substring(0, 2);  
		var xYear1=fechanac.substring(6,10);
		var xYear2=fechanac.substring(4,8);
		var xYear3=fechanac.substring(5,9);
						
		if(!isNaN(xYear1)){
			if(xYear1 >= 1998){
				window.alert("El ano de nacimiento no esta correcto.");
				document.forms["transferencia"].elements["CraCod"].focus();
				return 0	
			}
		}
		else
		true;
		
		if(!isNaN(xYear2)){
			if(xYear2 >= 1998){
				window.alert("El ano de nacimiento no esta correcto.");
				document.forms["transferencia"].elements["CraCod"].focus();
				return 0	
			}
		}
		else
		true;
		
		if(!isNaN(xYear3)){
			if(xYear3 >= 1998){
				window.alert("El ano de nacimiento no esta correcto.");
				document.forms["transferencia"].elements["CraCod"].focus();
				return 0	
			}
		}
		else
		true;
		
  //8. Documento actual.
  if(isNaN(document.forms["transferencia"].elements["DocActual"].value)){
	 window.alert("'Documento actual:', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["DocActual"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["DocActual"].value==""){
	 window.alert("Debe ingresar el numero de su documento actual."); 
	 document.forms["transferencia"].elements["DocActual"].focus(); 
	 return 0;
  }
  //9. Documento Icfes.
  if(isNaN(document.forms["transferencia"].elements["DocIcfes"].value)){
	 window.alert("'Documento de identidad con el que present� el ICFES:', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["DocIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["DocIcfes"].value==""){
	 window.alert("Debe ingresar el numero del documento con que present� el ICFES."); 
	 document.forms["transferencia"].elements["DocIcfes"].focus(); 
	 return 0;
  }
  //10. Icfes.
  if(isNaN(document.forms["transferencia"].elements["NroIcfes"].value)){
	 window.alert("'N�mero del registro del icfes (SNP):', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["NroIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["NroIcfes"].value==""){
	 window.alert("Debe ingresar el n�mero del registro del icfes."); 
	 document.forms["transferencia"].elements["NroIcfes"].focus(); 
	 return 0;
  }
  if(isNaN(document.forms["transferencia"].elements["CNroIcfes"].value)){
	 window.alert("'N�mero del registro del icfes (SNP):', el dato debe ser num�rico");
	 document.forms["transferencia"].elements["CNroIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["CNroIcfes"].value==""){
	 window.alert("Debe ingresar el n�mero del registro del icfes."); 
	 document.forms["transferencia"].elements["CNroIcfes"].focus(); 
	 return 0;
  }
  
// Valida cuenta de correo
  if(document.forms["transferencia"].elements["CtaCorreo"].value==""){
	 window.alert("Debe ingresar un correo electronico.");
	 document.forms["transferencia"].elements["CtaCorreo"].focus(); 
	 return 0;
  }	

  if((document.forms["transferencia"].elements["CtaCorreo"].value.indexOf('@', 0) == -1)||(document.forms["transferencia"].elements["CtaCorreo"].value.length < 5)){
      window.alert("Escriba una direccion de correo valida.");
      return (false);
  }

    
//DATOS SOCIO-ECON�MICOS
  //12. Direcci�n actual de residencia.
  if(document.forms["transferencia"].elements["dir"].value==""){
	 window.alert("Debe digitar la direcci�n actual de residencia."); 
	 document.forms["transferencia"].elements["dir"].focus(); 
	 return 0;
  }
  //14. Tel�fono de residencia.
  if(document.forms["transferencia"].elements["tel"].value==""){
	 window.alert("Debe ingresar el n�mero de tel�fono de residencia."); 
	 document.forms["transferencia"].elements["tel"].focus(); 
	 return 0;
  }
  
  //El tel�fono debe ser num�rico sin espacios.
  if(isNaN(document.forms["transferencia"].elements["tel"].value)){
	 window.alert("El n�mero de tel�fono debe de ser digitado sin especios.");
	 document.forms["transferencia"].elements["tel"].focus(); 
	 return 0;
  }
  
  //15. Localidad del colegios
  if(document.forms["transferencia"].elements["LocCol"].value==""){
	 window.alert("Debe ingresar la localidad del colegio."); 
	 document.forms["transferencia"].elements["LocCol"].focus(); 
	 return 0;
  }
  document.forms["transferencia"].submit();
}
function ValidaSNP(){
	   //Compara SNP
  if(document.forms["transferencia"].elements["TipoIcfes"].value!= document.forms["transferencia"].elements["CVTipoIcfes"].value){
	 window.alert("El Número del registro del icfes (SNP) es diferente."); 
	 document.forms["transferencia"].elements["TipoIcfes"].focus(); 
	 return 0;
  }
  if(document.forms["transferencia"].elements["NroIcfes"].value!= document.forms["transferencia"].elements["CNroIcfes"].value){
	 window.alert("El Número del registro del icfes (SNP) es diferente."); 
	 document.forms["transferencia"].elements["TipoIcfes"].focus(); 
         document.forms["transferencia"].elements["CNroIcfes"].value=""; 
	 return 0;
  }
}

