<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jquery.js" type="text/javascript"></script>

<?PHP
$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
?>


<script type="text/javascript">

	

	function actualizarOcupacion(salon,anio,periodo){
	      <? $cripto=new encriptar();?>
	      $.ajax({
		      type: 'GET',
		      url: '<?=$indice?>',
		      data: "formulario=<?=$cripto->codificar_url("no_pagina=registro_plan_trabajo&jxajax=consultarOcupacion",$configuracion)?>&cod_salon="+salon+"&anio="+anio+"&periodo="+periodo,
		      success: function(respuesta) {
			      myJson=$.parseJSON(respuesta);
			      pintarBlanco(myJson);
			      pintarVerde(myJson);
			      pintarRojo(myJson);
			      
		      }
	      });
		
	}
	
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
            alert("actualizaCelda"+idCelda + ano + per + "sal"+cod_salon+"vinc="+cod_vinculacion+"activ"+cod_actividad+"sede"+cod_sede);
	    
	    if(cod_salon==""||cod_actividad==""||cod_vinculacion==""){
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
                                alert("Mensaje:"+ myJson.mensaje);

				if(myJson.mensaje!=""){
				    alert(myJson.mensaje);
				}
                                $("#"+(myJson.cod_hora)).removeClass("celda_tit_hor_disp");
				$("#"+(myJson.cod_hora_nueva)).addClass("celda_tit_hor_no_disp");
				$("#"+(myJson.cod_hora_nueva)).html(myJson.data);
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
	
	function salonActual(){
	  // $('#info_salon').offset({left:mouseX, top:mouseY});
	   /* $("#info_salon").animate({
		top:mouseY ,
		left:mouseX
	    }, 800);*/
	}
	
	function borrarHorario(idCelda,ano,per){
            alert("borrar"+idCelda + ano + per);
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
			    }
		    }
	    });
	}
	
	function rescatarSalonesCompletos(anio,periodo,capacidad,idsede){
	    <? $cripto=new encriptar();?>

	    $.ajax({
		    type: 'GET',
		    url: '<?=$indice?>',
		    data: "formulario=<?=$cripto->codificar_url("no_pagina=registro_plan_trabajo&jxajax=rescatarSalonesCompletos",$configuracion)?>&anio="+anio+"&periodo="+periodo+"&capacidad="+capacidad+"&cod_sede="+idsede,    
		    success: function(respuesta) {
			$("#buscador_salones").html(respuesta);
		    }
	    });
	}
	
	
</script>