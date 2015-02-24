<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Horarios
 *
 * @author Edwin Sanchez
 */
//======= Revisar si no hay acceso ilegal ==============
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
//======================================================
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

function plan($carrera) {
    
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    //$valor=$acceso_db->verificar_variables($valor);

    $html = new html();
    
    
        $busqueda = "SELECT  distinct(pen_nro) , pen_cra_cod
                FROM  acpen
                WHERE  pen_cra_cod =" . $carrera . "
                AND  pen_estado LIKE  '%A%'
                ORDER BY pen_nro";

        $resultado = $conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");

        $i = 0;
        $html = "<select name='plan' id='plan'>";
        while (isset($resultado[$i][0])) {
            $html.= "<option value='".$resultado[$i][0]."'>Plan de Estudio: " . $resultado[$i][0] . " (" . $resultado[$i][1] . " - " . $resultado[$i][2] . ")</option>";
            $i++;
        }
              
        $html.= "</select>";
        //$mi_cuadro = $html->cuadro_lista($resultado_1, "plan", $configuracion, 0, 0, FALSE, 0, "plan");
        $respuesta = new xajaxResponse();
        $respuesta->addAssign("div_plan", "innerHTML", $html);
    
    return $respuesta;
}

function validar($valor1,$valor2,$valor3,$valor4,$proyecto)
    {//echo "valida";
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
    //$valor=$acceso_db->verificar_variables($valor);

    $html = new html();
  
    //$valor=$acceso_db->verificar_variables($valor);
    $año=substr($valor2,-6,4);
    $periodo=substr($valor2,-1);
    $espacio=$valor1;
    $grupo=$valor3;
    $capacidad=$valor4;

	    if($grupo=="" || !is_numeric($grupo))
	    {
		
	    }
	    else
	    {
		  $busqueda="
		SELECT * FROM ACCURSO
		WHERE cur_ape_ano =". $año."
		AND cur_ape_per = ".$periodo."
		AND cur_asi_cod = ".$espacio."
		AND cur_nro =". $grupo."
		";//echo $busqueda;
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");
	    }
            
            

//            $cadena_sql="SELECT doc_nro_iden, doc_nombre ";
//            $cadena_sql.=" FROM " . $configuracion['bd_proyecto'] . ".docentes ";
//            $cadena_sql.=" WHERE doc_cra_cod=".$proyecto." and doc_estado LIKE '%A%' ORDER BY doc_nro_iden ";
//            $resultadoDocente=$funcion->ejecutarSQL($configuracion, $accesoCoordinador, $cadena_sql,"busqueda");
//
//            if(isset($resultadoDocente[0][0]))
//                {
//                    $htmlDoc = '<b>DOCENTE ENCARGADO</b><br>';
//                    $htmlDoc .= ' <select name="docEncargado" id="docEncargado" size=1 align=center> ';
//                    for($j=0;$j<count($resultadoDocente);$j++)
//                    {
//                        if(isset($resultado[0][9]) && $resultado[0][9] == $resultadoDocente[$j][0])
//                            {
//                                $htmlDoc .= "<option value='".$resultadoDocente[$j][0]."' selected>".$resultadoDocente[$j][1]."</option>";
//                            }else
//                                {
//                                $htmlDoc .= "<option value='".$resultadoDocente[$j][0]."'>".$resultadoDocente[$j][1]."</option>";
//                                }
//                    }
//                    $htmlDoc.= '</select>';
//                }

//            $cadena_sql="SELECT cur_tipo_hor ";
//            $cadena_sql.=" FROM accurso ";
//            $cadena_sql.=" WHERE cur_cra_cod=".$proyecto." and cur_asi_cod = ".$espacio;
//            $cadena_sql.=" AND cur_nro = ".$grupo;
//            $cadena_sql.=" AND cur_ape_ano = ".$año;
//            $cadena_sql.=" AND cur_ape_per = ".$periodo;
//            $resultadoJornada=$funcion->ejecutarSQL($configuracion, $accesoCoordinador, $cadena_sql,"busqueda");
//
//            $tipoJor[1]='Diurno';
//            $tipoJor[2]='Nocturno';
//            $tipoJor[3]='Distancia';
//            $htmlJor = '<b>JORNADA</b><br>';
//            $htmlJor .= ' <select name="jornada" id="jornada" size=1 align=center> ';
//            for($j=1;$j<=3;$j++)
//                {
//                    if( $j == $resultadoJornada[0][0])
//                    {
//                        $htmlJor .= "<option value='".$j."' selected>".$tipoJor[$j]."</option>";
//                    }else
//                    {
//                        $htmlJor .= "<option value='".$j."'>".$tipoJor[$j]."</option>";
//                    }
//                }
//                $htmlJor.= '</select>';
            

            $respuesta = new xajaxResponse();
	    if((!is_numeric($grupo)) || $grupo=="")
	    {
		  $respuesta->addAlert("EL VALOR DIGITADO ".$grupo." NO ES NUMERICO");
		   
	    } 
            if(isset ($resultado[0][0]))
            {
                $respuesta->addAlert("EL GRUPO ".$grupo." YA ESTA ASIGNADO PARA ESTA ASIGNATURA\nPOR FAVOR DIGITE OTRO NÚMERO DE GRUPO");
//                $respuesta->addAssign("div_docEncargado","innerHTML",$htmlDoc);
               // $respuesta->addAssign("div_jornada","innerHTML",$htmlJor);
                $respuesta->addScript("document.getElementById('capacidad').value = ".$resultado[0][5]."");
                $respuesta->addScript("document.getElementById('grupo').readOnly  = 'true'");
              //  $respuesta->addScript("document.getElementById('jornada').readOnly = true");
                $respuesta->addScript("document.getElementById('periodo').readOnly = true");
                $respuesta->addScript("document.getElementById('espacio').readOnly = true");
                $respuesta->addScript("document.getElementById('btnGrabar').value = 'Actualizar Curso'");
                $respuesta->addScript("document.getElementById('hidHorario').value = '1'");
                $respuesta->addScript("document.getElementById('div_btnHorario').style.display = 'block'");
                $respuesta->addScript("document.getElementById('div_btnNuevaBusqueda').style.display = 'block'");
                //$respuesta->addAssign("div_btnHorario","innerHTML",$htmlBoton);
                
            }else{
//                $respuesta->addAssign("div_docEncargado","innerHTML",$htmlDoc);
                //$respuesta->addScript("document.getElementById('jornada').readOnly  = false");
                $respuesta->addScript("document.getElementById('periodo').readOnly = false");
                $respuesta->addScript("document.getElementById('espacio').readOnly = false");
                $respuesta->addScript("document.getElementById('capacidad').value = 30");
                $respuesta->addScript("document.getElementById('grupo').readOnly  = false");
                $respuesta->addScript("document.getElementById('btnGrabar').value = 'Guardar Curso'");
                $respuesta->addScript("document.getElementById('hidHorario').value = '0'");
                $respuesta->addScript("document.getElementById('div_mostrarHorario').style.display = 'none'");
                //$respuesta->addAssign("div_btnHorario","innerHTML","");
                

            }

            return $respuesta;

        
    }

    function nuevaBusqueda($valor1,$valor2,$valor3,$valor4,$proyecto)
    {
    setlocale(LC_MONETARY, 'en_US');
    require_once("clase/config.class.php");
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
  
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
  
    $año=substr($valor2,-6,4);
    $periodo=substr($valor2,-1);
    $espacio=$valor1;
    $grupo=$valor3;
    $capacidad=$valor4;

            /*$busqueda="
            SELECT * FROM ACCURSO
            WHERE cur_ape_ano =". $año."
            AND cur_ape_per = ".$periodo."
            AND cur_asi_cod = ".$espacio."
            AND cur_nro =". $grupo."

            ";*/
            $busqueda="
            SELECT * FROM ACCURSO
            WHERE cur_ape_ano =". $año."
            AND cur_ape_per = ".$periodo."
            AND cur_asi_cod = ".$espacio."
            ";
            //echo $busqueda;
            $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");

//            $cadena_sql="SELECT doc_nro_iden, doc_nombre ";
//            $cadena_sql.=" FROM " . $configuracion['bd_proyecto'] . ".docentes ";
//            $cadena_sql.=" WHERE doc_cra_cod=".$proyecto." and doc_estado LIKE '%A%' ORDER BY doc_nro_iden ";
//            $resultadoDocente=$funcion->ejecutarSQL($configuracion, $accesoCoordinador, $cadena_sql,"busqueda");
//
//            if(isset($resultadoDocente[0][0]))
//                {
//                    $htmlDoc = '<b>DOCENTE ENCARGADO</b><br>';
//                    $htmlDoc .= ' <select name="docEncargado" id="docEncargado" size=1 align=center> ';
//                    for($j=0;$j<count($resultadoDocente);$j++)
//                    {
//                        $htmlDoc .= "<option value='".$resultadoDocente[$j][0]."'>".$resultadoDocente[$j][1]."</option>";
//                    }
//                    $htmlDoc.= '</select>';
//                }

            //$cadena_sql="SELECT cur_tipo_hor ";
            //$cadena_sql.=" FROM " . $configuracion['bd_proyecto'] . ".curso ";
            //$cadena_sql.=" WHERE cur_cra_cod=".$proyecto." and cur_asi_cod = ".$espacio;
            //$resultadoJornada=$conexion->ejecutarSQL($configuracion, $accesoCoordinador, $cadena_sql,"busqueda");

           

            $respuesta = new xajaxResponse();

//                $respuesta->addAssign("div_docEncargado","innerHTML",$htmlDoc);
                $respuesta->addScript("document.getElementById('periodo').disabled = false");
                $respuesta->addScript("document.getElementById('espacio').disabled = false");
                $respuesta->addScript("document.getElementById('grupo').readOnly  = false");
                $respuesta->addScript("document.getElementById('capacidad').value = 30");
                $respuesta->addScript("document.getElementById('grupo').value = 0");                
                $respuesta->addScript("document.getElementById('btnGrabar').value = 'Guardar Curso'");
                $respuesta->addScript("document.getElementById('hidHorario').value = '0'");
                $respuesta->addScript("document.getElementById('div_mostrarHorario').style.display = 'none'");
                $respuesta->addScript("document.getElementById('div_btnHorario').style.display = 'none'");
                $respuesta->addScript("document.getElementById('div_btnNuevaBusqueda').style.display = 'none'");
                //$respuesta->addAssign("div_btnHorario","innerHTML","");
                

            

            return $respuesta;

        
    }


   function verhorario($espacio,$periodo, $grupo, $capacidad,$hora,$dia)
{
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");

    //$valor=$acceso_db->verificar_variables($valor);
    $periodoB=explode('-',$periodo);
    $annio=$periodoB[0];
    $per=$periodoB[1];
    $html = new html();
    

           $busqueda="select HOR_SED_COD, HOR_SAL_COD, SED_ABREV from achorario
                        INNER JOIN gesede ON achorario.HOR_SED_COD=gesede.SED_COD
                        where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora."
                            AND hor_ape_ano=".$annio." AND hor_ape_per=".$per;
//echo $busqueda;
            $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");

            $respuesta = new xajaxResponse();

            if ($resultado==false)
            {
                $resultado_1=" - ";
                $respuesta->addAssign($dia."_".$hora,"innerHTML",$resultado_1);
            }else{

                    $resultado_1="Sede: ".$resultado[0][2]." <br>Salon: ".$resultado[0][1];
                    $respuesta->addAssign($dia."_".$hora,"innerHTML",$resultado_1);
                }
         
        return $respuesta;
}

function horario($espacio,$periodo, $grupo, $capacidad,$hora,$dia)
    {
        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $año=substr($periodo,-6,4);
        $periodoacad=substr($periodo,-1);

 //       echo "Espacio: ".$espacio."Periodo".$periodo."Grupo:". $grupo. "Capacidad: ".$capacidad."Hora: ".$hora."Dia: ".$dia;

                $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=asignarSalon";
                $ruta.="&opcion=asignar";
                $ruta.="&hora=".$hora;
                $ruta.="&dia=".$dia;
                $ruta.="&capacidad=".$capacidad;
                $ruta.="&grupo=".$grupo;
                $ruta.="&periodo=".$periodoacad;
                $ruta.="&anio=".$año;
                $ruta.="&espacio=".$espacio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $cripto=new encriptar();
                $ruta=$cripto->codificar_url($ruta,$configuracion);
                //$abrir= "<script languaje=javascript>window.open(".$pagina.$variable.",'Sede','width=120,height=300,scrollbars=NO')</script>";
                $abrir = $indice.$ruta;
                $respuesta = new xajaxResponse();
                $respuesta->addScript("window.open('".$abrir."','Horario Academico','width=400,height=200,left=0,top=0,scrollbars=no,menubars=no,statusbar=NO,status=NO,resizable=NO,location=NO');");
                //$respuesta->addRedirect($abrir);
                //$respuesta->redirect($abrir);
                return $respuesta;

}

function salones($valor, $hora, $dia, $capacidad, $periodo, $año)
    {
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinador");

    //$valor=$acceso_db->verificar_variables($valor);

    $html = new html();
   

          $busqueda="
                select sal_cod,
                       sal_capacidad,
                       sal_tipo,
                       sal_descrip from gesalon
                       where SAL_ESTADO='A' AND sal_sed_cod=".$valor." AND sal_capacidad >=".$capacidad." AND SAL_COD not in
                            (SELECT hor_sal_cod
                                FROM achorario
                                    WHERE
                                    hor_sed_cod=".$valor." AND
                                    hor_dia_nro=".$dia." AND
                                    hor_hora=".$hora." AND
                                    hor_ape_ano=".$año." AND
                                    hor_ape_per=".$periodo." )
                       ORDER BY sal_cod


            ";
            $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda,"busqueda");
            $i=0;
            while(isset ($resultado[$i][0]))
            {
                $resultado_1[$i][0]=$resultado[$i][0];
                $resultado_1[$i][1]=$resultado[$i][0]." - ".$resultado[$i][3]." - Cap: ".$resultado[$i][1];
                $i++;
            }
            $mi_cuadro="<select name='salon' id='salon'>";
            $j=0;
            while(isset ($resultado_1[$j][0]))
            {
                $mi_cuadro .= "<option value='".$resultado_1[$j][0]."'>".$resultado_1[$j][1]."</option>";
                $j++;
            }
            $mi_cuadro.="</select>";

            $respuesta = new xajaxResponse();
            $respuesta->addAssign("salon","innerHTML",$mi_cuadro);
        
        return $respuesta;


}
?>
