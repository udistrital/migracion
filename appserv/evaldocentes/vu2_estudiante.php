<?php
// -----------------------------------------------------------------------\\
/*
1	M�dulo:			Evaluaci�n Docente periodo 2007-3
2	Nombre:			vu2_estudiante.php
3	Descripci�n:	Consulta y presentaci�n de las asignaturas inscritas por el estudiante 
					para efectuar la Evaluaci�n del respectivo docente. Tipo de usuario 51
4	Tipo:			Script PHP
5	Acceso a objetos: Tablas de la instancia SUDD en modo consulta:
					  acest, accra, acasperi, acins, acasi, accarga, acdocente
6	Aplicaci�n:		Sistema CONDOR
7	Ruta:			../evo73	
8	Ambiente:		Producci�n AIX 5.3 / OAS 10g R. 10.1.2
9	Fecha en producci�n:	5 de octubre de 2007
10	Elaborado por:			Carlos E. Rodr�guez J.
11	Revisi�n No.:	01
*/
// -----------------------------------------------------------------------\\	
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
//include("funparamspag.php");
require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');
$codigo = $_SESSION['usuario_login'];

fu_tipo_user($_SESSION["usuario_nivel"]); 
?> 

<HTML>
<style type="text/css">
	body {
		font-family: Verdana;
		font-size: 13px;
	}
	
	a {
		text-decoration: none;
	}
	
	a:hover {
		text-decoration: underline;
	}
</style>
<?  
	echo $headuserpag;
?>
<body> <?
//<BODY oncontextmenu="return false" onkeydown="return false">
		$vin = 51; //Nivel estudiante
//------ Scripts de consultas SQL a la DB ------------\\
	//-- Obteniendo asignaturas inscritas del estudiante -- \\
        $sql_e=" SELECT distinct ape_ano,";
        $sql_e.=" ape_per, ";
        $sql_e.=" est_cod, ";
        $sql_e.=" asi_cod, ";
        $sql_e.=" asi_nombre, ";
        $sql_e.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO, ";
        $sql_e.=" doc_nro_iden, ";
        $sql_e.=" (LTRIM(RTRIM(doc_apellido))||' '||LTRIM(RTRIM(doc_nombre))) doc_nombre,";
        $sql_e.=" cra_cod,";
        $sql_e.=" asi_ind_catedra,";
        $sql_e.=" cur_id";
        $sql_e.=" FROM acest";
        $sql_e.=" inner join accra on est_cra_cod = cra_cod";
        $sql_e.=" inner join acins ON est_cod = ins_est_cod";
        $sql_e.=" inner join acasperi ON ape_ano = ins_ano AND ape_per = ins_per";
        $sql_e.=" inner join acasi ON asi_cod = ins_asi_cod";
        $sql_e.=" inner join accursos on cur_id=ins_gr and cur_ape_ano=ins_ano and cur_ape_per=ins_per";
        $sql_e.=" inner join achorarios on hor_id_curso=cur_id";
        $sql_e.=" inner join accargas ON car_hor_id=hor_id";
        $sql_e.=" inner join acdocente ON car_doc_nro = doc_nro_iden ";
        $sql_e.=" WHERE est_cod = $codigo ";
        $sql_e.=" AND ape_estado = 'A'";
        $sql_e.=" AND doc_estado = 'A' ";
        $sql_e.=" AND car_estado = 'A' ";
        $sql_e.=" AND cur_estado = 'A'";
        $sql_e.=" AND hor_estado = 'A'";
        $sql_e.=" AND asi_ind_catedra='N'";
	//echo " ---- Usuario ".$sql_e;
	
	//require_once("frags_sql.php");// Incluir consulta sql_cal.. del calendario..
	//------ Contenido HTML -  ------------\\ 
	//$rs = ociexebind($oci_cn,$sql_e,311,4,20061001001,$null,$null);
	//$rs = ociexebind($oci_cn,$sql_e,311,4,$usuario,$xnull,$xnull);
	$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_e,"busqueda");
	if (isset($rs[0][0]))
	{
            $docentesEspacios=0;
		if  (!is_array($rs))
		{
			?> <font color="#336699">No tiene asignaturas registradas en el sistema.<br>
			Consulte en su Coordinaci&oacute;n de Carrera</font><?
		}
		else
		{
			//echo "mmmmm";
			$cra=$rs[0][8]; //+ ace_cod_evento	//Evento 53 pregrado o 54 postgrado
			require_once("funvalidcalendario.php");
			if (vercalendario("53",$cra) || vercalendario("54",$cra))
			{
				?> 
				<TABLE WIDTH=&quot;100%&quot; BORDER=1 CELLSPACING=1 CELLPADDING=2>
				<TR><TD bgcolor=gold width="1000" align="left">
				<? echo "C&oacute;digo de estudiante:<br>".$codigo."<br>Asignaturas inscritas:"?><BR>
				</TD></TR><?
				
				$i=0;
				while(isset($rs[$i][0]))
				{
					$codigo=$rs[$i][2];
					$asig=$rs[$i][3];
					$grupo=$rs[$i][10];
					$docente=$rs[$i][6];
					$numformato = "7";
					$fmto = $numformato;
					$sec = $sec + 1;
					$nombre = substr($rs[$i][4],0,40); //asi_nombre
					$nomdoc = $rs[$i][7];  //doc_nombre
					$_SESSION["sec".$sec] = $nomdoc." - ".$nombre." - Grupo ".$rs[$i][5];
					$_SESSION["nomdoc".$sec] = $nomdoc;
					$_SESSION["codigo".$sec] = $codigo;
					$_SESSION["asig".$sec] = $asig;
					$_SESSION["grupo".$sec] = $grupo;
					$_SESSION["docente".$sec] = $docente;
					$_SESSION["fmto".$sec] = $fmto;
					$_SESSION["nombre".$sec] = $nombre;
					?> <TR><TD align="left"> 
					<a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"><? echo $nombre ?></a></font>
					</a><br></TD></TR><?
					$i++;
				} 
				?>
				</table> <BR>
				<font color="#336699" >Recuerde grabar antes de seleccionar otra asignatura</font><? 
			}
			else
			{
				?>
				<font color="#336699">Evaluaci&oacute;n Docente no habilitada. <br>
				Verifique la validez de las fechas o consulte en su Coordinaci&oacute;n de Carrera </font><font color="#336699" size=3><br><br><br>
				<a href="pag_proceso_cerrado.php" return false target="fra_formato">Ver fechas de Evaluaci&oacute;n
				</a></font><?
			}
		}
        }else
            {
                $docentesEspacios=1;
            }
        
        $sql_catedra=" SELECT distinct ape_ano,";
        $sql_catedra.=" ape_per, ";
        $sql_catedra.=" est_cod, ";
        $sql_catedra.=" asi_cod, ";
        $sql_catedra.=" asi_nombre, ";
        $sql_catedra.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO, ";
        $sql_catedra.=" doc_nro_iden, ";
        $sql_catedra.=" (LTRIM(RTRIM(doc_apellido))||' '||LTRIM(RTRIM(doc_nombre))) doc_nombre,";
        $sql_catedra.=" cra_cod,";
        $sql_catedra.=" asi_ind_catedra";
        $sql_catedra.=" FROM acest";
        $sql_catedra.=" inner join accra on est_cra_cod = cra_cod";
        $sql_catedra.=" inner join acins ON est_cod = ins_est_cod";
        $sql_catedra.=" inner join acasperi ON ape_ano = ins_ano AND ape_per = ins_per";
        $sql_catedra.=" inner join acasi ON asi_cod = ins_asi_cod";
        $sql_catedra.=" inner join accursos on cur_id=ins_gr and cur_ape_ano=ins_ano and cur_ape_per=ins_per";
        $sql_catedra.=" inner join achorarios on hor_id_curso=cur_id";
        $sql_catedra.=" inner join accargas ON car_hor_id=hor_id";
        $sql_catedra.=" inner join acdocente ON car_doc_nro = doc_nro_iden ";
        $sql_catedra.=" WHERE est_cod = $codigo ";
        $sql_catedra.=" AND ape_estado = 'A'";
        $sql_catedra.=" AND doc_estado = 'A' ";
        $sql_catedra.=" AND car_estado = 'A' ";
        $sql_catedra.=" AND cur_estado = 'A'";
        $sql_catedra.=" AND hor_estado = 'A'";
        $sql_catedra.=" AND asi_ind_catedra='S'";        
	//echo $sql_catedra;
	$resultado = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_catedra,"busqueda");
	if (isset($resultado[0][0]))
	{
            $docentesCatedras=0;
		if  (!is_array($resultado))
		{
			?> <font color="#336699">No tiene c&aacute;tedras registradas en el sistema.<br>
			Consulte en su Coordinaci&oacute;n de Carrera</font><?
		}
		else
		{
			$cra=$resultado[0][8]; //+ ace_cod_evento	//Evento 53 pregrado o 54 postgrado
			require_once("funvalidcalendario.php");
			if (vercalendario("53",$cra) || vercalendario("54",$cra))
			{
				?> 
				<TABLE WIDTH=&quot;100%&quot; BORDER=1 CELLSPACING=1 CELLPADDING=2>
				<TR><TD bgcolor=gold width="1000" align="left">
				<? echo "C&aacute;tedras inscritas:"?><BR>
				</TD></TR><?
				
				$i=0;
				while(isset($resultado[$i][0]))
				{
					$codigo=$resultado[$i][2];
					$asig=$resultado[$i][3];
					$grupo=$resultado[$i][5];
					$docente=$resultado[$i][6];
					$numformato = "15";
					$fmto = $numformato;
					$sec = $sec + 1;
					$nombre = substr($resultado[$i][4],0,40); //asi_nombre
					$nomdoc = $resultado[$i][7];  //doc_nombre
					$_SESSION["sec".$sec] = $nomdoc." - ".$nombre." - Grupo ".$grupo;
					$_SESSION["nomdoc".$sec] = $nomdoc;
					$_SESSION["codigo".$sec] = $codigo;
					$_SESSION["asig".$sec] = $asig;
					$_SESSION["grupo".$sec] = $grupo;
					$_SESSION["docente".$sec] = $docente;
					$_SESSION["fmto".$sec] = $fmto;
					$_SESSION["nombre".$sec] = $nombre;
					?> <TR><TD align="left"> 
					<a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"><? echo $nombre ?></a></font>
					</a><br></TD></TR><?
					$i++;
				} 
				?>
				</table> <BR>
				<font color="#336699" >Recuerde grabar antes de seleccionar otra c&aacute;tedra</font><? 
			}
		}
        }else
            {
                $docentesCatedras=1;
            }
        if ($docentesCatedras==1&&$docentesEspacios==1)
                {
                    ?> <font color="#336699">No hay docentes para realizar evaluaci&oacute;n.<br>
                    Consulte en su Coordinaci&oacute;n de Carrera</font><?
		}

	?>
</body>
</HTML>