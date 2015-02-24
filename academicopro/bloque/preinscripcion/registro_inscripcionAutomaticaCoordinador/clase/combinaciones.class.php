<?

/**
 * Funcion buscarCombinaciones
 *
 * Esta clase se encarga de buscar los las distintas combinaciones de horarios para un estudainte basado en los espacios academicos y grupos
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 15/01/2013
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 * @global boolean Permite navegar en el sistema
 */


//$miCombinacion=new combinacionArreglos();
//$combinaciones=$miCombinacion->buscarCombinacionesHorario($grupos,'ea');
//var_dump($combinaciones);

/**
 * busca las posibles horarios dentro de un arreglo en el que cada registro contiene un grupo y un espacio
 * La clase crea un arreglo para cada espacio académico en el que el numero de registros corresponde el numero de grupos del espcio,
 * El algoritmo busca las combinaciones entre el primer arreglo y el segundo, luego entre el resultado del primero y el segundo y el tercero, luego el resultado anterios con el cuarto y asi sucesivamente
 * dependiendo del numero de espacios.
 * 
* @param type $espacios
* @return type
 */
class combinacionArreglos{
    
    
    function buscarCombinacionesHorario($arreglo, $clave) {
        $espacios=$this->valoresDistintos($arreglo,$clave);        
        $numeroEspacios=count($espacios);        
        $arregloPorEspacio=$this->CrearUnArregloPorCadaEspacio($espacios,$arreglo); 
        if(count($arregloPorEspacio) > 1) {
        $combinaciones=$this->buscarCombinaciones($arregloPorEspacio[1], $arregloPorEspacio[2]);            
            for($a=2;$a<$numeroEspacios;$a++){
                $combinaciones=$this->buscarCombinaciones($combinaciones, $arregloPorEspacio[$a+1]);    
            }
             return $combinaciones;
        }
        else{
            $arregloPorEspacio[0]=$arregloPorEspacio[1];
            unset($arregloPorEspacio[1]);
            return $arregloPorEspacio;
        }  
           
    }
    
    /**
     * toma el arreglo y obtiene la columna indicada en la clave, de esta columna se extraen los valores distintos
     * 
     * @param type $arreglo
     * @param type $clave
     * @return type
     */
    function valoresDistintos($arreglo, $clave) {
        
            foreach ($arreglo as $registro) {
                $fila[]=$registro[$clave];
            }
            $filaConValoresUnicos=  array_unique($fila);
            return $filaConValoresUnicos;
    }
    
    /**
     * Del arreglo $grupos se obtiene un arreglo por cada espacio con los grupos del espacio
     * 
     * @param type $espacios
     * @param type $grupos
     * @return type
     */
    function CrearUnArregloPorCadaEspacio($espacios, $grupos) {
        $a=1;
        foreach ($espacios as $espacio) {
            foreach ($grupos as $grupo) {
                if($espacio==$grupo['codEspacio']){
                    $arregloEspacio[$a][]=$grupo;                   
                }                
            }
             $a=$a+1;
        }        
        return $arregloEspacio;
    }
    
    /**
     * busca las combinaciones entre el arreglo uno y el arreglo 2
     *    
     * 
     * @param type $arreglo1
     * @param type $arreglo2
     * @return type
     */
    function buscarCombinaciones($arreglo1, $arreglo2) {
        if(is_array($arreglo1) and is_array($arreglo2)){
            $a=1;
            foreach ($arreglo1 as $uno) {
                foreach ($arreglo2 as $dos) {
                    if(isset($uno['codEspacio'])){
                        $registro[$a][]=$uno;
                        $registro[$a][]=$dos;
                    } else{
                        foreach ($uno as $valor) {
                            $registro[$a][]=$valor;
                        }                    
                        $registro[$a][]=$dos;
                        }                       
                        $combinacion[]=$registro[$a];
                        unset($registro);
                }
                $a=$a+1;    
            }

            return $combinacion;
        }else
        {
        return $arreglo1;
            
            }
    }
}


?>