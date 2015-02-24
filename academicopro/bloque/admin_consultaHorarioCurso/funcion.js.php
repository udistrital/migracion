<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jquery.js" type="text/javascript"></script>

<?PHP
$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
?>


<script type="text/javascript">

	

	function actualizarOcupacion(salon){
	      <? $cripto=new encriptar();?>
	      $.ajax({
		      type: 'GET',
		      url: '<?=$indice?>',
		      data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=consultarOcupacion",$configuracion)?>&cod_salon="+salon,
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
	
	function actualizarCeldaHora(idCelda){

	    <? $cripto=new encriptar();?>
	    cod_salon=$("#salon").val();
	    cod_curso=$("#id_curso").val();
	    
	    if(cod_salon==""){
	   	$('html, body').animate({ scrollTop: ($(".encabezado_curso_salon").offset().top)-50 }, 500);
		
		$(".encabezado_curso_salon").fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#F18E99').fadeOut(100).css("background-color", 'white').fadeIn(100).css("background-color", '#F18E99');
	
          
	    }else{
		$(".encabezado_curso_salon").css("background-color", 'white');
		$.ajax({
			type: 'GET',
			url: '<?=$indice?>',
			data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=actualizarHorario",$configuracion)?>&cod_hora="+idCelda+"&cod_salon="+cod_salon+"&cod_curso="+cod_curso,    
			success: function(respuesta) {
				myJson=$.parseJSON(respuesta);
				if(myJson.mensaje!=""){
				    alert(myJson.mensaje);
				}
				$("#"+(myJson.cod_hora)).addClass("celda_tit_hor_no_disp");
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
	
	function borrarHorario(idCelda){
	    <? $cripto=new encriptar();?>
	    
	    cod_salon=$("#salon").val();
	    cod_curso=$("#id_curso").val();
	    
	    $.ajax({
		    type: 'GET',
		    url: '<?=$indice?>',
		    data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=borrarHorario",$configuracion)?>&cod_hora="+idCelda+"&cod_salon="+cod_salon+"&cod_curso="+cod_curso,    
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
	
	function rescatarSalonesCompletos(idsede){
	    <? $cripto=new encriptar();?>

	    $.ajax({
		    type: 'GET',
		    url: '<?=$indice?>',
		    data: "formulario=<?=$cripto->codificar_url("no_pagina=adminConsultaHorarioCurso&jxajax=rescatarSalonesCompletos",$configuracion)?>&cod_sede="+idsede,    
		    success: function(respuesta) {
			$("#buscador_salones").html(respuesta);
		    }
	    });
	}
	
	
</script>