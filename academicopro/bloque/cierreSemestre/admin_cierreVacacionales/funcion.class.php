<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funcion_adminCierreVacacionales extends funcionGeneral
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
		$this->formulario='admin_cierreVacacionales';
		$this->bloque='cierreSemestre/admin_cierreVacacionales';
		$this->configuracion=$configuracion;
 		$this->pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                if($this->usuario=="")
                {
                    echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                    EXIT;
	}
        
	}
        
	function mostrarRegistro($registro, $totalRegistros, $opcion, $variable)
    	{	switch($opcion)
		{	case "multiplesCarreras":
				$this->multiplesCarreras($registro, $totalRegistros, $variable);
				break;
		}
	}
/*__________________________________________________________________________________________________
						Metodos especificos 
____________________________________________________________________________________________________*/
	
function multiplesCarreras($registro, $total, $variable)
	{	$indice=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                ?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
		     <tbody>
			<tr>
                            <td>
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                    <tr class="texto_subtitulo">
                                        <td>Cierre Cursos Vacacionales - Proyectos Curriculares<br><hr class="hr_subtitulo"></td>
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
                                $parametro="pagina=admin_cierreVacacionales";
                                $parametro.="&hoja=1";
                                $parametro.="&opcion=consultarProyecto";
                                $parametro.="&codProyecto=".$registro[$contador]['CODIGO'];
                                $parametro=  $this->cripto->codificar_url($parametro,  $this->configuracion);
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
				<p class="textoNivel0">Por favor haga click sobre el nombre del proyecto curricular que desee consultar.</p>
                            </td>
			</tr>
                    </tbody>
		</table>
		<?
	}

function confirmarRegistro($configuracion,$accion)
	{
		echo "SU REGISTRO SE HA GUARDADO EXITOSAMENTE";
		echo "<br>identificador=".$_REQUEST['identificador'];	
	}

function consultarCarrera($variable)
	{
            $periodoActual=$this->periodoActual($variable['codProyecto']);  
        ?>    
	  <table class="tablaBase centrar">
		<tr height='60PX'>

                        <td class="cuadro_plano centrar" width="100%" colspan='2'>
                          <!-- <form class="centrar" name="busquedaRapida" id="busquedaRapida">-->
                               
                            <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                              <tr height='60PX'>
                                    <td class="texto_subtitulo" colspan="4">   
                                        <b><center>Verifique que:</b></center>
                                     <ul><li>Que todos los docentes hayan digitado notas.</li></ul>
                                 
                                     <hr class="hr_subtitulo">
                                    </td><br>
                            </tr>                                  
                                <tr>
                                    <? $eventoInicial=92;
                                    $eventoInicio=$this->consultarEventos($periodoActual,$eventoInicial);
                                    $fechaActual=  date("Ymd");
                                    if ($eventoInicio[0]['ACE_FEC_INI']<=$fechaActual&&$eventoInicio[0]['ACE_FEC_FIN']>=$fechaActual)
                                    {
                                        $eventos=94;
                                        $consultarEvento=$this->consultarEventos($periodoActual,$eventos);

                                        if(!is_array($consultarEvento))
                                        {//si el registro no existe imprime esto?>
                                            <td valign='middle' width="33%" align='center' >
                                            <?//=$_REQUEST['proyecto']?>
                                                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $this->formulario?>'>    
                                                            <input type='hidden' name='action' value='<? echo $this->bloque;?>'>     
                                                            <input type='hidden' name='opcion' value='cerrar'>
                                                            <input type='hidden' name='periodo' value='<? echo  $variable['anio'].'-'.$variable['periodo'];?>'>
                                                            <input type='hidden' name='codProyecto' value='<? echo  $variable['codProyecto'];?>'><b>1.</b>
                                                            <input name='notas' value='Cierre de Vacacionales' type='submit'>    
                                                    </form>
                                            </td>
                                        <?}else
                                            {?> 
                                                <td class="eventos1" valign='middle' width="33%" align='center' ><b><?=$_REQUEST['codProyecto']?> Aplicó Reglamento con Éxito.</b>

                                                </td>
                                                <?
                                                $this->presentarReporteCierre($variable['codProyecto'],$periodoActual);
                                            }
								   
                                    }elseif(is_array($eventoInicio)&&!empty ($eventoInicio))
                                        {
                                            $mesNumero=array('01','02','03','04','05','06','07','07','09','10','11','12');
                                            $mesLetra=array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
                                            ?>
                                            <td class="eventos1" valign='middle' width="33%" align='center' >
                                                Las fechas para realizar cierre de intersemestrales van del <?echo substr($eventoInicio[0]['ACE_FEC_INI'], -2)."/".  str_replace($mesNumero,$mesLetra,substr($eventoInicio[0]['ACE_FEC_INI'], 4,2))."/".substr($eventoInicio[0]['ACE_FEC_INI'], 0,4);?> al <?echo substr($eventoInicio[0]['ACE_FEC_FIN'], -2)."/".  str_replace($mesNumero,$mesLetra,substr($eventoInicio[0]['ACE_FEC_FIN'], 4,2))."/".substr($eventoInicio[0]['ACE_FEC_FIN'], 0,4);?>.
                                            </td>
                                            <?
                                        }else
                                            {
                                              ?>
                                                <td class="eventos1" valign='middle' width="33%" align='center' >
                                                    No se han definido fechas para realizar el Cierre de cursos Vacacionales.
                                                </td>
                                              <?
                                            }
                                    echo "<div id='progresoCierre'></div>";
                                    ?>


                                </tr>	
                            </table>
                        </center>
                    </td>
		</tr>
	  </table>
        <?
	}        
            
    function periodoActual(){
	
        $cadena_sql=$this->sql->cadena_sql('periodo','V');
        $periodoActual=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); 
        return $periodoActual;
    }
    
	function consultarEventos($periodoActual,$evento){    
		$variables=array('codProyecto'=>$_REQUEST['codProyecto'],
						 'periodo'=>$periodoActual[0]['PERIODO'],
						 'anio'=>$periodoActual[0]['ANIO'],
						 'evento'=>$evento);    
	
		$cadena_sql=$this->sql->cadena_sql('valida_fecha',$variables); 
		$resultado_cancelo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado_cancelo;
		
	}

	function presentarReporteCierre($codProyecto,$periodoActual){
            $arreglo_estudiantes=  $this->consultarEstadosEstudiantesCierre($codProyecto, $periodoActual);
            $resultadoProceso=$this->consultarDatosReglamento($codProyecto, $periodoActual);
            $totalestudiantesCierre=count($resultadoProceso);
            $estadoJ004=0;
            $estadoJ007=0;
            $estadoJ027=0;
            $estadoZ027=0;
            $estadoZ007=0;
            $estadoU=0;
            $estadoT=0;
            $estadoV004=0;
            $estadoV007=0;
            $estadoV027=0;
            $otrosEstados=0;
            $clase='cuadro_plano';
            echo '<table>
                <tr>
                <td><br>
                </td>
                </tr>
                </table>';
            if (is_array($arreglo_estudiantes)&&!empty($arreglo_estudiantes))
            {
                $total=count($arreglo_estudiantes);
                foreach ($arreglo_estudiantes as $clave=>$valor)
                {
                    switch ($valor['ESTADO'])
                    {
                        case 'J':
                        case 'B':
                            if ($valor['ACUERDO']==2011004)
                            {
                                $estadoJ004++;
                            }elseif($valor['ACUERDO']==1993027)
                                {
                                    $estadoJ027++;
                                }else
                                    {
                                        $estadoJ007++;
                                    }
                            break;
                        case 'Z';
                            if ($valor['ACUERDO']==1993027)
                            {
                                $estadoZ027++;
                            }else
                                {
                                    $estadoZ007++;
                                }
                            break;
                        case 'U';
                            $estadoU++;
                            break;
                        case 'T';
                        case 'H';
                            $estadoT++;
                            break;
                        case 'V':
                        case 'A':
                            if ($valor['ACUERDO']==2011004)
                            {
                                $estadoV004++;
                            }elseif($valor['ACUERDO']==1993027)
                                {
                                    $estadoV027++;
                                }else
                                    {
                                        $estadoV007++;
                                    }
                            break;
                    }
                }
                
                //cuadro_plano izquierda
                $totalVacaciones=$estadoV004+$estadoV007+$estadoV027;
                $totalRiesgo=$estadoJ004+$estadoJ007+$estadoJ027;
                $totalPerdida=$estadoZ027+$estadoZ007+$estadoU;
                $estados=$totalVacaciones+$totalRiesgo+$totalPerdida+$estadoT;
                $otrosEstados=$total-$estados;
                
                ?>
	  <table class="contenidotabla" width='80%' border='1' align='center' cellpadding='4 px' cellspacing='0px'>
              <tr class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="100%" colspan="2">
                      <strong><br>ESTUDIANTES PROCESADOS EN EL CIERRE DE SEMESTRE<br></strong> 
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Estudiantes sin riesgo acad&eacute;mico:</strong> 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $totalVacaciones;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Acuerdo 027 de 1993: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoV027;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Acuerdo 007 de 2009: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoV007;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Acuerdo 004 de 2011: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoV004;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                    <strong>Estudiantes en Prueba Acad&eacute;mica o Bajo Rendimiento:</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $totalRiesgo;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Estudiantes en Prueba Acad&eacute;mica Acuerdo 027 de 1993: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoJ027;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Estudiantes en Prueba Acad&eacute;mica Acuerdo 007 de 2009: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoJ007;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Estudiantes en Bajo Rendimiento Acuerdo 004 de 2011: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoJ004;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Estudiantes en P&eacute;rdida de la calidad de estudiante: </strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $totalPerdida;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Acuerdo 027 de 1993: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoZ027;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Acuerdo 007 de 2009: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoZ007;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      &nbsp;&nbsp;&nbsp;Acuerdo 004 de 2011: 
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <?echo $estadoU;?>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Estudiantes en vacaciones y que terminaron materias:</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $estadoT;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Estudiantes en otros estados:</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $otrosEstados;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Total estudiantes a los que se aplic&oacute; reglamento:</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $total;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Estudiantes a los que no se aplic&oacute; reglamento:</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $totalestudiantesCierre-$total;?></strong>
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="70%">
                      <strong>Total Estudiantes:</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="30%">
                      <strong><?echo $totalestudiantesCierre;?></strong>
                  </td>
              </tr>
          </table><p></p>
          <?$clase="cuadro_color";
          ?>
	  <table class="contenidotabla" width='80%' border='1' align='center' cellpadding='4 px' cellspacing='0px'>
              <tr class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="50%" colspan="2" >
                      <strong><br>MOTIVOS DE PRUEBA/BAJO RENDIMIENTO<br></strong> 
                  </td>
                  <td class="<?echo $clase;?> centrar" width="50%" colspan="2">
                      <strong><br>CAUSALES EXCLUSIÓN<br></strong> 
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>1</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Bajo Promedio
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>1</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Promedio inferior a 3.2 para acuerdo 004 ó 3.0 para acuerdo 007 y 027.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>2</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber reprobado 3 o m&aacute;s espacios acad&eacute;micos.
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>2</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber reprobado 3 o m&aacute;s espacios acad&eacute;micos por cuatro per&iacute;odos.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>3</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Cursar un espacio acad&eacute;mico 3 veces o m&aacute;s.
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>3</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber reprobado un espacio acad&eacute;mico 3 veces o m&aacute;s.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong></strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>4</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Acumulaci&oacute;n de pruebas acad&eacute;micas.
                  </td>
              </tr>
              <tr onmouseover="this.style.background='#FFFF69'" onmouseout="this.style.background=''" class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="10%">
                      <strong></strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                  </td>
                  <td class="<?echo $clase;?> centrar" width="10%">
                      <strong>5</strong>
                  </td>
                  <td class="<?echo $clase;?>" width="40%">
                      Haber cumplido el plazo m&aacute;ximo para la terminaci&oacute;n del plan de estudios. 
                  </td>
              </tr>
          </table><p></p>
          
          
<?
            if(is_array($resultadoProceso))
            {
                $this->mostrarReporteResultadoProceso($resultadoProceso);
            }
            }else
                {
                ?>
                    <table class="tablaBase centrar" width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >
                        <tr>
                            <td class="<?echo $clase;?>" width="100%" colspan="2">
                                <strong><br>NO SE PROCESARON ESTUDIANTES<br></strong> 
                            </td>
                        </tr>
                    </table>
                <?
                }
		
	}
    /**
     * Funcion que presenta el encabezado de la tabla
     * @param type $resultadoProceso
     */    
    function mostrarReporteResultadoProceso($resultadoProceso){
        $total=count($resultadoProceso);
        $clase='cuadro_plano';
        ?>
	  <table class="contenidotabla" width='80%' border='1' align='center' cellpadding='4 px' cellspacing='0px'>
              <tr class="<?echo $clase;?>">
                  <td class="<?echo $clase;?>" width="100%" colspan="13">
        <?
//        echo "<h1>Resultados del Proceso</h1>";
//        
//        $html = "<table width='100%' colspan='13'>";
//        $html .= "<tr>";
//        $html .= "<td width='30%'>Total Estudiantes:</td>";
//        $html .= "<td width='70%'>".$total."</td>";
//        $html .= "</tr>";
//        $html .= "</table>";
//        
//        echo $html;
        
        if($total>0){
            $this->mostrarDetalleProceso($resultadoProceso);
        }
        ?>
                  </td>
              </tr>
          </table>                      
            <?
    }        

    
    function mostrarDetalleProceso($resultadoProceso){
        ?>
            <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() { 
                        $('#tabla').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
                
    <?  
        echo "<h1>Detalles del Proceso</h1>";
        $html = "<table id='tabla'>";
        $html .= "<thead>";
        $html .= "<tr>"; 
        $html .= "<td style='font-size: 9pt;'>No.</td>"; 
        $html .= "<td style='font-size: 9pt;'>Cra..</td>"; 
        $html .= "<td style='font-size: 9pt;'>Código Estudiante</td>"; 
        $html .= "<td style='font-size: 9pt;'>Nombre Estudiante</td>";
        $html .= "<td style='font-size: 9pt;'>Estado Anterior</td>";
        $html .= "<td style='font-size: 9pt;'>Estado Actual</td>";
        $html .= "<td style='font-size: 9pt;'>Acuerdo</td>";
        $html .= "<td style='font-size: 9pt;'>Motivo Prueba</td>";
        $html .= "<td style='font-size: 9pt;'>Num. Esp. Acad. Reprob.</td>";
        $html .= "<td style='font-size: 9pt;'>Máx. veces esp. reproba.</td>";
        $html .= "<td style='font-size: 9pt;'>Promedio</td>";
        $html .= "<td style='font-size: 9pt;'>Causal Exclusi&oacute;n</td>";
        $html .= "<td style='font-size: 9pt;'>% Plan Aprobado</td>";
        $html .= "<td style='font-size: 9pt;'>Esp. Acad. Reprob. y veces (Prueba)</td>";        
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach ($resultadoProceso as $key => $estudiante) {
                $id=$key+1;
            $html .= "<tr>";
            $html .= "<td style='font-size: 10pt;'>".$id."</td>";
            foreach ($estudiante as $key2=>$obs_estudiante) {
                if (!is_numeric($key2))
                {
                    if($key2=='ESPACIOS_REPROBADOS'&&!is_numeric($obs_estudiante))
                    {
                        $html.="<td style='font-size: 10pt;'>";
                        $espacios=json_decode($obs_estudiante);
                        foreach ($espacios as $key => $value) {
                            $html.=$key.":".$value."<br>";
                        }
                        $html.="</td>";
                    }
                    else{
                            $html.="<td style='font-size: 10pt;'>".$obs_estudiante."</td>";
                        }
                }
            }
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        
        $html .= "</table>";
        echo $html;
    }
    
        /**
         * Funcion que consulta los datos de los estudiantes procesados en el cierre de semestre
         * @param type $codProyecto
         * @param type $periodoActual
         * @return type
         */
        function consultarEstadosEstudiantesCierre($codProyecto,$periodoActual) {
            $variables=array('codProyecto'=>$codProyecto,
                            'periodo'=>$periodoActual[0]['PERIODO'],
                            'periodoAnt'=>$periodoActual[0]['PERIODO']-1,
                            'anio'=>$periodoActual[0]['ANIO'],
                            );
            $cadena_sql=$this->sql->cadena_sql('consultarDatosEstudiantesCierre',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
            
        }

        /**
         * Funcion que permite consultar los datos del cierre de semestre en la tabla de reglamento
         * @param type $codProyecto
         * @param type $periodoActual
         * @return type
         */
        function consultarDatosReglamento($codProyecto,$periodoActual) {
            $variables=array('codProyecto'=>$codProyecto,
                            'periodo'=>$periodoActual[0]['PERIODO'],
                            'periodoAnt'=>$periodoActual[0]['PERIODO']-1,
                            'anio'=>$periodoActual[0]['ANIO'],
                            );
            $cadena_sql=$this->sql->cadena_sql('consultarDatosReglamento',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
        
        }

        
        /**
         * Funcion que consulta los promedios de los estudiantes procesados
         */
        function consultarEstudiantesMejoresPromedios($codProyecto,$periodoActual) {
            $variables=array('codProyecto'=>$codProyecto,
                            'periodo'=>$periodoActual[0]['PERIODO'],
                            'anio'=>$periodoActual[0]['ANIO'],
                            );
            $cadena_sql=$this->sql->cadena_sql('consultarEstudiantesMejoresPromedios',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
            
        }


    
}

?>

