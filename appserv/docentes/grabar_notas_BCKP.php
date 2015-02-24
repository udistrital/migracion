<?PHP
//Validamos que los porcentajes digitados sean de tipo num�rico
foreach($_POST as $clave=>$valor)
{
	if($clave==p1||$clave==p2||$clave==p3||$clave==p4||$clave==p5||$clave==pl||$clave==pe){
		if((!is_numeric($valor))&&($valor!=NULL)){
			echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
			//$nonum= $valor;
			echo $clave."->".$valor."La nota digitada NO es un valor num&eacute;rico</p>";
			
			//die('<center><h3>Intente nuevamente Ingresando un caracter v�lido.</h3></center></font>');
		}
		
	}
}
$acupor = $_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['pl']+$_POST['pe'];
if($acupor>100){
	echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
	die('<center><h3>Revise los porcentajes de las notas, superan el 100%.</h3></center></font>');
}
if($acupor==0){
	echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
	die('<center><h3>Digite los porcentajes de las notas. Recuerde que no debe superar el 100%.</h3></center></font>');
}
//Actualizamos los porcentajes en la base de datos
require(dir_conect.'conexion.php');
$qrypor = OCIParse($oci_conecta, "UPDATE ACCURSO
									SET CUR_PAR1 = :bp1,
								    CUR_PAR2 = :bp2,
									CUR_PAR3 = :bp3,
									CUR_PAR4 = :bp4,
									CUR_PAR5 = :bp5,
									CUR_LAB = :bpl,
									CUR_EXA = :bpe,
									CUR_HAB = :bhab
 							    WHERE CUR_APE_ANO = ".$_POST['ano']."
							    AND CUR_APE_PER = ".$_POST['per']."
								AND CUR_ASI_COD =".$_SESSION["A"]."
								AND CUR_NRO =".$_SESSION["G"]);
										
OCIBindByName($qrypor, ":bp1", $_POST['p1']);
OCIBindByName($qrypor, ":bp2", $_POST['p2']);
OCIBindByName($qrypor, ":bp3", $_POST['p3']);
OCIBindByName($qrypor, ":bp4", $_POST['p4']);
OCIBindByName($qrypor, ":bp5", $_POST['p5']);
OCIBindByName($qrypor, ":bpl", $_POST['pl']);
OCIBindByName($qrypor, ":bpe", $_POST['pe']);
OCIBindByName($qrypor, ":bhab", $_POST['ph']);
OCIExecute($qrypor, OCI_DEFAULT);// or die(Ora_ErrorCode());
OCICommit($oci_conecta);
$i=0;
$acumulado = 0;
do{
$ac = (($_POST[sprintf('nota_1%d', $i)] * $_POST['p1'])/100)+
	  (($_POST[sprintf('nota_2%d', $i)] * $_POST['p2'])/100)+
	  (($_POST[sprintf('nota_3%d', $i)] * $_POST['p3'])/100)+
	  (($_POST[sprintf('nota_4%d', $i)] * $_POST['p4'])/100)+
	  (($_POST[sprintf('nota_5%d', $i)] * $_POST['p5'])/100)+
	  (($_POST[sprintf('lab_%d', $i)] * $_POST['pl'])/100)+
	  (($_POST[sprintf('exa_%d', $i)] * $_POST['pe'])/100);
	  $acumulado = str_replace(".", ",", sprintf('%s', $ac));
		if($ac > 0 && $_POST[sprintf('obs_%d', $i)]==19){
		echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>La observaci&oacute;n 19 es solo para notas cualitativas.<br>Haga clic en el bot&oacute;n "Ayuda".</h3></center></font>');
	}
	if($ac > 0 && $_POST[sprintf('obs_%d', $i)]==20){
		echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
		die('<center><h3>La observaci&oacute;n 20 es solo para notas cualitativas.<br>Haga clic en el bot&oacute;n "Ayuda".</h3></center></font>');
	}
	
	
	//Validamos que lasnotas digitadas sean de tipo num�rico, y que esten entre 0 y 50.
	foreach($_POST as $clave=>$valor)
	{
		//echo $valor."</p>";
		if((($valor==$_POST[sprintf('nota_1%d', $i)]))
			||(($valor==$_POST[sprintf('nota_2%d', $i)]))
			||(($valor==$_POST[sprintf('nota_3%d', $i)]))
			||(($valor==$_POST[sprintf('nota_4%d', $i)]))
			||(($valor==$_POST[sprintf('nota_5%d', $i)]))
			||(($valor==$_POST[sprintf('lab_%d', $i)]))
			||(($valor==$_POST[sprintf('exa_%d', $i)]))
			||(($valor==$_POST[sprintf('hab_%d', $i)]))){
			if((!is_numeric($valor))&&($valor!=NULL)){
			   echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
			   echo $clave."->".$valor." La nota digitada No es un valor num&eacute;rico</p>";
			   $nonum= $valor;
			   //die('<center><h3>Intente nuevamente Ingresando un caracter v�lido.</h3></center></font>');
			}
			elseif(($valor<0)&&($valor!=NULL)){
				echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
				echo $clave."->".$valor." Esta nota no puede menor que 0</p>";
				$nonum= $valor;
			}
			elseif(($valor>=51)&&($valor!=NULL)){
				echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
				echo $clave."->".$valor." Esta nota no puede ser mayor que 50</p>";
				$nonum= $valor;
			}
		} 
	}
		
	//Hacemoa la acutaulizaci�n de las notas en la base de datos
	$consulta =	"UPDATE ACINS ";
	 
	//Nota 1
	if(($_POST[sprintf('nota_1%d', $i)]=="")||($_POST[sprintf('nota_1%d', $i)]==$nonum))
	{
		$consulta.= "SET INS_NOTA_PAR1 =NULL,"; 
	}
	else
	{
		$consulta.= "SET INS_NOTA_PAR1 =".$_POST[sprintf('nota_1%d', $i)].",";			
	}
	//Nota 2 
	if(($_POST[sprintf('nota_2%d', $i)]=="")||($_POST[sprintf('nota_2%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_PAR2 =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_PAR2 =".$_POST[sprintf('nota_2%d', $i)].",";
	}
	//Nota 3 
	if(($_POST[sprintf('nota_3%d', $i)]=="")||($_POST[sprintf('nota_3%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_PAR3 =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_PAR3 =".$_POST[sprintf('nota_3%d', $i)].",";
	}
	//Nota 4
	if(($_POST[sprintf('nota_4%d', $i)]=="")||($_POST[sprintf('nota_4%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_PAR4 =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_PAR4 =".$_POST[sprintf('nota_4%d', $i)].",";
	}
	//Nota 5 
	if(($_POST[sprintf('nota_5%d', $i)]=="")||($_POST[sprintf('nota_5%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_PAR5 =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_PAR5 =".$_POST[sprintf('nota_5%d', $i)].",";
	}
	//Laboratorio 
	if(($_POST[sprintf('lab_%d', $i)]=="")||($_POST[sprintf('lab_%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_LAB =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_LAB =".$_POST[sprintf('lab_%d', $i)].",";
	}
	//ex�men 
	if(($_POST[sprintf('exa_%d', $i)]=="")||($_POST[sprintf('exa_%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_EXA =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_EXA =".$_POST[sprintf('exa_%d', $i)].",";
	}
	//habilitacion
	if(($_POST[sprintf('hab_%d', $i)]=="")||($_POST[sprintf('hab_%d', $i)]==$nonum))
	{
		$consulta.= "INS_NOTA_HAB =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_HAB =".$_POST[sprintf('hab_%d', $i)].",";
	}
	//bac
	if($ac=="")
	{
		$consulta.= "INS_NOTA_ACU =NULL ,";
	}
	else
	{
		$consulta.= "INS_NOTA_ACU =".$ac.",";
	}
 
	//observaciones
	if($_POST[sprintf('obs_%d', $i)]=="")
	{
		$consulta.= "INS_OBS = 0 ,";
	}
	else
	{
		$consulta.= "INS_OBS = ".$_POST[sprintf('obs_%d', $i)].",";
	}
	$consulta.= "INS_USUARIO = ".$_SESSION['usuario_login'];

	$consulta.=" WHERE INS_ANO = ".$_POST['ano']."
								AND INS_PER = ".$_POST['per']."
								AND INS_ASI_COD =".$_SESSION["A"]."
								AND INS_GR =".$_SESSION["G"]."
								AND INS_EST_COD = ".$_POST[sprintf('cod_%d',$i)];
	$qry = OCIParse($oci_conecta,$consulta); 
	$registro=OCIExecute($qry, OCI_DEFAULT); //or die(Ora_ErrorCode());
	  
	OCICommit($oci_conecta);
	//echo $consulta;
	  
	$i++;
}while($i<=$_POST['num_regs']-1);
cierra_bd($qrypor, $oci_conecta);
cierra_bd($qry, $oci_conecta);
	
?>