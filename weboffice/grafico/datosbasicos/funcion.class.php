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

class funciones_adminSolicitud extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=$sql;
		$this->formulario="admin_generados";
		$this->verificar="control_vacio(".$this->formulario.",'estudiante')";
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	

		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarEstudiante($configuracion,$opcion,$pregunta)
	{
		//Conexion ORACLE

					
		$accesoOracle=$this->conectarDB($configuracion,"coordinador");
		$conexion=$accesoOracle;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		
		$html="<div style='width:100%; height:37px; background:url(\"grafico/datosbasicos/heading_sup.png\") no-repeat;'>Bienvenido</div>";
		$html.="<div style='width:100%; height:15px; background:url(\"grafico/datosbasicos/heading_inf.png\") no-repeat;'>Hoy es Viernes 29 de Enero</div>";
		
		$html.="<table width='100%' height='400px'>";
		$html.="<tr>";
		$html.="	<td width='50%'>";
		$html.="Noticias";
		$html.="	</td>";
		$html.="	<td width='50%'>";
		$html.="Info Estudiante";		
		$html.="	</td>";		
		$html.="</tr>";
		$html.="</table>";

		
		echo $html;	
	}
	
	

	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
		//var_dump($valor);
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "siguiente":
				$variable="pagina=admin_capacitacion_funcionario";
				$variable.="&opcion=encuesta";
				$variable.="&pregunta=".$valor[0];				
				
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);

		//echo $indice.$variable;
		
		//header("Location: ".$indice.$variable);
		
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		
	
}

?>
