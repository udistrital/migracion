<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2007                                      #
#    paulo_cesar@udistrital.edu.co                                         #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/****************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 5 de Noviembre de 2009

******************************************************************************
* @subpackage   preinscripcion
* @package	bloques
* @copyright    
* @version      0.1
* @author      	Milton Parra
* @link		N/D
* @description  Menu para revisar adiciones.
* @usage        
*******************************************************************************/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql/sqlRevisarAdicion.class.php");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/ProgressBar.class.php");
class revisarAdicion {

    var $sqler;

    var $contador;

    var $codigoEstudiante;




    //@Constructor
    function __construct() {
        $this->resultado = new funcionGeneral();
        $this->sqler=new sqlRevisarAdicion();



    }

    function set_codigoEstudiante($codigo){
        $this->codigoEstudiante=$codigo;
    }


    function rescatarGruposEA($configuracion, $conexionOracle,$valores) {
    //cadena que busca la existencia de grupos de un EA
        $cadena_grupo_EA=$this->sqler->cadena_revisarAdicion($configuracion, "buscarGruposEA",$valores);
        
        $resultado_grupo_EA=$this->resultado->ejecutarSQL($configuracion, $conexionOracle, $cadena_grupo_EA, "busqueda");

        if (is_array($resultado_grupo_EA)) {
            return $resultado_grupo_EA;

        }

      return false;

    }

    //@TO DO: Buscar la forma de llenar los cupos de una vez
    function verificarCupo($configuracion,$conexion,$conexionOracle,$parametros) {


        $valores=array($parametros[5],$parametros[6],$parametros[0],$parametros[1],$parametros[2],$parametros[3]);

        $cadena_grupo_EA=$this->sqler->cadena_revisarAdicion($configuracion, "verificarCupoEA",$valores);
        $resultado_grupo_EA=$this->resultado->ejecutarSQL($configuracion, $conexion, $cadena_grupo_EA, "busqueda");
        if(is_array($resultado_grupo_EA)){
            if ($resultado_grupo_EA[0][0]>0) {
                return $resultado_grupo_EA[0][0];

            }else{
                return false;
            }

        }else{
                $valores=array($parametros[0],$parametros[6],$parametros[2],$parametros[3],$parametros[5],$parametros[6]);
                $cadena_grupo_EA=$this->sqler->cadena_revisarAdicion($configuracion, "buscarCuposEA",$valores);

                $resultado_cupos_EA=$this->resultado->ejecutarSQL($configuracion, $conexionOracle, $cadena_grupo_EA, "busqueda");
                if(is_array($resultado_cupos_EA)){
                       $valores[0]=$resultado_cupos_EA[0][0];
                       $valores[1]=$parametros[5];
                       $valores[2]=$resultado_cupos_EA[0][2];
                       $valores[3]=$resultado_cupos_EA[0][3];
                       $valores[4]=$resultado_cupos_EA[0][0];
                       $valores[5]=$parametros[0];
                       $valores[6]=$parametros[1];
                       $valores[7]=$parametros[2];
                       $valores[8]=$parametros[3];
                       $valores[9]=$resultado_cupos_EA[0][0]-$resultado_cupos_EA[0][1];

                       $cadena_sql=$this->sqler->cadena_revisarAdicion($configuracion, "insertarCupos",$valores);
                       $resultado_cupos_EA=$this->resultado->ejecutarSQL($configuracion, $conexion, $cadena_sql, "ejecutar");
                }

        }


        //TODO: Revisar:


        $valores=array($parametros[5],$parametros[6],$parametros[0],$parametros[1],$parametros[2],$parametros[3]);

        $cadena_grupo_EA=$this->sqler->cadena_revisarAdicion($configuracion, "verificarCupoEA",$valores);
        $resultado_grupo_EA=$this->resultado->ejecutarSQL($configuracion, $conexion, $cadena_grupo_EA, "busqueda");
        if(is_array($resultado_grupo_EA)){
            if ($resultado_grupo_EA[0][0]>0) {
                return $resultado_grupo_EA[0][0];

            }else{
                return false;
            }

        }

        return false;
    }



    function verificarCruceEA($configuracion, $conexionGestion, $conexionOracle, $valores) {
        //selecciona el primer EA del estudiante valores:0=cra; 1=planest; 2=año; 3=periodo; 4=cod est

        
        $conCruce=false;

        $this->codigoEstudiante=$valores[4];

        //busca si el grupo registrado existe
        $j=0;
        $k=1;
        $busqueda[0][0]=$valores[5];//EA
        $busqueda[0][1]=$valores[0];//CRA
        $busqueda[0][2]=$valores[6];//GRUPO

        $cadena_sql=$this->sqler->cadena_revisarAdicion($configuracion, "rescatarGrupoEstudiante",$this->codigoEstudiante);

        
        $resultado_grupo_EA=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql, "busqueda");

        if(is_array($resultado_grupo_EA)){

            $espacios=array_merge($busqueda,$resultado_grupo_EA);
            
            //Buscar los horarios
             $cadena_sql=$this->sqler->cadena_revisarAdicion($configuracion, "rescatarHorario",$espacios);
             $resultado_horario=$this->resultado->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql, "busqueda");

             if(is_array($resultado_horario)){
                 
                 //Revisar que no existan dos filas con los mismos valores
//
//
//                 echo "<table>";
//                 for($j=0;$j<count($resultado_horario);$j++)
//                 {
//                     echo "<tr>\n";
//                     echo "<td>\n";
//                     echo $resultado_horario[$j][0];
//                     echo "</td>\n";
//
//                     echo "<td>\n";
//                     echo $resultado_horario[$j][1];
//                     echo "</td>\n";
//
//                     echo "<td>\n";
//                     echo $resultado_horario[$j][2];
//                     echo "</td>\n";
//
//                     echo "<td>\n";
//                     echo $resultado_horario[$j][3];
//                     echo "</td>\n";
//
//                     echo "</tr>";
//                 }
//                 echo "</table>";
//
//                 exit;
                 for($j=0;$j<count($resultado_horario);$j++)
                 {
                     if($resultado_horario[$j][1]==$resultado_horario[$j+1][1]
                         &&$resultado_horario[$j][2]==$resultado_horario[$j+1][2]){
                         $conCruce=true;
                         break;
                     }
                 }


             }else{
                 //Error
             }






        }else{

            $conCruce=false;

        }

        return $conCruce;

    }







    function actualizarHorario($configuracion, $conexionGestion, $conexionOracle, $parametros) {
        
        $valores[0]=$parametros[4];
        $valores[1]=$parametros[5];
        $valores[2]=$parametros[6];
        $valores[3]=$parametros[0];
        $valores[4]=$parametros[1];
        $valores[5]=$parametros[2];
        $valores[6]=$parametros[3];
        $valores[7]=$parametros[7];

        $cadena_sql=$this->sqler->cadena_revisarAdicion($configuracion, "insertarGrupoEA",$valores);
        $resultado=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql, "ejecutar");

        return $resultado;

        


    }


    function actualizarCupos($configuracion, $conexionGestion, $conexionOracle, $valores) {

        
        $busqueda["espacio"]=$valores[5];//EA
        $busqueda["proyecto"]=$valores[0];//CRA
        $busqueda["grupo"]=$valores[6];//GRUPO
        $busqueda["anno"]=$valores[2];//Anno
        $busqueda["periodo"]=$valores[3];//periodo
        $busqueda["cupo"]=$valores[8];//Cupos actuales
        $busqueda["planEstudio"]=$valores[1];//Plan de estudio

        $cadena_sql=$this->sqler->cadena_revisarAdicion($configuracion, "actualizarCupoEA",$busqueda);
        $resultado=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql, "");
        return $resultado;

    }


    function actualizarErrores($configuracion, $conexionGestion, $conexionOracle, $valores) {

        $cadena_sql=$this->sqler->cadena_revisarAdicion($configuracion, "insertarRegistroError",$valores);
        $resultado=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql, "");
        return $resultado;

    }



    //function revisarCreditos($dia,$mes,$anno,$configuracion, $acceso_db)
    function actualizarCreditos($configuracion, $conexionGestion, $conexionOracle, $valores) {


        //revisa los creditos de cada estudiante

        $datos["creditos"]=$valores["creditos"];
        $datos["proyectoCurricular"]=$valores[0];
        $datos["planEstudios"]=$valores[1];
        $datos["anno"]=$valores[2];
        $datos["periodo"]=$valores[3];
        $datos["codigoEstudiante"]=$valores[4];

        
        $cadena_infoCreditos=$this->sqler->cadena_revisarAdicion($configuracion, "revisarInfoCreditos", $datos);
        

        $resultado_infoCredito=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_infoCreditos, "busqueda");


        //si hay registro de creditos
        if($resultado_infoCredito) {
            //actualiza con nuevo valor
            $cadena_actualizaCreditos=$this->sqler->cadena_revisarAdicion($configuracion, "actualizarCreditos", $datos);
            $resultado_actualizaCreditos=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_actualizaCreditos, "");
        }
        //si no hay registro de creditos
        else {
        //inserta registro con nuevo valor
        //si no esta registrado, realiza el registro.
            $cadena_insertarCreditos=$this->sqler->cadena_revisarAdicion($configuracion,  "insertarCreditos",$datos);
            $resultado_insertarCreditos=$this->resultado->ejecutarSQL($configuracion, $conexionGestion, $cadena_insertarCreditos, "");
        }


        


    }





}

?>