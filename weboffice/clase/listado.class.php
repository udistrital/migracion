<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          listado.class.php
* @author        Karen Palacios
* @revision      �ltima revisi�n 18 de marzo de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		
* @package		clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		1.0.0.1
* @author			Karen Palacios
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Clase principal del framework. Gestiona la creacion de listados
* @description 
/*--------------------------------------------------------------------------------------------------------------------------*/
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class listado extends funcionGeneral
{
	var $newtab;	
	var $newlabel;	
	
	 public function __construct($configuracion)
	{       error_reporting(0);
		$this->pagina;
		$this->setHojaActual($_REQUEST['hoja']);
		$this->setOrden($_REQUEST['orden']);
		$this->filtro=array();
		$this->columnas=array();
		$this->etiquetas=array();
		$this->confRegistro=$configuracion['registro'];
	}
	

	function setTabla($nombre,$dbms){
		$this->tabla=$nombre;	
		$this->dbms=$dbms;
	}

	function getTabla(){
		return $this->tabla;
	}	

	function setPagina($nombre){
		$this->pagina=$nombre;	
	}

	function getPagina(){
		return $this->pagina;
	}

	function setRegistro($registro){
		$this->registro=$registro;	
	}

	function getRegistro(){
		return $this->registro;
	}
	function setCheck($name,$value){
		$this->check[0]=$name;	
		$this->check[1]=$value;	
	}

	function getCheck(){
		return $this->check;
	}
	
	function setNumRegistros($numero){
		if(isset($numero)){
			$this->numRegistros=$numero;	
		}else{
			$this->numRegistros=$this->confRegistro;
		}
	}

	function getNumRegistros(){
		return (($this->numRegistros)*1);
	}
	
	function setFiltro($campo,$valor){
		$this->filtro[$campo]=$valor;	
	}

	function getFiltro(){
		$salida="";
		foreach($this->filtro as $clave=>$valor){
			$salida.="AND ".$clave.$valor." ";
		}	
		return $salida;
	}
	
	function setColumna($etiqueta,$campo){
		$this->columna[$campo]=$etiqueta;	
	}

	function getColumna($parametro){
	
		$columnas=array();
		$etiquetas=array();
		
		foreach($this->columna as $clave=>$valor){
			 array_push($columnas,$clave);
			 array_push($etiquetas,$valor);
		}
		if($parametro=='columnas'){
			return $columnas;
		}	
		elseif($parametro=='etiquetas'){
			return $etiquetas;
		}	
	}

	function setRelacion($tabla,$relacion){
		$this->relacion[$tabla]=$relacion;	
	}

	function getRelacion($parametro){
	
		$tablas="";
		$relaciones="";
		
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
	function setHojaActual($hoja){
		if(isset($hoja)){
			$this->hojaActual=$hoja;	
		}else{
			$this->hojaActual=1;
			$_REQUEST['hoja']=1;
		}	
	}
	
	function getORden(){
		return $this->orden;
	}
	
	function setOrden($columna){
		if(isset($columna)){
			$this->orden=$columna;	
		}else{
			$this->orden=1;
			$_REQUEST['orden']=1;
		}	
	}
	
	function getHojaActual(){
		return $this->hojaActual;
	}	
		
	
	function armarRegistro($conexion){

		$this->columnas=$this->getColumna('columnas');
		$this->etiquetas=$this->getColumna('etiquetas');
		
		if(!is_array($this->registro)){
			switch($this->dbms){
					case "oracle":
					
						$cadena_tot="SELECT COUNT(*) ";
						$cadena_tot.=" FROM ";
						$cadena_tot.=$this->getTabla().$this->getRelacion('tablas')." WHERE 1=1 ";
						$cadena_tot.=$this->getRelacion('relaciones')." ";
						$cadena_tot.=$this->getFiltro()." ";
						$registro=$this->ejecutarSQL($configuracion,$conexion,$cadena_tot,"busqueda");
						$this->total=$registro[0][0];
						//echo "total=".$this->total;
					
						$limites="WHERE R BETWEEN ".(($this->getHojaActual())*1-1)*(($this->getNumRegistros())*1+1)." AND ".((($this->getHojaActual())*1-1)*(($this->getNumRegistros())*1+1)+(($this->getNumRegistros())*1))."";
					
						if($_REQUEST['generaxls']){
							$limites="";
						}
					
						$cadena_sql="SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY ".$this->columnas[($this->getOrden())-1]." ASC) R";
						foreach($this->columnas as $valor){
							$cadena_sql.=",".$valor;
						}
						$cadena_sql.=" FROM ";
						$cadena_sql.=$this->getTabla().$this->getRelacion('tablas')." WHERE 1=1 ";
						$cadena_sql.=$this->getRelacion('relaciones')." ";
						$cadena_sql.=$this->getFiltro()." ";
						$cadena_sql.=") as consulta ";
						$cadena_sql.=$limites;
						
							//echo "<br>cadena= ".$cadena_sql;	
						//$cadena_sql="select * from ( select a.*, ROWNUM rnum from (select ape_ano,1 from acasperi ORDER BY ape_ano) a where ROWNUM <= 10) where rnum > 0";
					break;
					case "mysql":
					
					
					break;
					
			}
			$this->setRegistro($this->ejecutarSQL($configuracion, $conexion,$cadena_sql,"busqueda"));
			$this->totalParcial=$this->totalRegistros($configuracion,$conexion);

		}else{

			//espacio para limitar el array
			//debe retornar un $this->registro limitado y $this->totalParcial
			/*echo "<pre>";
			var_dump($this->getRegistro());
			echo "</pre>";*/
			$this->totalParcial=2;
			
			
		}
				
		
		
	
	}
	
	
	function armarListado($configuracion,$conexion)
	{
		ob_start();
	  /*la funcion menu navegacion requiere: configuracion,el numero de la hoja actual,el numero total de hojas,y una variable */
		setlocale(LC_MONETARY, 'en_US');		
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
	
		$menu=new navegacion();
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		$this->armarRegistro($conexion);
		$this->totalPaginas=ceil($this->total/$this->getNumRegistros());
		
		if(isset($_REQUEST['pagina'])){
			$pagina='pagina';
		}
		elseif(isset($_REQUEST['no_pagina'])){
			$pagina='no_pagina';
		}
		if(isset($_REQUEST['opcion'])){
			$variableNavegacion["opcion"]=$_REQUEST['opcion'];
		}
		$this->setPagina($_REQUEST[$pagina]);
		$variableNavegacion[$pagina]=$_REQUEST[$pagina];
		$variableNavegacion["orden"]=$this->getOrden();
		
		
		
			
															
		if(isset($_REQUEST['generaxls'])){
				$nom_archivo=time();
				$archivo=$configuracion["raiz_documento"]."/documento/listados/".$nom_archivo.".txt"; // el nombre de tu archivo
				$crea=fopen($archivo,"w"); // abrimos el archivo como escritura
				$i=0;
				for($i;$i<count($this->etiquetas);$i++){
					fwrite($crea,$this->etiquetas[$i].",");
				}
				fwrite($crea,chr(10));
				
				$contador=0;
				for($contador;$contador<$this->totalParcial;$contador++)
				{
					$j=1;
					for($j;$j<=count($this->columnas);$j++){
						fwrite($crea,$this->registro[$contador][$j].","); // guardamos el valor
					}
					fwrite($crea,chr(10));			
				}
				fclose($crea);
				
				echo "<script>window.open('".$configuracion["host"].$configuracion["site"]."/documento/lista.php?archivo=".$nom_archivo."')</script>";
		}else{
				echo "<a href='".$indice.$cripto->codificar_url($pagina."=".$this->getPagina()."&orden=".$this->getOrden()."&opcion=".$_REQUEST['opcion']."&generaxls=generaxls",$configuracion)."'>Generar XLS</a>";
				if($this->totalPaginas>1){
					$menu->menu_navegacion($configuracion,$this->getHojaActual(),$this->totalPaginas,$variableNavegacion);
				}
	
				$html.="<table width='100%' style='border-collapse:collapse; border-spacing:0;'>";
				$html.="<tr class='cuadro_color'>";

				$check=$this->getCheck();
				$html.="<td class='cuadro_plano'>#</td>";
				
				if(isset($check)){
					$html.="<td class='cuadro_plano'> </td>";	
				}
				
				
				
				$i=0;
				
				for($i;$i<count($this->etiquetas);$i++){
					$html.="<td style='border:1px solid #AAAAAA; background-color: #CCCCCC; font-family:Arial,Verdana,Trebuchet MS,Helvetica,sans-serif; font-size:12px;'><a href=".$indice.$cripto->codificar_url($pagina."=".$this->getPagina()."&opcion=".$_REQUEST['opcion']."&orden=".($i+1),$configuracion)."'>".$this->etiquetas[$i]."</a></td>";
				}
				$html.="</tr>";
				$contador=0;
				$numColumnas=count($this->columnas);
				for($contador;$contador<$this->totalParcial;$contador++)
				{
					$html.="<tr>";
					$html.="<td style='border:1px solid #AAAAAA; font-family:Arial,Verdana,Trebuchet MS,Helvetica,sans-serif; font-size:11px;'>".$this->registro[$contador][0]."</td>";	
					if(isset($check)){
						$html.="<td style='border:1px solid #AAAAAA; font-family:Arial,Verdana,Trebuchet MS,Helvetica,sans-serif; font-size:11px;'><input type='checkbox' name='".$check[0].$this->registro[$contador][0]."' value='".$this->registro[$contador][($check[1]+1)]."'></td>";	
					}
					$j=1;
					for($j;$j<=$numColumnas;$j++){
						$html.="<td style='border:1px solid #AAAAAA; font-family:Arial,Verdana,Trebuchet MS,Helvetica,sans-serif; font-size:11px;'>".$this->registro[$contador][$j]."</td>";
					}
					$html.="</tr>";
				}
			
				 $html.="</table>";
		
		}
		ob_end_flush();
		return $html;
	}
}	