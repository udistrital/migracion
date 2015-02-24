<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
require_once($configuracion["raiz_documento"].$configuracion["javascript"]."/Twig/Autoloader.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");	
include_once("sql.class.php");	
		

class funciones_admin_intHorariaLote extends funcionGeneral
{

	function __construct($configuracion)
	{
		$this->acceso_mysql=$this->conectarDB($configuracion,'');
		$this->acceso_oci=$this->conectarDB($configuracion,'administrador');
		$this->usuario=$this->rescatarValorSesion($configuracion,$this->acceso_mysql,"usuario");
		$this->cripto=new encriptar();
		$this->sql=new sql_panelPrincipal();
		$this->configuracion=$configuracion;
		$this->indice=$configuracion["host"]."/weboffice/index.php?";
		$this->error=array();
		$this->confirm = "";
		
	}
	
	function pintarFormulario(){
	
		echo "<div class='centralencabezado'>";
		echo "<form action='index.php'>";
		echo "<br/>1> NOTAS QUE PERTENECEN AL PLAN DE ESTUDIOS (SIN CREDITOS)";
		$registro=$this->consultarAsignaturasPertenecen();
		echo "<br/>TOTAL=".count($registro);
		
		echo "<input type='submit' value='Actualizar'>";
		$variable="action=admin_intensidadHorariaLote";
		$variable.="&opcion=pertenecenCRED";
		$variable=$this->cripto->codificar_url($variable,$this->configuracion);
		echo "<input type='hidden' name='formulario' value='{$variable}'>";
		
		echo "</form>";
		echo "</div>";
		
		
		
		echo "<div class='centralencabezado'>";
		echo "<form>";
		echo "<br/>2> NOTAS QUE PERTENECEN AL PLAN DE ESTUDIOS (QUEDARON SIN CLASIFICACION)";
		$registro=$this->consultarAsignaturasRestantesPertenecen();
		echo "<br/>TOTAL=".count($registro);
		
		echo "<input type='submit' value='Actualizar'>";
		$variable="action=admin_intensidadHorariaLote";
		$variable.="&opcion=pertenecenCLAS";
		$variable=$this->cripto->codificar_url($variable,$this->configuracion);
		echo "<input type='hidden' name='formulario' value='{$variable}'>";
		
		echo "</form>";
		echo "</div>";
		
		
		
		echo "<div class='centralencabezado'>";
		echo "<form>";
		echo "<br/>3> NOTAS QUE NO PERTENECEN AL PLAN DE ESTUDIOS (SIN CREDITOS)";
		$registro=$this->consultarAsignaturasNoPertenecen();
		echo "<br/>TOTAL=".count($registro);

		echo "<input type='submit' value='Actualizar'>";
		$variable="action=admin_intensidadHorariaLote";
		$variable.="&opcion=nopertenecenCRED";
		$variable=$this->cripto->codificar_url($variable,$this->configuracion);
		echo "<input type='hidden' name='formulario' value='{$variable}'>";
		
		echo "</form>";
		echo "</div>";
		
		
		echo "<div class='centralencabezado'>";
		echo "<form>";
		echo "<br/>4> NOTAS QUE NO PERTENECEN AL PLAN DE ESTUDIOS  (QUEDARON SIN CLASIFICACION) ";
		$registro=$this->consultarAsignaturasNoRestantesPertenecen();
		echo "<br/>TOTAL=".count($registro);
		
		echo "<input type='submit' value='Actualizar'>";
		$variable="action=admin_intensidadHorariaLote";
		$variable.="&opcion=nopertenecenCLAS";
		$variable=$this->cripto->codificar_url($variable,$this->configuracion);
		echo "<input type='hidden' name='formulario' value='{$variable}'>";	
		
		echo "</form>";
		echo "</div>";

		
	}
	


	function actualizarAsignaturas($opcion){
		echo $opcion;
		switch($opcion){
			case 'pertenecenCRED':
				$registro=$this->consultarAsignaturasPertenecen();
				$opcion="actualizarAsignaturas";
			break;
			case 'pertenecenCLAS':
				$registro=$this->consultarAsignaturasRestantesPertenecen();
				$opcion="actualizarCEACOD";
			break;
			case 'nopertenecenCRED':
				$registro=$this->consultarAsignaturasNoPertenecen();
				$opcion="actualizarAsignaturas";
			break;
			case 'nopertenecenCLAS':
				$registro=$this->consultarAsignaturasNoRestantesPertenecen();
				$opcion="actualizarCEACOD";
			break;			
		}
		
		$i=0;
		while(isset($registro[$i][0])){
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,$opcion,$registro[$i]);
			$resultado=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");
			$i++;
		}
		//return $registro;
	}	
	
	//las que quedaron no tienen creditos
	function consultarAsignaturasPertenecen(){
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturasPertenecenCRED",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		return $registro;
	}
	
	//las que quedaron sin cea_cod
	function consultarAsignaturasRestantesPertenecen(){
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturasPertenecenCLAS",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		return $registro;
	}
	
	//Las que no pertencen al plan q toca sacarlas por accursohis
	function consultarAsignaturasNoPertenecen(){
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturasNoPertenecenCRED",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		
		return $registro;
	}	
	
	//Las que quedaron sin cea_cod que no pertenecen al plan q toca sacarlas por accursohis
	function consultarAsignaturasNoRestantesPertenecen(){
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturasNoPertenecenCLAS",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		
		return $registro;
	}
	
	
	

		
}
	

?>

