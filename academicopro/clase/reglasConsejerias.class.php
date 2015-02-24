<?php
/* 
 * Permite ver las reglas de la consejeria
 * y retorna un valor de riesgo que puede tener el estudiante.
 * @author Edwin Sanchez
 */
class reglasConsejerias {

    /*Esta funcion retorna el valor de riesgo de un estudiante alto, medio, bajo
     *
     * @parametros $datosEstudiante array
     * $datosEstudiante[0][0] = codigo del estudiante
     * $datosEstudiante[0][1] = año
     * $datosEstudiante[0][2] = periodo
     * $datosEstudiante[0][3] = estado del estudiante
     * $datosEstudiante[0][4] = codigo del motivo de prueba (Si esta en prueba)
     * $datosEstudiante[0][5] = descripcion del motivo
     * $datosEstudiante[0][6] = Numero de semestres cursados por el estudiante
     * $datosEstudiante[0][7] = Promedio Acumulado
     * $datosEstudiante[0][8] = Promedio Ponderado
     * $datosEstudiante[0][9] = Numero de materias perdidas
     * $datosEstudiante[0][10] = Total de materias perdidas durante la carrera
     * $datosEstudiante[0][11] = Numero de veces que el estudiante ha estado en prueba academica
     *
     * @return $riesgo
     * $riesgo='1'; -- Riesgo Super Alto
     * $riesgo='2'; -- Riesgo Alto
     * $riesgo='3'; -- Riesgo Medio
     * $riesgo='4'; -- Riesgo Bajo
     * $riesgo='5'; -- Riesgo Ninguno
     */
    public function calcularRiesgo($datosEstudiante)
    {
        if(isset($datosEstudiante['MOTIVO_PRUEBA']))
        {
        switch ($datosEstudiante['MOTIVO_PRUEBA']) {
            case NULL:
                //return 'Sin Prueba';
                return '1;No definido para este semestre';
                break;
            
            case 0:
                //return 'Sin Prueba';
                return '5;Sin prueba';
                break;

            case 3:
                //return 'Asignatura reprobada 2 veces';
                return '3;Asignatura reprobada 2 veces';
                break;

            case 20:
                //return 'M&aacute;s de dos asignaturas reprobadas';
                return '4;Mas de dos asignaturas reprobadas';
                break;
       
            case 23:
                //return 'M&aacute;s de dos asignaturas reprobadas y Asignatura reprobada 2 veces';
                return '2;Mas de dos asignaturas reprobadas y Asignatura reprobada 2 veces';
                break;

            case 100:
                //return 'Promedio';
                return '4;Promedio';
                break;

            case 103:
                //return 'Promedio y Asignatura reprobada 2 veces';
                return '2;Promedio y Asignatura reprobada 2 veces';
                break;

            case 120:
                //return 'Promedio y M&aacute;s de dos asignaturas reprobadas';
                return '2;Promedio y Mas de dos asignaturas reprobadas';
                break;
  
            case 123:
                //return 'Promedio, M&aacute;s de dos asignaturas reprobadas y Asignatura reprobada 2 veces';
                return '1;Promedio, mas de dos asignaturas reprobadas y Asignatura reprobada 2 veces';
                break;

            default:
                return '<b>RIESGO NO DEFINIDO:</b> '.$datosEstudiante['MOTIVO_PRUEBA'];
                break;
        }
        }
        
        /**
        if($datosEstudiante[0][3]=='B' || $datosEstudiante[0][3]=='J')
            {
                if($datosEstudiante[0][11]>'1' && ($datosEstudiante[0][4]=='123'|| $datosEstudiante[0][4]=='23'))
                    {
                        $riesgo='1';
                        $mensaje=$datosEstudiante[0][5];
                    }else
                        {
                            $riesgo='2';
                            $mensaje=$datosEstudiante[0][5];
                        }
            }
            else if(($datosEstudiante[0][7]>='3.0' && $datosEstudiante[0][7]<='3.2'))
                {
                    if($datosEstudiante[0][9]>'1')
                        {
                            $riesgo='2';
                            $mensaje="PROMEDIO ACUMULADO MUY BAJO Y MAS DE UNA MATERIA PERDIDA";
                        }else
                            {
                                $riesgo='3';
                                $mensaje="PROMEDIO ACUMULADO MUY BAJO";
                            }                    
                }else if($datosEstudiante[0][10]>='1')
                    {
                        $riesgo='4';
                        $mensaje="PERDIO MAS DE 1 MATERIA DURANTE SU CARRERA";
                    }else if($datosEstudiante[0][10]=='0')
                        {
                            $riesgo='5';
                            $mensaje="SE ENCUENTRA NIVELADO";
                        }

                    $respuesta=array($riesgo,$mensaje);
                    return $respuesta;*/
    }
   
}
?>
