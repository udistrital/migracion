
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

class funcion_adminInscripcionCreditosEstudiante extends funcionGeneral
{

 	//@ Método costructor que crea el objeto sql de la clase sql_noticia
	function __construct($configuracion)
            {
	    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
	    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
	    include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");


            $this->administrar=new administrarModulo();
            $this->administrar->administrarModuloSGA($configuracion, '4');

	    $this->cripto=new encriptar();
	    $this->tema=$tema;
	    $this->sql=new sql_adminInscripcionCreditosEstudiante();
	    $this->log_us= new log();
            $this->formulario="adminInscripcionCreditosEstudiante";


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

            //echo $this->usuarioSesion[0][0];

	}




        #Entrada al bloque para consulta del horario para un estudiante estudiante
	function consultaPorEstudiante($configuracion,$tema,$acceso_db)
            {


                    $this->formularioConsultaHorario($configuracion,$tema, $acceso_db);

                  /* #Consulta la informacion general del Plan de Estudios
                   #$id es el id_carrera
                   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_id",$id);
                   $registroPlan=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                   $this->mostrarDatosEstudiante($configuracion,$tema,$registroPlan);

                   #Consulta los Espacios Academicos del plan de estudios
                   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEspaciosPlan",$id);
                   $registroEspaciosPlan=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                   $totalEspacios=$this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);

                   #Muestra los niveles de un plan de estudios
                   $this->mostrarHorarioEstudiante($configuracion,$tema,$registroEspaciosPlan,$totalEspacios);
*/

            }


        #formulario para ingresar el codigo del estudiante del que se quiere consultar el horario
        function formularioConsultaHorario($configuracion,$tema,$acceso_db)
                {


                ?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo  $this->formulario?>'>
                  <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td>
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>

                           </td>
                        </tr>
                        <tr align="center">
                            <td>
                                <h4>Horario de Estudiantes</h4>
                                          <hr noshade class="hr">
                              <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                          </td>
                        </tr>
                        <tr>
                            <td align='center' class="bloquelateralcuerpo">C&Oacute;DIGO DEL ESTUDIANTE</td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <input type='text' name='codEstudiante' size='15' maxlength='15' >
                                                </td>
                        </tr>
                        <tr>
                            <td  align='center'>
                                <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                                <input type='hidden' name='opcion' value="nuevo">
                                <input type='hidden' name='action' value='<? echo  $this->formulario ?>'>
                                <input value="Consultar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                            </td>
                        </tr>
                    </table>
               </form>

            <?
            }

        #muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante
 	function mostrarHorarioEstudiante($configuracion)
            {

                if($_REQUEST['codEstudiante']==NULL)
                {
                $codigoEstudiante=$this->usuario;

                }else{
                $codigoEstudiante=$_REQUEST['codEstudiante'];

                }

//            $codigoEstudiante='20092187004';

               $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEstudiante",$codigoEstudiante);
               $registroEstudiante=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                   if(isset($registroEstudiante))
                       {
                           $this->datosEstudiante($configuracion,$registroEstudiante);

                           //busca los grupos y el horario inscrito por el estudinate

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaGrupo",$codigoEstudiante);
                            $registroGrupo=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                            $this->HorarioEstudiante($configuracion,$registroGrupo,$registroEstudiante);


                            //Muestra el total de creditos al final del horario
                              $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaCreditosSemestre",$codigoEstudiante);
                              $registroCreditosInscritos=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                              if(is_array($registroCreditosInscritos))
                                  {
                                      ?>
                                      <div align="right" border="1">
                                      <?if ($registroCreditosInscritos[0][0]==NULL)
                                          {
                                            echo "<font size='1'><b>Cr&eacute;ditos Inscritos: 0</b></font>";
                                          }
                                          else
                                          {
                                            echo "<font size='1'><b>Total Cr&eacute;ditos Inscritos: ".$registroCreditosInscritos[0][0]."</b></font>";
                                          }
                                          ?>
                                          </div><?
                                   
                                  }else
                                      {
                                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaRegistroHorario",$codigoEstudiante);
                                        $registroCreditosEspacios=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                                        $conteo=0;
                                        if(is_array($registroCreditosEspacios))
                                            {
                                                $valores=array($registroCreditosEspacios[0][0],$registroCreditosEspacios[0][1],$registroCreditosEspacios[0][2],$registroCreditosEspacios[0][3],$registroCreditosEspacios[0][4]);
                                                for($i=0;$i<count($registroCreditosEspacios);$i++)
                                                {
                                                    $conteo+=$registroCreditosEspacios[$i][5];
                                                }
                                                $valores[5]=$conteo;

                                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"grabarCreditosNuevo",$valores);
                                                $registroInsertarCreditos=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
                                                ?><div align="right" border="1">
                                                    <?echo "<font size='1'><b>Total Cr&eacute;ditos Inscritos: ".$valores[5]."</b></font>";?>
                                                </div><?
                                            }
                                      }

                                      $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                      $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                                      $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                                      if($resultado_adicionesHab[0][0]==1){
                                      ?>
                              
                              <table align="center" >
                                <tr class="centrar">
                                <td class="centrar">

                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adicionarCreditos";
				$variable.="&opcion=espacios";
                                $variable.="&codEstudiante=".$codigoEstudiante;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
                                    
				?>
                                <a href="<?= $pagina.$variable ?>" on>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br>Adicionar
                                </a>
                                </td>
                            </tr>
                            </table>
                            <?
                                      }
                            ?>
                            <table class="cuadro_color centrar" width="100%">
                                <tr class="cuadro_plano centrar">
                                    <th>
                                        Observaciones
                                    </th>
                                </tr>
                                <tr class="cuadro_plano">
                                    <td>
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

        #Funcion que muestra la informacion del estudiante
	function datosEstudiante($configuracion,$registro)
            {
             ?>
             <br>
            <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
                    <tr class="texto_subtitulo">
                            <td colspan="2">
                                      <?echo "Nombre: <strong>".$registro[0][1]." ".$registro[0][2]." ".$registro[0][3]." ".$registro[0][4]."</strong><br>";?>
                                      <?echo "C&oacute;digo: <strong>".$registro[0][0]."</strong><br>";?>
                                      Proyecto Curricular:
                                      <?echo "<strong>".$registro[0][6]."</strong><br>";?>
                                        Plan de Estudios:
                                      <?echo "<strong>".$registro[0][5]." - ".$registro[0][7]."</strong>";?>

                                <hr>
                            </td>
                    </tr>

              </table>


            <?
            }

        function HorarioEstudiante($configuracion, $resultado_grupos,$registroEstudiante)
            {


                ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <?if($resultado_grupos!=NULL){
                                        
                                        $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                                          $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                                      $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                                          
                                        ?>

                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='cuadro_plano centrar'>
                                        <th><center><?echo "Horario de Clases";?></center></th>
                                        </thead>


                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <thead class='cuadro_color'>
                                                    <td class='cuadro_plano centrar'>Cod.</td>
                                                    <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                                                        <td class='cuadro_plano centrar' width="25">Grupo </td>
                                                        <td class='cuadro_plano centrar' width="60">Lun </td>
                                                        <td class='cuadro_plano centrar' width="60">Mar </td>
                                                        <td class='cuadro_plano centrar' width="60">Mie </td>
                                                        <td class='cuadro_plano centrar' width="60">Jue </td>
                                                        <td class='cuadro_plano centrar' width="60">Vie </td>
                                                        <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                                        <td class='cuadro_plano centrar' width="60">Dom </td>
                                                        <?
                                                        if($resultado_adicionesHab[0][0]==1){
                                                            ?>  <td class='cuadro_plano centrar'>Cambiar<br>Grupo</td>
                                                        
                                                        	<td class='cuadro_plano centrar'>Cancelar</td><?
							}
                                                        ?>
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
                    $variables[0][9]=$resultado_grupos[$j][9];  //nombre2 del estudiante
                    $variables[0][10]=$resultado_grupos[$j][10];  //apellido1 del estudiante
                    $variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                    //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
                    //var_dump($resultado_horarios);
                    ?>
                        <tr>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                            <td class='cuadro_plano'><?echo $resultado_grupos[$j][5];?></td>
                            <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
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

                    if($resultado_adicionesHab[0][0]==1){
                    ?>
                            <td class='cuadro_plano centrar'>

                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=cambiarGrupo";
				$variable.="&opcion=buscar";
                                $variable.="&codEstudiante=".$variables[0][6];
                                $variable.="&proyecto=".$variables[0][1];
                                $variable.="&codEspacio=".$variables[0][0];
                                $variable.="&grupo=".$variables[0][2];
                                $variable.="&planEstudio=".$variables[0][7];
                                $variable.="&nombre=".$resultado_grupos[$j][5];


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);


				?>

                                <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/reload.png"?>" border="0" width="25" height="25">
                                </a>

                                </td>
                                
                            <td class='cuadro_plano centrar'>

                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCreditosEstudiante";
				$variable.="&opcion=verificar";
                                $variable.="&codEstudiante=".$variables[0][6];
                                $variable.="&proyecto=".$variables[0][1];
                                $variable.="&codEspacio=".$variables[0][0];
                                $variable.="&grupo=".$variables[0][2];
                                $variable.="&planEstudio=".$variables[0][7];
                                $variable.="&nombre=".$resultado_grupos[$j][5];


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                </a>


                            </td>
				<?
                                }
                                ?>
                        </tr>
                        <?}
                                        }else{?>
                                        <tr>
                                            <td class='cuadro_plano centrar'>
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



        }


}
?>
