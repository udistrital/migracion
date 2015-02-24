<?php
// -----------------------------------------------------------------------\\
/*
1	M�dulo:			Evaluaci�n Docente periodo 2007-3
2	Nombre:			vartextosfijos.php
3	Descripci�n:	script de configuraci�n o de variables a utilizar en el proceso de Evaluaci�n Docente
					tales como a�o, periodo, encabezado y pie de p�gina
4	Tipo:			Script PHP
5	Acceso a objetos: No
6	Aplicaci�n:		Sistema CONDOR
7	Ruta:			../evo73	
8	Ambiente:		Producci�n AIX 5.3 / OAS 10g R. 10.1.2
9	Fecha en producci�n:	2 de octubre de 2007
10	Elaborado por:			Carlos E. Rodr�guez J.
11	Revisi�n No.:	01
12       Revisi�n No.     	02
13	Actualizaci�n	24 de marzo de 2011
14 	Author	Jes�s Neira Guio 
	Se actualizan variables para el periodo 2008-1
*/
// -----------------------------------------------------------------------\\
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('funev.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once("vartextosfijos.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');

fu_tipo_user($_SESSION["usuario_nivel"]);

$cadena_sql="SELECT ";
$cadena_sql.="ape_ano, ape_per ";
$cadena_sql.="FROM ";
$cadena_sql.="acasperi ";
$cadena_sql.="WHERE ";
$cadena_sql.="ape_estado='A'";

$rsdoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

$evanio = $rsdoc[0][0];
$evaper  = $rsdoc[0][1];

$evcreds = "<center>  
<table cellSpacing='0' cellPadding='0' width='100%' summary border='0'>
    <tbody>
      <tr>
        <td align='middle'>
          <p align='center' class='Estilo6'><font face='arial' size='1'>Copyright � 2006 Universidad Distrital Francisco Jos&eacute; de Caldas, Oficina
          Asesora de Sistemas. Todos los derechos reservados. <br>
          Tel&eacute;fonos 3238400 Ext. 2104 - 3239300 Ext. 2104. - <a href='../generales/frm_contacto.php'>computo@udistrital.edu.co</a><br>
          <b>NOTA: En este sitio, recopilamos informaci&oacute;n que solo es de inter&eacute;s de docentes y estudiantes de la Universidad.</b></font></p>
        </td>
      </tr>
    </tbody>
  </table>
</center>";
$headuserpag ="<HEAD>
	<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'> 
	<META http-equiv='Page-Enter' content='revealTrans(Duration=0.6,Transition=24)'>
	<META http-equiv='Page-Exit' content='revealTrans(Duration=0.6,Transition=2)'>
	<link href='../script/estilo.css' rel='stylesheet' type='text/css'>
	
	<script type='text/javascript' src='ValidaFormato.js'></script> 
	
</HEAD>";

?>