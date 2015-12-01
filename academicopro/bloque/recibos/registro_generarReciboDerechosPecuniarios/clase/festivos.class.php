<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Festivos
{
 
        private $hoy;
        private $festivos;
        private $ano;
        private $pascua_mes;
        private $pascua_dia;
       
        public function getFestivos($ano=''){
                $this->festivos($ano);
                return $this->festivos;
        }
       
        public function festivos($ano='')
        {
                $this->hoy=date('d/m/Y');
               
                if($ano=='')
                        $ano=date('Y');
                       
                $this->ano=$ano;
                //si no esta activo php-calendar utiliza esta funcion
                if(!function_exists('easter_date')) {
                    function easter_date ($Year)
                    {
                    /*
                    G is the Golden Number-1
                    H is 23-Epact (modulo 30)
                    I is the number of days from 21 March to the Paschal full moon
                    J is the weekday for the Paschal full moon (0=Sunday,
                    1=Monday, etc.)
                    L is the number of days from 21 March to the Sunday on or before
                    the Paschal full moon (a number between -6 and 28)
                    */
                    $G = $Year % 19;
                    $C = (int)($Year / 100);
                    $H = (int)($C - (int)($C / 4) - (int)((8*$C+13) / 25) + 19*$G +
                    15) % 30;
                    $I = (int)$H - (int)($H / 28)*(1 - (int)($H / 28)*(int)(29 /
                    ($H +
                    1))*((int)(21 - $G) / 11));
                    $J = ($Year + (int)($Year/4) + $I + 2 - $C + (int)($C/4)) % 7;
                    $L = $I - $J;
                    $m = 3 + (int)(($L + 40) / 44);
                    $d = $L + 28 - 31 * ((int)($m / 4));
                    $y = $Year;
                    $E = mktime(0,0,0, $m, $d, $y);

                    return $E;
                    }
                }
               
                $this->pascua_mes=date("m", easter_date($this->ano));
                $this->pascua_dia=date("d", easter_date($this->ano));
                               
                $this->festivos[$ano][1][1]   = true;           // Primero de Enero
                $this->festivos[$ano][5][1]   = true;           // Dia del Trabajo 1 de Mayo
                $this->festivos[$ano][7][20]  = true;           // Independencia 20 de Julio
                $this->festivos[$ano][8][7]   = true;           // Batalla de Boyacá 7 de Agosto
                $this->festivos[$ano][12][8]  = true;           // Maria Inmaculada 8 diciembre (religiosa)
                $this->festivos[$ano][12][25] = true;           // Navidad 25 de diciembre
               
                $this->calcula_emiliani(1, 6);                          // Reyes Magos Enero 6
                $this->calcula_emiliani(3, 19);                         // San Jose Marzo 19
                $this->calcula_emiliani(6, 29);                         // San Pedro y San Pablo Junio 29
                $this->calcula_emiliani(8, 15);                         // Asunción Agosto 15
                $this->calcula_emiliani(10, 12);                        // Descubrimiento de América Oct 12
                $this->calcula_emiliani(11, 1);                         // Todos los santos Nov 1
                $this->calcula_emiliani(11, 11);                        // Independencia de Cartagena Nov 11
               
                //otras fechas calculadas a partir de la pascua.
               
                $this->otrasFechasCalculadas(-3);                       //jueves santo
                $this->otrasFechasCalculadas(-2);                       //viernes santo
               
                $this->otrasFechasCalculadas(43,true);          //Ascención el Señor pascua
                $this->otrasFechasCalculadas(64,true);          //Corpus Cristi
                $this->otrasFechasCalculadas(71,true);          //Sagrado Corazón
               
                // otras fechas importantes que no son festivos
 
                // $this->otrasFechasCalculadas(-46);           // Miércoles de Ceniza
                // $this->otrasFechasCalculadas(-46);           // Miércoles de Ceniza
                // $this->otrasFechasCalculadas(-48);           // Lunes de Carnaval Barranquilla
                // $this->otrasFechasCalculadas(-47);           // Martes de Carnaval Barranquilla
        }
        protected function calcula_emiliani($mes_festivo,$dia_festivo)
        {
                // funcion que mueve una fecha diferente a lunes al siguiente lunes en el
                // calendario y se aplica a fechas que estan bajo la ley emiliani
                //global  $y,$dia_festivo,$mes_festivo,$festivo;
                // Extrae el dia de la semana
                // 0 Domingo  6 Sábado
                $dd = date("w",mktime(0,0,0,$mes_festivo,$dia_festivo,$this->ano));
                switch ($dd) {
                case 0:                                    // Domingo
                $dia_festivo = $dia_festivo + 1;
                break;
                case 2:                                    // Martes.
                $dia_festivo = $dia_festivo + 6;
                break;
                case 3:                                    // Miércoles
                $dia_festivo = $dia_festivo + 5;
                break;
                case 4:                                     // Jueves
                $dia_festivo = $dia_festivo + 4;
                break;
                case 5:                                     // Viernes
                $dia_festivo = $dia_festivo + 3;
                break;
                case 6:                                     // Sábado
                $dia_festivo = $dia_festivo + 2;
                break;
                }
                $mes = date("n", mktime(0,0,0,$mes_festivo,$dia_festivo,$this->ano))+0;
                $dia = date("d", mktime(0,0,0,$mes_festivo,$dia_festivo,$this->ano))+0;
                $this->festivos[$this->ano][$mes][$dia] = true;
        }      
        protected function otrasFechasCalculadas($cantidadDias=0,$siguienteLunes=false)
        {
                $mes_festivo = date("n", mktime(0,0,0,$this->pascua_mes,$this->pascua_dia+$cantidadDias,$this->ano));
                $dia_festivo = date("d", mktime(0,0,0,$this->pascua_mes,$this->pascua_dia+$cantidadDias,$this->ano));
               
                if ($siguienteLunes)
                {
                        $this->calcula_emiliani($mes_festivo, $dia_festivo);
                }      
                else
                {      
                        $this->festivos[$this->ano][$mes_festivo+0][$dia_festivo+0] = true;
                }
        }      
        public function esFestivo($dia,$mes)
        {
                //echo (int)$mes;
                if($dia=='' or $mes=='')
                {
                        return false;
                }
               
                if (isset($this->festivos[$this->ano][(int)$mes][(int)$dia]))
                {
                        return true;
                }
                else
                {
                        return FALSE;
                }
       
        }      
}?>
