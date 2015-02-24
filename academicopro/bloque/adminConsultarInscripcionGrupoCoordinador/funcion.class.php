<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

/**
 * Clase funcion_adminConsultarInscripcionGrupoCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class funcion_adminConsultarInscripcionGrupoCoordinador extends funcionGeneral
{
        /**
         * Método constructor que crea el objeto sql de la clase funcion_adminConsultarInscripcionGrupoCoordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function __construct($configuracion)
            {
	    /**
             * Incluye la clase encriptar.class.php
             *
             * Esta clase incluye funciones de encriptacion para las URL
             */
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            /**
             * Incluye la clase validar_fechas.class.php
             *
             * Esta clase incluye funciones que permiten validar si las fechas de adiciones y cancelaciones estan abiertas o no
             */
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");

            $this->fechas=new validar_fechas();
            
	    $this->cripto=new encriptar();

	    $this->sql=new sql_adminConsultarInscripcionGrupoCoordinador();
            
            $this->formulario="adminConsultarInscripcionGrupoCoordinador";

            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");
            /**
             * Instancia para crear la conexion de MySQL
             */
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            /**
             * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
             */
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

            /**
             * Datos de sesion
             */
	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

	}

        /**
         * Funcion que se encarga de mostrar los datos del espacio academico previamente seleccionado
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @global int '$_REQUEST['codEspacio']' Codigo del espacio academico
         * @global int '$_REQUEST['nroGrupo']' Numero de grupo que se desea consultar
         * @global int '$_REQUEST['planEstudio']' Numero del plan de estudio
         * @global string '$_REQUEST['nombreEspacio']' Nombre del espacio academico
         * @global int '$_REQUEST['nroCreditos']' Numero de creditos del espacio academico
         * @global int '$_REQUEST['codProyecto']' Codigo del proyecto curricular
         */
 	function mostrarDatosGrupo($configuracion)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$_REQUEST['codEspacio']);//echo $cadena_sql;exit;
                $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                $variables=array($_REQUEST['codEspacio'],$_REQUEST['nroGrupo'],$_REQUEST['planEstudio'],$_REQUEST['nombreEspacio'],$_REQUEST['nroCreditos'],$_REQUEST['codProyecto'],$resultado_clasif[0][7]);
                
                if($_REQUEST['planEstudio'])
                    {
                        $this->datosGeneralesProyecto($configuracion,$_REQUEST['planEstudio'],$variables);
                    }

                if($_REQUEST['codEspacio'] && $_REQUEST['nroGrupo'])
                    {
                        $variables=array($_REQUEST['codEspacio'],$_REQUEST['nroGrupo'],$_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$resultado_clasif[0][7]);
                        $this->datosGeneralesEspacioGrupo($configuracion,$variables);
                    }

                    $variables=array($_REQUEST['codEspacio'],$_REQUEST['nroGrupo'],$_REQUEST['planEstudio'],$_REQUEST['nombreEspacio'],$_REQUEST['nroCreditos'],$_REQUEST['codProyecto'],$resultado_clasif[0][7]);

                    $registro_permisos=$this->fechas->validar_fechas_grupo_coordinador($configuracion,$_REQUEST['codProyecto']);

                     switch ($registro_permisos)
                     {
                         case 'adicion':
                                  $this->estudiantesRegistradosAdiciones($configuracion,$variables);
                             break;

                         case 'cancelacion':
                                  $this->estudiantesRegistradosCancelacion($configuracion,$variables);
                             break;

                         case 'consulta':
                                  $this->estudiantesRegistradosConsulta($configuracion,$variables);
                             break;

                         default:
                                  $this->estudiantesRegistradosConsulta($configuracion,$variables);
                             break;
                     }
            }

        /**
         * Funcion que muestra los datos generales del proyecto curricular y los datos del espacio academico
         * 
         * Muestra datos del proyecto curricular y los datos del espacio academico como numero de creditos, htd, thc, hta
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param int $planEstudio Numero del plan de estudio
         * @param array $variables Variable con informacion del espacio academico
         * @param int $variables[0] Codigo del espacio academico
         * @param int $variables[1] Numero de grupo que se desea consultar
         * @param int $variables[2] Numero del plan de estudio
         * @param string $variables[3] Nombre del espacio academico
         * @param int $variables[4] Numero de creditos del espacio academico
         * @param int $variables[5] Codigo del proyecto curricular
         * @param int $variables[6] Clasificacion del espacio academico
         */
        function datosGeneralesProyecto($configuracion,$planEstudio,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datosProyecto", $planEstudio);//echo $cadena_sql;exit;
                $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$variables[0]);//echo $cadena_sql;exit;
                $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                if(is_array($resultado_proyecto))
                {
                    ?>
                    <table class="sigma centrar" width="100%">
                        <caption class="sigma">Datos Generales</caption>                        
                        <tr class="sigma cuadro_plano">
                            <td width="80%" colspan="2">
                                <font size="2"><b>Proyecto Curricular : </b><?echo $resultado_proyecto[0][2]." - ".$resultado_proyecto[0][1]?></font>
                            </td>
                            <td class="derecha" colspan="2" ><font size="2"><b>Plan Estudio : </b><?echo $resultado_proyecto[0][0]?></font></td>
                        </tr>
                        <tr class="sigma cuadro_plano centrar ">
                            <td colspan="4">
                                    <font size="3"><b><?echo $resultado_espaciosDesc[0][1]." - ".$resultado_espaciosDesc[0][2]?></b></font>
                            </td>                            
                        </tr >
                        <tr class="sigma cuadro_plano">
                            <td class="centrar" width="25%"><font size="2"><b>Nro Cr&eacute;ditos: </b><?echo $resultado_espaciosDesc[0][3]?></font></td>
                            <td class="centrar" width="25%"><font size="2"><b>H.T.D: </b><?echo $resultado_espaciosDesc[0][4]?></font></td>
                            <td class="centrar" width="25%"><font size="2"><b>H.T.C: </b><?echo $resultado_espaciosDesc[0][5]?></font></td>
                            <td class="centrar" width="25%"><font size="2"><b>H.T.A: </b><?echo $resultado_espaciosDesc[0][6]?></font></td>
                        </tr>
                    </table>
                            <?
                }
            }

        /**
         * Muestra los datos generales del espacio academico, asi mismo muestra los datos de otros grupos que tiene a cargo el coordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param array $variables Variable con informacion del espacio academico
         * @param int $variables[0] Codigo del espacio academico
         * @param int $variables[1] Numero de grupo que se desea consultar
         * @param int $variables[2] Numero del plan de estudio
         * @param string $variables[3] Nombre del espacio academico
         * @param int $variables[4] Numero de creditos del espacio academico
         * @param int $variables[5] Codigo del proyecto curricular
         * @param int $variables[6] Clasificacion del espacio academico
         */
        function datosGeneralesEspacioGrupo($configuracion,$variables)
            {
            ?>
                <table class="contenidotabla">
                    <?
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$variables[0]);//echo $cadena_sql;exit;
                        $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                        
                                $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_del_espacio_academico",$variables);//echo $cadena_sql;exit;
                                $resultado_gruposDelEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                if(is_array($resultado_gruposDelEspacio))
                                    {
                                  if (count($resultado_gruposDelEspacio)>1){
                                  ?>
                                        <tr><th class="sigma_a centrar" colspan="10" >
                                            OTROS GRUPOS DEL ESPACIO ACAD&Eacute;MICO <?echo strtoupper($resultado_espaciosDesc[0][2])?>
                                          </th></tr>
                                    <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                                    <tr class="centrar">
                                        <td colspan="10" class="cuadro_plano">
                                        <table class="contenidotabla centrar">
                                    <?
                                    $celdas=10/count($resultado_gruposDelEspacio);

                                    for($h=0;$h<count($resultado_gruposDelEspacio);$h++)
                                    {

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                        $variable.="&opcion=verGrupo";
                                        $variable.="&opcion2=cuadroRegistro";
                                        $variable.="&codEspacio=".$resultado_espaciosDesc[0][1];
                                        $variable.="&nombreEspacio=".$resultado_espaciosDesc[0][2];
                                        $variable.="&nroCreditos=".$resultado_espaciosDesc[0][3];
                                        $variable.="&nroGrupo=".$resultado_gruposDelEspacio[$h][0];
                                        $variable.="&planEstudio=".$variables[2];
                                        $variable.="&codProyecto=".$variables[3];
                                        $variable.="&clasificacion=".$variables[4];

                                        //var_dump($_REQUEST);exit;
                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                        ?>
                                            <td class="centrar" width="<?echo $celdas;?>%" onmouseover="this.border='1';this.bgColor='#ABB0BE'" onmouseout="this.border='0';this.bgColor=''"  onclick="location.replace('<?echo $pagina.$variable?>')">
                                                <a href="<?echo $pagina.$variable?>" style="color:#190707">
                                                    <font size="3"><b><?echo $resultado_gruposDelEspacio[$h][0]?></b></font>
                                            </a>
                                        </td>
                                        <?
                                    }
                                        ?></table></td></tr><?

                                  }

                                    }

                                ?>
                            <tr>
                                <th class="sigma centrar" width="12" >Nro Grupo</th>
                                <th class="sigma centrar" width="12">Lunes</th>
                                <th class="sigma centrar" width="12">Martes</th>
                                <th class="sigma centrar" width="12">Miercoles</th>
                                <th class="sigma centrar" width="12">Jueves</th>
                                <th class="sigma centrar" width="12">Viernes</th>
                                <th class="sigma centrar" width="12">Sabado</th>
                                <th class="sigma centrar" width="12">Domingo</th>
                                <th class="sigma centrar" width="12">Nro Cupos</th>
                                <th class="sigma centrar" width="12">Disponibles</th>
                            </tr><?
                            $variablesEspacio=array($variables[0],$variables[1]);
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupo",$variablesEspacio);//echo $cadena_sql;exit;
                            $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos",$variablesEspacio);//echo $cadena_sql;exit;
                            $resultado_inscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosProyecto", $variables[2]);//echo $cadena_sql;exit;
                            $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $variablesHorario=array($variables[0],$variables[3],'',$variables[1]);

                            $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variablesHorario);//echo $cadena_sql_horarios;exit;
                            $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                        ?>
                        <tr>
                            <td class="cuadro_plano centrar"><?echo $variables[1]?></td>
                        <?
                            $this->mostrarHorario($configuracion,$resultado_horarios);

                        ?>
                            <td class="cuadro_plano centrar"><?echo $resultado_espacios[0][3]?></td>
                            <td class="cuadro_plano centrar"><?echo $resultado_espacios[0][3]-$resultado_inscritos[0][0]?></td>
                        </tr>
                </table>
                            <?
            }

        /**
         * Muestra el horario del espacio academico y grupo seleccionado
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param array $resultado_horarios Arreglo que contiene datos del horario
         */
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

        /**
         * Funcion que muestra los estudiantes que estan registrados para el espacio academico y grupo especificado
         *
         * Muestra los estudiantes que estan registrados y a los cuales puede adicionar espacios academicos
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param array $variables Variable con informacion del espacio academico
         * @param int $variables[0] Codigo del espacio academico
         * @param int $variables[1] Numero de grupo que se desea consultar
         * @param int $variables[2] Numero del plan de estudio
         * @param string $variables[3] Nombre del espacio academico
         * @param int $variables[4] Numero de creditos del espacio academico
         * @param int $variables[5] Codigo del proyecto curricular
         * @param int $variables[6] Clasificacion del espacio academico
         */
        function estudiantesRegistradosAdiciones($configuracion,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesInscritos", $variables);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_estudiante))
                    {
                        ?>
                                
                           <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                                <table class="contenidotabla cuadro_plano">
                                    <caption class="sigma">Estudiantes Inscritos</caption>
                                    <tr class="sigma centrar">
                                        <th class="sigma" width="5%" >Nro</th>
                                        <th class="sigma" width="5%">
                                            <!--<script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>-->
                                            Seleccionar
                                            <br>
                                            <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'adminConsultarInscripcionGrupoCoordinador');">
                                            <!--<input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="<?echo $this->check?>">-->
                                        </th>
                                        <th class="sigma" width="10%" >C&oacute;digo</th>
                                        <th class="sigma" width="30%" >Nombre</th>
                                        <th class="sigma" width="20%" >Proyecto Curricular</th>
                                        <th class="sigma" width="5%" >Clasificaci&oacute;n&nbsp;</th>
                                        <th class="sigma" width="5%" >Estado</th>
                                        <th class="sigma" width="10%" >Cambiar Grupo</th>
                                        <th class="sigma" width="10%" >Cancelar</th>
                                    </tr>
                                    
                                    
                        <?
                        for($i=0;$i<count($resultado_estudiante);$i++)
                        {
                            
                            ?><tr class="cuadro_planoPequeño">
                                <td class="centrar"><?echo $i+1?></td>
                                <?
                                if(trim($resultado_estudiante[$i][3])=='S'){
                                ?>
                                <td class="centrar"><input type="checkbox" name="codEstudiante-<?echo $i?>" value="<?echo $resultado_estudiante[$i][0]?>"></td>
                                <?
                                }else
                                    {
                                        ?>
                                            <td class="centrar"></td>
                                        <?
                                    }
                                ?>
                                
                                <td class="centrar"><?echo $resultado_estudiante[$i][0]?></td>
                                <td class="izquierda"><?echo htmlentities(utf8_decode($resultado_estudiante[$i][1]))?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][2])?></td>
                                <td class="centrar"><?echo $resultado_estudiante[$i][4]?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][5])?></td>
                                
                                    <?
                                    
                                    
                                    if(trim($resultado_estudiante[$i][3])=='S')
                                        {
                                            $atributos['cambiar']=true;
                                        }else
                                            {
                                                $atributos['cambiar']=false;
                                                $atributos['horas']=true;
                                            }
                                        $atributos['pagina']="pagina=registroCambiarGrupoInscripcionGrupoCoordinador";
                                        $atributos['opcion']="&opcion=estudiante";
                                        $atributos['parametros']="&codEstudiante=".$resultado_estudiante[$i][0]."&planEstudio=".$variables[2]."&nroCreditos=".$variables[4];
                                        $atributos['parametros'].="&codEspacio=".$variables[0]."&nroGrupo=".$variables[1]."&nombreEspacio=".$variables[3];
                                        $atributos['parametros'].="&nombreEstudiante=".$resultado_estudiante[$i][1];
                                        $atributos['parametros'].="&clasificacion=".$resultado_estudiante[$i][4];
                                        $atributos['parametros'].="&proyecto=".$variables[5];

                                        $this->redireccionarEstudiante($configuracion, $atributos);
                                        unset($atributos);
                                    
                                    if(trim($resultado_estudiante[$i][3])=='S')
                                        {
                                            $atributos['cancelar']=true;
                                        }else
                                            {
                                                $atributos['cancelar']=false;
                                            }
                                        $atributos['pagina']="pagina=registroCancelarInscripcionGrupoEstudCoordinador";
                                        $atributos['opcion']="&opcion=verificaEstudiante";
                                        $atributos['parametros']="&codEstudiante=".$resultado_estudiante[$i][0]."&planEstudio=".$variables[2]."&nroCreditos=".$variables[4];
                                        $atributos['parametros'].="&codEspacio=".$variables[0]."&nroGrupo=".$variables[1]."&nombreEspacio=".$variables[3]."&proyecto=".$variables[5];
                                        $atributos['parametros'].="&nombreEstudiante=".htmlentities($resultado_estudiante[$i][1]);
                                        $atributos['parametros'].="&clasificacion=".$resultado_estudiante[$i][4];

                                        $this->redireccionarEstudiante($configuracion, $atributos);
                                        unset($atributos);
                                    ?>
                                    
                                </tr>
                            <?
                        }
                        $resultado['totalEstudiantes']=count($resultado_estudiante);
                        $resultado['planEstudio']=$variables[2];
                        $resultado['nroGrupo']=$variables[1];
                        $resultado['codEspacio']=$variables[0];
                        ?>
                                </table>
       <table class="sigma" width="100%">
            <tr class="sigma centrar">
                <td class="sigma izquierda">
                    Para los estudiantes seleccionados
                    <select class="sigma" id="accionCoordinador" name="accionCoordinador" onchange="submit()">
                        <option value="0" selected>Seleccione...</option>
                        <option value="cambiar">Cambiar de Grupo</option>
                        <option value="cancelar">Cancelar el Espacio Acad&eacute;mico</option>
                    </select>
                    <input type="hidden" name="opcion" value="grupoSeleccionado">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="total" value="<?echo $resultado['totalEstudiantes']?>">
                    <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                    <input type="hidden" name="nroGrupo" value="<?echo $variables[1]?>">
                    <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                    <input type="hidden" name="nombreEspacio" value="<?echo $variables[3]?>">
                    <input type="hidden" name="nroCreditos" value="<?echo $variables[4]?>">
                    <input type="hidden" name="proyecto" value="<?echo $variables[5]?>">
                    <input type="hidden" name="clasificacion" value="<?echo $variables[6]?>">
                </td>
            </tr>
        </table>
                    
        </form>
                        <?
                    }
                    
            }

        /**
         * Funcion que permite redireccionar a los diferentes procesos como adicion, cancelacion y cambio de grupo
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param array $atributos Variable con informacion del espacio academico
         * @param int $atributos['pagina'] Pagina a la cual sera referenciado
         * @param int $atributos['opcion'] Opcion que se le pasa para que seleccione la funcion
         * @param int $atributos['parametros'] Parametros que se deben pasar
         */
        function redireccionarEstudiante($configuracion,$atributos)
            {
          if($atributos['adiciones']==true)
              {
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable=$atributos['pagina'];
                $variable=$atributos['opcion'];
                $variable=$atributos['parametros'];
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?><td class="centrar">
                                    <a href="<?echo $pagina.$variable?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35" border="0" alt="Adicionar">
                                    </a>
                </td>
                <?
              }
          if($atributos['cambiar']==true)
              {
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable=$atributos['pagina'];
                $variable.=$atributos['opcion'];
                $variable.=$atributos['parametros'];
                
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                
                ?><td class="centrar">
                                    <a href="<?echo $pagina.$variable?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" width="25" height="25" border="0" alt="Cambiar Grupo">
                                    </a>
                </td>
                <?
              }
          if($atributos['cancelar']==true)
              {
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable=$atributos['pagina'];
                $variable.=$atributos['opcion'];
                $variable.=$atributos['parametros'];
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?><td class="centrar">
                                    <a href="<?echo $pagina.$variable?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="20" height="20" border="0" alt="Cancelar">
                                    </a>
                </td>
                <?
              }
          if($atributos['horas']==true)
              {
                ?>
                <td class="centrar" colspan="3">El estudiante pertenece a horas</td>
                <?
              }
        }

        /**
         * Funcion que muestra los estudiantes que estan registrados para el espacio academico y grupo especificado
         *
         * Muestra los estudiantes registrados y solo se pueden consultar
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param array $variables Variable con informacion del espacio academico
         * @param int $variables[0] Codigo del espacio academico
         * @param int $variables[1] Numero de grupo que se desea consultar
         * @param int $variables[2] Numero del plan de estudio
         * @param string $variables[3] Nombre del espacio academico
         * @param int $variables[4] Numero de creditos del espacio academico
         * @param int $variables[5] Codigo del proyecto curricular
         * @param int $variables[6] Clasificacion del espacio academico
         */
        function estudiantesRegistradosConsulta($configuracion,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesInscritos", $variables);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_estudiante))
                    {
                        ?>

                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <table class="contenidotabla cuadro_plano">

                          <caption class="sigma">Estudiantes Inscritos</caption>
                                    <tr class="sigma centrar">
                                        <th class="sigma" width="10%" >Nro</th>
                                        <th class="sigma"  width="10%">C&oacute;digo</th>
                                        <th class="sigma"  width="30%">Nombre</th>
                                        <th class="sigma"  width="30%">Proyecto<br>Curricular</th>
                                        <th class="sigma"  width="10%">Clasificaci&oacute;n</th>
                                        <th class="sigma" width="10%" >Estado</th>
                                    </tr>
                        <?
                        for($i=0;$i<count($resultado_estudiante);$i++)
                        {
                            ?><tr class="cuadro_planoPequeño">
                                <td class="centrar"><?echo $i+1?></td>
                                <td class="centrar"><?echo $resultado_estudiante[$i][0]?></td>
                                <td class="izquierda"><?echo htmlentities(utf8_decode($resultado_estudiante[$i][1]))?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][2])?></td>
                                <td class="centrar"><?echo $resultado_estudiante[$i][4]?></td>
                                <td class="centrar"><?echo $resultado_estudiante[$i][5]?></td>
                              </tr>
                            <?
                        }
                        $resultado['totalEstudiantes']=count($resultado_estudiante);
                        $resultado['planEstudio']=$variables[2];
                        $resultado['nroGrupo']=$variables[1];
                        $resultado['codEspacio']=$variables[0];
                        ?>
                                </table>
        

        </form>
                        <?
                    }

            }

        /**
         * Funcion que muestra los estudiantes que estan registrados para el espacio academico y grupo especificado
         *
         * Muestra los estudiantes registrados y que se les pueden hacer cancelaciones
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         * @param array $variables Variable con informacion del espacio academico
         * @param int $variables[0] Codigo del espacio academico
         * @param int $variables[1] Numero de grupo que se desea consultar
         * @param int $variables[2] Numero del plan de estudio
         * @param string $variables[3] Nombre del espacio academico
         * @param int $variables[4] Numero de creditos del espacio academico
         * @param int $variables[5] Codigo del proyecto curricular
         * @param int $variables[6] Clasificacion del espacio academico
         */
        function estudiantesRegistradosCancelacion($configuracion,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesInscritos", $variables);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_estudiante))
                    { 
                        ?>

                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <table class="sigma contenidotabla cuadro_plano">

                                    <caption class="sigma centrar">Estudiantes Inscritos</caption>
                                    <tr class="sigma centrar">
                                        <th class="sigma" width="5%" >Nro</th>
                                        <th class="sigma" width="5%">
                                            <!--<script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>-->
                                            Seleccionar
                                            <br>
                                            <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'adminConsultarInscripcionGrupoCoordinador');">
                                            <!--<input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="<?echo $this->check?>">-->
                                        </th>
                                        <th class="sigma" width="10%">C&oacute;digo</th>
                                        <th class="sigma" width="35%">Nombre</th>
                                        <th class="sigma" width="25%">Proyecto<br>Curricular</th>
                                        <th class="sigma" width="5%">Clasificaci&oacute;n&nbsp;</th>
                                        <th class="sigma" width="5%">Estado</th>
                                        <th class="sigma" width="10%">Cancelar</th>
                                    </tr>
                        <?
                        for($i=0;$i<count($resultado_estudiante);$i++)
                        {
                            ?><tr class="cuadro_planoPequeño">
                                <td class="centrar"><?echo $i+1?></td>
                                <?
                                if(trim($resultado_estudiante[$i][3])=='S'){
                                ?>
                                <td class="centrar"><input type="checkbox" name="codEstudiante-<?echo $i?>" value="<?echo $resultado_estudiante[$i][0]?>"></td>
                                <?
                                }else
                                    {
                                        ?>
                                        <td class="centrar"></td>
                                        <?
                                    }
                                ?>
                                <td class="centrar"><?echo $resultado_estudiante[$i][0]?></td>
                                <td class="izquierda"><?echo htmlentities(utf8_decode($resultado_estudiante[$i][1]))?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][2])?></td>
                                <td class="centrar"><?echo $resultado_estudiante[$i][4]?></td>
                                <td class="centrar"><?echo $resultado_estudiante[$i][5]?></td>
                                
                                    <?
                                    if(trim($resultado_estudiante[$i][3])=='S')
                                        {
                                            $atributos['cancelar']=true;
                                        }else
                                            {
                                                $atributos['horas']=true;
                                            }

                                            $atributos['pagina']="pagina=registroCancelarInscripcionGrupoEstudCoordinador";
                                            $atributos['opcion']="&opcion=verificaEstudiante";
                                            $atributos['parametros']="&codEstudiante=".$resultado_estudiante[$i][0]."&planEstudio=".$variables[2]."&nroCreditos=".$variables[4];
                                            $atributos['parametros'].="&codEspacio=".$variables[0]."&nroGrupo=".$variables[1]."&nombreEspacio=".$variables[3]."&proyecto=".$variables[5];
                                            $atributos['parametros'].="&nombreEstudiante=".htmlentities($resultado_estudiante[$i][1]);
                                            $atributos['parametros'].="&clasificacion=".$resultado_estudiante[$i][4];

                                            $this->redireccionarEstudiante($configuracion, $atributos);
                                            unset($atributos);

                                    ?>
                                    
                                </tr>
                            <?
                        }
                        $resultado['totalEstudiantes']=count($resultado_estudiante);
                        $resultado['planEstudio']=$variables[2];
                        $resultado['nroGrupo']=$variables[1];
                        $resultado['codEspacio']=$variables[0];
                        ?>
                                </table>
        <table class="sigma contenidotabla centrar" width="100%">
            <tr class="sigma centrar">
                <td>Para los estudiantes seleccionados
                    <select class="sigma" id="accionCoordinador" name="accionCoordinador" onchange="submit()">
                        <option value="0">Seleccione...</option>
                        <option value="cancelar">Cancelar el Espacio Acad&eacute;mico</option>
                    </select>
                    <input type="hidden" name="opcion" value="grupoSeleccionado">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="total" value="<?echo count($resultado_estudiante)?>">
                    <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                    <input type="hidden" name="nroGrupo" value="<?echo $variables[1]?>">
                    <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                    <input type="hidden" name="proyecto" value="<?echo $variables[5]?>">
                    <input type="hidden" name="nombreEspacio" value="<?echo $variables[3]?>">
                    <input type="hidden" name="clasificacion" value="<?echo $variables[6]?>">
                    <input type="hidden" name="nroCreditos" value="<?echo $variables[4]?>">
                </td>
            </tr>
        </table>

        </form>
                        <?
                    }

            }
}
?>
