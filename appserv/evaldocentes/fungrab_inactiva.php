<?php
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
require_once("funerr.php"); 

require_once(dir_eval.'conexion_ev06.php');

require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");

fu_tipo_user($_SESSION["usuario_nivel"]); // válido para pruebas
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

function leerespuestas($m) {
  //   ???      redim $r[$m+2];
  	global $r;
	global $fmto;
     for ($i=1; $i<=$m; $i++) {
   	   if ($i == $m && $fmto == "7" ) {
		   $r[$i] = @$_POST["r13"]; //La pregunta 11 del formato 7 tiene id="r13"
		   //r(i+1) = left(Request.Form("r14"),255) //Campo observaciones del formato 7 tiene id="r14"
	   } else {
		   $r[$i] = @$_POST["r".$i]; //valida_campo[Request.Form("r"&i)];
	   }     
     }
 } 
//-------------------------------
function concatvalores($n) {
	global $valores;
	global $fmto;
	define("fr", $fmto);	
	for ($i=1; $i<=$n; $i++) {
		if (fr == 7) {
			if ($i == 11 || $i == 12) {
				//excluir preguntas no activas
			} else {
				$valores .= "EPE_RES_".$i.",";
			}
		} elseif (fr == 6 || fr == 10 || fr == 12 || fr == 13) {; 
			$valores .= "EPA_RES_".$i.",";
		} else {
			$valores .= "EPC_RES_".$i.",";
		}
	} 
 }
//-----------------------------------------------
function addrespuestas($pregtot) {
	global $r;
	global $valores;
	global $fmto;
 	$n = $pregtot;
   for ($i=1; $i<=$n; $i++) {
   	   if ($i == $n && $fmto == 7 ) {
		   $valores .= "'".$r[$i]."','".substr(@$_POST["r14"],0,255)."',"; //Incluir observaciones
   	   } elseif (($i == 11 || $i == 12 ) && $fmto == 7)  {
	   		//echo "Preguntas no activas";
	   } elseif ($i < 11 && $fmto == "7") {; //
	   	   if (($r[$i] == 5 || $r[$i] == 4 || $r[$i] == 3 || $r[$i] == 2 || $r[$i] == 1)) {
			   $valores .= $r[$i].",";
		   } else {
		   		$valores .= "'0',"; //No admite valor null
		   }
	   } else {
		   $valores .= $r[$i].",";
	   }     
   }
 } 
 //*--------------------------------------------
 		//------------//$fmto = $text_fmto ; $doc = $text_doc;
//echo "Formato: ".$fmto;		
	$valores = "INSERT INTO desarrollador1.".$acepro[$fmto - 5]; //acepro[1]
   switch ($fmto)	{ 
    	case "6":
			if ($doc == 123) {											//Alimentar preguntas;}
				$a = file("c:\appserv\www\ev_20053\preguntas1.csv");
				$lineas_a = count($a);	//echo $lineas_a;
				foreach ($a as $contenido) {
					///----$p = $p+1;
					$lon_a = strlen($contenido);
							$preguntas = "INSERT INTO acpregevalua (pre_ano,pre_per,pre_formulario,pre_pregunta,pre_tipo_preg,pre_aplica,pre_texto,pre_estado)";
							//(-) (  12345678901234567890123456789)
							//(1) (	 2005,3,x,x,1,S,xxxxxxxxxxx,A)	
							//(2) (  2005,3,xx,x,1,S,xxxxxxxxxx,A)
							//(3) (  2005,3,x,xx,1,S,xxxxxxxxxx,A)
							//(4) (  2005,3,xx,xx,1,S,xxxxxxxxxx,A)
							if (substr($contenido,8,1) == "," && substr($contenido,10,1) == ",") {//(1)
									$preguntas .= " VALUES (".substr($contenido,0,12).",'".substr($contenido,13,1)."','".substr($contenido,15,strlen($contenido)-19)."','".substr($contenido,-3,1)."')";
								} else if (substr($contenido,9,1) == "," && substr($contenido,11,1) == ",") {	//(2);
									$preguntas .= " VALUES (".substr($contenido,0,13).",'".substr($contenido,14,1)."','".substr($contenido,16,strlen($contenido)-20)."','".substr($contenido,-3,1)."')";
								} else if (substr($contenido,8,1) == "," && substr($contenido,13,1) == ",") {	//(3)
									$preguntas .= " VALUES (".substr($contenido,0,13).",'".substr($contenido,14,1)."','".substr($contenido,16,strlen($contenido)-20)."','".substr($contenido,-3,1)."')";
								} else if (substr($contenido,9,1) == "," && substr($contenido,12,1) == ",") {	//(4)
									$preguntas .= " VALUES (".substr($contenido,0,14).",'".substr($contenido,15,1)."','".substr($contenido,18,strlen($contenido)-21)."','".substr($contenido,-3,1)."')";
								} else {
									echo "Verificar formato";
								}
								// --INSERTAR a BD --
								echo $preguntas;
								///----if ($p <= $lineas_a ) {
									$rs2 = @OCIParse($oci_cn,$preguntas);
									$rs2exe =@OCIExecute($rs2, OCI_COMMIT_ON_SUCCESS);
									if (!$rs2exe) {
									  $e = OCIError($rs2);
									  append_logev($pag,$e['code'],$fmto,110);
									}
									else {
										OCICommit($oci_cn);?>
										<font size = 2><EM>Operación Exitosa</EM></font><br><?
									}
								///----}
								$contenido = "";
								$preguntas = "";
				}
			} else {
				$pregtot = 11;
			}
			break;
			
		case "7":
			$pregtot = 13;	//Total de preguntas en la tabla 
			$est_cod= $_SESSION["codigo".$sec];
			$asi_cod = $_SESSION["asig".$sec];
			$gru = $_SESSION["grupo".$sec];
			leerespuestas($pregtot);
	   		$valores .= " (EPE_APE_ANO, EPE_APE_PER, EPE_EST_COD, EPE_CUR_ASI_COD, EPE_CUR_NRO, EPE_DOC_NRO_IDEN, ";
   			concatvalores($pregtot);	
			$valores .= " EPE_OBSERVA,EPE_ESTADO) VALUES (";
		    $valores .= $ano.",".$per.",".$est_cod.",".$asi_cod;
		    $valores .= ",".$gru.",".$doc.",";
			break;
		case "8":
			$pregtot = 3;		break;
		case "9":
			$pregtot = 7;		break;
		case "10":
			$pregtot = 20;		break;
		case "11":
			$pregtot = 11;		break;
		case "12":
			$pregtot = 14;		break;
		case "13":
			$pregtot = 13;		break;
		case "14":
			$pregtot = 6;		break;
  }
   		if ($fmto  != "7") {
		    $cra = $_SESSION["cra".$sec]; //@$_POST["text_cra"];
			leerespuestas($pregtot);
			if ($fmto == "6" || $fmto == "10" || $fmto == "12" || $fmto == "13") { 
				$valores .= " (EPA_APE_ANO, EPA_APE_PER, EPA_CRA_COD, EPA_DOC_NRO_IDEN, EPA_OBSERVA, ";
				concatvalores($pregtot);	
				$valores .= " EPA_ESTADO) VALUES (";
			//end if
			//if fmto = "8" or fmto = "9" or fmto = "11" or fmto = "14" then  //!???
			} else {
				$valores .= " (EPC_APE_ANO, EPC_APE_PER, EPC_CRA_COD, EPC_DOC_NRO_IDEN, EPC_OBSERVA2, ";
				concatvalores($pregtot);	
				$valores .= " EPC_ESTADO) VALUES (";
			}
		    $valores .= $ano.",".$per.",".$cra.",".$doc.",".$tipovin.",";
		}
			addrespuestas($pregtot);
		    $valores .= "'A')";
//-----------------------------------------------
/*	$leerpostdata = $_POST["sec"]." usuario = ".$_POST["usuario"]." nivel = ".$_POST["nivel"];
	echo "POSTDATA = ".$leerpostdata;
	$valores = "INSERT INTO desarrollador1.ACEVAPROEST (EPE_APE_ANO, EPE_APE_PER, EPE_EST_COD, 
		EPE_CUR_ASI_COD, EPE_CUR_NRO, EPE_DOC_NRO_IDEN, EPE_RES_1,EPE_RES_2,EPE_RES_3,
		EPE_RES_4,EPE_RES_5,EPE_RES_6,EPE_RES_7,EPE_RES_8,EPE_RES_9,EPE_RES_10,EPE_RES_13, 
		EPE_OBSERVA,EPE_ESTADO) VALUES (2005,1,20021020047,20505,2,19226603,5,5,5,'0','0',
		'0','0','0','0','0','','--------------****************----------------------','X')";
//*------------------------------------------*/
   	//echo $valores;
	$rs2 = @OCIParse($oci_cn,$valores); 
	$rs2exe = @OCIExecute($rs2, OCI_DEFAULT);
	if (!$rs2exe) {
	  $e = OCIError($rs2);
	  $x = append_logev($pag,$e['code'],$e['message'],$fmto,111);
	}else {
		OCICommit($oci_cn);?>
		<font size = 2 color="#336699"><EM>Operación Exitosa</EM></font><br><?
		//echo $_SESSION["vinselactual"];
	}
		OCIFreeCursor($rs2);
		OCILogOff ($oci_cn);
   //call $handerr[$pag,$fmto];
  if ($x != true) {?>	<br><br>
		<font size = 2 color="#336699"><EM>Gracias por contribuir al mejoramiento de nuestra Universidad</EM></font><br>
		<font size = 2 color="#336699"><EM>Continue con su evaluación</EM></font><br><?
	}else echo "No hay evaluaci&oacute;n que grabar"; ?>
		<input type="hidden" name="validaformato" type="button" value=0> 
		<input type="hidden" name="numfmto" id="numfmto" value="<? echo $fmto?>">
</BODY>
</HTML>

