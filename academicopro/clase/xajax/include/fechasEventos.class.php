<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fechas de eventos
 *
 * @author Maritza Callejas
 */
//======= Revisar si no hay acceso ilegal ==============
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
//======================================================
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

function proyectosFacultad($facultad,$decano=0){
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

        $html_lista_proyectos=new html();
        $conexion=new funcionGeneral();
        $conexionOracle=$conexion->conectarDB($configuracion,"coordinadorCred");
    
	if(is_numeric($facultad) && $facultad != 0 && $facultad!=999){
                $carreras_sql="SELECT cra_cod, 
                                (cra_cod ||' - '||cra_nombre) PROYECTO 
                                FROM accra 
                                WHERE cra_estado='A'
                                AND cra_cod>0";
                $carreras_sql.=" AND cra_dep_cod=".$facultad;
                
                if($decano>0){
                    $carreras_sql.=" AND cra_dep_cod in (
                                    SELECT dec_dep_cod 
                                    FROM peemp 
                                    INNER JOIN acdecanos ON dec_cod=emp_cod 
                                    WHERE emp_nro_iden='".$decano."')";
                }
                $carreras_sql.=" ORDER BY cra_cod";
            $carreras=$conexion->ejecutarSQL($configuracion, $conexionOracle, $carreras_sql, "busqueda");
            if($carreras){
                    $proyectoCurricular[0][0]='';
                    $proyectoCurricular[0][1]='--';
                    $proyectoCurricular[1][0]='999';
                    $proyectoCurricular[1][1]='Todos los proyectos';
                    foreach ($carreras as $key => $proyecto) {
                        $proyectoCurricular[$key+2][0]=$proyecto['CRA_COD'];
                        $proyectoCurricular[$key+2][1]=$proyecto['PROYECTO'];
                    }
            }
            

        }elseif($facultad==999){
                $carreras_sql="SELECT cra_cod, 
                                (cra_cod ||' - '||cra_nombre) PROYECTO 
                                FROM accra 
                                WHERE cra_estado='A'
                                AND cra_cod>0
                                AND cra_cod!=999";
                
                
                if($decano>0){
                    $carreras_sql.=" AND cra_dep_cod in (
                                    SELECT dec_dep_cod 
                                    FROM peemp 
                                    INNER JOIN acdecanos ON dec_cod=emp_cod 
                                    WHERE emp_nro_iden='".$decano."')";
                }
                $carreras_sql.=" ORDER BY cra_cod";
            $carreras=$conexion->ejecutarSQL($configuracion, $conexionOracle, $carreras_sql, "busqueda");
            if($carreras){
                    $proyectoCurricular[0][0]='';
                    $proyectoCurricular[0][1]='--';
                    $proyectoCurricular[1][0]='999';
                    $proyectoCurricular[1][1]='Todos los proyectos';
                    foreach ($carreras as $key => $proyecto) {
                        $proyectoCurricular[$key+2][0]=$proyecto['CRA_COD'];
                        $proyectoCurricular[$key+2][1]=$proyecto['PROYECTO'];
                    }
            }
            
        }else{
            $html_lista_proyectos="Seleccion no valida";
        }

        $html_lista_proyectos="<select id='codFacultad' tabindex='2' size='1' name='codProyecto'>";
        foreach ($proyectoCurricular as $key => $proyecto) {
                $html_lista_proyectos.="<option value='".$proyecto[0]."'>".$proyecto[1]."</option>  ";          
        }
        $html_lista_proyectos.="</select>";
        
	//se crea el objeto xajax para enviar la respuesta
	$respuesta = new xajaxResponse();
	//Se asignan los valores al objeto y se envia la respuesta
        $respuesta->addAssign("DIV_PROYECTO","innerHTML",$html_lista_proyectos);
	return $respuesta;
}

?>
