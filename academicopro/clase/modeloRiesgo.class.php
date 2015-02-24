<?php
/* 
 * Clase que tiene todos los calculos para el modelo de riesgo de bienestar institucional
 * 
 */

/**
 *  *
 * @author Maritza Callejas
 * Fecha 23 de Agosto de 2013
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class modeloRiesgo {
  private $configuracion;
  public $sesion;


  public function __construct() {

       
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $this->configuracion=$configuracion;
        
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

        $this->cripto=new encriptar();
        $this->funcionGeneral=new funcionGeneral();
        $this->sesion=new sesiones($configuracion);

        //Conexion General
        $this->acceso_db=$this->funcionGeneral->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->funcionGeneral->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        
        $this->usuario=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        
        $this->identificacion=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    /**
     * Funcion que calcula el valor del rendimiento académico segun modelo matemático
     * @param array $variables(cantidad_reprobados,cantidad_espacios_vistos,cantidad_pruebas_academicas,promedio_acumulado,cantidad_matriculas,cantidad_semestres,cantidad_aprobados,cantidad_espacios_adelantados,cantidad_espacios_nivelado)
     * @return double
     */
    function calcularRendimientoAcademico($variables){
        $ra=0;
        $valores='';
        //evaluamos que se tengan las variables necesarias para los calculos
        if($variables['cantidad_reprobados']>=0 && $variables['cantidad_espacios_vistos']>0 && $variables['cantidad_pruebas_academicas']>=0 && $variables['promedio_acumulado']>0 && $variables['cantidad_matriculas']>0 && $variables['cantidad_semestres']>0 && $variables['cantidad_aprobados']>0 && $variables['cantidad_espacios_adelantados']>=0 && $variables['cantidad_espacios_nivelado']>=0){
                $indice_repitencia= $this->calcularIndiceRepitencia($variables['cantidad_reprobados'],$variables['cantidad_espacios_vistos']);
                $num_prueba_academica=$variables['cantidad_pruebas_academicas'];
                $promedio_acumulado=$variables['promedio_acumulado'];
                $indice_permanencia= $this->calcularIndicePermanencia($variables['cantidad_matriculas'],$variables['cantidad_semestres']);
                $indice_nivelacion=  $this->calcularIndiceNivelacion($variables['cantidad_aprobados'],$variables['cantidad_espacios_adelantados'],$variables['cantidad_espacios_nivelado']);
                //resolver formula del modelo
                $ra = (10*$promedio_acumulado)+(25*(1-$indice_repitencia))+(5*$indice_permanencia)+(10*$indice_nivelacion)+(10*(1/(1+$num_prueba_academica)));
                
                $valores = array(   'indice_repitencia'=>$indice_repitencia,
                                    'indice_permanencia'=>$indice_permanencia,
                                    'indice_nivelacion'=>$indice_nivelacion,
                                    'rendimiento_academico'=>$indice_nivelacion
                );
        }else{
                $valores = array(   'indice_repitencia'=>0,
                                    'indice_permanencia'=>0,
                                    'indice_nivelacion'=>0,
                                    'rendimiento_academico'=>0
                );
            
        }
        return $valores;
                
    }
   
    /**
     * Funcion para calcular el indice de repitencia
     * @param int $cantidad_reprobados
     * @param int $cantidad_espacios_vistos
     * @return double
     */
    function calcularIndiceRepitencia($cantidad_reprobados,$cantidad_espacios_vistos){
        $indice='';
        if($cantidad_espacios_vistos>0){
            $indice = $cantidad_reprobados/$cantidad_espacios_vistos;
        }
        return $indice;
    }
     
    /**
     * Funcion para calcular el indice de permanencia
     * @param int $cantidad_matriculas
     * @param int $cantidad_semestres
     * @return double
     */
    function calcularIndicePermanencia($cantidad_matriculas,$cantidad_semestres){
        $indice='';
        if($cantidad_semestres>0){
            $indice = $cantidad_matriculas/$cantidad_semestres;
        }
        return $indice;
    }
     
    /**
     * Funcion para calcular el indice de nivelación
     * @param int $cantidad_aprobados
     * @param int $cantidad_espacios_adelantados
     * @param int $cantidad_espacios_nivelado
     * @return double
     */
    function calcularIndiceNivelacion($cantidad_aprobados,$cantidad_espacios_adelantados,$cantidad_espacios_nivelado){
         $indice='';
        if($cantidad_espacios_adelantados>0 || $cantidad_espacios_nivelado>0){
            $indice = $cantidad_aprobados/($cantidad_espacios_adelantados+$cantidad_espacios_nivelado);
        }
        return $indice;
    }
    
    /**
     * Funcion para calcular la probabilidad del riesgo de acuerdo a modelo matemático
     * @param array $variables(semestre_espacio_mas_atrasado,cantidad_matriculas,cantidad_reprobados,cantidad_espacios_vistos,cantidad_pruebas_academicas,promedio_acumulado,edad_ingreso,cantidad_semestres_despues_grado)
     * @return double
     */
    function calcularProbabilidadRiesgo($variables){
        $prob_riesgo=0;
        $valores='';
        //evaluamos que se tengan las variables necesarias para los calculos
        if($variables['semestre_espacio_mas_atrasado']>=0 && $variables['cantidad_matriculas']>0 && $variables['cantidad_reprobados']>=0 && $variables['cantidad_espacios_vistos']>0 && $variables['cantidad_pruebas_academicas']>=0 && $variables['promedio_acumulado']>0 && $variables['edad_ingreso']>0  && $variables['cantidad_semestres_despues_grado']>=0)
        {
            $indice_atraso=  $this->calcularIndiceAtraso($variables['semestre_espacio_mas_atrasado'],$variables['cantidad_matriculas']);
            $indice_repitencia= $this->calcularIndiceRepitencia($variables['cantidad_reprobados'],$variables['cantidad_espacios_vistos']);
            $num_prueba_academica=$variables['cantidad_pruebas_academicas'];
            $promedio_acumulado=$variables['promedio_acumulado'];
            $edad_ingreso=$variables['edad_ingreso'];
            $cantidad_semestres_despues_grado=$variables['cantidad_semestres_despues_grado'];

            //resolver formula del modelo
            $prob_riesgo=1/(1+exp(-(-2.425*$indice_atraso+0.130*$num_prueba_academica+6.534*$indice_repitencia+-3.059*$promedio_acumulado+-0.152*$edad_ingreso+0.111*$cantidad_semestres_despues_grado+10.317)));
            
            $valores = array(   'indice_repitencia'=>$indice_repitencia,
                                'indice_atraso'=>$indice_atraso,
                                'indice_riesgo'=>$prob_riesgo
                );
        }else{
            $valores = array(   'indice_repitencia'=>0,
                                'indice_atraso'=>0,
                                'indice_riesgo'=>0
                );
        }
        return $valores;
    }
    
    /**
     * Funcion para calcular el indice de atraso
     * @param int $semestre_espacio_mas_atrasado
     * @param int $cantidad_matriculas
     * @return double
     */
    function calcularIndiceAtraso($semestre_espacio_mas_atrasado,$cantidad_matriculas){
         $indice='';
        if($cantidad_matriculas>0){
            $indice = 1-($semestre_espacio_mas_atrasado/$cantidad_matriculas);
        }
        return $indice;
    }
}
?>
