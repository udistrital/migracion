<?php	
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
$usuario = $_SESSION['usuario_login'];

fu_tipo_user($_SESSION["usuario_nivel"]);
?> 
<HTML>
<?  
	echo $headuserpag;
?>
<BODY oncontextmenu="return false" onkeydown="return false" > <?

//------ Scripts de consultas SQL a la DB ------------\\
//-------- sql_d = validaci�n usuario docente 2006-3 con verificaci�n de carga acad�mica -------------------
//actualizado para la nueva estructura de tablas 13/11/2013 Milton Parra

$cadena_sql="SELECT ";
$cadena_sql.="DISTINCT(cra_cod),doc_nro_iden, ";
$cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
$cadena_sql.="cra_nombre,tvi_cod,tvi_nombre,asi_ind_catedra ";
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
$cadena_sql.="AND doc_nro_iden = $usuario ";
$cadena_sql.="AND doc_estado = 'A' ";
$cadena_sql.="AND car_estado = 'A' ";
$cadena_sql.="AND cur_estado = 'A' ";
$cadena_sql.="AND hor_estado='A' ";
$cadena_sql.="AND asi_ind_catedra='N' ";
$cadena_sql.="ORDER BY cra_cod ";







//------ Contenido HTML -  ------------\\ 
	//--- cargando arreglo con lista de carreras del docente --- \\19158790,12103409,TCO-->19053543, MTO(fmt13-->253387,19166898)
	     //$rs = ociexebind($oci_cn,$sql_d,312,4,19158790,$xnull,$xnull);
	 
	$rs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
	$cuenta1=count($rs);
	//echo "--- *** USUARIO EN PRUEBA *** ---";
	
	if (isset($rs[0][0]))
	{
		if  (!is_array($rs))
		{ 
			?><EM><font color="#336699">No existen registros para el usuario <? echo $usuario?></font></EM><BR> <?
		}
		else
		{
			$i=0;$j=0;
			while(isset($rs[$i][0]))
			{ 
				$reg_cra[$i][1] = $rs[$i][0];//cra_cod
				$reg_cra[$i][2] = $rs[$i][1];//doc_nro_iden
				$reg_cra[$i][3] = $rs[$i][2];//doc_nombre
				$reg_cra[$i][4] = $rs[$i][3];//cra_nombre
				$reg_cra[$i][5] = $rs[$i][4];//tipo_vin
				$reg_cra[$i][6] = substr($rs[$i][5],0,40);//nom_tvi
				$reg_cra[$i][7] = $rs[$i][6];//Codigo_asignatura
				//echo "  -----***-----i= ".$i.",  cra= ".$reg_cra[$i][1].", doc= ".$reg_cra[$i][2].", cranom= ".$reg_cra[$i][4];
				$i++;
			} 
			$k=0;
			$f = 1;
		}
	}
	
	//--- Presentando lista de carreras habilitadas o no seg�n vigencia del calendario --- \\
	if ($f == 1)
	{
		?>
		<font size=2><center><TR><TD colspan=4 bgcolor=gold width="1000" align="center"> <EM>
		<? echo "Vinculado a: "?></EM></TD></TR></center></font>
		<TR><TD colspan=4 width="1000"><BR> <?
		$sec = 0;	$noevalua = 0;
		require_once("funvalidcalendario.php");
		for ($k=0;$k<$i;$k++)
		{			
			$sec = $sec + 1;
			if (vercalendario("36",$reg_cra[$k][1]))	//Evento 36 autoevaluaci�n
			{
				 $tipovin = $reg_cra[$k][5];
				 $numformato = obtenernumformato($tipovin,"auto"); //Funci�n ncluida en "funvalidcalendario.php"
				 $nomtvi = $reg_cra[$k][6]; 
				 $cranom = $reg_cra[$k][4];
				 $nomdoc = $reg_cra[$k][3];
				 $_SESSION["sec".$sec] = "Autoevaluaci&oacute;n de: $nomdoc  - $nomtvi  -  $cranom";
				 $_SESSION["s".$sec] = $sec;
				 $_SESSION["cra".$sec] = $reg_cra[$k][1];
				 $_SESSION["docente".$sec] =$reg_cra[$k][2]; //$docente;  //$_SESSION["vin".$sec] = $vin;
				 $_SESSION["fmto".$sec] = $numformato;
				 $_SESSION["nomtvi".$sec] = $nomtvi;
				 $_SESSION["cranom".$sec] = $cranom;
				 $_SESSION["nomdoc".$sec] = $nomdoc;
				 ?> <font size=1>
				 <a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"> <? echo $_SESSION["cra".$sec]." ".$cranom?></a></font><br><br> <?
				 $_SESSION["tipovin".$sec] = $tipovin; 
			}
			else
			{
				 $noevalua++;
				 ?><BR><BR> <EM><font size=1><? echo $reg_cra[$k][1]." ".$reg_cra[$k][4]?> (*)</font><?
			}
		}
		if ($noevalua > 0)
		{
			?>
			<font color="#336699"><br><br><br><br><br><br><br><br>
			(*) Autoevaluaci&oacute;n Docente no habilitada, consulte en su Coordinaci&oacute;n de Carrera. -
			<a href="pag_proceso_cerrado.php" return false target="fra_formato">Ver Programaci&oacute;n de Actividades</a></font></EM> <?
		}
		else
		{
				?> <BR><font color="#336699" >Recuerde grabar antes de salir o seleccionar otro Proyecto Curricular</font> <? 
		}
		//OCILogOff ($oci_cn);
	}
	
//actualizado para la nueva estructura de tablas 13/11/2013 Milton Parra        

        $cadena_sql="SELECT ";
        $cadena_sql.="DISTINCT(cra_cod),doc_nro_iden, ";
        $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
        $cadena_sql.="cra_nombre,tvi_cod,tvi_nombre,asi_ind_catedra ";
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
        $cadena_sql.="AND doc_nro_iden = $usuario ";
        $cadena_sql.="AND doc_estado = 'A' ";
        $cadena_sql.="AND car_estado = 'A' ";
        $cadena_sql.="AND cur_estado = 'A' ";
        $cadena_sql.="AND hor_estado='A' ";
        $cadena_sql.="AND asi_ind_catedra='S' ";
        $cadena_sql.="ORDER BY cra_cod ";      
	//echo $sql_dc;
	$rsc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
	
	if (isset($rsc[0][0]))
	{
		if  (!is_array($rsc))
		{ 
			?><EM><font color="#336699">No existen registros para el usuario <? echo $usuario?></font></EM><BR> <?
		}
		else
		{
			$i=0;$j=0;
			while(isset($rsc[$i][0]))
			{ 
				$reg_crac[$i][1] = $rsc[$i][0];//cra_cod
				$reg_crac[$i][2] = $rsc[$i][1];//doc_nro_iden
				$reg_crac[$i][3] = $rsc[$i][2];//doc_nombre
				$reg_crac[$i][4] = $rsc[$i][3];//cra_nombre
				$reg_crac[$i][5] = $rsc[$i][4];//tipo_vin
				$reg_crac[$i][6] = substr($rsc[$i][5],0,40);//nom_tvi
				$reg_crac[$i][7] = $rsc[$i][6];//Codigo_asignatura
				//echo "  -----***-----i= ".$i.",  cra= ".$reg_cra[$i][1].", doc= ".$reg_cra[$i][2].", cranom= ".$reg_cra[$i][4];
				$i++;
			} 
			$k=0;
			$f = 2;
		}
	}
	
	
	
	if ($f == 2)
	{
		?>
		<font size=2><center><TR><TD colspan=4 bgcolor=gold width="1000" align="center"> <EM>
		<? echo "Docente de c&aacute;tedra institucional vinculado (a) a: "?></EM></TD></TR></center></font>
		<TR><TD colspan=4 width="1000"><BR> <?
		$sec = $cuenta1;	$noevalua = 0;
		require_once("funvalidcalendario.php");
		//echo "mmm".$sec;
		for ($k=0;$k<$i;$k++)
		{			
			
			$sec = $sec + 1;
			if (vercalendario("36",$reg_crac[$k][1]))	//Evento 36 autoevaluaci�n
			{
				 $tipovin = $reg_crac[$k][5];
				 $numformato = 16; //No. de formato correspondiente a las cátedras.
				 $nomtvi = $reg_crac[$k][6]; 
				 $cranom = $reg_crac[$k][4];
				 $nomdoc = $reg_crac[$k][3];
				 $_SESSION["sec".$sec] = "Autoevaluaci&oacute;n C&Aacute;TEDRA INSTITUCIONAL de: $nomdoc  - $nomtvi  -  $cranom";
				 $_SESSION["s".$sec] = $sec;
				 $_SESSION["cra".$sec] = $reg_crac[$k][1];
				 $_SESSION["docente".$sec] =$reg_crac[$k][2]; //$docente;  //$_SESSION["vin".$sec] = $vin;
				 $_SESSION["fmto".$sec] = $numformato;
				 $_SESSION["nomtvi".$sec] = $nomtvi;
				 $_SESSION["cranom".$sec] = $cranom;
				 $_SESSION["nomdoc".$sec] = $nomdoc;
				 ?> <font size=1>
				 <a href="formato.php?sec=<? echo $sec ?>" return false target="fra_formato"> <? echo $_SESSION["cra".$sec]." ".$cranom?></a></font><br><br> <?
				 $_SESSION["tipovin".$sec] = $tipovin; 
			}
			else
			{
				 $noevalua++;
				 ?><BR><BR> <EM><font size=1><? echo $reg_crac[$k][1]." ".$reg_crac[$k][4]?> (*)</font><?
			}
		}
		if ($noevalua > 0)
		{
			?>
			<font color="#336699"><br><br><br><br><br><br><br><br>
			(*) Autoevaluaci&oacute;n Docente no habilitada, consulte en su Coordinaci&oacute;n de Carrera. -
			<a href="pag_proceso_cerrado.php" return false target="fra_formato">Ver Programaci&oacute;n de Actividades</a></font></EM> <?
		}
		else
		{
				?> <BR><font color="#336699" >Recuerde grabar antes de salir o seleccionar otro Proyecto Curricular</font> <? 
		}
		//OCILogOff ($oci_cn);
	}
	
	
	?>
</BODY>
</HTML>