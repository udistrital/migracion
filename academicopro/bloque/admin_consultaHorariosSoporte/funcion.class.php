<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminConsultaHorario extends funcionGeneral
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
    	{	switch($opcion)
		{	case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $totalRegistros, $variable);
				break;
		}
	}
/*__________________________________________________________________________________________________
						Metodos especificos 
__________________________________________________________________________________________________*/
	
function multiplesCarreras($configuracion,$registro, $total, $variable)
	{	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                ?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
		     <tbody>
			<tr>
                            <td>
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                    <tr class="texto_subtitulo">
                                        <td>Proyectos Curriculares<br><hr class="hr_subtitulo"></td>
                                    </tr>
                                    <tr>
					<td>
                                            <table class='contenidotabla'>
						<tr class='cuadro_color'>
                                                    <td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
                                                    <td class='cuadro_plano centrar'>Nombre </td>
						</tr><?
                        for($contador=0;$contador<$total;$contador++)
                        {//Con enlace a la busqueda
                                $parametro="pagina=adminConsultaHorariosSoporte";
                                $parametro.="&hoja=1";
                                $parametro.="&opcion=reporteGrupos";
                                $parametro.="&tipoConsulta=todos";
                                $parametro.="&accion=consultar";
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
	{/*selecciona el tipo de consulta de los grupos*/   
         switch ($variable['tipoConsulta'])
                { case 'todos':
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaGruposTodos", $variable);
                    break;
                  case 'rapida':
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaGruposRapida", $variable);
                        break;
                  default :
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaGrupos", $variable);
                    break;
                }
            //echo $cadena_sql;// exit;    
            $rsGrupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->consultaGrupos($configuracion,$rsGrupos,$variable);
	}        
		
function consultaGrupos($configuracion,$rsGrupos,$variable)
       {    //verifica fechas de modificacion de horarios
        $qryfecha=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
        $rsFecha=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryfecha, "busqueda");
       
        $varValida=array('proyecto'=>$_REQUEST['proyecto'],'anio'=>$variable['anio'],'periodo'=> $variable['periodo'],'fecha'=> $rsFecha[0]['FECHA']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valida_fecha",$varValida);
        $rsValida=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
        $this->formulario='admin_ConsultaHorarios';
        ?>
	<table class="contenidotabla centrar" border="0">
	<?if (is_array($rsGrupos)){?>
	    <tr>
		<th class="sigma" width='10%'>C&oacute;digo</th>
		<th class="sigma centrar">Asignatura</th>
		<th class="sigma centrar" width='8%'>Grupo</th>
                <th class="sigma centrar" width='8%'>Capacidad Máxima</th>
		<th class="sigma centrar" width='8%'>Cupos</th>
		<th class="sigma centrar" width='8%'>Inscritos</th>
		<th class="sigma centrar" width='8%'>Disponible</th>
		<th class="sigma centrar" width='4%'>ver</th>
	    </tr>
            <form  enctype='multipart/form-data' method='POST' action='index.php' name='<?echo $this->formulario?>' > 
	<?  foreach ($rsGrupos as $i => $value) 
                {   $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta='pagina=adminConsultaHorarioCurso';
                    $ruta.='&opcion=verHorarioGrupo';
                    $ruta.='&espacio='.$rsGrupos[$i]['COD_ESPACIO'];
                    $ruta.='&grupo='.$rsGrupos[$i]['GRUPO'];
                    $ruta.='&plan='.$variable['plan'];
                    $ruta.='&proyecto='.$rsGrupos[$i]['PROYECTO'];
                    $ruta.='&periodo='.$variable['anio'].'-'.$variable['periodo'];
                    $ruta.='&capacidad='.$rsGrupos[$i]['CUPOS'];
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                ?>
                <tr>
                    <td class="cuadro_plano"><?echo $rsGrupos[$i]['COD_ESPACIO']?></td>
                    <td class="cuadro_plano"><a href="<?echo $indice.$ruta?>"><?echo $rsGrupos[$i]['NOM_ESPACIO']?></a></td>
                    <td class="cuadro_plano centrar"><?echo $rsGrupos[$i]['GRUPO']?></td>
                    <td class="cuadro_plano centrar"><?echo $rsGrupos[$i]['MAX_CAPACIDAD']?></td>
                    <td class="cuadro_plano centrar">
                            <input type="hidden" id="proyecto<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>" value="<? echo $rsGrupos[$i]['PROYECTO'];?>">
                            <input type="hidden" id="espacio<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>" value="<? echo $rsGrupos[$i]['COD_ESPACIO'];?>" >
                            <input type="hidden" id="anio<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>" value="<? echo $variable['anio'];?>" >
                            <input type="hidden" id="per<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>" value="<? echo $variable['periodo'];?>" >
                            <input type="hidden" id="grupo<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>" value="<? echo $rsGrupos[$i]['GRUPO'];?>" >   
                    <?   if(is_array($rsValida))
                            {
                                $evt="xajax_cupos(document.getElementById('espacio".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']."').value,document.getElementById('anio".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']."').value,document.getElementById('per".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']."').value,document.getElementById('grupo".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']."').value,document.getElementById('cupos".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']."').value);";
                                $str="<center>Modificar Cupos de estudiantes para el grupo ".$rsGrupos[$i]['GRUPO']."</center>";
                                $bloquear='';
                            }
                            else
                            {
                                $evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas para modificar cupos'))";
                                $str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas para modificar cupos</center>";
                                $bloquear='readonly';
                            }
                        ?>
                        <div id="<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>">
                        <input type="text" size="3" maxlength="3" <?echo $bloquear;?> name='cupos<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>' 
                               id='cupos<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>' 
                               value="<?echo $rsGrupos[$i]['CUPOS'];?>" 
                               onChange="<?echo $evt?>"
                               onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Cambio Cupos' , PADDING, 9)" >
                        
                        </div>
                        <div id="msn<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>"></div>    
                    </td>
                    <td class="cuadro_plano centrar"><?echo $rsGrupos[$i]['INSCRITOS']?></td>
                    <td class="cuadro_plano centrar"><?echo $rsGrupos[$i]['DISPONIBLES']?></td>
                    <td class="cuadro_plano centrar"><a href="<?echo $indice.$ruta?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/ver.png" border="0"></a></td>
                </tr>
                <?}?>
                </form>
            <?}else{?>
            <tr>
                <td align="center" color="red">
                    <?
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
                    $cadena=".::No existe horario para la asignatura seleccionada::."; 
                    $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($configuracion,$cadena);
                    ?>
                </td>
            </tr>
            <?}?>
	</table>
	<?
    }
    
    
     //Muestra el reporte de los horarios y cursos.
   function verReportes($configuracion,$variable)
    {       $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenCurso",$variable);
            $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
            if(!is_array($resultado))
            {
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                    $cadena="No existen horarios ni cursos para reportar en el periodo académico ".$variable['anio']."-".$variable['periodo']."";
                    $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($configuracion,$cadena);	
            }
            else
            {       $formulario='admin_repotesExcelCoodinador';
                    ?>
                    <table class="centrar" width="100%">
                            <tr>
                                    <td class="centrar">
                                    <?   $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $enlace="pagina=adminrepotesExcelCoodinador";
                                            //$enlace.="&action=exportarHorario";
                                            $enlace.="&opcion=Horario";
                                            $enlace.="&no_pagina=true";
                                            $enlace.="&proyecto=".$variable['proyecto']."";
                                            $enlace.="&periodo=".$variable['anio']."-".$variable['periodo']."";
                                            $enlace.="&espacio=".$variable['asignatura']."";

                                            $enlace=  $this->cripto->codificar_url($enlace, $configuracion);

                                            echo " <a href='".$indice.$enlace."' target='_blank' title='Generar reporte en Excel'>";
                                            ?>
                                            <img width="30" height="30" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/excel.jpg" alt="Modificar registro" title="Modificar objetos relacionados" border="0" />
                                            <br>
                                            <?
                                            echo "Generar reporte en hoja de c&aacute;lculo";
                                            echo "</a>";
                                    ?>
                                    </td>
                            </tr>
                    </table>
                    <table class="contenidotabla  centrar" width="100%">
                         <th class="sigma centrar" colspan="10">RES&Uacute;MEN HORARIOS </th>
                            <tr>
                                    <td class="cuadro_plano centrar"width="6%">Periodo</td>
                                    <td class="cuadro_plano centrar" width="4%">Proyecto</td>
                                    <td class="cuadro_plano centrar" width="26%">Asignatura</td>
                                    <td class="cuadro_plano centrar" width="4%">Grupo</td>
                                    <td class="cuadro_plano centrar" width="10%">Lunes</td>
                                    <td class="cuadro_plano centrar" width="10%">Martes</td>
                                    <td class="cuadro_plano centrar" width="10%">Mi&eacute;rcoles</td>
                                    <td class="cuadro_plano centrar" width="10%">Jueves</td>
                                    <td class="cuadro_plano centrar" width="10%">Viernes</td>
                                    <td class="cuadro_plano centrar" width="10%">S&aacute;bado</td>
                             </tr>
                            <?
                     foreach ($resultado as $cont => $value) 
                            { ?>
                                    <tr class="cuadro_color">
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['ANIO']."-".$resultado[$cont]['PERIODO'];?></td>
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['COD_PROYECTO'];?></td>
                                            <td class="cuadro_plano ">
                                                <? if($variable['opcion']=='exportarHorario')
                                                        {  echo $resultado[$cont]['COD_ESPACIO']." - ".UTF8_DECODE($resultado[$cont]['NOM_ESPACIO']);}
                                                 else   { echo $resultado[$cont]['COD_ESPACIO']." - ".$resultado[$cont]['NOM_ESPACIO'];}      
                                                  ?>
                                            </td>
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['GRUPO'];?></td>
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
                                                            <table class="contenidotabla  centrar" width="100%">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
                                                                                echo "(".$resultadoHor[$contHor]['COD_SALON_NVO'].") - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_SALON']);}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_SALON'];} 
                                                                                if($variable['opcion']!='exportarHorario')
                                                                                        {echo "<hr> ";}
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
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
                                                                                echo "(".$resultadoHor[$contHor]['COD_SALON_NVO'].") - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_SALON']);}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_SALON'];} 
                                                                                if($variable['opcion']!='exportarHorario')
                                                                                        {echo "<hr> ";}
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
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
                                                                                echo "(".$resultadoHor[$contHor]['COD_SALON_NVO'].") - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_SALON']);}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_SALON'];}  
                                                                               if($variable['opcion']!='exportarHorario')
                                                                                        {echo "<hr> ";}
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
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
                                                                                echo "(".$resultadoHor[$contHor]['COD_SALON_NVO'].") - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_SALON']);}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_SALON'];}  
                                                                                if($variable['opcion']!='exportarHorario')
                                                                                        {echo "<hr> ";}
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
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
                                                                                echo "(".$resultadoHor[$contHor]['COD_SALON_NVO'].") - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_SALON']);}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_SALON'];}    
                                                                                if($variable['opcion']!='exportarHorario')
                                                                                        {echo "<hr> ";}
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
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
                                                                                echo "(".$resultadoHor[$contHor]['COD_SALON_NVO'].") - ";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_SALON']);}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_SALON'];}      
                                                                                if($variable['opcion']!='exportarHorario')
                                                                                        {echo "<hr> ";}
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
}

?>

