
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

//@ Esta clase presenta el horario registrado para el estudiante y los enlaces para realizar inscripcion por busqeda
//@ Tambien se puede realizar cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_adminMenuFlotanteInscripciones extends funcionGeneral {

  private $configuracion;
  private $ano;
  private $periodo;
  private $parametrosHoras;
  private $datosEstudiante;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    $this->configuracion = $configuracion;
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/validar_fechas.class.php");
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    //$this->tema = $tema;
    $this->sql = new sql_adminMenuFlotanteInscripciones();
    $this->log_us = new log();
    $this->parametrosHoras=array();
    $this->formulario = "admin_menuFlotanteInscripciones";
    


    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
    
    //conexion distribuida 1 conecta a MySUDD de lo contrario conecta a ORACLE
    if ($configuracion['dbdistribuida']==1)
        {
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }
        else
            {
                $this->accesoMyOracle = $this->accesoOracle;
            }
    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
  }

  /**
   * Esta funcion presenta el horario del estudiante
   * Utiliza los metodos datosEstudiante, validar_fechas_estudiante_coordinador, validarEstadoEstudiante, registroAgil,
   *  horarioEstudianteConsulta, calcularCreditos, adicionar, finTabla
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codProyectoEstudiante, planEstudioEstudiante, nombreProyecto, codEstudiante, xajax, xajax_file)
   */
  function mostrarMenuFlotante() {
      if (isset($_REQUEST['retorno'])&&$_REQUEST['retorno']=='admin_buscarEspacioEEInscripcionesEstudiante')
      {
          $paginaEnlace='admin_buscarEspacioEEInscripcionesEstudiante';
      }else 
            {
                $paginaEnlace='admin_buscarEspacioInscripcionesEstudiante';
            }
    switch ($_REQUEST['pagina'])
    {
        case 'admin_inicioInscripcionEstudiante':
            $nivel=0;
            break;
        case 'admin_consultarInscripcionesEstudiante':
            $nivel=1;
            break;
        case 'admin_buscarEspacioInscripcionesEstudiante':
            $nivel=2;
            break;
        case 'admin_buscarEspacioEEInscripcionesEstudiante':
            $nivel=2;
            break;
        case 'admin_buscarGruposProyectosEstudiante':
            $nivel=3;
            break;
    }
    $codEstudiante = $this->usuario;
    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
//    var_dump($this->datosEstudiante);exit;
    
    $nombreNivel=array(0=>array("nombre"=>"Inicio","enlace"=>"pagina=admin_inicioInscripcionEstudiante&opcion=creditos"),
                        1=>array("nombre"=>">&nbsp;Horario","enlace"=>"pagina=admin_consultarInscripcionesEstudiante&opcion=mostrarConsulta"),
                        2=>array("nombre"=>">&nbsp;Espacios","enlace"=>"pagina=".$paginaEnlace."&opcion=espacios&codProyectoEstudiante=".$this->datosEstudiante[0]['COD_CARRERA']."&planEstudioEstudiante=".$this->datosEstudiante[0]['PLAN_ESTUDIO']."&codEstudiante=".$this->datosEstudiante[0]['CODIGO']."&tipoEstudiante=".$this->datosEstudiante[0]['TIPO_ESTUDIANTE']."&estado_est=".$this->datosEstudiante[0]['ESTADO']."&creditosInscritos=".(isset($_REQUEST['creditosInscritos'])?$_REQUEST['creditosInscritos']:'')."&numeroSemestres=".(isset($_REQUEST['numeroSemestres'])?$_REQUEST['numeroSemestres']:'')),
                        3=>array("nombre"=>">&nbsp;Grupos","enlace"=>""));
    if (isset($this->datosEstudiante)&&!is_null($this->datosEstudiante[0]['CODIGO']))
    {
        
    }    ?>
    <header>
        <div class="top">
            <ol>
                <li class="list_izq"><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/home2.png" ?>" border="0" width="18" height="18">
                <?
                for($i=0;$i<=$nivel;$i++)
                {
                    if($nivel==$i)
                    {
                        ?><li class="list_izq_inactivo"><?echo $nombreNivel[$i]['nombre'];?></li><?
                    }else
                        {
                            ?><li class="list_izq" onclick="window.location ='<?$variable = $this->cripto->codificar_url($nombreNivel[$i]['enlace'], $this->configuracion);echo $pagina.$variable;?>'"><?echo $nombreNivel[$i]['nombre'];?></li><?
                        }
                }
                ?>
            </ol>
            <?if($nivel>0)
            {?>
            <li class="list_der" onclick="window.location ='<?$variable = $this->cripto->codificar_url($nombreNivel[$i-2]['enlace'], $this->configuracion);echo $pagina.$variable;?>'"><< Regresar</li>
            <?}?>
        </div>
        
    </header>
    <br>
    <?

    }
  /**
   * Esta funcion presenta el horario del estudiante
   * Utiliza los metodos datosEstudiante, validar_fechas_estudiante_coordinador, validarEstadoEstudiante, registroAgil,
   *  horarioEstudianteConsulta, calcularCreditos, adicionar, finTabla
   * @param <array> $this->configuracion
   * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codProyectoEstudiante, planEstudioEstudiante, nombreProyecto, codEstudiante, xajax, xajax_file)
   */
  function mostrarMenuFlotanteCambiar() {
    switch ($_REQUEST['pagina'])
    {
        case 'admin_inicioInscripcionEstudiante':
            $nivel=0;
            break;
        case 'admin_consultarInscripcionesEstudiante':
            $nivel=1;
            break;
        case 'admin_buscarGruposProyectosEstudiante':
            $nivel=2;
            break;
    }
    $codEstudiante = $this->usuario;
    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
    
    $nombreNivel=array(0=>array("nombre"=>"Inicio","enlace"=>"pagina=admin_inicioInscripcionEstudiante&opcion=creditos"),
                        1=>array("nombre"=>">&nbsp;Horario","enlace"=>"pagina=admin_consultarInscripcionesEstudiante&opcion=mostrarConsulta"),
                        2=>array("nombre"=>">&nbsp;Grupos","enlace"=>""));
    if (isset($this->datosEstudiante)&&!is_null($this->datosEstudiante[0]['CODIGO']))
    {
        
    }    ?>
    <header>
        <div class="top">
            <ol>
                <li class="list_izq"><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/home2.png" ?>" border="0" width="18" height="18">
                <?
                for($i=0;$i<=$nivel;$i++)
                {
                    if($nivel==$i)
                    {
                        ?><li class="list_izq_inactivo"><?echo $nombreNivel[$i]['nombre'];?></li><?
                    }else
                        {
                            ?><li class="list_izq" onclick="window.location ='<?$variable = $this->cripto->codificar_url($nombreNivel[$i]['enlace'], $this->configuracion);echo $pagina.$variable;?>'"><?echo $nombreNivel[$i]['nombre'];?></li><?
                        }
                }
                ?>
            </ol>
            <?if($nivel>0)
            {?>
            <li class="list_der" onclick="window.location ='<?$variable = $this->cripto->codificar_url($nombreNivel[$i-2]['enlace'], $this->configuracion);echo $pagina.$variable;?>'"><< Regresar</li>
            <?}?>
        </div>
        
    </header>
    <br>
    <?

    }
    
    function consultarDatosEstudiante($codEstudiante){        
    $variables =$codEstudiante;   
    $cadena_sql=$this->sql->cadena_sql("datos_estudiante", $variables);
    return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

}
  
  

}
?>
