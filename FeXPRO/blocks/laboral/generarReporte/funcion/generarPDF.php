<?php
$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque"); //Because esteBloque is an array OK
require_once($this->miConfigurador->getVariableConfiguracion("raizDocumento")."/blocks/".$esteBloque["grupo"]."/".$esteBloque["nombre"].'/librerias/fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
?>