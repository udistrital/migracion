<?php

/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          crud.class.php 
* @author        Karen Palacios
* @revision      Última revisión 02 de Agosto de 2012
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		
* @package			clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	1.0.0.1 04 de junio de 2012
* @version      	1.0.0.2 02 de Agosto de 2012 //creacion del campo distinct y adicion de funciones como to_char
* @author			Karen Palacios
* @author			Oficina Asesora de Sistemas
* @link				N/D
* @description  	Clase para gestionar el menu de navegacion
*
/*--------------------------------------------------------------------------------------------------------------------------*/


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class crud extends funcionGeneral {
	
	
	private $tabla = '';
	public $dbms = '';
	public $primaria = '';
	public $titulo = '';
	public $campo = array();
	public $form;
	public $distinct = 'false';


	function __construct($configuracion,$titulo,$dbms,$usuario="") {
		$this->usr_cod 	= '1032386201';
		$this->tiempo=time();
		$this->titulo=$titulo;
		$this->dbms=$dbms;
		$this->acceso_db=$this->conectarDB($configuracion,$usuario);
		$this->mensaje="";
	}
	
	public function setFormulario($formulario){
		$this->formulario=$formulario;
	}
	
	public function setTabla($nombre,$primaria){
		$this->tabla=$nombre;	
		$this->primaria=$primaria;
		$this->conexion=$this->acceso_db;
	}

	public function setCampoSesion($campo){
		$this->campoSesion=$campo;
	}
	
	public function setDistinct($parametro){
		$this->distinct=$parametro;
	}
	
	public function getDistinct(){
		return $this->distinct;
	}	

	
	public function getTabla(){
		return $this->tabla;
	}	
	

	public function setCampo($etiqueta,$nombre,$parametros="",$valorFuncion="NULL",$vista=""){
	
		$this->campo[$nombre][0]=$etiqueta;
		$this->campo[$nombre][1]=$nombre;
		$this->campo[$nombre][2]=$parametros;
		$this->campo[$nombre][3]=$valorFuncion;
		$this->campo[$nombre][4]=$vista;
	}
	
	public function getCampos(){
		$campos=array_keys($this->campo);
		return $campos;
	}
	
	public function setFiltro($campo,$valor,$igualdad="",$tabla=""){
		$this->filtro[$campo][0]=$valor;
		$this->filtro[$campo][1]=$igualdad;
		$this->filtro[$campo][2]=$tabla;
	}
	
	
	public function getFiltro(){
		$salida="";
		

		//recoge los datos de la barra de filtros superios
		if(isset($_REQUEST['filters'])){
			$BarraFiltro = json_decode(stripslashes($_REQUEST['filters']),true);
			//var_dump($BarraFiltro);
				$i=0;
				while(isset($BarraFiltro["rules"][$i]["op"])){
					switch($BarraFiltro["rules"][$i]["op"]){
						case "bw":
							$salida.=" ".$BarraFiltro["groupOp"]." ".$BarraFiltro["rules"][$i]["field"]." like '%".$BarraFiltro["rules"][$i]["data"]."%'";
						break;	
					}
					$i++;
				}	
		}
	
		if(isset($this->filtro) AND is_array($this->filtro)){
			$this->filtros=array_keys($this->filtro);
			foreach($this->filtros as $clave){
					if($this->filtro[$clave][2]==""){
						$this->filtro[$clave][2]=$this->getTabla();
					}
					switch($this->filtro[$clave][1]){
						case "eq":
							$salida.=" AND ".$this->filtro[$clave][2].".".$clave."=".$this->filtro[$clave][0];
						break;	
						case "igual":
							$salida.=" AND ".$this->filtro[$clave][2].".".$clave."='".$this->filtro[$clave][0]."'";
						break;	
						case "in":
							$salida.=" AND ".$this->filtro[$clave][2].".".$clave." IN (".$this->filtro[$clave][0].")";
						break;	
						case "mayoroigual":
							$salida.=" AND ".$this->filtro[$clave][2].".".$clave.">='".$this->filtro[$clave][0]."'";
						break;	
						case "menoroigual":
							$salida.=" AND ".$this->filtro[$clave][2].".".$clave."<='".$this->filtro[$clave][0]."'";
						break;
					}				
			}
		}
			

		return $salida;
	}
	
	public function contarRegistros($configuracion){
		
		$distinct=($this->getDistinct()=='true')?" DISTINCT(".$this->primaria.") ":$this->primaria;
		
		$sqlConteo="SELECT COUNT(".$distinct.") AS count FROM ";
		$sqlConteo.=$this->getTabla()." ".$this->getRelacion('tablas')." WHERE 1=1 ";
		$sqlConteo.=$this->getRelacion('relaciones')." ";
		$sqlConteo.=$this->getFiltro()." ";
		
	//	echo $sqlConteo;
		$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $sqlConteo,"busqueda");

		return $resultado[0][0];		
		
	}
	
	public function armarCrud($configuracion,$insertar='false',$consultar='true',$actualizar='false',$eliminar='false'){
		
		$this->totalRegistros=$this->contarRegistros($configuracion);

		$this->campos=$this->getCampos();
		$this->consultar=$consultar;
		$this->actualizar=$actualizar;
		$this->insertar=$insertar;
		$this->eliminar=$eliminar;
		
		if(isset($_REQUEST['searchOper'])){
			$this->setFiltro($_REQUEST['searchField'],$_REQUEST['searchString'],$_REQUEST['searchOper'],$_REQUEST['searchTable']);
		}
		if(!isset($_REQUEST['xajax'])){
			
			if(isset($_REQUEST['generarxls'])){
				$this->generarXLS($configuracion);
			}	
			else{	
				if(isset($_REQUEST['rows'])||isset($_REQUEST['oper'])){
					$this->procesarCrud($configuracion);	
				}else{
					$this->pintarCrud($configuracion);	
				}
			}		
		}else{
			$this->pintarLista($_REQUEST['xajax'],$_REQUEST['nombrefiltro'],$_REQUEST['filtro']);
		}
	}
	
	public function generarXLS($configuracion){

		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=".time().".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$registro=array();
		$registro=$this->procesarCrud($configuracion);
		
		$i=0;
		echo "<table border=1>";
		
		echo "<tr>";
		foreach($this->campos as $clave){
			echo "<td>".$this->campo[$clave][0]."</td>";
		}	
		echo "</tr>";

		while(isset($registro[$i][0])){
			echo "<tr>";
			unset($registro[$i][0]); //elimino el registro de la llave primaria
			foreach($registro[$i] as $clave){
				echo "<td>".$clave."</td>";
			}
			echo "</tr>";
		$i++;
		}
		echo "</table>";
		
	//	unset($_REQUEST['generaxls']);
		
	}
	
	public function pintarLista($name,$nombrefiltro,$filtro){

		$sql=$this->OpcionFiltro[$name][0];
		$sql.=" AND ".$nombrefiltro;
	
		switch($this->OpcionFiltro[$name][3]){
			case "eq":
				$sql.=" = ";
				$sql.=$filtro;
			break;
			case "igual":
				$sql.=" = ";
				$sql.="'".$filtro."'";
				break;
			case "contiene":
				$sql.=" like ";
				$sql.="'%".$filtro."%'";
			break;	
			
		}	
		echo $sql;
		$resultado=$this->conexion->Execute($sql) ;
		
		echo "<select class='required FormElement' role='select' name='".$name."'  id='".$name."'>";
		while (!$resultado->EOF) {
			echo "<option value=".$resultado->Fields(0).">".utf8_encode($resultado->Fields(1))."</option>";
			$resultado->MoveNext();
		}	
		echo "<option value=''></option>";
		echo "</select>";
		
		
	}
		
	public function setRelacion($tabla,$relacion){
		$this->relacion[$tabla]=$relacion;
	}

	public function setOpcionFiltro($etiqueta,$name,$tabla,$campo,$objSql,$operacion,$tipoInput,$verEncabezado){
		
		$this->OpcionFiltro[$name][0]=$objSql;
		$this->OpcionFiltro[$name][1]=$etiqueta;
		$this->OpcionFiltro[$name][2]=$tabla;
		$this->OpcionFiltro[$name][3]=$operacion;
		
		
		switch($tipoInput){
			case "select":
				$evento=' onchange=if($(this).val()!="-1"){recargarGrilla("searchOper=eq&searchField='.$campo.'&searchString="+$(this).val()+"&searchTable='.$tabla.'")}else{recargarGrilla()}';
				$htmlCampo=$this->form->listaDeSeleccion($name,"",$objSql[0],$objSql[1],$evento);
			break;
			case "text_number":
				$htmlCampo=$this->OpcionFiltro[$name][1].': ';
				$htmlCampo.=$this->form->campoNumerico($name,'','class="FormElement ui-widget-content ui-corner-all" onchange=if($(this).val()!=""){recargarGrilla("searchOper='.$operacion.'&searchField='.$campo.'&searchString="+$(this).val()+"&searchTable='.$tabla.'")}else{recargarGrilla()}');
				
				//echo $this->form->campoNumerico($name, "",'onchange=if($(this).val()!=""){recargarGrilla("searchOper='.$operacion.'&searchField='.$campo.'&searchString="+$(this).val()+"&searchTable='.$tabla.'")}else{recargarGrilla()}');
				
				//$htmlCampo.=': <input class="FormElement ui-widget-content ui-corner-all" type="text"  name="'.$name.'"  id="'.$name.'" onchange=if($(this).val()!=""){recargarGrilla("searchOper='.$operacion.'&searchField='.$campo.'&searchString="+$(this).val()+"&searchTable='.$tabla.'")}else{recargarGrilla()} >';
			break;
		}
		$this->OpcionFiltro[$name][2]=$htmlCampo;
		$this->OpcionFiltro[$name][5]=$verEncabezado;
		$this->OpcionFiltro[$name][6]=$campo;
		
		
	}
		
	public function getRelacion($parametro){
	
	
		$tablas="";
		$relaciones="";
		if(isset($this->relacion)){
			if(is_array($this->relacion)){
				foreach($this->relacion as $clave=>$valor){
					$tablas.=",".$clave;
					$relaciones.=" AND ".$valor;
				}
				if($parametro=='tablas'){
					return $tablas;
				}
				elseif($parametro=='relaciones'){
					return $relaciones;
				}
			}
		}
	}
			
	public function pintarCrud($configuracion){
		?>
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				
				<title><?php echo $this->titulo ?></title>
				
				
				<style type="text/css">
				html, body {
					margin: 0;
					padding: 0;
					font-size: 75%;
				}
				.oculta{
					display: none;
				}
				.visible{
					display: block;
				}
				</style>
				
				<link rel="stylesheet" type="text/css" media="screen" href="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/css/condor/jquery-ui-1.8.20.custom.css" />
				<link rel="stylesheet" type="text/css" media="screen" href="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/src/css/ui.jqgrid.css" />
				<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/js/jquery-1.5.2.min.js" type="text/javascript"></script>
				<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
				<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
				<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/plugins/jquery.searchFilter.js" type="text/javascript"></script>
				<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jqgrid/plugins/jquery.contextmenu.js" type="text/javascript"></script>
				
				
				<script type="text/javascript">

				function actualizar(campoSelect,filtro){
					jQuery.map(jQuery("#"+campoSelect)[0].options, function(option){
							if(option.value==$("#"+filtro).val()){ 
				           		seleccion=option;
							}
							option.className="oculta";
							
				    }); 
					seleccion.selected=true;
					seleccion.className="visible";
		             
				}	

				function actXajax(camposelect,nombrefiltro,filtro) {
					$.ajax({
						type: 'GET',
						url: '<?php echo $this->formulario?>',
						data: "xajax="+camposelect+"&nombrefiltro="+nombrefiltro+"&filtro="+$("#"+filtro).val(),
						success: function(respuesta){
							$("#tr_"+camposelect+" .DataTD").html(respuesta);
						}
					});
				}				


				function recargarGrilla(params){
					 jQuery("#list").jqGrid('setGridParam',{url:'<?php echo $this->formulario?>?'+params,page:1}); 
					 jQuery("#list").trigger("reloadGrid");

				}

					
				function centerPopup(pop){  
					//request data for centering  
					var windowWidth = document.documentElement.clientWidth;  
					var windowHeight = document.documentElement.clientHeight;  
					var popupHeight = $(pop).height();  
					var popupWidth = $(pop).width();  
					//centering  
					$(pop).css({  
					"position": "absolute",  
					"top": windowHeight/2-popupHeight/2,  
					"left": windowWidth/2-popupWidth/2  
					});  
					  
				} 

					
				function dump(obj) {
				    var out = '';
				    for (var i in obj) {
				        out += i + ": " + obj[i] + "\n";
				    }

				    alert(out);

				}
		
				jQuery("#list").searchGrid({multipleSearch:true});
							
				$(function(){
					$("#list").jqGrid({
						url:'<?php echo $this->formulario?>',
						datatype: 'json',
						mtype: 'GET',
						colNames:[
							<?php
								echo "'ID'";
								foreach($this->campos as $clave){
									echo ",'".$this->campo[$clave][0]."'";
								}
							?>
						],
						colModel :[
							<?php
							echo "{name:'".$this->primaria."', index:'".$this->primaria."', hidden:true, search:true}";
							foreach($this->campos as $clave){
								echo ",{name:'".$this->campo[$clave][1]."', index:'".$this->campo[$clave][1]."', ".$this->campo[$clave][2]."}";
							}
							?>
							
												  		
					  	],
					  	rowNum:15,
					  	rowList:[10,20,30,40],
					  	pager: '#pager',
					  	rownumbers: true,
					  	gridview: true,	
					  	viewrecords: true,
					  	search:false,
						width:'800px',
					  	sortname: '<?php echo $this->primaria; ?>',
					  	sortorder: 'asc',
					   	editurl : '<?php echo $this->formulario?>',
					   	caption: '<?php echo $this->titulo ?>',
					   	viewGridRow:true
					   	//
				
					});
					
	


					jQuery.jgrid.edit = {
							width:"500",
						    addCaption: "Agregrar registro",
						    editCaption: "Editar registro",
						    bSubmit: "Guardar",
						    bCancel: "Cancelar",
						    processData: "Procesando...",
						    reloadAfterSubmit: true,
						    msg: {
						        required:"El campo es requerido",
						        number:"Por favor ingrese un numero valido!",
						        minValue:"value must be greater than or equal to ",
						        maxValue:"value must be less than or equal to"
						    },
						    afterComplete: function(data){
							  	json=data.responseText;
							  	myJson=$.parseJSON(json);
							  	//alert(myJson.mensaje);
							  
							  	$("#list").setGridParam({datatype:'json', rowTotal:<?php echo $this->totalRegistros ?>,}).trigger('reloadGrid');

							  
							  	jQuery("#FormError").addClass("ui-state-error");
							  	jQuery("#FormError").html(myJson.mensaje);
							  	jQuery("#FormError").show();
					
					  		},
					  		onInitializeForm: function(){
								//parametros antes de iniciar el formulario							
							    
							}
					};




					jQuery("#list").jqGrid(
							'navGrid',
							"#pager",
							{
								edit:<?php echo $this->actualizar ?>,
								add:<?php echo $this->insertar ?>,
								del:<?php echo $this->eliminar ?>,
								search:false,
								excel:true
								
							},
							{},
							{},
							{},
							{multipleSearch:true},
							{closeOnEscape:true},
							{width:1000},
							{beforeShowForm: function(form) {
				                  centerPopup("#editmodlist");
				            }}
											
							
					).navButtonAdd('#pager',{
                         caption:"Exportar a excel", 
                         buttonicon:"ui-icon-newwin", 
                         onClickButton: function(){ 
                           location.replace('<?php echo $this->formulario?>&generarxls=generarxls','_blank');
                         }, 
                         position:"last"
                     });
					 
					 //PARA ACTIVAR LA BARRA DE BUSQUEDA
					jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}); 
				
				
			
					
				});


				</script>
						
				</head>
				<body>
				<br/>
				<center>
					<table id="list" style="width:100%" ><tr><td></td></tr></table>
					<div style="width:100%" id="pager"></div>
			
				</center>
				
				</body>
				</html>
				
				<?PHP		
		
		
	}
	
public function procesarCrud($configuracion){

		switch($this->dbms){
			case 'mysql':
				$result=$this->procesarCrud_mysql($configuracion);
			break;
			case 'oracle':
				$result=$this->procesarCrud_oracle($configuracion);
			break;
		
		}
		
		if(!isset($_REQUEST['generarxls'])){

			$responce=new stdClass();
			
			$responce->mensaje = $this->mensaje;
			$responce->page = $this->page;
			$responce->total = $this->total_pages;
			$responce->records = $this->totalRegistros;
			
			$i=0;
			while(isset($result[$i][0])){
				$responce->rows[$i]['id']=$result[$i][0];
				$responce->rows[$i]['cell']=array();
				foreach($result[$i] as $clave){
					array_push($responce->rows[$i]['cell'],utf8_encode($clave));
				}
				$i++;
			}
			
			echo json_encode($responce);
		
		}else{
			return $result;
		}
		
				
	}	


	public function procesarCrud_mysql($configuracion){
	
		$operador=isset($_REQUEST['oper']) AND $_REQUEST['oper']<>""?$_REQUEST['oper']:"";
		switch($operador){
			case 'edit':
				
				$mensaje="El registro se modifico con exito";
				
				break;
			case 'add':

				$mensaje="El registro se almaceno con exito";
								
				break;
			case 'del':
				
				break;
			default:

				break;
			
		}
		
		return $result;
	
		
				
	}	


	public function procesarCrud_oracle($configuracion){
	
		$operador=isset($_REQUEST['oper']) AND $_REQUEST['oper']<>""?$_REQUEST['oper']:"";
		switch($operador){
			case 'edit':
				
				$mensaje="El registro se modifico con exito";
				
				break;
			case 'add':

				$mensaje="El registro se almaceno con exito";
								
				break;
			case 'del':
				
				break;
			default:

				$this->page = isset($_GET['page'])?$_GET['page']:"1";
				$limit= isset($_GET['rows'])?$_GET['rows']:"";
				$sidx = isset($_GET['sidx'])?$_GET['sidx']:"";
				$sord = isset($_GET['sord'])?$_GET['sord']:"ASC";
 
				$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
				if($totalrows) {
					$limit = $totalrows;
				}
				
				if($this->totalRegistros > 0 && $limit > 0){
					$this->total_pages = ceil($this->totalRegistros/$limit);
				}else{
					$this->total_pages = 0;
				}
				if ($this->page > $this->total_pages){
					$this->page=$this->total_pages;
				}	
				$start = $limit*$this->page - $limit;
			
				if($start <0) $start = 0;
				
				$cadenaSql="SELECT ";
				$cadenaSql.=($this->getDistinct()=='true')?" DISTINCT ":"";
				$cadenaSql.="PRIMARIA ";
				foreach($this->campos as $clave){
					$cadenaSql.=($this->campo[$clave][3]=="NULL")?",".$this->campo[$clave][1]:",".$this->campo[$clave][3];
				}					
				$cadenaSql.=" FROM (";
				$cadenaSql.=" SELECT ROWNUM ORDEN,A.*";
				$cadenaSql.=" FROM ( SELECT ";
				$cadenaSql.=$this->primaria;
				$cadenaSql.=" PRIMARIA ";
				foreach($this->campos as $clave){
					$cadenaSql.=",".$this->campo[$clave][1];
				}				
				$cadenaSql.=" FROM ";
				$cadenaSql.=$this->getTabla()." ".$this->getRelacion('tablas')." WHERE 1=1 ";
				$cadenaSql.=$this->getRelacion('relaciones')." ";
				$cadenaSql.=$this->getFiltro()." ";

				if($sidx<>""){
					$cadenaSql.=" ORDER BY  $sidx  $sord";
				}
				
				$cadenaSql.=" ) A ) AS B ";
				
				if(!isset($_REQUEST['generarxls'])){
					$cadenaSql.=" WHERE ORDEN BETWEEN ".(($this->page)*1-1)*(($limit)*1+1)." AND ".((($this->page)*1-1)*(($limit)*1+1)+(($limit)*1))."";
				}
				
							
				$this->mensaje=$cadenaSql;
						
				$result=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadenaSql,"busqueda");

			break;
			
		}
		
		return $result;
	
		
				
	}	

	
}	