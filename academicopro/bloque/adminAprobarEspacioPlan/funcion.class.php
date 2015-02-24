
<?php
/*--------------------------------------------------------------------------------------------------------------------------
@ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if (!isset($GLOBALS["autorizado"])) {
  include ("../index.php");
  exit;
}
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/planEstudios.class.php");

//@ Clase que contiene los mÃ©todos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_aprobarEspacioPlan extends funcionGeneral {
    private $ano;
    private $periodo;

//@ MÃ©todo costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
  //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
  //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    $this->cripto = new encriptar();
    $this->tema = $tema;
    $this->sql = new sql_aprobarEspacio();
    $this->log_us = new log();
    $this->parametrosPlan=new planEstudios();
    $this->formulario = "adminAprobarEspacioPlan";
    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "asesvice");
    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");
    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];
    //Datos de sesion
    $this->formulario = "adminAprobarEspacioPlan";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo", "");
    $periodo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
    $this->ano=$periodo[0]['ANO'];
    $this->periodo=$periodo[0]['PERIODO'];
    
  } #Cierre de constructor

  //inicia lista de planes de estudios
  function verRegistro($configuracion, $tema, $acceso_db, $formulario) {
    $variable = "";
    #Consulta por planes de estudios existentes
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "listaPlanesEstudio", $variable);
    $registro = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
    $this->listaMallas($tema, $registro, $configuracion);
  } #Cierre de funcion verRegistro


  //presenta lista de planes de estudios
  function listaMallas($tema, $registro, $configuracion) {
    $this->encabezadoModulo($configuracion, "", "", "")
        ?>
<table  class="contenidotabla centrar" width="100%" >
  <tr class="texto_subtitulo">
    <td class="cuadro_color centrar" colspan="6">
          <?
          echo "Aprobaci&oacute;n de Espacios en Planes de Estudios";
          ?>
      <hr class="hr_subtitulo">
    </td>
  </tr>
      <? if (is_array($registro)) { ?>
  <tr class='bloquecentralcuerpo'>

    <td width="10%" align="center"><strong>C&oacute;digo</strong></td>
    <td align="center"><strong>Proyecto Curricular</strong></td>
    <td width="10%" align="center"><strong>Ver</strong></td>
    <td width="14%" align="center"><strong>Mensajes<br>por ver</strong></td>

  </tr>
        <? for ($i = 0;$i < count($registro);$i++) {
            $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
            $ruta = "pagina=adminAprobarEspacioPlan";
            //$ruta.= "&opcion=mostrar";
            $ruta.= "&opcion=buscarPlan";
            $ruta.= "&proyecto=" . $registro[$i][7];
            $ruta.= "&nombreProyecto=" . $registro[$i][1];
            $ruta = $this->cripto->codificar_url($ruta, $configuracion);
        if($registro[$i][8]!=(isset($registro[$i-1][8])?$registro[$i-1][8]:''))
        {
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "facultad", $registro[$i][8]);
            $facultad = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

          ?><tr><td colspan="6"><hr><?echo $facultad[0]['FACULTAD']?></td></tr><?
        }
        else{

        }
          ?>
          <tr class='bloquecentralcuerpo' onclick="location.href='<?=$indice . $ruta ?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
    <td width="10%" align="center"><?echo $registro[$i][7] ?></td>
    <td>                           <?echo $registro[$i][1] ?></td>
    <td align="center">
      <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" border="0">
    </td>
    <td align="center">
              <?
              $variable = $registro[$i][0];
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "mensajeGeneral", $variable);
              $resultado_numMensaje = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "mensajeEspacios", $variable);
              $resutado_mensajeEA = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
              if ($resultado_numMensaje[0][0] != 0) {
                echo "Msj General " . $resultado_numMensaje[0][0] . "<br>";
              }
              if (count($resutado_mensajeEA) != 0) for ($j = 0;$j < count($resutado_mensajeEA);$j++) {
                  echo "Msj EA " . $resutado_mensajeEA[$j][0] . "<br>";
                }
              ?>

    </td>

  </tr>
        <?
        } #Cierre de for

      } #Cierre de is_array($registro)
      else { ?>
  <tr>
    <td class='bloquecentralcuerpo' align="center"><strong>No existen planes de estudio</strong></td>
  </tr>
      <?
      } #Cierre de else  is_array($registro)

      ?>

</table>
  <?
  } #Cierre de funcion listaMallas

function buscarEnfasis($configuracion, $codProyecto,$nombreProyecto)
{
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cantidadPlanes", $codProyecto);
    $numeroPlanes = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "listaPlanes", $codProyecto);
    $registro = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

    if ($numeroPlanes[0][0]>1)
    {
      $this->encabezadoModulo($configuracion, "", $codProyecto, $nombreProyecto);
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
          <td width="30%" align="center"><strong>Mensajes por ver</strong></td>
        </tr>
        <? for ($i = 0;$i < count($registro);$i++) {
            $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
            $ruta = "pagina=adminAprobarEspacioPlan";
            $ruta.= "&opcion=mostrar";
            //$ruta.= "&opcion=buscarPlan";
            $ruta.= "&planEstudio=" . $registro[$i][0];
            $ruta = $this->cripto->codificar_url($ruta, $configuracion);
          ?>
  <tr class='bloquecentralcuerpo' onclick="location.href='<?=$indice . $ruta ?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
    <td width="10%" align="center"><?echo $registro[$i][0] ?></td>
    <td>                           <?echo $registro[$i][1] ?></td>
    <td align="center">
              <?
              $variable = $registro[$i][0];
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "mensajeGeneral", $variable);
              $resultado_numMensaje = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
              //$variable=$registro[$i][0];
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "mensajeEspacios", $variable);
              $resutado_mensajeEA = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
              if ($resultado_numMensaje[0][0] != 0) {
                echo "Msj General " . $resultado_numMensaje[0][0] . "<br>";
              }
              if (count($resutado_mensajeEA) != 0) for ($j = 0;$j < count($resutado_mensajeEA);$j++) {
                  echo "Msj EA " . $resutado_mensajeEA[$j][0] . "<br>";
                }
              ?>
    </td>
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

    }else{$this->mostrarRegistro($configuracion, $registro[0][0]);}

}


  #Llama las funciones "verPlanEstudios", "listaNiveles" y "listaEspacios" para visualizar
  #la informacion general del Plan de Estudios y los Espacios Academicos que lo componen agrupados por niveles
  function mostrarRegistro($configuracion, $id) {
  #Consulta la informacion general del Plan de Estudios
  #$id es el id_carrera
    $registroPlan=$this->consultarDatosPlan($configuracion, $id);
    $this->verPlanEstudios($configuracion, $registroPlan);
    #Consulta los Espacios Academicos del plan de estudios
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultaEspacioPlan", $id);
    $registroEspaciosPlan = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
    $totalEspacios = $this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
    #Muestra los niveles de un plan de estudios
    $this->listaEspacios($configuracion, $registroEspaciosPlan, $totalEspacios, $registroPlan);
  }


  #Funcion que muestra la informacion del Plan de Estudios
  function verPlanEstudios($configuracion, $registro) {
  #enlace regreso  listado de planes
    $this->encabezadoModulo($configuracion, $registro[0][0],$registro[0][11], $registro[0][7]);
    $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
    $ruta = "pagina=registroComentarioPlanAsisVice";
    $ruta.= "&opcion=verComentarios";
    $ruta.= "&planEstudio=" . $registro[0][0];
    $ruta = $this->cripto->codificar_url($ruta, $configuracion);
    ?>
<br>
<table class="contenidotabla centrar" width="100%" >
  <tr>
    <td class="centrar">
      <a href="<?=$indice . $ruta ?>">

            <?
            $variable = $registro[0][0];
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "mensajeGeneral", $variable);
            $resultado_numMensaje = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
            if ($resultado_numMensaje[0][0] == 0) {
              echo "<img src='" . $configuracion['site'] . $configuracion['grafico'] . "/kopete.png' width='38' height='38' border='0' alt='Enviar Mensaje'>";
              echo "<br>Enviar un<br>Mensaje General<br>";
            } else if ($resultado_numMensaje[0][0] == 1) {
                echo "<img src='" . $configuracion['site'] . $configuracion['grafico'] . "/viewrel.png' width='30' height='30' border='0' alt='Nuevo Mensaje General'>";
                echo "<br>Existe un<br>Mensaje General<br>Nuevo ";
              } else if ($resultado_numMensaje[0][0] > 1) {
                  echo "<img src='" . $configuracion['site'] . $configuracion['grafico'] . "/viewrel.png' width='30' height='30' border='0' alt='Nuevos Mensajes Generales'>";
                  echo "<br>Existen " . $resultado_numMensaje[0][0] . "<br>Mensajes Generales<br> Nuevos ";
                }
            ?>
      </a>
    </td>
  </tr>
  <tr class="cuadro_color centrar">
    <td colspan="2" class="cuadro_color centrar">
          <? echo $registro[0][7] ?>
      <hr>
    </td>
  </tr>
  <tr>
    <td class="cuadro_color centrar" colspan="2">
      PLAN DE ESTUDIOS EN CR&Eacute;DITOS N&Uacute;MERO <? echo "<strong>" . $registro[0][0] . " - " . $registro[0][8] . "</strong>" ?>
    </td>
  </tr>
  <tr class="cuadro_plano centrar">
        <?
        $cadena_sql = $this->sql->cadena_sql($configuracion, "parametrosPlan", $registro[0][0]);
        $resultado_parametros = $this->accesoGestion->ejecutarAcceso($cadena_sql, "busqueda");
        if (is_array($resultado_parametros)) {
          if ($resultado_parametros[0]['parametros_aprobado'] == '0') {
            ?>
    <td class="centrar">
      <font size='2' color='red'>EXISTEN PAR&Aacute;METROS DEL PLAN DE ESTUDIOS QUE EST&Aacute;N SIN APROBAR</font>
              <?
              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
              $ruta = "pagina=adminAprobarEspacioPlan";
              $ruta.= "&opcion=verParametros";
              $ruta.= "&planEstudio=" . $registro[0][0];
              $ruta.= "&nombreProyecto=" . $registro[0][7];
              $ruta.= "&codProyecto=" . $registro[0][7];
              $ruta = $this->cripto->codificar_url($ruta, $configuracion);
              ?>
    </td>
    <td class="centrar">
      <a href="<? echo $pagina . $ruta ?>">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] . "/clean.png" ?>" width="30" height="30" border="0" alt="Editar Requisito"><br>Aprobar
      </a>
    </td>
          <?
          } else if ($resultado_parametros[0]['parametros_aprobado'] == '1') {
              ?>
    <td class="centrar">
      <font size='2' color='green'>EXISTEN PAR&Aacute;METROS DEL PLAN DE ESTUDIOS QUE EST&Aacute;N APROBADOS</font>
                <?
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $ruta = "pagina=adminAprobarEspacioPlan";
                $ruta.= "&opcion=verParametros";
                $ruta.= "&planEstudio=" . $registro[0][0];
                $ruta.= "&nombreProyecto=" . $registro[0][7];
                $ruta = $this->cripto->codificar_url($ruta, $configuracion);
                ?>
    </td>
    <td class="centrar">
      <a href="<? echo $pagina . $ruta ?>">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] . "/xmag.png" ?>" width="30" height="30" border="0" alt="Editar Requisito"><br>Ver
      </a>
    </td>
            <?
            }
        } else {
          ?>
    <td class="centrar">
      <font size='2' color='red'>NO EXISTEN REGISTRADOS PAR&Aacute;METROS DEL PLAN DE ESTUDIOS</font>
    </td>
        <?
        }
        ?>

  </tr>
</table>


  <?
  }


  #Muestra los niveles existentes para el Plan de Estudios
  function listaEspacios($configuracion, $registro, $totalEspacios, $registroPlan) {
      $variablesEvento=array('ano'=>$this->ano,
                            'periodo'=>$this->periodo);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEventoGestionPlanes",$variablesEvento);
        $fechasEventoPlanes = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
        $fecha=  date('Ymd');
        if($fecha>$fechasEventoPlanes[0]['FIN'])
        {
            $permiso=0;
        }else{$permiso=1;}
      
    $creditosRegistrados=0;
    $creditosNivel=0;
    $creditosTotal=0;
    $creditosAprobados = 0;
    $comentHoras="";
    $idEncabezado = 0;
      $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultaNivelesPlan", $registroPlan[0][0]);
      $registroNiveles = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

    ?>
<style type="text/css">
  #toolTipBox {
    display: none;
    padding: 5;
    font-size: 12px;
    border: black solid 1px;
    font-family: verdana;
    position: absolute;
    background-color: #ffd038;
    color: 000000;
  }
</style>
<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
  <tr class="cuadro_plano">
    <td  align="center">
      <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
        <tbody>
          <tr>
            <td>
              <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                <tr>
                  <td>
                    <table class='contenidotabla'>

                      <?
                      $ob = 0;
                      $creob = 0;
                      $obRegistrados=0;
                      $oc = 0;
                      $creoc = 0;
                      $ocRegistrados=0;
                      $ei = 0;
                      $creei = 0;
                      $eiRegistrados=0;
                      $ee = 0;
                      $creee = 0;
                      $eeRegistrados=0;
                      $cp = 0;
                      $crecp = 0;
                      $cpRegistrados=0;
                      if(is_array($registroNiveles))
                          {

                          foreach ($registroNiveles as $key => $value) {

                          //mensaje al inicio de cada semestre
                                if ($value[0]==98)
                                {
                                    echo "<tr><td colspan='12' align='center'><h2>COMPONENTE PROPED&Eacute;UTICO</td></tr>";
                                }else{
                                        echo "<tr><td colspan='12' align='center'><h2>PER&Iacute;ODO DE FORMACI&Oacute;N " . $value[0] . "</h2></td></tr>";
                                    }
                              ?>
                      <tr class='cuadro_color'>
                                <?
                /*<td class='cuadro_plano centrar'>Nivel </td>*/
                                ?>
                        <td class='cuadro_plano centrar'>Cod. </td>
                        <td class='cuadro_plano centrar'>Nombre </td>
                        <td class='cuadro_plano centrar'>N&uacute;mero<br>Cr&eacute;ditos</td>
                        <td class='cuadro_plano centrar'>HTD </td>
                        <td class='cuadro_plano centrar'>HTC </td>
                        <td class='cuadro_plano centrar'>HTA </td>
                        <td class='cuadro_plano centrar'>Clasificaci&oacute;n </td>
                        <td class='cuadro_plano centrar' colspan="2">Aprobar </td>
                        <td class='cuadro_plano centrar' >Modificar</td>
                        <?//se wadiciona para borrar espacios no aprobados por vice?>
                        <td class='cuadro_plano centrar' >Borrar</td>
                        <td class='cuadro_plano centrar'>Comentarios </td>
                      </tr><?
                              $valoresEncabezado = array($registroPlan[0][0], $value[0], $idEncabezado);
                              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultarEncabezado", $valoresEncabezado);
                              $registroEncabezados = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");

                            
                            if (is_array($registroEncabezados)) {
                              for ($p = 0;$p < count($registroEncabezados);$p++) {
                                if ($registroEncabezados[$p][10] == $value[0]) {
                                  ?>

                      <tr>
                                    <?
                                    ?>
                        <td class='cuadro_plano' colspan="2" bgcolor="#ECF8E0" ><font color="#090497"><? echo $registroEncabezados[$p][1];?></font></td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0"><font color="#090497"><? echo $registroEncabezados[$p][9];

                                        $porNotas = 0;
                                        $porInscripcion = 0;
                                        $porHorario = 0;
                                        ?></font></td>
                                      <?

                                      switch ($registroEncabezados[$p][7]) {
                                        case '0':
                                          $creditosNivel+= $registroEncabezados[$p][9];
                                          $creditosRegistrados+=$registroEncabezados[$p][9];

                                          switch ($registroEncabezados[$p][5]) {
                                            case '1':
                                              $obRegistrados+=$registroEncabezados[$p][9];
                                              $comentClasif = "Obligatorio B&aacute;sico";
                                              break;
                                            case '2':
                                              $ocRegistrados+=$registroEncabezados[$p][9];
                                              $comentClasif = "Obligatorio Complementario";
                                              break;
                                            case '3':
                                              $eiRegistrados+=$registroEncabezados[$p][9];
                                              $comentClasif = "Electivo Intr&iacute;nseco";
                                              break;
                                            case '4':
                                              $eeRegistrados+=$registroEncabezados[$p][9];
                                              $comentClasif = "Electivo Extr&iacute;nseco";
                                              $comentHoras = "Sugerencia Plan de Estudio";
                                              break;
                                            case '5':
                                              $cpRegistrados+=$registroEncabezados[$p][9];
                                              $comentClasif = "Componente Proped&eacute;utico";
                                              break;
                                          }
                                          ?>

                        <td class='cuadro_plano centrar' colspan="3" bgcolor="#ECF8E0"><?echo $comentHoras?></td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0" >
                          <font color="#090497">
                                            <? echo $comentClasif; ?>
                          </font>
                        </td>

                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0">
                                          <?
        if($permiso==1)
        {
                                          $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                          $variables = "pagina=registroAprobarOpcionAsisVice";
                                          $variables.= "&opcion=aprobarEncabezado";
                                          $variables.= "&id_encabezado=" . $registroEncabezados[$p][0];
                                          $variables.= "&encabezado_nombre=" . $registroEncabezados[$p][1];
                                          $variables.= "&planEstudio=" . $registroEncabezados[$p][3];
                                          $variables.= "&codProyecto=" . $registroEncabezados[$p][4];
                                          $variables.= "&clasificacion=" . $registroEncabezados[$p][5];
                                          $variables.= "&nroCreditos=" . $registroEncabezados[$p][9];
                                          $variables.= "&nivel=" . $registroEncabezados[$p][10];
                                          $variables = $this->cripto->codificar_url($variables, $configuracion);

                                          ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="25" height="25" border="0">
                          </a>
<?}?>                            
                        </td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0">
                                          <?
        if($permiso==1)
        {
                                          $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                          $variables = "pagina=registroAprobarOpcionAsisVice";
                                          $variables.= "&opcion=no_aprobarEncabezado";
                                          $variables.= "&id_encabezado=" . $registroEncabezados[$p][0];
                                          $variables.= "&encabezado_nombre=" . $registroEncabezados[$p][1];
                                          $variables.= "&planEstudio=" . $registroEncabezados[$p][3];
                                          $variables.= "&codProyecto=" . $registroEncabezados[$p][4];
                                          $variables.= "&clasificacion=" . $registroEncabezados[$p][5];
                                          $variables.= "&nroCreditos=" . $registroEncabezados[$p][9];
                                          $variables.= "&nivel=" . $registroEncabezados[$p][10];
                                          include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                          $this->cripto = new encriptar();
                                          $variables = $this->cripto->codificar_url($variables, $configuracion);

                                          ?>

                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="25" height="25" border="0">
                          </a>
<?}?>                            
                        </td>
                        <td class="cuadro_plano centrar" bgcolor="#ECF8E0">
                                          <?
                                          if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                            ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <?
                                                   if ($porNotas == '1') {
                                                     echo "<br>Notas";
                                                   }
                                                   if ($porHorario == '1') {
                                                     echo "<br>Horarios";
                                                   }
                                                   if ($porInscripcion == '1') {
                                                     echo "<br>Inscripciones";
                                                   } ?>',this)">
                          </div>

                                          <?
                                          } else {
        if($permiso==1)
        {
                                              
                                            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                            $variables = "pagina=registroModificarEspacioAsisVice";
                                            $variables.= "&opcion=modificarEspacioEncabezado";
                                            $variables.= "&id_encabezado=" . $registroEncabezados[$p][0];
                                            $variables.= "&encabezado_nombre=" . $registroEncabezados[$p][1];
                                            $variables.= "&planEstudio=" . $registroEncabezados[$p][3];
                                            $variables.= "&codProyecto=" . $registroEncabezados[$p][4];
                                            $variables.= "&clasificacion=" . $registroEncabezados[$p][5];
                                            $variables.= "&nroCreditos=" . $registroEncabezados[$p][9];
                                            $variables.= "&nivel=" . $registroEncabezados[$p][10];
                                            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                            $this->cripto = new encriptar();
                                            $variables = $this->cripto->codificar_url($variables, $configuracion);
                                            ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?
        }
                                          }
                                          ?>

                        </td>
                        <td class='cuadro_plano centrar'>
                        
<?
        if($permiso==1)
        {

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=registroBorrarEACoordinador";
                            $ruta.="&opcion=solicitarEncabezado";
                            $ruta.="&id_encabezado=".$registroEncabezados[$p][0];
                            $ruta.="&encabezado_nombre=".$registroEncabezados[$p][1];
                            $ruta.="&nroCreditos=".$registroEncabezados[$p][9];
                            $ruta.="&nivel=".$registroEncabezados[$p][10];
                            $ruta.="&planEstudio=".$registroEncabezados[$p][3];
                            $ruta.="&codProyecto=".$registroEncabezados[$p][4];
                            $ruta.="&clasificacion=".$registroEncabezados[$p][5];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                            $borrarEncabezado=$pagina.$ruta;
                            ?>
                            <a href='<?echo $borrarEncabezado?>' class='centrar'>
                            <img src='<?echo $configuracion['site'].$configuracion['grafico'];?>/delete.png' width='25' height='25' border='0'><br><font size='1'>Borrar</font>
                            </a>
                            <?}?>
                        </td>
                        <td class="cuadro_plano" bgcolor="#ECF8E0">
                        </td>
                                        <?
                                        break;
                                      case '1':

                                        $creditosNivel+= $registroEncabezados[$p][9];
                                        $creditosTotal+= $registroEncabezados[$p][9];
                                        $creditosRegistrados+=$registroEncabezados[$p][9];

                                        switch ($registroEncabezados[$p][5]) {
                                          case '1':
                                            $ob++;
                                            $obRegistrados+=$registroEncabezados[$p][9];
                                            $creob+= $registroEncabezados[$p][9];
                                            $comentClasif = "Obligatorio B&aacute;sico";
                                            break;
                                          case '2':
                                            $oc++;
                                            $ocRegistrados+=$registroEncabezados[$p][9];
                                            $creoc+= $registroEncabezados[$p][9];
                                            $comentClasif = "Obligatorio Complementario";
                                            break;
                                          case '3':
                                            $ei++;
                                            $eiRegistrados+=$registroEncabezados[$p][9];
                                            $creei+= $registroEncabezados[$p][9];
                                            $comentClasif = "Electivo Intr&iacute;nseco";
                                            break;
                                          case '4':
                                            $ee++;
                                            $eeRegistrados+=$registroEncabezados[$p][9];
                                            $creee+= $registroEncabezados[$p][9];
                                            $comentClasif = "Electivo Extr&iacute;nseco";
                                            $comentHoras = "Sugerencia Plan de Estudio";
                                            break;
                                          case '5':
                                            $cp++;
                                            $cpRegistrados+=$registroEncabezados[$p][9];
                                            $crecp+= $registroEncabezados[$p][9];
                                            $comentClasif = "Componente Proped&eacute;utico";
                                            break;
                                        }
                                        ?>
                        <td class='cuadro_plano centrar' colspan="3" bgcolor="#ECF8E0"><?echo $comentHoras?></td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0" >
                          <font color="#090497">
                                            <? echo $comentClasif; ?>
                          </font>
                        </td><?
                                        $creditosAprobados+= $registroEncabezados[$p][9];
                                        ?>      <td class='cuadro_plano centrar' colspan="2" bgcolor="#ECF8E0">
                          Aprobado
                        </td>
                        <td class="cuadro_plano centrar" bgcolor="#ECF8E0">
                                          <?
                                          if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                            ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                                     echo "<br>Notas";
                                                   }
                                                   if ($porHorario == '1') {
                                                     echo "<br>Horarios";
                                                   }
                                                   if ($porInscripcion == '1') {
                                                     echo "<br>Inscripciones";
                                                   } ?>',this)">
                          </div>
                                          <?
                                          } else {
        if($permiso==1)
        {
                                              
                                            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                            $variables = "pagina=registroModificarEspacioAsisVice";
                                            $variables.= "&opcion=modificarEspacioEncabezado";
                                            $variables.= "&id_encabezado=" . $registroEncabezados[$p][0];
                                            $variables.= "&encabezado_nombre=" . $registroEncabezados[$p][1];
                                            $variables.= "&planEstudio=" . $registroEncabezados[$p][3];
                                            $variables.= "&codProyecto=" . $registroEncabezados[$p][4];
                                            $variables.= "&clasificacion=" . $registroEncabezados[$p][5];
                                            $variables.= "&nroCreditos=" . $registroEncabezados[$p][9];
                                            $variables.= "&nivel=" . $registroEncabezados[$p][10];
                                            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                            $this->cripto = new encriptar();
                                            $variables = $this->cripto->codificar_url($variables, $configuracion);
                                            ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?}
                                          }
                                          ?>
                        </td>
                        <td  class="cuadro_plano centrar">
                        </td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0">
                        </td>
                                        <?
                                        break;
                                      case '2':
                                        $creditosRegistrados+=$registroEncabezados[$p][9];
                                        switch ($registroEncabezados[$p][5]) {
                                          case '1':
                                            //$ob++;
                                            //$creob+= $registroEncabezados[$p][9];
                                            $obRegistrados+=$registroEncabezados[$p][9];
                                            $comentClasif = "Obligatorio B&aacute;sico";
                                            break;
                                          case '2':
                                            //$oc++;
                                            //$creoc+= $registroEncabezados[$p][9];
                                            $ocRegistrados+=$registroEncabezados[$p][9];
                                            $comentClasif = "Obligatorio Complementario";
                                            break;
                                          case '3':
                                            //$ei++;
                                            //$creei+= $registroEncabezados[$p][9];
                                            $eiRegistrados+=$registroEncabezados[$p][9];
                                            $comentClasif = "Electivo Intr&iacute;nseco";
                                            break;
                                          case '4':
                                            //$ee++;
                                            //$creee+= $registroEncabezados[$p][9];
                                            $eeRegistrados+=$registroEncabezados[$p][9];
                                            $comentClasif = "Electivo Extr&iacute;nseco";
                                            $comentHoras = "Sugerencia Plan de Estudio";
                                            break;
                                          case '5':
                                            //$ob++;
                                            //$creob+= $registroEncabezados[$p][9];
                                            $cpRegistrados+=$registroEncabezados[$p][9];
                                            $comentClasif = "Componente Proped&eacute;utico";
                                            break;
                                        }
                                        ?>
                        <td class='cuadro_plano centrar' colspan="3" bgcolor="#ECF8E0"><?echo $comentHoras?></td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0" >
                          <font color="#090497">
                                            <? echo $comentClasif; ?>
                          </font>
                        </td>
                        <td class='cuadro_plano centrar' colspan="2" bgcolor="#ECF8E0">
                          No Aprobado</td>
                        <td class="cuadro_plano centrar" bgcolor="#ECF8E0">
                                          <?
                                          if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                            ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                                     echo "<br>Notas";
                                                   }
                                                   if ($porHorario == '1') {
                                                     echo "<br>Horarios";
                                                   }
                                                   if ($porInscripcion == '1') {
                                                     echo "<br>Inscripciones";
                                                   } ?>',this)">
                          </div>
                                          <?
                                          } else {
        if($permiso==1)
        {
                                              
                                            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                            $variables = "pagina=registroModificarEspacioAsisVice";
                                            $variables.= "&opcion=modificarEspacioEncabezado";
                                            $variables.= "&id_encabezado=" . $registroEncabezados[$p][0];
                                            $variables.= "&encabezado_nombre=" . $registroEncabezados[$p][1];
                                            $variables.= "&planEstudio=" . $registroEncabezados[$p][3];
                                            $variables.= "&codProyecto=" . $registroEncabezados[$p][4];
                                            $variables.= "&clasificacion=" . $registroEncabezados[$p][5];
                                            $variables.= "&nroCreditos=" . $registroEncabezados[$p][9];
                                            $variables.= "&nivel=" . $registroEncabezados[$p][10];
                                            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                            $this->cripto = new encriptar();
                                            $variables = $this->cripto->codificar_url($variables, $configuracion);
                                            ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?}
                                          }
                                          ?>

                        </td>
                        <td  class="cuadro_plano centrar">
                        </td>
                        <td class='cuadro_plano centrar' bgcolor="#ECF8E0">
                        </td>
                                        <?
                                        break;
                                    }
                                    $idEncabezado = $registroEncabezados[$p][0];
                                    $encabezado = array($registroEncabezados[$p][0], $registroPlan[0][0]);
                                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultarEspaciosEncabezado", $encabezado);
                                    $registroEspEncabezados = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                                    if (is_array($registroEspEncabezados)) {
                                      ?>
                        <!--<td class='cuadro_plano centrar' colspan="8" bgcolor="#ECF8E0"><font color="#090497">Espacios Acad&eacute;micos con Opciones</font></td>-->
                                      <?
                                      for ($q = 0;$q < count($registroEspEncabezados);$q++) {
                                        $espacioOpcion[0] = $registroEspEncabezados[$q][0];
                                        $espacioOpcion[1] = $registroPlan[0][0];
                                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosEspacioOpcion", $espacioOpcion);
                                        $registroEspaciosOpcion = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                                        //Busca los comentarios no leidos
                                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "comentariosNoLeidos", $espacioOpcion[0], $espacioOpcion[1]);
                                        $comentariosNoLeidos = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                                        $porNotas = 0;
                                        $porInscripcion = 0;
                                        $porHorario = 0;
                                        ?>

                      <tr>
                                        <?
                                    /*<td class='cuadro_plano centrar'><strong><?echo $registroEspaciosOpcion[0][2]?></strong></td>*/
                                        ?>
                        <td class='cuadro_plano derecha' width="40"><font color="#049713"><? echo $registroEspaciosOpcion[0][0] ?></font></td>
                        <td class='cuadro_plano'>&nbsp;&nbsp;&nbsp;<font color="#049713"><? echo $registroEspaciosOpcion[0][1] ?></font></td>
                                        <?
                                        //verificar que 3*creditos=HTD+HTC+HTA si no se cumple se muestran los registros en rojo
                                        if ((48 * $registroEspaciosOpcion[0][3]) == (($registroEspaciosOpcion[0][4] + $registroEspaciosOpcion[0][5] + $registroEspaciosOpcion[0][6]) * $registroEspaciosOpcion[0][13])) {
                                          ?>
                        <td class='cuadro_plano derecha'><font color='#049713'><? echo $registroEspaciosOpcion[0][3] ?></font></td>
                        <td class='cuadro_plano derecha'><font color="#049713"><? echo $registroEspaciosOpcion[0][4] ?></font></td>
                        <td class='cuadro_plano derecha'><font color="#049713"><? echo $registroEspaciosOpcion[0][5] ?></font></td>
                        <td class='cuadro_plano derecha'><font color="#049713"><? echo $registroEspaciosOpcion[0][6] ?></font></td>
                                        <?
                                        } else {
                                          ?>
                        <td class='cuadro_plano derecha'><font color='red'><? echo $registroEspaciosOpcion[0][3] ?></font></td>
                        <td class='cuadro_plano derecha'><font color='red'><? echo $registroEspaciosOpcion[0][4] ?></font></td>
                        <td class='cuadro_plano derecha'><font color='red'><? echo $registroEspaciosOpcion[0][5] ?></font></td>
                        <td class='cuadro_plano derecha'><font color='red'><? echo $registroEspaciosOpcion[0][6] ?></font></td>
                                        <?
                                        }
                                        ?>
                        <td class='cuadro_plano'>&nbsp;&nbsp;&nbsp;<font color="#049713"><? echo $registroEspaciosOpcion[0][7] ?></font></td>
                                        <?
                                        //verifica que este aprobado el espacio academico
                                        ?>
                                        <?
                                        //                        echo $registroEspaciosOpcion[0][11];exit;
                                        //                        echo $registroEspEncabezados[$q][1];exit;
                                        if ($registroEspaciosOpcion[0][11] == 1 && $registroEspEncabezados[$q][1] == 1) {
                                          ?>      <td class='cuadro_plano centrar' colspan="2">
                          Aprobado
                        </td>
                        <td class="cuadro_plano centrar">
                                            <?
                                            if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                              ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                                       echo "<br>Notas";
                                                     }
                                                     if ($porHorario == '1') {
                                                       echo "<br>Horarios";
                                                     }
                                                     if ($porInscripcion == '1') {
                                                       echo "<br>Inscripciones";
                                                     } ?>',this)">
                          </div>

                                            <?
                                            } else {
        if($permiso==1)
        {
                                                
                                              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                              $variables = "pagina=registroModificarEspacioAsisVice";
                                              $variables.= "&opcion=modificarEspacio";
                                              $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                              $variables.= "&planEstudio=" . $registroPlan[0][0];
                                              $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                              $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                              $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                              $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                              $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                              $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                              $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                              $variables.= "&semanas=" . $registroEspaciosOpcion[0][13];
                                              $variables.= "&codProyecto=" . $registroPlan[0][10];
                                              include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                              $this->cripto = new encriptar();
                                              $variables = $this->cripto->codificar_url($variables, $configuracion);
                                              ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?}
                                            }
                                            ?>

                        </td>
                        <td class='cuadro_plano centrar'>
                        </td>
                        <td class='cuadro_plano centrar'>
                                            <?
                                            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                            $variables = "pagina=registroAgregarComentarioEspacioAsisVice";
                                            $variables.= "&opcion=verComentarios";
                                            $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                            $variables.= "&planEstudio=" . $registroPlan[0][0];
                                            $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                            $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                            $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                            $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                            $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                            $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                            $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                            $variables.= "&codProyecto=" . $registroPlan[0][10];
                                            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                            $this->cripto = new encriptar();
                                            $variables = $this->cripto->codificar_url($variables, $configuracion);
                                            ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" width="25" height="25" border="0"><br>
                                              <?
                                              if (count($comentariosNoLeidos) > 0) {
                                                echo "Nuevos(" . count($comentariosNoLeidos) . ")";
                                              } else {
                                              }
                                              ?>
                          </a>
                        </td>
                                        <?
                                        } else if ($registroEspaciosOpcion[0][11] == 2 || $registroEspEncabezados[$q][1] == 2) {
                                            ?>      <td class='cuadro_plano centrar' colspan="2">
                          No Aprobado</td>
                        <td class="cuadro_plano centrar">
                                              <?
                                              if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                                ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                                         echo "<br>Notas";
                                                       }
                                                       if ($porHorario == '1') {
                                                         echo "<br>Horarios";
                                                       }
                                                       if ($porInscripcion == '1') {
                                                         echo "<br>Inscripciones";
                                                       } ?>',this)">
                          </div>
                                              <?
                                              } else {
        if($permiso==1)
        {
                                                  
                                                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                                $variables = "pagina=registroModificarEspacioAsisVice";
                                                $variables.= "&opcion=modificarEspacio";
                                                $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                                $variables.= "&planEstudio=" . $registroPlan[0][0];
                                                $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                                $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                                $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                                $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                                $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                                $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                                $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                                $variables.= "&semanas=" . $registroEspaciosOpcion[0][13];
                                                $variables.= "&codProyecto=" . $registroPlan[0][10];
                                                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                                $this->cripto = new encriptar();
                                                $variables = $this->cripto->codificar_url($variables, $configuracion);
                                                ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?}
                                              }
                                              ?>


                        </td>
                        <td class='cuadro_plano centrar'>
                        </td>
                        <td class='cuadro_plano centrar'>
                                              <?
                                              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                              $variables = "pagina=registroAgregarComentarioEspacioAsisVice";
                                              $variables.= "&opcion=verComentarios";
                                              $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                              $variables.= "&planEstudio=" . $registroPlan[0][0];
                                              $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                              $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                              $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                              $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                              $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                              $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                              $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                              $variables.= "&codProyecto=" . $registroPlan[0][10];
                                              include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                              $this->cripto = new encriptar();
                                              $variables = $this->cripto->codificar_url($variables, $configuracion);
                                              ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" width="25" height="25" border="0"><br>
                                                <?
                                                if (count($comentariosNoLeidos) > 0) {
                                                  echo "Nuevos(" . count($comentariosNoLeidos) . ")";
                                                } else {
                                                }
                                                ?>
                          </a>
                        </td>
                                          <?
                                          } else {
                                            ?>
                        <td class='cuadro_plano centrar'>
                                              <?
        if($permiso==1)
        {
                                              
                                              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                              $variables = "pagina=registroAprobarOpcionAsisVice";
                                              $variables.= "&opcion=aprobar";
                                              $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                              $variables.= "&planEstudio=" . $registroPlan[0][0];
                                              $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                              $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                              $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                              $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                              $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                              $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                              $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                              $variables.= "&nombreGeneral=" . $registroEncabezados[$p][1];
                                              $variables.= "&idEncabezado=" . $idEncabezado;
                                              $variables.= "&codProyecto=" . $registroPlan[0][10];
                                              $variables = $this->cripto->codificar_url($variables, $configuracion);

                                              ?>

                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="25" height="25" border="0">
                          </a>
                            <?}?>
                        </td>
                        <td class='cuadro_plano centrar'>
                                              <?
        if($permiso==1)
        {
                                              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                              $variables = "pagina=registroAprobarOpcionAsisVice";
                                              $variables.= "&opcion=no_aprobar";
                                              $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                              $variables.= "&planEstudio=" . $registroPlan[0][0];
                                              $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                              $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                              $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                              $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                              $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                              $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                              $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                              $variables.= "&nombreGeneral=" . $registroEncabezados[$p][1];
                                              $variables.= "&idEncabezado=" . $idEncabezado;
                                              $variables.= "&codProyecto=" . $registroPlan[0][10];
                                              include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                              $this->cripto = new encriptar();
                                              $variables = $this->cripto->codificar_url($variables, $configuracion);

                                              ?>

                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="25" height="25" border="0">
                          </a>
                            <?}?>
                        </td>
                        <td class="cuadro_plano centrar">
                                              <?
                                              if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                                ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                                         echo "<br>Notas";
                                                       }
                                                       if ($porHorario == '1') {
                                                         echo "<br>Horarios";
                                                       }
                                                       if ($porInscripcion == '1') {
                                                         echo "<br>Inscripciones";
                                                       } ?>',this)">
                          </div>

                                              <?
                                              } else {
        if($permiso==1)
        {
                                                  
                                                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                                $variables = "pagina=registroModificarEspacioAsisVice";
                                                $variables.= "&opcion=modificarEspacio";
                                                $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                                $variables.= "&planEstudio=" . $registroPlan[0][0];
                                                $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                                $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                                $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                                $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                                $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                                $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                                $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                                $variables.= "&semanas=" . $registroEspaciosOpcion[0][13];
                                                $variables.= "&codProyecto=" . $registroPlan[0][10];
                                                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                                $this->cripto = new encriptar();
                                                $variables = $this->cripto->codificar_url($variables, $configuracion);
                                                ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?
        }
                                              }
                                              ?>


                        </td>
                        <td class='cuadro_plano centrar'>
                        </td>
                        <td class='cuadro_plano centrar'>
                                              <?
                                              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                              $variables = "pagina=registroAgregarComentarioEspacioAsisVice";
                                              $variables.= "&opcion=verComentarios";
                                              $variables.= "&codEspacio=" . $registroEspaciosOpcion[0][0];
                                              $variables.= "&planEstudio=" . $registroPlan[0][0];
                                              $variables.= "&nivel=" . $registroEspaciosOpcion[0][2];
                                              $variables.= "&creditos=" . $registroEspaciosOpcion[0][3];
                                              $variables.= "&htd=" . $registroEspaciosOpcion[0][4];
                                              $variables.= "&htc=" . $registroEspaciosOpcion[0][5];
                                              $variables.= "&hta=" . $registroEspaciosOpcion[0][6];
                                              $variables.= "&clasificacion=" . $registroEspaciosOpcion[0][8];
                                              $variables.= "&nombreEspacio=" . $registroEspaciosOpcion[0][1];
                                              $variables.= "&codProyecto=" . $registroPlan[0][10];
                                              include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                              $this->cripto = new encriptar();
                                              $variables = $this->cripto->codificar_url($variables, $configuracion);

                                              ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" width="25" height="25" border="0"><br>
                                                <?
                                                if (count($comentariosNoLeidos) > 0) {
                                                  echo "Nuevos(" . count($comentariosNoLeidos) . ")";
                                                } else {
                                                }
                                                ?>
                          </a>
                        </td>
                                          <?
                                          }
                                      }
                                    }
                                    ?>
                      </tr>
                                <?
                                } //$a++;

                              }
                              unset($registroEncabezados);
                            }
                          for ($a = 0;$a < $totalEspacios;$a++) {
                              If ($registro[$a][2]==$value[0])
                              {


                            ?><tr>
                              <?
                              $porNotas = '0';
                              $porInscripcion = '0';
                              $porHorario = '0';
                              //Cuenta los creditos por nivel y los creditos total del plan de estudio
                              $creditosRegistrados+=$registro[$a][3];
                              $creditosNivel+= $registro[$a][3];
                              //Busca los comentarios no leidos
                              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "comentariosNoLeidos", $registro[$a][0], $registroPlan[0][0]);
                              $comentariosNoLeidos = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                              ?>
                        <td class='cuadro_plano centrar'><? echo $registro[$a][0] ?></td>
                        <td class='cuadro_plano'><? echo $registro[$a][1];
                                if ($registro[$a][13] == 32) {
                                  echo " (Anualizado)";
                                } ?></td>
                                <?
                                //verificar que 48(horas)*creditos=(HTD+HTC+HTA)*(Nro Semanas) si no se cumple se muestran los registros en rojo
                                if ((48 * $registro[$a][3]) == (($registro[$a][4] + $registro[$a][5] + $registro[$a][6]) * $registro[$a][13])) {
                                  ?>
                        <td class='cuadro_plano centrar'><? echo $registro[$a][3] ?></td>
                        <td class='cuadro_plano centrar'><? echo $registro[$a][4] ?></td>
                        <td class='cuadro_plano centrar'><? echo $registro[$a][5] ?></td>
                        <td class='cuadro_plano centrar'><? echo $registro[$a][6] ?></td>
                              <?
                              } else {
                                ?>
                        <td class='cuadro_plano centrar'><font color='red'><? echo $registro[$a][3] ?></font></td>
                        <td class='cuadro_plano centrar'><font color='red'><? echo $registro[$a][4] ?></font></td>
                        <td class='cuadro_plano centrar'><font color='red'><? echo $registro[$a][5] ?></font></td>
                        <td class='cuadro_plano centrar'><font color='red'><? echo $registro[$a][6] ?></font></td>
                              <?
                              }
                              ?>
                        <td class='cuadro_plano'><? echo $registro[$a][7] ?></td>
                              <?
                              //verifica que este aprobado el espacio academico
                              if ($registro[$a][11] == 1) {
                              $creditosTotal+=$registro[$a][3];
                              switch ($registro[$a][8]) {
                                case '1':
                                  $ob++;
                                  $obRegistrados+=$registro[$a][3];
                                  $creob+= $registro[$a][3];
                                  break;
                                case '2':
                                  $oc++;
                                  $ocRegistrados+=$registro[$a][3];
                                  $creoc+= $registro[$a][3];
                                  break;
                                case '3':
                                  $ei++;
                                  $eiRegistrados+=$registro[$a][3];
                                  $creei+= $registro[$a][3];
                                  break;
                                case '4':
                                  $ee++;
                                  $eeRegistrados+=$registro[$a][3];
                                  $creee+= $registro[$a][3];
                                  break;
                                case '5':
                                  $cp++;
                                  $cpRegistrados+=$registro[$a][3];
                                  $crecp+= $registro[$a][3];
                                  break;
                              }
                                ?>
                        <td class='cuadro_plano centrar' colspan="2">
                          Aprobado
                            <?$creditosAprobados+=$registro[$a][3];?>
                        </td>
                        <td class="cuadro_plano centrar">

                                  <?
                                  if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                    ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                             echo "<br>Notas";
                                           }
                                           if ($porHorario == '1') {
                                             echo "<br>Horarios";
                                           }
                                           if ($porInscripcion == '1') {
                                             echo "<br>Inscripciones";
                                           } ?>',this)">
                          </div>
                                  <?
                                  } else {
        if($permiso==1)
        {
                                      
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variables = "pagina=registroModificarEspacioAsisVice";
                                    $variables.= "&opcion=modificarEspacio";
                                    $variables.= "&codEspacio=" . $registro[$a][0];
                                    $variables.= "&planEstudio=" . $registroPlan[0][0];
                                    $variables.= "&nivel=" . $registro[$a][2];
                                    $variables.= "&creditos=" . $registro[$a][3];
                                    $variables.= "&htd=" . $registro[$a][4];
                                    $variables.= "&htc=" . $registro[$a][5];
                                    $variables.= "&hta=" . $registro[$a][6];
                                    $variables.= "&clasificacion=" . $registro[$a][8];
                                    $variables.= "&nombreEspacio=" . $registro[$a][1];
                                    $variables.= "&semanas=" . $registro[$a][13];
                                    $variables.= "&codProyecto=" . $registroPlan[0][10];
                                    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                    $this->cripto = new encriptar();
                                    $variables = $this->cripto->codificar_url($variables, $configuracion);
                                    ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?
                          
                          }
                                  }
                                  ?>

                        </td>
                        <td class='cuadro_plano centrar'>

                        <?
                        if($permiso==1)
                        {
                            
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroBorrarEACoordinador";
                            $variables.="&opcion=confirmarBorrarEA";
                            $variables.="&codEspacio=".$registro[$a][0];
                            $variables.="&planEstudio=".$registroPlan[0][0];
                            $variables.="&nivel=".$registro[$a][2];
                            $variables.="&nroCreditos=".$registro[$a][3];
                            $variables.="&htd=".$registro[$a][4];
                            $variables.="&htc=".$registro[$a][5];
                            $variables.="&hta=".$registro[$a][6];
                            $variables.="&clasificacion=".$registro[$a][8];
                            $variables.="&nombreEspacio=".$registro[$a][1];
                            $variables.="&nombreProyecto=".$registroPlan[0][7];
                            $variables.="&codProyecto=" . $registroPlan[0][10];
                            $variables.="&aprobado=1";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>

                            <a href="<?echo $pagina.$variables?>" class="centrar">
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/delete.png" width="25" height="25" border="0"><br><font size="1">Inactivar</font>
                            </a>
                            <?}?>                            
                        </td>
                        <td class='cuadro_plano centrar'>
                                  <?
                                  $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                  $variables = "pagina=registroAgregarComentarioEspacioAsisVice";
                                  $variables.= "&opcion=verComentarios";
                                  $variables.= "&codEspacio=" . $registro[$a][0];
                                  $variables.= "&planEstudio=" . $registroPlan[0][0];
                                  $variables.= "&nivel=" . $registro[$a][2];
                                  $variables.= "&creditos=" . $registro[$a][3];
                                  $variables.= "&htd=" . $registro[$a][4];
                                  $variables.= "&htc=" . $registro[$a][5];
                                  $variables.= "&hta=" . $registro[$a][6];
                                  $variables.= "&clasificacion=" . $registro[$a][8];
                                  $variables.= "&nombreEspacio=" . $registro[$a][1];
                                  $variables.= "&codProyecto=" . $registroPlan[0][10];
                                  include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                  $this->cripto = new encriptar();
                                  $variables = $this->cripto->codificar_url($variables, $configuracion);
                                  ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" width="25" height="25" border="0"><br>

                                    <?
                                    if (count($comentariosNoLeidos) > 0) {
                                      echo "Nuevos(" . count($comentariosNoLeidos) . ")";
                                    } else {
                                    }
                                    ?>
                          </a>
                        </td><?
                              } else if ($registro[$a][11] == 2) {
                              switch ($registro[$a][8]) {
                                case '1':
                                  $obRegistrados+=$registro[$a][3];
                                  break;
                                case '2':
                                  $ocRegistrados+=$registro[$a][3];
                                  break;
                                case '3':
                                  $eiRegistrados+=$registro[$a][3];
                                  break;
                                case '4':
                                  $eeRegistrados+=$registro[$a][3];
                                  break;
                                case '5':
                                  $cpRegistrados+=$registro[$a][3];
                                  break;
                              }
                                  ?>
                        <td class='cuadro_plano centrar' colspan="2">
                          No aprobado</td>
                        <td class="cuadro_plano centrar">
                                    <?
                                    if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                      ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                               echo "<br>Notas";
                                             }
                                             if ($porHorario == '1') {
                                               echo "<br>Horarios";
                                             }
                                             if ($porInscripcion == '1') {
                                               echo "<br>Inscripciones";
                                             } ?>',this)">
                          </div>
                                    <?
                                    } else {
        if($permiso==1)
        {
                                        
                                      $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                      $variables = "pagina=registroModificarEspacioAsisVice";
                                      $variables.= "&opcion=modificarEspacio";
                                      $variables.= "&codEspacio=" . $registro[$a][0];
                                      $variables.= "&planEstudio=" . $registroPlan[0][0];
                                      $variables.= "&nivel=" . $registro[$a][2];
                                      $variables.= "&creditos=" . $registro[$a][3];
                                      $variables.= "&htd=" . $registro[$a][4];
                                      $variables.= "&htc=" . $registro[$a][5];
                                      $variables.= "&hta=" . $registro[$a][6];
                                      $variables.= "&clasificacion=" . $registro[$a][8];
                                      $variables.= "&nombreEspacio=" . $registro[$a][1];
                                      $variables.= "&semanas=" . $registro[$a][13];
                                      $variables.= "&codProyecto=" . $registroPlan[0][10];
                                      include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                      $this->cripto = new encriptar();
                                      $variables = $this->cripto->codificar_url($variables, $configuracion);
                                      ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?
        }
                                    }
                                    ?>

                        </td>
                        <td class='cuadro_plano centrar'>
                        </td>
                        <td class='cuadro_plano centrar'>
                                    <?
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variables = "pagina=registroAgregarComentarioEspacioAsisVice";
                                    $variables.= "&opcion=verComentarios";
                                    $variables.= "&codEspacio=" . $registro[$a][0];
                                    $variables.= "&planEstudio=" . $registroPlan[0][0];
                                    $variables.= "&nivel=" . $registro[$a][2];
                                    $variables.= "&creditos=" . $registro[$a][3];
                                    $variables.= "&htd=" . $registro[$a][4];
                                    $variables.= "&htc=" . $registro[$a][5];
                                    $variables.= "&hta=" . $registro[$a][6];
                                    $variables.= "&clasificacion=" . $registro[$a][8];
                                    $variables.= "&nombreEspacio=" . $registro[$a][1];
                                    $variables.= "&codProyecto=" . $registroPlan[0][10];
                                    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                    $this->cripto = new encriptar();
                                    $variables = $this->cripto->codificar_url($variables, $configuracion);
                                    ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" width="25" height="25" border="0"><br>

                                      <?
                                      if (count($comentariosNoLeidos) > 0) {
                                        echo "Nuevos(" . count($comentariosNoLeidos) . ")";
                                      } else {
                                      }
                                      ?>
                          </a>
                        </td><?
                                } else {
                              switch ($registro[$a][8]) {
                                case '1':
                                  $obRegistrados+=$registro[$a][3];
                                  break;
                                case '2':
                                  $ocRegistrados+=$registro[$a][3];
                                  break;
                                case '3':
                                  $eiRegistrados+=$registro[$a][3];
                                  break;
                                case '4':
                                  $eeRegistrados+=$registro[$a][3];
                                  break;
                                case '5':
                                  $cpRegistrados+=$registro[$a][3];
                                  break;
                              }
                                  ?>
                        <td class='cuadro_plano centrar'>
        <?if($permiso==1)
        {?>
                          <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario
                                          ?>'>
                            <input type="hidden" name="codEspacio" value="<? echo $registro[$a][0] ?>">
                            <input type="hidden" name="planEstudio" value="<? echo $registroPlan[0][0] ?>">
                            <input type="hidden" name="nivel" value="<? echo $registro[$a][2] ?>">
                            <input type="hidden" name="creditos" value="<? echo $registro[$a][3] ?>">
                            <input type="hidden" name="htd" value="<? echo $registro[$a][4] ?>">
                            <input type="hidden" name="htc" value="<? echo $registro[$a][5] ?>">
                            <input type="hidden" name="hta" value="<? echo $registro[$a][6] ?>">
                            <input type="hidden" name="clasificacion" value="<? echo $registro[$a][8] ?>">
                            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                            <input type="hidden" name="opcion" value="guardar">
                            <input type="image" src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="25" height="25">
                          </form>
<?}?>                            
                        </td>
                        <td class='cuadro_plano centrar'>
                                    <?
        if($permiso==1)
        {
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variables = "pagina=registroNoAprobarEspacioAsisVice";
                                    $variables.= "&opcion=no_aprobar";
                                    $variables.= "&codEspacio=" . $registro[$a][0];
                                    $variables.= "&planEstudio=" . $registroPlan[0][0];
                                    $variables.= "&nivel=" . $registro[$a][2];
                                    $variables.= "&creditos=" . $registro[$a][3];
                                    $variables.= "&htd=" . $registro[$a][4];
                                    $variables.= "&htc=" . $registro[$a][5];
                                    $variables.= "&hta=" . $registro[$a][6];
                                    $variables.= "&clasificacion=" . $registro[$a][8];
                                    $variables.= "&nombreEspacio=" . $registro[$a][1];
                                    $variables.= "&codProyecto=" . $registroPlan[0][10];
                                    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                    $this->cripto = new encriptar();
                                    $variables = $this->cripto->codificar_url($variables, $configuracion);
                                    ?>

                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/x.png" width="25" height="25" border="0">
                          </a>
<?}?>                            

                        </td>
                        <td class="cuadro_plano centrar">
                                    <?
                                    if ($porNotas == '1' || $porInscripcion == '1' || $porHorario == '1') {
                                      ?>
                          <div class="centrar">
                            <span id="toolTipBox" width="300" ></span>
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/error.png" width="25" height="25" border="0"
                                 onmouseover="toolTip('Existen registros en la base datos <? if ($porNotas == '1') {
                                               echo "<br>Notas";
                                             }
                                             if ($porHorario == '1') {
                                               echo "<br>Horarios";
                                             }
                                             if ($porInscripcion == '1') {
                                               echo "<br>Inscripciones";
                                             } ?>',this)">
                          </div>
                                    <?
                                    } else {
        if($permiso==1)
        {
                                        
                                      $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                      $variables = "pagina=registroModificarEspacioAsisVice";
                                      $variables.= "&opcion=modificarEspacio";
                                      $variables.= "&codEspacio=" . $registro[$a][0];
                                      $variables.= "&planEstudio=" . $registroPlan[0][0];
                                      $variables.= "&nivel=" . $registro[$a][2];
                                      $variables.= "&creditos=" . $registro[$a][3];
                                      $variables.= "&htd=" . $registro[$a][4];
                                      $variables.= "&htc=" . $registro[$a][5];
                                      $variables.= "&hta=" . $registro[$a][6];
                                      $variables.= "&clasificacion=" . $registro[$a][8];
                                      $variables.= "&nombreEspacio=" . $registro[$a][1];
                                      $variables.= "&semanas=" . $registro[$a][13];
                                      $variables.= "&codProyecto=" . $registroPlan[0][10];
                                      include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                      $this->cripto = new encriptar();
                                      $variables = $this->cripto->codificar_url($variables, $configuracion);
                                      ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/modificar.png" width="25" height="25" border="0"><br>
                          </a>                    <?
        }
                                    }
                                    ?>
                        </td>
                        <td class='cuadro_plano centrar'>
                            <?
        if($permiso==1)
        {
                            
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroBorrarEACoordinador";
                            $variables.="&opcion=confirmarBorrarEA";
                            $variables.="&codEspacio=".$registro[$a][0];
                            $variables.="&planEstudio=".$registroPlan[0][0];
                            $variables.="&nivel=".$registro[$a][2];
                            $variables.="&nroCreditos=".$registro[$a][3];
                            $variables.="&htd=".$registro[$a][4];
                            $variables.="&htc=".$registro[$a][5];
                            $variables.="&hta=".$registro[$a][6];
                            $variables.="&clasificacion=".$registro[$a][8];
                            $variables.="&nombreEspacio=".$registro[$a][1];
                            $variables.="&nombreProyecto=".$registroPlan[0][7];
                            $variables.="&codProyecto=" . $registroPlan[0][10];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>

                            <a href="<?echo $pagina.$variables?>" class="centrar">
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/delete.png" width="25" height="25" border="0"><br><font size="1">Borrar</font>
                            </a>
                            <?}?>
                        </td>
                        <td class='cuadro_plano centrar'>
                                    <?
                                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                    $variables = "pagina=registroAgregarComentarioEspacioAsisVice";
                                    $variables.= "&opcion=verComentarios";
                                    $variables.= "&codEspacio=" . $registro[$a][0];
                                    $variables.= "&planEstudio=" . $registroPlan[0][0];
                                    $variables.= "&nivel=" . $registro[$a][2];
                                    $variables.= "&creditos=" . $registro[$a][3];
                                    $variables.= "&htd=" . $registro[$a][4];
                                    $variables.= "&htc=" . $registro[$a][5];
                                    $variables.= "&hta=" . $registro[$a][6];
                                    $variables.= "&clasificacion=" . $registro[$a][8];
                                    $variables.= "&nombreEspacio=" . $registro[$a][1];
                                    $variables.= "&codProyecto=" . $registroPlan[0][10];
                                    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                                    $this->cripto = new encriptar();
                                    $variables = $this->cripto->codificar_url($variables, $configuracion);
                                    ?>
                          <a href="<? echo $pagina . $variables ?>" class="centrar">
                            <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viewrel.png" width="25" height="25" border="0"><br>

                                      <?
                                      if (count($comentariosNoLeidos) > 0) {
                                        echo "Nuevos(" . count($comentariosNoLeidos) . ")";
                                      } else {
                                      }
                                      ?>
                          </a>
                        </td>
                                <?
                                }
                              ?>
                      </tr>

                            <?
                              }

                          }
                              ?>
                      <tr><td class='cuadro_plano centrar'  colspan='6'>TOTAL CR&Eacute;DITOS:
                                  <?
                                  if ($registroPlan[0][0] == '261' || $registroPlan[0][0] == '262' || $registroPlan[0][0] == '263' || $registroPlan[0][0] == '269') {
                                    $creditosNiveles = '36';
                                  } else {
                                    $creditosNiveles = '18';
                                  }
                                  if ($creditosNivel > $creditosNiveles) {
                                    ?><font color=red><? echo $creditosNivel
                                    ?></font><?
                                  } else {
                                    ?><font color=blue><? echo $creditosNivel
                                    ?></font><?
                                  }
                                  ?>
                        </td>
                        <td class='cuadro_plano centrar'  colspan='6'>TOTAL CR&Eacute;DITOS APROBADOS:
                                  <?
                                  if ($creditosAprobados > $creditosNiveles) {
                                    ?><font color=red><? echo $creditosAprobados;
                                    ?></font><?
                                  } else {
                                    ?><font color=blue><? echo $creditosAprobados;
                                    ?></font><?
                                  }
                                  ?>
                        </td>
                      </tr>
                      <tr>
                        <td colspan='12'></td>
                      </tr><?
                              $creditosNivel = 0;
                              $creditosAprobados = 0;
                  }
  /**
 * Fin presentar nivel
 */
                          ?>
                    </table>
                    <table border="0" width="100%">

                      <tr>
                        <td class="cuadro_plano centrar">
                          H.T.D : Horas de Trabajo Directo<br>
                          H.T.C : Horas de Trabajo Cooperativo<br>
                          H.T.A : Horas de Trabajo Aut&oacute;nomo
                        </td>
                      </tr>
                    </table>

                        <?
                          }
                          else
                            {
                                $this->presentarMensajeNoEspacios();
                            }
//USO DE LA CLASE planEstudios.class.php

        if ($registroPlan[0]['PLAN_PROPEDEUTICO']==0)
        {
            $creditosTotal=$creditosTotal-$crecp;
        }
      $mensajeEncabezado='CRÉDITOS APROBADOS POR VICERRECTORIA';
      $mensaje='Los cr&eacute;ditos aprobados por vicerrector&iacute;a, corresponden a la suma de cr&eacute;ditos de los espacios acad&eacute;micos<br>
                registrados por el coordinador y aprobados por vicerrector&iacute;a, para el plan de estudios.';
      $creditosAprobados=array(array('OB'=>$creob,
                                    'OC'=>$creoc,
                                    'EI'=>$creei,
                                    'EE'=>$creee,
                                    'CP'=>$crecp,
                                    'TOTAL'=>$creditosTotal,
                                    'ENCABEZADO'=>$mensajeEncabezado,
                                    'MENSAJE'=>$mensaje,
                                    'PROPEDEUTICO'=>$registroPlan[0]['PLAN_PROPEDEUTICO']));
      $this->parametrosPlan->mostrarParametrosRegistradosPlan($creditosAprobados);
      $mensajeEncabezado='RANGOS DE CR&Eacute;DITOS INGRESADOS POR EL COORDINADOR *';
      $mensaje='*Los rangos de cr&eacute;ditos, corresponden a los datos que el Coordinador registr&oacute; como par&aacute;metros iniciales<br>
                del plan de estudio, seg&uacute;n lo establecido en el art&iacute;culo 12 del acuerdo 009 de 2006.';
      $parametrosAprobados=array(array('ENCABEZADO'=>$mensajeEncabezado,
                                        'MENSAJE'=>$mensaje,
                                        'PROPEDEUTICO'=>$registroPlan[0]['PLAN_PROPEDEUTICO']));
      $this->parametrosPlan->mostrarParametrosAprobadosPlan($registroPlan[0]['PLAN'],$parametrosAprobados);



//FIN USO CLASE

                        ?>

              </table>
            </td>

          </tr>

      </table>
    </td>
  </tr>

</tbody>
</table>


    <?
    ?>
</td>
</tr>

</table>


  <?
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



  function guardarAprobacion($configuracion) {
    $codEspacio = $_REQUEST['codEspacio'];
    $planEstudio = $_REQUEST['planEstudio'];
    $nivel = $_REQUEST['nivel'];
    $creditos = $_REQUEST['creditos'];
    $htd = $_REQUEST['htd'];
    $htc = $_REQUEST['htc'];
    $hta = $_REQUEST['hta'];
    $espacioObligatorio='';
    $clasificacion = $_REQUEST['clasificacion'];
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "periodoActivo");
    $periodoActivo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
    $datosPlan=$this->consultarDatosPlan($configuracion, $planEstudio);
    if (is_array($datosPlan)&&$datosPlan[0]['PLAN_PROPEDEUTICO']==0&&$clasificacion==5)
    {
        $espacioObligatorio='N';
    }else
        {
            $espacioObligatorio='S';
        }

    if (isset($codEspacio) and isset($planEstudio)) {
    #Actualiza la aprobacion de los espacios academicos
      $this->cadena_sql = $this->sql->cadena_sql($configuracion, "aprobarEspacio", $codEspacio, $planEstudio);
      $registroEspaciosPlan = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
      $totalEspacios = $this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
      //Vericar que se ejecuto la aprobacion de cada uno de los espacios academicos
      if ($registroEspaciosPlan == true) {
      //Busca los datos del espacio academico que se va a aprobar, para poder pasarlos a oracle
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosEspacio", $codEspacio, $planEstudio);
        $registrodatosEspacios = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
        //Busca los datos de la carrera
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosNumeroCarreras", $codEspacio, $planEstudio);
        $numeroCarreras = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosCarrera", $codEspacio, $planEstudio);
        $registrodatosCarrera = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
        //Si carga el espacio academico en Oracle en la tabla acasi, debemos cargarlo ahora en acpen
        if ($clasificacion == '3' || $clasificacion == '4') {
          $electivo = 'S';
        } else {
          $electivo = 'N';
        }
        $nombreEspacio = strtr(strtoupper($registrodatosEspacios[0][0]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
        //Arreglo para tener los datos a cargar en ACASI (Oracle)
        $variableAcasi = array($codEspacio, $nombreEspacio, $registrodatosCarrera[0][0], 'A', 'S', 'N');
        // Arreglo para tener los datos a cargar en ACPEN (Oracle)
        for ($a=0;$a<$numeroCarreras[0][0];$a++) {
          $variableAcpen[$a] = array($registrodatosCarrera[$a][1], $codEspacio, $nivel, $electivo, $htd, $htc, 'A', $creditos, $planEstudio, $hta,$espacioObligatorio);
        }
        //buscar datos en ACASI
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacioAcasi", $variableAcasi, $planEstudio);
        $busquedaAcasi = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
        //Si existe el espacio academico en acasi, se busca en acpen si esta registrado con el plan de estudio
        if (is_array($busquedaAcasi)) {
        //buscar datos en ACPEN
          for ($a=0;$a<$numeroCarreras[0][0];$a++) {
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacioAcpen", $variableAcpen[$a], $planEstudio);
            $busquedaAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
            if (is_array($busquedaAcpen)) {break;}
          }
          //Si existen datos en acpen con el plan de estudios estipulado, se envia mensaje de que no se puede cargar el espacio academico por que ya esta cargado
          if (is_array($busquedaAcpen)) {
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variableAcasi[0], $planEstudio);
            $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
            ?>
<table class='contenidotabla centrar' border="0" width="100%">
  <tr align="center">
    <td class="centrar" colspan="4">
      <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 102</h4>
      <hr noshade class="hr">

    </td>
  </tr>
              <?
              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
              $variables = "pagina=adminAprobarEspacioPlan";
              $variables.= "&opcion=mostrar";
              $variables.= "&planEstudio=" . $planEstudio;
              include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
              $this->cripto = new encriptar();
              $variables = $this->cripto->codificar_url($variables, $configuracion);
              ?>
  <tr align="center">
    <td class="centrar" colspan="4">
      <a href="<? echo $pagina . $variables ?>" class="centrar">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
      </a>
    </td>
  </tr>
</table><?
          } else {
            for ($a=0;$a<$numeroCarreras[0][0];$a++) {
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cargarEspacioAcpen", $variableAcpen[$a], $planEstudio);
              $registroEspaciosCargadoAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");

              $variableAcpen[$a]['clasificacion']=$clasificacion;
              $this->cadena_sql=  $this->sql->cadena_sql($configuracion, 'registrarClasificacion', $variableAcpen[$a]);
              $registroClasificacion = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");

              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadocargarEspacio", $variableAcpen[$a][1], $planEstudio);
              $registroEstadoCargado = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
              //Arreglo para registrar en el log de eventos
              $variablesRegistro = array($this->usuario, date('YmdHis'), '18', 'Aprobo Espacio Academico Existente', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ".$_REQUEST['codEspacio'] . ", 0, 0, " . $_REQUEST['planEstudio'] .", ".$registrodatosCarrera[$a][1], $_REQUEST['planEstudio']);
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
              $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
            }
            if ($registroEspaciosCargadoAcpen == true) {
              ?>
<table class='contenidotabla centrar' border="0" width="100%">
  <tr align="center">
    <td class="centrar" colspan="4">
      <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> HA SIDO APROBADO Y CARGADO CORRECTAMENTE</h4>
      <hr noshade class="hr">

    </td>
  </tr>
                <?
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variables = "pagina=adminAprobarEspacioPlan";
                $variables.= "&opcion=mostrar";
                $variables.= "&planEstudio=" . $planEstudio;
                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variables = $this->cripto->codificar_url($variables, $configuracion);
                ?>
  <tr align="center">
    <td class="centrar" colspan="4">
      <a href="<? echo $pagina . $variables ?>" class="centrar">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
      </a>
    </td>
  </tr>
</table><?
            } else {
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variableAcasi[0], $planEstudio);
              $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
              if ($cambiarEstadoEspacio == true) { ?>
<table class='contenidotabla centrar' border="0" width="100%">
  <tr align="center">
    <td class="centrar" colspan="4">
      <h6>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 101* </h6>
      <hr noshade class="hr">

    </td>
  </tr><?
                  $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                  $variables = "pagina=adminAprobarEspacioPlan";
                  $variables.= "&opcion=mostrar";
                  $variables.= "&planEstudio=" . $planEstudio;
                  include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variables = $this->cripto->codificar_url($variables, $configuracion);
                  ?>
  <tr align="center">
    <td class="centrar" colspan="4">
      <a href="<? echo $pagina . $variables ?>" class="centrar">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
      </a>
    </td>
  </tr>
</table>
              <?
              }
            }
          }
        } else {
          for ($a=0;$a<$numeroCarreras[0][0];$a++) {
          //buscar datos en ACPEN
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEspacioAcpen", $variableAcpen[$a], $planEstudio);
            $busquedaAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
            if (is_array($busquedaAcpen)) {break;}
          }
          if (is_array($busquedaAcpen)) {
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variableAcasi[0], $planEstudio);
            $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
            ?>
            <table class='contenidotabla centrar' border="0" width="100%">
              <tr align="center">
                <td class="centrar" colspan="4">
                  <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 102</h4>
                  <hr noshade class="hr">

                </td>
              </tr>
              <?
              $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
              $variables = "pagina=adminAprobarEspacioPlan";
              $variables.= "&opcion=mostrar";
              $variables.= "&planEstudio=" . $planEstudio;
              include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
              $this->cripto = new encriptar();
              $variables = $this->cripto->codificar_url($variables, $configuracion);
              ?>
                <tr align="center">
                  <td class="centrar" colspan="4">
                    <a href="<? echo $pagina . $variables ?>" class="centrar">
                      <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                    </a>
                  </td>
                </tr>
              </table>
          <?
          } else {
          //Cargar datos en ACASi
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cargarEspacioAcasi", $variableAcasi, $planEstudio);
            $registroEspaciosCargadoAcasi = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
            if ($registroEspaciosCargadoAcasi == true) {
            //Cargar datos en ACPEN
              for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "cargarEspacioAcpen", $variableAcpen[$a], $planEstudio);
                $registroEspaciosCargadoAcpen = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
                if ($registroEspaciosCargadoAcpen == true) {

                  $variableAcpen[$a]['clasificacion']=$clasificacion;
                  $this->cadena_sql=  $this->sql->cadena_sql($configuracion, 'registrarClasificacion', $variableAcpen[$a]);
                  $registroClasificacion = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");

                //Si se registra en acpen se envia mensaje notificando de que se aprobo correctamente
                  $this->cadena_sql = $this->sql->cadena_sql($configuracion, "estadocargarEspacio", $variableAcpen[$a][1], $planEstudio);
                  $registroEstadoCargado = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                  //Arreglo para registrar en el log de eventos
                  $variablesRegistro = array($this->usuario, date('YmdHis'), '10', 'Aprobo Espacio Academico', $periodoActivo[0][0]."-".$periodoActivo[0][1].", ".$_REQUEST['codEspacio'] . ", 0, 0, " . $_REQUEST['planEstudio'] .", ".$registrodatosCarrera[$a][1], $_REQUEST['planEstudio']);
                  $this->cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesRegistro);
                  $registroEvento = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                }
              }
              if ($registroEspaciosCargadoAcpen == true) {


                ?>
                  <table class='contenidotabla centrar' border="0" width="100%">
                    <tr align="center">
                      <td class="centrar" colspan="4">
                        <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> HA SIDO APROBADO Y CARGADO CORRECTAMENTE</h4>
                        <hr noshade class="hr">

                      </td>
                    </tr>
                  <?
                  $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                  $variables = "pagina=adminAprobarEspacioPlan";
                  $variables.= "&opcion=mostrar";
                  $variables.= "&planEstudio=" . $planEstudio;
                  include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variables = $this->cripto->codificar_url($variables, $configuracion);
                  ?>
                  <tr align="center">
                    <td class="centrar" colspan="4">
                      <a href="<? echo $pagina . $variables ?>" class="centrar">
                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                      </a>
                    </td>
                  </tr>
                  <?
                  $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                  $variables = "pagina=registroComentarioPlanAsisVice";
                  $variables.= "&opcion=verComentarios";
                  $variables.= "&planEstudio=" . $planEstudio;
                  include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variables = $this->cripto->codificar_url($variables, $configuracion);
                  ?>
                    <tr align="center">
                      <td class="centrar" colspan="4">
                        <a href="<? echo $pagina . $variables ?>" class="centrar">
                          <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/kword.png" width="35" height="35" border="0"><br>Enviar mensaje general
                        </a>
                      </td>
                    </tr>
                  </table>
              <?
              }
            } else {
            //Si el espacio no se puede cargar en acpen se debe borrar de acasi y cambiar el estado sga_planEstudio_espacio
            //Borrar datos en ACASI del espacio que se queria aprobar
              $this->cadena_sql = $this->sql->cadena_sql($configuracion, "borrarEspacioAcasi", $variableAcasi, $planEstudio);
              $borrarEspacioAcasi = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "");
              if ($borrarEspacioAcasi == true) {
              //Cambiar Estado de aprobacion sga_planEstudio_espacio
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variableAcasi[0], $planEstudio);
                $cambiarEstadoEspacio = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "");
                if ($cambiarEstadoEspacio == true) { ?>
                  <table class='contenidotabla centrar' border="0" width="100%">
                    <tr align="center">
                      <td class="centrar" colspan="4">
                        <h6>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 101</h6>
                        <hr noshade class="hr">

                      </td>
                    </tr><?
                    $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                    $variables = "pagina=adminAprobarEspacioPlan";
                    $variables.= "&opcion=mostrar";
                    $variables.= "&planEstudio=" . $planEstudio;
                    include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variables = $this->cripto->codificar_url($variables, $configuracion);
                    ?>
                    <tr align="center">
                      <td class="centrar" colspan="4">
                        <a href="<? echo $pagina . $variables ?>" class="centrar">
                          <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                        </a>
                      </td>
                    </tr>
                  </table>
                <?
                }
              }
            }
          }
        }
      } else {
        ?>
          <table class='contenidotabla centrar' border="0" width="100%">
            <tr align="center">
              <td class="centrar" colspan="4">
                <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <? echo $codEspacio ?> NO HA SIDO APROBADO -- ERROR 104</h4>
                <hr noshade class="hr">

              </td>
            </tr>
          <?
          $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
          $variables = "pagina=adminAprobarEspacioPlan";
          $variables.= "&opcion=mostrar";
          $variables.= "&planEstudio=" . $planEstudio;
          include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $variables = $this->cripto->codificar_url($variables, $configuracion);
          ?>
          <tr align="center">
            <td class="centrar" colspan="4">
              <a href="<? echo $pagina . $variables ?>" class="centrar">
                <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
              </a>
            </td>
          </tr>
        </table><?
      }
    }
  }


  function encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto) {
      $variablesEvento=array('ano'=>$this->ano,
                            'periodo'=>$this->periodo);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEventoGestionPlanes",$variablesEvento);
        $fechasEventoPlanes = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
        $fecha=date('Ymd');
        if($fecha>$fechasEventoPlanes[0]['FIN'])
        {
            $permiso=0;
        }else{$permiso=1;}
    ?>

<table class='contenidotabla centrar'>
  <tr align="center">
    <td class="centrar" colspan="3">
      <h4>M&Oacute;DULO PARA LA ADMINISTRACI&Oacute;N DE PLANES DE ESTUDIOS</h4>
    </td>
  </tr>
  <tr align="center">
    <td class="centrar" width="40%">
      <table class='contenidotabla centrar'>
        <tr>
          <td class="centrar" width="14%">
                <?
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variables = "pagina=adminAprobarEspacioPlan";
                $variables.= "&opcion=ver";
                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variables = $this->cripto->codificar_url($variables, $configuracion);
                ?>
            <a href="<? echo $pagina . $variables ?>">
              <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
          </td>
          <td class="centrar" width="14%">
                <?
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variables = "pagina=registro_aprobarPortafolio";
                $variables.= "&opcion=ver";
                include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variables = $this->cripto->codificar_url($variables, $configuracion);
                ?>
            <a href="<? echo $pagina . $variables ?>">
              <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/portafolio2.png" width="35" height="35" border="0"><br>Portafolio<br>Electivas Extr&iacute;nsecas
            </a>
          </td>
          <?
          if ($planEstudio!=""&&$codProyecto!=""&&$nombreProyecto!=""){
              if ($permiso==1){?>
        <td class="centrar" width="16">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroConsultarAgrupacionEspaciosCoordinador";
                    $variables.="&opcion=verEncabezado";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;


                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
        ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/agrupar.png" width="35" height="35" border="0"><br>
                Administrar Espacios<br> con Opciones
            </a>
        </td>
        <td class="centrar" width="14%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroCrearEACoordinador";
                    $variable.="&opcion=seleccionClasificacion";
                    $variable.="&codProyecto=".$codProyecto;
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

        ?>
            <a href="<?echo $pagina.$variable?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="38" height="38" border="0" ><br>
                <font size="1">Solicitar Creaci&oacute;n<br>Espacio Acad&eacute;mico</font>
            </a>
        </td>
        <td class="centrar" width="16%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroPortafolioElectivasCoordinador";
                    $variables.="&opcion=crear";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;
                    $variables.="&clasificacion=4";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="35" height="35" border="0"><br>Solicitud Creaci&oacute;n<br>Electivo Extr&iacute;nseco
            </a>
        </td>
        <td class="centrar" width="14%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                    $variables.="&opcion=listaPlanes";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
        ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/favorito.png" width="35" height="35" border="0"><br>Solicitar Agregar<br> Espacio Existente
            </a>
        </td>
        <?}
        ?>
        <td class="centrar" width="14%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registro_parametrosPlanEstudio";
                    $variables.="&opcion=administrar";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
        ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/parametros.png" width="35" height="35" border="0"><br>
                Par&aacute;metros <br>Plan Estudios
            </a>
        </td>


        <?}?>

        </tr>
      </table>

    </td>
  </tr>
</table><?
  }


  function editarParametros($configuracion) {
    $cadena_sql = $this->sql->cadena_sql($configuracion, "datosCarreraParametros", $_REQUEST['planEstudio']);
    $registroParametros = $this->accesoGestion->ejecutarAcceso($cadena_sql, "busqueda");
    $codProyecto=$registroParametros[0][3];
    $planEstudio=$_REQUEST['planEstudio'];
    $nombreProyecto="'".$registroParametros[0][4]."'";
    $this->encabezadoModulo($configuracion, $_REQUEST['planEstudio'], $registroParametros[0][3], $registroParametros[0][4]);
    ?>

<form action="index.php" name="<? echo $this->formulario
          ?>" method="POST">
  <table class="contenidotabla centrar">
    <tr class="centrar">
      <td colspan="4">
        Digite el n&uacute;mero total de cr&eacute;ditos del plan de estudios <?echo $planEstudio;?>:<br>
        <input type="text" id="totalCreditos" name="totalCreditos" value="<? echo $_REQUEST['totalCreditos'] ?>" size="3" maxlength="3">
        <br><img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/kwrite.png" width="25" height="25" alt="Continuar" border="0" onclick="xajax_editarCreditosPlan(document.getElementById('totalCreditos').value, <? echo $codProyecto;?>, <? echo $planEstudio;?>,<?echo $nombreProyecto;?>)">
      </td>
    </tr>
    <tr>
      <td colspan="4">
        <div id="div_creditos">

        </div>
      </td>
    </tr>


    <tr class="centrar">
      <td colspan="4">
        <input type="hidden" name="planEstudio" value="<? echo $_REQUEST['planEstudio'] ?>">
        <input type="hidden" name="codProyecto" value="<? echo $registroParametros[0][3] ?>">
        <input type="hidden" name="nombreProyecto" value="<? echo $registroParametros[0][4] ?>">
        <input type="hidden" name="opcion" value="aprobarParametros">
        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
        <div id="div_graficas">

        </div>
      </td>
    </tr>

  </table>
</form><?
  }


  function aprobarParametros($configuracion) {
    $cadena_sql = $this->sql->cadena_sql($configuracion, "datosCarreraParametros", $_REQUEST['planEstudio']);
    $resultado_datos = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    $variablesParametros = array($_REQUEST['planEstudio'], $_REQUEST['totalCreditos'], '40', '18', '8', $_REQUEST['OB'], $_REQUEST['OC'], $_REQUEST['EI'], $_REQUEST['EE'], $_REQUEST['CP']);
    $cadena_sql = $this->sql->cadena_sql($configuracion, "actualizarParametros", $variablesParametros);
    $resultado_parametros = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");
    if ($resultado_parametros == true) {
      $variablesLog = array($this->usuario, date('YmdGis'), '39', 'Actualizo parametros plan Estudio', "T:".$_REQUEST['totalCreditos'] . " - OB:" . $_REQUEST['OB'] . " - OC:" . $_REQUEST['OC'] . " - EI:" . $_REQUEST['EI'] . " - EE:" . $_REQUEST['EE']. " - CP:" . $_REQUEST['CP'], $_REQUEST['planEstudio']);
      $cadena_sql = $this->sql->cadena_sql($configuracion, "registroEvento", $variablesLog);
      $resultado_evento = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");
      $variablesComentario = array($_REQUEST['planEstudio'], $resultado_datos[0][3], $this->usuario, date('YmdGis'), 'Los parametros del plan de estudio se aprobaron satisfactoriamente');
      $cadena_sql = $this->sql->cadena_sql($configuracion, "comentarioAprobar", $variablesComentario);
      $resultado_comentario = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");
      $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
      $ruta = "pagina=adminAprobarEspacioPlan";
      $ruta.= "&codProyecto=" . $resultado_datos[0][3];
      $ruta.= "&planEstudio=" . $_REQUEST['planEstudio'];
      $ruta.= "&nombreProyecto=" . $resultado_datos[0][4];
      include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
      $this->cripto = new encriptar();
      $ruta = $this->cripto->codificar_url($ruta, $configuracion);
      echo "<script>location.replace('" . $pagina . $ruta . "')</script>";
    }
  }


  function vistaPrincipalParametros($configuracion) {
    $cadena_sql = $this->sql->cadena_sql($configuracion, "datosCarreraParametros", $_REQUEST['planEstudio']);
    $registroParametros = $this->accesoGestion->ejecutarAcceso($cadena_sql, "busqueda");
    $this->encabezadoModulo($configuracion, $_REQUEST['planEstudio'], $registroParametros[0][3], $registroParametros[0][4]);
    $cadena_sql = $this->sql->cadena_sql($configuracion, "parametrosPlan", $_REQUEST['planEstudio']);
    $resultado_parametros = $this->accesoGestion->ejecutarAcceso($cadena_sql, "busqueda");
    $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarDatosPlan",$_REQUEST['planEstudio']);
    $resultado_datosPlan = $this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda" );

    ?>
<table class="contenidotabla">
  <tr>
    <td colspan="7" class="cuadro_color centrar">
      PAR&Aacute;METROS DEL PLAN DE ESTUDIOS
    </td>
  </tr>
  <tr class="cuadro_plano centrar">
    <td class="cuadro_plano centrar">Obligatorios Basicos<br><? echo $resultado_parametros[0][0] ?></td>
    <td class="cuadro_plano centrar">Obligatorios Complementarios<br><? echo $resultado_parametros[0][1] ?></td>
    <td class="cuadro_plano centrar">Electivos Intrinsecos<br><? echo $resultado_parametros[0][2] ?></td>
    <td class="cuadro_plano centrar">Electivos Extrinsecos<br><? echo $resultado_parametros[0][3] ?></td>
    <td class="cuadro_plano centrar">Componente Proped&eacute;utico<br><? echo $resultado_parametros[0][6] ?></td>
    <td class="cuadro_plano centrar">Editar<br>
          <?
          $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
          $ruta = "pagina=adminAprobarEspacioPlan";
          $ruta.= "&opcion=editarParametros";
          $ruta.= "&totalCreditos=" . $resultado_parametros[0][4];
          $ruta.= "&OB=" . $resultado_parametros[0][0];
          $ruta.= "&OC=" . $resultado_parametros[0][1];
          $ruta.= "&EI=" . $resultado_parametros[0][2];
          $ruta.= "&EE=" . $resultado_parametros[0][3];
          $ruta.= "&CP=" . $resultado_parametros[0][6];
          $ruta.= "&codProyecto=" . $registroParametros[0][3];
          $ruta.= "&planEstudio=" . $_REQUEST['planEstudio'];
          $ruta.= "&nombreProyecto=" . $_REQUEST['nombreProyecto'];
          include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $ruta = $this->cripto->codificar_url($ruta, $configuracion);
          ?>
      <a href="<? echo $pagina . $ruta ?>">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/kword.png" width="25" height="25" alt="Continuar" border="0">
      </a>
    </td>
    <td class="cuadro_plano centrar">
          <? if ($resultado_parametros[0][5] == '1') {
            echo "Aprobado";
          } else {
            echo "Aprobar<br>";
            $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
            $ruta = "pagina=adminAprobarEspacioPlan";
            $ruta.= "&opcion=aprobarParametros";
            $ruta.= "&totalCreditos=" . $resultado_parametros[0][4];
            $ruta.= "&OB=" . $resultado_parametros[0][0];
            $ruta.= "&OC=" . $resultado_parametros[0][1];
            $ruta.= "&EI=" . $resultado_parametros[0][2];
            $ruta.= "&EE=" . $resultado_parametros[0][3];
            $ruta.= "&CP=" . $resultado_parametros[0][6];
            $ruta.= "&codProyecto=" . $registroParametros[0][3];
            $ruta.= "&planEstudio=" . $_REQUEST['planEstudio'];
            $ruta.= "&nombreProyecto=" . $_REQUEST['nombreProyecto'];
            include_once ($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $ruta = $this->cripto->codificar_url($ruta, $configuracion);
            ?>

      <a href="<? echo $pagina . $ruta ?>">
        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/clean.png" width="25" height="25" alt="Continuar" border="0">
      </a>
          <?
          }
          ?>
    </td>
  </tr>
</table>

    <?
    $OB = $resultado_parametros[0][0];
    $OC = $resultado_parametros[0][1];
    $EI = $resultado_parametros[0][2];
    $EE = $resultado_parametros[0][3];
    $CP = $resultado_parametros[0][6];
    $maximo = $resultado_parametros[0][4];
    $porcentajeObligatorios = (($OB + $OC) / $maximo) * 100;
    $porcentajeObligatoriosBasicos = (($OB) / ($OB + $OC)) * 100;
    $porcentajeObligatoriosComplementarios = (($OC) / ($OB + $OC)) * 100;
    $porcentajeElectivos = (($EI + $EE) / $maximo) * 100;
    $porcentajeElectivosIntrinsecos = (($EI) / ($EI + $EE)) * 100;
    $porcentajeElectivosExtrinsecos = ($EE / ($EI + $EE)) * 100;
    if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)
    {
        $sumaCreditos=$OB+$OC+$EI+$EE+$CP;
    }
    else
        {
            $sumaCreditos=$OB+$OC+$EI+$EE;
        }
    $vista = "<table class='contenidotabla centrar'><tr><td class='centrar'>Suma de Cr&eacute;ditos: " . $sumaCreditos . "<br>Total de cr&eacute;ditos:" . $maximo . "</td></tr></table>";
    $vista.= "<table class='tablaGrafico' align='center' width='100%' cellspacing='0' cellpadding='2'>
";
    if ($porcentajeObligatorios > '0') {
      $vista.= "<tr>
<td  width='70%' class='centrar' bgcolor='#F3DF8D'> <font color='black'>Obligatorios: " . round($porcentajeObligatorios, 1) . " %</font>
<table class='tablaGrafico' width='100%' cellspacing='0' cellpadding='1' ";
      if ($porcentajeObligatoriosBasicos > '0') {
            if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)//para planes de estudio de Ingenieria de la facultad tecnologica
            { $ancho=$porcentajeObligatoriosBasicos-20;
                $vista.="<tr>
                        <td width='".$ancho."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                        </td>
                        <td width='20%' class='centrar texto_gris' bgcolor='#CEE3F6' style='border:5px solid #29467F'> CP ".$CP." cred</td>";
            }else{
            $vista.="<tr>
                        <td width='".$porcentajeObligatoriosBasicos."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                        </td>";
            }
      }
      if ($porcentajeObligatoriosComplementarios > '0') {
        $vista.= "<td width='" . $porcentajeObligatoriosComplementarios . "%' class='centrar'  height='100%' bgcolor='#6B8FD4'>OC<br> " . round($porcentajeObligatoriosComplementarios, 1) . " %
</td>
</tr>
</table>";
      }
    }
    if ($porcentajeElectivos > '0') {
      $vista.= "</td>
<td width='30%' class='centrar' bgcolor='#F7EDC5'><font color='black'>Electivos: " . round($porcentajeElectivos, 1) . " %
<table class='tablaGrafico'  width='100%' cellspacing='0' cellpadding='1' >";
      if ($porcentajeElectivosIntrinsecos > '0') {
        $vista.= "<tr>
<td width='" . $porcentajeElectivosIntrinsecos . "%' class='centrar'  height='100%' bgcolor='#006064'>EI<br> " . round($porcentajeElectivosIntrinsecos, 1) . " %
</td>";
      }
      if ($porcentajeElectivosExtrinsecos > '0') {
        $vista.= "<td width='" . $porcentajeElectivosExtrinsecos . "%' class='centrar'  height='100%' bgcolor='#36979E'>EE<br> " . round($porcentajeElectivosExtrinsecos, 1) . " %
</td>
</tr>
</table>";
      }
    }
    $vista.= "</td>
</tr>
</table>";
    echo $vista;
    ?>
<table class="contenidotabla" border="0" >
  <tr>
    <td class="cuadro_plano centrar">
      OB : Obligatorios Basicos<br>
      OC : Obligatorios Complemetarios<br>
      EI : Electivos Intrinsecos<br>
      EE : Electivos Extrinsecos<br>
      CP : Componente Proped&eacute;utico
    </td>
  </tr>
</table>
  <?
  }


  function consultarParametrosPlan($configuracion,$plan) {
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscarParametros", $plan);
    return $resultado_parametros = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
  }

  function consultarDatosPlan($configuracion,$plan) {
    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "buscar_id", $plan);
    return $registroPlan = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
  }
}
?> 