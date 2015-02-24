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

class funcion_adminBuscarEspacioInscripcionesEstudiante extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $espaciosPlan;
  private $espaciosCursados;
  private $espaciosPorCursar;
  private $espaciosInscritos;
  private $requisitosPlan;
  private $espaciosEquivalentes;
  private $espaciosInscritosEquivalentes;
  private $espaciosBorrados;
  private $espaciosParaInscribir;
  private $espaciosPorNiveles;
  private $espaciosPorRequisitos;
  private $espaciosPorNivelesRequisitos;
  private $espaciosNoInscribir;
  

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/administrarModulo.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");

    $this->configuracion = $configuracion;
    $this->cripto = new encriptar();
    $this->procedimientos=new procedimientos();
    //$this->tema = $tema;
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
    $this->formulario = "admin_buscarGruposProyectosEstudiante";
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
        
    $codEstudiante = $this->usuario;    //var_dump($codEstudiante); 
    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante); 
    $this->consultaEspaciosPermitidos();

    
    ?><table width="70%" align="center" border="0" >
        <tr class="bloquelateralcuerpo">
          <td class="centrar">
            <?
            //$this->enlaceHorario();
            ?>
          </td>
        </tr>
      </table>
<a name="1"></a>
<BR><BR><a class="scroll" href="#2">Ver espacios que no puede inscribir</a><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/down2.png" width="15" height="10" border="0">
    <?            
      if (is_array($this->espaciosPorCursar))
        {
          if (is_array($this->espaciosParaInscribir))
          {
              $this->mostrarEspaciosPorCursar('ESPACIOS PERMITIDOS','Adicionar',$this->espaciosParaInscribir,'inscribir');
          }
          if (is_array($this->espaciosNoInscribir))
          {
              $this->mostrarEspaciosPorCursar('ESPACIOS QUE NO PUEDE INSCRIBIR ','Observaci&oacute;n',$this->espaciosNoInscribir,'');
          }
        }
        else
          {
          $this->mensajeNoEspacios();

          }
        //$this->retorno($_REQUEST);
    }


    
    /**
     * Funcion que muestra los espacios academicos que se pueden registrar al estudiante
     * listadoEspacios es una matriz de los espacios que puede inscribir al estudiante
     * @param <array> $listadoEspacios (CODIGO,NOMBRE,NIVEL,ELECTIVA,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     */
    function mostrarEspaciosPorCursar($titulo,$nombreObservacion,$espaciosAMostrar,$observacion=''){
        $ancho ='18%';
        $this->buscarEspaciosEquivalentes();
        $cancelados=$this->buscarEspaciosCancelados();
        ?>
        <div class="tablaEspacios" align="left">
            <div class="espacios_permitidos" ><b><?=$titulo?></b></div>
            <?                     
            foreach ($espaciosAMostrar as $nivelEspacio)
            {
                $resultado[]=$nivelEspacio['NIVEL'];             
            }
            sort($resultado);
            $niveles=array_unique($resultado);
            foreach ($niveles as $nivel)
            {
                ?>
                <div class="niveles" align="center">PER&Iacute;ODO DE FORMACI&Oacute;N <?echo $nivel ?></div>
                <div class="columna0" style="width: 100%; text-align:center;">
                <div class="columna2" style="width:15%">C&oacute;digo Espacio</div>
                <div class="columna2" style="width:46%">Nombre Espacio</div>
                <?
                if ($nombreObservacion=='Adicionar')
                {
                    if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
                    {
                        $ancho='12%';
                        ?>
                        <div class="columna2" style="width:<?echo $ancho;?>">Cr&eacute;ditos</div>            
                    <div class="columna2" style="width:<?echo $ancho;?>">Clasificaci&oacute;n</div>
                        <div class="columna2" style="width:15%"><?echo $nombreObservacion;?></div>
                <?
                }else
                    {
                            ?> <div class="columna2" style="width:<?echo $ancho;?>">Clasificaci&oacute;n</div> 
                               <div class="columna2" style="width:21%"><?echo $nombreObservacion;?></div><?}?>
           
                <?
                }else
                    {
                        ?><div class="columna2" style="width:39%"><?echo $nombreObservacion;?></div><?
                    }?>
                </div>
                <?
                foreach ($espaciosAMostrar as $espacio)
                {
                    if($nivel==$espacio['NIVEL'])
                    {
                        if (trim(isset($espacio['ELECTIVA'])?$espacio['ELECTIVA']:'') == 'S') { //CAMBIAR POR ELECTIVA
                            $clasificacion = "<font color='#088A08'>Electivo</font>";
                        } else {
                            $clasificacion = 'Obligatorio';
                        }
                        ?>
                        <div class="cuadro_clase" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" style="width: 100%">
                        <div class='cuadro_plano_centrar' style="width:15%"><? echo $espacio['CODIGO'];?></div>
                        <div class='cuadro_espacio' style="width:46%"><? echo htmlentities($espacio['NOMBRE']); ?></div>
                        <?
                        if ($nombreObservacion=='Adicionar')
                        {
                            if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
                            {
                                $clasificacion=(isset($espacio['CLASIFICACION'])?$espacio['CLASIFICACION']:'');
                                ?>
                                <div class='cuadro_plano_centrar' style="width:<?echo $ancho;?>"><? echo $espacio['CREDITOS'];?></div>            
                            <?
                            }
                        if(is_array($cancelados))
                        {
                            $respuesta=$this->mensajeEspacioCancelado($cancelados,$espacio['CODIGO']);                         
                        }else
                            {
                                $respuesta='false';
                            }
?>
                            <div class='cuadro_plano_centrar' style="width:<?echo $ancho;?>"><? echo $clasificacion; ?></div>
                            <div class='cuadro_espacio' id="centrar_let"style="width:<?echo $ancho;?>"><?
                            if($respuesta=='false')
                            {
                                $this->enlaceAdicionar($espacio);
                            }else
                                {
                                    echo $respuesta;
                                }
                            ?></div>
                        <?
                        }else
                            {?>
                                <div class='cuadro_espacio' style="width:36%"><?
                                    if ($espacio['REQUISITOS']==0)
                                    {
                                        echo "El espacio no cumple con requisitos:<br>".$this->NoCumpleRequisito($espacio['CODIGO']);
                                    }
                                    if ($espacio['NIVELES']==0)
                                    {
                                        echo "El espacio supera el m\E1ximo de semestres consecutivos.<br>";
                                    }
                                ?></div><?
                            }?>
                        </div>
                        <?
                        if (is_array($this->espaciosEquivalentes))
                        {
                            foreach ($this->espaciosEquivalentes as $equivalente)
                            {
                                if($equivalente['CODIGO_ESPACIO']==$espacio['CODIGO'])
                                {
                                    ?>
                                    <div class="contenidotabla_equivalencias" onmouseover="this.style.background='#F4FA58'" onmouseout="this.style.background=''">
                                        <div class='cuadro_plano_derecha' style="width:15%"><? echo $equivalente['CODIGO_EQUIVALENCIA'] ?></div>
                                        <div class='cuadro_espacio' style="width:46%"><? echo htmlentities($equivalente['NOMBRE'])?><b> Equivalente</b></div>
                                        <?if ($nombreObservacion=='Adicionar')
                                        {
                                        ?>
                                            <div class='cuadro_plano_centrar' style="width:<?echo $ancho;?>"><? echo $clasificacion; ?></div>
                                            <div class='cuadro_espacio' style="width:<?echo $ancho;?>"><?$this->enlaceAdicionar($equivalente);?>
                                            </div>
                                        <?}else
                                            {
                                            ?>
                                                <div class='cuadro_espacio' style="width:36%"><?
                                                if ($espacio['REQUISITOS']==0)
                                                {
                                                    echo "El espacio no cumple con requisitos:<br>".$this->NoCumpleRequisito($espacio['CODIGO'])."<br>";
                                                }
                                                if ($espacio['NIVELES']==0)
                                                {
                                                    echo "El espacio supera el m\E1ximo de semestres consecutivos.<br>";
                                                }
                                            ?></div><?
                                            }?>
                                    </div><?
                                }
                            }                            
                        }                            
                    }else{}
                }
            }
          ?>
        </div><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/up2.png" width="15" height="10" border="0"><a class="scroll" href="#1">Volver Arriba</a><br><br><a name="2">
    <?
    }

    
    /**
     * Funcion que genera el enlace para regresar al horario del estudiante
     */
    function enlaceHorario() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        //$variable=$this->variablesRetorno();
        ?>
      <div>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                '<?
                        $variable = "pagina=admin_consultarInscripcionesEstudiante";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codProyecto=" . $this->datosEstudiante[0]['COD_CARRERA'];
                        $variable.="&planEstudio=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
                        $variable.="&codProyectoEstudiante=" . $this->datosEstudiante[0]['COD_CARRERA'];
                        $variable.="&planEstudioEstudiante=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
                        $variable.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];
                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                        echo $pagina . $variable;
                 ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/vcalendar.png" width="35" height="35" border="0"><br>
          <b>Horario Estudiante</b
            </button>					
        </div>
        
        <?
    }

    /**
     * Funcion que genera las variables para enlaces de retorno
     * @return <type>
     */
    function variablesRetorno() {

        $variable = "pagina=admin_consultarInscripcionesEstudiante";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyectoEstudiante=" . $this->datosEstudiante[0]['COD_CARRERA'];
        $variable.="&planEstudioEstudiante=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
        $variable.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];

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
        <div>
            <button class="botonEnlacePreinscripcion" onclick="window.location = 
                '<?
                        $variable="pagina=admin_consultarInscripcionesEstudiante";
                        $variable.="&opcion=consultar";
                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                        echo $pagina . $variablesPag;
                 ?>'
                "><center><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="25" height="25" border="0"><br>
          <b>Regresar</b
            </button>					
            </div>

<?
      }

    /**
     * Funcion que muestra enlace para adicionar un espacio
     * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,estado_est)
     */
    function enlaceAdicionar($espacio) {
                  $parametros='';
                  $parametro = "=";
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=".$this->formulario;
                  $variables.="&opcion=validar";
                  $variables.="&action=".$this->formulario;
                  $variables.="&destino=registro_inscribirEspacioInscripcionesEstudiante";
                  $parametros.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];
                  $parametros.="&codProyectoEstudiante=" . $this->datosEstudiante[0]['COD_CARRERA'];
                  $parametros.="&planEstudioEstudiante=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
                  $parametros.="&estado_est=" . trim($this->datosEstudiante[0]['ESTADO']);
                  $parametros.="&tipoEstudiante=" . trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']);
                  $parametros.="&codEspacio=" . (isset($espacio['CODIGO'])?$espacio['CODIGO']:$espacio['CODIGO_EQUIVALENCIA']);
                  $parametros.="&nombreEspacio=" . $espacio['NOMBRE'];
                  $parametros.="&creditos=" . (isset($espacio['CREDITOS'])?$espacio['CREDITOS']:'');
                  $parametros.="&numeroSemestres=".(isset($_REQUEST['numeroSemestres'])?$_REQUEST['numeroSemestres']:'');
                  $parametros.="&creditosInscritos=".(isset($_REQUEST['creditosInscritos'])?$_REQUEST['creditosInscritos']:'');
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
        
        $this->espaciosPorCursar=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'],true);  
        if(is_array($this->espaciosPorCursar)){
        foreach ($this->espaciosPorCursar as $espacio)
        {
            if($espacio['REQUISITOS']==1&&$espacio['NIVELES']==1)
            {
                $inscritos=$this->buscarEspaciosInscritos();                
                if(is_array($inscritos))
                {
                    $noInscribir=0;
                    foreach ($inscritos as $fila)
                    {
                        if ($espacio['CODIGO']==$fila['CODIGO'])
                        {
                            $noInscribir=1;
                        }
                    }
                    if ($noInscribir==0)
                    {
                $this->espaciosParaInscribir[]=$espacio;
                    }
                }else
                    {
                        $this->espaciosParaInscribir[]=$espacio;
                    }
            }elseif($espacio['REQUISITOS']==1&&$espacio['NIVELES']==0)
                {
                    $this->espaciosNoInscribir[]=$espacio;
                }elseif($espacio['REQUISITOS']==0&&$espacio['NIVELES']==1)
                    {
                        $this->espaciosNoInscribir[]=$espacio;
                    }elseif($espacio['REQUISITOS']==0&&$espacio['NIVELES']==0)
                        {
                            $this->espaciosNoInscribir[]=$espacio;
                        }
        }

     }
      
   }
      
       /**
     * Funcion que retorna los datos de un espacio si existe
     * @param <array> $datos ()
     * @return <array/string> 
     */
    function buscarEspaciosInscritos() {
        $datosEstudiante=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                                'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                                'ano'=>  $this->datosEstudiante[0]['ANO'],
                                'periodo'=>  $this->datosEstudiante[0]['PERIODO']);
        $espaciosInscritos=$this->procedimientos->buscarEspaciosInscritos($datosEstudiante);
        return $espaciosInscritos;
    }      
    
   /**
     * Funcion que retorna los datos de un espacio cancelado
     * @param <array> $datos ()
     * @return <array/string> 
     */
 function buscarEspaciosCancelados() {
     $espaciosCancelados=json_decode($this->datosEstudiante[0]['CANCELADOS'],true);
     return $espaciosCancelados;
    }
    
    
 function mensajeEspacioCancelado($cancelados,$espacio){
            $cancelado=0;
            foreach ($cancelados as $espacios)
        {
                if($espacios==$espacio) 
            {
                    $cancelado=1;
                    break;
            }
            }
            if($cancelado==1)
            {
                return 'El espacio ha sido cancelado.';
        }else
            {
                    return 'false';
            }
    }
    function buscarEspaciosEquivalentes(){
        $this->espaciosEquivalentes=json_decode($this->datosEstudiante[0]['EQUIVALECIAS'],true);        
    }      
    


   
   function consultarEspaciosInscritosEquivalentes(){
     
        $codigoEspaciosEquivalentes=isset($codigoEspaciosEquivalentes)?$codigoEspaciosEquivalentes:'';
              
        $codigoEquivalente='';
        if (is_array($this->espaciosEquivalentes)) 
        {
            foreach ($this->espaciosEquivalentes as $espacioEquivalente)
                {
                    $codigoEspaciosEquivalentes[]=$espacioEquivalente['CODIGO']; 
                    if (is_array($this->espaciosInscritos))
                    {
                        foreach ($this->espaciosInscritos as $inscritos)
                        {
                            if($espacioEquivalente['CODIGO']==$inscritos)
                            {
                                $codigoEquivalente[]=$espacioEquivalente['ASI_COD_ANTERIOR'];
                                $codigoEquivalente[]=(isset($espacioEquivalente['ASI_COD_ANTERIOR2'])?$espacioEquivalente['ASI_COD_ANTERIOR2']:'');
                            }
                        }
                    }
                }
        }else
            {
                $codigoEquivalente='';
            }
           return $codigoEquivalente;       
    }
    
   
   function consultarDatosEstudiante($codEstudiante){        
    $variables =array('codEstudiante'=>$codEstudiante,
                        'ano'=>  $this->ano,
                        'periodo'=>  $this->periodo);
    $cadena_sql=$this->sql->cadena_sql("carga", $variables);
    return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    
    }
  
    function NoCumpleRequisito($requisito){
        $resultado='';
 
        $this->espaciosNoCumpleRequisitos=json_decode($this->datosEstudiante[0]['REQUISITOS_NO_APROBADOS'],true);        //var_dump($this->espaciosNoCumpleRequisitos);
          foreach($this->espaciosNoCumpleRequisitos as $noCumple)
          {                          
            if($requisito == $noCumple['CODIGO'])
            {
                $resultado.=$noCumple['REQUISITO']."-".$noCumple['NOMBRE']."<br>";
            }
          }
         
       //var_dump($resultado);
      return $resultado;
        
    }

  }
?>