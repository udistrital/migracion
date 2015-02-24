<?php
//echo "<br>hola";exit;
require('usr/local/apache/htdocs/academicopro/clase/pdf_sab_notas/pdf/mpdf.php');
$mpdf=new mPDF('','LETTER',18,'ARIAL',3,0,38,25,7,12);
$var1=$mpdf;
$mpdf->AddPage();
$mpdf->SetFont('Arial','B',16);
$mpdf->Cell(40,10,'¡Hola, Mundo!');
 $mpdf->Output('archivo.pdf','D');
?>