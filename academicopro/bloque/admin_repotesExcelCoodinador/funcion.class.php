<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_ReportesExcelCoodinador extends funcionGeneral
{
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
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
__________________________________________________________________________________________________*/

     //Muestra el reporte de los horarios y cursos.
   function exportarHorario($configuracion,$variable)
    {
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenCurso",$variable);
            $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarCarrera",$variable);
            $carreraActual=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
			if(!is_array($resultado))
            {
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                    $cadena="No existen horarios ni cursos para reportar en el periodo académico ".$variable['anio']."-".$variable['periodo']."";
                    $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($configuracion,$cadena);	
            }
            else
            {
                    ?>
					<br/>
					<table bgcolor="#EEEEEE"  class="contenidotabla  centrar" width="100%">
						<tr>
							<td colspan='8'><center><br/><h2>REPORTE DE HORARIOS <?=$resultado[0]['ANIO']?>-<?=$resultado[0]['PERIODO']?><br/></h2></center> </td>
						</tr>
						<tr>
							<td colspan='8'><center><br/><b>PROYECTO CURRICULAR  <?=$resultado[0]['COD_PROYECTO']?>-<?=$carreraActual[0][0]?></b><br/></center><br/></td>
						</tr>
					</table>
					<br/>
                    <table border="1" bordercolor="#AAAAAA" class="contenidotabla  centrar" width="100%">
                         <th class="sigma centrar" colspan="8">RES&Uacute;MEN HORARIOS </th>
                            <tr>
                                    <td bgcolor="#DCDCDC" class="cuadro_plano centrar" width="26%">Asignatura</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="4%">Grupo</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">Lunes</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">Martes</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">Mi&eacute;rcoles</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">Jueves</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">Viernes</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">S&aacute;bado</td>
                                    <td bgcolor="#DCDCDC"  class="cuadro_plano centrar" width="10%">Domingo</td>
                             </tr>
                            <?
					$i=0;		
                    foreach ($resultado as $cont => $value) 
                    {
					$colorCelda=($i%2==0)?"#F4F5EB":"";
					$i++;
					?>
                                    <tr bgcolor="<?=$colorCelda?>" class="cuadro_color">
                                           
                                            <td class="cuadro_plano " style="vertical-align:middle">
                                                <? if($variable['opcion']=='Horario')
                                                        {  echo $resultado[$cont]['COD_ESPACIO']." - ".UTF8_DECODE($resultado[$cont]['NOM_ESPACIO']);}
                                                 else   { echo $resultado[$cont]['COD_ESPACIO']." - ".$resultado[$cont]['NOM_ESPACIO'];}      
                                                  ?>
                                            </td>
                                            <td class="cuadro_plano centrar" style="vertical-align:middle"><? echo $resultado[$cont]['GRUPO'];?></td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'1');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'2');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                               <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'3');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'4');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'5');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'6');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                      'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                       'anio'=>$resultado[$cont]['ANIO'], 
                                                       'periodo'=>$resultado[$cont]['PERIODO'], 
                                                       'grupo'=>$resultado[$cont]['GRUPO'], 
                                                       'dia'=>'7');
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {    ?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo "<b>".$resultadoHor[$contHor]['HORA_L'].":</b> ";
                                                                                echo "(<b>sede:</b>".$resultadoHor[$contHor]['NOM_SEDE'].")  ";
                                                                                echo "(<b>edificio:</b>".UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO']).") ";   
                                                                                echo "(<b>salon:</b>".$resultadoHor[$contHor]['NOM_SALON'].") ";
                                                                                ?>
                                                                        </td>
                                                                </tr>        
                                                                <?}?>
                                                            </table>   
                                                           <?}?>
                                            </td>                                            
                                    </tr>
                        <?  }  ?>
                    </table>
                    <?
            }
    }
    
   function consultarOcupacion($configuracion,$variable)
	{
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "resumenOcupacion", $variable);
            $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(!is_array($resultado))
            {
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                    $cadena="No existen ocupación horarios ni cursos para reportar en el periodo académico ".$variable['anio']."-".$variable['periodo']."";
                    $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($configuracion,$cadena);	
            }
            else
            {       $formulario='admin_repotesExcelCoodinador';
                    ?>
                    
                    <table class="contenidotabla  centrar" width="100%">
                        <th class="sigma centrar" colspan="10">RES&Uacute;MEN OCUPACI&Oacute;N </th>
                            <tr>
                                    <td class="cuadro_plano centrar" width="6%">Periodo</td>
                                    <td class="cuadro_plano centrar" width="6%">D&iacute;a</td>
                                    <td class="cuadro_plano centrar" width="6%">Hora</td>
                                    <td class="cuadro_plano centrar" width="18%">Asignatura</td>
                                    <td class="cuadro_plano centrar" width="6%">Grupo</td>
                                    <td class="cuadro_plano centrar" width="13%">Proyecto</td>
                                    <td class="cuadro_plano centrar" width="11%">Sal&oacute;n</td>
                                    <td class="cuadro_plano centrar" width="11%">Edificio</td>
                                    <td class="cuadro_plano centrar" width="11%">Sede</td>
                                    <td class="cuadro_plano centrar" width="11%">Inscritos</td>
                                    <td class="cuadro_plano centrar" width="11%">Docente</td>
                             </tr>
                            <?
                     foreach ($resultado as $cont => $value) 
                            { ?>
                                    <tr class="cuadro_color">
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['ANIO']."-".$resultado[$cont]['PERIODO'];?></td>
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['DIA'];?></td>
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['HORA_L'];?></td>
                                            <td class="cuadro_plano "><? echo UTF8_DECODE($resultado[$cont]['COD_ESPACIO']." - ".$resultado[$cont]['ESPACIO']);?></td>
                                            <td class="cuadro_plano centrar"><? echo str_pad($resultado[$cont]['COD_PROYECTO'], 3, "0", STR_PAD_LEFT)."-".$resultado[$cont]['GRUPO'];?></td>
                                            <td class="cuadro_plano "><? echo UTF8_DECODE($resultado[$cont]['COD_PROYECTO']." - ".$resultado[$cont]['PROYECTO']);?></td>
                                            <td class="cuadro_plano "><? echo UTF8_DECODE($resultado[$cont]['NOM_SALON']." CAP(".$resultado[$cont]['CAP_SALON'].")") ;?></td>
                                            <td class="cuadro_plano "><? echo UTF8_DECODE($resultado[$cont]['NOM_EDIFICIO']);?></td>
                                            <td class="cuadro_plano "><? echo UTF8_DECODE($resultado[$cont]['ID_SEDE']." - ".$resultado[$cont]['NOM_SEDE']);?></td>
                                            <td class="cuadro_plano centrar"><? echo (isset($resultado[$cont]['INSCRITOS'])?$resultado[$cont]['INSCRITOS']:'');?></td>
                                            <td class="cuadro_plano "><? echo $resultado[$cont]['DOCENTE'];?></td>
                                    </tr>
                        <?  }  ?>
                    </table>
                    <?
            }
    }  
}

?>

