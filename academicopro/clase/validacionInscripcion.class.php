<?php
/* 
 * Funcion que tiene todas las validaciones para el proceso de inscripciones
 * 
 */

/**
 * Permite hacer las diferentes validaciones para la inscripción de espacios académicos
 * Cada funcion recibe unos parametros especificos
 *
 * @author Edwin Sánchez
 * Fecha 03 de Septiembre de 2010
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class validacionInscripcion {
    
    public function __construct() {

        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $this->configuracion = $esta_configuracion->variable();
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->cripto=new encriptar();
        $this->funcionGeneral=new funcionGeneral();

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
     * Función que permite validar si el horario del nuevo espacio académico
     * presenta cruce con el horario inscrito por el estudiante.
     * 
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codEspacio
     * @param <int> $grupo
     * @param <array> $retorno
     * @return <boolean>
     * 
     */

    public function validarCruce($configuracion,$codEstudiante,$codEspacio,$grupo,$hor_alternativo,$retorno)
	{
            $variablesEstudiante=array($codEstudiante);
            $cadena_sql=$this->cadena_sql($configuracion,"horario_registrado", $variablesEstudiante);
            $resultado_horario_registrado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            
            $variablesGrupo=array($grupo,$hor_alternativo);
            $cadena_sql=$this->cadena_sql($configuracion,"horario_grupo_nuevo", $variablesGrupo);
            $resultado_horario_grupo_nuevo=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $band=0;

            for($i=0;$i<count($resultado_horario_registrado);$i++) {
                    for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                        if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                            echo "<script>alert ('*El horario del espacio académico presenta cruce con el horario del estudiante. No se ha realizado la inscripción*');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=".$retorno['pagina'];
                            $variable.="&opcion=".$retorno['opcion'];
                            $variable.=$retorno['parametros'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                        }
                    }
                }
                if($band==0)
                    {
                        return true;
                    }else
                        {
                            return false;
                        }
        }

    /**
     * Función que permite verificar si el estudiante ya aprobo el espacio académico que se desea inscribir
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codEspacio
     * @param <int> $codProyecto
     * @param <array> $retorno
     * @return <boolean>
     */
    public function validarAprobado($configuracion,$codEstudiante,$codEspacio,$codProyecto,$retorno)
        {
            $variablesAprobado=array($codProyecto,$codEstudiante,$codEspacio);
            $cadena_sql=$this->cadena_sql($configuracion,"buscar_espacio_aprobado", $variablesAprobado);
            $resultado_EspacioAprobado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (is_array($resultado_EspacioAprobado))
                {
                            echo "<script>alert ('*El espacio académico fue aprobado por el estudiante en el periodo ".$resultado_EspacioAprobado[0][2]."-".$resultado_EspacioAprobado[0][3].". No se puede adicionar de nuevo*');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=".$retorno['pagina'];
                            $variable.="&opcion=".$retorno['opcion'];
                            $variable.=$retorno['parametros'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;

                }else
                {
                    return true;
                }
        }

    /**
     * Función que permite verificar si el estudiante esta en prueba académica
     * si es verdadero verifica que el espacio académico corresponda a un espacio causal de la
     * prueba académica
     * 
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codProyecto
     * @param <int> $codEspacio
     * @param <int> $planEstudio
     * @param <array> $retorno
     * @return <boolean>
     */
    public function validarPruebaAcademica($configuracion,$codEstudiante,$codProyecto,$codEspacio,$planEstudio,$retorno)
        {
            $this->validarEstadoEstudiante($configuracion, $codEstudiante, $retorno);
            $cadena_sql=$this->cadena_sql($configuracion,"estado_estudiante", $codEstudiante);
            $resultado_estudiante=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if($resultado_estudiante[0][0]=='B')
                {
                    $variableEspacio=array($codEspacio,$planEstudio);
                    $cadena_sql=$this->cadena_sql($configuracion,"espacios_planEstudiante",$variableEspacio);
                    $resultado_espacioPlan=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_espacioPlan))
                        {
                            $variablesReprobado=array($codEstudiante,$codEspacio);

                            $cadena_sql=$this->cadena_sql($configuracion,"espacio_reprobado", $variablesReprobado);
                            $resultado_reprobado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            if(!is_array($resultado_reprobado))
                                {
                                    echo "<script>alert ('*No se puede inscribir el Espacio Académico. El estudiante está en Prueba Académica (Parágrafo 1, Artículo 1, Acuerdo 07 de 2009)*.');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=".$retorno['pagina'];
                                    $variable.="&opcion=".$retorno['opcion'];
                                    $variable.=$retorno['parametros'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }
                                else
                                {
                                    return true;
                                }
                        }else
                            {
                                echo "<script>alert ('*El espacio académico no pertenece al plan de estudio del estudiante. El estudiante se encuentra en prueba académica*');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=".$retorno['pagina'];
                                $variable.="&opcion=".$retorno['opcion'];
                                $variable.=$retorno['parametros'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                            }
                }
                else
                    {
                        return true;
                    }
        }


    /**
     * Función que permite verificar el estado academico del estudiante para inscribir espacios
     * si es verdadero permite inscribir
     *
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codProyecto
     * @param <int> $planEstudio
     * @param <array> $retorno
     * @return <boolean>
     */
    public function validarEstadoEstudiante($configuracion,$codEstudiante,$retorno)
        {
            $cadena_sql=$this->cadena_sql($configuracion,"estado_estudiante", $codEstudiante);
            $resultado_estudiante=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(trim($resultado_estudiante[0][0])!='A'&& trim($resultado_estudiante[0][0])!='B')
            {
                echo "<script>alert('*El estado del estudiante (".$resultado_estudiante[0][2].") no permite adicionar espacios académicos*')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=".$retorno['pagina'];
                $variable.="&opcion=".$retorno['opcion'];
                $variable.=$retorno['parametros'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }
                else
                    {
                        return true;
                    }
        }

    public function validarEspacioInscrito($configuracion,$codEstudiante,$codProyecto,$codEspacio,$retorno)
        {
            $variablesInscrito=array($codEstudiante,$codEspacio);
            $cadena_sql=$this->cadena_sql($configuracion,"consultar_espacioInscrito",$variablesInscrito);
            $resultado_espacioInscrito=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacioInscrito))
                {
                    echo "<script>alert ('*El espacio académico ya esta inscrito en el periodo actual, no se puede inscribir de nuevo*');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=".$retorno['pagina'];
                    $variable.="&opcion=".$retorno['opcion'];
                    $variable.=$retorno['parametros'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }else
                    {
                        return true;
                    }
        }

    public function validarEspacioCancelado($configuracion,$codEstudiante,$codProyecto,$codEspacio,$retorno,$continuar)
        {
            $cadena_sql=$this->cadena_sql($configuracion,"periodoActual","");
            $resultado_periodo=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesCancelo=array($codEstudiante,$codEspacio,$resultado_periodo[0][0],$resultado_periodo[0][1]);
            $cadena_sql=$this->cadena_sql($configuracion,"consultar_espacioCancelado",$variablesCancelo);
            $resultado_espacioCancelado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacioCancelado))
                {
                $this->encabezadoSistema($configuracion);
                ?>
<table class="contenidotabla centrar">
    <tr>
        <td class="centrar" colspan="2">
            El espacio acad&eacute;mico fue cancelado en el periodo actual, ¿Desea adicionarlo de nuevo?
        </td>
    </tr>
    <tr class="centrar">
        <td>
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=".$continuar['pagina'];
            $ruta.="&opcion=".$continuar['opcion'];
            $ruta.=$continuar['parametros']."&validacionCancelado=1";

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?echo $pagina.$ruta?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']."/clean.png";?>" width="35" height="35" border="0">
            </a>
        </td>
        <td>
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=".$retorno['pagina'];
            $ruta.="&opcion=".$retorno['opcion'];
            $ruta.=$retorno['parametros'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?echo $pagina.$ruta?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']."/x.png";?>" width="35" height="35" border="0">
            </a>
        </td>
    </tr>
</table>
                    
                <?
                }else
                    {
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=".$continuar['pagina'];
                        $ruta.="&opcion=".$continuar['opcion'];
                        $ruta.=$continuar['parametros']."&validacionCancelado=1";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        echo "<script>location.replace('".$pagina.$ruta."')</script>";
                    }
        }

    public function verificarRangos($configuracion,$planEstudio,$codEspacio,$codEstudiante, $clasificacion, $retorno)
    {
      $cadena_sql=$this->cadena_sql($configuracion,"periodoActual","");
      $resultado_periodo=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        
      $cadena_sql=$this->cadena_sql($configuracion,"rangos_proyecto", $planEstudio);
        $resultado_parametros=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        if(is_array($resultado_parametros))
        {

            $variablesClasificacion=array($planEstudio,$codEspacio);
            $cadena_sql=$this->cadena_sql($configuracion, "info_espacioAdicionar", $variablesClasificacion);
            $resultado_clasificacionEspacio=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            if(is_array($resultado_clasificacionEspacio))
                {
                    $variables=array($codEstudiante,$clasificacion, $resultado_periodo[0][0], $resultado_periodo[0][1]);
                    if ($clasificacion==5){
                        
//                        for($j=0;$j<2;$j++){
//                            $cadena_sql=$this->cadena_sql($configuracion,"espaciosAprobadosClas",$variables);//echo $cadena_sql."<br>";//exit;
//                            $registroEspaciosAprobados=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//                            for($i=0;$i<=count($registroEspaciosAprobados);$i++)
//                            {
//                                $credEst=$credEst+$registroEspaciosAprobados[$i][2];
//                            }
//                            $cadena_sql=$this->cadena_sql($configuracion,"espaciosInscritosClas",$variables);//echo $cadena_sql."<br>";//exit;
//                            $registroEspaciosInscritos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//                            for($i=0;$i<=count($registroEspaciosInscritos);$i++)
//                            {
//                                $credInsEst=$credInsEst+$registroEspaciosInscritos[$i][0];
//                            }
//                            //$clasificacion=1;
//                            $variables[1]=1;
//                            }
//                        $creditos=$credEst+$credInsEst+$resultado_clasificacionEspacio[0][0];
                        $creditos=0;
                        $resultado_parametros[0][$clasificacion]=0;
                    }
//                    elseif ($clasificacion==1){
//                        for($j=0;$j<2;$j++){
//                            $cadena_sql=$this->cadena_sql($configuracion,"espaciosAprobadosClas",$variables);//echo $cadena_sql."**<br>";//exit;
//                            $registroEspaciosAprobados=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//                            for($i=0;$i<=count($registroEspaciosAprobados);$i++)
//                            {
//                                $credEst=$credEst+$registroEspaciosAprobados[$i][2];
//                            }
//                            $cadena_sql=$this->cadena_sql($configuracion,"espaciosInscritosClas",$variables);//echo $cadena_sql."**<br>";//exit;
//                            $registroEspaciosInscritos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//                            for($i=0;$i<=count($registroEspaciosInscritos);$i++)
//                            {
//                                $credInsEst=$credInsEst+$registroEspaciosInscritos[$i][0];
//                            }
//                            //$clasificacion=5;
//                            $variables[1]=5;
//                            }
//                        $creditos=$credEst+$credInsEst+$resultado_clasificacionEspacio[0][0];
//                    }
                    else
                    {
                        $cadena_sql=$this->cadena_sql($configuracion,"espaciosAprobadosClas",$variables);
                        $registroEspaciosAprobados=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                        if(!isset($credEst))
                        {
                            $credEst=0;
                        }
                        for($i=0;$i<=count($registroEspaciosAprobados);$i++)
                        {
                            $credEst=$credEst+(isset($registroEspaciosAprobados[$i][2])?$registroEspaciosAprobados[$i][2]:'');
                        }
                        $cadena_sql=$this->cadena_sql($configuracion,"espaciosInscritosClas",$variables);
                        $registroEspaciosInscritos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                        if(!isset($credInsEst))
                        {
                            $credInsEst=0;
                        }
                        for($i=0;$i<=count($registroEspaciosInscritos);$i++)
                        {
                            $credInsEst=$credInsEst+(isset($registroEspaciosInscritos[$i][0])?$registroEspaciosInscritos[$i][0]:'');
                        }
                        $creditos=$credEst+$credInsEst+$resultado_clasificacionEspacio[0][0];
                    }

                    if($creditos<=$resultado_parametros[0][$clasificacion])
                        {
                            return true;
                        }
                        else
                        {
                            $cadena_sql=$this->cadena_sql($configuracion,"clasificacion",$clasificacion);
                            $resultadoClasificacion=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                            echo "<script>alert ('*No se puede adicionar el espacio académico ".$codEspacio." - ".$resultado_clasificacionEspacio[0][1].". Supera el número de créditos permitidos para la clasificación ".$resultadoClasificacion[0][0]."*');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=".$retorno['pagina'];
                            $variable.="&opcion=".$retorno['opcion'];
                            $variable.=$retorno['parametros'];
                            
                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                        }
                }
                else
                {
                    echo "<script>alert ('*No Imposible rescatar los datos de la clasificación del espacio académico*');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=".$retorno['pagina'];
                    $variable.="&opcion=".$retorno['opcion'];
                    $variable.=$retorno['parametros'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }
        }
        else
        {
            echo "<script>alert ('*Los rangos de créditos no estan definidos por el proyecto curricular. No se puede inscribir el espacio académico*');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=".$retorno['pagina'];
            $variable.="&opcion=".$retorno['opcion'];
            $variable.=$retorno['parametros'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            exit;
        }
    }


    public function validarRangos($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno)
        {
            $cadena_sql=$this->cadena_sql($configuracion,"rangos_proyecto", $planEstudio);
            $resultado_parametros=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($resultado_parametros))
                {
                    $OB=$resultado_parametros[0][1];
                    $OC=$resultado_parametros[0][2];
                    $EI=$resultado_parametros[0][3];
                    $EE=$resultado_parametros[0][4];

                    $variablesClasificacion=array($planEstudio,$codEspacio);

                    $cadena_sql=$this->cadena_sql($configuracion,"clasificacion_espacioAdicionar", $variablesClasificacion);
                    $resultado_clasificacionEspacio=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    $cadena_sql=$this->cadena_sql($configuracion,"datosEstudiante", $codEstudiante);
                    $resultado_estudiante=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_clasificacionEspacio))
                        {
                            $cadena_sql=$this->cadena_sql($configuracion,"espaciosAprobados",$codEstudiante);
                            $registroEspaciosAprobados=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


                                for($i=0;$i<count($registroEspaciosAprobados);$i++)
                                {
                                    $idEspacio= $registroEspaciosAprobados[$i][0];
                                    $variables=array($idEspacio, $resultado_estudiante[0][1]);
                                    $cadena_sql=$this->cadena_sql($configuracion,"valorCreditosPlan",$variables);
                                    $registroCreditosEspacio=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                        switch($registroCreditosEspacio[0][1])
                                        {
                                            case 1:
                                                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$OBEst;
                                                break;

                                            case 2:
                                                    $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$OCEst;
                                                break;

                                            case 3:
                                                    $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$EIEst;
                                                break;

                                            case 4:
                                                    $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$EEEst;
                                                break;

                                            case 5:
                                                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$OBEst;
                                                break;


                                         }
                                }

                                $cadena_sql=$this->cadena_sql($configuracion,"espaciosInscritos",$codEstudiante);
                                $registroEspaciosInscritos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


                                for($i=0;$i<count($registroEspaciosInscritos);$i++)
                                {
                                    $idEspacio= $registroEspaciosInscritos[$i][0];
                                    $variables=array($idEspacio, $resultado_estudiante[0][1]);
                                    $cadena_sql=$this->cadena_sql($configuracion,"valorCreditosPlan",$variables);
                                    $registroCreditosEspacio=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                        switch($registroCreditosEspacio[0][1])
                                        {
                                            case 1:
                                                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$OBEst;
                                                break;

                                            case 2:
                                                    $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$OCEst;
                                                break;

                                            case 3:
                                                    $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$EIEst;
                                                break;

                                            case 4:
                                                    $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$EEEst;
                                                break;

                                            case 5:
                                                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                                                    $totalCreditosEst=$totalCreditosEst+$OBEst;
                                                break;


                                         }
                                }

                                switch ($resultado_clasificacionEspacio[0][1])
                                {
                                    case "1":
                                        $OBEst=$OBEst+$resultado_clasificacionEspacio[0][0];
                                            if($OBEst<=$OB)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('*No se puede adicionar el espacio académico. Supera el número de créditos Obligatorios Basicos permitidos*');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=".$retorno['pagina'];
                                                        $variable.="&opcion=".$retorno['opcion'];
                                                        $variable.=$retorno['parametros'];

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "2":
                                        $OCEst=$OCEst+$resultado_clasificacionEspacio[0][0];
                                            if($OCEst<=$OC)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('*No se puede adicionar el espacio académico. Supera el número de créditos Obligatorios Complementarios permitidos*');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=".$retorno['pagina'];
                                                        $variable.="&opcion=".$retorno['opcion'];
                                                        $variable.=$retorno['parametros'];

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "3":
                                        $EIEst=$EIEst+$resultado_clasificacionEspacio[0][0];
                                            if($EIEst<=$EI)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('*No se puede adicionar el espacio académico. Supera el número de créditos Electivos Intrinsecos permitidos*');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=".$retorno['pagina'];
                                                        $variable.="&opcion=".$retorno['opcion'];
                                                        $variable.=$retorno['parametros'];

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "4":
                                        $EEEst=$EEEst+$resultado_clasificacionEspacio[0][0];
                                            if($EEEst<=$EE)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('*No se puede adicionar el espacio académico. Supera el número de créditos Electivos Extrinsecos permitidos*');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=".$retorno['pagina'];
                                                        $variable.="&opcion=".$retorno['opcion'];
                                                        $variable.=$retorno['parametros'];

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "5":
                                        $OBEst=$OBEst+$resultado_clasificacionEspacio[0][0];
                                            if($OBEst<=$OB)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('*No se puede adicionar el espacio académico. Supera el número de créditos Componente Propedéutico permitidos*');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=".$retorno['pagina'];
                                                        $variable.="&opcion=".$retorno['opcion'];
                                                        $variable.=$retorno['parametros'];

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;


                                }

                        }else
                            {
                                echo "<script>alert ('*El espacio académico no corresponde al plan de estudios del estudiante*');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=".$retorno['pagina'];
                                $variable.="&opcion=".$retorno['opcion'];
                                $variable.=$retorno['parametros'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                            }

                }else
                    {
                        echo "<script>alert ('*Los rangos de créditos no estan definidos por el proyecto curricular*');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=".$retorno['pagina'];
                        $variable.="&opcion=".$retorno['opcion'];
                        $variable.=$retorno['parametros'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
        }

    public function validarEspacioPlan($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno)
        {
    
            $variablesPlan=array($codEspacio,$planEstudio);
            $cadena_sql=$this->cadena_sql($configuracion,"espacios_planEstudiante",$variablesPlan);
            $resultado_espacioPlan=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_espacioPlan))
                {
                    $cadena_sql=$this->cadena_sql($configuracion,"datos_espacio",$variablesPlan);
                    $resultado_espacioDatos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    $cadena_sql=$this->cadena_sql($configuracion, "buscarInfoEspacioOracle", $variablesPlan);
                    $resultado_infoEspacioNoPlanOracle=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                    if($resultado_espacioDatos[0][1]=='4' || $resultado_infoEspacioNoPlanOracle[0][3]=='4')
                        {
                          return true;
                        }else
                            {
                                echo "<script>alert ('*El espacio académico no pertenece al plan de estudio del estudiante. No se puede inscribir el espacio académico*');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=".$retorno['pagina'];
                                $variable.="&opcion=".$retorno['opcion'];
                                $variable.=$retorno['parametros'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                            }
                }else
                    {
                        return true;
                    }
        }

    public function validarRequisitos($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno,$continuar)
        {
            $variableEspacio=array($codEspacio,$planEstudio);
            $cadena_sql=$this->cadena_sql($configuracion,"datos_espacio", $variableEspacio);
            $resultado_espacioDatos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $requisito=array($planEstudio, $codEspacio);
            $cadena_sql=$this->cadena_sql($configuracion,"requisitos", $requisito);
            $resultado_requisito=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $band='0';

            if($resultado_espacioDatos[0][1]=='4')
                {
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=".$continuar['pagina'];
                    $ruta.="&opcion=".$continuar['opcion'];
                    $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                    echo "<script>location.replace('".$pagina.$ruta."')</script>";
                    exit;
                }else
                    {
                        if(is_array($resultado_requisito))
                        {
                        for($i=0;$i<count($resultado_requisito);$i++)
                        {
                            if($band=='0')
                            {
                            switch ($resultado_requisito[$i][0])
                            {
                                case "1":
                                    $variablesRequisito=array($resultado_requisito[$i][1],$codEstudiante);

                                    $cadena_sql=$this->cadena_sql($configuracion,"curso_aprobado", $variablesRequisito);
                                    $resultado_aprobado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                    if(is_array($resultado_aprobado))
                                        {

                                                $cadena_sql=$this->cadena_sql($configuracion,"curso_no_cursado", $variablesRequisito);
                                                $resultado_requisitoNoCursado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                if($resultado_aprobado[0][0]<30)
                                                    {
                                                    $nombreEspacio1=strtr(strtoupper($resultado_requisitoNoCursado[0][0]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                                                    $nombreEspacio2=strtr(strtoupper($resultado_espacioDatos[0][2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                                                    $this->encabezadoSistema($configuracion);
                                                                    ?>
                                                    <table class="contenidotabla centrar">
                                                        <tr>
                                                            <td class="centrar" colspan="2">
                                                                El estudiante curso y perdio el espacio académico <? echo $resultado_requisito[$i][1]." - ".$nombreEspacio1?>  que es requisito de <?echo $codEspacio." - ".$nombreEspacio2?><br>¿Desea inscribirlo?
                                                            </td>
                                                        </tr>
                                                        <tr class="centrar">
                                                            <td>
                                                                <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=".$continuar['pagina'];
                                                                $ruta.="&opcion=".$continuar['opcion'];
                                                                $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1";

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                ?>
                                                                <a href="<?echo $pagina.$ruta?>">
                                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']."/clean.png";?>" width="35" height="35" border="0">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=".$retorno['pagina'];
                                                                $ruta.="&opcion=".$retorno['opcion'];
                                                                $ruta.=$retorno['parametros'];

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                ?>
                                                                <a href="<?echo $pagina.$ruta?>">
                                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']."/x.png";?>" width="35" height="35" border="0">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                                    <?
                                                    
                                                }else
                                                    {
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $ruta="pagina=".$continuar['pagina'];
                                                        $ruta.="&opcion=".$continuar['opcion'];
                                                        $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1";

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                        echo "<script>location.replace('".$pagina.$ruta."')</script>";
                                                        exit;
                                                    }
                                            }else
                                                {
                                                $cadena_sql=$this->cadena_sql($configuracion,"curso_no_cursado", $variablesRequisito);
                                                $resultado_requisitoNoCursado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $nombreEspacio1=strtr(strtoupper($resultado_requisitoNoCursado[0][0]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                                                $nombreEspacio2=strtr(strtoupper($resultado_espacioDatos[0][2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                                                if($resultado_aprobado[0][0]<30){
                                                    $this->encabezadoSistema($configuracion);
                                                                    ?>
                                                    <table class="contenidotabla centrar">
                                                        <tr>
                                                            <td class="centrar" colspan="2">
                                                                El estudiante no ha cursado el espacio académico <? echo $resultado_requisito[$i][1]." - ".$nombreEspacio1?>  que es requisito de <?echo $codEspacio." - ".$nombreEspacio2?> <br>¿Desea inscribirlo?
                                                            </td>
                                                        </tr>
                                                        <tr class="centrar">
                                                            <td>
                                                                <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=".$continuar['pagina'];
                                                                $ruta.="&opcion=".$continuar['opcion'];
                                                                $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1";

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                ?>
                                                                <a href="<?echo $pagina.$ruta?>">
                                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']."/clean.png";?>" width="35" height="35" border="0">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=".$retorno['pagina'];
                                                                $ruta.="&opcion=".$retorno['opcion'];
                                                                $ruta.=$retorno['parametros'];

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                ?>
                                                                <a href="<?echo $pagina.$ruta?>">
                                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']."/x.png";?>" width="35" height="35" border="0">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                
                                            <?}
                                        }
                        }
                    }

                }


                }else
                    {
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=".$continuar['pagina'];
                        $ruta.="&opcion=".$continuar['opcion'];
                        $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        echo "<script>location.replace('".$pagina.$ruta."')</script>";
                        exit;
                    }
            }

            
                
        }
	
        //Funcion que valida si se desea a agregar mas creditos que los permitidos por el proyecto
        //para estudiantes con promedio mayor al establecido por el proyecto
     private function validarInscribirMasCreditos($codEstudiante,$planEstudio,$maxCreditos, $creditosTotal){
			
     		//prueba 
     		//$maxCreditos = 1;
     	
			//Funcion para sacar promedio
			//Si un estudiante tiene mas de 4 en promedio debe poder inscribir mas de los creditos permitidos
			$cadena_sql_promedio=$this->cadena_sql($this->configuracion,"consultar_promedio", $codEstudiante);
			$resultado_Promedio=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_promedio,"busqueda" );
			if(!is_array($resultado_Promedio)){ echo "No es posible verificar promedio estudiante"; exit; }
			
			//Obtiene el promedio maximo para inscribir mas creditos
			$cadena_sql_promedioMinimo=$this->cadena_sql($this->configuracion,"promedio_minimo", $planEstudio);
			$resultado_promedioMinimo=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_promedioMinimo,"busqueda" );
			if(!is_array($resultado_promedioMinimo)){ echo "No es posible verificar promedio mínimo del proyecto curricular"; exit; }
			
					
			//Prueba
			//$resultado_Promedio[0][0]= 4.1;
			
			//Url de redireccion
			$indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
			
			$ruta="pagina=adminConsultarInscripcionEstudianteCoordinador";
			$ruta.="&opcion=mostrarConsulta";
			$ruta.="&codProyecto=".$_REQUEST['codProyecto'];
			$ruta.="&planEstudio=".$_REQUEST['planEstudio'];
			$ruta.="&planEstudioGeneral=".$_REQUEST['planEstudio'];
			$ruta.="&codProyectoEstudiante=".$_REQUEST['codProyecto'];
			$ruta.="&planEstudioEstudiante=".$_REQUEST['planEstudio'];
			$ruta.="&nombreProyecto=".$_REQUEST['codProyecto'];
			$ruta.="&codEstudiante=".$codEstudiante;
			
			
			$ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
			
			$insert = http_build_query($_REQUEST);
			
			//se agrega forzar para no pasar por el if
			$insert .="&forzar=true";
			
			$insert = $this->cripto->codificar_url($insert,$this->configuracion);
			
			 
			if($resultado_Promedio[0][0]*10>=$resultado_promedioMinimo[0][0]&&!isset($_REQUEST['forzar'])&&$creditosTotal>$maxCreditos) {
				//codigo de confirmacion
				?>
			
							<table class="contenidotabla centrar" style="font-size:1em;">
							<tr>
							<td class="centrar" colspan="2">
							Sí usted inscribe este espacio superará el número maximo de créditos establecidos para el proyecto curricular
							<br>Número de créditos adicionales <?php echo $creditosTotal -$maxCreditos ?> 
							<br>Número Maximo de créditos permitidos <?php echo $maxCreditos ?> 
							<br>¿Desea Proceder?
							</td>
							</tr>
							<tr class="centrar">
							<td>
							
							            <a href="<?echo $indice.$insert?>">
							                <img src="<?echo $this->configuracion["site"].$this->configuracion["grafico"]."/clean.png";?>" width="35" height="35" border="0">
							            </a>
							        </td>
							        <td>
							            
							            <a href="<?echo $indice.$ruta?>">
							                <img src="<?echo $this->configuracion["site"].$this->configuracion["grafico"]."/x.png";?>" width="35" height="35" border="0">
							            </a>
							        </td>
							    </tr>
							</table>
			
			
						<?php 
						exit;
			
			            }else return true;
			
		}

        
    public function validarCreditosPeriodo($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno)
        {
            $creditosTotal=0;
            $cadena_sql=$this->cadena_sql($configuracion,"consultaEspaciosEstudiante", $codEstudiante);
            $resultado_Espacios=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesProyecto=array($planEstudio,$codEspacio);
            $cadena_sql=$this->cadena_sql($configuracion,"clasificacion_espacioAdicionar", $variablesProyecto);
            $resultado_EspaciosDatos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            if(!isset($creditos))
            {
                $creditos=0;
            }
            if(is_array($resultado_Espacios))
                {
                    for($i=0;$i<count($resultado_Espacios);$i++)
                    {
                        $creditos+=$resultado_Espacios[$i][1];
                    }
                    $creditosTotal=$creditos+$resultado_EspaciosDatos[0][0];
                }

            $cadena_sql=$this->cadena_sql($configuracion,"creditos_PlanEstudio", $planEstudio);
            $resultado_EspaciosCreditos=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
			
           
            
            if(is_array($resultado_EspaciosCreditos))
                {	
                	
                	$val = $this->validarInscribirMasCreditos($codEstudiante,$planEstudio,$resultado_EspaciosCreditos[0][0],$creditosTotal);
                	
                	if($val==true && isset($_REQUEST['forzar']) ) return true;
                	
                    if($creditosTotal>$resultado_EspaciosCreditos[0][0])
                    {
                        echo "<script>alert ('*No se pueden inscribir más de ".$resultado_EspaciosCreditos[0][0]." créditos por periodo académico para cada estudiante. No se puede inscribir el espacio académico*');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=".$retorno['pagina'];
                        $variable.="&opcion=".$retorno['opcion'];
                        $variable.=$retorno['parametros'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }else
                        {
                            return true;
                        }
                }else
                    {
                        echo "<script>alert ('*Los parametros del plan de estudio no estan definidos por el proyecto curricular*');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=".$retorno['pagina'];
                        $variable.="&opcion=".$retorno['opcion'];
                        $variable.=$retorno['parametros'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
        }

    public function validarCupoEspacio($configuracion,$codEstudiante,$codProyecto,$codEspacio,$grupo,$retorno,$continuar)
        {
            $variableCurso=array($codEspacio,$grupo);
            $cadena_sql=$this->cadena_sql($configuracion,"consultar_datosCurso", $variableCurso);
            $resultado_curso=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_curso))
                {
                    $cupoDisponible=$resultado_curso[0][1]-$resultado_curso[0][2];

                    if($cupoDisponible>0)
                        {
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=".$continuar['pagina'];
                            $ruta.="&opcion=".$continuar['opcion'];
                            $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1&validacionCupo=1";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                            echo "<script>location.replace('".$pagina.$ruta."')</script>";
                            exit;
                        }else
                            {
                                $cadena_sql=$this->cadena_sql($configuracion,"datosCoordinador", $this->usuario);
                                $resultado_datosCoordinador=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                $band='0';

                                for($i=0;$i<count($resultado_datosCoordinador);$i++)
                                {
                                    if($band=='0')
                                        {
                                            if($resultado_datosCoordinador[$i][1]==$resultado_curso[0][0])
                                            {
                                                $band='1';
                                                $this->encabezadoSistema($configuracion);
                                                ?>
                                                <table class="contenidotabla centrar">
                                                        <tr>
                                                            <td class="centrar" colspan="2">
                                                                Si adiciona este estudiante, presentara sobrecupo en el grupo<br>¿Desea inscribirlo?
                                                            </td>
                                                        </tr>
                                                        <tr class="centrar">
                                                            <td>
                                                                <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=".$continuar['pagina'];
                                                                $ruta.="&opcion=".$continuar['opcion'];
                                                                $ruta.=$continuar['parametros']."&validacionCancelado=1&validacionRequisitos=1&validacionCupo=1";

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                ?>
                                                                <a href="<?echo $pagina.$ruta?>">
                                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']."/clean.png";?>" width="35" height="35" border="0">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $ruta="pagina=".$retorno['pagina'];
                                                                $ruta.="&opcion=".$retorno['opcion'];
                                                                $ruta.=$retorno['parametros'];

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                                ?>
                                                                <a href="<?echo $pagina.$ruta?>">
                                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']."/x.png";?>" width="35" height="35" border="0">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table><?
                                            
                                            }else
                                                {
                                                    echo "<script>alert('*El espacio académico no pertenece a su proyecto curricular y presenta sobrecupo*')</script>";
                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $variable="pagina=".$retorno['pagina'];
                                                    $variable.="&opcion=".$retorno['opcion'];
                                                    $variable.=$retorno['parametros'];

                                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                    $this->cripto=new encriptar();
                                                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                    exit;
                                                }
                                        }
                                 
                                }
                                
                            }
                }
        }

    /**
     * Función que permite verificar si el estudiante esta en prueba académica
     * si es verdadero verifica que el espacio académico corresponda a un espacio causal de la
     * prueba académica
     *
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codProyecto
     * @param <int> $codEspacio
     * @param <int> $planEstudio
     * @param <array> $retorno
     * @return <boolean>
     */

    public function validarCancelarPrueba($configuracion,$codEstudiante,$codProyecto,$codEspacio,$planEstudio,$retorno,$continuar)
        {
            $cadena_sql=$this->cadena_sql($configuracion,"periodoActual",'');
            $resultado_periodo=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $cadena_sql=$this->cadena_sql($configuracion,"estado_estudiante", $codEstudiante);
            $resultado_estudiante=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if($resultado_estudiante[0][0]=='B' || $resultado_estudiante[0][0]=='J')
                {
                    $variablesReprobado=array($codEstudiante,$codEspacio);
                    $cadena_sql=$this->cadena_sql($configuracion,"espacio_reprobado", $variablesReprobado);
                    $resultado_reprobado=$this->funcionGeneral->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_reprobado))
                    {
                        $this->encabezadoSistema($configuracion);
                        ?>
                        <table class="contenidotabla centrar">
                            <tr>
                              <td class="centrar" colspan="2">
                                <h1><font color="red">¡¡ ESTUDIANTE EN PRUEBA ACAD&Eacute;MICA !!</font></h1>
                                <h3>El espacio acad&eacute;mico fue reprobado en el per&iacute;odo anterior. ¿Desea cancelarlo?</h3>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td>
                                    <?
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $ruta="pagina=".$continuar['pagina'];
                                    $ruta.="&opcion=".$continuar['opcion'];
                                    $ruta.=$continuar['parametros'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                    ?>
                                    <a href="<?echo $pagina.$ruta?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']."/clean.png";?>" width="35" height="35" border="0">
                                    </a>
                                </td>
                                <td>
                                    <?
                                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                  $ruta="pagina=".$retorno['pagina'];
                                  $ruta.="&opcion=".$retorno['opcion'];
                                  $ruta.=$retorno['parametros'];

                                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                  $this->cripto=new encriptar();
                                  $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                  ?>
                              <a href="<?echo $pagina.$ruta?>">
                                  <img src="<?echo $configuracion['site'].$configuracion['grafico']."/x.png";?>" width="35" height="35" border="0">
                                </a>
                                </td>
                            </tr>
                        </table>
                <?
                    }
                    else
                        {
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=".$continuar['pagina'];
                            $ruta.="&opcion=".$continuar['opcion'];
                            $ruta.=$continuar['parametros'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                            echo "<script>location.replace('".$pagina.$ruta."')</script>";
                        }
                }
                else
                  {
                      $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                      $ruta="pagina=".$continuar['pagina'];
                      $ruta.="&opcion=".$continuar['opcion'];
                      $ruta.=$continuar['parametros'];

                      include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                      $this->cripto=new encriptar();
                      $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                      echo "<script>location.replace('".$pagina.$ruta."')</script>";
                  }
        }

    function encabezadoSistema($configuracion)
        {
        ?>
<table class="contenidotabla centrar">
    <tr>
        <td colspan="6" class="centrar">
            SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<br>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']."/pequeno_universidad.png";?>"alt='UD' border='0'>
            <hr>
        </td>
    </tr>
</table>
        <?
        }

    function cadena_sql($configuracion,$tipo,$variable)
        {
            switch ($tipo)
                {
                    case 'horario_registrado':

                        $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA";
                        $cadena_sql.=" FROM ACHORARIOS HOR";
                        $cadena_sql.=" INNER JOIN ACINS ON HOR.HOR_ID_CURSO=ACINS.INS_GR";
                        $cadena_sql.=" INNER JOIN ACCURSOS ON CUR_ID=HOR_ID_CURSO AND CUR_APE_ANO=INS_ANO AND CUR_APE_PER=INS_PER";
                        $cadena_sql.=" WHERE ACINS.INS_EST_COD=".$variable[0];
                        $cadena_sql.=" AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" ORDER BY 1,2";
                        break;

                    case 'horario_grupo_nuevo':

                        $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA";
                        $cadena_sql.=" FROM ACHORARIOS HOR";
                        $cadena_sql.=" INNER JOIN ACCURSOS ON CUR_ID=HOR_ID_CURSO";
                        $cadena_sql.=" WHERE HOR_ID_CURSO=".$variable[0];
                        $cadena_sql.=" AND HOR_ALTERNATIVA=".$variable[1];
                        $cadena_sql.=" AND CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                        $cadena_sql.=" AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                        $cadena_sql.=" ORDER BY 1,2";
                        break;

                    case 'buscar_espacio_aprobado':

                        $cadena_sql="SELECT NOT_ASI_COD, NOT_GR, NOT_ANO, NOT_PER, NOT_NOTA FROM ACNOT ";
                        $cadena_sql.="WHERE NOT_CRA_COD = ".$variable[0];
                        $cadena_sql.=" AND NOT_EST_COD = ".$variable[1];
                        $cadena_sql.=" AND NOT_ASI_COD = ".$variable[2];
                        $cadena_sql.=" AND NOT_NOTA >=30";
                        $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";
                        break;

                    case "estado_estudiante":

                        $cadena_sql="SELECT ESTADO_COD, ESTADO_NOMBRE, ESTADO_DESCRIPCION FROM ACEST ";
                        $cadena_sql.="INNER JOIN ACESTADO ON ACEST.EST_ESTADO_EST=ACESTADO.ESTADO_COD ";
                        $cadena_sql.="WHERE EST_COD=".$variable;
                        break;

                    case 'espacios_planEstudiante':

                        $cadena_sql="SELECT pen_asi_cod, pen_nro ";
                        $cadena_sql.="FROM acpen ";
                        $cadena_sql.="where pen_asi_cod= ".$variable[0];
                        $cadena_sql.=" and pen_nro= ".$variable[1];
                        break;

                    case "espacio_reprobado":

                        $cadena_sql="SELECT not_asi_cod FROM acnot ";
                        $cadena_sql.="WHERE not_est_cod=".$variable[0];
                        $cadena_sql.=" AND not_asi_cod=".$variable[1];
                        $cadena_sql.=" AND not_nota<30";
                        $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";
                        break;
                    
                    case "consultar_espacioInscrito":

                        $cadena_sql="SELECT ins_asi_cod, ins_est_cod FROM acins ";
                        $cadena_sql.="WHERE ins_est_cod=".$variable[0];
                        $cadena_sql.=" AND ins_asi_cod=".$variable[1];
                        $cadena_sql.=" AND ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" AND ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        break;
                    
                    case "periodoActual":

                        $cadena_sql="SELECT ape_ano, ape_per FROM acasperi ";
                        $cadena_sql.="WHERE ape_estado like '%A%'";
                        break;
                    
                    case "consultar_espacioCancelado":

                        $cadena_sql="SELECT * ";
                        $cadena_sql.="FROM ".$configuracion['prefijo']."horario_estudiante";
                        $cadena_sql.=" WHERE horario_codEstudiante =".$variable[0];
                        $cadena_sql.=" AND horario_idEspacio =".$variable[1];
                        $cadena_sql.=" AND horario_ano =".$variable[2];
                        $cadena_sql.=" AND horario_periodo =".$variable[3];
                        $cadena_sql.=" AND horario_estado ='3'";
                        break;

                    case 'rangos_proyecto':

                        $cadena_sql="SELECT parametro_creditosPlan,parametros_OB, parametros_OC, parametros_EI, parametros_EE ";
                        $cadena_sql.=" FROM sga_parametro_plan_estudio ";
                        $cadena_sql.=" WHERE parametro_idPlanEstudio =".$variable;
                        break;

                    case 'clasificacion_espacioAdicionar':

                        $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                        $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                        $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[1]."";
                        break;

                    case 'datosEstudiante':

                        $cadena_sql="SELECT est_cra_cod, est_pen_nro, est_nombre, cra_nombre  ";
                        $cadena_sql.="FROM acest ";
                        $cadena_sql.="INNER JOIN ACCRA ON acest.est_cra_cod=accra.cra_cod ";
                        $cadena_sql.="WHERE est_cod=".$variable;
                        $cadena_sql.=" AND est_ind_cred like '%S%'";
                        break;

                    case 'espaciosAprobados':

                        $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD ";
                        $cadena_sql.="FROM ACNOT ";
                        $cadena_sql.="WHERE NOT_EST_COD =".$variable;
                        $cadena_sql.=" AND NOT_NOTA >= '30'";
                        $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";
                        break;

                    case 'espaciosAprobadosClas':

                        $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD, NOT_CRED, NOT_CEA_COD ";
                        $cadena_sql.="FROM ACNOT ";
                        $cadena_sql.="WHERE NOT_EST_COD =".$variable[0];
                        $cadena_sql.=" AND NOT_NOTA >= '30'";
                        $cadena_sql.=" AND NOT_CEA_COD=".$variable[1];
                        $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";
                        $cadena_sql.=" AND NOT_CRA_COD=(SELECT EST_CRA_COD FROM ACEST WHERE EST_COD=NOT_EST_COD)";
                        break;

                    case 'espaciosInscritos':

                        $cadena_sql="SELECT ins_asi_cod, ins_cra_cod ";
                        $cadena_sql.="FROM acins ";
                        $cadena_sql.="WHERE ins_est_cod =".$variable;
                        $cadena_sql.=" AND ins_ano= (SELECT ape_ano FROM acasperi WHERE ape_estado LIKE '%A%')";
                        $cadena_sql.=" AND ins_per= (SELECT ape_per FROM acasperi WHERE ape_estado LIKE '%A%')";
                        break;

                    case 'valorCreditosPlan':

                        $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                        $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                        $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0]." AND id_planEstudio=".$variable[1];
                        break;
                    
                    case 'datos_espacio':

                        $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion, espacio_nombre FROM sga_espacio_academico ";
                        $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                        $cadena_sql.="WHERE sga_espacio_academico.id_espacio= '".$variable[0]."'";
                        break;

                    case 'buscarInfoEspacioOracle':

                        $cadena_sql="SELECT CLP_CRA_COD, CLP_ASI_COD, CLP_PEN_NRO, CLP_CEA_COD, CLP_ESTADO FROM ACCLASIFICACPEN";
                        $cadena_sql.=" WHERE CLP_ASI_COD='".$variable[0]."'";
                        $cadena_sql.=" AND CLP_CEA_COD=4";
                        $cadena_sql.=" AND CLP_ESTADO LIKE '%A%'";

                        break;

                    case 'requisitos':

                        $cadena_sql="SELECT requisitos_previoAprobado, requisitos_idEspacioPrevio, requisitos_idEspacioPosterior, requisitos_idPlanEstudio ";
                        $cadena_sql.="FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio ";
                        $cadena_sql.="WHERE requisitos_idPlanEstudio='".$variable[0]."'AND requisitos_idEspacioPosterior='".$variable[1]."'";
                        break;

                    case 'curso_aprobado':

                        $cadena_sql="SELECT NOT_NOTA FROM ACNOT WHERE NOT_ASI_COD = '";
                        $cadena_sql.=$variable[0]."' AND NOT_EST_COD ='".$variable[1]."'";
                        $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";
                        break;

                    case 'curso_no_cursado':

                        $cadena_sql="SELECT asi_nombre FROM ACASI WHERE ASI_COD = '";
                        $cadena_sql.=$variable[0]."'";
                        break;

                    case 'consultaEspaciosEstudiante':

                        $cadena_sql="SELECT DISTINCT INS_ASI_COD, INS_CRED ";
                        $cadena_sql.="FROM ACINS ";
                        $cadena_sql.="WHERE INS_EST_COD= ".$variable;
                        $cadena_sql.=" AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                        $cadena_sql.=" AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                        break;
                    case 'espaciosInscritosClas':

                        $cadena_sql="SELECT INS_CRED  ";
                        $cadena_sql.="FROM ACINS ";
                        $cadena_sql.="WHERE INS_EST_COD= ".$variable[0];
                        $cadena_sql.="AND INS_ANO= ".$variable[2];
                        $cadena_sql.="AND INS_PER=".$variable[3];
                        $cadena_sql.="AND INS_CEA_COD=".$variable[1];

                        break;

                    case 'creditos_PlanEstudio':

                        $cadena_sql="SELECT parametro_maxCreditosNivel ";
                        $cadena_sql.=" FROM sga_parametro_plan_estudio ";
                        $cadena_sql.=" WHERE parametro_idPlanEstudio =".$variable;
                        break;
                     
                    case 'promedio_minimo':
                      	$cadena_sql="SELECT parametro_promedioMinimo ";
                       	$cadena_sql.=" FROM sga_parametro_plan_estudio ";
                       	$cadena_sql.=" WHERE parametro_idPlanEstudio =".$variable;
                       	break;
                        
                        
                    case 'consultar_promedio':
                    	 $cadena_sql=" SELECT fa_promedio_nota('".$variable."') PROMEDIO ";
//                    	 $cadena_sql.=" FROM DUAL ";
                    	break;
                    	
                    case 'consultar_datosCurso':

                        $cadena_sql="SELECT cur_cra_cod, cur_nro_cupo,";
                        $cadena_sql.=" (SELECT COUNT ( * )";
                        $cadena_sql.=" FROM acins";
                        $cadena_sql.=" WHERE ins_asi_cod = cur_asi_cod";
                        $cadena_sql.=" AND ins_gr = cur_id";
                        $cadena_sql.=" AND ins_ano = cur_ape_ano";
                        $cadena_sql.=" AND ins_per = cur_ape_per";
                        $cadena_sql.=" ) AS inscritos ";
                        $cadena_sql.=" FROM accursos";
                        $cadena_sql.=" WHERE cur_asi_cod = '".$variable[0]."'";
                        $cadena_sql.=" AND cur_id = '".$variable[1]."'";
                        $cadena_sql.=" AND cur_ape_ano =(SELECT ape_ano FROM acasperi WHERE ape_estado LIKE '%A%')";
                        $cadena_sql.=" AND cur_ape_per =(SELECT ape_per FROM acasperi WHERE ape_estado LIKE '%A%')";
                        break;

                    case 'datosCoordinador':

                        $cadena_sql="SELECT DISTINCT ";
                        $cadena_sql.="PEN_NRO, ";
                        $cadena_sql.="CRA_COD ";
                        $cadena_sql.="FROM ACCRA ";
                        $cadena_sql.="INNER JOIN GEUSUCRA ";
                        $cadena_sql.="ON ACCRA.CRA_COD = ";
                        $cadena_sql.="GEUSUCRA.USUCRA_CRA_COD ";
                        $cadena_sql.="INNER JOIN ACPEN ";
                        $cadena_sql.="ON ACCRA.CRA_COD = ";
                        $cadena_sql.="ACPEN.PEN_CRA_COD ";
                        $cadena_sql.="WHERE ";
                        $cadena_sql.="GEUSUCRA.USUCRA_NRO_IDEN = ";
                        $cadena_sql.=$variable." ";
                        $cadena_sql.="AND PEN_NRO > 200 ";
                        $cadena_sql.="order by CRA_COD";
                        break;
                    
                    case 'info_espacioAdicionar':

                        $cadena_sql="SELECT espacio_nroCreditos, espacio_nombre FROM sga_espacio_academico ";
                        $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[1];
                        break;

                    case 'rangos_proyecto':

                        $cadena_sql="SELECT parametro_creditosPlan, parametros_OB, parametros_OC, parametros_EI, parametros_EE ";
                        $cadena_sql.=" FROM sga_parametro_plan_estudio ";
                        $cadena_sql.=" WHERE parametro_idPlanEstudio =".$variable;
                        break;

                    case 'clasificacion':
                        $cadena_sql="SELECT CEA_NOM ";
                        $cadena_sql.="FROM GECLASIFICAESPAC ";
                        $cadena_sql.="WHERE CEA_COD=".$variable;
                        break;


                }
                return $cadena_sql;
        }
}
?>
