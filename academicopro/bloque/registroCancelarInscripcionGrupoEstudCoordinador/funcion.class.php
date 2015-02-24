<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCancelarInscripcionGrupoEstudCoordinador extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacionInscripcion.class.php");
        

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
        $this->formulario="registroCancelarInscripcionGrupoEstudCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->verificacion=new validacionInscripcion();
        

    }

    function verificaCancelacionEstudiante($configuracion) {

        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $nombreEstudiante=$_REQUEST['nombreEstudiante'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];//'73';//
        $proyecto=$_REQUEST['proyecto'];//'73';//
        $espacio=$_REQUEST['codEspacio'];//'1238';//
        $grupo=$_REQUEST['nroGrupo'];//'5';//
        $nroCreditosGrupo=$_REQUEST['nroCreditos'];
        $clasificacion=$_REQUEST['clasificacion'];
        $verifica=$_REQUEST['verifica'];

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"periodo", "");//echo $cadena_sql_periodo;exit;
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda");
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];
        $nroEstudiante=0;
        $cancelaEstudiante=$codigoEstudiante."-".$proyecto."-".$espacio."-".$grupo."-".$ano."-".$periodo."-".$nroCreditosGrupo."-".$nombreEspacio.-"".$nroEstudiante;
        $variablesEstudiante=array($codigoEstudiante, $ano, $periodo, $espacio, $grupo, $nroEstudiante);

        $retorno['pagina']='adminConsultarInscripcionGrupoCoordinador';
        $retorno['opcion']='verGrupo';
        $retorno['opcion2']='cuadroRegistro';
        $retorno['parametros']='&codEspacio='.$espacio;
        $retorno['parametros'].='&nombreEspacio='.$nombreEspacio;
        $retorno['parametros'].='&nroCreditos='.$nroCreditosGrupo;
        $retorno['parametros'].='&nroGrupo='.$grupo;
        $retorno['parametros'].='&planEstudio='.$planEstudio;
        $retorno['parametros'].='&codProyecto='.$proyecto;
        $retorno['parametros'].='&clasificacion='.$clasificacion;

        $continuar['pagina']='registroCancelarInscripcionGrupoEstudCoordinador';
        $continuar['opcion']='cancelarEspacio';
        $continuar['parametros']="&cancelaEstudiante=".$cancelaEstudiante;
        $continuar['parametros'].="&planEstudio=".$planEstudio;
        //echo $verifica;exit;
        if($verifica==2||$verifica==6)
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
        else
        {
            $this->verificacion->validarCancelarPrueba($configuracion, $codigoEstudiante, $proyecto, $espacio, $planEstudio, $retorno, $continuar);
        }
    }

    function verificaCancelacionEstudianteXGrupo($configuracion) {

        $totalSeleccionados=0;
        $total=$_REQUEST['total']+1;
        $valorCancelado==0;
        $valorNoCancelado==0;

        for($i=0;$i<$total;$i++) {
            $codigo['codEstudiante-'.$i]=$_REQUEST['codEstudiante-'.$i];
            if($codigo['codEstudiante-'.$i]!=NULL) {
                $totalSeleccionados++;
            }
        }

        $espacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $grupo=$_REQUEST['nroGrupo'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $creditos=$_REQUEST['nroCreditos'];
        $proyecto=$_REQUEST['codProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        //var_dump($_REQUEST);exit;
        //$nombreEstudiante=$_REQUEST['nombreEstudiante'];

        if($totalSeleccionados==0) {
            echo "<script>alert('Por favor seleccione los estudiantes a los que desea cancelar el espacio académico')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
            $variable.="&opcion=verGrupo";
            $variable.="&opcion2=cuadroRegistro";
            $variable.="&codEspacio=".$espacio;
            $variable.="&nroGrupo=".$grupo;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;
            $variable.="&nombreEspacio=".$nombreEspacio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            exit;
        }

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"periodo", "");//echo $cadena_sql_periodo;exit;
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda");
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $valor=0;
        $valorCan=0;

        for($e=0;$e<$total;$e++) {
            $band=0;
            $variableCodigo=$codigo['codEstudiante-'.$e];

            if($variableCodigo[0]!=NULL) {
                $variableCodigo;
                $nroEstudiante=$e;
                $variablesEstudiante=array($variableCodigo, $ano, $periodo, $espacio, $grupo, $nroEstudiante);

                $resultadoVerificar= $this->verificaCancelacion($configuracion, $variablesEstudiante);
                $resultadoVerificar=explode('-',$resultadoVerificar);
                $verifica=$resultadoVerificar[0];

               $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio", $espacio);//echo $cadena_sql;exit;
               $resultado_espacioDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

               $cadena_sql_pertenecePlan=$this->sql->cadena_sql($configuracion,"pertenecePlanEstudio", $variableCodigo);
               $resultado_pertenecePlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_pertenecePlan,"busqueda" );

               $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_reprobado", $variablesEstudiante);//echo $cadena_sql;exit;
               $resultado_reprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

               if($resultado_pertenecePlan[0][0]!=$planEstudio)
               {
                       $verifica='5';
               }
               elseif (is_array($resultado_reprobado))
                  {
                       if((trim($resultado_reprobado[0][1])=='B' || trim($resultado_reprobado[0][1])=='J'))
                       {
                          $verifica='6';
                       }
                       else
                         {
                            $verifica="2";
                         }
                 }
              if($verifica=='')
              {
                  $verifica='4';
              }
                switch ($verifica) {

                    case "5":
                        $estudianteNoCancelado[$valor]=($variableCodigo.'-5');
                        $valor=$valor+1;
                        break;

                    case "4":
                    //echo $variableCodigo."entro al 4 <br>";
                        $variables=array($variableCodigo,$proyecto,$ano,$periodo,$planEstudio,$espacio,$grupo,$creditos);
                        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo "<br>".$cadena_sql_buscarEspacioOracle;//exit;
                        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

                        //$cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variables);//echo $cadena_sql_buscarEspacioMysql;exit;
                        //$resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

                        //if($resultado_EspacioMysql[0][6]==$grupo and $resultado_EspacioOracle[0][3]==$grupo) {
                        if($resultado_EspacioOracle[0][3]==$grupo) {
                            //echo $variableCodigo."entro al si 4 <br>";
                            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);//echo $cadena_sql_creditos;exit;
                            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");
                            $creditosEstudiante=$resultado_creditos[0][0]-$creditos;
                                                        
                            $variablesCreditos=array($creditosEstudiante, $variableCodigo, $ano, $periodo);                           
                            $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"actualizar_creditos", $variablesCreditos);//echo $cadena_sql_actualizarCreditos;exit;
                            $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );

                            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);
                            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"" );

                            $cadena_sql_cancelarMysql=$this->sql->cadena_sql($configuracion,"cancelar_espacio_mysql", $variables);//echo $cadena_sql_actualizarCreditos;exit;
                            $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_cancelarMysql,"" );

                            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variables);
                            $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                            $variables[10]=(($resultado_cupoInscritos[0][0])-1);

                            $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
                            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                            $variablesRegistro=array($this->usuario,date('YmdGis'),'2','Cancela Espacio académico',$ano.", ".$periodo.", ".$espacio.", ".$grupo.", 0, ".$planEstudio.", ".$proyecto, $variableCodigo);
                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $estudianteCancelado[$valorCan]=($variableCodigo.'-4');
                            $valorCan=$valorCan+1;
                        }
                        else {
                            //echo $variableCodigo."entro al no del 4 <br>";
                            $estudianteNoCancelado[$valor]=($variableCodigo.'-1');
                            $valor=$valor+1;
                            $variablesRegistro=array($this->usuario,date('YmdGis'),'33','No pudo cancelar, problemas estudiante',$ano.", ".$periodo.", ".$espacio.", ".$grupo.", 0, ".$planEstudio.", ".$proyecto, $variableCodigo);
                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                        }
                        break;

                    case "3":
                    //echo $variableCodigo."entro al 3 <br>";
                        $estudianteNoCancelado[$valor]=($variableCodigo.'-3');
                        $valor=$valor+1;
                        break;

                    case "2":
                    //echo $variableCodigo."entro al 2 <br>";
                        $estudianteNoCancelado[$valor]=($variableCodigo.'-2');
                        $valor=$valor+1;
                        break;
                    case "6":
                    //echo $variableCodigo."entro al 2 <br>";
                        $estudianteNoCancelado[$valor]=($variableCodigo.'-6');
                        $valor=$valor+1;
                        break;

                    // default:
                    //   break;
                }

                //  }

            }
        }


        ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>

    <table class="contenidotabla centrar" border="0">
    <tr class="cuadro_brownOscuro centrar">
            <td colspan="7"><b><font size="2">
                        <?                        
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                        $variable.="&opcion=verGrupo";
                        $variable.="&opcion2=cuadroRegistro";
                        $variable.="&codEspacio=".$espacio;
                        $variable.="&nroGrupo=".$grupo;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&codProyecto=".$proyecto;
                        $variable.="&nombreEspacio=".$nombreEspacio;
                        $variable.="&nroCreditos=".$creditos;
                        $variable.="&clasificacion=".$clasificacion;

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        ?>
                <a href="<?= $pagina.$variable ?>" >
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
                </a>
            </td>

        </tr>        
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="7"><b><font size="2">INFORME DE CANCELACI&Oacute;N DE INSCRIPCI&Oacute;N</font></b></td>
        </tr>
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="7"><b><font size="2"><?echo $espacio."-".$nombreEspacio ?></font></b></td>
        </tr>
                <? if($valor!=0) { ?>
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="7"><b><font size="2">Para los siguientes estudiantes no se realizó la cancelaci&oacute;n</font></b></td>
        </tr>
        <tr class="cuadro_brownOscuro centrar">
            <td width="10%" >C&oacute;digo</td>
            <td width="30%" >Nombre</td>
            <td width="30%" >Proyecto Curricular</td>
            <td width="20%" >Descripción</td>
            <td width="10%" >Cancelar</td>
        </tr>
                    <?
                    for($i=0;$i<$valor;$i++) {
                        if($estudianteNoCancelado[$i]) {
                            $resultadoNoCancelado=explode('-',$estudianteNoCancelado[$i]);
                            $codEstudiante=$resultadoNoCancelado[0];
                            $motivo=$resultadoNoCancelado[1];
                            $noCancelados=array($espacio, $grupo, $codEstudiante);
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesNoCancelados", $noCancelados);//echo $cadena_sql;exit;
                            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            ?><tr class="cuadro_planoPequeño">
            <td class="centrar"><?echo $codEstudiante?></td>
            <td class="izquierda"><?echo htmlentities($resultado_estudiante[0][1])?></td>
            <td class="centrar"><?echo htmlentities($resultado_estudiante[0][2])?></td>
            <td class="centrar"><?
                    switch ($motivo) {
                                        case '1':
                                            echo "Inconsistencia registro estudiante";
                                            $verifica=1;
                                            break;
                                        case '2':
                                            echo "Reprobo Espacio el semestre anterior";
                                            $verifica=2;
                                            break;
                                        case '3':
                                            echo "Espacio ya cancelado en el semestre en curso";
                                            $verifica=3;
                                            break;
                                        case '5':
                                            echo "No pertenece al Plan de Estudios ".$planEstudio;
                                            break;
                                        case '6':
                                            echo "<font color='red'>Estudiante en Prueba Academica</font>";
                                            $verifica=6;
                                            break;
                                    }                                
                                    ?></td><?
                                if($motivo==5)
                                {
                                 ?><td class="centrar"></td><?
                                }
                                else {
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=registroCancelarInscripcionGrupoEstudCoordinador";
                                $variable.="&opcion=verificaEstudiante";
                                $variable.="&codEstudiante=".$codEstudiante;
                                $variable.="&nombreEstudiante=".htmlentities($resultado_estudiante[0][1]);
                                $variable.="&planEstudio=".$planEstudio;
                                $variable.="&proyecto=".$proyecto;
                                $variable.="&codEspacio=".$espacio;
                                $variable.="&nroGrupo=".$grupo;
                                $variable.="&nroCreditosGrupo=".$creditos;
                                $variable.="&nombreEspacio=".$nombreEspacio;
                                $variable.="&verifica=".$verifica;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                ?> <td class="centrar">
                <a href="<?echo $pagina.$variable?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="20" height="20" border="0" alt="Cancelar">
                </a>
            </td>
                        <? } ?>
        </tr>
                    <?

                        }
                    }
                }
                ?>
                <? if($valorCan!=0) { ?>
        <tr class="cuadro_brownOscuro centrar">
            <td colspan="7"><b><font size="2">Cancelaciones exitosas</font></b></td>
        </tr>
        <tr class="cuadro_brownOscuro centrar">
            <td width="10%" >C&oacute;digo</td>
            <td width="35%" >Nombre</td>
            <td width="35%" colspan="3">Proyecto Curricular</td>
        </tr>
            <?
            for($i=0;$i<$valorCan;$i++) {
                        if($estudianteCancelado[$i]) {
                            $resultadoCancelado=explode('-',$estudianteCancelado[$i]);
                            $codEstudiante=$resultadoCancelado[0];
                            $motivo=$resultadoCancelado[1];
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesCancelados", $codEstudiante);//echo $cadena_sql;exit;
                            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            ?><tr class="cuadro_planoPequeño">
            <td class="centrar"><?echo $codEstudiante?></td>
            <td class="izquierda"><?echo htmlentities($resultado_estudiante[0][1])?></td>
            <td class="centrar"><?echo htmlentities($resultado_estudiante[0][2])?></td>
        </tr>
                    <?

                }
            }
                }
                ?>
    </table>
</form>
                <?
            }

    function verificaCancelacion($configuracion, $variablesEstudiante) {

        for($i=0;$i<=$variablesEstudiante[4];$i++) {
            $codigoEstudiante=$variablesEstudiante[0];
            $ano=$variablesEstudiante[1];
            $periodo=$variablesEstudiante[2];
            $codEspacio=$variablesEstudiante[3];
            $nroGrupo=$variablesEstudiante[4];            
            
            $verificar=array($codigoEstudiante, $ano, $periodo, $codEspacio, $nroGrupo);
            $cadena_sql_estado=$this->sql->cadena_sql($configuracion,"verificar_estado", $verificar); //echo $cadena_sql_estado;exit;
            $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_estado,"busqueda");
            $resultadoVerificar=$resultado_plan[0][0]."-".$i;

            return ($resultadoVerificar);
        }

    }

    function cancelacionEspacioEstudiante($configuracion) {

        $resultadoEstudiante=explode('-',$_REQUEST['cancelaEstudiante']);
        $planEstudio=$_REQUEST['planEstudio'];
        $codEstudiante=$resultadoEstudiante[0];
        $proyecto=$resultadoEstudiante[1];
        $espacio=$resultadoEstudiante[2];
        $grupo=$resultadoEstudiante[3];
        $ano=$resultadoEstudiante[4];
        $periodo=$resultadoEstudiante[5];
        $creditos=$resultadoEstudiante[6];
        $nombre=$resultadoEstudiante[7];
        $nroEstudiante=$resultadoEstudiante[8];

        $variables=array($codEstudiante,$proyecto,$ano,$periodo,$planEstudio,$espacio,$grupo,$creditos);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio", $espacio);//echo $cadena_sql;exit;
        $resultado_EspacioDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql;exit;
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
       
        //$cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variables);//echo $cadena_sql_buscarEspacioMysql;exit;
        //$resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

     $cadena_sql_pertenecePlan=$this->sql->cadena_sql($configuracion,"pertenecePlanEstudio", $codEstudiante);
     $resultado_pertenecePlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_pertenecePlan,"busqueda" );
     if($resultado_pertenecePlan[0][0]==$planEstudio)
       {
        //if($resultado_EspacioMysql[0][6]==$grupo and $resultado_EspacioOracle[0][3]==$grupo) {
        if($resultado_EspacioOracle[0][3]==$grupo) {
          
            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);//echo $cadena_sql_creditos;exit;
            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");
            $creditosEstudiante=$resultado_creditos[0][0]-$creditos;

            $variablesCreditos=array($creditosEstudiante, $variableCodigo, $ano, $periodo);
            $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"actualizar_creditos", $variablesCreditos);//echo $cadena_sql_actualizarCreditos;exit;
            $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );


            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"" );

            $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"cancelar_espacio_mysql", $variables);
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

            echo "<script>alert ('El Espacio Académico fue cancelado. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";

            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
            $variable.="&opcion=verGrupo";
            $variable.="&opcion2=cuadroRegistro";
            $variable.="&codEspacio=".$espacio;
            $variable.="&nombreEspacio=".$nombre;
            $variable.="&nroCreditos=".$creditos;
            $variable.="&nroGrupo=".$grupo;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;
            $variable.="&clasificacion=".$resultado_EspacioDesc[0][7];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            exit;
              
        }
        else {

            echo "<script>alert ('La base de datos se encuentra ocupada. El Espacio Académico ".$nombre." no ha sido cancelado');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
            $variable.="&opcion=verGrupo";
            $variable.="&opcion2=cuadroRegistro";
            $variable.="&codEspacio=".$espacio;
            $variable.="&nombreEspacio=".$nombre;
            $variable.="&nroCreditos=".$creditos;
            $variable.="&nroGrupo=".$grupo;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;
            $variable.="&clasificacion=".$resultado_EspacioDesc[0][7];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            exit;
            }
         }
        else {
                if($resultado_EspacioDesc[0][7]==4)
                {
                    if($resultado_EspacioOracle[0][3]==$grupo) {

                    $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);//echo $cadena_sql_creditos;exit;
                    $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");
                    $creditosEstudiante=$resultado_creditos[0][0]-$creditos;

                    $variablesCreditos=array($creditosEstudiante, $variableCodigo, $ano, $periodo);
                    $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"actualizar_creditos", $variablesCreditos);//echo $cadena_sql_actualizarCreditos;exit;
                    $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );


                    $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);
                    $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"" );

                    $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"cancelar_espacio_mysql", $variables);
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

                    echo "<script>alert ('El Espacio Académico fue cancelado. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$espacio;
                    $variable.="&nombreEspacio=".$nombre;
                    $variable.="&nroCreditos=".$creditos;
                    $variable.="&nroGrupo=".$grupo;
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&codProyecto=".$proyecto;
                    $variable.="&clasificacion=".$resultado_EspacioDesc[0][7];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                    } else {

                            echo "<script>alert ('La base de datos se encuentra ocupada. El Espacio Académico ".$nombre." no ha sido cancelado');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$espacio;
                            $variable.="&nombreEspacio=".$nombre;
                            $variable.="&nroCreditos=".$creditos;
                            $variable.="&nroGrupo=".$grupo;
                            $variable.="&planEstudio=".$planEstudio;
                            $variable.="&codProyecto=".$proyecto;
                            $variable.="&clasificacion=".$resultado_EspacioDesc[0][7];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                            }
                }else
                    {
                        echo "<script>alert ('El estudiante no pertenece al plan de estudios ".$planEstudio.". El espacio académico no ha sido cancelado');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                        $variable.="&opcion=verGrupo";
                        $variable.="&opcion2=cuadroRegistro";
                        $variable.="&codEspacio=".$espacio;
                        $variable.="&nombreEspacio=".$nombre;
                        $variable.="&nroCreditos=".$creditos;
                        $variable.="&nroGrupo=".$grupo;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&codProyecto=".$proyecto;
                        $variable.="&clasificacion=".$resultado_EspacioDesc[0][7];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
                

                }
    }

    function cancelaSolicitud($configuracion) {

        $resultadoEstudiante=explode('-',$_REQUEST['cancelaEstudiante']);
        $planEstudio=$_REQUEST['planEstudio'];
        $codEstudiante=$resultadoEstudiante[0];
        $proyecto=$resultadoEstudiante[1];
        $espacio=$resultadoEstudiante[2];
        $grupo=$resultadoEstudiante[3];
        $ano=$resultadoEstudiante[4];
        $periodo=$resultadoEstudiante[5];
        $creditos=$resultadoEstudiante[6];
        $nombre=$resultadoEstudiante[7];
        $nroEstudiante=$resultadoEstudiante[8];

        echo "<script>alert ('La solicitud fue cancelada por el usuario');</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
        $variable.="&opcion=verGrupo";
        $variable.="&opcion2=cuadroRegistro";
        $variable.="&codEspacio=".$espacio;
        $variable.="&nombreEspacio=".$nombre;
        $variable.="&nroCreditos=".$creditos;
        $variable.="&nroGrupo=".$grupo;
        $variable.="&planEstudio=".$planEstudio;
        $variable.="&codProyecto=".$proyecto;

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;

    }

    function solicitarConfirmacion($configuracion)
    {

        $totalSeleccionados=0;
        $total=$_REQUEST['total']+1;
        $valorCancelado==0;
        $valorNoCancelado==0;

        for($i=0;$i<$total;$i++) {
            $codigo['codEstudiante-'.$i]=$_REQUEST['codEstudiante-'.$i];
            if($codigo['codEstudiante-'.$i]!=NULL) {
                $totalSeleccionados++;
            }
        }
//var_dump($_REQUEST);exit;
        $espacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $grupo=$_REQUEST['nroGrupo'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $creditos=$_REQUEST['nroCreditos'];
        $proyecto=$_REQUEST['proyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        //$nombreEstudiante=$_REQUEST['nombreEstudiante'];

        if($totalSeleccionados==0) {
            echo "<script>alert('Por favor seleccione los estudiantes a los que desea cancelar')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
            $variable.="&opcion=verGrupo";
            $variable.="&opcion2=cuadroRegistro";
            $variable.="&codEspacio=".$espacio;
            $variable.="&nroGrupo=".$grupo;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;
            $variable.="&nombreEspacio=".$nombreEspacio;
            $variable.="&total=".$total;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            exit;
        }
        ?>
<table class="contenidotabla centrar">
    <tr>
        <td class="centrar" colspan="2">
            SISTEMA DE GESTION ACADEMICA
        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="2">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png" border="0" alt="LogoU">
            <hr>
        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="2">
            <font size="2">¿Esta seguro que desea cancelar el espacio acad&eacute;mico a los <?echo $totalSeleccionados?> estudiantes seleccionados?</font>
        </td>
    </tr>
    <tr>
        <td class="centrar">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroCancelarInscripcionGrupoEstudCoordinador";
            $variable.="&opcion=variosEstudiantes";
            $variable.="&opcion2=cuadroRegistro";
            $variable.="&codEspacio=".$espacio;
            $variable.="&nroGrupo=".$grupo;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;
            $variable.="&nombreEspacio=".$nombreEspacio;
            $variable.="&nroCreditos=".$creditos;
            $variable.="&total=".$total;
            $variable.="&clasificacion=".$clasificacion;

            for($i=0;$i<$total;$i++)
                {
                    $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];
                }

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            ?>
            <a href="<?echo $pagina.$variable?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0" alt="Si"><br>Si
            </a>
        </td>
        <td class="centrar">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
            $variable.="&opcion=verGrupo";
            $variable.="&opcion2=cuadroRegistro";
            $variable.="&codEspacio=".$espacio;
            $variable.="&nroGrupo=".$grupo;
            $variable.="&planEstudio=".$planEstudio;
            $variable.="&codProyecto=".$proyecto;
            $variable.="&nombreEspacio=".$nombreEspacio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            ?>
            <a href="<?echo $pagina.$variable?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="30" height="30" border="0" alt="Cancelar"><br>No
            </a>
        </td>
    </tr>
</table>
        <?
    }


}


?>

