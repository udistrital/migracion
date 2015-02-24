<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");exit;}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminMenuOcupacion extends funcionGeneral
{
	function __construct($configuracion, $sql)
	{	//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
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
            {   //$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,'proyecto_curricular',$registro);
                //$proyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                $this->encabezado($configuracion,(isset($proyecto)?$proyecto:''));
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
         
	  ?>
	  <table class="tablaBase centrar" >
		<tr>
			<td colspan="2" class="cuadro_plano centrar"><h3 >
				GESTI&Oacute;N DE HORARIOS <BR><BR>
                                    OCUPACI&Oacute;N DE SALONES</h3>
			</td>
		</tr>
		<?
                $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$ruta="pagina=adminocupacionSalones";
                $ruta.="&opcion=inicio";
		$rutainicio=$this->cripto->codificar_url($ruta,$configuracion);
                ?>
       		
		<tr>
			<td class="cuadro_plano centrar">
				<a href="<?echo $indice.$rutainicio?>"><img width="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" alt="ir" border="0"><br>Inicio</a>
			</td>

                        <td class="cuadro_plano centrar" width="95%" rowspan='2'>
                          <!-- <form class="centrar" name="busquedaRapida" id="busquedaRapida">-->
                          <? $formulario='admin_ocupacionSalones'?>
                               
                           <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $formulario?>'>    
                               <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                                <tr>
                                    <th colspan="5"><center>BUSQUEDA:</center></th>
                                </tr>		
                                <tr>
                                    <td valign='top'> SEDE:<br/><br/>
                                        <input class="required" type="text" name="sede" id="espacio" value="<? if(isset($_REQUEST['sede'])){echo strtoupper($_REQUEST['sede']);} ?>"/>
                                        
                                    </td>
                                    <td valign='top'>EDIFICIO:<br/><br/>
                                        <input class="required" type="text" name="edificio" id="espacio" value="<? if(isset($_REQUEST['edificio'])){echo strtoupper($_REQUEST['edificio']);} ?>"/>
                                    </td>
                                    <td valign='top'>SAL&Oacute;N:<br/><br/>
                                        <input class="required" type="text" name="salon" id="espacio" value="<? if(isset($_REQUEST['salon'])){echo strtoupper($_REQUEST['salon']);} ?>"/>
                                    </td>
                                    
                                    <td valign='top'>PROYECTO:<br/><br/>
                                        <input class="required" type="text" name="proyecto" id="espacio" value="<? if(isset($_REQUEST['proyecto'])){echo strtoupper($_REQUEST['proyecto']);} ?>"/>
                                    </td>
                                    <td rowspan="2">PERIODO:
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
                                    <td valign='top'> ASIGNATURA:<br/><br/>
                                        <input class="required" type="text" name="espacio" id="espacio" value="<? if(isset($_REQUEST['espacio'])){echo strtoupper($_REQUEST['espacio']);} ?>"/>
                                        
                                    </td>
                                    <td valign='top'>D&Iacute;A:<br/><br/>
                                        <input class="required" type="text" name="dia" id="espacio" value="<? if(isset($_REQUEST['dia'])){echo strtoupper($_REQUEST['dia']);} ?>"/>
                                    </td>
                                    <td valign='top'>HORA:<br/><br/>
                                        <input class="required" type="text" name="hora" id="espacio" value="<? if(isset($_REQUEST['hora'])){echo strtoupper($_REQUEST['hora']);} ?>"/>
                                    </td>
                                    <td valign='top'>
                                    </td>
                                   
                                </tr>                                
                                
                                 <tr>
                                    <td valign='middle' width="50px" colspan="5" align='center' >
                                        <input type='hidden' name='action' value='<? echo $formulario;?>'>     
                                        <input type='hidden' name='tipoConsulta' value='rapida'>
                                        <input type='hidden' name='opcion' value='buscar'>
                                        <input name='buscar' value='Buscar' type='submit'>    
                                    </td>
                                 </tr>	
                              </table>

                              </center></form>
			</td>
		</tr>

	  </table><br>
            <?
            $this->formReporteSalones($configuracion,$formulario);

    }
    
    function formReporteSalones($configuracion,$formulario) {
        $sedes=$this->consultarSedes($configuracion);
        ?>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $formulario?>'>    
                <table class="tablaBase centrar" >
                    <tr>
                    <td colspan="4" class="cuadro_plano centrar">
                        <h3>REPORTE DE ESPACIOS F&Iacute;SICOS POR SEDE</h3>
                        </td>
                    </tr>
                </table>
                <table class="formulario contenidotabla centrar">                    
                    <tr>
                        <td colspan="4" class="cuadro_plano centrar">
                            <select name='sede' id='sede'>
                                <option value="0" >Seleccione la Sede..</option>
                                <option value="999" >TODAS</option>
                              <?
                              foreach($sedes as $key => $data){ 
                                    echo "<option value=".$sedes[$key]['ID_SEDE'].">".$sedes[$key]['NOML_SEDE']."</option>";
                              }
                              ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                       <td valign='middle' width="50px" colspan="5" align='center' >
                           <input type='hidden' name='action' value='<? echo $formulario;?>'>     
                           <input type='hidden' name='opcion' value='reporteSalones'>
                           <input name='buscar' value='Buscar' type='submit'>    
                       </td>
                    </tr>	
                </table>
            </form>
	  <?        
        
    }
    
    function consultarSedes($configuracion) {
         $cadena_sql = $this->sql->cadena_sql($configuracion,$this->accesoOracle,'consultarSedes');
         return $resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
    }
}
?>

