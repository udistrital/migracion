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
include_once($ruta . "/locale/es_es/Mensaje.php");


$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");

//Se debe tener una variable llamada procesarAjax
$cadenaACodificar.="&procesarAjax=true";
$cadenaACodificar.="&bloqueNombre=" . $esteBloque["nombre"];
$cadenaACodificar.="&bloqueGrupo=" . $esteBloque["grupo"];
$cadenaACodificar.="&action=index.php";

$campo = array("#departamento");

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