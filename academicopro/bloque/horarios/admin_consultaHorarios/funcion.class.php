<script type="text/javascript" language="javascript">
function viewNodes(oImg,elem)
{ 	var cKids = elem.childNodes;
    for(var i=0;i<cKids.length;i++){
        if ("UL" == cKids[i].tagName){
            if(cKids[i].style.display=="block"){
                cKids[i].style.display="none";
                oImg.src = "./grafico/mas.jpg";
            }else{
                cKids[i].style.display="block";
                oImg.src = oImg.src = "./grafico/menos.jpg"
            }
        }
    }
}

function HideAll(sTagName)
{
    cElems = document.getElementsByTagName(sTagName);
    iNumElems = cElems.length;
    if(iNumElems>0){cElems[0].style.display = "block";
        cElems[1].style.display = "block";
    } 		for(var i=2;i<iNumElems;i++) cElems[i].style.display = "none";
}

function ShowAll(sTagName)
{
    cElems = document.getElementsByTagName(sTagName);
    iNumElems = cElems.length;
    if(iNumElems>0){cElems[0].style.display = "block";
        cElems[1].style.display = "block";
    } 		for(var i=2;i<iNumElems;i++) cElems[i].style.display = "block";
}

function HideChildNodes(sIdName)
{
    cElems = document.getElementById(sIdName);
    iNumElems = cElems.length;
    if(iNumElems>0)cElems[0].style.display = "block";
    for(var i=1;i<iNumElems;i++) cElems[i].style.display = 'none';
}
</script>

<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

class funciones_adminConsultaHorario extends funcionGeneral
{
    private $configuracion;
    private $periodo;
    function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
                $this->validacion=new validarInscripcion();
		
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
		$this->formulario='admin_ConsultaHorarios';
		$this->configuracion=$configuracion;
		$cadena_sql=$this->sql->cadena_sql('periodo');
		$this->periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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
	
function multiplesCarreras($configuracion,$registro, $total, $variable){

		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
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
                                $parametro="pagina=adminConsultaHorarios";
                                $parametro.="&hoja=1";
                                $parametro.="&opcion=consultarGrupos";
                                $parametro.="&tipoConsulta=todos";
                                $parametro.="&accion=consultar";
                                $parametro.="&proyecto=".$registro[$contador]['CODIGO'];
                                //$parametro.="&carrera=".$registro[$contador][0];
                                //$parametro.="&xajax=nbcPrimario|nbcSecundario|justificaEst|propeCra|acreCra|procesar_formulario|cancelaAcre";
                                //$parametro.="&xajax_file=proyecto";
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

	function consultarCarrera($variable)
	{/*selecciona el tipo de consulta de los grupos*/   
		 switch ($variable['tipoConsulta']){ 
			case 'todos':
				$cadena_sql=$this->sql->cadena_sql( "consultaGruposTodos", $variable);
			break;
			case 'rapida':
				$cadena_sql=$this->sql->cadena_sql( "consultaGruposRapida", $variable);
				break;
			default :
				$cadena_sql=$this->sql->cadena_sql( "consultaGrupos", $variable);
			break;
		}

                        $rsGrupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
			$this->consultaGrupos($rsGrupos,$variable);
	}        
		
	function consultaGrupos($rsGrupos,$variable)
	{
		$varValida=array('proyecto'=>$_REQUEST['proyecto'],'anio'=>$variable['anio'],'periodo'=> $variable['periodo'],'fecha'=> date('YmdHis'));
		$rsValida=$this->consultarFechasHorarios($varValida);
		
		?>    
		<table width="100%" align=center border="0">
		<?if (is_array($rsGrupos)){
            $grupos=  count($rsGrupos);
            $a=0;
            
            foreach ($rsGrupos as $value) {
                $espacios[]=array('CODIGO'=>$value['COD_ESPACIO'],
                                    'NOMBRE'=>$value['NOM_ESPACIO']);
            }
            $espacios = array_map("unserialize", array_unique(array_map("serialize", $espacios)));    
            $clase="cuadro_planoBorde centrar";
            $clase2="lista_simple";?>
			<tr><td><div id="AgreementMenu">
			<ul class="menu">
            <li>
                <div onClick="viewNodes(this,this.parentNode)" style="cursor: pointer">
					<h2>ESPACIOS CON GRUPOS CREADOS <?echo $variable['anio']."-".$variable['periodo']?><h2>
				 <?if(!is_array($rsValida)){?>
					[Las fechas para creaci&oacute;n de grupos se encuentran cerradas]
				 <?}?>
				 </div>
                <div ><div onClick="ShowAll('ul')" style="cursor: pointer;display: inline" class="<?echo $clase2?>">[Mostrar Todos] &nbsp;&nbsp;</div><div onClick="HideAll('ul')" style="cursor: pointer;display: inline" class="<?echo $clase2?>">&nbsp;&nbsp; [Ocultar Todos]</div></div>
				<br/>
			   <ul class="menu" id="category">
                    <?foreach ($espacios as $key=>$espacio)
                    {
						$a=$key;
                        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta='pagina=adminConsultaHorarioCurso';
                        $ruta.='&opcion=generar';
                        $ruta.='&espacio='.$rsGrupos[$key]['COD_ESPACIO'];
                        $ruta.='&proyecto='.$rsGrupos[$key]['PROYECTO'];
                        $ruta.='&periodo='.$variable['anio'].'-'.$variable['periodo'];
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    
                    ?>
						<li>
                        <div onClick="viewNodes(this,this.parentNode)" style="cursor: pointer">
                            <table width ="100%">
                                <tr  onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                                    <td width='65%' class="<?echo $clase2?>"><?echo $espacio['CODIGO']." - ".$espacio['NOMBRE'];?></td>
                                    <?if(is_array($rsValida)){?>
                                    <td width='35%' class="<?echo $clase2?>" style="text-align:right" onClick="location.href='<?=$indice.$ruta?>'">
									Crear Nuevo Grupo
									<img width="20" height="20" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"]?>/new.png" alt="" title="" border="0" />
									</td>
                                    <?}?>
                                </tr>
                            </table>
                        </div>
                        <ul class="menu"><div>
                            <table width ="100%">
                                <tr class="<?echo $clase?>">
                                    <td width='15%'>Grupo</td>
                                    <td width='25%'>Capacidad M&aacute;xima</td>
                                    <td width='15%'>Cupo</td>
                                    <td width='15%'>Inscritos</td>
                                    <td width='15%'>Disponibles</td>
                                </tr>
                            </table>
                        </div>
                        </ul>
                            <?
                        for($i=$a;$i<$grupos;$i++)
                        {
                            $ruta='pagina=adminConsultaHorarioCurso';
                            $ruta.='&opcion=verHorarioGrupo';
                            $ruta.='&curso='.$rsGrupos[$i]['CURSO'];
                            $ruta.='&espacio='.$rsGrupos[$i]['COD_ESPACIO'];
                            $ruta.='&grupo='.$rsGrupos[$i]['GRUPO'];
                            $ruta.='&plan='.$variable['plan'];
                            $ruta.='&proyecto='.$rsGrupos[$i]['PROYECTO'];
                            $ruta.='&periodo='.$variable['anio'].'-'.$variable['periodo'];
                            $ruta.='&capacidad='.$rsGrupos[$i]['CUPOS'];
                            $ruta.='&tipocurso='.$rsGrupos[$i]['TIPO'];
                            $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                            
                            if($espacio['CODIGO']==(isset($rsGrupos[$i]['COD_ESPACIO'])?$rsGrupos[$i]['COD_ESPACIO']:''))
                            {?>
                                <ul class="menu">
								<table width ="100%" style="border-collapse:collapse">
									<tr style="cursor:pointer;" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
										<td class="<?echo $clase?>" width='15%' onclick="location.href='<?=$indice.$ruta?>'" ><?echo str_pad($rsGrupos[$i]['PROYECTO'], 3, 0, STR_PAD_LEFT)."-".$rsGrupos[$i]['GRUPO']?></td>
										<td class="<?echo $clase?>" width='25%' onclick="location.href='<?=$indice.$ruta?>'" ><?echo $rsGrupos[$i]['MAX_CAPACIDAD']?></td>
										<td class="<?echo $clase?>" width='15%' onclick="">
<!--                                            <input type="text" size="3" maxlength="3" readonly name='cupos<? //echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>' id='cupos<? //echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>' 												value="<?//echo $rsGrupos[$i]['CUPOS'];?>" onclick="">-->
												<input type="hidden" id="proyecto<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>" value="<? echo $rsGrupos[$i]['PROYECTO'];?>">
												<input type="hidden" id="espacio<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>" value="<? echo $rsGrupos[$i]['COD_ESPACIO'];?>" >
												<input type="hidden" id="anio<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>" value="<? echo $variable['anio'];?>" >
												<input type="hidden" id="per<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>" value="<? echo $variable['periodo'];?>" >
												<input type="hidden" id="grupo<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>" value="<? echo $rsGrupos[$i]['CURSO'];?>" >  
										<?   	if(is_array($rsValida)){                      
													$evt="xajax_cupos(document.getElementById('espacio".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']."').value,document.getElementById('anio".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']."').value,document.getElementById('per".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']."').value,document.getElementById('grupo".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']."').value,document.getElementById('cupos".$rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']."').value);";
													$str="<center>Modificar Cupos de estudiantes para el grupo ".str_pad($rsGrupos[$i]['PROYECTO'], 3, 0, STR_PAD_LEFT)."-".$rsGrupos[$i]['GRUPO']."</center>";
													$bloquear='';
												}
												else{
													$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas para modificar cupos'))";
													$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas para modificar cupos</center>";
													$bloquear='readonly';
												}
											?>
											<div id="<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['GRUPO']; ?>">
											<input type="text" size="3" maxlength="3" <?echo $bloquear;?> name='cupos<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>' 
												   id='cupos<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>' 
												   value="<?echo $rsGrupos[$i]['CUPOS'];?>" 
												   onChange="<?echo $evt?>"
												   onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Cambio Cupos' , PADDING, 9)" >

											</div>
											<div id="msn<? echo $rsGrupos[$i]['COD_ESPACIO'].$rsGrupos[$i]['CURSO']; ?>"></div>    
										</td>
										<td class="<?echo $clase?>" width='15%' onclick="location.href='<?=$indice.$ruta?>'" ><?echo $rsGrupos[$i]['INSCRITOS']?></td>
										<td class="<?echo $clase?>" width='15%' onclick="location.href='<?=$indice.$ruta?>'" ><?echo $rsGrupos[$i]['DISPONIBLES']?></td>
									</tr>                                            
								</table>
                                </ul>

                        <?}
                        }?>
                    </li>
                    <?}?>
                </ul>
            </li>
        </ul>
		</div>
        <script>
            HideAll("ul");
        </script>
		</td></tr>
            <?}else{?>
            <tr>
                <td align="center" color="red">
                    <?
					$this->consultarAsignatura();
					
                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                    $cadena=".:: No existen grupos creados ::."; 
                    $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($this->configuracion,$cadena);
                    ?>
                </td>
            </tr>
            <?}?>
	</table>

	<?
    }
	
	
	
	function consultarAsignatura(){
			
	}
	
    
    function consultarFechasHorarios($varValida) {
        $cadena_sql=$this->sql->cadena_sql("valida_fecha",$varValida);
        return $rsValida=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
     //Muestra el reporte de los horarios y cursos.
   function verReportes($variable)
    {       
       if(is_numeric($variable['anio']) && is_numeric($variable['periodo']) && is_numeric($variable['proyecto'])){
            
            $cadena_sql=$this->sql->cadena_sql("resumenCurso",$variable);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
            if(!is_array($resultado))
            {
                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
                    $cadena="No existen horarios ni cursos para reportar en el periodo académico ".$variable['anio']."-".$variable['periodo']."";
                    $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($this->configuracion,$cadena);	
            }
            else
            {       $formulario='admin_repotesExcelCoodinador';
                    ?>
                    <table class="centrar" width="100%">
                            <tr>
                                    <td class="centrar">
                                    <?   $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                            $enlace="pagina=adminrepotesExcelCoodinador";
                                            //$enlace.="&action=exportarHorario";
                                            $enlace.="&opcion=Horario";
                                            $enlace.="&no_pagina=true";
                                            $enlace.="&proyecto=".$variable['proyecto']."";
                                            $enlace.="&periodo=".$variable['anio']."-".$variable['periodo']."";
                                            $enlace.="&espacio=".$variable['asignatura']."";

                                            $enlace=  $this->cripto->codificar_url($enlace, $this->configuracion);

                                            echo " <a href='".$indice.$enlace."' target='_blank' title='Generar reporte en Excel'>";
                                            ?>
                                            <img width="30" height="30" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"]?>/excel.jpg" alt="Generar Reporte" title="Reporte Hoja de Calculo" border="0" />
                                            <br>
                                            <?
                                            echo "Generar reporte en hoja de c&aacute;lculo";
                                            echo "</a>";
                                    ?>
                                    </td>
                            </tr>
                    </table>
                    <table class="contenidotabla  centrar" width="100%">
                         <th class="sigma centrar" colspan="11">RES&Uacute;MEN HORARIOS </th>
                            <tr>
                                    <td class="cuadro_plano centrar"width="4%">Periodo</td>
                                    <td class="cuadro_plano centrar" width="4%">Proyecto</td>
                                    <td class="cuadro_plano centrar" width="16%">Asignatura</td>
                                    <td class="cuadro_plano centrar" width="6%">Grupo</td>
                                    <td class="cuadro_plano centrar" width="10%">Lunes</td>
                                    <td class="cuadro_plano centrar" width="10%">Martes</td>
                                    <td class="cuadro_plano centrar" width="10%">Mi&eacute;rcoles</td>
                                    <td class="cuadro_plano centrar" width="10%">Jueves</td>
                                    <td class="cuadro_plano centrar" width="10%">Viernes</td>
                                    <td class="cuadro_plano centrar" width="10%">S&aacute;bado</td>
                                    <td class="cuadro_plano centrar" width="10%">Domingo</td>
                             </tr>
                            <?
							foreach ($resultado as $cont => $value){
							?>
                                    <tr class="cuadro_color">
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['ANIO']."-".$resultado[$cont]['PERIODO'];?></td>
                                            <td class="cuadro_plano centrar"><? echo $resultado[$cont]['COD_PROYECTO'];?></td>
                                            <td class="cuadro_plano ">
                                    <? 
                                        $variable['opcion']=isset($variable['opcion'])?$variable['opcion']:"";
					if($variable['opcion']=='exportarHorario'){ 
                                            echo $resultado[$cont]['COD_ESPACIO']." - ".UTF8_DECODE($resultado[$cont]['NOM_ESPACIO']);
					}else{
                                            	echo $resultado[$cont]['COD_ESPACIO']." - ".$resultado[$cont]['NOM_ESPACIO'];
					}      
                                    ?>
                                            </td>
                                            <td class="cuadro_plano centrar"><? echo str_pad($variable['proyecto'], 3, "0", STR_PAD_LEFT)."-".$resultado[$cont]['GRUPO'];?></td>
                                            <td class="cuadro_plano centrar">
                                                <?   $varHorario=array('proyecto'=>$resultado[$cont]['COD_PROYECTO'],
                                                                        'asignatura'=> $resultado[$cont]['COD_ESPACIO'], 
                                                                        'anio'=>$resultado[$cont]['ANIO'], 
                                                                        'periodo'=>$resultado[$cont]['PERIODO'], 
                                                                        'grupo'=>$resultado[$cont]['GRUPO'], 
                                                                        'dia'=>'1');
                                                        $cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor){
													?>
                                                            <table class="centrar">
                                                            <? foreach ($resultadoHor as $contHor => $value) {?>
                                                                <tr>
                                                                        <td class="sigma centrar">
                                                                                <?
                                                                                echo $resultadoHor[$contHor]['HORA_L']." - ";
                                                                                echo $resultadoHor[$contHor]['NOM_SEDE']." - ";
										$variable['opcion']=isset($variable['opcion'])?$variable['opcion']:"";
                                                                                if($variable['opcion']=='exportarHorario')
                                                                                       { echo UTF8_DECODE($resultadoHor[$contHor]['NOM_EDIFICIO'])." - ";}
                                                                                else   { echo $resultadoHor[$contHor]['NOM_EDIFICIO']." - ";}      
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
                                                        $cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
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
							$cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
							$resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
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
                                                        $cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
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
                                                        $cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
                                                        $resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
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
                                                        $cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
							$resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {	?>
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
									'dia'=>'7');
                                                        $cadena_sql=$this->sql->cadena_sql("resumenHorarioCurso",$varHorario);
							$resultadoHor=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                    
                                                    if($resultadoHor)
                                                            {	?>
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
        }else{
               include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
               $cadena="Valores no validos. ";
               $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
               alerta::sin_registro($this->configuracion,$cadena);	
       }
    } 
}

?>

