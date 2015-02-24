<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

class funciones_copiarHorarios extends funcionGeneral
{
    private $configuracion;
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
		//$this->tema=$tema;
		$this->sql=$sql;
                $this->validacion=new validarInscripcion();
		
                //Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
                $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
                //Conexion Oracle
                if($this->nivel==4){
                    $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }
                
		//Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->formulario="admin_copiarHorarios";
		$this->bloque="horarios/admin_copiarHorarios";
                $this->configuracion=$configuracion;
		$this->verificar="control_vacio(".$this->formulario.",'proyecto')";
//		$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}

	//Contiene el formulario para seleccionar el periodo académico del cual se va a copiar el horario y el Proyecto Curriular
	//al cual se le va a realizar la copia.
	function formCopiarHorarios($configuracion)
	{
            $tab=0;
	      if($this->usuario)
		{ $usuario=$this->usuario;}
		else
		{ $usuario=$this->identificacion;}
		
		if($usuario=="")
		{	echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
                
                $valor['usuario']=$usuario;
		$_REQUEST['proyecto']=isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
		$valor['proyecto']=$_REQUEST['proyecto'];
                
                if($this->nivel==110 ){
                            $verificacion=$this->validacion->verificarProyectoAsistente($valor['proyecto'],$this->usuario,$this->nivel);
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
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                    <table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr>
                            <td style='text-align:center' align="center" colspan="4" height="50px">
                                <h4 class="bloquelateralcuerpo" >COPIAR HORARIOS</h4>
                                <hr>
                            </td>
                        </tr>
                         <th align=center class="bloquelateralcuerpo" colspan="4" height="30px">Seleccione los periodos acad&eacute;micos.</th>
                        <tr>
                            <td class="sigma derecha">
                                 Periodo acad&eacute;mico anterior:
                            </td>
                            <td class="sigma">
                                <?  $varPer=array('estado'=>"'P','A'");
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaPeriodos",$varPer);
                                    $resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                                    $html=new html();
                                    $configuracion["ajax_function"]="xajax_nombreCurso";
                                    $configuracion["ajax_control"]="periodoAnterior";
                                    foreach ($resultadoPer as $key => $value) 
                                       {    $registro[$key][0]=$resultadoPer[$key]['PERIODO'];
                                            $registro[$key][1]=$resultadoPer[$key]['PERIODO'];
                                       }
                                    $mi_cuadro=$html->cuadro_lista($registro,'periodoAnterior',$configuracion,0,2,FALSE,'periodoAnterior',100);
                                    echo $mi_cuadro;
                                ?>
                            </td>

                            <td class="sigma derecha">
                               Periodo acad&eacute;mico nuevo:
                            </td>
                            <td class="sigma">
                                <?  $varPerNvo=array('estado'=>"'A','X'");
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaPeriodos",$varPerNvo);
                                    $resultadoPerNuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                    foreach ($resultadoPerNuevo as $key1 => $value) 
                                       {    $registro1[$key1][0]=$resultadoPerNuevo[$key1]['PERIODO'];
                                            $registro1[$key1][1]=UTF8_DECODE($resultadoPerNuevo[$key1]['PERIODO']);
                                       }
                                    $mi_cuadro=$html->cuadro_lista($registro1,'periodoNuevo',$configuracion,0,0,FALSE,'periodoNuevo',100);
                                    echo $mi_cuadro;
                                ?>
                           </td>
                        </tr>
                        <tr>
                            <td class="sigma centrar" colspan="4" height="40px">
                                <input type="hidden" name="proyecto" value="<? echo $valor['proyecto']; ?>">
                                <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                <input type='hidden' name='opcion' value='seleccionar'>
                                <input value="Continuar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
                            </td>
                        </tr>
                    </table>
                    </form>
 		<?
               
        }
	    
	//Confirma el periodo académico, el proyecto curricualr seleccionados, y el formulario para realizar la copia de los horarios.
	function duplicarHorario($configuracion)
	{
            ?>
                <script>
                function desactiva_enlace(enlace)
                {
                enlace.disabled='disabled';
                }
                </script>            <?
		unset($valor);
		$valor['proyecto']=$_REQUEST['proyecto'];
		$valor['periodoAnterior']=$_REQUEST['periodoAnterior'];
		$valor['periodoNuevo']=$_REQUEST['periodoNuevo'];
                $tab=0;
                //verifica fechas de modificacion de horarios
                $qryfecha=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
                $rsFecha=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryfecha, "busqueda");
                $periodoAnterior=explode('-',$_REQUEST['periodoAnterior']);
                $periodoNuevo=explode('-',$_REQUEST['periodoNuevo']);
                //valida la fecha del evento copiar horarios
                $variableAnt=array('proyecto'=>$_REQUEST['proyecto'],'anio'=>$periodoAnterior[0],'periodo'=> $periodoAnterior[1],'fecha'=> $rsFecha[0]['FECHA'],'evento'=> '12');
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valida_fecha",$variableAnt);
                $rsValidaAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                //valida la fecha del evento crear horarios
                $variableNvo=array('proyecto'=>$_REQUEST['proyecto'],'anio'=>$periodoNuevo[0],'periodo'=> $periodoNuevo[1],'fecha'=> $rsFecha[0]['FECHA'],'evento'=> '87');
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valida_fecha",$variableNvo);
                $rsValidaNvo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                
                if(is_array($rsValidaAnt) || is_array($rsValidaNvo))
                     {$estado='abierto';}
                else {$estado='cerrado';}
                
              if($valor['periodoAnterior']==$valor['periodoNuevo']) 
                   {include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
                    $cadena1="No es posible copiar horarios del período académico ".$valor['periodoAnterior']." al período académico ".$valor['periodoNuevo']."."; 
                    $cadena=htmlentities($cadena1, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($configuracion,$cadena);
                    exit;
                   }   
              if($estado=='cerrado') 
                   {include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
                    $cadena1="No es posible copiar horarios para el periodo académico ".$_REQUEST['periodoNuevo'].", Las fechas de acuerdo al CALENDARIO ACADÉMICO estan cerradas."; 
                    $cadena=htmlentities($cadena1, ENT_COMPAT, "UTF-8");
                    alerta::sin_registro($configuracion,$cadena);
                   }   
              else{
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carrera",$valor);
                    
                    $resultadoCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    ?>
                    <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                            <table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                                    <tr>
                                            <td style='text-align:center' align="center" colspan="2">
                                            <h4 class="bloquelateralcuerpo">COPIAR H0RARIOS</h4>
                                            <hr>
                                    </td>
                            </tr>
                            </table>
                            <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
                                    <tr>
                                            <td>	
                                                    <table class="formulario" align="center">
                                                            <tr class="sigma">
                                                                    <td width="20%" align="center">
                                                                            <br><font size="2">Se&ntilde;or Coordinador, usted ha solicitado copiar el horario<br> del periodo acad&eacute;mico <b><? echo $valor['periodoAnterior'];?></b> al peridodo acad&eacute;mico <b><? echo $valor['periodoNuevo'];?><br></b> del<b> Proyecto Curricular <? echo $resultadoCarrera[0][1];?></b> </b>.</font><br>
                                                                    </td>
                                                            </tr>
                                                            <tr  class="bloquecentralencabezado">
                                                                    <td colspan="3" align="center">
                                                                            <p><span class="texto_negrita"> Haga click en "Copiar Horarios" para copiar los cursos y los horarios, en caso contrario haga click en "Cancelar".</span></p>
                                                                    </td>
                                                            </tr>
                                                            <tr align='center'>
                                                                    <td colspan="3">
                                                                            <table class="tablaBase">
                                                                                    <tr>

                                                                                            <td align="center">
                                                                                                    <input type='hidden' name='proyecto' value='<? echo $valor['proyecto'] ?>'>
                                                                                                    <input type='hidden' name='periodoNuevo' value='<? echo $valor['periodoNuevo'] ?>'>
                                                                                                    <input type='hidden' name='periodoAnterior' value='<? echo $valor['periodoAnterior'] ?>'>
                                                                                                    <input type='hidden' name='action' value='<? echo $this->bloque ?>'>
                                                                                                    <input type='hidden' name='opcion' value='duplicar'>
                                                                                                    <input value="Copiar Horarios" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="desactiva_enlace(this);submit()"/><br>
                                                                                            </td>
                                                                                            <td align="center">
                                                                                                    <input type='hidden' name='proyecto' value='<? echo $valor['proyecto'] ?>'>
                                                                                                    <input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
                                                                                            </td>
                                                                                    </tr>
                                                                            </table>
                                                                    </td>
                                                            </tr>
                                                    </table>
                                            </td>
                                    </tr>
                            </table>
                    </form>	
                    <?   
            }     
	}
	
	//Verifica año y periodo académico, si no hay registros, inserta año y perido.
	function seleccionarPeriodo($configuracion)
	{
		unset($valor);
		$valor['proyecto']=$_REQUEST['proyecto'];
		$valor['periodoAnterior']=$_REQUEST['periodoAnterior'];
		$valor['periodoNuevo']=$_REQUEST['periodoNuevo'];
		$periodoAnterior=explode('-',$valor['periodoAnterior']);
		$valor['anioAnterior']=$periodoAnterior[0];
		$valor['perAnterior']=$periodoAnterior[1];
		$periodoNuevo=explode('-',$valor['periodoNuevo']);
		$valor['anioNuevo']=$periodoNuevo[0];
		$valor['perNuevo']=$periodoNuevo[1];
                
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carrera",$valor);
                $resultadoCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaRegistro",$valor);
                $resultadoVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($resultadoVerifica))
		{       $this->redireccionarInscripcion($configuracion,"duplicarHorario",$valor);
			/*$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"borrarPeriodo",$valor);
			$resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="Para la carrera: ".$resultadoCarrera[0][1]. ", ya existe un registro con el peridodo acad&eacute;mico ".$valor['periodoNuevo'].".<br>";
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);*/
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarAnioPer",$valor);
                        $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                       
			if($resultado==TRUE)
			{
				$this->redireccionarInscripcion($configuracion,"duplicarHorario",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El registro no se pudo guardar con el a&ntilde;o y el periodo acad&eacute;mico seleccionados, por favor intentelo nuevamente.<br>";
                                $cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena);
			}
		}
	}

        //Ejecuta el copiado de los horarios.
        function ejecutarDuplicarHorario($configuracion)
        {
            $auxCurso=0;
            $auxHorario=0;
            $cursoNuevo=0;
            $horarioNuevo=0;
            $cursoExiste='';
            $horarioExiste='';
            if(is_numeric($_REQUEST['proyecto']) && is_numeric(substr($_REQUEST['periodoAnterior'],0,4)) && is_numeric(substr($_REQUEST['periodoAnterior'],-1)) ){
                    if($this->nivel==110 ){
                            $verificacion=$this->validacion->verificarProyectoAsistente($_REQUEST['proyecto'],$this->usuario,$this->nivel);
                            if($verificacion!='ok')
                                {
                                    echo "<script>alert('".$verificacion."');</script>";
                                    $this->redireccionarInscripcion($configuracion, 'duplicarHorario', $_REQUEST);
                                    exit;
                                }
                    }
                    //busca los cursos del periodo anterior
                    $varCurso=array('proyecto'=>$_REQUEST['proyecto'],
                                    'anio'=>substr($_REQUEST['periodoAnterior'],0,4),
                                    'periodo'=>substr($_REQUEST['periodoAnterior'],-1));
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"infoCurso",$varCurso);
                    $resultadoCurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                    if (is_array($resultadoCurso))
                    {
                        foreach ($resultadoCurso as $key => $value) 
                        {
                            $varNuevoCurso=array('anio'=>substr($_REQUEST['periodoNuevo'],0,4),
                                                  'periodo'=>substr($_REQUEST['periodoNuevo'],-1),
                                                  'espacio'=>$resultadoCurso[$key]['ESPACIO'],
                                                  'grupo'=>$resultadoCurso[$key]['GRUPO'],
                                                  'proyecto'=>$resultadoCurso[$key]['PROYECTO'],
                                                  'cupos'=>$resultadoCurso[$key]['CUPOS'],
                                                  'estado'=>$resultadoCurso[$key]['ESTADO'],
                                                  'facultad'=>$resultadoCurso[$key]['FACULTAD'],
                                                  'max_capacidad'=>$resultadoCurso[$key]['MAX_CAPACIDAD'],
                                                  'hor_alternativo'=>$resultadoCurso[$key]['HOR_ALTERNATIVO'],
                                                  'tipo'=>$resultadoCurso[$key]['TIPO']);
                            $idCursoAnterior=$resultadoCurso[$key]['CUR_ID'];
                             //busca si lo cursos existen en el nuevo periodo
                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"infoCurso",$varNuevoCurso);
                            $resCursoExis=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                            //si el curso no existe
                            if(!is_array($resCursoExis))
                            {//inserta el curso
                                //solicita el numero id para el curso
                                $cadena_sql = $this->sql->cadena_sql($configuracion,$this->accesoOracle,'siguienteCurso',"");
                                $resultado = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                $varNuevoCurso['cur_id']=$resultado[0][0];
                                //inserta el curso para el nuevo semestre
                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCurso",$varNuevoCurso);
                                $resCursoNvo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

                                if($resCursoNvo=='true')
                                { //busca el horario del curso del periodo anterior
                                    $cursoNuevo++;
                                    $varHorario=array('cur_id'=>$idCursoAnterior);
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarHorario",$varHorario);
                                    $resHorarioAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                    if(is_array($resHorarioAnt))
                                    {
                                        foreach($resHorarioAnt as $keyH => $valueH)
                                        {
                                            //busca que no haya un registro en la misma hora, dia, salon, anio y periodo
                                            $varHorarioNuevo=array('dia'=>$resHorarioAnt[$keyH]['DIA'],'hora'=>$resHorarioAnt[$keyH]['HORA'],'salon'=>$resHorarioAnt[$keyH]['COD_SALON'],'ano'=>$varNuevoCurso['anio'],'periodo'=>$varNuevoCurso['periodo']);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarHorario",$varHorarioNuevo);
                                            $resHorarioNuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                            if(is_array($resHorarioNuevo))
                                            {
                                                $horarioExiste[$auxHorario]['espacio']=$resultadoCurso[$key]['ESPACIO'];
                                                $horarioExiste[$auxHorario]['grupo']=$resultadoCurso[$key]['GRUPO'];
                                                $horarioExiste[$auxHorario]['salon']=$resHorarioAnt[$keyH]['COD_SALON'];
                                                $horarioExiste[$auxHorario]['dia']=$resHorarioAnt[$keyH]['DIA'];
                                                $horarioExiste[$auxHorario]['hora']=$resHorarioAnt[$keyH]['HORA'];
                                                $auxHorario++;

                                            }else{
                                                //registra el horario del período anterior en el nuevo si el salon existe y esta habilitado parar asignar clase
                                                //solicita el numero id para el horario
                                                $cadena_sql = $this->sql->cadena_sql($configuracion,$this->accesoOracle,'siguienteHorario',"");
                                                $resultado = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                $varNuevoHorario['hor_id']=$resultado[0][0];

                                                $varHorario=array('hor_id'=>$varNuevoHorario['hor_id'],'cur_id'=>$varNuevoCurso['cur_id'],'dia'=>$resHorarioAnt[$keyH]['DIA'],'hora'=>$resHorarioAnt[$keyH]['HORA'],'alternativo'=>$resHorarioAnt[$keyH]['ALTERNATIVO'],'estado'=>'A','salon'=>$resHorarioAnt[$keyH]['COD_SALON']);
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"registrar_horario",$varHorario);
                                                $resHorarioReg=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                                                $horarioNuevo++;
                                            }
                                        }
                                    }
                                }
                            }//si el curso ya ha sido copiado o si ya existe
                            else{//registra los cursos que no se pudieron copiar
                                    $cursoExiste[$auxCurso]['grupo']=$resultadoCurso[$key]['GRUPO'];
                                    $cursoExiste[$auxCurso]['proyecto']=$resultadoCurso[$key]['PROYECTO'];
                                    $cursoExiste[$auxCurso]['espacio']=$resultadoCurso[$key]['ESPACIO'];
                                    $auxCurso++;
                                    //busca si el horario del curso existe en el nuevo periodo
                                    $varHorarioExiste=array('cur_id'=>$resCursoExis[0]['CUR_ID'],'anio'=>substr($_REQUEST['periodoNuevo'],0,4),'periodo'=>substr($_REQUEST['periodoNuevo'],-1));
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarHorario",$varHorarioExiste);
                                    $resHorarioExiste=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
                                    if(is_array($resHorarioExiste))
                                    {
                                        foreach ($resHorarioExiste as $keyHor=>$valorHor)
                                        {
                                        $horarioExiste[$auxHorario]['espacio']=$resultadoCurso[$key]['ESPACIO'];
                                        $horarioExiste[$auxHorario]['grupo']=$resultadoCurso[$key]['GRUPO'];
                                        $horarioExiste[$auxHorario]['salon']=$resHorarioExiste[$keyHor]['COD_SALON'];
                                        $horarioExiste[$auxHorario]['dia']=$resHorarioExiste[$keyHor]['DIA'];
                                        $horarioExiste[$auxHorario]['hora']=$resHorarioExiste[$keyHor]['HORA'];
                                        $auxHorario++;
                                        }
                                    }//si no existe horario del curso en el nuevo periodo
                                    else{



        //busca el horario del curso del periodo anterior
                                    $varHorario=array('cur_id'=>$idCursoAnterior);
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarHorario",$varHorario);
                                    $resHorarioAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                    if(is_array($resHorarioAnt))
                                    {
                                        foreach($resHorarioAnt as $keyHN => $valueH)
                                        {
                                            //busca que no haya un registro en la misma hora, dia, salon, anio y periodo
                                            $varHorarioNuevo=array('dia'=>$resHorarioAnt[$keyHN]['DIA'],'hora'=>$resHorarioAnt[$keyHN]['HORA'],'salon'=>$resHorarioAnt[$keyHN]['COD_SALON'],'ano'=>$varNuevoCurso['anio'],'periodo'=>$varNuevoCurso['periodo']);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarHorario",$varHorarioNuevo);
                                            $resHorarioNuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                            if(is_array($resHorarioNuevo))
                                            {
                                                $horarioExiste[$auxHorario]['espacio']=$resultadoCurso[$key]['ESPACIO'];
                                                $horarioExiste[$auxHorario]['grupo']=$resultadoCurso[$key]['GRUPO'];
                                                $horarioExiste[$auxHorario]['salon']=$resHorarioAnt[$keyHN]['COD_SALON'];
                                                $horarioExiste[$auxHorario]['dia']=$resHorarioAnt[$keyHN]['DIA'];
                                                $horarioExiste[$auxHorario]['hora']=$resHorarioAnt[$keyHN]['HORA'];
                                                $auxHorario++;

                                            }else{
                                                //registra el horario del período anterior en el nuevo si el salon existe y esta habilitado parar asignar clase
                                                //solicita el numero id para el horario
                                                $cadena_sql = $this->sql->cadena_sql($configuracion,$this->accesoOracle,'siguienteHorario',"");
                                                $resultado = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                $varNuevoHorario['hor_id']=$resultado[0][0];

                                                $varHorario=array('hor_id'=>$varNuevoHorario['hor_id'],'cur_id'=>$resCursoExis[0]['CUR_ID'],'dia'=>$resHorarioAnt[$keyHN]['DIA'],'hora'=>$resHorarioAnt[$keyHN]['HORA'],'alternativo'=>$resHorarioAnt[$keyHN]['ALTERNATIVO'],'estado'=>'A','salon'=>$resHorarioAnt[$keyHN]['COD_SALON']);
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"registrar_horario",$varHorario);
                                                $resHorarioReg=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                                                $horarioNuevo++;
                                            }
                                        }
                                    }                                



                                    }

                                }
                        }
                        $varRedir=array('estado'=>'correcto','proyecto'=>$_REQUEST['proyecto'],'periodo'=>$_REQUEST['periodoAnterior'],'periodoNuevo'=>$_REQUEST['periodoNuevo']);   
                        $this->redireccionarInscripcion($configuracion,"reportes",$varRedir,$cursoExiste,$horarioExiste,$cursoNuevo,$horarioNuevo);   
                    }else
                        {
                            $varRedir=array('estado'=>'SinCurso','proyecto'=>$_REQUEST['proyecto'],'periodo'=>$_REQUEST['periodoAnterior']);
                            $this->redireccionarInscripcion($configuracion,"reportes",$varRedir);
                        }
            }else{
                    $varRedir=array('estado'=>'valoresNoValidos','proyecto'=>$_REQUEST['proyecto'],'periodo'=>$_REQUEST['periodoAnterior']);
                    $this->redireccionarInscripcion($configuracion,"reportes",$varRedir);

                    exit;
            }
        }

                
	//Muestra el resumen de los horarios y cusrsos copiados.
	function verReportes($configuracion)
	{     
              	unset($valor);
		$valor['proyecto']=$_REQUEST['proyecto'];
		$valor['periodo']=$_REQUEST['periodo'];
				/*
		echo $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuenta=count($resultado);*/
             switch ($_REQUEST['estado']) 
                {
                    case 'SinCurso':
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="No existen horarios ni cursos para copiar del periodo académico ".$_REQUEST['periodo']."";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
                    break;
                    case 'valoresNoValidos':
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="VALORES NO VALIDOS PARA COPIAR HORARIOS.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
                    break;
                    case 'correcto':
                         ?>
                            <table style="width:100%" class="formulario contenidotabla centrar">
                                <tr>
                                    <th ><center><?ECHO "SE REALIZÓ LA COPIA DE HORARIOS DEL PERIODO ".$_REQUEST['periodo']." AL  ".$_REQUEST['periodoNuevo'];?></center></th>
                                </tr>		
                                <tr>
                                    <th ><center><?ECHO "Se copiaron ".$_REQUEST['totalCursos']." cursos y ".$_REQUEST['totalHorarios']." horarios.";?></center></th>
                                </tr>		
                             </table>
                         <?
                         include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php"); 
                        if($_REQUEST['cursoExiste']>0)
                            { $cadena1="No fue posible copiar ".$_REQUEST['cursoExiste']." grupo(s), pues ya existen para el período académico ".$_REQUEST['periodoNuevo']."."; 
                             $cadena=htmlentities($cadena1, ENT_COMPAT, "UTF-8");
			     alerta::sin_registro($configuracion,$cadena);
                            }
                             
                        if($_REQUEST['horarioExiste']>0)
                            { $cadena1="No fue posible copiar ".$_REQUEST['horarioExiste']." horario(s), pues ya se encuentran registrados para el período académico ".$_REQUEST['periodoNuevo']."."; 
                             $cadena=htmlentities($cadena1, ENT_COMPAT, "UTF-8");
			     alerta::sin_registro($configuracion,$cadena);}	     
                     break;
                  default:
                     break;
               }

	} 
 /*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
	
	//Rescata el usuario de la variable de sesion.
	function verificarUsuario()
	{
		//Verificar existencia del usuario 	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$this->identificacion);
		@$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($unUsuario))
		{
			return $unUsuario;			
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosUsuario",$this->usuario);
			@$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
			if(is_array($unUsuario))
			{
				return $unUsuario;
			}
			else
			{
				return false;
			}
		
		}
		
	}	
	
	//Valida que la fechas estén habilitadas para el registro de activides del plan docente.
	function validaCalendario($variable,$configuracion)
	{
		//Valida las fechas del calendario
		
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		$valor[0]=$usuario;
								
		$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$valor[9] =$rows[0][0];
		$valor[10]=$_REQUEST['nivel'];
						
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
		@$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
		
			if(!is_array($calendario))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
				$total=count($resultado);
					
				setlocale(LC_MONETARY, 'en_US');
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				$cripto=new encriptar();
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr  class="bloquecentralcuerpo">
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>Las fechas para digitar los PLANES DE TRABAJO DOCENTES para el periodo acad&eacute;mico '.$ano.'-'.$per.', est&aacute;n cerradas, solo podr&aacute; ';
										 echo "<a href='";
										$variable="pagina=registro_plan_trabajo";
										$variable.="&opcion=reportes";
										$variable.="&nivel=".$valor[10];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para imprimir el reporte'><b>IMPRIMIR</b></a>";
										echo ' el reporte.</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									NOTA: Para imprimir el reporte de notas, haga Click en '; 
									echo "<a href='";
										$variable="pagina=registro_plan_trabajo";
										$variable.="&opcion=reportes";
										$variable.="&nivel=".$valor[10];
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para imprimir el reporte'><b>IMPRIMIR</b></a>";
									echo '</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				exit;
			}	
			else
			{
				return $calendario;
			}
	}
	
				
	//Redirecciona la página dependiendo de la acción que se esté realizando en el módulo.
	function redireccionarInscripcion($configuracion, $opcion, $valor,$cursoExiste='',$horarioExiste='',$totalCursos='',$totalHorarios='')
	{       include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                //si todos los cursos se puedieron copiar
                if ($cursoExiste!='' && count($cursoExiste)>0)
                {$cursoExiste=count ($cursoExiste);}
                else{$cursoExiste=0;}
                //si todos los horarios se puedieron copiar
                if ($horarioExiste!='' && count($horarioExiste)>0)
                {$horarioExiste=count($horarioExiste);}
                else{$horarioExiste=0;}
                switch($opcion)
		{
			case "formgrado":
				$variable="pagina=adminCopiarHorarios";
				$variable.="&proyecto=".$valor['proyecto'];
				break;
			case "duplicarHorario":
				$variable="pagina=adminCopiarHorarios";
				$variable.="&opcion=duplicarHorario";
				$variable.="&proyecto=".$valor['proyecto'];
				$variable.="&periodoAnterior=".$valor['periodoAnterior'];
				$variable.="&periodoNuevo=".$valor['periodoNuevo'];
				break;
			case "consultarCursos":
				$variable="pagina=adminConsultaHorarios";
				$variable.="&opcion=>consultarGrupos";
				$variable.="&tipoConsulta=>rapida";
				$variable.="&accion=>consultar";
				$variable.="&proyecto=".$valor['proyecto'];
                                $variable.="&periodo=".$valor['periodo'];
				break;
			case "reportes":
				$variable="pagina=adminCopiarHorarios";
				$variable.="&opcion=verReporte";
                                $variable.="&estado=".$valor['estado'];
                                $variable.="&proyecto=".$valor['proyecto'];
				$variable.="&periodo=".$valor['periodo'];
				$variable.="&periodoNuevo=".$valor['periodoNuevo'];
                                $variable.="&cursoExiste=".$cursoExiste;
                                $variable.="&horarioExiste=".$horarioExiste;
                                $variable.="&totalCursos=".$totalCursos;
                                $variable.="&totalHorarios=".$totalHorarios;
                                
				break;
		}
                $variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

