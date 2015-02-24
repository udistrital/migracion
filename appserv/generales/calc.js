<!--
/**********************************************************************************   
calc.js
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
function addChar(input, caracter){
if(input.value == null || input.value == "0")
	input.value = caracter
else
	input.value += caracter
}

function cos(form){form.display.value = Math.round(Math.cos(form.display.value));}

function sin(form){form.display.value = Math.round(Math.sin(form.display.value));}

function tan(form){form.display.value = Math.tan(form.display.value);}

function pi(form){form.display.value = Math.PI;}

function pow2(form){form.display.value = Math.pow(form.display.value,2);}

function sqrt(form){form.display.value = Math.sqrt(form.display.value);}

function ln(form){form.display.value = Math.log(form.display.value);}

function exp(form){form.display.value = Math.exp(form.display.value);}

function deleteChar(input){input.value = input.value.substring(0, input.value.length - 1);}

function compute(form){form.display.value = eval(form.display.value);}

function compute2(form){form.display.value = eval(form.display.value)/100;}

function square(form){form.display.value = eval(form.display.value) * eval(form.display.value);}

function changeSign(input){
  if(input.value.substring(0, 1) == "-")
	 input.value = input.value.substring(1, input.value.length)
  else
	input.value = "-" + input.value
}

function checkNum(str){
  for(var i = 0; i < str.length; i++) {
	  var ch = str.substring(i, i+1)
	  if(ch < "0" || ch > "9") {
		 if(ch != "/" && ch != "*" && ch != "+" && ch != "-" && ch != "."
			&& ch != "(" && ch!= ")") {
			alert("Dato Invalido!")
			return false
		  }
	  }
  }
  return true
}