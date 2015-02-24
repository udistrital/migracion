<?php
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
		require_once('funerr.php');	
		require_once('funev2.php');
		require_once('funev.php');

require_once(dir_eval.'conexion_ev06.php');

require_once(dir_conect.'fu_tipo_user.php');
require_once("vartextosfijos.php");

require_once(dir_script.'fu_pie_pag.php');

fu_tipo_user($_SESSION["usuario_nivel"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	echo $headuserpag;
	$sec = @$_GET["sec"];
	$fmtonum = $_SESSION["fmto".$sec];
	$docente = $_SESSION["docente".$sec];
	$vin = $_SESSION["usuario_nivel"];
	$nomdoc = $_SESSION["nomdoc".$sec];
//echo "sec ".$sec." fmto ".$fmtonum." docente ".$docente." vin ".$vin." nomdocente ".$nomdoc;
	if ($fmtonum == 7) {
	
		$cod_est = $_SESSION["codigo".$sec];
		$cod_asi = $_SESSION["asig".$sec];
		$gru = $_SESSION["grupo".$sec];
		$docente = $_SESSION["docente".$sec];
  		$nom_asi = $_SESSION["nombre".$sec]; 
		
	}
	else if ($fmtonum == 6 || $fmtonum == 10 || $fmtonum == 12 || $fmtonum == 13) {
		$nomtvi = $_SESSION["nomtvi".$sec];
		$cra = $_SESSION["cra".$sec]; 
		$cranom = $_SESSION["cranom".$sec];
		$tipovin = $_SESSION["tipovin".$sec];
	}else if ($fmtonum == 8 || $fmtonum == 9 || $fmtonum == 11 || $fmtonum == 14) {
		$nomtvi = $_SESSION["nomtvi".$sec];
		$cra = $_SESSION["cra".$sec];
		$coord = $_SESSION["coordinacion".$sec];
		$cranom = $_SESSION["cranom".$sec];
		$vinsel = $_SESSION["vinsel".$sec];
		$craactual = $_SESSION["craactual"];
        if ($vin==16){
			$nomvinselactual = $_SESSION["vinselactual".$sec];
		}else if ($vin==4){
			$nomvinselactual = $_SESSION["nomvinselactual"];
		}
		$tipovin = $_SESSION["tipovin".$sec];
	}else {
		$nomtvi = @$_GET["tvi"];
		$cra = $_SESSION["cra".$sec]; //$cra = @$_GET["cra"];
	} ?>
<BODY  onclick="JavaScript:activar_grabar(<? echo $fmtonum ?>)" oncontextmenu="return false">
<?
//----------------------------------------------------------------------
	switch ($fmtonum) {
		case 6:
			$numpre = 11;		break;
		case 7:
			$numpre = 11;		break;
		case 8:
			$numpre = 3;		break;
		case 9:
			$numpre = 7;		break;
		case 10:
			$numpre = 20;		break;
		case 11:
			$numpre = 11;		break;
		case 12:
			$numpre = 14;		break;
		case 13:
			$numpre = 13;		break;
		case 14:
			$numpre = 6;		break;
	}
//------------------------------------------


function funpreguntas($fmto, $numpre) {
	$pag = 2;
	global $oci_cn;
	//Se mantienen las preguntas para el periodo 2006-3
	//clave = 2111 // indica el punto preciso donde se generó el error
/*	$sql_pre = "SELECT pre_ano AS a, pre_per AS per, pre_formulario AS fmto, ";
	$sql_pre = $sql_pre." pre_pregunta AS pregnum, pre_tipo_preg AS tipo,pre_aplica AS sn,";
	$sql_pre = $sql_pre." pre_texto AS pre_txt,pre_estado AS estado, enc_encabeza AS encab, enc_preg_ini AS ini";
	$sql_pre = $sql_pre." FROM acpregevalua, acencabevalua";
	$sql_pre = $sql_pre." WHERE pre_ano = 2005 AND pre_per = 3 AND pre_formulario = ".$fmto." AND pre_estado = 'A' AND";
	$sql_pre = $sql_pre." enc_ano = pre_ano AND enc_per = pre_per AND pre_formulario = enc_formulario AND";
	$sql_pre = $sql_pre." pre_pregunta > enc_preg_ini-1 AND pre_pregunta < enc_preg_fin+1";
	$sql_pre = $sql_pre." ORDER BY pre_pregunta";
*/ 
 $sql_pre = "SELECT pre_ano AS a, pre_per AS per, pre_formulario AS fmto, 
			 pre_pregunta AS pregnum, pre_tipo_preg AS tipo,pre_aplica AS sn,
			 pre_texto AS pre_txt,pre_estado AS estado, enc_encabeza AS encab, enc_preg_ini AS ini,
			 pre_tipo, pre_requerido, pre_num_opciones,pre_val_opcion,pre_info_texto
			  FROM acpregevalua, acencabevalua
			  WHERE pre_ano = 2007 AND pre_per = 3 AND pre_formulario = ".$fmto." AND pre_estado = 'A' AND
			 enc_ano = pre_ano AND enc_per = pre_per AND pre_formulario = enc_formulario AND
			 pre_pregunta > enc_preg_ini-1 AND pre_pregunta < enc_preg_fin+1
			  ORDER BY pre_pregunta";
 echo $fmto;
    $rs_pre = ociexe($oci_cn,$sql_pre,333,1);
	
	if ($rs != -1){
		$row2 = OCIFetch($rs_pre);		
			//Incluir carga del docente según carrera;
		if (!row2) {
			echo "Sin registros en el formato ".$fmto." y numpreguntas ".$numpre." requerido";
		}
		   for ($k=1; $k<=$numpre; $k++) {
			   $pre[1][$k] = OCIResult($rs_pre,7); // texto
			   $pre[2][$k] = OCIResult($rs_pre,9);//cstr(OCIResult($rs_pre,9)); // encabezado
			   if (OCIResult($rs_pre,4) == OCIResult($rs_pre,10)) { //cint(OCIResult($rs_pre,10))
					$pre[3][$k] = 0;
			   } else {
					$pre[3][$k] = 1; //$rs_pre["pregnum"];
			   }
			   $pre[4][$k] = OCIResult($rs_pre,4); //cint(OCIResult($rs_pre,4));//Preservar el número de pregunta de la tabla
			   $pre[5][$k] = OCIResult($rs_pre,6);
			   $pre[6][$k] = OCIResult($rs_pre,11); // pre_tipo
			   $pre[7][$k] = OCIResult($rs_pre,12); // pre_requerido
			   $pre[8][$k] = OCIResult($rs_pre,13); // pre_num_opciones
			   $pre[9][$k] = OCIResult($rs_pre,14); // pre_val_opcion
			   $pre[10][$k] = OCIResult($rs_pre,15); // pre_info_texto
			   OCIFetch($rs_pre);
			}
			//ocifreestatement($rs_pre);
			OCIFreeCursor($rs_pre);
			OCILogOff ($oci_cn);
			$_SESSION["pre"] = $pre;
	}
}
	//----------------------------------------------------------------------
			funpreguntas($fmtonum, $numpre);
	//------------------------------------------?>
<? 
function verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,$tipo){
	global $coord;
	global $craactual;
	global $sec;
	global $nomvinselactual;
	global $vin;
	$cpc = "";
	if ($rsrep != -1){
		$row = OCIFetch($rsrep);
		$evaluado = OCIResult($rsrep,1);
		if ($evaluado > 0) {
			$sexo = qygenero($oci_cn,$docente);	
			if ($sexo == "F"){
				$ao = "a";
				$aodel = "de la";
			}else{$ao = "";$aodel = "del";}
			?> <br /><br /><br /><br /><br /><div align="center"><font color="#993366"> *** <br />
			<? if ($tipo == "auto") {
				$cpc = "otro Proyecto Curricular ";?>
				Señor<? echo $ao ?> docente <? echo $nomdoc ?>:<br />Usted ya registr&oacute; la autoevaluaci&oacute;n 
				correspondiente a su vinculaci&oacute;n como <? echo $nomtvi ?> con el Proyecto Curricular
				<? echo $cranom ?>.
			<? }else {$cpc = "otro docente, ";
					if ($vin == 4 ) {
						$cpc = "otro docente, otra vinculación u otro Proyecto Curricular,";?>
						Señor Coordinador:<br />
						La evaluaci&oacute;n de <? echo $nomdoc ?> por el Consejo de Proyecto Curricular  
						<? echo $craactual ?>, en su vinculaci&oacute;n como docente de <? echo $nomvinselactual ?>,
						ya fue registrada en el sistema.<?
					}else if ($vin ==16) {
						$craactual = $_SESSION["craactual".$sec];
						$cpc = "otro docente, ";?>
						Señor Decano:<br />
						La evaluaci&oacute;n del Decano a <? echo $nomdoc ?>, docente coordinador del Proyecto 
						Curricular <? echo $craactual ?>, en su vinculaci&oacute;n <? echo $nomvinselactual ?>,
						ya fue registrada en el sistema.<?
				    }
			   ;} ?>
			
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
		/**** ------------ Docente que ya se autoevalúo -------------*/
function concat_sql($cpocra,$nomtabla,$nomdociden,$docente,$cra,$evper,$evano){
	global $evanio; global $evaper;
	$sql = "SELECT COUNT(".$cpocra.") AS cta FROM ".$nomtabla;
	$sql = $sql." WHERE ".$nomdociden." = ".$docente." AND ".$cpocra." = ".$cra;
	$sql = $sql." AND ".$evano." = ".$evanio." AND ".$evper." = ".$evaper;
	return $sql;
}
function qygenero($oci_cn,$docente){
	$sql_doc = "Select doc_sexo from acdocente where doc_nro_iden = '".$docente."'";
	$rsdoc = ociexe($oci_cn,$sql_doc,336,1);
	if ($rsdoc != -1){
		$rowdoc = OCIFetch($rsdoc);
		$sexo = OCIresult($rsdoc,1);
	}
	return $sexo;
}?>	
	<form name="evaluar" method=post action="fungrab.php" target="iframe_m">	<?
//echo "Creando form ";
	
	switch ($fmtonum) {
		case 6:
			$sq6 = concat_sql("epa_cra_cod","desarrollador1.ACEVAPROAUT_06","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			//echo 'sq6 = '.$sq6;
			$rsrep = ociexe($oci_cn,$sq6,334,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,1,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			
			break; 
		case 7:
				$sql_repite_ev_e = "SELECT COUNT(epe_cur_asi_cod) AS cta FROM desarrollador1.ACEVAPROEST ";
				$sql_repite_ev_e = $sql_repite_ev_e." WHERE epe_est_cod = ".$cod_est ;
				$sql_repite_ev_e = $sql_repite_ev_e." AND epe_cur_asi_cod = ".$cod_asi." AND epe_cur_nro = ".$gru;
				$sql_repite_ev_e = $sql_repite_ev_e." AND epe_doc_nro_iden = ".$docente;
				$sql_repite_ev_e = $sql_repite_ev_e." AND epe_ape_ano = ".$evanio." AND epe_ape_per = ".$evaper;
				$rs = ociexe($oci_cn,$sql_repite_ev_e,335,1);
				if ($rs != -1){
					$row = OCIFetch($rs);
					$evaluado = OCIresult($rs,1);
					if ($evaluado > 0) {
						$sexo = qygenero($oci_cn,$docente);
						$el =""; $ao = "";
						if ($sexo == "F") {
							$el = "La ";
							$ao = "a";
						}else {$el = "El "; $ao = "o";}
						?> <br /><br /><br /><br /><br /><div align="center"><font color="#993366"> *** <br /><?
						echo $el?>docente <? echo $nomdoc ?> <br />de la asignatura <? echo $nom_asi ?><br />
						ya fue evaluad<? echo $ao ?>  por Usted 
						<br />***</div><br /><br /><br /><br /><br /></font>
						Contin&uacute;e seleccionando las asignaturas pendientes por evaluar
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<? 
						$fmtonum = 0;
					}else{
						blq ($docente,$cra,$vin,$sec,3,3,2,7,10,5,$numpre,0,0,0,0);?>
						Observaciones <br>
						<textarea id=r14 name=r14 cols=80 rows=4 onKeyDown='javascript:ctatxt(this.form.r14.value)'
						onKeyUp='javascript:ctatxt(this.form.r14.value)'></textarea><br><?
					}
				}
				break;
		case 8:
			$sq6 = concat_sql("epc_cra_cod","desarrollador1.ACEVAPROCPC_08","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,337,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,3,$numpre,0,0,0,0,0,0,0,0); 		break;
			}else $fmtonum = 0;
			break;
		case 9:
			$sq6 = concat_sql("epc_cra_cod","desarrollador1.ACEVAPROCPC_09","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,338,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,6,$numpre,0,0,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 10:
			$sq6 = concat_sql("epa_cra_cod","desarrollador1.ACEVAPROAUT_10","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,339,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,1,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 11:
			$sq6 = concat_sql("epc_cra_cod","desarrollador1.ACEVAPROCPC_11","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,340,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,6,$numpre,0,0,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 12:
			$sq6 = concat_sql("epa_cra_cod","desarrollador1.ACEVAPROAUT_12","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,341,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,1,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 13:
			$sq6 = concat_sql("epa_cra_cod","desarrollador1.ACEVAPROAUT_13","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,342,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,1,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break;
		case 14:
			$sq6 = concat_sql("epc_cra_cod","desarrollador1.ACEVAPROCPC_14","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,343,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,6,$numpre,0,0,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		} 
				//blq1(Id del docente; cra_cod; estudiante o docente o coordinador
				//sec para extraer Encabezado, No. bloques, hasta la pregunta x1 del 1er bloque, 
				//hasta la pregunta x2 del 2do bloque,... )
				//Máximo 5 bloques	?>
	<INPUT type="hidden" id=text_sec name=text_sec size=12 value=<? echo $sec?>>
	<INPUT type="hidden" id=text_fmto name=text_fmto size=12 value=<? echo $fmtonum?>>
	<INPUT type="hidden" id=text_doc name=text_doc size=15 value=<? echo $docente?>>
	<INPUT type="hidden" id=text_tipovin name=text_tipovin size=15 value=<? echo $tipovin?>>
	
<P>&nbsp;</P>
</form>
	<INPUT type="hidden" value =<? echo $fmtonum?> name="boton_grabar" id="b">
	<br><? fu_pie();?>
</BODY>
</HTML>