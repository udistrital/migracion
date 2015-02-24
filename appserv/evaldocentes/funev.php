<?php
 require_once('funaccess.php'); 
function valida_campo($x) {
     if ($x) {
        return  $x; 
     } else {
        return  0;
     }   
  }
	$rad4[1] = "S&iacute;";
	$rad4[2] = "No";
//----------------------------------------------------------------------------------
	$rad5[1] = "80 - 100%";
	$rad5[2] = "50&nbsp;-&nbsp;79%";
	$rad5[3] = "1&nbsp;-&nbsp;49%";
//----------------------------------------------------------------------------------
	$tab[1] = "<TABLE WIDTH=&quot;100%&quot; BORDER=1 CELLSPACING=1 CELLPADDING=1>";
	$tab[2] = "</TABLE><br>";
	$tab[3] = "</TABLE>";
//--------------------------------------------------------------------------------------------
function blq($docente,$cra,$vin,$sec,$b,$b11,$b12,$b21,$b22,$b31,$b32,$b41,$b42,$b51,$b52) {
	global $fmtonum;
					//($Id $del $docente; $Cra_cod; $vinculo: 1 = $Estudiante, 2= $Docente, 3= $Coordinador { 
					//Sec para extraer de session el Encabezado; b= No. bloques; b11=tipo de bloque, 
					//b12=hasta la pregunta x1 del 1er bloque, 
					//b21= tipo de bloque, b22= hasta la pregunta x2 del 2do bloque,... )
					//M�ximo 5 bloques	

	$bxy[1][1] = $b11	; $bxy[1][2] = $b12;
	$bxy[2][1] = $b21	; $bxy[2][2] = $b22;	
	$bxy[3][1] = $b31	; $bxy[3][2] = $b32;
	$bxy[4][1] = $b41	; $bxy[4][2] = $b42;
	$bxy[5][1] = $b51	; $bxy[5][2] = $b52;

	$encab =$_SESSION["sec".$sec];

	for ($k=0; $k<=$b; $k++) { 			//Encabezado m�s No. bloques;
		//if b=4 and k=3 then	'Tercer bloque con pregunta nula
		//else
		global $tab;
			echo $tab[1];
				if ($k == 0) { 
					echo $tab[3];
					if ($encab != ""){
						echo $tab[1];
						?><center><TR><TD colspan=4 style="COLOR: red" bgcolor=gold width="930" align="center"> 
							<? echo $encab?></TD></TR></center><?
							
						echo $tab[3];
					}
					echo $tab[1];
					////////////////////// Inicio Prueba----------
					//	echo $vin." Despliegue de prueba ";
					//	cargadoc($docente, $cra);	
					//////////////////////	Fin de Prueba----------			
					if ($vin == 30 || $vin == 4 || $vin == 16) {
						cargadoc($docente, $cra);	//Incluir carga del docente;
					}
				} else {
						//blqx($bxy[$k][1], $bxy[$k-1][2]+1, $bxy[$k][2]);	//blqx(bloque tipo, desde la pregunta, hasta la pregunta);
						if ($bxy[$k][1] == 6)
						{
						 //insertar control de texto
						 
							 //Imprimimos el texto que va en el textarea
							if ($fmtonum==7 || $fmtonum==15)
							{
							$texto_textarea="<b>Respetado (a) estudiante:</b> Por favor enuncie las observaciones  que tiene sobre el desempe&ntilde;o del  (la ) docente:";
							}
							if (($fmtonum==10)||($fmtonum==12)||($fmtonum==13)||($fmtonum==6)||($fmtonum==16))
							{
							$texto_textarea="<b>Respetado (a) profesor:</b> Por favor enuncie las observaciones  que tiene sobre esta evaluaci&oacute;n:";
							}
							if (($fmtonum==9)||($fmtonum==14)||($fmtonum==8) || ($fmtonum==17))
							{
							$texto_textarea="<b>Se&ntilde;ores (as) profesores (as) o Decanos (as):</b>  Por favor enuncie las observaciones  que tiene sobre esta evaluaci&oacute;n:";
							}
							
							//************************************
							if($fmtonum!=11)
							{
								echo "$texto_textarea</p>
								<textarea id=obs name=obs cols=80 rows=3 onKeyDown='javascript:ctatxt(this.form.obs.value)'
								onKeyUp='javascript:ctatxt(this.form.obs.value)'></textarea><br>";
							}
						}
						else {
							blqx(7, $bxy[$k-1][2]+1, $bxy[$k][2]);	//blqx(bloque tipo fijo en 7, desde la pregunta, hasta la pregunta);
						}

				}
			    	echo $tab[2];
			    	if ($k == 0) {
					echo $tab[1];
					///// Inicio Prueba ----
					
					//	<TR ><TD colspan=4 width="920" align="center"><center>ACTIVIDAD DOCENTE SEG�N PLAN DE TRABAJO</center></TD></TR>  
					////  Fin Prueba -----
					if($fmtonum==15)
					{
						echo "<tr><td><p align='justify'><b>Respetado (a) estudiante:</b> A continuaci&oacute;n se presenta un conjunto de aspectos relacionados con el desarrollo de la C&aacute;tedra durante
						el presente semestre. Eval&uacute;elos y realice los aportes necesarios.</TD></TR>";
					}
					if ($vin == 4 || $vin == 16 || $vin == 17) {?>
						<TR ><TD colspan=4 width="920" align="center"><STRONG><center>ACTIVIDAD DOCENTE SEG&Uacute;N PLAN DE TRABAJO</center></STRONG></TD></TR>  <?
					}
					if ($fmtonum==7 || $fmtonum==15) {?>
						<TR ><TD colspan=4 width="920" align="center"><STRONG><center>ASPECTOS A EVALUAR</center></STRONG></TD></TR>  <?
					}
					echo $tab[2];
				}			
	}
}
//--------------------------------------------------------------------------------------------
function blqx($k, $l ,$m) {
	$pre = $_SESSION["pre"];
		$num_opciones = $pre[8][$l];
		$valor = explode(",",$pre[9][$l]); //valores
		$texto = explode(",",$pre[10][$l]); //texto
	qytiporadio ($k,1,0,0,$num_opciones,$valor,$texto);	//Encabezado de bloque;
	//---------------------------------------------------
	for ($j=$l-1; $j<=$m; $j++) { 
		if ($pre[3][$j] == 0) {?>
			<TR>  		<TD> <? echo $pre[2][$j]			//subtitulo  ?> </TD> 	  	</TR> <?
		//else
		}?>
			<TR>  		<TD> <? echo $pre[1][$j]?>	</TD> 	<? 
						qytiporadio ($k,2,$pre[4][$j],$pre[5][$j],$num_opciones,$valor,$texto); //Cuerpo de bloque ?> 	</TR> <?
	}
}
//--------------------------------------------------------------------------------------------
function qytiporadio($a,$b,$x,$y,$w,$v,$t) {	// $b:encabezado o preguntas del bloque; $w: n�mero de opciones en el bloque
		global $rad1;							// $v: arreglo de valores; $t: arreglo de texto
		global $rad2;
		global $rad3;
		global $rad4;
		global $rad5;

		switch ($a) { 	//Bloque tipo;
			case 7:
				if ($b == 1) {//b=1 Encab de bloque ?>
						<TD width="100%" align=center><font size = 1><STRONG><?
					for ($i=0; $i<=$w; $i++) { 
						if ($t[$i-1] != " "){
							if ($i<$w) {
								//echo $t[$i-1+$w]." ".$t[$i-1]." ";
							}else{
								//echo $t[$i-1+$w]." ".$t[$i-1];
							}
							
						}
					}
					?></STRONG></font></TD><?
					for ($i=1; $i<=$w; $i++) { ?>
						<TD><? echo $t[$i-1+$w]; ?></TD><?
					}
				} elseif ($b = 2) {; //b=2 Cuerpo de bloque
					for ($i=1; $i<=$w; $i++) { 
							if ($i < $w || ($i == $w && $y =="S" )) {?>
								<TD><center><INPUT type="radio" id=r<? echo $x?>
								name=r<? echo $x ;?> 
								value=<? echo $v[$i-1] ;?> 
								title=<? //echo $t[$i]; ?>></center></TD>
								<?
							}else {?>
								<TD><center>-</center></TD><?
							}
					}
				}
				break;
						
		}
	}
//----------------------------------------------------------------------------------
?>