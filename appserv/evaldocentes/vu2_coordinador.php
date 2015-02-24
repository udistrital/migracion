<?php	
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
//include("funparamspag.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');
$usuario = $_SESSION['usuario_login'];

fu_tipo_user($_SESSION["usuario_nivel"]); // v�lido para pruebas, en producci�n debe corresponder al usuario que ingresa a evaluar?> 

<HTML>
<?  
	echo $headuserpag;
?>
<BODY oncontextmenu="return false"> <?

//------ Scripts de consultas SQL a la DB ------------\\
	// Carreras y total de docentes discriminado por vinculaci�n --- desde 2006-3
	// Excluye las carreras registradas de un coordinador que no tienen docentes vinculados ---
        //actualizado para la nueva estructura de tablas 13/11/2013 Milton Parra
        $cadena_sql="SELECT ";
        $cadena_sql.="cra_cod, cra_nombre, cra_emp_nro_iden, tvi_cod, tvi_nombre, COUNT(distinct car_doc_nro) AS cta ";
        $cadena_sql.="FROM ";
        $cadena_sql.="mntac.acdocente ";
        $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
        $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
        $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
        $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
        $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
        $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";//AND cra_emp_nro_iden=doc_nro_iden";
        $cadena_sql.=" WHERE ";
        $cadena_sql.=" cra_emp_nro_iden = $usuario ";
        $cadena_sql.=" AND ape_estado='A'";
        $cadena_sql.=" AND car_estado='A'";
        $cadena_sql.=" AND hor_estado='A'";
        $cadena_sql.=" AND cur_estado='A'";
        $cadena_sql.=" AND doc_estado='A'";
        $cadena_sql.=" GROUP BY cra_cod, cra_nombre, cra_emp_nro_iden,tvi_nombre, tvi_cod ";
        $cadena_sql.=" ORDER BY cra_cod,tvi_cod";
	
	// --------- cuenta carreras de un coordinador -------2006-3
	$sql_vcon="SELECT  COUNT(DISTINCT cra_cod) ";
	$sql_vcon.="FROM mntac.acdocente ";
        $sql_vcon.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
        $sql_vcon.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
        $sql_vcon.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
        $sql_vcon.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
        $sql_vcon.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
        $sql_vcon.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";//AND cra_emp_nro_iden=doc_nro_iden";
        $sql_vcon.=" WHERE ";
        $sql_vcon.=" cra_emp_nro_iden = $usuario ";
        $sql_vcon.=" AND ape_estado='A'";
        $sql_vcon.=" AND car_estado='A'";
        $sql_vcon.=" AND hor_estado='A'";
        $sql_vcon.=" AND cur_estado='A'";
        $sql_vcon.=" AND doc_estado='A'";

	// --------- arreglo de nombres de vinculaci�n ---\\
		// idem en vu2_decano, ftocoordev---!!
	$vinsel2x[1] = "Planta T. Completo";
	$vinsel2x[2] = 'T. Completo Ocasional';
	$vinsel2x[3] = 'Medio T. Ocasional';
	$vinsel2x[4] = 'H. C. (Contrato)';
	$vinsel2x[5] = 'H. C. (Honorarios)';
	$vinsel2x[6] = 'Planta Medio Tiempo';
		
	$cra = $_SESSION["cravusec".$vusec];
	
	//echo 'cra'.$cra."ohhh";
	//------ Contenido HTML -  ------------\\ 
	//--- cargando arreglo con lista de carreras del coordinador y vinculaciones de docentes  --- \\
	//$rs4 = ociexebind($oci_cn,$sql_vco,313,4,11187193,$xnull);
	//echo "--- *** USUARIO EN PRUEBA *** ---";		
	$rs4 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
	$f = 0;
	if(isset($rs4))
	{ 
		if(is_array($rs4))
		{
			$i=0;$j=0;
			while(isset($rs4[$i][0]))
			{ 
				$reg_vco[$i][1] = $rs4[$i][0];//cra_cod
				$reg_vco[$i][2] = substr($rs4[$i][1],0,40);//cra_nombre
				$reg_vco[$i][3] = $rs4[$i][2];//usario coordinador cra_emp_nro_iden
				$reg_vco[$i][4] = $rs4[$i][3];//vinsel = tvi_cod
				$reg_vco[$i][5] = substr($rs4[$i][4],8,25);//tvi_nombre
				$reg_vco[$i][6] = $rs4[$i][5];//cta_tvi_cod
				$i++;
			}
			$k=0;	$f = 1;
		}
		else
		{
			?> 
			<font color="#336699"><EM>Usuario no reconocido como Coordinador en el Sistema, 
			o Coordinador sin docentes registrados.</font></EM><BR><BR><BR>
			<?
		}
	}
	//	ocilogoff($oci_cn);
		
	//--- Presentando lista con carreras habilitadas o no seg�n vigencia del calendario --- \\
	$rs3 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_vcon,"busqueda");
	if (isset($rs3[0][0]))
	{
		$totcra = $rs3[0][0];
		?><font color="#336699">Seleccione una vinculaci&oacute;n</font><br><?
		$vusec = 0;	$cranw = 0; $noevalua = 0;
		?> <table bgcolor="#c6c384" border="1" CELLSPACING=1 CELLPADDING=1> <?
		for ($k=0;$k<$i;$k++)
		{
			$vusec = $vusec + 1;
			$crac = $reg_vco[$k][1];
			if ($crac != $cranw)
			{
				$cranom = $reg_vco[$k][2];
				?><EM><tr><td colspan=4 <font size=1><? echo $crac." ".$cranom?></font><?
				//<td><font size=1></font></td>  
				$cranw = $crac;
				require_once("funvalidcalendario.php");
				if (!vercalendario("37",$crac)) //Evento 37 evaluaci�n del consejo curricular
				{
					?> (*) <? $noevalua++;
				}
				?> </td></tr></EM> <?
			}
			$vinsel = $reg_vco[$k][4];
			/*$sql_cta_ev2 = "SELECT COUNT(epc_doc_nro_iden) AS cta FROM ACEVAPROCPC_".obtenernumformato($vinsel);
			$sql_cta_ev2 = $sql_cta_ev2." WHERE epc_cra_cod = ".$crac." AND epc_observa2 = ".$vinsel." AND";
			$sql_cta_ev2 = $sql_cta_ev2." epc_ape_ano = ".$evanio." AND epc_ape_per = ".$evaper;
			$rs5 = ociexe($oci_cn,$sql_cta_ev2,318,4);$row5 = OCIFetch($rs5); */
			//echo " crac = ".$crac;
			$_SESSION["cravusec".$vusec] = $cranw;//$crac;
			$_SESSION["cranom".$vusec] = $cranom;
			$_SESSION["vinsel".$vusec] = $vinsel;
			?><EM><tr><td colspan=4 ><font size=1> <?
			if ($noevalua > 0)
			{
				?>
				<?  echo $reg_vco[$k][5] ?> </font></td></tr></EM> <?
			}
			else
			{
				?>
				<?unset($vusev);?>
				<a href='vu2_coordinador.php?nvusec=<? echo $vinsel?>&carrera=<? echo $crac?>' return false target="fra_registro">
				<? echo $reg_vco[$k][5]?></a> <? //<td align="right"><font size=1>
				//<? echo OCIResult($rs5,1)."/".OCIResult($rs4,6) ?></font></td></tr></EM><? //ocifreestatement($rs5);OCILogOff ($oci_cn);
			}
		} ?></table><?
		if ($noevalua > 0)
		{
			?>
			<font color="#336699"><br><br>
			(*) Evaluaci&oacute;n de Consejo no habilitada. -
			<a href="pag_proceso_cerrado.php" return false target="fra_formato">Ver Programaci&oacute;n de Actividades</a></font></EM>
			<?
		}
	
	}
//------------------------- Lista de docentes de la carrera seleccionada con carga acad�mica ---------------
	$carrera= $_REQUEST["carrera"];
	$vinselactual= $_REQUEST["nvusec"];
	if ($carrera != "")
	{
            //CAMBIAR ape_estado A ESTADO A PARA PERIODO ACTIVO
            //actualizado para la nueva estructura de tablas 13/11/2013 Milton Parra

                $cadena_sql="SELECT DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre,  "; 
                $cadena_sql.="cra_cod,cra_nombre,tvi_cod,tvi_nombre "; 
                $cadena_sql.="FROM "; 
                $cadena_sql.="mntac.acdocente "; 
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" doc_nro_iden <> $usuario  AND cra_cod = $carrera  AND tvi_cod = $vinselactual";
                $cadena_sql.=" AND doc_estado = 'A'";
                $cadena_sql.=" AND ape_estado='A'";
                $cadena_sql.=" AND car_estado='A'";
                $cadena_sql.=" AND hor_estado='A'";
                $cadena_sql.=" AND cur_estado='A'";
                $cadena_sql.=" ORDER BY doc_nombre";

		//echo "xxx".$sql_c."<br>";
	  	$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
		
		if (isset($rs[0][0]))
		{
			?><TABLE BORDER=1 CELLSPACING=1 CELLPADDING=1><?
			if (!is_array($rs))
			{
				?>
				 <font size=3>
				 <center><strong><TR><TD colspan=4 bgcolor=gold width="1000" align="center"> 
				 <? echo "Carrera: ".$carrera." ".$cranom;?></strong></TD></TR>
				 <TR><TD colspan=4 width="1000" align="center"> 
				 <strong><? echo "Sin docentes de ".$vinsel2x[$vinselactual]?></strong>
				 <?
			}
			else
			{
				 $tipovin = $rs[0][4];
				 //$cra = OCIResult($rs,3);    ????
				 $numformato = obtenernumformato($tipovin,"cpc"); //Funci�n ncluida en "funvalidcalendario.php"
				 $sql_cta_ev="SELECT COUNT(epc_doc_nro_iden) AS cta FROM autoevaluadoc.ACEVAPROCPC_".$numformato;
				 $sql_cta_ev.=" WHERE epc_cra_cod = ".$carrera." AND epc_observa2 = ".$tipovin." AND";
				 $sql_cta_ev.=" epc_ape_ano = ".$evanio." AND epc_ape_per = ".$evaper;
	  			 $rs2 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_cta_ev,"busqueda");
	
				//--------- sql_cta = Total docentes en la carrera y tipo vinculaci�n seleccionados --------\\ 
				//if ($usuario == "" || $cra = "" || $vinselactual == "") {echo "Error de datos en 320,4. Intente de nuevo o reinicie la aplicaci&oacute;n."; exit;}
                                 //actualizado para la nueva estructura de tablas 13/11/2013 Milton Parra
								
				//$rs3 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_cta,"busqueda");
                                $rs3[0][0]=count($rs);
				$cranom = $rs[0][3];
				$nomtvi = substr($rs[0][5],0,30);
				?> <font size=3>
				<center><strong><TR><TD colspan=4 bgcolor=gold width="1000" align="center"> 
				<? echo "Carrera: ".$carrera." - ".substr($cranom,0,25)?></strong></EM></TD></TR>
				<TR><TD colspan=4 width="1000" align="center"> 
				<strong><? echo "Docentes de ".$vinsel2x[$vinselactual]
				//El conteo de evaluados incluye HCC y HCH en un total, al igual que MT y MTO?></strong><BR>
				<?
				//echo "mmm".$sql_cta_ev."<br>";
				//echo "nnn".$sql_cta."<br>";
				echo "Evaluados ".$rs2[0][0]." de ".$rs3[0][0]
				?>
				</TD></TR></center><?
				$_SESSION["craactual"] = $cranom.", C&oacute;digo ".$carrera;
				$_SESSION["nomvinselactual"] = $vinsel2x[$vinselactual];
				//????$_SESSION["vinrefresh"] = $vinselactual;
				//OCILogOff ($oci_cn);?><BR><? 
				//$sec = 0;	//Inicializa secuencia de la lista de docentes
				$sec = 0;
				$i=0;
				while(isset($rs[$i][0]))
				{  	// Obtiene todos los registros de v�nculo sea tipovin �nico o varios.
				  	 $sec = $sec + 1;			 //$sid = cstr($sec) ?>
					 <TR><TD colspan=4 align="left"> <?
					 $docente = $rs[$i][0];
					 $nomdoc = $rs[$i][1];
				 	 $_SESSION["sec".$sec] = "Evaluaci&oacute;n por el Consejo de Proyecto Curricular de: ".$nomdoc." - ID No. ".$docente;
						 
					 $sql_ev="SELECT COUNT(epc_doc_nro_iden) as cta FROM autoevaluadoc.ACEVAPROCPC_".$numformato;
					 $sql_ev.="WHERE epc_doc_nro_iden = ".$docente." AND epc_cra_cod = ".$carrera." AND";
					 $sql_ev.="epc_observa2 = ".$tipovin." AND epc_ape_ano = ".$evanio." AND epc_ape_per = ".$evaper;	  
	  				 $rs3 = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_ev,"busqueda");
					
					 if ($rs3[0][0] == 0)
					 { 
						$_SESSION["cra".$sec] = $carrera;
						$_SESSION["docente".$sec] = $docente;
						$_SESSION["nomdoc".$sec] = $nomdoc;
						$_SESSION["fmto".$sec] = $numformato;
						$_SESSION["tipovin".$sec] = $tipovin;
						?><a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"> 
						<font size=1><? echo "&bull;  ".$nomdoc?></a></font><br> <?
					 }
					 else
					 {
						?><font size=1><? echo "&bull;  ".$nomdoc?></font><br><?
					 }
				$i++;
				}
			}
			?>
			</table><br></strong></font>
			<? 
		}
	}
	else
	{
		echo "No hay carrera";
	}
?>
<input type="hidden" name="vu2sec" id="vu2sec" value=<? echo $vu2sec ?> >
</BODY>
</HTML>