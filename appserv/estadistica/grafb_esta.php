<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_bar.php");

require_once('msql_desercion.php');

$SubTit = 'Deserción en el período '.OCIResult($QryEst,5).'-'.OCIResult($QryEst,6);

//Datos de la gráfica
$p=0;
do{
   $dataX[$p] = OCIResult($QryEst,7);
   $dataY[$p] = OCIResult($QryEst,9);
   $p++;
}while(ocifetch($QryEst));

$clr = array(0=>'#CDC673',
			 1=>'#EEC591',
			 2=>'#FFF68F',
			 3=>'#A52A2A');
			 
$som = array(0=>'#736C19',
			 1=>'#946B37',
			 2=>'#A59C35',
			 3=>'#4B0000');
$rand = rand(0, 3);

if($p>=1 && $p<8) $SWG = 0.7;
if($p>=8 && $p<=12) $SWG = 0.6;

// parametros básicos de creación de la gráfica
$graph = new Graph(400,200,'auto');	
$graph->img->SetMargin(40,30,40,40);
$graph->SetScale("textint");
$graph->SetFrame(true,'#669999',1);
$graph->SetColor('#FFFFFF');
$graph->SetMarginColor('#FFFFFF');

$graph->SetShadow();
$graph->img->SetMargin(40,30,20,40);

// Agregar una cierta tolerancia, de modo que la escala no termine exactamente en el valor máximo.
$graph->yaxis->scale->SetGrace(20);

//Configuración de texto en X-axis
//$a = $gDateLocale->GetShortMonth();
$graph->xaxis->SetTickLabels($dataX);
$graph->xaxis->SetFont(FF_FONT1);
$graph->xaxis->SetColor('gray','darkblue');

// Configuración de texto en  y-axis 
$graph->yaxis->SetColor('gray','darkblue');
$graph->ygrid->SetColor('gray');

//Configuración de titulos
$graph->title->Set('Sistema de Información Cóndor');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,13); 
$graph->title->SetColor('darkblue');

$graph->subtitle->Set($SubTit);
$graph->subtitle->SetColor('blue');
$graph->subtitle->SetFont(FF_FONT2,FS_BOLD);

$graph->yaxis->title->Set("Cantidad");
$graph->yaxis->title->SetColor('darkred');
$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);

$graph->xaxis->title->Set("Estados");
$graph->xaxis->title->SetColor('darkred');
$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD);
                              
//Configuración de las barras
$bplot = new BarPlot($dataY);
$bplot->SetFillColor($clr[$rand]);
$bplot->SetColor($clr[$rand]);
$bplot->SetWidth($SWG);
$bplot->SetShadow($som[$rand]);

//Despegar valores sobre las barras
$bplot->value->Show();
//$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot->value->SetFormat('%d');
//Black para valores positivos y darkred para valores negativos
$bplot->value->SetColor("black","darkred");
$graph->Add($bplot);

$graph->Stroke();

OCIFreeCursor($QryEst);
//OCILogOff($oci_conecta);
?>