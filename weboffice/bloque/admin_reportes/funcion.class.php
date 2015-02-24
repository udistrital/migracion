<?php

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funcion_Reportes extends funcionGeneral {
	
	function rescatarVariable($variable,$usuario,$configuracion,$conexion){
		switch($variable){
		
			case "CRACOORDINADOR":
				$acceso=$this->conectarDB($configuracion,$usuario);
				$usuario=$this->rescatarValorSesion($configuracion,$conexion,"usuario");
				$cadena_sql="SELECT CRA_COD FROM ACCRA WHERE CRA_EMP_NRO_IDEN=".$usuario;
				$registro=$this->ejecutarSQL($configuracion, $acceso, $cadena_sql,"busqueda");
				$i=0;
				while(isset($registro[$i][0])){
					$resultado[]=$registro[$i][0];
					$i++;
				}
				$resultado=implode(',',$resultado);
			break;

		}
	
		return $resultado;
	}
}	