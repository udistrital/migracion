<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");exit;}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminMenuHorario extends funcionGeneral
{
	function __construct($configuracion, $sql)
	{	//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
                $this->formulario='admin_consultaHorarios';
                $this->bloque='horarios/admin_consultaHorarios';

		//$this->tema=$tema;
		$this->sql=$sql;
                //Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
                
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
		//Conexion ORACLE
		if($this->nivel==4){
                    $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }
                
		//Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
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
            {   $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,'proyecto_curricular',$registro);
                $proyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                $this->encabezado($configuracion,$proyecto);
            }
/*__________________________________________________________________________________________________
						Metodos especificos 
__________________________________________________________________________________________________*/

    function encabezado($configuracion,$proyecto){
       
         //Busca los periodos/
         $cadena_sql = $this->sql->cadena_sql($configuracion,$this->accesoOracle,'periodo');
         $resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
         //busca el periodo activo
         foreach ($resultadoPeriodo as $key => $value) 
              { if($resultadoPeriodo[$key]['ESTADO']=='A')
                    {$per_actual=$resultadoPeriodo[$key]['ANIO'].'-'.$resultadoPeriodo[$key]['PERIODO'];}
              }
        //busca datos de la asignatura
		$espacio=isset($_REQUEST['espacio'])?$_REQUEST['espacio']:"";
		$totalRegAsig=0;
		
        if($espacio<>"")
           { $variable=array('proyecto'=>$_REQUEST['proyecto'],
                            'asignatura'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                            'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):substr($per_actual,-6,4), 
                            'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):substr($per_actual,-1), 
                            'tipoBusca'=>is_numeric($_REQUEST['espacio'])?'codigo':'nombre'
                            );
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaAsignatura", $variable);
				$rsAsignatura=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
				
				if($rsAsignatura){
				  $totalRegAsig=$this->totalRegistros($configuracion, $this->accesoOracle);
				}
				else{ //busca asignatura con el periodo activo
                    $variable['anio']=substr($per_actual,-6,4);
                    $variable['periodo']=substr($per_actual,-1);
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaAsignatura", $variable);
                    $rsAsignatura=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    if($rsAsignatura){
						$totalRegAsig=$this->totalRegistros($configuracion, $this->accesoOracle);
					}
                    else{
						$totalRegAsig=0;
					}  
				}  
           }

         
	  ?>
	  <table class="tablaBase centrar">
		<tr>
			<td colspan="6" class="cuadro_plano centrar"><h3 >
				GESTI&Oacute;N DE HORARIOS - <? echo $proyecto[0]['NOMBRE_LARGO'];?></h3>
			</td>
		</tr>
		<?
                
                $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				$ruta="pagina=adminConsultaHorarios";
				$ruta.="&opcion=";
				$rutainicio=$this->cripto->codificar_url($ruta,$configuracion);
				//enlace crear horario
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				$ruta="pagina=adminConsultaHorarioCurso";
				$ruta.="&opcion=generar";
				$ruta.="&item=crear";
                $ruta.='&proyecto='.$proyecto[0]['CODIGO'];
                if($totalRegAsig==1){
					$ruta.='&espacio='.$rsAsignatura[0]['COD_ESPACIO'];
				}
                elseif(isset($_REQUEST['espacio'])){
					$ruta.='&espacio='.$_REQUEST['espacio'];
				}
				else{
					$ruta.='&espacio=';
				}
                //para creacion solo se envia el periodo actual
                //$ruta.='&periodo='.$per_actual;
                // permite envia cualquier periodo --
                if(isset($_REQUEST['periodo']))
                     {$ruta.='&periodo='.$_REQUEST['periodo'];}
                else {$ruta.='&periodo='.$per_actual;}
                $rutaadd=$this->cripto->codificar_url($ruta,$configuracion);
                //enlace copiar horario
				$ruta="pagina=adminCopiarHorarios";
				$ruta.="&opcion=copiarHorarios";
				$ruta.='&proyecto='.$proyecto[0]['CODIGO'];
				$rutacopia=$this->cripto->codificar_url($ruta,$configuracion);
				//enlace consultar horario
				$ruta='pagina=adminConsultaHorarios';
				$ruta.='&opcion=consultarGrupos';
				$ruta.='&tipoConsulta=todos';
                $ruta.='&proyecto='.$proyecto[0]['CODIGO'];
                if(isset($_REQUEST['periodo']))
                     {$ruta.='&periodo='.$_REQUEST['periodo'];}
                else {$ruta.='&periodo='.$per_actual;}
		$rutaconsulta=$this->cripto->codificar_url($ruta,$configuracion);
                
                //enlace reporte horario
				$ruta='pagina=adminConsultaHorarios';
                $ruta.='&opcion=reporteGrupos';
                $ruta.='&tipoConsulta=todos';
                $ruta.='&proyecto='.$proyecto[0]['CODIGO'];
                if(isset($_REQUEST['periodo']))
                     {$ruta.='&periodo='.$_REQUEST['periodo'];}
                else {$ruta.='&periodo='.$per_actual;}
                if($totalRegAsig==1){
					$ruta.='&espacio='.$rsAsignatura[0]['COD_ESPACIO'];
				}
                elseif(isset($_REQUEST['espacio'])){
					$ruta.='&espacio='.$_REQUEST['espacio'];
				}
				else{
					$ruta.='&espacio=';
				}
		$rutaReporte=$this->cripto->codificar_url($ruta,$configuracion);

		?>
       		
		<tr>
			<td class="cuadro_plano centrar">
				<a href="<?echo $indice.$rutainicio?>"><img width="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" alt="ir" border="0"><br>Inicio</a>
			</td>
			<td class="cuadro_plano centrar">
				<a href="<?echo $indice.$rutaadd?>"><img width="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/addHorario.PNG" alt="add" border="0"><br>Crear curso</a>
			</td>			
            <td class="cuadro_plano centrar">
				<a href="<?echo $indice.$rutacopia?>"><img width="30"src="<?echo $configuracion['site'].$configuracion['grafico']?>/copiarHorario.PNG" alt="copiar" border="0"><br>Copiar Horario</a>
			</td>

			<td class="cuadro_plano centrar">		
				<a href="<?echo $indice.$rutaconsulta?>"><img width="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/verHorario.PNG" alt="ver" border="0"><br>Listar Horarios</a>
			</td>
      			<td class="cuadro_plano centrar">		
				<a href="<?echo $indice.$rutaReporte?>"><img width="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reporte.png" alt="rep" border="0"><br>Reporte Horarios</a>
			</td>

                        <td class="cuadro_plano centrar" width="60%" rowspan='2'>
                          <!-- <form class="centrar" name="busquedaRapida" id="busquedaRapida">-->
                               
                          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $this->formulario?>'>    
                               <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                                <tr>
                                    <th colspan="2"><center>BUSQUEDA POR ASIGNATURA:</center></th>
                                </tr>		
                                <tr>
                                       <td valign='top'>ASIGNATURA:<br/><br/>
                                        <input class="required" type="text" name="espacio" id="espacio" value="<? if(isset($_REQUEST['espacio'])){echo strtoupper($_REQUEST['espacio']);} ?>"/>
                                        <? if(isset($totalRegAsig)){echo '<br>'.$totalRegAsig.' Asignaturas coinciden con la búsqueda';}?>
                                    </td>
                                    <td>PERIODO:
                                    <?      for($j=0;$j<count($resultadoPeriodo);$j++){
                                                    $checked=$j==0?'checked':'';
                                                    if(!isset($_REQUEST['periodo']) && $resultadoPeriodo[$j]['ESTADO']=='A')
                                                            {$checked='checked';}
                                                    elseif(isset($_REQUEST['periodo']) && $_REQUEST['periodo']==($resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO']) )
                                                            {$checked='checked';}
                                                    else    {$checked='';}
                                                    echo "<br/><input type='radio' name='periodo' value='".$resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO']."' ".$checked." />";
                                                    if($resultadoPeriodo[$j]['ESTADO']=='A')
                                                        {echo "<b>".$resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO']."</b>";}
                                                    else{echo $resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO'];}
                                                        
                                            }
                                            ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td valign='middle' width="50px" colspan="2" align='center' >
                                        <input type='hidden' name='action' value='<? echo $this->bloque;?>'>     
                                        <input type='hidden' name='tipoConsulta' value='rapida'>
                                        <input type='hidden' name='opcion' value='buscar'>
                                        <input type='hidden' name='tipoBusca' value=''>
                                        <input type='hidden' name='proyecto' value='<? echo  $proyecto[0]['CODIGO'];?>'>
                                        <input name='buscar' value='Buscar' type='submit'>    
                                    </td>
                                 </tr>	
                              </table>

                              </center></form>
			</td>
		</tr>
                <tr>
			<td colspan="5" align="center">
                            <table class="tablaMarco" align="center">
                                <tr>
                                    <td align="rigth">
					<p><a href="<? echo $configuracion["host"];?>/appserv/manual/gestion_de_horarios.pdf">
					<img border="0" alt=" " width="20" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/acroread.png"?>" />
					Ver Manual de Usuario.</a></p>
                                    </td>
				</tr>
                            </table>
			</td>
		</tr>


	  </table>
	  <?
    }        
}
?>

