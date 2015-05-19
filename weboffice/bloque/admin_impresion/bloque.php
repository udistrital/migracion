<?
/*
###########################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion 
###########################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      ultima revision 2 de junio de 2007
*****************************************************************************
* @subpackage   admin_recibo
* @package	bloques
* @copyright    
* @version      0.3
* @link		N/D
* @description  Bloque principal para la administracion de medicamentoes
*
******************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//Se incluye para manejar los mensajes de error
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"oracle2");
$enlace=$accesoOracle->conectar_db();

$acceso_db=$conexion->recursodb($configuracion,"");
$enlace=$acceso_db->conectar_db();
//Rescatar los datos generales
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
$datoBasico=new datosGenerales();

$anno=$datoBasico->rescatarDatoGeneral($configuracion, "anno", "", $accesoOracle);
$periodo=$datoBasico->rescatarDatoGeneral($configuracion, "per", "", $accesoOracle);

$_REQUEST["anioRecibo"]=(isset($_REQUEST["anioRecibo"])?$_REQUEST["anioRecibo"]:'');
$_REQUEST["periodoRecibo"]=(isset($_REQUEST["periodoRecibo"])?$_REQUEST["periodoRecibo"]:'');

$valor[0]=$_REQUEST["factura"];
if($_REQUEST["anioRecibo"]){
    $valor[1]=$_REQUEST["anioRecibo"];

}else{
    $valor[1]=$anno;
}
if($_REQUEST["periodoRecibo"]){
    $valor[2]=$_REQUEST["periodoRecibo"];

}else{
    $valor[2]=$periodo;
}
$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosActual");
$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	

$cadena_sql2=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosPrimerSemestre");

$registro2=ejecutar_admin_recibo($cadena_sql2,$accesoOracle,"busqueda");	

if(!is_array($registro) && !is_array($registro2))
{	
	
	$cadena="<p>En la actualidad no tiene ningun recibo registrado para impresion.</p>";
	$cadena="<p>Por favor contacte a su Coordinador(a) para que generen su recibo a traves de plantillas</p>";
	alerta::sin_registro($configuracion,$cadena);	
}
else
{
        if(!$registro && $registro2){
            $registro = $registro2;
        }
        $campos=count($registro);
	con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db,$accesoOracle,$datoBasico);
}


/****************************************************************
*  			Funciones				*
****************************************************************/



function con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db,$accesoOracle,$datoBasico)
{
	//Clases necesarias
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/fpdf.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/codigoBarras.class.php");
	ob_end_clean();
	//Objetos
	$codigoBarras= new codigoBarras($configuracion);	
	$cripto=new encriptar();
	$pdf=new FPDF('P','mm','letter');
	
	//Variables
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	//Variables para el codigo de barras
	$funcion1="FN";
	$ia1='415';
	$ia2='8020';
	$ia3='3900';
	$ia4='96';
	$codigoInstitucion='7709998000421';
	
	//Rescatar los valores de la factura
	$codigoEstudiante=$registro[0][1];
	$documento=$registro[0][12];
	$nombre=htmlspecialchars($registro[0][13]);
	$nombreCarrera=$registro[0][14];
	
	//Rescatar identificador de la factura  (SEQ_PRUEBA)(ACESTMATPRUEBA) (MATRECIBOS)
	//$codigoConsecutivo=obtenerCodigo();
	$codigoConsecutivo=$registro[0][0];
	
	//Rescatar fecha de pago ordinario 8 Digitos yyyymmdd	
	$fechaPago=$registro[0][10];
	
	//Rescatar fecha de pago extraordinario 8 Digitos yyyymmdd	
	$fechaPagoExtra=$registro[0][11];
	
	//Matricula Ordinaria
	
	$valorMatricula=$registro[0][3];	
	$valorMatriculaExtra=$registro[0][4];
		
	$anno=$datoBasico->rescatarDatoGeneral($configuracion, "anno", "", $accesoOracle);
	$periodo=$datoBasico->rescatarDatoGeneral($configuracion, "per", "", $accesoOracle);
		
	//Si la secuencia esta registrada en ACREFEST entonces calculamos el pago basados en dicha informacion
	$valor[0]=$_REQUEST["factura"];
        if($_REQUEST["anioRecibo"]){
            $valor[1]=$_REQUEST["anioRecibo"];

        }else{
            $valor[1]=$anno;
        }
        if($_REQUEST["periodoRecibo"]){
            $valor[2]=$_REQUEST["periodoRecibo"];

        }else{
            $valor[2]=$periodo;
        }
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"conceptosActual");
	$registroConceptos=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
	if(is_array($registroConceptos))
	{	
			
		$otrosConceptos=0;
		$conceptos=0;
		
		//Matricula
		$j=0;
		while(isset($registroConceptos[$j][0]))
		{
			if($registroConceptos[$j][3]==1)
			{
				$obs_diferido=ejecutar_admin_recibo("SELECT mntac.fua_obs_diferido(".$registro[0][1].",".$registro[0][0].")",$accesoOracle,"busqueda");	
				$registroConceptos[$j][5]=$registroConceptos[$j][5]." ".$obs_diferido[0][0];
			}	
			if($registroConceptos[$j][3]==2)
			{
				$valorSeguro=$registroConceptos[$j][4];
				$otrosConceptos+=$registroConceptos[$j][4];
			}
			elseif($registroConceptos[$j][3]>2)
			{
				$otrosConceptos+=$registroConceptos[$j][4];
			}
			
			$j++;
		}
		$valorPagar=$valorMatricula+$otrosConceptos;
		$valorPagarExtra=$valorMatriculaExtra+$otrosConceptos;
		
	}
	else
	{
		//Si es la primera cuota y no es recibo de Saber Pro
		if($registro[0][7]==1&&strpos($registro[0][15],'SABER')===FALSE)
		{
			$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db,$valor,"valorSeguro");
			$registroSeguro=ejecutar_admin_recibo($cadena_sql,$acceso_db,"busqueda");			
			$valorSeguro=$registroSeguro[0][0];
	
			
			$valorPagar=$valorMatricula+$valorSeguro;
		}
		else
		{
			$valorSeguro=0;
			$valorPagar=$valorMatricula;
		
		}
		
		//Calcular matricula extraordinario
		
		if($registro[0][7]==1)
		{
			$valorPagarExtra=$valorMatriculaExtra+$valorSeguro;
		}
		else
		{
			$valorPagarExtra=$valorMatriculaExtra;
		}
	}
	//Valores para el Codigo de Barras
	$codigoEstudianteI=str_repeat("0",(12-strlen($codigoEstudiante))).$codigoEstudiante;	//12 Digitos
	$codigoConsecutivoI=str_repeat("0",(6-strlen($codigoConsecutivo))).$codigoConsecutivo;	//6 Digitos
	$valorPagarI=str_repeat("0",(10-strlen($valorPagar))).$valorPagar; 			//10 Digitos
	$valorPagarExtraI=str_repeat("0",(10-strlen($valorPagarExtra))).$valorPagarExtra; 	//10 Digitos
	
	
	//Pago ordinario a codificar
	$codigo=$funcion1;
	$codigo.=$ia1;
	$codigo.=$codigoInstitucion;
	$codigo.=$ia2;
	$codigo.=$codigoEstudianteI;
	$codigo.=$codigoConsecutivoI;
	$codigo.=$funcion1;
	$codigo.=$ia3;
	$codigo.=$valorPagarI;
	$codigo.=$funcion1;
	$codigo.=$ia4;
	$codigo.=$fechaPago;
	
	
	
	//Etiqueta del Codigo Ordiario
	$etiquetaCodigo="(";
	$etiquetaCodigo.=$ia1;
	$etiquetaCodigo.=")";
	$etiquetaCodigo.=$codigoInstitucion;
	$etiquetaCodigo.="(";
	$etiquetaCodigo.=$ia2;
	$etiquetaCodigo.=")";
	$etiquetaCodigo.=$codigoEstudianteI;
	$etiquetaCodigo.=$codigoConsecutivoI;
	$etiquetaCodigo.="(";
	$etiquetaCodigo.=$ia3;
	$etiquetaCodigo.=")";
	$etiquetaCodigo.=$valorPagarI;
	$etiquetaCodigo.="(";
	$etiquetaCodigo.=$ia4;
	$etiquetaCodigo.=")";
	$etiquetaCodigo.=$fechaPago;
	
	
	//Pago E a codificar
	$codigoExtraordinario=$funcion1;
	$codigoExtraordinario.=$ia1;
	$codigoExtraordinario.=$codigoInstitucion;
	$codigoExtraordinario.=$ia2;
	$codigoExtraordinario.=$codigoEstudianteI;
	$codigoExtraordinario.=$codigoConsecutivoI;
	$codigoExtraordinario.=$funcion1;
	$codigoExtraordinario.=$ia3;
	$codigoExtraordinario.=$valorPagarExtraI;
	$codigoExtraordinario.=$funcion1;
	$codigoExtraordinario.=$ia4;	
	$codigoExtraordinario.=$fechaPagoExtra;
	//Etiqueta codigo extraordinario
	$etiquetaCodigoExtra="(";
	$etiquetaCodigoExtra.=$ia1;
	$etiquetaCodigoExtra.=")";
	$etiquetaCodigoExtra.=$codigoInstitucion;
	$etiquetaCodigoExtra.="(";
	$etiquetaCodigoExtra.=$ia2;
	$etiquetaCodigoExtra.=")";
	$etiquetaCodigoExtra.=$codigoEstudianteI;
	$etiquetaCodigoExtra.=$codigoConsecutivoI;
	$etiquetaCodigoExtra.="(";
	$etiquetaCodigoExtra.=$ia3;
	$etiquetaCodigoExtra.=")";
	$etiquetaCodigoExtra.=$valorPagarExtraI;
	$etiquetaCodigoExtra.="(";
	$etiquetaCodigoExtra.=$ia4;
	$etiquetaCodigoExtra.=")";
	$etiquetaCodigoExtra.=$fechaPagoExtra;
	
	//echo $codigo."<br>";
	//echo $codigoExtraordinario."<br>";
	
	//Generar los codigos de Barra
	$imagenCodigo=generarCodigoBarras($codigoBarras, $codigo, $configuracion,$codigo);
	//echo $imagenCodigo;
	
	
	$imagenCodigoExtra=generarCodigoBarras($codigoBarras, $codigoExtraordinario, $configuracion,$codigoExtraordinario);
	//echo $imagenCodigo;
	
	//Valores de Impresion
	$valor[0]=$imagenCodigo;
	$valor[1]=$imagenCodigoExtra;
	$valor[2]=$codigoConsecutivo;
	$valor[3]=$nombre;
	$valor[4]=$codigoEstudiante;
	$valor[5]=$documento;
	$valor[6]=$nombreCarrera;
	$valor[7]=substr($fechaPago,6,2)."/".substr($fechaPago,4,2)."/".substr($fechaPago,0,4);
	$valor[8]=$valorPagar;
	$valor[9]=$etiquetaCodigo;
	$valor[10]=substr($fechaPagoExtra,6,2)."/".substr($fechaPagoExtra,4,2)."/".substr($fechaPagoExtra,0,4);
	$valor[11]=$valorPagarExtra;
	$valor[12]=$etiquetaCodigoExtra;
	$valor[13]=$registro[0][5];
	$valor[14]=$registro[0][6];
	//Fecha Expedicion
	$valor[15]=substr($registro[0][8],6,2)."/".substr($registro[0][8],4,2)."/".substr($registro[0][8],0,4);
	//Cuota
	$valor[16]=$registro[0][7];
	//Valor Seguro
	if(isset($valorSeguro))
	{
		$valor[17]=$valorSeguro;
	}
	else
	{
		$valor[17]=0;
	}
	//Observaciones
	$parametro[0]=$codigoConsecutivo;
	if($_REQUEST["anioRecibo"]){
            $parametro[1]=$_REQUEST["anioRecibo"];

        }else{
            $parametro[1]=$anno;
        }
        
	$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $parametro,"datosSolicitud");
	$registroObservacion=ejecutar_admin_recibo($cadena_sql,$acceso_db,"busqueda");	
	if(isset($registro[0][15]))
	{
		$valor[18]=$registro[0][15];
	}
	else
	{
		$valor[18]="";
	}
	
	$valor[19]=$valorMatricula;
	if(!isset($pago))
	{
		$pago="";
	}
	$pdf->AddPage();
			
	//Copia Estudiante
	$salida=prepararPDF($configuracion,$pdf,$valor,$pago, "estudiante",$registroConceptos);
	
	//Copia Proyecto Curricular
	$salida=prepararPDF($configuracion,$pdf,$valor,$pago, "proyecto",$registroConceptos);
	
	//Copia Banco
	$salida=prepararPDF($configuracion,$pdf, $valor,$pago, "banco",$registroConceptos);
	$pdf->Output();
}



function cadena_busqueda_recibo($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{
	
		case "valorSeguro":
				$cadena_sql="SELECT ";
				$cadena_sql.="`valor` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."referenciaPago ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_referencia=2";
		break;
					
		case "recibosActual":	
			
			$cadena_sql="SELECT ";
			$cadena_sql.="ema_secuencia, ";
			$cadena_sql.="ema_est_cod, ";
			$cadena_sql.="ema_cra_cod, ";
			$cadena_sql.="ema_valor, ";
			$cadena_sql.="ema_ext, ";
			$cadena_sql.="ema_ano_pago, ";
			$cadena_sql.="ema_per_pago, ";
			$cadena_sql.="ema_cuota, ";
			$cadena_sql.="TO_CHAR(EMA_FECHA, 'YYYYMMDD'), ";
			$cadena_sql.="ema_estado, ";
			$cadena_sql.="TO_CHAR(EMA_FECHA_ORD, 'YYYYMMDD'), ";
			$cadena_sql.="TO_CHAR(EMA_FECHA_EXT, 'YYYYMMDD'), ";
			$cadena_sql.="est_nro_iden, ";
			$cadena_sql.="est_nombre, ";
			$cadena_sql.="cra_abrev, ";
			$cadena_sql.="ema_obs ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACESTMAT, ";
			$cadena_sql.="ACEST, ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="EMA_SECUENCIA = ".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_ESTADO='A' ";	
			$cadena_sql.="AND ";
			$cadena_sql.="ema_est_cod = est_cod ";
			$cadena_sql.="AND ";
			$cadena_sql.="ema_cra_cod = cra_cod";
			//$cadena_sql.="AND ";
			//$cadena_sql.="EMA_IMP_RECIBO=0";	

			break;
			
                case "recibosPrimerSemestre":
                        $cadena_sql =" SELECT ";
                        $cadena_sql.=" AMA_SECUENCIA,";
                        $cadena_sql.=" EAD_COD,";
                        $cadena_sql.=" AMA_CRA_COD,";
                        $cadena_sql.=" AMA_VALOR,";
                        $cadena_sql.=" AMA_EXT,";
                        $cadena_sql.=" AMA_ANO,";
                        $cadena_sql.=" AMA_PER,";
                        $cadena_sql.=" case when AMA_CUOTA is null then 1";
                        $cadena_sql.=" else ama_cuota end ama_cuota,";
                        $cadena_sql.=" TO_CHAR(AMA_FECHA, 'YYYYMMDD'), ";
                        $cadena_sql.=" AMA_ESTADO,";
                        $cadena_sql.=" TO_CHAR(AMA_FECHA_ORD, 'YYYYMMDD'), ";
                        $cadena_sql.=" TO_CHAR(AMA_FECHA_EXT, 'YYYYMMDD'), ";
                        $cadena_sql.=" EST_NRO_IDEN,";
                        $cadena_sql.=" EST_NOMBRE,";
                        $cadena_sql.=" cra_abrev, ";
			$cadena_sql.=" AMA_OBS";
                        $cadena_sql.=" FROM ACADMMAT";
                        $cadena_sql.=" INNER JOIN ACESTADM on AMA_ANO = EAD_ASP_ANO and AMA_PER = EAD_ASP_PER and AMA_ASP_CRED = EAD_ASP_CRED";
                        $cadena_sql.=" INNER JOIN ACEST on EAD_COD = EST_COD";
                        $cadena_sql.=" INNER JOIN ACCRA on AMA_CRA_COD = CRA_COD";
                        $cadena_sql.=" WHERE ";
                        $cadena_sql.=" AMA_SECUENCIA = ".$valor[0]." ";
                        $cadena_sql.=" AND AMA_ANO = ".$valor[1]." ";
                        $cadena_sql.=" AND AMA_PER = ".$valor[2]." ";

                    break;
                    
		case "conceptosActual":
			$cadena_sql="SELECT ";
			$cadena_sql.="aer_secuencia, ";
			$cadena_sql.="aer_ano, ";
			$cadena_sql.="aer_bancod, ";
			$cadena_sql.="aer_refcod, ";
			$cadena_sql.="aer_valor, ";
			$cadena_sql.="reb_refnom ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acrefban, ";
			$cadena_sql.="acrefest ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="aer_secuencia=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="aer_ano=".$valor[1]." ";	
// 			$cadena_sql.="AND ";
// 			$cadena_sql.="aer_refcod > 1 ";
			$cadena_sql.="AND ";
			$cadena_sql.="aer_bancod=23 ";				
			$cadena_sql.="AND ";
			$cadena_sql.="aer_refcod=reb_refcod ";
			break;
			
		case "matricula":
			$cadena_sql="SELECT ";
			$cadena_sql.="codigo_est, ";
			$cadena_sql.="valor_mat, ";
			$cadena_sql.="valor_bruto ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="codigo_est=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="id_carrera=".$valor[1]." ";
			$cadena_sql.="LIMIT 1 ";
			//echo $cadena_sql;
			break;
			
		case "datosSolicitud":
			//MySQL
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_solicitud_recibo`, ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`codigo_est`, ";
			$cadena_sql.="`id_carrera`, ";
			$cadena_sql.="`cuota`, ";
			$cadena_sql.="`estado`, ";
			$cadena_sql.="`fecha`, ";
			$cadena_sql.="`anno`, ";
			$cadena_sql.="`periodo`, ";
			$cadena_sql.="`tipoPlantilla`, ";
			$cadena_sql.="`unidad`, ";
			$cadena_sql.="`secuencia`, ";
			$cadena_sql.="`observacion` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="secuencia= ".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="anno= ".$valor[1]." ";
			//echo $cadena_sql;
			break;
		
		case "exencion":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.codigo_est, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.id_programa, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.id_exencion, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.anno, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.periodo, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.fecha, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.nombre, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.porcentaje, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.etiqueta ";			
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.codigo_est=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante.id_carrera=".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.codigo_est=".$configuracion["prefijo"]."estudiante.codigo_est ";			
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion.id_exencion=".$configuracion["prefijo"]."exencion.id_exencion ";
			//$cadena_sql.="LIMIT 1 ";
			break;
			
		
		default:
			$cadena_sql="";
			break;
	}
	//echo $cadena_sql;
	return $cadena_sql;
}

function ejecutar_admin_recibo($cadena_sql,$conexion,$tipo)
{
	//echo $cadena_sql;
	$resultado= $conexion->ejecutarAcceso($cadena_sql,$tipo);
	return $resultado;
}

function generarCodigoBarras($codigoBarras, $codigo, $configuracion,$valor)
{
	$codigoBarras->altoSimbolo(50);
	//$bar->setFont("arial");
	$codigoBarras->escalaSimbolo(0.5);
	$codigoBarras->colorSimbolo("#000000","#FFFFFF");

	$elCodigo=$valor;
	$return = $codigoBarras->generar($codigo,$configuracion["raiz_documento"].$configuracion["documento"]."/".$elCodigo);
	if($return==false)
	{
		$codigoBarras->error(true);
	}
	return $elCodigo;
}


function prepararPDF($configuracion,$pdf, $valor,$pago="",$tipo="",$conceptos="")
{
	setlocale(LC_MONETARY, 'en_US');
	if($tipo=="proyecto" || $tipo=="banco")
	{
		if($tipo=="proyecto")
		{
			$offset=70;
			$pdf->Ln(14);
			$pie="- COPIA PROYECTO CURRICULAR -";
			$division=".........................................................................................................................Doblar.........................................................................................................................";
		
		}
		else
		{
			$pdf->Ln(12);
			$offset=165;
			$pie="- COPIA BANCO -";
					
		}
		
		$pdf->SetTextColor(50, 50, 50);
		$pdf->Image($configuracion["raiz_documento"].$configuracion["grafico"].'/recibo.jpg',0,($offset+1),210);
		$pdf->Image($configuracion["raiz_documento"].$configuracion["documento"].'/'.$valor[0].'.png',8,($offset+39),127);		
		$pdf->Image($configuracion["raiz_documento"].$configuracion["documento"].'/'.$valor[1].'.png',8,($offset+70),127);
		$pdf->SetFont('Arial','B',10);		
		$pdf->Ln(-5);
		$pdf->Cell(15,4,"",0);
		//Universidad
		$pdf->Cell(50,4,"UNIVERSIDAD DISTRITAL ",0);
		$pdf->Ln(3);
		$pdf->Cell(15,4,"",0);
		//Francsico Jose de Caldas
		$pdf->Cell(40,4,"Francisco Jose de Caldas",0);		
		$pdf->Ln(1);
		$pdf->Cell(70,4,"",0);
		//Comprobante de Pago
		$pdf->Cell(70,4,"COMPROBANTE DE PAGO No ".$valor[2],0);	
		//Banco		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,4,"CS ".strtoupper(md5($valor[2].$valor[4].$valor[9])),0);	
			
		$pdf->Ln(3);
		$pdf->Cell(15,4,"",0);
		$pdf->SetFont('Arial','',8);
		//NIT
		$pdf->Cell(70,4,"NIT 899.999.230.7",0);		
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',10);
		//Encabezado
		$pdf->Ln(7);
		//Nombre
		$pdf->Cell(76,4,"Nombre del Estudiante",0);
		//Codigo Estudiante
		$pdf->Cell(26,4,"Codigo",0);
		//Documento
		$pdf->Cell(45,4,"Doc. Identidad",0);
		//Carrera
		$pdf->Cell(30,4,"Proyecto Curricular");		
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Ln(5);
		//Nombre
		$pdf->Cell(73,4,(utf8_decode($valor[3])),0);
		//Codigo Estudiante
		$pdf->Cell(32,4,$valor[4],0);
		//Documento
		$pdf->Cell(29,4,$valor[5],0);
		//Carrera
		$pdf->Cell(30,4,$valor[6],0);		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(5);
		//Tipo Pago
		$pdf->Cell(35,4,"Pago",0);
		//Fecha 1
		$pdf->Cell(50,4,"Fecha Limite",0);
		//Pago Ordinario
		$pdf->Cell(50,4,"TOTAL A PAGAR",0);
		//Fecaha expedicion
		$pdf->Cell(40,4,"Fecha de Expedicion");
		$pdf->Cell(30,4,"Periodo");		
		$pdf->Ln(5);
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,4,"Ordinario",0);
		//Fecha Pago
		$pdf->Cell(50,4,$valor[7],0);
		//Pago Ordinario
		$pdf->Cell(60,4,money_format('$ %!.0i',$valor[8]),0);
		
		//Fecha Expedicion
		$pdf->Cell(30,4,$valor[15],0);
		//Periodo
		$pdf->Cell(10,4,$valor[13]."-".$valor[14]);		
			
			
			
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(5);
		//Tipo Pago
		$pdf->Cell(135,4,"",0);
		//Referencia
		$pdf->Cell(18,4,"Ref.",0);
		//Fecha expedicion
		$pdf->Cell(28,4,"Descripcion");
		//Periodo
		$pdf->Cell(20,4,"Valor");
		$pdf->Ln(5);
		
		//________________________Referencias de pago_______________________________________
		
		if(!is_array($conceptos))
		{
		
			
			$obs_cuota=(isset($obs_cuota)?$obs_cuota:'');
			
			$pdf->SetFont('Arial','B',8);
			//Referencia No 1
			//Espacio
			$pdf->Cell(135,4,"",0);
			//Numero
			$pdf->Cell(10,4,"001",0,0,'R');
			//Espacio
			$pdf->Cell(5,4,"",0);
			//Descripcion
			$pdf->Cell(24,4,"Matricula".$obs_cuota,0);
			//Valor
			$pdf->Cell(20,4,money_format('$ %(!.0i',$valor[19]),0,0,'R');
			
			
			$pdf->Ln(4);
			
			//Referencia No 2
			//Espacio
			$pdf->Cell(135,4,"",0);
			//Numero
			$pdf->Cell(10,4,"002",0,0,'R');
			//Espacio
			$pdf->Cell(5,4,"",0);
			//Descripcion
			$pdf->Cell(24,4,"Seguro",0);

			
			
			//Valor
			if($valor[16]==1)
			{
				$pdf->Cell(20,4,money_format('$ %(!.0i',$valor[17]),0,0,'R');
			}
			else
			{
				$pdf->Cell(20,4,"$ 0",0,0,'R');
			}

			//Valor del Codigo Ordinario
			$pdf->SetFont('Arial','',8);
			//Valores
			$pdf->Ln(5);				
			$pdf->Cell(35,4,$valor[9],0);
			
			$pdf->Ln(6);
		
			
		}
		else
		{
			//Actualmente el formato del recibo soporta hasta cinco (5) conceptos por recibo
			
			for($j=0;$j<5;$j++)
			{
				if(isset($conceptos[$j][0]))
				{
	// 				aer_secuencia
	// 				aer_ano
	// 				aer_bancod
	// 				aer_refcod
	// 				aer_valor
	// 				reb_refnom
					
					
					
					if($j!=3)
					{
						//Espacio
						$pdf->Cell(135,3,"",0);
					}
					else
					{
										
						//Valor del Codigo Ordinario
						$pdf->SetFont('Arial','',8);
						//Valores
						$pdf->Cell(105,4,$valor[9],0);
						$pdf->Cell(30,3,"",0);	
					}
					$pdf->SetFont('Arial','B',7);
					//Numero
					$pdf->Cell(10,3,$conceptos[$j][3],0);
					//Espacio
					$pdf->Cell(5,3,"",0);
					//Descripcion
					$pdf->Cell(24,3,$conceptos[$j][5],0);
					//Valor
					$pdf->Cell(20,3,money_format('$ %(!.0i',$conceptos[$j][4]),0,0,'R');
				}
				else
				{
					if($j!=3)
					{
						//Espacio
						$pdf->Cell(135,3,"",0);
					}
					else
					{
						//Valor del Codigo Ordinario
						$pdf->SetFont('Arial','',8);
						//Valores
						$pdf->Cell(105,4,$valor[9],0);
						$pdf->Cell(30,3,"",0);	
					
					}
				}
				
				
				
				$pdf->Ln(3);
				
			}
		}
		
		
		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		//Tipo Pago
		$pdf->Cell(35,4,"Pago",0);
		//Fecha 1
		$pdf->Cell(50,4,"Fecha Limite",0);
		//Pago Ordinario
		$pdf->Cell(48,4,"TOTAL A PAGAR",0);
		$pdf->SetFont('Arial','',8);
		//Encabezado Observacion
		$pdf->Cell(60,4,"OBSERVACIONES: Unicamente Efectivo",0);
		
		//Valores
		$pdf->Ln(5);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,4,"Extraordinario",0);
		//Fecha Pago
		$pdf->Cell(50,4,$valor[10],0);
		//Pago Extraordinario
		$pdf->Cell(48,4,money_format('$ %!.0i',$valor[11]),0);
		
		if($valor[18] !="")
		{
		  $pdf->Cell(60,4,$valor[18],0);
		}		
		
		
		$pdf->Ln(20);
		//Valor del Codigo Extraordinario
		$pdf->SetFont('Arial','',8);
		$pdf->SetTextColor(50, 50, 50);
		$pdf->Cell(35,4,$valor[12],0);
		
		//Pie del Recibo
		$pdf->Ln(5);
		$pdf->Cell(45,4,"",0);
		$pdf->Cell(95,4,$pie,0);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(35,4,"- Espacio para timbre o sello Banco -",0);
		if(isset($division))
		{
		$pdf->Ln(4);
		$pdf->Cell(140,4,$division,0);
		}
		
	}
	else
	{           
		$offset=0;
		$pdf->Image($configuracion["raiz_documento"].$configuracion["grafico"].'/recibo1.jpg',0,($offset),210);
		$pie="- COPIA ESTUDIANTE -";
		
		$pdf->SetFont('Arial','B',10);		
		$pdf->Ln(-4);
		$pdf->Cell(15,4,"",0);
		//Universidad
		$pdf->Cell(50,4,"UNIVERSIDAD DISTRITAL ",0);
		$pdf->SetFont('Arial','B',8);
                $pdf->Cell(75,4,"",0);
		$pdf->Cell(70,4,"PAGUE UNICAMENTE EN",0);		
		$pdf->Ln(2);
		$pdf->Cell(15,4,"",0);
		//Francsico Jose de Caldas
		$pdf->SetFont('Arial','B',10);		
		$pdf->Cell(40,6,"Francisco Jose de Caldas",0);		
		$pdf->Ln(1);
		$pdf->Cell(70,4,"",0);
		//Comprobante de Pago
		$pdf->Cell(70,4,"COMPROBANTE DE PAGO No ".$valor[2],0);
				
		//Banco		
		$pdf->SetFont('Arial','B',8);
		$pdf->Ln(1);
		$pdf->Cell(140,4,"",0);
		$pdf->Cell(70,4," BANCO DE OCCIDENTE ",0);	
                $pdf->Image($configuracion["raiz_documento"].$configuracion["grafico"].'/logo_occidente.jpg' , 188 ,3, 11 , 13,'JPG', '');
				
		$pdf->Ln(3);
		$pdf->Cell(15,4,"",0);
		$pdf->SetFont('Arial','',8);
		//NIT
		$pdf->Cell(70,4,"NIT 899.999.230.7",0);
				
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',10);
		//Encabezado
		$pdf->Ln(7);
		//Nombre
		$pdf->Cell(76,4,"Nombre del Estudiante",0);
		//Codigo Estudiante
		$pdf->Cell(26,4,"Codigo",0);
		//Documento
		$pdf->Cell(45,4,"Doc. Identidad",0);
		//Carrera
		$pdf->Cell(30,4,"Proyecto Curricular");		
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Ln(5);
		//Nombre
                $pdf->Cell(73,4,utf8_decode($valor[3]),0);
		//Codigo Estudiante
		$pdf->Cell(32,4,$valor[4],0);
		//Documento
		$pdf->Cell(33,4,$valor[5],0);
		//Carrera
		$pdf->Cell(30,4,$valor[6],0);		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(5);
		$pdf->Cell(33,4,"Referencia",0);
		//Fecha expedicion
		$pdf->Cell(62,4,"Descripcion");
		//Periodo
		$pdf->Cell(45,4,"Valor");
		
		//Fecha expedicion
		$pdf->Cell(40,4,"Fecha de Expedicion");
		//Periodo
		$pdf->Cell(30,4,"Periodo");		
		
		
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		
		
		
		//________________________Referencias de pago_______________________________________
		
		if(!is_array($conceptos))
		{
			$pdf->SetFont('Arial','B',8);
			$pdf->Ln(5);
			//Referencia No 1
			//Espacio
			$pdf->Cell(10,4,"",0);
			//Numero
			$pdf->Cell(10,4,"001",0);
			//Descripcion
			$pdf->Cell(60,4,"Matricula",0);
			//Valor
			$pdf->Cell(50,4,money_format('$ %(!.0i',$valor[19]),0,0,'R');
			//Espacio
			$pdf->Cell(15,4,"",0);
			
			
			
			//Fecha Expedicion
			$pdf->Cell(30,4,$valor[15],0);
			//Periodo
			$pdf->Cell(10,4,$valor[13]."-".$valor[14]);				
			
			
			$pdf->Ln(4);
			//Referencia No 2
			//Espacio
			$pdf->Cell(10,4,"",0);
			//Numero
			$pdf->Cell(10,4,"002",0);
			//Descripcion
			$pdf->Cell(60,4,"Seguro",0);
			
			//Valor
			if($valor[16]==1)
			{
				$pdf->Cell(50,4,money_format('$ %(!.0i',$valor[17]),0,0,'R');
			}
			else
			{
				$pdf->Cell(50,4,money_format('$ %(!.0i',0),0,0,'R');
			}
			
			
			//Espacio Final
			$pdf->Cell(15,4,"",0);
			$pdf->Ln(12);
		}
		else
		{
			$j=0;
			$espacioFinal=16;
			
			$pdf->Ln(5);
			//Actualmente el formato del recibo soporta hasta conco (5) conceptos por recibo
			while(isset($conceptos[$j][0]))
			{
// 				aer_secuencia
// 				aer_ano
// 				aer_bancod
// 				aer_refcod
// 				aer_valor
// 				reb_refnom
				
				$pdf->SetFont('Arial','B',7);
				
				//Espacio
				$pdf->Cell(10,4,"",0);
				//Numero
				$pdf->Cell(10,4,$conceptos[$j][3],0);
				//Descripcion
				$pdf->Cell(60,4,$conceptos[$j][5],0);
				//Valor
				$pdf->Cell(50,4,money_format('$ %(!.0i',$conceptos[$j][4]),0,0,'R');
				//Espacio
				$pdf->Cell(15,4,"",0);
				
				if($j==0)
				{				
					$pdf->SetFont('Arial','B',8);
					//Fecha Expedicion
					$pdf->Cell(30,4,$valor[15],0);
					//Periodo
					$pdf->Cell(10,4,$valor[13]."-".$valor[14]);	
				}
				else
				{
					if($j==2)
					{				
						$pdf->SetFont('Arial','B',8);
						//Observacion
						$pdf->Cell(60,4,$valor[18],0);
					}
				
				
				}
				
				$pdf->Ln(3);
				$espacioFinal-=3;
				
				$j++;
				
			}
			//Si existe menos de tres conceptos
			$pdf->SetFont('Arial','B',8);
			while($j<3)
			{
				if($j==2)
				{				
					
					//Observacion
					//Espacio
					$pdf->Cell(135,4,"",0);
					if($valor[18] !="")
					{
						$pdf->Cell(30,4,$valor[18],0);
					}
					else
					{
						$pdf->Cell(30,4,"UNICAMENTE EFECTIVO",0);
					}
				}
				else
				{
					//Salto de Linea
					$pdf->Ln(3);
					$espacioFinal-=3;
				}
				$j++;
			}
			
			//Espacio Final
			$pdf->Ln($espacioFinal);
		
		}
		//__________________Fin Referencias_______________________________________
		
		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		//Tipo Pago
		$pdf->Cell(10,4,"",0);
		$pdf->Cell(30,4,"Tipo de Pago",0);
		//Fecha 1
		$pdf->Cell(45,4,"Pague Hasta",0);
		//Pago Ordinario
		$pdf->Cell(50,4,"TOTAL A PAGAR",0);
		
		//Valores
		$pdf->Ln(5);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(45,4,"Ordinario",0);
		//Fecha Pago
		$pdf->Cell(45,4,$valor[7],0);
		//Pago Ordinario
		$pdf->Cell(60,4,money_format('$ %(!.0i',$valor[8]),0);
		
		//Valores
		$pdf->Ln(5);
		$pdf->Cell(45,4,"Extraordinario",0);
		//Fecha Pago
		$pdf->Cell(45,4,$valor[10],0);
		//Pago Extraordinario
		$pdf->Cell(48,4,money_format('$ %!.0i',$valor[11]),0);
		
		
		
				
		//Pie del Recibo
		$pdf->SetFont('Arial','',8);	
		$pdf->Ln(5);
		$pdf->Cell(45,4,"",0);
		$pdf->Cell(95,4,$pie,0);
		$pdf->Cell(35,4,"- Espacio para timbre o sello Banco -",0);
		
		//$division=".........................................................................................................................Doblar.........................................................................................................................";	
		//$pdf->Ln(4);
		//$pdf->Cell(140,4,$division,0);	
	
	}

}

?>
