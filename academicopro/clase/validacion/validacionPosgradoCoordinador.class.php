<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inscripcionGrupoPosgrado
 *
 * @author Luis Fernando Torres
 */
include($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/validacion/validaciones.class.php");



class inscripcionPosgradoGrupoCoordinador {

  public $datosInscripcion;
  public $validaciones;
  public $inscripcionEstudiante;

  function __constuct() {  

  }

    /**
   * Funcion que llama los metodos para inscripcion por grupos en posgrados.
   *
   * Las validaciones que se deben realizar se encuentran relacionanen el arreglo $this->validaciones.
   * cada validación es ejecutada por la clase validarInscripcion (archivo validaciones) que retorna un valor Falso o verdadero
   *
   * @param <array> $datosInscripcion {codEstudiante, codProyectoEstudiante, planEstudioEstudiante, codEspacio, grupo}
   */
    function validarIncripcionGrupo($datosInscripcion) {

        $this->inscripcionEstudiante=new validarInscripcion();
        $validacion=$this->crearArregloEliminarValidaciones($datosInscripcion);
        $this->crearArregloValidaciones();
        $this->eliminarValidaciones($validacion);
        $resultadoValidacion=$this->realizarValidacion($this->validaciones, $datosInscripcion);
        if($resultadoValidacion=='ok')
          {
            return 'ok';
          }
        else
          {
          $formato_resultadoValidacion=  $this->formatearResultadoValidacion($resultadoValidacion);
          return $formato_resultadoValidacion;
          }
      }

    /**
     * Funcion donde se deben registra las validaciones que se van a realizar
     */
    function crearArregloValidaciones(){

          $this->validaciones=array(  //'validarCodigoEstudiante',
                                      'validarEstadoEstudiante',
                                      'validarEspacioInscrito',
                                      'validarAprobado',
                                      'validarCruceHorario',
                                      'validarEspacioPlan',
                                      'validarRequisitos',
                                    );
        }

    /**
     * Funcion para deterinar validaciones a eliminar
     * @param <array> $datosInscripcion
     * @return array
     */
    function crearArregloEliminarValidaciones($datosInscripcion) {

        $validacion=array();
        //validacion espacioPlan
        if(!isset ($_REQUEST['validarEspacioPlan']))
        {
          $validacion[0]='validarEspacioPlan';
        }
        return $validacion;
    }
        
    /**
     * Funcion para eliminar validaciones del areglo original
     * @param <array> $validaciones
     */
    function eliminarValidaciones($validacion) {
        foreach ($validacion as $key => $value)
          {
          $apuntador=array_search($value, $this->validaciones);
          if($apuntador!==false){
            unset ($this->validaciones[$apuntador]);}
          }
          $arreglo=$this->validaciones;
          unset ($this->validaciones);
          foreach ($arreglo as $value)
            {
                $this->validaciones[]=$value;
            }
    }

        
    /**
 *
 * @param <type> $validacion
 * @param <type> $datosInscripcion
 * @return <type>
 */
    function realizarValidacion($validacion, $datosInscripcion) {

          for ($a=0;$a<count($validacion);$a++){

          unset ($resultadoValidacion);
          $resultadoValidacion[$a]=$this->inscripcionEstudiante->$validacion[$a]($datosInscripcion);
          if ($resultadoValidacion[$a]=='ok'){
          }
          else
          {
            $resultadoValidacion[$a][0]['validacion']=$validacion[$a];

            return ($resultadoValidacion[$a][0]);
          }

        }

        return 'ok';
        }

        /**
         *
         * @param <type> $resultado
         */
        function formatearResultadoValidacion($resultado) {

          switch ($resultado['validacion']) {

            case 'validarCodigoEstudiante':

              return 'Estudiante Inactivo';
            break;

            case 'validarEstadoEstudiante':

              return 'Codigo no valido';
            break;

            case 'validarEspacioInscrito':

              return 'El espacio se encuentra inscrito en el grupo: '.$resultado['GRUPO'];
            break;

            case 'validarAprobado':

              return 'El espacio ya ha sido aprobado';
            break;

            case 'validarCruceHorario':

              return 'Presenta cruce de horario';
            break;

            case 'validarEspacioPlan':

              return 'No pertenece al plan de estudios del estudiante';
            break;

            case 'validarRequisitos':

              return 'No ha cursado requisitos';
            break;

            default:
              return 'No existe mesaje para esta validacion';
        break;
          }

        } 
      
}
?>
