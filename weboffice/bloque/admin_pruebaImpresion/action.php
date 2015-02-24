<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      Ãšltima revisiÃ³n 2 de junio de 2007
*****************************************************************************
* @subpackage   admin_recibo
* @package	bloques
* @copyright    
* @version      0.3
* @link		N/D
* @description  Bloque principal para la administraciÃ³n de medicamentoes
*
******************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
//Se incluye para manejar los mensajes de error
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");


//Pagina a donde direcciona el menu
$pagina="registro_recibo";

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	$nueva_sesion=new sesiones($configuracion);
	$nueva_sesion->especificar_enlace($enlace);
	$esta_sesion=$nueva_sesion->numero_sesion();
	//Rescatar el valor de la variable usuario de la sesion
	$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"usuario");
	if($registro)
	{
		
		$el_usuario=$registro[0][0];
	}
	$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
	if($registro)
	{
		
		$usuario=$registro[0][0];
	}
	
	//Rescatar los recibos que se encuentran en proceso de impresion
	$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $usuario,"completa");
	$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);
	
	if(!is_array($registro))
	{	
		
		$cadena="En la actualidad esta Coordinaci&oacute;n no tiene ning&uacute;n recibo registrado para impresi&oacute;n.";
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{
		
		$campos=count($registro);
		
		con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db);
		
	}
}



/****************************************************************
*  			Funciones				*
****************************************************************/



function con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db)
{
	//Clases necesarias
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/fpdf.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/codigoBarras.class.php");
		
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
	
	
	//Recorrer las solicitudes
	for($contador=0;$contador<$campos;$contador++)
	{
		//Rescatar los valores de la factura
		$codigoEstudiante=$registro[$contador][2];
		$nombre=$registro[$contador][5];
		$documento=$registro[$contador][7];
		$nombreCarrera=$registro[$contador][8];
		
		//Rescatar identificador de la factura  (SEQ_PRUEBA)(ACESTMATPRUEBA) (MATRECIBOS - abc123)
		//$codigoConsecutivo=obtenerCodigo();
		$codigoConsecutivo=999989+$contador;
		//Rescatar fecha de pago ordinario 8 Digitos yyyymmdd	
		//$fechaPago=obtenerPago();
		$fechaPago='20081120';
		//Rescatar fecha de pago extraordinario 8 Digitos yyyymmdd	
		//$fechaPagoExtra=obtenerPago();
		$fechaPagoExtra='20081121';
		
		//Calcular matricula Ordinaria
		$valor[0]=$codigoEstudiante;
		$valor[1]=$registro[$contador][6];
		
		$matricula=calcular_matricula($configuracion, $acceso_db, $valor);		
		$valorMatricula=$matricula[0];
		//Calcular matricula extraordinario
		$valorMatriculaExtra=round($valorMatricula*1.2,0);
		//Calcular otros pagos
		$pago=calcular_pago();
		
		//Calcular valor a pagar: valorMatricula+OtrosPagos
		$otroPago=0;
		if(is_array($pago))
		{
			$i=0;			
			while($pago[$i])
			{
				$otroPago+=$pago[$i++][1];
			}
		
		}
		$valorPagar=$valorMatricula+$otroPago;
		$valorPagarExtra=$valorMatricula+$otroPago;
		
		
		
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
		$imagenCodigo=generarCodigoBarras($codigoBarras, $codigo, $configuracion,$valor);
		
		$imagenCodigoExtra=generarCodigoBarras($codigoBarras, $codigoExtraordinario, $configuracion,$valor);
		
		//Valores de Impresion
		$valor[0]=$imagenCodigo;
		$valor[1]=$imagenCodigoExtra;
		$valor[2]=$codigoConsecutivo;
		$valor[3]=$nombre;
		$valor[4]=$codigoEstudiante;
		$valor[5]=$documento;
		$valor[6]=$nombreCarrera;
		$valor[7]=$fechaPago;
		$valor[8]=$valorPagar;
		$valor[9]=$etiquetaCodigo;
		$valor[10]=$fechaPagoExtra;
		$valor[11]=$valorPagarExtra;
		$valor[12]=$etiquetaCodigoExtra;
		
		$pdf->AddPage();
				
		//Copia Estudiante
		$salida=prepararPDF($configuracion,$pdf,$valor,$pago, "estudiante");
		
		//Copia Proyecto Curricular
		$salida=prepararPDF($configuracion,$pdf,$valor,$pago, "proyecto");
		
		//Copia Banco
		$salida=prepararPDF($configuracion,$pdf, $valor,$pago, "banco");
		
		
		
		
		
		
		
		
	}
	$pdf->Output();
}

function estadistica($configuracion,$contador)
{?><table style="text-align: left;" border="0"  cellpadding="5" cellspacing="0" class="bloquelateral" width="100%">
	<tr>
		<td >
			<table cellpadding="10" cellspacing="0" align="center">
				<tr class="bloquecentralcuerpo">
					<td valign="middle" align="right" width="10%">
						<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/info.png" border="0" />
					</td>
					<td align="left">
						Actualmente hay <b><? echo $contador ?> usuarios</b> registrados.
					</td>
				</tr>
				<tr class="bloquecentralcuerpo">
					<td align="right" colspan="2" >
						<a href="<?
						echo $configuracion["site"].'/index.php?page='.enlace('admin_dir_dedicacion').'&registro='.$_REQUEST['registro'].'&accion=1&hoja=0&opcion='.enlace("mostrar").'&admin='.enlace("lista"); 
						
						?>">Ver m&aacute;s informaci&oacute;n >></a>
					</td>
				</tr>
			</table> 
		</td>
	</tr>  
</table>
<?}



function calcular_matricula($configuracion, $acceso_db, $valores)
{
	//1. Verificar pago inicial y reliquidado
	$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"matricula");
	$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);	
	if(is_array($registro))
	{
		$valor_original=$registro[0][1];
		$valor_matricula=$registro[0][2];
		
		$valor_reliquidado=$valor_matricula;		
		unset($registro);
		
		//2. Rescatar exenciones del estudiante
		$descripcion="";
		$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"exencion");		
		$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);
		if(is_array($registro))
		{
			
			//3. Calcular el pago de acuerdo a las exenciones y construir las observaciones
			for($i=0;$i<count($registro);$i++)
			{
				$esta_exencion=(100-$registro[$i][7])/100;
				$valor_matricula=$valor_matricula*$esta_exencion;
				$descripcion=$descripcion." ".$registro[$i][8];
			}
			
		}
		
		//Devolver los valores calculados		
		$matricula[0]=$valor_matricula;
		$matricula[1]=$descripcion;
		$matricula[2]=$valor_original;
		$matricula[3]=$valor_reliquidado;
		
		return $matricula;
			
	}
	

}


function calcular_pago()
{
	//Rescatar las referencias de pago del estudiante para el periodo
	//TODO
	
	//Devolver los valores de las referencias
	/*for()
	{
	$pago[][]=
	}
	*/
	$pago=0;
	return $pago;

}

function cadena_busqueda_recibo($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{
		case "completa":	
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo.id_solicitud_recibo, ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo.id_usuario, ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo.codigo_est, ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo.estado, ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo.fecha, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante.nombre, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante.id_carrera, ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante.documento, ";
			$cadena_sql.=$configuracion["prefijo"]."programa.nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante, ";  
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo, ";
			$cadena_sql.=$configuracion["prefijo"]."programa ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo.codigo_est=".$configuracion["prefijo"]."estudiante.codigo_est ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."estudiante.id_carrera=".$configuracion["prefijo"]."programa.id_programa ";
			
			if(isset($_REQUEST["accion"]))
			{
				
				$variable="";
				
				reset ($_REQUEST);
				while (list ($clave, $val) = each ($_REQUEST)) 
				{
					
					if($clave!='pagina')
					{
						$variable.="&".$clave."=".$val;
						//echo $clave;
					}
				}		
				
				switch($_REQUEST["accion"])
				{	
					case '1':				
						$cadena_sql.="AND ";
						$cadena_sql.=$configuracion["prefijo"]."solicitud_recibo.id_usuario=".$valor." ";
						$cadena_sql.="AND ";
						$cadena_sql.=$configuracion["prefijo"]."solicitud_recibo.estado<2 "; 
					
						break;
						
					//Todas los servicios que cumplan con el criterio de busqueda
					case '2':
							
						
						break;
						
								
					
					
					default:
						break;
							
					
				}
			}
			else
			{
				
				
			}
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

function ejecutar_admin_recibo($cadena_sql,$acceso_db)
{
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	return $registro;
}

function generarCodigoBarras($codigoBarras, $codigo, $configuracion,$valor)
{
	$codigoBarras->altoSimbolo(50);
	//$bar->setFont("arial");
	$codigoBarras->escalaSimbolo(0.5);
	$codigoBarras->colorSimbolo("#000000","#FFFFFF");

	$elCodigo=$codigo;
	$return = $codigoBarras->generar($codigo,'png',$configuracion["raiz_documento"].$configuracion["documento"]."/".$elCodigo);
	if($return==false)
	{
		$codigoBarras->error(true);
	}
	return $elCodigo;
}


function prepararPDF($configuracion,$pdf, $valor,$pago,$tipo="")
{

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
		$pdf->Cell(29,4,$valor[5],0);
		//Carrera
		$pdf->Cell(30,4,$valor[6],0);		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(5);
		//Tipo Pago
		$pdf->Cell(30,4,"Pago",0);
		//Fecha 1
		$pdf->Cell(55,4,"Fecha Ordinaria",0);
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
		$pdf->Cell(50,4,substr($valor[7],0,4)."/".substr($valor[7],4,2)."/".substr($valor[7],6),0);
		//Pago Ordinario
		$pdf->Cell(60,4,money_format('$ %!.0i',$valor[8]),0);
		//Fecha Expedicion
		$pdf->Cell(45,4,date("m/d/Y ",time()),0);		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(5);
		//Tipo Pago
		$pdf->Cell(135,4,"",0);
		//Referencia
		$pdf->Cell(18,4,"Ref.",0);
		//Fecaha expedicion
		$pdf->Cell(28,4,"Descripción");
		//Periodo
		$pdf->Cell(20,4,"Valor");
		
		//Valores de las referencias
		
		$pdf->SetFont('Arial','',8);
		$pdf->Ln(4);
		//Referencia 1
		//Tipo Pago
		$pdf->Cell(135,4,"",0);
		//Referencia
		$pdf->Cell(18,4,"116",0);
		//Fecaha expedicion
		$pdf->Cell(28,4,"Matrícula");
		//Periodo
		$pdf->Cell(20,4,"$ 1");
		
		$pdf->Ln(3);
		//Referencia 2
		//Tipo Pago
		$pdf->Cell(135,4,"",0);
		//Referencia
		$pdf->Cell(18,4,"1",0);
		//Fecaha expedicion
		$pdf->Cell(28,4,"Seguro");
		//Periodo
		$pdf->Cell(20,4,"$ 0");
		
		$pdf->Ln(3);
		//Referencia 3
		//Tipo Pago
		$pdf->Cell(135,4,"",0);
		//Referencia
		$pdf->Cell(18,4,"42",0);
		//Fecaha expedicion
		$pdf->Cell(28,4,"Carnet");
		//Periodo
		$pdf->Cell(20,4,"$ 0");
		
		$pdf->Ln(3);
		//Referencia 4
		//Tipo Pago
		$pdf->Cell(135,4,"",0);
		//Referencia
		$pdf->Cell(18,4,"43",0);
		//Fecaha expedicion
		$pdf->Cell(28,4,"Sistematización");
		//Periodo
		$pdf->Cell(20,4,"$ 0");
		
		
		//Valor del Codigo Ordinario
		$pdf->SetFont('Arial','',8);
		$pdf->Ln(1);
		//Valores
		$pdf->Cell(35,4,$valor[9],0);
		
		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(6);
		//Tipo Pago
		$pdf->Cell(30,4,"Pago",0);
		//Fecha 1
		$pdf->Cell(55,4,"Fecha Ordinaria",0);
		//Pago Ordinario
		$pdf->Cell(48,4,"TOTAL A PAGAR",0);
		$pdf->SetFont('Arial','',8);
		//Encabezado Observacion
		$pdf->Cell(60,4,"OBSERVACIONES:",0);
		
		//Valores
		$pdf->Ln(5);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(35,4,"Extraordinario",0);
		//Fecha Pago
		$pdf->Cell(50,4,substr($valor[10],0,4)."/".substr($valor[10],4,2)."/".substr($valor[10],6),0);
		//Pago Extraordinario
		$pdf->Cell(48,4,money_format('$ %!.0i',$valor[11]),0);
		
		
		
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
		//Fecaha expedicion
		$pdf->Cell(62,4,"Descripción");
		//Periodo
		$pdf->Cell(45,4,"Valor");
		
		//Fecha expedicion
		$pdf->Cell(40,4,"Fecha de Expedición");
		//Periodo
		$pdf->Cell(30,4,"Periodo");		
		$pdf->Ln(5);
		
		//Valores
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial','B',8);
		
		
		//Fecha Expedicion
		$pdf->Cell(145,4,"",0);
		$pdf->Cell(45,4,date("m/d/Y ",time()),0);		
		
		
		//Encabezado
		$pdf->SetTextColor(50, 50, 50);
		$pdf->SetFont('Arial','B',9);
		$pdf->Ln(16);
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
		$pdf->Cell(50,4,substr($valor[7],0,4)."/".substr($valor[7],4,2)."/".substr($valor[7],6),0);
		//Pago Ordinario
		$pdf->Cell(60,4,money_format('$ %!.0i',$valor[8]),0);
		
		//Valores
		$pdf->Ln(5);
		$pdf->Cell(45,4,"Extraordinario",0);
		//Fecha Pago
		$pdf->Cell(50,4,substr($valor[10],0,4)."/".substr($valor[10],4,2)."/".substr($valor[10],6),0);
		//Pago Extraordinario
		$pdf->Cell(48,4,money_format('$ %!.0i',$valor[11]),0);
		
		
		
				
		//Pie del Recibo
		$pdf->Ln(5);
		$pdf->Cell(45,4,"",0);
		$pdf->Cell(95,4,$pie,0);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(35,4,"- Espacio para timbre o sello Banco -",0);
		
		//$division=".........................................................................................................................Doblar.........................................................................................................................";	
		//$pdf->Ln(4);
		//$pdf->Cell(140,4,$division,0);	
	
	}

}

?>

