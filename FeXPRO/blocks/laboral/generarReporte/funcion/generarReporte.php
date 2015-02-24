<?php
$idBuscado = $_REQUEST["idBuscado"];
$conexion = "oracle";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $idBuscado);
$cadena_sql = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$resultado = $cadena_sql[0];

//echo  $resultado['PRIMER_APELLIDO'].'<br>';
//echo utf8_decode($resultado['PRIMER_APELLIDO']).'<br>';
//echo utf8_encode($resultado['PRIMER_APELLIDO']).'<br>';

//
//SELECT * FROM v_datos_certificacion where CARGO = 'PENSIONADO DOC' OR CARGO = 'PENSIONADO ADM'
if($resultado['FECHA_RETIRO']==NULL){
//var_dump($resultado);
//if($resultado['CARGO']!='PENSIONADO DOC'|| ['CARGO']!='PENSIONADO ADM'){

	if($_REQUEST["promedioMensual"]=="on" && $_REQUEST["basico"]=="on" && $_REQUEST["sueldoBasico"]=="on" && $_REQUEST["mensualDiscriminado"]=="on"){
		$this->generarPDF9($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["promedioMensual"]=="on" && $_REQUEST["basico"]=="on" && $_REQUEST["sueldoBasico"]=="on"){
		$this->generarPDF5($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["promedioMensual"]=="on" && $_REQUEST["basico"]=="on" && $_REQUEST["mensualDiscriminado"]=="on"){
		$this->generarPDF10($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["sueldoBasico"]=="on" && $_REQUEST["basico"]=="on" && $_REQUEST["mensualDiscriminado"]=="on"){
		$this->generarPDF11($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["sueldoBasico"]=="on" && $_REQUEST["promedioMensual"]=="on" && $_REQUEST["mensualDiscriminado"]=="on"){
		$this->generarPDF12($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["promedioMensual"]=="on" && $_REQUEST["basico"]=="on"){
		$this->generarPDF6($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["basico"]=="on" && $_REQUEST["sueldoBasico"]=="on"){
		$this->generarPDF7($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["promedioMensual"]=="on" && $_REQUEST["mensualDiscriminado"]=="on"){
		$this->generarPDF13($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["promedioMensual"]=="on" && $_REQUEST["sueldoBasico"]=="on"){
		$this->generarPDF8($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}elseif($_REQUEST["mensualDiscriminado"]=="on" && $_REQUEST["basico"]=="on"){
		$this->generarPDF14($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
	}else{
		if($_REQUEST['promedioMensual'] == "on"):
		$this->generarPDF($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
		elseif($_REQUEST['basico'] == "on"):
		$this->generarPDF2($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
		elseif($_REQUEST['mensualDiscriminado'] == "on"):
		$this->generarPDF15($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
		else:
		$this->generarPDF3($cadena_sql);
		$pdf = new PDF();
		$pdf->crearPDF($cadena_sql[0]);
		endif;
	}
}else{
	//echo "No ingreso";exit;
	$this->generarPDF4($cadena_sql);
	$pdf = new PDF();
	$pdf->crearPDF($cadena_sql[0]);;
}
?>