<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

/*
 *@ Esta clase presenta los espacios academicos que se pueden inscribir a un estudiante de Creditos.
 */

class funcion_adminBuscarEspacioCreditosEstudianteSop extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $espaciosPlan;
  private $parametrosPlan;
  private $espaciosInscritos;
  private $espaciosAprobados;
  private $espaciosCursados; 
  private $espaciosReprobados;
  private $espaciosPlanMenosAprobados;
  

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");

    $this->configuracion = $configuracion;
    $this->cripto = new encriptar();
    $this->procedimientos=new procedimientos();
    $this->tema = $tema;
    $this->sql = $sql;
    $this->datosEstudiante=array('codProyectoEstudiante'=>$_REQUEST['codProyectoEstudiante'],
                                 'planEstudioEstudiante'=>$_REQUEST['planEstudioEstudiante'],
                                 'codEstudiante'=>$_REQUEST['codEstudiante'],
                                 'creditosInscritos'=>$_REQUEST['creditosInscritos'],
                                 'estado_est'=>$_REQUEST['estado_est'],
                                 'tipoEstudiante'=>$_REQUEST['tipoEstudiante']);

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");

    //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
    //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
    if($configuracion["dbdistribuida"]==1){
        $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
    }else{
        $this->accesoMyOracle = $this->accesoOracle;
    }
    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Datos de sesion
    $this->formulario = "admin_buscarGruposEstudianteCreditosSop";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", ''); 
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
    ?>
    <head>
        <script language="JavaScript">
            var message = "";
            function clickIE(){
                if (document.all){
                    (message);
                    return false;
                }
            }
            function clickNS(e){
                if (document.layers || (document.getElementById && !document.all)){
                    if (e.which == 2 || e.which == 3){
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers){
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            } else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }
            document.oncontextmenu = new Function("return false")
        </script>
    </head>
    <?
  }

    /**
     * Funciom que presenta los espacios que puede adicionar al estudiante
     * Utiliza los metodos consultaEspaciosPermitidos, mostrarEspacios, mensajeNoEspacios, mensajePruebaAcademica, retorno
     */
    function consultarEspaciosPermitidos() {
    $_REQUEST['ano']=$this->ano;
    $_REQUEST['periodo']=$this->periodo;
    $resultado_planEstudio=$this->consultaEspaciosPermitidos();
   
    ?><table width="70%" align="center" border="0" >
        <tr class="bloquelateralcuerpo">
          <td class="centrar">
            <?
            $this->enlaceHorario();
            ?>
          </td>
        </tr>
      </table>
    <?
      if (is_array($resultado_planEstudio))
        {
          $this->mostrarEspacios($resultado_planEstudio, $_REQUEST);
        }
        else
          {
          $this->mensajeNoEspacios();
          if (trim($_REQUEST['estado_est'])=='B')
                $this->mensajePruebaAcademica();

          }
        $this->retorno($_REQUEST);
    }

    /**
     * Funcion que muestra los espacios academicos que puede registrar el estudiante
     * utiliza los metodos evaluaRangos, consultarEspaciosEquivalentes, valida_requisitos, valida_cancelo_estudiante, parametros_plan,
     * enlaceAdicionar, mensajeSuperaCreditosClasificacion, mensajeSuperaCreditos, mensajeEspacioCancelado
     * @param <array> $listadoEspacios (CODIGO,NOMBRE,NIVEL,CREDITOS,CLASIFICACION)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     * @param <array> $resultado_espacios_estudiante (NOT_ASI_COD, NOTA, NOT_OBS)
     */
    function mostrarEspacios($listadoEspacios, $datosGenerales) {
      ?>
      <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
        <caption class="sigma">
          <center>
            ESPACIOS PERMITIDOS
          </center>
        </caption>
        <tr>
          <td>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <tr >
                <th class="sigma centrar" width="10%"><b>C&oacute;digo Espacio</b></th>
                <th class="sigma centrar" width="40%"><b>Nombre Espacio</b></th>
                <th class="sigma centrar" width="8%"><b>Clasificaci&oacute;n</b></th>
                <th class="sigma centrar" width="8%"><b>Nro. Cr&eacute;ditos</b></th>
                <th class="sigma centrar" width="15%"><b>Adicionar</b></th>
              </tr>
              <?
                list($OBEst, $OCEst, $EIEst, $EEEst, $CPEst, $OB, $OC, $EI, $EE, $CP)=$this->evaluaRangos($datosGenerales);
                $numeroListadoEspacios=count($listadoEspacios);
                $variablesCancelado=array($datosGenerales['codEstudiante'],$this->ano, $this->periodo);
                $resultado_cancelo = $this->valida_cancelo_estudiante($variablesCancelado);
                for ($i = 0; $i < $numeroListadoEspacios; $i++) {
                        
                      if ((isset($listadoEspacios[$i - 1]['NIVEL'])?$listadoEspacios[$i - 1]['NIVEL']:'') != $listadoEspacios[$i]['NIVEL']) {
                      ?>
                        <tr>
                          <td class="sigma_a cuadro_plano centrar" colspan="6"><font size="2"> NIVEL <? echo $listadoEspacios[$i]['NIVEL'] ?></font></td>
                        </tr>
                        <?
                      }

                          $clasificacion = $listadoEspacios[$i]['CLASIFICACION'];
                          $totalOB=$listadoEspacios[$i]['CREDITOS']+$OBEst;
                          $totalOC=$listadoEspacios[$i]['CREDITOS']+$OCEst;
                          $totalEI=$listadoEspacios[$i]['CREDITOS']+$EIEst;
                          $totalEE=$listadoEspacios[$i]['CREDITOS']+$EEEst;
                          $totalCP=$listadoEspacios[$i]['CREDITOS']+$CPEst;
                          $bandClasifica=0;
                          switch($listadoEspacios[$i]['CLASIFICACION']){
                                    case 'OB':
                                        if ($OB<$totalOB) {
                                            $bandClasifica=1;
                                        }
                                        break;

                                    case 'OC':
                                        if ($OC<$totalOC) {
                                            $bandClasifica=1;
                                        }
                                        break;

                                    case 'EI':
                                        if ($EI<$totalEI) {
                                            $bandClasifica=1;
                                        }
                                        break;

                                    case 'EE':
                                        if ($EE<$totalEE) {
                                            $bandClasifica=1;
                                        }
                                        break;
                                    case 'CP':
                                        if ($CP<$totalCP) {
                                            $bandClasifica=1;
                                        }
                                        break;
                                }

                      $band_requisitos = '0';

                      $numeroCreditosRestantes=($this->parametrosPlan[0]['parametro_maxCreditosNivel']-$_REQUEST['creditosInscritos']);

                       if ($band_requisitos == '0' ) {

                      ?>
                      <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td class='cuadro_plano centrar'><? echo $listadoEspacios[$i]['CODIGO'] ?></td>
                        <td class='cuadro_plano '><? echo $listadoEspacios[$i]['NOMBRE'] ?></td>
                        <td class='cuadro_plano centrar'><? echo $clasificacion ?></td>
                        <td class='cuadro_plano centrar'><font color="<?  if($listadoEspacios[$i]['CREDITOS']<=$numeroCreditosRestantes && $bandClasifica==0) echo "#3BAF29"; else echo "#F90101"; ?>"><? echo $listadoEspacios[$i]['CREDITOS']; ?></font></td>
                        <td class='cuadro_plano centrar'>
                        <?
                        if($listadoEspacios[$i]['CREDITOS'] <= $numeroCreditosRestantes && !is_array($resultado_cancelo))
                        {
                            if($bandClasifica==0)
                            {
                                $this->enlaceAdicionar($listadoEspacios[$i], $datosGenerales) ;
                            }else
                                {
                                    $this->mensajeSuperaCreditosClasificacion();
                                }
                        }elseif($listadoEspacios[$i]['CREDITOS'] > $numeroCreditosRestantes)
                            {
                                $this->mensajeSuperaCreditos($this->parametrosPlan[0]['parametro_maxCreditosNivel']);
                            }elseif(is_array($resultado_cancelo))
                                {
                                if($bandClasifica==0)
                                {
                                    $cancelado=0;
                                    foreach ($resultado_cancelo as $value)
                                    {
                                        if($value['horario_idEspacio']==$listadoEspacios[$i]['CODIGO'])
                                        {
                                            $cancelado=1;
                                        }else
                                            {
                                            }
                                    }
                                    if($cancelado==1)
                                    {
                                        $this->mensajeEspacioCancelado();
                                    }else
                                        {
                                           $this->enlaceAdicionar($listadoEspacios[$i], $datosGenerales) ;                                        
                                        }
                                }else
                                    {
                                        $this->mensajeSuperaCreditosClasificacion();
                                    }
                                }
                        ?>
                        </td>
                      </tr>
                      <? //
                    }//fin if band_requisitos
                }//fin for que recorre el listado de espacios academicos
          ?>
            </table>
          </td>
        </tr>
      </table>
      <?
    }

    /**
     * Funcion que genera el enlace para regresar al horario del estudiante
     * Utiliza el metodo variablesRetorno
     */
    function enlaceHorario() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable=$this->variablesRetorno();
        ?>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                '<?
                    echo $pagina . $variable;
                ?>'
            "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/vcalendar.png" width="35" height="35" border="0"><br>
          <b>Horario Estudiante</b>
            </button>
        
        <?
    }

    /**
     * Funcion que genera las variables para enlaces de retorno
     * @return <type>
     */
    function variablesRetorno() {

        $variable = "pagina=admin_consultarInscripcionCreditosEstudianteSop";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        return $variable;
}

    /**
     * Funcion que muestra enlace para regresar a la pagina de consulta de inscripciones del estudainte
     * Utiliza el metodo variablesRetorno
     */
    function retorno() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variablesPag=$this->variablesRetorno();
        ?>
        <table class="cuadro_color centrar" width="100%">
          <tr class="centrar">
            <td colspan="3">
                 <button class="botonEnlacePreinscripcion" onclick="window.location = 
                    '<?
                        echo $pagina . $variablesPag;
                    ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="25" height="25" border="0"><br>
                <font size="2"><b>Regresar</b></font>
                </button>
            </td>
          </tr>
        </table>
<?
      }

    /**
     * Funcion que muestra enlace para adicionar un espacio
     * @param <array> $datosEspacio (CODIGO,NOMBRE,NIVEL,CREDITOS,CLASIFICACION)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,creditosInscritos,estado_est,ano,periodo)
     */
    function enlaceAdicionar($datosEspacio, $datosGenerales) {
        $parametro = "=";
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=".$this->formulario;
                  $variables.="&opcion=validar";
                  $variables.="&action=".$this->formulario;
                  $variables.="&destino=registro_adicionarInscripcionCreditosEstudianteSop";
        
                  $parametros="&codEstudiante=" . $datosGenerales['codEstudiante'];
                  $parametros.="&codProyectoEstudiante=" . $datosGenerales['codProyectoEstudiante'];
                  $parametros.="&planEstudioEstudiante=" . $datosGenerales['planEstudioEstudiante'];
                  $parametros.="&estado_est=" . trim($datosGenerales['estado_est']);
                  $parametros.="&tipoEstudiante=" .  $datosGenerales['tipoEstudiante'];
                  $parametros.="&creditos=" . (isset($datosEspacio['CREDITOS'])?$datosEspacio['CREDITOS']:'');
                  $parametros.="&creditosInscritos=" . $_REQUEST['creditosInscritos'];
                  $parametros.="&codEspacio=" . $datosEspacio['CODIGO'];
                  $parametros.="&nombreEspacio=" . $datosEspacio['NOMBRE'];
                  $parametros.="&parametro=".$parametro;
                  $parametros.="&retorno=admin_buscarEspacioCreditosEstudianteSop";

                  $variable = $variables . $parametros;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                  ?>
                    <button class="botonEnlacePreinscripcion" onclick="window.location = 
                            '<?
                                echo $pagina . $variable;
                            ?>'
                        "><center><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/clean.png" ?>" border="0" width="20" height="20">
                    </button>
                  <?
      }

    /**
     * Funcion que presenta mensaje cunado no hay mensajes para inscribir
     */
    function mensajeNoEspacios() {
          ?>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
              <tr>
                <th class='cuadro_plano centrar' colspan="6">No se encontraron espacios acad&eacute;micos para adicionar.</th>
              </tr>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
            </table>
          <?
      }

    /**
     * Funcion que presenta mensaje cuando el estudiante se encuentra en Prueba academica
     */
    function mensajePruebaAcademica() {
          ?>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
              <tr>
                <th class='cuadro_plano centrar' colspan="6">TENGA EN CUENTA: 'Paragrafo 1: Durante el semestre en que el estudiante se encuentre en prueba acad&eacute;mica, solo podra inscribir y cursar las asignaturas que originaron su situacion de prueba y las demas que haya reprobado.' Lo anterior se encuentra establecido en el ACUERDO No. 07 (Diciembre 16 de 2009) Articulo 1°.</th>
              </tr>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
            </table>
          <?
      }//fin funcion mensajePruebaAcademica


    /**
     * Funcion que presenta mensaje cuando no se puede adicionar el espacio por q supera los creditos permitidos
     */
    function mensajeSuperaCreditos($numMaxCreditos) {

        echo "No puede adicionar, el n&uacute;mero de cr&eacute;ditos inscritos supera los ".$numMaxCreditos ;
        
    }


    /**
     * Funcion que presenta mensaje cuando no se puede adicionar el espacio por q supera los creditos permitidos
     */
    function mensajeSuperaCreditosClasificacion() {

        echo "No puede adicionar, el n&uacute;mero de cr&eacute;ditos inscritos supera los establecidos para esta clasificaci&oacute;n";

    }

    /**
     * Funcion que presenta mensaje cuando no se puede adicionar por q el espacio fue cancelado por el estudiante
     */
    function mensajeEspacioCancelado() {

        echo "No se puede adicionar porque ha sido cancelado";
        
    }



    /**
     * Funcion que permite consultar los espacios permitidos para cursar por el estudiante
     * Utiliza los metodos consultarNotaAprobatoria, consultarEspaciosAprobados, consultarEspaciosInscritos,consultarEspaciosReprobados
     * @param <array> $resultado_espacios_estudiante (NOT_ASI_COD, NOTA, NOT_OBS)
     * @return <array>
     */
    function consultaEspaciosPermitidos() {
                         
          $notaAprobatoria=isset($notaAprobatoria)?$notaAprobatoria:'';
          $notaAprobatoria=$this->consultarNotaAprobatoria();
          $resultado='';
          
          $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "espaciosPlan");
           if($cadena=='')
             {
                $this->espaciosPlan=$this->consultarEspaciosPlan();
                $this->procedimientos->registrarArreglo($this->espaciosPlan,'espaciosPlan');               
             }else
             { 
                $this->espaciosPlan=$this->procedimientos->stringToArray($cadena[0][0]);           
             }
             
          $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "cursados");
            if($cadena=='')
             {
                $this->espaciosCursados=$this->consultarEspaciosCursados();
                $this->procedimientos->registrarArreglo($this->espaciosCursados,'cursados');
             }else
             { 
                $this->espaciosCursados=$this->procedimientos->stringToArray($cadena[0][0]);           
             }
          $this->espaciosInscritos=$this->consultarEspaciosInscritos();
          $this->espaciosAprobados=  $this->buscarEspaciosAprobados($notaAprobatoria);
          $this->espaciosPlanMenosAprobados=$this->buscarEspaciosPlanMenosAprobados();
          if(is_array ($this->espaciosInscritos))
          {
            $this->espaciosPlanMenosAprobadosMenosInscritos=$this->buscarEspaciosPlanMenosAprobadosMenosInscritos();
          }else
          {
              $this->espaciosPlanMenosAprobadosMenosInscritos=$this->espaciosPlanMenosAprobados;
          }
          $this->requisitosPlan=$this->consultarRequisitosPlan();
          $this->espaciosReprobados=$this->buscarEspaciosReprobados($notaAprobatoria);
       
          $espaciosPorCursar=$this->espaciosPorCursar();         
          
          if (trim($this->datosEstudiante['estado_est'])=='B'){
          if(is_array ($this->espaciosReprobados))
          {
            $espaciosPrueba=$this->buscarEspaciosPlanMenosAprobadosMenosInscritosMenosReprobados();          
          } else
          {
              
          }
            if (is_array($espaciosPrueba))
              {
                  foreach ($espaciosPrueba as $value) {
                      foreach ($this->espaciosPlan as $value2)
                      if ($value==$value2['CODIGO'])
                      {$resultado[]=$value2;}
                  }
              }else{}
              
          return $resultado;
                       
          }else{
                     if (is_array($espaciosPorCursar))
                         {
                            foreach ($espaciosPorCursar as $value) {
                                foreach ($this->espaciosPlan as $value2)
                                if ($value==$value2['CODIGO'])
                                {$resultado[]=$value2;}
                            }
                          }else{}
              
          return $resultado;
              
               }
                              
          }

    /**
     * Funcion que buscar los espacios que el estudiante puede cursar en el periodo
     * @return type 
     */
     function espaciosPorCursar(){
    
        if(is_array($this->requisitosPlan))
            {
                $espaciosACursar=$this->evaluarRequisitos();
            }else
                {
                    $espaciosACursar=$this->espaciosPlanMenosAprobadosMenosInscritos;
                }
        return $espaciosACursar;
    }
    
    
    /**
     * Funcioqn que evalua si el estudiante cumple con requisitos para cada espacio academico
     * @return type 
     */ 
    function evaluarRequisitos(){
        $espaciosACursar=array();
        foreach ($this->espaciosPlanMenosAprobadosMenosInscritos as $codigoEspacio)
        {
            $resultadoRequisitos=$this->buscarRequisitosEspacio($codigoEspacio);
            if($resultadoRequisitos=='sinRequisitos')
            {
                $espaciosACursar[]=$codigoEspacio;
            }elseif($resultadoRequisitos=='conRequisitos')
                { 
                }else
                    {}
        }
        return $espaciosACursar;
    }
    
   /**
    * Funcion que busca los requisitos de un espacio dentro del arreglo de requisitos del plan
    * @param type $codigoEspacio
    * @return string 
    */
    function buscarRequisitosEspacio($codigoEspacio){        
        $codigoRequisitos = '';  
        $resultado='';
        foreach ($this->requisitosPlan as $requisito)
        {
            if ($requisito['CODIGO_ESPACIO']==$codigoEspacio)
            {
                $codigoRequisitos[] = $requisito['CODIGO_REQUISITO'];
            }else
                { 
                //si el requisito no es requisito del espacio no lo incluye en el arreglo
                }
        }
        if(is_array($codigoRequisitos))
        {
            $resultado=$this-> verificarAprobacionRequisito($codigoRequisitos);
            if($resultado =='cumpleConRequisitos')
            {
                $retorno= 'sinRequisitos';
            }else
                {
                    $retorno='conRequisitos';
                }
        }else
            {
                $retorno='sinRequisitos';
            }
        return $retorno;
    }
    
    
    /**
     * Funcionq ue verifica si el estudiante ha aprobado un requisito para un espacio academico
     * @param type $codigoRequisitos
     * @return string 
     */
    function verificarAprobacionRequisito($codigoRequisitos){
        $resultado='';
        $requisitoAprobado=array();
        if(is_array($this->espaciosAprobados))
        {
            foreach ($this->espaciosAprobados  as $aprobados)
            {
                foreach ($codigoRequisitos  as $requisito)
                {
                    if($requisito == $aprobados)
                    {
                        $requisitoAprobado[]=$requisito;
                    }else
                        { 
                        }
                }
            }
        }
        if(is_array($requisitoAprobado))
        {
            if(count($requisitoAprobado)==count($codigoRequisitos))
            {
                $resultado='cumpleConRequisitos';
            }else
                {
                    $resultado='noCumpleRequisitos';
                } 
        }else
            {
                $resultado='noCumpleRequisitos';
            }
        return $resultado;
    }
     
    /**
     * Funcion que busca los espacios aprobados por el estudiante entre el arreglo de los cursados.
     * @param type $notaAprobatoria
     * @return string 
     */
    function buscarEspaciosAprobados($notaAprobatoria){
        $aprobados=array();
        if(is_array($this->espaciosCursados))
        {
        
        if (trim($this->datosEstudiante['tipoEstudiante'])=='S')
        {
            foreach ($this->espaciosCursados as $value1)
            {
                if ($value1['NOTA']>=$notaAprobatoria)
                {
                    $aprobados[]=$value1['CODIGO'];
                }
            }
        }else
            {
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
    function buscarEspaciosReprobados($nota) {
          $reprobados=isset($reprobados)?$reprobados:'';
          if (is_array($this->espaciosCursados)){
              foreach ($this->espaciosCursados as $key => $value) {
                 if ($value['NOTA']<$nota ){
                    $reprobados[]=$value['CODIGO'];
                 }
              }
              return $reprobados;
          }
          else
            {
              return 0;
            }
      }
      
    /**
     * Funcion que resta los espacios Inscritos del arreglo de los del plan menos los aprobados
     * @return type 
     */
      function buscarEspaciosPlanMenosAprobadosMenosInscritos(){
        $resultado_espacios=array_diff($this->espaciosPlanMenosAprobados, $this->espaciosInscritos);
       return $resultado_espacios;      
    }

    /**
     * Funcion que obtiene los espacios reprobados del arreglo de los del plan menos los aprobados menos los inscritos
     * @return type 
     */
    function buscarEspaciosPlanMenosAprobadosMenosInscritosMenosReprobados(){
        $resultado_espacios=array_intersect($this->espaciosPlanMenosAprobadosMenosInscritos, $this->espaciosReprobados);
        return $resultado_espacios;      
    }

    /**
     * Funcion que resta a los espacios del plan de estudios del estudiante los que ya ha aprobado
     * @return type 
     */
    function buscarEspaciosPlanMenosAprobados(){
        foreach ($this->espaciosPlan as $espacio)
            {
                $espacioPlan[]=$espacio['CODIGO'];
            }
            
        $resultado_espacios=array_diff($espacioPlan, $this->espaciosAprobados);
       return $resultado_espacios;      
    }

    /**
     * Funcion que permite consultar la nota de un espacio de un estudiante
     * @return <string/0>
     */
    function consultarCursoAprobado($curso,$espacios_estudiante) {
          if (is_array($espacios_estudiante)){
              $resultado[0][0]=0;
              foreach ($espacios_estudiante as $key => $value) {
                  
                 if ($value['CODIGO']==$curso[0] && $value['NOTA']>$resultado[0][0]){
                    $resultado[0][0] =$value['NOTA'];
                    $resultado[0][1] =$value['CODIGO'];
                 }
              }
              return $resultado;
          }
          else
            {
              return 0;
            }
      }

    /**
     * Funcion que consulta los creditos de acuerdo a la clasificacion y los rangos
     * Utiliza los metodos consultarNotaAprobatoria
     * @param <array>$datosEstudiante()
     * @param <array> $resultado_espacios_estudiante (NOT_ASI_COD, NOTA, NOT_OBS)

     */
     function evaluaRangos($datosEstudiante) {         
        $planEstudiante=$datosEstudiante['planEstudioEstudiante']; 
        $this->parametrosPlan=$this->parametros_plan($planEstudiante);
     
        $totalCreditos=$this->parametrosPlan[0]['parametro_creditosPlan'];
        $OB= $this->parametrosPlan[0]['parametros_OB'];
        $OC= $this->parametrosPlan[0]['parametros_OC'];
        $EI= $this->parametrosPlan[0]['parametros_EI'];
        $EE= $this->parametrosPlan[0]['parametros_EE'];
        $CP= $this->parametrosPlan[0]['parametros_CP'];
        if(is_array($this->espaciosAprobados))
        {
            $OBEst=0;
            $OCEst=0;
            $EIEst=0;
            $EEEst=0;
            $CPEst=0;
            $totalCreditosEst=0;
            $numeroAprobados=count($this->espaciosAprobados);
            for($i=0;$i<$numeroAprobados; $i++)
            {
                $idEspacio=(isset($this->espaciosAprobados[$i])?$this->espaciosAprobados[$i]:'');
                foreach ($this->espaciosCursados as $value) {
                if ($value['CODIGO']==$idEspacio){
                    $registroCreditosEspacio=$value;
                    }
                }
                if (isset($registroCreditosEspacio['CLASIFICACION']))
                {
                switch($registroCreditosEspacio['CLASIFICACION'])
                {
                    case '1':
                        $OBEst=$OBEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case '2':
                        $OCEst=$OCEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case '3':
                        $EIEst=$EIEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case '4':
                        $EEEst=$EEEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case '5':
                        $CPEst=$CPEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                }
            }
        }
        }
        if(is_array($this->espaciosInscritos)){
        $numeroInscritos=count($this->espaciosInscritos);
        for($i=0;$i<$numeroInscritos;$i++) {
            $codEspacio=(isset($this->espaciosInscritos[$i])?$this->espaciosInscritos[$i]:'');
                foreach ($this->espaciosPlan as $value) {
                if ($value['CODIGO']==$codEspacio){
                    $registroCreditosEspacio=$value;
                    }else
                        {
                            $registroCreditosEspacio='';
                    }
                }
            if(is_array($registroCreditosEspacio)) {
                switch($registroCreditosEspacio['CLASIFICACION'])
                {
                    case 'OB':
                        $OBEst=$OBEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case 'OC':
                        $OCEst=$OCEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case 'EI':
                        $EIEst=$EIEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case 'EE':
                        $EEEst=$EEEst+$registroCreditosEspacio['CREDITOS'];
                        break;

                    case 'CP':
                        $CPEst=$CPEst+$registroCreditosEspacio['CREDITOS'];
                        break;
                }
            }
        }
        }
        return array($OBEst, $OCEst, $EIEst, $EEEst, $CPEst, $OB, $OC, $EI, $EE, $CP);

    }//fin funcion evaluaRangos

    /**
     * Funcion que permite consultar los espacios aprobados por el estudiante
     * @return <string/0>
     */
      
    function consultarEspaciosPlan() {
            $variables=array('planEstudioEstudiante'=>$this->datosEstudiante['planEstudioEstudiante'],
                            'codProyectoEstudiante'=>$this->datosEstudiante['codProyectoEstudiante']
                            );
            $cadena_sql = $this->sql->cadena_sql("espacios_plan_estudio", $variables);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;
        } 
    
    /**
     * Funcion que consulta los espacios que ha cursado el estudiante
     * @return type 
     */
    function consultarEspaciosCursados() {
        $variables=array('codEstudiante'=>$this->datosEstudiante['codEstudiante'],
                         'codProyectoEstudiante'=>  $this->datosEstudiante['codProyectoEstudiante']);
        $cadena_sql = $this->sql->cadena_sql("espacios_cursados", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
   /**
    * Funcion que consulta los requisitos para los espacios academicos del plan de estudios del estudiante
    * @return type 
    */       
   function consultarRequisitosPlan() {
      $variables=array('codProyectoEstudiante'=>$this->datosEstudiante['codProyectoEstudiante'],
                        'planEstudioEstudiante'=>$this->datosEstudiante['planEstudioEstudiante']
                        );
      $cadena_sql = $this->sql->cadena_sql("buscar_requisitos_plan", $variables);
      $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        if(is_array($resultado))
        {
            foreach ($resultado as $valor) { 
                $resultadoCodigo[]=array('CODIGO_ESPACIO'=>$valor['COD_ASIGNATURA'],
                                        'CODIGO_REQUISITO'=>$valor['COD_REQUISITO']);
            }
        }else
            {
                $resultadoCodigo='';                
            }
        return($resultadoCodigo) ;
     }
     
    /**
     * Funcion que permite consultar los espacios inscritos por el estudiante en el periodo
     * @return <string/0>
     */
    function consultarEspaciosInscritos() {
        
         $variables=array('codEstudiante'=>  $this->datosEstudiante['codEstudiante'],
                        'periodo'=>  $this->periodo, 
                        'ano'=>  $this->ano 
                        );
        $cadena_sql = $this->sql->cadena_sql("espacios_inscritos", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(is_array($resultado))
        {
            foreach ($resultado as $valor)
            {
                $resultadoCodigo[]=$valor['INS_ASI_COD'];
            }
        }else
            {
                $resultadoCodigo='';
            }
        return $resultadoCodigo;
    }

    /**
     * Funcion que permite consultar la nota aprobatoria para el proyecto del estudiante
     * @return <int>
     */
    function consultarNotaAprobatoria() {

        $variables=array('codProyectoEstudiante'=>  $this->datosEstudiante['codProyectoEstudiante']            
                        );
        $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
      }

    /**
     * Funcion que permite consultar los espacios equivalentes de los permitidos  para cursar por el estudiante
     * @return <array>
     */
    function consultarEspaciosEquivalentes($datosEquivalencia) {  
          $cadena_sql = $this->sql->cadena_sql("espacios_equivalentes", $datosEquivalencia);
          return $resultado_planEstudio = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
      }
      
    /**
     * Funcion que consulta los parametros de un plan de estudios
     * @param <int> $planEstudio
     * @return <array>
     */
    function parametros_plan($planEstudio){
        $cadena_sql_parametros=$this->sql->cadena_sql("parametros_plan", $planEstudio);
        return $resultado_parametros=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );
        }

    /**
     * Funcion que consulta los espacios cancelados para el estudiante
     * @param <array> $variablesCancelado(codigoEstudiante,codigo_asignatura,año y periodo)
     * @return <array>
     */
      function valida_cancelo_estudiante($variablesCancelado){
            $cadena_sql=$this->sql->cadena_sql("espaciosCancelados", $variablesCancelado);
            return $resultado_cancelo=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        }

  }
?>