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
		      data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=consultarOcupacion",$configuracion)?>&cod_salon="+salon+"&anio="+anio+"&periodo="+periodo,
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
	
	function actualizarCeldaHora(idCelda,anio,periodo){

	    <? $cripto=new encriptar();?>
	    cod_salon=$("#salon").val();
	    cod_curso=$("#id_curso").val();
            alert("actualizaCelda"+idCelda + anio + periodo + "sal"+cod_salon + "cur"+cod_curso);
	    
	    if(cod_salon==""){
	   	$('html, body').animate({ scrollTop: ($(".encabezado_curso_salon").offset().top)-50 }, 500);
		
		$(".encabezado_curso_salon").fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#FA5858').fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#FA5858');
	
          
	    }else{
		$(".encabezado_curso_salon").css("background-color", 'white');
		$.ajax({
			type: 'GET',
			url: '<?=$indice?>',
			data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=actualizarHorario",$configuracion)?>&cod_salon="+cod_salon+"&cod_hora="+idCelda+"&cod_curso="+cod_curso+"&anio="+anio+"&periodo="+periodo,    
			success: function(respuesta) {
				myJson=$.parseJSON(respuesta);
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
	
	function borrarHorario(idCelda,anio,periodo){
	    <? $cripto=new encriptar();?>
	    
	    cod_salon=$("#salon").val();
	    cod_curso=$("#id_curso").val();
	    
	    $.ajax({
		    type: 'GET',
		    url: '<?=$indice?>',
		    data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=borrarHorario",$configuracion)?>&cod_salon="+cod_salon+"&cod_hora="+idCelda+"&cod_curso="+cod_curso+"&anio="+anio+"&periodo="+periodo,    
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
		    data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=rescatarSalonesCompletos",$configuracion)?>&anio="+anio+"&periodo="+periodo+"&capacidad="+capacidad+"&cod_sede="+idsede,    
		    success: function(respuesta) {
			$("#buscador_salones").html(respuesta);
		    }
	    });
	}
	
	
</script>