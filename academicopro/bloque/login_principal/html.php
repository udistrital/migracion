<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
	$formulario="autenticacion";
	$validar="control_vacio(".$formulario.",'usuario')";
	$validar.="&& control_vacio(".$formulario.",'clave')";
	
?><script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]  ?>/md5.js" type="text/javascript" language="javascript"></script>
<form method="post" action="index.php" name="<?echo $formulario?>">
<table cellpadding="0" border="0" cellspacing="0">
  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_0_0.jpg"  style="width: 176px; height: 57px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_0_1.jpg"  style="width: 155px; height: 57px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_0_2.jpg"  style="width: 158px; height: 57px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_0_3.jpg"  style="width: 171px; height: 57px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_0_4.jpg"  style="width: 131px; height: 57px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_0_5.jpg"  style="width: 9px; height: 57px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_1_0.jpg"  style="width: 176px; height: 78px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_1_1.jpg"  style="width: 155px; height: 78px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_1_2.jpg"  style="width: 158px; height: 78px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_1_3.jpg"  style="width: 171px; height: 78px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_1_4.jpg"  style="width: 131px; height: 78px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_1_5.jpg"  style="width: 9px; height: 78px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_2_0.jpg"  style="width: 176px; height: 85px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_2_1.jpg"  style="width: 155px; height: 85px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_2_2.jpg"  style="width: 158px; height: 85px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_2_3.jpg"  style="width: 171px; height: 85px; border-width: 0px;"></a></td>
    	<?
		if(!isset($_REQUEST["no_usuario"]))
		{
		?>    
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_2_4.jpg"  style="width: 131px; height: 85px; border-width: 0px;"></a></td>
		<?
		}
		else
		{
		?>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_error_2_4.jpg"  style="width: 131px; height: 85px; border-width: 0px;"></a></td>		
		<?		
		}?>		    
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_2_5.jpg"  style="width: 9px; height: 85px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_3_0.jpg"  style="width: 176px; height: 27px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_3_1.jpg"  style="width: 155px; height: 27px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_3_2.jpg"  style="width: 158px; height: 27px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_3_3.jpg"  style="width: 171px; height: 27px; border-width: 0px;"></a></td>
<td colspan="1" class="login_celda1">
			<table align="center" border="0" cellpadding="0" cellspacing="2">
				<tr>
					<td><? $tab=1;?>
					<input  class="cuadro_login" maxlength="30" size="12" tabindex="<? echo $tab++;?>" name="usuario" >
					</td>
				</tr>
			</table>
		</td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_3_5.jpg"  style="width: 9px; height: 27px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_4_0.jpg"  style="width: 176px; height: 16px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_4_1.jpg"  style="width: 155px; height: 16px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_4_2.jpg"  style="width: 158px; height: 16px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_4_3.jpg"  style="width: 171px; height: 16px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_4_4.jpg"  style="width: 131px; height: 16px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_4_5.jpg"  style="width: 9px; height: 16px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_5_0.jpg"  style="width: 176px; height: 29px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_5_1.jpg"  style="width: 155px; height: 29px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_5_2.jpg"  style="width: 158px; height: 29px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_5_3.jpg"  style="width: 171px; height: 29px; border-width: 0px;"></a></td>
<td colspan="1" class="login_celda1">
			<table align="center" border="0" cellpadding="0" cellspacing="2">
				<tr>
					<td>
					<input class="cuadro_login" maxlength="60" size="12" tabindex="<?echo $tab++;?>	" name="clave" type="password" >
					<input type="hidden" name="action" value="login_principal">
					</td>
				</tr>
			</table>
		</td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_5_5.jpg"  style="width: 9px; height: 29px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_6_0.jpg"  style="width: 176px; height: 31px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_6_1.jpg"  style="width: 155px; height: 31px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_6_2.jpg"  style="width: 158px; height: 31px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_6_3.jpg"  style="width: 171px; height: 31px; border-width: 0px;"></a></td>
<td colspan="1" class="login_celda1">
			<table align="center" border="0" cellpadding="0" cellspacing="2">
				<tr>
					<td>
					<input class="cuadro_login" name="aceptar" type="button" value="Aceptar" tabindex="<? echo $tab++;?>" onclick="<?echo $formulario?>.clave.value = hex_md5(<?echo $formulario?>.clave.value);return(<? echo $validar; ?>)? document.forms['<? echo $formulario?>'].submit():false">
					</td>
				</tr>
			</table>
		</td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_6_5.jpg"  style="width: 9px; height: 31px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_7_0.jpg"  style="width: 176px; height: 101px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_7_1.jpg"  style="width: 155px; height: 101px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_7_2.jpg"  style="width: 158px; height: 101px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_7_3.jpg"  style="width: 171px; height: 101px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_7_4.jpg"  style="width: 131px; height: 101px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_7_5.jpg"  style="width: 9px; height: 101px; border-width: 0px;"></a></td>
</tr>

  <tr>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_8_0.jpg"  style="width: 176px; height: 183px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_8_1.jpg"  style="width: 155px; height: 183px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_8_2.jpg"  style="width: 158px; height: 183px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_8_3.jpg"  style="width: 171px; height: 183px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_8_4.jpg"  style="width: 131px; height: 183px; border-width: 0px;"></a></td>
    <td><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["bloques"]."/login_principal/" ?>imagen/index_8_5.jpg"  style="width: 9px; height: 183px; border-width: 0px;"></a></td>
</tr>

</table>

