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

class funcion_admin_buscarEspacioPreinscripcionDemandaEstudiante extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $espaciosPlan;
  private $espaciosCursados;
  private $espaciosPreinscritos;
  private $calcularInscritos;
  private $OBEst;
  /**
   * Esta el la busqueda de los requisitos registrados para el plan de estudios
   * @var type 
   */
  private $requisitosPlan;
  private $espaciosAprobados;
  public $sesion;


  
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
   // include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");

    $this->configuracion = $configuracion;
    $this->validacion = new validarInscripcion();
    $this->cripto = new encriptar();
    $this->fechas=new validar_fechas();
    $this->procedimientos=new procedimientos();
    //$this->tema = $tema;
    $this->sql = $sql;
    $this->sesion = new sesiones($configuracion);
    $obj_sesion=$this->sesion;
    $this->datosEstudiante=array('codProyectoEstudiante'=>$_REQUEST['codProyectoEstudiante'],
                                 'planEstudioEstudiante'=>$_REQUEST['planEstudioEstudiante'],
                                 'codEstudiante'=>$_REQUEST['codEstudiante'],
                                 'creditosInscritos'=>$_REQUEST['creditosInscritos'],
                                 'estado_est'=>$_REQUEST['estado_est'],
                                 'tipoEstudiante'=>$_REQUEST['tipoEstudiante']);

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Datos de sesion
    $this->formulario = "registro_preinscribirEspacioDemandaEstudiante";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    //Conexion ORACLE
    if ($this->nivel==51){
      $this->accesoOracle = $this->conectarDB($configuracion,"estudiante");
      $this->codEstudiante=$this->rescatarValorSesion($configuracion,$this->acceso_db,"id_usuario");
    }
    elseif ($this->nivel==52){
      $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");
      $this->codEstudiante=$this->rescatarValorSesion($configuracion,$this->acceso_db,"id_usuario");
    }

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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
     * Funcion que presenta los espacios que puede adicionar el estudiante
     * Utiliza los metodos mostrarEspacios, retorno
     * 
     */
    function buscarEspaciosPermitidos() {

    $codEstudiante = $this->usuario;    
    $this->datosEstudiante=$this->consultarDatosEstudiante($codEstudiante);
    $variablesInscritos = array('codEstudiante' => $this->datosEstudiante[0]['CODIGO'],
                'ano' => $this->ano,
                'periodo' => $this->periodo,
                'codProyectoEstudiante' => $this->datosEstudiante[0]['COD_CARRERA'],
                'planEstudioEstudiante' => $this->datosEstudiante[0]['PLAN_ESTUDIO']);
    $this->consultarEspaciosPermitidos($variablesInscritos);
    
    $parametros=$this->consultarParametros();   // var_dump($parametros);exit;   
  
    
    ?><table width="70%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar">
        <?
            //$this->enlaceHorario();
         ?>
         </td>
     </tr>
      </table>
    <?   
    if (is_array($this->espaciosPorCursar)){
      if (isset($this->espaciosParaInscribir)&&is_array($this->espaciosParaInscribir))
          {
              $this->mostrarEspaciosPorCursar('ESPACIOS PERMITIDOS','Adicionar',$this->espaciosParaInscribir,'inscribir',$parametros);
          }else
            {
                $this->mensajeNoEspacios();
            }
      if (isset($this->espaciosNoInscribir)&&is_array($this->espaciosNoInscribir))
          {
              $this->mostrarEspaciosPorCursar('ESPACIOS QUE NO PUEDE PREINSCRIBIR ','Observaci&oacute;n',$this->espaciosNoInscribir,'',$parametros);
          }
        
    }else
        {
        $this->mensajeNoEspacios();
        }
  }  

  function consultarDatosEstudiante($codEstudiante){        
    $variables =array('codEstudiante'=>$codEstudiante,
                    'ano'=>  $this->ano,
                    'periodo'=>  $this->periodo); 
    $cadena_sql=$this->sql->cadena_sql("carga", $variables); 
    return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    
    }
    
      
   /**
     * Funcion que muestra los espacios academicos que se pueden registrar al estudiante
     * listadoEspacios es una matriz de los espacios que puede inscribir al estudiante
     * @param <array> $listadoEspacios (CODIGO,NOMBRE,NIVEL,ELECTIVA,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     */
    function mostrarEspaciosPorCursar($titulo,$nombreObservacion,$espaciosAMostrar,$observacion='',$parametros){ 
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");    
        $ancho ='18%';
        $this->buscarEspaciosEquivalentes();
        $cancelados=$this->buscarEspaciosCancelados();        
        ?>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.js"></script>
        <script type="text/javascript">
        var p=jQuery.noConflict();
        
        p(document).ready(function() {
                globalVar = new Array();
                p('.checkbox_creditos').each(function () {
                globalVar.push(p(this).val());
                });
                
        });
        
        function chequea(id)
            {
                var valor=0;
                var texto;
                var tipo_estudiante='<?php echo $this->datosEstudiante[0]['TIPO_ESTUDIANTE']; ?>';
              
                p('.checkbox_creditos').each(function () {
                if (this.checked) {
                    valor=parseInt(valor)+parseInt(p(this).val());
                    var creditos=<?php echo $parametros - $this->calcularInscritos;?>;
                if(valor<creditos && tipo_estudiante=='S'){
                var total=creditos-valor;
                texto='Vamos bien, te quedan '+total+' creditos.';
                p('#boton_enviar').html('<input style="border:none;background-color:#000;color:#FFF;padding:2px;border-radius:5px 5px 5px 5px;cursor:pointer;" type="button" value="Enviar datos" style="background-color: #DDD;cursor: pointer;" onclick="enviar()" />');
                }
                if(valor<creditos && tipo_estudiante=='N'){
                var total=creditos-valor;
                texto='Vamos bien, te quedan '+total+' espacios.';
                p('#boton_enviar').html('<input style="border:none;background-color:#000;color:#FFF;padding:2px;border-radius:5px 5px 5px 5px;cursor:pointer;" type="button" value="Enviar datos" style="background-color: #DDD;cursor: pointer;" onclick="enviar()" />');
                }
                if(valor==creditos && tipo_estudiante=='S'){
                texto='Ya Inscribiste el máximo de créditos.';
                p('#boton_enviar').html('<input style="border:none;background-color:#000;color:#FFF;padding:2px;border-radius:5px 5px 5px 5px;cursor:pointer;" type="button" value="Enviar datos" style="background-color: #DDD;cursor: pointer;" onclick="enviar()" />');
                }
                if(valor==creditos && tipo_estudiante=='N'){
                texto='Ya Inscribiste el máximo de espacios.';
                p('#boton_enviar').html('<input style="border:none;background-color:#000;color:#FFF;padding:2px;border-radius:5px 5px 5px 5px;cursor:pointer;" type="button" value="Enviar datos" style="background-color: #DDD;cursor: pointer;" onclick="enviar()" />');
                }
                if(valor>creditos && tipo_estudiante=='S'){
                var total=creditos-(valor-p(id).val());
                texto='Puedes registrar solo '+total+' credito(s) mas.';
                p('#boton_enviar').html('<input style="border:none;background-color:#000;color:#FFF;padding:2px;border-radius:5px 5px 5px 5px;cursor:pointer;" type="button" value="Enviar datos" style="background-color: #DDD;cursor: pointer;" onclick="enviar()" />');
                p(id).removeAttr("checked");
                }
                if(valor>creditos && tipo_estudiante=='N'){
                var total=creditos-(valor-p(id).val());
                texto='Puedes registrar solo '+total+' espacio(s) mas.';
                p('#boton_enviar').html('<input style="border:none;background-color:#000;color:#FFF;padding:2px;border-radius:5px 5px 5px 5px;cursor:pointer;" type="button" value="Enviar datos" style="background-color: #DDD;cursor: pointer;" onclick="enviar()" />');
                p(id).removeAttr("checked");
                }
                }
                
                else
       {
       if(valor=='0')
       {
       texto='Seleccione un espacio académico';    
       }
       }
                
                });
                p('#mensaje_texto').html(texto);
                p('#mensaje').fadeIn();

            }

            function enviar()
            {
                var valor=0;
                var estado;
                
                var arreglo_real=globalVar;
                var arreglo_nuevo= new Array();
                
                p('.checkbox_creditos').each(function () {
                arreglo_nuevo.push(p(this).val());
                if (this.checked) {
                    valor=parseInt(valor)+parseInt(p(this).val());
                    var creditos=<?php echo $parametros - $this->calcularInscritos;?>;
                if(valor<creditos){ estado=1; }
                if(valor==creditos){ estado=1; }
                if(valor>creditos){ estado=0; texto='No puedes inscribir.'; 
                p('#boton_enviar').html('');
                p('#mensaje_texto').html(texto);
                p('#mensaje').fadeIn();
                
                 }
                }
                });
                if(estado=='1')
                {
                var variable_envio=1;
                
                var temp = new Array();
                if ( (!arreglo_real[0]) || (!arreglo_nuevo[0]) ) { // If either is not an array
                    variable_envio=0;
                }
                if (arreglo_real.length != arreglo_nuevo.length) {
                    variable_envio=0;
                }
                // Put all the elements from arreglo_real into a "tagged" array
                for (var i=0; i<arreglo_real.length; i++) {
                    key = (typeof arreglo_real[i]) + "~" + arreglo_real[i];
                // Use "typeof" so a number 1 isn't equal to a string "1".
                    if (temp[key]) { temp[key]++; } else { temp[key] = 1; }
                // temp[key] = # of occurrences of the value (so an element could appear multiple times)
                }
                // Go through arreglo_nuevo - if same tag missing in "tagged" array, not equal
                for (var i=0; i<arreglo_nuevo.length; i++) {
                    key = (typeof arreglo_nuevo[i]) + "~" + arreglo_nuevo[i];
                    if (temp[key]) {
                        if (temp[key] == 0) { variable_envio=0; } else { temp[key]--; }
                    // Subtract to keep track of # of appearances in arreglo_nuevo
                    } else { // Key didn't appear in arreglo_real, arrays are not equal.
                        variable_envio=0;
                    }
                }
        
                if(variable_envio=='1')
                {
                document.registro_preinscribirEspacioDemandaEstudiante.submit();    
                }
                else
                {
                var texto='No hackear la página por favor :( Recarga de nuevo';
                p('#boton_enviar').html('');
                p('#mensaje_texto').html(texto);
                p('#mensaje').fadeIn();
                }
                    
                }

            }
            
            function cerrar()
            {
                p('#mensaje').fadeOut(1500);
            }
            
            </script>
        
        <div style="-webkit-box-shadow: 2px 2px 2px 2px #CCC;box-shadow: 2px 2px 2px 2px #CCC;position: fixed;background-color: #FFF;margin: auto;border: 1px solid #CCC;border-radius:10px 10px 10px 10px;width: 270px;display: none;padding: 5px;text-align: center;margin-left: 20%;" id="mensaje">
        <div id="mensaje_cerrar" align="right" style="cursor: pointer;"><span onclick="cerrar()" style="color:green;border-radius: 20px 20px 20px 20px;display: block;margin-left: 260px;padding: 2px;border: 1px solid #CCC;width: 10px;text-align: center;position: absolute;margin-top: -10px;background-color: white;">x</span></div>
        <div id="mensaje_texto" style="color: #000;"></div>
        <div id="boton_enviar"></div>
        </div>
        
        <?php
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $parametros='';
        $parametro = "=";
        $variables = "pagina=registro_preinscribirEspacioDemandaEstudiante";
        $variables.="&opcion=verificarPreinscripcion";
        $variables.="&destino=registro_preinscribirEspacioDemandaEstudiante";
        $parametros.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];
        $parametros.="&codProyectoEstudiante=" . $this->datosEstudiante[0]['COD_CARRERA'];
        $parametros.="&planEstudioEstudiante=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
        $parametros.="&estado_est=" . trim($this->datosEstudiante[0]['ESTADO']);
        $parametros.="&tipoEstudiante=" . trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']);
        $parametros.="&codEspacio=" . (isset($espaciosAMostrar[0]['CODIGO'])?$espaciosAMostrar[0]['CODIGO']:$espaciosAMostrar[0]['CODIGO_EQUIVALENCIA']);
        $parametros.="&nombreEspacio=" . $espaciosAMostrar[0]['NOMBRE']; 
        $parametros.="&creditos=" . (isset($espaciosAMostrar[0]['CREDITOS'])?$espaciosAMostrar[0]['CREDITOS']:'');
        //$parametros.="&numeroSemestres=".trim($this->datosEstudiante[0]['numeroSemestres']);
        $parametros.="&parametro=".$parametro;
        $variable = $variables . $parametros; 
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        ?>
        
        <?php
        if($titulo=='ESPACIOS PERMITIDOS')
        {
        ?>
        
        <form  method='POST' action='index.php' name='<? echo $this->formulario; ?>'>
          <?php
            //$pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            //$variable = "pagina=".$this->formulario;
            //$variable.="&opcion=verificarPreinscripcion";
            //$variable.="&codProyectoEstudiante=" . $this->datosEstudiante[0]['COD_CARRERA'];
            //$variable.="&planEstudioEstudiante=" . $this->datosEstudiante[0]['PLAN_ESTUDIO'];
            //$variable.="&codEstudiante=" . $this->datosEstudiante[0]['CODIGO'];
            //$variable.="&estado_est=" . trim($this->datosEstudiante[0]['ESTADO']);
            //$variable.="&tipoEstudiante=" . trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']);
            
         ?> 


            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
            <input type="hidden" name="opcion" value="verificarPreinscripcion">
            <input type="hidden" name="codProyectoEstudiante" value="<?echo  $this->datosEstudiante[0]['COD_CARRERA']?>">
            <input type="hidden" name="planEstudioEstudiante" value="<?echo $this->datosEstudiante[0]['PLAN_ESTUDIO']?>">
            <input type="hidden" name="codEstudiante" value="<?echo $this->datosEstudiante[0]['CODIGO']?>">
            <input type="hidden" name="estado_est" value="<?echo $this->datosEstudiante[0]['ESTADO']?>">
            <input type="hidden" name="tipoEstudiante" value="<?echo $this->datosEstudiante[0]['TIPO_ESTUDIANTE']?>">
<!--            <a href="<?= $pagina . $variable ?>" on></a>-->
        <?php
        }
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
                                        echo "Supera m&aacute;ximo de semestres consecutivos.<br>";
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
                                        <div class='cuadro_plano_centrar' style="width:15%"><? echo $equivalente['CODIGO_EQUIVALENCIA'] ?></div>
                                        <div class='cuadro_espacio' style="width:46%"><? echo htmlentities($equivalente['NOMBRE'])?><b> Equivalente</b></div>
                                        <?if ($nombreObservacion=='Adicionar')
                                        {
                                        ?>
                                            <div class='cuadro_plano_centrar' style="width:<?echo $ancho;?>"><? echo $clasificacion; ?></div>
                                            <?echo $this->enlaceAdicionar($equivalente);?>
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
                                                    echo "Supera m&aacute;ximo de semestres consecutivos.<br>";
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
                
            </div>
            
            <?php
        if($titulo=='ESPACIOS PERMITIDOS')
        {
        ?>
        </form>
        <?php
        }
        ?>
    <?
    }

 /**
  * 
  */   
    function buscarEspaciosEquivalentes(){
        $this->espaciosEquivalentes='';//json_decode($this->datosEstudiante[0]['EQUIVALECIAS'],true);        
    }  
    
/**
* Funcion que retorna los datos de un espacio cancelado
* @param <array> $datos ()
* @return <array/string> 
*/
 function buscarEspaciosCancelados() {
        $variable=array('ano'=>$this->ano, 
                        'periodo'=>$this->periodo,
                        'codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                        'codProyectoEstudiante'=>$this->datosEstudiante[0]['COD_CARRERA']
                      );
        
        $cadena_sql = $this->sql->cadena_sql("consultaPreinscripcionesCanceladasEstudiante", $variable); 
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->espaciosCancelados=$resultado;
        return $resultado;
       
    }
    
/**
 *Funcion 
 * @param type $cancelados
 * @param type $espacio
 * @return string 
 */
    
function mensajeEspacioCancelado($cancelados,$espacio){    
            $cancelado=0;
            foreach ($cancelados as $espacios)
            {
                if($espacios['ASI_CODIGO']==$espacio) 
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
    
  /**
     * Funcion que muestra enlace para adicionar un espacio
     * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,estado_est)
     */
    function enlaceAdicionar($espacio) {        
        ?>
                    <?php
                    if($this->datosEstudiante[0]['TIPO_ESTUDIANTE']=='S'){
                        echo "<div><input class='checkbox_creditos' name='espacio".trim($espacio['CODIGO'])."' type='checkbox' value='".$espacio['CREDITOS']."' onclick='chequea(this)' /></div>";
                        
                        }else{
                           echo "<div><input class='checkbox_creditos' name='espacio".trim($espacio['CODIGO'])."'type='checkbox' value='1' onclick='chequea(this)' /></div>";
                       }
                    ?>
                    
        <?
      }
      
    function NoCumpleRequisito($requisito){
        $resultado='';
 
        $this->espaciosNoCumpleRequisitos=json_decode($this->datosEstudiante[0]['REQUISITOS_NO_APROBADOS'],true);        
          foreach($this->espaciosNoCumpleRequisitos as $noCumple)
          {                          
            if($requisito == $noCumple['CODIGO'])
            {
                $resultado.=$noCumple['REQUISITO']."-".(isset($noCumple['NOMBRE'])?$noCumple['NOMBRE']:'')."<br>";
            }
          }

      return $resultado;
        
    }
    
  /**
     * Funcion que permite consultar los espacios permitidos para cursar por el estudiante
     * @return <array>
     */
    function consultarEspaciosPermitidos($variablesInscritos) {
        $this->espaciosPorCursar=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'],true);
        if(is_array($this->espaciosPorCursar)){
            $inscritos=$this->consultarEspaciosInscritos($variablesInscritos);                   
            $this->calcularInscritos= $this->calcularInscritos($inscritos); 
        foreach ($this->espaciosPorCursar as $espacio)
        {
            if($espacio['REQUISITOS']==1&&$espacio['NIVELES']==1)
            {
                if(is_array($inscritos))
                {
                    $noInscribir=0;
                    foreach ($inscritos as $fila)
                    {                   
                        if ($espacio['CODIGO']==$fila['ASI_CODIGO'])
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
  function consultarEspaciosInscritos($variablesInscritos) {     
        $espaciosInscritos=$this->procedimientos->buscarEspaciosInscritosPreinscripcion($variablesInscritos);        
        return $espaciosInscritos;
  }

 function consultarParametros(){
    $this->parametros=json_decode($this->datosEstudiante[0]['PARAMETROS'],true); //var_dump($this->parametros);exit;
    if($this->datosEstudiante[0]['TIPO_ESTUDIANTE']=='S'){
      $parametros=$this->parametros['maxcreditos'];       
    }else{
            $parametros=$this->parametros['maxespacios'];
         }
    $this->porcentajeParametros();     
    return $parametros;
}

function porcentajeParametros(){
    
    $cadenaCreditosAprobados=json_decode($this->datosEstudiante[0]['CREDITOS_APROBADOS'],true);  // var_dump($cadenaCreditosAprobados);exit; 
    $totalCreditosEst=$cadenaCreditosAprobados['total'];
    $this->OBEst=$cadenaCreditosAprobados['OB']; 
    $OCEst=$cadenaCreditosAprobados['OC']; 
    $EIEst=$cadenaCreditosAprobados['EI']; 
    $EEEst=$cadenaCreditosAprobados['EE']; 
    $CPEst=$cadenaCreditosAprobados['CP'];
    //echo $totalCreditosEst;exit;
}

  /**
   * Funcion que calcula el numero de espacios inscritos al estudiante en el periodo actual
   * @param <array> $registroGrupo (CODIGO,NOMBRE,CREDITOS,ELECTIVA,GRUPO)
   * @return <int> $suma
   */
  function calcularInscritos($datosInscritos) {
      $resultado=0;
      if(is_array($datosInscritos))
      {
        if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
        {
            foreach ($datosInscritos as $espacio)
            {                
                $resultado+=$espacio['CREDITOS'];
            }
        }else
            {
                $resultado=count($datosInscritos); 
            }
      }else
          {
            
          } 
      return $resultado;
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
                <th class='cuadro_plano centrar' colspan="6">No se encontraron espacios acad&eacute;micos para preinscribir.</th>
              </tr>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
            </table>
          <?
      }

  }
?>
