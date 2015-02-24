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

class funcion_adminBuscarEspacioEECreditosEstudianteSop extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $espaciosPlan;

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
     * Funciom que presenta los espacios electivos extrinsecos que puede adicionar el estudiante
     * Utiliza los metodos consultaEspaciosPermitidos, enlaceHorario, mostrarEspacios, mensajeNoEspacios, mensajeEstadoDiferenteActivo, retorno
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codEstudiante, codProyectoEstudiante, planEstudioEstudiante, creditosInscritos, estado_est)
     */
    function consultarEspaciosPermitidos() {
        $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
        if (trim($_REQUEST['estado_est'])=='A'){
            $resultado_planEstudio=$this->consultaEspaciosPermitidos();

        }else{
            $resultado_planEstudio="";
        }
    ?>
    <table width="70%" align="center" border="0" >
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
            $arregloOrdenado=$this->ordenarListado($resultado_planEstudio,'DEP_COD','PEN_CRA_COD');
            $this->mostrarEspacios($arregloOrdenado, $_REQUEST,$this->espaciosCursados);
        }
        else
          {
              if (trim($_REQUEST['estado_est'])=='A'){
                    $this->mensajeNoEspacios();
              }else{
                    $cadena_sql = $this->sql->cadena_sql("consultarNombreEstado", $_REQUEST['estado_est']);
                    $resultado_estado = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
                    $this->mensajeEstadoDiferenteActivo($resultado_estado[0][1]);
              }
          }
        $this->retorno($_REQUEST);
    }

    /**
     * Funcion que muestra los espacios academicos Electivo extrinsecos que puede registrar el estudiante
     * listadoEspacios es una matriz de los espacios que puede inscribir al estudiante
     * Utiliza los metodos de evaluaRangos, parametros_plan, valida_cancelo_estudiante, enlaceAdicionar, mensajeSuperaCreditos, mensajeSuperaCreditosClasificacion, mensajeEspacioCancelado
     * @param <array> $listadoEspacios (CODIGO,NOMBRE,NIVEL,CREDITOS,CLASIFICACION)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     * @param <array> $resultado_espacios_estudiante (CODIGO,NOTA,NOT_OBS)
     */
    function mostrarEspacios($listadoEspacios, $datosGenerales,$resultado_espacios_estudiante) {
        list($OBEst, $OCEst, $EIEst, $EEEst, $CPEst, $OB, $OC, $EI, $EE,$CP)=$this->evaluaRangos($datosGenerales,$resultado_espacios_estudiante);
        $resultado_parametros =$this->parametros_plan($datosGenerales['planEstudioEstudiante']);
        $variablesCancelado=array($datosGenerales['codEstudiante'],$this->ano, $this->periodo);
        $resultado_cancelo = $this->valida_cancelo_estudiante($variablesCancelado);
        
      ?>
      <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
        <caption class="sigma">
          <center>
            ELECTIVAS EXTR&Iacute;NSECAS OFRECIDAS
          </center>
        </caption>
        <tr>
          <td> 
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <?
                $facActual=-1;
                $proyActual=-1;
                $totalEspacios=count($listadoEspacios);
                for ($i = 0; $i <$totalEspacios; $i++) {
                    if ((isset($listadoEspacios[$i]['DEP_COD'])?$listadoEspacios[$i]['DEP_COD']:'')!=$facActual)
                        {?>
                            <tr>
                                <td class="sigma_a cuadro_plano centrar" colspan="6">
                                    <b>
                                    <?
                                        echo strtoupper($listadoEspacios[$i]['DEP_NOMBRE']);
                                    ?>
                                    </b>
                                </td>
                            </tr>
                        <?
                        $facActual=(isset($listadoEspacios[$i]['DEP_COD'])?$listadoEspacios[$i]['DEP_COD']:'');
                        }
                    if((isset($listadoEspacios[$i]['PEN_CRA_COD'])?$listadoEspacios[$i]['PEN_CRA_COD']:'')!=$proyActual)
                        {
                        ?>
                        <tr>
                            <td class='sigma_b centrar' colspan="6" style=""><b><?echo $listadoEspacios[$i]['CRA_NOMBRE']?></b></td>
                        </tr>
                        <tr >
                            <th class="sigma centrar" width="10%"><b>C&oacute;digo Espacio</b></th>
                            <th class="sigma centrar" width="40%"><b>Nombre Espacio</b></th>
                            <th class="sigma centrar" width="8%"><b>Clasificaci&oacute;n</b></th>
                            <th class="sigma centrar" width="8%"><b>Nro Cr&eacute;ditos</b></th>
                            <th class="sigma centrar" width="15%"><b>Adicionar</b></th>
                        </tr>
                        <?$proyActual=(isset($listadoEspacios[$i]['PEN_CRA_COD'])?$listadoEspacios[$i]['PEN_CRA_COD']:'');
                        }
                  
                    $clasificacion = $listadoEspacios[$i]['CLASIFICACION'];

                    $totalEI=$listadoEspacios[$i]['CREDITOS']+$EIEst;
                    $totalEE=$listadoEspacios[$i]['CREDITOS']+$EEEst;
                    $bandClasifica=0;
                    switch($listadoEspacios[$i]['CLASIFICACION']){
                                   
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
                     }

                    $numeroCreditosRestantes=($resultado_parametros[0]['parametro_maxCreditosNivel']-$_REQUEST['creditosInscritos']);

                      ?>
                      <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td class='cuadro_plano centrar'><? echo $listadoEspacios[$i]['CODIGO'] ?></td>
                        <td class='cuadro_plano '><? echo $listadoEspacios[$i]['NOMBRE'] ?></td>
                        <td class='cuadro_plano centrar'><? echo $clasificacion ?></td>
                        <td class='cuadro_plano centrar'><font color="<?  if($listadoEspacios[$i]['CREDITOS']<=$numeroCreditosRestantes && $bandClasifica==0) echo "#3BAF29"; else echo "#F90101"; ?>"><? echo $listadoEspacios[$i]['CREDITOS'] ?></font></td>
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
                                $this->mensajeSuperaCreditos($resultado_parametros[0]['parametro_maxCreditosNivel']);
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
                      <?

                }//fin for
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
     * @param <array> $retorno (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     */
    function retorno() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variablesPag=$this->variablesRetorno();
        ?>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                    '<?
                        echo $pagina . $variablesPag;
                    ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="25" height="25" border="0"><br>
                <font size="2"><b>Regresar</b></font>
            </button>

        <!--            <tr class="cuadro_plano centrar">
              <th>
                  Observaciones
              </th>
          </tr>
          <tr class="cuadro_plano">
              <td>
                  * Si la casilla de adici&oacute;n est&aacute; en <font color="#F90101">rojo</font>, significa que el grupo presenta sobrecupo
              </td>
          </tr>-->
<?
      }

    /**
     * Funcion que muestra enlace para adicionar un espacio electivo extrinseco
     * @param <array> $datosEspacio (CODIGO,NOMBRE,NIVEL,CREDITOS,CLASIFICACION)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,creditosInscritos,estado_est,ano,periodo)
     */
    function enlaceAdicionar($datosEspacio, $datosGenerales) {
        $parametro = "=";

                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=".$this->formulario;
                  $variables.="&opcion=validar";
                  $variables.="&action=".$this->formulario;
                  $variables.="&destino=registro_adicionarInscripcionEECreditosEstudiante";
                  $variables.="&retorno=admin_buscarEspacioEECreditosEstudianteSop";
        
                  $parametros="&codEstudiante=" . $datosGenerales['codEstudiante'];
                  $parametros.="&codProyectoEstudiante=" . $datosGenerales['codProyectoEstudiante'];
                  $parametros.="&planEstudioEstudiante=" . $datosGenerales['planEstudioEstudiante'];
                  $parametros.="&estado_est=" . trim($datosGenerales['estado_est']);
                  $parametros.="&tipoEstudiante=" .  $datosGenerales['tipoEstudiante'];
                  $parametros.="&creditos=" . (isset($datosEspacio['CREDITOS'])?$datosEspacio['CREDITOS']:'');
                  $parametros.="&creditosInscritos=" . $_REQUEST['creditosInscritos'];
                  $parametros.="&codEspacio=" . $datosEspacio['CODIGO'];
                  $parametros.="&nombreEspacio=" . $datosEspacio['NOMBRE'];
                  $parametros.="&planEstudio=" . (isset($datosEspacio['PENSUM'])?$datosEspacio['PENSUM']:'');
                  $parametros.="&codProyecto=" . (isset($datosEspacio['PEN_CRA_COD'])?$datosEspacio['PEN_CRA_COD']:'');
                  $parametros.="&parametro=".$parametro;

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
     * Funcion que presenta mensaje cuando no hay espacios para inscribir
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
     * Funcion que presenta mensaje cuando no se puede adicionar el espacio por q supera los creditos permitidos
     * @param <int> $numMaxCreditos
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
     * Utiliza los metodos consultarNotaAprobatoria, consultarEspaciosAprobados, consultarEspaciosInscritos
     * @return <array>
     * @param <array> $resultado_espacios_estudiante (CODIGO,NOTA,NOT_OBS)
     */
    function consultaEspaciosPermitidos() {
          $notaAprobatoria='';
          $notaAprobatoria=$this->consultarNotaAprobatoria();
          
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
          $this->espaciosAprobados=$this->consultarEspaciosAprobados($notaAprobatoria);
          
          $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "extrinsecos");
            if($cadena=='')
             {
                $this->espaciosExtrinsecos=$this->consultarEspaciosExtrinsecos(); 
                $this->procedimientos->registrarArreglo($this->espaciosExtrinsecos,'extrinsecos'); 
             }else
             { 
                $this->espaciosExtrinsecos=$this->procedimientos->stringToArray($cadena[0][0]);           
             }
          $this->espaciosExtrinsecosParaInscribir=$this->buscarEspaciosExtrinsecos(); 
          return $this->espaciosExtrinsecosParaInscribir;         
          
        }
    
       /**
        * Funcion que busca los espacios electivos extrínsecos para inscribir al estudiante
        * @return type 
        */
     function buscarEspaciosExtrinsecos(){
          
          $espaciosExtrinsecos=array(); 
          $a=0;
          foreach ($this->espaciosExtrinsecos as $key => $value) { 
                  if ((isset($this->espaciosExtrinsecos[$key+1]['CODIGO'])?$this->espaciosExtrinsecos[$key+1]['CODIGO']:'')!=$this->espaciosExtrinsecos[$key]['CODIGO'])
                  {
                        $espaciosExtrinsecos[$a]=$this->espaciosExtrinsecos[$key];
                        $a++;
                    }
              }
              
          //Se eliminan del arreglo los espacios EE que el estudiante ya aprobó
          $extrinsecosMenosAprobados=array();
          foreach($espaciosExtrinsecos as $key => $value){
                $existe=0;
                if(is_array($this->espaciosInscritos)){
                foreach($this->espaciosAprobados as $key2 => $value2){
                      if($value['CODIGO'] == $value2 ){
                        $existe=1;  
                      }
                }
                if ($existe==0){
                    $extrinsecosMenosAprobados[]=$value; 
                }
                }else
                    {
                        $extrinsecosMenosAprobados=$espaciosExtrinsecos;
          }
          
          }
          
          //Se eliminan del arreglo los espacios EE que el estudiante ya inscribio
          $extrinsecosMenosAprobadosMenosInscritos=array();
          foreach($extrinsecosMenosAprobados as $key => $value){
                $existe=0;
                if(is_array($this->espaciosInscritos)){
                foreach($this->espaciosInscritos as $key2 => $value2){
                      if($value['CODIGO'] == $value2['INS_ASI_COD'] ){
                        $existe=1;
                      }
                }
                if ($existe==0){
                    $extrinsecosMenosAprobadosMenosInscritos[]=$value;
                }
                }else
                    {
                        $extrinsecosMenosAprobadosMenosInscritos=$extrinsecosMenosAprobados;
          }
          }
       
          $extrinsecosMenosAprobadosMenosInscritosMenosPlan=array();
       
          foreach($extrinsecosMenosAprobadosMenosInscritos as $key => $value){
                $existe=0;
                foreach($this->espaciosPlan as $key2 => $value2){
                       if($value['CODIGO'] == $value2['CODIGO'] ){
                        $existe=1;
                      }
                }
                if ($existe==0){
                    $extrinsecosMenosAprobadosMenosInscritosMenosPlan[]=$value;
                }
          }
          
          
          return $extrinsecosMenosAprobadosMenosInscritosMenosPlan; 
      }

/*
 * Funcion que evalua los rangos de los creditos dependiendo de los parametros del plan
 * @param <array> $datosEstudiante ()
 * @param <array> $resultado_espacios_estudiante (CODIGO,NOTA,NOT_OBS)
 */
     function evaluaRangos($datosEstudiante,$resultado_espacios_estudiante) {
        $planEstudiante=$datosEstudiante['planEstudioEstudiante'];
        $registroCreditosGeneral =$this->parametros_plan($planEstudiante);
        
        $totalCreditos=$registroCreditosGeneral[0]['parametro_creditosPlan'];
        $OB= $registroCreditosGeneral[0]['parametros_OB'];
        $OC= $registroCreditosGeneral[0]['parametros_OC'];
        $EI= $registroCreditosGeneral[0]['parametros_EI'];
        $EE= $registroCreditosGeneral[0]['parametros_EE'];
        $CP= $registroCreditosGeneral[0]['parametros_CP'];

        $notaAprobatoria=$this->consultarNotaAprobatoria();
        $nota = $notaAprobatoria;
        $i=0;
        if(is_array($this->espaciosAprobados))
        {
        $OBEst=0;
        $OCEst=0;
        $EIEst=0;
        $EEEst=0;
            $CPEst=0;
        $totalCreditosEst=0;
            $numeroAprobados=count($this->espaciosAprobados);
            for($i=0;$i<$numeroAprobados;$i++)
            {
                $idEspacio= isset($this->espaciosAprobados[$i][0])?$this->espaciosAprobados[$i][0]:'';
            $variables=array($idEspacio, $planEstudiante);

            $cadena_sql=$this->sql->cadena_sql("valorCreditosPlan", $variables);
            $registroCreditosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                switch($registroCreditosEspacio[0][1])
                {
                case 1:
                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                    break;

                case 2:
                    $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                    break;

                case 3:
                    $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                    break;

                case 4:
                    $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                    break;

                case 5:
                        $CPEst=$CPEst+$registroCreditosEspacio[0][0];
                    break;

            }
        }
        }
        $cadena_sql=$this->sql->cadena_sql("espacios_inscritos", $_REQUEST);
        $registroEspaciosInscritos=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );

        for($i=0;$i<count($registroEspaciosInscritos);$i++) {
            $idEspacio= $registroEspaciosInscritos[$i][0];
            $variables=array($idEspacio, $planEstudiante);

            $cadena_sql=$this->sql->cadena_sql("valorCreditosPlan", $variables);
            $registroCreditosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($registroCreditosEspacio)) {

                switch($registroCreditosEspacio[0][1]) {
                    case 1:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                        break;

                    case 2:
                        $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                        break;

                    case 3:
                        $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                        break;

                    case 4:
                        $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                        break;

                    case 5:
                        $CPEst=$CPEst+$registroCreditosEspacio[0][0];
                        break;

                }
            }else {
                $cadena_sql=$this->sql->cadena_sql("valorCreditos", $variables);
                $registroCreditosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                switch($registroCreditosEspacio[0][1]) {
                    case 1:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                        break;

                    case 2:
                        $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                        break;

                    case 3:
                        $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                        break;

                    case 4:
                        $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                        break;

                    case 5:
                        $CPEst=$CPEst+$registroCreditosEspacio[0][0];
                        break;

                }
            }
        }
        $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;

        return array($OBEst, $OCEst, $EIEst, $EEEst, $CPEst, $OB, $OC, $EI, $EE,$CP);

    }

/**
     * Funcion que permite consultar la nota de un espacio de un estudiante
     * @param <array> $curso ()
     * @param <array> $espacios_estudiante (CODIGO,NOTA,NOT_OBS)
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
     * Funcion que consulta si un estudiante cancelo un espacio academico
     * @param <array> $variablesCancelado (codigoEstudiante, codEspacio, ano, periodo)
     * @return <string/0>
     */

      function valida_cancelo_estudiante($variablesCancelado){
            $cadena_sql=$this->sql->cadena_sql("estudianteCancelo", $variablesCancelado); 
            return $resultado_cancelo=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        }

    /**
     * Funcion que presenta mensaje cuando el estado es diferente a activo
     * @param <string> $estado
     */
    function mensajeEstadoDiferenteActivo($estado) {

          ?>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
              <tr>
                <th class='cuadro_plano centrar' colspan="6">Su estado acad&eacute;mico <? echo $estado;?>, no le permite Adicionar espacios Electivos Extrinsecos.</th>
              </tr>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
            </table>
          <?
      }


    /**
    * Funcion que organiza un arreglo de acuerdo a dos campos
    * @param type $arregloAOrdenar
    * @param type $campo
    * @param type $campo2
    * @param type $inverso
    * @return type
    */
    function ordenarListado ($arregloAOrdenar, $campo1, $campo2, $inverso = false) {
        //organiza el arreglo por el campo1
        $arregloRetorno=$this->ordenarArreglo($arregloAOrdenar, $campo1, $inverso);

        //crea un arreglo por cada valor diferente de campo1
            $a=0;
            $arregloInicial=array();
        foreach ($arregloRetorno as $key => $value) {
            $order=$arregloRetorno[$key][$campo1];
            if(isset($arregloRetorno[$key+1])){
                if ($order==$arregloRetorno[$key+1][$campo1])
            {
                $arregloInicial[$a][]=$arregloRetorno[$key];
            }
            else
                {
                    $arregloInicial[$a][]=$arregloRetorno[$key];
                    $a++;
                }
            }else{
                $arregloInicial[$a][]=$arregloRetorno[$key];
            }
        }
        //Cada arreglo de campo1 lo organiza por campo2
        $arregloFinal=array();
            foreach ($arregloInicial as $key => $value) {
                $arregloRetorno2=$this->ordenarArreglo($arregloInicial[$key], $campo2, $inverso);
            //Crea el arreglo final
            $arregloFinal=array_merge($arregloFinal,$arregloRetorno2);
        }
        return $arregloFinal;
    }

    /**
     * Funcion que permite ordenar un arreglo por el campo especificado en campo
     * @param type $arregloAOrdenar
     * @param type $campo
     * @param type $inverso
     * @return type
     */
    function ordenarArreglo($arregloAOrdenar,$campo,$inverso='false') {
        $posicion = array();
        $nuevaFila = array();
        //realiza un barrido por el arreglo creando un arreglo con los valores del campo a ordenar
        foreach ($arregloAOrdenar as $key => $fila) {
                $posicion[$key]  = $fila[$campo];
                $nuevaFila[$key] = $fila;
        }
        //organiza el nuevo arreglo de acuerdo a los valores del campo
        if ($inverso) {
            arsort($posicion);
        }
        else {
            asort($posicion);
        }
        //crea un arreglo ordenado por campo
        $arregloRetorno = array();
        foreach ($posicion as $key => $pos) {
            $arregloRetorno[] = $nuevaFila[$key];
        }
        return $arregloRetorno;
    }

    /**
     *Funcion que permite consultar los espacios electivos extrínsecos existentes en la universidad
     * @return type 
     */
        function consultarEspaciosExtrinsecos (){
          $cadena_sql = $this->sql->cadena_sql("electivas_extrinsecas", $_REQUEST);
          $resultado_planEstudio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado_planEstudio;
  }
       
     /**
     * Funcion que permite consultar los espacios del plan de estudios del estudiante
     * @return type 
     */
      
     function consultarEspaciosPlan() {
           
       $variables=array('planEstudioEstudiante'=>$this->datosEstudiante['planEstudioEstudiante'],
                            'codProyectoEstudiante'=>$this->datosEstudiante['codProyectoEstudiante']
                            );
       $cadena_sql = $this->sql->cadena_sql("espacios_plan_estudio", $variables);
       $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
       return $resultado;
        } 
        
     /**
     * Funcion que permite consultar los espacios cursados por el estudiante
     * @return <string/0>
     */
     function consultarEspaciosCursados() {
            $variables=$this->datosEstudiante['codEstudiante'];
            $cadena_sql = $this->sql->cadena_sql("espacios_cursados", $variables);
            $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
            return $resultado;
        }    
        
    /**
     * Funcion que permite consultar los espacios aprobados por el estudiante
     * @param <int> $nota
     * @param <array> $espacios_estudiante (CODIGO)
     * @return <string/0>
     */
    function consultarEspaciosAprobados($notaAprobatoria) {
        $aprobados=array();
        if (is_array($this->espaciosCursados))
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
     * Funcion que permite consultar los espacios inscritos por el estudiante en el periodo
     * @return <string/0>
     */
    function consultarEspaciosInscritos() {
          $inscritos=isset($inscritos)?$inscritos:'';
          $cadena_sql = $this->sql->cadena_sql("espacios_inscritos", $_REQUEST);
          $resultado_inscritos = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
          if (is_array($resultado_inscritos)){
            return $resultado_inscritos;
          }
          else
          {
            return 0;
          }
      }

    /**
     * Funcion que permite consultar la nota aprobatoria para el proyecto del estudiante
     * @return <int>
     */
    function consultarNotaAprobatoria() {
          $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $_REQUEST);
          $resultado_nota = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado_nota[0][0];
      }

    /**
     * Funcion que permite consultar los parametros del plan de estudio del estudiante
     * @param <int> $planEstudio
     * @return <array>
     */
 
     function parametros_plan($planEstudio){
        $cadena_sql_parametros=$this->sql->cadena_sql("parametros_plan", $planEstudio);
        return $resultado_parametros=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );
        }
        
  }
?>