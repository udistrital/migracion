<?php

//$this->miConfigurador->fabricaConexiones->crypto->codificar(

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    //1. Verificar que el usuario esté registrado en el sistema
    $variable["usuario"] = $_REQUEST["usuario"];
   
    /*$clave="";
    $codifica=$this->miConfigurador->fabricaConexiones->crypto->codificarClave($clave);
    echo "mmm".$codifica."<br>";*/
    
    /**
     * @todo En entornos de producción la clave debe codificarse utilizando un objeto de la clase Codificador
     */
    $variable["clave"] = $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST["clave"]);
    //echo $variable["clave"];
     
    /* $conexion="aplicativo";
      $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion); */
    
    //No se para qué es eto
    $conexion = "estructura";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
    if (!$esteRecursoDB) {

        echo "//Este se considera un error fatal";
        exit;
    }
    //var_dump($_REQUEST);                                    
    $cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
  
    if ($registro) {        
           if ($registro[0]['clave'] == $variable["clave"]) {
        	 
            //1. Crear una sesión de trabajo
            $estaSesion = $this->miSesion->crearSesion($registro[0]["id_usuario"]);
			
			$arregloLogin = array($registro[0]["id_usuario"], $_SERVER['REMOTE_ADDR'],time());
			
		//$cadena_sql = $this->sql->cadena_sql("loginOK", $arregloLogin);
    		//$registroAcceso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            
            if ($estaSesion) {
            	                
                $registro[0]["sesionID"] = $estaSesion;
				//echo $registro[0]["tipo"]; exit;
				switch($registro[0]["tipo"])
				{
                                        case '1':
                                                $this->funcion->redireccionar("gestionPassword", $registro[0]);
						break;
                                        case '2':
                                                $this->funcion->redireccionar("validaActualizacion", $registro[0]);
						break;    
                                }
                //Redirigir a la página principal del usuario, en el arreglo $registro se encuentran los datos de la sesion:
                //$this->funcion->redireccionar("indexUsuario", $registro[0]);
                return true;
            }
        } else {
        	/*$arregloClave = array($variable["usuario"], $_SERVER['REMOTE_ADDR'],time());
			
			$cadena_sql = $this->sql->cadena_sql("claveInvalida", $arregloClave);
    		$registroAccesoClave = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            // Redirigir a la página de inicio con mensaje de error en usuario/clave*/
            $this->funcion->redireccionar("paginaPrincipal",'clave');
        }
    }else
    {
    	$arregloNoExiste = array($variable["usuario"], $_SERVER['REMOTE_ADDR'],time());
			
		/*$cadena_sql = $this->sql->cadena_sql("usuarioNoExiste", $arregloNoExiste);
		$registroAccesoNoExiste = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");*/
    	// Redirigir a la página de inicio con mensaje de error en usuario/clave
            $this->funcion->redireccionar("paginaPrincipal",'usuario');
    }
}
?>