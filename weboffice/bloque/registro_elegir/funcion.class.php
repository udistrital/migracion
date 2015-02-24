<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionRegistro.class.php");

class registro_consultaCenso implements funcionRegistro
{

	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$datos="";
		$contador=0;
		$formulario="registro_consultarcenso";
		$tab=1;
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	?><form enctype='multipart/form-data' method="post" action="index.php" name="<? echo $formulario?>">
	<table width="100%" cellpadding="12" cellspacing="0"  align="center">
		<tbody>
			<tr>
				<td align="center" valign="middle">
					<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
						<tr>
							<td>
								<table align='center' width='100%' cellpadding='7' cellspacing='1' border=0>
									<tr class="bloquecentralencabezado">
										<td colspan="3" rowspan="1"><a href="<?		
										$variable="pagina=mesaDirectiva";
										$variable.="&accion=reiniciar";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable;	?>">Reiniciar Elecci&oacute;n</a>
										</td>
									</tr>
									<tr class='bloquecentralcuerpo centrar' >
										<td bgcolor='<? echo $this->tema->celda ?>'>
											<input class="botonGrande" value="Elegir" name="buscar" id="boton" tabindex='<? echo $tab++ ?>' type="button" onclick="xajax_elegirAsambleista(document.getElementById('boton').value)"><br>								
										</td>
									</tr>
									<tr class='bloquecentralcuerpo'>
										<td colspan="3" rowspan="1">
										<div id=divRegistro></div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	</form>
	<?
	}
	
	
	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
	{
		
	}
	
	
	function mostrarRegistro($configuracion,$tema,$id_entidad, $acceso_db, $formulario)
	{
				
	}
		
	function corregirRegistro()
	{
	
	}
}
?>
