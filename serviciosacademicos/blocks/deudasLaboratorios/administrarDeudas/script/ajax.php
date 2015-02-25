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
$cadenaACodificar1=$cadenaACodificar."&funcion=obtenerDeudasInterfaz";
$cadena1=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar1,$enlace);
//cadena codificada para recibir formulario de creacion
$cadenaACodificar2=$cadenaACodificar."&funcion=nuevoDeuda";
$cadena2=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar2,$enlace);
//cadena codificada para crear elemento
$cadenaACodificar3=$cadenaACodificar."&funcion=editarDeudas&metodo=crear";
$cadena3=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar3,$enlace);
//cadena codificada para Activar Desactivar Elemento
$cadenaACodificar4=$cadenaACodificar."&funcion=desactivarDeuda";
$cadena4=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar4,$enlace);
//cadena codificada para Editar Elemento
$cadenaACodificar5=$cadenaACodificar."&funcion=editarDeuda";
$cadena5=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar5,$enlace);
//cadena codificada para Actualizar Elemento
$cadenaACodificar6=$cadenaACodificar."&funcion=actualizarDeuda";
$cadena6=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar6,$enlace);
//cadena codificada para Editar los Elementos
$cadenaACodificar7=$cadenaACodificar."&funcion=editarDeudas&metodo=nuevo";
$cadena7=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar7,$enlace);
//cadena codificada para consultar Usuario operacion metodo Operacion
$cadenaACodificar8=$cadenaACodificar."&funcion=editarDeudas&metodo=operacion";
$cadena8=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar8,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz
$cadenaACodificar9=$cadenaACodificar."&funcion=editarDeudas&metodo=interfaz&divRespuesta=resultadoUsuario";
$cadena9=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar9,$enlace);
//cadena codificada para consultar Deudas del usuario
$cadenaACodificar10=$cadenaACodificar."&funcion=editarDeudas&metodo=nuevo";
$cadena10=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar10,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar11=$cadenaACodificar."&funcion=editarDeudas&metodo=interfaz&soloConsulta=true";
$cadena11=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar11,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar12=$cadenaACodificar."&funcion=editarDeudas&metodo=operacion&soloConsulta=true";
$cadena12=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar12,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar13=$cadenaACodificar."&funcion=filtrarDeudas";
$cadena13=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar13,$enlace);


$urlObtenerListaElementos = $url.$cadena1;
$urlObtenerNuevoElemento = $url.$cadena2;
$urlCambiarEstadoElemento = $url.$cadena4;
$urlEditarElemento=$url.$cadena5;
$urlActualizarElemento=$url.$cadena6;
$urlConsultarElemento=$url.$cadena7;
$urlConsultarDeudasUsuario=$url.$cadena10;
?>

<script type='text/javascript'>

	function obtenerListaElementos(){
		
		$.ajax({
            url: "<?php echo $url;?>",
            data: "<?php echo $cadena11?>",
            type:"post",
            dataType: "html",
            success: function(jresp){
            	var edicion = document.getElementById("consultas");
            	edicion.innerHTML = jresp;
            	$("#tabs").tabs( "option", "active", 1 );
		       }
        });

		$.ajax({
            url: "<?php echo $url;?>",
            data: "<?php echo $cadena1?>",
            type:"post",
            dataType: "html",
            success: function(jresp){
            	var consultas = document.getElementById("consultas");
            	consultas.innerHTML =consultas.innerHTML+"<br><br>"+ jresp;
            	$("#tabs").tabs( "option", "active", 1 );
		       }
        });
        
	}

	function consultarElemento(el){
		var idElementoElemento = el.parentElement.parentElement.parentElement.id;
		var idElemento = idElementoElemento.replace("listaElemento","");
		$.ajax({
            url: "<?php echo $urlConsultarElemento;?>",
            type:"get",
            dataType: "html",
            data:"id="+idElemento,
            success: function(jresp){
            	$(".consultaElemento").hide();
            	var consulta = document.getElementById("consultaElemento"+idElemento);
            	$("#consultaElemento"+idElemento).show();
	  			consulta.innerHTML = jresp;
		       }
        });
	}

	function consultarDeudasUsuario(){
		//if($("#formulario").validationEngine('validate')!=false){
			var data = $("#formulario").serialize();
			$.ajax({
	            url: "<?php echo $url;?>",
	            type:"post",
	            dataType: "html",
	            data: "<?php echo $cadena10?>&"+data,
	            success: function(jresp){
	            	var resultado = document.getElementById("resultadoDeudas");
	            	resultado.innerHTML = jresp;
			       }
	        });
		//}
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
	            	resultado.innerHTML = jresp;;
			       }
	        });
		 
		}
	}

	function consultarUsuarioNoEdicion(){
		if($("#formulario").validationEngine('validate')!=false){
			var data = $("#formulario").serialize();
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena12?>&"+data,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var resultado = document.getElementById("resultadoUsuario");
	            	resultado.innerHTML = jresp;;
			       }
	        });
		 
		}
	}

	function consultarSeleccionUsuario(id, opt, tipo, cod){
			var data = "opcionConsulta=identificacion&valorConsulta="+id;
			data =  data + "&tipo="+tipo;
			data =  data + "&codigo="+cod;
			if (opt ==1) data =  data + "&soloConsulta=true";
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena8?>&"+data,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var resultado = document.getElementById("resultadoUsuario");
	            	resultado.innerHTML = jresp;;
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

	function cambiarEstadoElemento(el){
		var idElementoElemento = el.parentElement.parentElement.parentElement.id;
		var idElemento = idElementoElemento.replace("listaElemento","");
		$.ajax({
            url: "<?php echo $urlCambiarEstadoElemento;?>",
            type:"get",
            dataType: "json",
            data:"id="+idElemento,
            success: function(jresp){
            	if(jresp.innerString){
		  			el.innerHTML =  jresp.innerString;
		  			el.title  = jresp.newTitle;
		  			el.className = jresp.className;
		  			}else alert("Fallo Cambio!");
		       }	  			
        });	 
	}

	function eliminarElementoTabla(el){
		
	}

	function editarElemento(id,el,idLab,idEst){
		var row = el.parentElement.parentElement.parentElement.parentElement;
        var $tr    = $('#aClonar');
	    var $clone = $tr.clone().attr('id', 'edicionFila'+id);
	    $clone.attr('style', '' );
	    //console.log($clone[0].cells,$clone[0].cells.length,row.cells.length);
	    for(i=0;i<row.cells.length;i++){ 
		    var string = $clone[0].cells[i].innerHTML;
		    string = string.replace('crearElemento(this)','actualizarElemento('+id+',this)');
		    string = string.replace('<div class="listaEliminar">','<div class="listaEliminar" style="display:none;">');
		    if(string.indexOf('name="listadoEstados"')==-1&&string.indexOf('name="listadoLaboratorios"')==-1
		    		&&string.indexOf('name="Anno"')==-1&&string.indexOf('name="Periodo"')==-1){
		    	string = string.replace('value=""','value="'+row.cells[i].innerHTML+'"');
		    }else if(string.indexOf('name="listadoEstados"')!=-1){
		    	string = string.replace('value="'+idEst+'"','value="'+idEst+'" selected');
		    	string = string.replace('disabled=""','');
			}else if(string.indexOf('name="listadoLaboratorios"')!=-1){
				string = string.replace('value="'+idLab+'"','value="'+idLab+'" selected');
				string = string.replace('name="listadoLaboratorios"','name="listadoLaboratorios" disabled=""');
			 }else if(string.indexOf('name="Anno"')>0){
				 string = string.replace('value="'+row.cells[i].innerHTML+'"','value="'+row.cells[i].innerHTML+'" selected');
				 string = string.replace('name="Anno"','name="Ano" disabled=""');
			 }else if(string.indexOf('name="Periodo"')>0){
				 string = string.replace('value="'+row.cells[i].innerHTML+'"','value="'+row.cells[i].innerHTML+'" selected');
				 string = string.replace('name="Periodo"','name="Periodo" disabled=""');
			 }

			 if(string.indexOf('id="Material"')>0){
				 string = string.replace('id="Material"','id="Material" disabled=""');
			 }
			 
		    $clone[0].cells[i].innerHTML = string;
		    //console.log(string);
		    }
        row.innerHTML = $clone[0].innerHTML;
	}
	
	
	function actualizarElemento(id, el){
		if($("#tablaEdicion").validationEngine('validate')!=false){
			var data="&";
			data += $("#datosUsuario").serialize();
			data += "&"+$("#infoDeudor").serialize();
			var inputs = $(el).closest('tr').find('input');
			for(i=0;i<inputs.length;i++) data+="&"+inputs[i].id+"="+inputs[i].value;
			var selects = $(el).closest('tr').find('select');
			for(i=0;i<selects.length;i++) data+="&"+selects[i].id+"="+$(selects[i]).val();
				$.ajax({
		            url: "<?php echo $url;?>",
		            data: "<?php echo $cadena6?>"+data+"&idDeuda="+id,
		            type:"post",
		            dataType: "json",
		            success: function(jresp){
		            	if(jresp[0]==true){
					         var row = el.parentElement.parentElement.parentElement.parentElement;
					         var tr = $(el).closest('tr');
					         var clone = tr.clone();
					         for(i=0;i<row.cells.length;i++){ 
					 		    var string = clone[0].cells[i].innerHTML;
					 		   if(string.indexOf('listadoLaboratorios')==1) var idLab = $(row.cells[i].firstChild).val();
					 		   if(string.indexOf('input')==1&&string.indexOf('FechaCreacion')==-1){
					 			  string = row.cells[i].firstChild.value; 
					 		   }else if(string.indexOf('select')==1){
					 			  var index = row.cells[i].firstChild.selectedIndex;
					 			  var text = row.cells[i].firstChild[index].innerHTML;
					 			  string  = text;
					 		   }else if(string.indexOf('FechaCreacion')>0){
					 			  var now = new Date();
					 			 string  = jresp[2];
					 		   }else if(string.indexOf('edicionMenu')>0){
					 			  string  = jresp[3];
					 		   }
					 		  clone[0].cells[i].innerHTML = string;
					         }
					         row.innerHTML = clone[0].innerHTML;				         
					         alert("<?php echo $lenguaje->getCadena("resistroExito");?>");
				            
					       }else alert(jresp);
				       }
		        });
			}
		}

	function crearElemento(el){
		if($("#tablaEdicion").validationEngine('validate')!=false){
			var data="&";
			data += $("#datosUsuario").serialize();
			data += "&"+$("#infoDeudor").serialize();
			var inputs = $(el).closest('tr').find('input');
			for(i=0;i<inputs.length;i++) data+="&"+inputs[i].id+"="+inputs[i].value;
			var selects = $(el).closest('tr').find('select');
			for(i=0;i<selects.length;i++) data+="&"+selects[i].id+"="+$(selects[i]).val();
			
				$.ajax({
		            url: "<?php echo $url;?>",
		            data: "<?php echo $cadena3?>"+data,
		            type:"post",
		            dataType: "json",
		            success: function(jresp){
			            if(jresp[0]==true){
				         var row = el.parentElement.parentElement.parentElement.parentElement;
				         var tr = $(el).closest('tr');
				         var clone = tr.clone();
				         for(i=0;i<row.cells.length;i++){ 
				 		    var string = clone[0].cells[i].innerHTML;
				 		   if(string.indexOf('listadoLaboratorios')==1) var idLab = $(row.cells[i].firstChild).val();
				 		   if(string.indexOf('input')==1&&string.indexOf('FechaCreacion')==-1){
				 			  string = row.cells[i].firstChild.value; 
				 		   }else if(string.indexOf('select')==1){
				 			  var index = row.cells[i].firstChild.selectedIndex;
				 			  var text = row.cells[i].firstChild[index].innerHTML;
				 			  string  = text;
				 		   }else if(string.indexOf('FechaCreacion')>0){
				 			  var now = new Date();
				 			 string  = jresp[2];
				 		   }else if(string.indexOf('edicionMenu')>0){
				 			  string  = jresp[3];
				 		   }
				 		  clone[0].cells[i].innerHTML = string;
				         }
				         row.innerHTML = clone[0].innerHTML;				         
				         alert("<?php echo utf8_encode($lenguaje->getCadena("resistroExito"));?>");
			            
				       }else alert(jresp);
		            }
		        });
			}
		}

	function obtenerNuevoElemento(){
		$.ajax({
            url: "<?php echo $urlObtenerNuevoElemento;?>",
            type:"get",
            dataType: "html",
            success: function(jresp){
	  			var edicion = document.getElementById("edicion");
	  			edicion.innerHTML = jresp;
		       }
        });
	}
	

	function setValor(oEl,value){
			for(i=0;i<oEl.length;i++){
				oEl[i].value = value;
				if(oEl[i].checked&&oEl[i].value==value) oEl[i].checked= true;
				console.log(oEl[i].value,value,oEl[i].checked,value,oEl[i].type)
				}

		}

	function limpiarFormulario(){
		$('#formulario')[0].reset();
		}

	function agregarFila(){ 
			var $tr    = $('#aClonar');
		    var $clone = $tr.clone().attr('id', 'nuevaFila'+Math.floor((Math.random()*100)+1));
		    $clone.attr('style', '' );
		    $clone.find(':text').val('');
		    $('#tablaEdicion tr:first').after($clone);

		}
		
	function reseleccionar(idSel){
			$('#'+idSel+' option:eq(0)').prop('selected', true);
		}

	function filtrarDeudas(){
		var data = $("#deudasFiltro").serialize();
		$.ajax({
            url: "<?php echo $url;?>",
            data: "<?php echo $cadena13?>&"+data,
            type:"post",
            dataType: "html",
            success: function(jresp){
            	var resultado = document.getElementById("resultadosFiltro");
            	resultado.innerHTML = jresp;;
		       }
        });
			
		}

</script>