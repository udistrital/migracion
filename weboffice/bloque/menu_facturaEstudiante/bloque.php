<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Menu principal
* @usage        
*****************************************************************************/



if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
$cripto=new encriptar();

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
$nueva_sesion=new sesiones($configuracion);
$nueva_sesion->especificar_enlace($enlace);
$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");

if($registro)
{
        $usuario=$registro[0][0];
}

?><table align="center" class="tablaMarcoLateral">
	<tbody>
		<tr>
			<td >
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2">
					<tr class="centralcuerpo">
						<td>
						<b>:.</b> Men&uacute;
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<br>
						<a href="<?		
							/*enlace historico de recibos de pagos*/
                                                        $indiceAcademico= $configuracion["host"]."/academicopro/index.php?";
	
                                                        $variable="pagina=admin_consultarHistoricoRecibos";
                                                        $variable.="&usuario=".$usuario;
                                                        $variable.="&action=loginCondor";
                                                        $variable.="&opcion=consultarEstudiante";
                                                        $variable.="&tipoUser=51";
                                                        $variable.="&modulo=Estudiante";
                                                        $variable.="&aplicacion=Condor";
                                                        $variable.="&tipoBusqueda=codigo";
                                                        $variable.="&datoBusqueda=".$usuario;
							$variable=$cripto->codificar_url($variable,$configuracion);
                                                        $enlaceHistoricoRecibosPago=$indiceAcademico.$variable; 
							echo $enlaceHistoricoRecibosPago;		
							?>"> Hist&oacute;rico Recibos</a>
							
						</td>
					</tr>
					
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=adminPago";
							//Codigo del Estudiante
							$variable.="&opcion=detallePago&usuario=".$_REQUEST['usuario']."&tipoUser=".$_REQUEST['tipoUser'];
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Detalle Matr&iacute;cula</a>							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=adminPago";
							//Codigo del Estudiante
							$variable.="&opcion=reciboActual&usuario=".$_REQUEST['usuario']."&tipoUser=".$_REQUEST['tipoUser'];
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Recibo actual</a>
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
							<a href="<?		
							$variable="pagina=adminPago";
							//Codigo del Estudiante
							$variable.="&opcion=diferirMatricula&usuario=".$_REQUEST['usuario']."&tipoUser=".$_REQUEST['tipoUser'];
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Diferir matricula</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
							<a href="<?		
							//Enlace ICETEX
							include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/menu_facturaEstudiante/crypto/Encriptador.class.php");
							$miCodificadorServiciosAcademicos=Encriptador::singleton();
							
							$usuario = $_REQUEST['usuario'];
							$identificacion = $_REQUEST['usuario'];
							$modulo = $_REQUEST['tipoUser'];
							
							$indiceSara = $configuracion["host"]."/serviciosacademicos/index.php?";
							$tokenCondor = "condorSara2014";
							$tokenCondor = $miCodificadorServiciosAcademicos->codificar($tokenCondor);
							$opcion = 'datos=';
							$variable="icetex&rol=estudiante";
							$variable.="&pagina=index";
							$variable.="&usuario=".$usuario;
							$variable.="&opcionPagina=icetex";
							$variable.="&modulo=".$modulo;
							$variable.="&token=".$tokenCondor;
							$variable=$miCodificadorServiciosAcademicos->codificar($variable);
							$enlaceIcetex = $indiceSara.$opcion.$variable;
							
							echo $enlaceIcetex;		
							?>">Crédito <img src="<?php echo $configuracion["host"] ?>/weboffice/grafico/icetex_02.png" alt="ICETEX" style="width:75px;height:15px;display:inline;"></img>
							 matricula</a>							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<br>
						<hr class="hr_subtitulo">
						<br>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>