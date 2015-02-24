
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
$GLOBALS ["formularioMalla"]="adminMalla";
$formularioMalla= "adminMalla";
$verificarMalla="control_vacio(".$formularioMalla.",'descripcion_malla')";
$verificarMalla.="&& control_vacio(".$formularioMalla.",'nombre_malla')";
$GLOBALS["verificarMalla"]=$verificarMalla;


?>

<?
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminActualizarEstudiante extends funcionGeneral
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
	    $this->sql=new sql_adminActualizarEstudiante();
	    $this->log_us= new log();


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle produccion
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

	
	    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links  
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];	
	    		
	}

	#Consulta los planes de estudio los presenta utilizando la funcion "listaPlanEstudio"
        function buscarEstudianteOracle($configuracion)
            {
         

                     #consulta estudiantes de creditos en oracle
                     $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEstudiantesOracle",$variable);
		     $registroEstudianteOracle=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                     $totalEstudiantesOracle=count($registroEstudianteOracle);
                     //echo $totalEstudiantesOracle;                     

                     if(is_array($registroEstudianteOracle))
                         {
                             #vaciar tabla sga_estudiante_creditos
                             $this->cadena_sql=$this->sql->cadena_sql($configuracion,"borrarEstudiantes",$variable);
                             $BorrarEstudiante=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");



                             #cargar tabla sga_estudiante_creditos con datos de oracle
                             $this->cargarEstudiantes($configuracion,$registroEstudianteOracle,$totalEstudiantesOracle);
                         }
                     else
                         {
                           echo "No se ha realizado la consulta de estudiantes";
                         }

            }

        function cargarEstudiantes($configuracion,$estudiante,$totalEstudiantesOracle)
            {

                for($a=0;$a<$totalEstudiantesOracle;$a++)
                    {
                        
                     $variable=array($estudiante[$a][0],$estudiante[$a][1],$estudiante[$a][2],$estudiante[$a][3],$estudiante[$a][4],$estudiante[$a][5],$estudiante[$a][6]);
                     $this->cadena_sql=$this->sql->cadena_sql($configuracion,"insertarEstudiantes",$variable);
		     $insertarRegistro=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                    }

                     echo "Total estudiantes de créditos cargados: ".$a;

            }

        #Muestra el listado de Planes de Estudios por Proyecto Curricular
        function actualizarEstudianteTodos($tema,$registro,$configuracion,$especifico)
            {
                ?>
		<table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
		<tr class="texto_subtitulo">
		   <td colspan="6">
			Consultar Planes de Estudio
			<hr class="hr_subtitulo">			
                   </td>
		</tr>
                <? if (is_array($registro))
                        {
                            if($this->id_accesoSesion==4)
                                {
                                    ?>
                                   <tr class="texto_subtitulo">
                                     <td colspan="5">
                                        <? $this->filtroMallas($tema,$configuracion);?>
                                     </td>
                                   </tr>
                                    <?
                                }#Cierre de if $this->id_accesoSesion==4 ?>

                            <tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');"  >
                            <td width="10%" align="center"><strong>Cod.</strong></td>
                            <td width="70%" align="center"><strong>Nombre</strong></td>
                            <td width="10%" class="texto_negrita" align="center">Ver</td>
                            <?
                            #Si el acceso de la sesion es 4 (coordinador) entonces es el admin del sistema sga
                            if(/*$this->especifico=='true' or*/ $this->id_accesoSesion==4)
                                {
                                    ?>
                                   <td class="texto_negrita">Editar</td>
                                   <td class="texto_negrita">Eliminar</td>
                                    <?
                                }
                                    ?>
                            </tr>
                            <?
                            for($i=0; $i<count($registro); $i++)
                                {
                                    ?>
                                    <tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" <? if(($i%2)==0){ ?> bgcolor='<? echo $tema->celda ?>' <?}?>>
                                    <td align="center"><?= $registro[$i][0]?></td>
                                    <td><?= $registro[$i][1]?>
                                    </td>
                                    <!--<td align="center"><?= $registro[$i][2]." - " .$registro[$i][3]?></td>-->
                                    <!--<td align="center"><?= $registro[$i][4];?> </td>-->
                                    <!--<td align="center"><?= date("Y-m-d",$registro[$i][5]);  ?></td> -->
                                    <?
                                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $ruta="pagina=adminPlanEstudios";
                                        $ruta.="&opcion=mostrar";
                                        $ruta.="&id_malla=".$registro[$i][0];
                                        $ruta.="&especifico=".$this->especifico;
                                        $ruta.="&carrera=".$_REQUEST["carrera"];
                                        $ruta.="&xajax=generar_lista|descripcionEspacioAcademico|asociarEspacioMalla|editarEspacioMalla|formEspacio";
                                        $ruta.="&xajax_file=arbolesMallasEspacios";
                                    ?>
                                    <td align="center">
                                      <?
                                        $rutaVer=$ruta."&ejecucion=Consultar";
                                        $rutaVer=$this->cripto->codificar_url($rutaVer,$configuracion);
                                      ?>
                                        <a href="<?= $indice.$rutaVer ?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png" border="0">
                                        </a>
                                    </td>
                                    <? if(/*$this->especifico=='true' or*/ $this->id_accesoSesion==4){

                                    ?>
                                    <td align="center">
                                      <? $rutaEditar=$this->cripto->codificar_url($ruta,$configuracion); ?>
                                         <a id="editar_<?= $registro[$i][0];?>" onclick="confirmarEdicion('<?= $indice.$rutaEditar ?>','editar_<?= $registro[$i][0];?>')">
                                         <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/editar.png" border="0">
                                         </a>
                                    </td>
                                    <td align="center">
                                        <?
                                          $variable="pagina=borrar_registro";
                                          $variable.="&opcion=mallaAcademica";
                                          $variable.="&id_malla=".$registro[$i][0];
                                          $variable.="&especifico=".$especifico;
                                          $variable.="&carrera=".$_REQUEST["carrera"];

                                          $redireccion="";
                                          reset ($_REQUEST);
                                          while (list ($clave, $val) = each ($_REQUEST))
                                            {
                                                 $redireccion.="&".$clave."=".$val;
                                            }

                                            $variable.="&redireccion=".$this->cripto->codificar_url($redireccion,$configuracion);
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);
                                        ?>
                                        <a href="<?= $indice.$variable; ?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/cancelar.gif" border="0"></a>
                                   </td>
                                    <? }?>
                                    </tr>
                                    <?
                                }
                        }
                    else
                        {
                          ?>
                           <tr>
                               <td class='bloquecentralcuerpo' align="center"><strong>No exiten planes de estudio</strong></td>
                           </tr>
                          <?
                        }
                ?>
                  
		</table>
		<? 
        }

        function filtroMallas($tema,$configuracion)
            {
	   $obj_html=new html();
	   		
          ?>
             <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='filtroMalla'>
		  <input type="hidden" name="pagina" value="adminMallas">
		  <input type="hidden" name="opcion" value="ver">
                  <table border='1' cellpadding="0" cellspacing="2" width="100%"  >
                    <tr class="texto_subtitulo"  >
                      <td>Facultad: </td>
                      <td>
		 	  <?
			  echo 'Facultad: '.$_REQUEST['id_facultad'];	
		          if(isset($_REQUEST['id_facultad'])){
			     $this->id_facultad=$_REQUEST['id_facultad'];
			  }		
			  else{
			     $this->id_facultad="-1";	
			  } 	 
                          $this->cadena_sql=$this->sql->cadena_sql($configuracion,"facultades",$variable);
			  $this->facultad=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
			  $this->cuadro=$obj_html->cuadro_lista($this->facultad,"facultad",$configuracion,$this->id_facultad,'1','',$tab=0,'facultad');
			  echo $this->cuadro;
			?>
                      </td>
                      
                    </tr>
		    <tr class="texto_subtitulo">
	 	      <td>Proyecto curricular: </td>
                      <td>
                         <?
			   //if($_REQUEST[])	
			 ?>
                      </td>
		    </tr>	
		    <tr class="texto_subtitulo">
	 	      <td colspan="2">&nbsp;</td>
		    </tr>	
                  </table>  
                  </form>
         <?
        }

	#Llama las funciones "verPlanEstudios", "listaNiveles" y "listaEspacios" para visualizar
        #la informacion general del Plan de Estudios y los Espacios Academicos que lo componen agrupados por niveles
	function mostrarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
            {
                   #Consulta la informacion general del Plan de Estudios
                   #$id es el id_carrera
                   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_id",$id);
                   $registroPlan=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                   $this->verPlanEstudios($configuracion,$tema,$registroPlan);

                   #Consulta los Espacios Academicos del plan de estudios
                   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEspaciosPlan",$id);
                   $registroEspaciosPlan=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
                   $totalEspacios=$this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);

                   #Muestra los niveles de un plan de estudios
                   $this->listaNiveles($configuracion,$tema,$registroEspaciosPlan,$totalEspacios);


            }

        #Funcion que muestra la informacion del Plan de Estudios
	function verPlanEstudios($configuracion,$tema,$registro)
            {

              //$contador=0;
              //global $formularioMalla;
              //global $verificarMalla;
              //$this->menuMallas($configuracion);
             ?>
             <br>
            <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
                    <tr class="texto_subtitulo">
                            <td colspan="2">
                                      <?echo $registro[0][7];?>
                                        <hr>
                            </td>
                    </tr>
                    <tr>
                        <td class="texto_subtitulo" colspan="2">
                            Plan de Estudios en Cr&eacute;ditos n&uacute;mero
                              <?
                                echo "<strong>".$registro[0][0]."</strong>";
                              ?>
                        </td>
                    </tr>
              </table>


            <?
            }

        #Muestra los niveles existentes para el Plan de Estudios
	function listaNiveles($configuracion,$tema,$registro,$total)
            {
                ?>
                <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
                    <tr class="cuadro_plano">
                        <td  align="center">
                      <?

                           $nivel=0;
                           $totalCreditosPlan=0;
                           $espaciosBasico=0;
                           $espacioComplementario=0;
                           $espacioIntrinseco=0;
                           for($a=0; $a<$total; $a++)
                               {

                                    #Mestra los niveles del plan de estudios
                                  if($nivel!=$registro[$a][2])
                                       {

                                       echo "NIVEL ".$registro[$a][2];

                                       #Muestra los espacios academicos del nivel
                                       $nivelEspacio=$registro[$a][2];
 

                                       $this->listaEspacios($configuracion,$tema,$registro,$total,$nivelEspacio);
                                       //echo "N&uacute;mero de Cr&eacute;ditos:". $espacio;
                                       $nivel++;


                                       }

                                   else{}

                                    #cuenta la suma de creditos del plan                                   
                                    $totalCreditosPlan=$totalCreditosPlan+$registro[$a][3];

                                        #descuenta los creditos que pertenecen a la misma electiva
                                    if($registro[$a][9]==$registro[$a-1][9] and $registro[$a][8]==3)
                                    {
                                        $totalCreditosPlan=$totalCreditosPlan-$registro[$a][3];
                                    }

                                       #Calcula numero de creditos de espacios academicos obligatorios basicos

                                   if($registro[$a][8]==1)
                                       {

                                       $espaciosBasico=$espaciosBasico+$registro[$a][3];



                                       }

                                   else{} 

                                      #Calcula numero de creditos de creditos de espacios academicos obligatorios complementarios

                                   if($registro[$a][8]==2)
                                       {

                                       $espacioComplementario=$espacioComplementario+$registro[$a][3];

                                       }

                                   else{}

                                      #Calcula numero de creditos de espacios academicos electivos intrinsecos

                                   if($registro[$a][8]==3 and $registro[$a][9]!=$registro[$a-1][9])
                                       {

                                       $espacioIntrinseco=$espacioIntrinseco+$registro[$a][3];

                                       }

                                   else{}
                                      #Calcula numero de creditos de espacios academicos electivos extrinsecos

                                   if($registro[$a][8]==4)
                                       {

                                       $espacioExtrinseco=$espacioExtrinseco+$registro[$a][3];

                                       }

                                   else{}

                               }
                        ?>
                        </td>
                    </tr>
                </table>
                  <?
            #muestra mensaje al final del plan de estudios
           $porcentajeBasico=($espaciosBasico*100)/$totalCreditosPlan;
           $porcentajeComplementario=($espacioComplementario*100)/$totalCreditosPlan;
           $porcentajeIntrinseco=($espacioIntrinseco*100)/$totalCreditosPlan;
            ?>
            <tr>
                <td align="center">
                    <table class='cuadro_plano cuadro_brown' >
                       <tr>
                           <td align="center" colspan="3">
                            <p class="textoNivel0">TOTAL CREDITOS DEL PLAN DE ESTUDIOS:</p>
                            </td>
                       </tr>
                       <tr>
                           <td align="center" colspan="3">
                            <p class="textoNivel0"><h2><?echo $totalCreditosPlan?></h2></p>
                            </td>
                       </tr>
                       <tr>
                           <td align="center">
                                <p class="textoNivel0" align="left"></p>
                            </td>
                           <td align="center">
                               <p class="textoNivel0">Cr&eacute;ditos</p>
                            </td>
                            <td align="center">
                                <p class="textoNivel0">Porcentaje</p>
                            </td>
                       </tr>                   
                       <tr>
                           <td align="center">
                                <p class="textoNivel0" align="left">Obligatorios B&aacute;sicos:</p>
                            </td>
                           <td align="center">
                                <p class="textoNivel0"><?echo "<strong>".$espaciosBasico?></strong></p>
                            </td>                           <td align="center">
                                <p class="textoNivel0"><?echo number_format($porcentajeBasico,1)."%"?></p>
                            </td>
                       </tr>
                       <tr>
                           <td align="center">
                                <p class="textoNivel0" align="left">Obligatorios Complementarios:</p>
                            </td>
                           <td align="center">
                                <p class="textoNivel0"><?echo "<strong>".$espacioComplementario?></strong></p>
                            </td>                           <td align="center">
                                <p class="textoNivel0"><?echo number_format($porcentajeComplementario,1)."%"?></p>
                            </td>
                       </tr>
                       <tr>
                           <td align="center">
                               <p class="textoNivel0" align="left">Electivos Intr&iacute;nsecos:</p>
                            </td>
                           <td align="center">
                                <p class="textoNivel0"><?echo "<strong>".$espacioIntrinseco?></strong></p>
                            </td>                           <td align="center">
                                <p class="textoNivel0"><?echo number_format($porcentajeIntrinseco,1)."%"?></p>
                            </td>
                       </tr>
                    </table>
                </td>
                <td>

                </td>
            </tr>
            <?



            }
            
        #muestra los espacios academicos del semestre
	function listaEspacios($configuracion,$tema,$registro,$total,$nivelEspacio)
            {
                ?><table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
			<tbody>
				<tr>
					<td>
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'>
                                                                                    <td class='cuadro_plano centrar'>Cod. </td>
                                                                                    <td class='cuadro_plano centrar'>Nombre </td>
                                                                                    <td class='cuadro_plano centrar'>N&uacute;mero<br>Cr&eacute;ditos</td>
                                                                                    <td class='cuadro_plano centrar'>HTD </td>
                                                                                    <td class='cuadro_plano centrar'>HTC </td>
                                                                                    <td class='cuadro_plano centrar'>HTA </td>
                                                                                    <td class='cuadro_plano centrar'>Clasificaci&oacute;n </td>
										</tr>
                                                                                    <?
                                                                                    $totalcreditosNivel=0;
                                                                                    for($a=0; $a<=$total; $a++)
                                                                                           {

                                                                                           if($nivelEspacio==$registro[$a][2])
                                                                                               {
                                                                                                   $totalcreditosNivel=$totalcreditosNivel+$registro[$a][3];
                                                                                                   //Revisar, si es un espacio electivo intrinseco se muestra el nombre en la tabla y sus opciones
                                                                                                   if($registro[$a][8]==3)
                                                                                                        {
                                                                                                           #Muestra los Espacios Electivos Intrinsecos (agrupados los que pertenecen al mismo nombreElectivo)
                                                                                                           $this->listaElectivo($configuracion,$tema,$registro,$total,$nivelEspacio,$a, $id_nombreElectivo);
                                                                                                           if($registro[$a][9]==$registro[$a-1][9])
                                                                                                                {
                                                                                                                 $totalcreditosNivel=$totalcreditosNivel-$registro[$a][3];
                                                                                                                }
                                                                                                         }
                                                                                                            
                                                                                                   else
                                                                                                        {
                                                                                                            #Muestra los Espacios Electivos Intrinsecos (agrupados los que pertenecen al mismo nombreElectivo)
                                                                                                           $this->listaObligatorio($configuracion,$tema,$registro,$total,$nivelEspacio,$a);
                                                                                                            
                                                                                                        }

                                                                                               }

                                                                                           }
                                                                                           
                                                                                      ?>
                                                                                <tr>
                                                                                    <td>
                                                                                    </td>
                                                                                    <td>
                                                                                    </td>
                                                                                    <td class='cuadro_plano centrar' colspan="5">
                                                                                        <?
                                                                                        if($totalcreditosNivel>18)
                                                                                        {
                                                                                            echo "N&uacute;mero de Cr&eacute;ditos: <b><font color='red'>".$totalcreditosNivel."</b><br>M&aacute;ximo n&uacute;mero de cr&eacute;ditos por semestre: 18<br>(Acuerdo 009 de 2006)</font>";
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            echo "Numero de Cr&eacute;ditos: ".$totalcreditosNivel;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
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

        #muestra los espacios academicos del semestre, $a es el contador de espacios academicos del FOR de la funcion listaEspacios
	function listaElectivo($configuracion,$tema,$registro,$total,$nivelEspacio,$a)
            {
                if($registro[$a][9]!=$registro[$a-1][9])
                {
                
                ?>
                <tr>
                        <td class='cuadro_plano'></td>
                        <td class='cuadro_plano'><strong><?echo $registro[$a][10]?></strong></td>
                        <td class='cuadro_plano centrar'><strong><?echo $registro[$a][3]?></strong></td>
                        <td class='cuadro_plano'></td>
                        <td class='cuadro_plano'></td>
                        <td class='cuadro_plano'></td>
                        <td class='cuadro_plano'></td>
                </tr>
                <?}

                ?>
                <tr>
                       <td class='cuadro_plano centrar'><font color='green'><?echo $registro[$a][0]?></font></td>
                       <td class='cuadro_plano'><font color='green'><?echo $registro[$a][1]?></font></td>
                    <?//verificar que 3*creditos=HTD+HTC+HTA
                    if(3*$registro[$a][3]==$registro[$a][4]+$registro[$a][5]+$registro[$a][6])
                        {?>
                               <td class='cuadro_plano derecha'><font color='green'><?echo $registro[$a][3]?></font></td>
                               <td class='cuadro_plano derecha'><font color='green'><?echo $registro[$a][4]?></font></td>
                               <td class='cuadro_plano derecha'><font color='green'><?echo $registro[$a][5]?></font></td>
                               <td class='cuadro_plano derecha'><font color='green'><?echo $registro[$a][6]?></font></td>
                        <?;
                        }
                    else
                        {
                        ?>
                               <td class='cuadro_plano derecha'><font color='red'><?echo $registro[$a][3]?></font></td>
                               <td class='cuadro_plano derecha'><font color='red'><?echo $registro[$a][4]?></font></td>
                               <td class='cuadro_plano derecha'><font color='red'><?echo $registro[$a][5]?></font></td>
                               <td class='cuadro_plano derecha'><font color='red'><?echo $registro[$a][6]?></font></td>                                                                                                               }

                        <?
                         }
                ?>

                       <td class='cuadro_plano'><?echo $registro[$a][7]?></td>
               </tr>
            <?
            }

        #muestra los espacios academicos del semestre, $a es el contador de espacios academicos del FOR de la funcion listaEspacios
	function listaObligatorio($configuracion,$tema,$registro,$total,$nivelEspacio,$a)
            {
            ?>
                   <tr>
                           <td class='cuadro_plano centrar'><?echo $registro[$a][0]?></td>
                           <td class='cuadro_plano'><?echo $registro[$a][1]?></td>
                <?//verificar que 3*creditos=HTD+HTC+HTA
                if(3*$registro[$a][3]==$registro[$a][4]+$registro[$a][5]+$registro[$a][6])
                    {?>
                           <td class='cuadro_plano centrar'><?echo $registro[$a][3]?></td>
                           <td class='cuadro_plano centrar'><?echo $registro[$a][4]?></td>
                           <td class='cuadro_plano centrar'><?echo $registro[$a][5]?></td>
                           <td class='cuadro_plano centrar'><?echo $registro[$a][6]?></td>
                    <?
                    }
                else
                    {
                    ?>
                           <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][3]?></font></td>
                           <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][4]?></font></td>
                           <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][5]?></font></td>
                           <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][6]?></font></td>                                                                                                               }

                    <?
                     }
                    ?>

                           <td class='cuadro_plano'><?echo $registro[$a][7]?></td>
                   </tr>
                <?
           
            }

}
?>
