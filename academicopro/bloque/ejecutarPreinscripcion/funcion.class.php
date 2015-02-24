<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/revisarAdicion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/agregarHorario.class.php");
require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/ProgressBar.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");

class funciones_ejecutarPreinscripcion extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.

    var $bar; 
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->tema=$tema;
        $this->sql=$sql;
        $this->cripto=new encriptar;
        $this->revision=new revisarAdicion;
        $this->asignacion=new agregarHorario;
        $this->administrar=new administrarModulo();
        $this->administrar->administrarModuloSGA($configuracion, '2');



                /*$bar = new barraProgreso($message='<br>Buscando Registros...',
$hide=true, $sleepOnFinish=0, $barLength=500, $precision=50,
$backgroundColor='#cccccc', $foregroundColor='blue');
                $elements = 1000000; //total number of elements to process
                $bar->initialize($elements); //print the empty bar

                for($i=0;$i<$elements;$i++)
                {
                    //do something here...
                            $bar->increase(); //calls the bar with every processed element
                }


*/
        //Conexion ORACLE
        //$this->bar->increase();
        $this->bar=new ProgressBar("Ejecutando Preinscripcion...");
        
        $conexion=new multiConexion();
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");
        //Conexion General
        //$this->bar->increase();
        $this->acceso_db=$this->conectarDB($configuracion,"");
        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        //Conexion DB SGA
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //$this->bar->increase();
        $this->formulario="realizarPreinscripcion";
        $this->verificar="control_vacio(".$this->formulario.",'planEstudio', 0, 500)";
        $this->forma="realizarPreinscripcion";
    //$this->bar->increase();



    }

    function buscarEstudiantes ($configuracion,$conexion) {

    //busca los estudiantes asociados a la carrera y plan de estudio en créditos
        $tiempo=time();
        $parametro[0]=$_REQUEST['carrera'];
        $parametro[1]=$_REQUEST['planEstudio'];
        $parametro[2]=$_REQUEST['orden'];
        $parametro[3]=$_REQUEST['semestres'];
        $parametro[4]=$_REQUEST['anno'];
        $parametro[5]=$_REQUEST['periodo'];
        //busca los estudiantes del plan de estudios coorespondiente, de acuerdo a los parámetros definidos
        //echo "Buscando estudiantes...";

        //si se hace un recargar a la pagina...
        if(isset($resultado_est)) {
            $this->redireccionarProceso($configuracion, "borrarDatos", $parametro);
        }

        
        $this->bar->setAutohide(true);
        $this->bar->setForegroundColor('#efdca8');



        $cadena_est_sql=$this->sql->cadena_ejPre_sql($configuracion,$this->accesoOracle, "buscarEstudiantes",$parametro);
        $resultado_est=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_est_sql, "busqueda");
        //$resultado_est contiene el array de los estudiantes de la cra y planest
        sleep(1);

        if(is_array($resultado_est)) {

            $totalEstudiantes=count($resultado_est);
            $this->bar->setBarLength(500);
            $this->bar->initialize(10);
            $this->bar->setForegroundColor('#efcf76');

            $this->bar->setMessage("Procesando un total de ".$totalEstudiantes." del Proyecto Curricular");
            sleep(1);//espera 1 segundo


            //Recorrer uno a uno los estudiantes del proyecto curricular que tienen registrados Espacios Académicos Perdidos
            for ($i=0; $i<$totalEstudiantes; $i++) {
                $variables[0]=$resultado_est[$i][0];//codigo estudiante
                $this->revision->set_codigoEstudiante($variables[0]);
                $this->bar->setMessage("Procesando el estudiante ".$variables[0]." ... ");

                $cred=$this->revisarNotas($configuracion, $this->accesoGestion, $variables[0], $parametro);

                $variables[1]=$parametro[4]; //año
                $variables[2]=$parametro[5]; //periodo

                //para cada estudiante revisa si tiene EA registrados en tabla reprobados
                $cadena_EA=$this->sql->cadena_ejPre_sql($configuracion, $this->accesoGestion, "buscarEA",$variables);
                $resultado_EA=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_EA, "busqueda");

                $varbusqueda[0]=$parametro[0];//cra
                $varbusqueda[1]=$parametro[1];//planest
                $varbusqueda[2]=$parametro[4];//año
                $varbusqueda[3]=$parametro[5];//período
                $varbusqueda[4]=$variables[0];//cod est
                $creditosEstudiante=0;

                if(is_array($resultado_EA)) {

                    $j=0;

                    while($resultado_EA[$j][0]) {

                        $varbusqueda[5]=$resultado_EA[$j][0]; //Espacio Academico


                        $registroGrupos=$this->revision->rescatarGruposEA($configuracion,$this->accesoOracle,$varbusqueda);


                        if(is_array($registroGrupos)) {
                            $k=0;
                            $conCruce=true;
                            while($registroGrupos[$k][0]&&$conCruce==true) {
                            //revisa disponibilidad de cupos en grupos de EA registrados
                                $varbusqueda[6]=$registroGrupos[$k][0]; //Grupo
                                $total=$this->revision->verificarCupo($configuracion,$this->accesoGestion,$this->accesoOracle,$varbusqueda);
                                if($total>0) {
                                    $conCruce=$this->revision->verificarCruceEA($configuracion,$this->accesoGestion,$this->accesoOracle,$varbusqueda);
                                    if($conCruce==false) {

                                        $varbusqueda[7]=2;//Estado
                                        $varbusqueda[8]=$total;//Cupos actuales

                                        $resultado=$this->revision->actualizarHorario($configuracion,$this->accesoGestion,$this->accesoOracle,$varbusqueda);

                                        $resultado&=$this->revision->actualizarCupos($configuracion,$this->accesoGestion,$this->accesoOracle,$varbusqueda);

                                        if($resultado==true) {

                                            //Se aumenta el numero de creditos relacionados con el estudiante
                                            $creditosEstudiante+=$resultado_EA[$j][2];

                                            $datos["proyectoCurricular"]=$varbusqueda[0];
                                            $datos["planEstudios"]=$varbusqueda[1];
                                            $datos["anno"]=$varbusqueda[2];
                                            $datos["periodo"]=$varbusqueda[3];
                                            $datos["codigoEstudiante"]=$varbusqueda[4];
                                            $datos["espacioAcademico"]=$varbusqueda[5];
                                            $datos["grupo"]=$varbusqueda[6];
                                            $datos["observaciones"]="1";

                                            $resultado=$this->revision->actualizarErrores($configuracion,$this->accesoGestion,$this->accesoOracle,$datos);

                                        }else {
                                        //Error fatal
                                            echo "<script>alert('En este momento no se puede realizar el proceso. Si el problema persiste, comuníquese con nosotros a través del correo ".$configuracion['correo']." o al tel. 3238400 ext 1110');</script>";
                                            echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                                            exit;

                                        }



                                    }
                                }
                                $k++;
                            }
                            if($conCruce==true) {
                            //Llenar la tabla de error de inscripcion

                                $datos["proyectoCurricular"]=$varbusqueda[0];
                                $datos["planEstudios"]=$varbusqueda[1];
                                $datos["anno"]=$varbusqueda[2];
                                $datos["periodo"]=$varbusqueda[3];
                                $datos["codigoEstudiante"]=$varbusqueda[4];
                                $datos["espacioAcademico"]=$varbusqueda[5];
                                $datos["grupo"]=$varbusqueda[6];
                                $datos["observaciones"]="No hay cupo en los grupos que no presentan cruce.";
                                $resultado=$this->revision->actualizarErrores($configuracion,$this->accesoGestion,$this->accesoOracle,$datos);

                            }

                        }
                        else{
                                $datos["proyectoCurricular"]=$varbusqueda[0];
                                $datos["planEstudios"]=$varbusqueda[1];
                                $datos["anno"]=$varbusqueda[2];
                                $datos["periodo"]=$varbusqueda[3];
                                $datos["codigoEstudiante"]=$varbusqueda[4];
                                $datos["espacioAcademico"]=$varbusqueda[5];
                                $datos["grupo"]=$varbusqueda[6];
                                $datos["observaciones"]="No se han creado grupos de este espacio.";
                                $resultado=$this->revision->actualizarErrores($configuracion,$this->accesoGestion,$this->accesoOracle,$datos);
                        }

                        $j++;
                    }
                    //Actualizar el numero de creditos del estudiante
                    $varbusqueda["creditos"]=$creditosEstudiante;
                    $resultado=$this->revision->actualizarCreditos($configuracion,$this->accesoGestion,$this->accesoOracle,$varbusqueda);

                }else {
                //El estudiante no tiene materias perdidas
                }
            }
        }
        else {
        //Cuando no encuentra estudiantes
            $this->bar->stop();
            echo "No hay estudiantes para realizar la Pre-inscripci&oacute;n";
            exit;
        }
        //$message="fin";
        //$hide=TRUE;
        //$this->bar->barraProgreso($message, $hide);
        $this->bar->setMessage("Generando Reporte...");
        $this->bar->increase();
        sleep(1);//espera 1 segundo
        $this->generarReporte($configuracion, $this->accesoGestion, $this->accesoOracle, $varbusqueda);


    }
    function revisarNotas ($configuracion, $conexion, $estudiante, $parametros) {
    //estudiantes 0=cod_est
    //parametros 0=cra, 1=planest, 2=orden, 3=semestres, 4=anno, 5=periodo
    //método para buscar notas reprobadas de estudiantes
    //$semestre=$parametros[4]."-".$parametros[5];

        $valor[0]=$estudiante;//cod est
        $valor[6]=$parametros[0];//carrera
        $valor[7]=$parametros[1];//plan est
        $valor[8]=$parametros[4];//año
        $valor[9]=$parametros[5];//periodo



        $this->bar->setMessage("Revisando notas... ".$i." de ".$tot." estudiantes" );
        $cadena_not_sql=$this->sql->cadena_ejPre_sql($configuracion,$this->accesoOracle, "consultarNotaEst",$valor[0]);
        $resultado_not=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_not_sql, "busqueda");
        //echo $estudiantes[$i][0]."<br>";
        if(is_array($resultado_not)) {
            $j=0;

            $k=1;

            while ($resultado_not[$j][0]) {
            //por cada EA con nota inferior a 30 revisa si aparece perdido más de una vez
                $veces=1;
                //echo $veces."***";
                if(isset($resultado_not[$k][0])) {
                    while ($resultado_not[$j][0]==$resultado_not[$k][0]) {
                    //si aparece más de una vez perdido, busca la siguiente vez perdido
                        $j++;
                        $k++;
                        $veces++;
                    }
                }


                $valor[1]=$resultado_not[$j][0];//cod EA
                $valor[2]=$resultado_not[$j][2];//not sem
                $valor[3]=$resultado_not[$j][1];//grupo sem ant
                $valor[4]=$veces;//veces
                $valor[5]=$resultado_not[$j][3];//creditos

                //echo $resultado_not[$j][0]."<br>";

                //invoca método para buscar EA con nota inferior a 30 que puedan estar aprobados
                $this->revisarReprobados($configuracion, $conexion, $valor);

                $j++;
                $k++;
            }
        }
        return $total;
    }


    function revisarReprobados ($configuracion, $conexion, $infoEA) {
    //busca si un EA perdido aparece aprobado
    //echo ".";
        $cadena_apro_sql=$this->sql->cadena_ejPre_sql($configuracion,$this->accesoOracle, "consultarNotaAprobado",$infoEA);
        $resultado_apro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_apro_sql, "busqueda");
        if (!is_array($resultado_apro)) {
        //si no aparece aprobado un EA registra este EA en la DB con el mismo grupo de la última vez cursado
            if($infoEA[4]>1) {
                $vez="veces";
            }
            else {
                $vez="vez";
            }
            //echo "El estudiante c&oacute;digo ".$infoEA[0]." ha cursado el Espacio Acad&eacute;mico ".$infoEA[1]." ".$infoEA[4]." ".$vez." y ha sido registrado en el grupo Nº ".$infoEA[3]." para el pr&oacute;ximo semestre<br>";
            //exit;
            if(($infoEA[7]>220) && ($infoEA[7]<230)){
                $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion,$this->accesoGestion, "buscarEquivalente",$infoEA[1]);
                $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
                if($resultado_espacio){
                    $infoEA[1]=$resultado_espacio[0][0];
                }
            }


            $cadena_ins_sql=$this->sql->cadena_ejPre_sql($configuracion,$this->accesoGestion, "insertarNotaEstudiante",$infoEA);
            $resultado_ins=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_ins_sql, "");

            //Insertar los grupos con cupos
            $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion,$this->accesoGestion, "consultarCupoMysql",$infoEA);
            $resultado_existe_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");




        //echo $cadena_ins_sql."<br>";
        }


    }



function generarReporte($configuracion, $conexion, $conexionOracle, $parametros)
{

        $datos["proyectoCurricular"]=$parametros[0];
        $datos["planEstudios"]=$parametros[1];
        $datos["anno"]=$parametros[2];
        $datos["periodo"]=$parametros[3];

        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "rescatarEstudiantes",$datos);
        $registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
        $datos["totalEstudiantes"]=$registro[0][0];

        unset($registro);
        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "rescatarEstudiantesConEA",$datos);
        $registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
        $datos["totalEstudiantesConEA"]=$registro[0][0];

        unset($registro);
        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "buscarNumEspacios",$datos);
        $registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
        $datos["totalEspacios"]=$registro[0][0];

        unset($registro);
        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "buscarErrores",$datos);
        $resultado_errores=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");

        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "buscarCuposDisponibles",$datos);//echo "errores".$cadena_sql;exit;
        $resultado_cuposDisponibles=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");

        $this->bar->stop();
        $rutaBloque=$configuracion["raiz_documento"].$configuracion["bloques"]."/ejecutarPreinscripcion/";
        include_once($rutaBloque."/formulario/mostrar.php");
        
}










    function redireccionarProceso($configuracion, $opcion, $valor="") {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        unset($_REQUEST['action']);
        $cripto=new encriptar();
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        switch($opcion) {
            case "revisarCreditos":
                $variable="pagina=revisarAdicion";
                $variable.="&opcion=revisarCreditos";
                $variable.="&carrera=".$valor[0];
                $variable.="&planEstudio=".$valor[1];
                $variable.="&orden=".$valor[2];
                break;

            case "borrarDatos":
                $variable="pagina=realizarPreinscripcion";
                $variable.="&opcion=borrar";
                $variable.="&carrera=".$valor[0];
                $variable.="&planEstudio=".$valor[1];
                $variable.="&orden=".$valor[2];
                $variable.="&semestres=".$valor[3];
                $variable.="&anno=".$valor[4];
                $variable.="&periodo=".$valor[5];
                break;

            case "cancelar":
                $variable="pagina=adminSolicitudCertificado";
                break;

            case "mostrarregistro":
                $variable="pagina=registro_blogdev";
                $variable.="&opcion=generar";
                break;

            case "administracion":
                $variable="pagina=admin_usuario";
                $variable.="&accion=1";
                $variable.="&hoja=0";
                break;

            case "confirmacion":
                $variable="pagina=confirmacionInscripcionGrado";
                $variable.="&opcion=confirmar";
                $variable.="&identificador=".$valor;
                break;

            case "formgrado":
                $variable="pagina=registro_inscripcionGrado";
                $variable.="&opcion=verificar";
                $variable.="&xajax=pais|region|paisFormacion|regionFormacion";
                $variable.="&xajax_file=inscripcion";
                break;

            case "confirmacionCoordinador":
                $variable="pagina=confirmacionInscripcionCoordinador";
                $variable.="&opcion=confirmar";
                $variable.="&sinCodigo=1";
                $variable.="&identificador=".$valor;
                break;

            case "corregirUsuario":
                $variable="pagina=registroInscripcionCorregir";
                $variable.="&opcion=corregir";
                $variable.="&identificador=".$valor;
                break;

            case "exitoInscripcion":
                if(isset($_REQUEST['sinCodigo'])) {
                    $variable="pagina=exitoInscripcionSecretario";
                }
                else {
                    $variable="pagina=exitoInscripcion";
                }

                $variable.="&identificador=".$valor;
                $variable.="&opcion=verificar";
                break;

            case "principal":
                $variable="pagina=index";
                break;



        }

        $variable=$cripto->codificar_url($variable,$configuracion);
        echo "<script>location.replace('".$indice.$variable."')</script>";
        exit();
    }


}


?>
