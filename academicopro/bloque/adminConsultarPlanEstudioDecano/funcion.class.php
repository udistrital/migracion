<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/planEstudios.class.php");

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_adminConsultarPlanEstudioDecano extends funcionGeneral {

  public $configuracion;
  private $ob;
  private $oc;
  private $ei;
  private $ee;
  private $cp;
  private $creob;
  private $creoc;
  private $creei;
  private $creee;
  private $crecp;
  private $datosPlan;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminConsultarPlanEstudioDecano();
        $this->log_us= new log();
        $this->parametrosPlan=new planEstudios();
        $this->formulario="adminConsultarPlanEstudioDecano";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->ob=0;
        $this->oc=0;
        $this->ei=0;
        $this->ee=0;
        $this->cp=0;
        $this->creob=0;
        $this->creoc=0;
        $this->creei=0;
        $this->creee=0;
        $this->crecp=0;
        $this->datosPlan=array();

    }#Cierre de constructor

    /**
     * Funcion que muestra el menu de opciones para el usuario
     * @param <type> $configuracion
     */
    function menuDecano()
    {
     if (!isset($this->datosPlan[0]['PLAN']))
     {
         $this->datosPlan=$this->consultarDatosPlan("");
     }
        ?>
        <table class="contenidotabla centrar" width="100%" >
            <tr class="centrar">
                <td colspan="2" class="centrar">
                    <?
                    $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarPlanEstudioDecano";
                    $ruta.="&opcion=ver";

                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
                    <a href="<?= $indice.$ruta ?>">
                        <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                        <br>Ver Planes de Estudios
                    </a>
                </td>
                <td colspan="2" class="centrar">
                <?
                    $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarElectivosExtrinsecosDecano";
                    $ruta.="&opcion=ver";

                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
                <a href="<?= $indice.$ruta ?>">
                    <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Electivos Extrinsecos">
                    <br>Portafolio de<br>Electivos Extr&iacute;nsecos
                </a>
            </td>
                <?
            if(!is_numeric($this->datosPlan[0]['PROYECTO_NOMBRE'])&&isset($this->datosPlan[0]['PROYECTO_NOMBRE'])){
            ?>
            <td colspan="2" class="centrar" width="25%">
                <?
                    $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=adminVerRequisitosDecano";
                    $ruta.="&opcion=visualizar";
                    $ruta.="&planEstudio=".$this->datosPlan[0]['PLAN'];
                    $ruta.="&nombreProyecto=".$this->datosPlan[0]['PROYECTO_NOMBRE'];
                    $ruta.="&codProyecto=".$this->datosPlan[0]['PLAN'];

                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>

                <a href="<?= $indice.$ruta ?>">
                    <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                    <br>Requisitos del<br>Plan de Estudios
                </a>
            </td>
            <?
            }
            ?>
            </tr>

        </table>
        <?
    }

   /**
    * Esta funcion busca los planes de estudio asociados al coordinador
    * @param <type> $configuracion
    */
    function verRegistro() {
      $this->encabezadoModulo();
      $variable="";
      #Consulta por planes de estudios existentes
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"listaPlanesEstudio",$variable,'');
      $registro=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

      $this->listaMallas($registro);
    }#Cierre de funcion verRegistro

    /**
     * Esta funcion muestra los planes de estudio que existen
     * @param <type> $configuracion
     * @param <type> $registro
     */
    function listaMallas($registro) {
        $this->menuDecano();
                ?>
<table  class="contenidotabla centrar" width="100%" >
    <tr class="texto_subtitulo">
        <td class="cuadro_color centrar" colspan="6">
            <hr class="hr_subtitulo">
        </td>
    </tr>
        <? if (is_array($registro)) {
                        ?>
    <tr class='bloquecentralcuerpo'>

        <td width="10%" align="center"><strong>C&oacute;digo</strong></td>
        <td align="center"><strong>Proyecto Curricular</strong></td>
        <td width="10%" align="center"><strong>Ver</strong></td>

    </tr>
            <? for($i=0; $i<count($registro); $i++) {
                        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=adminConsultarPlanEstudioDecano";
                        $ruta.="&opcion=buscarPlan";
                        $ruta.="&proyecto=".$registro[$i][6];
                        $ruta.="&nombreProyecto=".$registro[$i][1];
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        if($registro[$i][8]!=(isset($registro[$i-1][8])?$registro[$i-1][8]:''))
        {
            $this->cadena_sql = $this->sql->cadena_sql($this->configuracion, "facultad", $registro[$i][8]);
            $facultad = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

          ?><tr><td colspan="6"><hr><?echo $facultad[0]['FACULTAD']?></td></tr><?
        }
        else{

        }
                    ?>
    <tr class='bloquecentralcuerpo' onclick="location.href='<?=$indice . $ruta ?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''" alt="Proyecto">
        <td width="10%" align="center"><?= $registro[$i][7]?></td>
        <td>
                <?= $registro[$i][1]?>
        </td>
        <td align="center">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/viewrel.png" border="0">
        </td>
    </tr>
                <?
                }#Cierre de for
            }#Cierre de is_array($registro)
            else {?>
    <tr>
        <td class='bloquecentralcuerpo' align="center"><strong>No exiten planes de estudio</strong></td>
    </tr>
            <? }#Cierre de else  is_array($registro)
            ?>

</table>
        <?
    }

    /**
     * Esta funcion permite seleccionar la profundizacion para unproyecto con varios planes de estudio
     * @param <type> $configuracion
     * @param <type> $id
     * @param <type> $nombreProyecto
     */

    function buscarEnfasis($id, $nombreProyecto)
    {
        $this->cadena_sql = $this->sql->cadena_sql($this->configuracion, "cantidadPlanes", $id);
        $numeroPlanes = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

        $this->cadena_sql = $this->sql->cadena_sql($this->configuracion, "listaPlanes", $id);
        $registro = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
        if ($numeroPlanes[0][0]>1)
        {
          $this->encabezadoModulo();
          ?>
          <table class="contenidotabla centrar" width="100%" >
            <tr class="texto_subtitulo">
              <td class="cuadro_color centrar" colspan="6">
                    <?
                    echo "Seleccione la Profundizaci&oacute;n del Proyecto ".$nombreProyecto;
                    ?>
                <hr class="hr_subtitulo">
              </td>
            </tr>
          <? if (is_array($registro)) { ?>
            <tr class='bloquecentralcuerpo'>
              <td width="15%" align="center"><strong>C&oacute;digo</strong></td>
              <td width="55%" align="center"><strong>Profundizaci&oacute;n</strong></td>
            </tr>
            <? for ($i = 0;$i < count($registro);$i++) {
                $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $ruta = "pagina=adminConsultarPlanEstudioDecano";
                $ruta.= "&opcion=mostrar";
                //$ruta.= "&opcion=buscarPlan";
                $ruta.= "&planEstudio=" . $registro[$i][0];
                $ruta = $this->cripto->codificar_url($ruta, $this->configuracion);
              ?>
      <tr class='bloquecentralcuerpo' onclick="location.href='<?=$indice . $ruta ?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
        <td width="10%" align="center"><?echo $registro[$i][0] ?></td>
        <td>                           <?echo $registro[$i][1] ?></td>
      </tr>
            <?
            } #Cierre de for

          } #Cierre de is_array($registro)
          else { ?>
      <tr>
        <td class='bloquecentralcuerpo' align="center"><strong>No exiten planes de estudio</strong></td>
      </tr>
          <?
          } #Cierre de else  is_array($registro)

          ?>
          </table>
          <?

        }else
          {
            $this->mostrarRegistro($registro[0][0]);
          }
    }

    /**
     * Esta fucnicon muestra otros planes de estudio
     * @param <type> $this->configuracion
     * @param <type> $planEstudio
     */
    function mostrarRegistro($planEstudio='') {
        if ($planEstudio==''){
            $planEstudio=$_REQUEST['planEstudio'];
          }else
            {

            }
        $id=$planEstudio;
        #Consulta la informacion general del Plan de Estudios
        #$id es el id_carrera
        $this->datosPlan=$this->consultarDatosPlan($id);

        #Consulta los Espacios Academicos del plan de estudios
        $this->encabezadoModulo();
        $this->menuDecano();
        $this->verPlanEstudios();
        #Consulta los Espacios Academicos del plan de estudios
        $niveles=$this->consultarNiveles($planEstudio);

        #Muestra los niveles de un plan de estudios
        $this->listaEspacios($niveles);

    }

    /**
     * Funcion que presenta el encabezado del plan de estudios
     * @param <type> $configuracion
     * @param <type> $registro
     */
    function verPlanEstudios() {

        #enlace regreso  listado de planes
        ?>
<br>
<table class="contenidotabla centrar" width="100%" >
    <tr class="cuadro_color centrar">
        <td colspan="2" class="cuadro_color centrar">
        <?echo $this->datosPlan[0]['PROYECTO_NOMBRE'];?>
            <hr>
        </td>
    </tr>
    <tr>
        <td class="cuadro_color centrar" colspan="2">
            PLAN DE ESTUDIOS EN CR&Eacute;DITOS N&Uacute;MERO <?echo "<strong>".$this->datosPlan[0]['PLAN']." - ".$this->datosPlan[0]['PLAN_NOMBRE']."</strong>"?>
        </td>
    </tr>
</table>
        <?
    }

    /**
     * Funcion que presenta el plan de estudios por niveles
     * @param <type> $niveles
     * @param <type> $registro
     */
    function listaEspacios($niveles) {
      $nivelPropedeutico=6;
      $asociados=$this->consultarEspaciosAsociados($this->datosPlan[0]['PLAN']);
      $lista="0";
      $creditosNivel=0;
      $id_encabezado=0;
      //crea listado de espacios asociados
      if (is_array($asociados))
      {
        foreach ($asociados as $key => $value) {
          $lista.=",".$value['id_espacio'];
        }
      }
      else{

          }
      //Busca encabezados y espacios asociados
      $registroEncabezados=$this->consultarNombresGeneralesNiveles();
      $variablesEspacios=array('planEstudio'=>$this->datosPlan[0]['PLAN'],
                               'lista'=>$lista);
      //Busca espacios del plan que no esten asociados
      $registroEspacios=$this->consultarEspacios($variablesEspacios);
      $this->iniciarTabla();
      if (is_array($niveles)){
        foreach ($niveles as $key => $nivel) {
          $this->iniciarTablaNiveles();
          $this->mostrarEncabezadoNiveles('PERIODO DE FORMACI&Oacute;N '.$nivel[0]);
          if(is_array($registroEncabezados))
          {
              foreach ($registroEncabezados as $key => $value) {
                if ($registroEncabezados[0]['NIVEL']==$nivel[0])
                {
                  $encabezadoEliminado=array_shift($registroEncabezados);
                  if($encabezadoEliminado['ID_ENC']!=$id_encabezado)
                  {
                    $this->mostrarCeldaEncabezado($encabezadoEliminado);
                    if($encabezadoEliminado['COD_CLASIF_ENC']!=4)
                    {
                      if (!is_null($encabezadoEliminado['ID_ESP']))
                      {
                        $this->mostrarCeldaEspaciosEncabezados($encabezadoEliminado);
                      }
                    }
                    $id_encabezado=$encabezadoEliminado['ID_ENC'];
                    $creditosNivel+=$encabezadoEliminado['CRED_ENC'];
                  }else
                    {
                      if($encabezadoEliminado['COD_CLASIF_ENC']!=4)
                      {
                        if (!is_null($encabezadoEliminado['ID_ESP']))
                        {
                          $this->mostrarCeldaEspaciosEncabezados($encabezadoEliminado);
                        }
                      }
                      $id_encabezado=$encabezadoEliminado['ID_ENC'];
                    }
                }else{
                      }
              }
          }else{

               }
          if (is_array($registroEspacios))
          {
            foreach($registroEspacios as $key => $value){
              if ($registroEspacios[0]['NIVEL']==$nivel[0])
              {
                $espacioEliminado=array_shift($registroEspacios);
                $this->mostrarCeldaEspacios($espacioEliminado);
                $creditosNivel+=$espacioEliminado['CRED_ESP'];
              }else{
                    }
            }
          }else {

                }
          $this->mostrarCreditosNivel($creditosNivel);

          $this->cerrarTablaNiveles();
          $creditosNivel=0;
          if ($nivel[0]==$nivelPropedeutico)
          {
            $registroEncabezadosPropedeuticos=$this->consultarNombresGeneralesPropedeutico();
            //Busca espacios del plan que no esten asociados
            $registroProppedeuticos=$this->consultarPropedeuticos($variablesEspacios);
            if(is_array($registroEncabezadosPropedeuticos) || is_array($registroProppedeuticos))
            {
              $this->propedeutico($nivel,$registroEncabezadosPropedeuticos,$registroProppedeuticos);
            }
          }
        }
      }
      else{
            $this->presentarMensajeNoEspacios();
          }
      $this->cerrarTabla();
      if (is_array($niveles)){
      $this->parametrosPlan->presentarAbreviaturas();
      }else
      {

      }
      //$parametros=$this->consultarParametrosPlan($registro[0]['PLAN']);
      $mensajeEncabezado='RANGOS DE CR&Eacute;DITOS INGRESADOS POR EL COORDINADOR ';
      $mensaje='Los rangos de cr&eacute;ditos, corresponden a los datos que el Coordinador registr&oacute; como par&aacute;metros iniciales<br>
                del plan de estudio, seg&uacute;n lo establecido en el art&iacute;culo 12 del acuerdo 009 de 2006.';
      $parametrosAprobados=array(array('ENCABEZADO'=>$mensajeEncabezado,
                                        'MENSAJE'=>$mensaje,
                                        'PROPEDEUTICO'=>$this->datosPlan[0]['PLAN_PROPEDEUTICO']));
      $this->parametrosPlan->mostrarParametrosAprobadosPlan($this->datosPlan[0]['PLAN'],$parametrosAprobados);

      //$this->parametrosPlan->presentarParametrosPlan($parametros);
      //$this->parametrosPlan->presentarBarrasParametrosPlan($parametros);
    }
   
    /**
     * Esta funcion permite iniciar la tabla donde se presenta el plan de estudios
     */
    function iniciarTabla() {
      ?>
        <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
            <tr class="cuadro_plano">
              <td  align="center">
      <?
    }

    /**
     * Esta funcion permite cerrar la tabla donde se presenta el plan de estudios
     */
    function cerrarTabla() {
      ?>
              </td>
            </tr>
        </table>
      <?
    }

    /**
     * Esta funcion muestra el encabezado de cada nivel
     * @param <type> $nivel
     */
    function mostrarEncabezadoNiveles($nivel) {
    ?>
        <tr>
          <td colspan='10' align='center'><h2><?echo $nivel?></h2>
          </td>
        </tr>
        <tr class='cuadro_color'>
          <td class='cuadro_plano centrar' width="7%">Cod.
          </td>
          <td class='cuadro_plano centrar' width="40%">Nombre
          </td>
          <td class='cuadro_plano centrar' width="7%">N&uacute;mero<br>Cr&eacute;ditos
          </td>
          <td class='cuadro_plano centrar' width="7%">HTD
          </td>
          <td class='cuadro_plano centrar' width="7%">HTC
          </td>
          <td class='cuadro_plano centrar' width="7%">HTA
          </td>
          <td class='cuadro_plano centrar' width="25%">Clasificaci&oacute;n
          </td>
        </tr>
    <?
    }

    /**
     * Esta funcion permite iniciar la tabla de cada nivel
     */
    function iniciarTablaNiveles(){
    ?>
      <table class='contenidotabla'>
      <?
    }

    /**
     * Esta funcion permite cerrar la tabla de cada nivel
     */
    function cerrarTablaNiveles(){
    ?>
      </table>
      <?
    }

    /**
     * Esta funcion muestra la celda de datos de cada encabezado en los niveles
     * @param <type> $encabezado
     * @param <type> $planEstudio
     */
    function mostrarCeldaEncabezado($encabezado) {
    ?>
    <tr>
        <td class='cuadro_plano' colspan="2" onclick="" ><font color="#090497"><?echo $encabezado['NOMBRE_ENC']?></font></td>
        <td class='cuadro_plano centrar'><font color="#090497"><?echo $encabezado['CRED_ENC'];?></font></td>
        <td class='cuadro_plano centrar' colspan="3"><?
        switch ($encabezado['COD_CLASIF_ENC'])
                {
                case '1':
                    $this->ob++;
                    $this->creob+=$encabezado['CRED_ENC'];
                    break;
                case '2':
                    $this->oc++;
                    $this->creoc+=$encabezado['CRED_ENC'];
                    break;
                case '3':
                    $this->ei++;
                    $this->creei+=$encabezado['CRED_ENC'];
                    break;
                case '4':
                    $this->ee++;
                    $this->creee+=$encabezado['CRED_ENC'];
                    $this->mostrarEnlaceExtrinsecas();
                    break;
                case '5':
                    $this->cp++;
                    $this->crecp+=$encabezado['CRED_ENC'];
               }
               ?></td>
        <td class='cuadro_plano centrar'><font color="#090497"><?echo $encabezado['CLASIF_ENC'];?></font></td>
    </tr>
    <?
    }

    /**
     * Esta funcion muestra la celda de datos de cada espacio asociado en los niveles
     * @param <type> $espaciosEncabezados
     */
    function mostrarCeldaEspaciosEncabezados($espaciosEncabezados){
      switch ($espaciosEncabezados['SEM']) {
        case '16':
          $semanas='';
          break;
        case '32':
          $semanas=' &nbsp;&nbsp;(Anualizado)';
          break;
        default:
          $semanas='';
          break;
      }
      ?>
        <tr>
        <td class='cuadro_plano derecha' width="7%"><font color="#049713"><?echo $espaciosEncabezados['ID_ESP']?></font></td>
        <td class='cuadro_plano' width="40%">&nbsp;&nbsp;&nbsp;<font color="#049713"><?echo $espaciosEncabezados['NOMBRE_ESP'].$semanas?></font></td>
        <?//verifica que los creditos correspondan con la distribucion horaria, si no se cumple, se muestran los registros en rojo
        if(48*$espaciosEncabezados['CRED']==($espaciosEncabezados['HTD']+$espaciosEncabezados['HTC']+$espaciosEncabezados['HTA'])*$espaciosEncabezados['SEM']) {
        ?>
        <td class='cuadro_plano centrar'width="7%"><font color='#049713'><?echo $espaciosEncabezados['CRED']?></font></td>
        <td class='cuadro_plano centrar'width="7%"><font color="#049713"><?echo $espaciosEncabezados['HTD']?></font></td>
        <td class='cuadro_plano centrar'width="7%"><font color="#049713"><?echo $espaciosEncabezados['HTC']?></font></td>
        <td class='cuadro_plano centrar'width="7%"><font color="#049713"><?echo $espaciosEncabezados['HTA']?></font></td>
        <?
        }
        else
        {
        ?>
        <td class='cuadro_plano centrar'width="7%"><font color='red'><?echo $espaciosEncabezados['CRED']?></font></td>
        <td class='cuadro_plano centrar'width="7%"><font color='red'><?echo $espaciosEncabezados['HTD']?></font></td>
        <td class='cuadro_plano centrar'width="7%"><font color='red'><?echo $espaciosEncabezados['HTC']?></font></td>
        <td class='cuadro_plano centrar'width="7%"><font color='red'><?echo $espaciosEncabezados['HTA']?></font></td>
        <?
        }
        ?>
        <td class='cuadro_plano' width="25%">&nbsp;&nbsp;&nbsp;<font color="#049713"><?echo $espaciosEncabezados['CLASIF_ESP']?></font></td>
        <?//verifica que este aprobado el espacio academico?>
        </tr>
      <?
    }

    /**
     * Esta funcion muestra la celda de datos de cada espacio en los niveles
     * @param <type> $espacios
     */
    function mostrarCeldaEspacios($espacios){
      switch ($espacios['SEMANAS']) {
        case '16':
          $semanas='';
          break;
        case '32':
          $semanas=' &nbsp;&nbsp;(Anualizado)';
          break;
        default:
          $semanas='';
          break;
      }
      ?>
        <tr>
        <td class='cuadro_plano derecha' width="7%"><?echo $espacios['COD_ESP']?></td>
        <td class='cuadro_plano' width="40%"><?echo $espacios['NOMBRE_ESP'].$semanas?></td>
        <?//verifica que los creditos correspondan con la distribucion horaria, si no se cumple, se muestran los registros en rojo
        if(48*$espacios['CRED_ESP']==($espacios['HTD']+$espacios['HTC']+$espacios['HTA'])*$espacios['SEMANAS']) {
        ?>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['CRED_ESP']?></td>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['HTD']?></td>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['HTC']?></td>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['HTA']?></td>
        <?
        }
        else
        {
        ?>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['CRED_ESP']?></td>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['HTD']?></td>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['HTC']?></td>
        <td class='cuadro_plano centrar' width="7%"><?echo $espacios['HTA']?></td>
        <?
        }
        ?>
        <td class='cuadro_plano' width="25%"><?echo $espacios['NOMBRE_CLASIF']?></td>
        <?//verifica que este aprobado el espacio academico?>
        </tr>
      <?
    }

    /**
     * Esta funcion muestra el total de creditos del nivel
     * @param <type> $creditosNivel
     */
    function mostrarCreditosNivel($creditosNivel) {
    ?>
        <tr>
          <td colspan="5"></td>
          <td colspan="2" class='cuadro_plano centrar'>
              TOTAL DE CR&Eacute;DITOS: <font color="#0000FF"><?echo $creditosNivel;?></font>
          </td>
        </tr>
    <?
    }

    /**
     * Esta funcion presenta el componente propedeutico
     * @param <type> $param
     */
    function propedeutico($nivel,$registroEncabezados,$registroEspacios) {
        $creditosNivel=0;
          $this->iniciarTablaNiveles();
          $this->mostrarEncabezadoNiveles('COMPONENTE PROPED&Eacute;UTICO');
          if(is_array($registroEncabezados))
          {
              foreach ($registroEncabezados as $key => $value) {
                  $encabezadoEliminado=array_shift($registroEncabezados);
                  if($encabezadoEliminado['ID_ENC']!=$id_encabezado)
                  {
                    $this->mostrarCeldaEncabezado($encabezadoEliminado);
                    if($encabezadoEliminado['COD_CLASIF_ENC']!=4)
                    {
                      if (!is_null($encabezadoEliminado['ID_ESP']))
                      {
                        $this->mostrarCeldaEspaciosEncabezados($encabezadoEliminado);
                      }
                    }
                    $id_encabezado=$encabezadoEliminado['ID_ENC'];
                    $creditosNivel+=$encabezadoEliminado['CRED_ENC'];
                  }else{
                          $this->mostrarCeldaEspaciosEncabezados($encabezadoEliminado);
                          $id_encabezado=$encabezadoEliminado['ID_ENC'];
                       }
              }
          }else{
               }
          if (is_array($registroEspacios))
          {
            foreach($registroEspacios as $key => $value){
                $espacioEliminado=array_shift($registroEspacios);
                $this->mostrarCeldaEspacios($espacioEliminado);
                $creditosNivel+=$espacioEliminado['CRED_ESP'];
                    }
          }else {

                }
          $this->mostrarCreditosNivel($creditosNivel);

          $this->cerrarTablaNiveles();
          $creditosNivel=0;

    }

    /**
     * Este funcion presenta mensaje cuando no hay espacios en el plan de estudios
     */
    function presentarMensajeNoEspacios(){
      $this->iniciarTabla();
      ?><br>NO HAY ESPACIOS REGISTRADOS EN EL PLAN DE ESTUDIOS<br><br><?
      $this->cerrarTabla();
    }

    /**
     * Esta funcion presenta el enlace al portafolio de electivos extrinsecos
     * @param <type> $planEstudio
     */
    function mostrarEnlaceExtrinsecas() {
      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
      $variable = "pagina=adminConsultarElectivosExtrinsecosDecano";
      $variable.="&opcion=ver";
      $variable.="&planEstudio=".$this->datosPlan[0]['PLAN'];
      $variable.="&nombreProyecto=".$this->datosPlan[0]['PROYECTO_NOMBRE'];
      $variable.="&codProyecto=".$this->datosPlan[0]['PLAN'];

      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $variable = $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
      <font class="centrar subrayado"><a href="<?= $pagina . $variable ?>" >Ver Electivos Extr&iacute;nsecos</a></font>
      <?
    }

    /**
     * Esta funcion presenta el encabezado del modulo
     * @param <type> $this->configuracion
     * @param <type> $planEstudio
     * @param <type> $codProyecto
     * @param <type> $nombreProyecto
     */
    function encabezadoModulo() {
    ?>
      <table class='contenidotabla centrar'>
          <tr align="center">
              <td class="centrar" colspan="4">
                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<br>PLANES DE ESTUDIO UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS</h4>
              </td>
          </tr>
      </table><?
    }

    /**
     * Esta funcion consulta los niveles del plan de estudios
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarNiveles($planEstudio) {
      $this->cadena_sql = $this->sql->cadena_sql($this->configuracion, "consultaNivelesPlan", $planEstudio);
      return $registroNivelesPlan = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
    }

    /**
     * Esta funcion consulta los nombres generales del plan de estudios junto con sus espacios asociados
     * @param <type> $registro
     * @return <type>
     */
    function consultarNombresGeneralesNiveles() {
      $planEstudio=$this->datosPlan[0]["PLAN"];
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEncabezado",$planEstudio);
      return $registroEncabezados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Esta funcion consulta los nombres generales del plan de estudios junto con sus espacios asociados en el componente propedeutico
     * @param <type> $registro
     * @return <type>
     */
    function consultarNombresGeneralesPropedeutico() {
      $planEstudio=$this->datosPlan[0]["PLAN"];
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEncabezadoPropedeutico",$planEstudio);
      return $registroEncabezados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Este función consulta los codigos de los espacios que estan asociados
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarEspaciosAsociados($planEstudio) {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEspaciosAsociados",$planEstudio);
      return $registroAsociados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Esta funcion consulta los espacios del plan de estudios eliminando los que se encuentran asociados.
     * @param <type> $parametros
     * @return <type>
     */
    function consultarEspacios($parametros) {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEspacios",$parametros);
      return $registroAsociados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Esta funcion consulta los espacios del componente propedeutico.
     * @param <type> $parametros
     * @return <type>
     */
    function consultarPropedeuticos($parametros) {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarPropedeutico",$parametros);
      return $registroAsociados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Esta funcion consulta los parametros del plan de estudios
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarParametrosPlan($planEstudio) {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarParametros", $planEstudio);
      return $resultado_parametros=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Funcion que permite consultar los datos del plan de estudios
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarDatosPlan($planEstudio) {
        $cadena_sql_plan=$this->sql->cadena_sql($this->configuracion,"buscar_id",$planEstudio);
        return $registroPlan=$this->accesoGestion->ejecutarAcceso($cadena_sql_plan,"busqueda");
    }

    }
?>
