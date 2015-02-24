<?php
// -----------------------------------------------------------------------\\
/*
1	M�dulo:			Evaluaci�n Docente periodo 2007-3
2	Nombre:			formato.php
3	Descripci�n:	Composici�n y despliegue del formato para el registro de las evaluaciones 
					y autoevaluaciones de los estudiantes, Proyectos Curriculares, decanos y docentes
					Tipo de usuario 51,4,16 y 30
4	Tipo:			Script PHP
5	Acceso a objetos: Tablas de la instancia SUDD en modo consulta: 
					  acpregevalua, acencabevalua
6	Aplicaci�n:		Sistema CONDOR
7	Ruta:			../evo73
8	Ambiente:		Producci�n AIX 5.3 / OAS 10g R. 10.1.2
9	Fecha en producci�n:	12 de octubre de 2007
10	Elaborado por:			Carlos E. Rodr�guez J.
11	Revisi�n No.:	01
12	Revisi�n No.:	02 	12 de marzo de 2008
3	Actualización	24 de marzo de 2011
14 	Author	Jesús Neira Guio 
				formato de $sql_pre validado.
*/
// -----------------------------------------------------------------------\\
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
//		require_once('funerr.php');	
//		require_once('funev2.php');
require_once('funev.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once("vartextosfijos.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');

fu_tipo_user($_SESSION["usuario_nivel"]);
?>

<html>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>

<? 
	echo $headuserpag;
	$sec = @$_REQUEST["sec"];
	$fmtonum = $_SESSION["fmto".$sec];
	$docente = $_SESSION["docente".$sec];
	$vin = $_SESSION["usuario_nivel"];
	$nomdoc = $_SESSION["nomdoc".$sec];
	$cra = $_SESSION["cra".$sec];
	if ($fmtonum == 7)
	{
	
		$cod_est = $_SESSION["codigo".$sec];
		$cod_asi = $_SESSION["asig".$sec];
		$gru = $_SESSION["grupo".$sec];
		$docente = $_SESSION["docente".$sec];
  		$nom_asi = $_SESSION["nombre".$sec]; 
		
	}
	else if ($fmtonum == 6 || $fmtonum == 10 || $fmtonum == 12 || $fmtonum == 13 )
	{
		$nomtvi = $_SESSION["nomtvi".$sec];
		$cra = $_SESSION["cra".$sec]; 
		$cranom = $_SESSION["cranom".$sec];
		$tipovin = $_SESSION["tipovin".$sec];
	}
	else if ($fmtonum == 16)
	{
		$nomtvi = $_SESSION["nomtvi".$sec];
		$cra = $_SESSION["crac".$sec]; 
		$cranom = $_SESSION["cranomc".$sec];
		$tipovin = $_SESSION["tipovin".$sec];
	}
	else if ($fmtonum == 8 || $fmtonum == 9 || $fmtonum == 11 || $fmtonum == 14)
	{
		$nomtvi = $_SESSION["nomtvi".$sec];
		$cra = $_SESSION["cra".$sec];
		$coord = $_SESSION["coordinacion".$sec];
		$cranom = $_SESSION["cranom".$sec];
		$vinsel = $_SESSION["vinsel".$sec];
		$craactual = $_SESSION["craactual"];
		if ($vin==16)
		{
			$nomvinselactual = $_SESSION["vinselactual".$sec];
			
		}
		else if ($vin==4)
		{
			$nomvinselactual = $_SESSION["nomvinselactual"];
		}
		$tipovin = $_SESSION["tipovin".$sec];
	}
	else if($fmtonum == 17){
		$nomtvi = $_SESSION["nomtvi".$sec];
		$fac = $_SESSION["fac".$sec];
		$docid=$_SESSION["docente".$sec];
		$doccat = $_SESSION["nomdoc".$sec];
		$cranom = $_SESSION["cranom".$sec];
		$vinsel = $_SESSION["vinsel".$sec];
		$craactual = $_SESSION["craactual"];
		
		if ($vin==16)
		{
			$nomvinselactual = $_SESSION["vinselactual".$sec];
			
		}
		else if ($vin==4)
		{
			$nomvinselactual = $_SESSION["nomvinselactual"];
		}
		$tipovin = $_SESSION["tipovin".$sec];
	}
	else if ($fmtonum == 15)
	{
	
		$cod_est = $_SESSION["codigo".$sec];
		$cod_asi = $_SESSION["asig".$sec];
		$gru = $_SESSION["grupo".$sec];
		$docente = $_SESSION["docente".$sec];
  		$nom_asi = $_SESSION["nombre".$sec]; 
		
	}
	else
	{
		$nomtvi = @$_REQUEST["tvi"];
		$cra = $_SESSION["cra".$sec]; //$cra = @$_GET["cra"];
	}
	
?>
<!--body  onclick="JavaScript:activar_grabar(<? //echo $fmtonum ?>)"-->
<?
//----------------------------------------------------------------------
	switch ($fmtonum) {
		case 6:
			$numpre = 14;		break;
		case 7:
			$numpre = 13;		break;
		case 8:
			$numpre = 3;		break;
		case 9:
			$numpre = 7;		break;
		case 10:
			$numpre = 23;		break;
		case 11:
			$numpre = 10;		break;
		case 12:
			$numpre = 17;		break;
		case 13:
			$numpre = 16;		break;
		case 14:
			$numpre = 6;		break;
		case 15:
			$numpre = 12;		break;
		case 16:
			$numpre = 12;		break;
		case 17:
			$numpre = 9;		break;
	}
//------------------------------------------


function funpreguntas($fmto, $numpre)
{
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	include_once("../clase/multiConexion.class.php");
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
	
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('evaldocente');
	
	$cadena_sql="SELECT ";
	$cadena_sql.="ape_ano, ape_per ";
	$cadena_sql.="FROM ";
	$cadena_sql.="acasperi ";
	$cadena_sql.="WHERE ";
	$cadena_sql.="ape_estado='P'";
	$rsdoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
	
	$evanio = $rsdoc[0][0];
	$evaper  = $rsdoc[0][1];
	
	$pag = 2;
	global $oci_cn;
        //consulta las preguntas de la evaluacion
 	$sql_pre = "SELECT pre_ano AS a, pre_per AS per, pre_formulario AS fmto, 
		pre_pregunta AS pregnum, pre_tipo_preg AS tipo,pre_aplica AS sn,
		pre_texto AS pre_txt,pre_estado AS estado, enc_encabeza AS encab, enc_preg_ini AS ini,
		pre_tipo, pre_requerido, pre_num_opciones,pre_val_opcion,pre_info_texto
		FROM autoevaluadoc.acpregevalua, autoevaluadoc.acencabevalua
		WHERE pre_ano = ".$evanio." AND pre_per = ".$evaper." AND pre_formulario = ".$fmto." AND pre_estado = 'A' AND
		enc_ano = pre_ano AND enc_per = pre_per AND pre_formulario = enc_formulario AND
		pre_pregunta > enc_preg_ini-1 AND pre_pregunta < enc_preg_fin+1
		ORDER BY pre_pregunta";
 	
	$rs_pre = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_pre,"busqueda");
	if (isset($rs_pre[0][0]))
	{
		//Incluir carga del docente seg�n carrera;
		if (!is_array($rs_pre))
		{
			echo "Sin registros en el formato ".$fmto." y numpreguntas ".$numpre." requerido";
		}
		for ($k=0; $k<=$numpre; $k++)
		{
			$pre[1][$k] = $rs_pre[$k][6];		 // texto
			$pre[2][$k] = $rs_pre[$k][8];		//cstr(OCIResult($rs_pre,9)); // encabezado
			if ($rs_pre[$k][3] == $rs_pre[$k][9])	//cint(OCIResult($rs_pre,10))
			{ 
				$pre[3][$k] = 0;
			}
			else
			{
				$pre[3][$k] = 1; //$rs_pre["pregnum"];
			}
			$pre[4][$k] = $rs_pre[$k][3]; //cint(OCIResult($rs_pre,4));//Preservar el n�mero de pregunta de la tabla
			$pre[5][$k] = $rs_pre[$k][5];
			$pre[6][$k] = $rs_pre[$k][10]; // pre_tipo
			$pre[7][$k] = $rs_pre[$k][11]; // pre_requerido
			$pre[8][$k] = $rs_pre[$k][12]; // pre_num_opciones
			$pre[9][$k] = $rs_pre[$k][13]; // pre_val_opcion
			$pre[10][$k] = $rs_pre[$k][14]; // pre_info_texto
		
		}
			//ocifreestatement($rs_pre);
			$_SESSION["pre"] = $pre;
	}
}
	//----------------------------------------------------------------------
			funpreguntas($fmtonum, $numpre);
	//------------------------------------------?>
<? 
function verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,$tipo)
{
	global $coord;
	global $craactual;
	global $sec;
	global $nomvinselactual;
	global $vin;
	$cpc = "";
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	include_once("../clase/multiConexion.class.php");
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
	
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('evaldocente');
	//$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
	if (isset($rsrep[0][0]))
	{
		$evaluado = $rsrep[0][0];
		if ($evaluado > 0)
		{
			$sexo = qygenero($oci_cn,$docente);	
			if ($sexo == "F")
		{
				$ao = "a";
				$aodel = "de la";
		}
		else
		{
			$ao = "";$aodel = "del";
		}
			?> <br /><br /><br /><br /><br /><div align="center"><font color="#993366"> *** <br />
			<?
			if ($tipo == "auto")
			{
				$cpc = "otro Proyecto Curricular ";?>
				Se&ntilde;or<? echo $ao ?> docente <? echo $nomdoc ?>:<br />Usted ya registr&oacute; la autoevaluaci&oacute;n 
				correspondiente a su vinculaci&oacute;n como <? echo $nomtvi ?> con el Proyecto Curricular
				<? echo $cranom;
				EXIT;	 
				?>.
			<?
			}
			else
			{
				$cpc = "otro docente, ";
				if ($vin == 4 )
				{	
					$cpc = "otro docente, otra vinculaci&oacute;n u otro Proyecto Curricular,";?>
					Se&ntilde;or Coordinador:<br />
					La evaluaci&oacute;n de <? echo $nomdoc ?> por el Consejo de Proyecto Curricular  
					<? echo $craactual ?>, en su vinculaci&oacute;n como docente de <? echo $nomvinselactual ?>,
					ya fue registrada en el sistema.<?
					EXIT;
				}
				else if ($vin ==16)
				{
					$craactual = $_SESSION["craactual".$sec];
					$cpc = "otro docente, ";?>
					Se&ntilde;or Decano:<br />
					La evaluaci&oacute;n del Decano a <? echo $nomdoc ?>, docente de <? echo $craactual ?>, en su vinculaci&oacute;n <? echo $nomvinselactual ?>,
					ya fue registrada en el sistema.<?
					EXIT;
				}
			 ;
			 }
			 ?>
			
			<br />***</div><br /><br /><br /><br /><br /></font>
			<font color="#3336699"></font>
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
			<? 
			$fmtonum = 0;//???
			//$repite = true;
		}
	}
	return $evaluado;
}
		/**** ------------ Docente que ya se autoeval�o -------------*/
function concat_sql($cpocra,$nomtabla,$nomdociden,$docente,$cra,$evper,$evano)
{
	global $evanio; global $evaper;
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	include_once("../clase/multiConexion.class.php");
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
	
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('evaldocente');
	
	$sql="SELECT COUNT('".$cpocra."') AS cta ";
	$sql.="FROM ";
	$sql.="$nomtabla ";
	$sql.="WHERE ";
	$sql.="$nomdociden= '".$docente."' ";
	$sql.="AND ";
	$sql.="$cpocra ='".$cra."' ";
	$sql.="AND ";
	$sql.="$evano ='".$evanio."' ";
	$sql.="AND ";
	$sql.="$evper = '".$evaper."'";
	return $sql;
}
function qygenero($oci_cn,$docente)
{
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	include_once("../clase/multiConexion.class.php");
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
	
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('evaldocente');
	
	$sql_doc = "Select doc_sexo from acdocente where doc_nro_iden = '".$docente."'";
	$rsdoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_doc,"busqueda");
	if (is_array($rsdoc))
	{
		$sexo = $rsdoc[0][0];
	}
	return $sexo;
}
?>	
<form name="evaluar" method=post action="fungrab.php">
<?
//echo "Creando form ";
	
	switch ($fmtonum) {
		case 6:	//AUTOEVALUACION  DOCENTES  DE VINCULACIÓN ESPECIAL (Hora Cátedra)
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Respetado (a) docente:</b> Para poder realizar la autoevaluaci&oacute;n, es necesario digitar las notas de todos los estudiantes inscritos en cada una de sus asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epa_cra_cod","autoevaluadoc.ACEVAPROAUT_06","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
				
				
				
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,10,6,12,3,13,0,0,0,0);
					echo "<p align='justify'><b>Respetado (a) profesor (a):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					$fmtonum = 0; // ???
				}
			}
			
			break; 
		case 7:		//EVALUACION DE DOCENTES  POR ESTUDIANTES
				//Verifica si el docente ya fué evaluado
				$sql_repite_ev_e="SELECT COUNT(epe_cur_asi_cod) AS cta ";
				$sql_repite_ev_e.="FROM ";
				$sql_repite_ev_e.="autoevaluadoc.ACEVAPROEST ";
				$sql_repite_ev_e.="WHERE ";
				$sql_repite_ev_e.="epe_est_cod = '".$cod_est."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_cur_asi_cod = '".$cod_asi."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_cur_nro = '".$gru."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_doc_nro_iden = '".$docente."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_ape_ano = '".$evanio."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_ape_per = '".$evaper."'";
				$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_repite_ev_e,"busqueda");
				if (isset($rs[0][0]))
				{
					//Verifica que los docentes que hayan digitado almenos una nota, de lo contrario no se pueda evaluar.
					/*$sql_notas="SELECT ";
					$sql_notas.="ins_nota_par1 || ins_nota_par2 || ins_nota_par3 || ins_nota_par4 || ins_nota_par5 || DECODE(tra_nivel,'PREGRADO',NULL,10) ";
					$sql_notas.="FROM ";
					$sql_notas.="actipcra, accra, acins ";
					$sql_notas.="WHERE ";
					$sql_notas.="tra_cod = cra_tip_cra ";
					$sql_notas.="AND ";
					$sql_notas.="cra_cod = ins_cra_cod ";
					$sql_notas.="AND ";
					$sql_notas.="ins_est_cod='".$cod_est."' ";
					$sql_notas.="AND ";
					$sql_notas.="ins_asi_cod='".$cod_asi."' ";
					$sql_notas.="AND ";
					$sql_notas.="ins_gr='".$gru."'";
					
					$rs_notas = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_notas,"busqueda");
					if(($rs_notas[0][0]==''))
					{
						$sexo = qygenero($oci_cn,$docente);
							$el =""; $ao = "";
							if ($sexo == "F")
							{
								$el = "La ";
								$ao = "a";
							}
							else
							{
								$el = "El "; $ao = "o";
							}
						?> <br /><br /><br /><br /><br /><div align="center"><?
						echo $el?>docente <? echo UTF8_DECODE($nomdoc) ?> <br />de la asignatura <? echo $nom_asi ?><br />
						<b>NO ha digitado al menos una nota</b>.<br />Por lo tanto no lo puede evaluar.
						<br /> Por favor contacte con el Coordinador de su Proyecto Curricular. 
						</div><br /><br /><br /><br /><br />
						Seleccine otra asignatura pendientes por evaluar
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<? 
						EXIT;
					}
					else
					{*///Descomentarias para validar q el docente haya digitado al menos una nota.
						$evaluado = $rs[0][0];
						if ($evaluado > 0)
						{
							$sexo = qygenero($oci_cn,$docente);
							$el =""; $ao = "";
							if ($sexo == "F")
							{
								$el = "La ";
								$ao = "a";
							}
							else
							{
								$el = "El "; $ao = "o";
							}
							?> <br /><br /><br /><br /><br /><div align="center"><font color="#993366"> *** <br /><?
							echo $el?>docente <? echo htmlentities($nomdoc) ?> <br />de la asignatura <? echo $nom_asi ?><br />
							ya fue evaluad<? echo $ao ?>  por Usted 
							<br />***</div><br /><br /><br /><br /><br /></font>
							Contin&uacute;e seleccionando las asignaturas pendientes por evaluar
							<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
							<? 
							$fmtonum = 0;
							EXIT;
						}
						else
						{
							blq ($docente,$cra,$vin,$sec,3,3,9,6,11,3,12,0,0,0,0);
							echo "<p align='justify'><b>Respetado (a) estudiante:</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
							//blq ($docente,$cra,$vin,$sec,1,3,10,0,0,0,0,0,0,0,0);
						}		
					//}Descomentarias para validar q el docente haya digitado al menos una nota.
					
				}
				break;
		case 8:		//EVALUACION  DE DOCENTES DE VINCULACIÓN ESPECIAL POR EL CONSEJO DE PROYECTO CURRICULAR (Hora Cátedra)
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Para poder realizar la evaluaci&oacute;n por el consejo del Proyecto Curricular, es necesario que el (la) docente $nomdoc digite las notas de todos los estudiantes inscritos en cada una de las asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epc_cra_cod","autoevaluadoc.ACEVAPROCPC_08","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,2,6,2,0,0,0,0,0,0);
					//blq ($docente,$cra,$vin,$sec,3,3,,6,21,3,23,0,0,0,0);
					echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					$fmtonum = 0;
				}
			}
			break;
		case 9:		//EVALUACION  DE DOCENTES DE VINCULACIÓN ESPECIAL POR EL CONSEJO DE PROYECTO CURRICULAR (Tiempo Completo Ocasional)

			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Para poder realizar la evaluaci&oacute;n por el consejo del Proyecto Curricular, es necesario que el (la) docente $nomdoc digite las notas de todos los estudiantes inscritos en cada una de las asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epc_cra_cod","autoevaluadoc.ACEVAPROCPC_09","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,6,6,7,0,0);
					//blq ($docente,$cra,$vin,$sec,3,3,,6,21,3,23,0,0,0,0);
					echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					$fmtonum = 0; // ???
				}
			}
			break; 
		case 10:	//AUTOEVALUACION  DOCENTES  DE  PLANTA (T.C.)
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Respetado (a) docente:</b> Para poder realizar la autoevaluaci&oacute;n, es necesario digitar las notas de todos los estudiantes inscritos en cada una de sus asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epa_cra_cod","autoevaluadoc.ACEVAPROAUT_10","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,19,6,21,3,22,0,0,0,0);
					echo "<p align='justify'><b>Respetado (a) profesor (a):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{ 
					$fmtonum = 0; // ???
				}
			}
			break; 
		case 11:	//EVALUACION  DE DOCENTES DE PLANTA (T.C.) POR EL CONSEJO DE  PROYECTO CURRICULAR
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Para poder realizar la evaluaci&oacute;n por el consejo del Proyecto Curricular, es necesario que el (la) docente $nomdoc digite las notas de todos los estudiantes inscritos en cada una de las asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epc_cra_cod","autoevaluadoc.ACEVAPROCPC_11","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,9,6,10,0,0);
					//blq ($docente,$cra,$vin,$sec,5,3,2,3,5,3,6,3,7,3,9,3,9);
					echo "<b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b>  Por favor enuncie las observaciones  que tiene sobre esta evaluaci&oacute;n:</p>
					<textarea id=obs name=obs cols=80 rows=3 onKeyDown='javascript:ctatxt(this.form.obs.value)'
					onKeyUp='javascript:ctatxt(this.form.obs.value)'></textarea><br></p>";
					echo "<b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b>  Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{ 
					$fmtonum = 0; // ???
				}
			}
			break;
		case 12:	//AUTOEVALUACION  DOCENTES  DE VINCULACIÓN ESPECIAL (Tiempo Completo Ocasional)
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Respetado (a) docente:</b> Para poder realizar la autoevaluaci&oacute;n, es necesario digitar las notas de todos los estudiantes inscritos en cada una de sus asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epa_cra_cod","autoevaluadoc.ACEVAPROAUT_12","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,13,6,15,3,16,0,0,0,0);
					echo "<p align='justify'><b>Respetado (a) profesor (a):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					$fmtonum = 0; // ???
				}
			}
			break; 
		case 13:	//AUTOEVALUACION  DOCENTES  ( M.T. y M.T.O.)
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Respetado (a) docente:</b> Para poder realizar la autoevaluaci&oacute;n, es necesario digitar las notas de todos los estudiantes inscritos en cada una de sus asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epa_cra_cod","autoevaluadoc.ACEVAPROAUT_13","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,12,6,14,3,15,0,0,0,0);
					echo "<p align='justify'><b>Respetado (a) profesor (a):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					$fmtonum = 0; // ???
				}
			}
			break; 
		case 14:	//EVALUACION  DE DOCENTES ( M.T y M.T.O )  POR EL CONSEJO DE PROYECTO CURRICULAR
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Para poder realizar la evaluaci&oacute;n por el consejo del Proyecto Curricular, es necesario que el (la) docente $nomdoc digite las notas de todos los estudiantes inscritos en cada una de las asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = concat_sql("epc_cra_cod","autoevaluadoc.ACEVAPROCPC_14","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,5,6,7,0,0);
					//blq ($docente,$cra,$vin,$sec,3,3,,6,21,3,23,0,0,0,0);
					echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					$fmtonum = 0; // ???
				}
			}
			break;
		case 15:		//EVALUACION DE DOCENTES  POR ESTUDIANTES
				//Verifica si el docente ya fué evaluado
				$sql_repite_ev_e="SELECT COUNT(epe_cur_asi_cod) AS cta ";
				$sql_repite_ev_e.="FROM ";
				$sql_repite_ev_e.="autoevaluadoc.ACEVAPROEST_15 ";
				$sql_repite_ev_e.="WHERE ";
				$sql_repite_ev_e.="epe_est_cod = '".$cod_est."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_cur_asi_cod = '".$cod_asi."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_cur_nro = '".$gru."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_doc_nro_iden = '".$docente."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_ape_ano = '".$evanio."' ";
				$sql_repite_ev_e.="AND ";
				$sql_repite_ev_e.="epe_ape_per = '".$evaper."'";
				$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_repite_ev_e,"busqueda");
				if (isset($rs[0][0]))
				{
					//Verifica que los docentes que hayan digitado almenos una nota, de lo contrario no se pueda evaluar.
					/*$sql_notas="SELECT ";
					$sql_notas.="ins_nota_par1 || ins_nota_par2 || ins_nota_par3 || ins_nota_par4 || ins_nota_par5 || DECODE(tra_nivel,'PREGRADO',NULL,10) ";
					$sql_notas.="FROM ";
					$sql_notas.="actipcra, accra, acins ";
					$sql_notas.="WHERE ";
					$sql_notas.="tra_cod = cra_tip_cra ";
					$sql_notas.="AND ";
					$sql_notas.="cra_cod = ins_cra_cod ";
					$sql_notas.="AND ";
					$sql_notas.="ins_est_cod='".$cod_est."' ";
					$sql_notas.="AND ";
					$sql_notas.="ins_asi_cod='".$cod_asi."' ";
					$sql_notas.="AND ";
					$sql_notas.="ins_gr='".$gru."'";
					
					$rs_notas = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_notas,"busqueda");
					if(($rs_notas[0][0]==''))
					{
						$sexo = qygenero($oci_cn,$docente);
							$el =""; $ao = "";
							if ($sexo == "F")
							{
								$el = "La ";
								$ao = "a";
							}
							else
							{
								$el = "El "; $ao = "o";
							}
						?> <br /><br /><br /><br /><br /><div align="center"><?
						echo $el?>docente <? echo UTF8_DECODE($nomdoc) ?> <br />de la c&aacute;tedra <? echo $nom_asi ?><br />
						<b>NO ha digitado al menos una nota</b>.<br />Por lo tanto no lo puede evaluar.
						<br /> Por favor contacte con el Coordinador de su Proyecto Curricular. 
						</div><br /><br /><br /><br /><br />
						Seleccine otra asignatura pendientes por evaluar
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<?EXIT; 
					}
					else
					{*/
						$evaluado = $rs[0][0];
						if ($evaluado > 0)
						{
							$sexo = qygenero($oci_cn,$docente);
							$el =""; $ao = "";
							if ($sexo == "F")
							{
								$el = "La ";
								$ao = "a";
							}
							else
							{
								$el = "El "; $ao = "o";
							}
							?> <br /><br /><br /><br /><br /><div align="center"><font color="#993366"> *** <br /><?
							echo $el?>docente <? echo UTF8_DECODE($nomdoc) ?> <br />de la c&aacute;tedra <? echo $nom_asi ?><br />
							ya fue evaluado<? echo $ao ?>  por Usted 
							<br />***</div><br /><br /><br /><br /><br /></font>
							Contin&uacute;e seleccionando las asignaturas pendientes por evaluar
							<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
							<? 
							$fmtonum = 0;
							EXIT;
						}
						else
						{
							blq ($docente,$cra,$vin,$sec,3,3,10,6,12,0,0,0,0,0,0);
							echo "<p align='justify'><b>Respetado (a) estudiante:</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
							//blq ($docente,$cra,$vin,$sec,1,3,10,0,0,0,0,0,0,0,0);
						}		
					//}
					
				}
			break;
			
			case 16:	//AUTOEVALUACION  DOCENTES  CATEDRAS INSTITUCIONALES
			$cra = $_SESSION["cra".$sec];
			$sql_digito='SELECT fua_digito_notas ('.$docente.','.$cra.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			/*if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Respetado (a) docente:</b> Para poder realizar la autoevaluaci&oacute;n, es necesario digitar las notas de todos los estudiantes inscritos en cada una de sus asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}*/
			//else
			//{
				$sq6 = concat_sql("epa_cra_cod","autoevaluadoc.ACEVAPROAUT_16","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
				//echo 'sq6 = '.$sq6;
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				//$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
				$sql_evaluo="SELECT * "; 
				$sql_evaluo.="FROM autoevaluadoc.acevaproaut_16 ";
				$sql_evaluo.="WHERE epa_ape_ano='".$evanio."' AND epa_ape_per='".$evaper."' AND epa_doc_nro_iden='".$docente."' AND epa_cra_cod='".$cra."' ";
				$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_evaluo,"busqueda");
				//echo $sql_evaluo."<br>";
				if (!is_array($row_notas))
				{
					blq ($docente,$cra,$vin,$sec,3,3,11,6,12,0,0,0,0,0,0);
					echo "<p align='justify'><b>Respetado (a) profesor (a):</b> Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{
					echo "Se&ntilde;or Docente, usted ya registr&oacute; la autoevaluaci&oacute;n de la c&aacute;tedra.";
					EXIT;	 
				}
			//}
			break;
			case 17:	//EVALUACION  DE DOCENTES DE PLANTA (T.C.) POR EL CONSEJO DE  PROYECTO CURRICULAR
			$sql_digito='SELECT fua_digito_notas ('.$doccid.','.$fac.') from DUAL';
			$row_notas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_digito,"busqueda");
			if($row_notas[0][0]=='N')
			{
				echo "<p align='justify'><b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b> Para poder realizar la evaluaci&oacute;n por el consejo del Proyecto Curricular, es necesario que el (la) docente $nomdoc digite las notas de todos los estudiantes inscritos en cada una de las asignaturas. Los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0.</p>";
				EXIT;
			}
			else
			{
				$sq6 = "SELECT COUNT('epc_cra_cod') AS cta 
					FROM autoevaluadoc.ACEVAPROCPC_17 
					WHERE epc_doc_nro_iden= '".$docid."' 
					AND epc_dep_cod ='".$fac."' 
					AND epc_ape_ano ='".$evanio."' 
					AND epc_ape_per = '".$evaper."' ";
				$rsrep = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq6,"busqueda");
				$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docid,$nomdoc,$nomtvi,$cranom,"cpc");
				if ($verif_repite == 0)
				{
					blq ($docente,$cra,$vin,$sec,3,3,8,6,10,0,0);
					//blq ($docente,$cra,$vin,$sec,5,3,2,3,5,3,6,3,7,3,9,3,9);
					echo "<b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b>  Le agradecemos su valiosa y oportuna colaboraci&oacute;n la cual redundar&aacute; en el mejoramiento de la gesti&oacute;n docente en nuestra universidad.</p>";
				}
				else
				{ 
					$fmtonum = 0; // ???
				}
			}
			break;
		} 
				//blq1(Id del docente; cra_cod; estudiante o docente o coordinador
				//sec para extraer Encabezado, No. bloques, hasta la pregunta x1 del 1er bloque, 
				//hasta la pregunta x2 del 2do bloque,... )
				//M�ximo 5 bloques
	
?>
	
	<INPUT type="hidden" id=text_sec name=text_sec size=12 value=<? echo $sec?>>
	<INPUT type="hidden" id=text_fmto name=text_fmto size=12 value=<? echo $fmtonum?>>
	<INPUT type="hidden" id=text_doc name=text_doc size=15 value=<? echo $docente?>>
	<INPUT type="hidden" id=text_tipovin name=text_tipovin size=15 value=<? echo $tipovin?>>
	
	<input type="button" value="Grabar" title="Grabar" onclick="validar_form(document.evaluar)">
<P>&nbsp;</P>
</form>
	<INPUT type="hidden" value =<?echo $fmtonum?> name="boton_grabar" id="b">
</BODY>
</HTML>
