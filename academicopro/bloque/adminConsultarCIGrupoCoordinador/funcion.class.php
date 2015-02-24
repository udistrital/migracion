
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");


#Realiza la preparacion del formulario para la validacion de javascript

?>

<?
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminConsultarCIGrupoCoordinador extends funcionGeneral
{

 	//@ Método costructor que crea el objeto sql de la clase sql_noticia
	function __construct($configuracion)
            {
	    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
	    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
	    include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            

	    $this->cripto=new encriptar();
	    $this->tema=$tema;
	    $this->sql=new sql_adminConsultarCIGrupoCoordinador();
	    $this->log_us= new log();
            $this->formulario="adminConsultarCIGrupoCoordinador";


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

            #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

            //$this->check="javascript:todos('".$this->formulario."',this,'codEstudiante-')";

            //echo $this->usuarioSesion[0][0];

	}


        #muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante
 	function mostrarDatosGrupo($configuracion)
            {
                //var_dump($_REQUEST);
                if($_REQUEST['planEstudio'])
                    {
                        $this->datosGeneralesProyecto($configuracion,$_REQUEST['planEstudio']);
                    }

                if($_REQUEST['codEspacio'] && $_REQUEST['nroGrupo'])
                    {
                        $variables=array($_REQUEST['codEspacio'],$_REQUEST['nroGrupo'],$_REQUEST['planEstudio'],$_REQUEST['codProyecto']);
                        $this->datosGeneralesEspacioGrupo($configuracion,$variables);
                    }

                    $registroFecha=$this->consultarFechas($configuracion,$_REQUEST['planEstudio'],$_REQUEST['codProyecto']);
                    //var_dump($registroFecha);//exit;

                    $variables=array($_REQUEST['codEspacio'],$_REQUEST['nroGrupo'],$_REQUEST['planEstudio'],$_REQUEST['nombreEspacio'],$_REQUEST['nroCreditos'],$_REQUEST['codProyecto']);

                    switch(trim($registroFecha[0][0]))
                           {
                               case '100':
                                   $inicial=$registroFecha[0][1]-date('YmdHis');
                                   $final=$registroFecha[0][2]-date('YmdHis');

                                   if(($inicial>=0) && ($final>0))
                                       {
                                            $this->estudiantesRegistradosAdiciones($configuracion,$variables);
                                       }
                                       else if(($inicial<0) && ($final>0))
                                       {
                                            $this->estudiantesRegistradosAdiciones($configuracion,$variables);
                                       }
                                       else
                                           {
                                             $this->estudiantesRegistradosConsulta($configuracion,$variables);
                                           }
                                   break;

                               case '101':

                                   $inicial=$registroFecha[0][1]-date('YmdHis');
                                   $final=$registroFecha[0][2]-date('YmdHis');

                                   if(($inicial>=0) && ($final>0))
                                       {
                                             $this->estudiantesRegistradosCancelacion($configuracion,$variables);
                                       }else if(($inicial<0) && ($final>0))
                                       {
                                             $this->estudiantesRegistradosCancelacion($configuracion,$variables);
                                       }else
                                           {
                                             $this->estudiantesRegistradosConsulta($configuracion,$variables);
                                           }
                                   break;

                               case '0':
                                         $this->estudiantesRegistradosConsulta($configuracion,$variables);
                                       break;

                           }
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
                            <td class="cuadro_brownOscuro cuadro_plano centrar" colspan="2"><b>Datos Generales</b></td>
                        </tr>
                        <tr class="cuadro_color">
                            <td><b>Proyecto Curricular : <?echo $resultado_proyecto[0][2]." - ".$resultado_proyecto[0][1]?></b></td>
                        
                            <td class="derecha"><b>Plan Estudio : <?echo $resultado_proyecto[0][0]?></b></td>
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
                                <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">C&oacute;digo</td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar" width="30%" colspan="5">Nombre Espacio Acad&eacute;mico</td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">Nro Cr&eacute;ditos</td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">H.T.D</td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">H.T.C</td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">H.T.A</td>
                            </tr>

                    <?
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$variables[0]);//echo $cadena_sql;exit;
                        $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                        ?>

                            <tr>
                                <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][1]?></font></td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar" colspan="5"><font size="2"><?echo $resultado_espaciosDesc[0][2]?></font></td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][3]?></font></td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][4]?></font></td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][5]?></font></td>
                                <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][6]?></font></td>
                            </tr>
                            
                            <?
                                $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_del_espacio_academico",$variables);//echo $cadena_sql;exit;
                                $resultado_gruposDelEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                if(is_array($resultado_gruposDelEspacio))
                                    {?>
                                    <tr>
                                        <td class="centrar" colspan="10">
                                            <font size="2"><b>OTROS GRUPOS DEL ESPACIO ACAD&Eacute;MICO <?echo strtoupper($resultado_espaciosDesc[0][2])?></b></font>
                                        </td>
                                    </tr>
                                    <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                                    <tr class="centrar">
                                        <td colspan="10">
                                        <table class="contenidotabla centrar">
                                    <?
                                    $celdas=10/count($resultado_gruposDelEspacio);
                                    for($h=0;$h<count($resultado_gruposDelEspacio);$h++)
                                    {
                                        ?><?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarCIGrupoCoordinador";
                                        $variable.="&opcion=verGrupo";
                                        $variable.="&opcion2=cuadroRegistro";
                                        $variable.="&codEspacio=".$resultado_espaciosDesc[0][1];
                                        $variable.="&nombreEspacio=".$resultado_espaciosDesc[0][2];
                                        $variable.="&nroCreditos=".$resultado_espaciosDesc[0][3];
                                        $variable.="&nroGrupo=".$resultado_gruposDelEspacio[$h][0];
                                        $variable.="&planEstudio=".$variables[2];
                                        $variable.="&codProyecto=".$variables[3];

                                        //var_dump($_REQUEST);exit;
                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                        ?>
                                            <td class="centrar" width="15%" onmouseover="this.border='1';this.bgColor='#CECEF6'" onmouseout="this.border='0';this.bgColor=''"  onclick="location.replace('<?echo $pagina.$variable?>')">
                                                <a href="<?echo $pagina.$variable?>">
                                                    <font size="3"><b><?echo $resultado_gruposDelEspacio[$h][0]?></b></font>
                                            </a>
                                        </td>
                                        <?
                                    }
                                        ?></table></td></tr><?
                                    }

                                ?>
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

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos",$variablesEspacio);//echo $cadena_sql;exit;
                            $resultado_inscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosProyecto", $variables[2]);//echo $cadena_sql;exit;
                            $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $variablesHorario=array($variables[0],$variables[3],'',$variables[1]);

                            $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variablesHorario);//echo $cadena_sql_horarios;//exit;
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

        function mostrarHorario($configuracion,$resultado_horarios)
            {
            if(is_array($resultado_horarios))
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
                                }else
                                    {
                                        echo "<td class='cuadro_plano centrar' colspan='7'>No existe horario registrado</td>";
                                    }
                                ?></td><?
                        }

        function estudiantesRegistradosAdiciones($configuracion,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesInscritos", $variables);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_estudiante))
                    {
                        ?>
                                
                           <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                                <table class="contenidotabla">
                                    <thead>
                                    <tr class="cuadro_brownOscuro centrar">
                                        <td colspan="7"><b><font size="2">Estudiantes Inscritos</font></b></td>
                                    </tr>
                                    <tr class="cuadro_brownOscuro centrar">
                                        
                                        <td width="5%">
                                            <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                                            Seleccionar
                                            <br>
                                            <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'adminConsultarCIGrupoCoordinador');">
                                            <!--<input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="<?echo $this->check?>">-->
                                        </td>
                                        <td width="10%" >C&oacute;digo</td>
                                        <td width="35%" >Nombre</td>
                                        <td width="35%" >Proyecto<br>Curricular</td>
                                        <td width="10%" >Cambiar Grupo</td>
                                        <td width="10%" >Cancelar</td>
                                    </tr>
                                    </thead>
                                    
                        <?
                        for($i=0;$i<count($resultado_estudiante);$i++)
                        {
                            
                            ?><tr class="cuadro_planoPequeño">
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
                                <td class="izquierda"><?echo htmlentities($resultado_estudiante[$i][1])?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][2])?></td>
                                
                                    <?
                                    
                                    
                                    if(trim($resultado_estudiante[$i][3])=='S')
                                        {
                                            $atributos['cambiar']=true;
                                        }else
                                            {
                                                $atributos['cambiar']=false;
                                                $atributos['horas']=true;
                                            }
                                        $atributos['pagina']="pagina=registroCambiarGrupoCIGrupoCoordinador";
                                        $atributos['opcion']="&opcion=estudiante";
                                        $atributos['parametros']="&codEstudiante=".$resultado_estudiante[$i][0]."&planEstudio=".$variables[2]."&nroCreditos=".$variables[4];
                                        $atributos['parametros'].="&codEspacio=".$variables[0]."&nroGrupo=".$variables[1]."&nombreEspacio=".$variables[3];
                                        $atributos['parametros'].="&nombreEstudiante=".$resultado_estudiante[$i][1];
//var_dump($atributos);exit;
                                        $this->redireccionarEstudiante($configuracion, $atributos);
                                        unset($atributos);
                                    
                                    if(trim($resultado_estudiante[$i][3])=='S')
                                        {
                                            $atributos['cancelar']=true;
                                        }else
                                            {
                                                $atributos['cancelar']=false;
                                            }
                                        $atributos['pagina']="pagina=registroCancelarCIGrupoEstudianteCoordinador";
                                        $atributos['opcion']="&opcion=verificaEstudiante";
                                        $atributos['parametros']="&codEstudiante=".$resultado_estudiante[$i][0]."&planEstudio=".$variables[2]."&nroCreditos=".$variables[4];
                                        $atributos['parametros'].="&codEspacio=".$variables[0]."&nroGrupo=".$variables[1]."&nombreEspacio=".$variables[3]."&proyecto=".$variables[5];
                                        $atributos['parametros'].="&nombreEstudiante=".htmlentities($resultado_estudiante[$i][1]);

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
        <table class="contenidotabla">
            <tr class="cuadro_brownOscuro centrar">
                <td class="derecha">PARA LOS <div id="contadorSeleccionados"></div> ESTUDIANTES SELECCIONADOS</td>
                <td>
                    <select id="accionCoordinador" name="accionCoordinador" onchange="submit()">
                        <option value="0">Seleccione...</option>
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
                </td>
            </tr>
        </table>
                    
        </form>
                        <?
                    }else
                        {
                        ?>
                        <table class="contenidotabla">
                            <tr class="cuadro_brownOscuro centrar">
                                <td colspan="7"><b><font size="2">No existen registros de estudiantes en este grupo</font></b></td>
                            </tr>
                        </table>
                        <?
                        }
                    
            }

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

        function consultarFechas($configuracion,$planEstudio,$codProyecto)
            {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo",'');//echo $cadena_sql;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $i=5;
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

                                                if(($registroFecha[$j][1]>=date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($registroFecha[$j][1]<date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
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

                                                if(($registroFecha[$j][1]>=date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($registroFecha[$j][1]<date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
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

                                                if(($registroFecha[$j][1]>=date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($registroFecha[$j][1]<date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
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

                                                if(($registroFecha[$j][1]>=date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($registroFecha[$j][1]<date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
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

                                                if(($registroFecha[$j][1]>=date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
                                                       {
                                                            $band=1;
                                                            break;
                                                       }else if(($registroFecha[$j][1]<date('YmdHis'))&&(date('YmdHis')<=$registroFecha[$j][2]))
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

         function estudiantesRegistradosConsulta($configuracion,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesInscritos", $variables);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_estudiante))
                    {
                        ?>

                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                                <table class="contenidotabla">

                                    <tr class="cuadro_brownOscuro centrar">
                                        <td colspan="7">Estudiantes Inscritos</td>
                                    </tr>
                                    <tr class="cuadro_brownOscuro centrar">
                                        <td width="10%">C&oacute;digo</td>
                                        <td width="35%">Nombre</td>
                                        <td width="35%">Proyecto<br>Curricular</td>
                                    </tr>
                        <?
                        for($i=0;$i<count($resultado_estudiante);$i++)
                        {
                            ?><tr class="cuadro_planoPequeño">
                                <td class="centrar"><?echo $resultado_estudiante[$i][0]?></td>
                                <td class="izquierda"><?echo htmlentities($resultado_estudiante[$i][1])?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][2])?></td>
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

        function estudiantesRegistradosCancelacion($configuracion,$variables)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantesInscritos", $variables);//echo $cadena_sql;exit;
                $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_estudiante))
                    { 
                        ?>

                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                                <table class="contenidotabla">

                                    <tr class="cuadro_brownOscuro centrar">
                                        <td colspan="7">Estudiantes Inscritos</td>
                                    </tr>
                                    <tr class="cuadro_brownOscuro centrar">
                                        <td width="5%">
                                            <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                                            Seleccionar<br><input align="center" type=checkbox name=todos value="checkbox" onselect="javascript:todos('0',this);">
                                        </td><td width="10%">C&oacute;digo</td>
                                        <td width="35%">Nombre</td>
                                        <td width="35%">Proyecto<br>Curricular</td>
                                        <td width="5%">Cancelar</td>
                                    </tr>
                        <?
                        for($i=0;$i<count($resultado_estudiante);$i++)
                        {
                            ?><tr class="cuadro_planoPequeño">
                                <?
                                if(trim($resultado_estudiante[$i][3])=='S'){
                                ?>
                                <td class="centrar"><input type="checkbox" name="estudiante<?echo $i?>" value="<?echo $resultado_estudiante[$i][0]?>"></td>
                                <?
                                }else
                                    {
                                        ?>
                                        <td class="centrar"></td>
                                        <?
                                    }
                                ?>
                                <td class="centrar"><?echo $resultado_estudiante[$i][0]?></td>
                                <td class="izquierda"><?echo htmlentities($resultado_estudiante[$i][1])?></td>
                                <td class="centrar"><?echo htmlentities($resultado_estudiante[$i][2])?></td>
                                
                                    <?
                                    if(trim($resultado_estudiante[$i][3])=='S')
                                        {
                                            $atributos['cancelar']=true;
                                        }else
                                            {
                                                $atributos['horas']=true;
                                            }
                                        $atributos['pagina']="pagina=registroCancelarCIEstudianteGrupoCoordinador";
                                        $atributos['opcion']="&opcion=espacios";
                                        $atributos['parametros']="&codEstudiante=".$resultado_estudiante[$i][0]."&planEstudio=".$variables[2];
                                        $atributos['parametros'].="&codEspacio=".$variables[0]."&nroGrupo=".$variables[1];

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
        <table class="contenidotabla">
            <tr class="cuadro_brownOscuro centrar">
                <td>Para los estudiantes seleccionados <? echo $variables[5]."algo aca"; ?></td>
                <td>
                    <select id="accionCoordinador" name="accionCoordinador" onchange="submit()">
                        <option value="0">Seleccione...</option>
                        <option value="cancelar">Cancelar el Espacio Acad&eacute;mico</option>
                    </select>
                    <input type="hidden" name="opcion" value="grupoSeleccionado">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="totalEstudiantes" value="<?echo count($resultado_estudiante)?>">
                    <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                    <input type="hidden" name="nroGrupo" value="<?echo $variables[1]?>">
                    <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                    <input type="hidden" name="proyecto" value="<?echo $variables[5]?>">
                </td>
            </tr>
        </table>

        </form>
                        <?
                    }

            }
}
?>
