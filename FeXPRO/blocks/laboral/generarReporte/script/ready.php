<?php 
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
//if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){

?>

        // Asociar el widget de validación al formulario
        $("#generarReporte").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

        $(function() {
            $("#generarReporte").submit(function() {
                $resultado=$("#generarReporte").validationEngine("validate");
                
                if ($resultado) {
                
                    return true;
                }
                return false;
            });
        });

        $("#idUsuario").select2();
		
		$("#idUsuario").change(function() {
		mostrar();
		});
	
<?php 
//}
?>



