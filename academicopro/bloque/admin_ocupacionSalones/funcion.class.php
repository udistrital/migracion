<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_AdminOcupacion extends funcionGeneral
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
		//$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		//$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
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
    	{}
/*__________________________________________________________________________________________________
						Metodos especificos 
__________________________________________________________________________________________________*/
	

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
                    <table class="centrar" width="100%">
                            <tr>
                                    <td class="centrar">
                                    <?   $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $enlace="pagina=adminrepotesExcelCoodinador";
                                            //$enlace.="&action=exportarHorario";
                                            $enlace.="&opcion=Ocupacion";
                                            $enlace.="&no_pagina=true";
                                            $enlace.="&periodo=".$variable['anio']."-".$variable['periodo']."";
                                            $enlace.="&sede=".$variable['sede']."";
                                            $enlace.="&edificio=".$variable['edificio']."";
                                            $enlace.="&salon=".$variable['salon']."";
                                            $enlace.="&proyecto=".$variable['proyecto']."";
                                            $enlace.="&espacio=".$variable['espacio']."";
                                            $enlace.="&dia=".$variable['dia']."";
                                            $enlace.="&hora=".$variable['hora']."";
                                            

                                            $enlace=  $this->cripto->codificar_url($enlace, $configuracion);

                                            echo " <a href='".$indice.$enlace."' target='_blank' title='Generar reporte en Excel'>";
                                            ?>
                                            <img width="30" height="30" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/excel.jpg" alt="Generar Reporte" title="Reporte Ocupacion" border="0" />
                                            <br>
                                            <?
                                            echo "Generar reporte en hoja de c&aacute;lculo";
                                            echo "</a>";
                                    ?>
                                    </td>
                            </tr>
                            <tr>
                                    <td class="cuadro_plano centrar">
                                    <?  echo "<b>Total Coincidencias ".$totalRegAsig=$this->totalRegistros($configuracion, $this->accesoOracle); 
                                    ?>
                                    </td>
                            </tr>
                    </table>
                    <table class="contenidotabla  centrar" width="100%">
                        <th class="sigma centrar" colspan="15">RES&Uacute;MEN OCUPACI&Oacute;N </th>
                            <tr>
                                    <td class="cuadro_plano centrar" width="6%">D&iacute;a</td>
                                    <td class="cuadro_plano centrar" width="6%">Hora</td>
                                    <td class="cuadro_plano centrar" width="20%">Asignatura</td>
                                    <td class="cuadro_plano centrar" width="6%">Grupo</td>
                                    <td class="cuadro_plano centrar" width="12%">Proyecto</td>
                                    <td class="cuadro_plano centrar" width="12%">Sal&oacute;n</td>
                                    <td class="cuadro_plano centrar" width="12%">Edificio</td>
                                    <td class="cuadro_plano centrar" width="12%">Sede</td>
                                    <td class="cuadro_plano centrar" width="12%">No Inscritos </td>
                                    <td class="cuadro_plano centrar" width="12%">...Profesor...</td>
                             </tr>
                            <?
                     foreach ($resultado as $cont => $value) 
                            { ?>
                                    <tr class="cuadro_color">
                                            <td class="cuadro_plano centrar"><? echo (isset($resultado[$cont]['DIA'])?$resultado[$cont]['DIA']:'-');?></td>
                                            <td class="cuadro_plano centrar"><? echo (isset($resultado[$cont]['HORA_L'])?$resultado[$cont]['HORA_L']:'-');?></td>
                                            <td class="cuadro_plano "><? echo $resultado[$cont]['COD_ESPACIO']." - ".$resultado[$cont]['ESPACIO'];?></td>
                                            <td class="cuadro_plano centrar"><? echo str_pad($resultado[$cont]['COD_PROYECTO'], 3, "0", STR_PAD_LEFT)."-".$resultado[$cont]['GRUPO'];?></td>
                                            <td class="cuadro_plano "><? echo $resultado[$cont]['COD_PROYECTO']." - ".$resultado[$cont]['PROYECTO'];?></td>
                                            <td class="cuadro_plano "><? echo (isset($resultado[$cont]['NOM_SALON'])?$resultado[$cont]['NOM_SALON']:'-');echo (isset($resultado[$cont]['CAP_SALON'])?" CAP(".$resultado[$cont]['CAP_SALON'].")":'-');?></td>
                                            <td class="cuadro_plano "><? echo (isset($resultado[$cont]['NOM_EDIFICIO'])?$resultado[$cont]['NOM_EDIFICIO']:'-');?></td>
                                            <td class="cuadro_plano "><? echo (isset($resultado[$cont]['ID_SEDE'])?$resultado[$cont]['ID_SEDE']:'')." - ".(isset($resultado[$cont]['NOM_SEDE'])?$resultado[$cont]['NOM_SEDE']:'');?></td>
                                            <td class="cuadro_plano centrar"><? echo (isset($resultado[$cont]['INSCRITOS'])?$resultado[$cont]['INSCRITOS']:'-');?></td>
                                            <td class="cuadro_plano "><? echo $resultado[$cont]['DOCENTE'];?></td>
                                    </tr>
                        <?  }  ?>
                    </table>
                    <?
            }
    }
    
    function reporteSalones($configuracion) {
        if(isset($_REQUEST['facultad'])&&!is_null($_REQUEST['facultad'])&&$_REQUEST['facultad']!='999'&&$_REQUEST['facultad']!='0')
        {
            $facultad=$_REQUEST;
        }  else {
            $facultad='';
        }
        $reporteSalones=$this->consultarSalones($configuracion,$facultad);
        $salones=count($reporteSalones);
        ?>
                    <table class="contenidotabla  centrar" width="100%">
                        <?
                        for($i=0;$i<$salones;$i++)
                        {
                            if($reporteSalones[$i]['FACULTAD']!=(isset($reporteSalones[$i-1]['FACULTAD'])?$reporteSalones[$i-1]['FACULTAD']:''))
                            {
                        ?>
                        <th><tr><td></td></tr></th>
                        <th class="sigma centrar" colspan="15"><? echo $reporteSalones[$i]['FACULTAD'];?></th>
                            <tr class="cuadro_color">
                                    <td class="cuadro_plano centrar" width="15%">EDIFICIO</td>
                                    <td class="cuadro_plano centrar" width="10%">COD. ESPACIO</td>
                                    <td class="cuadro_plano centrar" width="30%">NOMBRE ESPACIO F&Iacute;SICO</td>
                                    <td class="cuadro_plano centrar" width="10%">CAPACIDAD</td>
                                    <td class="cuadro_plano centrar" width="25%">TIPO DE ESPACIO</td>
                                    <td class="cuadro_plano centrar" width="10%">DESTINADO PARA CLASE</td>
                             </tr>
                             <?}
                             ?>
                                    <tr>
                                            <td class="cuadro_plano centrar" width="15%"><? echo $reporteSalones[$i]['NOM_EDIFICIO'];?></td>
                                            <td class="cuadro_plano centrar" width="10%"><? echo $reporteSalones[$i]['COD_SALON'];?></td>
                                            <td class="cuadro_plano " width="30%"><? echo $reporteSalones[$i]['NOM_SALON'];?></td>
                                            <td class="cuadro_plano centrar" width="10%"><? echo (isset($reporteSalones[$i]['CAPACIDAD'])?$reporteSalones[$i]['CAPACIDAD']:'-');?></td>
                                            <td class="cuadro_plano " width="25%"><? echo $reporteSalones[$i]['TIPO_ESPACIO'];?></td>
                                            <td class="cuadro_plano centrar" width="10%"><?if((isset($reporteSalones[$i]['ASIGNA_CLASE'])?$reporteSalones[$i]['ASIGNA_CLASE']:'')=='SI'){echo "<strong>";} echo (isset($reporteSalones[$i]['ASIGNA_CLASE'])?$reporteSalones[$i]['ASIGNA_CLASE']:'');if((isset($reporteSalones[$i]['ASIGNA_CLASE'])?$reporteSalones[$i]['ASIGNA_CLASE']:'')=='SI'){echo "</strong>";}?></td>
                                    </tr>
                        <?  }  ?>
                    </table>
                    <?

    }
    
    function consultarSalones($configuracion,$facultad) {
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "reporteSalones", $facultad);
            return $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
    }
}

?>

