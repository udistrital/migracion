<?php

$miSesion = Sesion::singleton ();


if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
        $miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
	switch($opcion)
	{
                case "indexAdminAdmisiones":
                        $variable="pagina=indexAdminAdmisiones";
			$variable.="&redireccionar=true";
			$variable.="&mensaje=bienvenida";
			$variable.="&usuario=".$_REQUEST["usuario"];
                        if(isset($_REQUEST["tipo"]))
                        {    
                            $variable.="&tipo=".$_REQUEST["tipo"];
                        }
                        else
                        {
                            $variable.="&tipo=1";
                        }    
                        $variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
			break;
                
                case "indexInscripcion":
                    //var_dump($valor);  exit;
                        $variable="pagina=admisiones";
			$variable.="&redireccionar=true";
			$variable.="&rba_id=".$valor["rba_id"];
			$variable.="&usuario=".$_REQUEST["usuario"];
                        $variable.="&tipo=1";
                        $variable.="&tiempo=".time();
			$variable.="&sesionID=".$valor["sesionID"];
                        break;
                
                case "paginaPrincipal":
			$variable="pagina=index";
			if(isset($valor) && $valor!='')
			{
				$variable.="&error=".$valor;
			}
			break;


	}

	foreach($_REQUEST as $clave=>$valor)
	{
                unset($_REQUEST[$clave]);

	}

	
	$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");
	$variable=$this->miConfigurador->fabricaConexiones->crypto->codificar($variable);

	$_REQUEST[$enlace]=$variable;
	$_REQUEST["recargar"]=true;

}

?>