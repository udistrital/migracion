
        <script language="javascript">
            var numero = 0;
            var cadenaVerificar;

            function verificarFormulario(formulario)
            {
            if( control_vacio(formulario,'codEstudiante[0]')&&
                verificar_numero(formulario,'codEstudiante[0]'))
              {
                for(b=1;b<numero+1;b++)
                    {
                    if(verificar_numero(formulario,'codEstudiante['+b+']')&&
                      control_vacio(formulario,'codEstudiante['+b+']'))
                      {
                      }
                    else
                      {
                        return false;
                      }
                  }             
              }
              else
              {
                return false;
              }
              return true;
            }
            function nuevaFila(numeroFilas)
            {
                for(a=0;a<numeroFilas;a++){
                // obtenemos acceso a la tabla por su ID
                var table = document.getElementById("tabla");
                // obtenemos acceso a la fila maestra por su ID
                var trow = document.getElementById("fila");
                // tomamos la celda
                var content = trow.getElementsByTagName("td");
                // creamos una nueva fila
                var newRow = table.insertRow(-1);
                newRow.className = trow.attributes['class'].value;
                // creamos una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                // creamos una nueva ID para el examinador
                newID = 'codEstudiante' + (++numero);
                newNombre = 'codEstudiante[' + (numero)+']';
                txt = table.rows.length-1+' '+'<input type="text" id="'+newID+'" name="'+newNombre+'" size="11" " onchange="xajax_nombreEstudiante(document.getElementById(\''+newID+'\').value,'+numero+')"/>'
                newCell.innerHTML = txt
                //tomar la celda
                var contenidoNombre=trow.getElementsByTagName("td");
                //crea una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division
                division= '<div id="div_nombreEstudiante'+numero+'" class="cuadro_plano"><&minus;&minus; Ingrese c&oacute;digo de estudiante</div>'
                //se asigna la division a la celda
                newCell.innerHTML = division
                }
            }

            function removeLastRow()
            {
                // obtenemos la tabla
                var table = document.getElementById("tabla");

                // si tenemos mas de una fila, borramos
                while(table.rows.length > 2)
                {
                    table.deleteRow(table.rows.length-1);
                    --numero;
                }
            }

            function removeLastestRow()
            {
                // obtenemos la tabla
                var table = document.getElementById("tabla");

                // si tenemos mas de una fila, borramos
                if(table.rows.length > 2)
                {
                    table.deleteRow(table.rows.length-1);
                    --numero;
                }
            }


        </script>
<?php


/**
 * Funcion adminInscripcionGrupoCoordinadorCI
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupoCI
 * @subpackage Admin
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 29/05/2013
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
 * Clase funcion_adminInscripcionGrupoCoordinadorCI
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class funcion_registroAdicionEstudiantesGrupoCoorCI extends funcionGeneral {

  public $configuracion;
  public $ano;
  public $periodo;
  Public $codProyecto;//proyecto curricular del coordinador
  public $planEstudio;//plan de estudio del coordinador
  public $codEspacio;//
  public $idGrupo;
  public $accesoOracle;

  /**
     * Método constructor que crea el objeto sql de la clase funcion_adminInscripcionGrupoCoordinadorCI
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
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas_vacacionales.class.php");
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/procedimientosCI.class.php");
        $this->formulario = "registro_adicionEstudiantesGrupoCoorCI";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "inscripcionCI/registro_adicionEstudiantesGrupoCoorCI";//nombre del BLOQUE que procesa el formulario
        $this->planEstudio=$_REQUEST['planEstudio'];
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->codEspacio=$_REQUEST['codEspacio'];
        $this->grupo=$_REQUEST['idGrupo'];

        $this->cripto = new encriptar();
        $this->sql = new sql_registroAdicionEstudiantesGrupoCoorCI($configuracion);
        $this->fechas=new validar_fechas_vacacionales();
        $this->procedimientos=new procedimientosCI();

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
            if($this->nivel==4){
                $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
            }elseif($this->nivel==110){
                $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
            }elseif($this->nivel==114){
                $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
            }else{
                echo "EL PERFIL NO TIENE PERMISOS PARA ESTE MODULO";
            }

            $this->verificar="verificar_numero(".  $this->formulario.",'codEstudiante[0]')";
        $this->verificar.="&&control_vacio(".  $this->formulario.",'codEstudiante[0]')";
        //Buscar año y periodo activos
      $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
      $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      $this->ano=$resultado_peridoActivo[0]['ANO'];
      $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

    }

    /**
     *
     */
    function validarFechas(){

      $registro_permisos=$this->fechas->validar_fechas_CI_grupo_coordinador($this->configuracion,  $this->codProyecto);
      if($registro_permisos=='adicion'){
        $this->mostrarFormularioAdicion();
      }else{
        //no presenta nada
        }                 
    }

    /**
     *
     */
    function mostrarFormularioAdicion() {
      ?>
    <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>

          <table id="tabla" class="sigma" width="100%">
          <caption class="sigma centrar">
            INSCRIBIR ESTUDIANTES EN EL GRUPO
          </caption><br>
            <?//opciones para agregar 5,10,15...50 estudiantes?>
            <tr id="fila" class="sigma derecha">
              <td class="sigma centrar" colspan="5" width="100%">Seleccione el n&uacute;mero de estudiantes:
                <select id="filas" class="boton" name="filas" onchange="removeLastRow(),nuevaFila(document.getElementById('filas').value-1)">
                  <?$opciones=1?>
                  <option selected class="boton" value="1" onClick="removeLastRow(),nuevaFila(0)">1</option>
                  <?for($opciones=5;$opciones<=50;$opciones+=5){?>
                  <option id="<?echo $opciones-1?>" class="boton" value="<?echo $opciones?>"><?echo $opciones?></option>
                  <?}?>
                </select>&nbsp;&nbsp;
                <?//opciones para agregar 1 estudiante y opcion para borrar todas las filas?>
                <input type="button" class="boton" value="Adicionar filas" onClick="nuevaFila(1)" alt="Adicionar">
                <input type="button" class="boton" value="Reiniciar filas" onClick="removeLastRow()" alt="Remover">
                <input type="button" class="boton" value="Borrar filas" onClick="removeLastestRow()" alt="Remover">
              </td>
            </tr>
            <tr class="sigma derecha">
              <td width="18%">
                1 <input type="text" id="codEstudiante0" name="codEstudiante[0]" size="11" onchange="xajax_nombreEstudiante(document.getElementById('codEstudiante0').value,0)">
                <input type="hidden" name="opcion" value="registrarEstudiante">
                <input type="hidden" name="action" value="<? echo $this->bloque;?>">
                <input type="hidden" name="codProyecto" value="<? echo $_REQUEST['codProyecto'] ?>">
                <input type="hidden" name="planEstudio" value="<? echo $_REQUEST['planEstudio'] ?>">
                <input type="hidden" name="codEspacio" value="<? echo $_REQUEST['codEspacio'] ?>">
                <input type="hidden" name="idGrupo" value="<? echo $_REQUEST['idGrupo'] ?>">
              </td>
              <td colspan="2">
                <div id="div_nombreEstudiante0" class="cuadro_plano"><&minus;&minus; Ingrese c&oacute;digo de estudiante</div>
              </td>
            </tr>
        </table>
        <table width="100%">
          <tr class="cuadro_plano centrar">
            <td><input type="checkbox" name="validarEspacioPlan" value="1" checked><b>
                Verificar si el espacio acad&eacute;mico corresponde al plan de estudios del estudiante</b> (Opcional para estudiantes de Horas)
            </td>
          </tr>
          <tr>
            <td align="center">
              <input class="boton" type="button" value="Registrar" onclick="if(verificarFormulario(<?echo $this->formulario?>)){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
            </td>
          </tr>
        </table>
     </form>
<?


    }

    /**
     *
     */
    function adicionarEstudiantes() {
    $datos_EspacioAcademico=$this->buscarDatosEspacio();

    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/validacion/validacionCoordinadorCI.class.php");
    $a=0;
    //var_dump($_REQUEST['codEstudiante']);exit;
    foreach ($_REQUEST['codEstudiante'] as $key => $codigoEstudiante) {
            $this->resultadoDatosEstudiante=$this->buscarDatosEstudiante($codigoEstudiante);
            $datosInscripcion=$this->crearArregloDatosInscripcion($this->resultadoDatosEstudiante[0],$datos_EspacioAcademico);
            $this->validacionGrupo=new inscripcionCIGrupoCoordinador();
            $resultado_validacion=$this->validacionGrupo->validarIncripcionGrupo($datosInscripcion);
            if($resultado_validacion=='ok')
                { 
                    $this->realizarInscripcion($datosInscripcion);
                }elseif($resultado_validacion=='4')
                    {
                        //si el espacio es ofertado como extrinseco
                        $datosInscripcion['cea']=4;
                        $resultado_validacion='ok';
                        $this->realizarInscripcion($datosInscripcion);
                    }
                    else{
                        }
            //arreglo necesario para presentar el informe de la inscripcion
            $arregloInforme[$a]['codigo']=$this->resultadoDatosEstudiante[0]['CODIGO'];
            $arregloInforme[$a]['nombre']=(isset($this->resultadoDatosEstudiante[0]['NOMBRE'])?$this->resultadoDatosEstudiante[0]['NOMBRE']:'');
            $arregloInforme[$a]['motivo']=$resultado_validacion;
            $a++;
            unset ($datosInscripcion);

      }
      //var_dump($arregloInforme);
      $this->mostrarReporte($arregloInforme);
      
    }

    /**
     *
     */
    function validarCodigosIngresados($arregloCodigo) {
          function casa($value) {
            return !empty($value);
          }
     //elimina los valores vacios
     $arregloCodigoNoVacios=array_filter($arregloCodigo, 'casa');
     return $arregloCodigoNoVacios;
}

    /**
     *
     */
    function buscarDatosEspacio() {
          $variablesEspacios = array('codProyecto' => $this->codProyecto,
                                     'planEstudio' => $this->planEstudio,
                                     'codEspacio' => $this->codEspacio);
          $cadena_sql = $this->sql->cadena_sql("buscarEspaciosAcademicos", $variablesEspacios);//echo $cadena_sql;exit;
          $arreglo_espacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_espacio;
  }

    /**
       *
       */
    function buscarDatosEstudiante($codigo) {
          $variablesDatosEstudiante = array('codEstudiante'=>$codigo );
          $cadena_sql = $this->sql->cadena_sql("buscarDatosEstudiantes", $variablesDatosEstudiante);//echo $cadena_sql;exit;
          $arreglo_datosEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          if(is_array($arreglo_datosEstudiante))
            {
              return $arreglo_datosEstudiante;
            }
            else
              {
                $resultado[0]=array('CODIGO'=>$codigo);
                return $resultado;
              }
          
    }

    /**
     * busca si el código del estudainte existe en la base de datos
     */
    function buscarCodigoEstudiante($codigo) {
          $variablesDatosEstudiante = array('codEstudiante'=>$codigo );
          $cadena_sql = $this->sql->cadena_sql("buscarCodigoEstudiante", $codigo);//echo $cadena_sql;exit;
          $arreglo_datosEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $codigo;
    }

    /**
     * Crear arreglo con datos inscripcion
     *
     * Crea un arreglo con los datos de la inscripcion
     * @param <array> $datosEstudiante {CODIGO, PROYECTO, PLANESTUDIOS} el PROYECTO y PLANESTUDIOS son del estudiante
     */
    function crearArregloDatosInscripcion($datosEstudiante,$datosEspacio){
        $arregloDatosInscripcion=array(   'codEstudiante'=>$datosEstudiante['CODIGO'],
                                          'codProyectoEstudiante'=>  (isset($datosEstudiante['PROYECTO'])?$datosEstudiante['PROYECTO']:''),
                                          'codProyecto'=> $this->codProyecto,
                                          'planEstudio'=> $this->planEstudio,
                                          'planEstudioEstudiante'=>  (isset($datosEstudiante['PLANESTUDIOS'])?$datosEstudiante['PLANESTUDIOS']:''),
                                          'modalidad'=>  (isset($datosEstudiante['MODALIDAD'])?$datosEstudiante['MODALIDAD']:''),
                                          'estado_est'=>  (isset($datosEstudiante['ESTADO'])?$datosEstudiante['ESTADO']:''),
                                          'codEspacio'=>  $this->codEspacio,
                                          'creditos'=> $datosEspacio[0]['CREDITOS'],
                                          'htd'=> $datosEspacio[0]['HTD'],
                                          'htc'=> $datosEspacio[0]['HTC'],
                                          'hta'=> $datosEspacio[0]['HTA'],
                                          'cea'=> (isset($datosEspacio[0]['CEA'])?$datosEspacio[0]['CEA']:''),
                                          'idGrupo'=>  $this->grupo,
                                          'ano'=>  $this->ano,
                                          'periodo'=>  $this->periodo,
                                          'usuario'=>  $this->usuario,
                                          'evento'=>1,
                                          'descripcion'=>'Adiciona Espacio Académico CI',
                                          'registro'=>$this->ano.'-'.$this->periodo.','.$this->codEspacio.',0,'.$this->grupo.','.(isset($datosEstudiante['PLANESTUDIOS'])?$datosEstudiante['PLANESTUDIOS']:'').','.$this->codProyecto,
                                          'afectado'=>$datosEstudiante['CODIGO']
                                      );
        return $arregloDatosInscripcion;
    }

    /**
     *
     * @param <type> $datosinscripción
     * @return <type> 
     */
    function realizarInscripcion($datosinscripción) {
      include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/registrarInscripcionCI.class.php");
      $this->registrarEstudiante=new registrarInscripcionCI($this->usuario,$this->accesoOracle);
      $resultado_registro=$this->registrarEstudiante->inscribirEstudiante($datosinscripción);
      return $resultado_registro;
}

    /**
     *  Funcion que presenta el reporte de cambio de grupo
     * @param <array> $reporte (codEstudiante=>reporte)
     */
    function mostrarReporte($reporte) {
      $i=1;
        ?>
        <hr>
        <?
        $this->encabezadoReporte();
        $this->reporteNoExito($reporte,$i);
        $this->reporteExito($reporte,$i);
        ?>
        <hr>
        <?
    }

    /**
     * funcion que genera el encabezado para el reporte
     */
    function encabezadoReporte() {
      ?>
      <table width="100%">
        <tr>
          <caption class="sigma centrar">
            REPORTE DE INSCRIPCION
          </caption>
        </tr>
      </table>
      <?
    }

    /**
     * Funcion que genera la tabla de reporte para los cambios hechos
     * @param <array> $casosExito (codEstudiante=>reporte)
     * @param <int> $num
     */
    function reporteExito($casosExito,$num) {
      ?><table class="sigma contenidotabla"><? 
        $this->encabezadoExito($casosExito);
        $this->reporteEstudiantesExito($casosExito,$num);
      ?></table><?
    }

    /**
     * Funcion que genera la tabla de reporte de los casos que no se realizó cambio
     * @param <array> $casosNoExito (codEstudiante=>reporte)
     * @param <int> $num
     */
    function reporteNoExito ($casosNoExito,$num) {
      ?><table class="sigma contenidotabla"><? 
        $this->encabezadoNoExito($casosNoExito);
        $this->reporteEstudiantesNoExito($casosNoExito,$num);
      ?></table><?
    }

    /**
     * Funcion que genera el encabezado de la tabla de cambios realizados
     * @param <array> $reporte (codEstudiante=>reporte)
     */
    function encabezadoExito($reporte) {

      foreach ($reporte as $key => $value)
        {if($reporte[$key]['motivo']=='ok')
          {
            ?>
          <tr align="center"><td colspan="7" class='cuadro_plano centrar'><b>Inscripciones exitosas</b></td></tr>
            <tr class="cuadro_color">
              <td align="center">Nro</td>
              <td align="center">C&oacute;digo</td>
              <td colspan="2" align="center">Nombre</td>
              <td align="center">Descripci&oacute;n</td>
            </tr>
            <?
          break;
          }
        }
    }

    /**
     * Funcion que presenta cada caso de cambio exitoso
     * @param <array> $reporte (codEstudiante=>reporte)
     * @param <int> $i
     */
    function reporteEstudiantesExito($reporte,$i) {

      foreach ($reporte as $key => $value) {
        if($reporte[$key]['motivo']=='ok')
        {?>
        <tr><td align="center"><?echo $i?></td>
          <td align="center"><?echo $reporte[$key]['codigo']?></td>
          <td colspan="2"><?echo $reporte[$key]['nombre']?></td>
          <td align="center"><?echo $reporte[$key]['motivo']?></td>
        </tr>
        <?
        $i++;
        }
      }
    }

    /**
     * Funcion que genera el encabezado de la tabla de casos no realizados
     * @param <array> $reporte (codEstudiante=>reporte)
     */
    function encabezadoNoExito($reporte) {
      foreach ($reporte as $key => $value)
        {if($reporte[$key]['motivo']!='ok')
          {
            ?>
        <tr align="center"><td colspan="7" class='cuadro_plano centrar'><font color="#F90101">Para los siguientes estudiantes <b>NO</b> se realiz&oacute; la inscripcion</font></td></tr>
            <tr class="cuadro_color">
              <td align="center">Nro</td>
              <td align="center">C&oacute;digo</td>
              <td align="center">Nombre</td>
              <td colspan="2" align="center">Descripci&oacute;n</td>
            </tr>
            <?
          break;
          }
        }
    }

    /**
     * Funcion que presenta cada caso no realizado
     * @param <array> $reporte (codEstudiante=>reporte)
     * @param <int> $i
     */
    function reporteEstudiantesNoExito($reporte,$i) {

      foreach ($reporte as $key => $value) {

        if($reporte[$key]['motivo']!='ok')
          {?>
          <tr><td align="center"><?echo $i?></td>
            <td align="center"><?echo $reporte[$key]['codigo']?></td>
            <td><?echo $reporte[$key]['nombre']?></td>
            <td colspan="2" align="center"><?echo $reporte[$key]['motivo']?></td>
          </tr>
          <?
          $i++;
          }
      }
    }

    }
    ?>