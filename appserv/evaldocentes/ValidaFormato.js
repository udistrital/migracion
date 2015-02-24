var resultado;
resultado = '';
function validar_rgroup(rgroup) {
	var algo_pinchado;
	algo_pinchado = 'no';
	resultado='';
	for (var i=0; i<rgroup.length; i++) {
		if (rgroup[i].checked) {
			algo_pinchado = 'si';
			break;
		}
	}

	if (algo_pinchado == 'no'){
	resultado = resultado + 'Selecciona un valor para la pregunta: ' + rgroup[0].name + '\n';
	}
	
	return resultado;
}

function validar_form(formulario) {
	f=formulario.text_fmto.value;
	//alert('mmm' + f);
	if((f != 7) && (f != 15)){
	//for (var j=1; j<=12; j++){
		//resp="r"+j;
		//alert (resp);
		resultado=validar_rgroup(formulario.r1);
		resultado=resultado+validar_rgroup(formulario.r2);
		resultado=resultado+validar_rgroup(formulario.r3);
		if(f!=8){
			resultado=resultado+validar_rgroup(formulario.r4);
			resultado=resultado+validar_rgroup(formulario.r5);
			resultado=resultado+validar_rgroup(formulario.r6);
		}
		if(f!= 8 && f!= 14){
			resultado=resultado+validar_rgroup(formulario.r7);
		}
		if(f!= 8 && f!= 14 && f!= 9){
			resultado=resultado+validar_rgroup(formulario.r8);
			resultado=resultado+validar_rgroup(formulario.r9);
		}
		if(f!= 8 && f!= 14 && f!= 9 && f!= 17){
			resultado=resultado+validar_rgroup(formulario.r10);
		}
		if (f!= 8 && f!= 14 && f!= 9 && f!= 17 && f!= 11){
			resultado=resultado+validar_rgroup(formulario.r11);
		}
		if (f!= 8 && f!= 14 && f!= 9 && f!= 17 && f!= 11 && f!= 6){
			resultado=resultado+validar_rgroup(formulario.r12);
		}
		if (f!= 8 && f!= 14 && f!= 9 && f!= 17 && f!= 11 && f!= 6 && f!= 16){
			resultado=resultado+validar_rgroup(formulario.r13);
		}
		if (f!= 8 && f!= 14 && f!= 9 && f!= 17 && f!= 11 && f!= 6 && f!= 16 && f!= 13){
			resultado=resultado+validar_rgroup(formulario.r14);
		}
		if (f!= 8 && f!= 14 && f!= 9 && f!= 17 && f!= 11 && f!= 6 && f!= 16 && f!= 13 && f!= 12){
			resultado=resultado+validar_rgroup(formulario.r15);
			resultado=resultado+validar_rgroup(formulario.r16);
			resultado=resultado+validar_rgroup(formulario.r17);
			resultado=resultado+validar_rgroup(formulario.r18);
			resultado=resultado+validar_rgroup(formulario.r19);
			resultado=resultado+validar_rgroup(formulario.r20);
		}
			
	}
		
	if (resultado!= '') {
		salida = 'Debes corregir los siguientes aspectos en tu evaluacion: \n' + resultado;
		alert(salida);
	}
	else
	{
		//alert("si pasa");
		formulario.submit();
	}
}