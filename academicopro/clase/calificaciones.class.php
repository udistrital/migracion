<?
/*
/***************************************************************************
******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.1
* @author      	Kelly K. López
* @description  Clase para el manejo de muestras en  arboles de consulta
* @fecha        Mayo 2009
*******************************************************************************
*/

class calificacion{
    #Funcion constructor		
    function calificacion(){
    }#Cierre de funcion calificaciones

    function calcularPromedioCreditos(){
    }#Cierre de funcion calcularPromedioCreditos

    function calcularCalificacionEspacio($calificacion_porcentajes){
	
	#Variable donde se va alamacenando el calculo de la nota final para la asignatura	
        $this->totalCalificacion=0;
        
        $i=0;
        #Recorre el vector de calificaciones que viene organizado por valor de la calificacion y su correspondiente porcentaje 
	while($i<=count($calificacion_porcentajes)){
	    #Valor de la calificacion	
	    $this->calificacion=$calificacion_porcentajes[$i];
	    #Porcentaje para la calificacion	
	    $this->porcentaje=$calificacion_porcentajes[$i+1];	
	    
            #Multiplica el valor de la calificacion por el porcentaje que representa 
	    #para la materia 
	    $this->calificacionParcial=($this->calificacion * $this->porcentaje)/100;

            #Va sumando el valor de la calificacion al total de la calificacion para la asignatura
            $this->totalCalificacion=($this->totalCalificacion+$this->calificacionParcial);	

            $i=$i+2;
	}#Cierre de while que recoorre vector de $calificacion_porcentajes 

        #Carga vector con los posibles valores que existen para las calificaciones
	$j=15;
	$contador=0;
        while($j<= 50){
	     $this->rangoCalificaciones[$contador]=$j; 	
	     $j=$j+5;
	     $contador=$contador+1;	
	}
	
        #Recorre el vector ubicando el valor formateado de la calificacion
        $n=0; 
        while($n<=count($this->rangoCalificaciones)){
	     $m=$n+1;	
             if($this->totalCalificacion >= $this->rangoCalificaciones[$n]){

                if($this->totalCalificacion < $this->rangoCalificaciones[$m]){
	      
                   $this->inferiorCalificacion=$this->rangoCalificaciones[$n];
	           $this->superiorCalificacion=$this->rangoCalificaciones[$m];

	           break; 	
               }#Cierre de if $this->totalCalificacion < $this->rangoCalificaciones[$n+1]
             
             }#Cierre de if $this->totalCalificacion >= $this->rangoCalificaciones[$n]
	   $n=$n+1;
	}#cierre de while	

	
	#Formatea el totalCalificacion para que tome los dos decimales mas significativos
        $this->totalCalificacion=round($this->totalCalificacion,2);

        #Formatea inferiorCalificacion para que tome los dos decimales mas significativos
	$this->inferiorCalificacion=round($this->inferiorCalificacion,2); 

	#Resta al total de la calificacion la calificacion inferior proxima 	
	$this->diferenciaCalificacion=($this->totalCalificacion-$this->inferiorCalificacion);     

        
        #Valida si es inferior a 2.5 aproxima la nota a la nota inmediatamente inferior dentro del rango
        if($this->diferenciaCalificacion < 2.5){
	   $this->finalCalificacion=$this->inferiorCalificacion;
	}
        
	#Valida si es superior a 2.5 aproxima la nota a la nota inmediatamente superior dentro del rango 
        if($this->diferenciaCalificacion >= 2.5){
	   $this->finalCalificacion=$this->superiorCalificacion;	
        }
	        
        #Devulve el valor formateado
        return $this->finalCalificacion;
	
    }#Cierre de funcion calcularNotaIndividual

}#Cierre de  clase calificaciones
?>