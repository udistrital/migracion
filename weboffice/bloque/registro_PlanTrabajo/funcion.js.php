<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jquery_1.js" type="text/javascript"></script>

<?PHP
$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
?>


<script type="text/javascript">

	

	function pintarBlanco(obj) {
	    $(".celda_hora").removeClass("celda_tit_hor_disp");
	    $(".celda_hora").removeClass("celda_tit_hor_no_disp");
	}
	
	
	function pintarVerde(obj) {
	    $(".celda_hora").addClass("celda_tit_hor_disp");
	}

	function pintarRojo(obj) {
	    for (var i in obj) {
	      $("#"+i).removeClass("celda_tit_hor_disp");
	      $("#"+i).addClass("celda_tit_hor_no_disp");
	    }
	}
	
	function actualizarCeldaHora(idCelda,ano,per){

	    <? $cripto=new encriptar();?>
            
	    cod_salon=$("#salon").val();
	    cod_actividad=$("#actividad").val();
	    cod_vinculacion=$("#vinculacion").val();
	    cod_sede=$("#sede").val();

    if(cod_salon==="" || cod_salon==="-1" || typeof(cod_salon)==="undefined" || cod_actividad==="-1" || cod_actividad==="" || typeof(cod_actividad)==="undefined" || cod_vinculacion==="" || cod_vinculacion==="-1" || typeof(cod_vinculacion)==="undefined"){
                alert("Por favor seleccione el Tipo de vinculación, Actividad, Sede, Edificio y Salón para registrar la actividad.");
	   	$('html, body').animate({ scrollTop: ($(".encabezado_curso_salon").offset().top)-50 }, 500);
		
		$(".encabezado_curso_salon").fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#FA5858').fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#FA5858');
	
          
	    }else{
		$(".encabezado_curso_salon").css("background-color", 'white');
		$.ajax({
			type: 'GET',
			url: '<?=$indice?>',
			data: "formulario=<?=$cripto->codificar_url("no_pagina=registro_plan_trabajo&jxajax=actualizarHorario",$configuracion)?>&cod_salon="+cod_salon+"&cod_hora="+idCelda+"&ano="+ano+"&per="+per+"&cod_vinculacion="+cod_vinculacion+"&cod_actividad="+cod_actividad+"&cod_sede="+cod_sede,
			success: function(respuesta) {
				myJson=$.parseJSON(respuesta);

				if(myJson.mensaje!=""){
				    //alert(myJson.mensaje);
				}
                                $("#"+(myJson.cod_hora)).removeClass("celda_tit_hor_disp");
				$("#"+(myJson.cod_hora_nueva)).addClass("celda_tit_hor_no_disp");
				$("#"+(myJson.cod_hora_nueva)).html(myJson.data);
//                                $.each(myJson.actividad,function(key, value)
//                                {
//                                    valor_actividad="actividad"+key;
//                                    valor_carga_actividad="carga_actividad"+key;
//                                    $("#"+(myJson.valor_actividad)).html(myJson.data_actividad.key);
//                                    $("#"+(myJson.valor_carga_actividad)).html(myJson.data_carga_actividad.key);
//                                });

				$("#"+(myJson.actividad)).html(myJson.data_actividad);
				$("#"+(myJson.carga_actividad)).html(myJson.data_carga_actividad);
			}
		});
            }
	    
	}
	
	function registrarCeldaHora(idCelda,ano,per){

	    <? $cripto=new encriptar();?>
            
	    cod_salon=$("#salon").val();
	    cod_actividad=$("#actividad").val();
	    cod_vinculacion=$("#vinculacion").val();
	    cod_sede=$("#sede").val();

            if(cod_salon==="" || cod_salon==="-1" || typeof(cod_salon)==="undefined" || cod_actividad==="-1" || cod_actividad==="" || typeof(cod_actividad)==="undefined" || cod_vinculacion==="" || cod_vinculacion==="-1" || typeof(cod_vinculacion)==="undefined"){
                alert("Por favor seleccione el Tipo de vinculación, Actividad, Sede, Edificio y Salón para registrar la actividad.");
	   	$('html, body').animate({ scrollTop: ($(".encabezado_curso_salon").offset().top)-50 }, 500);
		
		$(".encabezado_curso_salon").fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#FA5858').fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#FA5858');
	
          
	    }else{
		$(".encabezado_curso_salon").css("background-color", 'white');
		$.ajax({
			type: 'GET',
			url: '<?=$indice?>',
			data: "formulario=<?=$cripto->codificar_url("no_pagina=registro_plan_trabajo&jxajax=registrarHorario",$configuracion)?>&cod_salon="+cod_salon+"&cod_hora="+idCelda+"&ano="+ano+"&per="+per+"&cod_vinculacion="+cod_vinculacion+"&cod_actividad="+cod_actividad+"&cod_sede="+cod_sede,
			success: function(respuesta) {
                                myJson=$.parseJSON(respuesta);

				if(myJson.registro!=""){
				    alert(myJson.registro);
				}
                                $("#"+(myJson.cod_hora)).removeClass("celda_tit_hor_disp");
				$("#"+(myJson.cod_hora_nueva)).addClass("celda_tit_hor_no_disp");
				$("#"+(myJson.cod_hora_nueva)).html(myJson.data);
				$("#"+(myJson.actividad)).html(myJson.data_actividad);
				$("#"+(myJson.carga_actividad)).html(myJson.data_carga_actividad);
                                
			}
		});
            }
	    
	}
	
	var mouseX;
	var mouseY;
	$(document).mousemove( function(e) {
	  mouseX = e.pageX; 
	  mouseY = e.pageY;
	});
	
	
	function borrarHorario(idCelda,ano,per){
	    <? $cripto=new encriptar();?>
	    
	    $.ajax({
		    type: 'GET',
		    url: '<?=$indice?>',
		    data: "formulario=<?=$cripto->codificar_url("no_pagina=registro_plan_trabajo&jxajax=borrarHorario",$configuracion)?>&cod_hora="+idCelda+"&ano="+ano+"&per="+per,    
		    success: function(respuesta) {
			    myJson=$.parseJSON(respuesta);
			    if(myJson.error!=""){
				alert("Mensaje:"+ myJson.error);
			    }else{
				$("#"+(myJson.cod_hora)).removeClass("celda_tit_hor_no_disp");
				$("#"+(myJson.cod_hora)).addClass("celda_tit_hor_disp");				
				$("#"+(myJson.cod_hora)).html(myJson.data);
				$("#"+(myJson.actividad)).html(myJson.data_actividad);
				$("#"+(myJson.carga_actividad)).html(myJson.data_carga_actividad);
			    }
		    }
	    });
	}
	
	
</script>
