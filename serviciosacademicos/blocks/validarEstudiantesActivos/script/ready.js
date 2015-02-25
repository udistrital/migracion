
// Asociar el widget de validación al formulario
$("#formulario").validationEngine({promptPosition : "centerRight", scroll: false});
$( "[title]" ).tooltip();



var read 
if(!document.getElementById("leido")){
	
	//machetazo el ready se ejecuta dos veces
	read = document.createElement("div");
	read.id="leido";
	document.body.appendChild(read);
	
	$(':file').change(function(evt){
		
	    var file = this.files[0];
	    var name = file.name;
	    var size = file.size;
	    var type = file.type;
	    maxSize=10*1024*1024;
	    if(size>maxSize||(type!="application/vnd.ms-excel"&&type!="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")){
	    	$("#formulario")[0].reset();
	    	alert ("Archivo Invalido!");
	    }
	});
	
	 $("#enviarListado").click(function() {
		enviarListado(); 
	 });
	
}


