<?php
/**
 * Funcion admin_preinscripcion
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package Preinscripcion_Automatica
 * @subpackage Admin
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 26/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");

/**
 * Clase funcion_admin_preinscripcion
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package Preinscripcion_Automatica
 * @subpackage Admin
 */
class funcion_admin_preinscripcion extends funcionGeneral {

    /**
     * Método constructor que crea el objeto sql de la clase funcion_admin_preinscripcion
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion) {

        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        /**
         * Incluye la clase fpdf.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/pdf/fpdf.php");

        require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/ProgressBar.class.php");
        
        $this->cripto = new encriptar();
        $this->sql = new sql_admin_preinscripcion();
        $this->formulario = "admin_preinscripcion";

        /**
         * Intancia para crear la conexion ORACLE
         */
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
        /**
         * Intancia para crear la conexion al framework
         */
        $this->acceso_db = $this->conectarDB($configuracion, "");
		$this->configuracion=$configuracion;
        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        /**
         * Datos de sesion
         */
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
    }
	
	
	/**
     * Funcion que se encarga de mostrar los proyectos curriculares y planes de estudio que tiene a cargo el coordinador
     *
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function verProyectos($configuracion)
    {
        /*
         * Consulta los proyectos curriculares con su respectivo plan de estudio, y se muestra en un <select>
         */
        $cadena_sql = $this->sql->cadena_sql($configuracion, "proyectos_curriculares", $this->usuario); //echo $cadena_sql;exit;
        $resultado_proyectos = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1){

			?>
			<table class='sigma_borde centrar' align="center" width="80%" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
			
			<caption class="sigma">SELECCIONE EL PROYECTO CURRICULAR</caption>
			<th class="sigma centrar">Carrera</th>
			<th class="sigma centrar">Nombre</th>
    

			<?
			
				for($i=0;$i<count($resultado_proyectos);$i++) {
					?>
						<tr>
					<?
						if ($resultado_proyectos[$i][0]==97 || $resultado_proyectos[$i][0]==98)
						{
							$cadena_sql_plan=$this->sql->cadena_sql("planEstudio",$resultado_proyectos[$i][2]);//echo $cadena_sql_plan;exit;
							$nombreProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_plan,"busqueda" );
							$resultado_proyectos[$i][1]=$nombreProyecto[0][0];
						}
						$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
						$variable="pagina=admin_preinscripcion";
						$variable.="&opcion=pintarFormulario";
						$variable.="&codProyecto=".$resultado_proyectos[$i][0];
						$variable.="&nombreProyecto=".$resultado_proyectos[$i][1];

						$variable=$this->cripto->codificar_url($variable,$this->configuracion);

					?>
						<td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][0]?></a></td>
						 <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][1]?></a></td>
					</tr>
					<?
				}
		}elseif(count($resultado_proyectos)==1){
							
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
			$variable="pagina=admin_preinscripcion";
			$variable.="&opcion=pintarFormulario";
			$variable.="&codProyecto=".$resultado_proyectos[0][0];
			$variable.="&nombreProyecto=".$resultado_proyectos[0][1];

			echo "<script>location.replace('".$pagina.$variable."')</script>";

		}
    }
	
	
    /**
     * Funcion que muestra el la pantalla principal de la preinscripcion
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework     
     */
    function vista_preinscripcion($configuracion)
    {
       
        $cadena_sql = $this->sql->cadena_sql($configuracion,"periodo_academico",'');
        $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$variables_preins = array($_REQUEST['codProyecto'], $resultado_periodo[0][0], $resultado_periodo[0][1]);
			
			$this->formularioCondor= "admin_preinscripcion2";
            $this->verificar="control_vacio(".$this->formularioCondor.",'nro_max_asignaturasCondor')";
            $this->verificar.="&& verificar_rango(".$this->formularioCondor.",'nro_max_asignaturasCondor','0','10')";
            $this->verificar.="&& verificar_rango(".$this->formularioCondor.",'nro_max_semestresCondor','0','10')";
        ?>
            <fieldset>
                    <legend><?=$_REQUEST['nombreProyecto']?></legend>
                    <table class="contenidotabla centrar">
                        <?
                        $cadena_sql = $this->sql->cadena_sql($configuracion, "verificarParametrosCondor", $variables_preins);//echo $cadena_sql;
                        $parametrosCondor = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                        ?>
                        <tr class="izquierda">
                            <td class="izquierda">
                                    
                                </a> Parametros para Adiciones y Cancelaciones
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="div_parametrosCondor" style="">
                                    <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formularioCondor;?>'>
                                        <table class="contenidotabla centrar" border="1">
                                        <tr>
                                            <td width="60%">
                                    <fieldset>
                                    <legend>Horas</legend>
                                    <table class="contenidotabla centrar">
                                        <tr>
                                            <?
                                            $mensajeSemestresSuperiores="Si escoge SI, se permitir&aacute; inscribir asignaturas de semestres superiores al que se encuentra el estudiante.";
                                            $mensajeSemestresSuperiores.="<br>Si escoge NO, solo inscribir&aacute;n las asignaturas del semestre siguiente al que se encuentra el estudiante.";
                                            ?>
                                            <td onmouseover="Tip('<?echo $mensajeSemestresSuperiores?>', SHADOW, true, TITLE, 'Semestres Superiores', PADDING, 9)">
                                                <table class="contenidotabla centrar">
                                                    <tr>
                                                        <td width="55%" class="centrar"><font color="red" size="2"> * </font>Semestres Superiores: </td>
                                                        <td class="izquierda">
                                                            <table class="contenidotabla centrar">
                                                                <tr>
                                                                    <td>
																		Si <input type="radio" name="semestresSuperioresCondor" value="S" <?if ($parametrosCondor[0][4] == 'S') {echo "checked";} ?>
                                                                                  onclick="">
                                                                    </td>
																	<td>
																		No <input type="radio" name="semestresSuperioresCondor" value="N" <?if ($parametrosCondor[0][4] == 'N') {echo "checked";} ?>
                                                                                  onclick="">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                      </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        <!--</tr>
                                            <?
                                            $mensajeSemestresConsecutivos="Seleccione SI si el n&uacute;mero de semestres permitidos son consecutivos, de lo contrario seleccione NO";
                                            ?>
                                        <tr>-->
                                            <td onmouseover="Tip('<?echo $mensajeSemestresConsecutivos?>', SHADOW, true, TITLE, 'Semestres Consecutivos', PADDING, 9)">
                                                <div id="div_semestresConsecutivosCondor">
                                                    <table class="contenidotabla centrar">
                                                    <tr>
                                                        <td width="55%" class="centrar"><font color="red" size="2"> * </font>Semestres Consecutivos: </td>
                                                        <td class="izquierda">
                                                            <table class="contenidotabla centrar">
                                                                <tr>
                                                                    <td>
                                                                        Si <input type="radio" name="semestresConsecutivosCondor"  value="S" <? if ($parametrosCondor[0][2] == 'S') {echo "checked";} ?>>
                                                                    </td>
                                                                    <td>
                                                                        No <input type="radio" name="semestresConsecutivosCondor"  value="N" <? if ($parametrosCondor[0][2] == 'N') {echo "checked";} ?>>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                          </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        <!--</tr>
                                            <?
                                            $mensajeMaximoSemestres="Digite el n&uacute;mero m&aacute;ximo de semestres de los cuales el estudiante podr&aacute; inscribir asignaturas";
                                            ?>
                                        <tr>-->
                                            <td onmouseover="Tip('<?echo $mensajeMaximoSemestres?>', SHADOW, true, TITLE, 'Nro M&aacute;ximo de Semestres', PADDING, 9)">
                                                <div id="div_maximoSemestresCondor">
                                                    <table class="contenidotabla centrar">
                                                    <tr>
                                                        <td width="55%" class="centrar"><font color='red' size='2'> * </font>Nro. M&aacute;ximo Semestres:  </td>
                                                        <td class="izquierda">
                                                            <table class="contenidotabla centrar">
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <input type="text" name="nro_max_semestresCondor" size="3" maxlength="3" value="<? if ($parametrosCondor[0][1] > 0) {echo $parametrosCondor[0][1];} ?>">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <?
                                            $mensajeMasAsignaturas="Seleccione SI, para permitir que los estudiantes que tengan perdidas tres o m&aacute;s asignaturas,<br>";
                                            $mensajeMasAsignaturas.="puedan inscribir otras asignaturas. De lo contrario seleccione NO";
                                            ?>
                                            <td onmouseover="Tip('<?echo $mensajeMasAsignaturas?>', SHADOW, true, TITLE, 'Mas Asignaturas', PADDING, 9)">
                                                <table class="contenidotabla centrar">
                                                    <tr>
                                                        <td width="55%" class="centrar"><font color='red' size='2'> * </font>Mas Asignaturas: </td>
                                                        <td class="izquierda">
                                                            <table class="contenidotabla centrar">
                                                                <tr>
                                                                    <td>Si <input type="radio" name="masAsignaturasCondor" value="S" <? if ($parametrosCondor[0][5] == 'S') {echo "checked";} ?>></td>
                                                                    <td>No <input type="radio" name="masAsignaturasCondor" value="N" <? if ($parametrosCondor[0][5] == 'N') {echo "checked";} ?>></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        <!--</tr>
                                            <?
                                            $mensajeMaximoAsignaturas="Digite el n&uacute;mero m&aacute;ximo de asignaturas que <br>";
                                            $mensajeMaximoAsignaturas.="puede cursar los estudiantes";
                                            ?>
                                        <tr>-->
                                            <td onmouseover="Tip('<?echo $mensajeMaximoAsignaturas?>', SHADOW, true, TITLE, 'N&uacute;mero M&aacute;ximo Asignaturas', PADDING, 9)">
                                                <div id="div_nro_max_asignaturasCondor">
                                                    <table class="contenidotabla centrar">
                                                    <tr>
                                                        <td width="55%" class="centrar"><font color='red' size='2'> * </font>Nro. M&aacute;ximo Asignaturas: </td>
                                                        <td class="izquierda">
                                                            <table class="contenidotabla centrar">
                                                                <tr>
                                                                    <td colspan='2'>
                                                                    <input type="text" name="nro_max_asignaturasCondor" size="3" maxlength="3" value="<? if ($parametrosCondor[0][3] > 0) {echo $parametrosCondor[0][3];} ?>">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div>
                                            </td>
                                        <!--</tr>
                                            <?
                                            $mensajeVerificarRequisito="Seleccione SI, para verificar los requisitos de las asignaturas, de lo contrario seleccione NO";
                                            ?>
                                        <tr>-->
                                            <td onmouseover="Tip('<?echo $mensajeVerificarRequisito?>', SHADOW, true, TITLE, 'Verificar Requisito', PADDING, 9)">
                                                <div id="div_maximoSemestresCondor">
                                                   <table class="contenidotabla centrar">
                                                    <tr>
                                                        <td width="55%" class="centrar"><font color="red" size="2"> * </font>Verificar Requisito: </td>
                                                        <td class="izquierda">
                                                            <table class="contenidotabla centrar">
                                                                <tr>
                                                                    <td>Si <input type="radio" name="verificar_requisitoCondor" value="S" <? if ($parametrosCondor[0][0] == 'S') {echo "checked";} ?>></td>
                                                                    <td>No <input type="radio" name="verificar_requisitoCondor" value="N" <? if ($parametrosCondor[0][0] == 'N') {echo "checked";} ?>></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    </fieldset>
                                </td>
								</tr>
                                                                 
									<tr>
										<td colspan="3" class="centrar">
											<input type="hidden" name="annio" value="<?echo $resultado_periodo[0][0]?>">
											<input type="hidden" name="periodo" value="<?echo $resultado_periodo[0][1]?>">
											<input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
											<input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
											<input type="hidden" name="prioridadPerdidas" value="S">
											<input type="hidden" name="prioridadCondor" value="J">
											<input type="hidden" name="parametros" value="C">
											<input type="hidden" name="opcion" value="parametros_condor">
											<input type="hidden" name="action" value="<?echo $this->formulario?>">
											<input type="button" name="guardar" value="<?if(is_array($parametrosCondor)){echo "Actualizar Parametros";}else{echo "Guardar Parametros";}?>" onclick="if(<?=$this->verificar;?>){document.forms['<?=$this->formularioCondor?>'].submit()}else{false}" >
											
										</td>
									</tr>
							</table >
                                </form>
                                </div>
                            </td>
                        </tr>
                    </table>
                </fieldset><?
	}

    /**
     * Registra los parametros de preinscripción y parametros de Condor
     * Esta función sirve para los dos tipos de registros (P) Preinscripción
     * o (C) Condor
     * 
     */
    function registrar_parametros($configuracion)
    {
        switch($_REQUEST['parametros'])
        {
          

                case 'C':

                if($_REQUEST['semestresSuperioresCondor']==NULL ||
                        $_REQUEST['masAsignaturasCondor']==NULL ||
                        $_REQUEST['nro_max_asignaturasCondor']==NULL ||
                        $_REQUEST['verificar_requisitoCondor']==NULL ||
                        $_REQUEST['prioridadCondor']==NULL 
                    )
                {
                    echo "<script>alert('Todos los campos marcados con * son obligatorios')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=admin_preinscripcion";
                    $variable.="&opcion=pintarFormulario";
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                    $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                    $variable.="&nroPensum=".$_REQUEST["nroPensum"];

                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }else{
                        if($_REQUEST['semestresSuperioresCondor']=='S')
                        {
                            if($_REQUEST['semestresConsecutivosCondor']==NULL || $_REQUEST['nro_max_semestresCondor']==NULL)
                                {
                                    echo "<script>alert('Todos los campos marcados con * son obligatorios')</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=admin_preinscripcion";
                                    $variable.="&opcion=mostrar";
                                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                    $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                    $variable.="&nroPensum=".$_REQUEST["nroPensum"];

                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }else
                                    {
                                        $variableGuardar[6]=$_REQUEST['nro_max_semestresCondor'];
                                        $variableGuardar[7]=$_REQUEST['semestresConsecutivosCondor'];
                                    }
                        }else
                            {
                                $variableGuardar[6]='1';
                                $variableGuardar[7]='N';
                            }

                        $variableGuardar[0]=$_REQUEST['codProyecto'];
                        $variableGuardar[1]=$_REQUEST['annio'];
                        $variableGuardar[2]=$_REQUEST['periodo'];
                        $variableGuardar[3]='0';
                        $variableGuardar[4]='99999999999';
                        $variableGuardar[5]=$_REQUEST['verificar_requisitoCondor'];
                        $variableGuardar[8]=$_REQUEST['nro_max_asignaturasCondor'];
                        $variableGuardar[9]=$_REQUEST['semestresSuperioresCondor'];
                        $variableGuardar[10]=$_REQUEST['masAsignaturasCondor'];
                        $variableGuardar[11]=$_REQUEST['prioridadPerdidasCondor'];
                        $variableGuardar[12]=$_REQUEST['prioridadCondor'];
                        $variableGuardar[13]='A';
						$variableGuardar[14]='C';
                        $variableGuardar[15]='';
                        $variableGuardar[16]='';
                        $variableGuardar[17]='';
                        $variableGuardar[18]='';
                        $variableGuardar[19]='';
                        $variableGuardar[20]='';
                        $variableGuardar[21]='';
                        $variableGuardar[22]='';
                        $variableGuardar[23]='';

                        //Verifica si existen datos de parametros ya registrados para el periodo académico vigente
                        $cadena_sql = $this->sql->cadena_sql($configuracion, "verificarParametrosCondor",$variableGuardar );
                        $resultado_parametros = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                        if(is_array($resultado_parametros))
                        {
                                //Actualiza los parametros actuales
                                $cadena_sql = $this->sql->cadena_sql($configuracion, "actualizarParametrosPreinscripcion",$variableGuardar );
                                $resultado_parametrosActualizacion = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

                                if($resultado_parametrosActualizacion==true){
								
                                        echo "<script>alert('Los parámetros para la preinscripción se han actualizado con éxito')</script>";
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=admin_preinscripcion";
                                        $variable.="&opcion=pintarFormulario";
                                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                        $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                        $variable.="&nroPensum=".$_REQUEST["nroPensum"];

                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }else
                                        {
                                            echo "<script>alert('La base de datos se encuentra ocupada, por favor intente más tarde')</script>";
                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=admin_preinscripcion";
                                            $variable.="&opcion=pintarFormulario";
                                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                            $variable.="&nroPensum=".$_REQUEST["nroPensum"];

                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                                            exit;
                                        }
                            }else
                                {
                                    //Insertar nuevos datos de parametros
                                    $cadena_sql = $this->sql->cadena_sql($configuracion, "insertarParametrosPreinscripcion",$variableGuardar );//echo $cadena_sql;exit;
                                    $resultado_parametrosInsertar = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

                                    if($resultado_parametrosInsertar==true)
                                    {
                                        echo "<script>alert('Los parametros para la preinscripción se han guardado con éxito')</script>";
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=admin_preinscripcion";
                                        $variable.="&opcion=pintarFormulario";
                                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                        $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                        $variable.="&nroPensum=".$_REQUEST["nroPensum"];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }else
                                        {
                                            echo "<script>alert('La base de datos se encuentra ocupada, por favor intente más tarde')</script>";
                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=admin_preinscripcion";
                                            $variable.="&opcion=pintarFormulario";
                                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                            $variable.="&nroPensum=".$_REQUEST["nroPensum"];

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                                            exit;
                                        }
                                }
                }

                    break;
        }

        
    }

  

}

?>
