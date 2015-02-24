<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/formulario/lib/jquery.js" type="text/javascript"></script>
<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/formulario/lib/jquery.metadata.js" type="text/javascript"></script>
<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/formulario/jquery.validate.js" type="text/javascript"></script>

<?PHP
$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
?>


<script type="text/javascript">

	limpiarValoresFormIns();
	
	
	$().ready(function() {
		$("#form_consulta").validate();
		$("#form_insertar").validate({
			submitHandler: function(form){
					form.submit();
			}
		});
	});

	

	function consultarAsignatura(valor){
		
		limpiarValoresFormIns();
		<? $cripto=new encriptar();?>
		
		estudiante=$("#form_insertar #estudiante").val();
		$.ajax({
			type: 'GET',
			url: '<?=$indice?>',
			data: "formulario=<?=$cripto->codificar_url("no_pagina=adminNovedadesNotas&jxajax=consultarAsignatura",$configuracion)?>&asignatura="+valor+"&estudiante="+estudiante,
			success: function(respuesta) {

				myJson=$.parseJSON(respuesta);

				$("#form_insertar #nombreAsignaturaInsertar").html(myJson.nombreAsignatura);
				$("#form_insertar #semestre").val(myJson.nivel);
				
				
				if(myJson.cred!=undefined){
					$("#form_insertar #creditos").attr('value',myJson.cred);
					$("#form_insertar #creditos").attr('readonly', 'readonly');
				}
				
				if(myJson.htd!=undefined){
					$("#form_insertar #hteoricas").attr('value',myJson.htd);
					$("#form_insertar #hteoricas").attr('readonly', 'readonly');
				}
				
				if(myJson.htc!=undefined){
					$("#form_insertar #hpracticas").attr('value',myJson.htc);
					$("#form_insertar #hpracticas").attr('readonly', 'readonly');
				}
				if(myJson.hta!=undefined){
					$("#form_insertar #hautonomo").attr('value',myJson.hta);
					$("#form_insertar #hautonomo").attr('readonly', 'readonly');
				}
				if(myJson.ht!=undefined){

					$("#form_insertar #hteoricas").attr('value',myJson.ht);
					$("#form_insertar #hteoricas").attr('readonly', 'readonly');
				}
				if(myJson.hp!=undefined){
					$("#form_insertar #hpracticas").attr('value',myJson.hp);
					$("#form_insertar #hpracticas").attr('readonly', 'readonly');				
				}
				if(myJson.ceacod!=undefined){
					$("#form_insertar #ceacod option[value="+myJson.ceacod+"]").attr("selected",true);
				}				
			}
		});
		
	}

	function limpiarValoresFormIns(){
		$("#form_insertar #nombreAsignaturaInsertar").html("");
		$("#form_insertar #grupo").val("0");
		$("#form_insertar #semestre").val("");
		$("#form_insertar #creditos").val("");
		$("#form_insertar #creditos").removeAttr("readonly");
		$("#form_insertar #hteoricas").val("");
		$("#form_insertar #hteoricas").removeAttr("readonly");
		$("#form_insertar #hpracticas").val("");
		$("#form_insertar #hpracticas").removeAttr("readonly");
		$("#form_insertar #hautonomo").val("");
		$("#form_insertar #hautonomo").removeAttr("readonly");

	}
	

</script>