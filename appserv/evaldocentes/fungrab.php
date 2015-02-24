<?php
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
require_once("funerr.php"); 
require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');

fu_tipo_user($_SESSION["usuario_nivel"]);
 ?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
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
   $acepro[10] = "ACEVAPROEST_15";
   $acepro[11] = "ACEVAPROAUT_16";
   $acepro[12] = "ACEVAPROCPC_17";
   $ano = $evanio;
   $per = $evaper;
   $sec= @$_REQUEST["text_sec"];
   $fmto = @$_REQUEST["text_fmto"];			///---- verificar
   $doc = @$_REQUEST["text_doc"];				///---- verificar
   $tipovin = @$_REQUEST["text_tipovin"];				///---- verificar

function leerespuestas($m) { 
  	global $r;
	global $fmto;
	$once=$m-2;
	for ($i=1; $i<=$m; $i++) {
		if ($i == $once && $fmto == "7" ){
		
			//$r[$i] = @$_REQUEST["r13"]; //La pregunta 11 del formato 7 tiene id="r13"
			//r(i+1) = left(Request.Form("r14"),255) //Campo observaciones del formato 7 tiene id="r14"
			$r[$i]=0;
		}
		elseif($i == $once && $fmto == "10" ){
			$r[$i]=0;
		}
		elseif($i == $once && $fmto == "12" ){
			$r[$i]=0;
		}
		elseif($i == $once && $fmto == "13" ){
			$r[$i]=0;
		}
		elseif($i == $once && $fmto == "6" ){
			$r[$i]=0;
		}
		/*elseif($i == $once && $fmto == "15"){
			$r[$i]=0;
		}*/
		else {
			//Silas preguntas están vacias, que el registro se guarde con un 0.
			if(isset($_REQUEST["r".$i])){
			$r[$i] = @$_REQUEST["r".$i]; //valida_campo[Request.Form("r"&i)];
			}
			else{
			$r[$i]='NULL';
			}
			
		} 
	}
 } 
//-------------------------------
function concatvalores($n) {
	global $valores;
	global $fmto;
	define("fr", $fmto);	
	for ($i=1; $i<=$n; $i++) {
		if (fr == 7 || fr==15) {
			//if ($i == 11 || $i == 12) {
				//excluir preguntas no activas
			//} 
			//else {
				$valores .= "EPE_RES_".$i.",";
			//}
		} elseif (fr == 6 || fr == 10 || fr == 12 || fr == 13 || fr == 16) {; 
			$valores .= "EPA_RES_".$i.",";
		} else {
			$valores .= "EPC_RES_".$i.",";
		}
	} 
 }
//-----------------------------------------------
function addrespuestas($pregtot)
{
	global $r;
	global $valores;
	global $fmto;
 	$n = $pregtot;
	for ($i=1; $i<=$n; $i++)
	{
		if ($i == $n && $fmto == 7 ) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
				//"'".$r[$i]."','".substr(@$_REQUEST["obs"],0,255)."',"; //Incluir observaciones
		} 
		elseif($i == $n && $fmto == 10) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 12) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 13) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 6) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 8) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 9) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 11) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 14) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		} 
		elseif (($i == 13) && $fmto == 7)  {
					//echo "Pregunta no activa";
		}
		
		elseif (($i < 14) && ($i != $once) && ($fmto == "7")) { 
		
			if (($r[$i] == 5 || $r[$i] == 4 || $r[$i] == 3 || $r[$i] == 2 || $r[$i] == 1)) {
				$valores .= $r[$i].",";
				
			} else {
					$valores .= "'0',"; //No admite valor null
			}
		}
		elseif ($i == $n && $fmto == 15 ) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
				//"'".$r[$i]."','".substr(@$_REQUEST["obs"],0,255)."',"; //Incluir observaciones
		}
		elseif($i == $n && $fmto == 16) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		}
		elseif($i == $n && $fmto == 17) {
			$valores .= $r[$i].",'".substr(@$_REQUEST["obs"],0,255)."',";
		} 
		else {
			$valores .= $r[$i].",";
		}     
	}
 } 
 //*--------------------------------------------
 		//------------//$fmto = $text_fmto ; $doc = $text_doc;
//echo "Formato: ".$fmto;		
	$valores = "INSERT INTO autoevaluadoc.".$acepro[$fmto - 5]; //acepro[1]
	//echo $fmto;
   switch ($fmto){ 
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
								//----if ($p <= $lineas_a ) {
									$rs2 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$preguntas,"busqueda");
									if(!isset($rs2))
									{
									  $e = $rs2[0][0];
									  append_logev($pag,$e['code'],$fmto,110);
									}
									else
									{
										//OCICommit($oci_cn);?>
										<font size = 2><EM>Operaci&oacute;n Exitosa</EM></font><br><?
									}
								//----}
								$contenido = "";
								$preguntas = "";
				}
			} else {
				$pregtot = 14;
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
			//echo 'mmmm'.$valores;
			break;
		case "8":
			$pregtot = 3;		break;
		case "9":
			$pregtot = 7;		break;
		case "10":
			$pregtot = 23;		break;
		case "11":
			$pregtot = 10;		break;
		case "12":
			$pregtot = 17;		break;
		case "13":
			$pregtot = 16;		break;
		case "14":
			$pregtot = 6;		break;
		case "15":
			$pregtot = 11;		break;
		case "16":
			$pregtot = 12;		break;
		case "17":
			$pregtot = 9;		break;
  }
   		
   		if ($fmto  != "7")
   		{
		    	$cra = $_SESSION["cra".$sec]; //@$_REQUEST["text_cra"];
			
			if ($fmto == "6" || $fmto == "10" || $fmto == "12" || $fmto == "13" || $fmto == "16")
			{ 
				leerespuestas($pregtot);
				$valores .= " (EPA_APE_ANO, EPA_APE_PER, EPA_CRA_COD, EPA_DOC_NRO_IDEN,EPA_TIP_VIN,";
				concatvalores($pregtot);	
				$valores .= "EPA_OBSERVA,EPA_ESTADO) VALUES (";
				$valores .= $ano.",".$per.",".$cra.",".$doc.",".$tipovin.",";
				//end if
				//if fmto = "8" or fmto = "9" or fmto = "11" or fmto = "14" then  //!???
			}
			elseif($fmto == "15")
			{
				$pregtot = 11;	//Total de preguntas en la tabla 
				$est_cod= $_SESSION["codigo".$sec];
				$asi_cod = $_SESSION["asig".$sec];
				$gru = $_SESSION["grupo".$sec];
				leerespuestas($pregtot);
				$valores .= " (EPE_APE_ANO, EPE_APE_PER, EPE_EST_COD, EPE_CUR_ASI_COD, EPE_CUR_NRO, EPE_DOC_NRO_IDEN, ";
				concatvalores($pregtot);	
				$valores .= " EPE_OBSERVA,EPE_ESTADO) VALUES (";
				$valores .= $ano.",".$per.",".$est_cod.",".$asi_cod;
				$valores .= ",".$gru.",".$doc.",";
			}
			elseif($fmto == "17")
			{
				$docid=$_SESSION["docente".$sec];
				$fac = $_SESSION["fac".$sec];
				leerespuestas($pregtot);
				$valores .= " (EPC_APE_ANO, EPC_APE_PER, EPC_DEP_COD, EPC_DOC_NRO_IDEN,EPC_OBSERVA2, ";
				concatvalores($pregtot);	
				$valores .= "EPC_OBSERVA1, EPC_ESTADO) VALUES (";
				$valores .= $ano.",".$per.",".$fac.",".$docid.",".$tipovin.",";
			}  
			else
			{
				leerespuestas($pregtot);
				$valores .= " (EPC_APE_ANO, EPC_APE_PER,EPC_CRA_COD,  EPC_DOC_NRO_IDEN,EPC_OBSERVA2, ";
				concatvalores($pregtot);	
				$valores .= "EPC_OBSERVA1, EPC_ESTADO) VALUES (";
				$valores .= $ano.",".$per.",".$cra.",".$doc.",".$tipovin.",";
			}
		    	 
		}
		
			addrespuestas($pregtot);
		    $valores .= "'A')";
//-----------------------------------------------
/*	$leerpostdata = $_REQUEST["sec"]." usuario = ".$_REQUEST["usuario"]." nivel = ".$_REQUEST["nivel"];
	echo "POSTDATA = ".$leerpostdata;
	$valores = "INSERT INTO desarrollador1.ACEVAPROEST (EPE_APE_ANO, EPE_APE_PER, EPE_EST_COD, 
		EPE_CUR_ASI_COD, EPE_CUR_NRO, EPE_DOC_NRO_IDEN, EPE_RES_1,EPE_RES_2,EPE_RES_3,
		EPE_RES_4,EPE_RES_5,EPE_RES_6,EPE_RES_7,EPE_RES_8,EPE_RES_9,EPE_RES_10,EPE_RES_13, 
		EPE_OBSERVA,EPE_ESTADO) VALUES (2005,1,20021020047,20505,2,19226603,5,5,5,'0','0',
		'0','0','0','0','0','','--------------****************----------------------','X')";
		
		
//*------------------------------------------*/
//echo "$evanio,$evaper,$est_cod,$asi_cod,$gru,$doc,$r1,$r2,$r3,$r4,,$r5,$r6,$r7,$r8,$r9,$r10,$obs,$r12,$r13";
	$rs2 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$valores,"busqueda");
	
	$afectados=$conexion->totalAfectados($configuracion,$accesoOracle); //Esta linea es para verificar si se guardaron los registros en la base de datos;
	
	if($afectados >= 1)
	{
		echo '<center><br><br><br><br><font size = 2 color="#336699"><b>OPERACI&Oacute;N EXITOSA, '.$afectados.' Registro(s) afectado(s). </b></font></center><p>';
		//echo $_SESSION["vinselactual"];
	}
	else
	{
		$e = $rs2[0][0];
		$x = append_logev($pag,$e['code'],$e['message'],$fmto,111);
		
	}

   //call $handerr[$pag,$fmto];
  if ($x != true) {	
		//echo "<font size = 2 color='#336699'>Gracias por contribuir al mejoramiento de nuestra Universidad</p></font><br>";
		?>
			<br><br>
			<center><font size = 2 color="#336699">Gracias por contribuir al mejoramiento de nuestra Universidad</font></p>
			<font size = 2 color='#336699'>Si est&aacute; vinculado a m&aacute;s asignaturas, lo invitamos a continuar con la evaluaci&oacute;n.</font></p>
			
		<?}else echo "No hay evaluaci&oacute;n que grabar"; ?>
		<input type="hidden" name="validaformato" type="button" value=0> 
		<input type="hidden" name="numfmto" id="numfmto" value="<? echo $fmto?>">
</BODY>
</HTML>

