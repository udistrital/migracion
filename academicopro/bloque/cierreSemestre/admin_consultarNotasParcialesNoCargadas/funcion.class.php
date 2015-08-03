<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          funcion.class.php 
* @author        Milton Parra
* @revision      Última revisión 08 de septiembre de 2014
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		cierreSemestre
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Milton Parra
* @author		Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar cargar notas parciales que no fueron procesadas al momento del cierre
*
/*--------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


class funcion_adminConsultarNotasParcialesNoCargadas extends funcionGeneral
{
    private $periodo;
    private $formulario;
    private $bloque;
            
    

	function __construct($configuracion, $sql)
	{

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $this->procedimiento=new procedimientos();
            //$this->tema=$tema;
            $this->sql=$sql;
            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");
            //Datos de sesion
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
            $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
            $this->formulario='admin_consultarNotasParcialesNoCargadas';
            $this->bloque='cierreSemestre/admin_consultarNotasParcialesNoCargadas';
            $this->configuracion=$configuracion;
            $this->pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            if($this->usuario=="")
            {
                echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                EXIT;
            }
            //Conexion ORACLE
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
            $cadena_sql = $this->sql->cadena_sql("periodos");
            $this->periodo= $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                
                
	}
        
	/**
         * Funcion que presenta el listado de proyectos asociados al usuario
         * @param type $registro
         * @param type $totalRegistros
         * @param type $opcion
         * @param type $variable
         */
        function mostrarProyectosCargar($registro, $totalRegistros, $opcion, $variable)
    	{	switch($opcion)
		{	case "multiplesCarreras":
				$this->multiplesCarreras($registro, $totalRegistros, $variable);
				break;
		}
	}
/*__________________________________________________________________________________________________
						Metodos especificos 
____________________________________________________________________________________________________*/
	/**
         * Este función permite presentar los proyectos curriculares a los cuales está asociado el usuario de postgrado
         * @param type $registro
         * @param type $total
         * @param type $variable
         */
function multiplesCarreras($registro, $total, $variable)
	{	
            $tab=0;
            $indice=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                ?>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
		     <tbody>
			<tr>
                            <td>
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                    <tr class="texto_subtitulo">
                                        <td colspan="3">Cargar notas parciales - Seleccione el Proyecto Curricular<br><hr class="hr_subtitulo"></td>
                                    </tr>
						<tr class='cuadro_color'>
                                                    <td class='cuadro_plano centrar ancho10' >Seleccione</td>
                                                    <td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
                                                    <td class='cuadro_plano centrar'>Nombre </td>
						</tr><?
                        for($contador=0;$contador<$total;$contador++)
                        {
                            ?>
                                <tr>
                                    <td class='cuadro_plano centrar'>
                                        <input type="radio" name="codProyecto" value="<?echo $registro[$contador]['CODIGO'];?>"<?if ($contador==0) echo "checked";?>>
                                        <input type='hidden' name='nombreProyecto' value="<? echo $registro[$contador]['NOMBRE'];?>">
                                    </td>
                                    <td class='cuadro_plano centrar'><?echo $registro[$contador]['CODIGO'];?></td>
                                    <td class='cuadro_plano'><?echo $registro[$contador]['NOMBRE'];?></td>
                                            </tr><?
                        }?>
                        <tr>
                            <?  $this->seleccionarPeriodo($tab);?> 
                        </tr>
                        <tr>
                            <td width="800%" colspan='3' class="centrar">
                                <? $this->enlaceConsultar('Consultar','consultarProyecto');?>
                            </td>
                        </tr>
                        
			     </table>
		 	    </td>
			</tr>
                        
                    </tbody>
		</table>
		<?
	}

        
        /**
         * Funcion que presenta el formulario pára selccionar el ao y periodo para el cargue de notas.
         * @param type $variables
         */
        function presentarFormularioPeríodo($variables)
        {
            $tab=1;
            $datos='';
            $codigo  =(isset($_REQUEST['codigo'])?$_REQUEST['codigo']:'');
            ?>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div id="normal" >
                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr class=texto_elegante >
                            <td colspan='2'>
                                <b>::::..</b> Cargar notas parciales <?echo $variables[0]['CODIGO']." - ".$variables[0]['NOMBRE'];?>
                                <hr class=hr_subtitulo>
                            </td>
                        </tr>        
                        <tr>
                            <?  $this->seleccionarPeriodo($tab);?> 
                        </tr>
                        <tr>
                            <td width="100%" colspan='3'class="centrar" >
                                <input type='hidden' name='codProyecto' value="<? echo $variables[0]['CODIGO'];?>">
                                <input type='hidden' name='nombreProyecto' value="<? echo $variables[0]['NOMBRE'];?>">
                                <? $this->enlaceConsultar('Consultar','consultarProyecto');?>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
            <?
        }
        
    /**
     * Funcion que arma el boton de enviar datos para los formularios
     * @param type $nombre
     * @param type $opcion
     */    
    function enlaceConsultar($nombre,$opcion)
        {
            ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
              <input type='hidden' name='action' value="<? echo $this->bloque?>">
              <input type='hidden' name='opcion' value="<?echo $opcion;?>">
              <input class="centrar"value="<?echo $nombre;?>" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            <?
        }
        
        /**
         * Funcion que arma el boton de seleeccion del periodo
         * @param type $tab
         */
        function seleccionarPeriodo($tab) {
        ?>
            <td class='cuadro_plano' width="80%" colspan='3'>Seleccione el período para consultar...
            <?
                $html_perCod="<select id='periodoCargar' tabindex='".$tab++."' size='1' name='periodoCargar'>";
                foreach ($this->periodo as $key => $periodo) {
                    $html_perCod.="<option value='".$periodo['ANIO']."-".$periodo['PERIODO']."'";
                    $html_perCod.=" >".$periodo['ANIO']."-".$periodo['PERIODO']."</option>  ";          
                }
                $html_perCod.="</select>";
                echo $html_perCod;
            ?> 
            </td>
        <?            
        }
        
	/**
         * Funcion que permite presentar las notas parciales no cargadas del proyecto
         * @param type $variable
         */		
        function consultarNotasNoCargadasProyecto($variable)
	{
            $periodoActual[0]['ANIO']=$variable['anio'];
            $periodoActual[0]['PERIODO']=$variable['periodo'];
            $eventos=73;
            $consultarEvento=$this->consultarEventos($periodoActual,$eventos);
    
        ?>    
        <table class="tablaBase centrar">
            <tr height='60PX'>
                <td class="cuadro_plano centrar" width="100%">
                    <center>
                        <table style="width:100%" class="formulario contenidotabla centrar">
                            <tr>
                            <? 				
                            if(!is_array($consultarEvento)){//si el registro no existe imprime esto?>
                                <td valign='middle' width="33%" align='center'>
                                    De acuerdo a los datos registrados en el sistema <br><?echo $variable['codProyecto']." - ".$variable['nombreProyecto'];?><br>
                                    No ha realizado el cierre de semestre para el per&iacute;odo <?echo $periodoActual[0]['ANIO']."-".$periodoActual[0]['PERIODO'];?><br>
                                    Por favor verifique la información.
                                </td>
                                <?}else{?> 

                                    <td class="eventos1" valign='middle' width="20%" align='center'>
                                                    <b> Notas parciales no cargadas en <? echo $variable['anio']."-".$variable['periodo'];?> de <?=$_REQUEST['nombreProyecto']." C&oacute;d. ".$_REQUEST['codProyecto'];?></b>
                                    </td>
                                    <?
                                        $this->presentarNotasParcialesNoCargadas($variable['codProyecto'],$periodoActual);
                                    }?>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
        </table>

        <?
	}
        
        /**
         * Funcion que permite consultar los eventos de cierre de semestre para el proyecto
         * @param type $periodoActual
         * @param type $evento
         * @return type
         */
	function consultarEventos($periodoActual,$evento){    
		$variables=array('codProyecto'=>$_REQUEST['codProyecto'],
						 'periodo'=>$periodoActual[0]['PERIODO'],
						 'anio'=>$periodoActual[0]['ANIO'],
						 'evento'=>$evento);    
	
		$cadena_sql=$this->sql->cadena_sql('valida_fecha',$variables);
		$resultado_cancelo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado_cancelo;
		
	}
        
        
        /**
         * Funcion uqe presenta el listado de notas parciales no cargadas
         * @param type $codProyecto
         * @param type $periodo
         */
        function presentarNotasParcialesNoCargadas($codProyecto,$periodo) {
        ?>  <script language="javascript" type="text/javascript">
                function SelectAllCheckBox(chkbox,FormId){
                    for (var i=0;i < document.forms[FormId].elements.length;i++)
                    {
                        var Element = document.forms[FormId].elements[i];
                        if (Element.type == "checkbox")
                        Element.checked = chkbox.checked;
                    }
                }
            </script>
        <?
            $notasNoCargadas=$this->consultarNotasNoCargadas($codProyecto,$periodo);
            if (!is_array($notasNoCargadas)||is_null($notasNoCargadas))
            {
                ?><table>
                    <tr class="cuadro_color centrar">
                        <td colspan="10" class="centrar">
                            No se han encontrado registros de notas no cargadas para el per&iacute;odo <?echo $periodo[0]['ANIO']."-".$periodo[0]['PERIODO'];?>
                        </td>
                    </tr>
                </table>
                <?
            }else{
            ?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>' Id="checBox">
                <table class="sigma contenidotabla">
                    <tr class="cuadro_color centrar">
                        <td colspan="10" class="centrar">
                            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                            <input type="hidden" name="periodoCargar" value="<?echo $periodo[0]['ANIO']."-".$periodo[0]['PERIODO'];?>">
                            <? $this->enlaceConsultar('Cargar notas seleccionadas','confirmarNotas');?>
                        </td>
                    </tr>
                    <tr>
                        <td class="cuadro_plano centrar" colspan="6">Mostrando registros de notas 1 a <?echo count($notasNoCargadas);?></td>
                    </tr>
                    <tr class="cuadro_color">
                        <td class="cuadro_plano centrar">
                            No.
                        </td>
                        <td class="cuadro_plano centrar">
                            C&oacute;d. Estudiante
                        </td>
                        <td class="cuadro_plano centrar">
                            Nombre Estudiante
                        </td>
                        <td class="cuadro_plano centrar">
                            Estado
                        </td>
                        <td class="cuadro_plano centrar">
                            C&oacute;d. Espacio
                        </td>
                        <td class="cuadro_plano centrar">
                            Espacio Académico
                        </td>
                        <td class="cuadro_plano centrar">
                            Nota
                        </td>
                        <td class="cuadro_plano centrar">
                            Grupo
                        </td>
                        <td class="cuadro_plano centrar">
                            <input type='checkbox' name='SelectedAll' onclick='SelectAllCheckBox(this,"checBox")'>
                        </td>
                    </tr>
                <?
                $numero=1;
            foreach ($notasNoCargadas as $registro => $valor) {
                    echo '<tr onmouseover="this.style.background=\'#FFFFAA\'" onmouseout="this.style.background=\'\'">';
                    echo "<td align='center'>
                            ".$numero."
                        </td>";
                        if((isset($notasNoCargadas[$registro-1]['COD_ESTUDIANTE'])?$notasNoCargadas[$registro-1]['COD_ESTUDIANTE']:'')!=$valor['COD_ESTUDIANTE'])
                        {echo "<td align='center'>
                            ".$valor['COD_ESTUDIANTE']."
                        </td>
                        <td>
                            ".htmlentities($valor['ESTUDIANTE'])."
                        </td>
                        <td>
                            ".$valor['ESTADO']."
                        </td>";}else{echo "<td align='center'></td><td></td><td></td>";}
                        echo "<td>
                            ".$valor['COD_ESPACIO']."
                        </td>
                        <td>
                            ".$valor['ESPACIO']."
                        </td>
                        <td align='center'>
                            ".$valor['NOTA']."
                        </td>
                        <td align='center'>
                            ".$valor['GRUPO']."
                        </td>
                        <td align='center'>
                            <input type='checkbox' name='insc".$registro."' value='".$valor['COD_ESTUDIANTE']."-".$valor['COD_ESPACIO']."'>
                        </td>
                    </tr>";
                        $numero++;
            }
            ?>
                    <tr class="cuadro_color centrar">
                        <td colspan="10" class="centrar">
                            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                            <input type="hidden" name="periodoCargar" value="<?echo $periodo[0]['ANIO']."-".$periodo[0]['PERIODO']?>">
                            <? $this->enlaceConsultar('Cargar notas seleccionadas','confirmarNotas');?>
                        </td>
                    </tr>
                </table>
            </form>                    
            <?}
            
        }
        
        /**
         * Funcion que permite al usuario confirmar el cargue de notas
         */
        function confirmarNotas() {
            ?><link rel='stylesheet' type='text/css' href='<?echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion["estilo"];?>/basico/estilo.php'/><?
            foreach ($_REQUEST as $key => $value) {
                if ($_REQUEST[$key]=='')
                {
                    unset ($_REQUEST[$key]);
                }
            }
            if($_REQUEST['total']==0)
            {
                echo "<script>alert('Seleccione las notas que desea cargar')</script>";
                $this->enlaceVolver('consultarProyecto');
            }
                ?><body leftMargin='0' topMargin='0' class='fondoprincipal'>
                    <table class='tabla_general'>
                        <tbody>
                            <tr>
                                <td colspan='1' valign='top'>
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='seccion_C_colapsada'>
                                    <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
                                        <tr class="texto_subtitulo">
                                            <td colspan="10" class="centrar">
                                                Se van a cargar en total <?=$_REQUEST['total']?> notas para el per&iacute;odo <?echo $_REQUEST['periodoCargar'];?> ¿Desea Continuar?
                                            </td>
                                        </tr>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                        <tr class="texto_subtitulo">
                                            <td align="center">
                                                <?$this->variablesFormulario()?>
                                                <input type="image" name="aceptar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30"><br>Si
                                            </td>
                                            <td align="center">
                                              <input type="image" name="cancelar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" width="30" height="30"><br>No
                                            </td>
                                        </tr>
                                        </form>                    
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?
            
        }
        

       /**
        * Funcion que crea las variables iniciales para el envío de información en el formulario
        */
        function variablesFormulario() {
            unset ($_REQUEST['pagina']);
            unset ($_REQUEST['cancelar_x']);
            unset ($_REQUEST['cancelar_y']);
            $_REQUEST['opcion']="cargarNotas";
            $_REQUEST['action']=$this->bloque;
            foreach ($_REQUEST as $key => $value)
              {?>
                <input type="hidden" name="<?echo $key?>" value="<?echo $value?>"><?
              }
    }
    
    /**
     * Funcion que crea un enlace para retornar a otro punto del proceso
     * @param type $opcion
     */
    function enlaceVolver($opcion) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $ruta="pagina=adminConsultarNotasParcialesNoCargadas";
        $ruta.="&opcion=".$opcion;
        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
        $ruta.="&periodoCargar=".$_REQUEST['periodoCargar'];
        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        echo "<script>location.replace('".$pagina.$ruta."')</script>";
        exit;
        
    }
    
    /**
     * Funcion para retornar cuando se cancela el proceso
     */
    function cancelar() {
        $this->enlaceVolver('volver');
        exit;
    }
    
    /**
     * Funcion para realizazar el registro de notas seleccionadas
     */
    function cargarNotas() {
        $total=0;
        $registros=0;
        $codProyecto=$_REQUEST['codProyecto'];
        $periodo=explode('-', $_REQUEST['periodoCargar']);
        foreach ($_REQUEST as $key => $value) {
            if (strpos($key,'insc')!==false)
            {
                $datosInscripcion=  explode("-", $value);
                $inscripcion[$datosInscripcion[0]][]=$datosInscripcion[1];
                $total++;
            }
        }
        if($total!=$_REQUEST['total'])
        {
            echo "<script>alert('Se presentó un error. Por favor intente nuevamente.')</script>";
            $this->enlaceVolver('consultarProyecto');
        }
        foreach ($inscripcion as $key => $value) {
            $codigos=implode(",", $inscripcion[$key]);
            $registrados=$this->cargarNotasEstudiante($key,$codigos,$periodo,$codProyecto);
            $registros+=$registrados;
            if($registrados>=1)
            {
            $variablesRegistro=array('usuario'=>$this->usuario,
                                'evento'=>'86',
                                'descripcion'=>'Carga nota parcial postgrado',
                                'registro'=>$periodo[0]."-".$periodo[1].", ".$key.", \"".$codigos."\", ".$codProyecto,
                                'afectado'=>$key);
            $this->procedimiento->registrarEvento($variablesRegistro);
            }
        }
            echo "<script>alert('Se cargaron ".$registros." registros. Continúe por favor.')</script>";
            $this->enlaceVolver('consultarProyecto');
        exit;
    }
     
    
    /**
     * Funcion para consultar las notas parciales que no han sido cargadas
     * @param type $codProyecto
     * @param type $periodo
     * @return type
     */
    function consultarNotasNoCargadas($codProyecto,$periodo) {
        $variables=array('codProyecto'=>$codProyecto,
                        'periodo'=>$periodo[0]['PERIODO'],
                        'anio'=>$periodo[0]['ANIO'],
                        );
        $cadena_sql=$this->sql->cadena_sql('consultarNotasNoCargadas',$variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        return $resultado;

    }
    
    /**
     * Funcion para registrar notas
     * @param type $codEstudiante
     * @param type $espacios
     * @param type $periodo
     * @param type $codProyecto
     * @return type
     */
    function cargarNotasEstudiante($codEstudiante,$espacios,$periodo,$codProyecto) {
        $variables=array('codProyecto'=>$codProyecto,
                        'codEstudiante'=>$codEstudiante,
                        'espacios'=>$espacios,
                        'periodo'=>$periodo[1],
                        'anio'=>$periodo[0],
                        );
        $cadena_sql=$this->sql->cadena_sql('cargarNotasEstudiante',$variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        $total=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        return $total;
        
    }

}

?>

