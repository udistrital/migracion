    
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

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminConsultarInscripcionEstudianteCoordinador extends funcionGeneral
{
	private $configuracion;

 	//@ Método costructor que crea el objeto sql de la clase sql_noticia
	function __construct($configuracion)
            {
	    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
	    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//	    include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");

            $this->fechas=new validar_fechas();

	    $this->cripto=new encriptar();
//	    $this->tema=$tema;
	    $this->sql=new sql_adminConsultarInscripcionEstudianteCoordinador();
	    $this->log_us= new log();
            $this->formulario="adminConsultarInscripcionEstudianteCoordinador";
            $this->bloque="inscripcion/adminConsultarInscripcionEstudianteCoordinador";


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

            #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];

	    $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

            //echo $this->usuarioSesion[0][0];
	    $this->configuracion=$configuracion;

	}


        /*
         * muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante
         */
 	function mostrarHorarioEstudiante()
            {
              $codigoEstudiante=$_REQUEST['codEstudiante'];

               $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultaEstudiante",$codigoEstudiante);
               $registroEstudiante=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");

                   if(isset($registroEstudiante))
                       {
                           $this->datosEstudiante($registroEstudiante);

                           $cadena_sql=$this->sql->cadena_sql($this->configuracion,"periodoActivo",'');
                           $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                           $variablesInscritos=array($codigoEstudiante,$resultado_periodo[0][0],$resultado_periodo[0][1]);

                           $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultaGrupo",$variablesInscritos);
                           $registroGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                           
                           $registro_permisos=$this->fechas->validar_fechas_estudiante_coordinador($this->configuracion,$registroEstudiante[0][0]);

                           //echo $registro_permisos;

                           switch ($registro_permisos)
                           {
                               case 'adicion':
                                        $this->HorarioEstudianteInscripcion($registroGrupo,$registroEstudiante,$_REQUEST['planEstudioGeneral'],$_REQUEST["codProyecto"]);
                                   break;

                               case 'cancelacion':
                                        $this->HorarioEstudianteCancelacion($registroGrupo,$registroEstudiante,$_REQUEST['planEstudioGeneral'],$_REQUEST["codProyecto"]);
                                   break;

                               case 'consulta':
                                        $this->HorarioEstudianteConsulta($registroGrupo,$registroEstudiante);
                                   break;


                               default:
                                        $this->HorarioEstudianteConsulta($registroGrupo,$registroEstudiante);
                                   break;
                           }

                              $creditos=$this->calcularCreditos($registroGrupo);
                              ?>
                                    <div align="right">

                                          </div>
<table class="sigma" align="center" width="70%">
    <tr>
        <td colspan="2" class="sigma derecha">
    <?if ($creditos==0)
                                          {
                                            echo "<font size='1'><b>Cr&eacute;ditos Inscritos: 0</b></font>";
                                          }
                                          else
                                          {
                                            echo "<font size='1'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                                          }
                                          ?>
        </td>
    </tr>
    <tr>
        <th class="sigma centrar">
            Abreviatura
        </th>
        <th class="sigma centrar">
            Nombre
        </th>
    </tr>
                                          <?


                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacion",'');
                                    $resultado_clasificacion=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");

                                    for($k=0;$k<count($resultado_clasificacion);$k++)
                                    {
                                        ?>
    <tr class="sigma cuadro_plano">
        <td class="sigma centrar">
            <?echo $resultado_clasificacion[$k][1]?>
        </td>
        <td class="sigma centrar">
            <?echo $resultado_clasificacion[$k][2]?>
        </td>
    </tr>
                                        <?
                                    }

                                     ?>

    <tr class="sigma centrar">
        <th class="sigma centrar" colspan="2">
            Observaciones
        </th>
    </tr>
    <tr class="sigma">
        <td class="sigma" colspan="2">
            <!--<h3>Recuerde el proceso de adiciones se cerrar&aacute; el 15 de febrero de 2010 a las 11:59pm.</h3>-->
            <br>
            * Recuerde que si cancela un espacio académico, no podra adicionarlo de nuevo para el periodo actual
            <br>
            * Recuerde verificar el cruce de horarios de los espacios académicos
            <br>
            * Recuerde que si el grupo no cumple con el cupo mínimo, puede ser cancelado
        </td>
    </tr>
</table>

                            <?


                       }
                    else{
                        echo "El código de estudiante: <strong>".$codigoEstudiante."</strong> no está inscrito en Créditos.";
                        }




            }

        /*
         * Funcion que muestra la informacion del estudiante
         */
	function datosEstudiante($registro)
            {
                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"estado_estudiante",$registro[0][0]);
                $resultado_estado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

             ?>
            <table class="contenidotabla centrar">
                    <tr>
                            <td>
                                <?echo "Nombre: <strong>".htmlentities($registro[0][1])."</strong>";?>
                            </td>
                            <td>
                                <?echo "C&oacute;digo: <strong>".$registro[0][0]."</strong>";?>
                            </td>
                            <td>
                             Estado:
                                <?echo "<strong>".htmlentities($resultado_estado[0][1])."</strong>";?>
                            </td>
                    </tr>
                    <tr>
                        <td>
                            Plan de Estudios:
                                      <?echo "<strong>".htmlentities($registro[0][2])." - ".htmlentities($registro[0][4])."</strong><br>";?>
                        </td>
                        <td>
                            Proyecto Curricular:
                                      <?echo "<strong>".$registro[0][3]."</strong><br>";?>
                        </td>


                    </tr>

              </table>
<hr>

            <?
            }

        /*
         * Funcion que muestra el horario del estudiante y permite consultar espacios académicos
         * Esta funcion se utiliza cuando las fechas de adiciones o cancelaciones no estan habilitadas
         */
        function HorarioEstudianteConsulta($resultado_grupos,$registroEstudiante)
            {

                ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){
                                        
                                        $registroEstudiante[0][5]=(isset($registroEstudiante[0][5])?$registroEstudiante[0][5]:'');
                                        $registroEstudiante[0][6]=(isset($registroEstudiante[0][6])?$registroEstudiante[0][6]:'');
                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                        $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscar_adiciones_estudiantes",$variable);
                                        $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                                        ?>

                                    <table  width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <caption class="sigma centrar"><?echo "Horario de Clases";?></caption>
                                        <tr>
                                            <td>
                                                <table class='contenidotabla sigma' width="100%">
                                                    <thead class='sigma'>
                                                    <th class='cuadro_plano sigma centrar' width="25">Cod.</th>
                                                    <th class='cuadro_plano sigma centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Grupo </th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Cr&eacute;ditos</th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Clasificaci&oacute;n</th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Lun </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Mar </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Mie </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Jue </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Vie </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">S&aacute;b </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Dom </th>

                                                    </thead>

                <?

                //recorre cada uno del los grupos
                for($j=0;$j<count($resultado_grupos);$j++){

                    //
                    $variables[0]['CODIGO']=$resultado_grupos[$j]['CODIGO'];  //idEspacio
                    $variables[0]['PROYECTO']=$resultado_grupos[$j]['PROYECTO'];  //proyecto
                    $variables[0]['GRUPO']=$resultado_grupos[$j]['GRUPO'];  //grupo
                    $variables[0]['ID_GRUPO']=$resultado_grupos[$j]['ID_GRUPO'];  //grupo
                    $variables[0]['NOMBRE']=$resultado_grupos[$j]['NOMBRE'];  //nombre del espacio
                    $variables[0]['COD_ESTUDIANTE']=$resultado_grupos[$j]['COD_ESTUDIANTE'];  //codigo del estudiante
                    $variables[0]['PENSUM']=$resultado_grupos[$j]['PENSUM'];  //plan de estudios del estudiante
                    $variables[0]['ESTUDIANTE']=$resultado_grupos[$j]['ESTUDIANTE'];  //nombre1 del estudiante
                    $variables[0]['CREDITOS']=$resultado_grupos[$j]['CREDITOS'];  //creditos
                    $variables[0]['CLASIFICACION']=$resultado_grupos[$j]['CLASIFICACION'];  //clasificacion

                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                    //busca la clasificacion del espacio academico
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacionEspacio",$resultado_grupos[$j]['CODIGO']);
                    $resultado_clasif=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['CODIGO'];?></td>
                            <td class='cuadro_plano '><?echo htmlentities($resultado_grupos[$j]['NOMBRE']);?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['GRUPO'];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['CREDITOS'];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['CLASIFICACION'];?></td>
                    <?
                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?

                        //Recorre el arreglo del resultado de los horarios
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            if ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==$resultado_horarios[$k+1]['DIA'] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1]['SALON']==($resultado_horarios[$k]['SALON']))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==(isset($resultado_horarios[$k+1]['DIA'])?$resultado_horarios[$k+1]['DIA']:'') && (isset($resultado_horarios[$k+1]['HORA'])?$resultado_horarios[$k+1]['HORA']:'')==($resultado_horarios[$k]['HORA']+1) && $resultado_horarios[$k+1]['SALON']==($resultado_horarios[$k]['SALON']))
                                {
                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l]['HORA']."-".($resultado_horarios[$m]['HORA']+1)."</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:' - ') . "<br>Edificio: " . $resultado_horarios[$l]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$l]['SALON'];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']!=$resultado_horarios[$k+1][0])
                            {
                                    $dia="<strong>".$resultado_horarios[$k]['HORA']."-".($resultado_horarios[$k]['HORA']+1)."</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['SALON'];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==$resultado_horarios[$k+1]['DIA'] && $resultado_horarios[$k+1]['SALON']!=($resultado_horarios[$k]['SALON']))
                            {
                                    $dia="<strong>".$resultado_horarios[$k]['HORA']."-".($resultado_horarios[$k]['HORA']+1)."</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['SALON'];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k]['DIA']!=$i)
                            {

                            }
                        }
                        ?></td><?
                    }

                   ?>
                        </tr>
                        <?}
                                        }else{?>
                                        <tr>
                                            <td class='sigma centrar'>
                                                No se encontraron datos de espacios adicionados
                                            </td>
                                        </tr>
                        <?}


                ?>
                                                </table>
                                            </td>

                                        </tr>

                                    </table>
                                </td>
                            </tr>

			</tbody>
		</table>
             <?
             $codEstudiante=$registroEstudiante[0][0];
                   list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($codEstudiante);
               ?>
             <br>
             <table align='center' width='650' cellspacing='0' cellpadding='2'>
                  <tr>
                      <td>
                      <?
                      echo $valor1;
                      echo $valor2;
                      echo $valor3;
                      echo $valor4;
                      echo $valor5;
                      echo $valor6;
                      ?>
                      </td>
                  </tr>
             </table>

                <?



        }

        /*
         * Funcion que muestra el horario del estudiante y permite realizar adicion, cancelacion y cambio de grupo
         * Esta funcion se utiliza cuando las fechas de adiciones para coordinador estan habilitadas
         */
        function HorarioEstudianteInscripcion($resultado_grupos,$registroEstudiante,$planEstudioGeneral,$codProyecto)
            {
               $cadena_sql=$this->sql->cadena_sql($this->configuracion,"estado_estudiante",$registroEstudiante[0][0]);
               $resultado_estado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
               $this->registroAgil($registroEstudiante);
                ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){
                                        $registroEstudiante[0][5] = (isset($registroEstudiante[0][5])?$registroEstudiante[0][5]:'');
                                        $registroEstudiante[0][6] = (isset($registroEstudiante[0][6])?$registroEstudiante[0][6]:'');
                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                          $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscar_adiciones_estudiantes",$variable);
                                          $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                                        ?>

                                    <table class="sigma contenidotabla" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <caption class="sigma">Horario de Clases</caption>
                                        <tr>
                                            <td>
                                                <table class='sigma contenidotabla' width="100%">
                                                    <thead class='sigma'>
                                                        <th class='cuadro_plano  sigma centrar' width="20">Cod.</th>
                                                        <th class='cuadro_plano sigma centrar' width="250">Nombre Espacio<br>Acad&eacute;mico </th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Grupo </th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Cr&eacute;ditos</th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Clasificaci&oacute;n</th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Lun </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Mar </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Mie </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Jue </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Vie </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">S&aacute;b </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Dom </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Cambiar Grupo </th>
                                                        <th class='sigma centrar' width="60">Cancelar </th>

                                                    </thead>

                <?


                //recorre cada uno del los grupos
                for($j=0;$j<count($resultado_grupos);$j++){

                    //
                                        
                    $variables[0]['CODIGO']=$resultado_grupos[$j]['CODIGO'];  //idEspacio
                    $variables[0]['PROYECTO']=$resultado_grupos[$j]['PROYECTO'];  //proyecto
                    $variables[0]['GRUPO']=$resultado_grupos[$j]['GRUPO'];  //grupo
                    $variables[0]['ID_GRUPO']=$resultado_grupos[$j]['ID_GRUPO'];  //grupo
                    $variables[0]['NOMBRE']=$resultado_grupos[$j]['NOMBRE'];  //nombre del espacio
                    $variables[0]['COD_ESTUDIANTE']=$resultado_grupos[$j]['COD_ESTUDIANTE'];  //codigo del estudiante
                    $variables[0]['PENSUM']=$resultado_grupos[$j]['PENSUM'];  //plan de estudios del estudiante
                    $variables[0]['ESTUDIANTE']=$resultado_grupos[$j]['ESTUDIANTE'];  //nombre1 del estudiante
                    $variables[0]['CREDITOS']=$resultado_grupos[$j]['CREDITOS'];  //creditos
                    $variables[0]['CLASIFICACION']=$resultado_grupos[$j]['CLASIFICACION'];  //clasificacion

                    
                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                    //busca la clasificacion del espacio academico
                    //$cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacionEspacio",$resultado_grupos[$j][0]);
                    //$resultado_clasif=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['CODIGO'];?></td>
                            <td class='cuadro_plano'><?echo htmlentities($resultado_grupos[$j]['NOMBRE']);?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['GRUPO'];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['CREDITOS'];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['CLASIFICACION'];?></td>
                    <?

                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?

                        //Recorre el arreglo del resultado de los horarios
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            
                            if ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==(isset($resultado_horarios[$k+1]['DIA'])?$resultado_horarios[$k+1]['DIA']:'') && (isset($resultado_horarios[$k+1]['HORA'])?$resultado_horarios[$k+1]['HORA']:'')==($resultado_horarios[$k]['HORA']+1) && $resultado_horarios[$k+1]['SALON']==($resultado_horarios[$k]['SALON']))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==(isset($resultado_horarios[$k+1]['DIA'])?$resultado_horarios[$k+1]['DIA']:'') && (isset($resultado_horarios[$k+1]['HORA'])?$resultado_horarios[$k+1]['HORA']:'')==($resultado_horarios[$k]['HORA']+1) && $resultado_horarios[$k+1]['SALON']==($resultado_horarios[$k]['SALON']))
                                {
                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l]['HORA']."-".($resultado_horarios[$m]['HORA']+1)."</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:' - ') . "<br>Edificio: " . $resultado_horarios[$l]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$l]['SALON'];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']!=(isset($resultado_horarios[$k+1]['DIA'])?$resultado_horarios[$k+1]['DIA']:''))
                            {
                                    $dia="<strong>".$resultado_horarios[$k]['HORA']."-".($resultado_horarios[$k]['HORA']+1)."</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['SALON'];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==$resultado_horarios[$k+1]['DIA'] && $resultado_horarios[$k+1]['SALON']!=($resultado_horarios[$k]['SALON']))
                            {
                                    $dia="<strong>".$resultado_horarios[$k]['HORA']."-".($resultado_horarios[$k]['HORA']+1)."</strong><br>Sede: " . (isset($resultado_horarios[$k]['SEDE'])?$resultado_horarios[$k]['SEDE']:'') . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n: " . $resultado_horarios[$k]['SALON'];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k]['DIA']!=$i)
                            {

                            }
                        }
                        ?></td><?
                    }


                    ?>
                            <td class='cuadro_plano centrar'>

                                <?
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoInscripcionEstudCoordinador";
				$variable.="&opcion=buscar";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&proyecto=".$variables[0]['PROYECTO'];
                                $variable.="&codEspacio=".$variables[0]['CODIGO'];
                                $variable.="&creditos=".$resultado_grupos[$j]['CREDITOS'];
                                $variable.="&grupo=".$variables[0]['GRUPO'];
                                $variable.="&id_grupo=".$variables[0]['ID_GRUPO'];
                                $variable.="&planEstudio=".$variables[0]['PENSUM'];
                                $variable.="&nombre=".$resultado_grupos[$j]['ESTUDIANTE'];
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);


                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);


				?>

                                <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $this->configuracion["site"].$this->configuracion["grafico"]."/reload.png"?>" border="0" width="25" height="25">
                                </a>

                                </td>

                            <td class='cuadro_plano centrar'>

                                <?//echo $planEstudio;
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarInscripcionEstudCoordinador";
				$variable.="&opcion=cancelarCreditos";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$variables[0]['COD_ESTUDIANTE'];
                                $variable.="&proyecto=".$variables[0]['PROYECTO'];
                                $variable.="&codEspacio=".$variables[0]['CODIGO'];
                                $variable.="&creditos=".$resultado_grupos[$j]['CREDITOS'];
                                $variable.="&grupo=".$variables[0]['GRUPO'];
                                $variable.="&id_grupo=".$variables[0]['ID_GRUPO'];
                                $variable.="&planEstudio=".$variables[0]['PENSUM'];
                                $variable.="&nombre=".$resultado_grupos[$j]['ESTUDIANTE'];
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $this->configuracion["site"].$this->configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                </a>


                            </td>
                        </tr>
                        <?}
                            $codEstudiante=$registroEstudiante[0][0];
                            list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($codEstudiante);
                        ?>
                        <br>
                        <table class="sigma" align='center' width='85%' cellspacing='0' cellpadding='2' >
                                <tr class="centrar">
                                    <td class=' centrar' width='85%'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    ?>
                                </td>
                                    <td class=' centrar' width='15%'>

                                <?

                                $creditosInscritos=$this->calcularCreditos($resultado_grupos);
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
				$variable.="&opcion=espacios";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                $variable.="&creditosInscritos=".$creditosInscritos;
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar</font>
                                </a>
                                    </td>
                               </tr>
                            <tr class="centrar">
                                <td class=' centrar' width='85%'>
                                   <? echo $valor5;
                                      echo $valor6;
                                   ?>
                                </td>
                                <td class=' centrar' width='15%'>

                                <?

                                $creditosInscritos=$this->calcularCreditos($resultado_grupos);
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEEEstudianteCoordinador";
				$variable.="&opcion=espacios";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                $variable.="&creditosInscritos=".$creditosInscritos;
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar<br>Electivas Extrinsecas</font>
                                </a>
                                </td>
                            </tr>
                            </table>
                              <?  }else{
                                         $codEstudiante=$registroEstudiante[0][0];
                                          list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($codEstudiante);
                                          ?>
                                        <tr>
                                            <td class='cuadro_plano centrar'>
                                                No se encontraron datos de espacios adicionados
                                            </td>
                                        </tr>
                             <table align='center' width='85%' cellspacing='0' cellpadding='2' >
                                <tr class="centrar">
                                <td class='cuadro_plano centrar' width='85%'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    ?>
                                </td>
                                <td class='cuadro_plano centrar' width='15%'>
                                <?
                                $creditosInscritos=$this->calcularCreditos($resultado_grupos);
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
				$variable.="&opcion=espacios";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                $variable.="&creditosInscritos=".$creditosInscritos;
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar</font>
                                </a>
                                </td>
                                </tr>
                            <tr>
                                <td class='cuadro_plano centrar' width='85%'>
                                   <? echo $valor5;
                                      echo $valor6;
                                   ?>
                                </td>
                                <td class='cuadro_plano centrar' width='15%'>

                                <?

                                $creditosInscritos=$this->calcularCreditos($resultado_grupos);
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEEEstudianteCoordinador";
				$variable.="&opcion=espacios";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                $variable.="&creditosInscritos=".$creditosInscritos;
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar<br>Electivas Extrinsecas</font>
                                </a>
                                </td>
                            </tr>
                            </table>
                        <?
                        }
                        ?>
                                                </table>
                                            </td>

                                        </tr>

                                    </table>
                                </td>
                            </tr>

			</tbody>
		</table>

                <?



        }

        /*
         * Funcion que muestra el horario del estudiante y permite cancelar espacios académicos
         * Esta funcion se utiliza cuando las fechas de cancelaciones estan habilitadas
         */
        function HorarioEstudianteCancelacion($resultado_grupos,$registroEstudiante,$planEstudioGeneral,$codProyecto)
            {
                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"estado_estudiante",$registroEstudiante[0][0]);
                $resultado_estado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){

                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                          $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscar_adiciones_estudiantes",$variable);
                                      $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                                        ?>

                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='cuadro_plano centrar'>
                                        <th><center><?echo "Horario de Clases";?></center></th>
                                        </thead>


                                        <tr>
                                            <td>
                                                <table class='sigma contenidotabla'>
                                                    <thead class='sigma'>
                                                    <th class='cuadro_plano sigma centrar'>Cod.</th>
                                                    <th class='cuadro_plano sigma centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Grupo </th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Cr&eacute;ditos</th>
                                                        <th class='cuadro_plano sigma centrar' width="25">Clasificaci&oacute;n</th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Lun </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Mar </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Mie </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Jue </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Vie </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">S&aacute;b </th>
                                                        <th class='cuadro_plano sigma centrar' width="60">Dom </th>
                                                        <th class='cuadro_plano sigma centrar'>Cancelar</th>
                                                    </thead>

                <?


                //recorre cada uno del los grupos
                for($j=0;$j<count($resultado_grupos);$j++){

                    //
                    $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                    $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                    $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                    $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                    $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                    $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                    $variables[0][8]=$resultado_grupos[$j][8];  //nombre1 del estudiante
                    $variables[0][9]=$resultado_grupos[$j][9];  //creditos
                    $variables[0][10]=$resultado_grupos[$j][10];  //clasificacion
                    //$variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
                    //var_dump($resultado_horarios);

                    //busca la clasificacion del espacio academico
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacionEspacio",$resultado_grupos[$j][0]);
                    $resultado_clasif=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                            <td class='cuadro_plano'><?echo htmlentities(utf8_decode($resultado_grupos[$j][5]));?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][10];?></td>
                    <?


                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?

                        //Recorre el arreglo del resultado de los horarios
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

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
                        ?></td><?
                    }


                                ?>
                            <td class='cuadro_plano centrar'>

                                <?
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarInscripcionEstudCoordinador";
				$variable.="&opcion=cancelarCreditos";
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&creditos=".$resultado_grupos[$j][9];
                                $variable.="&codEstudiante=".$variables[0]['COD_ESTUDIANTE'];
                                $variable.="&proyecto=".$variables[0]['PROYECTO'];
                                $variable.="&codEspacio=".$variables[0]['CODIGO'];
                                $variable.="&creditos=".$resultado_grupos[$j]['CREDITOS'];
                                $variable.="&grupo=".$variables[0]['GRUPO'];
                                $variable.="&id_grupo=".$variables[0]['ID_GRUPO'];
                                $variable.="&planEstudio=".$variables[0]['PENSUM'];
                                $variable.="&nombre=".$resultado_grupos[$j]['ESTUDIANTE'];
                                $variable.="&estado_est=".trim($resultado_estado[0][0]);


                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $this->configuracion["site"].$this->configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                </a>


                            </td>
                        </tr>
                        <?}
                            $creditosInscritos=$this->calcularCreditos($resultado_grupos);
                            $codEstudiante=$registroEstudiante[0][0];
                            list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($codEstudiante);
                        ?>
                        <br>
                        <table align='center' width='85%' cellspacing='0' cellpadding='2' >
                                <tr class="centrar">
                                    <td class='centrar' width='85%'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    ?>
                                </td>

                               </tr>
                            <tr class="centrar">
                                <td class='centrar' width='15%'>
                                   <? echo $valor5;
                                      echo $valor6;
                                   ?>
                                </td>

                            </tr>
                            </table><?
                                        }else{
                                                $creditosInscritos=$this->calcularCreditos($resultado_grupos);
                                                $codEstudiante=$registroEstudiante[0][0];
                                                list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($codEstudiante);
                                          ?>
                                        <tr>
                                            <td class='cuadro_plano centrar'>
                                                No se encontraron datos de espacios adicionados
                                            </td>
                                        </tr>
                                        <table align='center' width='85%' cellspacing='0' cellpadding='2' >
                                <tr class="centrar">
                                <td class='cuadro_plano centrar' width='85%'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    ?>
                                </td>
                                </tr>
                            <tr>
                                <td class='cuadro_plano centrar' width='15%'>
                                   <? echo $valor5;
                                      echo $valor6;
                                   ?>
                                </td>
                            </tr>
                            </table>
                        <?}


                ?>
                                                </table>
                                            </td>

                                        </tr>

                                    </table>
                                </td>
                            </tr>

			</tbody>
		</table>

                <?
        }

        function calcularCreditos($registroGrupo)
            {
                $suma=0;
                for($i=0;$i<count($registroGrupo);$i++)
                {
                    $suma+=$registroGrupo[$i]['CREDITOS'];
                }

                return $suma;

            }

        function porcentajeParametros($codEstudiante)
            {
            $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarPlan",$codEstudiante);
            $registroPlan=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
            $planEstudiante=$registroPlan[0][1];

            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"creditosPlan",$planEstudiante);
            $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $totalCreditos= $registroCreditosGeneral[0][0];
            $OB= $registroCreditosGeneral[0][1];
            $OC= $registroCreditosGeneral[0][2];
            $EI= $registroCreditosGeneral[0][3];
            $EE= $registroCreditosGeneral[0][4];

            $OBEst=0;
            $OCEst=0;
            $EIEst=0;
            $EEEst=0;
            $CPEst=0;

            $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"espaciosAprobados",$codEstudiante);
            $registroEspaciosAprobados=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");


            for($i=0;$i<count($registroEspaciosAprobados);$i++)
            {
            /*$idEspacio= $registroEspaciosAprobados[$i][0];
            $variables=array($idEspacio, $planEstudiante);
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"valorCreditosPlan",$variables);
            $registroCreditosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );*/

           switch($registroEspaciosAprobados[$i][3])
            {
                case 1:
                        $OBEst=$OBEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 2:
                        $OCEst=$OCEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 3:
                        $EIEst=$EIEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 4:
                        $EEEst=$EEEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 5:
                        $CPEst=$CPEst+$registroEspaciosAprobados[$i][2];
                    break;

                case '':
                        $totalCreditosEst=$totalCreditosEst+0;
                    break;

                 }

            /*switch($registroCreditosEspacio[0][1])
            {
                case 1:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                    break;

                case 2:
                        $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                    break;

                case 3:
                        $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                    break;

                case 4:
                        $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                    break;

                case '':
                        $totalCreditosEst=$totalCreditosEst+0;
                    break;

                 }*/
            }
            $OBEst=$OBEst+$CPEst;
           $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;


            if($totalCreditos==0){$porcentajeCursado=0;}
            else{$porcentajeCursado=$totalCreditosEst*100/$totalCreditos;}
            if($OB==0){$porcentajeOBCursado=0;}
            else{$porcentajeOBCursado=$OBEst*100/$OB;}
            if($OC==0){$porcentajeOCCursado=0;}
            else{$porcentajeOCCursado=$OCEst*100/$OC;}
            if($EI==0){$porcentajeEICursado=0;}
            else{$porcentajeEICursado=$EIEst*100/$EI;}
            if($EE==0){$porcentajeEECursado=0;}
            else{$porcentajeEECursado=$EEEst*100/$EE;}

            if($totalCreditos>0)
            {
            $vista="
            <table class='sigma contenidotabla' align='center' width='100%' cellspacing='0'>
                 <caption class='sigma'>Cr&eacute;ditos Acad&eacute;micos</caption>
                 <tr class='centrar'>
                      <th class='sigma' width='16%'>Clasificaci&oacute;n
                      </th>
                      <th class='sigma' width='10%'>Total
                      </th>
                      <th class='sigma' width='14%'>Aprobados
                      </th>
                      <th class='sigma' width='14%'>Por Aprobar
                      </th>
                      <th class='sigma' width='46%'>% Cursado
                      </th>
                   </tr>
                   </table>";

             $vistaOB="<table class='sigma contenidotabla'  cellspacing='0' >
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>OB
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>".$OB."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$OBEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$FaltanOB=$OB-$OBEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>";
           if($porcentajeOBCursado==0)
             {
             $vistaOB.="
                       <table class='sigma contenidotabla' cellspacing='0'>
                        <tr><td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td></tr>
                       </table>";
             $OBEst=0;
             }
             if($porcentajeOBCursado>=100)
             {
             $vistaOB.="
                           <table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td></tr>
                           </table>";
             }
             if($porcentajeOBCursado!=0 AND $porcentajeOBCursado!=100)
             {
             $vistaOB.="<table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='".$porcentajeOBCursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalOB=100-$porcentajeOBCursado."%' bgcolor='#fffcea'>
                           </td></tr>
                           </table>";
             }
             $vistaOB.="</td></tr>
                      </table>";


             $vistaOC="<table class='sigma contenidotabla'  cellspacing='0'>
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>OC
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>".$OC."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$OCEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$FaltanOC=$OC-$OCEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>";
           if($porcentajeOCCursado==0)
             {
             $vistaOC.="
                       <table class='sigma contenidotabla'  cellspacing='0'>
                        <tr><td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td></tr>
                       </table>";
             $OCEst=0;
             }
             if($porcentajeOCCursado>=100)
             {
             $vistaOC.="
                           <table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td></tr>
                           </table>";
             }
             if($porcentajeOCCursado!=0 AND $porcentajeOCCursado!=100)
             {
             $vistaOC.="<table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='".$porcentajeOCCursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalOC=100-$porcentajeOCCursado."%' bgcolor='#fffcea'>
                           </td></tr>
                           </table>";
             }
             $vistaOC.="</td></tr>
                      </table>";



             $vistaEI="<table class='sigma contenidotabla'  cellspacing='0' >
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>EI
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>".$EI."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$EIEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$FaltanEI=$EI-$EIEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>";
           if($porcentajeEICursado==0)
             {
             $vistaEI.="
                       <table class='sigma contenidotabla'  cellspacing='0'>
                        <tr><td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td></tr>
                       </table>";
             $EIEst=0;
             }
             if($porcentajeEICursado>=100)
             {
             $vistaEI.="
                           <table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%
                           </td></tr>
                           </table>";
             }
             if($porcentajeEICursado!=0 AND $porcentajeEICursado!=100)
             {
             $vistaEI.="<table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='".$porcentajeEICursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalEI=100-$porcentajeEICursado."%' bgcolor='#fffcea'>
                           </td></tr>
                           </table>";
             }
             $vistaEI.="</td></tr>
                      </table>";


             $vistaEE="<table class='sigma contenidotabla'  cellspacing='0' >
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>EE
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>".$EE."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$EEEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$FaltanEE=$EE-$EEEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>";
           if($porcentajeEECursado==0)
             {
             $vistaEE.="
                       <table class='sigma contenidotabla' cellspacing='0'>
                        <tr><td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td></tr>
                       </table>";
             $EEEst=0;
             }
             if($porcentajeEECursado>=100)
             {
             $vistaEE.="
                           <table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td></tr>
                           </table>";
             }
             if($porcentajeEECursado!=0 AND $porcentajeEECursado!=100)
             {
             $vistaEE.="<table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='".$porcentajeEECursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$TotalEE=100-$porcentajeEECursado."%' bgcolor='#fffcea'>
                           </td></tr>
                           </table>";
             }
             $vistaEE.="</td></tr>
                      </table>";

             $vistaTotal="<table class='sigma contenidotabla' cellspacing='0' >
                   <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='16%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>".$totalCreditos."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$totalCreditosEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>".$Faltan=$totalCreditos-$totalCreditosEst."
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar'  width='46%'>";
           if($porcentajeCursado==0)
             {
             $vistaTotal.="
                       <table class='sigma contenidotabla'  cellspacing='0'>
                        <tr><td width='100%' class='bloquelateralayuda centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td></tr>
                       </table>";
             $totalCreditosEst=0;
             }
             if($porcentajeCursado>=100)
             {
             $vistaTotal.="
                           <table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='100%' class='bloquelateralayuda centrar textoBlanco' colspan='2' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%
                           </td></tr>
                           </table>";
             }
             if($porcentajeCursado!=0 AND $porcentajeCursado!=100)
             {
             $vistaTotal.="<table class='sigma contenidotabla'  cellspacing='0'>
                           <tr><td width='".$porcentajeCursado."%' class='bloquelateralayuda centrar textoBlanco' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%
                           </td>
                           <td class='bloquelateralayuda centrar' width='".$Total=100-$porcentajeCursado."%' bgcolor='#fffcea'>
                           </td></tr>
                           </table>";
             }
             $vistaTotal.="</td></tr>
                      </table>";

            }
            else
            {
               $vista="
            <table class='sigma contenidotabla'  cellspacing='0' bgcolor='#fffffa'>
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='5'>El Proyecto Curricular no ha definido los rangos de cr&eacute;ditos<br>para el plan de estudios
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='16%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='10%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Por Aprobar
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>% Cursados
                      </td>
                   </tr>
                   </table>";

               $vistaOB="<table class='sigma contenidotabla' cellspacing='0' cellpadding='2' bgcolor='#fffffa'>
                                <tr>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar' bgcolor='#fffcea' width='46%'> 0%
                                </td>
                                </tr>
                             </table>";
               $vistaOC=$vistaOB;
               $vistaEI=$vistaOB;
               $vistaEE=$vistaOB;
               $vistaTotal=$vistaOB;
            }
            return array($vista, $vistaOB, $vistaOC, $vistaEI, $vistaEE, $vistaTotal);

    }

        function registroAgil($registroEstudiante)
            {
                ?>
             <style type="text/css">
                #toolTipBox {
                        display: none;
                        position:absolute;
                        width:200px;
                        background:#000;
                        border:4px double #fff;
                        text-align:left;
                        padding:5px;
                        -moz-border-radius:6px;
                        z-index:1000;
                        margin:0;
                        padding:0;
                        color:#fff;
                        font:11px/12px verdana,arial,serif;
                        margin-top:3px;
                        font-style:normal;
                        font-weight:bold;
                        opacity:0.85;
                }
            </style>
             <script type="text/javascript" language="javascript">
                function buscarHorario(arreglo,codEspacio,codProyecto)
                {
                    var horario=new Array();
                    var fila=0;
                    var columna=0;
                    horario[fila]=new Array();

                    var resultado=arreglo.split("|");

                    for(var j=0;j<resultado.length;j++)
                    {
                        if(j%11==0)
                        {
                            fila++;
                            horario[fila]=new Array();
                            columna=0;
                        }
                        horario[fila][columna]=new Array(resultado[j]);
                        columna++;
                        //alert("Fila:"+fila+" Evento: "+horario[fila]+" Valor J: "+j);
                    }


                    var tx="";

                    tx="<table class='contenidotabla centrar'>";
                    tx+="<tr class='sigma centrar'>";
                    tx+="<td width='25%'>Dia</td><td width='25%'>Hora</td><td width='25%'>Sede</td><td width='25%'>Salon</td>";
                    tx+="</tr>";
                    for(var q=1;q<fila;q++)
                    {
                        tx+="<tr>";
                        tx+="<td>"+horario[q][3]+"</td><td class='sigma centrar'>"+horario[q][4]+"</td><td class='centrar'>"+horario[q][5]+"</td><td class='centrar'>"+horario[q][6]+"</td>";
                        tx+="</tr>";
                    }
                    tx+="<tr><td colspan='4'><b>Cupo: "+horario[1][1]+"    Inscritos: "+horario[1][2]+"    Disponibles: "+(horario[1][1]-horario[1][2])+"</b></td></tr>";
                    tx+="</table>";

                    document.getElementById('div_horario').innerHTML=tx;
                    var inp="";

                    inp="<input type='hidden' name='id_grupo' value='"+horario[1][9]+"'>"
                    inp+="<input type='hidden' name='hor_alternativo' value='"+horario[1][10]+"'>"
                    inp+="<input type='hidden' name='carrera' value='"+horario[1][8]+"'>"
                    inp+="<input class='boton' type='button' style='cursor:pointer;' onclick='submit()' name='registrar' value='Registrar'>"

                    document.getElementById('div_registrar').innerHTML=inp;

                    var infGrup="";

                    infGrup="<font size='1px' color='#FFFFFF'><b>"+horario[1][7]+"</b></font>";

                    document.getElementById('div_InfoGrupo').innerHTML=infGrup;
                }



            </script>
             <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
             <table class="contenidotabla centrar">
                 <caption class="sigma">
                    REGISTRO AGIL DE ESPACIOS ACAD&Eacute;MICOS
                 </caption>
                 <tr class="sigma">
                     <th width="33%" class="sigma centrar">Espacio Acad&eacute;mico</th>
                     <th width="33%" class="sigma centrar">Grupos</th>
                     <th width="33%" class="sigma centrar">Horarios</th>
                 </tr>
                 <tr>
                     <td width="33%" class="cuadro_plano centrar">
                         <div style='width: 100%; height: 150px;border:0px solid #000000;'>
                             <input type="text" name="codEspacioAgil" style=" background-color: #ABB0BE;" id="codEspacioAgil" size="4" maxlength="6" onblur="this.style.backgroundColor='#ABB0BE'" onfocus="this.style.backgroundColor='#A7C3DF'"onchange="xajax_buscarEspacios(document.getElementById('codEspacioAgil').value,<?echo $registroEstudiante[0][2]?>,<?echo $registroEstudiante[0][3]?>)" onkeypress="javascript:if(event.keyCode==13){xajax_buscarEspacios(document.getElementById('codEspacioAgil').value,<?echo $registroEstudiante[0][2]?>,<?echo $registroEstudiante[0][3]?>);return false;}" >
                             <img src="<?echo $this->configuracion["site"].$this->configuracion["grafico"]."/viewrel.png"?>" style="cursor:pointer" border="0" onclick="xajax_buscarEspacios(document.getElementById('codEspacioAgil').value,<?echo $registroEstudiante[0][2]?>,<?echo $registroEstudiante[0][3]?>)"><br>
                         <div id="div_infoea" style='width: 100%; height: 120px;'>
                             <table class='contenidotabla centrar'>
                                 <tr>
                                     <td rowspan="4" colspan="4" class="centrar">
                                         Digite el c&oacute;digo del espacio acad&eacute;mico
                                     </td>
                                 </tr>
                             </table>
                         </div>
                         </div>
                     </td>
                     <td width="33%" class="cuadro_plano centrar">
                         <div style='width: 100%; height: 150px;border:0px solid #000000;'>
                         <div id="div_grupos">
                             <select class='sigma' style='width: 100%; height: 120px;' size='7' >
                                 <optgroup label="Digite el c&oacute;digo del E.A."></optgroup>
                             </select>
                         </div>
                         <div id="div_InfoGrupo" style="background-color:ABB0BE">

                         </div>
                         </div>

                     </td>
                     <td width="33%" class="cuadro_plano centrar">
                         <div style='width: 100%; height: 150px;border:0px solid #000000;overflow:auto;'>
                         <div id="div_horario" style="width: 100%; height: 120px;border:0px solid #000000;">
                             Seleccione el grupo para ver el horario
                         </div>
                         </div>
                         <div class='centrar'><span id='toolTipBox' width='10' ></span></div>
                     </td>
                 </tr>
                 <tr>
                     <td colspan="3" class="centrar">
                         <input type="hidden" name="codEstudiante" value="<?echo $registroEstudiante[0][0]?>">
                         <input type="hidden" name="planEstudio" value="<?echo $registroEstudiante[0][2]?>">
                         <input type="hidden" name="codProyecto" value="<?echo $registroEstudiante[0][3]?>">
                         <input type="hidden" name="action" value="<?echo $this->bloque?>">
                         <input type="hidden" name="opcion" value="registroAgil">
                         <div id="div_registrar"></div>
                     </td>
                 </tr>
             </table>
             </form>
                <?
            }
            function nuevoRegistro()
            {

            }



}
?>
