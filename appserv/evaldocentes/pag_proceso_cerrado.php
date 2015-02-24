<?php 
// -----------------------------------------------------------------------\\
/*
1	M�dulo:			Evaluaci�n Docente periodo 2007-3
2	Nombre:			pag_proceso_cerrado.php
3	Descripci�n:	P�gina de informaci�n de fechas v�lidas para el ingreso y registro de evaluaciones
					Tipo de usuario: todos (51,30,4,16)
4	Tipo:			Script PHP
5	Acceso a objetos: Tablas de la instancia SUDD en modo consulta: acasperi,accalendario
6	Aplicaci�n:		Sistema CONDOR
7	Ruta:			../evo73
8	Ambiente:		Producci�n AIX 5.3 / OAS 10g R. 10.1.2
9	Fecha en producci�n:	3 de octubre de 2007
10	Elaborado por:			Carlos E. Rodr�guez J.
11	Revisi�n No.:	01
*/
// -----------------------------------------------------------------------\\

require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
//include("funparamspag.php");
require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
include_once("../clase/multiConexion.class.php");
	
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');

fu_tipo_user($_SESSION["usuario_nivel"]); // v�lido para pruebas, en producci�n debe corresponder al usuario que ingresa a evaluar 

//Consultando separadadamente la vigencia del calendario
$sq_calev = " SELECT ROWNUM,TO_CHAR(ape_ano),TO_CHAR(ape_per),TO_CHAR(cal_cod_evento),cal_des_evento,
		TO_CHAR(cal_fec_ini,'dd/mm/yyyy'),TO_CHAR(cal_fec_fin,'dd/mm/yyyy')
		FROM acasperi,accalendario
		WHERE ape_ano = cal_anio
		AND ape_per = cal_periodo AND cal_estado = 'A' AND ape_estado='A' AND cal_cod_evento in(30,31,32,33,34,35,36)
		order by cal_fec_ini";
			
echo $headuserpag;	
?>			
<BODY ><center>
	<font size = 5><EM>Proceso de Evaluaci&oacute;n Docente</EM></font><BR><?
		$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sq_calev,"busqueda");	//P�gina 10
		if (isset($rs[0][0]))
		{
			//print $sq_calev;
			if  (!is_array($rs))
			{			
				?>
				<EM><font color="#336699">Programaci&oacute;n a&uacute;n no registrada en el sistema</font></EM>
				<?
			}
			else
			{ 
			$cta = 0;
			$i=0;
			while(isset($rs[$i][0]))
			{
				if ($cta == 0)
				{
					?><font size = 5><EM>Periodo Acad&eacute;mico 
					<? echo $rs[$i][1]." - ".$rs[$i][2] ?></EM></font><BR><BR><BR><BR><BR>
					<EM>Programaci&oacute;n de Actividades
					<BR><BR>
					<TABLE width="88%"BORDER=1 CELLSPACING=1 CELLPADDING=1 align="center">
					<tr>
						<td width="10%" align="center">Actividad</td>
						<td width="50%" align="center">Descripci&oacute;n</td>
						<td width="20%" align="center">Inicio</td>
						<td width="20%" align="center">Finalizaci&oacute;n</td>
					</tr><?
					$cta = 1;
				}
				?>
				<tr>
					<td align="center"><? echo $i+1?></td>						
					<td ><? echo $rs[$i][4]?></td>
					<td align="center"><? echo $rs[$i][5]?></td>
					<td align="center"><? echo $rs[$i][6]?></td>
				</tr><?											
				$i++;	
			}
			?>
			</TABLE><br /><br /><br />
			<?
			}
		}
	//echo "Fecha actual:     ".date("d-n-Y");?>
	<BR><BR><pre><font size = 2>
</EM></pre></font>
</center>
</BODY>
</HTML>
