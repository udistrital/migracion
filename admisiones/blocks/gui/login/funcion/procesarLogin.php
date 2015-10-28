<?php
$rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
include_once($rutaClases."/log.class.php");
$this->log_us = new log();

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    //1. Verificar que el usuario esté registrado en el sistema
    $variable["usuario"] = $_REQUEST["usuario"];
   
    /**
     * @todo En entornos de producción la clave debe codificarse utilizando un objeto de la clase Codificador
     */
    $variable["clave"] = $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST["clave"]);
    /* $conexion="aplicativo";
      $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion); */
    
    //No se para qué es eto
    $conexion = "admisiones";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
    if (!$esteRecursoDB) {

        echo "//Este se considera un error fatal";
        exit;
    }
    //var_dump($_REQUEST);                                    
    $cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    if ($registro) {
        $usuario=$registro[0]['id_usuario'];
        //echo $registro[0]["id_usuario"];
        $variable[6]=$registro[0]['rba_ref_pago'];
        $variable[0]="INGRESAR";
        $variable[1]=$registro[0]["rba_id"]; //
        $variable[2]="Inicio sesion";
        $variable[3]="Ingresar a admisiones"; //
        $variable[4]=time();
        $variable[5]="Ingresa al módulo de admisones ";
        $variable[5].="Id referencia pago: ".$registro[0]["rba_id"];

        $this->log_us->log_usuario($variable,$esteRecursoDB);    
        //echo $registro[0]['clave']." Clave BD<br>";
        //echo $variable["clave"]." Clave Form<br>";   
        if ($registro[0]['clave'] == $variable["clave"]) {
            if($registro[0][4]<$registro[0][5])
            {
                $this->funcion->redireccionar("paginaPrincipal",'valorInscripcion');
            }    
            //1. Crear una sesión de trabajo
            $estaSesion = $this->miSesion->crearSesion($registro[0]["id_usuario"]);
			
	    $arregloLogin = array($registro[0]["id_usuario"], $_SERVER['REMOTE_ADDR'],time());
			
		//$cadena_sql = $this->sql->cadena_sql("loginOK", $arregloLogin);
    		//$registroAcceso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            
            if ($estaSesion) {
            	                
                $registro[0]["sesionID"] = $estaSesion;
				
                                switch($registro[0]["tipo"])
				{
                                        case '1':
                                           // var_dump($registro[0]); exit;
                                            $resultado = $this->miSesion->setValorSesion('rba_id', $registro[0]["rba_id"]);
                                            $this->funcion->redireccionar("indexInscripcion", $registro[0]);
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
			
		
    	// Redirigir a la página de inicio con mensaje de error en usuario/clave
            $this->funcion->redireccionar("paginaPrincipal",'usuario');
    }
}
?>