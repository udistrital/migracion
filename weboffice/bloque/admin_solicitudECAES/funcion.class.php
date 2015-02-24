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
	
/*
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/listado.class.php");
		$milista=new listado($configuracion);
		$milista->setNumRegistros(10);
		$milista->setTabla('acest','oracle');
		$milista->setRelacion('accra','cra_cod=est_cra_cod');
		$milista->setRelacion('acestotr','est_cod=eot_cod');
		$milista->setColumna('cra_cod','carrera');
		$milista->setColumna('est_cod','c&oacute;digo');
		$milista->setColumna('est_nombre','nombre');
		$milista->setColumna('eot_email_ins','correo');
		$milista->setColumna('est_estado_est','estado');
		$milista->setFiltro('est_cra_cod',20);
		$milista->setFiltro('est_estado_est',"'A'");
		$milista->setCheck('miname',1);
*/
	function verInscritos($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/listado.class.php");
		$milista=new listado($configuracion);
		$milista->setNumRegistros(8);
		//$milista->setRegistro($this->ejecutarSQL($configuracion,$this->accesoOracle,"select * from acest where est_cod=20032025075","busqueda"));
		$milista->setTabla('v_presentar_ecaes','oracle');
		$milista->setRelacion('accra','cra_cod=pee_cra_cod');
		$milista->setRelacion('acest','pee_cod=est_cod');
		$milista->setRelacion('acestotr','eot_cod=est_cod');
		$milista->setColumna('codigo','est_cod');
		$milista->setColumna('documento','est_nro_iden');
		$milista->setColumna('nombre','est_nombre');
		$milista->setColumna('correo','eot_email');
		$milista->setColumna('semestre','pee_semestre');
		$milista->setColumna('porcentaje','pee_porcentaje_cursado');
		$milista->setFiltro('cra_emp_nro_iden =',$this->usuario);
		$milista->setFiltro('pee_presento=',"'N'");
		$milista->setFiltro('pee_porcentaje_cursado >=',70);
		
		
		//$milista->setCheck('miname',1);
		
		echo $milista->armarListado($configuracion,$this->accesoOracle);
		//echo "<br><center><input type='submit' value='Generar Recibos'></center>"; 
	}		
		
}

?>

