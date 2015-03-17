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

$campo = array("#nombreDoc", "#nombreDocNuevo","#nombreDocNuevo");
?>
<script type='text/javascript'>
<?php
foreach ($campo as $valor) {

    $cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
    $enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
    $laurl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
    ?>

        $(function() {
            $("<?php echo $valor ?>").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "<?php echo $laurl ?>",
                        dataType: "json",
                        data: {
                            featureClass: "P",
                            style: "full",
                            maxRows: 12,
                            name_startsWith: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item, i) {
                                return item;
                            }));
                        }
                    });
                },
                minLength: 3,
                autofocus: true,
                open: function() {
                    $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                },
                close: function() {
                    $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
                },
                select: function(event, ui) {
                    event.preventDefault();
                    //$("<?php //echo $valor  ?>").val(ui.item.label);
                    <?php
                    if($valor == '#nombreDoc')
                        {
                            ?>
                             $("#identificacionFinal").val(ui.item.value);    
                            <?php
                        }else
                            {
                            ?>
                             $("<?php echo $valor  ?>").val(ui.item.label);
                            <?php
                            }
                    ?>
                   
                },
                focus: function(event, ui) {
                    event.preventDefault();
                    $("<?php echo $valor ?>").val(ui.item.label);
                }


            });
        });

    <?php
}
?>
<?php
$valor = "#divDatosDocente";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>

    function consultarDocente() {
        $("#divDatosProyecto").css("display", "block");
        var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacion: $("#identificacionFinal").val(),
                nombreDoc: $("#nombreDoc").val()
            }
        })
                .done(function(data) {
            $('<?php echo $valor ?>').replaceWith(data);

        })
                .fail(function() {
            alert("error");
        });

    }
    
<?php
$valor = "#curso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>

    function poblarCurso() {

        var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                idProyecto: $("#proyecto").val()
            }
        })
                .done(function(data) {
            $('<?php echo $valor ?>').replaceWith(data);

        })
                .fail(function() {
            alert("error");
        });

    }
    
<?php
$valor = "#cuerpoHorario";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function cargarHorario()
    {
            $("#horario").css("display", "block");
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                curso: $("#cursos").val(),
                ano: 2013,
                periodo: 3
                }
            })
                    .done(function(data) {
                $('<?php echo $valor ?>').replaceWith(data);

            })
                    .fail(function() {
                alert("error");
            });
    }
    
<?php
$valor = "#cuerpoAsignacion";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function cargarAsignacion()
    {
            $("#horario").css("display", "block");
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                curso: $("#cursos").val(),
                ano: 2013,
                periodo: 1
                }
            })
                    .done(function(data) {
                $('<?php echo $valor ?>').replaceWith(data);

            })
                    .fail(function() {
                alert("error");
            });
    }    
<?php
$valor = "#adicionDocente";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
function adicionarDocente()
{
        //$("#adicionarDocente").css("display", "block");   
        $("#docenteNuevo").select2();
        $("#adicionarDocente").toggle("slow");        
}  

<?php
$valor = "#respuestaGuardar";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function guardarDocente()
    {        
        $("#respuestaGuardar").css("display", "block");    
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteNuevo: $("#docenteNuevo").val(),
                horasDocNuevo: $("#horasDocNuevo").val(),
                periodo: $("#periodo").val(),
                curso:$("#cursos").val(),
                proyecto:$("#proyecto").val()
                }
            })
                    .done(function(data) {
                $('<?php echo $valor ?>').replaceWith(data);

            })
                    .fail(function() {
                alert("error");
            });
    } 
    
    
    
 
<?php
$valor = "#respuestaModificar";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function modificarHorasDocente(identificacion)
    {        
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteModificar: identificacion,
                horasDoc: $("#horasDoc" + identificacion).val(),
                periodo: $("#periodo").val(),
                curso:$("#cursos").val(),
                proyecto:$("#proyecto").val()
                }
            })
            .done(function(data) {
                
                if(data === 'error')
                {
                    alert('No se realizo la actualización');
                }else
                {
                    $('#horas' + identificacion).replaceWith(data);
                }
                var divMostrar = "#modifica" + identificacion; 
                $(divMostrar).toggle("slow");

            })
            .fail(function() {
                alert("error");
            });
    } 
    
    function eliminarDocente(identificacion)
    {        
           var divEliminar = "#elimina" + identificacion; 
           //$(divMostrar).css("display", "block");  
           $(divEliminar).toggle("slow");
    } 
    
    <?php
$valor = "#respuestaEliminar";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function eliminarHorasDocente(identificacion)
    {        
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteEliminar: identificacion,
                periodo: $("#periodo").val(),
                curso:$("#cursos").val(),
                proyecto:$("#proyecto").val()
                }
            })
            .done(function(data) {
                alert(data);
                if(data === 'error')
                {
                    alert('No se realizo la eliminación');
                }else
                {
                    alert('Se borro la asignación con exito');
                }
                var divElimina = "#elimina" + identificacion; 
                $(divElimina).toggle("slow");

            })
            .fail(function() {
                alert("error");
            });
    }
<?php
$valor = "#cursosTodos";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function poblarCursosTodos()
    {
            
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                proyecto: $("#proyectoTabCurso").val(),
                periodo: $("#periodoTabCurso").val()
                }
            })
            .done(function(data) {
                $('<?php echo $valor ?>').html(data);

            })
            .fail(function() {
                alert("error");
            });
    }
    
    function mostrarInfoCurso(idCurso)
    {        
        var divInfo = "#info" + idCurso;
        var imagen = $("#flecha" + idCurso);

        if($(divInfo).css("display") === 'none')
        {
            
            imagen.attr("src", "<?php echo $urlImagenes;?>/uparrow.png");
            $(divInfo).toggle("slow");
            $("#imgAdicionDocente" + idCurso).css("display","block");
            cargarAsignacionCurso(idCurso,divInfo); 
        }else
        {
            imagen.attr("src", "<?php echo $urlImagenes;?>/rightarrow.png");
            $("#imgAdicionDocente" + idCurso).css("display","none");
            $(divInfo).toggle("slow");
        }
        
           
    } 
    
<?php
$valor = "#cuerpoAsignacionCurso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function cargarAsignacionCurso(idCurso,divInfo)
    {
            var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                curso: idCurso,
                periodo: $("#periodoTabCurso").val()
                }
            })
                    .done(function(data) {
                $(divInfo).html(data);

            })
                    .fail(function() {
                alert("error");
            });
            
    }
    



<?php
$valor = "#respuestaGuardar";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function guardarDocente()
    {        
        $("#respuestaGuardar").css("display", "block");    
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteNuevo: $("#docenteNuevo").val(),
                horasDocNuevo: $("#horasDocNuevo").val(),
                periodo: $("#periodo").val(),
                curso:$("#cursos").val(),
                proyecto:$("#proyecto").val()
                }
            })
                    .done(function(data) {
                $('<?php echo $valor ?>').replaceWith(data);

            })
                    .fail(function() {
                alert("error");
            });
    } 
    
<?php
$valor = "#respuestaModificar";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function modificarHorasDocente(identificacion)
    {        
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteModificar: identificacion,
                horasDoc: $("#horasDoc" + identificacion).val(),
                periodo: $("#periodoTabCurso").val(),
                curso:$("#cursos").val(),
                proyecto:$("#proyectoTabCurso").val()
                }
            })
            .done(function(data) {
                
                if(data === 'error')
                {
                    alert('No se realizo la actualización');
                }else
                {
                    $('#horas' + identificacion).replaceWith(data);
                }
                var divMostrar = "#modifica" + identificacion; 
                $(divMostrar).toggle("slow");

            })
            .fail(function() {
                alert("error");
            });
    } 
    
    
<?php
$valor = "#docenteNuevoCurso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>      
   function adicionarDocenteCurso(idCurso)
    {
        //mostrarInfoCurso(idCurso);
        $("#adicionarDocenteCurso" + idCurso).toggle("slow");
        
        $(function() {
            $("#docenteNuevoCurso" + idCurso).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "<?php echo $estaUrl ?>",
                        dataType: "json",
                        data: {
                            featureClass: "P",
                            style: "full",
                            maxRows: 12,
                            name_startsWith: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item, i) {
                                return item;
                            }));
                        }
                    });
                },
                minLength: 3,
                autofocus: true,
                open: function() {
                    $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                },
                close: function() {
                    $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
                },
                select: function(event, ui) {
                    event.preventDefault();
                    $("#iddocenteNuevoCurso" + idCurso).val(ui.item.value);    
                },
                focus: function(event, ui) {
                    event.preventDefault();
                    $("#docenteNuevoCurso" + idCurso).val(ui.item.label);
                }


            });
            
            $("#docenteNuevoTipVinCurso" + idCurso).select2();
            
        });

                
}      
    
<?php
/*$valor = "#adicionDocenteCurso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);*/
?>    
function adicionarHorasDocCurso(idCurso, idHorario, posicion)
{    
    if($("#iddocenteNuevoCurso" + idCurso).val() === '')
    {
        alert('Debe seleccionar el docente a asignar carga');
        
    }else if(($("#docenteNuevoTipVinCurso" + idCurso).val() === '') || ($("#docenteNuevoTipVinCurso" + idCurso).val() === '0'))
    {
        alert('Debe seleccionar el tipo de vinculación');
        
    }else
    {
        if($("#"+ idCurso + "adiciona" + posicion).hasClass('celda_hora'))
        {
            var nuevasHoras = parseFloat($("#contHorasSelecCurso"+idCurso).val());
            var totalHoras = nuevasHoras + 1;
            $("#contHorasSelecCurso"+idCurso).val(totalHoras);
            $('#horasSelecCurso'+idCurso).html(totalHoras);
            $("#"+ idCurso + "adiciona" + posicion).removeClass("celda_hora").addClass("celda_hora_seleccionada"); 
            
        }else if($("#"+ idCurso + "adiciona" + posicion).hasClass('celda_hora_seleccionada'))
        {
            var nuevasHoras = parseFloat($("#contHorasSelecCurso"+idCurso).val());
            var totalHoras = nuevasHoras - 1;
            $("#contHorasSelecCurso"+idCurso).val(totalHoras);
            $('#horasSelecCurso'+idCurso).html(totalHoras);
            $("#"+ idCurso + "adiciona" + posicion).removeClass("celda_hora_seleccionada").addClass("celda_hora");
            
        }
        
    }
               
}
<?php
$valor = "#respuestaGuardarCurso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function guardarDocenteCurso(idCurso)
    {        
        var arregloHoras = {}; 
        var hora = 0;
        var horasSeleccionadas = 0;
        
        for(var i=0;i<=($('#totalHorasSelecCurso' + idCurso).val());i++)
        {
            if($("#"+ idCurso + "adiciona"+i).hasClass('celda_hora'))
            {
                

            }else if($("#"+ idCurso + "adiciona"+i).hasClass('celda_hora_seleccionada'))
            {                
                arregloHoras[hora] = $("#"+ idCurso + "horaSelecHorario"+i).val();
                hora ++;
                horasSeleccionadas++;
            }
        }      
        
        if($("#iddocenteNuevoCurso" + idCurso).val() === '')
        {
            alert('Debe seleccionar el docente a asignar carga');
            $("#iddocenteNuevoCurso" + idCurso).focus();
            return false;

        }else if(($("#docenteNuevoTipVinCurso" + idCurso).val() === '') || ($("#docenteNuevoTipVinCurso" + idCurso).val() === '0'))
        {
            alert('Debe seleccionar el tipo de vinculación');
            $("#docenteNuevoTipVinCurso" + idCurso).focus();
            return false;
        }
        
        if(horasSeleccionadas === 0)
        {
            alert('Debe seleccionar al menos una hora lectiva');
            return false;
        }
        
        var jsonString = JSON.stringify(arregloHoras);

            
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteNuevo: $("#iddocenteNuevoCurso" + idCurso).val(),
                curso: idCurso,
                periodo: $("#periodoTabCurso").val(),
                tipoVinculacion: $("#docenteNuevoTipVinCurso" + idCurso).val(),   
                horasSeleccionadas: jsonString
                }
            })
            .done(function(data) {
                
                var divInfo = "#info" + idCurso;
                cargarAsignacionCurso(idCurso,divInfo); 
                
                if(data !== 'ok')
                {
                    alert(data);
                    
                }
                
            })
            .fail(function() {
                alert("error en el servidor");
            });
    }
    
function modificarDocente(identificacion,idCurso)
    {        
         var divEliminar = "#" + idCurso + "elimina" + identificacion; 
         var divModificar = "#" + idCurso + "modifica" + identificacion; 
           
         $(divEliminar).css("display", "none");  
         $(divModificar).toggle("slow");
        
    } 
 

function modificarHorasDocCurso(idDocente, idCurso, posicion)
{
    if($("#"+ idDocente + "Curso" + idCurso + "modifica" + posicion).hasClass('celda_hora'))
        {
            var nuevasHoras = parseFloat($("#"+ idDocente + "modcontHorasSelecCurso"+idCurso).val());
            var totalHoras = nuevasHoras + 1;
            $("#"+ idDocente + "modcontHorasSelecCurso"+idCurso).val(totalHoras);
            $("#"+ idDocente + "modhorasSelecCurso"+idCurso).html(totalHoras);
            $("#"+ idDocente + "Curso" + idCurso + "modifica" + posicion).removeClass("celda_hora").addClass("celda_hora_seleccionada"); 
            
        }else if($("#"+ idDocente + "Curso" + idCurso + "modifica" + posicion).hasClass('celda_hora_seleccionada'))
        {
            var nuevasHoras = parseFloat($("#"+ idDocente + "modcontHorasSelecCurso"+idCurso).val());
            var totalHoras = nuevasHoras - 1;
            $("#"+ idDocente + "modcontHorasSelecCurso"+idCurso).val(totalHoras);
            $("#"+ idDocente + "modhorasSelecCurso"+idCurso).html(totalHoras);
            $("#"+ idDocente + "Curso" + idCurso + "modifica" + posicion).removeClass("celda_hora_seleccionada").addClass("celda_hora");
            
        }
}

<?php
$valor = "#respuestaModificarCurso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function guardarModificarDocenteCurso(idDocente,idCurso,tipoVinc)
    {        
        var arregloHorasNo = {};
        var arregloHorasSi = {};
        var horasSi = 0;
        var horasNo = 0;
        var horasSeleccionadas = 0;
        
        for(var i=0;i<=($('#' + idDocente + 'modtotalHorasSelecCurso' + idCurso).val());i++)
        {
            if($("#"+ idDocente + "Curso" + idCurso + "modifica" + i).hasClass('celda_hora'))
            {
                arregloHorasNo[horasNo] = $("#"+ idDocente + "Curso" + idCurso + "modhoraSelecHorario"+i).val();
                horasNo++;

            }else if($("#"+ idDocente + "Curso" + idCurso + "modifica" + i).hasClass('celda_hora_seleccionada'))
            {                
                arregloHorasSi[horasSi] = $("#"+ idDocente + "Curso" + idCurso + "modhoraSelecHorario"+i).val();
                horasSi++;
                horasSeleccionadas++;
            }
        } 
        
        if(horasSeleccionadas === 0)
        {
            alert('Debe seleccionar al menos una hora lectiva');
            return false;
        }
        
        var jsonStringSi = JSON.stringify(arregloHorasSi);
        var jsonStringNo = JSON.stringify(arregloHorasNo);

            
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteNuevo: idDocente,
                tipoVinculacion: tipoVinc,   
                curso: idCurso,
                periodo: $("#periodoTabCurso").val(),
                horasSeleccionadas: jsonStringSi,
                horasNoSeleccionadas: jsonStringNo,
                }
            })
            .done(function(data) {
                
                var divInfo = "#info" + idCurso;
                cargarAsignacionCurso(idCurso,divInfo); 
                
                if(data !== 'ok')
                {
                    alert(data);
                    
                }
            })
            .fail(function() {
                alert("error en el servidor");
            });
    }

function eliminarDocente(identificacion,idCurso)
    {        
           var divEliminar = "#" + idCurso + "elimina" + identificacion; 
           var divModificar = "#" + idCurso + "modifica" + identificacion; 
           $(divModificar).css("display", "none");  
           $(divEliminar).toggle("slow");
    } 
    
    <?php
$valor = "#respuestaEliminarCurso";
$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal, $enlace);
?>    
    function eliminarHorasDocente(idDocente,idCurso,tipoVinc)
    {        
        var arregloHorasNo = {};
        var arregloHorasSi = {};
        var horasSi = 0;
        var horasNo = 0;
        var horasSeleccionadas = 0;
        
        for(var i=0;i<=($('#' + idDocente + 'modtotalHorasSelecCurso' + idCurso).val());i++)
        {
            if($("#"+ idDocente + "Curso" + idCurso + "modifica" + i).hasClass('celda_hora'))
            {
                arregloHorasNo[horasNo] = $("#"+ idDocente + "Curso" + idCurso + "modhoraSelecHorario"+i).val();
                horasNo++;

            }else if($("#"+ idDocente + "Curso" + idCurso + "modifica" + i).hasClass('celda_hora_seleccionada'))
            {                
                arregloHorasSi[horasSi] = $("#"+ idDocente + "Curso" + idCurso + "modhoraSelecHorario"+i).val();
                horasSi++;
                horasSeleccionadas++;
            }
        } 
        
        var jsonStringSi = JSON.stringify(arregloHorasSi);
        var jsonStringNo = JSON.stringify(arregloHorasNo);

            
           var respuesta = $.ajax({
            url: "<?php echo $estaUrl ?>",
            dataType: "html",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                identificacionDocenteNuevo: idDocente,
                tipoVinculacion: tipoVinc,   
                horasSeleccionadas: jsonStringSi,
                horasNoSeleccionadas: jsonStringNo,
                }
            })
            .done(function(data) {
                if(data !== 'error')
                {
                    var divInfo = "#info" + idCurso;
                    cargarAsignacionCurso(idCurso,divInfo); 
                    
                    //alert('La asignación se guardo con exito');
                }else
                {
                    alert('No se puede guardar la asignación');
                }
                
            })
            .fail(function() {
                alert("error en el servidor");
            });
    }

    
</script>