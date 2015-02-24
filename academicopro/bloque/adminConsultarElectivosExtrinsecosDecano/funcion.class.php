
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

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_adminConsultarElectivosExtrinsecosDecano extends funcionGeneral {
  public $configuracion;
  public $formulario;

      //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminConsultarElectivosExtrinsecosDecano($configuracion);
        $this->log_us= new log();
        $this->formulario="adminConsultarElectivosExtrinsecosDecano";

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




    }#Cierre de constructor

    
  function verRegistroOtros() {
      $planEstudio=isset($planEstudio)?$planEstudio:'';
    $extrinsecos=$this->consultarExtrinsecosOtrosPlanesEstudio();
    $this->encabezadoModulo();
    $this->menuDecano($this->configuracion, $planEstudio);
    If(is_array($extrinsecos))
      {
      foreach ($extrinsecos as $key => $value) {
          if ($extrinsecos[$key]['ID_FACULTAD']!=(isset($extrinsecos[$key-1]['ID_FACULTAD'])?$extrinsecos[$key-1]['ID_FACULTAD']:''))
          {
            $this->iniciarEncabezadoFacultad($value['FACULTAD']);
          }
          if ($extrinsecos[$key]['ID_PROYECTO']!=(isset($extrinsecos[$key-1]['ID_PROYECTO'])?$extrinsecos[$key-1]['ID_PROYECTO']:''))
          {
              $variable=array(1=>$value['PLAN_ESTUDIOS'],
                          2=>$value['PROYECTO']);
              $this->encabezadoExtrinsecasOtros($variable);
              $this->iniciarTabla();
              $this->iniciarTablaNiveles();
              $this->mostrarEncabezadoNiveles();
          }
          $this->mostrarCeldaEspacios($extrinsecos[$key]);
          if ($extrinsecos[$key]['ID_PROYECTO']!=(isset($extrinsecos[$key+1]['ID_PROYECTO'])?$extrinsecos[$key+1]['ID_PROYECTO']:''))
          {
            $this->cerrarTablaNiveles();
            $this->cerrarTabla();
          }
          if ($extrinsecos[$key]['ID_FACULTAD']!=(isset($extrinsecos[$key+1]['ID_FACULTAD'])?$extrinsecos[$key+1]['ID_FACULTAD']:''))
          {
            $this->cerrarEncabezadoFacultad();
          }
        }
      }else
        {
          $this->encabezadoExtrinsecas($variable);
          $this->iniciarTabla();
          echo "NO HAY ELECTIVOS EXTR&Iacute;NSECOS REGISTRADOS";
          $this->mostrarEnlaceExtrinsecasOtrosPlanes($variable);
          $this->cerrarTabla();
        }
  }


    function menuDecano($configuracion,$variable) {
        ?>
<table class="contenidotabla centrar" width="100%" >
    <tr align="center">
        <td colspan="2" class="centrar"  width="33%">
        <?
                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarPlanEstudioDecano";
                    $ruta.="&opcion=ver";

                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                    ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Ver Planes de Estudios
            </a>
        </td>

      <td colspan="2" class="centrar"  width="33%">
        <?
                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarElectivosExtrinsecosDecano";
                    $ruta.="&opcion=ver";

                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                    ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Portafolio de<br>Electivos Extr&iacute;nsecos
            </a>
        </td>
    </tr>
</table>
        <?
    }
    function encabezadoModulo() {
    ?>
    <table class='contenidotabla centrar'>
      <tr align="center">
          <td class="centrar" colspan="4">
              <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<br>PLANES DE ESTUDIO UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS</h4>
          </td>
      </tr>
    </table>
    <?
    }

    function encabezadoExtrinsecas($variable) {
      ?>
      <table class="contenidotabla centrar" width="100%" >
          <tr class="cuadro_color centrar">
              <td colspan="2" class="cuadro_color centrar">
                          <?echo $variable[2]?>
                  <hr>
              </td>
          </tr>
          <tr>
              <td class="cuadro_color centrar" colspan="2">
                 ELECTIVOS EXTR&Iacute;NSECOS DEL PLAN DE ESTUDIOS <?echo "<strong>".$variable[1];?>
              </td>
          </tr>
      </table>
      <?
    }

    function encabezadoExtrinsecasOtros($variable) {
      ?>
      <table class="contenidotabla centrar" width="100%" >
          <tr class="cuadro_color centrar">
              <td colspan="2" class="cuadro_color centrar">
                <b><?echo $variable[2]?></b>
                  <hr>
              </td>
          </tr>
      </table>
      <?
    }

    function iniciarTabla() {
      ?>
        <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
            <tr class="cuadro_plano">
              <td  align="center">
      <?
    }

    function cerrarTabla() {
      ?>
              </td>
            </tr>
        </table>
      <?
    }

    function iniciarTablaNiveles(){

    ?>
      <table class='contenidotabla'>
      <?
    }

    function cerrarTablaNiveles(){
    ?>
      </table>
      <?
    }

    function iniciarEncabezadoFacultad($facultad) {
    ?>
      <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td class="cuadro_plano centrar"><h2><?echo $facultad?></h2>
      <?

    }

    function cerrarEncabezadoFacultad() {
    ?>
            </td>
        </tr>
      </table><br>
      <?

    }
    function iniciarEncabezadoProyecto($proyecto) {
    ?>
      <table class='contenidotabla'>
        <tr>
          <td><?echo $proyecto?>
      <?

    }
    function cerrarEncabezadoProyecto() {
    ?>
          </td>
        </tr>
      </table>
      <?
    }


    function mostrarEncabezadoNiveles() {
    ?>
        <tr class='cuadro_color'>
          <td class='cuadro_plano centrar' width="8%">Cod.
          </td>
          <td class='cuadro_plano centrar' width="35%">Nombre
          </td>
          <td class='cuadro_plano centrar' width="8%">N&uacute;mero<br>Cr&eacute;ditos
          </td>
          <td class='cuadro_plano centrar' width="8%">HTD
          </td>
          <td class='cuadro_plano centrar' width="8%">HTC
          </td>
          <td class='cuadro_plano centrar' width="8%">HTA
          </td>
          <td class='cuadro_plano centrar' width="25%">Clasificaci&oacute;n
          </td>
        </tr>
    <?
    }

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
        <td class='cuadro_plano centrar' width="40"><?echo $espacios['COD_ESP']?></td>
        <td class='cuadro_plano'><?echo $espacios['NOMBRE_ESP'].$semanas?></td>
        <?//verifica que los creditos correspondan con la distribucion horaria, si no se cumple, se muestran los registros en rojo
        if(48*$espacios['CRED_ESP']==($espacios['HTD']+$espacios['HTC']+$espacios['HTA'])*$espacios['SEMANAS']) {
        ?>
        <td class='cuadro_plano centrar'><?echo $espacios['CRED_ESP']?></td>
        <td class='cuadro_plano centrar'><?echo $espacios['HTD']?></td>
        <td class='cuadro_plano centrar'><?echo $espacios['HTC']?></td>
        <td class='cuadro_plano centrar'><?echo $espacios['HTA']?></td>
        <?
        }
        else
        {
        ?>
        <td class='cuadro_plano centrar'><?echo $espacios['CRED_ESP']?></td>
        <td class='cuadro_plano centrar'><?echo $espacios['HTD']?></td>
        <td class='cuadro_plano centrar'><?echo $espacios['HTC']?></td>
        <td class='cuadro_plano centrar'><?echo $espacios['HTA']?></td>
        <?
        }
        ?>
        <td class='cuadro_plano'><?echo $espacios['NOMBRE_CLASIF']?></td>
        <?//verifica que este aprobado el espacio academico?>
        </tr>
      <?
    }

    function mostrarEnlaceExtrinsecasOtrosPlanes() {
    ?>
      <br>
      <table border="0" width="100%">
        <tr>
          <td class="centrar subrayado">
            <?
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=".$this->formulario;
            $variable.="&opcion=verOtros";

            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            ?>
            <a href="<?= $pagina . $variable ?>" >Ver Electivos Extr&iacute;nsecos de otros Planes de Estudio
            </a>
          </td>
        </tr>
      </table>
      <br>
    <?
    }

  function consultarPlanEstudio() {
    $cadena_sql=$this->sql->cadena_sql("consultarPlan", $this->usuario);
    return $resultadoPlan=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  function consultarExtrinsecosPlanEstudio($planEstudio) {
    $cadena_sql=$this->sql->cadena_sql("consultarExtrinsecosPlan", $planEstudio);
    return $resultadoExtrinsecos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
  }

  function consultarExtrinsecosOtrosPlanesEstudio() {
    $cadena_sql=$this->sql->cadena_sql("consultarExtrinsecosOtrosPlanes","");
    return $resultadoExtrinsecos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
  }



}
?>
