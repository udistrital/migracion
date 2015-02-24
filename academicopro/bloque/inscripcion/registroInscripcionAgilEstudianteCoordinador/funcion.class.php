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
    private $configuracion;
            function __construct($configuracion, $sql) {
    
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;
        

       

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroInscripcionAgilEstudianteCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->configuracion=$configuracion;
        
        //Conexion ORACLE
        //
        if($this->nivel==4){
        	$this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        }elseif($this->nivel==110){
        	$this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }

    }

    function validaciones()
    {
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/validacionInscripcion.class.php");
        $this->validacion=new validacionInscripcion();
        //var_dump($_REQUEST);exit;
        $codEstudiante=$_REQUEST['codEstudiante'];
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $codEspacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['id_grupo'];
        $hor_alternativo=$_REQUEST['hor_alternativo'];
        $variablesClasificacion=array('codEspacio'=>$_REQUEST['codEspacio'], 'codEstudiante'=>$_REQUEST['codEstudiante']);
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,'clasificacionEspacio',$variablesClasificacion);
        $clasificacionEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        if (is_array($clasificacionEspacio)&&is_numeric($clasificacionEspacio[0][0]))
        {
            $clasificacion=$clasificacionEspacio[0][0];
        }
        else
        {
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,'clasificacionExtrinseco',$variablesClasificacion);
            $clasificacionExtrinseco=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
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
        $retorno['parametros']="&codEstudiante=".$codEstudiante."&planEstudioGeneral=".$_REQUEST['planEstudioGeneral']."&codProyecto=".$codProyecto."&planEstudio=".$planEstudio;

        $continuar['pagina']="registroInscripcionAgilEstudianteCoordinador";
        $continuar['opcion']="ejecutarValidaciones";
        $continuar['parametros']="&codEspacio=".$codEspacio."&codEstudiante=".$codEstudiante."&planEstudio=".$planEstudio."&codProyecto=".$codProyecto."&id_grupo=".$grupo."&hor_alternativo=".$hor_alternativo."&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];


        if(!isset($_REQUEST['validacionCancelado']))
            {   
                $retornarEspacioCancelado=$this->validacion->validarEspacioCancelado($this->configuracion,$codEstudiante,$codProyecto,$codEspacio,$retorno,$continuar);
            }

        if(isset($_REQUEST['validacionCancelado']) && !isset($_REQUEST['validacionRequisitos']))
            {
                $retornarEspacioRequisitos=$this->validacion->validarRequisitos($this->configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno,$continuar);
            }

        if(isset($_REQUEST['validacionCancelado']) && isset($_REQUEST['validacionRequisitos']) && !isset($_REQUEST['validacionCupo']))
            {
                $retornarEspacioCupo=$this->validacion->validarCupoEspacio($this->configuracion,$codEstudiante,$codProyecto,$codEspacio,$grupo,$retorno,$continuar);
            }

        if(isset($_REQUEST['validacionCancelado']) && isset($_REQUEST['validacionRequisitos']) && isset($_REQUEST['validacionCupo']))
            {
                $retornarCruce=$this->validacion->validarCruce($this->configuracion,$codEstudiante,$codEspacio,$grupo,$hor_alternativo,$retorno);

                $retornarAprobado=$this->validacion->validarAprobado($this->configuracion,$codEstudiante,$codEspacio,$codProyecto,$retorno);
//*PRUEBA ACADEMICA*
                //$retornarPrueba=$this->validacion->validarPruebaAcademica($this->configuracion,$codEstudiante,$codProyecto,$codEspacio,$planEstudio,$retorno);

                $retornarEspacioInscrito=$this->validacion->validarEspacioInscrito($this->configuracion,$codEstudiante,$codProyecto,$codEspacio,$retorno);

                //$retornarEspacioRangos=$this->validacion->validarRangos($this->configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno);
                $retornarEspacioRangos=  $this->validacion->verificarRangos($this->configuracion, $planEstudio, $codEspacio, $codEstudiante, $clasificacion, $retorno);

                $retornarEspacioPlan=$this->validacion->validarEspacioPlan($this->configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno);
                
                $retornarEspacioCreditos=$this->validacion->validarCreditosPeriodo($this->configuracion,$codEstudiante,$planEstudio,$codEspacio,$retorno);
                
                
                

                /**
                 *
                 * Si todas las validaciones se hacen correctamente
                 * se procede a realizar la inscripcion
                 */

                $this->registrarEstudiante($codEstudiante,$codEspacio,$planEstudio,$codProyecto,$grupo,$hor_alternativo,$retorno);
            }

        
    }


    function registrarEstudiante($codEstudiante,$codEspacio,$planEstudio,$codProyecto,$grupo,$hor_alternativo,$retorno)
        {
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"periodoActivo", "");//echo $cadena_sql_periodo;exit;
            $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $var_inscripcion=array($codEspacio,$planEstudio);
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"espacio_planEstudio",$var_inscripcion);
            $resultado_espacio_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            
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
              $variables[12]=$resultado_espacio_planEstudio[0][5];//Clasificacion
              $variables[13]=$hor_alternativo;//Clasificacion
            }
            else
              {
                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"espacio_otroPlanEstudio",$var_inscripcion);//echo $cadena_sql;exit;
                $resultado_espacio_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
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
                $variables[12]=$resultado_espacio_planEstudio[0][6];//nivel
                $variables[13]=$hor_alternativo;//hor alternativo
              }

            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"adicionar_espacio_mysql", $variables);
            $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
            $resultado_adicionarMysql=true;
            if($resultado_adicionarMysql==true)
                {
                    $cadena_sql_adicionar=$this->sql->cadena_sql($this->configuracion,"adicionar_espacio_oracle", $variables);
                    $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );
                    if($resultado_adicionar==true)
                        {
                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizar_cupo", $variables);
                            $resultado_actualizarCupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );

                            $variablesRegistro[0]=$this->usuario;
                            $variablesRegistro[1]=date('YmdGis');
                            $variablesRegistro[2]='1';
                            $variablesRegistro[3]='Adiciona Espacio académico';
                            $variablesRegistro[4]=$resultado_periodo[0][0]."-".$resultado_periodo[0][1].",".$codEspacio.",0,".$grupo.",".$planEstudio.",".$codProyecto;
                            $variablesRegistro[5]=$codEstudiante;

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($this->configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                            echo "<script>alert ('El espacio académico fue adicionado exitosamente. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                    $variable="pagina=".$retorno['pagina'];
                                    $variable.="&opcion=".$retorno['opcion'];
                                    $variable.=$retorno['parametros'];

                                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                        }
                         else
                         {
                            echo "<script>alert ('En este momentoooo la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"borrar_datos_mysql_no_conexion", $variables);
                                $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                                $variablesRegistro[0]=$this->usuario;
                                $variablesRegistro[1]=date('YmdGis');
                                $variablesRegistro[2]='50';
                                $variablesRegistro[3]='Conexion Error Oracle';
                                $variablesRegistro[4]=$resultado_periodo[0][0]."-".$resultado_periodo[0][1].",".$codEspacio.",0,".$grupo.",".$planEstudio.",".$codProyecto;
                                $variablesRegistro[5]=$codEstudiante;
                                
                                $cadena_sql_registroEvento=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                $variable="pagina=".$retorno['pagina'];
                                $variable.="&opcion=".$retorno['opcion'];
                                $variable.=$retorno['parametros'];

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                        }
                 }
                  else
                  {
                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"borrar_datos_mysql_no_conexion", $variables);
                        $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                        $variablesRegistro[0]=$this->usuario;
                        $variablesRegistro[1]=date('YmdGis');
                        $variablesRegistro[2]='51';
                        $variablesRegistro[3]='Conexion Error MySQL';
                        $variablesRegistro[4]=$resultado_periodo[0][0]."-".$resultado_periodo[0][1].",".$codEspacio.",0,".$grupo.",".$planEstudio.",".$codProyecto;
                        $variablesRegistro[5]=$codEstudiante;

                        $cadena_sql_registroEvento=$this->sql->cadena_sql($this->configuracion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=".$retorno['pagina'];
                        $variable.="&opcion=".$retorno['opcion'];
                        $variable.=$retorno['parametros'];
                                        
                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                   }
    }

    
}

?>