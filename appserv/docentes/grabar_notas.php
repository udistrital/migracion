<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

//Validamos que los porcentajes digitados sean de tipo num�rico
foreach($_REQUEST as $clave=>$valor)
{
	if($clave==p1||$clave==p2||$clave==p3||$clave==p4||$clave==p5||$clave==pl||$clave==pe)
	{
		if((!is_numeric($valor))&&($valor!=NULL))
		{
			echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
			//$nonum= $valor;
			echo $clave."->".$valor."La nota digitada NO es un valor num&eacute;rico</p>";
			
			//die('<center><h3>Intente nuevamente Ingresando un caracter v�lido.</h3></center></font>');
		}
		
	}
}
//echo 'mmmmmmm'.$_SESSION['nivel'];

$acupor = $_REQUEST['p1']+$_REQUEST['p2']+$_REQUEST['p3']+$_REQUEST['p4']+$_REQUEST['p5']+$_REQUEST['pl']+$_REQUEST['pe'];
if($acupor>100 && $_SESSION['nivel']!='PREGRADO' && $_SESSION['nivel']!='EXTENSION'){
	echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
	die('<center><h3>Revise los porcentajes de las notas, superan el 100%.</h3></center></font>');
}
if($acupor==0 && $_SESSION['nivel']!='PREGRADO'  && $_SESSION['nivel']!='EXTENSION'){
	echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
	die('<center><h3>Digite los porcentajes de las notas. Recuerde que no debe superar el 100%.</h3></center></font>');
}

//Actualizamos los porcentajes en la base de datos


$qrypor="UPDATE ";
$qrypor.="ACCURSO ";
$qrypor.="SET ";
if(isset($_REQUEST['p1']))
{
	$qrypor.="CUR_PAR1 = '".$_REQUEST['p1']."', ";	
}
if(isset($_REQUEST['p2']))
{
	$qrypor.="CUR_PAR2 = '".$_REQUEST['p2']."', ";
}
if(isset($_REQUEST['p3']))
{
	$qrypor.="CUR_PAR3 = '".$_REQUEST['p3']."', ";
}
if(isset($_REQUEST['p4']))
{
	$qrypor.="CUR_PAR4 = '".$_REQUEST['p4']."', ";
}
if(isset($_REQUEST['p5']))
{
	$qrypor.="CUR_PAR5 = '".$_REQUEST['p5']."', ";
}
if(isset($_REQUEST['pl']))
{
	$qrypor.="CUR_LAB = '".$_REQUEST['pl']."', ";
}
if(isset($_REQUEST['pe']))
{
	$qrypor.="CUR_EXA = '".$_REQUEST['pe']."', ";
}
$qrypor.="CUR_HAB = '".$_REQUEST['ph']."' ";
$qrypor.="WHERE ";
$qrypor.="CUR_APE_ANO = '".$_REQUEST['ano']."' ";
$qrypor.="AND ";
$qrypor.="CUR_APE_PER = '".$_REQUEST['per']."' ";
$qrypor.="AND ";
$qrypor.="CUR_ASI_COD = '".$_SESSION['A']."' ";
$qrypor.="AND ";
$qrypor.="CUR_NRO ='".$_SESSION['G']."'";

$row=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qrypor,"busqueda");

require_once(dir_script.'msql_notaspar_doc.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");

$i=0;
$acumulado = 0;
do{

$ac = (($_REQUEST[sprintf('acu_%d', $i)] ));
	$acumulado = str_replace(".", ",", sprintf('%s', $ac));
	//echo "mmm".$ac"<br>";	
		if($ac > 0 && $_REQUEST[sprintf('obs_%d', $i)]==19){
		echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>La observaci&oacute;n 19 es solo para notas cualitativas.<br>Haga clic en el bot&oacute;n "Ayuda".</h3></center></font>');
	}
	if($ac > 0 && $_REQUEST[sprintf('obs_%d', $i)]==20){
		echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>La observaci&oacute;n 20 es solo para notas cualitativas.<br>Haga clic en el bot&oacute;n "Ayuda".</h3></center></font>');
	}
	
	
	//Validamos que lasnotas digitadas sean de tipo num�rico, y que esten entre 0 y 50.
	foreach($_REQUEST as $clave=>$valor)
	{
		//echo $valor."</p>";
		if((($valor==$_REQUEST[sprintf('nota_1%d', $i)]))
		||(($valor==$_REQUEST[sprintf('nota_2%d', $i)]))
		||(($valor==$_REQUEST[sprintf('nota_3%d', $i)]))
		||(($valor==$_REQUEST[sprintf('nota_4%d', $i)]))
		||(($valor==$_REQUEST[sprintf('nota_5%d', $i)]))
		||(($valor==$_REQUEST[sprintf('lab_%d', $i)]))
		||(($valor==$_REQUEST[sprintf('exa_%d', $i)]))
		||(($valor==$_REQUEST[sprintf('hab_%d', $i)])))
		{
			if((!is_numeric($valor))&&($valor!=NULL))
			{
			   echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
			   echo $clave."->".$valor." La nota digitada No es un valor num&eacute;rico</p>";
			   $nonum= $valor;
			   //die('<center><h3>Intente nuevamente Ingresando un caracter v�lido.</h3></center></font>');
			}
			elseif(($valor<0)&&($valor!=NULL))
			{
				echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
				echo $clave."->".$valor." Esta nota no puede menor que 0</p>";
				$nonum= $valor;
			}
			elseif(($valor>=51)&&($valor!=NULL))
			{
				echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
				echo $clave."->".$valor." Esta nota no puede ser mayor que 50</p>";
				$nonum= $valor;
			}
		}
	}
	/*echo "mmm".$_REQUEST['p1'];
	if(($_REQUEST[sprintf('nota_1%d', $i)]!="")&&($_REQUEST['P1']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del parcial 1.</h3></center></font>');
	}
	elseif(($_REQUEST[sprintf('nota_2%d', $i)]!="")&&($_REQUEST['P2']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del parcial 2.</h3></center></font>');
	}
	elseif(($_REQUEST[sprintf('nota_3%d', $i)]!="")&&($_REQUEST['P3']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del parcial 3.</h3></center></font>');
	}
	elseif(($_REQUEST[sprintf('nota_4%d', $i)]!="")&&($_REQUEST['P4']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del parcial 4.</h3></center></font>');
	}
	elseif(($_REQUEST[sprintf('nota_5%d', $i)]!="")&&($_REQUEST['P5']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del parcial 5.</h3></center></font>');
	}
	elseif(($_REQUEST[sprintf('nota_lab%d', $i)]!="")&&($_REQUEST['Pl']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del laboratorio.</h3></center></font>');
	}
	elseif(($_REQUEST[sprintf('nota_exa%d', $i)]!="")&&($_REQUEST['Pe']=="")&&($_SESSION['nivel']!='PREGRADO'))
	{
		echo "<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>Digite el valor del porcentaje correspondiente a las notas del ex&aacute;men.</h3></center></font>');
	}
	else
	{*/	
		//Hacemoa la acutaulizaci�n de las notas en la base de datos
		$consulta =	"UPDATE ACINS SET ";
		//Nota 1
		if(isset($_REQUEST[sprintf('nota_1%d', $i)]))
		{
			if(($_REQUEST[sprintf('nota_1%d', $i)]=="")||($_REQUEST[sprintf('nota_1%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_PAR1 =NULL,"; 
			}
			else
			{
				$consulta.= "INS_NOTA_PAR1 ='".$_REQUEST[sprintf('nota_1%d', $i)]."', ";
			}
		}
		
		//Nota 2 
		if(isset($_REQUEST[sprintf('nota_2%d', $i)]))
		{
			if(($_REQUEST[sprintf('nota_2%d', $i)]=="")||($_REQUEST[sprintf('nota_2%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_PAR2 =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_PAR2 ='".$_REQUEST[sprintf('nota_2%d', $i)]."', ";
			}
		}
		
		//Nota 3 
		if(isset($_REQUEST[sprintf('nota_3%d', $i)]))
		{
			if(($_REQUEST[sprintf('nota_3%d', $i)]=="")||($_REQUEST[sprintf('nota_3%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_PAR3 =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_PAR3 ='".$_REQUEST[sprintf('nota_3%d', $i)]."', ";
			}
		}
		
		//Nota 4
		if(isset($_REQUEST[sprintf('nota_4%d', $i)]))
		{
			if(($_REQUEST[sprintf('nota_4%d', $i)]=="")||($_REQUEST[sprintf('nota_4%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_PAR4 =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_PAR4 ='".$_REQUEST[sprintf('nota_4%d', $i)]."', ";
			}
		}
		//Nota 5 
		if(isset($_REQUEST[sprintf('nota_5%d', $i)]))
		{
			if(($_REQUEST[sprintf('nota_5%d', $i)]=="")||($_REQUEST[sprintf('nota_5%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_PAR5 =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_PAR5 ='".$_REQUEST[sprintf('nota_5%d', $i)]."', ";
			}
		}
		//Laboratorio 
		if(isset($_REQUEST[sprintf('lab_%d', $i)]))
		{
			if(($_REQUEST[sprintf('lab_%d', $i)]=="")||($_REQUEST[sprintf('lab_%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_LAB =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_LAB ='".$_REQUEST[sprintf('lab_%d', $i)]."', ";
			}
		}
		//ex�men 
		if(isset($_REQUEST[sprintf('exa_%d', $i)]))
		{
			if(($_REQUEST[sprintf('exa_%d', $i)]=="")||($_REQUEST[sprintf('exa_%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_EXA =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_EXA ='".$_REQUEST[sprintf('exa_%d', $i)]."', ";
			}
		}
		//habilitacion
		if(isset($_REQUEST[sprintf('hab_%d', $i)]))
		{
			if(($_REQUEST[sprintf('hab_%d', $i)]=="")||($_REQUEST[sprintf('hab_%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_HAB =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_HAB ='".$_REQUEST[sprintf('hab_%d', $i)]."', ";
			}
		}
		//bac
		/*if($ac=="")
		{
			$consulta.= "INS_NOTA_ACU =NULL ,";
		}
		else
		{
			$consulta.= "INS_NOTA_ACU ='".$ac."', ";
		}
		if(isset($_REQUEST[sprintf('acu_%d', $i)]))
		{
			if(($_REQUEST[sprintf('acu_%d', $i)]=="")||($_REQUEST[sprintf('acu_%d', $i)]==$nonum))
			{
				$consulta.= "INS_NOTA_ACU =NULL ,";
			}
			else
			{
				$consulta.= "INS_NOTA_ACU ='".$_REQUEST[sprintf('acu_%d', $i)]."', ";
			}
		}*/
	
		//observaciones
		if($_REQUEST[sprintf('obs_%d', $i)]=="")
		{
			$consulta.= "INS_OBS = 0 ,";
		}
		else
		{
			$consulta.= "INS_OBS = '".$_POST[sprintf('obs_%d', $i)]."', ";
		}
		$consulta.= "INS_USUARIO = '".$_SESSION['usuario_login']."' ";
		$consulta.=" WHERE ";
		$consulta.="INS_ANO = '".$_REQUEST['ano']."' ";
		$consulta.="AND ";
		$consulta.="INS_PER = ".$_REQUEST['per']." ";
		$consulta.="AND ";
		$consulta.="INS_ASI_COD ='".$_SESSION['A']."' ";
		$consulta.="AND ";
		$consulta.="INS_GR ='".$_SESSION["G"]."' ";
		$consulta.="AND ";
		$consulta.="INS_EST_COD ='".$_REQUEST[sprintf('cod_%d',$i)]."'";
		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
		//echo $consulta;
	//}
	$i++;
	
}while($i<=$_REQUEST['num_regs']-1);
?>