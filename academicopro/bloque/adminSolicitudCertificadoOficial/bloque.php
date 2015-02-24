<?
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
* @description  Menu principal del bloque entidades de salud
* @usage        
*****************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
$indiceSeguro=$configuracion["host"].$configuracion["site"]."/index.php?";
$cripto=new encriptar();
//$usuario=$_REQUEST['usuario'];
$opcion=$_REQUEST['opcion'];
/*$user=$_REQUEST['tipoUser'];
echo $usuario;
echo $action;
echo $user;
exit;
*/

//Codigo para rescatar valores guardados en una session de usuario
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
$nueva_sesion=new sesiones($configuracion);
$nueva_sesion->especificar_enlace($enlace);
$level=$nueva_sesion->especificar_nivel($opcion);
$esta_sesion=$nueva_sesion->numero_sesion();
//
//echo $esta_sesion;
$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
$nivel=$nueva_sesion->rescatar_valor_sesion($configuracion,"nivelUsuario");
//$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
if($registro)
{

        //echo "registro".$registro[0][0];
}



?><table align="center" class="tablaMenu">
	<tbody>
		<tr>
			<td >
				<br>
                                <table align="center" border="0" cellpadding="5" cellspacing="2" width="80%">
                                        <table class="formulario" align="center">
						<tr  class="bloquecentralencabezado">
							<td align="center">
								<p><span class="texto_negrita">SOLICITUDES DE CERTIFICADO DE CALIFICACIONES PARA USO EXTERNO</span></p>
							</td>
						</tr>
                                        </table>
                                    <br>
					<table class="formulario" align="center" >
                                                <?
                                                if($opcion==51 or $opcion==52 or $opcion==83 or $nivel[0][0]==51 or $nivel[0][0]==52 or $nivel[0][0]==83)
                                                {
                                                    if(is_array($nivel))
                                                    {
                                                        $opcion=$nivel[0][0];
                                                    }
                                                    ?>

                                                <tr class="formulario">
                                                    <td align="center">
                                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]?>/chk.png" border='0' >                                                        
                                                    </td>
                                                    <td>
                                                    <a href="<?
                                                            $variable="pagina=solicitudNuevaCertificado";
                                                            $variable.="&& opcion=nuevo";
                                                            $variable.="&& clase=".$opcion;
                                                            //$variable.="&usuario=".$_REQUEST['opcion'];
                                                            //var_dump ($variable);
                                                            //$variable.="&xajax=caracteristicasEspacio|asociarEspacioMalla";
                                                            //$variable.="&xajax_file=mallasEspacios";
                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                            echo $indice.$variable;
                                                            ?>">  Crear Solicitud de Certificado Oficial</a>

                                                    </td>
                                                </tr>
                                                <?}?>
                                                <tr class="formulario">
                                                    <td align="center">
                                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]?>/viewrel1.png" border='0' >                                                        
                                                    </td>
                                                    <td>
                                                    <a href="<?
                                                            $variable="pagina=consultarSolicitudCertificado";
                                                            $variable.="&opcion=nuevo";
                                                            $variable.="&& clase=".$opcion;
                                                            //$variable.="&xajax=caracteristicasEspacio|asociarEspacioMalla";
                                                            //$variable.="&xajax_file=mallasEspacios";
                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                            echo $indice.$variable;
                                                            ?>">  Consultar Solicitud de Certificado</a>

                                                    </td>
                                                </tr>
                                                <?if ($opcion==83 or $nivel[0][0]==83)
                                                {
                                                    if(is_array($nivel))
                                                    {
                                                        $opcion=$nivel[0][0];
                                                    }
                                                    ?>

                                                <tr class="formulario">
                                                    <td align="center">
                                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]?>/editarGrande.png" border='0' >                                                        
                                                    </td>
                                                    <td>
                                                    <a href="<?
                                                            $variable="pagina=editarSolicitudCertificado";
                                                            $variable.="&opcion=nuevo";
                                                            $variable.="&& clase=".$opcion;
                                                            //$variable.="&xajax=caracteristicasEspacio|asociarEspacioMalla";
                                                            //$variable.="&xajax_file=mallasEspacios";
                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                            echo $indice.$variable;
                                                            ?>">  Editar Solicitud de Certificado</a>

                                                    </td>
                                                </tr>
                                                <tr class="formulario">
                                                    <td align="center">
                                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]?>/boton_borrar.png" border='0' >                                                        
                                                    </td>
                                                    <td>
                                                    <a href="<?
                                                            $variable="pagina=borrarSolicitudCertificado";
                                                            $variable.="&opcion=ingresar";
                                                            $variable.="&& clase=".$opcion;
                                                            //$variable.="&xajax=caracteristicasEspacio|asociarEspacioMalla";
                                                            //$variable.="&xajax_file=mallasEspacios";
                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                            echo $indice.$variable;
                                                            ?>">  Borrar Solicitud de Certificado</a>

                                                    </td>
                                                </tr>
                                                <?}?>
                                        </table>
 				</table>
			</td>
		</tr>
	</tbody>
</table>
