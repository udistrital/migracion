<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminVerRequisitosAsisVice extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");
        //Datos de sesion
        $this->formulario="adminVerRequisitosAsisVice";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");


    }

    function verRequisitosAsisVice($configuracion) {

        $codProyecto=isset($codProyecto)?$codProyecto:'';
        $nombreProyecto=isset($nombreProyecto)?$nombreProyecto:'';

      $variable[0][0]=$planEstudio=$_REQUEST['planEstudio'];
      $variable[0][7]=$_REQUEST['nombreProyecto'];
      $this->cadena_sql=$this->sql->cadena_sql($configuracion, "espacios_academicos", $planEstudio, '');
      $resultado_espacios=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

      $this->cadena_sql=$this->sql->cadena_sql($configuracion, "requisitos_registrados", $planEstudio, '');
      $resultado_registrados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

      ?>
      <table align="center" border="0" cellpadding="0" width="100%">
          <tr>
              <td style='text-align:center' align="center" colspan="2">
                <?
                $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
                $this->menuAsisVice($configuracion, $variable)
                ?>
              </td>
          </tr>
          <tr>
            <td>
                <table class="contenidotabla centrar" width="100%" >
                    <tr class="cuadro_color centrar">
                        <td colspan="2" class="cuadro_color centrar">
                                    <?echo $variable[0][7]?>
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="cuadro_color centrar" colspan="2">
                          REQUISITOS DEL PLAN DE ESTUDIOS <?echo "<strong>".$variable[0][0];?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
      </table>
      <?
      if($resultado_registrados==true)
         {
          ?>
          <table align='center' width='100%' class='contenidotabla'>
              <thead class='texto_subtitulo centrar'>
              <tr>
                <td class="cuadro_color cuadro_plano centrar" colspan="2" width="41%"><b>ESPACIO ACAD&Eacute;MICO</b></td>
                  <td class="cuadro_color cuadro_plano centrar" colspan="3" width="41%"><b>REQUISITO</b></td>
                  <td class="cuadro_color cuadro_plano centrar" rowspan="2" width="18%">¿El REQUISITO DEBE<br> SER APROBADO?</td>
              </tr>
              <tr>
                <td class="cuadro_color cuadro_plano centrar">C&oacute;digo</td>
                  <td class="cuadro_color cuadro_plano centrar">Nombre</td>
                  <td class="cuadro_color cuadro_plano centrar">N&uacute;mero</td>
                  <td class="cuadro_color cuadro_plano centrar">C&oacute;digo</td>
                  <td class="cuadro_color cuadro_plano centrar">Nombre</td>
              </tr>
          </thead>

                  <?
                  $conteoRegistrados=count($resultado_registrados);
          $num=0;
                  for($i=0;$i<$conteoRegistrados;$i++) {
                      if($resultado_registrados[$i][4]==1) {
                          $aprobado="SI";
                      }else {
                          $aprobado="NO";
                      }

                      ?><tr>
              <td class="cuadro_plano centrar">
                      <?echo $resultado_registrados[$i][2]?>
              </td>
              <td class="cuadro_plano">
                      <?echo $resultado_registrados[$i][3]?>
              </td>
          <?
          if ((isset($resultado_registrados[$i-1][2])?$resultado_registrados[$i-1][2]:'')==$resultado_registrados[$i][2])
          {$num++;}
           else {
                    $num=0;
                  }
          ?>
              <td class="cuadro_plano centrar">
                      <?echo $num+1;?>
              </td>
              <td class="cuadro_plano centrar">
                      <?echo $resultado_registrados[$i][0]?>
              </td>
              <td class="cuadro_plano">
                      <?echo $resultado_registrados[$i][1]?>
              </td>
              <td class="cuadro_plano centrar">
                      <?echo $aprobado?>
              </td>
          </tr>
                  <?
          if ($resultado_registrados[$i][2]!=(isset($resultado_registrados[$i+1][2])?$resultado_registrados[$i+1][2]:''))
          {echo "<tr><td colspan=6><hr></td></tr>";}
                  }

                  ?>
          </table>
        <?
        }
        else
            {
             ?>
              <table align='center' width='100%' cellpadding='2' cellspacing='2' class='contenidotabla'>
              <br>
                <caption>
                NO EXISTEN REQUISITOS REGISTRADOS PARA ESTE PLAN DE ESTUDIOS
                </caption>
              </table>
             <?
            }

    }

 //////////////////////////////////////////////////////////////////////

        function menuAsisVice($configuracion,$variable)
    {  
        ?>
<table class="contenidotabla centrar" width="100%" >
    <tr align="center">
        <td colspan="2" class="centrar"  width="33%">
        <?
                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarPlanEstudioAsisVice";
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
                    $ruta="pagina=adminConsultarElectivosExtrinsecosAsisVice";
                    $ruta.="&opcion=ver";

                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                    ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Portafolio de<br>Electivos Extr&iacute;nsecos
            </a>
        </td>
        <td colspan="2" class="centrar" width="25%">
            <?
                $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=adminVerRequisitosAsisVice";
                $ruta.="&opcion=visualizar";
                $ruta.="&planEstudio=".$variable[0][0];
                $ruta.="&nombreProyecto=".$variable[0][7];
                $ruta.="&codProyecto=".$variable[0][0];

                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                ?>

            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Requisitos del<br>Plan de Estudios
            </a>
        </td>
    </tr>
</table>
        <?
    }

    //inicia lista de planes de estudios
    function verRegistro($configuracion) {
       

        ?>
<table width="90%" border="0" align="center"  cellpadding="5" cellspacing="2">
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
                    <?
                    $variable="";
                    #Consulta por planes de estudios existentes
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion, "listaPlanesEstudio", $variable, '');
                    $registro=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                    $this->listaMallas($configuracion, $registro);?>
        </td>
    </tr>
</table>
        <?
    }#Cierre de funcion verRegistro

    //presenta lista de planes de estudios
    function listaMallas($configuracion, $registro) {
         
         $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto)
                ?>
<table  class="contenidotabla centrar" width="100%" >
    <tr class="texto_subtitulo">
        <td class="cuadro_color centrar" colspan="6">
                    <?
                    echo "Planes de Estudio";
                    ?>
            <hr class="hr_subtitulo">
        </td>
    </tr>
            <?              
              if (is_array($registro)) {?>
            
    <tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');"  >

        <td width="10%" align="center"><strong>C&oacute;digo</strong></td>
        <td align="center"><strong>Nombre</strong></td>
        <td width="10%" align="center"><strong>Ver</strong></td>

    </tr>
                <? for($i=0; $i<count($registro); $i++) {
                    ?>
    <tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" <? if(($i%2)==0) { ?> bgcolor='<? echo $tema->celda ?>' <?}?>>
        <td width="10%" align="center"><?= $registro[$i][0]?></td>
        <td>
                            <?= $registro[$i][1]?>
        </td>
                        <?
                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=adminVerRequisitosAsisVice";
                        $ruta.="&opcion=visualizar";
                        $ruta.="&planEstudio=".$registro[$i][0];
                        ?>
        <td align="center">
                            <?
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                            ?>
            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png" border="0">
            </a>
        </td>
    </tr>
                    <?
                }#Cierre de for
            }#Cierre de is_array($registro)
            else {?>
    <tr>
        <td class='bloquecentralcuerpo' align="center"><strong>No existen planes de estudio</strong></td>
    </tr>
                <? }#Cierre de else  is_array($registro)
            ?>

</table>
        <?
    }

    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {
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



}
 
?>

