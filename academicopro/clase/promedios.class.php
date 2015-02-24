<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of promediosclass
 *
 * @author Edwin Sanchez
 */
class promedios {

    // Esta funcion retorna el promedio ponderado por periodo academico
    public function ponderadocreditos($resultado)
    {
        for($i=0; $i<=count($resultado)-1; $i++)
        {
            //Nota
           $notas[$i][0]=$resultado[$i][0];
            //Semestre
           $notas[$i][1]=$resultado[$i][1];
            //Creditos
           $notas[$i][2]=$resultado[$i][2];


           if($notas[$i][1]!=($notas[$i+1][1]))
            {
               $sum_cred += $notas[$i][2];
            }
            $promedio[$i][0] = ($notas[$i][0] * $notas[$i][2])/10;
            $prom_suma += $promedio[$i][0];
        }

           $ponderado_cred=$prom_suma/$sum_cred;
            // = $suma / $sum_cred;
            return $ponderado_cred;



    }

    //

     public function ponderadohoras($resultado)
     {

     }

     public function acumuladocreditos ($resultado)
     {
//       var_dump($resultado);
//       exit;
       for($i=0; $i<count($resultado); $i++)
        {
            //Nota
           if($asi!=$resultado[$i][4])
           {
             $notas[$i][0]=$resultado[$i][0];
              //Semestre
             $notas[$i][1]=$resultado[$i][1];
              //Creditos
             $notas[$i][2]=$resultado[$i][2];

             $sum_cred += $notas[$i][2];

             $promedio[$i][0] = ($notas[$i][0] * $notas[$i][2])/10;
              $prom_suma += $promedio[$i][0];
             $asi=$resultado[$i][4];
           }
            else
              {
            }
        }

           $acumulado_cred=$prom_suma/$sum_cred;
            // = $suma / $sum_cred;
            return $acumulado_cred;


     }
    
 public function acumuladohoras ($resultado)
     {
         for($i=0; $i<count($resultado); $i++)
         {
            //Notas
            $notas[$i][0] = $resultado[$i][0];

            // Sumar las notas
            $promedio_acum += $notas[$i][0];
            
         }
            
            $acumulado_horas = number_format($promedio_acum / count($resultado))/10;
            return $acumulado_horas;

     }


}
?>
