<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");exit;}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminMenuCierreSemestre extends funcionGeneral
{
    public $configuracion;
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
                $this->configuracion=$configuracion;
		}
	function mostrarRegistro($registro, $totalRegistros, $opcion, $variable)
            {   $cadena_sql=$this->sql->cadena_sql('proyecto_curricular',$registro);
                $proyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                $this->encabezado($proyecto);
            }
/*__________________________________________________________________________________________________
						Metodos especificos 
__________________________________________________________________________________________________*/

    function encabezado($proyecto){
       
         //Busca los periodos/
         $cadena_sql = $this->sql->cadena_sql('periodo');
         $resultadoPeriodo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
         //busca el periodo activo
         foreach ($resultadoPeriodo as $key => $value) 
              { if($resultadoPeriodo[$key]['ESTADO']=='A')
                    {$per_actual=$resultadoPeriodo[$key]['ANIO'].'-'.$resultadoPeriodo[$key]['PERIODO'];}
              }
        //busca datos de la asignatura
        

         
	  ?>
	  <table class="tablaBase centrar">
		<tr>
			<td colspan="6" class="cuadro_plano centrar"><h3 >
                                CIERRE DE SEMESTRE</h3>
			</td>
		</tr>
		<?
                
                $indice=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
		$ruta="pagina=adminCierreSemestre";
                $ruta.="&opcion=";
		$rutainicio=$this->cripto->codificar_url($ruta,  $this->configuracion);
                
		?>
       		
		<tr height='60PX'>
			<td width="200px" class="cuadro_plano centrar">
                            <a href="<?echo $indice.$rutainicio?>"><img width="30" src="<?echo $this->configuracion['site'].  $this->configuracion['grafico']?>/go-first.png" alt="ir" border="0"><br>Inicio</a>
			</td>
                        <td colspan="4" class="cuadro_plano centrar"><h3 >
                                PROYECTO CURRICULAR - <? echo $proyecto[0]['NOMBRE_LARGO'];?></h3>
			</td>

                        <td class="cuadro_plano centrar" width="30%" rowspan='2'>
           
                          <? $formulario='admin_cierreSemestre'?>
                               
                           <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $formulario?>'>    
                               <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                                <tr>
                                           <td>PERIODO:
                                    <?      for($j=0;$j<count($resultadoPeriodo);$j++){
                                                    $checked=$j==0?'checked':'';
                                                    if(!isset($_REQUEST['periodo']) && $resultadoPeriodo[$j]['ESTADO']=='A')
                                                            {$checked='checked';}
                                                    elseif(isset($_REQUEST['periodo']) && $_REQUEST['periodo']==($resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO']) )
                                                            {$checked='checked';}
                                                    else    {$checked='';}
                                                    
                                                    if($resultadoPeriodo[$j]['ESTADO']=='A')
                                                        {echo "<br/><input type='radio' name='periodo' value='".$resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO']."' ".$checked." />";
                                                         echo "<b>".$resultadoPeriodo[$j]['ANIO']."-".$resultadoPeriodo[$j]['PERIODO']."</b>";}
                                                        
                                            }
                                            ?>
                                    </td>
                                </tr>
                              </table>

                              </center></form>
			</td>
		</tr>
	  </table>
	  <?
    }        
}
?>

