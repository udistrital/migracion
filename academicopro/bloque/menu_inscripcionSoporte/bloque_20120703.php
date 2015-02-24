<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Luis Fernando Torres
Copyright (C) 2006-2012

Última revisión 30 de julio de 2012

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Luis Fernando Torres
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


?><table>
	<tbody>
		<tr>
			<td >
				<table>
                                  
					<tr>
                                                <td class="ab_name">
                                                    <b>:.</b> Men&uacute;<br>
						</td>
					</tr>
                                        <tr>
                                            <td>
                                                <button class="botonEnlacePreinscripcionDeshabilitado" disabled="true" onclick="window.location = 
                                                    '<?
                                                        switch ($_REQUEST['opcion']) {
                                                            
                                                            case 'horas':
                                                                $variable="pagina=admin_consultarInscripcionEstudianteHoras";
                                                                $variable.="&opcion=mostrarConsulta";
                                                                break;

                                                            case 'creditos':
                                                                $variable="pagina=admin_consultarInscripcionCreditosEstudiante";
                                                                $variable.="&opcion=mostrarConsulta";
                                                                break;

                                                            default:
                                                                echo 'No se ha definido el tipo de estudiante!!';exit;
                                                                break;
                                                        }
                                                           

                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                            echo $indice.$variable;	
                                                    ?>'
                                                    ">Inscribir
                                                </button>					
                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td>
                                                <button class="botonEnlacePreinscripcion" onclick="window.open( 
                                                    'http://pruebasoas.udistrital.edu.co/tutorial/inscripciones.html')
                                                    ">Tutorial
                                                </button>					
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button class="botonEnlacePreinscripcion" onclick="window.open( 
                                                    'http://pruebasoas.udistrital.edu.co/tutorial/inscripciones.html')
                                                    ">Horarios
                                                </button>					
                                            </td>
                                        </tr>-->
                                        <tr>
                                            <td>
                                                <button class="botonEnlacePreinscripcion" onclick="window.location = 
                                                    '<?
                                                            $variable="pagina=registro_reporteError";
                                                            $variable.="&opcion=mensajeError";
                                                            $variable.="&paginaRetorno=".$_REQUEST['pagina'];
                                                            $variable.="&opcionRetorno=".$_REQUEST['opcion'];
                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                            echo $indice.$variable;
                                                    ?>'
                                                    ">Reportar problema
                                                </button>					
                                            </td>
                                        </tr>                                        
					<tr>
						<td>
						<hr class="hr_subtitulo">
						<br>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>