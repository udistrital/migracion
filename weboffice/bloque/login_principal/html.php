<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
	if(!isset($GLOBALS["autorizado"]))
	{
		include("../index.php");
		exit;		
	}
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/notiCondor.class.php");
	$cripto=new encriptar();
	$formulario="autenticacion";
	$validar="control_vacio(".$formulario.",\'usuario\')";
	$validar.="&& control_vacio(".$formulario.",\'clave\')";
	$validar2="control_vacio(".$formulario.",'usuario')";
	$validar2.="&& control_vacio(".$formulario.",'clave')";
	$indice=$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/login_principal/";
	$directorio=$configuracion["host"].$configuracion["site"]."/index.php?";
		
?>
<script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]  ?>/md5.js" type="text/javascript" language="javascript"></script>
<form method="post" action="index.php" name="<?echo $formulario?>">
<table class="tablaBase">
  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_0_0.jpg" style="width: 8px;  height: 214px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_0_1.jpg" style="width: 99px;  height: 214px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_0_2.jpg" style="width: 167px;  height: 214px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_0_3.jpg" style="width: 107px;  height: 214px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_0_4.jpg" style="width: 182px;  height: 214px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_0_5.jpg" style="width: 224px;  height: 214px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_0_6.jpg" style="width: 9px;  height: 214px; border-width: 0px;"></td>
</tr>
  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_1_0.jpg" style="width: 8px;  height: 156px; border-width: 0px;"></td>
    <td colspan="2" rowspan="2">
    <div id="cuadroLogin">
     <SCRIPT>
	<!--
	function verificacion()
	{
		if(<? echo $validar2; ?>)
		{
			<?echo $formulario?>.clave.value = hex_md5(<?echo $formulario?>.clave.value);
			document.forms['<? echo $formulario?>'].submit()
		}
		else
		{
			false
		}
	}
	if (is_ie)
	{
		document.writeln('<table align="center" border="0" cellpadding="0" cellspacing="2">\n<tr>\n<td><? $tab=1;?>\n<span class="textoNivel1">Usuario: &nbsp;&nbsp;</span></td><td><input  class="cuadro_login" maxlength="30" size="8" tabindex="<? echo $tab++;?>" name="usuario" >\n</td>\n</tr>\n<tr>\n<td>\n<span class="textoNivel1">Clave:  <span></td><td> <input class="cuadro_login" maxlength="60" size="8" tabindex="<?echo $tab++;?>" name="clave" type="password" onkeyup="if(!enter(event)){if(<? echo $validar; ?>){<?echo $formulario?>.clave.value = hex_md5(<?echo $formulario?>.clave.value); document.forms[\'<? echo $formulario?>\'].submit()}else{false}}">\n<input type="hidden" name="action" value="login_principal">\n</td>\n</tr>\n</table>')
	}
	else
	{
	 	document.writeln('<table align="center" border="0" cellpadding="0" cellspacing="2">\n<tr>\n<td ><? $tab=1;?>\n<span class="textoNivel1">Usuario: &nbsp;&nbsp;</span></td><td><input  class="cuadro_login" maxlength="30" size="12" tabindex="<? echo $tab++;?>" name="usuario" >\n</td>\n</tr>\n<tr>\n<td>\n<span class="textoNivel1">Clave:</span></td><td> <input class="cuadro_login" maxlength="60" size="12" tabindex="<?echo $tab++;?>" name="clave" type="password" onkeyup="if(!enter(event)){if(<? echo $validar; ?>){<?echo $formulario?>.clave.value = hex_md5(<?echo $formulario?>.clave.value); document.forms[\'<? echo $formulario?>\'].submit()}else{false}}">\n<input type="hidden" name="action" value="login_principal"></td></tr><tr><td colspan="2" class="centrar"><br><input class="cuadro_login" name="aceptar" type="button" value="Aceptar" tabindex="<? echo $tab++;?>" onclick="verificacion()" ></td></tr></table>')
	}
	// -->
	</SCRIPT>
    <div>
    <div id="submenuLogin1" class="textoNivel1">
    	<ul id="menu1" class="textoTema">
    	<li>¿Olvid&oacute; su Clave?</li>
    	<li>Usuario Nuevo</li>
    	<li>Servicios del Portal</li>
    	</ul>
    </div>
    <div id="mensaje1" class="textoNivel1 derecha textoTema">
    	<p>Este sitio provee informaci&oacute;n que solo es de inter&eacute;s del personal docente, estudiantes y funcionarios de  la
    	Universidad Distrital Francisco José de Caldas.</p>
    </div>
    </td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_1_3.jpg"  style="width: 107px; height: 156px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_1_4.jpg"  style="width: 182px; height: 156px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_1_5.jpg"  style="width: 224px; height: 156px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $indice ?>index_1_6.jpg" style="width: 9px;  height: 156px; border-width: 0px;"></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_2_0.jpg" style="width: 8px;  height: 117px; border-width: 0px;"></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_2_3.jpg"  style="width: 107px; height: 117px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_2_4.jpg"  style="width: 182px; height: 117px; border-width: 0px;"></a></td>
    <td id="notiCondor" >
    <div id="noticiero" class="textoNivel0 textoTema">
    <? 
    	$noticia=new notiCondor();
    	
    	$titular=$noticia->titular($configuracion, 2);
    	if(is_array($titular))
    	{
    		echo "<table>";
    		$j=0;
    		
    		while(isset($titular[$j][0]))
    		{
    			$variable="pagina=notiCondor";
    			$variable="&&registro=".$titular[$j][0];
    			echo "<tr>";
    			echo "<td class='textoNivel0 textoTema'>";
    			echo "<span class='texto_negrita'>".date("d/m/Y",$titular[$j][2])."</span>- ".htmlentities($titular[$j][1]);
    			echo "<br><br>";
    			echo "</td>";
    			echo "</tr>";
    			$j++;
    		}
    		echo "</table>";
    	}
    	else
    	{
    		echo $titular;
    	}
    
    ?>
    </div>
    </td>
    <td><img alt=" " src="<? echo $indice ?>index_2_6.jpg" style="width: 9px;  height: 117px; border-width: 0px;"></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_3_0.jpg" style="width: 8px;  height: 15px; border-width: 0px;"></td>
    <td rowspan="3" colspan="2">
    <div id="mensaje1" class="textoNivel1 textoTema centrar">
    <span class="texto_negrita">Oficina Asesora de Sistemas</span><br>
    Tel&eacute;fonos 3238400 Ext. 1112<br>
    computo@udistrital.edu.co
    </div>
    </td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_3_3.jpg"  style="width: 107px; height: 15px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_3_4.jpg"  style="width: 182px; height: 15px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_3_5.jpg"  style="width: 224px; height: 15px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $indice ?>index_3_6.jpg" style="width: 9px;  height: 15px; border-width: 0px;"></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_4_0.jpg" style="width: 8px;  height: 19px; border-width: 0px;"></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_4_3.jpg"  style="width: 107px; height: 19px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_4_4.jpg"  style="width: 182px; height: 19px; border-width: 0px;"></a></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_4_5.jpg"  style="width: 224px; height: 19px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $indice ?>index_4_6.jpg" style="width: 9px;  height: 19px; border-width: 0px;"></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_5_0.jpg" style="width: 8px;  height: 26px; border-width: 0px;"></td>
    <td><a href="#"><img alt=" " src="<? echo $indice ?>index_5_3.jpg"  style="width: 107px; height: 26px; border-width: 0px;"></a></td>
    <td class="webofficeFondoOscuro" colspan="3">
    <div id="divMenu2">
	<ul id="menu2" class="textoNivel1">
		<li>Políticas de Privacidad</li>
		<li>Consideraciones de Seguridad </li>
		<li>Ayuda</li>
	</ul>
    </div>
    </td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $indice ?>index_6_0.jpg" style="width: 8px;  height: 53px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_6_1.jpg" style="width: 99px;  height: 53px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_6_2.jpg" style="width: 167px;  height: 53px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_6_3.jpg" style="width: 107px;  height: 53px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_6_4.jpg" style="width: 182px;  height: 53px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_6_5.jpg" style="width: 224px;  height: 53px; border-width: 0px;"></td>
    <td><img alt=" " src="<? echo $indice ?>index_6_6.jpg" style="width: 9px;  height: 53px; border-width: 0px;"></td>
</tr>
</table>
</form>