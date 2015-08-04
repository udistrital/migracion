<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 19 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_lote_recibo
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.2
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para realizar la carga de solicitudes de recibo de pago
*				a traves de plantillas
*				caso de uso: SOLICITAR RECIBO DE PAGO
*
/*--------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloqueRegistroLoteRecibo extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroLoteRecibo();
 		$this->funcion=new funciones_registroLoteRecibo($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		$this->funcion->nuevoRegistro($configuracion,"");		
	}
	
	
	function action($configuracion)
	{
		
		//echo "<br>*1 Entro al action //bloque.php line 53*";
		//Cargar la plantilla y registrarla en la base de datos
		$resultado=$this->funcion->cargarArchivoLote($configuracion);
		
		if(is_array($resultado))
		{	
			//echo "<br>*5 La insercion del archivo en solicitud lote fue exitosa*  //bloque.php line 59";
			
			$valor[0]=$resultado["nombreArchivo"];
			$valor[1]=$resultado["nombreInterno"];
			$datos=$this->funcion->leerArchivoLote($configuracion, $valor[1]);
			
			if($datos!=false)
			{
				//echo "<br>*7 Retorno los valores delas celdas*  //bloque.php line 67";
				$columnas=$datos->sheets[0]['numCols']; 
				
				//Se revisa que las columnas leidas correspondan a las que tiene cada una de la plantillas.
				//Para el caso de postgrado 26
				//Para el caso de pregrado 13
				//echo "<br>*8 Numero de Columnas= $columnas*  //bloque.php line 73";
				
				if($columnas==28||$columnas==13)
				{
					$resultado=$this->funcion->verificarLote($valor, $datos, $datos->sheets[0]['cells'][1][1], $configuracion,$this->funcion->acceso_db);
					if($resultado==false)
					{
						//echo "<br>*9 El resultado de verificarlote fue false*  //bloque.php line 80";
						$this->funcion->mensajeErrorCarga($configuracion, "inconsistencia", $valor);
					}
					
				}
				else
				{
					$this->funcion->errorCarga["numeroColumnas"]="El n&uacute;mero de columnas leidas no corresponde a ninguna plantilla.";					
					$this->funcion->mensajeErrorCarga($configuracion, "inconsistencia", $valor);
				
				}
			}
			else
			{
				$this->funcion->mensajeErrorCarga($configuracion, "sinLeer", $valor);
			}
			
			
		}
		else
		{
			if(isset($this->funcion->errorCarga))
			{
				foreach($this->funcion->errorCarga as $clave =>$valor)
				{
					$this->funcion->mensajeErrorCarga($configuracion, "",$valor);
				
				}
			}
			$this->funcion->mensajeErrorCarga($configuracion, "noArchivo");
		}
			
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueRegistroLoteRecibo($configuracion);

if(!isset($_REQUEST['procesar']))
{	
	echo "***************************sin procesar********************************";
	$esteBloque->html($configuracion);
}
else
{	echo "---------------------procesando-----------------------------------------";	
	$esteBloque->action($configuracion);
}


?>
