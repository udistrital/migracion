<?php 
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
//if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){

?>
	$(function() {
		                	
                $("#consultar").click(function() {
                
                    if($("#periodo").val() == "")
                    {
                        alert('Debe seleccionar un periodo académico');
                        return false;
                    }
                    
                    if($("#nombreDoc").val() == "")
                    {
                        alert('Debe digitar el nombre o identificación del docente');
                        return false;
                    }                                       
                                                        
                        consultarDocente();
                
                });
                
	});
	
        $("#numIdentificacion").keydown(function(event){
                    if((event.keyCode < 46 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && (event.keyCode!=8) && (event.keyCode!=9) && (event.keyCode < 37 || event.keyCode > 40)){
                   return false;
                       }
                    });
        $("#periodo").select2();            
            
	$("#proyecto").select2();
        
        $("#periodoTabCurso").select2();            
            
	$("#proyectoTabCurso").select2();
        
        $("#cursos").select2();
        
        $(function() {
		$(document).tooltip();
	});
	
	// Asociar el widget tabs a la división cuyo id es tabs
	$(function() {
		$("#tabs").tabs();
	});

	
<?php 
//}
?>



