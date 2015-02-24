<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroInscripcionAgilEstudianteCoordinador extends funcionGeneral {
    
    function __construct($configuracion, $sql) {
    
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;
        

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroInscripcionAgilEstudianteCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function validaciones($configuracion)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacionInscripcion.class.php");
        $this->validacion=new validacionInscripcion();
        //var_dump($_REQUEST);exit;
        $codEstudiante=$_REQUEST['codEstudiante'];
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $codEspacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['grupo'];
        $variablesClasificacion=array(codEspacio=>$_REQUEST['codEspacio'], codEstudiante=>$_REQUEST['codEstudiante']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,'clasificacionEspacio',$variablesClasificacion);
        $clasificacionEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        if (is_array($clasificacionEspacio)&&is_numeric($clasificacionEspacio[0][0]))
        {
            $clasificacion=$clasificacionEspacio[0][0];
        }
        else
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,'clasificacionExtrinseco',$variablesClasificacion);
            $clasificacionExtrinseco=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            if(is_array($clasificacionExtrinseco)&&is_numeric($clasificacionExtrinseco[0][0]))
            {
                $clasificacion=$clasificacionExtrinseco[0][0];
            }
            else
                {
                    echo "No se pudo obtener la clasificaci&oacute;n del espacio";exit;;
                }
        }
        $retorno['pagina']="adminConsultarInscripcionEstudianteCoordinador";
        $retorno['opcion']="mostrarConsulta";
        $retorno['parametros']="&codEstudiante=".$codEstudiante."&planEstudioGeneral=".$_REQUEST['planEstudioGeneral']."&codProyecto=".$codProyecto;

        $continuar['pagina']="registroInscripcionAgilEstudianteCoordinador";
        $continuar['opcion']="ejecutarValidaciones";
        $continuar['parametros']="&codEspacio=".$codEspacio."&codEstudiante=".$codEstudiante."&planEstudio=".$planEstudio."&codProyecto=".$codProyecto."&grupo=".$grupo."&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];


        if(!isset($_REQUEST['validacionCancelado']))
            {   
                $retornarEspacioCancelado=$this->validacion->validarEspacioCancelado($configuracion,$codEstudiante,$codProyecto,$codEspacio,$retorno,$continuar);
            }

        if(isset($_REQUEST['validacionCancelado']) && !isset($_REQUEST['validacionRequisitos']))
            {
                $retornarEspacioRequisitos=$this->validacion->validarRequisitos($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno,$continuar);
            }

        if(isset($_REQUEST['validacionCancelado']) && isset($_REQUEST['validacionRequisitos']) && !isset($_REQUEST['validacionCupo']))
            {
                $retornarEspacioCupo=$this->validacion->validarCupoEspacio($configuracion,$codEstudiante,$codProyecto,$codEspacio,$grupo,$retorno,$continuar);
            }

        if(isset($_REQUEST['validacionCancelado']) && isset($_REQUEST['validacionRequisitos']) && isset($_REQUEST['validacionCupo']))
            {
                $retornarCruce=$this->validacion->validarCruce($configuracion,$codEstudiante,$codEspacio,$grupo,$retorno);

                $retornarAprobado=$this->validacion->validarAprobado($configuracion,$codEstudiante,$codEspacio,$codProyecto,$retorno);
//*PRUEBA ACADEMICA*
                //$retornarPrueba=$this->validacion->validarPruebaAcademica($configuracion,$codEstudiante,$codProyecto,$codEspacio,$planEstudio,$retorno);

                $retornarEspacioInscrito=$this->validacion->validarEspacioInscrito($configuracion,$codEstudiante,$codProyecto,$codEspacio,$retorno);

                //$retornarEspacioRangos=$this->validacion->validarRangos($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno);
                $retornarEspacioRangos=  $this->validacion->verificarRangos($configuracion, $planEstudio, $codEspacio, $codEstudiante, $clasificacion, $retorno);

                $retornarEspacioPlan=$this->validacion->validarEspacioPlan($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno);
                
                $retornarEspacioCreditos=$this->validacion->validarCreditosPeriodo($configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno);

                /**
                 *
                 * Si todas las validaciones se hacen correctamente
                 * se procede a realizar la inscripcion
                 */

                $this->registrarEstudiante($configuracion,$codEstudiante,$codEspacio,$planEstudio,$codProyecto,$grupo,$retorno);
            }

        
    }


    function registrarEstudiante($configuracion,$codEstudiante,$codEspacio,$planEstudio,$codProyecto,$grupo,$retorno)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", "");//echo $cadena_sql_periodo;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $var_inscripcion=array($codEspacio,$planEstudio);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_planEstudio",$var_inscripcion);//echo $cadena_sql;exit;
            $resultado_espacio_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacio_planEstudio)){
              $variables[0]=$codEstudiante;
              $variables[1]=$grupo;
              $variables[2]=$codEspacio;
              $variables[3]=$codProyecto;
              $variables[4]=$resultado_periodo[0][0];
              $variables[5]=$resultado_periodo[0][1];
              $variables[6]=$planEstudio;
              $variables[7]=$resultado_espacio_planEstudio[0][0];//Creditos E.A.
              $variables[8]=$resultado_espacio_planEstudio[0][1];//H.T.D
              $variables[9]=$resultado_espacio_planEstudio[0][2];//H.T.C
              $variables[10]=$resultado_espacio_planEstudio[0][3];//H.T.A
              $variables[11]=$resultado_espacio_planEstudio[0][4];//Clasificacion
            }
            else
              {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_otroPlanEstudio",$var_inscripcion);//echo $cadena_sql;exit;
                $resultado_espacio_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                $variables[0]=$codEstudiante;
                $variables[1]=$grupo;
                $variables[2]=$codEspacio;
                $variables[3]=$codProyecto;
                $variables[4]=$resultado_periodo[0][0];
                $variables[5]=$resultado_periodo[0][1];
                $variables[6]=$planEstudio;
                $variables[7]=$resultado_espacio_planEstudio[0][0];//Creditos E.A.
                $variables[8]=$resultado_espacio_planEstudio[0][1];//H.T.D
                $variables[9]=$resultado_espacio_planEstudio[0][2];//H.T.C
                $variables[10]=$resultado_espacio_planEstudio[0][3];//H.T.A
                $variables[11]=$resultado_espacio_planEstudio[0][4];//Clasificacion
              }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"adicionar_espacio_mysql", $variables);
            $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
            
            if($resultado_adicionarMysql==true)
                {
                    $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,"adicionar_espacio_oracle", $variables);
                    $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );

                    if($resultado_adicionar==true)
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
                            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                            $variablesRegistro[0]=$this->usuario;
                            $variablesRegistro[1]=date('YmdGis');
                            $variablesRegistro[2]='1';
                            $variablesRegistro[3]='Adiciona Espacio académico';
                            $variablesRegistro[4]=$resultado_periodo[0][0]."-".$resultado_periodo[0][1].",".$codEspacio.",0,".$grupo.",".$planEstudio.",".$codProyecto;
                            $variablesRegistro[5]=$codEstudiante;

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                            echo "<script>alert ('El espacio académico fue adicionado exitosamente. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
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
                            echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variables);
                                $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                $variablesRegistro[0]=$this->usuario;
                                $variablesRegistro[1]=date('YmdGis');
                                $variablesRegistro[2]='50';
                                $variablesRegistro[3]='Conexion Error Oracle';
                                $variablesRegistro[4]=$resultado_periodo[0][0]."-".$resultado_periodo[0][1].",".$codEspacio.",0,".$grupo.",".$planEstudio.",".$codProyecto;
                                $variablesRegistro[5]=$codEstudiante;
                                
                                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

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
                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variables);
                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                        $variablesRegistro[0]=$this->usuario;
                        $variablesRegistro[1]=date('YmdGis');
                        $variablesRegistro[2]='51';
                        $variablesRegistro[3]='Conexion Error MySQL';
                        $variablesRegistro[4]=$resultado_periodo[0][0]."-".$resultado_periodo[0][1].",".$codEspacio.",0,".$grupo.",".$planEstudio.",".$codProyecto;
                        $variablesRegistro[5]=$codEstudiante;

                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

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

?>