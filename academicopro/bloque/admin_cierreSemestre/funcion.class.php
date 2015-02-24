<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funcion_adminCierreSemestre extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
		//$this->tema=$tema;
		$this->sql=$sql;
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		//Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->formulario='admin_cierreSemestre';
		$this->configuracion=$configuracion;
 		$this->pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
	}
	function nuevoRegistro($configuracion,$acceso_db)
	{}
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	function corregirRegistro()
    	{}
	function listaRegistro($configuracion,$id_registro)
    	{}
	function mostrarRegistro($configuracion,$registro, $totalRegistros, $opcion, $variable)
    	{	switch($opcion)
		{	case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $totalRegistros, $variable);
				break;
		}
	}
/*__________________________________________________________________________________________________
						Metodos especificos 
____________________________________________________________________________________________________*/
	
function multiplesCarreras($configuracion,$registro, $total, $variable)
	{	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                ?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
		     <tbody>
			<tr>
                            <td>
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                    <tr class="texto_subtitulo">
                                        <td>Cierre de Semestre - Proyectos Curriculares<br><hr class="hr_subtitulo"></td>
                                    </tr>
                                    <tr>
					<td>
                                            <table class='contenidotabla'>
						<tr class='cuadro_color'>
                                                    <td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
                                                    <td class='cuadro_plano centrar'>Nombre </td>
						</tr><?
                        for($contador=0;$contador<$total;$contador++)
                        {
                                $parametro="pagina=adminCierreSemestre";
                                $parametro.="&hoja=1";
                                $parametro.="&opcion=consultarProyecto";
                                $parametro.="&proyecto=".$registro[$contador]['CODIGO'];
                                $parametro=  $this->cripto->codificar_url($parametro,$configuracion);
                                       echo "<tr> 
                                                 <td class='cuadro_plano centrar'>".$registro[$contador]['CODIGO']."</td>
                                                 <td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador]['NOMBRE']."</a></td>
                                            </tr>";
                        }?>
					  </table>
					</td>
				 </tr>
			     </table>
		 	    </td>
			</tr>
			<tr>
                            <td class='cuadro_plano cuadro_brown'>
				<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee consultar.</p>
                            </td>
			</tr>
                    </tbody>
		</table>
		<?
	}
			
function confirmarRegistro($configuracion,$accion)
	{
		echo "SU REGISTRO SE HA GUARDADO EXITOSMENTE";
		echo "<br>identificador=".$_REQUEST['identificador'];	
	}

function consultarCarrera($configuracion,$variable)
	{
            $periodoActual=$this->periodoActual();  
        ?>    
	  <table class="tablaBase centrar">
		<tr height='60PX'>

                        <td class="cuadro_plano centrar" width="30%" rowspan='2'>
                          <!-- <form class="centrar" name="busquedaRapida" id="busquedaRapida">-->
                               
                                <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                              <tr height='60PX'>
                                    <td class="texto_subtitulo" colspan="4">   
                                        <b><center>Señor Coordinador verifique que:</b></center>
                                     <ul><li>Los estudiantes se encuentran en los estados. A (activo) ó B (activo en prueba académica)</li></ul>
                                     <ul><li>Que todos los docentes hayan digitado notas.</li></ul>
                                 
                                     <hr class="hr_subtitulo">
                                    </td><br>
                            </tr>                                  
                                <tr>
                                    <? $eventoInicial=60;
                                    $eventoInicio=$this->consultarEventos($periodoActual,$eventoInicial);
                                    $fechaActual=  date("Ymd");
                                    if ($eventoInicio[0]['ACE_FEC_INI']>=$fechaActual&&$eventoInicio[0]['ACE_FEC_FIN']<=$fechaActual)
                                    {
									$eventos=71;
                                    $consultarEvento=$this->consultarEventos($periodoActual,$eventos);
								
                                    if(!is_array($consultarEvento)){//si el registro no existe imprime esto?>
                                    <td valign='middle' width="50px" align='center' >
									<?//=$_REQUEST['proyecto']?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $this->formulario?>'>    
                                            <input type='hidden' name='action' value='<? echo $this->formulario;?>'>     
                                            <input type='hidden' name='opcion' value='cerrar'>
                                            <input type='hidden' name='periodo' value='<? echo  $variable['anio'].'-'.$variable['periodo'];?>'>
                                            <input type='hidden' name='proyecto' value='<? echo  $variable['proyecto'];?>'><b>1.</b>
                                            <input name='notas' value='Cierre de notas' type='submit'>    
                                        </form>
                                    </td>
                                    <?}else{?> 

                                                    <td class="eventos1" valign='middle' width="50px" align='center' >
                                                            <?=$_REQUEST['codProyecto']?><b>Cerró Notas con Éxito.</b>
                                                    </td>

                                    <?}?>
                                   <?
								   
									$eventos=72;
									$consultarEvento=$this->consultarEventos($periodoActual,$eventos); 
								
									
									if(!is_array($consultarEvento)){
									
										$eventos=71;
										$consultarEvento=$this->consultarEventos($periodoActual,$eventos);
											if(!is_array($consultarEvento)){
												echo "<td valign='middle' width='50px' align='center' >";
												echo "<b>2.</b> Cambiar Estado.";
												echo "</td>";
											}else{
											?>
											<td valign='middle' width="50px" align='center' >
												<?=$_REQUEST['codProyecto']?>
												<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $this->formulario?>'>    
													<input type='hidden' name='action' value='<? echo $this->formulario;?>'>     
													<input type='hidden' name='opcion' value='cambiar'>
													<input type='hidden' name='proyecto' value='<? echo  $variable['proyecto'];?>'>
													<input type='hidden' name='periodo' value='<? echo  $variable['anio'].'-'.$variable['periodo'];?>'>
													<input name='estado' value='Cambiar Estado' type='submit'>    
												</form>
											</td>
											<?
										
											}
										}else {?>
										<td class="eventos1" valign='middle' width="50px" align='center' >
											<?=$_REQUEST['codProyecto']?><b>Cambió Estados con Éxito.</b>
										</td>
									
									<?}?>
                                    
                                    
									<? 
									$eventos=73;
									
										$consultarEvento=$this->consultarEventos($periodoActual,$eventos); 
										if(!is_array($consultarEvento)){
										
										$eventos=72;
										$consultarEvento=$this->consultarEventos($periodoActual,$eventos);
										
										if(!is_array($consultarEvento)){
												echo "<td valign='middle' width='50px' align='center' >";
												echo "<b>3.</b> Aplicar Reglamento.";
												echo "</td>";
										}else{
											?>
											<td valign='middle' width="50px" align='center' >
												<?=$_REQUEST['codProyecto']?>
												<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $this->formulario?>'>    
													<input type='hidden' name='action' value='<? echo $this->formulario;?>'>     
													<input type='hidden' name='opcion' value='aplicar'>
													<input type='hidden' name='proyecto' value='<? echo  $variable['proyecto'];?>'>
													<input type='hidden' name='periodo' value='<? echo  $variable['anio'].'-'.$variable['periodo'];?>'>
													<input name='reglamento' value='Aplicar Reglamento' type='submit'>  
												</form>
											</td>
											<?
										}
									} else {?> 
									<td class="eventos1" valign='middle' width="50px" align='center' ><b>Aplicó Reglamento con Éxito.</b>
										<?=$_REQUEST['codProyecto']?>
									</td>
									<?}
                                                                        }else
                                        {
                                
                                        }
?>


								</tr>	
                              </table>
                              </center>
			</td>
		</tr>
                
	  </table>


        <?
	}        
            
    function realizarCambioEstado() {
        
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,'periodo','A');
        $periodoActual=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $cambioEstado= "BEGIN mntac.PCK_PR_CIERRE_SEMESTRE.Pra_Cambio_Estado(".$periodoActual[0]['ANIO'].",".$periodoActual[0]['PERIODO'].",".$_REQUEST['codProyecto']."); END; ";
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cambioEstado, "");       
    
        if($resultado<>TRUE)
        {
           $variable="mensaje=no";
        }
                
		$variable.="&pagina=adminCierreSemestre";
		$variable.="&opcion=consultarProyecto";
		$variable.="&proyecto=".$_REQUEST['codProyecto'];
		$variable= $this->cripto->codificar_url($variable,$this->configuracion);
		echo "<script>location.replace('".$this->pagina.$variable."')</script>";
		
    }            

    function realizarAplicacionReglamento() {
        
        $periodoActual=$this->periodoActual();
        
		$cambioEstado= "BEGIN mntac.PCK_PR_CIERRE_SEMESTRE.Pra_Aplica_Reglamento(".$periodoActual[0]['ANIO'].",".$periodoActual[0]['PERIODO'].",".$_REQUEST['codProyecto']."); END; ";
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cambioEstado, "");
        
        if($resultado<>TRUE)
        {
           $variable="mensaje=no";
        }
                
		$variable.="&pagina=adminCierreSemestre";
		$variable.="&opcion=consultarProyecto";
		$variable.="&proyecto=".$_REQUEST['codProyecto'];
		$variable= $this->cripto->codificar_url($variable,$this->configuracion);
		echo "<script>location.replace('".$this->pagina.$variable."')</script>";
      
    }  
    
    function periodoActual(){
	
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,'periodo','A');
        $periodoActual=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); 
        return $periodoActual;
    }
    
	function consultarEventos($periodoActual,$evento){    
		$variables=array('proyecto'=>$_REQUEST['proyecto'],
						 'periodo'=>$periodoActual[0]['PERIODO'],
						 'anio'=>$periodoActual[0]['ANIO'],
						 'evento'=>$evento);    
	
		$cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,'valida_fecha',$variables); 
		$resultado_cancelo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado_cancelo;
		
	}


    
}

?>

