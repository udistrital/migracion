<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCancelarCreditosEstudiante extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
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
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroCancelarCreditosEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");



    }

 

    function verificarCancelacionEspacio($configuracion) {
    //verifica la posibilidad de cancelar un espacio académico
        if($_REQUEST['codEstudiante']==NULL) {
            $codigoEstudiante=$this->usuario;//si ingresa como estudiante

        }else {
            $codigoEstudiante=$_REQUEST['codEstudiante'];//si ingresa como coordinador

        }

        //$codigoEstudiante='20092073001';
        $codEspacio=$_REQUEST['codEspacio'];//'1238';//
        $grupo=$_REQUEST['grupo'];//'5';//
        $proyecto=$_REQUEST['proyecto'];//'73';//
        $planEstudio=$_REQUEST['planEstudio'];//'223';//
        $nombre=$_REQUEST['nombre'];//'Prueba';//

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"periodo", "");//busca el periodo actual
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda");
        $verificar=array($codigoEstudiante, $proyecto, $resultado_periodo[0][0], $resultado_periodo[0][1], $codEspacio, $grupo, $planEstudio, $nombre);
        // si es estudiante
        $cadena_sql_estado=$this->sql->cadena_sql($configuracion,"verificar_estado", $verificar);//echo $cadena_sql_estado;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_estado,"busqueda");

        $cadena_sql=$this->sql->cadena_sql($configuracion,"verificar_espacio_reprobado", $verificar);//echo $cadena_sql;exit;
        $resultado_reprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        $verificar[11]=$resultado_plan[0][0];
            if (($resultado_plan[0][0]==2) || (is_array($resultado_reprobado)) ) {
                ?><table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
                    <tr class="texto_subtitulo">
                        <td align="center">
                            <?
                                echo "El espacio academico ".$nombre." no se puede cancelar porque el semestre anterior fue reprobado";
                            ?>
                        </td>
                    </tr>
                </table>
                <?
                exit;
            }
            if ($resultado_plan[0][0]==3) {
                ?><table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
    <tr class="texto_subtitulo">
        <td align="center"><?
                            echo "El espacio academico ".$nombre." no se puede cancelar nuevamente";
                            ?>
        </td>
    </tr>
</table>
                <?
                exit;
            }

            else {
                $this->redireccionarProceso($configuracion, "verificarCreditos", $verificar);
            }

       
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
//var_dump($_REQUEST);exit;
        $variables=array($codigoEstudiante, $proyecto, $ano, $periodo, $planEstudio, $espacio, $grupo, $nombre);

        $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);//echo $cadena_sql_creditos;exit;
        $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_creditos,"busqueda");

        $totalCreditos=$this->calcularCreditos($configuracion,$resultado_creditos);

        $cadena_sql_minimo=$this->sql->cadena_sql($configuracion,"minimo_creditos", $planEstudio);
        $resultado_minimo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_minimo,"busqueda");
        $cadena_sql_creditoEspacio=$this->sql->cadena_sql($configuracion,"creditos_espacio", $espacio);
        $resultado_creditoEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditoEspacio,"busqueda");

        $creditos=$totalCreditos-$resultado_creditoEspacio[0][0];

        if ($creditos<=$resultado_minimo[0][0]) {
           
                ?><table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
    <tr class="texto_subtitulo">
        <td align="center"><?
                            echo "El espacio academico ".$nombre." no se puede cancelar porque el número de créditos mínimo es ".$resultado_minimo[0][0];
                            ?>
        </td>
    </tr>
</table>
                <?
                exit;
            
        }
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
                        Recuerde que si cancela un Espacio Acad&eacute;mico &eacute;ste no se puede volver a inscribir en el presente semestre. <br><br> ¿Est&aacute; seguro que desea cancelar <? echo $nombre ?>?
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                <input type="hidden" name="proyecto" value="<?echo $proyecto?>">
                <input type="hidden" name="codEspacio" value="<?echo $espacio?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="grupo" value="<?echo $grupo?>">
                <input type="hidden" name="ano" value="<?echo $ano?>">
                <input type="hidden" name="periodo" value="<?echo $periodo?>">
                <input type="hidden" name="nombre" value="<?echo $nombre?>">
                <input type="hidden" name="creditos" value="<?echo $resultado_creditoEspacio[0][0]?>">
                <input type="hidden" name="opcion" value="cancelarCreditos">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <tr>
                    <td align="center">
                        <input type="image" name="aceptar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30">
                    </td>
                    <td align="center">
                        <input type="image" name="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="30" height="30">
                    </td>
                </tr>

            </form>
        </td>
    </tr>
</table>
        <?
        exit;



        echo "<script>alert ('Recuerde que si cancela un Espacio Académico éste no se puede volver a inscribir en el presente semestre. <br> ¿Está seguro que desea cancelar ".$nombre."?');</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=registroCancelarCreditosEstudiante";
        $variable.="&opcion=cancelarCreditos";
        $variable.="&codEstudiante=".$codigoEstudiante;
        $variable.="&proyecto=".$proyecto;
        $variable.="&ano=".$ano;
        $variable.="&periodo=".$periodo;
        $variable.="&codEspacio=".$espacio;
        $variable.="&grupo=".$grupo;
        $variable.="&planEstudio=".$planEstudio;
        $variable.="&nombre=".$nombre;

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";


    }

    function cancelarCreditos($configuracion) {
        $codEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $ano=$_REQUEST['ano'];
        $periodo=$_REQUEST['periodo'];
        $nombre=$_REQUEST['nombre'];
        $creditos=$_REQUEST['creditos'];

                         //[0]Estudiante,[1]Proyecto,[2]año,[3]Periodo,[4]PlanEst,[5]Espacio,[6]Grupo
        $variables=array($codEstudiante,$proyecto,$ano,$periodo,$planEstudio,$espacio,$grupo,$nombre,$creditos);
        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql_buscarEspacioOracle."<br>";exit;
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variables);//echo $cadena_sql_buscarEspacioMysql;exit;
        $resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if($resultado_EspacioOracle[0][3]==$grupo)
        {
            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);
            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"" );

            $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"cancelar_espacio_mysql", $variables);
            $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );

            $variables[9]=$resultado_creditos[0][0]-$creditos;
            $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"actualizar_creditos", $variables);
            $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );
            
            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variables);
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

//            echo "<script>alert ('El Espacio Académico ".$nombre." ha sido cancelado');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminInscripcionEstudianteCoordinador";
            $variable.="&opcion=validar";
            $variable.="&codEstudiante=".$codEstudiante;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else {
            echo "<script>alert ('El Espacio Académico ".$nombre." no se puede cancelar');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminInscripcionCreditosEstudiante";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codEstudiante=".$codEstudiante;

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

        echo "<script>alert ('El Espacio Académico ".$nombre." no ha sido cancelado');</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminInscripcionCreditosEstudiante";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codEstudiante=".$codEstudiante;

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
                $variable="pagina=registroCancelarCreditosEstudiante";
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
                break;

            case "solicitar_confirmacion":
                $variable="pagina=registroCancelarCreditosEstudiante";
                $variable.="&opcion=solicitarConfirmacion";
                $variable.="&carrera=".$valor[0];
                $variable.="&planEstudio=".$valor[1];
                $variable.="&orden=".$valor[2];
                break;

            case "cancelar":
                $variable="pagina=registroCancelarCreditosEstudiante";
                $variable.="&opcion=cancelarCreditos";
                $variable.="&codEstudiante=".$valor[0];
                $variable.="&proyecto=".$valor[1];
                $variable.="&ano=".$valor[2];
                $variable.="&periodo=".$valor[3];
                $variable.="&codEspacio=".$valor[5];
                $variable.="&grupo=".$valor[6];
                $variable.="&planEstudio=".$valor[4];
                $variable.="&nombre=".$valor[7];

        }


        $variable=$cripto->codificar_url($variable,$configuracion);
        echo "<script>location.replace('".$indice.$variable."')</script>";

    }


    function calcularCreditos($configuracion,$registroGrupo)
            {
                $suma=0;
                for($i=0;$i<count($registroGrupo);$i++)
                {
                    $suma+=$registroGrupo[$i][1];
                }

                return $suma;

            }

}


?>

