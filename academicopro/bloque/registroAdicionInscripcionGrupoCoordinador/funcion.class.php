<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAdicionInscripcionGrupoCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacionInscripcion.class.php");
        $this->validacion=new validacionInscripcion();

        $this->fechas=new validar_fechas();

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroAdicionInscripcionGrupoCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }


    function cuadroRegistro($configuracion)
        {
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];

        $variables['codEspacio']=$_REQUEST['codEspacio'];
        $variables['nroGrupo']=$_REQUEST['nroGrupo'];
        $variables['planEstudio']=$_REQUEST['planEstudio'];
        $variables['codProyecto']=$_REQUEST['codProyecto'];
        $variables['nroCreditos']=$_REQUEST['nroCreditos'];
        $variables['nombreEspacio']=$_REQUEST['nombreEspacio'];
        $variables['clasificacion']=$_REQUEST['clasificacion'];

        $registro_permisos=$this->fechas->validar_fechas_grupo_coordinador($configuracion,$codProyecto);

                     switch ($registro_permisos)
                     {
                         case 'adicion':
                                  $this->unEstudiante($configuracion,$variables);
                             break;

                         case 'cancelacion':
                                  $this->deshabilitado($configuracion,$variables);
                             break;

                         case 'consulta':
                                  $this->deshabilitado($configuracion,$variables);
                             break;

                         default:
                                  $this->deshabilitado($configuracion,$variables);
                             break;
                     }
    }

    function unEstudiante($configuracion,$variables)
        {
      
        ?>
        <table class="sigma centrar" align="center">
        <tr>
            <td class="sigma centrar" width="20%">
                <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionGrupoCoordinador";
                    $variable.="&opcion=consultar";
                    $variable.="&planEstudio=".$variables['planEstudio'];
                    $variable.="&codProyecto=".$variables['codProyecto'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br><b>Atras</b>
            </a>
            </td>
            <td class="sigma centrar" width="40%">
                REGISTRAR NUEVOS ESTUDIANTES A ESTE GRUPO
            </td>
            <td width="20%" class="sigma centrar">
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                    <input type="hidden" name="opcion" value="varios">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="codEspacio" value="<?echo $variables['codEspacio']?>">
                    <input type="hidden" name="nroGrupo" value="<?echo $variables['nroGrupo']?>">
                    <input type="hidden" name="planEstudio" value="<?echo $variables['planEstudio']?>">
                    <input type="hidden" name="codProyecto" value="<?echo $variables['codProyecto']?>">
                    <input type="hidden" name="nroCreditos" value="<?echo $variables['nroCreditos']?>">
                    <input type="hidden" name="nombreEspacio" value="<?echo $variables['nombreEspacio']?>">
                    <input type="hidden" name="clasificacion" value="<?echo $variables['clasificacion']?>">
                    Nro Estudiantes<br>
                    <select class="sigma" id="nroEstudiantes" name="nroEstudiantes" onchange="submit()">
                        <option value="0">Seleccione...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                </form>
            </td>
        </tr>
        <tr class="sigma centrar">
            <th class="sigma" colspan="2">
                Codigo del Estudiante
            </th>
            <th class="sigma">
                Registrar
            </th>
        </tr>
    <tr class="sigma" class="izquierda">
        
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
            <td width="60%" class="sigma centrar" colspan="2">
                <input type="hidden" name="opcion" value="estudianteRegistrar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="hidden" name="codEspacio" value="<?echo $variables['codEspacio']?>">
                <input type="hidden" name="nroGrupo" value="<?echo $variables['nroGrupo']?>">
                <input type="hidden" name="planEstudio" value="<?echo $variables['planEstudio']?>">
                <input type="hidden" name="codProyecto" value="<?echo $variables['codProyecto']?>">
                <input type="hidden" name="nroCreditos" value="<?echo $variables['nroCreditos']?>">
                <input type="hidden" name="nombreEspacio" value="<?echo $variables['nombreEspacio']?>">
                <input type="hidden" name="clasificacion" value="<?echo $variables['clasificacion']?>">
                        <input type="text" name="codEstudiante" maxlength="11" size="11">
                    </td>
                    <td width="20%" class="sigma centrar" >
                        <input class="boton" type="submit" value="Registrar">
                    </td>

        </form>
    </tr>

</table>
        <?
    }

    function variosEstudiantes($configuracion)
        {
        ?>
        <table class="sigma centrar"  align="center">
        <tr>
            <td class="sigma centrar">
                <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionGrupoCoordinador";
                    $variable.="&opcion=consultar";
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br><b>Atras</b>
            </a>
            </td>
            <td colspan="2" class="sigma centrar">
                REGISTRAR NUEVOS ESTUDIANTES A ESTE GRUPO
            </td>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
            <td width="15%" colspan="5" class="sigma centrar">
                <input type="hidden" name="opcion" value="varios">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="hidden" name="codEspacio" value="<?echo $_REQUEST['codEspacio']?>">
                <input type="hidden" name="nroGrupo" value="<?echo $_REQUEST['nroGrupo']?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="nroCreditos" value="<?echo $_REQUEST['nroCreditos']?>">
                <input type="hidden" name="nombreEspacio" value="<?echo $_REQUEST['nombreEspacio']?>">
                <input type="hidden" name="clasificacion" value="<?echo $_REQUEST['clasificacion']?>">
                Nro Estudiantes<br>
                <select class="sigma" id="nroEstudiantes" name="nroEstudiantes" onchange="submit()">
                    <option value="0">Seleccione...</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
            </td>
        </form>
        </tr>
        <tr class="sigma centrar">
            <th class="sigma"  width="15%">
                C&oacute;digo del Estudiante
            </th>
            <th class="sigma" colspan="2">
                Datos Estudiante
            </th>
            <th class="sigma" width="15%">
                Registrar
            </th>
        </tr>
        
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <?
            if($_REQUEST['nroEstudiantes'])
                {
                    for($j=1;$j<=$_REQUEST['nroEstudiantes'];$j++)
                    {
                        
                        ?>
                            <tr class="sigma cuadro_plano centrar">
                                <td>
                                    <input type="text" id="codEstudiante-<?echo $j?>" name="codEstudiante-<?echo $j?>" onchange="xajax_nombreEstudiante(document.getElementById('codEstudiante-<?echo $j?>').value,<?echo $j?>)" maxlength="11" size="11">
                                </td>
                                <td colspan="2">
                                    <div id="div_nombreEstudiante-<?echo $j?>" ></div>
                                </td>
                                    
                                
                                <?
                                    if($j==1)
                                        {?>
                                            <td  class="sigma" rowspan="<?echo $_REQUEST['nroEstudiantes']?>">
                                                <input type="hidden" name="opcion" value="registrarVarios">
                                                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                                <input type="hidden" name="codEspacio" value="<?echo $_REQUEST['codEspacio']?>">
                                                <input type="hidden" name="nroGrupo" value="<?echo $_REQUEST['nroGrupo']?>">
                                                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                                                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                                                <input type="hidden" name="nroCreditos" value="<?echo $_REQUEST['nroCreditos']?>">
                                                <input type="hidden" name="nombreEspacio" value="<?echo $_REQUEST['nombreEspacio']?>">
                                                <input type="hidden" name="nroEstudiantes" value="<?echo $_REQUEST['nroEstudiantes']?>">
                                                <input type="hidden" name="clasificacion" value="<?echo $_REQUEST['clasificacion']?>">
                                                <input type="submit" value="Registrar" >
                                            </td>
                                        <?}

                                    ?>
                            
                        <?
                    }
                }
        ?>
        </form>
        </table>
        <?
        
    }

    function consultarFechas($configuracion,$planEstudio,$codProyecto)
        {
        
            $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo",'');//echo $cadena_sql;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $i=5;
            $band=0;
                            do{

                               switch($i)
                               {

                                   case '5':
                                            $variablesFecha=array($planEstudio,$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechas",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                {
                                                for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                    $inicial=$registroFecha[$j][1]-date('YmdHis');
                                                    $final=$registroFecha[$j][2]-date('YmdHis');

                                                if(($inicial>=0) && (0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }

                                                }
                                       break;

                                   case '4':
                                            $variablesFecha=array($codProyecto,$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechas",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                {
                                                    for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                    $inicial=$registroFecha[$j][1]-date('YmdHis');
                                                    $final=$registroFecha[$j][2]-date('YmdHis');

                                                if(($inicial>=0) && (0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                }
                                       break;

                                   case '3':

                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"facultad",$codProyecto);//echo $cadena_sql;exit;
                                            $registroFacultad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                            if(is_array($registroFacultad))
                                                {
                                                    $variablesFecha=array($registroFacultad[0][0],$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechas",$variablesFecha);//echo $cadena_sql;exit;
                                                    $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                                    if(is_array($registroFecha))
                                                    {
                                                       for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                    $inicial=$registroFecha[$j][1]-date('YmdHis');
                                                    $final=$registroFecha[$j][2]-date('YmdHis');

                                                if(($inicial>=0) && (0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    }
                                                }


                                       break;

                                    case '2':
                                            $variablesFecha=array($codProyecto,$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechasGeneral",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                    {
                                                        for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                    $inicial=$registroFecha[$j][1]-date('YmdHis');
                                                    $final=$registroFecha[$j][2]-date('YmdHis');

                                                if(($inicial>=0) && (0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    }
                                       break;

                                    case '1':
                                            $variablesFecha=array($codProyecto,$i,$resultado_periodo[0][0],$resultado_periodo[0][1]);
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaFechasGeneral",$variablesFecha);//echo $cadena_sql;exit;
                                            $registroFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                            if(is_array($registroFecha))
                                                    {
                                                        for($j=0;$j<count($registroFecha);$j++)
                                                {
                                                    $inicial=$registroFecha[$j][1]-date('YmdHis');
                                                    $final=$registroFecha[$j][2]-date('YmdHis');

                                                if(($inicial>=0) && (0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
                                                            $registroFecha[0][0]=$registroFecha[$j][0];
                                                            $registroFecha[0][1]=$registroFecha[$j][1];
                                                            $registroFecha[0][2]=$registroFecha[$j][2];
                                                            break;
                                                       }else
                                                           {
                                                            $band=0;
                                                           }
                                                }
                                                    }
                                       break;

                                       default:
                                           $band=1;
                                       $registroFecha[0][0]='0';
                                           break;

                               }
                                $i--;
                           }while($band==0);

                           return $registroFecha;
        }

    function deshabilitado($configuracion,$variables)
        {
        ?>
<!--<table class="sigma">
    <tr>
        <td class="centrar"> SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</td>
    </tr>
</table>-->
        <?
        }

    function registrarEstudiante($configuracion)
        {
            $variablesVerifica['codEstudiante']=$_REQUEST['codEstudiante'];
            $variablesVerifica['nroCreditos']=$_REQUEST['nroCreditos'];
            $variablesVerifica['codEspacio']=$_REQUEST['codEspacio'];
            $variablesVerifica['nombreEspacio']=$_REQUEST['nombreEspacio'];
            $variablesVerifica['codProyecto']=$_REQUEST['codProyecto'];
            $variablesVerifica['planEstudio']=$_REQUEST['planEstudio'];
            $variablesVerifica['nroGrupo']=$_REQUEST['nroGrupo'];
            $variablesVerifica['clasificacion']=$_REQUEST['clasificacion'];
            
            $var_espacio=array($variablesVerifica['codEspacio'],$variablesVerifica['planEstudio']);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_planEstudio", $var_espacio);//echo $cadena_sql;exit;
            $resultado_datosEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            
            $cadena_sql=$this->sql->cadena_sql($configuracion,"ano_periodo", "");//echo $cadena_sql_periodo;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesVerifica['anno']=$resultado_periodo[0][0];
            $variablesVerifica['periodo']=$resultado_periodo[0][1];
           
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante",$variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesVerifica['codProyectoEst']=$resultado_estudiante[0][0];
            $variablesVerifica['planEstudioEst']=$resultado_estudiante[0][1];

            $variables[0]=$_REQUEST['codEstudiante'];
            $variables[1]=$_REQUEST['nroGrupo'];
            $variables[2]=$_REQUEST['codEspacio'];
            $variables[3]=$variablesVerifica['codProyectoEst'];
            $variables[4]=$variablesVerifica['anno'];
            $variables[5]=$variablesVerifica['periodo'];
            $variables[6]=$variablesVerifica['planEstudio'];
            $variables[7]=$resultado_datosEspacio[0][0];//Creditos E.A.
            $variables[8]=$resultado_datosEspacio[0][1];//H.T.D del E.A.
            $variables[9]=$resultado_datosEspacio[0][2];//H.T.C del E.A.
            $variables[10]=$resultado_datosEspacio[0][3];//H.T.A del E.A.
            $variables[11]=$resultado_datosEspacio[0][4];//Clasificacion E.A.

            $retorno['pagina']="adminConsultarInscripcionGrupoCoordinador";
            $retorno['&opcion']="verGrupo";
            $retorno['&opcion2']="cuadroRegistro";
            $retorno['parametros']="&codEspacio=".$variablesVerifica['codEspacio'];
            $retorno['parametros'].="&nroGrupo=".$variablesVerifica['nroGrupo'];
            $retorno['parametros'].="&planEstudio=".$variablesVerifica['planEstudio'];
            $retorno['parametros'].="&codProyecto=".$variablesVerifica['codProyecto'];
            $retorno['parametros'].="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
            $retorno['parametros'].="&nroCreditos=".$variablesVerifica['nroCreditos'];
            $retorno['parametros'].="&clasificacion=".$variablesVerifica['clasificacion'];

            if(!$_REQUEST['funcionCancelado'])
                {
                    $this->verificarCancelado($configuracion, $variablesVerifica);
                }

            if(!$_REQUEST['funcionRequisitos'])
                {
                    $this->verificarRequisitos($configuracion,$variablesVerifica);
                }
                
            $this->verificarEstudiante($configuracion,$variablesVerifica);
//*PRUEBA ACADEMICA*
//            $this->validacion->validarPruebaAcademica($configuracion, $variablesVerifica['codEstudiante'], $variablesVerifica['codProyecto'], $variablesVerifica['codEspacio'], $variablesVerifica['planEstudioEst'], $retorno);
            
            $this->validacion->validarCreditosPeriodo($configuracion, $variablesVerifica['codEstudiante'], $variablesVerifica['planEstudioEst'], $variablesVerifica['codEspacio'], $retorno);
//            echo "si";
//            $this->verificarEspacioCreditos($configuracion,$variablesVerifica);
//            echo "otro si";exit;
            $this->verificarEspacioCruce($configuracion,$variables,$variablesVerifica);

            if($_REQUEST['clasificacion']!=4)
                {
                    $this->verificarEspacioPlan($configuracion,$variablesVerifica);
                }

                    $this->validacion->verificarRangos($configuracion, $variablesVerifica['planEstudioEst'], $variablesVerifica['codEspacio'], $variablesVerifica['codEstudiante'], $variablesVerifica['clasificacion'], $retorno);
//                    $this->verificarRangosEst($configuracion,$variablesVerifica);
            
            $this->verificarEspacioAprobado($configuracion,$variables,$variablesVerifica);

            //var_dump($_REQUEST);exit;
            
            $cadena_sql=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variables);//echo $cadena_sql;exit;
            $resultado_cupo_grupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);//echo $cadena_sql;exit;
            $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            if(!is_array($resultado_espacioPlan))
            {//var_dump($variablesVerifica);echo '1<br>';
                $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_electivo",$variablesVerifica);
                $resultado_electiva=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                if(is_array($resultado_electiva))
                    {
                        $variables[6]=$variablesVerifica['planEstudioEst'];
                        $variables[11]=4;
                    }
            }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"adicionar_espacio_mysql", $variables);
            $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            if($resultado_adicionarMysql==true)
                {
                    $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,"adicionar_espacio_oracle", $variables);
                    $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );

                    if($resultado_adicionar==true)
                        {
                            $aumentaCupo=($resultado_cupo_grupo[0][1]+1);
                            $variables[6]=$aumentaCupo;

                            $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
                            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                            $variablesRegistro[0]=$this->usuario;
                            $variablesRegistro[1]=date('YmdGis');
                            $variablesRegistro[2]='1';
                            $variablesRegistro[3]='Adiciona Espacio académico';
                            $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                            $variablesRegistro[5]=$_REQUEST['codEstudiante'];

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                            echo "<script>alert ('El espacio académico [".$variablesVerifica['codEspacio']."-".$variablesVerifica['nombreEspacio']."] fue adicionado exitosamente. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                    $variable.="&opcion=verGrupo";
                                    $variable.="&opcion2=cuadroRegistro";
                                    $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                    $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                    $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                    $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                    $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                    $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                    $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                        }
                         else
                         {//exit;
                            echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variables);
                                $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                $variablesRegistro[0]=$this->usuario;
                                $variablesRegistro[1]=date('YmdGis');
                                $variablesRegistro[2]='50';
                                $variablesRegistro[3]='Conexion Error Oracle';
                                $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                                $variablesRegistro[5]=$_REQUEST['codEstudiante'];
                                
                                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                $variable.="&opcion=verGrupo";
                                $variable.="&opcion2=cuadroRegistro";
                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                        $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                        $variablesRegistro[5]=$_REQUEST['codEstudiante'];

                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                        $variable.="&opcion=verGrupo";
                        $variable.="&opcion2=cuadroRegistro";
                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];
                                        
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                   }
    }

    function verificarRangosEst($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"rangos_proyecto", $variablesVerifica['planEstudio']);//echo $cadena_sql;exit;
            $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($resultado_parametros))
                {
                    $OB=$resultado_parametros[0][0];
                    $OC=$resultado_parametros[0][1];
                    $EI=$resultado_parametros[0][2];
                    $EE=$resultado_parametros[0][3];

                    $variablesClasificacion=array($variablesVerifica['planEstudio'],$variablesVerifica['codEspacio']);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacion_espacioAdicionar", $variablesClasificacion);//echo $cadena_sql;exit;
                    $resultado_clasificacionEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_clasificacionEspacio))
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosAprobados",$variablesVerifica['codEstudiante']);//secho $this->cadena_sql;exit;
                            $registroEspaciosAprobados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


                                for($i=0;$i<=count($registroEspaciosAprobados);$i++)
                                {
                                    $idEspacio= $registroEspaciosAprobados[$i][0];
                                    $variables=array($idEspacio, $resultado_estudiante[0][1]);
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"valorCreditosPlan",$variables);//echo $cadena_sql;exit;
                                    $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                        switch($registroCreditosEspacio[0][1])
                                        {
                                            case 1:
                                                    $OBEst=$OBEst+$registroCreditosEspacio[0][1];
                                                    $totalCreditosEst=$totalCreditosEst+$OBEst;
                                                break;

                                            case 2:
                                                    $OCEst=$OCEst+$registroCreditosEspacio[0][1];
                                                    $totalCreditosEst=$totalCreditosEst+$OCEst;
                                                break;

                                            case 3:
                                                    $EIEst=$EIEst+$registroCreditosEspacio[0][1];
                                                    $totalCreditosEst=$totalCreditosEst+$EIEst;
                                                break;

                                            case 4:
                                                    $EEEst=$EEEst+$registroCreditosEspacio[0][1];
                                                    $totalCreditosEst=$totalCreditosEst+$EEEst;
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
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Obligatorios Basicos permitidos ');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                                        $variable.="&opcion=verGrupo";
                                                        $variable.="&opcion2=cuadroRegistro";
                                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Obligatorios Complementarios permitidos ');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                                        $variable.="&opcion=verGrupo";
                                                        $variable.="&opcion2=cuadroRegistro";
                                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Electivos Intrinsecos permitidos ');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                                        $variable.="&opcion=verGrupo";
                                                        $variable.="&opcion2=cuadroRegistro";
                                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Electivos Extrinsecos permitidos ');</script>";
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                                        $variable.="&opcion=verGrupo";
                                                        $variable.="&opcion2=cuadroRegistro";
                                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                                echo "<script>alert ('Imposible rescatar los datos de la clasificación del espacio académico');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                $variable.="&opcion=verGrupo";
                                $variable.="&opcion2=cuadroRegistro";
                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                            }

                }else
                    {
                        echo "<script>alert ('Los rangos de créditos no estan definidos por el proyecto curricular');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                        $variable.="&opcion=verGrupo";
                        $variable.="&opcion2=cuadroRegistro";
                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
        }

    function verificarEstudiante($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_estudiante))
                {
                    echo "<script>alert ('El código ingresado no corresponde a un estudiante de créditos');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                    $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                    $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                    $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                    $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                    $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                    $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }

        }

    function verificarEstudiantePrueba($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"estado_estudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if($resultado_estudiante[0][0]=='B')
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);//echo $cadena_sql;exit;
                    $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_espacioPlan))
                        {
                            $variablesReprobado=array($variablesVerifica['codEstudiante'],$variablesVerifica['codEspacio']);
                            
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_reprobado", $variablesReprobado);//echo $cadena_sql;exit;
                            $resultado_reprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            if(!is_array($resultado_reprobado))
                                {
                                    echo "<script>alert ('No se puede inscribir el Espacio Académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio'].". El estudiante está en Prueba Académica (Parágrafo 1, Artículo 1, Acuerdo 07 de 2009).');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                    $variable.="&opcion=verGrupo";
                                    $variable.="&opcion2=cuadroRegistro";
                                    $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                    $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                    $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                    $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                    $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                    $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                    $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                                if($variablesVerifica['clasificacion']==4)
                                    {
                                        return true;
                                    }else
                                        {
                                        echo "<script>alert ('1. El espacio académico [".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."] no pertenece al plan de estudio del estudiante. No se puede inscribir el espacio académico');</script>";
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                        $variable.="&opcion=verGrupo";
                                        $variable.="&opcion2=cuadroRegistro";
                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                        }
                                
                            }
                }
                else
                    {
                        return true;
                    }

        }

    function verificarEspacioCreditos($configuracion,$variablesVerifica)
        {
      
            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarParametrosEstudiante", $variablesVerifica['planEstudioEst']);//echo $cadena_sql;exit;
            $resultado_parametrosEstudiante=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEspaciosEstudiante", $variablesVerifica);
            $resultado_Espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_Espacios))
                {
                    for($i=0;$i<count($resultado_Espacios);$i++)
                    {
                        $creditos+=$resultado_Espacios[$i][1];
                    }
                    $creditosTotal=$creditos+$variablesVerifica['nroCreditos'];
                }

            if(is_array($resultado_parametrosEstudiante))
              {

                if($creditosTotal>$resultado_parametrosEstudiante[0][0])
                    {
                        echo "<script>alert ('No se pueden inscribir más de ".$resultado_parametrosEstudiante[0][0]." créditos por periodo académico para cada estudiante. No se puede inscribir el espacio académico [ ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."]');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                $variable.="&opcion=verGrupo";
                                $variable.="&opcion2=cuadroRegistro";
                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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
                  echo "<script>alert ('No se puede inscribir el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio'].". Los parametros del plan de estudio del estudiante no estan definidos');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                $variable.="&opcion=verGrupo";
                                $variable.="&opcion2=cuadroRegistro";
                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                }
       }

    function verificarEspacioCruce($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (!is_array($resultado_EspacioOracle)) {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variables);//echo $cadena_sql_horario_registrado;exit;
                $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupo_nuevo", $variables);
                $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                for($i=0;$i<count($resultado_horario_registrado);$i++) {
                    for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                        if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                            echo "<script>alert ('El espacio académico [".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."] presenta cruce con el horario del estudiante. No se ha realizado la inscripción');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                            $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                        }
                    }
                }
            }else
                {
                    $varGrupo=array($resultado_EspacioOracle[0][3],$variables[2],$variables[4],$variables[5]);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_proyecto_grupo", $varGrupo);//echo "<br>".$cadena_sql;exit;
                    $resultado_ProyectoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    echo "<script>alert ('El espacio académico ya fue adicionado para el estudiante ".$variables[0].".\\nProyecto Curricular: ".$resultado_ProyectoGrupo[0][0]." - ".$resultado_ProyectoGrupo[0][1]."\\nGrupo: ".$resultado_EspacioOracle[0][3]."');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                            $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                }
       }

    function verificarEspacioPlan($configuracion,$variablesVerifica)
        {
            if($variablesVerifica['clasificacion']==4)
            {
                return true;
                break;
            }
        
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);
            $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_espacioPlan))
                {//var_dump($variablesVerifica);echo '2<br>';
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_electivo",$variablesVerifica);
                    $resultado_electiva=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    if($variablesVerifica['clasificacion']==4)
                    {
                        return true;
                        break;
                    }
                    elseif(is_array($resultado_electiva))
                    {
                        for($e=0;$e<count($resultado_electiva);$e++)
                        {
                            if($resultado_electiva[$e][0]==4)
                            {
                                //echo "Es electiva";exit;
                                //$variablesVerifica['clasificacion']=4;
                                return true;
                                break;
                            }
                        }
                    }
                    else
                    {
                            echo "<script>alert ('2. El espacio académico [".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."] no pertenece al plan de estudio del estudiante. No se puede inscribir el espacio académico');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                            $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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

    function verificarRequisitos($configuracion,$variablesVerifica)
        {

            $requisito=array($variablesVerifica['planEstudio'], $variablesVerifica['codEspacio']);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"requisitos", $requisito);//echo $cadena_sql;exit;
            $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $band='0';

            if(is_array($resultado_requisito))
                {
                    for($i=0;$i<count($resultado_requisito);$i++)
                    {
                        if($band=='0')
                        {
                        switch ($resultado_requisito[$i][0])
                        {
                            case "1":
                                    $variablesRequisito=array($resultado_requisito[$i][1],$variablesVerifica['codEstudiante']);

                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_aprobado", $variablesRequisito);//echo $cadena_sql;exit;
                                    $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                    if(is_array($resultado_aprobado))
                                        {

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_no_cursado", $variablesRequisito);//echo $cadena_sql;exit;
                                                $resultado_requisitoNoCursado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                if($resultado_aprobado[0][0]<30){
                                                    $nombreEspacio1=strtr(strtoupper($resultado_requisitoNoCursado[0][0]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                                                    $nombreEspacio2=strtr(strtoupper($variablesVerifica['nombreEspacio']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");

                                                ?>
                                                <script type="text/javascript">
                                                    if(confirm('El estudiante curso y perdio el espacio académico <? echo $resultado_requisito[$i][1]." - ".$nombreEspacio1?>  que es requisito de <?echo $variablesVerifica['codEspacio']." - ".$nombreEspacio2?>\n¿Desea inscribirlo?'))
                                                        {
                                                            <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $variable="pagina=registroAdicionInscripcionGrupoCoordinador";
                                                                $variable.="&opcion=estudianteRegistrar";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];
                                                                $variable.="&funcionCancelado=1";
                                                                $variable.="&funcionRequisitos=1";

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                            ?>
                                                                location.replace('<?echo $pagina.$variable?>');
                                                        }else
                                                        {
                                                            <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                                                $variable.="&opcion=verGrupo";
                                                                $variable.="&opcion2=cuadroRegistro";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                                ?>
                                                            location.replace('<?echo $pagina.$variable?>');


                                                        }
                                                    </script>
                                            <?}

                                            else
                                                {
                                                    $band='0';
                                                }
                                            }else
                                                {
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_no_cursado", $variablesRequisito);//echo $cadena_sql;exit;
                                                $resultado_requisitoNoCursado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                if($resultado_aprobado[0][0]<30){
                                                ?>
                                                <script type="text/javascript">
                                                    if(confirm('El estudiante no ha cursado el espacio académico <? echo $resultado_requisito[$i][1]." - ".utf8_encode($resultado_requisitoNoCursado[0][0])?>  que es requisito de <?echo $variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']?>\n¿Desea inscribirlo?'))
                                                        {
                                                            <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $variable="pagina=registroAdicionInscripcionGrupoCoordinador";
                                                                $variable.="&opcion=estudianteRegistrar";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];
                                                                $variable.="&funcionCancelado=1";
                                                                $variable.="&funcionRequisitos=1";

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                            ?>
                                                                location.replace('<?echo $pagina.$variable?>');
                                                        }else
                                                        {
                                                            <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                                                $variable.="&opcion=verGrupo";
                                                                $variable.="&opcion2=cuadroRegistro";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                                ?>
                                                            location.replace('<?echo $pagina.$variable?>');


                                                        }
                                                    </script>
                                            <?}else
                                                {

                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $variable="pagina=registroAdicionInscripcionGrupoCoordinador";
                                                    $variable.="&opcion=estudianteRegistrar";
                                                    $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                    $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                                                    $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                    $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                    $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                    $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                    $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                                    $variable.="&clasificacion=".$variablesVerifica['clasificacion'];
                                                    $variable.="&funcionCancelado=1";
                                                    $variable.="&funcionRequisitos=1";

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
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroAdicionInscripcionGrupoCoordinador";
                $variable.="&opcion=estudianteRegistrar";
                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                $variable.="&clasificacion=".$variablesVerifica['clasificacion'];
                $variable.="&funcionCancelado=1";
                $variable.="&funcionRequisitos=1";

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
        }

    function verificarCancelado($configuracion,$variablesVerifica)
        {

            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEspaciosCancelado", $variablesVerifica);//echo $cadena_sql;exit;
            $resultado_Cancelado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $band='0';// Si $band esta en 0 quiere decir que no hay problema y puede seguir adicionando
                             // Si $band es 1 quiere decir que no cumple con los requisitos
            if(is_array($resultado_Cancelado))
                {
                     ?>
                        <script type="text/javascript">
                        if(confirm('El espacio académico <?echo $variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']?> fue cancelado con anterioridad\n¿Desea adicionarlo de nuevo?'))
                        {
                        <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=registroAdicionInscripcionGrupoCoordinador";
                            $variable.="&opcion=estudianteRegistrar";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                            $variable.="&clasificacion=".$variablesVerifica['clasificacion'];
                            $variable.="&funcionCancelado=1";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);
                        ?>
                            location.replace('<?echo $pagina.$variable?>');
                        }else
                                {
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                        $variable.="&opcion=verGrupo";
                                        $variable.="&opcion2=cuadroRegistro";
                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                                        $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                        ?>
                                        location.replace('<?echo $pagina.$variable?>');
                                }
                        </script>
                     <?
                     exit;
                }
        }

    function verificarEspacioAprobado($configuracion,$variables,$variablesVerifica)
     {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_aprobado", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioAprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (is_array($resultado_EspacioAprobado))
                {
                            echo "<script>alert ('El espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." fue aprobado por el estudiante en el periodo ".$resultado_EspacioAprobado[0][2]."-".$resultado_EspacioAprobado[0][3].". No se puede adicionar de nuevo');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
                            $variable.="&clasificacion=".$variablesVerifica['clasificacion'];

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

    function registrarVarios($configuracion)
        {
        //var_dump($_REQUEST);exit;
            $total=$_REQUEST['nroEstudiantes']+1;
            for($i=1;$i<$total;$i++)
            {
                $variablesVerifica['codEstudiante-'.$i]=$_REQUEST['codEstudiante-'.$i];
            }

            $variablesVerifica['codEspacio']=$_REQUEST["codEspacio"];
            $variablesVerifica['codProyecto']=$_REQUEST["codProyecto"];
            $variablesVerifica['planEstudio']=$_REQUEST["planEstudio"];
            $variablesVerifica['nroGrupo']=$_REQUEST["nroGrupo"];
            $variablesVerifica['nroCreditos']=$_REQUEST["nroCreditos"];
            $variablesVerifica['nombreEspacio']=$_REQUEST["nombreEspacio"];
            $variablesVerifica['nroEstudiantes']=$_REQUEST["nroEstudiantes"];
            $variablesVerifica['clasificacion']=$_REQUEST["clasificacion"];

            $var_espacio=array($variablesVerifica['codEspacio'],$variablesVerifica['planEstudio']);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_planEstudio", $var_espacio);//echo $cadena_sql;exit;
            $resultado_datosEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"ano_periodo", "");//echo $cadena_sql_periodo;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesVerifica['anno']=$resultado_periodo[0][0];
            $variablesVerifica['periodo']=$resultado_periodo[0][1];

            $atributos['codEspacio']=$variablesVerifica['codEspacio'];
            $atributos['codProyecto']=$variablesVerifica['codProyecto'];
            $atributos['planEstudio']=$variablesVerifica['planEstudio'];
            $atributos['nroGrupo']=$variablesVerifica['nroGrupo'];
            $atributos['nroCreditos']=$variablesVerifica['nroCreditos'];
            $atributos['nombreEspacio']=$variablesVerifica['nombreEspacio'];
            $atributos['clasificacion']=$variablesVerifica['clasificacion'];

            $exitos=1;
            $noexitos=1;

            for($j=1;$j<$total;$j++){

            $variablesVerifica['codEstudiante']=$variablesVerifica['codEstudiante-'.$j];
                
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante",$variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesVerifica['codProyectoEst']=$resultado_estudiante[0][0];
            $variablesVerifica['planEstudioEst']=$resultado_estudiante[0][1];

            $variables[0]=$variablesVerifica['codEstudiante'];
            $variables[1]=$variablesVerifica['nroGrupo'];
            $variables[2]=$variablesVerifica['codEspacio'];
            $variables[3]=$variablesVerifica['codProyectoEst'];
            $variables[4]=$variablesVerifica['anno'];
            $variables[5]=$variablesVerifica['periodo'];
            $variables[6]=$variablesVerifica['planEstudio'];
            $variables[7]=$resultado_datosEspacio[0][0];//Creditos E.A.
            $variables[8]=$resultado_datosEspacio[0][1];//H.T.D del E.A.
            $variables[9]=$resultado_datosEspacio[0][2];//H.T.C del E.A.
            $variables[10]=$resultado_datosEspacio[0][3];//H.T.A del E.A.
            $variables[11]=$resultado_datosEspacio[0][4];//Clasificacion E.A.
            
            $validacionEstudiante=$this->verificarEstudianteVarios($configuracion,$variablesVerifica);
                if($validacionEstudiante=="true")
                    {
                        $validacionRangos=$this->verificarRangosVarios($configuracion,$variablesVerifica);
                        if($validacionRangos=="true")
                            {
                                $validacionPrueba=$this->verificarEstudiantePruebaVarios($configuracion,$variablesVerifica);
                                if($validacionPrueba=="true")
                                    {
                                    $validacionCreditos=$this->verificarEspacioCreditosVarios($configuracion,$variablesVerifica);
                                    if($validacionCreditos=="true")
                                        {
                                            $validacionCruce=$this->verificarEspacioCruceVarios($configuracion,$variables,$variablesVerifica);
                                            if($validacionCruce=="true")
                                                {
                                                    $validacionPlan=$this->verificarEspacioPlanVarios($configuracion,$variablesVerifica);
                                                    if($validacionPlan=="true" || $validacionPlan==4)
                                                        {
                                                            if($validacionPlan==4)
                                                            {
                                                                $variables[11]=4;
                                                                $variables[6]=$variablesVerifica['planEstudioEst'];
                                                                //var_dump($variables);exit;
                                                            }
                                                            $validacionRequisitos=$this->verificarRequisitosVarios($configuracion,$variablesVerifica);
                                                            if($validacionRequisitos=="true")
                                                                {
                                                                    $validacionAprobado=$this->verificarEspacioAprobadoVarios($configuracion,$variablesVerifica);
                                                                    if($validacionAprobado=="true")
                                                                        {

                                                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variables);//echo $cadena_sql;exit;
                                                                            $resultado_cupo_grupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"adicionar_espacio_mysql", $variables);
                                                                            $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                                                            if($resultado_adicionarMysql==true)
                                                                                {
                                                                                    $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,"adicionar_espacio_oracle", $variables);//echo $cadena_sql_adicionar;exit;
                                                                                    $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );

                                                                                    if($resultado_adicionar==true)
                                                                                        {
                                                                                            $aumentaCupo=($resultado_cupo_grupo[0][1]+1);
                                                                                            $variables[6]=$aumentaCupo;

                                                                                            $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
                                                                                            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                                                                                            $variablesRegistro[0]=$this->usuario;
                                                                                            $variablesRegistro[1]=date('YmdGis');
                                                                                            $variablesRegistro[2]='1';
                                                                                            $variablesRegistro[3]='Adiciona Espacio académico';
                                                                                            $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                                                                                            $variablesRegistro[5]=$variablesVerifica['codEstudiante'];

                                                                                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                                                                                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                                                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                                                                                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                                                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                                                                                            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                                                            $reporteExitos[$exitos]=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico se inscribio exitosamente";
                                                                                            $exitos++;
                                                                                            //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";

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
                                                                                                $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                                                                                                $variablesRegistro[5]=$_REQUEST['codEstudiante'];

                                                                                                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                                                                                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                                                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                                                                                                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                                                                $reporteNoExitos[$noexitos]=$variablesVerifica['codEstudiante'].",".$resultado_estudiante[0][2].",".$resultado_estudiante[0][3].",Error de conexion Oracle";
                                                                                                $noexitos++;

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
                                                                                        $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                                                                                        $variablesRegistro[5]=$_REQUEST['codEstudiante'];

                                                                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                                                                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                                                                                        $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                                                        $reporteNoExitos[$noexitos]=$variablesVerifica['codEstudiante'].",".$resultado_estudiante[0][2].",".$resultado_estudiante[0][3].",Error de conexion Mysql";
                                                                                        $noexitos++;

                                                                                   }
                                                                    }else
                                                                        {
                                                                            $reporteNoExitos[$noexitos]=$validacionAprobado['reporte'];
                                                                            $noexitos++;
                                                                        }
                                                                }else
                                                                    {
                                                                        $reporteNoExitos[$noexitos]=$validacionRequisitos['reporte'];
                                                                        $noexitos++;
                                                                    }
                                                        }else
                                                                    {
                                                                        $reporteNoExitos[$noexitos]=$validacionPlan['reporte'];
                                                                        $noexitos++;
                                                                    }
                                                }else
                                                                    {
                                                                        $reporteNoExitos[$noexitos]=$validacionCruce['reporte'];
                                                                        $noexitos++;
                                                                    }
                                        }else
                                                                    {
                                                                        $reporteNoExitos[$noexitos]=$validacionCreditos['reporte'];
                                                                        $noexitos++;
                                                                    }
                                }else
                                                                {
                                                                    $reporteNoExitos[$noexitos]=$validacionPrueba['reporte'];
                                                                    $noexitos++;
                                                                }
                        }else
                                                            {
                                                                $reporteNoExitos[$noexitos]=$validacionRangos['reporte'];
                                                                $noexitos++;
                                                            }
                    }else
                                                        {
                                                            $reporteNoExitos[$noexitos]=$validacionEstudiante['reporte'];
                                                            $noexitos++;
                                                        }

            }
            $this->encabezadoGrupo($configuracion, $atributos);
            $this->generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variablesVerifica);
        }

    function verificarEspacioAprobadoVarios($configuracion,$variablesVerifica)
     {
            $variables=array($variablesVerifica['codProyecto'],$variablesVerifica['codEstudiante'],$variablesVerifica['codEspacio']);
            
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_aprobado_varios", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioAprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (is_array($resultado_EspacioAprobado))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico ya fue cursado y aprobado por el estudiante en el periodo ".$resultado_EspacioAprobado[0][2]." - ".$resultado_EspacioAprobado[0][3]." ";
                    return $variableRetorno;
                    break;

                }else
                {
                    return "true";
                }
       }

    function verificarRangosVarios($configuracion,$variablesVerifica)
        {
//            if($variablesVerifica['clasificacion']==4)
//                {
//                    return "true";
//                    break;
//                }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"rangos_proyecto", $variablesVerifica['planEstudioEst']);//echo $cadena_sql;exit;
            $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($resultado_parametros))
                {
                    $variablesClasificacion=array($variablesVerifica['planEstudioEst'],$variablesVerifica['codEspacio']);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacion_espacioAdicionar", $variablesClasificacion);//echo $cadena_sql;exit;
                    $resultado_clasificacionEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                    
                    if (!is_array($resultado_clasificacionEspacio))
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_electivo",$variablesVerifica);
                        $resultado_electiva=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                        if(is_array($resultado_electiva))
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion, "info_espacioAdicionar", $variablesClasificacion);//echo $cadena_sql;exit;
                            $resultado_clasificacionEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                            //echo "Es electiva";exit;
                            //$variablesVerifica['clasificacion']=4;
                            $resultado_clasificacionEspacio[0][1]=4;
                        }
                    }
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_clasificacionEspacio))
                        {
                            $variables=array($variablesVerifica['codEstudiante'],$resultado_clasificacionEspacio[0][1], $variablesVerifica['anno'], $variablesVerifica['periodo']);
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosAprobadosClas",$variables);//echo $cadena_sql;exit;
                            $registroEspaciosAprobados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                            for($i=0;$i<=count($registroEspaciosAprobados);$i++)
                            {
                                $credEst=$credEst+$registroEspaciosAprobados[$i][2];
                            }
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosInscritosClas",$variables);//echo $cadena_sql;exit;
                            $registroEspaciosInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                            for($i=0;$i<=count($registroEspaciosInscritos);$i++)
                            {
                                $credInsEst=$credInsEst+$registroEspaciosInscritos[$i][0];
                            }
                            $creditos=$credEst+$credInsEst+$resultado_clasificacionEspacio[0][0];
                            if($creditos<=$resultado_parametros[0][$resultado_clasificacionEspacio[0][1]])
                                {
                                    return true;
                                }
                                else
                                    {
                                        $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacion",$resultado_clasificacionEspacio[0][1]);//echo $cadena_sql;exit;
                                        $resultadoClasificacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                                        
                                        $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El estudiante supera el número de créditos permitidos para la clasificación ".$resultadoClasificacion[0][0]." por el plan de estudio";
                                        return $variableRetorno;
                                        break;
                                    }
                        }else
                            {
                                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",Imposible rescatar los datos de la clasificación del espacio académico";
                                return $variableRetorno;
                                break;
                            }

                }else
                    {
                        $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",Los rangos de créditos no estan definidos por el proyecto curricular";
                        return $variableRetorno;
                        break;
                    }
        }

    function verificarEstudianteVarios($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_estudiante))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudianteHoras", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][1]).",El estudiante no pertenece al sistema de cr&eacute;ditos";
                    return $variableRetorno;
                    break;
                }else
                    {
                        return "true";
                    }

        }

     function verificarEstudiantePruebaVarios($configuracion,$variablesVerifica)
        {//*PRUEBA ACADEMICA*
            $cadena_sql=$this->sql->cadena_sql($configuracion,"estado_estudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

//            if(trim($resultado_estudiante[0][0])=='B' || trim($resultado_estudiante[0][0])=='J')
//                {
//
//                    $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);//echo $cadena_sql;exit;
//                    $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//
//                    if(is_array($resultado_espacioPlan))
//                        {
//                            $variablesReprobado=array($variablesVerifica['codEstudiante'],$variablesVerifica['codEspacio']);
//
//                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_reprobado", $variablesReprobado);//echo $cadena_sql;exit;
//                            $resultado_reprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//
//                            if(!is_array($resultado_reprobado))
//                                {
//                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
//                                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//
//                                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][1]).",El estudiante está en Prueba Académica (Parágrafo 1 - Artículo 1 - Acuerdo 07 de 2009)";
//                                    return $variableRetorno;
//                                    break;
//                                }
//                                else
//                                {
//                                    return "true";
//                                }
//                        }else
//                            {
//                                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
//                                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//
//                                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico no pertenece al plan de estudios del estudiante";
//
//                                return $variableRetorno;
//                            }
//                }
//                else
//                    {
                        return "true";
//                    }

        }

    function verificarEspacioCreditosVarios($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarParametrosEstudiante", $variablesVerifica['planEstudioEst']);//echo $cadena_sql;exit;
            $resultado_parametrosEstudiante=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEspaciosEstudiante", $variablesVerifica);//echo $cadena_sql;exit;
            $resultado_Espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_Espacios))
                {
                    for($i=0;$i<count($resultado_Espacios);$i++)
                    {
                        $creditos+=$resultado_Espacios[$i][1];
                    }
                    $creditosTotal=$creditos+$variablesVerifica['nroCreditos'];
                }else
                    {
                        $creditosTotal='0';
                    }

            if(is_array($resultado_parametrosEstudiante))
              {
                if($creditosTotal>$resultado_parametrosEstudiante[0][0])
                {

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El número de créditos es mayor al permitido";
                    return $variableRetorno;
                    break;
                }else
                    {
                        return "true";
                    }
              }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",Los parametros del plan de estudio del estudiante no estan definidos";
                    return $variableRetorno;
                    break;
                }

            
       }

    function verificarEspacioCruceVarios($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (!is_array($resultado_EspacioOracle)) {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variables);//echo $cadena_sql;exit;
                $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupo_nuevo", $variables);//echo $cadena_sql;exit;
                $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                $band=0;
                for($i=0;$i<count($resultado_horario_registrado);$i++) {
                    for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                        if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico presenta cruce con el horario del estudiante";
                            $band=1;
                            return $variableRetorno;
                            break;
                        }
                    }
                }
                if($band==0)
                    {
                        return "true";
                    }
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".$resultado_estudiante[0][2].",".$resultado_estudiante[0][3].",El espacio académico ya fue adicionado";

                    return $variableRetorno;
                }
       }

    function verificarEspacioPlanVarios($configuracion,$variablesVerifica)
        {
            if($variablesVerifica['clasificacion']==4)
                {
                    return "true";
                    break;
                }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);//echo $cadena_sql;exit;
            $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_espacioPlan))
            {//var_dump($variablesVerifica);echo '3<br>';
                $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_electivo",$variablesVerifica);
                $resultado_electiva=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                if(is_array($resultado_electiva))
                {
                    for($e=0;$e<count($resultado_electiva);$e++)
                    {
                        if($resultado_electiva[$e][0]==4)
                        {
                            //echo "Es electiva";exit;
                            //$variablesVerifica['clasificacion']=4;
                            return 4;
                            break;
                        }
                    }
                }
                else
                {

                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico no pertenece al plan de estudios del estudiante";

                return $variableRetorno;}
            }
            else
                {
                    return "true";
                }
        }

    function verificarRequisitosVarios($configuracion,$variablesVerifica)
        {
        //var_dump($variablesVerifica);exit;

            if($variablesVerifica['codProyecto']==134)
                {
                    return "true";
                    break;
                }

            $requisito=array($variablesVerifica['planEstudio'], $variablesVerifica['codEspacio']);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"requisitos", $requisito);//echo $cadena_sql;exit;
            $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($resultado_requisito))
                {
                    $band=0; // Si $band esta en 0 quiere decir que no hay problema y puede seguir adicionando
                             // Si $band es 1 quiere decir que no cumple con los requisitos

                    for($i=0;$i<count($resultado_requisito);$i++)
                    {
                        switch ($resultado_requisito[$i][0])
                        {
                            case "1":
                                    $variablesRequisito=array($resultado_requisito[$i][1],$variablesVerifica['codEstudiante']);

                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_aprobado", $variablesRequisito);//echo $cadena_sql;exit;
                                    $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                    if(is_array($resultado_aprobado))
                                        {
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_no_aprobado", $variablesRequisito);//echo $cadena_sql;exit;
                                            $resultado_requisitoNoAprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                            if(is_array($resultado_requisitoNoAprobado))
                                            {
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                                                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El estudiante curso y no aprobo el requisito";

                                                return $variableRetorno;
                                            }else
                                                {
                                                    return "true";
                                                }
                                                
                                        }else
                                            {
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_no_cursado", $variablesRequisito);//echo $cadena_sql;exit;
                                                $resultado_requisitoNoCursado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                                                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities(utf8_decode($resultado_estudiante[0][2])).",".htmlentities($resultado_estudiante[0][3]).",El estudiante no ha cursado los requisitos establecidos";

                                                return $variableRetorno;

                                            }
                                break;
                            case "0":
                                    return "true";
                                break;
                        }
                    }

                }else
                    {
                        return "true";
                    }
        }

    function generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variablesVerifica)
        {
      
        //Actualiza el cupo del nuevo grupo de los estudiantes
        $variablesInscritosNue=array($variablesVerifica['codEspacio'],$variablesVerifica['nroGrupo']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosNue);//echo $cadena_sql;exit;
        $resultado_InscritosNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritosNue[2]=$resultado_InscritosNue[0][0];
        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosNue);
        $resultado_ActualizacionNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );
?>

<table class="sigma" width="100%">
   
    <?
//   var_dump($reporteNoExitos);
//   echo count($reporteNoExitos);

        if(is_array($reporteExitos))
            {
                echo "<caption class='sigma'>REGISTROS EXITOSOS</caption>";
                for($i=1;$i<=count($reporteExitos);$i++)
                {
                    $arreglo=explode(",",$reporteExitos[$i]);
                    $codEstudiante=$arreglo[0];
                    $nombreEstudiante=$arreglo[1];
                    $proyectoEstudiante=$arreglo[2];
                    $Descripcion=$arreglo[3];


                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $codEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $nombreEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $proyectoEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $Descripcion?>
                            </td>
                        </tr>
                    <?
                }
            }
        if(is_array($reporteNoExitos))
            {
                echo "<caption class='sigma'>REGISTROS NO EXITOSOS</caption>";
                for($p=1;$p<=count($reporteNoExitos);$p++)
                {
                    $arregloNo=explode(",",$reporteNoExitos[$p]);
                    $codEstudianteNo=$arregloNo[0];
                    $nombreEstudianteNo=$arregloNo[1];
                    $proyectoEstudianteNo=$arregloNo[2];
                    $DescripcionNo=$arregloNo[3];

                                        
                    $variablesRegistro[0]=$this->usuario;
                    $variablesRegistro[1]=date('YmdGis');
                    $variablesRegistro[2]='31';
                    $variablesRegistro[3]=$DescripcionNo;
                    $variablesRegistro[4]=$variablesVerifica['anno']."-".$variablesVerifica['periodo'].",".$variablesVerifica['codEspacio'].",0,".$variablesVerifica['nroGrupo'].",".$variablesVerifica['planEstudio'].",".$variablesVerifica['codProyecto'];
                    $variablesRegistro[5]=$codEstudianteNo;

                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $codEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $nombreEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $proyectoEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $DescripcionNo?>
                            </td>
                        </tr>
                    <?
                }
            }
    ?>
</table>
            <?
        }

    function encabezadoGrupo($configuracion,$atributos)
        {
        ?>
<table class="sigma centrar" width="100%">
<tr class="centrar">
    <td width="33%">
        <a href="javascript:history.back();">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0" alt="atras">
        </a>
    </td>
    <td width="33%">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
        $variable.="&opcion=verGrupo";
        $variable.="&opcion2=cuadroRegistro";
        $variable.="&codEspacio=".$atributos['codEspacio'];
        $variable.="&nroGrupo=".$atributos['nroGrupo'];
        $variable.="&planEstudio=".$atributos['planEstudio'];
        $variable.="&codProyecto=".$atributos['codProyecto'];
        $variable.="&nombreEspacio=".$atributos['nombreEspacio'];
        $variable.="&nroCreditos=".$atributos['nroCreditos'];
        $variable.="&clasificacion=".$atributos['clasificacion'];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);
        ?>
            <a href="<?echo $pagina.$variable?>">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0" alt="atras">
            </a>
    </td>
    <td width="33%">
        <a href="javascript:history.forward();">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0" alt="atras">
        </a>
    </td>
</tr>
<tr>
    <td colspan="3">
        <?
        $this->datosGeneralesProyecto($configuracion, $atributos['planEstudio']);

        $variables=array($atributos['codEspacio'],$atributos['nroGrupo'],$atributos['planEstudio']);
        $this->datosGeneralesEspacioGrupo($configuracion,$variables);
        ?>

    </td>
</tr>
   
</table>
        <?
    }

    function datosGeneralesProyecto($configuracion,$planEstudio)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosProyecto", $planEstudio);//echo $cadena_sql;exit;
            $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($resultado_proyecto))
            {
                ?>
                <table class="sigma centrar" width="100%">
                    <caption class='sigma'>Datos Generales</caption>
                    <tr class="sigma">
                        <td>Proyecto Curricular : <?echo $resultado_proyecto[0][2]." - ".$resultado_proyecto[0][1]?></td>

                        <td class="derecha">Plan Estudio : <?echo $resultado_proyecto[0][0]?></td>
                    </tr>
                </table>
                <?
                return $codProyecto=$resultado_proyecto[0][2];
            }
        }

    function datosGeneralesEspacioGrupo($configuracion,$variables)
        {
        ?>
            <table class="sigma cuadro_plano centrar" width="100%">
                         <caption class="sigma">
                            REPORTE DE INSCRIPCIONES
                        </caption>
                        <tr>
                            <th class="sigma centrar" width="10%">C&oacute;digo</th>
                            <th class="sigma centrar" width="30%" colspan="5">Nombre Espacio Acad&eacute;mico</th>
                            <th class="sigma centrar" width="10%">Nro Cr&eacute;ditos</th>
                            <th class="sigma centrar" width="10%">H.T.D</th>
                            <th class="sigma centrar" width="10%">H.T.C</th>
                            <th class="sigma centrar" width="10%">H.T.A</th>
                        </tr>

                <?
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$variables[0]);//echo $cadena_sql;exit;
                    $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    ?>

                        <tr>
                            <td class="sigma centrar"><font size="2"><?echo $resultado_espaciosDesc[0][1]?></font></td>
                            <td class="sigma centrar" colspan="5"><font size="2"><?echo $resultado_espaciosDesc[0][2]?></font></td>
                            <td class="sigma centrar"><font size="2"><?echo $resultado_espaciosDesc[0][3]?></font></td>
                            <td class="sigma centrar"><font size="2"><?echo $resultado_espaciosDesc[0][4]?></font></td>
                            <td class="sigma centrar"><font size="2"><?echo $resultado_espaciosDesc[0][5]?></font></td>
                            <td class="sigma centrar"><font size="2"><?echo $resultado_espaciosDesc[0][6]?></font></td>
                        </tr>

                        <tr>
                            <th class="sigma cuadro_plano centrar" >Nro Grupo</th>
                            <th class="sigma cuadro_plano centrar" width="12">Lunes</th>
                            <th class="sigma cuadro_plano centrar" width="12">Martes</th>
                            <th class="sigma cuadro_plano centrar" width="12">Miercoles</th>
                            <th class="sigma cuadro_plano centrar" width="12">Jueves</th>
                            <th class="sigma cuadro_plano centrar" width="12">Viernes</th>
                            <th class="sigma cuadro_plano centrar" width="12">Sabado</th>
                            <th class="sigma cuadro_plano centrar" width="12">Domingo</th>
                            <th class="sigma cuadro_plano centrar" width="12">Nro Cupos</th>
                            <th class="sigma cuadro_plano centrar" width="12">Disponibles</th>
                        </tr><?
                        $variablesEspacio=array($variables[0],$variables[1]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupo",$variablesEspacio);//echo $cadena_sql;exit;
                        $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datosProyecto", $variables[2]);//echo $cadena_sql;exit;
                        $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                        $variablesHorario=array($variables[0],$resultado_proyecto[0][2],'',$variables[1]);

                        $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variablesHorario);//echo $cadena_sql_horarios;exit;
                        $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                    ?>
                    <tr class="cuadro_plano centrar">
                        <td class="sigma  centrar"><?echo $variables[1]?></td>
                    <?
                        $this->mostrarHorario($configuracion,$resultado_horarios);

                    ?>
                        <td class="sigma  centrar"><?echo $resultado_espacios[0][3]?></td>
                        <td class="sigma  centrar"><?echo $resultado_espacios[0][3]-$resultado_espacios[0][4]?></td>
                    </tr>
            </table>
                        <?
        }

    function mostrarHorario($configuracion,$resultado_horarios)
        {
                for($i=1; $i<8; $i++) {
                                ?><td class='sigma centrar'><?
                                    for ($k=0;$k<count($resultado_horarios);$k++) {

                                        if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                            {
                                                $l=$k;
                                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                                {
                                                    $m=$k;
                                                    $m++;
                                                    $k++;
                                                }
                                                $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                echo $dia."<br>";
                                                unset ($dia);
                                            }
                                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                                            {
                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                    echo $dia."<br>";
                                                    unset ($dia);
                                                    $k++;
                                            }
                                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                                            {
                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                    echo $dia."<br>";
                                                    unset ($dia);
                                            }
                                            elseif ($resultado_horarios[$k][0]!=$i)
                                            {

                                            }
                                    }

                                }
                                ?></td><?
                        }
}

?>