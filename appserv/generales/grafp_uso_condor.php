<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_pie.php");
require_once("../jpgraph/jpgraph_pie3d.php");

require_once('msql_uso_condor.php');

ob_start();
// datos de la gr�fica
$p=0;
do{
   $dataX[$p]=OCIResult($QryUsoC, 2).' '.OCIResult($QryUsoC, 3);
   $dataY[$p] =OCIResult($QryUsoC, 3)/1000;
   $p++;
}while(ocifetch($QryUsoC));

// parametros b�sicos de creaci�n de la gr�fica
$graph = new PieGraph(520,270,"auto");
$graph->SetShadow();

// Configuraci�n de titulos
$graph->title->Set('Sistema de Informaci�n C�ndor');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,13); 
$graph->title->SetColor("darkblue");
$graph->legend->Pos(0.02,0.1);
$graph->subtitle->Set('Accesos Mensuales');
$graph->subtitle->SetColor('blue');
$graph->subtitle->SetFont(FF_FONT2,FS_BOLD);

// Creaci�n del pie 3D
$p1 = new PiePlot3d($dataY);
$p1->SetTheme("sand");
$p1->SetCenter(0.4);
$p1->SetSize(140);

// Adjuste �ngulo
$p1->SetAngle(45);

$p1->SetStartAngle(45);
$p1->ExplodeSlice(3);

// Valores
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->SetFont(FF_ARIAL,FS_BOLD,9);
$p1->value->SetColor("navy");
$p1->value->SetFormat('%1.3f%%');

$p1->SetLegends($dataX);

$graph->Add($p1);
$graph->Stroke();

ob_end_flush();
OCIFreeCursor($QryUsoC);
OCILogOff($oci_conecta);
?>