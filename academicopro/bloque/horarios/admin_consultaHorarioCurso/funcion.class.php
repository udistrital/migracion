<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funciones_adminHorarios extends funcionGeneral {
    private $configuracion;

    //@ Método costructor
    function __construct($configuracion, $sql) {

        $this->cripto = new encriptar();
        $this->procedimientos=new procedimientos();
        $this->validacion=new validarInscripcion();
        $this->sql = $sql;
        
        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        //Conexion Oracle
        if($this->nivel==4){
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
        }elseif($this->nivel==110){
            $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        
        $this->configuracion=$configuracion;
        $this->formulario = 'admin_consultaHorarioCurso';
        $this->bloque = 'horarios/admin_consultaHorarioCurso';

    }

function formCrear($configuracion)
{    
        $espacio=isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'';
        $grupo=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
        $max_capacidad=isset($_REQUEST['max_capacidad'])?$_REQUEST['max_capacidad']:'';
        $cupos=isset($_REQUEST['cupos'])?$_REQUEST['cupos']:'';
        $proyecto=$_REQUEST['proyecto'];
        $per=isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'';
        $anio=substr($per,-6,4);
        $periodo=substr($per,-1);
        $this->identificacion=isset($this->identificacion)? $this->identificacion:'';
        $variable = $this->identificacion;
        
        if($this->nivel==110 ){
                            $verificacion=$this->validacion->verificarProyectoAsistente($proyecto,$this->usuario,$this->nivel);
                            if($verificacion!='ok')
                                {
                                    ?>
                                          <table class="contenidotabla centrar">
                                            <tr>
                                              <td class="cuadro_brownOscuro centrar">
                                                  <?echo $verificacion;?>
                                              </td>
                                            </tr>
                                          </table>
                                    <?
                                    exit;
                                }
                    }
        $qryfecha= "SELECT TO_CHAR(CURRENT_TIMESTAMP,'YYYYMMDDHH24MISS') ".'"FECHA"';
        $rsFecha=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryfecha, "busqueda");
        $variable=array('proyecto'=>$proyecto,'anio'=>$anio,'periodo'=> $periodo,'fecha'=> $rsFecha[0]['FECHA']);
        $cadena_sql=$this->sql->cadena_sql($configuracion, "valida_fecha", $variable);
        $rsValida=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if (!is_array($rsValida))
        { ?>
		<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class="cuadro_plano cuadro_color" >
			<tr>
				<td align="center">.:: En este momento no puede crear cursos. Las fechas en el CALENDARIO ACADÉMICO para modificación de Horarios están cerradas ::. 
                                </td>
                        </tr>
                </table>
            <?
            exit;

        }
        
        if($espacio!='' && is_numeric($espacio)){ 
			$arregloEA=array('espacio'=>$espacio,'proyecto'=>$proyecto);
			$cadena_sql = $this->sql->cadena_sql($configuracion, "rescatarAsignatura", $arregloEA);
			$resultadoEspacio = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			if(!is_array($resultadoEspacio)){ 
				$errorAsig=" Esta asignatura no pertenece a ningún plan de estudios de su Proyecto Curricular";}
			}  
		else{
			$errorAsig=" Seleccione una asignatura válida para registrar el horario";
		}

        if(isset($errorAsig)){?>
		
			<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class="cuadro_plano cuadro_color" >
                <tr>
					<td align="center" valign="top">
                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="30" height="30">
						<font size="3" ><? echo $errorAsig;?></font>
					</td>
                </tr>
            </table>
             <?
        }else{
		
    	?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
		<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class="cuadro_plano cuadro_color" >
			<tr>
				<td align="center">
					<?

					if(!isset($espacio) && !isset($grupo))
					    {$habilitado =" disabled='true' ";}

					if(!isset($espacio))
					    {$soloLectura =" readonly='true' ";}
                                         ?>
					<b>REGISTRAR CURSO A ASIGNATURA</b><br>
                    <font size="3" ><? echo $resultadoEspacio[0]['COD_ESPACIO']." - ".$resultadoEspacio[0]['NOM_ESPACIO'];?></font>
				</td>
			</tr>
			<tr>
				<td>
					<table class="contenidotabla centrar" border="0">
						<tr>
							<td  class="centrar" onmouseover="Tip('<center>Periodo académico para registrar curso</center>', SHADOW, true, TITLE, 'Periodo', PADDING, 9)">
								<b>PERIODO</b><br>
								<?
								$tab=0;
								$arregloPeriodo = array($espacio, $proyecto);
								$cadena_sql = $this->sql->cadena_sql($configuracion, "periodo", $arregloPeriodo);
								$resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

								$habilitado=isset($habilitado)?$habilitado:'';
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
								$html=new html();
								foreach ($resultadoPeriodo as $key => $value){
									$valorPer[$key][0]=$resultadoPeriodo[$key]['ANIO']."-".$resultadoPeriodo[$key]['PERIODO'];
									$valorPer[$key][1]=$resultadoPeriodo[$key]['ANIO']."-".$resultadoPeriodo[$key]['PERIODO'];
								}
								
								if(isset($_REQUEST['periodo']) && $_REQUEST['periodo'] ==$resultadoPeriodo[$key]['ANIO']."-".$resultadoPeriodo[$key]['PERIODO'])
								{
									$nivel=$html->cuadro_lista($valorPer,'periodo',$configuracion,$resultadoPeriodo[$key]['ANIO']."-".$resultadoPeriodo[$key]['PERIODO'],0,FALSE,$tab++,'periodo');
								}
								else{ 
									$nivel=$html->cuadro_lista($valorPer,'periodo',$configuracion,1,0,FALSE,$tab++,'periodo');
								}        
								echo $nivel;

								?>
							</td>
							<td class="centrar" onmouseover="Tip('<center>Identificación del grupo a registrar</center>', SHADOW, true, TITLE, 'Grupo', PADDING, 9)">
								<b>GRUPO</b><br>
								<input type="text" name='grupo' id="grupo" value="<?=$grupo?>" size="3" maxlength="5" align="center" onchange="xajax_validar(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('cupos').value,<?echo $proyecto;?>)">
							</td>
                                                        <td  class="centrar" onmouseover="Tip('<center>Capacidad Máxima de cupos del curso</center>', SHADOW, true, TITLE, 'Capacidad Máxima', PADDING, 9)">
								<b>CAPACIDAD DEL CURSO</b><br>
								<input type="text" <?echo $habilitado?> name='max_capacidad' id="max_capacidad" value="<?echo $max_capacidad?>" size="3" align="center" >
							</td>
							<td  class="centrar" onmouseover="Tip('<center>Cupos para inscripciones de Estudiantes', SHADOW, true, TITLE, 'Cupos inscripción', PADDING, 9)">
								<b>CUPOS</b><br>
								<input type="text" <?echo $habilitado?> name='cupos' id="cupos" value="<?echo $cupos?>" size="3" align="center" >
							</td>
							<td  class="centrar" onmouseover="Tip('<center>Modalidad del curso', SHADOW, true, TITLE, 'Tipo de Curso', PADDING, 9)">
								<b>TIPO CURSO</b><br>
								<SELECT name='tipocurso' id="tipocurso" >
									<option value="1">TE&Oacute;RICO</option>
									<option value="2">PR&Aacute;CTICO</option>
									<option value="3">TE&Oacute;RICO/PR&Aacute;CTICO</option>
								</SELECT>
							</td>
                                                        
		    
						</tr>
					</table>
				</td>  
			</tr>
		</table>
		<?
			$this->verificar="control_vacio(".$this->formulario.",'grupo')";
			$this->verificar.="&&control_vacio(".$this->formulario.",'max_capacidad')";
			$this->verificar.="&&control_vacio(".$this->formulario.",'cupos')";
			$this->verificar.="&&control_vacio(".$this->formulario.",'espacio')";
			$this->verificar.="&&verificar_numero(".$this->formulario.",'grupo')";
			$this->verificar.="&&verificar_numero(".$this->formulario.",'max_capacidad')";
			$this->verificar.="&&verificar_numero(".$this->formulario.",'cupos')";
			//$this->verificar.="&&seleccion_valida(".$this->formulario.",'docEncargado')";
			$this->verificar.="&&seleccion_valida(".$this->formulario.",'periodo')";
			//$this->verificar.="&&seleccion_valida(".$this->formulario.",'espacio')";
			$this->verificar.="&&verificar_rango(".$this->formulario.",'cupos','0','100')";
		?>
		<table align=center style="width: 100%; text-align: left;" class="bloquelateral cuadro_color " >
			<tr>
				<td align="center">
					<input type="hidden" name="action" value="<? echo $this->bloque ?>">
					<input type="hidden" name="opcion" value="guardado">
					<input type='hidden' name='verHorario' id='hidHorario' value='0'>
					<input type="hidden" name="proyecto" value="<? echo $proyecto ?>">
                                        <input type="hidden" name="espacio" value="<? echo $espacio ?>">
					<input type="hidden" name="plan" value="<? echo (isset($plan)?$plan:'') ?>" >
					<input value="Guardar Curso" id="btnGrabar" name="aceptar" type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}" >
				</td>
			</tr>
		</table>
           </form>
	<?
        
        }
}

function guardarCurso($configuracion)
{
            $anio=substr($_REQUEST['periodo'],-6,4);
            $periodo=substr($_REQUEST['periodo'],-1);
            $espacio=$_REQUEST['espacio'];
            $grupo=$_REQUEST['grupo'];
            $curso=$_REQUEST['curso'];
            $cupos=$_REQUEST['cupos'];
            $max_capacidad=$_REQUEST['max_capacidad'];
            $proyecto=$_REQUEST['proyecto'];
            $plan=$_REQUEST['plan'];
            $tipocurso=$_REQUEST['tipocurso'];
            $curSesion=1;
			
            if(is_numeric($anio) && is_numeric($periodo) && is_numeric($espacio) && is_numeric($grupo) && is_numeric($cupos) && is_numeric($max_capacidad) && is_numeric($proyecto) && is_numeric($tipocurso)){
                    if($this->nivel==110 ){
                            $verificacion=$this->validacion->verificarProyectoAsistente($proyecto,$this->usuario,$this->nivel);
                            if($verificacion!='ok')
                                {
                                    ?>
                                          <table class="contenidotabla centrar">
                                            <tr>
                                              <td class="cuadro_brownOscuro centrar">
                                                  <?echo $verificacion;?>
                                              </td>
                                            </tr>
                                          </table>
                                    <?
                                    exit;
                                }
                    }
                    $arregloPensum = array($espacio,$proyecto);
                    $cadena_sql = $this->sql->cadena_sql($configuracion, "infoAsignatura", $arregloPensum);
                    $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
                    $semestre=$resultado[0][0];

                    $arregloAsig = array('espacio'=>$espacio,'grupo'=>$grupo,'anio'=>$anio,'periodo'=>$periodo,'proyecto'=>$proyecto);
                    $resultadoexiste=$this->consultarCurso($arregloAsig);

                    //si el curso ya esta creado con su respectivo cupos
                    if($cupos>$max_capacidad){
                            echo "<script>alert('¡ATENCIÓN! EL CUPO ES MAYOR A LA CAPACIDAD DEL CURSO');</script>";
        //                $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','generar',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso);
        //                exit;
                    }
                    if(is_array($resultadoexiste)){
                                        //Si se desea cambiar la cupos se deben revisar todos los horarios creados para no generar
                                        //incovenientes con la cupos de los salones
                                $curso=$resultadoexiste[0]['CURSO'];

                                        $variable=array('curso'=>$curso,'espacio'=>$espacio,'grupo'=>$grupo,'anio'=>$anio,'periodo'=>$periodo);

                                        //con esta consulta revisamos si el grupo ya tiene horarios creados
                                $cadena_sql=$this->sql->cadena_sql($configuracion, "infoHorario", $variable); 
                                        $resultadoSalon=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");


                                        $valida=0;
                                $mensajeErrorCapacidad="";
                                // funcion que realiza la validacion de los cupos del salon y del curso y presenta error cunado el curso es mayor al salon
                                if(is_array($resultadoSalon))
                                        {  $i=0;
                                                while(isset($resultadoSalon[$i]['SEDE']))
                                                        {       $variable=array('sede'=>$resultadoSalon[$i]['SEDE'], 'salon'=>$resultadoSalon[$i]['SALON']);
                                                                        $cadena_sql=$this->sql->cadena_sql($configuracion, "infoSalon", $variable);
                                                                        $resultadoCapacidadSalon=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                                        //&& $resultadoCapacidadSalon[0]['COD_SEDE']!=0 -- filtro que omite los salones por asignar/
                                                                        if($max_capacidad > $resultadoCapacidadSalon[0]['CAPACIDAD'] && $resultadoCapacidadSalon[0]['COD_SEDE']!=0 )
                                                                                        {  $valida=1;
                                                                                           $mensajeErrorCapacidad.=" * La capacidad máxima del salón ".$resultadoSalon[$i]['SALON']." ocupado el dia ".$resultadoSalon[$i]['DIA']." a las ".$resultadoSalon[$i]['HORA']." horas es de ".$resultadoCapacidadSalon[0]['CAPACIDAD']." cupos.";
                                                                                        }
                                                                        $i++;
                                                        }
                                        }
                                if ($valida == 1){ 
                                                        echo "<script>alert('NO SE PUDIERON ACTUALIZAR LOS DATOS DEL GRUPO ".str_pad($proyecto, 3, "0", STR_PAD_LEFT)."-".$grupo." POR LOS SIGUIENTES MOTIVOS: $mensajeErrorCapacidad ');</script>";
                                                $datosRegistro=array('usuario'=>$this->usuario,
                                                                          'evento'=>'60',
                                                                          'descripcion'=>'Error al crear grupo de curso',
                                                                          'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso.", PRY=".$proyecto.", CAP=".$max_capacidad.", CUP=".$cupos,
                                                                          'afectado'=>$proyecto);
                                                $this->procedimientos->registrarEvento($datosRegistro);

                                                $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso);
                                                exit;
                                }else{
                                                $arregloAct = array('proyecto'=>$proyecto,'anio'=>$anio,'periodo'=>$periodo,'espacio'=>$espacio,'grupo'=>$grupo,'max_capacidad'=>$max_capacidad,'cupos'=>$cupos);
                                                $cadena_sql = $this->sql->cadena_sql($configuracion, "actualizarCurso", $arregloAct);
                                                $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
                                                if($resultado==true)
                                                                {   echo "<script>alert('SE ACTUALIZÓ LA CAPACIDAD MÁXIMA Y EL CUPO DEL CURSO ".str_pad($proyecto, 3, "0", STR_PAD_LEFT)."-".$grupo." SATISFACTORIAMENTE. ');</script>";
                                                                        $datosRegistro=array('usuario'=>$this->usuario,
                                                                                                  'evento'=>'60',
                                                                                                  'descripcion'=>'Actualiza grupo de curso',
                                                                                                  'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso.", PRY=".$proyecto.", CAP=".$max_capacidad.", CUP=".$cupos,
                                                                                                  'afectado'=>$proyecto);
                                                                        $this->procedimientos->registrarEvento($datosRegistro);
                                                                        $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso);
                                                                        exit;
                                                                }
                                                            else
                                                                {   echo "<script>alert('NO SE PUDIERON ACTUALIZAR LOS DATOS DEL CURSO ".str_pad($proyecto, 3, "0", STR_PAD_LEFT)."-".$grupo."');</script>";
                                                                    $datosRegistro=array('usuario'=>$this->usuario,
                                                                                              'evento'=>'60',
                                                                                              'descripcion'=>'Error al crear grupo de curso',
                                                                                              'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso.", PRY=".$proyecto.", CAP=".$max_capacidad.", CUP=".$cupos,
                                                                                              'afectado'=>$proyecto);
                                                                    $this->procedimientos->registrarEvento($datosRegistro);
                                                                        $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso);
                                                                        exit;
                                                                }
                              }
                        }
                    else {
                                $curso=$this->consultarIDCurso();		
        //					//cambiar por la consulta a la secuencia de cursos
        //					$curso=rand(1,99999);

                            $arregloInsertarCurso = array('anio'=>$anio,
                                                          'periodo'=>$periodo,
                                                          'espacio'=>$espacio,
                                                          'curso'=>$curso[0][0],
                                                          'grupo'=>$grupo,
                                                          'proyecto'=>$proyecto,
                                                          'max_capacidad'=>$max_capacidad,
                                                          'cupos'=>$cupos,
                                                          'estado'=>'A',
                                                          'tipocurso'=>$tipocurso
                                                                                                          );
                            $resultado=$this->registrarCurso($arregloInsertarCurso);

                                                if($resultado==true){
                                                        echo "<script>alert('EL GRUPO $grupo SE REGISTRÓ CON EXITO. ');</script>";
                                                        $datosRegistro=array('usuario'=>$this->usuario,
                                                                                  'evento'=>'58',
                                                                                  'descripcion'=>'Crea grupo de curso',
                                                                                  'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso[0][0].", PRY=".$proyecto.", CAP=".$max_capacidad.", CUP=".$cupos,
                                                                                  'afectado'=>$proyecto);
                                                        $this->procedimientos->registrarEvento($datosRegistro);

                                    $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso[0][0]);
                                    exit;
                                }else
                                {   echo "<script>alert('NO FUE POSIBLE REGISTRAR EL GRUPO, VERIFIQUE LOS DATOS E INTENTE DE NUEVO.');</script>";
                                    $datosRegistro=array('usuario'=>$this->usuario,
                                                              'evento'=>'58',
                                                              'descripcion'=>'Error al crear grupo de curso',
                                                              'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso.", PRY=".$proyecto.", CAP=".$max_capacidad.", CUP=".$cupos,
                                                              'afectado'=>$proyecto);
                                    $this->procedimientos->registrarEvento($datosRegistro);
                                    $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','generar',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso);
                                    exit;
                                }

                    }
                
            }else{
                     echo "<script>alert('VALORES NO VALIDOS PARA EL REGISTRO DEL GRUPO.');</script>";
                            $datosRegistro=array('usuario'=>$this->usuario,
                                                      'evento'=>'58',
                                                      'descripcion'=>'Error al crear grupo de curso',
                                                      'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso.", PRY=".$proyecto.", CAP=".$max_capacidad.", CUP=".$cupos,
                                                      'afectado'=>$proyecto);
                            $this->procedimientos->registrarEvento($datosRegistro);
                            $this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','generar',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,(isset($_REQUEST['funcion'])?$_REQUEST['funcion']:''),$curso);
                            exit;
            }

        }

        
        /**
        **Aspectos a mejorar: Hay que traer una sola consulta para los horarios. no una por cada hora del while
        **no olvidar corregir el año y el period q estan por defecto 2013 - 1
        
        **/
    function verHorarioGrupo($configuracion)
    {?>
<?
		$proyecto=$_REQUEST['proyecto'];
		$plan=isset($_REQUEST['plan'])?$_REQUEST['plan']:"";
		$espacio=$_REQUEST['espacio'];
		$grupo=$_REQUEST['grupo'];
		$cupos=isset($_REQUEST['cupos'])?$_REQUEST['cupos']:"";
		$curso=isset($_REQUEST['curso'])?$_REQUEST['curso']:"";
		$anio=substr($_REQUEST['periodo'],-6,4);
		$periodo=substr($_REQUEST['periodo'],-1);
		$tipo="consulta";

		$this->verificar="control_vacio(".$this->formulario.",'cupos')";
		$this->verificar.="&&control_vacio(".$this->formulario.",'max_capacidad')";
		$this->verificar.="&&verificar_numero(".$this->formulario.",'cupos')";
		$this->verificar.="&&verificar_numero(".$this->formulario.",'max_capacidad')";
		$variable=array('proyecto'=>$proyecto,'plan'=>$plan,'espacio'=>$espacio,'anio'=>$anio,'periodo'=>$periodo,'grupo'=>$grupo,'curso'=>$curso);
		$cadena_sql=$this->sql->cadena_sql($configuracion, "infoGrupo",$variable);
		$rsGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

		//Busca los grupos de la Asignatura
		$cadena_sql=$this->sql->cadena_sql($configuracion, "CursosEspacio",$variable);
		$rsCursos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                $cursoConsulta=0;
		foreach ($rsCursos as $key => $value){  	
			if($rsGrupo[0]['CURSO']==$rsCursos[$key]['CURSO']){
				$cursoConsulta=$key;
			}
		}        
		//consulta los docentes
        $arregloDocente = array('espacio'=>$espacio,'proyecto'=>$proyecto,'anio'=>$anio,'periodo'=>$periodo,'grupo'=>$grupo,'curso'=>$curso);
        $cadena_sql = $this->sql->cadena_sql($configuracion, "infoDocente", $arregloDocente);
        $resDocente = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
        $qryfecha= "SELECT TO_CHAR(CURRENT_TIMESTAMP,'YYYYMMDDHH24MISS') ".'"FECHA"';        
        $rsFecha=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryfecha, "busqueda");
        $variable=array('proyecto'=>$proyecto,'anio'=>$anio,'periodo'=> $periodo,'fecha'=> $rsFecha[0]['FECHA']);
        $cadena_sql=$this->sql->cadena_sql($configuracion, "valida_fecha", $variable);
        $rsValida=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
        if (is_array($rsValida)){
			$visible="";
            $soloLectura ="";
        }
        else{
            $visible="disabled='disabled'";
            $habilitado =" disabled='true' ";
            $soloLectura =" readonly='true' ";
        }
        if (!is_array($rsValida))
        { ?><div class="celda_curso" align="center"><?
            echo "<br><strong>.:: En este momento no podrá: insertar, actualizar, ni borrar registros. Las fechas en el CALENDARIO ACADÉMICO para modificación de Horarios están cerradas ::.<br></strong>"; 
            ?></div><?
        }
                        $datos=array('codProyecto'=>$proyecto,'ano'=>$anio,'periodo'=>$periodo);
                        $cadena_sql=$this->sql->cadena_sql($configuracion, "consultarInscripcionAutomatica",$datos);
                        $resultadoInscripcion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                        if(is_array($resultadoInscripcion)&&!isset($resultadoInscripcion[0]['FIN']))
                        {
                            ?><div class="celda_curso" align="center"><?
                            echo "<br><strong>.:: En este momento no podrá: insertar, actualizar, ni borrar registros. Se está ejecutando el proceso de Inscripción Automática para el Proyecto ::.<br></strong>"; 
                            ?></div><?
                            $visible="disabled='disabled'";
                            $habilitado =" disabled='true' ";
                            $soloLectura =" readonly='true' ";
                        }
        
                        ?>
	<div id="div_mostrarHorario" style="display:block">
		<div class="encabezado_curso">	
			<form  enctype='multipart/form-data' method='POST' action='index.php' name='<?echo $this->formulario?>'>
			      <?  echo "<h2>DATOS DEL CURSO - ".$rsGrupo[0]['ASI_NOMBRE']."</h2>";
			      echo "<input  name='espacio' id='espacio' type='hidden' value='".$rsGrupo[0]['CURSO']."' />";
			      ?>
			      DOCENTES ENCARGADOS:
			      <? 
			      if($resDocente){ 
				  foreach($resDocente as $key => $value){
				    echo $resDocente[$key]['DOC_DOCENTE']." - ".$resDocente[$key]['APE_DOCENTE']." ".$resDocente[$key]['NOM_DOCENTE'];
				  }
			      }else{ 
				  echo 'NO SE HA REGISTRADO DOCENTE PARA ESTE CURSO';
			      }    
			      ?>
			      <br>
                              <table width="100%"><tr><td  class="celda_curso" align="left">
                              <input value="<< Regresar" id="btnBusqueda" name="busqueda" type="submit" onmouseover="Tip('<center>Regresa al listado de Cursos', SHADOW, true, TITLE, 'Regresar', PADDING, 9)" ></td>
                              <td class="celda_curso">            
			      GRUPO
                                  <?
			      $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			      $ruta='pagina=adminConsultaHorarioCurso';
			      $ruta.='&opcion=verHorarioGrupo';
			      $ruta.='&espacio='.$rsGrupo[0]['ASI_CODIGO'];
			      $ruta.='&proyecto='.$rsGrupo[0]['PROYECTO'];
			      $ruta.='&periodo='.$rsGrupo[0]['ANIO'].'-'.$rsGrupo[0]['PERIODO'];

			      if(isset($rsCursos[$cursoConsulta-1]['CURSO'])){
			      //imprime el curso anterior del espacio
			      $rutaAnt=$ruta.'&curso='.$rsCursos[$cursoConsulta-1]['CURSO'].'&grupo='.$rsCursos[$cursoConsulta-1]['GRUPO'];
			      $rutaAnt=$this->cripto->codificar_url($rutaAnt,$configuracion);
			      ?><a href="<?echo $indice.$rutaAnt?>"><font size="2"><b><?echo "<< ".str_pad($proyecto, 3, "0", STR_PAD_LEFT)."-".$rsCursos[$cursoConsulta-1]['GRUPO']." - ";?></b></font></a>
			      <?}?>
			      <input type="text" <?echo $soloLectura?> name='grupo' id="grupo" value="<?echo str_pad($proyecto, 3, "0", STR_PAD_LEFT)."-".$_REQUEST['grupo']?>" size="5" align="center" >
			      <?
			      //imprime el curso siguiente del espacio
			      if(isset($rsCursos[$cursoConsulta+1]['CURSO']))
			      {
				$rutaSig=$ruta.'&curso='.$rsCursos[$cursoConsulta+1]['CURSO'].'&grupo='.$rsCursos[$cursoConsulta+1]['GRUPO'];
				$rutaSig=$this->cripto->codificar_url($rutaSig,$configuracion);
			      ?><a href="<?echo $indice.$rutaSig?>"><font size="2"><b><?echo " - ".str_pad($proyecto, 3, "0", STR_PAD_LEFT)."-".$rsCursos[$cursoConsulta+1]['GRUPO']." >>";?></b></font></a>
			      <?}?>
			      </td>
                              <td alig="center" class="celda_curso">
			      <input type="hidden" name="action" value="<? echo $this->bloque ?>">
			      <input type="hidden" name="opcion" value="guardado">
			      <input type='hidden' name='verHorario' id='hidHorario' value='2'>
			      <input type='hidden' name='funcion' id='funcion' value='consulta'>
			      <input type="hidden" name="proyecto" value="<? echo $proyecto ?>">
			      <input type="hidden" name="plan" value="<? echo $plan ?>" >
			      <input type="hidden" name="espacio" value="<? echo $espacio ?>" >
			      <input type="hidden" name="periodo" value="<? echo $anio."-".$periodo?>" >
			      <input type="hidden" name="grupo" value="<? echo $grupo?>" >
			      <input type="hidden" name="curso" value="<? echo $curso?>" >
			      
				CAPACIDAD DEL CURSO:<input type="text" <?echo $soloLectura?> name='max_capacidad' id="max_capacidad" value="<?echo $rsGrupo[0]['MAX_CAPACIDAD'];?>" size="3" align="center" onmouseover="Tip('<center>Capacidad Máxima de cupos del curso</center>', SHADOW, true, TITLE, 'Capacidad Máxima', PADDING, 9)">
				CUPOS: <input type="text" <?echo $soloLectura?> name='cupos' id="cupos" value="<?echo $rsGrupo[0]['CUPOS'];?>" size="5" align="center" onmouseover="Tip('<center>Cupos para inscripciones de Estudiantes', SHADOW, true, TITLE, 'Cupos inscripción', PADDING, 9)" >

			      <input value="Actualizar Curso" <?echo $visible?> id="btnGrabar" name="aceptar" type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit();}else{false}" 
			      onmouseover="Tip('<center>Actualiza solo Capacidad y Cupos', SHADOW, true, TITLE, 'Actualizar', PADDING, 9)" ></td>
			      <td alig="right" class="celda_curso"><input value="Eliminar Curso" id="btnBusqueda" name="elimina" type="submit" <?echo $visible?> onmouseover="Tip('<center>Borra el registro del Curso', SHADOW, true, TITLE, 'Eliminar', PADDING, 9)"></td>
			      
			      <?php $codCurso=$curso ?>
			      <input type="hidden" name="id_curso" id="id_curso" value="<?=$codCurso?>" >
				</tr></table>			    
						
			</form>			
				
			</div>	
				
				
			<div class="encabezado_curso_salon">
			  <h2>SELECCIONE EL SALÓN QUE DESEA ASIGNAR</h2>
			  <? echo $this->rescatarSede($anio,$periodo,$rsGrupo[0]['MAX_CAPACIDAD']);?>
			  <div id='buscador_salones'>
			    <? echo $this->rescatarSalonesCompletos($anio,$periodo,$rsGrupo[0]['MAX_CAPACIDAD']);?>
			  </div>
			  <div id="info_salon" >
			 
			  </div>
                          <table align="center">
                              <tr>
                                  <td width="20px" bgColor="#C1D5A5"></td><td class="lista_simple"> Sal&oacute;n Habilitado</td>
                                  <td width="20px" bgColor="#FE9A2E"></td><td class="lista_simple"> Sal&oacute;n Ocupado</td>
                              </tr>
                              <tr>
                                  
                              </tr>
                          </table>
			</div>	
				
				
				<?
				if(isset($espacio) && isset($grupo))
				{
					?>
					
					<table border="0" style="width: 100%; text-align: left;  border-collapse: collapse;" class="cuadro_plano cuadro_color " >
					<thead>
						<tr class="centrar">
							<th class="celda_titulo_horario">DIA/HORA</th>
							<th class="celda_titulo_horario">LUNES</th>
							<th class="celda_titulo_horario">MARTES</th>
							<th class="celda_titulo_horario">MIERCOLES</th>
							<th class="celda_titulo_horario">JUEVES</th>
							<th class="celda_titulo_horario">VIERNES</th>
							<th class="celda_titulo_horario">SABADO</th>
							<th class="celda_titulo_horario">DOMINGO</th>
						</tr>
					</thead>
					<tbody>
					<?
    
					$this->cadena_sql = $this->sql->cadena_sql($configuracion, "hora", "");
					$resultado = $this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
                                        $i = 0;
					$habilitadoHor = 0;
                                        $borrarHorario=" >";
                                        $actualizarHorario='';
                                        $actualizarCelda='';

                                        while (isset($resultado[$i]['HORA_C']))
					{
						?>
						<tr>
							<td align="center" valign="middle" class="celda_titulo_horario">
							   <?=$resultado[$i]['HORA_L']?>
							</td>
							<?
							for($dia=1;$dia<=7;$dia++){
							      $arregloHorario = array('curso'=>$codCurso,'dia'=>$dia,'hora'=>$resultado[$i]['HORA_C'],'anio'=>$anio,'periodo'=>$periodo);
							      $this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
							      $resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
                                                            if (is_array($rsValida))
                                                            {
                                                                $borrarHorario="onclick='borrarHorario(\"{$dia}-{$resultado[$i]['HORA_C']}\",\"$anio\",\"$periodo\")'>[X]";
                                                                $actualizarHorario="onclick='actualizarCeldaHora(\"{$dia}-{$resultado[$i]['HORA_C']}\",\"$anio\",\"$periodo\")'";
                                                                $actualizarCelda="onclick='actualizarCeldaHora(\"{$dia}-{$resultado[$i]['HORA_C']}\",\"$anio\",\"$periodo\")'";
                                                            }
                                                            if(is_array($resultadoInscripcion)&&!isset($resultadoInscripcion[0]['FIN']))
                                                            {
                                                                $borrarHorario="";
                                                                $actualizarHorario="";
                                                                $actualizarCelda="";
                                                            }
                                                            
                                                              
							?>
							  <td class="celda_hora" id="<?=$dia?>-<?=$resultado[$i]['HORA_C']?>">
							      <?
							      if(is_array($resultadoHor)){
									  echo "<div class='borrar_horario'".$borrarHorario."</div>";
									  echo "<div class='contenido_horario' ".$actualizarHorario." >";
									  echo "	Sede: ".$resultadoHor[0]['NOM_SEDE']."<br>Edificio: ".$resultadoHor[0]['NOM_EDIFICIO']."<br>Salon: ".$resultadoHor[0]['SALON_NVO']."<BR> ".$resultadoHor[0]['NOM_SALON']."";
									  echo "</div>";
							      }else{
									  echo "<div class='contenido_horario' ".$actualizarCelda.">";
									  echo "<br/>";
									  echo "</div>";
							      }?>
							  </td>
							<?
							}
							?>
						</tr>
				    <?	
				      $i++;
				    }
				    
				    ?>
					</tbody>
					</table>
				
			    <?	} ?>
			
	
	</div>
	
	<?    
}//fin function

	function eliminaCurso($configuracion){
            
			$proyecto=$_REQUEST['proyecto'];
			$plan=$_REQUEST['plan'];
			$espacio=$_REQUEST['espacio'];
			$grupo=$_REQUEST['grupo'];
			$curso=$_REQUEST['curso'];
			$max_capacidad=$_REQUEST['max_capacidad'];
			$cupos=$_REQUEST['cupos'];
			$anio=substr($_REQUEST['periodo'],-6,4);
			$periodo=substr($_REQUEST['periodo'],-1);
			
			$variable=array('curso'=>$curso,'espacio'=>$espacio,'grupo'=>$grupo,'anio'=>$anio,'periodo'=>$periodo);
			
			$cadena_sql=$this->sql->cadena_sql($configuracion, "infoHorario", $variable);
			$rsHorario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

			$variable=array('curso'=>$curso,'espacio'=>$espacio,'grupo'=>$grupo,'anio'=>$anio,'periodo'=>$periodo);
			$cadena_sql=$this->sql->cadena_sql($configuracion, "infoCarga", $variable);
			$rsCarga=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			 
			$variable=array('curso'=>$curso,'espacio'=>$espacio,'grupo'=>$grupo,'anio'=>$anio,'periodo'=>$periodo);
			$cadena_sql=$this->sql->cadena_sql($configuracion, "infoInscritos", $variable);
			$rsInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                        
                        $datos=array('codProyecto'=>$proyecto,'ano'=>$anio,'periodo'=>$periodo);
                        $cadena_sql=$this->sql->cadena_sql("consultarInscripcionAutomatica",$datos);
                        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

			if(is_array($rsHorario)){
				echo "<script>alert('No se puede borrar el curso, porque tiene un horario asociado ')</script>";
				$this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,$_REQUEST['funcion'],$curso);
				exit;
			}
//                        elseif(is_array($resultadoInscripcion)&&empty($resultadoInscripcion['FIN']))
//                        {
//				echo "<script>alert('No se puede borrar el curso, porque se está ejecutando la preinscripción automática ')</script>";
//				$this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,$_REQUEST['funcion'],$curso);
//				exit;
//                        }
			elseif(is_array($rsCarga)){
				echo "<script>alert('No se puede borrar el curso, porque tiene un docente asociado ')</script>";
				$this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,$_REQUEST['funcion'],$curso);
				exit;
			}  
			elseif($rsInscritos[0]['INSCRITOS']>0){
				echo "<script>alert('No se puede borrar el curso, porque tiene estudiantes inscritos ')</script>";
				$this->direccionar($configuracion,$grupo,'adminConsultaHorarioCurso','verHorarioGrupo',$proyecto,$plan,$max_capacidad,$cupos,$_REQUEST['periodo'],$espacio,$_REQUEST['funcion'],$curso);
				exit;			
			}
			else{
				$arrayParametros=array('proyecto'=>$proyecto,'plan'=>$plan,'espacio'=>$espacio,'grupo'=>$grupo,'anio'=>$anio,'periodo'=>$periodo,'curso'=>$curso);
				$cadena_sql=$this->sql->cadena_sql($configuracion, "eliminaCurso", $arrayParametros);
				$rsElimina=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                                $datosRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'61',
                                                          'descripcion'=>'Elimina grupo de curso',
                                                          'registro'=>$_REQUEST['periodo'].", EA=".$espacio.", GR=".$grupo.", ID=".$curso.", PRY=".$proyecto,
                                                          'afectado'=>$proyecto);
                                $this->procedimientos->registrarEvento($datosRegistro);
                                
				echo "<script>alert('El curso se eliminó satisfactoriamente.')</script>";
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				$ruta="pagina=adminConsultaHorarios";
				$ruta.="&opcion=consultarGrupos";
				$ruta.="&tipoConsulta=rapida";
				$ruta.="&proyecto=".$proyecto;
				$ruta.="&plan=".$plan;
				$ruta.="&grupo=".$grupo;
				$ruta.="&curso=".$curso;
				$ruta.="&periodo=".$_REQUEST['periodo'];
				$ruta.="&espacio=".$espacio;
				$ruta=$this->cripto->codificar_url($ruta,$configuracion);
				echo "<script>location.replace('".$indice.$ruta."')</script>";
			}
        }//fin function
        
    function direccionar ($configuracion,$grupo,$pagina='',$opcion='',$proyecto='',$plan='',$max_capacidad='',$cupos='',$periodo='',$espacio='',$funcion='',$curso='')
        {   
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=".$pagina;
            $ruta.="&opcion=".$opcion;
            $ruta.="&proyecto=".$proyecto;
            $ruta.="&plan=".$plan;
            $ruta.="&grupo=".$grupo;
            $ruta.="&max_capacidad=".$max_capacidad;
            $ruta.="&cupos=".$cupos;
            $ruta.="&periodo=".$periodo;
            $ruta.="&espacio=".$espacio;
            $ruta.="&funcion=".$funcion;
            $ruta.="&curso=".$curso;
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            echo "<script>location.replace('".$indice.$ruta."')</script>";
        }   
        
        
        
	function rescatarSalonesCompletos($anio,$periodo,$capacidad,$sede=""){
            ?>
<link href="<?echo $this->configuracion["site"] . $this->configuracion['estilo']?>/select2/select2.css" rel="stylesheet"/>
<script src="<?echo $this->configuracion["site"] . $this->configuracion['estilo']?>/select2/select2.js"></script>
<script>
$(document).ready(function() { $("#salon").select2(); });
</script>        
            <?
	   // $varSalon=array('sede'=>$resultadoexiste[0]['COD_SEDE'],'salon'=>$resultadoexiste[0]['SALON'],'dia'=>$_REQUEST['dia'],'hora'=>$_REQUEST['hora'],'anio'=>$_REQUEST['anio'],"periodo"=>$_REQUEST['periodo'],'cupos'=>$_REQUEST['capacidad']);
		if($sede<>""){
                    $variable=array('sede'=>$sede,
                                    'capacidad'=>$capacidad);
			$this->cadena_sql = $this->sql->cadena_sql($this->configuracion, "salones",$variable);
			$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda");
			?>
			  SAL&Oacute;N :
			  <select name='salon' id='salon' onchange='actualizarOcupacion($(this).val(),<?echo $anio?>,<?echo $periodo?>)'>
				<option value="">[Seleccione]</option>
				  <?
				foreach ($resultado as $key => $value){ 
				  echo "<option value=".$resultado[$key]['COD_SALON'].">".$resultado[$key]['NOM_EDIFICIO']." - ".$resultado[$key]['NOM_SALON']." - Cap: ".$resultado[$key]['CUPOS']."</option>";
				}
				  ?>    
			  </select>
			<?
		}else{
			?>
			SAL&Oacute;N :
				  <select name='salon' id='salon' onchange='actualizarOcupacion($(this).val(),<?echo $anio?>,<?echo $periodo?>)'>
				  <option value="">Seleccione primero una sede</option>
				  </select>
			<?
		
		}
  
	}
       
	/**
         * Funcion que rescata la sede 
         * @param type $anio
         * @param type $periodo
         */
        function rescatarSede($anio,$periodo,$capacidad){
		$varSede=array('sede'=>'-1');
		
		$cadena_sql_sede=$this->sql->cadena_sql($this->configuracion,"sede",$varSede);
		$resultado_sede=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_sede,"busqueda");
		
		/*para filtrar por disponibilidad y cap <select name='sede' id='sede' onchange="xajax_salones(document.getElementById('sede').value,'<?=$_REQUEST['hora']?>','<?=$_REQUEST['dia']?>','<?=$_REQUEST['capacidad']?>','<?=$_REQUEST['periodo']?>','<?echo $_REQUEST['anio']?>')">*/

		?>
		  SEDE:
		  
		  <select name='sede' id='sede' onchange="rescatarSalonesCompletos(<?echo $anio?>,<?echo $periodo?>,<?echo $capacidad?>,$(this).val())">
		  
		    <option value="0" >Seleccione la Sede..</option>
		    <?
		    foreach($resultado_sede as $key => $data){ 
			  echo "<option value=".$resultado_sede[$key]['ID_SEDE'].">".$resultado_sede[$key]['NOML_SEDE']."</option>";
		    }
		    ?>
		  </select>
		<?
	
	}
	
	/**
         * 
         * @param type $cod_salon
         * @param type $anio
         * @param type $periodo
         */
	function consultarOcupacion($cod_salon,$anio,$periodo){
		$cadena_sql=$this->sql->cadena_sql($this->configuracion,"salones_ocupados",array("cod_salon"=>$cod_salon,'anio'=>$anio,'periodo'=>$periodo));
		$resultado=$this->ejecutarSQL($this->configuracion,$this->accesoOracle,$cadena_sql,"busqueda");

                $i=0;
		$json=array();
		while(isset($resultado[$i][0])){
		  foreach($resultado[$i] as $clave=>$valor){
		  $json[$resultado[$i]["COD_HORA"]][$clave]=$valor;
		  }
		  $i++;
		}
		echo json_encode($json);
	}
	
	/**
         * Funcion que permite actualizar un horario de un curso
         * @param type $cod_salon
         * @param type $cod_hora
         * @param type $cod_curso
         * @param type $anio
         * @param type $periodo
         */
        function actualizarHorario($cod_salon,$cod_hora,$cod_curso,$anio,$periodo){
		//verifica q ese salon este disponible para esa hora
		$cadena_sql = $this->sql->cadena_sql($this->configuracion, "salon_ocupado", array("cod_salon"=>$cod_salon,"cod_hora"=>$cod_hora,'anio'=>$anio,'periodo'=>$periodo));
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

		$json=array();
		
		//si no esta asignado
		if(!is_array($resultado)){
		
			//verifica que el curso tenga un horario asignado para esa hora
			$cadena_sql = $this->sql->cadena_sql($this->configuracion, "curso_con_horario", array("curso"=>$cod_curso,"cod_hora"=>$cod_hora));
			$curso=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
	
					
			//si el curso no tiene asignada esa hora la registra
			if(!is_array($curso)){
				
				$inscritos=$this->consultarEstudiantesInscritos($cod_salon,$cod_hora,$cod_curso);
				if(($inscritos*1)>0){
					$json["mensaje"]=" No puede modificar el horario, este curso tiene {$inscritos[0][0]} estudiantes inscritos.";
				}else{
					$cadena_sql = $this->sql->cadena_sql($this->configuracion, "siguienteHorario", "");
					$resultadoHor = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                        
                                        //verfica la maximo numero de horas
					$cod_hora=explode("-",$cod_hora);
					$parametro=array('curso'=>$cod_curso, 'dia'=>$cod_hora[0],'hora'=>$cod_hora[1],'anio'=>$cod_curso[0],"periodo"=>$cod_curso[1],"salon"=>$cod_salon,"estado"=>'A','id_horario'=>$resultadoHor[0][0]);    
						   
					$cadena_sql = $this->sql->cadena_sql($this->configuracion, 'registrar_horario', $parametro);
					$resultadoHor = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
                                        $datosRegistro=array('usuario'=>$this->usuario,
                                                                  'evento'=>'59',
                                                                  'descripcion'=>'Crea Horario de curso',
                                                                  'registro'=>$anio."-".$periodo.", SAL=".$cod_salon.", D=".$cod_hora[0].", HR=".$cod_hora[1].", ID=".$parametro['id_horario'].", CUR=".$cod_curso,
                                                                  'afectado'=>$cod_curso);
                                        $this->procedimientos->registrarEvento($datosRegistro);
                                        
					
					$cadena_sql = $this->sql->cadena_sql($this->configuracion, "verHorarioTemp", $parametro);
					$resultadoHor = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
					
					$json["mensaje"]="";
					$json["data"]="<div class='borrar_horario' onclick='borrarHorario(\"{$cod_hora[0]}-{$cod_hora[1]}\",\"$anio\",\"$periodo\")'>[X]</div>";
					$json["data"].="<div class='contenido_horario' onclick='actualizarCeldaHora(\"{$cod_hora[0]}-{$cod_hora[1]}\",\"$anio\",\"$periodo\")'>";
					$json["data"].="	Sede: ".$resultadoHor[0]['NOM_SEDE']."<br>Edificio: ".$resultadoHor[0]['NOM_EDIFICIO']."<br>Salon: ".$resultadoHor[0]['SALON_NVO']."<BR> ".$resultadoHor[0]['NOM_SALON']."";
					$json["data"].="</div>";
					$json["cod_hora_nueva"]=$cod_hora[0]."-".$cod_hora[1];
				}

			}else{
				//si ya esta asignado actualiza el salon actual con el nuevo
				$this->cadena_sql = $this->sql->cadena_sql($this->configuracion, 'actualizar_horario',array("curso"=>$cod_curso,"cod_hora"=>$cod_hora,"salon"=>$cod_salon));
				$resultadoHor = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql, ""); 
								
				$cod_hora=explode("-",$cod_hora);
				$parametro=array('curso'=>$cod_curso, 'dia'=>$cod_hora[0],'hora'=>$cod_hora[1],'anio'=>$cod_curso[0],"periodo"=>$cod_curso[1],"salon"=>$cod_salon,"estado"=>'A');    
                                $datosRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'62',
                                                          'descripcion'=>'Actualiza Horario de curso',
                                                          'registro'=>$anio."-".$periodo.", SAL=".$cod_salon.", D=".$cod_hora[0].", HR=".$cod_hora[1].", CUR=".$cod_curso,
                                                          'afectado'=>$cod_curso);
                                $this->procedimientos->registrarEvento($datosRegistro);
						
				$this->cadena_sql = $this->sql->cadena_sql($this->configuracion, "verHorarioTemp", $parametro);
				$resultadoHor = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
				
				$json["mensaje"]="El salon se actualizo correctamente ";
				$json["data"]="<div class='borrar_horario' onclick='borrarHorario(\"{$cod_hora[0]}-{$cod_hora[1]}\",\"$anio\",\"$periodo\")'>[X]</div>";
				$json["data"].="<div class='contenido_horario' onclick='actualizarCeldaHora(\"{$cod_hora[0]}-{$cod_hora[1]}\",\"$anio\",\"$periodo\")'>";
				$json["data"].="	Sede: ".$resultadoHor[0]['NOM_SEDE']."<br>Edificio: ".$resultadoHor[0]['NOM_EDIFICIO']."<br>Salon: ".$resultadoHor[0]['SALON_NVO']."<BR> ".$resultadoHor[0]['NOM_SALON']."";
				$json["data"].="</div>";
				$json["cod_hora_nueva"]=$cod_hora[0]."-".$cod_hora[1];
			}
		}else{
			$json["mensaje"]="El salón ya está asignado en esta hora ";
			$json["cod_hora"]=$resultado[0]["COD_HORA"];
		
		}
		   
		echo json_encode($json);
	} 
	
	/**
         * Funcion que permite borrar un horario de un curso
         * @param type $cod_salon
         * @param type $cod_hora
         * @param type $cod_curso
         * @param type $anio
         * @param type $periodo
         */
        function borrarHorario($cod_salon,$cod_hora,$cod_curso,$anio,$periodo){

		//verifica que el curso tenga estudiantes inscritos
		$inscritos=$this->consultarEstudiantesInscritos($cod_salon,$cod_hora,$cod_curso);
                $horario=explode("-", $cod_hora);
                $variable=array('curso'=>$cod_curso,'anio'=>$anio,'periodo'=>$periodo,'dia'=>$horario[0],'hora'=>$horario[1]);
                $cadena_sql=$this->sql->cadena_sql($this->configuracion, "infoCarga", $variable);
                $rsCarga=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
		$json=array();
		$json["error"]="";
		
		//si no tiene estudiantes elimina el horario
		if(($inscritos*1)>0){
			$json["error"]=" No puede modificar el horario, este curso tiene {$inscritos[0][0]} estudiantes inscritos.";
		}elseif(is_array($rsCarga))
                {$json["error"]=" No puede modificar el horario porque tiene carga registrada.";}
                else{
			$cadena_sql=$this->sql->cadena_sql($this->configuracion, "borrar_horario", array("curso"=>$cod_curso,"cod_hora"=>$cod_hora));
			$curso=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
                        $datosRegistro=array('usuario'=>$this->usuario,
                                                  'evento'=>'63',
                                                  'descripcion'=>'Borra Horario de curso',
                                                  'registro'=>$anio."-".$periodo.", SAL=".$cod_salon.", D=".$cod_hora[0].", HR=".$cod_hora[2].", CUR=".$cod_curso,
                                                  'afectado'=>$cod_curso);
                        $this->procedimientos->registrarEvento($datosRegistro);                        
			$json["cod_hora"]=$cod_hora;
			$json["data"]="<div class='contenido_horario' onclick='actualizarCeldaHora(\"{$cod_hora}\",\"$anio\",\"$periodo\")'>";
			$json["data"].="	<br/>";
			$json["data"].="</div>";
		}
		echo json_encode($json);
	} 

	/**
         * Funcion que permite consultar el numero de estudiantes inscritos en un curso
         * @param type $cod_salon
         * @param type $cod_hora
         * @param type $cod_curso
         * @return type
         */
        function consultarEstudiantesInscritos($cod_salon,$cod_hora,$cod_curso){
		$cod_hora=explode("-",$cod_hora);
		$parametro=array('curso'=>$cod_curso, 'dia'=>$cod_hora[0],'hora'=>$cod_hora[1],"salon"=>$cod_salon,"estado"=>'A');
		$cadena_sql = $this->sql->cadena_sql($this->configuracion, "infoInscritos",$parametro);
		$resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		return $resultado[0][0];
	}
        
        /**
         * Funcion que busca el siguiente id del curso
         * @return type
         */
        function consultarIDCurso()
        {
            $cadena_sql = $this->sql->cadena_sql($this->configuracion, 'siguienteCurso',"");
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;
        }

        /**
         * Funcion que permite consultar los datos del curso
         * @param type $variables
         * @return type
         */
        function consultarCurso($variables)
        {
            $cadena_sql = $this->sql->cadena_sql($this->configuracion, "infoCurso", $variables); 
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            return $resultado;
        }
        
        /**
         * Funcion que permite registrar un curso nuevo
         * @param type $variables
         * @return type
         */
        function registrarCurso($variables)
        {
            $cadena_sql = $this->sql->cadena_sql($this->configuracion, "insertarCurso", $variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
            return $resultado;
        }
}
