<?php
/**
 *
 * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

//configurar path de incluir
$new_include_path = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" )
					."/blocks/".$esteBloque["grupo"]."/".$esteBloque["nombre"]."/";
set_include_path($new_include_path);

//Lenguaje

$nombreClaseLenguaje = "Lenguaje" . $esteBloque ["nombre"];
$lenguaje = new $nombreClaseLenguaje ($new_include_path);

//URL base
$rutaURL = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );


$url=$this->miConfigurador->getVariableConfiguracion("host");
$url.=$this->miConfigurador->getVariableConfiguracion("site");
$url.="/index.php?";

//Variables
$cadenaACodificar="pagina=".$this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar.="&procesarAjax=true";
$cadenaACodificar.="&action=index.php";
$cadenaACodificar.="&bloqueNombre=".$esteBloque["nombre"];
$cadenaACodificar.="&bloqueGrupo=".$esteBloque["grupo"];
$cadenaACodificar.="&usuario=".$_REQUEST["usuario"];
$cadenaACodificar.="&modulo=".$_REQUEST["modulo"];
$cadenaACodificar.="&sessionId=".$_REQUEST["sessionId"];

//Codificar las variables
$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");

//Cadena codificada para recibir lista
$cadenaACodificar0=$cadenaACodificar."&funcion=mensajeRecibos";
$cadena0=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar0,$enlace);

//Cadena codificada para recibir lista
$cadenaACodificar1=$cadenaACodificar."&funcion=solicitarCredito";
$cadena1=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar1,$enlace);
//cadena codificada para recibir formulario de creacion
$cadenaACodificar2=$cadenaACodificar."&funcion=cancelarCredito";
$cadena2=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar2,$enlace);
//cadena codificada para crear elemento
$cadenaACodificar3=$cadenaACodificar."&funcion=aprobarCredito";
$cadena3=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar3,$enlace);
//cadena codificada para Activar Desactivar Elemento
$cadenaACodificar4=$cadenaACodificar."&funcion=registroResolucion";
$cadena4=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar4,$enlace);
//cadena codificada para Editar Elemento
$cadenaACodificar5=$cadenaACodificar."&funcion=registroReintegro";
$cadena5=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar5,$enlace);
//cadena codificada para Actualizar Elemento
$cadenaACodificar6=$cadenaACodificar."&funcion=registroContable";
$cadena6=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar6,$enlace);
//cadena codificada para Editar los Elementos
$cadenaACodificar7=$cadenaACodificar."&funcion=consultarHistorico";
$cadena7=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar7,$enlace);
//cadena codificada para consultar Usuario operacion metodo Operacion
$cadenaACodificar8=$cadenaACodificar."&funcion=editarDeudas&metodo=operacion";
$cadena8=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar8,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz
$cadenaACodificar9=$cadenaACodificar."&funcion=editarDeudas&metodo=interfaz&divRespuesta=resultadoUsuario";
$cadena9=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar9,$enlace);
//cadena codificada para consultar Deudas del usuario
$cadenaACodificar10=$cadenaACodificar."&funcion=cargarInterfazTab2";
$cadena10=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar10,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar11=$cadenaACodificar."&funcion=enviarMarcas";
$cadena11=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar11,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar12=$cadenaACodificar."&funcion=editarDeudas&metodo=operacion&soloConsulta=true";
$cadena12=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar12,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar13=$cadenaACodificar."&funcion=filtrarDeudas";
$cadena13=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar13,$enlace);


$cadenaACodificar14=$cadenaACodificar."&funcion=solicitarCreditoEstudiante";
$cadena14=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar14,$enlace);

$urlObtenerListaElementos = $url.$cadena1;
$urlObtenerNuevoElemento = $url.$cadena2;
$urlCambiarEstadoElemento = $url.$cadena4;
$urlEditarElemento=$url.$cadena5;
$urlActualizarElemento=$url.$cadena6;
$urlConsultarElemento=$url.$cadena7;
$urlConsultarDeudasUsuario=$url.$cadena10;
?>

<script type='text/javascript'>



	function solicitarCredito(val){
		
		$.ajax({
            url: "<?php echo $url;?>",
            data: "<?php echo $cadena1?>"+"&valorConsulta="+val,
            type:"post",
            dataType: "html",
            success: function(jresp){
               
            	if( jresp.substring(jresp.length , jresp.length-4) =="true" ) consultarUsuario();
                else{
            		var edicion = document.getElementById("resultadoCredito");
            		edicion.innerHTML = jresp;
                }	
            	
		       }
        });

	
        
	}

	function mensajeComienzo(val){
		
		$.ajax({
            url: "<?php echo $url;?>",
            data: "<?php echo $cadena0?>"+"&valorConsulta="+val,
            type:"post",
            dataType: "html",
            success: function(jresp){
               
                
            		var edicion = document.getElementById("formularioSolicitarCredito");
            		edicion.innerHTML = jresp;
                
            	
		       }
        });

	
        
	}
	
	function solicitarCreditoEstudiante(val, periodo){
			
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena14?>"+"&valorConsulta="+val+"&periodo="+periodo,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	               
	            	if( jresp.substring(jresp.length , jresp.length-4) =="true" ) mensajeComienzo();
	                else{
	            		var edicion = document.getElementById("formularioSolicitarCredito");
	            		edicion.innerHTML = jresp;
	                }	
	            	
			       }
	        });
	
		
	        
		}

	function cancelarCredito(val){
			
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena2?>"+"&valorConsulta="+val,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	               
	            	if( jresp.substring(jresp.length , jresp.length-4) =="true" ) consultarUsuario();
	                else{
	            		var edicion = document.getElementById("resultadoCredito");
	            		edicion.innerHTML = jresp;
	                }	
	            	
			       }
	        });
	
		
	        
		}

	function aprobarCredito(val){
		
		$.ajax({
            url: "<?php echo $url;?>",
            data: "<?php echo $cadena3?>"+"&valorConsulta="+val,
            type:"post",
            dataType: "html",
            success: function(jresp){
               
            	if( jresp.substring(jresp.length , jresp.length-4) =="true" ) consultarUsuario();
                else{
            		var edicion = document.getElementById("resultadoCredito");
            		edicion.innerHTML = jresp;
                }	
            	
		       }
        });

	
        
	}

	

	function registroReintegro(val,periodo){
			
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena5?>"+"&valorConsulta="+val+"&periodo="+periodo,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	               
	            	if( jresp.substring(jresp.length , jresp.length-4) =="true" ) consultarUsuario();
	                else{
	            		var edicion = document.getElementById("resultadoCredito");
	            		edicion.innerHTML = jresp;
	                }	
	            	
			       }
	        });
	
		
	        
	}

	
	function enviarResolucion(){
		if($("#formularioResolucion").validationEngine('validate')!=false){
			 
			var div = document.getElementById("rconsultas");
			div.innerHTML = '<div id="loading"></div>';
			var dataS = $("#formularioResolucion").serialize();
			var file1 = document.getElementById("documentoResolucion");
			var file2 = document.getElementById("excelResolucion");
			var data = new FormData();
			
			data.append("documentoResolucion", file1.files[0]);
			data.append("excelResolucion", file2.files[0]);
			s1 = dataS.split("&");
			
			for(i=0;i<s1.length;i++){
					var split = s1[i].split("=");
					data.append(split[0], split[1]);
					
					
			}
			
			$.ajax({
	            url: "<?php echo $url.$cadena4;?>",
	            data:data,
	            type:"post",
	            dataType: "html",
	            contentType: 'multipart/form-data', 
	            processData: false,
	            contentType: false,
	            success: function(jresp){
	            	var div = document.getElementById("rconsultas");
	            	div.innerHTML =jresp;
	            		
			       }
	        });
		 
		}
		
	}

	function enviarMarca(val){
		if($("#formularioResolucion").validationEngine('validate')!=false){
			var div = document.getElementById("rconsultas");
			div.innerHTML = '<div id="loading"></div>';
			var file1 = document.getElementById("excelMarca");
			//var val = document..getSelection("periodo");
			
			var data = new FormData();
			
			data.append("excelMarca", file1.files[0]);
			data.append("periodo",val)
			
			$.ajax({
	            url: "<?php echo $url.$cadena11;?>",
	            data:data,
				    
	            type:"post",
	            dataType: "html",
	            contentType: 'multipart/form-data', 
	            processData: false,
	            contentType: false,
	            success: function(jresp){
		           
	            		var div = document.getElementById("rconsultas") ;
	            	
	            	
	            	div.innerHTML =jresp;
	            		
			       }
	        });
		 
		}
		
	}
		
	

	function consultarUsuario(){
		if($("#formulario").validationEngine('validate')!=false){
			var data = $("#formulario").serialize();
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena8?>&"+data,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var resultado = document.getElementById("resultadoUsuario");
	            	var div = document.getElementById("resultadoCredito");
	            	div.innerHTML = "";
	            	
	            	
	            	
	            	if( jresp.substring(jresp.length , jresp.length-4) =="true" ){
		            	
	            		 consultarUsuario();
	            		}else {
	            			resultado.innerHTML = jresp;
	            		}
			       }
	        });
		 
		}
	}

		function enviarRegistroContable(){
				if($("#formularioFinanciera").validationEngine('validate')!=false){
					var data = $("#formularioFinanciera").serialize();
					var observacion = document.getElementById("observaciones");
					//data = data +"&observaciones="+observacion;
					$.ajax({
				    url: "<?php echo $url;?>",
				    data: "<?php echo $cadena6?>&"+data,
				    type:"post",
				    dataType: "html",
				    success: function(jresp){
				    	
				    	var div = document.getElementById("resultadoCredito");
				    		  	
    	
				    	if( jresp.substring(jresp.length , jresp.length-4) =="true" ){
					    
				    		 consultarUsuario();
				    		}else {
				    			div.innerHTML = jresp;
				    		}
					       }
				});
				 
				}
			}




		function consultarHistorico(val,val2){
				
					
					$.ajax({
				    url: "<?php echo $url;?>",
				    data: "<?php echo $cadena7?>"+"&valorConsulta="+val+"&periodo="+val2,
				    type:"post",
				    dataType: "html",
				    success: function(jresp){
				    	
				    	var div = document.getElementById("resultadoCredito");
				    		  	
    	
				    	if( jresp.substring(jresp.length , jresp.length-4) =="true" ){
					    
				    		 consultarUsuario();

				    		}else {
				    			div.innerHTML = jresp;
				    		}
					       }
				});
				 
				
			}

	
		 
		
	function cargarInterfazTab2(){
		$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena10?>",
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var edicion = document.getElementById("consultas");
	            	edicion.innerHTML = jresp;
	            	$("#tabs").tabs( "option", "active", 1 );
			       }
	        });
		 
		
	}


	function consultarUsuarioInterfaz(){
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena9?>",
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var edicion = document.getElementById("edicion");
	            	edicion.innerHTML = jresp;
	            	$("#tabs").tabs( "option", "active", 0 );
			       }
	        });
		 
		
	}

	
		


</script>
