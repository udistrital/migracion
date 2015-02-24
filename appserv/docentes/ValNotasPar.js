<!--
/**********************************************************************************
LisLov
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function calcula_acu(nom) {
	var acu = document.getElementById("fnotpar").elements["p1"].value*1
	+ document.getElementById("fnotpar").elements["p2"].value*1 + document.getElementById("fnotpar").elements["p3"].value*1
	+ document.getElementById("fnotpar").elements["p4"].value*1 + document.getElementById("fnotpar").elements["p5"].value*1
	+ document.getElementById("fnotpar").elements["pl"].value*1 + document.getElementById("fnotpar").elements["pe"].value*1;

	document.getElementById("fnotpar").elements["TO"].value = acu;

	if(acu > 100){
	   alert('La sumatoria de los porcentajes no debe ser mayor al 100%.');
	   document.getElementById("fnotpar").elements[nom].value="";
	   document.getElementById("fnotpar").elements[nom].focus();
	}
}
function val_nota(nombre_campo){
	var elemento = document.getElementById(nombre_campo);
	var valor= elemento.value;
	var valora = valor.toString();
	if(valora.substring(2, 1) == "," || valora.substring(2, 1) == "."){
       alert("Digite las notas sin PUNTO ni COMA.");
	   document.forms["fnotpar"].elements[nombre_campo].value="";
	}
	if(valor > 50){
       alert("Las nota no pueden ser mayores de 50. Art. 43 Estatuto Estudiantil.");
	   document.forms["fnotpar"].elements[nombre_campo].value="";
	}
}
// -->