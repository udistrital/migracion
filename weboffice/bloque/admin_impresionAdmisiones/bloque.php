<?
/*
###########################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion 
###########################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      ultima revision 17 de noviembre de 2010
*****************************************************************************
* @subpackage   admin_recibo
* @package	bloques
* @copyright    
* @version      0.4
* @link		N/D
* @description  Bloque principal para la administracion de recibos de estudiantes de primer semestre
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
$accesoOracle=$conexion->recursodb($configuracion,"admisiones");
$enlace=$accesoOracle->conectar_db();

$acceso_db=$conexion->recursodb($configuracion,"");
$enlace=$acceso_db->conectar_db();

//Rescatar los datos generales
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
$datoBasico=new datosGenerales();

$perAcad=$_REQUEST["periodo"];
$valor[3]=$perAcad;
$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"periodoacad");
$anoperiodo=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	

$anno=$anoperiodo[0][0];
$periodo=$anoperiodo[0][1];
//echo $_REQUEST["fecha"];
if(isset($_REQUEST["carrera"]))
{
	$valor[0]=$_REQUEST["carrera"];
	$valor[1]=$anno;
	$valor[2]=$periodo;
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosActual");
	$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
}
if(isset($_REQUEST["fecha"]))
{
	$valor[5]=$_REQUEST["fecha"];
	$valor[1]=$anno;
	$valor[2]=$periodo;
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosActualFecha");
	$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	//echo "mmm".$cadena_sql;
}

if(isset($_REQUEST["credencial"]))
{
	$valor[4]=$_REQUEST["credencial"];
	$valor[1]=$anno;
	$valor[2]=$periodo;
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosActualCred");
	$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
	//echo "mmm".$cadena_sql;
}

if(!is_array($registro))
{	
	
	$cadena="<p>En la actualidad no tiene ning&uacute;n recibo registrado para impresi&oacute;n.</p>";
	$cadena.="<p>Por favor generen los recibos a trav&eacute;s del formulario disponible para admisiones.</p>";
	alerta::sin_registro($configuracion,$cadena);	
}
else
{
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
	//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/pagegroup.php");
	
	//Objetos
	$codigoBarras= new codigoBarras($configuracion);	
	$cripto=new encriptar();
	$pdf=new FPDF('P','mm','letter');
	//$pdf2=new PDF_PageGroup;
	//Variables
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	//Variables para el codigo de barras
	$funcion1="FN";
	$ia1='415';
	$ia2='8020';
	$ia3='3900';
	$ia4='96';
	$codigoInstitucion='7709998000421';
	
	//echo $campos;
	//$pdf2->StartPageGroup();
		
	for($i=0; $i<=$campos-1; $i++)
	{
		//Rescatar los valores de la factura
		$codigoEstudiante=$registro[$i][1];
		$documento=$registro[$i][12];
		$nombre=$registro[$i][13];
		$nombreCarrera=$registro[$i][14];
		
		//Rescatar identificador de la factura  (SEQ_PRUEBA)(ACESTMATPRUEBA) (MATRECIBOS)
		//$codigoConsecutivo=obtenerCodigo();
		$codigoConsecutivo=$registro[$i][0];
		
		//Rescatar fecha de pago ordinario 8 Digitos yyyymmdd	
		$fechaPago=$registro[$i][10];
		
		//Rescatar fecha de pago extraordinario 8 Digitos yyyymmdd	
		$fechaPagoExtra=$registro[$i][11];
		
		//Matricula Ordinaria
		
		$valorMatricula=$registro[$i][3];	
		$valorMatriculaExtra=$registro[$i][4];
		
		$perAcad=$_REQUEST["periodo"];
		$valor[3]=$perAcad;
		$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"periodoacad");
		$anoperiodo=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
		
		$anno=$anoperiodo[0][0];
		$periodo=$anoperiodo[0][1];
				
		//Si la secuencia esta registrada en ACREFEST entonces calculamos el pago basados en dicha informacion
		//echo $codigoConsecutivo."<br>";
		$valor[0]=$codigoConsecutivo;//esta consulta hay que modificarla
		$valor[1]=$anno;
		$valor[2]=$periodo;
		$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"conceptosActual");
		$registroConceptos=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
		
		//echo $cadena_sql;
		
		if(is_array($registroConceptos))
		{	
				
			$otrosConceptos=0;
			$conceptos=0;
			
			//Matricula
			$j=0;
			while(isset($registroConceptos[$j][0]))
			{
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
			//Si es la primera cuota
			if($registro[$i][7]==1)
			{
				$valorSeguro=5300;
				$valorPagar=$valorMatricula+$valorSeguro;
			}
			else
			{
				$valorSeguro=0;
				$valorPagar=$valorMatricula;
			
			}
			
			//Calcular matricula extraordinario
			
			if($registro[$i][7]==1)
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
		
		
		
		//Etiqueta del Codigo Ordinario
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
		
		
		//Pago Extraordinario a codificar
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
		$valor[13]=$registro[$i][5];
		$valor[14]=$registro[$i][6];
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
		$parametro[1]=$anno;
		//$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $parametro,"datosSolicitud");
		//$registroObservacion=ejecutar_admin_recibo($cadena_sql,$acceso_db,"busqueda");	
		$registroObservacion=$registro[$i][15];
		if(is_array($registro))
		{
			$valor[18]=$registroObservacion." - No aplica pago extraordinario";
		}
		else
		{
			$valor[18]=$registroObservacion;
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
	}
	$pdf->Output();
}



function cadena_busqueda_recibo($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{
		case "periodoacad":
			$cadena_sql="SELECT ";
			$cadena_sql.="ape_ano, ";
			$cadena_sql.="ape_per, ";
			$cadena_sql.="ape_estado ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acasperiadm ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="ape_estado='".$valor[3]."' "; 
			//echo $cadena_sql."<br>";
			break;
			
		case "recibosActual":	
			
			$cadena_sql="SELECT ";
			$cadena_sql.="ama_secuencia, ";
			$cadena_sql.="ama_codigo, ";
			$cadena_sql.="ama_cra_cod, ";
			$cadena_sql.="ama_valor, ";
			$cadena_sql.="ama_ext, ";
			$cadena_sql.="ama_ano, ";
			$cadena_sql.="ama_per, ";
			$cadena_sql.="ama_cuota, ";
			$cadena_sql.="TO_CHAR(AMA_FECHA, 'YYYYMMDD'), ";
			$cadena_sql.="ama_estado, ";
			$cadena_sql.="TO_CHAR(AMA_FECHA_ORD, 'YYYYMMDD'), ";
			$cadena_sql.="TO_CHAR(AMA_FECHA_EXT, 'YYYYMMDD'), ";
			$cadena_sql.="asp_nro_iden, ";
			$cadena_sql.="(LTRIM(RTRIM(ASP_APELLIDO)))||' '||(LTRIM(RTRIM(ASP_NOMBRE))) nombre, ";
			$cadena_sql.="cra_abrev, ";
			$cadena_sql.="ama_asp_cred ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACADMMAT, ";
			$cadena_sql.="ACASP, ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="AMA_CRA_COD = ".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ASP_APE_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ASP_APE_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ESTADO='A' ";	
			$cadena_sql.="AND ";
			$cadena_sql.="ama_asp_cred = asp_cred ";
			$cadena_sql.="AND ";
			$cadena_sql.="ama_cra_cod = cra_cod";
			//$cadena_sql.="AND ";
			//$cadena_sql.="EMA_IMP_RECIBO=0";	
			//echo $cadena_sql;
			break;
		 case "recibosActualFecha":	
			$cadena_sql="SELECT ";
			$cadena_sql.="ama_secuencia, ";
			$cadena_sql.="ama_codigo, ";
			$cadena_sql.="ama_cra_cod, ";
			$cadena_sql.="ama_valor, ";
			$cadena_sql.="ama_ext, ";
			$cadena_sql.="ama_ano, ";
			$cadena_sql.="ama_per, ";
			$cadena_sql.="ama_cuota, ";
			$cadena_sql.="TO_CHAR(AMA_FECHA, 'YYYYMMDD'), ";
			$cadena_sql.="ama_estado, ";
			$cadena_sql.="TO_CHAR(AMA_FECHA_ORD, 'YYYYMMDD'), ";
			$cadena_sql.="TO_CHAR(AMA_FECHA_EXT, 'YYYYMMDD'), ";
			$cadena_sql.="asp_nro_iden, ";
			$cadena_sql.="(LTRIM(RTRIM(ASP_APELLIDO)))||' '||(LTRIM(RTRIM(ASP_NOMBRE))) nombre, ";
			$cadena_sql.="cra_abrev, ";
			$cadena_sql.="ama_asp_cred ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACADMMAT, ";
			$cadena_sql.="ACASP, ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			//$cadena_sql.="AMA_CRA_COD = ".$valor[0]." ";
			//$cadena_sql.="AND ";
			$cadena_sql.="ASP_APE_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ASP_APE_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ESTADO='A' ";	
			$cadena_sql.="AND ";
			$cadena_sql.="TO_CHAR(AMA_FECHA, 'DD/MM/YYYY') = '".$valor[5]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.="ama_asp_cred = asp_cred ";
			$cadena_sql.="AND ";
			$cadena_sql.="ama_cra_cod = cra_cod ";
			//$cadena_sql.="AND ";
			//$cadena_sql.="EMA_IMP_RECIBO=0";	
			//echo $cadena_sql;
			break;   
		 case "recibosActualCred":	
			$cadena_sql="SELECT ";
			$cadena_sql.="ama_secuencia, ";
			$cadena_sql.="ama_codigo, ";
			$cadena_sql.="ama_cra_cod, ";
			$cadena_sql.="ama_valor, ";
			$cadena_sql.="ama_ext, ";
			$cadena_sql.="ama_ano, ";
			$cadena_sql.="ama_per, ";
			$cadena_sql.="ama_cuota, ";
			$cadena_sql.="TO_CHAR(AMA_FECHA, 'YYYYMMDD'), ";
			$cadena_sql.="ama_estado, ";
			$cadena_sql.="TO_CHAR(AMA_FECHA_ORD, 'YYYYMMDD'), ";
			$cadena_sql.="TO_CHAR(AMA_FECHA_EXT, 'YYYYMMDD'), ";
			$cadena_sql.="asp_nro_iden, ";
			$cadena_sql.="(LTRIM(RTRIM(ASP_APELLIDO)))||' '||(LTRIM(RTRIM(ASP_NOMBRE))) nombre, ";
			$cadena_sql.="cra_abrev, ";
			$cadena_sql.="ama_asp_cred ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACADMMAT, ";
			$cadena_sql.="ACASP, ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			//$cadena_sql.="AMA_CRA_COD = ".$valor[0]." ";
			//$cadena_sql.="AND ";
			$cadena_sql.="ASP_APE_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ASP_APE_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ESTADO='A' ";	
			$cadena_sql.="AND ";
			$cadena_sql.="AMA_ASP_CRED = '".$valor[4]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.="ama_asp_cred = asp_cred ";
			$cadena_sql.="AND ";
			$cadena_sql.="ama_cra_cod = cra_cod ";
			//$cadena_sql.="AND ";
			//$cadena_sql.="EMA_IMP_RECIBO=0";	
			//echo $cadena_sql;
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
			$cadena_sql.="acrefadm ";
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
			//echo $cadena_sql."<br>";
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
	//echo $cadena_sql."</p>";
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
		$pdf->Cell(40,4,"Francisco José de Caldas",0);		
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
		$pdf->Cell(26,4,"Código",0);
		//Documento
		$pdf->Cell(45,4,"Doc. Identidad",0);
		//Carrera
		$pdf->Cell(30,4,"Proyecto Curricular");		
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Ln(5);
		//Nombre
		$pdf->Cell(73,4,UTF8_DECODE($valor[3]),0);
		//Codigo Estudiante
		$pdf->Cell(32,4,$valor[4],0);
		//Documento
		$pdf->Cell(29,4,$valor[5],0);
		//Carrera
		$pdf->Cell(30,4,UTF8_DECODE($valor[6]),0);		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(5);
		//Tipo Pago
		$pdf->Cell(35,4,"Pago",0);
		//Fecha 1
		$pdf->Cell(50,4,"Fecha Límite",0);
		//Pago Ordinario
		$pdf->Cell(50,4,"TOTAL A PAGAR",0);
		//Fecaha expedicion
		$pdf->Cell(40,4,"Fecha de Expedición");
		//Periodo
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
		$pdf->Cell(28,4,"Descripción");
		//Periodo
		$pdf->Cell(20,4,"Valor");
		$pdf->Ln(5);
		
		//________________________Referencias de pago_______________________________________
		
		if(!is_array($conceptos))
		{
		
			
			$pdf->SetFont('Arial','B',8);
			//Referencia No 1
			//Espacio
			$pdf->Cell(135,4,"",0);
			//Numero
			$pdf->Cell(10,4,"001",0,0,'R');
			//Espacio
			$pdf->Cell(5,4,"",0);
			//Descripcion
			$pdf->Cell(24,4,"Matrícula",0);
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
					$pdf->Cell(24,3,UTF8_DECODE($conceptos[$j][5]),0);
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
		$pdf->Cell(50,4,"Fecha Límite",0);
		//Pago Ordinario
		$pdf->Cell(48,4,"TOTAL A PAGAR",0);
		$pdf->SetFont('Arial','',8);
		//Encabezado Observacion
		$pdf->Cell(60,4,"Pago únicamente en efectivo",0);
				
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
		$pdf->Ln(3);
		$pdf->Cell(15,4,"",0);
		//Francsico Jose de Caldas
		$pdf->Cell(40,4,"Francisco José de Caldas",0);		
		$pdf->Ln(1);
		$pdf->Cell(70,4,"",0);
		//Comprobante de Pago
		$pdf->Cell(70,4,"COMPROBANTE DE PAGO No ".$valor[2],0);
				
		//Banco		
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(70,4,"PAGUE EN BANCO DE OCCIDENTE ",0);		
				
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
		$pdf->Cell(26,4,"Código",0);
		//Documento
		$pdf->Cell(45,4,"Doc. Identidad",0);
		//Carrera
		$pdf->Cell(30,4,"Proyecto Curricular");		
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Ln(5);
		//Nombre
		$pdf->Cell(73,4,$valor[3],0);
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
		$pdf->Cell(62,4,"Descripción");
		//Periodo
		$pdf->Cell(45,4,"Valor");
		
		//Fecha expedicion
		$pdf->Cell(40,4,"Fecha de Expedición");
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
			$pdf->Cell(60,4,"Matrícula",0);
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
				$pdf->Cell(60,4,UTF8_DECODE($conceptos[$j][5]),0);
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
						$pdf->Cell(30,4,"PAGO ÚNICAMENTE EFECTIVO",0);
						
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
				
		$pdf->Cell(60,4,"  - PAGO ÚNICAMENTE EFECTIVO",0);
				
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
