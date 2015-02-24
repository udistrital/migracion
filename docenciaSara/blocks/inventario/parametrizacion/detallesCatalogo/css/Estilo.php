<?php
$indice=0;
$estilo[$indice++]="../../../css/dataTable/demo_page.css";
$estilo[$indice++]="../../../css/dataTable/demo_table.css";
$estilo[$indice++]="../../../css/dataTable/demo_table_jui.css";
$estilo[$indice++]="../../../css/dataTable/jquery.dataTables.css";
$estilo[$indice++]="../../../css/dataTable/jquery.dataTables_themeroller.css";
$estilo[$indice++]="../../../css/mensajes/mensajes.css";


$rutaBloque=$this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site");

if($unBloque["grupo"]==""){
	$rutaBloque.="/blocks/".$unBloque["nombre"];
}else{
	$rutaBloque.="/blocks/".$unBloque["grupo"]."/".$unBloque["nombre"];
}

foreach ($estilo as $nombre){
	echo "<link rel='stylesheet' type='text/css' href='".$rutaBloque."/css/".$nombre."'>\n";

}
?>