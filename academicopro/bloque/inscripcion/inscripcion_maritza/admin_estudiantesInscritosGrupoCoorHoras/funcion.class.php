<?php
/**
 * Funcion admin_estudiantesInscritosGrupoCoorHoras
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoorHorasPorGrupo
 * @subpackage Consulta
 * @author Fernando Torres & Milton Parra
 * @version 0.0.0.1
 * Fecha: 04//04/2011
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

/**
 * Clase funcion_admin_estudiantesInscritosGrupoCoorHoras
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoorHorasPorGrupo
 * @subpackage Consulta
 */
class funcion_estudiantesInscritosGrupoCoorHoras extends funcionGeneral
{

      public $configuracion;
      public $ano;
      public $periodo;
      public $codProyecto;
      public $planEstudio;
      public $codEspacio;
      public $grupo;

  /**
         * Método constructor que crea el objeto sql de la clase funcion_adminConsultarInscripcionGrupoCoordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function __construct($configuracion)
            {
//        foreach ($datosEspacio as $key => $value) {
//          echo $key."=>".$value."<br>";
//}
//exit;
            $this->configuracion=$configuracion;
            $this->codProyecto=$_REQUEST['codProyecto'];
            $this->planEstudio=$_REQUEST['planEstudio'];
            $this->codEspacio=$_REQUEST['codEspacio'];
            $this->grupo=$_REQUEST['grupo'];
	    $this->id_grupo=$_REQUEST['id_grupo'];
	    /**
             * Incluye la clase encriptar.class.php
             *
             * Esta clase incluye funciones de encriptacion para las URL
             */
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            /**
             * Incluye la clase validar_fechas.class.php
             *
             * Esta clase incluye funciones que permiten validar si las fechas de adiciones y cancelaciones estan abiertas o no
             */
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");

            $this->fechas=new validar_fechas();
            
	    $this->cripto=new encriptar();

	    $this->sql=new sql_estudiantesInscritosGrupoCoorHoras($configuracion);

            $this->validacion= new validarInscripcion();
            
            $this->formulario="admin_estudiantesInscritosGrupoCoorHoras";
            $this->bloque="inscripcion/admin_estudiantesInscritosGrupoCoorHoras";

            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");
            /**
             * Instancia para crear la conexion de MySQL
             */
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            /**
             * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
             */
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

            /**
             * Datos de sesion
             */
	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");


              //Buscar año y periodo activos
            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->ano=$resultado_peridoActivo[0]['ANO'];
            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}

        /**
         * Funcion que genera la interfaz de usuario del grupo
         */
        function armarVistaGrupo() {
          $numero=1;
          $resultadoDatosProyecto=$this->buscarDatosProyecto();
          $this->mostrarDatosProyecto($resultadoDatosProyecto[0]);
          if(isset($_REQUEST['nivel']))
          {$this->mostrarEnlaceNivel($_REQUEST['nivel']);}
          $resultadoDatosEspacio=$this->buscarDatosEspacio();
          $this->mostrarEspacioAcademico($resultadoDatosEspacio[0]);
          $resultadoGrupos=$this->buscarGrupos();
          $this->mostrarBarraGrupos($resultadoGrupos);
          ?>
          <div style="margin: 5px">
              <table class="sigma contenidotabla">
              <?
              $this->mostrarEncabezadoHorario();
              ?>
                <tr>
              <?
              $this->mostrarGrupo();
              $resultadoHorario=$this->consultarHorario();
              $this->mostrarHorario($resultadoHorario);
              $numeroInscritos=$this->contarInscritos();
              $cupo=$this->rescatarCupodeGrupo($resultadoGrupos);//rescata el cupo del grupo seleccionado
              $this->mostrarCupos($cupo,$numeroInscritos[0][0]);
              ?>
                </tr>
              </table >
          </div>

              <?
          $resultadoEstudiantesInscritos=$this->buscarEstudiantesInscritos();
          $proceso=$this->procesoHabilitado();
              ?>


          <div style="margin: 5px">
              <table class="sigma contenidotabla">
                <?
                if(is_array($resultadoEstudiantesInscritos)){
                $this->tituloEstudiantesInscritos();
                $this->encabezadoEstudiantesInscritos($proceso);
                ?><!--<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? //echo $this->formulario ?>' Id="checBox">-->
                  <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' Id="checBox"><?
                        foreach ($resultadoEstudiantesInscritos as $key => $estudiante) {
                                ?><tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''"><?
                                $resultadoDatosEstudiante=$this->buscarDatosEstudiante($estudiante['CODIGO']);
                                $datosEstudiante['codEstudiante']=$estudiante['CODIGO'];
                                $resultadoDatosEstudiante[0]['VERIFICACION']=$this->validacion->validarEstudiante($datosEstudiante);
                                $this->mostrarDatosEstudiante($resultadoDatosEstudiante[0],$numero,$proceso,(isset($estudiante['CLASIFICACION'])?$estudiante['CLASIFICACION']:''));
                                $this->mostrarEnlaces($resultadoDatosEstudiante[0],$resultadoDatosEspacio[0],$proceso);
                                $numero++;
                                ?></tr><?
                        }
                        $this->enlaceCambiarGrupo($proceso,$resultadoDatosEspacio);
              ?></form><?
              }
              else
                {
                  $this->tituloNoInscritos();
                }
                ?>
              </table >
          </div>
          <?
        }

        /**
         * Funcion que consulta los datos del proyecto al que pertenece el grupo
         * @return <array> $arreglo_proyecto (CODIGO,NOMBRE)
         */
        function buscarDatosProyecto() {

              $variablesProyecto = array( 'codProyecto' => $this->codProyecto);

              $cadena_sql = $this->sql->cadena_sql("buscarDatosProyecto", $variablesProyecto);
              $arreglo_proyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_proyecto;
        }

        /**
         * Funcion que presenta los datos del proyecto en el grupo
         * @param <int> $proyecto codigo del proyecto
         */
        function mostrarDatosProyecto($proyecto) {
              ?>
              <table class="sigma_borde centrar" width="100%">
                              <th class="sigma_a centrar">
                                <?echo $proyecto['CODIGO'].' - '.$proyecto['NOMBRE'].'<br>Plan de Estudios '.$this->planEstudio;?>
                              </th>
              </table>
              
              <?
        }

        function mostrarEnlaceNivel($nivel) {
      ?>
      <table width="100%">
        <tr class="cuadro_plano centrar subrayado">
          <td class="centrar">
            <a href="<?= $this->crearEnlaceNivel($nivel) ?>">REGRESAR AL NIVEL <?echo $nivel?></a>
          </td>
        </tr>
      </table>
      <?

        }

        function crearEnlaceNivel($nivel) {
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_inscripcionGrupoCoorHoras";
            $variable.="&opcion=consultarPorNivel";
            $variable.="&planEstudio=" . $this->planEstudio;
            $variable.="&codProyecto=" . $this->codProyecto;
            $variable.="&nivel=" . $nivel;

            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            return $pagina.$variable;
        }

        /**
         * Funcion que consulta los datos del espacio academico del grupo
         * @return <array> $arreglo_espacio (CODIGO,NOMBRE,CREDITOS,HTD,HTC,HTA,ELECTIVO)
         */
        function buscarDatosEspacio() {


              $variablesEspacios = array( 'codProyecto' => $this->codProyecto,
                                          'planEstudio' => $this->planEstudio,
                                          'codEspacio' => $this->codEspacio,);

              $cadena_sql = $this->sql->cadena_sql("buscarEspaciosAcademicos", $variablesEspacios);
              $arreglo_espacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_espacio;
      }
        
        /**
         * Funcion que presenta la informacion del espacio academico del grupo.}
         * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS,HTD,HTC,HTA,ELECTIVO)
         */
        function mostrarEspacioAcademico($datosEspacio) {

            $gui=$this->configuracion['tamanno_gui'];
            $tamano_div=array('70','400','100','60','60','60');
            //$tamano_div=array($gui*0.09, $gui*0.5, $gui*0.1, $gui*0.08, $gui*0.08, $gui*0.08);
            $rotulos=array('','','Cr&eacute;ditos: ','HTD: ','HTC: ','HTA: ');

        ?><div style="margin: 6px"><?
            for($a=0;$a<=5;$a++){

                        ?>
                        <div style="float:left; font-family: Arial ;text-align:center ;background-color: #DDDDDD ;font-size: 15px;width:<?echo $tamano_div[$a]?>px">
                        <?echo $rotulos[$a].'<b>'.$datosEspacio[$a].'</b>'?>
                        </div>

                    <?}?>
            </div>
            <br>
                        <?

        }

          /**
           * Funcion que consulta los grupos del espacio academico creados en el proyecto
           * @param <array> $arreglo_grupos (GRUPO,CUPO,INSCRITOS)
           */
        function buscarGrupos() {

              $variablesGrupos = array( 'codProyecto' => $this->codProyecto,
                                        'codEspacio'=>  $this->codEspacio,
                                        'ano' => $this->ano,
                                        'periodo' => $this->periodo);

              $cadena_sql = $this->sql->cadena_sql("buscarGrupos", $variablesGrupos);
              $arreglo_grupos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

              return $arreglo_grupos;


        }

        /**
         * Funcion que presenta los enlaces para seleccionar los grupos de un espacio academico
         * @param <array> $grupos (GRUPO,CUPO,INSCRITOS) Un arreglo por cada grupo
         */
        function mostrarBarraGrupos($grupos) {

?>  
            <br><div style="float:left; width:15%; text-align:center">
                  GRUPOS:
                  </div>
        <?
        $porcentajeTamañoCelda=85/count($grupos);
        
        foreach ($grupos as $key=> $grupo) {
          $enlace=$this->enlaceGrupo($grupo['ID_GRUPO'],$grupo['GRUPO']);
          if ($_REQUEST['id_grupo']==$grupo['ID_GRUPO'])
            {$color="#E4EBFE";}
            else{$color='';}
          ?>
                    <div style="float:left; width:<?echo $porcentajeTamañoCelda?>%; text-align:center;background:<?echo $color?>; cursor:pointer" onclick="location.replace('<?echo $enlace?>')" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background='<?echo $color?>'">
                    <?echo $grupo['GRUPO'];?>
                    </div>                    
          <?
          }
          ?><br><?
        }

        /**
         * Funcion que genera enlaces para seleccionar cada grupo de un espacio academico
         * @param <int> $grupo codigo del grupo
         */
        function enlaceGrupo($id_grupo, $grupo) {

                              $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                              $variable = "pagina=admin_estudiantesInscritosGrupoCoorHoras";
                              $variable.="&opcion=verGrupo";
                              $variable.="&codProyecto=" . $this->codProyecto;
                              $variable.="&planEstudio=" . $this->planEstudio;
                              $variable.="&codEspacio=" . $this->codEspacio;
                              $variable.="&grupo=" . $grupo;
                              $variable.="&id_grupo=" . $id_grupo;
                              if (isset ($_REQUEST['nivel']))
                              {$variable.="&nivel=".$_REQUEST['nivel'];}

                              include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                              $this->cripto = new encriptar();
                              $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                               return $pagina.$variable;
        }

        /**
         * Funcion que presenta el encabezado del horario
         */
        function mostrarEncabezadoHorario() {


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
         * Funcion que presenta el numero del grupo
         */
        function mostrarGrupo() {

            if (isset($_REQUEST['grupo'])) {
            ?>
                <td class="cuadro_plano centrar">
                    <? echo $_REQUEST['grupo'] ?>
                </td>
            <?
            }
            else {
              echo 'No existen Grupos';
            }



        }

        /**
         * Funcion que consulta el horario del grupo.
         * @return <array> $arreglo_horario (DIA,HORA,SEDE,SALON)
         */
        function consultarHorario() {


          if(isset($_REQUEST['grupo'], $this->codEspacio)){
              $variablesHorario = array('espacio' => $this->codEspacio,
                                        'codGrupo' => $_REQUEST['id_grupo'],
                                        'ano' => $this->ano,
                                        'periodo' => $this->periodo);

              $cadena_sql_horario = $this->sql->cadena_sql("buscarHorarioGrupo", $variablesHorario); 
              
              $arreglo_horario = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horario, "busqueda");

              return $arreglo_horario;
          }else{echo 'No existe horario';}

        }

        /**
         * Funcion que presenta el horario del grupo
         * @param <array> $horario (DIA,HORA,SEDE,SALON) Un arreglo por cada hora
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
                          } elseif ($horario[$a]['DIA'] == $i && $horario[$a]['DIA'] == $horario[$a + 1]['DIA'] && $horario[$a + 1]['SALON'] != ($horario[$a]['SALON'])) {
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
         * Funcion que consulta el numero de inscritos en el grupo
         * @return <array> $arreglo_numeroInscritos Conteo de inscritos el el grupo
         */
        function contarInscritos() {

              if(isset($this->id_grupo, $this->codEspacio)){
              $variablesHorario = array('espacio' => $this->codEspacio,
                                        'codGrupo' => $this->id_grupo,
                                        'ano' => $this->ano,
                                        'periodo' => $this->periodo);

              $cadena_sql_numeroInscritos = $this->sql->cadena_sql("contarInscritos", $variablesHorario); //echo $cadena_sql_horarios;exit;
              $arreglo_numeroInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_numeroInscritos, "busqueda");
              return $arreglo_numeroInscritos;
          }else{echo 'No existe el grupo';}
        }

        /**
         * Funcion que consulta el cupo del grupo
         * @param <array> $resultadoGrupos (GRUPO,CUPO,INSCRITOS)
         * @return <int> $cupo Cupo del grupo
         */
        function rescatarCupodeGrupo($resultadoGrupos) {
        //rescatar del arreglo el cupo del grupo seleccionado y almacenarlo en la variable $cupo
          
            foreach ($resultadoGrupos as $key => $value) {
                
                if($value['ID_GRUPO']==$this->id_grupo){
                  $cupo=$value['CUPO'];
                }
              }
              return $cupo;

        }

        /**
         *Funcion que presenta el cupo y numero de inscritos del grupo
         * @param <int> $cupo Cupo del grupo
         * @param <int> $numeroInscritos Numero de estudiantes inscritos en el grupo
         */
        function mostrarCupos($cupo,$numeroInscritos) {
        if(isset ($cupo)){
          ?>
              <td class="cuadro_plano centrar"><?echo $cupo?></td>
              <td class="cuadro_plano centrar"><?echo $cupo-$numeroInscritos?></td>
          <?
        }else{echo 'no tiene cupo ni disponible';}

    }

        /**
         * Funcion que genera el encabezado de inscripciones del grupo cuando hay inscritos
         */
        function tituloEstudiantesInscritos() {
              ?>
                          <tr>
                              <caption class="sigma centrar">
                                ESTUDIANTES INSCRITOS
                              </caption>
                          </tr>
              <?
        }

        /**
         * Funcion que genera el encabezado de inscripciones del grupo cuando no hay inscritos
         */
        function tituloNoInscritos() {
              ?>
                          <tr>
                              <caption class="sigma centrar">
                                No hay estudiantes inscritos en el grupo
                              </caption>
                          </tr>
              <?
        }

        /**
         * Funcion que genera el encabezado del listado de estudiantes de acuerdo al proceso de inscripcion habilitado.
         * @param <string> $tipo Proceso habilitado para el proyecto curricular en caledario de inscripciones
         */
        function encabezadoEstudiantesInscritos($tipo) {
          ?><script language="javascript" type="text/javascript">
            function SelectAllCheckBox(chkbox,FormId){
              for (var i=0;i < document.forms[FormId].elements.length;i++)
                {
                  var Element = document.forms[FormId].elements[i];
                  if (Element.type == "checkbox")
                  Element.checked = chkbox.checked;
                }
              }
            </script>
<?
          $chek="<input type='checkbox' name='SelectedAll' onclick='SelectAllCheckBox(this,\"checBox\")'/>";
          switch ($tipo) {
            case "adicion":
                $camposEncabezado= array('Nro',$chek,'C&oacute;digo','Nombre','Proyecto', 'Estado', 'Cambiar', 'Cancelar');
              break;
            case "cancelacion":
                $camposEncabezado= array('Nro',$chek,'C&oacute;digo','Nombre','Proyecto', 'Estado', 'Cancelar');
              break;
            case "consulta":
                $camposEncabezado= array('Nro','C&oacute;digo','Nombre','Proyecto', 'Estado');
              break;
            default:
                $camposEncabezado= array('Nro','C&oacute;digo','Nombre','Proyecto', 'Estado');
              break;
          }
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
         * Funcion que consulta los estudiantes inscritos en el grupo
         * @return <array> $arreglo_estudiantesInscritos Codigos de estudiantes inscritos en el grupo
         */
        function buscarEstudiantesInscritos() {
 
              $variablesEstudiantesInscritos = array( 'codProyecto' => $this->codProyecto,
                                                      'codEspacio'=>  $this->codEspacio,
                                                      'grupo'=>  $this->id_grupo,
                                                      'ano' => $this->ano,
                                                      'periodo' => $this->periodo);
              $cadena_sql = $this->sql->cadena_sql("buscarEstudiantesInscritos", $variablesEstudiantesInscritos);
              $arreglo_estudiantesInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_estudiantesInscritos;
        }

        /*
         * Funcion que presenta el listado de los estudiantes inscritos en el grupo
         */
//
//        function mostrarEstudiantesInscritos($estudiantes) {
//
//
//          foreach ($estudiantes as $key => $value) {
//            echo '<td>'.$key.'=>'.$value['CODIGO'].'</td>';
//          }
//
//        }

        /**
         * Funcion que consulta los datos del estudiante inscrito
         * @param <int> $codigo Codigo del estudiante
         * @return <array> $arreglo_datosEstudiante (CODIGO,NOMBRE,PROYECTO,ESTADO,PLAN)
         */
        function buscarDatosEstudiante($codigo) {
              $variablesDatosEstudiante = array( 'codEstudiante'=>$codigo );
              
              $cadena_sql = $this->sql->cadena_sql("buscarDatosEstudiantes", $variablesDatosEstudiante);
              $arreglo_datosEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_datosEstudiante;
        }

        /**
         * Funcion que presenta los datos basicos del estudiante
         * @param <array> $estudiante (CODIGO,NOMBRE,PROYECTO,PROYECTOABREV,ESTADO,PLAN)
         * @param <int> $numero Numero en el listado de inscritos del grupo
         */
        function mostrarDatosEstudiante($estudiante, $numero, $proceso){          
          ?>
              <td align="center"><?echo $numero?></td>
              <?if($proceso=='adicion'||$proceso=='cancelacion')
                  {
                    if(is_array($estudiante['VERIFICACION'])){?>
                    <td align="center"><input type="checkbox" name="codEstudiante<?echo $numero?>" value="<?echo $estudiante['CODIGO']?>"></td>
                    <?}
                      else
                        {?>
                        <td align="center"></td>
                        <?}
                  }
                ?>
              <td align="center"><?echo (isset($estudiante['CODIGO'])?$estudiante['CODIGO']:'')?></td>
              <td><?echo htmlentities(isset($estudiante['NOMBRE'])?$estudiante['NOMBRE']:'')?></td>
              <td align="center"><?echo (isset($estudiante['PROYECTOABREV'])?$estudiante['PROYECTOABREV']:'')?></td>
              <td align="center"><?echo (isset($estudiante['ESTADO'])?$estudiante['ESTADO']:'')?></td>
          <?
        }

        /**
         * Funcion que presenta los enlaces de cambio de grupo y cancelacion de acuerdo a las fechas habilitadas.
         * @param <array> $datosEstudiante (CODIGO,NOMBRE,PROYECTO,ESTADO,PLAN)
         * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS,HTD,HTC,HTA,ELECTIVO)
         * @param <string> $tipo Proceso habilitado en inscripciones para el proyecto
         */

        function mostrarEnlaces($datosEstudiante,$datosEspacio,$tipo){
          switch ($tipo) {
            case "adicion":
                $enlace= array('enlaceCambiar'=>'reload', 'enlaceCancelar'=>'x');
                $col=2;
              break;
            case "cancelacion":
                $enlace= array(enlaceCancelar=>'x');
                $col=1;
              break;
            case "consulta":
                $enlace= array();
                $col=0;
              break;
            default:
                $enlace= array();
                $col=0;
              break;
            }
            if(is_array($datosEstudiante['VERIFICACION'])){
          foreach ($enlace as $key => $value) {
            ?><td align="center"><a href="<? echo $this->$key($datosEstudiante, $datosEspacio) ?>" ><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/".$value.".png" ?>" border="0" width="20" height="20"></a></td><?
              }
            }
            else
              {
              if ($col>0){
              ?><td align="center" colspan="<?echo $col;?>">No pertenece al proyecto</td><?
              }else
              {}

              }
        }

        /**
         * Esta funcion consulta en el calendario el proceso de inscripcion habilitado para el proyecto.
         * @return <string> $proceso Proceso de inscripcion habilitado para el proyecto
         */

        function procesoHabilitado() {
        $proceso=$this->fechas->validar_fechas_grupo_coordinador($this->configuracion, $this->codProyecto);
        return $proceso;
        }

        /**
         * Funcion que permite crear enlaces para cambiar de grupo a cada estudiante
         * @param <array> $datosEstudiante (CODIGO,NOMBRE,PROYECTO,ESTADO,PLAN)
         * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS,HTD,HTC,HTA,ELECTIVO)
         * @param <array> $pagina Pagina a la cual direcciona
         * @param <array> $variable Parametros para la pagina de enlace
         */

        function enlaceCambiar($datosEstudiante,$datosEspacio) {
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable="pagina=admin_buscarGruposProyectoHoras";
          $variable.="&opcion=cambiarGrupo";
          $variable.="&codProyecto=".$this->codProyecto;
          $variable.="&planEstudio=".$this->planEstudio;
          $variable.="&codEstudiante=".$datosEstudiante['CODIGO'];
          $variable.="&codProyectoEstudiante=".$datosEstudiante['PROYECTO'];
          $variable.="&planEstudioEstudiante=".$datosEstudiante['PLAN'];
          $variable.="&estado_est=".$datosEstudiante['ESTADO'];
          $variable.="&creditos=".$datosEspacio['CREDITOS'];
          $variable.="&codEspacio=".$this->codEspacio;
          $variable.="&nombreEspacio=".$datosEspacio['NOMBRE'];
          $variable.="&id_grupo=".$this->id_grupo;
          $variable.="&grupo=".$this->grupo;
          $variable.="&parametro==";
          $variable.="&destino=inscripcion/registro_cambiarGrupoEstudianteCoorHoras";
          $variable.="&retorno=admin_estudiantesInscritosGrupoCoorHoras";
          $variable.="&opcionRetorno=verGrupo";

          include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);

          return $pagina.$variable;
        }

        /*
         * Funcion que permite crear enlaces para cancelar el espacio academico a cada estudiante
         * @param <array> $datosEstudiante (CODIGO,NOMBRE,PROYECTO,ESTADO,PLAN)
         * @param <array> $datosEspacio (CODIGO,NOMBRE,CREDITOS,HTD,HTC,HTA,ELECTIVO)
         * @param <array> $pagina Pagina a la cual direcciona
         * @param <array> $variable Parametros para la pagina de enlace
         */
        function enlaceCancelar($datosEstudiante,$datosEspacio) {
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable="pagina=registro_cancelarInscripcionEstudianteCoorHoras";
          $variable.="&opcion=verificarCancelacion";
          $variable.="&codProyecto=".$this->codProyecto;
          $variable.="&planEstudio=".$this->planEstudio;
          $variable.="&codEstudiante=".$datosEstudiante['CODIGO'];
          $variable.="&codProyectoEstudiante=".$datosEstudiante['PROYECTO'];
          $variable.="&planEstudioEstudiante=".$datosEstudiante['PLAN'];
          $variable.="&estado_est=".$datosEstudiante['ESTADO'];
          $variable.="&creditos=".$datosEspacio['CREDITOS'];
          $variable.="&codEspacio=".$this->codEspacio;
          $variable.="&nombreEspacio=".$datosEspacio['NOMBRE'];
          $variable.="&id_grupo=".$this->id_grupo;
          $variable.="&grupo=".$this->grupo;
          //$variable.="&parametro==";
          //$variable.="&destino=registro_cambiarGrupoEstudianteCoorHoras";
          $variable.="&retorno=admin_estudiantesInscritosGrupoCoorHoras";
          $variable.="&opcionRetorno=verGrupo";

          include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);

          return $pagina.$variable;
        }

        /**
         * Funcion que presenta enlaces para cambio de grupo y cancelacion
         * @param <string> $parametro
         * @param <array> $datos (CODIGO,NOMBRE,CREDITOS,HTD,HTC,HTA,ELECTIVO)
         */
        function enlaceCambiarGrupo($parametro,$datos) {
          if($parametro=='adicion'||$parametro=='cancelacion'){
              ?><table>
                  <tr>
                    <td>Para los estudiantes seleccionados...</td>
                    <td><input type="hidden" name="opcion" value="procesar">
                      <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                      <input type="hidden" name="codProyecto" value="<? echo $this->codProyecto ?>">
                      <input type="hidden" name="planEstudio" value="<? echo $this->planEstudio ?>">
                      <input type="hidden" name="codEspacio" value="<? echo $this->codEspacio ?>">
                      <input type="hidden" name="nombreEspacio" value="<? echo $datos[0]['NOMBRE'] ?>">
                      <input type="hidden" name="id_grupo" value="<? echo $this->id_grupo ?>">
                      <input type="hidden" name="grupo" value="<? echo $this->grupo ?>">
                      <input type="hidden" name="parametro" value="=">
                      <input type="hidden" name="destino" value="inscripcion/registro_cambiarGrupoEstudianteGrupoCoorHoras">
                      <input type="hidden" name="retorno" value="admin_estudiantesInscritosGrupoCoorHoras">
                      <input type="hidden" name="opcionRetorno" value="verGrupo">
                      <?if($parametro=='adicion'){?>
                      <input type="image" name="cambiar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/reload.png" width="20" height="20">
                    </td>
                    <td width="100">Cambiar
                    <?}?>
                    </td>
                    <td>
                      <input type="image" name="cancelar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" width="20" height="20">
                    </td>
                    <td>Cancelar</td>
                  </tr>
                </table><?
                
                }
        }

}
?>
