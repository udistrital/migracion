<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_gantt.php");
require_once(dir_script."/msql_ano_per.php");
require_once("msql_graf_fraccionamiento.php");

$SubTit = 'Período Académico '.$ano.'-'.$per.' Finalización '.$FecIniAddCan;

$graph = new GanttGraph(0,0,"auto");
$graph->SetMarginColor('white');
$graph->SetColor('#F3F3F3');
$graph->SetBox();
//$graph->SetShadow();

//Titulo y subtitulo
$graph->title->Set("Fraccionamiento de Pago de Matrícula");
$graph->title->SetColor("#006CD9");
$graph->subtitle->Set($SubTit);
$graph->subtitle->SetColor("#006CD9");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);

//Despliegue de  dia, semana y mes 
$graph->ShowHeaders(GANTT_HDAY | GANTT_HWEEK | GANTT_HMONTH);
$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY2WNBR);
//$graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
//$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY2);

//Nombre corto del mes con 1 ó 2 dígito del año
//$graph->scale->month->SetStyle(MONTHSTYLE_SHORTNAMEYEAR2);
$graph->scale->month->SetStyle(MONTHSTYLE_LONGNAME);
$graph->scale->month->SetFontColor("#FFFFFF");
$graph->scale->month->SetBackgroundColor("#006CD9");

// 1% margen vertical de las actividades
$graph->SetLabelVMarginFactor(1);

//Formato de la barra para la primera actividad
//($fila,$titulo,$fecini,$fecfin)
$activity1 = new GanttBar(0,"Fraccionamiento",$IniAd, $FinAd,"[$AddPor%]");
//Color de la barra
$activity1->SetPattern(BAND_RDIAG,"magenta");
$activity1->SetFillColor("violet");
//Altura total de la barra
$activity1->SetHeight(15);
//Progreso de la actividad $AddPor%
$activity1->progress->Set($AddPor);

//Adicion de las barras al gráfico
$graph->Add($activity1);

//Linea vertical en la fecha fin de la primera actividad
$vlineA = new GanttVLine($FinAd, $FinAd);
$vlineA->SetDayOffset(0.5);
$graph->Add($vlineA);

//Despliegue del gráfico
$graph->Stroke();
OCILogOff($oci_conecta);
?>