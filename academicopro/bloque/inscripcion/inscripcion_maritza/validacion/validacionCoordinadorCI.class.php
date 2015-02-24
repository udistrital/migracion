<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inscripcionGrupoPosgrado
 *
 * @author Luis Fernando Torres
 * @version 0.0.0.02 Milton Parra
 */
include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/validacion/validaciones_vacacionales.class.php");



class inscripcionCIGrupoCoordinador {

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
        $this->inscripcionEstudiante=new validarInscripcionVacacional();
        $validacion=$this->crearArregloEliminarValidaciones($datosInscripcion);
        switch (trim($datosInscripcion['modalidad']))
        {
        case 'N':
          $this->crearArregloValidacionesHoras();
          break;
        case 'S':
          $this->crearArregloValidacionesCreditos();
          break;
        default :
          $this->crearArregloValidacionesCreditos();
          break;
        }
        $this->eliminarValidaciones($validacion);
        $resultadoValidacion=$this->realizarValidacion($this->validaciones, $datosInscripcion);
        if($resultadoValidacion=='ok')
          {
            return 'ok';
          }
          elseif($resultadoValidacion=='4')
          {
              return '4';//si el espacio es ofertado como extrinseco
          }
        else
          {
          $formato_resultadoValidacion=  $this->formatearResultadoValidacion($resultadoValidacion);
          return $formato_resultadoValidacion;
          }
      }

    /**
     * Funcion donde se deben registra las validaciones que se van a realizar a estudiantes de horas
     */
    function crearArregloValidacionesHoras(){

          $this->validaciones=array(  //'validarCodigoEstudiante',
                                      'validarEstadoEstudiante',
                                      'validarReprobado',
                                      'validarEspacioInscrito',
                                      'validarAprobado',
                                      'validarMaxCantidadEspacios',
                                      'validarMaxCantidadHorasDiarias',
                                      'validarCruceHorario',
                                      'validarEspacioPlan',
                                      'validarRequisitos',
                                    );
        }
        
    /**
     * Funcion donde se deben registra las validaciones que se van a realizar a estudiantes de creditos
     */
    function crearArregloValidacionesCreditos(){

          $this->validaciones=array(  
                                      'validarEstadoEstudiante',
                                      'validarReprobado',
                                      'validarEspacioInscrito',
                                      'validarAprobado',
                                      'validarMaxCantidadEspacios',
                                      'validarMaxCantidadHorasDiarias',
                                      'validarCruceHorario',
                                      'validarCreditosInscritos',
                                      'validarEspacioPlanCreditos',
                                      'validarRequisitosCreditos',
                                    );
        }

    /**
     * Funcion para deterinar validaciones a eliminar
     * @param <array> $datosInscripcion
     * @return array
     */
    function crearArregloEliminarValidaciones($datosInscripcion) {

        $validacion=array();
        //prueba academica
        if (trim($datosInscripcion['estado_est'])!='B'&&trim($datosInscripcion['estado_est'])!='J')
        {
          $validacion[0]='validarReprobado';
        }
        //validacion espacioPlan
        if(!isset ($_REQUEST['validarEspacioPlan'])&&trim($datosInscripcion['modalidad'])=='N')
        {
          $validacion[1]='validarEspacioPlan';
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
    }

    /**
 *
 * @param <array> $validacion
 * @param <array> $datosInscripcion
 * @return string/array
 */
    function realizarValidacion($validacion, $datosInscripcion) {
          foreach ($validacion as $a => $value) {
                unset ($resultadoValidacion);
                $resultadoValidacion=array();
                $resultadoValidacion[$a]=$this->inscripcionEstudiante->$validacion[$a]($datosInscripcion);
                if ($resultadoValidacion[$a]=='ok'){

                }
                elseif($resultadoValidacion[$a]=='4')
                {
                    return '4';//si el espacio es ofertado como extrinseco
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

            case 'validarReprobado':

              return 'Estudiante en Prueba Acad&eacute;mica';
            break;

            case 'validarEstadoEstudiante':

              return 'Codigo no valido';
            break;

            case 'validarEspacioInscrito':

              return 'El espacio se encuentra inscrito en el grupo: '.(isset($resultado['GRUPO'])?$resultado['GRUPO']:'');
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

            case 'validarCreditosInscritos':

              return 'Supera '.$resultado["MAX_PERIODO"].' Cr&eacute;ditos para el per&iacute;odo';
            break;

            case 'validarCreditosPorClasificacion':

              return 'Supera el n&uacute;mero de Cr&eacute;ditos '.$resultado["creditos"].' del plan de estudios';
            break;

            case 'validarEspacioPlanCreditos':

              return 'No pertenece al plan de estudios del estudiante';
            break;

            case 'validarRequisitosCreditos':
              return 'No ha cursado requisitos del espacio a inscribir';
            break;

            case 'validarCancelado':
              return 'El espacio ha sido cancelado';
            break;

            case 'validarMaxCantidadEspacios':
                return 'Supera espacios máximos permitidos';
                break;
            case 'validarMaxCantidadHorasDiarias':
                return 'Supera horas máximas permitidas por día';
                break;
            
            default:
              return 'No existe mesaje para esta validacion';
        break;
          }

        } 
      
}
?>
