<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/reglasConsejerias.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
//include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");

//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/phpmailer.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_admin_consejeriasConsultaEstudiante extends funcionGeneral {

  public $configuracion;
  public $numero = 0; //cuenta las filas de la tabla de estudiantes asociados
  public $usuario;
  public $nivel;
  public $datosEstudiante;
  public $periodo;
  //public $datosDocente;
  //private $notasDefinitivas;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($this->configuracion["raiz_documento"].$this->configuracion["estilo"]."/".$this->estilo."/tema.php");

    $this->configuracion = $configuracion;
    $this->cripto = new encriptar();
    $this->sql = new sql_admin_consejeriasConsultaEstudiante($configuracion);
    $this->log_us = new log();
    $this->reglasConsejerias = new reglasConsejerias();
    $this->validacion=new validarInscripcion();
    $this->formulario="admin_consejeriasConsultaEstudiante";//nombre del bloque que procesa el formulario

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($this->configuracion, "oraclesga");

    //Conexion General
    $this->acceso_db = $this->conectarDB($this->configuracion, "");
    $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");

    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($this->configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($this->configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    //Datos de sesion
    $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");
    
    $this->verificar="control_vacio(".$this->formulario.",'codigo','')";   
  }
    
    function consultarEstudiante() {
    //$codDocente = $this->usuario;//numero documento docente
    //$nivelDocente = $this->nivel;
        
        
    if($this->nivel==30){
        $this->mostrarEnlaceRegresar();        
        }
    if($this->nivel==51||$this->nivel==52){
        if (isset($this->usuario)&&$this->usuario==(isset($_REQUEST['usuario'])?$_REQUEST['usuario']:''))
            {
                $_REQUEST['datoBusqueda']=$_REQUEST['usuario'];
                $_REQUEST['tipoBusqueda']='codigo';
            }
        }
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
        $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'');
        if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
            if($tipoBusqueda=='codigo'){
                $codEstudiante = $datoBusqueda;
            }  
            if($tipoBusqueda=='identificacion'){
                $codEstudiante = $this->consultarCodigoEstudiantePorIdentificacion($datoBusqueda);
                if(is_array($codEstudiante )){
                        $this->mostrarListadoProyectos($codEstudiante);
                    }
            }
        }else{
            if($tipoBusqueda=='codigo'){
                echo "C&oacute;digo de estudiante no valido";
            }
            if($tipoBusqueda=='identificacion'){
                echo "Identificac&oacute;n de estudiante no valida";
            }
        }
        if($tipoBusqueda=='nombre'){
            $codEstudiante = $this->consultarCodigoEstudiantePorNombre($datoBusqueda);
            if(is_array($codEstudiante )){
                    $this->mostrarListadoProyectos($codEstudiante);
                }
        }
    if((isset($codEstudiante)?$codEstudiante:'') && !is_array($codEstudiante)){
        $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
    //$this->datosDocente=  $this->buscarDatosDocente($codDocente);
    }
    if (is_array($this->datosEstudiante)) {
        if($this->nivel==110 || $this->nivel==114){
            $verificacion=$this->validacion->validarProyectoAsistente($this->datosEstudiante['CODIGO'],$this->usuario,$this->nivel);
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
        if($this->nivel==111||$this->nivel==112){
            $verificacion=$this->validacion->validarFacultadAsistente($this->datosEstudiante['CODIGO'],$this->usuario);        
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
          $this->mostrarDatosEstudiante();
          
          $reglamentoEstudiante = $this->consultarReglamentoEstudiante($this->datosEstudiante['CODIGO']); 
          
          $this->espaciosCursados=  $this->buscarNotasDefinitivas();
          $notaAprobatoria=$this->consultarNotaAprobatoria();
          $this->espaciosAprobados=  $this->buscarEspaciosAprobados($notaAprobatoria,$this->espaciosCursados);
          $this->espaciosReprobados=  $this->buscarEspaciosReprobados($notaAprobatoria,$this->espaciosCursados);
          $espaciosCursadosSinAprobar=$this->buscarEspaciosCursadosSinAprobar();
          
           //mostrar deudas
          $deudasEstudiante = $this->consultarDeudasEstudiante($this->datosEstudiante['CODIGO']); 
          
          if(is_array($deudasEstudiante)){
                $this->mostrarDeudasEstudiante($deudasEstudiante,$this->datosEstudiante['ESTADO']);
          }
          
          //Si es egresado mostrar Datos de grado
          if($this->datosEstudiante['ESTADO']=='E'){
                $gradoEstudiante = $this->consultarDatosGradoEstudiante($this->datosEstudiante['CODIGO'],$this->datosEstudiante['CODIGO_CRA']); 
                $this->mostrarDatosGradoEstudiante($gradoEstudiante);

          }
          //mostrar valores de modelos bienestar
          $valores_rendimiento_academico = $this->buscarValoresRendimientoAcademico($reglamentoEstudiante);
          $valores_riesgo_academico = $this->buscarValoresRiesgoAcademico($reglamentoEstudiante);
          $this->mostrarResultadosModelos($valores_rendimiento_academico,$valores_riesgo_academico);
          
          //mostrar reglamento
          if($this->datosEstudiante['NIVEL']=='PREGRADO'){
                $this->mostrarReglamentoEstudiante($reglamentoEstudiante,$this->datosEstudiante['ESTADO']);
          }
          //mostrar espacios perdidos
          
          if($this->nivel!=121){
          
            if(is_array($espaciosCursadosSinAprobar)){
              $datosEspaciosReprobados=$this->buscarDatosEspaciosReprobados($espaciosCursadosSinAprobar,$this->espaciosCursados);
              $this->mostrarEnacabezadoEspaciosPerdidos();
              $this->mostrarEspaciosReprobados($datosEspaciosReprobados);            
            }
            else{
                $this->mostrarEnacabezadoEspaciosPerdidos();
                  ?>
                      <table class='sigma centrar' width="100%">
                      <tr class='centrar'>
                          <td class='sigma centrar'>
                          <b>ACTUALMENTE NO TIENE ESPACIOS ACADEMICOS REPROBADOS</b>
                          </td>
                      </tr>
                      </table>
                  <?
            }
            $this->mostrarHorarioEstudiante();
            $this->mostrarNotasParciales();                  
          }
          $this->mostrarEnacabezadoNotasDefinitivas();
          $this->mostrarNotasDefinitivas($this->espaciosCursados);
          // Agregada 08/07/2015 para presentar tabla de porcentajes de creditos.
        if (trim($this->datosEstudiante['MODALIDAD']) == 'S' && trim($this->datosEstudiante['NIVEL'])=='PREGRADO')
          {
             $this->mostrarTablaPorcentajes();
          }
          
    } else {
?>
      <table class="contenidotabla centrar">
        <tr>
          <td class="cuadro_brownOscuro centrar">
            NO SE ENCONTRARON LOS DATOS DEL ESTUDIANTE!
          </td>
        </tr>
      </table>
<?
    }
  }

    /**
     * 
     */
    function mostrarEnlaceRegresar() {
      ?>
        <div>
    <?
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_consejeriasDocente";
        $variable.="&opcion=verEstudiantes";
        $variable.="&filtro=".(isset($_REQUEST['filtro'])?$_REQUEST['filtro']:'');
        $variable.="&codProyecto=".(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
    ?>
        <a href="<? echo $pagina . $variable ?>">
            <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/atras.png" width="20" height="15" border="0" style="vertical-align:middle"><font size="2">Regresar</font>
        </a>
        </div>      
      
      <?
  }
  
    /**
     * 
     */
    function mostrarDatosEstudiante() {

    $this->buscarPeriodoActivo();
      ?>

      <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="1px" >
        <tbody>
          <tr>
            <td>
              <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                <caption class="sigma centrar"><? echo "DATOS DEL ESTUDIANTE"; ?></caption>
                <tr>
                  <td>
                    <table width="100%"  cellspacing="1 px" cellpadding="1px" border="0px">
                      <tr>
                        <td style="border:0px" rowspan="14" colspan="6">


                          <?
                          //ruta de la foto para validar si existe
                          $fotoLocal=$this->configuracion['raiz_appserv'].'/est_fotos/'.$this->datosEstudiante['CODIGO'].'.jpg';
                          //ruta de la foto para presentarla
                          $fotoWeb=$this->configuracion['host'].'/appserv/est_fotos/'.$this->datosEstudiante['CODIGO'].'.jpg'; 
                                                                                                               
                          $archivo=  @fopen($fotoLocal,"r");
			  if(!$archivo)
				{ 
				  $fotoLocal=$this->configuracion['raiz_appserv'].'/est_fotos/'.$this->datosEstudiante['CODIGO'].'.JPG';
				  $fotoWeb=$this->configuracion['host'].'/appserv/est_fotos/'.$this->datosEstudiante['CODIGO'].'.JPG';	
				  $archivo=  @fopen($fotoLocal,"r");		
				}
                          //si la foto existe la muestra si no presenta una imagen por defecto
                          if($archivo){
                       
                            echo '<img src="'.$fotoWeb.'" height="180" border="0">';
                            }else
                              {
                              echo '<img src="'.$this->configuracion['site'] . $this->configuracion['grafico'].'/personal.png" width="80" height="80" border="0">';
                              }
                          ?>                         
                        </td>
                        <td class='cuadro_plano' style="border:0px">Nombre: </td>
                        <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['NOMBRE'] ?></td>
                        <td class="cuadro_plano" style="border:0px" rowspan="2" colspan="6">
<?
?>
                  </td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px" width="20%">C&oacute;digo: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['CODIGO'] ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Carrera: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['CODIGO_CRA'] . " - " . $this->datosEstudiante['NOMBRE_CRA'] ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Plan de Estudios: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['PENSUM'])?$this->datosEstudiante['PENSUM']:'No registra dato'); ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Modalidad: </td>
                  <td class='cuadro_plano' style="border:0px"><? if(trim($this->datosEstudiante['MODALIDAD'])=='S')
                  {echo "CR&Eacute;DITOS";}
                  elseif(trim($this->datosEstudiante['MODALIDAD'])=='N')
                  {echo "HORAS";}
                  else {"SIN DATO";}?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Identificaci&oacute;n: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['IDENTIFICACION'])?$this->datosEstudiante['IDENTIFICACION']:'') ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Tel&eacute;fono: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['TELEFONO'])?$this->datosEstudiante['TELEFONO']:'') ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">E-Mail: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['EMAIL'])?$this->datosEstudiante['EMAIL']:'') ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Fecha de nacimiento: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['FEC_NACIMIENTO_MOSTRAR'])?$this->datosEstudiante['FEC_NACIMIENTO_MOSTRAR']:'') ?></td>
                </tr>
<!--                <tr>
                  <td class='cuadro_plano' style="border:0px">Veces en Prueba: </td>
                  <td class='cuadro_plano' style="border:0px"><? //echo $resultado_total[0]['VECES_PRUEBA_ESTUDIANTE'] ?></td>
                </tr>-->
                <tr>
                  <td class='cuadro_plano' style="border:0px">Estado Actual: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['DESC_ESTADO'] ?></td>
                </tr>
                <tr>
                  <td class="cuadro_plano" style="border:0px">Motivo</td>
                  <td class="cuadro_plano" style="border:0px">
                    <?echo (isset($_REQUEST['motivoPrueba'])?$_REQUEST['motivoPrueba']:'');?>
                  </td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Promedio Acumulado: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['PROMEDIO'])?$this->datosEstudiante['PROMEDIO']:''); ?></td>
                </tr>
                <? if($this->datosEstudiante['NIVEL']=='PREGRADO'){?>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Acuerdo: </td>
                  <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosEstudiante['ACUERDO'])){echo substr($this->datosEstudiante['ACUERDO'], -3)." de ".substr($this->datosEstudiante['ACUERDO'],0,4);}else{} ?></td>
                </tr>
                 <? } ?>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Valor Matrícula: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['VALOR_MATRICULA'])?$this->datosEstudiante['VALOR_MATRICULA']:'')?></td>
                </tr>
              </table>
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
     *Presenta los espacios que el estudiante ha cursado y aun no ha aprobado
     * @param type $espacios 
     */
    function buscarDatosEspaciosReprobados($espaciosSinAprobar,$espaciosCursados) {

        $espacioNoAprobado=array();
                
        //para cada espacio sin aprobar
        foreach ($espaciosSinAprobar as $codigoEspacioSinAprobar) {
            //buscar los datos en los espacios cursados
            foreach ($espaciosCursados as $espacio) {

                if($espacio['CODIGO']==$codigoEspacioSinAprobar){
                    $espacioNoAprobado[]=$espacio;
                }else
                    {
                    }
            }
            
        }                       
        
        return $espacioNoAprobado;
    }
    
    function mostrarEspaciosReprobados($reprobados) {
               $codigo=0;
        ?>
        <table class='contenidotabla sigma' width="100%" >
          <thead class='sigma'>
            <tr class='cuadro_plano'>
              <th class='sigma cuadro_plano centrar'>Codigo</th>
              <th class='sigma cuadro_plano centrar'>Nombre Espacio Acad&eacute;mico</th>
              <th class='sigma cuadro_plano centrar'>Nota</th>
              <th class='sigma cuadro_plano centrar' colspan="2">Per&iacute;odo</th>
            </tr>
                       <?foreach ($reprobados as $espacio) {
                            
                           ?>
                            <tr>
                                <td class='cuadro_plano centrar'>
                                    <?if($codigo!=$espacio['CODIGO'])
                                        {
                                        echo '<b>'.$espacio['CODIGO'].'</b>';                                        
                                        }
                                    else{
                                        echo $espacio['CODIGO'];                                        
                                        }?>
                                </td>                                         
                                <td class='cuadro_plano'>
                                    <?if($codigo!=$espacio['CODIGO'])
                                        {
                                        echo '<b>'.$espacio['NOMBRE'].'</b>';                                        
                                        }
                                    else{
                                        echo $espacio['NOMBRE'];                                        
                                        }?>
                                </td>                                         
                                <td class='cuadro_plano centrar'>
                                    <?if($codigo!=$espacio['CODIGO'])
                                        {
                                        echo '<b>'.number_format((isset($espacio['NOTA'])?$espacio['NOTA']:'')/10, 1).'</b>';                                        
                                        }
                                    else{
                                        echo number_format($espacio['NOTA']/10, 1);                                        
                                        }?>
                                </td>                                         
                                <td class='cuadro_plano centrar'>
                                    <?if($codigo!=$espacio['CODIGO'])
                                        {
                                        echo $espacio['ANO'].'-'.$espacio['PERIODO'].'</b>';                                        
                                        }
                                    else{
                                        echo $espacio['ANO'].'-'.$espacio['PERIODO'];                                  
                                        }?>
                                </td>                                                                                                                                                                        
                            </tr>                                   
                            <?
                                 $codigo=$espacio['CODIGO'];       
            
                            } 
                            ?>            
          </thead>
        </table>
        <?                    
        echo "<P class='cuadro_plano' style='border:0px'>*Los espacios acad&eacute;micos perdidos que el estudiante ya ha aprobado no aparecen en esta lista</P>";      
    }                
                
    /**
     * muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante
     */
    function mostrarHorarioEstudiante() {

      if ($this->datosEstudiante['CODIGO']) {
        $cadena_sql = $this->sql->cadena_sql("periodoActivo");
        $resultado_periodo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->periodo=$resultado_periodo;

        $variablesInscritos = array($this->datosEstudiante['CODIGO'], $resultado_periodo[0][0], $resultado_periodo[0][1]);
        $cadena_sql = $this->sql->cadena_sql("consultaGrupo", $variablesInscritos);
        $registroGrupo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(!is_array($registroGrupo)){
            $cadena_sql = $this->sql->cadena_sql("consultaGrupoPeriodoAnterior", $variablesInscritos);
            $registroGrupo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
        }

        $this->HorarioEstudianteConsulta($registroGrupo);


        $creditos = $this->calcularCreditos($registroGrupo);
      }
    }

    function mostrarNotasParciales() {
        //Consultar espacios y grupos que tiene inscrito el estudiante
      $cadena_sql = $this->sql->cadena_sql("consultarGrupos", $this->datosEstudiante['CODIGO']);
      $resultado_grupo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
       if (!is_array($resultado_grupo)) {
            $cadena_sql = $this->sql->cadena_sql("consultarGruposPeriodoAnterior", $this->datosEstudiante['CODIGO']);
            $resultado_grupo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
       }
    ?>

      <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
        <caption class="sigma centrar"><? echo "NOTAS PARCIALES ".$resultado_grupo[0][4]."-".$resultado_grupo[0][5]; ?></caption>
        <tr>
          <td>

    <?
      
      if (is_array($resultado_grupo)) {
    ?>
        <table class='contenidotabla sigma' width="100%">
          <thead class='sigma'>
            <tr class='cuadro_plano'>
              <th class='sigma cuadro_plano centrar'>Cod.</th>
              <th class='sigma cuadro_plano centrar'>Nombre Espacio Acad&eacute;mico</th>
              <th class='sigma cuadro_plano centrar'>Docente</th>
              <th class='sigma cuadro_plano centrar'>Grupo</th>
              <th class='sigma cuadro_plano centrar'>Nro Fallas</th>
              <th class='sigma cuadro_plano centrar'>Nota 1</th>
              <th class='sigma cuadro_plano centrar'>Nota 2</th>
              <th class='sigma cuadro_plano centrar'>Nota 3</th>
              <th class='sigma cuadro_plano centrar'>Nota 4</th>
              <th class='sigma cuadro_plano centrar'>Nota 5</th>
              <th class='sigma cuadro_plano centrar'>Nota 6</th>
              <th class='sigma cuadro_plano centrar'>Nota Lab</th>
              <th class='sigma cuadro_plano centrar'>Nota Examen</th>
              <th class='sigma cuadro_plano centrar'>Habil</th>
              <th class='sigma cuadro_plano centrar'>Acumulado</th>

            </tr>
          </thead>
    <?
        for ($i = 0; $i < count($resultado_grupo); $i++) {


          //busca porcentajes notas parciales del estudiante
          $cadena_sql = $this->sql->cadena_sql("consultarPorcentajeNotasParciales", $resultado_grupo[$i]);
          $resultado_porcentaje = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

          //busca notas parciales del estudiante
          
           $variablestotales = array($resultado_grupo[$i][0], 
                                    $resultado_grupo[$i][1], 
                                    $this->datosEstudiante['CODIGO'], 
                                    $resultado_grupo[$i][4], 
                                    $resultado_grupo[$i][5]);
          
          $cadena_sql = $this->sql->cadena_sql("consultarNotasParciales", $variablestotales);
          $resultado_notas = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          $cadena_sql = $this->sql->cadena_sql("consultarDocentesCurso", $resultado_grupo[$i]);
          $resultado_docentes = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          $docentes='';
          if(is_array($resultado_docentes))
          {
              foreach ($resultado_docentes as $key => $docente) {
                  $docentes=$docente['DOCENTE_APELLIDO']." ".$docente['DOCENTE_NOMBRE']."<br>";
              }
          }
    ?>
          <tr>
            <td class='cuadro_plano centrar'><? echo $resultado_grupo[$i]['CODIGO_ESPACIO'] ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_grupo[$i]['NOMBRE_ESPACIO'] ?></td>
            <td class='cuadro_plano centrar'><? echo (isset($docentes)?$docentes:''); ?></td>
            <td class='cuadro_plano centrar'><? echo (isset($resultado_grupo[$i]['NRO_GRUPO'])?$resultado_grupo[$i]['NRO_GRUPO']:''); ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_notas[0]['FALLAS'] ?></td>

            <?
            //arreglo con los nombres de las claves del arreglo $resultado_porcentaje y $resultado_notas
            $notas=array('NOTA1','NOTA2','NOTA3','NOTA4','NOTA5','NOTA6','NOTA_LABORATORIO','NOTA_EXAMEN','NOTA_HAB');                       

            for($n=0;$n<count($notas);$n++){?>

            <td class='cuadro_plano centrar'>
                <table class='contenidotabla centrar'>
                  <tr>
                    <th class='sigma cuadro_plano centrar'>
    <?
                    if((isset($resultado_porcentaje[0][$notas[$n]])?$resultado_porcentaje[0][$notas[$n]]:NULL)!=NULL)
                      {
                     echo $resultado_porcentaje[0][$notas[$n]].'%';
                      }
                    else{echo '-';}?>
                    </th>
                </tr>
                <tr>    
                  <td class='sigma centrar'>
                    <?
                    if((isset($resultado_porcentaje[0][$notas[$n]])?$resultado_porcentaje[0][$notas[$n]]:NULL)!=NULL)
                      {                                
                    echo number_format(((isset($resultado_notas[0][$notas[$n]])?$resultado_notas[0][$notas[$n]]:'') / 10), 1);
                      }
                    else{echo '-';}?>

                  </td>
                </tr>
              </table>
            </td>

            <?}?>
            <td class='cuadro_plano centrar'>
              <table class='contenidotabla centrar'><tr><td class='centrar'>
                </td>
                <?
                echo (isset($resultado_notas[0]['ACUMULADO'])?$resultado_notas[0]['ACUMULADO']:'');


                ?>
                </tr>
              </table>
            </td>

          </tr>
    <?
        }
    ?>
      </table>
    <?
      } else {
    ?>
        <table class='contenidotabla sigma' width="100%">
          <tr class='centrar'>
            <td class='sigma'>
              <b>NO EXISTEN INSCRIPCIONES REGISTRADAS</b>
            </td>
          </tr>
        </table>
    <?
     
      }
    ?>
    </td>
    </tr>
    </table>
    <?
    }

    /**
     *Presenta el titulo  HISTORICO DE NOTAS
     */
    function mostrarEnacabezadoEspaciosPerdidos() {
        ?>        
            <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px">
                    <caption class="sigma centrar"><? echo "ESPACIOS PERDIDOS"; ?></caption>
            </table>
        <?
    }
    
    /**
     *Presenta el titulo  HISTORICO DE NOTAS
     */
    function mostrarEnacabezadoNotasDefinitivas() {
        ?>        
            <table  width="100%" border="0" align="center" cellpadding="0 px" cellspacing="0px" >
                    <caption class="sigma centrar"><? echo "HISTORICO DE NOTAS"; ?></caption>
            </table>
        <?
    }

    
    function HorarioEstudianteConsulta($resultado_grupos) {

    ?>
      <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
        <tbody>
          <tr>
            <td>
                <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                  <caption class="sigma centrar"><? echo "Horario de Clases ".$resultado_grupos[0][3]."-".$resultado_grupos[0][4]; ?></caption>
        <? 
        if ($resultado_grupos != NULL) { ?>


          <tr>
            <td>
              <table class='contenidotabla sigma' width="100%">
                <thead class='sigma'>
                <th class='cuadro_plano sigma centrar'>Cod.</th>
                <th class='cuadro_plano sigma centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </th>
                <th class='cuadro_plano sigma centrar' width="25">Grupo </th>
                <th class='cuadro_plano sigma centrar' width="25">Cr&eacute;ditos</th>
                <th class='cuadro_plano sigma centrar' width="25">Clasificaci&oacute;n</th>
                <th class='cuadro_plano sigma centrar' width="60">Lun </th>
                <th class='cuadro_plano sigma centrar' width="60">Mar </th>
                <th class='cuadro_plano sigma centrar' width="60">Mie </th>
                <th class='cuadro_plano sigma centrar' width="60">Jue </th>
                <th class='cuadro_plano sigma centrar' width="60">Vie </th>
                <th class='cuadro_plano sigma centrar' width="60">S&aacute;b </th>
                <th class='cuadro_plano sigma centrar' width="60">Dom </th>
                </thead>
    <?
        //recorre cada uno del los grupos
        for ($j = 0; $j < count($resultado_grupos); $j++) {

          //
          $variables[0][0] = $resultado_grupos[$j][0];  //idEspacio
          $variables[0][1] = $resultado_grupos[$j][1];  //proyecto
          $variables[0][2] = $resultado_grupos[$j][2];  //grupo
          $variables[0][3] = $resultado_grupos[$j][5];  //nombre del espacio
          $variables[0][4] = $resultado_grupos[$j][6];  //codigo del estudiante
          $variables[0][5] = (isset($resultado_grupos[$j][7])?$resultado_grupos[$j][7]:'');  //creditos
          $variables[0][6] = (isset($resultado_grupos[$j][8])?$resultado_grupos[$j][8]:'');  //clasificacion
          $variables[0][7] = $resultado_grupos[$j][3];  //año
          $variables[0][8] = $resultado_grupos[$j][4];  //período
          //$variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante
          //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
          $cadena_sql = $this->sql->cadena_sql("horario_grupos", $variables);
          $resultado_horarios = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          //busca la clasificacion del espacio academico
          $cadena_sql = $this->sql->cadena_sql("clasificacionEspacio", $resultado_grupos[$j][0]);
          $resultado_clasif = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    ?>
          <tr>
            <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j][0]; ?></td>
            <td class='cuadro_plano '><? echo htmlentities($resultado_grupos[$j][5]); ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_horarios[0]['NRO_GRUPO']; ?></td>
            <td class='cuadro_plano centrar'><? echo (isset($resultado_grupos[$j][7])?$resultado_grupos[$j][7]:''); ?></td>
            <td class='cuadro_plano centrar'><? echo (isset($resultado_grupos[$j][9])?$resultado_grupos[$j][9]:''); ?></td>
    <?
          //recorre el numero de dias del la semana 1-7 (lunes-domingo)
          for ($i = 1; $i < 8; $i++) {
    ?><td class='cuadro_plano centrar'><?
            //Recorre el arreglo del resultado de los horarios
            for ($k = 0; $k < count($resultado_horarios); $k++) {
                                        if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:'') . "<br>Edificio: " . (isset($resultado_horarios[$l]['EDIFICIO'])?$resultado_horarios[$l]['EDIFICIO']:'') . "<br>Sal&oacute;n:" . (isset($resultado_horarios[$l]['SALON'])?$resultado_horarios[$l]['SALON']:'');
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

            }
                            ?></td><?
                                  }
                            ?>
                              </tr>

                              <?
                                }
                              } else {
                              ?>
                                <tr>
                                  <td class='sigma centrar'>
                                    <b>NO EXISTE HORARIO REGISTRADO</b>
                                  </td>
                                </tr>
                            <? } ?>
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
     *Presenta el listado de notas por nivel
     * @param type $resultado_notas consulta de notas activas de un estudiante 
     */
    function mostrarNotasDefinitivas($resultado_notas) {        

        $clasetr='';
        
        //Buscar los niveles cursados*******************************
        $niveles=array();//almacena los nivele de las notas
        if (is_array($resultado_notas)){
        foreach ($resultado_notas as $nota) {
            $niveles[]=(isset($nota['NIVEL'])?$nota['NIVEL']:'');
        }
        sort($niveles);
        $niveles=  array_unique($niveles);
        
        }
        
        
        //**************************************************************
        
        ?><table class='sigma' width='95%' align=center border='0'><?
        
        foreach ($niveles as $nivel) {
            if($nivel==0)
            {
                ?>
                <tr>
                    <th colspan='6' align='center'><? echo 'ELECTIVAS' ?></th>
                </tr>
                <?                
            }elseif($nivel==98)
                {
                    ?>
                    <tr>
                        <th colspan='6' align='center'><? echo 'COMPONENTE PROPED&Eacute;UTICO' ?></th>
                    </tr>
                    <?                
                }else
                    {
                        ?>
                        <tr>
                            <th colspan='6' align='center'><? echo 'NIVEL: '. $nivel ?></th>
                        </tr>
                        <?
                    }
            ?>
                <tr>
                    <th class='sigma' align='center' width='10%'>Asignatura</th>
                    <th class='sigma' align='center' width='40%'>Nombre</th>
                    <th class='sigma' align='center' width='10%'>Nota</th>
                    <th class='sigma' align='center' width='10%'>A&ntilde;o</th>
                    <th class='sigma' align='center' width='10%'>Periodo</th>
                    <th class='sigma' align='center' width='20%'>Observaci&oacute;n</th>
                </tr>                
            <?
                        foreach ($resultado_notas as $nota) {
                            if($nivel==(isset($nota['NIVEL'])?$nota['NIVEL']:'')){
                                ?>
                                    <tr class='<? echo $clasetr ?>'>
                                        <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $nota['CODIGO'] ?></td>
                                        <td class='cuadro_plano' style="border:0px"><? echo $nota['NOMBRE'] ?></td>
                                        <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo (isset($nota['NOTA'])?$nota['NOTA']:'') ?></td>
                                        <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $nota['ANO'] ?></td>
                                        <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $nota['PERIODO'] ?></td>
                                        <td class='cuadro_plano centrar' style="border:0px" align='center'>
                                            <?
                                                    if ($nota['CODIGO_OBSERVACION'] != 0) {
                                                    echo $nota['NOTA_OBSERVACIONES'];
                                                    } else {
                                                    echo '';
                                                    }
                                            ?>
                                        </td>                                 
                                    </tr>
                                <?

                            }
                        }
        }
        
        ?>
            </table><?
        //$this->detallarNotas($resultado_notas); se deja comentada porque se sacó de esta funcion pero no se estaba mostrando.

    }
    /*
     * Presenta detalle de las notas. Se encontraba interno en la funcion mostrarNotasDefinitivas pero se saca y se coloca en una nueva funcion.
     * Dentro de mostrarNotasDefinitivas no se estaba usando, ya que antes de presentarlo existia un exit.
     */
    function detallarNotas($resultado_notas) {
      if (count($resultado_notas) > 1) {
    ?><table class='sigma' width='95%' align=center border='0'><?
        for ($i = 0; $i < count($resultado_notas); $i++) {
          if ($i % 2 == 0) {
            $clasetr = "sigma_a";
          } else {
            $clasetr = "sigma";
          }
          if ($resultado_notas[$i - 1]['NIVEL'] != $resultado_notas[$i]['NIVEL']) {
    ?>
              <tr>
                <th colspan='6' align='center'><? echo 'NIVEL ' . $resultado_notas[$i]['NIVEL'] ?></th>
              </tr>
              <tr>
                <th class='sigma' align='center' width='10%'>Asignatura</th>
                <th class='sigma' align='center' width='40%'>Nombre</th>
                <th class='sigma' align='center' width='10%'>Nota</th>
                <th class='sigma' align='center' width='10%'>A&ntilde;o</th>
                <th class='sigma' align='center' width='10%'>Periodo</th>
                <th class='sigma' align='center' width='20%'>Observaci&oacute;n</th>
              </tr>

              <tr class='<? echo $clasetr ?>'>
                <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $resultado_notas[$i]['CODIGO'] ?></td>
                <td class='cuadro_plano' style="border:0px"><? echo $resultado_notas[$i]['NOMBRE'] ?></td>
                <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $resultado_notas[$i]['NOTA'] ?></td>
                <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $resultado_notas[$i]['ANO_CURSADO'] ?></td>
                <td class='cuadro_plano centrar' style="border:0px" align='center'><? echo $resultado_notas[$i]['PERIODO_CURSADO'] ?></td>
                <td class='cuadro_plano centrar' style="border:0px" align='center'>
    <?
            if ($resultado_notas[$i]['CODIGO_OBSERVACION'] != 0) {
              echo $resultado_notas[$i]['NOTA_OBSERVACIONES'];
            } else {
              echo '';
            }
    ?>
          </td>
        </tr>
    <?
          } else {
    ?>
            <tr class='<? echo $clasetr ?>'>
              <td class='cuadro_plano centrar' style="border:0px" align='center' width='10%'><? echo $resultado_notas[$i]['CODIGO'] ?></td>
              <td class='cuadro_plano' style="border:0px" width='40%'><? echo $resultado_notas[$i]['NOMBRE'] ?></td>
              <td class='cuadro_plano centrar' style="border:0px" align='center' width='10%'><? echo $resultado_notas[$i]['NOTA'] ?></td>
              <td class='cuadro_plano centrar' style="border:0px" align='center' width='10%'><? echo $resultado_notas[$i]['ANO_CURSADO'] ?></td>
              <td class='cuadro_plano centrar' style="border:0px" align='center' width='10%'><? echo $resultado_notas[$i]['PERIODO_CURSADO'] ?></td>
              <td class='cuadro_plano centrar' style="border:0px" align='center'>
    <?
            if ($resultado_notas[$i]['CODIGO_OBSERVACION'] != 0) {
              echo $resultado_notas[$i]['NOTA_OBSERVACIONES'];
            } else {
              echo '';
            }
    ?>
          </td>
        </tr>
    <?
          }
        }
    ?></table><?
      }
        
    }

    function calcularCreditos($registroGrupo) {
      $suma = 0;
      for ($i = 0; $i < count($registroGrupo); $i++) {
        $suma+=(isset($registroGrupo[$i][9])?$registroGrupo[$i][9]:'');
      }

      return $suma;
    }

    /**
     *Consulta datos del estudiante 
     */          
    function consultarDatosEstudiante($codEstudiante) {
               
        $variables=array(
                            'codEstudiante'=>$codEstudiante
                        );   

        $cadena_sql = $this->sql->cadena_sql("consultarDatosEstudiante", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado[0];
        
    }
    
    function buscarDatosDocente($docente) {
            
        
                $variables=array(
                            'codDocente'=>$docente
                        );   


        $cadena_sql = $this->sql->cadena_sql("consultarDatosDocente", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado;
        
    }
    
    function buscarPeriodoActivo() {
               
        $variables=array(
                           
                        );   

        $cadena_sql = $this->sql->cadena_sql("periodoActivo", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado;
        
    }
    
    /**
     * Funcion que permite consultar la nota aprobatoria para el proyecto del estudiante
     * @return <int>
     */
    function consultarNotaAprobatoria() {

        $variables=array('codProyectoEstudiante'=>  $this->datosEstudiante['CODIGO_CRA']            
                        );
        $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
      }
    
    /**
     *
     * @return type 
     */  
    function buscarNotasDefinitivas() {
        
      $cadena_sql = $this->sql->cadena_sql("consultarEspaciosCursados", $this->datosEstudiante['CODIGO']);
      $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");        
     
      return $resultado;
      
    }

    /**
     * Funcion que busca los espacios aprobados por el estudiante entre el arreglo de los cursados.
     * @param type $notaAprobatoria
     * @return string 
     */
    function buscarEspaciosAprobados($notaAprobatoria, $espaciosCursados){
        $aprobados=array();
        
        if(is_array($espaciosCursados))
        {
            foreach ($espaciosCursados as $value1)
            {
                if (isset($value1['NOTA'])&&$value1['NOTA']>=$notaAprobatoria)
                {
                    $aprobados[]=$value1['CODIGO'];
                }
            }

        }else
            {
                $aprobados='';
            }

        return $aprobados;
    }
    
    /**
     * Funcion que permite consultar los espacios reprobados por el estudiante
     * @return <string/0>
     */
    function buscarEspaciosReprobados($notaAprobatoria, $espaciosCursados) {
        
          $reprobados=isset($reprobados)?$reprobados:'';
          
          if (is_array($espaciosCursados)){
              foreach ($espaciosCursados as $value) {
                  if (isset($value['NOTA'])&&($value['NOTA']<$notaAprobatoria||$value['CODIGO_OBSERVACION']==20||$value['CODIGO_OBSERVACION']==23||$value['CODIGO_OBSERVACION']==25)){
                    if ($value['CODIGO_OBSERVACION']==19||$value['CODIGO_OBSERVACION']==22||$value['CODIGO_OBSERVACION']==24)
                    {
                    }else
                        {
                            $reprobados[]=$value['CODIGO'];
                        }
                 }
              }
              if(is_array($reprobados)){
                  
                    $reprobados=  array_unique($reprobados);
                    return $reprobados;      
                  
              }else{
             return 0;   
              }
              
          }
          else
            {
              return 0;
            }
      }    
    
    /**
     * Funcion que resta los espacios aprobados del arreglo de los espacios reprobados
     * Con esto se obtienen los espacios que ya han sido cursados una o mas veces y aún no se han aprobado
     * @return type 
     */
    function buscarEspaciosCursadosSinAprobar(){
        if(is_array($this->espaciosReprobados) and is_array($this->espaciosAprobados)){
        $resultado=array_diff($this->espaciosReprobados, $this->espaciosAprobados);
       return $resultado;
        }
        else{return 0;}
    }      
      
       
    /**
     * Función para consultar reglamento de un estudiante
     * @param type $cod_estudiante
     * @return type 
     */
    function consultarReglamentoEstudiante($cod_estudiante) {

        $cadena_sql = $this->sql->cadena_sql("consultar_reglamento_estudiante", $cod_estudiante);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
      }
    
    /**
     * Función para consultar las deudas relacionadas a un estudiante
     * @param type $cod_estudiante
     * @return type 
     */
    function consultarDeudasEstudiante($cod_estudiante) {

        $cadena_sql = $this->sql->cadena_sql("consultar_deudas_estudiante", $cod_estudiante);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
      }
    
    /**
     * Funcion para mostrar el reglamento de un estudiante
     * @param type $reglamentoEstudiante 
     */
    function mostrarReglamentoEstudiante($reglamentoEstudiante,$estado){
        ?>
            <script type="text/javascript" src='<? echo $this->configuracion["host"] . $this->configuracion["site"];?>/funcion/jquery.js'></script>
            <script type="text/javascript">
            $(function()
            {

            $("#mostrar").click(function(event) {
            event.preventDefault();
            $("#caja").slideToggle();
            });

            $("#caja a").click(function(event) {
            event.preventDefault();
            $("#caja").slideUp();
            });
            });
            </script>
            <style type="text/css">
            
            #caja {
            width:70%;
            display: none;
            padding:5px;
           
            }
            #mostrar{
            display:block;
            width:70%;
            padding:5px;
            
            }
            </style>
        <?
        $html ="<center><table  width='100%' border='0' align='center' cellpadding='5 px' cellspacing='1px'>";
        $html .="<caption class='sigma centrar'>HISTORICO DE REGLAMENTO DEL ESTUDIANTE</caption>";   
        $html .="</table> ";
        $html .="<div >";
        $html .="<table class='contenidotabla sigma' width='100%' >";
        $html .="<tr class='cuadro_plano'>";
        $html .="<th class='sigma cuadro_plano centrar' width='20%'>Per&iacute;odo</th>";
        $html .="<th class='sigma cuadro_plano centrar' width='20%'>Causales prueba acad&eacute;mica por per&iacute;odo</th>";
        $html .="<th class='sigma cuadro_plano centrar' width='20%'>Promedio ponderado Acumulado</th>";
        if($estado=='U' || $estado=='Z' ){
            $html .="<th class='sigma cuadro_plano centrar' width='20%'>Causal p&eacute;rdida calidad de estudiante</th>";
        }
        $html .="</tr>";
        if($reglamentoEstudiante){
                foreach ($reglamentoEstudiante as $key => $reglamento) {
                    $causales = $this->formatearCausales($reglamento['REG_MOTIVO']);
                    $promedio = (isset($reglamento['REG_PROMEDIO'])?$reglamento['REG_PROMEDIO']:'');
                    if($promedio ){
                        $promedio=($promedio/100);
                    }
                    $html .="<tr>";
                    $html .="<td  class='cuadro_plano centrar'>".$reglamento['REG_ANO']."-".$reglamento['REG_PER']."</td>";
                    $html .="<td  class='cuadro_plano'>".$causales."</td>";
                    if($promedio){
                        $html .="<td  class='cuadro_plano centrar'>".number_format($promedio,2)."</td>";
                    }else{
                        $html .="<td  class='cuadro_plano centrar'>&nbsp;</td>";
                    }
                    if($estado=='U' || $estado=='Z' ){
                        $causales_exclusion = $this->formatearCausales((isset($reglamento['REG_CAUSAL_EXCLUSION'])?$reglamento['REG_CAUSAL_EXCLUSION']:''));
                        $html .="<td  class='cuadro_plano'>".$causales_exclusion."</td>";
                    }
                    $html .="</tr>";
                }
        }
        $html .="<table class='contenidotabla sigma' width='50%' border='0' align='center' cellpadding='5 px' cellspacing='1px' >";
        $html .="<tr class='cuadro_plano'>";
        $html .="<th class='sigma cuadro_plano centrar' colspan='2'>Convenci&oacute;n causales de prueba acad&eacute;mica</th>";
        $html .="</tr>";
                $html .="</tr>";
                $html .="<tr class='cuadro_plano'>";
                $html .="<th class='sigma cuadro_plano centrar' >No. causal</th>";
                $html .="<th class='sigma cuadro_plano centrar' >Descripci&oacute;n</th>";
                $html .="</tr>";
                $html .="<tr>";
                $html .="<td  class='cuadro_plano centrar'>1</td>";
                $html .="<td  class='cuadro_plano'>Promedio inferior a 3.2 para acuerdo 004 ó 3.0 para acuerdo 007 y 027 </td>";
                $html .="</tr>";
                $html .="<tr>";
                $html .="<td  class='cuadro_plano centrar'>2</td>";
                $html .="<td  class='cuadro_plano'>Estar cursando 3 o m&aacute;s espacios acad&eacute;micos reprobados en un mismo periodo</td>";
                $html .="</tr>";
                $html .="<tr>";
                $html .="<td  class='cuadro_plano centrar'>3</td>";
                $html .="<td  class='cuadro_plano'>Estar cursando 3 o m&aacute;s veces un mismo espacio acad&eacute;mico</td>";
                $html .="</tr>";
         if($estado=='U' || $estado=='Z' ){
                $html .="<th class='sigma cuadro_plano centrar' colspan='2'>Convenci&oacute;n causales de p&eacute;rdida calidad de estudiante</th>";
                $html .="</tr>";
                $html .="<tr class='cuadro_plano'>";
                $html .="<th class='sigma cuadro_plano centrar' >No. causal</th>";
                $html .="<th class='sigma cuadro_plano centrar' >Descripci&oacute;n</th>";
                $html .="</tr>";
                $html .="<tr>";
                $html .="<td  class='cuadro_plano centrar'>1</td>";
                $html .="<td  class='cuadro_plano'>Promedio inferior a 3.2 para acuerdo 004 ó 3.0 para acuerdo 007 y 027 </td>";
                $html .="</tr>";
                $html .="<tr>";
                $html .="<td  class='cuadro_plano centrar'>2</td>";
                $html .="<td  class='cuadro_plano'>Reprob&oacute; 3 o m&aacute;s espacios acad&eacute;micos en un mismo periodo</td>";
                $html .="</tr>";
                $html .="<tr>";
                $html .="<td  class='cuadro_plano centrar'>3</td>";
                $html .="<td  class='cuadro_plano'>Reprob&oacute; 3 o m&aacute;s veces un mismo espacio acad&eacute;mico</td>";
                $html .="</tr>";
                $html .="<tr class='cuadro_plano'>";
                $html .="<td  class='cuadro_plano centrar'>4</td>";
                $html .="<td  class='cuadro_plano'>Acumulaci&oacute;n de pruebas acad&eacute;micas</td>";
                $html .="</tr>";
                $html .="<tr class='cuadro_plano'>";
                $html .="<td  class='cuadro_plano centrar'>5</td>";
                $html .="<td  class='cuadro_plano'>Renovaciones de Matr&iacute;cula</td>";
                $html .="</tr>";
                }
        $html .="</table><br>";    
        $html .="</div></center>";
        
        echo $html;
        
    }

    /**
     * Funcion para pones comas entre los codigos de las causales
     * @param type $causales
     * @return string
     */
    function formatearCausales($causales){
        $causales = str_replace('0','',$causales);
        $pos2 = stripos($causales, '2');
        $pos3 = stripos($causales, '3');
        if($pos2>0 || $pos3>0){
            $causales = str_replace('1','1,',$causales);
        }
        if($pos3>0){
            $causales = str_replace('2','2,',$causales);
        }
        return $causales;
    }
    
    /** 
     * Funcion que retorna arreglo con los codigos de los espacios vistos
     * @return array
     */
    function espaciosVistos(){
        $i=0;
        $espacios_vistos=array();
        if(is_array($this->espaciosCursados)){
            foreach ($this->espaciosCursados as  $espacio) {
                    $espacios_vistos[$i]=$espacio[0];
                    $i++;
            }
         }
         $espacios_vistos = array_unique($espacios_vistos);
        return $espacios_vistos;
    }
    
    /**
     * Funcion para contar la cantidad de pruebas academicas de un estudiante teniendo en cuenta el acuerdo al cual esta acogido
     * @param array $reglamentoEstudiante
     * @return int
     */
    function contarPruebasAcademicas($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $cantidad=0;
            switch( $this->datosEstudiante['ACUERDO'])
                    {
                        case '2011004':
                                $cantidad = $this->contarPruebasAcuerdo2011004($reglamentoEstudiante);
                        break;
                        case '2009007':
                                $cantidad = $this->contarPruebasAcuerdo2009007($reglamentoEstudiante);
                        break;
                        case '1993027':
                                $cantidad = $this->contarPruebasAcuerdo1993027($reglamentoEstudiante);
                        break;
                    }
        }else{
            $cantidad='';
        }
        return $cantidad;
    }
    
    /**
     * Funcion que cuenta la cantidad de pruebas con el acuerdo 004
     * @param type $reglamentoEstudiante
     * @return int/string
     */
    function contarPruebasAcuerdo2011004($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $veces=0;
            foreach ($reglamentoEstudiante as $reglamento) {
                $anioperiodo=$reglamento['REG_ANO'].$reglamento['REG_PER'];
                if($reglamento['REG_REGLAMENTO']=='S' && $anioperiodo>=20113){
                    $veces++;
                }
            }
        }else{
            $veces='';
        }
        return $veces;
    }
    
    /**
     * Funcion que cuenta la cantidad de pruebas con el acuerdo 007
     * @param array $reglamentoEstudiante
     * @return string
     */
    function contarPruebasAcuerdo2009007($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $veces=0;
            foreach ($reglamentoEstudiante as $reglamento) {
                $anioperiodo=$reglamento['REG_ANO'].$reglamento['REG_PER'];
                if($reglamento['REG_REGLAMENTO']=='S' && $anioperiodo!=20101 && $anioperiodo!=20111 ){
                    $veces++;
                }
            }
        }else{
             $veces='';
        }
        return $veces;
    }
    
    /**
     * Funcion que cuenta la cantidad de pruebas con el acuerdo 027
     * @param array $reglamentoEstudiante
     * @return int/string
     */
    function contarPruebasAcuerdo1993027($reglamentoEstudiante){
        if(is_array($reglamentoEstudiante)){
            $veces=0;
            foreach ($reglamentoEstudiante as $reglamento) {
                if($reglamento['REG_REGLAMENTO']=='S'){
                    $veces++;
                }
            }
        }else{
            $veces='';
        }
        return $veces;
    }

    /**
     * Funcion para consultar la cantidad de matriculas de un estudiante
     * @param type $cod_estudiante
     * @return type
     */
    function consultarMatriculas($cod_estudiante){
        $cadena_sql = $this->sql->cadena_sql("matriculas", $cod_estudiante);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }

    /**
     * Funcion para contar los semestres transcurridos desde el ingreso a la institucion
     * @param string $semestre_ingreso
     * @return int
     */
    function contarSemestresTranscurridos($semestre_ingreso){
        $cantidad='';
        $anio_actual=date('Y');
        if(date('m')<=6){
            $semestre_actual=1;
        }else{
            $semestre_actual=2;
        }
        $periodo_actual =$anio_actual.$semestre_actual;
        if($periodo_actual>=$semestre_ingreso){
            $cantidad = $this->calcularCantidadSemestres($semestre_ingreso,$periodo_actual);
        }
        return $cantidad;
    }
    
    /**
     * Funcion para contar los espacios adelantados
     * @param int $cantidad_matriculas
     * @return int
     */
    function contarEspaciosAdelantados($cantidad_matriculas){
        $cantidad=0;
        
        if(is_array($this->espaciosAprobados))
        {
            foreach ($this->espaciosAprobados as $value1)
            {
                if ((isset($value1['NOTA'])&&$value1['NOTA']>=$notaAprobatoria) || (isset($value1['CODIGO_OBSERVACION'])?$value1['CODIGO_OBSERVACION']:'')==19)
                {
                    if($value1['NIVEL']>$cantidad_matriculas){
                        $cantidad++;
                    }
                }
            }

        }
        return $cantidad;
    }
    
    /**
     * Funcion  para calcular la edad del estudiante al momento del ingreso a la institucion 
     * @param string $semestre_ingreso
     * @return int
     */
    function calcularEdadIngreso($semestre_ingreso){
        $anios='';
        $anio_ingreso = substr($semestre_ingreso, 0, 4);
        $per_ingreso = substr($semestre_ingreso, 4, 1);
        if($per_ingreso==1){
            $mes_ingreso= '02';
        }else if ($per_ingreso==2){
            $mes_ingreso= '08';
        }
        $fecha_ingreso = $anio_ingreso.$mes_ingreso.'01';
        if($this->datosEstudiante['FEC_NACIMIENTO']){
            $anios=  $this->calcularAnios($this->datosEstudiante['FEC_NACIMIENTO'],$fecha_ingreso);
        }else{
            echo "<br>No existe informaci&oacute;n de la fecha de nacimiento";
        }
        return $anios;
    }
    
    
    /**
     * Funcion para calcular los años entre dos fechas
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return type
     */
    function calcularAnios($fecha_inicio,$fecha_fin){
        if(strtotime($fecha_inicio) > strtotime($fecha_fin)){
                echo "ERROR -> la fecha inicial es mayor a la fecha final <br>";
               exit();
        }else{
                $meses=$this->calcularCantidadMeses($fecha_inicio,$fecha_fin);
                $meses=(int)$meses-1;
                
                $anios=(int)($meses/12);
        }
      
        return $anios;
    }
    
    
    /**
     * Funcion para calcular la cantidad de meses entre 2 fechas
     * @param date $fecha_inicio
     * @param date $fecha_fin
     * @return int 
     */
    function calcularCantidadMeses($fecha_inicio,$fecha_fin){
        $dia_inicio= substr($fecha_inicio, 8,2);
        $mes_inicio= substr($fecha_inicio, 5,2);
        $ano_inicio= substr($fecha_inicio, 0,4);

        $dia_fin= substr($fecha_fin, 8,2);
        $mes_fin= substr($fecha_fin, 5,2);
        $ano_fin= substr($fecha_fin, 0,4);
        $dif_anios = $ano_fin- $ano_inicio;
                if($dif_anios == 1){
                    $mes_inicio = 12 - $mes_inicio;
                    $meses = $mes_fin + $mes_inicio;
                }
                else{
                        if($dif_anios == 0){
                            $meses=$mes_fin - $mes_inicio;
                        }
                        else{
                            if($dif_anios > 1){
                                $mes_inicio = 12 - $mes_inicio;
                                $meses = $mes_fin + $mes_inicio + (($dif_anios - 1) * 12);
                            }
                            else { exit;    }
                        }
                    }
                    return $meses;
    }
    
    /**
     * Funcion para contar la cantidad de semestre despues del grado para ingresar a la institucion
     * @param type $semestre_ingreso
     * @return type
     */
    function contarSemestresDespuesDeGrado($semestre_ingreso){
        $cantidad='';
        if((isset($this->datosEstudiante['FEC_GRADO'])?$this->datosEstudiante['FEC_GRADO']:'')){
            $anio_grado=  substr($this->datosEstudiante['FEC_GRADO'], 0, 4);
            $mes_grado=  substr($this->datosEstudiante['FEC_GRADO'], 4, 2);
            if($mes_grado<=6){
                $semestre_grado=1;
            }else{
                $semestre_grado=2;
            }
            $periodo_grado =$anio_grado.$semestre_grado;
            if($semestre_ingreso>$periodo_grado){
                $periodo_grado =  $this->calcularSiguientePeriodo($periodo_grado);
                $cantidad = $this->calcularCantidadSemestres($periodo_grado,$semestre_ingreso);
            }else{
                $cantidad=0;
            }
        }else{
            echo "<br>No tiene informaci&oacute;n de fecha de grado";
        }
        return $cantidad;
    }
    
    /**
     * Funcion para calcular  la cantidad de semestres entre 2 periodos
     * @param type $periodo_ini
     * @param type $periodo_fin
     * @return type
     */
    function calcularCantidadSemestres($periodo_ini,$periodo_fin){
	$semestres = 0;
	$anio_ini = substr($periodo_ini,0,4);
	$per_ini = substr($periodo_ini,4,1);
	$anio_fin = substr($periodo_fin,0,4);
	$per_fin = substr($periodo_fin,4,1);

	$anios = $anio_fin - $anio_ini;
	$semestres=$anios*2;
	if($per_ini>$per_fin){
		$semestres--;
	}
	if($per_ini<$per_fin){
		$semestres++;
	}
	return $semestres;
    }


    /**
     * Funcion para calcular el siguiente periodo a uno especificado
     * @param string $periodo
     * @return string
     */
    function calcularSiguientePeriodo($periodo){
	$anio = substr($periodo,0,4);
	$per = substr($periodo,4,1);
	if($per==2){
		$per=1;
		$anio++;
	}else if($per==1){
		$per++;
	}
	$periodo=$anio.$per;
	return $periodo;	

    }
    
    function calculosRendimientoAc($reglamentoEstudiante,$notaAprobatoria,$espaciosVistos,$matriculas,$semestre_ingreso){
          $cantidad_matriculas = count($matriculas);
          $var_modelo1 = array(
                'cantidad_aprobados' => count($this->espaciosAprobados), 
                'cantidad_reprobados' => count($this->espaciosReprobados), 
                'cantidad_espacios_vistos' => count($espaciosVistos),
                'cantidad_pruebas_academicas' => $this->contarPruebasAcademicas($reglamentoEstudiante),
                'promedio_acumulado' => $this->datosEstudiante['PROMEDIO'],
                'cantidad_matriculas' => $cantidad_matriculas,
                'cantidad_semestres'=>$this->contarSemestresTranscurridos($semestre_ingreso),
                'cantidad_espacios_adelantados' => $this->contarEspaciosAdelantados($cantidad_matriculas,$notaAprobatoria,$this->espaciosCursados),
                'cantidad_espacios_nivelado' => 50
          );

          $rendimiento_academico = $this->modeloRiesgo->calcularRendimientoAcademico($var_modelo1);
          return $rendimiento_academico;
    }
    
    function calculosProbabilidadR($reglamentoEstudiante,$espaciosVistos,$matriculas,$semestre_ingreso){
        $var_modelo2 = array(
                'semestre_espacio_mas_atrasado' => 5,
                'cantidad_matriculas' => count($matriculas),
                'cantidad_pruebas_academicas' => $this->contarPruebasAcademicas($reglamentoEstudiante),
                'cantidad_reprobados' => count($this->espaciosReprobados), 
                'cantidad_espacios_vistos' => count($espaciosVistos),
                'promedio_acumulado' => $this->datosEstudiante['PROMEDIO'],
                'edad_ingreso'=>  $this->calcularEdadIngreso($semestre_ingreso),
                'cantidad_semestres_despues_grado' => $this->contarSemestresDespuesDeGrado($semestre_ingreso)
              
          );
          
          $probabilidad_riesgo = $this->modeloRiesgo->calcularProbabilidadRiesgo($var_modelo2);
          return $probabilidad_riesgo;
    }
    
    function mostrarResultadosModelos($valores_rendimiento_academico,$valores_probabilidad_riesgo){
        
            echo "<br><table width='80%' style='border: #70739b 3px solid;' align='center'>";
            echo "<th style='vertical-align:middle; text-align:center; font-size: 12px;line-height:15px;' colspan='2'>MODELOS DE RENDIMIENTO Y RIESGO</th>";
            echo "<tr>";
            echo "<td width='100%' align='left'>";
                $this->mostrarModeloRendimiento($valores_rendimiento_academico);
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td width='100%' align='left'>";
                $this->mostrarModeloRiesgo($valores_probabilidad_riesgo);  
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2' align='center'><br>";
            echo "<a target='_blank' href='".$this->configuracion['host'].$this->configuracion['site']."/documentos/bienestar/Modelos.pdf'><img src='".$this->configuracion['site']. $this->configuracion['grafico']."/acroread.png' width='25' height='25' border='0' style='vertical-align:middle'>
                        Ver información modelos de rendimiento y riesgo </a>";
            echo "</td>";
            echo "</tr>";
            echo "</table><br>";
        
    }
    
    function buscarValoresRendimientoAcademico($reglamentoEstudiante){
            $valores='';
            if(is_array($reglamentoEstudiante)){
                $cantidad = count($reglamentoEstudiante);
                if(isset($reglamentoEstudiante[$cantidad-1]['REG_RENDIMIENTO_AC'])?$reglamentoEstudiante[$cantidad-1]['REG_RENDIMIENTO_AC']:''){
                    $valores = array(
                                'REG_ANO'=>$reglamentoEstudiante[$cantidad-1]['REG_ANO'],
                                'REG_PER'=>$reglamentoEstudiante[$cantidad-1]['REG_PER'],
                                'REG_NUMERO_PRUEBAS_AC'=>$reglamentoEstudiante[$cantidad-1]['REG_NUMERO_PRUEBAS_AC'],
                                'REG_PROMEDIO'=>$reglamentoEstudiante[$cantidad-1]['REG_PROMEDIO'],
                                'REG_INDICE_REPITENCIA'=>$reglamentoEstudiante[$cantidad-1]['REG_INDICE_REPITENCIA'],
                                'REG_INDICE_PERMANENCIA'=>$reglamentoEstudiante[$cantidad-1]['REG_INDICE_PERMANENCIA'],
                                'REG_INDICE_NIVELACION'=>$reglamentoEstudiante[$cantidad-1]['REG_INDICE_NIVELACION'],
                                'REG_RENDIMIENTO_AC'=>$reglamentoEstudiante[$cantidad-1]['REG_RENDIMIENTO_AC']
                 );
                }
            }
            return $valores;
    }
    
      function buscarValoresRiesgoAcademico($reglamentoEstudiante){
            $valores='';
            if(is_array($reglamentoEstudiante)){
            
                $cantidad = count($reglamentoEstudiante);
                if(isset($reglamentoEstudiante[$cantidad-1]['REG_INDICE_RIESGO'])?$reglamentoEstudiante[$cantidad-1]['REG_INDICE_RIESGO']:''){
                    $valores = array(
                                'REG_ANO'=>$reglamentoEstudiante[$cantidad-1]['REG_ANO'],
                                'REG_PER'=>$reglamentoEstudiante[$cantidad-1]['REG_PER'],
                                'REG_NUMERO_PRUEBAS_AC'=>$reglamentoEstudiante[$cantidad-1]['REG_NUMERO_PRUEBAS_AC'],
                                'REG_NUMERO_PRUEBAS_AC'=>$reglamentoEstudiante[$cantidad-1]['REG_NUMERO_PRUEBAS_AC'],
                                'REG_INDICE_ATRASO'=>$reglamentoEstudiante[$cantidad-1]['REG_INDICE_ATRASO'],
                                'REG_PROMEDIO'=>$reglamentoEstudiante[$cantidad-1]['REG_PROMEDIO'],
                                'REG_EDAD_INGRESO'=>$reglamentoEstudiante[$cantidad-1]['REG_EDAD_INGRESO'],
                                'REG_NUM_SEMESTRES_INGRESO'=>$reglamentoEstudiante[$cantidad-1]['REG_NUM_SEMESTRES_INGRESO'],
                                'REG_INDICE_RIESGO'=>$reglamentoEstudiante[$cantidad-1]['REG_INDICE_RIESGO']
                    );
                }
            }
            return $valores;
    }
    
    
    function mostrarModeloRendimiento($valores_rendimiento_academico){
        $rendimiento_academico=(isset($valores_rendimiento_academico['REG_RENDIMIENTO_AC'])?$valores_rendimiento_academico['REG_RENDIMIENTO_AC']:'');
        if($rendimiento_academico){
                $rendimiento_academico=number_format($rendimiento_academico*100,2);
                $ancho_barra = (450*$rendimiento_academico)/100;
                echo "<table align='left' width='800' cellspacing='0'>
                        <tr>
                        <td width='350'><br>RA (Rendimiento Acad&eacute;mico) = ".$rendimiento_academico." %</td>
                        <td width='450'><table width='100%' style='border: 1px solid;' align='center'>
                                        <tr>
                                           <td width='".$ancho_barra."' class='sigma centrar' bgcolor='#325cb3'>".$rendimiento_academico."%</td>
                                           <td class='porcentajes' width='".(450-$ancho_barra)."' ></td>
                                           </tr>
                                        </table>
                        </td>
                        </tr>
                       </table>";
               
          }else{
                echo "<br>RA (Rendimiento Acad&eacute;mico) = (El riesgo no se encuentra calculado para el ultimo registro de cierre)";
          }
    }
    
    function mostrarModeloRiesgo($valores_probabilidad_riesgo){
        ?>
            <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/overlib/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
        <?
        $probabilidad_riesgo=(isset($valores_probabilidad_riesgo['REG_INDICE_RIESGO'])?$valores_probabilidad_riesgo['REG_INDICE_RIESGO']:'');
        if($probabilidad_riesgo){
              $probabilidad_riesgo=$probabilidad_riesgo*100;
              $nivel_probabilidad='';
              if($probabilidad_riesgo>0 && $probabilidad_riesgo<5){
                    $nivel_probabilidad = "Bajo";
                    $color='649813';
                }elseif($probabilidad_riesgo>=5 && $probabilidad_riesgo<15){
                    $nivel_probabilidad = "Medio bajo";
                    $color='9de00e';
                }if($probabilidad_riesgo>=15 && $probabilidad_riesgo<20){
                    $nivel_probabilidad = "Medio";
                    $color='e0d90e';
                }if($probabilidad_riesgo>=20 && $probabilidad_riesgo<30){
                    $nivel_probabilidad = "Medio alto";
                    $color='e0580e';
                }if($probabilidad_riesgo>=30){
                    $nivel_probabilidad = "Alto";
                    $color='c12418';
                }
                $probabilidad_riesgo=number_format($probabilidad_riesgo,2);
                $descripcion = "NIVELES DE RIESGO";
                $descripcion .= "<br>Nivel Bajo 0-5%";
                $descripcion .= "<br>Nivel Medio Bajo 5-15%";
                $descripcion .= "<br>Nivel Medio 15-20%";
                $descripcion .= "<br>Nivel Medio Alto 20-30%";
                $descripcion .= "<br>Nivel Alto Superior a 30%";
                $ancho_barra = (450*$probabilidad_riesgo)/100;
                
                ?><table align='left' width='800' cellspacing='0' >
                        <tr>
                        <td width='350' onmouseover="return overlib('<? echo $descripcion;?>',FGCOLOR,'#cccccc',WIDTH,190);" onmouseout='return nd();' ><br>Indice de riesgo = <? echo $probabilidad_riesgo;?> %  - <? echo $nivel_probabilidad;?></td>
                        <td width='450'><table width='100%' style='border: 1px solid;' align='center'>
                                        <tr>
                                        <td width='<? echo $ancho_barra;?>' class='sigma centrar' bgcolor='#<? echo $color;?>' style='order: red 5px solid;' > <? echo $probabilidad_riesgo; ?>%
                                        </td>
                                        <td class='porcentajes' width='<? echo (450-$ancho_barra);?>'>
                                        </td>
                                        </tr>
                                        </table>
                           </td>
                        </tr>
                       </table><?
                
                
          }else{
                echo "<br>Probabilidad de riesgo = (La probabilidad de riesgo no pudo ser calculada)";
          }
    }
    
    function consultarCodigoEstudiantePorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_id", $identificacion);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
    
    
    function mostrarListadoProyectos($codigos){
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
        if(is_array($codigos)){
            echo "<br>C&oacute;digos relacionados a la busqueda:";
            echo "<br><br><table align='center' >";
            foreach ($codigos as $codigo) {
                    $variable="pagina=".$this->formulario;
                    $variable.="&opcion=enviarCodigo";
                    $variable.="&action=".$this->formulario;
                    $variable.="&tipoBusqueda=codigo";
                    $variable.="&datoBusqueda=".$codigo['CODIGO'];
                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
?>
                    <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['CODIGO'];?></a></td>
                        <? if (isset($codigo['NOMBRE'])?$codigo['NOMBRE']:''){?>
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['NOMBRE'];?></a></td>
                        <? }?>
                        <td><a href="<? echo $pagina.$variable;?>"><? echo " Proyecto: ".$codigo['COD_PROYECTO']." - ".$codigo['PROYECTO'];?></a></td>
                    </tr>
  <?          }
            echo "</table>";
            
}
    }
    
    function consultarCodigoEstudiantePorNombre($nombre){
        $cadena_nombre='';
        $nombre = explode(" ", strtoupper($nombre));
        $palabras = count($nombre);
        $i=1;
            
        foreach ($nombre as $parte) {
            if($i==1){
                $cadena_nombre="'%".$parte."%'";
            }else{
                
                $cadena_nombre.=" AND est_nombre like '%".$parte."%'";
            }
            $i++;
        }
        
        $nombre=str_replace(" ", "%", $nombre);
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_nombre", $cadena_nombre);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }

        
        /**
     * Funcion para mostrar las deudas de un estudiante
     * @param type $deudasEstudiante 
     */
    function mostrarDeudasEstudiante($deudasEstudiante,$estado){
        
        $html ="<center>";
        $html .="<table class='contenidotabla sigma' width='100%' >";
        $html .="<caption class='sigma centrar'>DEUDAS DEL ESTUDIANTE</caption>";   
        $html .="<tr class='cuadro_plano'>";
        $html .="<th class='sigma cuadro_plano centrar' width='15%'>Per&iacute;odo</th>";
        $html .="<th class='sigma cuadro_plano centrar' width='15%'>C&oacute;digo Concepto</th>";
        $html .="<th class='sigma cuadro_plano centrar' width='70%'>Descripci&oacute;n Concepto</th>";
        $html .="</tr>";
        if($deudasEstudiante){
                foreach ($deudasEstudiante as $key => $deuda) {
                    $html .="<tr>";
                    $html .="<td  class='cuadro_plano centrar'>".$deuda['ANIO']."-".$deuda['PERIODO']."</td>";
                    $html .="<td  class='cuadro_plano centrar'>".$deuda['COD_CONCEPTO']."</td>";
                    $html .="<td  class='cuadro_plano'>".$deuda['CONCEPTO']."-".$deuda['MATERIAL']."</td>";
                    $html .="</tr>";
                }
        }
        $html .="<tr><td colspan='3'>&nbsp;</td></tr>";
        $html .="</center>";
        
        echo $html;
        
    }
    
    /**
     * Función para consultar los datos que se encuentran registrados del egresado
     * @param type $codEstudiante
     * @param type $codProyecto
     * @return type
     */
    function consultarDatosGradoEstudiante($codEstudiante,$codProyecto) {
        $datos = array( 'codEstudiante'=>$codEstudiante,
                        'codProyecto'=>$codProyecto);
        $cadena_sql = $this->sql->cadena_sql("consultar_datos_grado_estudiante", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }

    /**
     * Función para mostrar los datos de grado de un egresado
     * @param type $datosGrado
     */
    function mostrarDatosGradoEstudiante($datosGrado){
        $html ="<center>";
        $html .="<table class='contenidotabla sigma' width='100%' >";
        $html .="<caption class='sigma centrar'>DATOS DE GRADO</caption>";   
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='33%'><b>Nro. Acta de sustentaci&oacute;n:</b> ".(isset($datosGrado[0]['EGR_ACTA_SUSTENTACION'])?$datosGrado[0]['EGR_ACTA_SUSTENTACION']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px' ><b>Nota:</b> ".(isset($datosGrado[0]['EGR_NOTA'])?$datosGrado[0]['EGR_NOTA']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='33%'><b>Acta de grado:</b> ".(isset($datosGrado[0]['EGR_ACTA_GRADO'])?$datosGrado[0]['EGR_ACTA_GRADO']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='33%'><b>Menci&oacute;n:</b> ".(isset($datosGrado[0]['MENCION'])?$datosGrado[0]['MENCION']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='33%'><b>Fecha de grado:</b> ".(isset($datosGrado[0]['EGR_FECHA_GRADO'])?$datosGrado[0]['EGR_FECHA_GRADO']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px'><b>Libro:</b> ".(isset($datosGrado[0]['EGR_LIBRO'])?$datosGrado[0]['EGR_LIBRO']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px'><b>Folio:</b> ".(isset($datosGrado[0]['EGR_FOLIO'])?$datosGrado[0]['EGR_FOLIO']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px'><b>Registro diploma:</b> ".(isset($datosGrado[0]['EGR_REG_DIPLOMA'])?$datosGrado[0]['EGR_REG_DIPLOMA']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Titulo obtenido:</b> ".(isset($datosGrado[0]['TITULO_OBTENIDO'])?$datosGrado[0]['TITULO_OBTENIDO']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Rector:</b> ".(isset($datosGrado[0]['RECTOR_GRADO'])?$datosGrado[0]['RECTOR_GRADO']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Secretario Acad&eacute;mico:</b> ".(isset($datosGrado[0]['SECRETARIO_GRADO'])?$datosGrado[0]['SECRETARIO_GRADO']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Trabajo de grado:</b> ".(isset($datosGrado[0]['EGR_TRABAJO_GRADO'])?$datosGrado[0]['EGR_TRABAJO_GRADO']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Director:</b> ".(isset($datosGrado[0]['EGR_DIRECTOR_TRABAJO'])?$datosGrado[0]['EGR_DIRECTOR_TRABAJO']:'')." ".(isset($datosGrado[0]['EGR_DIRECTOR_TRABAJO_2'])?$datosGrado[0]['EGR_DIRECTOR_TRABAJO_2']:'')."</td>";
        $html .="</tr>";
        $html .="</table>";
        $html .="</center>";

        echo $html;
        $this->mostrarDatosContactoEgresado($datosGrado);
    }
    
    /**
     * Función para mostrar los datos de contacto de un egresado
     * @param type $datosGrado
     */
    function mostrarDatosContactoEgresado($datosGrado){
        $html ="<center>";
        $html .="<table class='contenidotabla sigma' width='100%' >";
        $html .="<caption class='sigma centrar'>DATOS DE CONTACTO EGRESADO</caption>";   
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' colspan='3'><b>Direcci&oacute;n:</b> ".(isset($datosGrado[0]['EGR_DIRECCION_CASA'])?$datosGrado[0]['EGR_DIRECCION_CASA']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='50%'><b>Tel&eacute;fono Residencia:</b> ".(isset($datosGrado[0]['EGR_TELEFONO_CASA'])?$datosGrado[0]['EGR_TELEFONO_CASA']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='50%'><b>Celular:</b> ".(isset($datosGrado[0]['EGR_MOVIL'])?$datosGrado[0]['EGR_MOVIL']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px'><b>E-Mail:</b> ".(isset($datosGrado[0]['EGR_EMAIL'])?$datosGrado[0]['EGR_EMAIL']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px' width='10%'><b>Empresa:</b> ".(isset($datosGrado[0]['EGR_EMPRESA'])?$datosGrado[0]['EGR_EMPRESA']:'')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="   <td class='cuadro_plano' style='border:0px'><b>Direcci&oacute;n Empresa:</b> ".(isset($datosGrado[0]['EGR_DIRECCION_EMPRESA'])?$datosGrado[0]['EGR_DIRECCION_EMPRESA']:'')."</td>";
        $html .="   <td class='cuadro_plano' style='border:0px'><b>Tel&eacute;fono Empresa:</b> ".(isset($datosGrado[0]['EGR_TELEFONO_EMPRESA'])?$datosGrado[0]['EGR_TELEFONO_EMPRESA']:'')."</td>";
        $html .="</tr>";
        $html .="</table>";
        $html .="</center><br><br>";
        echo $html;
    }
    
 /**
   * Funcion que muestra tabla con totales y porcentajes de créditos del estudiante. Agregada 08/07/2015
   * @param <array> $datosEstudiante
   * @return <array>
   */
  function porcentajeParametros() {
        $OBEst=$OCEst=$EIEst=$EEEst=$CPEst=$totalCreditosEst=0;
        $planEstudiante=  $this->datosEstudiante['PENSUM'];

        $cadena_sql=$this->sql->cadena_sql("creditosPlan",$planEstudiante);
        $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $totalCreditos= $registroCreditosGeneral[0][0];
        $OB= $registroCreditosGeneral[0][1];
        $OC= $registroCreditosGeneral[0][2];
        $EI= $registroCreditosGeneral[0][3];
        $EE= $registroCreditosGeneral[0][4];
        $CP= $registroCreditosGeneral[0][5];
        $datos=array('codEstudiante'=>  $this->datosEstudiante['CODIGO'],
                     'codProyecto'=>  $this->datosEstudiante['CODIGO_CRA']               
                    );
        $cadena_sql=$this->sql->cadena_sql("espaciosAprobados",$datos);
        $registroEspaciosAprobados = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        $numeroAprobados=count($registroEspaciosAprobados);
        for($i=0;$i<$numeroAprobados;$i++)
        {
            $idEspacio= $registroEspaciosAprobados[$i][0];
            $variables=array($idEspacio, $planEstudiante);

            switch((isset($registroEspaciosAprobados[$i][3])?$registroEspaciosAprobados[$i][3]:''))
            {
                case 1:
                        $OBEst=$OBEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 2:
                        $OCEst=$OCEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 3:
                        $EIEst=$EIEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 4:
                        $EEEst=$EEEst+$registroEspaciosAprobados[$i][2];
                    break;
                
                case 5:
                        $CPEst=$CPEst+$registroEspaciosAprobados[$i][2];
                    break;

                case '':
                        $totalCreditosEst=$totalCreditosEst+0;
                    break;

                 }
            }      
      
        $OBEst=$OBEst+$CPEst;
        $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;

        if($totalCreditos==0){$porcentajeCursado=0;}
        else{$porcentajeCursado=$totalCreditosEst*100/$totalCreditos;}
        if($OB==0){$porcentajeOBCursado=0;}
        else{$porcentajeOBCursado=$OBEst*100/$OB;}
        if($OC==0){$porcentajeOCCursado=0;}
        else{$porcentajeOCCursado=$OCEst*100/$OC;}
        if($EI==0){$porcentajeEICursado=0;}
        else{$porcentajeEICursado=$EIEst*100/$EI;}
        if($EE==0){$porcentajeEECursado=0;}
        else{$porcentajeEECursado=$EEEst*100/$EE;}
        
        $columnas[]=$columna1=15;
        $columnas[]=$columna2=16;
        $columnas[]=$columna3=16;
        $columnas[]=$columna4=16;
        $columnas[]=$columna5=37;
        
        if($totalCreditos>0)
        {
            $vista="
            <div class='fechas' ><b>Cr&eacute;ditos Ac&aacute;demicos</b></div>
            <table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                    <tr>
                          <th class='creditos centrar' width='".$columna1."%'>Clasificaci&oacute;n</th>
                          <th class='creditos centrar' width='".$columna2."%'>Total</th>
                          <th class='creditos centrar' width='".$columna3."%'>Aprobados</th>
                          <th class='creditos centrar' width='".$columna4."%'>Por Aprobar</th>
                          <th class='creditos centrar' width='".$columna5."%'>% Cursado</th>
                      </tr></table>";

            $vistaOB=  $this->armarFilasPorcentajes($columnas, 'OB', $OB, $OBEst, $porcentajeOBCursado,'5471ac');
            $vistaOC=  $this->armarFilasPorcentajes($columnas, 'OC', $OC, $OCEst, $porcentajeOCCursado,'6b8fd4');
            $vistaEI=  $this->armarFilasPorcentajes($columnas, 'EI', $EI, $EIEst, $porcentajeEICursado,'238387');
            $vistaEE=  $this->armarFilasPorcentajes($columnas, 'EE', $EE, $EEEst, $porcentajeEECursado,'61b7bc');
            $vistaTotal=  $this->armarFilasPorcentajes($columnas, 'Total', $totalCreditos, $totalCreditosEst, $porcentajeCursado,'b1232d');
        }
        else {
            $vista="
            <table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='6'>El Proyecto Curricular no ha definido los rangos de cr&eacute;ditos<br>para el plan de estudios
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna1."%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna2."%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna3."%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna4."%'>Por Aprobar
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='".$columna5."%'>% Cursado
                      </td>
                   </tr>
                   </table>";

            $vistaOB="<table align='center' width='100%' cellspacing='0' cellpadding='2' >
                                <tr>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna1."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna2."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna3."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='".$columna4."%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar' bgcolor='#fffcea' width='".$columna5."%'> 0%
                                </td>
                                </tr>
                           </table>";
                             }

        return array($vista, $vistaOB, $vistaOC, $vistaEI, $vistaEE, $vistaTotal);

    }    
    
    /**
     * Presenta la tabla de procentajes de espacios cursados para estudiantes de creditos. Agregada 08/07/2015
     * @param type $encabezado 
     */ 
    function mostrarTablaPorcentajes() {
        $this->clasificaciones=$this->consultarClasificaciones(); 
        $codEstudiante=$this->usuario;
        list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros();
         ?>
               <div class="tablet">
                   <div class='tablaCreditos'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    echo $valor5;
                                    echo $valor6;
                                    ?>
                            <?$this->mostrar_convenciones_clasificacion();?>
                   </div>
               </div> <?       
    }
    
    /**
     *Funcion que muestra las convenciones de los espacios de los estudiantes de créditos. Agregada 08/07/2015
     */
     function mostrar_convenciones_clasificacion(){
    ?>

    <div class="contenedor_abreviaturas">
        <div class="tablaClasificaciones" align="left">
            <div class="observaciones" ><b>CONVENCIONES</b></div>
                <div>
                    <div class="abreviatura"><b>Abreviatura</b></div>
                    <div class="nombre"><b>Nombre</b></div>
                </div>
                <?
                if(is_array($this->clasificaciones)){
                foreach ($this->clasificaciones as $clasificacion)
                {
                ?>
                <div>
                    <div class="abreviatura" style="font-size:11px;text-align: center;"><?echo $clasificacion['ABREV_CLASIF']?></div>
                    <div style="font-size:11px;text-align: center;"><?echo $clasificacion['NOMBRE_CLASIF']?></div>
                </div>
                <?
                }}
                ?>
        </div>
    </div>
            <?
    }//fin funcion mostrar_convenciones_clasificacion    
    
    /*
     * Consulta las clasificaciones de espacios académicos de creditos. Agregada 08/07/2015
     */
    function consultarClasificaciones() {
        $cadena_sql=$this->sql->cadena_sql("clasificacion",'');
        $resultado_clasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado_clasificacion;
    }
    
    /*
     * Funcion que arma cada fila donde se muestra el porcentaje de creditos por clasificacion. Agregada 08/07/2015
     * 
     */
    function armarFilasPorcentajes($columnas,$tipoEspacios,$porcentajePlan,$porcentajeEst,$porcentajeCursado,$color) {
        $columna1=$columnas[0];
        $columna2=$columnas[1];
        $columna3=$columnas[2];
        $columna4=$columnas[3];
        $columna5=$columnas[4];
        
            $vista="<table class='contenidotablaCreditos' align='center' width='100%' cellspacing='0' >
                   <tr>
                      <td class='centrar' width='".$columna1."%'>".$tipoEspacios."</td>
                      <td class='centrar' width='".$columna2."%'>".$porcentajePlan."</td>
                      <td class='centrar' width='".$columna3."%'>".$porcentajeEst."</td>
                      <td class='centrar' width='".$columna4."%'>".$Faltan=$porcentajePlan-$porcentajeEst."</td>
                      <td class='centrar' width='".$columna5."%'>";
            if($porcentajeCursado==0)
            {
                $vista.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='porcentajes' colspan='2'> 0%</td>
                       </table>";
                $porcentajeEst=0;
            }else if($porcentajeCursado>=100)
                {
                    $vista.="
                           <table align='center' width='100%' cellspacing='0'>
                                <td width='100%' class='sigma centrar' colspan='2' bgcolor='#".$color."'> ".round($porcentajeCursado,1)."%</td>
                           </table>";
            }else if($porcentajeCursado>0 AND $porcentajeCursado<100)
                {
                    $vista.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeCursado."%' class='sigma centrar' bgcolor='#".$color."'> ".round($porcentajeCursado,1)."%</td>
                           <td class='porcentajes' width='".$Total=100-$porcentajeCursado."%'></td>
                           </table>";
            }
            $vista.="</td>
                        </tr></table>";
            return $vista;
        
    }
    
    function consultarEgresado() {
        if($this->nivel==121){
                if($this->identificacion){
                    $codEstudiante = $this->consultarCodigoEgresadoPorIdentificacion($this->identificacion);
                    if(is_array($codEstudiante )){
                            $this->mostrarListadoProyectos($codEstudiante);
                        }elseif(!$codEstudiante){
                            echo "No existen registros de egresado para esta identificación ";
                        }elseif($codEstudiante){
                            
                            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                            $variable="pagina=".$this->formulario;
                            $variable.="&opcion=enviarCodigo";
                            $variable.="&action=".$this->formulario;
                            $variable.="&tipoBusqueda=codigo";
                            $variable.="&datoBusqueda=".$codEstudiante;
                            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                            echo "<script>location.replace('".$pagina.$variable."')</script>";                                
                            
                        }
                }
            }else{
                echo "Perfil no valido";
            }
  }

  function consultarCodigoEgresadoPorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_egresado_por_id", $identificacion);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
}
?>
