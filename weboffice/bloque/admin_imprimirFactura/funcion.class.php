<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionRegistro.class.php");

class funciones_generarFactura implements funcionRegistro
{

	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
	}
	
	function informacionGeneral($configuracion)
	{
		//El objetivo de esta funcion es determinar los valores de los diferentes campos que se deben tener para la elaboracion del recibo
		//Se requiere:
		// Matriz general:	
		//[0] Numero de factura
		//[1] Nombre del Pagador
		//[2] Identificacion del pagador
		//[3] Codigo interno del pagador (si aplica)
		//[4] Nombre del Proyecto Curricular (si aplica)
		//[5] Valor Total ordinario a pagar
		//[6] Valor Total extraordinario a pagar
		//[7] Fecha limite de pago ordinario
		//[8] Fecha Limite de pago extraordinario
		
		
		
	}
	
	function detallePago()
	{
		
		
		//Matriz detallePago
		//[ i ][0] Referencia
		//[ i ][1] Descripcion
		//[ i ][2] Valor
		
	}
	
	
	function codigoBarras()
	{
		
		//Matriz codigoBarras
		//[0] Ruta a imagen Codigo de Barras pago Ordinario
		//[1] Ruta a imagen Codigo de Barras pago Extraordinario
		//[2] Etiqueta imagen Codigo de Barras pago Ordinario
		//[3] Etiqueta imagen Codigo de Barras pago Extraordinario
		
		//Matriz general
	
	}
	
	function imagenCodigoBarras($configuracion,$codigo)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/codigoBarras.class.php");
		$this->codigoBarras= new codigoBarras($configuracion);	

		
		$this->codigoBarras->altoSimbolo(50);
		//$bar->setFont("arial");
		$this->codigoBarras->escalaSimbolo(0.5);
		$this->codigoBarras->colorSimbolo("#000000","#FFFFFF");
	
		$elCodigo=$codigo;
		
		$return = $codigoBarras->generar($codigo,'png',$configuracion["raiz_documento"].$configuracion["documento"]."/".$elCodigo);
		
		if($return==false)
		{
			$codigoBarras->error(true);
		}
		return $elCodigo;
	}

}

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

?>

