<?php	
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');
$usuario = $_SESSION['usuario_login'];

fu_tipo_user($_SESSION["usuario_nivel"]);?> 
	
<HTML>
<?  
	echo $headuserpag;
?>
<BODY oncontextmenu='return false' onkeydown='return false' > <?

//------ Scripts de consultas SQL a la DB ------------\\
//-------- sql_d = validaci�n usuario docente 2006-3 con verificaci�n de carga acad�mica -------------------"
// ---lista los coordinadores para cada decano 2006"
	$sql_f="SELECT dep_nombre,cra_cod, cra_nombre, cra_emp_nro_iden,cra_estado,cra_dep_cod,(doc_nombre||' '||doc_apellido) AS doc";
	$sql_f.=" FROM mntac.accra, mntac.acdocente, mntge.gedep, mntpe.peemp";
	$sql_f.=" WHERE cra_estado = 'A'";
	$sql_f.=" AND doc_nro_iden = cra_emp_nro_iden";
	$sql_f.=" AND cra_dep_cod = (SELECT MAX(dep_cod)";
	$sql_f.=" FROM mntpe.peemp, mntge.gedep";//-- 79296179 vice: retorna 18 y 100, dep_emp_cod 2622
	$sql_f.=" WHERE emp_nro_iden = $usuario"; //-- 70125904 tecno; 17162654 MA; 5937474 CE; 19437829 Ing
	$sql_f.=" AND dep_cod IN(23,24,32,33,101) AND emp_cod = dep_emp_cod)";
	$sql_f.=" AND emp_nro_iden = $usuario AND emp_cod = dep_emp_cod"; //'".$usuario."' AND emp_cod = dep_emp_cod";
	$sql_f.=" ORDER BY cra_cod";
	// --------- arreglo de nombres de vinculaci�n ---\\
	// idem en vu2_coordinador---!!
	$vinsel2x[1] = "Planta T. Completo";
	$vinsel2x[2] = 'T. Completo Ocasional';
	$vinsel2x[3] = 'Medio T. Ocasional';
	$vinsel2x[4] = 'H. C. (Contrato)';
	$vinsel2x[5] = 'H. C. (Honorarios)';
	$vinsel2x[6] = 'Planta Medio Tiempo';
	//echo "mmmm".$sql_f;
	//------ Contenido HTML -  ------------\\ 
	//--- cargando arreglo con lista de coordinadores  --- \\
	//$rs = ociexebind($oci_cn,$sql_f,322,4,19253011,$xnull);
	// echo "--- *** USUARIO EN PRUEBA *** ---";		
	$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_f,"busqueda");
	if (isset($rs[0][0]))
	{ 
		if (is_array($rs))
		{
			$i=0;	$f = 1;
			while(isset($rs[$i][0]))
			{ 
				$reg_vf[$i][1] = substr($rs[$i][0],0,32);//dep_nombre
				$carrera=$reg_vf[$i][1];
				$reg_vf[$i][2] = $rs[$i][1];//cra_cod
				$reg_vf[$i][3] = substr($rs[$i][2],0,25);//cra_nombre
				$reg_vf[$i][4] = $rs[$i][3];//cra_emp_nro_iden
				$reg_vf[$i][5] = substr($rs[$i][4],8,15);//doc_nombre
				//echo $reg_vf[$i][5] ;
			$i++;
			}
			$k=0;	
		}
	}
if ($f==1)
{
	?>
	<TABLE WIDTH=100% BORDER=1 CELLSPACING=1 CELLPADDING=1>
	<font size=3>
	<center><TR><TD colspan=4 bgcolor=gold align='center'>
	<? echo $carrera?></TD></TR>
	<tr><TD colspan=4 bgcolor=gold align='center'>Coordinadores:</TD></tr><?
	$c = 0; $sec = 0;	$noevalua = 0;
	require_once("funvalidcalendario.php");
	for ($k=0;$k<$i;$k++)
	{			
		//$sec = $sec + 1;
		$docente = $reg_vf[$k][4];
		$cra = $reg_vf[$k][2];
		//" ---Verificaci�n de coordinador como docente en la misma carrera "
		// --- Similar a docente con carga para autoevaluaci�n y en evaluaci�n por consejo o de coordinador.
                //Se ajusta para las nuevas tablas 13/11/2013 Milton Parra
                
                $cadena_sql="SELECT ";
                $cadena_sql.="DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="cra_cod,tvi_cod,tvi_nombre,cra_nombre,cur_asi_cod,asi_ind_catedra ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ape_estado='A' ";
                $cadena_sql.="AND doc_nro_iden = $docente ";
                $cadena_sql.="AND cra_cod = $cra ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                $cadena_sql.="AND asi_ind_catedra='N' ";
                $cadena_sql.="ORDER BY cra_cod ";
		$rs2 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
                
		//Identificar coordinadores como docentes;
		$coordocente = false;
		if (is_array($rs2))
		{
			$coordocente = true;
			$tipovin = $rs2[0][3];
			$numformato = obtenernumformato($tipovin,"cpc"); //Funci�n incluida en "funvalidcalendario.php"
			$nomdoc = htmlentities($rs2[0][1]);
			$cranom = $rs2[0][5];
		}
		if ($coordocente)
		{ 
			//Si el Docente Coordinador tiene evaluación 
			$sql_ev="SELECT COUNT(epc_doc_nro_iden) as cta FROM autoevaluadoc.ACEVAPROCPC_".$numformato;
			$sql_ev.=" WHERE epc_doc_nro_iden = $usuario AND epc_cra_cod = $cra AND ";
			$sql_ev.=" epc_ape_ano = '".$evanio."' AND epc_ape_per = '".$evaper."'";	  
				
			$rs3 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_ev,"busqueda");						 
			$evaluado = $rs3[0][0];
			//ocifreestatement($rs3);
			//  ocilogoff($oci_cn);
			?>
			<TR>
			<TD colspan=4 align="left"> <EM>
			<?
			echo "Cra: ".$cra;
			//if ($evaluado == 0 )
			//{
				$sec = $sec + 1; 
				$_SESSION["sec".$sec] = "Evaluaci&oacute;n del Decano al docente Coordinador: ".$nomdoc." - ID No. ".$docente;
		 		$_SESSION["cra".$sec] = $cra;
				$_SESSION["docente".$sec] = $docente;
				$_SESSION["nomdoc".$sec] = $nomdoc;
				$_SESSION["fmto".$sec] = $numformato;
				$_SESSION["craactual".$sec] = $cranom.", C&oacute;digo ".$cra;
				$_SESSION["tipovin".$sec] = $tipovin;
				$_SESSION["vinsel".$sec] = $vinsel;
				$_SESSION["vinselactual".$sec] = $vinsel2x[$tipovin];

				if (vercalendario("37",$cra)){//Evento 37 Consejo Curricular
					?>
					<a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"> 
					<font size=1><? echo " &bull;  ".$nomdoc?></font></a></tr></td><?
				}
				else
				{
					$noevalua++;?>
					<font size=1>(*) <? echo " &bull;  ".$nomdoc?></font></tr></td><?
				}
			//}
			/*else
			{
				?>
				<font size=1><? echo " &bull;  ".$nomdoc?></font></tr></td>
				<?
			}*/
		}
			
	}
	
	echo '<tr><TD colspan=4 bgcolor=gold align="center">Docentes catedras institucionales:</TD></tr>';
				
		//$sec = $sec + 1;
		//$docente = $reg_vf[$k][4];
		//$cra = $reg_vf[$k][2];
		//" ---Verificaci�n de coordinador como docente en la misma carrera "
		// --- Similar a docente con carga para autoevaluaci�n y en evaluaci�n por consejo o de coordinador.
                //Se ajusta para las nuevas tablas 13/11/2013 Milton Parra
                $cadena_sql="SELECT ";
                $cadena_sql.="DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="tvi_cod,tvi_nombre,dep_cod,dep_nombre,asi_ind_catedra ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.=" INNER JOIN mntge.gedep ON dep_cod=cra_dep_cod ";
                $cadena_sql.=" INNER JOIN mntpe.peemp ON emp_cod = dep_emp_cod ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ape_estado='A' ";
                $cadena_sql.="AND emp_nro_iden = $usuario ";
//                $cadena_sql.="AND cra_cod = $cra ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                $cadena_sql.="AND asi_ind_catedra='S' ";
                //$cadena_sql.="ORDER BY cra_cod ";                
		$rscat = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
		$totreg=count($rscat);
		//Identificar coordinadores como docentes;
		
		if (is_array($rscat))
		{
		for ($k=0;$k<$totreg;$k++)
		{
			//$cra = $reg_vf[$k][2];
			$iddocente = $rscat[$k][0];
			$tipovin = $rscat[$k][2];
			$numformato = 17; //Formato N. 17 
			$nomdoc = htmlentities($rscat[$k][1]);
			$facnom = $rscat[$k][5];
			$codfac = $rscat[$k][4];
			?>
			<TR>
			<TD colspan=4 align="left"> <EM>
			<?
			$sec = $sec + 1; 
			$_SESSION["sec".$sec] = "Evaluaci&oacute;n del Decano al docente Coordinador: ".$nomdoc." - C&eacute;dula: ".$iddocente." - ".$facnom;
			$_SESSION["fac".$sec] = $codfac;
			$_SESSION["docente".$sec] = $iddocente;
			$_SESSION["nomdoc".$sec] = $nomdoc;
			$_SESSION["fmto".$sec] = $numformato;
			$_SESSION["craactual".$sec] = $facnom.", C&oacute;digo ".$codfac;
			$_SESSION["tipovin".$sec] = $tipovin;
			$_SESSION["vinsel".$sec] = $vinsel;
			$_SESSION["vinselactual".$sec] = $vinsel2x[$tipovin];
			
			$sql_cal="SELECT COUNT(ace_cod_evento) "; // --Si calendario vigente..
			$sql_cal.="FROM accaleventos ";
			$sql_cal.="WHERE ace_dep_cod=$codfac ";
			$sql_cal.="AND ace_cod_evento=37 "; //-- 18 -- V�lida en cualquier periodo acad�mico..==> NO requiere verfificar acasperi aqu�.
			$sql_cal.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) >= TO_NUMBER(TO_CHAR(ace_fec_ini,'yyyymmdd')) "; 
			$sql_cal.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) <= TO_NUMBER(TO_CHAR(ace_fec_fin,'yyyymmdd')) ";	
			$sql_cal.="AND ace_estado = 'A'";
			
			$rs_cale =$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_cal,"busqueda");
			
			if ($rs_cale[0][0] != 0){//Evento 37 Consejo Curricular
				?>
				<a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"> 
				<font size=1><? echo " &bull;  ".$nomdoc?></font></a></tr></td><?
			}
			else
			{
				$noevalua++;?>
				<font size=1>(*) <? echo " &bull;  ".$nomdoc?></font></tr></td><?
			}
			
		}
			
	}
	 
	 //del FOR;?></strong>
	</table>
	</font><font color="#336699" >Recuerde grabar antes de salir o seleccionar otro docente</font> <? 
	if ($noevalua > 0)
	{
		?>
		<font color="#336699"><br><br>
		(*) Evaluaci&oacute;n de Consejo Curricular o del Decano al Coordinador no habilitada para esta Carrera. -
		<a href="pag_proceso_cerrado.php" return false target="fra_formato">Ver Programaci&oacute;n de Actividades</a></font></EM> <?
	}

 }
 else
 {
 	?> 
	<font color="#336699"><EM>Usuario no reconocido como Decano</font></EM><BR>
	<?
 }
 ?>
</BODY>
</HTML>
