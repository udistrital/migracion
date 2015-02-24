<?php
/* 
 * Funcion que presenta los parametros del plan de estudios
 * 
 */

/**
 * Permite presentar parametros del plan de estudio en las diferentes opciones de administracion
 * Cada funcion recibe unos parametros especificos
 *
 * @author Milton Parra
 * Fecha 15 de Marzo de 2011
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class planEstudios {
  private $configuracion;
  private $ano;
  private $periodo;
  private $planEstudio;


  public function __construct() {

        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $this->configuracion=$configuracion;
        
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->cripto=new encriptar();
        $this->funcionGeneral=new funcionGeneral();

        //Conexion General
        $this->acceso_db=$this->funcionGeneral->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->funcionGeneral->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        //$this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->usuario=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        
        $this->identificacion=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        if ($this->nivel==4||$this->nivel==28){
          $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"coordinadorCred");
        }
        elseif ($this->nivel==51){
          $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"estudiante");
          $this->codEstudiante=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        }
        elseif ($this->nivel==52){
          $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"estudianteCred");
          $this->codEstudiante=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        }
        else {
          $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"oraclesga");
        }
        $cadena_sql=$this->cadena_sql("periodoActual",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
        $this->planEstudio=0;

    }

    /**
     * Esta funcion muetra las abreviaturas utilizadas en distribucion horaria
     */
    public function presentarAbreviaturas() {
    ?>
      <br>
      <table border="0" width="100%">
        <tr>
          <td class="cuadro_plano centrar">
              H.T.D : Horas de Trabajo Directo<br>
              H.T.C : Horas de Trabajo Cooperativo<br>
              H.T.A : Horas de Trabajo Autonomo
          </td>
        </tr>
      </table>
      <br>
    <?
    }


    public function mostrarParametrosAprobadosPlan($planEstudio,$parametrosPlan) {
      $this->planEstudio=$planEstudio;
      $parametrosPlanAprobados=$this->consultarParametrosPlan();
      if(is_array($parametrosPlanAprobados))
        {
          $parametrosPlan[0]=array_merge($parametrosPlanAprobados[0],$parametrosPlan[0]);
          $this->presentarParametrosPlan($parametrosPlan);
          $this->presentarBarrasParametrosPlan($parametrosPlan);
        }else
          {
            $this->presentarParametrosNoRegistradosPlan($parametrosPlan);
          }
    }

    public function mostrarParametrosRegistradosPlan($parametrosPlan) {
      $this->presentarParametrosPlan($parametrosPlan);
      $this->presentarBarrasParametrosPlan($parametrosPlan);
    }

    /**
     * Esta funcion presenta los parametros del plan de estudios
     * @param <type> $parametros
     */
    function presentarParametrosPlan($parametros) {
    ?>
      <table class='cuadro_plano contenidotabla centrar'>
        <tr>
          <td class='cuadro_plano centrar' bgcolor='#f4f4f4'>
            <?echo $parametros[0]['ENCABEZADO'];?> <b>*</b>
          </td>
        </tr>
        <tr>
          <td class='cuadro_plano centrar'>
            Total de cr&eacute;ditos: <?echo $parametros[0]['TOTAL'];?>
          </td>
        </tr>
        <tr>
          <td class='cuadro_plano centrar'>
            Obligatorios Basicos: <?echo $parametros[0]['OB'];?><br>
            Obligatorios Complementarios: <?echo $parametros[0]['OC'];?><br>
            Electivos Intrinsecos: <?echo $parametros[0]['EI'];?><br>
            Electivos Extrinsecos: <?echo $parametros[0]['EE'];?>
<?        if ($parametros[0]['CP']>0)
        {
            if($parametros[0]['PROPEDEUTICO']==0)
            {
                ?><br>Componente Proped&eacute;utico (Opcional): <?echo $parametros[0]['CP'];
            }
            else
                {
                    ?><br>Componente Proped&eacute;utico: <?echo $parametros[0]['CP'];
                }
        }
?>
          </td>
        </tr>
      </table>
    <?
    }

    /**
     * Esta funcion presenta los parametros del plan de estudios
     * @param <type> $parametros
     */
    function presentarParametrosNoRegistradosPlan($parametros) {
    ?>
      <table class='cuadro_plano contenidotabla centrar'>
        <tr>
          <td class='cuadro_plano centrar' bgcolor='#f4f4f4'>
            <?echo $parametros[0]['ENCABEZADO'];?> <b>*</b>
          </td>
        </tr>
        <tr>
          <td class='cuadro_plano centrar'>
            Los rangos no se han defindo para el Plan de Estudios
          </td>
        </tr>
      </table>
    <?
    }

    /**
     * Esta funcion presenta las barras de los parametros del plan de estudios
     * @param <type> $parametros
     */
    function presentarBarrasParametrosPlan($parametros) {

    $maximo=$parametros[0]['TOTAL'];
    $OB=$parametros[0]['OB'];
    $OC=$parametros[0]['OC'];
    $EI=$parametros[0]['EI'];
    $EE=$parametros[0]['EE'];
    $CP=$parametros[0]['CP'];

    if ($maximo==0)
      {
        $porcentajeObligatorios=0;
        $porcentajeElectivos=0;
      }else
      {
        $porcentajeObligatorios = (($OB + $OC) / $maximo) * 100;
        $porcentajeElectivos = (($EI + $EE) / $maximo) * 100;
      }
    if (($OB + $OC)==0)
      {
        $porcentajeObligatoriosBasicos=0;
        $porcentajeObligatoriosComplementarios=0;
      }else
      {
        $porcentajeObligatoriosBasicos = (($OB) / ($OB + $OC)) * 100;
        $porcentajeObligatoriosComplementarios = (($OC) / ($OB + $OC)) * 100;
      }
    if (($EI+$EE)==0)
      {
        $porcentajeElectivosIntrinsecos=0;
        $porcentajeElectivosExtrinsecos=0;
      }else
      {
        $porcentajeElectivosIntrinsecos = (($EI) / ($EI + $EE)) * 100;
        $porcentajeElectivosExtrinsecos = ($EE / ($EI + $EE)) * 100;
      }
    $this->inicioTablaBarras();
    if($porcentajeObligatorios>'0')
      {
        $this->inicioCeldaBarras('#F3DF8D', 'Obligatorios', $porcentajeObligatorios);
        if($porcentajeObligatoriosBasicos>'0')
          {?>
            <tr>
            <?        if ($parametros[0]['PROPEDEUTICO']==1)
        {
            $this->mostrarCeldaParametrosCP('OB',$porcentajeObligatoriosBasicos,"#29467f",$CP);
        }else
            {
                $this->mostrarCeldaParametros('OB',$porcentajeObligatoriosBasicos,"#29467f");
            }
          }
        if($porcentajeObligatoriosComplementarios>'0')
          {
            $this->mostrarCeldaParametros('OC',$porcentajeObligatoriosComplementarios,"#6b8fd4");
            ?>
            </tr>
            <?
          }
        $this->finCeldaBarras();
      }
    if($porcentajeElectivos>'0')
      {
        $this->inicioCeldaBarras('#F7EDC5', 'Electivos', $porcentajeElectivos);
        if($porcentajeElectivosIntrinsecos>'0')
          {?>
            <tr>
            <?$this->mostrarCeldaParametros('EI',$porcentajeElectivosIntrinsecos,"#006064");
          }
        if($porcentajeElectivosExtrinsecos>'0')
          {
            $this->mostrarCeldaParametros('EE',$porcentajeElectivosExtrinsecos,"#36979e");
            ?>
            </tr>
            <?
          }
        $this->finCeldaBarras();
      }
        $this->finTablaBarras();
        $this->mensajeCreditos($parametros[0]['MENSAJE']);
    }

    /**
     * Esta funcion presenta cada celda de parametros del plan de estudios.
     * @param <type> $datos
     * @param <type> $color
     */
    function mostrarCeldaParametros($clasificacion,$datos,$color){
      ?>
      <td width='<?echo $datos;?>%' class='centrar textoBlanco'  height='100%'  bgcolor='<?echo $color?>'><?echo $clasificacion;?><br><?echo round($datos,0);?> %
      </td>
      <?
    }

    /**
     * Esta funcion presenta celda de parametros del plan de estudios cuando tiene componente propedeuticop.
     * @param <type> $datos
     * @param <type> $color
     */
    function mostrarCeldaParametrosCP($clasificacion,$datos,$color,$CP){
        $ancho=$datos-20;
      ?>
      <td width='<?echo $ancho;?>%' class='centrar textoBlanco'  height='100%'  bgcolor='<?echo $color?>'><?echo $clasificacion;?><br><?echo round($datos,0);?> %
      </td>
      <td width='20%' class='centrar texto_gris' bgcolor='#CEE3F6' style='border:5px solid <?echo $color?>'> CP <?echo $CP?> cred</td>
      <?
    }

    /**
     * Esta funcion arma el inicio de la celda de parametros
     * @param <type> $color
     * @param <type> $clase
     * @param <type> $porcentaje
     */
    function inicioCeldaBarras($color,$clase,$porcentaje) {
    ?>
      <td  width='70%' class='centrar' bgcolor='<?echo $color;?>'> <font color='black'><?echo $clase.": ".round($porcentaje,0)?> %</font>
      <table class='tablaGrafico' width='100%' cellspacing='0' cellpadding='1'>
    <?
    }

    /**
     * Esta funcion arma el cierre de la celda de parametros
     */
    function finCeldaBarras() {
      ?>
      </table></td>
      <?
    }

    /**
     * Esta funcion muestra el inicio de la tabla de las barras de parametros
     */
    function inicioTablaBarras() {
      ?>
      <table class='tablaGrafico' align='center' width='100%' cellspacing='0' cellpadding='2'>
        <tr>
      <?
    }

    /**
     * Esta funcion muetra el fin de la tabla de las barras de parametros
     */
    function finTablaBarras() {
      ?>
      <tr></table>
      <?
    }

    /**
     * Esta funcion muestra las observaciones generales del plan de estudios
     */
    function mensajeCreditos($mensaje) {
      ?>
      <table class='contenidotabla'>
        <tr>
          <td class='cuadro_plano centrar'>
            <b>*</b><?echo $mensaje;?>
          </td>
        </tr>
      </table><br>
      <?
    }



    public function consultarParametrosPlan() {
      $cadena_sql=$this->cadena_sql("buscarParametros",$this->planEstudio);
      return $parametros=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

    }

        /**
     * Funcion que entrega cadenas sql para consultas
     * @param <type> $tipo
     * @param <type> $variable
     * @return string
     */
    function cadena_sql($tipo,$variable)
        {
          switch ($tipo)
            {
              case "periodoActual":

                  $cadena_sql="SELECT ape_ano ANO,";
                  $cadena_sql.=" ape_per PERIODO";
                  $cadena_sql.=" FROM acasperi";
                  $cadena_sql.=" WHERE ape_estado like '%A%'";
                  break;

              case 'actualizar_cupo':
                  $cadena_sql="UPDATE ACCURSO ";
                  $cadena_sql.="SET CUR_NRO_INS=";
                  $cadena_sql.="   (SELECT count(*) FROM acins";
                  $cadena_sql.="    WHERE ins_asi_cod = ".$variable['codEspacio'];
                  $cadena_sql.="    and ins_gr=".$variable['grupo'];
                  $cadena_sql.="    and ins_ano=".$this->ano;
                  $cadena_sql.="    and ins_per=".$this->periodo.")";
                  $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                  $cadena_sql.=" AND cur_nro=".$variable['grupo'];
                  $cadena_sql.=" AND cur_ape_ano=".$this->ano;
                  $cadena_sql.=" AND cur_ape_per=".$this->periodo;
                  break;

              case 'registro_evento':
                  $cadena_sql="insert into ".$this->configuracion['prefijo']."log_eventos ";
                  $cadena_sql.="VALUES('','".$variable['usuario']."',";
                  $cadena_sql.="'".date('YmdGis')."',";
                  $cadena_sql.="'".$variable['evento']."',";
                  $cadena_sql.="'".$variable['descripcion']."',";
                  $cadena_sql.="'".$variable['registro']."',";
                  $cadena_sql.="'".$variable['afectado']."')";
                  break;

              case 'buscarParametros':
                  $cadena_sql="SELECT DISTINCT parametro_creditosPlan TOTAL,";
                  $cadena_sql.=" parametros_OB OB,";
                  $cadena_sql.=" parametros_OC OC,";
                  $cadena_sql.=" parametros_EI EI,";
                  $cadena_sql.=" parametros_EE EE,";
                  $cadena_sql.=" parametros_CP CP";
                  $cadena_sql.=" FROM ".$this->configuracion['prefijo']."parametro_plan_estudio";
                  $cadena_sql.=" WHERE parametro_idPlanEstudio=".$variable;
                  break;

            }
            return $cadena_sql;
        }
}
?>
