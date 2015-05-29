<?PHP
/*
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');

require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');*/
/*

require_once(dir_conect.'valida_pag.php');
;*/
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'NumeroVisitas.php');
require_once("../clase/multiConexion.class.php");

fu_tipo_user(52);


$estcod = $_SESSION['usuario_login'];
//Funci�n que nos retorna S o N dependiendo si el estudiante ya tiene preinscripci�n.
$cod_consul= "SELECT ";
$cod_consul.= "mntac.fua_realizo_preins($estcod) ";
//Ejecuta la funci�n

	//verifica si el estudiante se encuentra en mora de acuerdo a listado de Tesorería
        $conexion2=new multiConexion();
        $accesoSGA=$conexion2->estableceConexion(999);
        $conexion3=new multiConexion();
        $accesoOracle=$conexion3->estableceConexion(52);
        $registroDeudor = consultarDeudor($configuracion, $conexion2,$accesoSGA);
        if(is_array($registroDeudor)){
    
            $registroEstudiante=consultarEstudiante($configuracion, $conexion3,$accesoOracle);
            if($registroEstudiante[0][3]=='POSGRADO' || $registroEstudiante[0][3]=='DOCTORADO' || $registroEstudiante[0][3]=='MAESTRIA'){
                $mensajeT='Señor(a) Estudiante, \n\nDe acuerdo con nuestros registros contables,  Usted presenta saldos en mora con el pago de alguna(s) de las cuotas de matrículas diferidas, por lo tanto se solicita realizar el pago de acuerdo con el compromiso adquirido con la Universidad.  Recordamos que los saldos en mora serán reportados a la Oficina Asesora Jurídica, para su respectivo cobro. \n\nCualquier aclaración comunicarse al tel. 3239300 Ext. 1750.\n' ;
            }else{
                $mensajeT='Señor(a) Estudiante, \n\nDe acuerdo con nuestros registros contables,  Usted presenta saldos en mora con el pago de alguna(s) de las cuotas de matrículas diferidas, por lo tanto se solicita realizar el pago de acuerdo con el compromiso adquirido con la Universidad.  \n\nCualquier aclaración comunicarse al tel. 3239300 Ext. 1750.\n' ;
            }
            foreach ($registroDeudor as $deudor) {
                $mensajeT .= " * Año: ".$deudor[0]." Período: ".$deudor[1]." Valor: ".number_format($deudor[8], 0);
            }
            $mensajeT .= " * ";
            echo '<script type="text/javascript">
                    var mensaje = "'.$mensajeT.'";
                    alert(mensaje);
                </script>';
        }

$variable=(isset($variable)?$variable:'');
if((isset($result)?$result:'')=='N'){
	if($Nro == 1){
	$pmenu='est_pag_menu_uno.php';
	$pagpal = '../generales/cambiar_mi_clave.php';
	}
	elseif($Dia == 30){
	$pmenu='est_pag_menu_uno.php';
	$pagpal = '../generales/cambiar_mi_clave.php';
	}
	elseif($Mod == ""){
	$pmenu='est_pag_menu_uno.php';
	$pagpal = '../generales/cambiar_mi_clave.php';
	}
	else{
	$pmenu = 'est_pag_menu_preins.php';
	$pagpal = 'est_pag_principal.php';//aqui debo colocar la p�gina
	}
}
else{
	//echo "(".$Nro."-".$Dia."-".$Mod.")";
	if($Nro == 1){
	    $pmenu='est_pag_menu_uno.php';
	    $pagpal = '../generales/cambiar_mi_clave.php';
	    echo "1";
	}
	elseif($Dia == 30){
	    $pmenu='est_pag_menu_uno.php';
	    $pagpal = '../generales/cambiar_mi_clave.php';
	    echo "2";	    
	}
	elseif($Mod == ""){
	    $pmenu='est_pag_menu_uno.php';
	    $pagpal = '../generales/cambiar_mi_clave.php';
	    echo "3";	    
	}
	else{
		 $pmenu = 'est_pag_menu.php';
		if($configuracion['activar_caduca_pwd']=='S')
		  {   include_once("../clase/crypto/Encriptador.class.php");
		      $miCodificador=Encriptador::singleton();
		      $usuario = $_SESSION['usuario_login'];
		      $identificacion = $_SESSION['usuario_login'];
		      $indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
		      $tokenCondor = "condorSara2013!";
		      $tipo=52;
		      $tokenCondor = $miCodificador->codificar($tokenCondor);
		      $opcion="temasys=";
		      $variable.="gestionPassword&pagina=validarActualizacion";                                                        
		      $variable.="&usuario=".$usuario;
		      $variable.="&tipo=".$tipo;
		      $variable.="&token=".$tokenCondor;
		      $variable.="&opcionPagina=validaActualizacion";
		      //$variable=$cripto->codificar_url($variable,$configuracion);
		      $variable=$miCodificador->codificar($variable);
		      $enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;
		      $pagpal=$indiceSaraPassword.$opcion.$variable;
		     }	
		else {  require_once("../clase/encriptar.class.php");
			$cripto=new encriptar();
			//$indice="https://condor.udistrital.edu.co/weboffice/index.php?";
			$indice=$configuracion["host"]."/weboffice/index.php?";
			$variable="pagina=admin_datos_basicos";
			$variable.="&usuario=".$_SESSION['usuario_login'];
			//$variable.="&action=loginCondor";
			//$variable.="&tipoUser=".$_SESSION['usuario_login'];
			//$variable.="&modulo=ActualizaDatos";
			$variable.="&tiempo=".$_SESSION['usuario_login'];
			$variable=$cripto->codificar_url($variable,$configuracion);
		        $pagpal = $indice.$variable;//aqui debo colocar la p�gina*/
 		     }
	}
}

function consultarDeudor($configuracion,$conexion2,$accesoSGA){
        $cadena_sql="SELECT ";
        $cadena_sql.=" deu_anio,";
        $cadena_sql.=" deu_per,";
        $cadena_sql.=" deu_nro_iden,";
        $cadena_sql.=" deu_nombre_deudor,";
        $cadena_sql.=" deu_codigo,";
        $cadena_sql.=" deu_proyecto,";
        $cadena_sql.=" deu_cuota,";
        $cadena_sql.=" deu_fecha_ordinaria,";
        $cadena_sql.=" deu_valor,";
        $cadena_sql.=" deu_estado";
        $cadena_sql.=" FROM  sga_deudores_mat";
        $cadena_sql.=" WHERE "; 
        $cadena_sql.=" deu_codigo='".$_SESSION['usuario_login']."'";
        $cadena_sql.=" AND deu_estado=1";
			
        $resultado = $conexion2->ejecutarSQL($configuracion, $accesoSGA, $cadena_sql, "busqueda");
	
	return $resultado;
        
    }
			
    function consultarEstudiante($configuracion,$conexion2,$accesoOracle){
        $cadena_sql="SELECT ";
        $cadena_sql.=" est_cod,";
        $cadena_sql.=" est_nombre,";
        $cadena_sql.=" est_cra_cod,";
        $cadena_sql.=" trim(tra_nivel) NIVEL";
        $cadena_sql.=" FROM  acest";
        $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
        $cadena_sql.=" LEFT OUTER JOIN actipcra ON cra_tip_cra=tra_cod";
        $cadena_sql.=" WHERE "; 
        $cadena_sql.=" est_cod='".$_SESSION['usuario_login']."'";

        $resultado = $conexion2->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");
	
	return $resultado;
        
    }
			

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Estudiantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>


<!--div style='position:absolute; width:1024px; height:630px'></div-->



<frameset class='cuerpoPrincipal' cols='*,90%,*' FRAMEBORDER='0' BORDER='0' FRAMESPACING='0'  NORESIZE >

	 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>

		<frameset class='cuerpoPrincipal' rows='100%,*' FRAMEBORDER='0' BORDER='0' FRAMESPACING='0'  NORESIZE>

			 	 <frameset  rows="101,*,66"  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 NORESIZE  >
			
						<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="superior" src="../marcos/superior.php" NORESIZE>
			
					      

						<frameset cols="201,822" FRAMEBORDER='0' BORDER='0' FRAMESPACING='0' NORESIZE >
							<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="menu" target="_self" src="<? print $pmenu; ?>" NORESIZE >
							<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="principal" target="_self" src="<? print $pagpal; ?>" NORESIZE >
						</frameset>
					     
			

						<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="inferior" src="../marcos/inferior.php" NORESIZE>

				</frameset>

			 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>
		</frameset>
			
	 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>


</frameset>


<noframes><body>
</body></noframes>
</html>


 

