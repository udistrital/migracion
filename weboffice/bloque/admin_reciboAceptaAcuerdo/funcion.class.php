<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
require_once($configuracion["raiz_documento"].$configuracion["javascript"]."/Twig/Autoloader.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");	
include_once("sql.class.php");	
		

class funciones_admin_reciboAceptaAcuerdo extends funcionGeneral
{

	function __construct($configuracion)
	{
		$this->acceso_mysql=$this->conectarDB($configuracion,'');
		$this->acceso_oci=$this->conectarDB($configuracion,'estudiante');
		$this->usuario=$this->rescatarValorSesion($configuracion,$this->acceso_mysql,"usuario");
		$this->cripto=new encriptar();
		$this->sql=new sql_aceptaAcuerdo();
		$this->configuracion=$configuracion;
		$this->indice=$configuracion["host"]."/weboffice/index.php?";
		$this->error=array();
		$this->confirm = "";
		
	}
	
	function pintarAcuerdo(){
		echo '<center><div class="tablaMarco bloquecentralcuerpo marcoSombraGris">
							<div class="marcoSombraBlanco">
							<center>
							<h2>  ACTA DE COMPROMISO ACAD&Eacute;MICO</h2>
							<hr class="hr_subtitulo"><br/><br/>
							</center>

							De conformidad con los parámetros establecidos en la normatividad interna de la <span class="texto_negrita">UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS</span> y con el objetivo primordial de prevenir el bajo rendimiento académico y la deserción estudiantil, el estudiante registrado en esta cuenta acepta estar incurso en situación de prueba académica o bajo rendimiento académico; y por medio de este registro digital:					
	
								<br/>
								<br/><center>
								<b>SE COMPROMETE A:</b>
								</center><br/>
								
								<ul>
								<li>
									Cumplir cabalmente con los deberes académicos, vigentes a partir de su matrícula, entre los que se incluye: la asistencia a clases, la presentación de trabajos, evaluaciones y demás actividades definidas en los Syllabus; o aquellas acordadas al inicio del semestre con los docentes encargados de los espacios académicos inscritos.
								</li>
								<li>
									Colaborar activamente con el Profesor Consejero asignado por el Proyecto Curricular, para identificar las causales de su bajo rendimiento.
								</li>
								<li>
									Acudir a las reuniones de seguimiento relacionadas con el tema de prueba académica o bajo rendimiento
								</li>
								<li>
									Reportar oportunamente a la Coordinación del Proyecto Curricular o a la Oficina de Bienestar Institucional, cualquier situación que ponga en riesgo el cumplimiento de este compromiso.
								</li>
								<br/>
								<p>
									Conforme a la Ley 527 de 1999, el Decreto-ley 19 de 2012, la Ley 1341 de 2009 y relacionadas, la aceptación del presente compromiso digital tiene la misma validez jurídica que el trámite realizado a través de otros medios.
								</p>
						</div>		
								';
								
								
								
							echo'<center><br/>	<table class="">
									<tbody><tr class="bloquecentralcuerpo">';
									echo'<td>';
											echo "<form action='index.php'>";
											echo "<input type='submit' value='ACEPTO'>";
											$variable="opcion=acepto";
											$variable="&action=admin_reciboAceptaAcuerdo";
											$variable=$this->cripto->codificar_url($variable,$this->configuracion);
											echo "<input type='hidden' name='formulario' value='{$variable}'>";	
											echo "</form>";
									echo'</td>';
									echo'<td>';
											echo "<form action='index.php'>";
											echo "<input type='submit' value='NO ACEPTO'>";
											$variable="pagina=adminPago";
											$variable.="&opcion=reciboActual";
											$variable=$this->cripto->codificar_url($variable,$this->configuracion);
											echo "<input type='hidden' name='formulario' value='{$variable}'>";
											echo "</form>";
									echo'</td>';
											
							echo'</tr>
								</tbody></table></center>';
								
							echo '</p>														
				</div><br/><br/><br/><br/></center>
		';

		

				
	}
	


	function insertarCompromiso($opcion){
		
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"periodoActual",$valor);
		$periodo=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
//cambiar periodo
//                $periodo[0][1]=3;

		
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarDeudor",$this->usuario);
		$deudor=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
			
		if(!is_array($deudor) && $this->usuario<>""){
		
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql,"insertarCompromiso",array($this->usuario,$periodo[0][0],$periodo[0][1],time()));
			$resultado=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql,$sql,"");
			
			if($resultado){
				$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"desbloquearRecibos",array($this->usuario,$periodo[0][0],$periodo[0][1],time()));
				$resultado=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");		
			}
		}
		
		$variable="pagina=adminPago";
		$variable.="&opcion=reciboActual";
		$variable=$this->cripto->codificar_url($variable,$this->configuracion);
		echo "<script>location.replace('".$this->indice.$variable."')</script>";
	}	

	

		
}


	

?>

