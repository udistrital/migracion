<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_pie.php");
require_once("../jpgraph/jpgraph_pie3d.php");

require_once('msql_desercion.php');
$SubTit = 'Deserción en el período '.OCIResult($QryEst,5).'-'.OCIResult($QryEst,6);
ob_start();
// datos de la gráfica
$p=0;
do{
   $dataX[$p]=OCIResult($QryEst, 8);
   $dataY[$p]=OCIResult($QryEst, 9);
   $p++;
}while(ocifetch($QryEst));

// parametros básicos de creación de la gráfica
$graph = new PieGraph(580,270,"auto");
$graph->SetShadow();

// Configuración de titulos
$graph->title->Set('Sistema de Información Cóndor');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,13); 
$graph->title->SetColor("darkblue");
$graph->legend->Pos(0.1,0.2);
$graph->subtitle->Set($SubTit);
$graph->subtitle->SetColor('blue');
$graph->subtitle->SetFont(FF_VERDANA,FS_BOLD);

// background
$graph->SetBackgroundImage('maca.png',BGIMG_FILLPLOT);

// Creación del pie 3D
$p1 = new PiePlot3d($dataY);
$p1->SetTheme("sand");
$p1->SetCenter(0.4);
$p1->SetSize(140);

// Adjuste ángulo
$p1->SetAngle(45);

$p1->SetStartAngle(45);
$p1->ExplodeSlice(3);

// Valores
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->SetFont(FF_ARIAL,FS_BOLD,9);
$p1->value->SetColor("navy");
$p1->value->SetFormat('%1.3f%%');

//Configuaración de la convención
$p1->SetLegends($dataX);
$p1->value->SetColor('darkblue');
$graph->legend->Pos(0.03,0.68);
$graph->legend->SetShadow(true);

$graph->Add($p1);
$graph->Stroke();

ob_end_flush();
OCIFreeCursor($QryEst);
OCILogOff($oci_conecta);
?>