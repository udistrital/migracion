<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Karen Palacios
* @revision      Última revisión 07 de Abril de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		menu_coordinador
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Karen Palacios/
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque que contiene los enlaces a las diferentes secciones del
*				modulo recibos de pago->coordinador
*
/*--------------------------------------------------------------------------------------------------------------------------*/

	
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/listado.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

	$conexion= new funcionGeneral();
	$milista=new listado($configuracion);
	
	$acceso_db=$conexion->conectarDB($configuracion,"");
	$usuario=$conexion->rescatarValorSesion($configuracion,$acceso_db, "id_usuario");

	$html='<body style="background-color: #FFFFFF;  border:1px solid #AAAAAA; font-family:Arial,Verdana,Trebuchet MS,Helvetica,sans-serif; font-size:11px;">';
	switch($_REQUEST['opcion']){
		
		case 'InscritosECAES':
		
			$accesoOracle=$conexion->conectarDB($configuracion,"coordinador");
			$milista=new listado($configuracion);
			$milista->setNumRegistros(20);
			$milista->setTabla('mntac.acestecaes','oracle');
			$milista->setRelacion('acest','eca_cod=est_cod');
			$milista->setRelacion('accra','cra_cod=est_cra_cod');
			$milista->setColumna('carrera','cra_nombre');		
			$milista->setColumna('codigo','est_cod');
                        $milista->setColumna('documento','est_nro_iden');                        
			$milista->setColumna('nombre','est_nombre');
			$milista->setColumna('recibo','eca_genero_recibo');
			$milista->setColumna('pago','eca_pago_recibo');
			$milista->setFiltro('cra_emp_nro_iden=',$usuario);			
			$milista->setFiltro('eca_estado=',"'A'");	
			$milista->setFiltro('eca_ano=',$conexion->datosGenerales($configuracion,$accesoOracle, "anno"));
			$milista->setFiltro('eca_per=',$conexion->datosGenerales($configuracion,$accesoOracle, "per"));
			$html.=$milista->armarListado($configuracion,$accesoOracle);			
		break;
		default:
			$html="NO EXISTE UN LISTADO DISPONIBLE";
		break;
	
	}
	echo $html;
?>
