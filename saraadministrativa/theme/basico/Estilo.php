<?php
$indice=0;
$estilo[$indice++]="general.css";
$estilo[$indice++]="estiloCuadrosMensaje.css";
$estilo[$indice++]="estiloTexto.css";
$estilo[$indice++]="estiloFormulario.css";

//Para el aplicativo de voto en línea se incluye de forma predefinida jquery-ui
$estilo[$indice++]="jquery-ui.css";



$host=$this->miConfigurador->getVariableConfiguracion("host");
$sitio=$this->miConfigurador->getVariableConfiguracion("site");



foreach ($estilo as $nombre){
	echo "<link rel='stylesheet' type='text/css' href='".$host.$sitio."/theme/basico/css/".$nombre."'>\n";	
	
}
?>