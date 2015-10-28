function modificar(elem, request, response){
<?php

$valor = "pais";

$cadenaFinal = $cadenaACodificar . "&funcion=" . $valor;

$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$estaUrl = $url . $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaFinal, $enlace );

?>

$.ajax({

    url: "<?php echo $estaUrl?>",

    dataType: "json",

    data: {

        departamento:  $("#departamento").val(),

        municipio:   $("#municipio").val()   

    },

    success: function(data) { 

        if(data[0]!="Personaje no Valido"){

            $('#seleccion').html('');

            $("<option value='0'>Seleccione municipio</option>").appendTo("#municipio");

  $.each(data , function(indice,valor){


            $("<option value='"+data[ indice ].municipio+"'>"+data[ indice ].pais+"</option>").appendTo("#municipio");

  });

            $('#municipio').attr('disabled','');

            $('#municipio').removeAttr('disabled');

             $('#resultado').attr('disabled','');

            }else{

                $("#resultado").val(data[0]);

                $('#municipio').html('');

            $("<option value='1'>Sin Resultado</option>").appendTo("#muncipio");

                $('#municipio').attr('disabled','');

                 $('#resultado').attr('disabled','');

    } 
}


});

};