
<?php
/**
 * Funcion adminInscripcionGrupoCoordinadorPosgrado
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 10/03/2011
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");

/**
 * Clase funcion_adminInscripcionGrupoCoordinadorPosgrado
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class funcion_adminInscripcionGrupoCoordinadorPosgrado extends funcionGeneral {

  public $configuracion;
  public $ano;
  public $periodo;
  Public $codProyecto;
  public $planEstudio;


    /**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionGrupoCoordinadorPosgrado
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion) {
        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        $this->configuracion=$configuracion;
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $this->sql = new sql_adminInscripcionGrupoCoordinadorPosgrado($configuracion);
        $this->formulario = "admin_inscripcionGrupoCoorPosgrado";
        $this->planEstudio=(isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:'');
        $this->codProyecto=(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        

        
        /**
         * Instancia para crear la conexion General
         */
        $this->acceso_db = $this->conectarDB($configuracion, "");
        /**
         * Instancia para crear la conexion de MySQL
         */
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        /**
         * Datos de sesion
         */
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        //Conexion ORACLE
        //
        if($this->nivel==4){
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
      }elseif($this->nivel==110){
              $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }
      
        //Buscar año y periodo activos
      $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
      $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      $this->ano=$resultado_peridoActivo[0]['ANO'];
      $this->periodo=$resultado_peridoActivo[0]['PERIODO'];
      
      

    }

    /**
     *
     */
    function mostrarEspaciosOpcionNivel(){
    if (isset($this->planEstudio, $this->codProyecto)) {

    }

    else {

    echo  'El proyecto o el plan de estudios no es v&aacute;lido';

    }

      $this->buscador();

      //si no existe el parametro nivel, busca un espacio academico por codigo; si existe busca los espacios academicos del nivel
      if(isset($_REQUEST['nivel'])){


            $this->mostrarEncabezadoNivel($_REQUEST['nivel']);

            $resultado_espaciosAcademicos=$this->buscarEspaciosAcademicosPorNivel();

            $this->generarVistaEspacios($resultado_espaciosAcademicos);

      }


  }

    /**
     *
     */
    function mostrarEspaciosOpcionCodigo(){

    if (isset($this->planEstudio, $this->codProyecto)) {

    }

    else {

    echo  'El proyecto o el plan de estudios no es v&aacute;lido';

    }

      $this->buscador();

      //si no existe el parametro nivel, busca un espacio academico por codigo; si existe busca los espacios academicos del nivel
      if($_REQUEST['codEspacio'] && is_numeric($_REQUEST['codEspacio'])){

            $resultado_espaciosAcademicos=$this->buscarEspaciosAcademicosPorCodigo($_REQUEST['codEspacio']);

            $this->generarVistaEspacios($resultado_espaciosAcademicos);

      }
      else {echo 'Por favor ingrese un valor num&eacute;rico';}

  }

      /**
     *
     */
    function mostrarEspaciosOpcionNombre(){

    if (isset($this->planEstudio, $this->codProyecto)) {

    }

    else {

    echo  'El proyecto o el plan de estudios no es v&aacute;lido';

    }

      $this->buscador();

      //si no existe el parametro nivel, busca un espacio academico por codigo; si existe busca los espacios academicos del nivel
      if($_REQUEST['nombreEspacio']){

            $resultado_espaciosAcademicos=$this->buscarEspaciosAcademicosPorNombre($_REQUEST['nombreEspacio']);

            $this->generarVistaEspacios($resultado_espaciosAcademicos);

      }else{echo 'Por favor ingrese un nombre v&aacute;lido';}


  }

    /**
     * 
     * @param type $arregloEspacios
     */
    function generarVistaEspacios($arregloEspacios){

          if(is_array($arregloEspacios)){

            foreach ($arregloEspacios as $key => $espacio) {
            
                $this->mostrarEspacioAcademico($espacio);

                $resultadoGrupos=$this->buscarGrupos($espacio['CODIGO']);

                if(is_array($resultadoGrupos)){

                //tabla que presenta los datos del grupo:numero, horario, cupo, cupos disponibles, enlace para inscripciones
                ?><table class="sigma contenidotabla">

                <?
                $this->mostrarEncabezadoGrupo();

                foreach ($resultadoGrupos as $key => $grupo) {
                    $resultadoHorario=$this->consultarHorario($grupo['ID_GRUPO'],$espacio['CODIGO']);

                    if(is_array($resultadoHorario)){
                        $enlace=$this->mostrarEnlaceGrupo($grupo['ID_GRUPO'],$grupo['GRUPO'],$espacio['CODIGO']);

                        ?><tr onclick="location.href='<?=$enlace?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''"><?

                        $this->mostrarGrupo($grupo);
                    
                        $this->mostrarHorario($resultadoHorario);

                        $numeroInscrios=$this->contarInscritos($grupo['ID_GRUPO'],$espacio['CODIGO']);

                        $this->mostrarCupos($grupo,$numeroInscrios[0][0]);

                    }else{
                            $this->mostrarGrupo($grupo);
                        ?>
                          <td class="cuadro_plano centrar" align="center" colspan=10>
                              No hay horario registrado para este grupo
                          </td>
                        <?}

                            ?></tr><?
                      }

                 ?></table><?

                }else{
                              ?>
                <table class="sigma contenidotabla">
                  <tr>
                    <td class='cuadro_plano centrar'>
                      No hay grupos registrados para este Espacio Acad&eacute;mico
                    </td>
                  </tr>
                </table>
                              <?
                     }

            }

          }else{
                  ?>
                    <div style="float:left; font-family: Arial ;text-align:center ;background-color: #DDDDDD ;font-size: 15px;width:600px">
                      <b>No hay Espacios Académicos registrados en el nivel o c&oacute;digo seleccionado</b>
                    </div>
                  <?
                }

  }

    /**
     * Funcion que crea el buscador de espacios academicos
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $planEstudioCoor plan de estudio
     * @param int $codProyecto Codigo del proyecto curricular
     */
    function buscador() {

?>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div align="center">
                    <table class="sigma centrar" cellspacing=0px>
                        <caption class="sigma centrar">
                            BUSCAR ESPACIOS ACAD&Eacute;MICOS
                        </caption>
                        <tr class="sigma centrar">
                                <td>
                                    <?$this->seleccionarNivel();?>
                                </td>                                
                                <td class="sigma" rowspan="2">
                                  <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                  <input type="radio" name="opcionBusqueda" value="buscarCodigo" checked> C&oacute;digo<br>
                                  <input type="radio" name="opcionBusqueda" value="buscarNombre"
                                          <?if ($_REQUEST['opcion']=='consultarPorNombre'){echo 'checked';}?>
                                         > Nombre
                                </td>
                                
                                <td>
                                  <input type="text" name="datosBusqueda" size="20px">
                                </td>                                
                                <td>
                                    <input type="hidden" name="planEstudio" value="<? echo $this->planEstudio ?>">
                                    <input type="hidden" name="codProyecto" value="<? echo $this->codProyecto ?>">
                                    <small><input class="boton" type="submit" value=" Buscar "></small>
                                </td>
                                </tr>
                         
                       </table>
                    </div>
            </form>
<?      echo '<hr>';
        }
    
    /**
     * 
     */
    function seleccionarNivel() {

      ?>
      <table width="90%">
        <tr>
            <?//presenta los niveles de 1 a 10
            for($a=0;$a<=10;$a++){
              ?>
                
              <?
                    $this->enlaceNivel($a);
              ?>
               
            <?}?>
        </tr>
      </table>
      <?

    }
    
    /**
     * Funcion que presenta los enlaces para seleccionar los cursos de un nivel especifico
     * @param type $a
    **/
    function enlaceNivel($a) {

                          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                          $variable = "pagina=admin_inscripcionGrupoCoorPosgrado";
                          $variable.="&opcion=consultarPorNivel";
                          $variable.="&planEstudio=" . $this->planEstudio;
                          $variable.="&codProyecto=" . $this->codProyecto;
                          $variable.="&nivel=" . $a;
                        
                          include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                          $this->cripto = new encriptar();
                          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
?>                        
<td>
                            <a href="<? echo $pagina . $variable ?>" >
                                <?echo $a?>
                              <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/nivel_amarillo.png" width="20" height="20" border="0">
                              </a>
  </td>
                           <?
    }

    /**
     * 
     * @param type $nivel
     */
    function mostrarEncabezadoNivel($nivel) {
          ?>
          <table class="sigma_borde centrar" width="100%">
                          <caption class="sigma centrar">
                            Nivel <?echo $nivel?>
                          </caption>
          </table>
      <hr>
          <?
    }

    /**
     *
     */
    function buscarEspaciosAcademicosPorNivel() {

          $variablesEspacios = array('codProyecto' => $this->codProyecto,
                                     'planEstudio' => $this->planEstudio,
                                     'nivel' => $_REQUEST['nivel'],
                                     'opcion' => 'nivel');
          
          $cadena_sql = $this->sql->cadena_sql("buscarEspaciosAcademicos", $variablesEspacios);
          $arreglo_espacios = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_espacios;
}

    /**
     * 
     * @param type $nombre
     * @return type
     */
    function buscarEspaciosAcademicosPorNombre($nombre) {

      $nombre=strtoupper($nombre);//convierte a mayusculas
      $nombre=strtr($nombre, "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");//Ajusta los acentos y las ñ


          $variablesEspacios = array( 'codProyecto' => $this->codProyecto,
                                      'planEstudio' => $this->planEstudio,
                                      'nombreEspacio' => $nombre,
                                      'opcion' => 'nombre');

          $cadena_sql = $this->sql->cadena_sql("buscarEspaciosAcademicos", $variablesEspacios);
          $arreglo_espacio_busqueda = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_espacio_busqueda;
}

    /**
     * 
     * @param type $codigo
     * @return type
     */
    function buscarEspaciosAcademicosPorCodigo($codigo) {


          $variablesEspacios = array( 'codProyecto' => $this->codProyecto,
                                      'planEstudio' => $this->planEstudio,
                                      'codEspacio' => $codigo,
                                      'opcion' => 'codigo');

          $cadena_sql = $this->sql->cadena_sql("buscarEspaciosAcademicos", $variablesEspacios);
          $arreglo_espacio_busqueda = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_espacio_busqueda;
}

    /**
     * 
     * @param type $datosEspacio
     */
    function mostrarEspacioAcademico($datosEspacio) {
      
        $tamano_div=array('70','400','100','60','60','60');
        $rotulos=array('','','Cr&eacute;ditos: ','HTD: ','HTC: ','HTA: ');

    ?><div style="margin: 6px"><?
        for($a=0;$a<=5;$a++){

                    ?>
                    <div style="float:left; font-family: Arial ;text-align:center ;background-color: #DDDDDD ;font-size: 15px;width:<?echo $tamano_div[$a]?>px">
                    <?echo $rotulos[$a].'<b>'.(isset($datosEspacio[$a])?$datosEspacio[$a]:'').'</b>'?>
                    </div>

                <?}?>
        </div>
                    <?      

    }

    /**
     * 
     * @param type $codEspacio
     * @return type
     */
    function buscarGrupos($codEspacio) {

          $variablesGrupos = array( 'codProyecto' => $this->codProyecto,                                    
                                    'codEspacio'=>$codEspacio,
                                    'ano' => $this->ano,
                                    'periodo' => $this->periodo);
          
          $cadena_sql = $this->sql->cadena_sql("buscarGrupos", $variablesGrupos);
          $arreglo_grupos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_grupos;

    
}

    /**
     * 
     */
    function mostrarEncabezadoGrupo() {


      $camposEncabezado= array('Numero','Lunes','Martes','Miercoles','Jueves','Viernes','S&aacute;bado','Domingo','Cupo','Disponibles');
      ?>
              <tr class="cuadro_color">
                <?for($a=0;$a<count($camposEncabezado);$a++){
                  ?>
                  <td class="cuadro_plano centrar"><?echo $camposEncabezado[$a]?></td>
                <?}?>
                
              </tr>
            <?
  
}

    /**
     * 
     * @param type $grupo
     */
    function mostrarGrupo($grupo) {

        if (isset($grupo)) {
        ?>
            <td class="cuadro_plano centrar">
                <? echo $grupo['GRUPO'] ?>
            </td>
        <?
        }
        else {
          echo 'No existen Grupos';          
        }

      
      
    }

    /**
     * 
     * @param type $grupo
     * @param type $espacio
     * @return type
     */
    function consultarHorario($id_grupo,$espacio) {

      if(isset($id_grupo, $espacio)){
          $variablesHorario = array('espacio' => $espacio,
                                    'codGrupo' => $id_grupo,
                                    'ano' => $this->ano,
                                    'periodo' => $this->periodo);

          $cadena_sql_horario = $this->sql->cadena_sql("buscarHorarioGrupo", $variablesHorario);
          $arreglo_horario = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horario, "busqueda");

          return $arreglo_horario;
      }else{echo 'No existe horario';}

    }

    /**
     * 
     * @param type $horario
     */
    function mostrarHorario($horario) {

      if (is_array($horario)) {


                  for ($i = 1; $i < 8; $i++) {
                      ?><td class='cuadro_plano centrar'><?
                      for ($a = 0; $a < count($horario); $a++) {

                          if (    $horario[$a]['DIA'] == $i &&
                                  $horario[$a]['DIA'] == (isset($horario[$a + 1]['DIA'])?$horario[$a + 1]['DIA']:'') &&
                                  $horario[$a + 1]['HORA'] == ($horario[$a]['HORA'] + 1) &&
                                  $horario[$a + 1]['SALON'] == ($horario[$a]['SALON'])) {

                            $l = $a;
                              while ( $horario[$a]['DIA'] == $i &&
                                      $horario[$a]['DIA'] == (isset($horario[$a + 1]['DIA'])?$horario[$a + 1]['DIA']:'') &&
                                      $horario[$a + 1]['HORA'] == ($horario[$a]['HORA'] + 1) &&
                                      $horario[$a + 1]['SALON'] == ($horario[$a]['SALON'])) {

                                  $m = $a;
                                  $m++;
                                  $a++;
                              }
                              $dia = "<strong>" . $horario[$l]['HORA'] . "-" . ($horario[$m]['HORA'] + 1) . "</strong>";
                              echo $dia . "<br>";
                              unset($dia);
                          } elseif ($horario[$a]['DIA'] == $i && $horario[$a]['DIA'] != (isset($horario[$a + 1]['DIA'])?$horario[$a + 1]['DIA']:'')) {
                              $dia = "<strong>" . $horario[$a]['HORA'] . "-" . ($horario[$a]['HORA'] + 1) . "</strong>";
                              echo $dia . "<br>";
                              unset($dia);
                              $a++;
                          } elseif ($horario[$a]['DIA'] == $i && $horario[$a]['DIA'] == (isset($horario[$a + 1]['DIA'])?$horario[$a + 1]['DIA']:'') && (isset($horario[$a + 1]['SALON'])?$horario[$a + 1]['SALON']:'') != ($horario[$a]['SALON'])) {
                              $dia = "<strong>" . $horario[$a]['HORA'] . "-" . ($horario[$a]['HORA'] + 1) . "</strong>";
                              echo $dia . "<br>";
                              unset($dia);
                          } elseif ($horario[$a]['DIA'] != $i) {

                          }
                      }
                      ?></td><?
                  }
              } else {
                  echo "<td class='cuadro_plano centrar' colspan='7'>No tiene horario registrado</td>";
              }

}

    /**
     * 
     * @param type $grupo
     * @param type $espacio
     * @return type
     */
    function contarInscritos($grupo,$espacio) {

          if(isset($grupo, $espacio)){
          $variablesHorario = array('espacio' => $espacio,
                                    'codGrupo' => $grupo,
                                    'ano' => $this->ano,
                                    'periodo' => $this->periodo);

          $cadena_sql_numeroInscritos = $this->sql->cadena_sql("contarInscritos", $variablesHorario);
          $arreglo_numeroInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_numeroInscritos, "busqueda");

          return $arreglo_numeroInscritos;
      }else{echo 'No existe el grupo';}
    }

    /**
     * 
     * @param type $grupo
     * @param type $numeroInscritos
     */
    function mostrarCupos($grupo,$numeroInscritos) {
    //numero inscritos se obtiene haciendo un count en la tabla acins
    if(isset ($grupo)){
      ?>
                      <td class="cuadro_plano centrar"><?echo $grupo['CUPO']?></td>
                      <td class="cuadro_plano centrar"><?echo $grupo['CUPO']-$numeroInscritos?></td>
      <?
    }else{echo 'no tiene cupo ni disponible';}

}

    /**
     * 
     * @param type $grupo
     * @param type $espacio
     * @return type
     */
    function mostrarEnlaceGrupo($id_grupo,$grupo,$espacio) {

                          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                          $variable = "pagina=admin_estudiantesInscritosGrupoCoorPosgrado";
                          $variable.="&opcion=verGrupo";                          
                          $variable.="&codProyecto=" . $this->codProyecto;
                          $variable.="&planEstudio=" . $this->planEstudio;
                          $variable.="&codEspacio=" . $espacio;
                          $variable.="&id_grupo=" . $id_grupo;
                          $variable.="&grupo=" . $grupo;
                          $variable.="&nivel=" . $_REQUEST['nivel'];
                          //var_dump($_REQUEST);exit;
                          include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                          $this->cripto = new encriptar();
                          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                          return $pagina.$variable;
    }



      ###################################################



    /**
     * Funcion que crea los iconos de navegacion dentro del sistema
     *
     * @param array $this->configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param <type> $this->configuracion
     * @param <type> $atributos
     */
    function encabezado($atributos) {
                            ?>
                            <table class="contenidotabla centrar">
                                <tr>
                                    <td width="33%" class="centrar">
                                    <?
                                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                                    $variable = $atributos[0]['atras'];
                                    $variable.=$atributos[1]['atras'];
                                    $variable.=$atributos[2]['atras'];
                                    $variable.=$atributos[3]['atras'];
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                    ?>
                                        <a href="<? echo $pagina . $variable ?>">
                                            <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/atras.png" width="35" height="35" border="0">
                                        </a>
                                    </td>
                                    <td width="33%" class="centrar">
                            <?
                                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                                    $variable = $atributos[0]['inicio'];
                                    $variable.=$atributos[1]['inicio'];
                                    $variable.=$atributos[2]['inicio'];
                                    $variable.=$atributos[3]['inicio'];
                                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                            ?>
                                                <a href="<? echo $pagina . $variable ?>">
                                                    <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/inicio.png" width="35" height="35" border="0">
                                                </a>
                                            </td>
                                            <td width="33%" class="centrar">
                                                <a href="history.forward()">
                                                    <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/continuar.png" width="35" height="35" border="0">
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                            <?
                                }

    }
    ?>