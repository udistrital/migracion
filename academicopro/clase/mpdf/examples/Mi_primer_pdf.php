<?php

include_once('../class.MySQL.php');

define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "123");
define("MYSQL_NAME", "moni");

//$oMySQL = new MySQL();
//$query="SELECT * FROM sga_bloque";
//$oMySQL->ExecuteSQL($query);
//$arreglo=$oMySQL->ArrayResults();
//echo "<pre>".print_r($arreglo,true)."</pre>";

/*$html="<table border='1'>";
foreach($arreglo as $key=>$row)
{
    $html.="<tr><td>".$row['nombre']."</td></tr>";
}
$html.="</table>";*/

$html='<div style="border:1px solid #CCC;text-align:center;">Sebastian felipe rincon castellanos</div>';
$html.='<div style="border:1px solid #CCC;text-align:center;">20121378021</div>';
$html.='<div style="border:1px solid #CCC;text-align:center;">Ingenieria en telematica</div>';

include("../mpdf.php"); //Incluye como la clase.
$mpdf=new mPDF(); //Crea el objeto de tipo mPDF. Instancia la clase.
$mpdf->WriteHTML($html); //WriteHTML lo q hace es imprimir un pdf con unos campos en html, se le pasa una variable
$mpdf->Output(); //Finaliza el documento
//$mpdf->Output('filename.pdf','F'); //Para guardarlo en una ruta 
exit; 

?>