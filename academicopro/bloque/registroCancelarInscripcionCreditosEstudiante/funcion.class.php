<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCancelarInscripcionCreditosEstudiante extends funcionGeneral {
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
            $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");

        //Datos de sesion
        $this->formulario="registroCancelarInscripcionCreditosEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

?>
<head>
    <script language="JavaScript">
        var message = "";
        function clickIE(){
            if (document.all){
                (message);
                return false;
            }
        }
        function clickNS(e){
            if (document.layers || (document.getElementById && !document.all)){
                if (e.which == 2 || e.which == 3){
                    (message);
                    return false;
                }
            }
        }
        if (document.layers){
            document.captureEvents(Event.MOUSEDOWN);
            document.onmousedown = clickNS;
        } else {
            document.onmouseup = clickNS;
            document.oncontextmenu = clickIE;
        }
        document.oncontextmenu = new Function("return false")
    </script>
</head>
        <?

    }

    

    function verificarCancelacionEspacio($configuracion) {
    //verifica la posibilidad de cancelar un espacio académico
        //var_dump($_REQUEST);exit;
        $banderaGrupo=$_REQUEST['banderaGrupo'];
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];//'73';//
        $codEspacio=$_REQUEST['codEspacio'];//'1238';//
        $grupo=$_REQUEST['grupo'];//'5';//        
        $planEstudio=$_REQUEST['planEstudio'];//'223';//
        $nombre=$_REQUEST['nombre'];//'Prueba';//
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        $creditosInscritos=$_REQUEST['creditosInscritos'];

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"periodo", "");//busca el periodo actual
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda");
        $verificar=array($codigoEstudiante, $proyecto, $resultado_periodo[0][0], $resultado_periodo[0][1], $codEspacio, $grupo, $planEstudio, $nombre, $creditosInscritos);
        // si es estudiante
        $cadena_sql_estado=$this->sql->cadena_sql($configuracion,"verificar_estado", $verificar);//echo $cadena_sql_estado;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_estado,"busqueda");

        $cadena_sql=$this->sql->cadena_sql($configuracion,"verificar_espacio_reprobado", $verificar);//echo $cadena_sql;exit;
        $resultado_reprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if($resultado_plan[0][0]=='2' || (is_array($resultado_reprobado))&&!is_bool($resultado_reprobado))
            {
                echo "<script>alert ('El Espacio Académico ".$nombre." no se puede cancelar porque fue reprobado el semestre anterior');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarCreditosEstudiante";
                $variable.="&opcion=mostrarConsulta";
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }else if($resultado_plan=='3')
                {
                    echo "<script>alert ('El Espacio Académico ".$nombre." no se puede cancelar, ya fue cancelado');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCreditosEstudiante";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }


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
        $creditosInscritos=$_REQUEST['creditosInscritos'];

        $variables=array($codigoEstudiante, $proyecto, $ano, $periodo, $planEstudio, $espacio, $grupo, $nombre);

        
        $cadena_sql_minimo=$this->sql->cadena_sql($configuracion,"minimo_creditos", $planEstudio);
        $resultado_minimo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_minimo,"busqueda");
        $cadena_sql_creditoEspacio=$this->sql->cadena_sql($configuracion,"creditos_espacio", $espacio);//echo $cadena_sql_creditoEspacio;
        $resultado_creditoEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditoEspacio,"busqueda");
//echo "<br>".$creditosInscritos;exit;
        $creditos=$creditosInscritos-$resultado_creditoEspacio[0][0];

        if($creditos<$resultado_minimo[0][0])
            {
                $aviso['numeroCreditos']=$creditos;
            }
        

        
        ?>
<table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
    <tr class="texto_subtitulo">
        <td colspan="2">
            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px">
                <tr class="texto_subtitulo">
                    <td  align="center">
                                <?if ($verificar==2/**||$verificar==''*/) {
                                    ?>El Espacio Acad&eacute;mico no se puede cancelar porque fue reprobado el semestre anterior.<br><br><?

                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=adminConsultarCreditosEstudiante";
                                            $variable.="&opcion=mostrarConsulta";
                                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                    ?><a href="<?echo $pagina.$variable?>">
                                        <img border="0" src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="30" height="30" alt="Regresar"><br>Regresar
                                    </a><?
                                    exit;

                                }
                                if($verificar==3) {
                                    ?>El Espacio Acad&eacute;mico que va a cancelar ya ha sido cancelado en este per&iacute;odo.<br><br><?
                                }
                                if(isset($aviso)) {
                                    ?>El Espacio Acad&eacute;mico no se puede cancelar por que el estudiante quedar&aacute; con <?echo $aviso['numeroCreditos'] ?> cr&eacute;ditos
                                    <br>
                                    No es permitido ya que el n&uacute;mero de cr&eacute;ditos minimo es <?echo $resultado_minimo[0][0]?>.<br><br><?

                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=adminConsultarCreditosEstudiante";
                                            $variable.="&opcion=mostrarConsulta";
                                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                    ?><a href="<?echo $pagina.$variable?>">
                                        <img border="0" src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="30" height="30" alt="Regresar"><br>Regresar
                                    </a><?
                                    exit;
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
              <td align="center"><?
                 
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroCancelarInscripcionCreditosEstudiante";
                        $variable.="&opcion=cancelarCreditos";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&proyecto=".$_REQUEST["proyecto"];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                        $variable.="&espacio=".$_REQUEST["codEspacio"];
                        $variable.="&grupo=".$_REQUEST["grupo"];
                        $variable.="&periodo=".$_REQUEST["periodo"];
                        $variable.="&ano=".$_REQUEST["ano"];
                        $variable.="&nombre=".$_REQUEST["nombre"];
                        $variable.="&creditos=".$_REQUEST["creditos"];
                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);
                 ////echo $codProyecto."-".$codigoEstudiante."-".$planEstudioGeneral?>
                        <a href="<?echo $pagina.$variable?>">
                                <img border="0" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" alt="Confirmar">
                        </a>
                    </td>
                    <td align="center">
                        <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCreditosEstudiante";
                            $variable.="&opcion=mostrarConsulta";
                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
                            ?><a href="<?echo $pagina.$variable?>">
                                <img border="0" src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="30" height="30" alt="Cancelar">
                            </a>
                    </td>
                </tr>

            
        </td>
    </tr>
</table>
        <?    
    }

    function cancelarCreditos($configuracion) {

        //echo "estoy aca";exit;
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

        $variables=array($codEstudiante,$proyecto,$ano,$periodo,$planEstudio,$espacio,$grupo,$nombre,$creditos);
        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql_buscarEspacioOracle;exit;
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variables);//echo $cadena_sql_buscarEspacioMysql;exit;
        $resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if($resultado_EspacioOracle[0][3]==$grupo)
        {
            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);
            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);//echo $cadena_sql_horario_registrado;exit;
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
            
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarCreditosEstudiante";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
           /* $variable.="&proyecto=".$proyecto;
            $variable.="&espacio=".$espacio;
            $variable.="&grupo=".$grupo;
            $variable.="&ano=".$ano;
            $variable.="&periodo=".$periodo;
            $variable.="&nombre=".$nombre;
            $variable.="&creditos=".$creditos;*/

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else{
            echo "<script>alert ('La base de datos se encuentra ocupada. El Espacio Académico ".$nombre." no ha sido cancelado');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarCreditosEstudiante";
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
       
       // var_dump($_REQUEST);exit;
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
        
        echo "<script>alert ('El Espacio Académico ".$nombre.$codEstudiante." no ha sido cancelado');</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarCreditosEstudiante";
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
                $variable="pagina=registroCancelarInscripcionCreditosEstudiante";
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
                $variable.="&creditosInscritos=".$valor[8];
                break;

            case "solicitar_confirmacion":
                $variable="pagina=registroCancelarInscripcionCreditosEstudiante";
                $variable.="&opcion=solicitarConfirmacion";
                $variable.="&carrera=".$valor[0];
                $variable.="&planEstudio=".$valor[1];
                $variable.="&orden=".$valor[2];
                break;

            case "cancelar":
                $variable="pagina=registroCancelarInscripcionCreditosEstudiante";
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


}


?>

