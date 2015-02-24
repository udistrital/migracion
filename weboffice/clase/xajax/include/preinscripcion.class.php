<?php

/**
 *
 * @param <int> $codProyecto Codigo del proyecto curricular
 */
function nombreAsignatura($codProyecto, $asignatura)
{
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');

    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    $funcion=new funcionGeneral();
    $conexionOracle=$funcion->conectarDB($configuracion,"coordinador");

    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $valor=$acceso_db->verificar_variables($valor);
    $html=new html();

    $variable_asignatura=array($codProyecto,$asignatura);
    $cadena_sql=cadena_sql($configuracion, 'buscarAsignatura', $variable_asignatura);//echo $cadena_sql;exit;
    $resultado_asignatura=$funcion->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql, 'busqueda');

    if($resultado_asignatura[0][3]!=NULL)
        {
            $nombreAsig=$resultado_asignatura[0][3];
        }else
            {
            $nombreAsig="No se encontraron resultados";
            }

    if($resultado_asignatura[0][2]!=NULL)
        {
            $pensumAsig=$resultado_asignatura[0][2];
        }else
            {
            $pensumAsig="No se encontraron resultados";
            }

    $htmlAsignatura="<input type='text' name='nombreAsignaturaNoIns' id='nombreAsignaturaNoIns' value='".$nombreAsig."' size='50' readonly>";
    $htmlPensum="<input type='hidden' name='nroPensumNoIns' id='nroPensumNoIns' value='".$pensumAsig."'>";

    $respuesta = new xajaxResponse();
    ////Se retorna el mensaje de estudiantes procesados
    $respuesta->addAssign("div_asignaturaNoIns","innerHTML",$htmlAsignatura);
    $respuesta->addAssign("div_nroPensumNoIns","innerHTML",$htmlPensum);
            
    
return $respuesta;

}


function nombreEstudiante($codProyecto, $codEstudiante)
{
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');

    $esta_configuracion=new config();
    $configuracion=$esta_configuracion->variable();
    //Buscar un registro que coincida con el valor
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    $funcion=new funcionGeneral();
    $conexionOracle=$funcion->conectarDB($configuracion,"coordinador");

    $acceso_db=new dbms($configuracion);
    $enlace=$acceso_db->conectar_db();
    $valor=$acceso_db->verificar_variables($valor);
    $html=new html();

    $variable_estudiante=array($codProyecto,$codEstudiante);
    $cadena_sql=cadena_sql($configuracion, 'buscarEstudiante', $variable_estudiante);//echo $cadena_sql;exit;
    $resultado_estudiante=$funcion->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql, 'busqueda');

    if($resultado_estudiante[0][0]!=NULL)
        {
            $nombreEst=$resultado_estudiante[0][0];
        }else
            {
            $nombreEst="No se encontraron resultados";
            }
   

    $htmlEstudiante="<input type='text' name='nombreEstudianteNoIns' id='nombreEstudianteNoIns' value='".$nombreEst."' size='50' readonly>";

    $respuesta = new xajaxResponse();
    ////Se retorna el mensaje de estudiantes procesados
    $respuesta->addAssign("div_estudianteNoIns","innerHTML",$htmlEstudiante);


return $respuesta;

}

function cadena_sql($configuracion,$tipo,$variable='')
    {
        switch ($tipo)
        {
            case 'buscarAsignatura':

                $cadena_sql = "SELECT PEN_CRA_COD, ";
                $cadena_sql.="PEN_ASI_COD, ";
                $cadena_sql.="PEN_NRO, ";
                $cadena_sql.="ASI_NOMBRE ";
                $cadena_sql.="FROM ACPEN, ACASI ";
                $cadena_sql.="WHERE PEN_CRA_COD = '".$variable[0]."' ";
                $cadena_sql.="AND PEN_ASI_COD = '".$variable[1]."' ";
                $cadena_sql.="AND ASI_COD = PEN_ASI_COD ";

                break;

            case 'buscarEstudiante':

                $cadena_sql = "SELECT est_nombre ";
                $cadena_sql.="FROM  acest ";
                $cadena_sql.="WHERE est_cra_cod = '".$variable[0]."' ";
                $cadena_sql.="and est_cod = '".$variable[1]."'";

                break;
            
        }
        return $cadena_sql;
    }

?>
