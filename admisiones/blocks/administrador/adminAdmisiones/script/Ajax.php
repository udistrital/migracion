<?php
/**
 * Este archivo se utiliza para registrar las funciones javascript que sirven para peticiones AJAX. 
 * Se implementa antes de procesar cualquier bloque al momento de armar la página.
 * 
 * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 *
 * El archivo procesarAjax.php (carpeta funcion) tiene la tarea de procesar la peticiones ajax conforme a la variable
 * funcion que se registra en la URL.
 *
 */
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url.=$this->miConfigurador->getVariableConfiguracion("site");
$url.="/index.php?";

$ruta = $this->miConfigurador->getVariableConfiguracion("raizDocumento");
$ruta.="/blocks/" . $esteBloque["grupo"] . "/" . $esteBloque["nombre"] . "/";
$directorioImagenes = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/images";

$urlImagenes = $this->miConfigurador->getVariableConfiguracion("host");
$urlImagenes.=$this->miConfigurador->getVariableConfiguracion("site");
$urlImagenes.="/blocks/" . $esteBloque["grupo"] . "/" . $esteBloque["nombre"] . "/images";;

$rutaBloque=$this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/theme/basico/css/";
     
//Incluir el archivo de idioma
/**
 * @todo Rescatar el valor del idioma desde la sesión. En la actualidad de forma predeterminada se utiliza es_es
 */
//include_once($ruta . "/locale/es_es/Mensaje.php");


$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");

//Se debe tener una variable llamada procesarAjax
$cadenaACodificar.="&procesarAjax=true";
$cadenaACodificar.="&bloqueNombre=" . $esteBloque["nombre"];
$cadenaACodificar.="&tipo=".$_REQUEST['tipo'];
$cadenaACodificar.="&bloqueGrupo=" . $esteBloque["grupo"];
$cadenaACodificar.="&action=index.php";

$campo = array("#tablaInscripciones");

?>
<script type='text/javascript'>
<?php
foreach ($campo as $valor) {
    $cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
    $enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
    $laurl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
    
    ?>
    
    $(document).ready(function(){
       $("#departamento").change(function () {
               $("#departamento option:selected").each(function () {
                codDepto=$(this).val();
                //alert (codDepto);
                $.post("<?php echo $laurl ?>", { codDepto: codDepto }, function(data){
                $("#municipio").html(data);
                });            
            });
       })
    });
    
   $(document).ready(function(){ 
   $("#tipoIcfes").change(function () {
               $("#tipoIcfes option:selected").each(function () {
                tipIcfes=$(this).val();
                //alert (tipIcfes);
                $.post("<?php echo $laurl ?>", { tipIcfes: tipIcfes }, function(data){
                    $("#registroIcfes1").attr('value', data);
                });            
            });
       })
   });
   
    $(document).ready(function(){ 
   $("#tipoIcfes").change(function () {
               $("#tipoIcfes option:selected").each(function () {
                tipIcfes=$(this).val();
                //alert (tipIcfes);
                $.post("<?php echo $laurl ?>", { tipIcfes: tipIcfes }, function(data){
                    $("#registroIcfes2").attr('value', data);
                });            
            });
       })
   });
   
   $(document).ready(function(){ 
   $("#tipoIcfes").change(function () {
               $("#tipoIcfes option:selected").each(function () {
                tipIcfes=$(this).val();
                //alert (tipIcfes);
                $.post("<?php echo $laurl ?>", { tipIcfes: tipIcfes }, function(data){
                    $("#confirmarRegistroIcfes").attr('value', data);
                });            
            });
       })
   });
   
   $(document).ready(function(){
        $('#tablaInscripciones').dataTable( {
                        "sPaginationType": "full_numbers",
                        "aaSorting": [[ 2, "asc" ]],
                        "bPaginate": true,
                        "bLengthChange": true,
                        "bFilter": true,
                        "bSort": true,
                        "bInfo": true,
                        "bJQueryUI": true,
                        "bAutoWidth": true, 
                        "processing": true,
                        "serverSide": true,
                        "bProcessing": true,
                        "sAjaxSource": "<?php echo $laurl ?>",
                         "aoColumns": [
                                { mData: 'Año' } ,
                                { mData: 'Periodo' },
                                { mData: 'Credencial' },
                                { mData: 'Inscripcion' },
                                { mData: 'Nombre' },
                                { mData: 'Apellido' },
                                { mData: 'Identificacion' },
                                { mData: 'Carrera' },
                                { mData: 'SNP' }
                        ]
         } );
    });
    
    //AL seleccionar una localidad envía a procesar ajax para que se guarde en base de datos
    //variable[2] se rescata de lo que envía el formualario, y esta es una variable concatenada desde el sql
    //Se hizo esto porque despues de change(function) el bucle imprime solamente el último valor.
    $(document).ready(function(){
        var localidad;
        var valor;
        for(var $i=0; $i<30000; $i++){
        localidad="#localidadRes"+$i;
        $(localidad).change(function () {
            var opcion = $(this).val();
            var variable = opcion.split('-');
            $("#localidadRes"+variable[2]+" option:selected").each(function () {
                
                del = confirm('¿Esta seguro, que lo desea modificar la localidad de residencia?'); 
                codLocalidad=$(this).val();
                if(del){ 
                    $.post("<?php echo $laurl ?>", { codLocalidad: codLocalidad }, function(data){
                        $("#guardarLocalidad").html(data);
                    });
                }else {
                    return false; 
                } 
            });
        })
        }
    });
    
    $(document).ready(function(){
        var localidad;
        var valor;
        for(var $i=0; $i<3; $i++){
        localidad="#localidadCol"+$i;
        $(localidad).change(function () {
            var opcion = $(this).val();
            var variable = opcion.split('-');
            $("#localidadCol"+variable[2]+" option:selected").each(function () {
                del = confirm('¿Esta seguro, que lo desea modificar la localidad del colegio?'); 
                codLocalidadCol=$(this).val();
                if(del){ 
                    $.post("<?php echo $laurl ?>", { codLocalidadCol: codLocalidadCol }, function(data){
                        $("#guardarLocalidad").html(data);
                    });
                }else {
                    return false; 
                } 
            });
        })
        }
    });
    
    $(document).ready(function(){
        var estrato;
        var valor;
        for(var $i=0; $i<3; $i++){
        estrato="#estrato"+$i;
        $(estrato).change(function () {
            var opcion = $(this).val();
            var variable = opcion.split('-');
            $("#estrato"+variable[2]+" option:selected").each(function () {
                del = confirm('¿Esta seguro, que lo desea modificar el estrato?'); 
                estratoRes=$(this).val();
                if(del){ 
                    $.post("<?php echo $laurl ?>", { estratoRes: estratoRes }, function(data){
                        $("#guardarLocalidad").html(data);
                    });
                }else {
                    return false; 
                } 
            });
        })
        }
    });

    $(document).ready(function(){
        var tipoinscripcion;
        var valor;
        for(var $i=0; $i<3; $i++){
        tipoinscripcion="#tipinscripcion"+$i;
        $(tipoinscripcion).change(function () {
            var opcion = $(this).val();
            var variable = opcion.split('-');
            $("#tipinscripcion"+variable[2]+" option:selected").each(function () {
                del = confirm('¿Esta seguro, que lo desea modificar el tipo de inscripcion?'); 
                tipIns=$(this).val();
                if(del){ 
                    $.post("<?php echo $laurl ?>", { tipIns: tipIns }, function(data){
                        $("#guardarLocalidad").html(data);
                    });
                }else {
                    return false; 
                } 
            });
        })
        }
    });
    
    
    /*$(document).ready(function(){
        $("#estrato").change(function () {
            $("#estrato option:selected").each(function () {
                del = confirm('¿Esta seguro, que lo desea modificar el estrato?'); 
                estratoRes=$(this).val();
                if(del){ 
                    $.post("<?php echo $laurl ?>", { estratoRes: estratoRes }, function(data){
                        $("#guardarLocalidad").html(data);
                    });
                }else {
                    return false; 
                } 
            });
        })
    });*/
    
    <?php
}
?>
    
 $("#botonImprimirA").live("click", function () {
         var divContents = $("#printthisdiv").html();
         var printWindow = window.open('', '', 'height=600,width=800');
         printWindow.document.write('<html><head><title>Comprobante de inscripción</title>');
         printWindow.document.write('<link href=\"<?php echo $rutaBloque?>/general.css\" rel=\"stylesheet\" type=\"text/css\" />');
         printWindow.document.write('<link href=\"<?php echo $rutaBloque?>/jquery-ui.css\" rel=\"stylesheet\" type=\"text/css\" />');
         printWindow.document.write('<link href=\"<?php echo $rutaBloque?>/estiloTexto.css\" rel=\"stylesheet\" type=\"text/css\" />');
         printWindow.document.write('<link href=\"<?php echo $rutaBloque?>/estiloCuadrosMensaje.css\" rel=\"stylesheet\" type=\"text/css\" />');
         //printWindow.document.write('<link href=\"<?php echo $rutaBloque?>/estiloFormulario.css\" rel=\"stylesheet\" type=\"text/css\" />');
        //printWindow.document.write('<link href=\"general.css\" rel=\"stylesheet\" type=\"text/css\" />');
         printWindow.document.write('</head><body >');
         printWindow.document.write(divContents);
         printWindow.document.close();
         printWindow.print();
     });   

</script>
