<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAdicionCIGrupoCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");


//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroAdicionCIGrupoCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }


    function cuadroRegistro($configuracion)
        {
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];

        $registroFecha=$this->consultarFechas($configuracion,$planEstudio,$codProyecto);

        $variables['codEspacio']=$_REQUEST['codEspacio'];
        $variables['nroGrupo']=$_REQUEST['nroGrupo'];
        $variables['planEstudio']=$_REQUEST['planEstudio'];
        $variables['codProyecto']=$_REQUEST['codProyecto'];
        $variables['nroCreditos']=$_REQUEST['nroCreditos'];
        $variables['nombreEspacio']=$_REQUEST['nombreEspacio'];
       
        switch(trim($registroFecha[0][0]))
                           {
                               case '100':
                                   $inicial=$registroFecha[0][1]-date('YmdHis');
                                   $final=$registroFecha[0][2]-date('YmdHis');

                                   if(($inicial>=0) && ($final>0))
                                       {
                                            $this->unEstudiante($configuracion,$variables);
                                       }
                                       else if(($inicial<0) && ($final>0))
                                       {
                                            $this->unEstudiante($configuracion,$variables);
                                       }
                                       else
                                           {
                                             $this->deshabilitado($configuracion,$variables);
                                           }
                                   break;

                               case '101':

                                   $inicial=$registroFecha[0][1]-date('YmdHis');
                                   $final=$registroFecha[0][2]-date('YmdHis');

                                   if(($inicial>=0) && ($final>0))
                                       {
                                             $this->deshabilitado($configuracion,$variables);
                                       }else if(($inicial<0) && ($final>0))
                                       {
                                             $this->deshabilitado($configuracion,$variables);
                                       }else
                                           {
                                             $this->deshabilitado($configuracion,$variables);
                                           }
                                   break;

                               case '0':
                                         $this->deshabilitado($configuracion,$variables);
                                       break;

                           }

        ?>

    
<?
    }

    function unEstudiante($configuracion,$variables)
        {

        ?>
        <table class="contenidotabla centrar" align="center">
        <tr>
            <td class="centrar" width="20%">
                <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminCIGrupoCoordinador";
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
            <td class="cuadro_brownOscuro centrar" width="40%">
                REGISTRAR NUEVOS ESTUDIANTES A ESTE GRUPO
            </td>
            <td width="20%">
                
            </td>
        </tr>
        <tr class="cuadro_brownOscuro cuadro_plano centrar">
            <td>
                Nro Estudiantes
            </td>
            <td>
                Codigo del Estudiante
            </td>
            <td>
                Registrar
            </td>
        </tr>
    <tr class="cuadro_brownOscuro" class="izquierda">
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
          <td width="20%" class="cuadro_plano centrar">
            <input type="hidden" name="opcion" value="varios">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="hidden" name="codEspacio" value="<?echo $variables['codEspacio']?>">
            <input type="hidden" name="nroGrupo" value="<?echo $variables['nroGrupo']?>">
            <input type="hidden" name="planEstudio" value="<?echo $variables['planEstudio']?>">
            <input type="hidden" name="codProyecto" value="<?echo $variables['codProyecto']?>">
            <input type="hidden" name="nroCreditos" value="<?echo $variables['nroCreditos']?>">
            <input type="hidden" name="nombreEspacio" value="<?echo $variables['nombreEspacio']?>">

            <select id="nroEstudiantes" name="nroEstudiantes" onchange="submit()">
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
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
           <td width="60%" class="cuadro_plano centrar">
                <input type="hidden" name="opcion" value="estudianteRegistrar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="hidden" name="codEspacio" value="<?echo $variables['codEspacio']?>">
                <input type="hidden" name="nroGrupo" value="<?echo $variables['nroGrupo']?>">
                <input type="hidden" name="planEstudio" value="<?echo $variables['planEstudio']?>">
                <input type="hidden" name="codProyecto" value="<?echo $variables['codProyecto']?>">
                <input type="hidden" name="nroCreditos" value="<?echo $variables['nroCreditos']?>">
                <input type="hidden" name="nombreEspacio" value="<?echo $variables['nombreEspacio']?>">
                        <input type="text" name="codEstudiante" maxlength="11" size="11">
                    </td>
                    <td width="20%" class="cuadro_plano centrar" >
                        <input type="submit" value="Registrar">
                    </td>

        </form>
    </tr>

</table>
        <?
    }

    function variosEstudiantes($configuracion)
        {
        ?>
        <table class="contenidotabla centrar"  align="center">
        <tr>
            <td class="centrar">
                <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$_REQUEST['codEspacio'];
                    $variable.="&nroGrupo=".$_REQUEST['nroGrupo'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&nombreEspacio=".$_REQUEST['nombreEspacio'];
                    $variable.="&nroCreditos=".$_REQUEST['nroCreditos'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br><b>Atras</b>
            </a>
            </td>
            <td colspan="2" class="cuadro_brownoscuro centrar">
                REGISTRAR NUEVOS ESTUDIANTES A ESTE GRUPO
            </td>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
            <td width="15%" colspan="5" class="centrar">
                <input type="hidden" name="opcion" value="varios">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="hidden" name="codEspacio" value="<?echo $_REQUEST['codEspacio']?>">
                <input type="hidden" name="nroGrupo" value="<?echo $_REQUEST['nroGrupo']?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="nroCreditos" value="<?echo $_REQUEST['nroCreditos']?>">
                <input type="hidden" name="nombreEspacio" value="<?echo $_REQUEST['nombreEspacio']?>">

                <select id="nroEstudiantes" name="nroEstudiantes" onchange="submit()">
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
        <tr class="cuadro_brownOscuro cuadro_plano centrar">
            <td width="15%">
                C&oacute;digo del Estudiante
            </td>
            <td colspan="2">
                Datos Estudiante
            </td>
            <td width="15%">
                Registrar
            </td>
        </tr>
        
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <?
            if($_REQUEST['nroEstudiantes'])
                {
                    for($j=1;$j<=$_REQUEST['nroEstudiantes'];$j++)
                    {
                        ?>
                            <tr class="cuadro_brownOscuro cuadro_plano centrar">
                                <td>
                                    <input type="text" id="codEstudiante-<?echo $j?>" name="codEstudiante-<?echo $j?>" onchange="xajax_nombreEstudiante(document.getElementById('codEstudiante-<?echo $j?>').value,<?echo $j?>)" maxlength="11" size="11">
                                </td>
                                <td colspan="2">
                                    <div id="div_nombreEstudiante-<?echo $j?>" ></div>
                                </td>
                                    
                                
                                <?
                                    if($j==1)
                                        {?>
                                            <td  class="cuadro_plano" rowspan="<?echo $_REQUEST['nroEstudiantes']?>">
                                                <input type="hidden" name="opcion" value="registrarVarios">
                                                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                                <input type="hidden" name="codEspacio" value="<?echo $_REQUEST['codEspacio']?>">
                                                <input type="hidden" name="nroGrupo" value="<?echo $_REQUEST['nroGrupo']?>">
                                                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                                                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                                                <input type="hidden" name="nroCreditos" value="<?echo $_REQUEST['nroCreditos']?>">
                                                <input type="hidden" name="nombreEspacio" value="<?echo $_REQUEST['nombreEspacio']?>">
                                                <input type="hidden" name="nroEstudiantes" value="<?echo $_REQUEST['nroEstudiantes']?>">
                                                <input type="submit" value="Registrar">
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
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
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
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
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
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
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
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
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
                                                            break;
                                                       }else if(($inicial<0)&&(0<=$final))
                                                       {
                                                            $band=1;
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
<table class="contenidotabla">
    <tr>
        <td class="centrar"> SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</td>
    </tr>
</table>
        <?
        }

    function registrarEstudiante($configuracion)
        {
            unset($variablesVerifica);
            $variablesVerifica['codEstudiante']=$_REQUEST['codEstudiante'];
            $variablesVerifica['nroCreditos']=$_REQUEST['nroCreditos'];
            $variablesVerifica['codEspacio']=$_REQUEST['codEspacio'];
            $variablesVerifica['nombreEspacio']=$_REQUEST['nombreEspacio'];
            $variablesVerifica['codProyecto']=$_REQUEST['codProyecto'];
            $variablesVerifica['planEstudio']=$_REQUEST['planEstudio'];
            $variablesVerifica['nroGrupo']=$_REQUEST['nroGrupo'];
            $variablesVerifica['clasificacion']=$_REQUEST['clasificacion'];

//            var_dump($variablesVerifica);exit;
            $cadena_sql=$this->sql->cadena_sql($configuracion,"ano_periodo", "");//echo $cadena_sql_periodo;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesVerifica['anno']=$resultado_periodo[0][0];
            $variablesVerifica['periodo']=$resultado_periodo[0][1];
           
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante",$variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesVerifica['codProyectoEst']=$resultado_estudiante[0][0];
            $variablesVerifica['planEstudioEst']=$resultado_estudiante[0][1];

            $var_espacio=array($variablesVerifica['codEspacio'],$variablesVerifica['planEstudio']);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_planEstudio", $var_espacio);//echo $cadena_sql;exit;
            $resultado_datosEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            //var_dump($resultado_datosEspacio);exit;

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


            //Verifica que el estudiante pertenece a creditos
            $this->verificarEstudiante($configuracion,$variablesVerifica);

            //Verifica que no exceda los creditos permitidos en el periodo academico actual
            $this->verificarEspacioCreditos($configuracion,$variablesVerifica);

            //Verifica que no exista cruce con el horario que ya esta registrado
            $this->verificarEspacioCruce($configuracion,$variables,$variablesVerifica);

            //Verifica que el espacio academico pertenezca al plan de estudio del estudiante
            $this->verificarEspacioPlan($configuracion,$variablesVerifica);

            //Verifica que el espacio academico ya este aprobado por el estudiante
            $this->verificarEspacioAprobado($configuracion,$variables,$variablesVerifica);

            //Si se necesita verificar quel el espacio academico no haya sido reprobado
            /*
             * La siguiente linea de codigo se habilitara en caso de que sea aprobado por vicerrectoria
             * el cual permite verificar si el estudiante reprobo el espacio academico
             * en caso de ser asi, no deja adicionar el espacio academico
             */

//            $this->verificarEspacioReprobado($configuracion,$variables,$variablesVerifica);



            if(!$_REQUEST['funcionCancelado'])
                {
                    $cancelado=$this->verificarCancelado($configuracion, $variablesVerifica);
                }

            if(!$_REQUEST['funcionRequisitos'])
                {
                    $requisitos=$this->verificarRequisitos($configuracion,$variablesVerifica);
                }
            //var_dump($variablesVerifica);//exit;

            $cadena_sql=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variables);//echo $cadena_sql;exit;
            $resultado_cupo_grupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"adicionar_espacio_mysql", $variables);//echo $cadena_sql;exit;
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
                            $variablesRegistro[5]=$_REQUEST['codEstudiante'];

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);//echo $cadena_sql;exit;
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                            echo "<script>alert ('El espacio académico ".$variablesVerifica['codEspacio']."-".$variablesVerifica['nombreEspacio']." fue adicionado exitosamente. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarCIGrupoCoordinador";
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
                                $variable="pagina=adminConsultarCIGrupoCoordinador";
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
                        $variable="pagina=adminConsultarCIGrupoCoordinador";
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
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                    $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                    $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                    $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                    $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                    $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }

        }

    function verificarEspacioCreditos($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEspaciosEstudiante", $variablesVerifica);//echo $cadena_sql;exit;
            $resultado_Espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            //Se puede cambiar el numero de creditos para el periodo academico

            //$creditosPermitidos='18';
            $creditosPermitidos='4';

            if(is_array($resultado_Espacios))
                {
                    for($i=0;$i<count($resultado_Espacios);$i++)
                    {
                        $creditos+=$resultado_Espacios[$i][1];
                    }
                    $creditosTotal=$creditos+$variablesVerifica['nroCreditos'];
                }

            if($creditosTotal>$creditosPermitidos)
                {
                    echo "<script>alert ('No se pueden inscribir más de ".$creditosPermitidos." créditos por periodo académico para cada estudiante. No se puede inscribir el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCIGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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

    function verificarEspacioCruce($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (!is_array($resultado_EspacioOracle))
                {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variables);//echo $cadena_sql;exit;
                $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupo_nuevo", $variables);//echo $cadena_sql;exit;
                $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                for($i=0;$i<count($resultado_horario_registrado);$i++)
                {
                    for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++)
                    {

                        if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                            echo "<script>alert ('El espacio académico [".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."] presenta cruce con el horario del estudiante. No se ha realizado la inscripción');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCIGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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
                    echo "<script>alert ('El espacio académico ya fue adicionado para el estudiante ".$variables[0]." en el grupo ".$resultado_EspacioOracle[0][3]."');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCIGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
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
                            $variable="pagina=adminConsultarCIGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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

    function verificarEspacioReprobado($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_reprobado", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioReprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (is_array($resultado_EspacioReprobado))
                {
                            echo "<script>alert ('El espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." fue reprobado por el estudiante en el periodo ".$resultado_EspacioAprobado[0][2]."-".$resultado_EspacioAprobado[0][3].". No se puede adicionar');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCIGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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

    function verificarEspacioPlan($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);
            $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_espacioPlan))
                {
                    echo "<script>alert ('El espacio académico [".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']."] no pertenece al plan de estudio del estudiante. No se puede inscribir el espacio académico');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCIGrupoCoordinador";
                            $variable.="&opcion=verGrupo";
                            $variable.="&opcion2=cuadroRegistro";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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
                                                ?>
                                                <script type="text/javascript">
                                                    if(confirm('El estudiante curso y perdio el espacio académico <? echo $resultado_requisito[$i][1]." - ".utf8_encode($resultado_requisitoNoCursado[0][0])?>  que es requisito de <?echo $variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']?>\n¿Desea inscribirlo?'))
                                                        {
                                                            <?
                                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                $variable="pagina=registroAdicionarCIGrupoCoordinador";
                                                                $variable.="&opcion=estudianteRegistrar";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
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
                                                                $variable="pagina=adminConsultarCIGrupoCoordinador";
                                                                $variable.="&opcion=verGrupo";
                                                                $variable.="&opcion2=cuadroRegistro";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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
                                                                $variable="pagina=registroAdicionarCIGrupoCoordinador";
                                                                $variable.="&opcion=estudianteRegistrar";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
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
                                                                $variable="pagina=adminConsultarCIGrupoCoordinador";
                                                                $variable.="&opcion=verGrupo";
                                                                $variable.="&opcion2=cuadroRegistro";
                                                                $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                                                $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                                $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                                                $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

                                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                $this->cripto=new encriptar();
                                                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                                ?>
                                                            location.replace('<?echo $pagina.$variable?>');


                                                        }
                                                    </script>
                                            <?}
                                                }
                        }
                    }

                }exit;
                }
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
                            $variable="pagina=registroAdicionarCIGrupoCoordinador";
                            $variable.="&opcion=estudianteRegistrar";
                            $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                            $variable.="&codEstudiante=".$variablesVerifica['codEstudiante'];
                            $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                            $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];
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
                                        $variable="pagina=adminConsultarCIGrupoCoordinador";
                                        $variable.="&opcion=verGrupo";
                                        $variable.="&opcion2=cuadroRegistro";
                                        $variable.="&codEspacio=".$variablesVerifica['codEspacio'];
                                        $variable.="&nroGrupo=".$variablesVerifica['nroGrupo'];
                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];
                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                        $variable.="&nombreEspacio=".$variablesVerifica['nombreEspacio'];
                                        $variable.="&nroCreditos=".$variablesVerifica['nroCreditos'];

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

    function registrarVarios($configuracion)
        {
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

            $this->encabezadoGrupo($configuracion, $atributos);
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
                        $validacionCreditos=$this->verificarEspacioCreditosVarios($configuracion,$variablesVerifica);
                        if($validacionCreditos=="true")
                            {
                                $validacionCruce=$this->verificarEspacioCruceVarios($configuracion,$variables,$variablesVerifica);
                                if($validacionCruce=="true")
                                    {
                                        $validacionAprobado=$this->verificarEspacioAprobadoVarios($configuracion,$variables,$variablesVerifica);
                                        if($validacionAprobado=="true")
                                            {
                                            /*
                                             * Esta funcion se deja comentada para que no valide si el estudiante reprobo el espacio academico
                                             */
//                                                $validacionReprobado=$this->verificarEspacioReprobadoVarios($configuracion,$variables,$variablesVerifica);
//                                                if($validacionReprobado=="true")
//                                                {
                                                $validacionPlan=$this->verificarEspacioPlanVarios($configuracion,$variablesVerifica);
                                                if($validacionPlan=="true")
                                                    {
                                                    $validacionRequisitos=$this->verificarRequisitosVarios($configuracion,$variablesVerifica);
                                                    if($validacionRequisitos=="true")
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

                                                                    $reporteExitos[$exitos]=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico se inscribio exitosamente";
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
                                                            $reporteNoExitos[$noexitos]=$validacionRequisitos['reporte'];
                                                            $noexitos++;
                                                        }
                                            }else
                                                        {
                                                            $reporteNoExitos[$noexitos]=$validacionPlan['reporte'];
                                                            $noexitos++;
                                                        }
                                                        /*
                                                         * Esta funcion se deja comentada para que no valide si el estudiante reprobo el espacio academico
                                                         */
//                                        }else
//                                                        {
//                                                            $reporteNoExitos[$noexitos]=$validacionReprobado['reporte'];
//                                                            $noexitos++;
//                                                        }
                                    }else
                                                        {
                                                            $reporteNoExitos[$noexitos]=$validacionAprobado['reporte'];
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
                                                            $reporteNoExitos[$noexitos]=$validacionEstudiante['reporte'];
                                                            $noexitos++;
                                                        }

            }

            $this->generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variablesVerifica);
        }

    function verificarEstudianteVarios($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_estudiante))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudianteHoras", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][1]).",El estudiante no pertenece al sistema de cr&eacute;ditos";
                    return $variableRetorno;
                    break;
                }else
                    {
                        return "true";
                    }

        }

    function verificarEspacioCreditosVarios($configuracion,$variablesVerifica)
        {
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

            if($creditosTotal>'18')
                {
                    
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El número de créditos es mayor al permitido";
                    return $variableRetorno;
                    break;
                }else
                    {
                        return "true";
                    }
       }

    function verificarEspacioCruceVarios($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (!is_array($resultado_EspacioOracle)) {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variables);//echo $cadena_sql_horario_registrado;exit;
                $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupo_nuevo", $variables);
                $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                $band=0;
                for($i=0;$i<count($resultado_horario_registrado);$i++) {
                    for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                        if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico presenta cruce con el horario del estudiante";
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

    function verificarEspacioAprobadoVarios($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_aprobado", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioAprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (is_array($resultado_EspacioAprobado))
                {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico fue aprobado en el periodo ".$resultado_EspacioAprobado[0][2]."-".$resultado_EspacioAprobado[0][3];
                $band=1;
                return $variableRetorno;
                break;
                }
                else
                    {
                        return "true";
                    }
        }
       
    function verificarEspacioReprobadoVarios($configuracion,$variables,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_espacio_reprobado", $variables);//echo $cadena_sql;exit;
            $resultado_EspacioReprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if (is_array($resultado_EspacioReprobado))
                {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico fue reprobado en el periodo ".$resultado_EspacioAprobado[0][2]."-".$resultado_EspacioAprobado[0][3];
                $band=1;
                return $variableRetorno;
                break;
                }
                else
                    {
                        return "true";
                    }
       }
       
    function verificarEspacioPlanVarios($configuracion,$variablesVerifica)
        {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_planEstudiante",$variablesVerifica);//echo $cadena_sql;exit;
            $resultado_espacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($resultado_espacioPlan))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                    $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El espacio académico no pertenece al plan de estudios del estudiante";

                    return $variableRetorno;
                }else
                    {
                        return "true";
                    }
        }

    function verificarRequisitosVarios($configuracion,$variablesVerifica)
        {
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
                                            return "true";
                                        }else
                                            {
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"curso_no_cursado", $variablesRequisito);//echo $cadena_sql;exit;
                                                $resultado_requisitoNoCursado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variablesVerifica['codEstudiante']);//echo $cadena_sql;exit;
                                                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableRetorno['reporte']=$variablesVerifica['codEstudiante'].",".htmlentities($resultado_estudiante[0][2]).",".htmlentities($resultado_estudiante[0][3]).",El estudiante no ha cursado los requisitos establecidos";

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
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosNue);
        $resultado_InscritosNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritosNue[2]=$resultado_InscritosNue[0][0];
        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosNue);
        $resultado_ActualizacionNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );
?>

<table class="contenidotabla">
   
    <?
//   var_dump($reporteNoExitos);
//   echo count($reporteNoExitos);

        if(is_array($reporteExitos))
            {
                echo "<tr class='cuadro_brownOscuro centrar'><td colspan='4'>REGISTROS EXITOSOS</td></tr>";
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
                                <?echo htmlentities($nombreEstudiante)?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo htmlentities($proyectoEstudiante)?>
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
                echo "<tr class='cuadro_brownOscuro centrar'><td colspan='4'>REGISTROS NO EXITOSOS</td></tr>";
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
                    $variablesRegistro[3]='No pudo adicionar, problemas estudiante';
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
                                <?echo htmlentities($nombreEstudianteNo)?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo htmlentities($proyectoEstudianteNo)?>
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
<table class="contenidotabla">
<tr class="centrar">
    
    <td width="33%" colspan="3">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarCIGrupoCoordinador";
        $variable.="&opcion=verGrupo";
        $variable.="&opcion2=cuadroRegistro";
        $variable.="&codEspacio=".$atributos['codEspacio'];
        $variable.="&nroGrupo=".$atributos['nroGrupo'];
        $variable.="&planEstudio=".$atributos['planEstudio'];
        $variable.="&codProyecto=".$atributos['codProyecto'];
        $variable.="&nombreEspacio=".$atributos['nombreEspacio'];
        $variable.="&nroCreditos=".$atributos['nroCreditos'];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);
        ?>
            <a href="<?echo $pagina.$variable?>">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0" alt="inicio">
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
<tr class="cuadro_brownOscuro centrar">
    <td colspan="3">
        REPORTE DE INSCRIPCIONES
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
                <table class="contenidotabla">
                    <tr>
                        <td class="cuadro_brownOscuro centrar" colspan="2">Datos Generales</td>
                    </tr>
                    <tr>
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
            <table class="contenidotabla">
                        <tr>
                            <td colspan="11">
                                <hr align="center">
                            </td>
                        </tr>
                        <tr>
                            <td class="cuadro_brownOscuro centrar" width="10%">C&oacute;digo</td>
                            <td class="cuadro_brownOscuro centrar" width="30%" colspan="5">Nombre Espacio Acad&eacute;mico</td>
                            <td class="cuadro_brownOscuro centrar" width="10%">Nro Cr&eacute;ditos</td>
                            <td class="cuadro_brownOscuro centrar" width="10%">H.T.D</td>
                            <td class="cuadro_brownOscuro centrar" width="10%">H.T.C</td>
                            <td class="cuadro_brownOscuro centrar" width="10%">H.T.A</td>
                        </tr>

                <?
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$variables[0]);//echo $cadena_sql;exit;
                    $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    ?>

                        <tr>
                            <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][1]?></font></td>
                            <td class="cuadro_brownOscuro centrar" colspan="5"><font size="2"><?echo $resultado_espaciosDesc[0][2]?></font></td>
                            <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][3]?></font></td>
                            <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][4]?></font></td>
                            <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][5]?></font></td>
                            <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][6]?></font></td>
                        </tr>

                        <tr>
                            <td class="cuadro_color centrar" >Nro Grupo</td>
                            <td class="cuadro_color centrar" width="12">Lunes</td>
                            <td class="cuadro_color centrar" width="12">Martes</td>
                            <td class="cuadro_color centrar" width="12">Miercoles</td>
                            <td class="cuadro_color centrar" width="12">Jueves</td>
                            <td class="cuadro_color centrar" width="12">Viernes</td>
                            <td class="cuadro_color centrar" width="12">Sabado</td>
                            <td class="cuadro_color centrar" width="12">Domingo</td>
                            <td class="cuadro_color centrar" width="12">Nro Cupos</td>
                            <td class="cuadro_color centrar" width="12">Disponibles</td>
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
                    <tr>
                        <td class="cuadro_plano centrar"><?echo $variables[1]?></td>
                    <?
                        $this->mostrarHorario($configuracion,$resultado_horarios);

                    ?>
                        <td class="cuadro_plano centrar"><?echo $resultado_espacios[0][3]?></td>
                        <td class="cuadro_plano centrar"><?echo $resultado_espacios[0][3]-$resultado_espacios[0][4]?></td>
                    </tr>
            </table>
                        <?
        }

    function mostrarHorario($configuracion,$resultado_horarios)
        {
                for($i=1; $i<8; $i++) {
                                ?><td class='cuadro_plano centrar'><?
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