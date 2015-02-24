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
    $this->formulario = "admin_consejeriasDocente";
    $this->reglasConsejerias = new reglasConsejerias();
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
        
        
    if($this->nivel!=80){
        $this->mostrarEnlaceRegresar();        
        }
    $this->datosEstudiante=  $this->consultarDatosEstudiante((isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:''));
    //$this->datosDocente=  $this->buscarDatosDocente($codDocente);


    if (is_array($this->datosEstudiante)) {
          $this->mostrarDatosEstudiante();
          $reglamentoEstudiante = $this->consultarReglamentoEstudiante($this->datosEstudiante['CODIGO']); 
          if($this->datosEstudiante['NIVEL']=='PREGRADO'){
                $this->mostrarReglamentoEstudiante($reglamentoEstudiante,$this->datosEstudiante['ESTADO']);
          }
          $this->espaciosCursados=  $this->buscarNotasDefinitivas();
          $notaAprobatoria=$this->consultarNotaAprobatoria();
          $this->espaciosAprobados=  $this->buscarEspaciosAprobados($notaAprobatoria,$this->espaciosCursados);
          $this->espaciosReprobados=  $this->buscarEspaciosReprobados($notaAprobatoria,$this->espaciosCursados);
          $espaciosCursadosSinAprobar=$this->buscarEspaciosCursadosSinAprobar();
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
          $this->mostrarEnacabezadoNotasDefinitivas();
          $this->mostrarNotasDefinitivas($this->espaciosCursados);
?>


<?
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
                        <td style="border:0px" rowspan="10" colspan="6">


                          <?
                          //ruta de la foto para validar si existe
                          $fotoLocal=$this->configuracion['raiz_appserv'].'/est_fotos/'.$this->datosEstudiante['CODIGO'].'.jpg';
                          //ruta de la foto para presentarla
                          $fotoWeb=$this->configuracion['host'].'/appserv/est_fotos/'.$this->datosEstudiante['CODIGO'].'.jpg'; 
                                                                                                               
                          $archivo=  @fopen($fotoLocal,"r");

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
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['PENSUM']; ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Tel&eacute;fono: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo (isset($this->datosEstudiante['TELEFONO'])?$this->datosEstudiante['TELEFONO']:'') ?></td>
                </tr>
                <tr>
                  <td class='cuadro_plano' style="border:0px">E-Mail: </td>
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['EMAIL'] ?></td>
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
                  <td class='cuadro_plano' style="border:0px"><? echo $this->datosEstudiante['PROMEDIO'] ?></td>
                </tr>
                <? if($this->datosEstudiante['NIVEL']=='PREGRADO'){?>
                <tr>
                  <td class='cuadro_plano' style="border:0px">Acuerdo: </td>
                  <td class='cuadro_plano' style="border:0px"><? if(isset($this->datosEstudiante['ACUERDO'])){echo substr($this->datosEstudiante['ACUERDO'], -3)." de ".substr($this->datosEstudiante['ACUERDO'],0,4);}else{} ?></td>
                </tr>
                 <? } ?>
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
                    //echo '<B>PERDIDA</B><br>';
                }else
                    {
                    //echo 'pasada<br>';                    
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
              <th class='sigma cuadro_plano centrar'>Acumulado</th>


            </tr>
          </thead>
    <?
        for ($i = 0; $i < count($resultado_grupo); $i++) {


          //busca porcentajes notas parciales del estudiante
          $cadena_sql = $this->sql->cadena_sql("consultarPorcentajeNotasParciales", $resultado_grupo[$i], $this->datosEstudiante['CODIGO']);
          $resultado_porcentaje = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

          //busca notas parciales del estudiante
          
           $variablestotales = array($resultado_grupo[$i][0], 
                                    $resultado_grupo[$i][1], 
                                    $this->datosEstudiante['CODIGO'], 
                                    $resultado_grupo[$i][4], 
                                    $resultado_grupo[$i][5]);
          
          $cadena_sql = $this->sql->cadena_sql("consultarNotasParciales", $variablestotales);
          $resultado_notas = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    ?>

          <tr>
            <td class='cuadro_plano centrar'><? echo $resultado_grupo[$i]['CODIGO_ESPACIO'] ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_grupo[$i]['NOMBRE_ESPACIO'] ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_porcentaje[0]['DOCENTE_APELLIDO'] . " " . $resultado_porcentaje[0]['DOCENTE_NOMBRE'] ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_grupo[$i]['NRO_GRUPO'] ?></td>
            <td class='cuadro_plano centrar'><? echo $resultado_notas[0]['FALLAS'] ?></td>


            <?
            //arreglo con los nombres de las claves del arreglo $resultado_porcentaje y $resultado_notas
            $notas=array('NOTA1','NOTA2','NOTA3','NOTA4','NOTA5','NOTA6','NOTA_LABORATORIO','NOTA_EXAMEN');                       

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
                  <caption class="sigma centrar"><? echo "Horario de Clases"; ?></caption>

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
          $variables[0][5] = $resultado_grupos[$j][5];  //nombre del espacio
          $variables[0][6] = $resultado_grupos[$j][6];  //codigo del estudiante
          $variables[0][7] = $resultado_grupos[$j][7];  //plan de estudios del estudiante
          $variables[0][8] = (isset($resultado_grupos[$j][8])?$resultado_grupos[$j][8]:'');  //nombre1 del estudiante
          $variables[0][9] = (isset($resultado_grupos[$j][9])?$resultado_grupos[$j][9]:'');  //creditos
          $variables[0][10] = (isset($resultado_grupos[$j][10])?$resultado_grupos[$j][10]:'');  //clasificacion
          $variables[0][11] = $this->periodo[0][0];  //clasificacion
          $variables[0][12] = $this->periodo[0][1];  //clasificacion
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
            <td class='cuadro_plano centrar'><? echo (isset($resultado_grupos[$j][9])?$resultado_grupos[$j][9]:''); ?></td>
            <td class='cuadro_plano centrar'><? echo (isset($resultado_grupos[$j][10])?$resultado_grupos[$j][10]:''); ?></td>
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
        foreach ($resultado_notas as $nota) {
            $niveles[]=(isset($nota['NIVEL'])?$nota['NIVEL']:'');
        }
        
        sort($niveles);
        $niveles=  array_unique($niveles);
        
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
        exit;
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
        $html ="<center><a href='#' id='mostrar'><table  width='100%' border='0' align='center' cellpadding='5 px' cellspacing='1px'>";
        $html .="<caption class='sigma centrar'>VER HISTORICO DE REGLAMENTO DEL ESTUDIANTE</caption>";   
        $html .="</table></a> ";
        $html .="<div id='caja'>";
        $html .="<table class='contenidotabla sigma' width='100%' >";
        $html .="<tr class='cuadro_plano'>";
        $html .="<th class='sigma cuadro_plano centrar' width='20%'>Per&iacute;odo</th>";
        $html .="<th class='sigma cuadro_plano centrar' width='20%'>Causales por per&iacute;odo</th>";
        $html .="<th class='sigma cuadro_plano centrar' width='20%'>Promedio Acumulado por periodo</th>";
        if($estado=='U' || $estado=='Z' ){
            $html .="<th class='sigma cuadro_plano centrar' width='20%'>Causal perdida calidad de estudiante</th>";
        }
        $html .="</tr>";
        foreach ($reglamentoEstudiante as $key => $reglamento) {
            $causales = $this->formatearCausales($reglamento['REG_MOTIVO']);
            $promedio = (isset($reglamento['REG_PROMEDIO'])?$reglamento['REG_PROMEDIO']:'');
            if($promedio ){
                $promedio=($promedio/100);
            }
            $html .="<tr>";
            $html .="<td  class='cuadro_plano centrar'>".$reglamento['REG_ANO']."-".$reglamento['REG_PER']."</td>";
            $html .="<td  class='cuadro_plano'>".$causales."</td>";
            $html .="<td  class='cuadro_plano centrar'>".number_format($promedio,2)."</td>";
            if($estado=='U' || $estado=='Z' ){
                $causales_exclusion = $this->formatearCausales((isset($reglamento['REG_CAUSAL_EXCLUSION'])?$reglamento['REG_CAUSAL_EXCLUSION']:''));
                $html .="<td  class='cuadro_plano'>".$causales_exclusion."</td>";
            }
            $html .="</tr>";
        }
       
        $html .="<table class='contenidotabla sigma' width='50%' border='0' align='center' cellpadding='5 px' cellspacing='1px' >";
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
        $html .="<tr>";
        $html .="<td  class='cuadro_plano centrar'>4</td>";
        $html .="<td  class='cuadro_plano'>Acumulaci&oacute;n de pruebas acad&eacute;micas</td>";
        $html .="</tr>";
        $html .="</table><br>";    
        $html .="</div></center>";
        
        echo $html;
        
    }

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
}
?>
