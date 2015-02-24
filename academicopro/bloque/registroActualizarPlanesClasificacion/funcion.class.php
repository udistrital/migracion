<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroActualizarPlanesClasificacion extends funcionGeneral {
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
        $this->formulario="registroActualizarPlanesClasificacion";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    }

    /**
     * Funcion que muestra los espacios academicos del plan de estudio
     * @param <array> $configuracion variables de configuracion
     * @param <int> $_REQUEST['planEstudio'] plan de estudio
     */
    function verPlanEstudio($configuracion)
    {
     
        $cadena_sql=$this->sql->cadena_sql($configuracion,'espaciosCargados',$planEstudio);//echo $cadena_sql;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        if(is_array($resultado_plan))
            {
       
                for($i=0;$i<count($resultado_plan);$i++)
                {
                    $variables=array($resultado_plan[$i][0],$resultado_plan[$i][1]);
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'clasificacionEspacio',$variables);//echo $cadena_sql;exit;
                    $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");

                    $variables[2]=$resultado_clasif[0][0];
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarClasificacion',$variables);//echo $cadena_sql;exit;
                    $resultado_act=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                }

                echo "<script>alert('El cambio de clasificación se realizo con exito')</script>";

           
            }
    }
  
    /**
     * Funcion que muestra los espacios academicos del plan de estudio
     * @param <array> $configuracion variables de configuracion
     * @param <int> $_REQUEST['planEstudio'] plan de estudio
     */
    function actualizarClasificacion($configuracion)
    {
        $planEstudio=0;
        $cadena_sql=$this->sql->cadena_sql($configuracion,'espaciosOracle',$planEstudio);//echo $cadena_sql;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        if(is_array($resultado_plan))
            {

                for($i=0;$i<count($resultado_plan);$i++)
                {
                    $variables=array($resultado_plan[$i][1],$resultado_plan[$i][2]);
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'clasificacionEspacio',$variables);//echo $cadena_sql;exit;
                    $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
                    if(is_array($resultado_clasif))
                    {
                    //$variables[2]=$resultado_clasif[0][0];
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'buscarEspacio',$resultado_plan[$i]);//echo $cadena_sql;exit;
                    $resultado_esp=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    if (!is_array($resultado_esp))
                      {
                        //echo '2';var_dump($resultado_esp);
                        $resultado_plan[$i][3]=$resultado_clasif[0][0];
                          $cadena_sql=$this->sql->cadena_sql($configuracion,'crearClasificacion',$resultado_plan[$i]);//echo $cadena_sql.'<br>';
                          $resultado_reg=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                          if ($resultado_reg==TRUE)
                            {
                              //echo "se registró ".$resultado_plan[$i][1]."<br>";
                              //exit;
                            }
                            else
                            {
                              echo "***No se pudo insertar registro para el espacio ".$resultado_plan[$i][1]." del plan de estudios ".$resultado_plan[$i][2].", carrera ".$resultado_plan[$i][0].".<br>";
                              //exit;

                            }
                            unset ($resultado_reg);

                      }
                      else
                      {
                          //actualizar
                          //echo '1';var_dump($resultado_esp);
                          $variables[2]=$resultado_clasif[0][0];
                          $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarClasificacion',$variables);//echo $cadena_sql.'<br>';
                          $resultado_act=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                          if ($resultado_act==TRUE)
                            {
                              //echo "se actualizó ".$resultado_plan[$i][1]."<br>";//exit;
                            }
                            else
                            {
                              echo "####No se pudo actualizar el espacio ".$resultado_plan[$i][1]." del plan de estudios ".$resultado_plan[$i][2].", carrera ".$resultado_plan[$i][0].".<br>";
                              //exit;
                            }
                            unset ($resultado_act);
                      }
                    }
                    else
                      {
                      echo "!OJO! No hay información para el espacio código ".$resultado_plan[$i][1]." del plan de estudios ".$resultado_plan[$i][2]." carrera ".$resultado_plan[$i][0]."<br>";
                      }

                }

                echo "<script>alert('El cambio de clasificación se realizo con exito')</script>";


            }
    }

    /**
     * Funcion que actualiza los espacios academicos extrinsecos
     * @param <array> $configuracion variables de configuracion
     * @param <int> $_REQUEST['planEstudio'] plan de estudio
     */
    function verPlanExtrinsecas($configuracion)
    {

        $cadena_sql=$this->sql->cadena_sql($configuracion,'buscarElectivo',$planEstudio);//echo $cadena_sql;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");

        if(is_array($resultado_plan))
            {

                for($i=0;$i<count($resultado_plan);$i++)
                {
                    $variables=array($resultado_plan[$i][0],$resultado_plan[$i][1]);
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'clasificacionEspacio',$variables);//echo $cadena_sql;exit;
                    $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");

                    $variables[2]=$resultado_clasif[0][0];
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarClasificacion',$variables);//echo $cadena_sql;exit;
                    $resultado_act=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                }

                echo "<script>alert('El cambio de clasificación se realizo con exito')</script>";


            }
    }


    /**
     * Funcion que permite actualizar las inscripciones de especios academicos
     * @param <array> $configuracion Variables de configuracion
     */
    function actualizarInscripciones($configuracion)
    {
        $actualizadosBien=$actualizadosMal=$noActualizados=0;
        $cadena_sql=$this->sql->cadena_sql($configuracion,'estudiantesCreditos','');//echo $cadena_sql;exit;
        $resultado_estudiantesCreditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        for($i=0;$i<count($resultado_estudiantesCreditos);$i++)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,'inscripcionesEstudiante',$resultado_estudiantesCreditos[$i][0]);//echo $cadena_sql;exit;
            $resultado_inscripciones=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_inscripciones))
              {
                for($j=0;$j<count($resultado_inscripciones);$j++)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'buscarInfoEspacio',$resultado_inscripciones[$j]);//echo $cadena_sql;exit;
                    $resultado_info=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    if (is_array($resultado_info))
                    {
                        $varinfo=array($resultado_info[0][0],$resultado_info[0][1],$resultado_info[0][2],$resultado_info[0][3],$resultado_info[0][4],$resultado_inscripciones[$j][0],$resultado_inscripciones[$j][1],$resultado_estudiantesCreditos[$i][0]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarInscripcionEst',$varinfo);//echo $cadena_sql;exit;
                        $resultado_actualizado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                    }
                    else
                    {
                      $resultado_actualizado==false;
                    }
                    if($resultado_actualizado==true)
                        {
                            $actualizadosBien++;
                        }else
                            {
                                $actualizadosMal++;
                                echo 'Estudiante: '.$resultado_estudiantesCreditos[$i][0].' Espacio: '.$resultado_inscripciones[$j][0].'<br>';
                            }

                }
              
              }
               else
               {
                 $noActualizados++;
               }

              
        }

        echo "<br>Total de Estudiantes: ".count($resultado_estudiantesCreditos);
        echo "<br>Estudiantes Sin Inscripciones: ".$noActualizados;
        echo "<br>Registros actualizados correctamente: ".$actualizadosBien;
        echo "<br>Registros no actualizados correctamente: ".$actualizadosMal;

     
    }


     /**
     * Funcion que permite actualizar las notas de especios academicos
     * @param <array> $configuracion Variables de configuracion
     */
    function actualizarNotas($configuracion)
    {
        $actualizadosBien=$actualizadosMal=$noActualizados=0;
        $cadena_sql=$this->sql->cadena_sql($configuracion,'estudiantesCreditos','');//echo $cadena_sql;exit;
        $resultado_estudiantesCreditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

//        for($i=0;$i<count($resultado_estudiantesCreditos);$i++)
//        {
//            $cadena_sql=$this->sql->cadena_sql($configuracion,'notasEstudiante','20092073019');//echo "<br>".$cadena_sql;exit;
//            $resultado_notas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
//
//            for($j=0;$j<count($resultado_notas);$j++)
//            {
//                $cadena_sql=$this->sql->cadena_sql($configuracion,'buscarInfoEspacio',$resultado_notas[$j]);//echo "<br>".$cadena_sql;//exit;
//                $resultado_info=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
//
//                $varinfo=array($resultado_info[0][0],$resultado_info[0][1],$resultado_info[0][2],$resultado_info[0][3],$resultado_info[0][4],$resultado_notas[$j][0],$resultado_notas[$j][1],'20092073019');
//                $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarNotasEst',$varinfo);//echo "<br>".$cadena_sql;//exit;
//                $resultado_actualizado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
//                if($resultado_actualizado==true)
//                    {
//                        $actualizadosBien++;
//                    }else
//                        {
//                            $actualizadosMal++;
//                        }
//
//            }
//        }
        for($i=0;$i<count($resultado_estudiantesCreditos);$i++)
        {
            //echo $resultado_estudiantesCreditos[$i][0].'<br>';
            $cadena_sql=$this->sql->cadena_sql($configuracion,'notasEstudiante',$resultado_estudiantesCreditos[$i][0]);//echo $cadena_sql;exit;
            $resultado_notas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_notas))
            {
                for($j=0;$j<count($resultado_notas);$j++)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'buscarInfoEspacio',$resultado_notas[$j]);//echo $cadena_sql;exit;
                    $resultado_info=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    if (is_array($resultado_info))
                      {
                        $varinfo=array($resultado_info[0][0],$resultado_info[0][1],$resultado_info[0][2],$resultado_info[0][3],$resultado_info[0][4],$resultado_notas[$j][0],$resultado_notas[$j][1],$resultado_estudiantesCreditos[$i][0]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarNotasEst',$varinfo);//echo $cadena_sql;exit;
                        $resultado_actualizado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                      }
                      else
                      {
                        $resultado_actualizado=false;
                      }

                    if($resultado_actualizado==true)
                        {
                            $actualizadosBien++;
                        }
                        else
                        {
                                $actualizadosMal++;
                                //echo 'Estudiante: '.$resultado_estudiantesCreditos[$i][0].' Espacio: '.$resultado_notas[$j][0].'<br>';
                        }
                }
            }
            else
             {
                 $noActualizados++;
             }


        }

        echo "<br>Total de Estudiantes: ".count($resultado_estudiantesCreditos);
        echo "<br>Estudiantes Sin Notas: ".$noActualizados;
        echo "<br>Registros actualizados correctamente: ".$actualizadosBien;
        echo "<br>Registros no actualizados correctamente: ".$actualizadosMal;


    }


     /**
     * Funcion que permite actualizar las notas de espacios academicos extrinsecos
     * @param <array> $configuracion Variables de configuracion
     */
    function actualizarNotasElectivas($configuracion)
    {
        $actualizadosBien=$actualizadosMal=$noActualizados=0;
        $cadena_sql=$this->sql->cadena_sql($configuracion,'estudiantesCreditos','');//echo $cadena_sql;exit;
        $resultado_estudiantesCreditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        for($i=0;$i<count($resultado_estudiantesCreditos);$i++)
        {
            //echo $resultado_estudiantesCreditos[$i][0].'<br>';
            $cadena_sql=$this->sql->cadena_sql($configuracion,'notasEstudianteElectivas',$resultado_estudiantesCreditos[$i][0]);//echo $cadena_sql;exit;
            $resultado_notas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_notas))
            {
                for($j=0;$j<count($resultado_notas);$j++)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,'buscarInfoEspacioElectivo',$resultado_notas[$j]);//echo $cadena_sql;exit;
                    $resultado_info=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    if (is_array($resultado_info))
                      {
                        $varinfo=array($resultado_info[0][0],$resultado_info[0][1],$resultado_info[0][2],$resultado_info[0][3],$resultado_info[0][4],$resultado_notas[$j][0],$resultado_notas[$j][1],$resultado_estudiantesCreditos[$i][0]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,'actualizarNotasEst',$varinfo);//echo $cadena_sql;exit;
                        $resultado_actualizado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                      }
                      else
                      {
                        $resultado_actualizado=false;
                      }

                    if($resultado_actualizado==true)
                        {
                            $actualizadosBien++;
                        }
                        else
                        {
                                $actualizadosMal++;
                                //echo 'Estudiante: '.$resultado_estudiantesCreditos[$i][0].' Espacio: '.$resultado_notas[$j][0].'<br>';
                        }
                }
            }
            else
             {
                 $noActualizados++;
             }


        }

        echo "<br>Total de Estudiantes: ".count($resultado_estudiantesCreditos);
        echo "<br>Estudiantes Sin Notas: ".$noActualizados;
        echo "<br>Registros actualizados correctamente: ".$actualizadosBien;
        echo "<br>Registros no actualizados correctamente: ".$actualizadosMal;


    }


}


?>
