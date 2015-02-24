<!--
/**********************************************************************************   
LisLov
*   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
*   Este script fue realizado en la Oficina Asesora de Sistemas
*   Por: Pedro Luis Manjarrés Cuello
*********************************************************************************/
var WinOpen=0;
function ListaValores(pag, obs, an, al, iz, ar){
	if(WinOpen){
     if(!WinOpen.closed)
	    WinOpen.close();
  }
   WinOpen = window.open(pag+'?obs_retorno='+obs, "Lov", "width="+an+",height="+al+",scrollbars=YES,left="+iz+",top="+ar);
}
// -->