<?php

require_once("core/auth/sesionSql.class.php");


class Sesion
{
	/** Aggregations: */

	/** Compositions: */

	/*** Attributes: ***/

	/**
	 * Miembros privados de la clase
	 * @access private
	 */
	private static $instancia;


	/**
	 * Atributos de la sesión
	 */

	var $sesionId;
	var $sesionExpiracion;
	var $sesionUsuarioId;
	var $registro_sesion;
	var $miConfigurador;


	/**
	 * @name sesiones
	 * constructor
	 */
	private function __construct()
	{
		$this->miConfigurador=Configurador::singleton();

		$this->miSql=new sesionSql();


		// Valores predefinidos para las sesiones
		$this->sesionUsuario= $this->miConfigurador->fabricaConexiones->miLenguaje->getCadena("usuarioAnonimo");
		$this->sesionUsuarioNombre= '';
		$this->sesionExpiracion=$this->miConfigurador->getVariableConfiguracion("expiracion");
		$this->sesionNivel=0;
	}

	public static function singleton()
	{
		if (!isset(self::$instancia)) {
			$className = __CLASS__;
			self::$instancia = new $className;
		}
		return self::$instancia;
	}

	/**
	 * @name sesiones Verifica la existencia de una sesion válida en la máquina del cliente
	 * @param string nombre_db
	 * @return void
	 * @access public
	 */
	function verificarSesion()
	{

		//Se eliminan las sesiones expiradas
		$borrar=$this->borrarSesionExpirada();

		if($this->sesionNivel>0){

			//Verificar si en el cliente existe y tenga registrada una cookie que identifique la sesion
			$this->sesionId=$this->numeroSesion();
				
			if($this->sesionId){
				$resultado=$this->abrirSesion($this->sesionId);

				/* Detecta errores*/
				if ($resultado == false)
				{
					return false;
				}
				else
				{
					// Si no hubo errores se puede actualizar los valores
					// Update, porque se tiene un identificador
					/*Crear una nueva cookie*/
					setcookie("aplicativo",$this->sesionId,(time()+$this->sesionExpiracion),"/");
					$cadenaSql=$this->miSql->getCadenaSql("actualizarSesion",$this->sesionId);

					/**
					 * Ejecutar una consulta
					*/

					$conexion="configuracion";
					$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
					$resultado=$esteRecursoDB->ejecutarAcceso($cadenaSql,"acceso");

					return $resultado;
				}
			}

			return false;
		}
		return true;
	}


	/**
	 *@METHOD numero_sesion
	 *
	 * Rescata el número de sesion correspondiente a la máquina
	 * @PARAM sesion
	 * @return valor
	 * @access public
	 */

	function numeroSesion()
	{

		if(isset($_COOKIE["aplicativo"]))
		{
			$this->sesionId=$_COOKIE["aplicativo"];
		}
		else
		{
			if(isset($_REQUEST["sesionID"]))
			{
				$this->sesionId=$_REQUEST["sesionID"];
			}
			else
			{
				return false;
			}

		}
		return $this->sesionId;

	}/*Fin de la función numero_sesion*/


	/**
	 *@METHOD abrir_sesion
	 *
	 * Busca la sesión en la base de datos
	 * @PARAM sesion
	 * @return valor
	 * @access public
	 */

	function abrirSesion($sesion)
	{
		// Primero se verifica la longitud del parámetro
			
		if (strlen($sesion) != 32)
		{
			return FALSE;
		}
		else
		{
			// Verifica la validez del id de sesion

			if ($this->caracteresValidos($sesion) != strlen($sesion))
			{
				return FALSE;
			}

			$this->setSesionId($sesion);
			// Busca una sesión que coincida con el id del computador y el nivel de acceso de la página
			$this->el_resultado=$this->getValorSesion('idUsuario');
			if($this->el_resultado)
			{
				/**
				 * @todo: Cambiar para soportar JSON
				 */
				if($this->el_resultado[0][0]>=$this->sesionNivel)
				{
					
					echo "Esto no es lo que se pretende!!";
					$this->el_resultado=$this->getValorSesion('expiracion');
					if($this->el_resultado[0][0]>time())
					{
						return TRUE;
					}
					else
					{
						return FALSE;
					}
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}

		}
			

			
	} //Final del método abrir_sesion


	/**
	 *@METHOD caracteres_validos
	 *
	 * Verifica que los caracteres en el identificador de sesión sean válidos
	 * @PARAM cadena
	 * @return valor
	 * @access public//Realizar un barrido por la matriz de resultados para comprobar que se tiene los privilegios para la pagina
	 $this->validacion=0;
	 for($this->i=0;$this->i<$this->count;$this->i++)
	 {
	 */


	function caracteresValidos($cadena)
	{
		// Retorna el número de elementos que coinciden con la lista de caracteres
		return strspn($cadena, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
	}



	/**
	 *@METHOD crear_sesion
	 *
	 * Crea una nueva sesión en la base de datos.
	 * @PARAM usuario_aplicativo
	 * @PARAM nivel_acceso
	 * @PARAM expiracion
	 * @PARAM conexion_id
	 * @return boolean
	 * @access public
	 */

	function crearSesion($usuarioId)
	{

		//1. Identificador de sesion
		$this->fecha=explode (" ",microtime());
		$this->sesionId=md5($this->fecha[1].substr($this->fecha[0],2).rand());


		if (strlen($this->sesionId) != 32)
		{
			return FALSE;
		}
		else
		{
			// Verifica la validez del id de sesion
			if ($this->caracteresValidos($this->sesionId) != strlen($this->sesionId))
			{
				return FALSE;
			}
				
				
			/**Borra todas las sesiones que existan con el id del computador*/

			if(isset($_COOKIE["aplicativo"]))
			{
				$this->la_sesion=$_COOKIE["aplicativo"];
				$this->terminarSesion($configuracion,$this->la_sesion);
					
			}
				
			/*Actualizar la cookie*/
			$tiempo=time()+$this->sesionExpiracion*60;
			//echo $tiempo;
			//echo "La expiracion de las cookies es: ".$this->sesionExpiracion."<br>";
			setcookie("aplicativo",$this->sesionId,$tiempo,"/");
			/*Borrar cookies anteriores
			 setcookie("aplicativo","",time()+60*60*2,"/");*/
			//$cadena_sql = "INSERT INTO ".$configuracion["prefijo"]."valor_sesion ( id_usuario , sesionId , expiracion , nivel_acceso ) VALUES ('".$this->usuario."', '".$this->sesionId."',".(time()+$this->sesionExpiracion).",".$this->nivel.")";
				
			//Insertar id_usuario
			$this->resultado=$this->guardarValorSesion('idUsuario',$usuarioId,$this->sesionId,$tiempo);
				

			if($this->resultado){
				return $this->sesionId;

			}else{

				return false;
			}
		}
			
	}//Fin del método crear_sesion





	/**
	 *@METHOD guardar_valor_sesion
	 * @PARAM variable
	 * @PARAM valor
	 * @return boolean
	 * @access public
	 */

	function guardarValorSesion($variable,$valor,$sesion, $expiracion)
	{
		$num_args = func_num_args();
		if ($num_args == 0)
		{
			return FALSE;
		}
		else
		{
			if(strlen($sesion)!=32)
			{
				if(isset($_COOKIE["aplicativo"]))
				{
					$this->sesionId=$_COOKIE["aplicativo"];
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				$this->sesionId=$sesion;
			}


			// Si el valor de sesión existe entonces se actualiza, si no se crea un registro con el valor.

			$parametro["sesionId"]=$this->sesionId;
			$parametro["variable"]=$variable;
			$parametro["valor"]=$valor;
			$parametro["expiracion"]=$expiracion;
			$cadenaSql=$this->miSql->getCadenaSql("buscarValorSesion",$parametro);

			/**
			 * Ejecutar una consulta
			*/

			$conexion="configuracion";
			$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
			$resultado=$esteRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
				
			if($resultado){

				$cadenaSql=$this->miSql->getCadenaSql("actualizarValorSesion",$parametro);
			}
			else
			{
				$cadenaSql=$this->miSql->getCadenaSql("insertarValorSesion",$parametro);
			}
			$resultado=$esteRecursoDB->ejecutarAcceso($cadenaSql,"acceso");
			return $resultado;


		}
	}//Fin del método guardar_valor_sesion



	/**
	 *@METHOD borrar_valor_sesion
	 * @PARAM variable
	 * @PARAM valor
	 * @return boolean
	 * @access public
	 */

	function borrarValorSesion($configuracion,$variable,$sesion)
	{
		$num_args = func_num_args();
			
		if ($num_args == 0)
		{
			return FALSE;
		}
		else
		{


			if(strlen($sesion)!=32)
			{
				if(isset($_COOKIE["aplicativo"]))
				{
					$this->sesionId=$_COOKIE["aplicativo"];
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				$this->sesionId=$sesion;

			}
			if($variable!='TODOS'){
				$cadena_sql = "DELETE FROM ".$configuracion["prefijo"]."valor_sesion WHERE sesionId='".$this->sesionId."' AND variable='".$variable."'";
			}else{
				$cadena_sql = "DELETE FROM ".$configuracion["prefijo"]."valor_sesion WHERE sesionId='".$this->sesionId."'";

			}



			if(!isset($this->db_sel))
			{
				$this->db_sel = new dbms($configuracion);
				$this->db_sel->especificar_enlace($this->conexion_id);
			}


			$resultado=$this->db_sel->ejecutar_acceso_db($cadena_sql);
			//echo $resultado; importante para depurar
			return $resultado;
		}
			
	}//Fin del método borrar_valor_sesion

	/**
	 * @name borrar_sesion_expirada
	 * @return void
	 * @access public
	 */
	function borrarSesionExpirada()
	{

		$cadenaSql=$cadenaSql=$this->miSql->getCadenaSql("borrarSesionesExpiradas",$this->sesionId);

		$this->miConexion=$this->miConfigurador->fabricaConexiones->getRecursoDB("configuracion");

		if($this->miConexion){

			if($this->miConexion->ejecutarAcceso($cadenaSql))
			{
				return false;
			}else{
				return true;
			}
		}
			
	}//Fin del método borrar_sesion_expirada



	/**
	 *
	 * @name terminar_sesion
	 * @return boolean
	 * @access public
	 */

	function terminarSesion($sesion)
	{
		if(strlen($sesion)!=32)
		{
			return FALSE;
		}
		else
		{
			$this->esta_sesion=$sesion;
		}

		$cadena_sql = "DELETE FROM ".$configuracion["prefijo"]."valor_sesion WHERE sesionId = '".($this->esta_sesion)."'";
		if(!isset($this->db_sel)){
			$this->db_sel = new dbms($configuracion);
			$this->db_sel->especificar_enlace($this->conexion_id);
		}
		$resultado=$this->db_sel->ejecutar_acceso_db($cadena_sql);

		return $resultado;


	}//Fin del método terminar_sesion


	function borrarSesion($configuracion,$sesion)
	{
		if(strlen($sesion)!=32)
		{
			return FALSE;
		}
		else
		{
			$this->esta_sesion=$sesion;
		}

		$cadena_sql = "DELETE FROM ".$configuracion["prefijo"]."valor_sesion WHERE sesionId = '".($this->esta_sesion)."'";
		if(!isset($this->db_sel)){
			$this->db_sel = new dbms($configuracion);
			$this->db_sel->especificar_enlace($this->conexion_id);
		}
		$resultado=$this->db_sel->ejecutar_acceso_db($cadena_sql);
		return $resultado;


	}//Fin del método terminar_sesion


	function actualizarSesion($configuracion)
	{
		$resultado = $this->borrar_valor_sesion($configuracion,"id_usuario",$this->sesionId);
		$resultado &= $this->borrar_valor_sesion($configuracion,"usuario",$this->sesionId);
		$resultado &= $this->borrar_valor_sesion($configuracion,"acceso",$this->sesionId);
		$resultado &= $this->borrar_valor_sesion($configuracion,"expiracion",$this->sesionId);


		$resultado &= $this->guardarValorSesion($configuracion,"id_usuario",$this->id_usuario,$this->sesionId);
		$resultado &= $this->guardarValorSesion($configuracion,"usuario",$this->usuario,$this->sesionId);
		$resultado &= $this->guardarValorSesion($configuracion,"acceso",$this->nivel,$this->sesionId);
		$resultado &= $this->guardarValorSesion($configuracion,"expiracion",time()+$configuracion["expiracion"],$this->sesionId);


		return $resultado;
	}



	/**
	 *@METHOD setSesionId
	 *
	 * Asigna el valor del atributo sesionId
	 * @return valor
	 * @access public
	 */

	function setSesionId($sesionId)
	{

		$this->sesionId=$sesionId;

	} // end of member function especificar_sesion

	/**
	 *@METHOD setSesionExpiracion
	 * @return valor
	 * @access public
	 */

	function setSesionExpiracion($expiracion)
	{

		$this->sesionExpiracion=$expiracion;

	} //Fin del mètodo especificar_expiracion


	/**
	 *@METHOD setSesionNivel
	 *
	 * @param nivel
	 * @access public
	 */

	function setSesionNivel($nivel)
	{
		$this->sesionNivel=$nivel;

	} //Fin de la función especificar_enlace


	/**
	 *@METHOD setIdusuario
	 * @return valor
	 * @access public
	 */

	function setIdUsuario($id_usuario)
	{

		$this->setSesionUsuarioId=$id_usuario;

	} //Fin del mètodo especificar_usuario




	/**
	 *@METHOD rescatar_valor_sesion
	 * @PARAM variable
	 * @PARAM usuario_aplicativo ??
	 * @return boolean
	 * @access public
	 */


	function getValorSesion($variable)
	{
		if(isset($_COOKIE["aplicativo"]))
		{
			$this->sesionId=$_COOKIE["aplicativo"];
		}
		else
		{
			return FALSE;
		}

		// Busca la sesión
		$this->cadena_sql="SELECT ";
		$this->cadena_sql.="valor ";
		$this->cadena_sql.="FROM ";
		$this->cadena_sql.=$this->miConfigurador->getVariableConfiguracion("prefijo")."valor_sesion ";
		$this->cadena_sql.="WHERE ";
		$this->cadena_sql.="sesionId ='".($this->sesionId)."' ";
		$this->cadena_sql.="AND ";
		$this->cadena_sql.="variable='".$variable."' ";

		$miConexion=$this->miConfigurador->fabricaConexiones->getRecursoDB("configuracion");
		$resultado=$miConexion->ejecutarAcceso($this->cadena_sql,"busqueda");
		
		if($resultado)
		{
			return $resultado;
		}
		return false;


	}//Fin del método rescatar_valor_sesion	return FALSE;


	function getSesionId(){
		return $this->sesionId;
	}

	function getSesionUsuarioId(){
		return $this->sesionUsuarioId;
	}

	function getSesionNivel(){
		return $this->sesionNivel;
	}

	function getSesionExpiracion(){
		return $this->sesionExpiracion;
	}


}//Fin de la clase sesion

?>
