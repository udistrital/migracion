<?php
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
require_once("funerr.php"); 

require_once(dir_eval.'conexion_ev06.php');

require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");

fu_tipo_user($_SESSION["usuario_nivel"]);



 ?>
<HTML>
<HEAD>
	<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
	<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
	<link href="../script/estilo.css" rel="stylesheet" type="text/css">
	
	<script language="javascript" >
		function browser(){
				var brow = "";
				if(document.layers){brow="NN4";}    
				if(document.all){brow="ie";}
				if(!document.all && document.getElementById){brow="NN6";}
			return brow;
		} 
		function updtvu2(){
			var sec = parent.fra_registro.document.all.item("vu2sec").value;
			var numf = document.all.item("numfmto").value;
			//alert ("numf " + numf + " vusec " + sec);
			//if (numf == 7 || numf == 6 || numf == 10 || numf == 12 || numf == 13 ){ 
				//-No requerida-------parent.fra_registro.location.href = "vu2_docenteXXX.php?vusec="+sec;
			if(numf == 8 || numf == 9 || numf == 11 || numf == 14 ){ 
				parent.fra_registro.location.href = "vu2_coordinador.php?vusec="+sec;
			}
		}
</script>

</HEAD>

<BODY onLoad="JavaScript:updtvu2()" ><?
   $pag=1;
   //clave = fmto
   $acepro[1] = "ACEVAPROAUT_06";
   $acepro[2] = "ACEVAPROEST";
   $acepro[3] = "ACEVAPROCPC_08";
   $acepro[4] = "ACEVAPROCPC_09";
   $acepro[5] = "ACEVAPROAUT_10";
   $acepro[6] = "ACEVAPROCPC_11";
   $acepro[7] = "ACEVAPROAUT_12";
   $acepro[8] = "ACEVAPROAUT_13";
   $acepro[9] = "ACEVAPROCPC_14";
   $ano = $evanio;
   $per = $evaper;
   $sec= @$_POST["text_sec"];
   $fmto = @$_POST["text_fmto"];			///---- verificar
   $doc = @$_POST["text_doc"];				///---- verificar
   $tipovin = @$_POST["text_tipovin"];				///---- verificar
	$fecha=Date("d/m/Y");
	echo $fecha;
//function leerespuestas($m) {
  //   ???      redim $r[$m+2];
  	global $r;
	global $fmto;
	global $fmtonum;
  //-------------------------------

		
	$pregtot = 14;	//Total de preguntas en la tabla 
			$est_cod= $_SESSION["codigo".$sec];
			$asi_cod = $_SESSION["asig".$sec];
			$gru = $_SESSION["grupo".$sec];
			//leerespuestas($pregtot);
			 //$cra = $_SESSION["cra".$sec]; //@$_POST["text_cra"];
	   		//$valores .= " (EPE_APE_ANO, EPE_APE_PER, EPE_EST_COD, EPE_CUR_ASI_COD, EPE_CUR_NRO, EPE_DOC_NRO_IDEN, ";
   			//concatvalores($pregtot);	
			//$valores .= " EPE_OBSERVA,EPE_ESTADO) VALUES (";
		    //$valores .= $ano.",".$per.",".$est_cod.",".$asi_cod;
		    //$valores .= ",".$gru.",".$doc.",";
			$estado = "A";
		if ($fmto == 7) {
		$valores="INSERT INTO desarrollador1.ACEVAPROEST VALUES (:EPE_APE_ANO, :EPE_APE_PER,:EPE_EST_COD,:EPE_CUR_ASI_COD,:EPE_CUR_NRO, :EPE_DOC_NRO_IDEN,
		:EPE_RES_1,:EPE_RES_2,:EPE_RES_3,:EPE_RES_4,:EPE_RES_5,:EPE_RES_6,:EPE_RES_7,:EPE_RES_8,:EPE_RES_9,
		:EPE_RES_10,:EPE_RES_12,:EPE_RES_13,:EPE_FECHA_REG,:EPE_OBSERVA,:EPE_ESTADO)";
		$id_sentencia = oci_parse($oci_cn, $valores);
		oci_bind_by_name($id_sentencia, ':EPE_APE_ANO', $evanio);
		oci_bind_by_name($id_sentencia, ':EPE_APE_PER', $evaper);
		oci_bind_by_name($id_sentencia, ':EPE_EST_COD', $est_cod);
		oci_bind_by_name($id_sentencia, ':EPE_CUR_ASI_COD', $asi_cod);
		oci_bind_by_name($id_sentencia, ':EPE_CUR_NRO', $gru);
		oci_bind_by_name($id_sentencia, ':EPE_DOC_NRO_IDEN', $doc);
		oci_bind_by_name($id_sentencia, ':EPE_RES_1', $r1);
		oci_bind_by_name($id_sentencia, ':EPE_RES_2', $r2);
		oci_bind_by_name($id_sentencia, ':EPE_RES_3', $r3);
		oci_bind_by_name($id_sentencia, ':EPE_RES_4', $r4);
		oci_bind_by_name($id_sentencia, ':EPE_RES_5', $r5);
		oci_bind_by_name($id_sentencia, ':EPE_RES_6', $r6);
		oci_bind_by_name($id_sentencia, ':EPE_RES_7', $r7);
		oci_bind_by_name($id_sentencia, ':EPE_RES_8', $r8);
		oci_bind_by_name($id_sentencia, ':EPE_RES_9', $r9);
		oci_bind_by_name($id_sentencia, ':EPE_RES_10', $r10);
		oci_bind_by_name($id_sentencia, ':EPE_RES_12', $r12);
		oci_bind_by_name($id_sentencia, ':EPE_RES_13', $r13);
		oci_bind_by_name($id_sentencia, ':EPE_FECHA_REG', $r13);
		oci_bind_by_name($id_sentencia, ':EPE_OBSERVA',$obs);
		oci_bind_by_name($id_sentencia, ':EPE_ESTADO',$estado);
		$r = oci_execute($id_sentencia);
		oci_close($oci_cn);
		}
		
		
		
echo "$evanio,$evaper,$est_cod,$asi_cod,$gru,$doc,$r1,$r2,$r3,$r4,,$r5,$r6,$r7,$r8,$r9,$r10,$fecha,$obs,$r12,$r13,$estado";

 ?>
