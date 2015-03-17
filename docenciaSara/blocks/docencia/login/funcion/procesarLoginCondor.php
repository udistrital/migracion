<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	//1. Verificar que el usuario esté registrado en el sistema
       
	
		$variable["usuario"]=$_REQUEST["usuario"];
                $variable["modulo"]=$_REQUEST["modulo"];
		
		/*$conexion="aplicativo";
		 $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);*/

		$conexion="docente";                
		$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                
		if(!$esteRecursoDB){

			//Este se considera un error fatal
			exit;

		}

		$cadena_sql=$this->sql->cadena_sql("buscarUsuarioOracle",$variable);
                
		$registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                
		if($registro){
                    //Redirigir a la página principal del usuario
                    $this->funcion->redireccionar($_REQUEST["opcionPagina"],$registro[0]);
                    return true;
                }

		// Redirigir a la página de inicio con mensaje de error en usuario/clave
		$this->funcion->redireccionar("paginaPrincipal");

	}




?>