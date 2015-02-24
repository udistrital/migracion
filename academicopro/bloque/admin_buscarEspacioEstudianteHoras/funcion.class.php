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
 *@ Esta clase presenta los espacios academicos que se pueden inscribir a un estudiante de Horas.
 */

class funcion_adminBuscarEspacioEstudianteHoras extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $espaciosPlan;
  private $espaciosCursados;
  private $espaciosInscritos;
  private $requisitosPlan;
  private $espaciosEquivalentes;
  private $espaciosInscritosEquivalentes;
  private $espaciosBorrados;
  

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/administrarModulo.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
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
                                 'numeroSemestres'=>$_REQUEST['numeroSemestres'],
                                 'tipoEstudiante'=>$_REQUEST['tipoEstudiante']);


    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");
    //Conexion ORACLE
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

    //Datos de sesion
    $this->formulario = "admin_buscarGruposEstudianteHoras";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    
    $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
  }

    /**
     * Funciom que presenta los espacios que puede adicionar al estudiante
     * Utiliza los metodos mostrarEspacios, retorno
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codEstudiante, codProyectoEstudiante, planEstudioEstudiante, creditosInscritos, estado_est)
     */
    function consultarEspaciosPermitidos() {
    $_REQUEST['ano']=$this->ano;
    $_REQUEST['periodo']=$this->periodo;
        $datosEquivalentes=array('codProyectoEstudiante'=>$_REQUEST['codProyectoEstudiante']);
        $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "equivalencias");
        if($cadena=='')
        {
            $this->espaciosEquivalentes=$this->consultarEspaciosEquivalentes($datosEquivalentes);
            $this->procedimientos->registrarArreglo($this->espaciosEquivalentes,'equivalencias');
        }else
            { 
                $this->espaciosEquivalentes=$this->procedimientos->stringToArray($cadena[0][0]);           
            }
    
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
          }
        $this->retorno($_REQUEST);
    }

    /**
     * Funcion que muestra los espacios academicos que se pueden registrar al estudiante
     * listadoEspacios es una matriz de los espacios que puede inscribir al estudiante
     * @param <array> $listadoEspacios (CODIGO,NOMBRE,NIVEL,ELECTIVA,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
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
                <th class="sigma centrar" width="15%"><b>Adicionar</b></th>
              </tr>
              <?
                for ($i = 0; $i < count($listadoEspacios); $i++) {
                  if ((isset($listadoEspacios[$i - 1]['NIVEL'])?$listadoEspacios[$i - 1]['NIVEL']:'') != $listadoEspacios[$i]['NIVEL']) {
                  ?>
                    <tr>
                      <td class="sigma_a cuadro_plano centrar" colspan="6"><font size="2"> NIVEL <? echo $listadoEspacios[$i]['NIVEL'] ?></font></td>
                    </tr>
                    <?
                  }
                  if (trim($listadoEspacios[$i]['ELECTIVA']) == 'S') {
                    $clasificacion = "<font color='#088A08'>Electivo</font>";
                  } else {
                    $clasificacion = 'Obligatorio';
                  }
                  ?>
                  <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                    <td class='cuadro_plano centrar'><? echo $listadoEspacios[$i]['CODIGO'] ?></td>
                    <td class='cuadro_plano '><? echo $listadoEspacios[$i]['NOMBRE'] ?></td>
                    <td class='cuadro_plano centrar'><? echo $clasificacion ?></td>
                    <td class='cuadro_plano centrar'><? $this->enlaceAdicionar($listadoEspacios[$i], $datosGenerales) ?></td>
                  </tr>
                  <?
                  if (is_array($this->espaciosEquivalentes))
                    {
                      foreach ($this->espaciosEquivalentes as $espacio => $value) {
                          if($value['ASI_COD_ANTERIOR']== $listadoEspacios[$i]['CODIGO']||(isset($value['ASI_COD_ANTERIOR2'])?$value['ASI_COD_ANTERIOR2']:'')== $listadoEspacios[$i]['CODIGO']){
                    ?>
                  <tr onmouseover="this.style.background='#F4FA58'" onmouseout="this.style.background=''">
                    <td class='cuadro_plano derecha'><font color='#08088A'><? echo $value['CODIGO'] ?></font></td>
                    <td class='cuadro_plano '><font color='#08088A'><? echo $value['NOMBRE']?></font> <b>Equivalente</b></td>
                    <td class='cuadro_plano centrar'><? echo $clasificacion ?></td>
                    <td class='cuadro_plano centrar'><? $this->enlaceAdicionar($value, $datosGenerales) ?></td>
                  </tr>
                <?}
                        }
                    }
                }
          ?>
            </table>
          </td>
        </tr>
      </table>
      <?
    }

    /**
     * Funcion que genera el enlace para regresar al horario del estudiante
     */
    function enlaceHorario() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        //$variable=$this->variablesRetorno();
        ?>
    <tr>
        <td>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                '<?
                        $variable = "pagina=admin_consultarInscripcionEstudianteHoras";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
                        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
                        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
                        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
                        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];
                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                        echo $pagina . $variable;
                 ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/vcalendar.png" width="35" height="35" border="0"><br>
          <b>Horario Estudiante</b
            </button>					
        </td>
        </tr>
        
        <?
    }

    /**
     * Funcion que genera las variables para enlaces de retorno
     * @return <type>
     */
    function variablesRetorno() {

        $variable = "pagina=admin_consultarInscripcionEstudianteHoras";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
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
        
        <tr>
        <td>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                '<?
                        $variable="pagina=adminConsultarInscripcionEstudianteHoras";
                        $variable.="&opcion=consultar";
                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                        echo $pagina . $variablesPag;
                 ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="25" height="25" border="0"><br>
          <b>Regresar</b
            </button>					
            </td>
          </tr>

<?
      }

    /**
     * Funcion que muestra enlace para adicionar un espacio
     * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,estado_est)
     */
    function enlaceAdicionar($datosEspacio, $datosGenerales) {
        $parametro = "=";
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=".$this->formulario;
                  $variables.="&opcion=validar";
                  $variables.="&action=".$this->formulario;
                  $variables.="&destino=registro_adicionarEspacioEstudianteHoras";
        
                  $parametros = "&codProyecto=" .  $datosGenerales['codProyecto'];
                  $parametros.="&planEstudio=" . $datosGenerales['planEstudio'];
                  $parametros.="&codEstudiante=" . $datosGenerales['codEstudiante'];
                  $parametros.="&codProyectoEstudiante=" . $datosGenerales['codProyectoEstudiante'];
                  $parametros.="&planEstudioEstudiante=" . $datosGenerales['planEstudioEstudiante'];
                  $parametros.="&estado_est=" . trim($datosGenerales['estado_est']);
                  $parametros.="&tipoEstudiante=" . trim($datosGenerales['tipoEstudiante']);
                  $parametros.="&codEspacio=" . $datosEspacio['CODIGO'];
                  $parametros.="&nombreEspacio=" . $datosEspacio['NOMBRE'];
                  $parametros.="&creditos=" . (isset($datosEspacio['CREDITOS'])?$datosEspacio['CREDITOS']:'');
                  $parametros.="&numeroSemestres=".trim($datosGenerales['numeroSemestres']);
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
     * Funcion que permite consultar los espacios permitidos para cursar por el estudiante
     * @return <array>
     */
    function consultaEspaciosPermitidos() {
        $notaAprobatoria=isset($notaAprobatoria)?$notaAprobatoria:'';
        $espaciosPorCursar='';
        $espacioEquivalente='';
        $notaAprobatoria=$this->consultarNotaAprobatoria(); 
        $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "espaciosPlan");
        if($cadena==''){
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
        $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "requisitos");
         if($cadena=='')
         {
            $this->requisitosPlan=$this->consultarRequisitosPlan();
            $this->procedimientos->registrarArreglo($this->requisitosPlan,'requisitos');
         }else
            { 
                $this->requisitosPlan=$this->procedimientos->stringToArray($cadena[0][0]);           
            }
          
        $this->espaciosAprobados=$this->buscarEspaciosAprobados($notaAprobatoria); 
        $this->espaciosInscritos=$this->consultarEspaciosInscritos();        
        $this->espaciosInscritosEquivalentes=$this->consultarEspaciosInscritosEquivalentes();
        $this->espaciosBorrados=$this->espaciosBorrados();  
                   
        $espaciosPorCursar=$this->buscarEspaciosPorCursar();
        if(is_array($espaciosPorCursar))
        {
            foreach ($this->espaciosPlan as $espacio)
            {
                foreach ($espaciosPorCursar as $porCursar)
                {
                    if ($espacio['CODIGO']==$porCursar)
                    {
                        $espaciosPresentados[]=$espacio;                   
                    }
                }
            }
        }else{$espaciosPresentados='';}
        
        
         return $espaciosPresentados;
      }
      
    function consultarEspaciosPlan() {
        $variables=array('planEstudioEstudiante'=>$this->datosEstudiante['planEstudioEstudiante'],
                        'codProyectoEstudiante'=>$this->datosEstudiante['codProyectoEstudiante']
                        );
        $cadena_sql = $this->sql->cadena_sql("espacios_plan_estudio", $variables); 
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;        
       
    } 
          
     /**
     * Funcion que permite consultar los espacios aprobados por el estudiante
     * @return <string/0>
     */
    function consultarEspaciosCursados() {
        $variables=array('codEstudiante'=>  $this->datosEstudiante['codEstudiante']            
                        );
        $cadena_sql = $this->sql->cadena_sql("espacios_cursados", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
   function consultarNotaAprobatoria() {
        $variables=array('codProyectoEstudiante'=>  $this->datosEstudiante['codProyectoEstudiante']            
                        );
        $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }
    
  /**
     *Este método buscar los espacios académicos aprobados
     * 
     * Se toma el arreglo de espacios académicos cursados y se obtienen los espacios con nota mayor a la
     * aprobatoria o con observacion 19m, retorna los aprobados
     * 
     * @param type $notaAprobatoria 
     */
    
   function buscarEspaciosAprobados($notaAprobatoria){
        $aprobados='';
        if (is_array($this->espaciosCursados)){
        if (trim($this->datosEstudiante['tipoEstudiante'])=='S') 
        {
            foreach ($this->espaciosCursados as $value1)
            {
                if ($value1['NOTA']>=$notaAprobatoria)
                {
                    $aprobados[]=$value1;
                }
            }
        }elseif(trim($this->datosEstudiante['tipoEstudiante'])=='N')
            {
                foreach ($this->espaciosCursados as $value1)
                {
                    if ($value1['NOTA']>=$notaAprobatoria OR $value1['OBSERVACION']=='19')
                        {   
                            $aprobados[]=$value1;
                        }
                }
            }else
                {
                }
        }
        return $aprobados;
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
                $resultadoCodigo[]=$valor['CODIGO'];
            }
        }else
            {
                $resultadoCodigo='';
            }
        return $resultadoCodigo;
    }
 
    /**
     *Se buscar los espacios que puede cursar el estudiante
     * EspaciosPorCursar=plan-aprobados-preinscritos-NoRequisitos
     * 
     * @param type $notaAprobatoria 
     */
    
    function buscarEspaciosPorCursar(){
        $espaciosPresentados='';
        $espaciosAMostrar='';
        $ordenado='';
        $planMenosAprobados=$this->buscarPlanMenosAprobados();
         //filtra el numero de semestres de acuerdo a los parametros
    
         foreach ($this->espaciosPlan as $espacio)
            {
                foreach ($planMenosAprobados as $porCursar)
                {
                    if ($espacio['CODIGO']==$porCursar)
                    {
                        $espaciosPresentados[]=$espacio;                  
                    }
                }
            }
         foreach ($espaciosPresentados as $espacio)
            { 
                if($espacio['NIVEL']!='0')
                {
                $arreglo_niveles[]=$espacio['NIVEL'];                
                }
                                         
            }
            
         sort($arreglo_niveles);           

            $semestreMenor=$arreglo_niveles[0];
            
            $semestreMayor=$semestreMenor+$this->datosEstudiante['numeroSemestres'];
            
        foreach ($espaciosPresentados as $value) {                     
            for ($i = $semestreMenor; $i < $semestreMayor; $i ++)
                {                 
                    if($i==$value['NIVEL'] )
                    {
                          $espaciosAMostrar[]=$value['CODIGO'];                           
                    }else
                        {  
                        }         
                }
        }

         //Quita de los espacios del plan los inscritos
                if(is_array($this->espaciosInscritos)) 
            {
                $planMenosAprobadosMenosInscritos=$this->bucarPlanMenosAprobadosMenosInscritos($espaciosAMostrar);
            }else
                {
                    $planMenosAprobadosMenosInscritos=$espaciosAMostrar;                    
                }
        if (is_array($planMenosAprobadosMenosInscritos) && !empty($planMenosAprobadosMenosInscritos))
            {
        //quita los espacios inscritos equivalentes
        if(is_array($this->espaciosInscritosEquivalentes))
            {
                $planMenosAprobadosMenosInscritosEquivalentes=$this->bucarPlanMenosAprobadosMenosInscritosEquivalentes($planMenosAprobadosMenosInscritos);
           }else
                {
                    $planMenosAprobadosMenosInscritosEquivalentes=$planMenosAprobadosMenosInscritos;  
                }
        //quita los espacios cancelados
        if(is_array($this->espaciosBorrados))
                {
                    $espaciosBorrados=$this->bucarPlanMenosAprobadosMenosInscritosEquivalentesMenosBorrados($planMenosAprobadosMenosInscritosEquivalentes);                    
                }else
                    {
                        $espaciosBorrados=$planMenosAprobadosMenosInscritosEquivalentes;  
                    }
        //quita los espacios que no cumplen con los requisitos
        if(is_array($this->requisitosPlan))
            {
                $espaciosACursar=$this->evaluarRequisitos($espaciosBorrados);
            }else
                {
                    $espaciosACursar=$espaciosBorrados;  
                }
        }else{$espaciosACursar='';}
            //retorna los espacios a cursar
            return $espaciosACursar;
        }
    
    
   /**
     *Este método toma el arreglo de espacios del plan y le resta los espacios 
     * que el estudiante ya tiene aprobados
     *  
     */
    function buscarPlanMenosAprobados() {
        $planMenosAprobados=array();     
        foreach ($this->espaciosPlan as $espacioPlan)
            {
                $codigoEspaciosPlan[]=$espacioPlan['CODIGO']; 
            }
        if (is_array($this->espaciosAprobados))
        { 
            foreach ($this->espaciosAprobados as $espacioAprobados){
                
                $codigoEspaciosAprobado[]=$espacioAprobados['CODIGO']; 
                
            }
            $planMenosAprobados=array_diff($codigoEspaciosPlan, $codigoEspaciosAprobado); 
        }else
            {
                $planMenosAprobados=$codigoEspaciosPlan;
            }
         return $planMenosAprobados; 
        
    } 
    
    
    /**
    *Esta método quita los espacios preinscritos del arreglo $planMenosAprobados que
    * contiene los espacios del plan menos los espacios aprobados
    * $resultadoEspacios=espacios del plan - espacios aprobados *espacios preinscritos
    * 
    * @param type $planMenosAprobados
    * @return type 
    */
    function bucarPlanMenosAprobadosMenosInscritos($planMenosAprobados){      
       $resultado_espacios=array_diff($planMenosAprobados, $this->espaciosInscritos);
       return $resultado_espacios; 
    } 

    /**
    *Esta método quita los espacios preinscritos del arreglo $planMenosAprobados que
    * contiene los espacios del plan menos los espacios aprobados
    * $resultadoEspacios=espacios del plan - espacios aprobados *espacios preinscritos
    * 
    * @param type $planMenosAprobados
    * @return type 
    */
    function bucarPlanMenosAprobadosMenosInscritosEquivalentes($planMenosAprobados){
       $resultado_espacios=array_diff($planMenosAprobados, $this->espaciosInscritosEquivalentes);
       return $resultado_espacios; 
    } 

   /**
     * Funcion que permite consultar los requisitos del plan de estudios del estudiante
     * @return type 
     */
    function consultarRequisitosPlan() {
        $variables=array('codProyectoEstudiante'=>$this->datosEstudiante['codProyectoEstudiante'],
                        'planEstudioEstudiante'=>$this->datosEstudiante['planEstudioEstudiante']
                        );
        $cadena_sql = $this->sql->cadena_sql("buscar_requisitos_plan", $variables); 
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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
     *Este método evalua los requisitos de los espacios del arreglo $planMenosAprobadosMenosPreinscritos
     * 
     * 
     * @param array $planMenosAprobadosMenosPreinscritos Espacios para inscribir, sin evaluar requisitos
     * @return type 
     */
    function evaluarRequisitos($planMenosAprobadosMenosInscritos){
        $espaciosACursar=array();
        foreach ($planMenosAprobadosMenosInscritos as $codigoEspacio)
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
     *Este método busca los requisitos que tiene un espacio academico en el 
     * arreglo $this->requisitosPlan
     *
     * @param type $codigoEspacio
     * @return type 
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
     *Este metodo verifica uno a uno si los requisitos estan aprobados en el caso de que uno o varios requisitos
     * no esten aprobados retorna el 'noAprobado' en el caso de estar aprobados todos los requisitos retorna 'aprobado'
     * @param type $requisitos
     * @return type 
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
                    if($requisito == $aprobados['CODIGO'])
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

   function consultarEspaciosInscritosEquivalentes(){
     
       $codigoEspaciosEquivalentes=isset($codigoEspaciosEquivalentes)?$codigoEspaciosEquivalentes:'';
              
       $codigoEquivalente='';
       
       if (is_array($this->espaciosEquivalentes)) 
        {
            foreach ($this->espaciosEquivalentes as $espacioEquivalente){
                    $codigoEspaciosEquivalentes[]=$espacioEquivalente['CODIGO']; 
                    if (is_array($this->espaciosInscritos)){
                foreach ($this->espaciosInscritos as $inscritos) { 
                    if($espacioEquivalente['CODIGO']==$inscritos){
                      $codigoEquivalente[]=$espacioEquivalente['ASI_COD_ANTERIOR'];
                      $codigoEquivalente[]=$espacioEquivalente['ASI_COD_ANTERIOR2'];
                    }
                     
                }}
                 
            }
            
        }else
            {
                $codigoEquivalente='';
            }
       return $codigoEquivalente;       
    }
    
    function espaciosBorrados(){
      $variables=array('codEstudiante'=>  $this->datosEstudiante['codEstudiante'],
                        'periodo'=>  $this->periodo, 
                        'ano'=>  $this->ano 
                        );
        $cadena_sql = $this->sql->cadena_sql("espacios_borrados", $variables); 
        return $espacios_borrados= $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda"); 
        
    }        

    
    function bucarPlanMenosAprobadosMenosInscritosEquivalentesMenosBorrados($planMenosAprobadosMenosInscritosEquivalentes){
       
           foreach ($this->espaciosBorrados as $espacioBorrado)
            {
             foreach ($this->espaciosEquivalentes as $espacioEquivalente){     
                     if($espacioBorrado['CODIGO']==$espacioEquivalente['CODIGO'] || $espacioBorrado['CODIGO']==(isset($espacioEquivalente['ASI_COD_ANTERIOR2'])?$espacioEquivalente['ASI_COD_ANTERIOR2']:'') || $espacioBorrado['CODIGO']==(isset($espacioEquivalente['ASI_COD_ANTERIOR2'])?$espacioEquivalente['ASI_COD_ANTERIOR']:'')){                                                
                        $mostrar[]=$espacioEquivalente['ASI_COD_ANTERIOR']; 
                        $mostrar[]=$espacioEquivalente['ASI_COD_ANTERIOR2'];   
                        $mostrar[]=$espacioEquivalente['CODIGO'];
                 
            }
                }            
            }
        
       $resultado_espacios=array_diff($planMenosAprobadosMenosInscritosEquivalentes,$mostrar);  
      return $resultado_espacios;   
    }
 
    /**
     * Funcion que permite consultar los espacios equivalentes de los permitidos  para cursar por el estudiante
     * @return <array>
     */
    function consultarEspaciosEquivalentes($datosEquivalencia) {
          $cadena_sql = $this->sql->cadena_sql("espacios_equivalentes", $datosEquivalencia);
          return $resultado_planEstudio = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
      }

  }
?>