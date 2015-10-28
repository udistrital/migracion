//Para que funcione el dataTable(),
//$('#example').dataTable();
// Asociar el widget de validación al formulario

var $formValidar = $("#inscripcionAdmisiones");
        // Asociar el widget de validación al formulario
        
 $("#inscripcionAdmisiones").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

$(function() {
    $("#inscripcionAdmisiones").submit(function() {
        var resultado=$("#inscripcionAdmisiones").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
    
    /*$formValidar.formToWizard({
                submitButton: 'botonContinuarA',
                showProgress: true, 
                nextBtnName: 'Siguiente >>',
                prevBtnName: '<< Anterior',
                showStepNo: true,                
                validateBeforeNext: function() {
                                     
                        return $formValidar.validationEngine( 'validate' );
                }
            });*/
});



tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "",
        // Theme options
        //theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor",
        //theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        //theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        //theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        // Example content CSS (should be your site CSS)
        content_css : "css/content.css",
        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "lists/template_list.js",
        external_link_list_url : "lists/link_list.js",
        external_image_list_url : "lists/image_list.js",
        media_external_list_url : "lists/media_list.js",
        // Style formats
        /*style_formats : [
                {title : 'Bold text', inline : 'b'},
                {title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
                {title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
                {title : 'Example 1', inline : 'span', classes : 'example1'},
                {title : 'Example 2', inline : 'span', classes : 'example2'},
                {title : 'Table styles'},
                {title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
        ],*/

        // Replace values for the template plugin
        /*template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }*/
    });

$(document).ready(function(){
   $(document).bind("contextmenu",function(e){
       return false;
   });
}); 

$(function() {
    $( "button" )
    .button()
    .click(function( event ) {
        event.preventDefault();
    });
});


/*$(function() {
    $( document ).tooltip();
});*/

//Asociar el widget tabs a la división cuyo id es tabs
$(function() {
    $( "#tabs" ).tabs();
});


