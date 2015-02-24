<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCancelarInscripcionEstudCoordinador extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

         //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Datos de sesion
        $this->formulario="registroCancelarInscripcionEstudCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    

    function verificarCancelacionEspacio($configuracion) {
    //verifica la posibilidad de cancelar un espacio académico
        $banderaGrupo=$_REQUEST['banderaGrupo'];
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $codEspacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['grupo'];  
        $planEstudio=$_REQUEST['planEstudio'];
        $nombre=$_REQUEST['nombre'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"periodo", "");//busca el periodo actual
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda");
        $verificar=array($codigoEstudiante, $proyecto, $resultado_periodo[0][0], $resultado_periodo[0][1], $codEspacio, $grupo, $planEstudio, $nombre);
        // si es estudiante
        $cadena_sql_estado=$this->sql->cadena_sql($configuracion,"verificar_estado", $verificar);
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_estado,"busqueda");
        $verificar[11]=$resultado_plan[0][0];
        
        // si es coordinador
        $this->redireccionarProceso($configuracion, "verificarCreditos", $verificar);
    }
   
    function verificarCreditos($configuracion) {

        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $espacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['grupo'];
        $ano=$_REQUEST['ano'];
        $periodo=$_REQUEST['periodo'];
        $nombre=$_REQUEST['nombre'];
        $verificar=$_REQUEST['verificar'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];

        $variables=array($codigoEstudiante, $proyecto, $ano, $periodo, $planEstudio, $espacio, $grupo, $nombre);

        $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);
        $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");
        $cadena_sql_minimo=$this->sql->cadena_sql($configuracion,"minimo_creditos", $planEstudio);
        $resultado_minimo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_minimo,"busqueda");
        $cadena_sql_creditoEspacio=$this->sql->cadena_sql($configuracion,"creditos_espacio", $espacio);
        $resultado_creditoEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditoEspacio,"busqueda");

        $creditos=$resultado_creditos[0][0]-$resultado_creditoEspacio[0][0];
        
        ?>
<table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
    <tr class="texto_subtitulo">
        <td colspan="2">
            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px">
                <tr class="texto_subtitulo">
                    <td  align="center">
                                <?if ($verificar==2) {
                                    ?>El Espacio Acad&eacute;mico que va a cancelar fue reprobado el semestre anterior.<br><br><?
                                }
                                if($verificar==3) {
                                    ?>El Espacio Acad&eacute;mico que va a cancelar ya ha sido cancelado en este per&iacute;odo.<br><br><?
                                }
                                if(isset($aviso)) {
                                    ?>Si cancela este Espacio Acad&eacute;mico, el estudiante quedar&aacute; con <?echo $aviso ?> cr&eacute;ditos.<br><br><?
                                }
                                ?>
                                    Recuerde que si cancela un Espacio Acad&eacute;mico &eacute;ste no se puede volver a inscribir en el presente semestre. <br><br> ¿Est&aacute; seguro que desea cancelar <? echo htmlentities($nombre) ?>?
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
             <tr>
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                 <td align="center">
                <input type="hidden" name="planEstudioGeneral" value="<?echo $planEstudioGeneral?>">
                <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                <input type="hidden" name="proyecto" value="<?echo $proyecto?>">
                <input type="hidden" name="codEspacio" value="<?echo $espacio?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="grupo" value="<?echo $grupo?>">
                <input type="hidden" name="ano" value="<?echo $ano?>">
                <input type="hidden" name="periodo" value="<?echo $periodo?>">
                <input type="hidden" name="nombre" value="<?echo $_REQUEST['nombre']?>">
                <input type="hidden" name="creditos" value="<?echo $resultado_creditoEspacio[0][0]?>">                
                <input type="hidden" name="opcion" value="cancelarCreditos">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" name="aceptar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30">
                    </td>
                    <td align="center">               
                        <input type="image" name="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="30" height="30">
                    </td>
              </form>
            </tr>           
        </td>
    </tr>
</table>
        <?    
    }

    function cancelarCreditos($configuracion) {

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"periodo", "");//busca el periodo actual
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda");
        $codEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $espacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['grupo'];
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];
        $nombre=$_REQUEST['nombre'];
        $creditos=$_REQUEST['creditos'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];

        $variables=array($codEstudiante,$proyecto,$ano,$periodo,$planEstudio,$espacio,$grupo,$nombre,$creditos,$codProyecto,$planEstudioGeneral);

        if(!$_REQUEST['reprobado'])
                {
                    $this->verificarEspacioReprobado($configuracion,$variables);
                    exit;
                }
        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variables);
        $resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if($resultado_EspacioOracle[0][3]==$grupo)
        {
            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"asignaturas_inscritas", $variables);
            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_creditos,"busqueda");

            if(is_array($resultado_creditos))
                {
                    for($k=0;$k<=count($resultado_creditos);$k++)
                    {
                        $creditosEstudiante+=$resultado_creditos[$k][1];
                    }
                    $creditosTotal=$creditosEstudiante-$creditos;
//********** verifica si al cancelar el espacio el estudiante queda con menos de ocho creditos registrados
//********** Se modifica por el acuerdo 007 de 2011 29/11/2011
//
//                    if($creditosTotal<8)
//                        {
//                            echo "<script>alert ('El espacio académico no se puede cancelar, el estudiante no puede quedar con menos de 8 créditos en el periodo académico');</script>";
//                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//                            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
//                            $variable.="&opcion=mostrarConsulta";
//                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
//                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
//                            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
//
//                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
//                            $this->cripto=new encriptar();
//                            $variable=$this->cripto->codificar_url($variable,$configuracion);
//
//                            echo "<script>location.replace('".$pagina.$variable."')</script>";
//                            exit;
//                        }
                }

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"" );
            if (is_array($resultado_EspacioMysql))
            {
                $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"cancelar_espacio_mysql", $variables);
                $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );
            }
            else
            {
                $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"registrar_cancelar_espacio_mysql", $variables);
                $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );
            }
            
            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion, "cupo_grupo_ins", $variables);
            $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

            $variables[10]=(($resultado_cupoInscritos[0][0])-1);
            
            $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

            $variablesRegistro=array($this->usuario,date('YmdGis'),'2','Cancela Espacio académico',$ano.", ".$periodo.", ".$espacio.", ".$grupo.", 0, ".$planEstudio.", ".$proyecto, $codEstudiante);
            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
            $variable.="&proyecto=".$proyecto;
            $variable.="&espacio=".$espacio;
            $variable.="&grupo=".$grupo;
            $variable.="&ano=".$ano;
            $variable.="&periodo=".$periodo;
            $variable.="&nombre=".$nombre;
            $variable.="&creditos=".$creditos;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else{
            echo "<script>alert ('La base de datos se encuentra ocupada. El Espacio Académico ".$nombre." no ha sido cancelado');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
    }

    function cancelar($configuracion) {
       
        $codEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $ano=$_REQUEST['ano'];
        $periodo=$_REQUEST['periodo'];
        $nombre=$_REQUEST['nombre'];
        $creditos=$_REQUEST['creditos'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        
        echo "<script>alert ('El Espacio Académico ".htmlentities($nombre)." no ha sido cancelado');</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
        $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }


    function redireccionarProceso($configuracion, $opcion, $valor="") {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        unset($_REQUEST['action']);
        $cripto=new encriptar();
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        switch($opcion) {
            case "verificarCreditos":
                $variable="pagina=registroCancelarInscripcionEstudCoordinador";
                $variable.="&opcion=verificarCreditos";
                $variable.="&codEstudiante=".$valor[0];
                $variable.="&proyecto=".$valor[1];
                $variable.="&ano=".$valor[2];
                $variable.="&periodo=".$valor[3];
                $variable.="&codEspacio=".$valor[4];
                $variable.="&grupo=".$valor[5];
                $variable.="&planEstudio=".$valor[6];
                $variable.="&nombre=".$valor[7];
                $variable.="&verificar=".$valor[11];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                break;

            case "solicitar_confirmacion":
                $variable="pagina=registroCancelarInscripcionEstudCoordinador";
                $variable.="&opcion=solicitarConfirmacion";
                $variable.="&carrera=".$valor[0];
                $variable.="&planEstudio=".$valor[1];
                $variable.="&orden=".$valor[2];
                break;

            case "cancelar":
                $variable="pagina=registroCancelarInscripcionEstudCoordinador";
                $variable.="&opcion=cancelarCreditos";
                $variable.="&codEstudiante=".$valor[0];
                $variable.="&proyecto=".$valor[1];
                $variable.="&ano=".$valor[2];
                $variable.="&periodo=".$valor[3];
                $variable.="&codEspacio=".$valor[5];
                $variable.="&grupo=".$valor[6];
                $variable.="&planEstudio=".$valor[4];
                $variable.="&nombre=".$valor[7];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        }
        $variable=$cripto->codificar_url($variable,$configuracion);
        echo "<script>location.replace('".$indice.$variable."')</script>";
    }

    function verificarEspacioReprobado($configuracion,$variables)
    {
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_reprobado", $variables);
        $resultado_reprobados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_reprobados))
            {
            echo "<script type='text/javascript' language='javascript'>";

               echo "if(!confirm('El espacio académico fue reprobado en el periodo anterior, ¿Desea cancelarlo?'))
                    {";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&proyecto=".$variables[1];
                        $variable.="&codProyecto=".$variables[9];
                        $variable.="&planEstudioGeneral=".$variables[10];
                        $variable.="&planEstudio=".$variables[4];
                        $variable.="&codEstudiante=".$variables[0];
                        $variable.="&creditos=".$variables[8];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                       echo "location.replace('".$pagina.$variable."')
                    }else
                    {";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroCancelarInscripcionEstudCoordinador";
                        $variable.="&opcion=cancelarCreditos";
                        $variable.="&proyecto=".$variables[1];
                        $variable.="&planEstudio=".$variables[4];
                        $variable.="&codEstudiante=".$variables[0];
                        $variable.="&codEspacio=".$variables[5];
                        $variable.="&grupo=".$variables[6];
                        $variable.="&nombre=".$variables[7];
                        $variable.="&creditos=".$variables[8];
                        $variable.="&codProyecto=".$variables[9];
                        $variable.="&planEstudioGeneral=".$variables[10];
                        $variable.="&reprobado=1";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "location.replace('".$pagina.$variable."')
                    }
              </script>
                     ";
            }else
                {
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroCancelarInscripcionEstudCoordinador";
                    $variable.="&opcion=cancelarCreditos";
                    $variable.="&proyecto=".$variables[1];
                    $variable.="&planEstudio=".$variables[4];
                    $variable.="&codEstudiante=".$variables[0];
                    $variable.="&codEspacio=".$variables[5];
                    $variable.="&grupo=".$variables[6];
                    $variable.="&nombre=".$variables[7];
                    $variable.="&creditos=".$variables[8];
                    $variable.="&codProyecto=".$variables[9];
                    $variable.="&planEstudioGeneral=".$variables[10];
                    $variable.="&reprobado=1";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }
            
    }

}


?>

