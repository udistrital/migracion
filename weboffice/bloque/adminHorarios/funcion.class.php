<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funciones_adminHorarios extends funcionGeneral {

    //@ Método costructor
    function __construct($configuracion, $sql) {

        $this->cripto = new encriptar();
        $this->sql = $sql;
        //Conexion General
        $this->accesoCoordinador = $this->conectarDB($configuracion, "coordinador");
        $this->acceso_db = $this->conectarDB($configuracion, "");
        $this->idusuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

        //$cadena_sql = $this->sql->cadena_sql($configuracion, "datosUsuario", $this->idusuario);
        //$usuarioSistema = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
        //$this->usuario = $usuarioSistema[0][0];
        $this->formulario = 'adminHorarios';
       
//            $this->verificar="control_vacio('".$this->formulario."','grupo')";
//            $this->verificar.="&& control_vacio('".$this->formulario."','capacidad')";
//            $this->verificar.="&& verificar_rango('".$this->formulario."','grupo',0,99)";
//            $this->verificar.="&& verificar_rango('".$this->formulario."','capacidad',0,100)";
    }


    function encabezado($configuracion){
	  ?>
	  <table class="contenidotabla centrar">
		<tr>
			<td colspan="4" class="cuadro_plano centrar"><h4 >
				GESTI&Oacute;N DE HORARIOS</h4>
			</td>
		</tr>
		<?
		if($this->idusuario)
		{
			$usuario=$this->idusuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];
		}
						
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
	
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$ruta="pagina=adminHorarios";
		$ruta.="&opcion=proyectos";
		$ruta.="&item=crear";
		$rutaadd=$this->cripto->codificar_url($ruta,$configuracion);
	
		$ruta="pagina=adminGestionHorarios";
		$ruta.="&opcion=gestionHorarios";
		$rutacopia=$this->cripto->codificar_url($ruta,$configuracion);
		
		$ruta="pagina=adminHorarios";
		$ruta.="&opcion=consultaHorarios";
		$ruta.="&item=buscar";
		$rutaconsulta=$this->cripto->codificar_url($ruta,$configuracion);
		
		$ruta="pagina=adminHorarios";
		$ruta.="&opcion=consultaHorariosCreados";
		$ruta.="&tipoConsulta=todos";
		$ruta.="&item=buscar";
		$rutahorarioscreados=$this->cripto->codificar_url($ruta,$configuracion);		

		?>
		<tr>
			<td class="cuadro_plano centrar">
				<a href="<?echo $indice.$rutacopia?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/copiarHorario.PNG" alt="copiar" border="0"><br>Copiar Horario</a>
			</td>
			<td class="cuadro_plano centrar">
				<a href="<?echo $indice.$rutaadd?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/addHorario.PNG" alt="add" border="0"><br>Crear Horario</a>
			</td>
			<td class="cuadro_plano centrar">		
				<a href="<?echo $indice.$rutaconsulta?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/verHorario.PNG" alt="add" border="0"><br>Consultar Horario</a>
			</td>
			<td class="cuadro_plano centrar">		
				<a href="<?echo $indice.$rutahorarioscreados?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/verHorario.PNG" alt="add" border="0"><br>Horarios Creados</a>
			</td>			
		</tr>
		<tr>
			<td colspan="4" align="center">
				<table class="formulario" align="center">
						
					<tr>
						<td align="center">
							<p><a href="https://condor.udistrital.edu.co/appserv/manual/gestion_de_horarios.pdf">
							<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
							Ver Manual de Usuario.</a></p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3"></td>
		</tr>
	  </table>

	  <?

    }


// @ Método que permite ver los proyectos curriculares asociados a cada unos de los usuario
function verProyectos($configuracion)
{
		
	//@ Creamos una nueva instancia para las consultas sql
      
	$cadena_sql = $this->sql->cadena_sql($configuracion, "datosCoordinadorCarrera", $this->idusuario);
	$datosCoordinadorCarrera = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");


	$this->verificar="seleccion_valida(".$this->formulario.",'proyecto')";
	$this->verificar.="&&seleccion_valida(".$this->formulario.",'plan')";

	
	$item=isset($_REQUEST['item'])?$_REQUEST['item']:'';
    if($item == "crear")
	{
		echo "<center><h4>CREAR HORARIOS</h4></center>";
		$busquedaRapida=$this->consultarAsignaturaCodigo($configuracion,"plan");
	}
        else
	{
		echo "<center><h4>CONSULTAR HORARIOS</h4></center>";
		$busquedaRapida=$this->consultarAsignaturaCodigo($configuracion);
	}
        //@ Formulario para seleccionar el proyecto curricular
	?>
	
	<center>
	<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
		<br/><br/>
		<table class="formulario" style="width: 90%; text-align: left;" border="0" cellpadding="5" cellspacing="1"  >
			<th align=center class="bloquelateralcuerpo">BÚSQUEDA POR PLAN DE ESTUDIOS</th>
			<tbody>
			<tr>
				<td align="center">
					<select name='proyecto' id='proyecto' onchange="xajax_plan(document.getElementById('proyecto').value)">
					<option value="0">Seleccione...</option>
						<?
						for ($i = 0; $i < count($datosCoordinadorCarrera); $i++) {
						    echo "<option value=" . $datosCoordinadorCarrera[$i][0] . ">" . $datosCoordinadorCarrera[$i][1] . "</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td  align='center'>
				    <div name="div_plan" id="div_plan">
					<select disabled=yes >
					    <option>Plan de estudio</option>
					</select>
				    </div>
				</td>
			</tr>
			</tbody>
			
			<tr>
				<td  align='center'>
					<input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
					<input type='hidden' name='action' value="<? echo $this->formulario ?>">
					<?if ($item=="crear"){?>
					    <input type='hidden' name='opcion' value="plan">
					<?}else{?>
					    <input type='hidden' name='opcion' value="consulta">
					<?}?>
					<!--<input type="hidden" name="proyecto" value="<? //+echo$resultado[2][0]  ?>">-->
					<input value="Seleccionar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
				</td>
			</tr>
		</table>
	</form>
	</center>
	<?
}

function verHorario($configuracion)
{
        $espacio=isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'';
        $grupo=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
        $capacidad=isset($_REQUEST['capacidad'])?$_REQUEST['capacidad']:'';
        $proyecto=$_REQUEST['proyecto'];
        $plan=$_REQUEST['plan']?$_REQUEST['plan']:'';
		$per=isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'';
        $año=substr($per,-6,4);
        $periodo=substr($per,-1);
	 $this->identificacion=isset( $this->identificacion)? $this->identificacion:'';
        $variable = $this->identificacion;

               
        $cadena_sql = $this->sql->cadena_sql($configuracion, "proyecto_curricular", $proyecto);
        $resultadoProyectos = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

       
	?>
	<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
		<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class="cuadro_plano cuadro_color" >
			<tr>
				<td align="center">
					<IMG SRC="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/pequeno_universidad.png"  align="left">
					<b>PROYECTO CURRICULAR </b> <br> <? echo $resultadoProyectos[0][1] ?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<?
					if($plan<>""){
						$arregloEA=array($plan,$proyecto);
						$cadena_sql = $this->sql->cadena_sql($configuracion, "espacios_academicos", $arregloEA);
						$resultadoEspacio = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
					}else{
						$arregloEA=array($espacio,$proyecto);
						$cadena_sql = $this->sql->cadena_sql($configuracion, "rescatarAsignatura", $arregloEA);
						$resultadoEspacio = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
						if(!is_array($resultadoEspacio)){
							echo "Esta asignatura no pertenece a ningun plan de estudios de su Proyecto Curricular";
							exit;
						}else{
							echo "<script>xajax_nuevaBusqueda($espacio,$periodo,'','',$proyecto);</script>";
						}
						
					}


					if(!isset($espacio) && !isset($grupo))
					    {$habilitado =" disabled='true' ";}

					if(isset($espacio) && isset($grupo))
					    {$soloLectura =" readonly='true' ";}

					?>
					<b>ASIGNATURA</b><br>
					<!--        <select name="espacio" id="espacio" size=1 align=center onchange="if(document.getElementById('espacio').value!=0){document.getElementById('grupo').disabled=false;document.getElementById('capacidad').disabled=false;document.getElementById('docEncargado').disabled=false;document.getElementById('periodo').disabled=false;}else{document.getElementById('grupo').disabled=true;document.getElementById('capacidad').disabled=true;document.getElementById('docEncargado').disabled=true;document.getElementById('periodo').disabled=true;}" >-->
					<select name="espacio" id="espacio" size=1 align=center onchange="xajax_nuevaBusqueda(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,<?echo $proyecto;?>);document.getElementById('periodo').disabled=false;document.getElementById('grupo').disabled=false;document.getElementById('capacidad').disabled=false;document.getElementById('btnGrabar').disabled=false;" onmouseover="Tip('<center>Seleccione las asignatura a la cual desea administrar cursos y horarios</center>', SHADOW, true, TITLE, 'Asignatura', PADDING, 9)">
					<option value="0" >Seleccione...</option>
					<?
					for($i=0;$i<count($resultadoEspacio);$i++)
					{
						if($resultadoEspacio[$i][2]!=$resultadoEspacio[$i-1][2])
						{
							if ($resultadoEspacio[$i][2]==0)
							{
								echo "<optgroup label='Electivas'>";
							}
							else
							{
								echo "<optgroup label='Semestre ".$resultadoEspacio[$i][2]."'>";
							}
						}
						if($resultadoEspacio[$i][0] == $_REQUEST['espacio'])
						{
							echo "<option value='".$resultadoEspacio[$i][0]."' selected>".$resultadoEspacio[$i][1]."</option>";
						}
						else
						{
							echo "<option value='".$resultadoEspacio[$i][0]."'>".$resultadoEspacio[$i][1]."</option>";
						}
					}
					?>
					</select>

				</td>
			</tr>
			<tr>
				<td>
					<table class="contenidotabla centrar" border="0">
						<tr>
							<td  class="centrar" onmouseover="Tip('<center>Periodo académico para registrar curso</center>', SHADOW, true, TITLE, 'Periodo', PADDING, 9)">
								<b>PERIODO</b><br>
								<?
								$arregloPeriodo = array($espacio, $proyecto);
								$cadena_sql = $this->sql->cadena_sql($configuracion, "periodo", $arregloPeriodo);//echo $cadena_sql;
								$resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

								$habilitado=isset($habilitado)?$habilitado:'';
								echo ' <select name="periodo" id="periodo" size=1 align=center '.$habilitado.$soloLectura.'>';
								for($j=0;$j<count($resultadoPeriodo);$j++)
								{
								    if(isset($_REQUEST['periodo']) && $_REQUEST['periodo'] == $resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1])
									{
									    echo "<option value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."' selected>".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."</option>";
									}
									echo "<option value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."'>".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."</option>";
								}
								echo '</select>';
								?>
							</td>
								<?
								   /* if ($_REQUEST['capacidad'] <= 0)
									$valor='30';
								    else*/
									$valor=$_REQUEST['capacidad'];

								?>
							<td class="centrar" onmouseover="Tip('<center>Identificación del grupo a registrar</center>', SHADOW, true, TITLE, 'Grupo', PADDING, 9)">
								<b>GRUPO</b><br>
								<input type="text" <?echo $habilitado.$soloLectura?> name='grupo' id="grupo" value="<?echo $_REQUEST['grupo']?>" size="2" align="center" onchange="xajax_validar(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,<?echo $proyecto;?>)">
							</td>
							<td  class="centrar" onmouseover="Tip('<center>Capacidad del curso</center>', SHADOW, true, TITLE, 'Capacidad', PADDING, 9)">
								<b>CAPACIDAD</b><br>
								<input type="text" <?echo $habilitado?> name='capacidad' id="capacidad" value="<?echo $valor?>" size="3" align="center" >
							</td>
		    
						</tr>
						<!--tr class="centrar">
						<td colspan="4" class="centrar">
						<div id="div_docEncargado">
						<b>DOCENTE ENCARGADO</b><br>
						<?
						$cadena_sql = $this->sql->cadena_sql($configuracion, "docente_encargado", $proyecto);
						$resultadoDocente = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

						echo ' <select name="docEncargado" id="docEncargado" size=1 align=center '.$habilitado.'> ';
						for($j=0;$j<count($resultadoDocente);$j++)
						{
						    if(isset($_REQUEST['docEncargado']) && $_REQUEST['docEncargado'] == $resultadoDocente[$j][0])
							{
							    echo "<option value='".$resultadoDocente[$j][0]."' selected>".$resultadoDocente[$j][1]."</option>";
							}else
							    {
								echo "<option value='".$resultadoDocente[$j][0]."'>".$resultadoDocente[$j][1]."</option>";
							    }
						}
						echo '</select>';      
						?>
						</div>
						</td>
						</tr-->
					</table>
				</td>  
			</tr>
		</table>
		<?
		$this->verificar="control_vacio(".$this->formulario.",'grupo')";
		$this->verificar.="&&control_vacio(".$this->formulario.",'capacidad')";
		$this->verificar.="&&control_vacio(".$this->formulario.",'espacio')";
		$this->verificar.="&&verificar_numero(".$this->formulario.",'grupo')";
		$this->verificar.="&&verificar_numero(".$this->formulario.",'capacidad')";
    //            $this->verificar.="&&seleccion_valida(".$this->formulario.",'docEncargado')";
		$this->verificar.="&&seleccion_valida(".$this->formulario.",'periodo')";
		$this->verificar.="&&seleccion_valida(".$this->formulario.",'espacio')";
		$this->verificar.="&&verificar_rango(".$this->formulario.",'capacidad','0','100')";
		?>
		<table align=center style="width: 100%; text-align: left;" class="bloquelateral cuadro_color " >
			<tr>
				<td align="center">
					<input type="hidden" name="action" value="<? echo $this->formulario ?>">
					<input type="hidden" name="opcion" value="guardado">
					<input type='hidden' name='verHorario' id='hidHorario' value='0'>
					<input type="hidden" name="proyecto" value="<? echo $proyecto ?>">
					<input type="hidden" name="plan" value="<? echo $plan ?>" >
					<input <?if(!isset($espacio) && !isset($grupo)){echo "disabled='true'";echo "if";}?> value="<?if(isset($espacio) && isset($grupo)){echo "Actualizar Curso";}else{echo "Guardar Curso";}?>" id="btnGrabar" name="aceptar" type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}" >
					<div id="div_btnHorario" style="display:none;">
					    <input value="Ver Horario" id="btnHorario" name="horario" type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
					</div>
					<div id="div_btnNuevaBusqueda" <?if(isset($soloLectura)){echo "style='display:block;'";}else{echo "style='display:none;'";}?>>
					    <input value="Nueva Busqueda" id="btnBusqueda" name="busqueda" type="button" onclick="xajax_nuevaBusqueda(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,<?echo $proyecto;?>)">
					</div>
				</td>
			</tr>
		</table>
		<?
		if($_REQUEST['espacio'] && $_REQUEST['grupo'])
		{
			?>
			<div id="div_mostrarHorario" style="display:block">
			<table border="0" style="width: 100%; text-align: left;" class="cuadro_plano cuadro_color " >
			<thead>
				<tr class="centrar">
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/dia_hora.jpg" width="100" height="40"> </th>
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/lunes.jpg" width="100" height="40"> </th>
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/martes.jpg" width="100" height="40"> </th>
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/miercoles.jpg" width="100" height="40"> </th>
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/jueves.jpg" width="100" height="40"> </th>
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viernes.jpg" width="100" height="40"> </th>
					<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/sabado.jpg" width="100" height="40"> </th>
				</tr>
			</thead>
			<tbody>
			<?
				 $espacio=isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'';
				 $grupo=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
			        $arregloCurso = array($espacio,$grupo,$año,$periodo);
			        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "infoJornadaCurso",$arregloCurso);
			        $cursoJornada = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");

			$this->cadena_sql = $this->sql->cadena_sql($configuracion, "hora", "");
			$resultado = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");

			$i = 0;
			$habilitadoHor = 0;
			while (isset($resultado[$i][0]))
			{
				if($i%2==0)
				{
				      $color = "#BAD6FD";
				}
				else
				{
				      $color = "#F4F5EB";
				}
				$cursoJornada[0][0]=isset($cursoJornada[0][0])?$cursoJornada[0][0]:'';  
				switch($cursoJornada[0][0])
				{
					default :
					$habilitadoHor = 1;
					break;
				}

				$qryfecha="SELECT TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FROM dual";
				$rsFecha=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $qryfecha, "busqueda");
				
				if($año=='' && $periodo=='')
				{
					EXIT;
				}
				else
				{
					$variable=array($proyecto, $año, $periodo, $rsFecha[0][0]);
				
					$cadena_sql=$this->sql->cadena_sql($configuracion, "valida_fecha", $variable);
					$rsValida=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
				}
				?>
				<tr style="<?echo "background:".$color?>">
					<td align="center" valign="middle">
						<? echo "<br>".$resultado[$i][1] ?>
					</td>
					<?
					if($habilitadoHor==1)
					{
						if(is_array($rsValida))
						{
							$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",1);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",1)";
							$str="<center>Click para registrar salon el dia Lunes de ".$resultado[$i][1]."</center>";
						}
						else
						{
							$evt="javascript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
							$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
						}

					?>
					<td align="center" valign="middle" onclick="<?echo $evt;?>" onmouseover="Tip('<?echo $str;?>', SHADOW, true, TITLE, 'Lunes  <?echo $resultado[$i][1];?>', PADDING, 9)">
						<?
						$arregloHorario = array($espacio,$grupo,1,$resultado[$i][0],$año,$periodo);
						$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);//echo $this->cadena_sql;exit;
						$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
						?>
						<div id="1_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
					</td>
					<?
					}
					else
					{
						?>
						<td align="center" valign="middle">

						</td>
						<?
					}
                
					if($habilitadoHor==1)
					{
						if(is_array($rsValida))
						{
							$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",2);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",2)";
							$str="<center>Click para registrar salon el dia Martes de ".$resultado[$i][1]."</center>";
						}
						else
						{
							$evt="javascript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
							$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
						}

						?>
						<td align="center" valign="middle" onclick="<?echo $evt;?>" onmouseover="Tip('<?echo $str;?>', SHADOW, true, TITLE, 'Martes  <?echo $resultado[$i][1];?>', PADDING, 9)">
							<?
							$arregloHorario = array($espacio,$grupo,$resultado[$i][0],2,$año,$periodo);
							$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
							$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
							?>
							<div id="2_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
						</td>
						<?
					}
					else
					{
						?>
						<td align="center" valign="middle">

						</td>
						<?
					}

					if($habilitadoHor==1)
					{
						if(is_array($rsValida))
						{
							$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",3);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",3)";
							$str="<center>Click para registrar salon el dia Miercoles de ".$resultado[$i][1]."</center>";
						}
						else
						{
							$evt="javascript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
							$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
						}

						?>
						<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str;?>', SHADOW, true, TITLE, 'Miercoles  <?echo $resultado[$i][1];?>', PADDING, 9)">
							<?
							$arregloHorario = array($espacio,$grupo,3,$resultado[$i][0],$año,$periodo);
							$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
							$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
							?>
							<div id="3_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
						</td>
						<?
					}
					else
					{
						?>
						<td align="center" valign="middle">

						</td>
						<?
					}

					if($habilitadoHor==1)
					{
						if(is_array($rsValida))
						{
							$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",4);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",4)";
							$str="<center>Click para registrar salon el dia Jueves de ".$resultado[$i][1]."</center>";
						}
					else
					{
						$evt="javascript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
						$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
					}

					?>
					<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Jueves  <?echo $resultado[$i][1];?>', PADDING, 9)">
						<?
						$arregloHorario = array($espacio,$grupo,4,$resultado[$i][0],$año,$periodo);
						$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
						$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
						?>
						<div id="4_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
					</td>
					<?
					}
					else
					{
						?>
						<td align="center" valign="middle">

						</td>
						<?
					}

					if($habilitadoHor==1)
					{
						if(is_array($rsValida))
						{
							$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",5);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",5)";
							$str="<center>Click para registrar salon el dia Viernes de ".$resultado[$i][1]."</center>";
						}
					else
					{
						$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
						$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
					}

					?>
					<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Viernes  <?echo $resultado[$i][1];?>', PADDING, 9)">
						<?
						$arregloHorario = array($espacio,$grupo,5,$resultado[$i][0],$año,$periodo);
						$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
						$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
						?>
						<div id="5_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
					</td>
					<?
					}
					else
					{
						?>
						<td align="center" valign="middle">

						</td>
						<?
					}

					if($i<12)
					{
						if(is_array($rsValida))
						{
							$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",6);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",6)";
							$str="<center>Click para registrar salon el dia Sabado de ".$resultado[$i][1]."</center>";
						}
						else
						{
							$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
							$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
						}
						?>
						<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Sabado  <?echo $resultado[$i][1];?>', PADDING, 9)">
							<?
							$arregloHorario = array($espacio,$grupo,6,$resultado[$i][0],$año,$periodo);
							$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
							$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
							?>
							<div id="6_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
						</td>
						<?
					}
					else
					{
						?>
						<td align="center" valign="middle">

						</td>
						<?
					}
				?>
				</tr>
				<?
			$i++;
			}
			?>
			</tbody>
			</table>
			</div>
		<?
		}
		?>
	</form>
	<?
}


    function guardarCurso($configuracion)
        {
            $año=substr($_REQUEST['periodo'],-6,4);
            $periodo=substr($_REQUEST['periodo'],-1);
            $espacio=$_REQUEST['espacio'];
            $grupo=$_REQUEST['grupo'];
            $capacidad=$_REQUEST['capacidad'];
            $proyecto=$_REQUEST['proyecto'];
            $plan=$_REQUEST['plan'];
            $curSesion=1;
       

            if(isset($_REQUEST['verHorario']) && $_REQUEST['verHorario']==1)
            {
                $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=adminHorarios";
                $ruta.="&opcion=generar";
                $ruta.="&proyecto=".$proyecto;
                $ruta.="&plan=".$plan;
                $ruta.="&grupo=".$grupo;
                $ruta.="&capacidad=".$capacidad;
                $ruta.="&periodo=".$_REQUEST['periodo'];
                $ruta.="&espacio=".$espacio;
               
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$indice.$ruta."')</script>";
                //exit;
            }

            $arregloPensum = array($espacio,$proyecto);
            $cadena_sql = $this->sql->cadena_sql($configuracion, "infoAsignatura", $arregloPensum);//echo $cadena_sql."<br>";
            $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"busqueda");
            
            $semestre=$resultado[0][0];
            
            $arregloAsig = array($espacio,$grupo,$año,$periodo);
            
            $cadena_sql = $this->sql->cadena_sql($configuracion, "infoCurso", $arregloAsig); //echo $cadena_sql;
            $resultadoexiste=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"busqueda");
           
			//si el curso ya esta creado con su respectiva capacidad
            if(is_array($resultadoexiste))
            {
				//Si se desea cambiar la capacidad se deben revisar todos los horarios creados para no generar
				//incovenientes con la capacidad de los salones
                if(($resultadoexiste[0][0] != $capacidad))
                    {

                        $variable=array($espacio, $grupo, $año, $periodo);
						
						//con esta consulta revisamos si el grupo ya tiene horarios creados
                        $cadena_sql=$this->sql->cadena_sql($configuracion, "infoHorario", $variable); 
						$resultadoSalon=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

                        $valida=0;
                        
						$mensajeErrorCapacidad="";
						
						if(is_array($resultadoSalon)){
							$i=0;
							while(isset($resultadoSalon[$i][0])){
								$variable=array($resultadoSalon[$i][0], $resultadoSalon[$i][1]);
								$cadena_sql=$this->sql->cadena_sql($configuracion, "infoSalon", $variable);
								$resultadoCapacidadSalon=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
								if($capacidad > $resultadoCapacidadSalon[0][0]){
									$valida=1;
										$mensajeErrorCapacidad.=" * La capacidad máxima del salón ".$resultadoSalon[$i][1]." ocupado el dia ".$resultadoSalon[$i][2]." a las ".$resultadoSalon[$i][3]." horas es ".$resultadoCapacidadSalon[0][0];
									}
								$i++;
							}
						}
						
						
                        //exit;
                        if ($valida == 1){//echo "mas";
                            echo "<script>alert('NO SE PUDO ACTUALIZAR LOS DATOS DEL GRUPO ".$grupo." POR LOS SIGUIENTES MOTIVOS: $mensajeErrorCapacidad ');</script>";
                            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=adminHorarios";
                            if($_REQUEST['funcion']=="consulta")
                                $ruta.="&opcion=generar";
                            else
                            $ruta.="&opcion=verHorarioGrupo";
                            $ruta.="&proyecto=".$proyecto;
                            $ruta.="&plan=".$plan;
                            $ruta.="&grupo=".$grupo;
                            $ruta.="&capacidad=".$capacidad;
                            $ruta.="&periodo=".$_REQUEST['periodo'];
                            $ruta.="&espacio=".$espacio;
                          

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                            echo "<script>location.replace('".$indice.$ruta."')</script>";
						  
                        }else{

                            if($valida==0)
                            {
                                $arregloAct = array($espacio,$grupo,$año,$periodo,$capacidad,$docEncargado);
                                $cadena_sql = $this->sql->cadena_sql($configuracion, "actualizarCurso", $arregloAct);
                                $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"");

                                if($resultado==true)
                                    {
                                        echo "<script>alert('SE ACTUALIZO LOS DATOS DEL GRUPO ".$grupo.", RECUERDE QUE SOLO SE ACTUALIZA LA CAPACIDAD');</script>";
                                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $ruta="pagina=adminHorarios";
                                        if($_REQUEST['funcion']=="consulta")
                                            $ruta.="&opcion=generar";
                                        else
                                            $ruta.="&opcion=verHorarioGrupo";
                                        $ruta.="&proyecto=".$proyecto;
                                        $ruta.="&plan=".$plan;
                                        $ruta.="&grupo=".$grupo;
                                        $ruta.="&capacidad=".$capacidad;
                                        $ruta.="&periodo=".$_REQUEST['periodo'];
                                        $ruta.="&espacio=".$espacio;
                                        $ruta.="&funcion=".$_REQUEST['funcion'];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                        echo "<script>location.replace('".$indice.$ruta."')</script>";
                                    }else
                                        {
                                            echo "<script>alert('NO SE PUDO ACTUALIZAR LOS DATOS DEL GRUPO ".$grupo."');</script>";
                                            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $ruta="pagina=adminHorarios";
                                            if($_REQUEST['funcion']=="consulta")
                                                $ruta.="&opcion=generar";
                                            else
                                            $ruta.="&opcion=verHorarioGrupo";
                                            $ruta.="&proyecto=".$proyecto;
                                            $ruta.="&plan=".$plan;
                                            $ruta.="&grupo=".$grupo;
                                            $ruta.="&capacidad=".$capacidad;
                                            $ruta.="&periodo=".$_REQUEST['periodo'];
                                            $ruta.="&espacio=".$espacio;
                                            $ruta.="&funcion=".$_REQUEST['funcion'];


                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                           // echo "<script>location.replace('".$indice.$ruta."')</script>";
                                        }
                            }else{
                                    echo "<script>alert('NO SE PUDO ACTUALIZAR LOS DATOS DEL GRUPO ".$grupo."');</script>";
                                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $ruta="pagina=adminHorarios";
                                    if($_REQUEST['funcion']=="consulta")
                                        $ruta.="&opcion=generar";
                                    else
                                    $ruta.="&opcion=verHorarioGrupo";
                                    $ruta.="&proyecto=".$proyecto;
                                    $ruta.="&plan=".$plan;
                                    $ruta.="&grupo=".$grupo;
                                    $ruta.="&capacidad=".$capacidad;
                                    $ruta.="&periodo=".$_REQUEST['periodo'];
                                    $ruta.="&espacio=".$espacio;
                                    $ruta.="&funcion=".$_REQUEST['funcion'];


                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                    echo "<script>location.replace('".$indice.$ruta."')</script>";

                            }
                      }
                    }else
                        {
			    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			    $cadena="CURSO YA REGISTRADO, POR FAVOR CAMBIE EL NUMERO DEL GRUPO.<br>";

			    $cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			    alerta::sin_registro($configuracion,$cadena);

                           /* echo "<script>alert('CURSO YA REGISTRADO, POR FAVOR CAMBIE EL NUMERO DEL GRUPO');</script>";
                            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=adminHorarios";
                            $ruta.="&opcion=generar";
                            $ruta.="&proyecto=".$proyecto;
                            $ruta.="&plan=".$plan;
                            $ruta.="&grupo=".$grupo;
                            $ruta.="&capacidad=".$capacidad;
                            $ruta.="&periodo=".$_REQUEST['periodo'];
                            $ruta.="&espacio=".$espacio;
                            $ruta.="&docEncargado=".$docEncargado;
                            $ruta.="&funcion=".$_REQUEST['funcion'];
                           

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                            echo "<script>location.replace('".$indice.$ruta."')</script>";*/
                        }
            }else
            {

                $variable=$this->idusuario;
                $cadena_sql = $this->sql->cadena_sql($configuracion, "consultaDependencia", $variable);//echo $cadena_sql; exit;
                $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"busqueda");


                $arregloInsertarCurso = array($año,$periodo,$espacio,$grupo,$proyecto,$capacidad,'A',$semestre, $resultado[0][0], $curSesion);
                $cadena_sql = $this->sql->cadena_sql($configuracion, "insertarCurso", $arregloInsertarCurso);//echo $cadena_sql;exit;
                $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"");
		//echo $cadena_sql."<br>";
		//EXIT;
                    if($resultado==true)
                    {

                        echo "<script>alert('REGISTRO EXITOSO');</script>";
                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=adminHorarios";
                        $ruta.="&opcion=generar";
                        $ruta.="&proyecto=".$proyecto;
                        $ruta.="&plan=".$plan;
                        $ruta.="&grupo=".$grupo;
                        $ruta.="&capacidad=".$capacidad;
                        $ruta.="&periodo=".$_REQUEST['periodo'];
                        $ruta.="&espacio=".$espacio;
                        $ruta.="&funcion=".$_REQUEST['funcion'];
                        

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        //echo "<script>location.replace('".$indice.$ruta."')</script>";



                    }else
                    {
                        echo "<script>alert('REGISTRO FALLO CURSO');</script>";
                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=adminHorarios";
                        $ruta.="&opcion=generar";
                        $ruta.="&proyecto=".$proyecto;
                        $ruta.="&plan=".$plan;
                        $ruta.="&grupo=".$grupo;
                        $ruta.="&capacidad=".$capacidad;
                        $ruta.="&periodo=".$_REQUEST['periodo'];
                        $ruta.="&espacio=".$espacio;
                        $ruta.="&funcion=".$_REQUEST['funcion'];
                 
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        echo "<script>location.replace('".$indice.$ruta."')</script>";
                    }

            }

        }

        function consultaHorario($configuracion){
            $plan=$_REQUEST['plan'];
            $proyecto=$_REQUEST['proyecto'];
            $tipo="consulta";

            $this->descripcionFuncion($configuracion, $tipo, $proyecto);

	    
            $cadena_sql = $this->sql->cadena_sql($configuracion, "proyecto_curricular", $proyecto);
            $resultadoProyectos = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
            
		?>
		</form>

		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
		    <table class="contenidotabla centrar" border="0">
			    <tbody>
				    <tr>
					    <td align="center">
						    <?
							    $arregloEA=array($plan,$proyecto);
							    $cadena_sql = $this->sql->cadena_sql($configuracion, "espacios_academicos", $arregloEA);
							    $resultadoEspacio = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

							    if(!isset($espacio) && !isset($grupo))
								{$habilitado =" disabled='true' ";}

							    if(isset($espacio) && isset($grupo))
								{$soloLectura =" readonly='true' ";}

						    ?>
							    <b>ASIGNATURA</b><br>
						    <!--        <select name="espacio" id="espacio" size=1 align=center onchange="if(document.getElementById('espacio').value!=0){document.getElementById('grupo').disabled=false;document.getElementById('capacidad').disabled=false;document.getElementById('docEncargado').disabled=false;document.getElementById('periodo').disabled=false;}else{document.getElementById('grupo').disabled=true;document.getElementById('capacidad').disabled=true;document.getElementById('docEncargado').disabled=true;document.getElementById('periodo').disabled=true;}" >-->
							    <select name="espacio" id="espacio" size=1 align=center  onmouseover="Tip('<center>Seleccione las asignatura para consultar el horario</center>', SHADOW, true, TITLE, 'Asignatura', PADDING, 9)">
							    <option value="0" >Seleccione...</option>
						    <?
							    for($i=0;$i<count($resultadoEspacio);$i++)
							    {
								if($resultadoEspacio[$i][2]!=$resultadoEspacio[$i-1][2])
								    {
									    if ($resultadoEspacio[$i][2]==0){
										echo "<optgroup label='Electivas'>";
									    }
									    else
									    echo "<optgroup label='Semestre ".$resultadoEspacio[$i][2]."'>";
								    }
								    if($resultadoEspacio[$i][0] == $_REQUEST['espacio'])
									{
									    echo "<option value='".$resultadoEspacio[$i][0]."' selected>".$resultadoEspacio[$i][1]."</option>";
									}else
									    {
										echo "<option value='".$resultadoEspacio[$i][0]."'>".$resultadoEspacio[$i][1]."</option>";
									    }

							    }
						    ?>
							    </select>

					    </td>
					    <td  class="centrar" onmouseover="Tip('<center>Periodo académico para realizar la consulta del horario</center>', SHADOW, true, TITLE, 'Periodo', PADDING, 9)">
						    <b>PERIODO</b><br>
						    <?
						      // $arregloPeriodo = array($espacio, $proyecto);
							$arregloPeriodo=isset($arregloPeriodo)?$arregloPeriodo:'';  
							$cadena_sql = $this->sql->cadena_sql($configuracion, "periodo", $arregloPeriodo);//echo $cadena_sql;
							$resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");


							echo ' <select name="periodo" id="periodo" size=1 align=center>';
							for($j=0;$j<count($resultadoPeriodo);$j++)
							{
							    if(isset($_REQUEST['periodo']) && $_REQUEST['periodo'] == $resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1])
								{
								    echo "<option value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."' selected>".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."</option>";
								}
								echo "<option value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."'>".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."</option>";
							}
							echo '</select>';
						    ?>
					    </td>
				    </tr>
				    <tr> 
					    <td align="center">
						    <input type='hidden' name='action' value='<? echo $this->formulario?>'>
						    <input type='hidden' name='opcion' value='consultagrupos'>
						    <input type='hidden' name='plan' value='<? echo $plan?>'>
						    <input type='hidden' name='proyecto' value='<? echo $proyecto?>'> 
						    <input type="submit" name="buscar" value="BUSCAR">
					    </td>
				    </tr>
			    </tbody>
		    </table>
		</form>
		<?
        }

function consultarAsignaturaCodigo($configuracion,$opcion="consultagrupos"){
/*
		echo '<script src="'.$configuracion["host"].$configuracion["site"].$configuracion["javascript"].'/formulario/lib/jquery.js" type="text/javascript"></script>';
		echo '<script src="'.$configuracion["host"].$configuracion["site"].$configuracion["javascript"].'/formulario/lib/jquery.metadata.js" type="text/javascript"></script>';
		echo '<script src="'.$configuracion["host"].$configuracion["site"].$configuracion["javascript"].'/formulario/jquery.validate.js" type="text/javascript"></script>';
*/	
				
		echo '<form class="centrar" name="busquedaRapida" id="busquedaRapida" ><center>';
		echo '<br>';
		echo '<table style="width:90%" class="formulario contenidotabla centrar">';
		echo '<tr>';
		echo '	<th colspan="3"><center>BUSQUEDA RÁPIDA:</center></th>';
		echo '</tr>';		
		echo '<tr>';
		echo '	<th>PASO 1</th><th>PASO 2</th><th>PASO 3</th>';
		echo '</tr>';
		echo '<tr>';
		echo '	<td>';
		echo '		CODIGO ASIGNATURA:<br/>';
		echo '		<input class="required" type="text" name="espacio" id="espacio"/>';
		echo '	</td>';
		echo '	<td>';
		echo '		PERIODO:';

					$cadena_sql = $this->sql->cadena_sql($configuracion, "periodo");
					$resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
					for($j=0;$j<count($resultadoPeriodo);$j++){
						$checked=$j==0?'checked':'';
						echo "<br/><input type='radio' name='periodo' value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."' ".$checked." />".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1];
					}
		echo '	</td>';
		echo '	<td>';
	
			
				$cadena_sql = $this->sql->cadena_sql($configuracion, "datosCoordinadorCarrera", $this->idusuario);
				$datosCoordinadorCarrera = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

				echo "<input type='hidden' name='proyecto' value='' checked />";
				echo 'PROYECTO CURRICULAR:';
				
				for ($i = 0; $i < count($datosCoordinadorCarrera); $i++) {
					echo "<br/><input onchange='document.forms[\"busquedaRapida\"].submit()'  type='radio'  name='proyecto' value=" . $datosCoordinadorCarrera[$i][0] . " />" . $datosCoordinadorCarrera[$i][1];
				}
				
					
		echo '	</td>';	
		echo '</tr>';		
		echo '</table>';	
		echo "		<input type='hidden' name='action' value='".$this->formulario."'>";
		echo "		<input type='hidden' name='tipoConsulta' value='rapida'>";
		echo "		<input type='hidden' name='opcion' value='".$opcion."'>";
		echo '</center></form>';
}		
		

function formularioHorariosCompletos($configuracion){

				
		echo '<form class="centrar" name="busquedaHorarios" id="busquedaHorarios" ><center>';
		echo '<br>';
		echo '<table style="width:90%" class="formulario contenidotabla centrar">';
		echo '<tr>';
		echo '	<th colspan="3"><center>SELECCIONE:</center></th>';
		echo '</tr>';		
		echo '<tr>';
		echo '	<th>PASO 1</th><th>PASO 2</th><th>PASO 3</th>';
		echo '</tr>';
		echo '<tr>';
		echo '	<td>';
		echo '		PERIODO:';

					$cadena_sql = $this->sql->cadena_sql($configuracion, "periodoconanterior");
					$resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
					for($j=0;$j<count($resultadoPeriodo);$j++){
						$checked=$j==0?'checked':'';
						echo "<br/><input type='radio' name='periodo' value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."' ".$checked." />".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1];
					}
		echo '	</td>';
		echo '	<td>';

				$cadena_sql = $this->sql->cadena_sql($configuracion, "datosCoordinadorCarrera", $this->idusuario);
				$datosCoordinadorCarrera = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

				echo "<input type='hidden' name='proyecto' value='' checked />";
				echo 'PROYECTO CURRICULAR:';
				
				for ($i = 0; $i < count($datosCoordinadorCarrera); $i++) {
					echo "<br/><input  type='radio'  name='proyecto' value=" . $datosCoordinadorCarrera[$i][0] . " />" . $datosCoordinadorCarrera[$i][1];
				}
				
					
		echo '	</td>';	
		echo '	<td>';
				echo "<input type='hidden' name='order' value='' checked />";
				echo 'ORGANIZAR POR:';
				echo "<br/><input onchange='document.forms[\"busquedaHorarios\"].submit()'  type='radio'  name='order' value='3' /> Nombre";
				echo "<br/><input onchange='document.forms[\"busquedaHorarios\"].submit()'  type='radio'  name='order' value='2' /> Código";
				
		echo '	</td>';			
		echo '</tr>';		
		echo '</table>';	
		echo "		<input type='hidden' name='action' value='".$this->formulario."'>";
		echo "		<input type='hidden' name='tipoConsulta' value='todos'>";
		echo "		<input type='hidden' name='opcion' value='consultagrupos'>";
		echo '</center></form>';
}	

		
function consultaGrupos($configuracion)
{

	$plan=$_REQUEST['plan'];
	$proyecto=$_REQUEST['proyecto'];
	$asignatura=$_REQUEST['espacio'];
	$año=substr($_REQUEST['periodo'],-6,4);
	$periodo=substr($_REQUEST['periodo'],-1);
	$tipoConsulta=$_REQUEST['tipoConsulta']?$_REQUEST['tipoConsulta']:"";
	$order=$_REQUEST['order']?$_REQUEST['order']:"1";

	$variable=array($proyecto, $plan, $asignatura, $año, $periodo,$order);
	
	
	if($tipoConsulta=="rapida"){
		$cadena_sql=$this->sql->cadena_sql($configuracion, "consultaGruposRapida", $variable);
		$rsGrupos=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
	}
	elseif($tipoConsulta=="todos"){
	
		if(!isset($año) || !isset($periodo)  || !isset($proyecto) ){
			$this->formularioHorariosCompletos($configuracion);		
			exit;
		}else{
			$cadena_sql=$this->sql->cadena_sql($configuracion, "consultaGruposTodos", $variable);
			$rsGrupos=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
		}
		
		
	}
	else{
		$cadena_sql=$this->sql->cadena_sql($configuracion, "consultaGrupos", $variable);
		$rsGrupos=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
	}
	
	

	$tipo="consulta";

	$this->descripcionFuncion($configuracion, $tipo, $proyecto);
	?>
	</form>
	<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
	
	<table class="contenidotabla centrar" border="0">
	
	<?if (is_array($rsGrupos)){?>
	
	    <tr>
		<th class="sigma">C&oacute;digo</th>
		<th class="sigma">Asignatura</th>
		<th class="sigma centrar">Grupo</th>
		<th class="sigma centrar">Cup</th>
		<th class="sigma centrar">Ins</th>
		<th class="sigma centrar">Disp</th>
		<th class="sigma centrar">ver</th>
	    </tr>
	    <?for($i=0; $i<count($rsGrupos); $i++){?>
	    <tr>
		<td class="cuadro_plano"><?echo $rsGrupos[$i][1]?></td>
		<td class="cuadro_plano"><?echo $rsGrupos[$i][2]?></td>
		<td class="cuadro_plano centrar"><?echo $rsGrupos[$i][0]?></td>
		<td class="cuadro_plano centrar"><?echo $rsGrupos[$i][3]?></td>
		<td class="cuadro_plano centrar"><?echo $rsGrupos[$i][4]?></td>
		<td class="cuadro_plano centrar"><?echo $rsGrupos[$i][5]?></td>
		<?
		    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		    $ruta="pagina=adminHorarios";
		    $ruta.="&opcion=verHorarioGrupo";
		    $ruta.="&espacio=".$rsGrupos[$i][1];
		    $ruta.="&grupo=".$rsGrupos[$i][0];
		    $ruta.="&plan=".$plan;
		    $ruta.="&proyecto=".$proyecto;
		    $ruta.="&periodo=".$_REQUEST['periodo'];
		    $ruta.="&capacidad=".$rsGrupos[$i][3];
		      
		      include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		      $this->cripto=new encriptar();
		      $ruta=$this->cripto->codificar_url($ruta,$configuracion);
		?>
		<td class="cuadro_plano centrar"><a href="<?echo $indice.$ruta?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/ver.png" border="0"></a></td>
	    </tr>
	    <?}?>
	
	<?}else{?>
	<tr>
	    <td align="center" color="red">.::No existe horario para la asignatura seleccionada::.</td>
	</tr>
	<?}?>
	<tr>
	    <td align="center" colspan="4">
		<input type="submit" value="Nueva Consulta">
		<input type="hidden" name="action" value="<?echo $this->formulario?>">
		<input type="hidden" name="opcion" value="nuevaconsulta">
		<input type="hidden" name="plan" value="<?echo $plan?>">
		<input type="hidden" name="proyecto" value="<?echo $proyecto?>">
	    </td>
	</tr>
	</table>
	</form>
	<?

}

function descripcionFuncion($configuracion, $tipo, $proyecto)
{
	$cadena_sql = $this->sql->cadena_sql($configuracion, "proyecto_curricular", $proyecto);
	$resultadoProyectos = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
	
	if ($tipo="consulta")
	    $modulo="CONSULTA HORARIOS";
	?>
	<table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
	      <tr>
		    <td style='text-align:center' align="center" colspan="2">
			<h4 class="bloquelateralcuerpo"><?echo $modulo?></h4>
			<hr>
		    </td>
	      </tr>
	</table>
	<table class="contenidotabla centrar" border="0">
	    <th align=center colspan="4" class="bloquelateralcuerpo">
		    PROYECTO CURRICULAR <br><?echo $resultadoProyectos[0][1]?>
	    </th>
	</table
	<?
}

function verHorarioGrupo($configuracion)
{
	$proyecto=$_REQUEST['proyecto'];
	$plan=isset($_REQUEST['plan'])?$_REQUEST['plan']:"";
	$espacio=$_REQUEST['espacio'];
	$grupo=$_REQUEST['grupo'];
	$capacidad=$_REQUEST['capacidad'];
	$año=substr($_REQUEST['periodo'],-6,4);
	$periodo=substr($_REQUEST['periodo'],-1);
	$tipo="consulta";

	$this->descripcionFuncion($configuracion, $tipo, $proyecto);
	
	$this->verificar="control_vacio(".$this->formulario.",'capacidad')";
	//$this->verificar.="&&control_vacio(".$this->formulario.",'capacidad')";
	//$this->verificar.="&&control_vacio(".$this->formulario.",'espacio')";
	//$this->verificar.="&&verificar_numero(".$this->formulario.",'grupo')";
	$this->verificar.="&&verificar_numero(".$this->formulario.",'capacidad')";
	//$this->verificar.="&&seleccion_valida(".$this->formulario.",'docEncargado')";
	//$this->verificar.="&&seleccion_valida(".$this->formulario.",'periodo')";
	//$this->verificar.="&&seleccion_valida(".$this->formulario.",'espacio')";
	$variable=array($proyecto, $plan, $espacio, $año, $periodo,$grupo);
	$cadena_sql=$this->sql->cadena_sql($configuracion, "infoGrupo",$variable);
	$rsGrupo=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
	
	?>
	</form>
	<form  enctype='multipart/form-data' method='POST' action='index.php' name='<?echo $this->formulario?>'>
	<table align=center style="width: 100%; text-align: left;" class="bloquelateral cuadro_color " >
		<tr>
			<td align="center">
				<?
				
				if($plan<>""){
					
					$arregloEA=array($plan,$proyecto);
					$cadena_sql = $this->sql->cadena_sql($configuracion, "espacios_academicos", $arregloEA);				
					$resultadoEspacio = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

					if(!isset($espacio) && !isset($grupo))
						{$habilitado =" disabled='true' ";}

					if(isset($espacio) && isset($grupo))
						{$soloLectura =" readonly='true' ";}
					?>
					<b>ASIGNATURA</b><br>
					<!--        <select name="espacio" id="espacio" size=1 align=center onchange="if(document.getElementById('espacio').value!=0){document.getElementById('grupo').disabled=false;document.getElementById('capacidad').disabled=false;document.getElementById('docEncargado').disabled=false;document.getElementById('periodo').disabled=false;}else{document.getElementById('grupo').disabled=true;document.getElementById('capacidad').disabled=true;document.getElementById('docEncargado').disabled=true;document.getElementById('periodo').disabled=true;}" >-->
					<select name="espacio" id="espacio" disabled="true" size=1 align=center onchange="xajax_nuevaBusqueda(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,<?echo $proyecto;?>);document.getElementById('periodo').disabled=false;document.getElementById('grupo').disabled=false;document.getElementById('capacidad').disabled=false;document.getElementById('btnGrabar').disabled=false;" onmouseover="Tip('<center>Seleccione las asignatura a la cual desea administrar cursos y horarios</center>', SHADOW, true, TITLE, 'Asignatura', PADDING, 9)">
					<option value="0" >Seleccione...</option>
					<?
					for($i=0;$i<count($resultadoEspacio);$i++)
					{
						if($resultadoEspacio[$i][2]!=$resultadoEspacio[$i-1][2])
						{
							if  ($resultadoEspacio[$i][2]==0)
							{
							echo "<optgroup label='Electivas'>";
							}
							else
							{
								echo "<optgroup label='Semestre ".$resultadoEspacio[$i][2]."'>";
							}
						}
						if($resultadoEspacio[$i][0] == $_REQUEST['espacio'])
						{
							echo "<option value='".$resultadoEspacio[$i][0]."' selected>".$resultadoEspacio[$i][1]."</option>";
						}
						else
						{
							echo "<option value='".$resultadoEspacio[$i][0]."'>".$resultadoEspacio[$i][1]."</option>";
						}

					}
					echo "</select>";

				}
				else{

					echo $rsGrupo[0][2];	
					echo "<input  name='espacio' id='espacio' type='hidden' value='".$rsGrupo[0][1]."' />";
				}				
				?>
					
			</td>
		</tr>
		<tr>
			<td>
				<table class="contenidotabla centrar" border="0">
					<tr>
						<td  class="centrar" onmouseover="Tip('<center>Periodo académico para registrar curso </center>', SHADOW, true, TITLE, 'Periodo', PADDING, 9)">
							<b>PERIODO</b><br>
							<?
							$arregloPeriodo = array($espacio, $proyecto);
							$cadena_sql = $this->sql->cadena_sql($configuracion, "periodo", $arregloPeriodo);//echo $cadena_sql;
							$resultadoPeriodo = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
							$habilitado=isset($habilitado)?$habilitado:'';
							echo ' <select name="periodo" id="periodo" size=1 align=center '.$habilitado.$soloLectura.' disabled="true">';
							for($j=0;$j<count($resultadoPeriodo);$j++)
							{
								if(isset($_REQUEST['periodo']) && $año."-".$periodo == $resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1])
								{
									echo "<option value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."' selected>".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."</option>";
								}
								echo "<option value='".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."'>".$resultadoPeriodo[$j][0]."-".$resultadoPeriodo[$j][1]."</option>";
							}
							echo '</select>';
							?>
						</td>
						<?
							$valor=$rsGrupo[0][3];

						?>
						<td class="centrar" onmouseover="Tip('<center>Identificación del grupo a registrar</center>', SHADOW, true, TITLE, 'Grupo', PADDING, 9)">
							<b>GRUPO</b><br>
							<input type="text" <?echo $habilitado.$soloLectura?> name='grupo' id="grupo" value="<?echo $_REQUEST['grupo']?>" size="5" align="center" onchange="xajax_validar(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,<?echo $proyecto;?>)">
						</td>
						<td  class="centrar" onmouseover="Tip('<center>Capacidad del curso</center>', SHADOW, true, TITLE, 'Capacidad', PADDING, 9)">
							<b>CAPACIDAD</b><br>
							<input type="text" <?echo $habilitado?> name='capacidad' id="capacidad" value="<?echo $valor?>" size="5" align="center" >
						</td>
					</tr>
					<?
					$qryfecha="SELECT TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FROM dual";
					$rsFecha=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $qryfecha, "busqueda");
					//echo "mmm".$rsFecha[0][0]."<br>";
					$variable=array($proyecto, $año, $periodo, $rsFecha[0][0]);
					$cadena_sql=$this->sql->cadena_sql($configuracion, "valida_fecha", $variable);// echo $cadena_sql;
					$rsValida=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
					//echo "nnnn".$rsValida[0][0]."<br>";
					//echo "xxx".$rsValida[0][1]."<br>";
					if (is_array($rsValida))
					{
						$visible="";//echo "if";exit;
					}
					else
					{
						$visible="disabled='disabled'";//echo "else".$visible;exit;
					}

					if (!is_array($rsValida))
					{
						?>
						<tr>
							<td colspan="3" class="centrar"><font size="2" color="red">En este momento no podr&aacute;: inserta, Actualizar, ni borrar registros;
								<br>La fecha para estos permisos ha caducado
								<br> <b>"CALENDARIO ACAD&Eacute;MICO"</b>
								</font>
							</td>
						</tr>
						<?
					}
					?>
					<tr>
					    <td align="center" colspan="3">
						<input type="hidden" name="action" value="<? echo $this->formulario ?>">
						<input type="hidden" name="opcion" value="guardado">
						<input type='hidden' name='verHorario' id='hidHorario' value='2'>
						<input type='hidden' name='funcion' id='funcion' value='consulta'>
						<input type="hidden" name="proyecto" value="<? echo $proyecto ?>">
						<input type="hidden" name="plan" value="<? echo $plan ?>" >
						<input type="hidden" name="espacio" value="<? echo $espacio ?>" >
						<input type="hidden" name="periodo" value="<? echo $año."-".$periodo?>" >
						<input type="hidden" name="grupo" value="<? echo $grupo?>" >
						<input value="Actualizar Curso" <?echo $visible?> id="btnGrabar" name="aceptar" type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit();}else{false}" >
						<!--input value="Ver Horario" id="btnHorario" name="horario" type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"-->
						<input value="Regresar" id="btnBusqueda" name="busqueda" type="submit">
						<input value="Eliminar Curso" id="btnBusqueda" name="elimina" type="submit" <?echo $visible?>>
					    </td>
					</tr>
				</table>
				<?
				if(isset($espacio) && isset($grupo))
				{
					?>
					<div id="div_mostrarHorario" style="display:block">
					<table border="0" style="width: 100%; text-align: left;" class="cuadro_plano cuadro_color " >
					<thead>
						<tr class="centrar">
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/dia_hora.jpg" width="100" height="40"> </th>
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/lunes.jpg" width="100" height="40"> </th>
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/martes.jpg" width="100" height="40"> </th>
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/miercoles.jpg" width="100" height="40"> </th>
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/jueves.jpg" width="100" height="40"> </th>
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/viernes.jpg" width="100" height="40"> </th>
							<th> <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/sabado.jpg" width="100" height="40"> </th>
						</tr>
					</thead>
					<tbody>
					<?
    
					$this->cadena_sql = $this->sql->cadena_sql($configuracion, "hora", "");
					$resultado = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");

					$i = 0;
					$habilitadoHor = 0;
					while (isset($resultado[$i][0]))
					{
						if($i%2==0)
						{
							$color = "#BAD6FD";
						}
						else
						{
							$color = "#F4F5EB";
						}
						$cursoJornada[0][0]=isset($cursoJornada[0][0])?$cursoJornada[0][0]:'';
						switch($cursoJornada[0][0])
						{
							default :
							$habilitadoHor = 1;
							break;
						}
						$qryfecha="SELECT TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FROM dual";
						$rsFecha=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $qryfecha, "busqueda");
						
						$variable=array($proyecto, $año, $periodo, $rsFecha[0][0]);
						$cadena_sql=$this->sql->cadena_sql($configuracion, "valida_fecha", $variable);
						$rsValida=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");
						//echo "mmm".$cadena_sql."<br>";
						?>
						<tr style="<?echo "background:".$color?>">
							<td align="center" valign="middle">
							    <? echo "<br>".$resultado[$i][1] ?>
							</td>
							<?
							if($habilitadoHor==1)
							{
								if(is_array($rsValida))
								{
								    $evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",1);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",1)";
								    $str="<center>Click para registrar salon el dia Lunes de ".$resultado[$i][1]."</center>";
								}
								else
								{
								    $evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
								    $str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
								}
								?>
							<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Lunes  <?echo $resultado[$i][1];?>', PADDING, 9)">
							    <?
							    $arregloHorario = array($espacio,$grupo,1,$resultado[$i][0],$año,$periodo);
							    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);//echo $this->cadena_sql;
							    $resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
							    ?>
							    <div id="1_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
							</td>
							<?
							}
							else
							{
							?>
							<td align="center" valign="middle">

							</td>
							<?
							}

							if($habilitadoHor==1)
							{
								if(is_array($rsValida))	
								{
									$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",2);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",2)";
									$str="<center>Click para registrar salon el dia Martes de ".$resultado[$i][1]."</center>";
								}
								else
								{
									$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
									$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
								}
								?>
							<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Martes  <?echo $resultado[$i][1];?>', PADDING, 9)">
								<?
								$arregloHorario = array($espacio,$grupo,2,$resultado[$i][0],$año,$periodo);
								$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
								$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
								?>
								<div id="2_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
							</td>
							<?
							}
							else
							{
								?>
								<td align="center" valign="middle">

								</td>
								<?
							}

							if($habilitadoHor==1)
							{
								if(is_array($rsValida))
								{
									$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",3);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",3)";
									$str="<center>Click para registrar salon el dia Miercoles de ".$resultado[$i][1]."</center>";
								}
								else
								{
									$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
									$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
								}
								?>
								<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Miercoles  <?echo $resultado[$i][1];?>', PADDING, 9)">
									<?
									$arregloHorario = array($espacio,$grupo,3,$resultado[$i][0],$año,$periodo);
									$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
									$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
									?>
									<div id="3_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
								</td>
								<?
							}
							else
							{
								?>
								<td align="center" valign="middle">

								</td>
								<?
							}

							if($habilitadoHor==1)
							{
								if(is_array($rsValida))
								{
									$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",4);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",4)";
									$str="<center>Click para registrar salon el dia Jueves de ".$resultado[$i][1]."</center>";
								}
								else
								{
									$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
									$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
								}
								?>
								<td align="center" valign="middle" onclick="<?echo $evt;?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Jueves  <?echo $resultado[$i][1];?>', PADDING, 9)">
									<?
									$arregloHorario = array($espacio,$grupo,4,$resultado[$i][0],$año,$periodo);
									$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
									$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
									?>
									<div id="4_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
								</td>
								<?
							}
							else
							{
								?>
								<td align="center" valign="middle">

								</td>
								<?
							}

							if($habilitadoHor==1)
							{
								if(is_array($rsValida))
								{
									$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",5);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",5)";
									$str="<center>Click para registrar salon el dia Viernes de ".$resultado[$i][1]."</center>";
								}
								else
								{
									$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
									$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
								}
								?>
								<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Viernes  <?echo $resultado[$i][1];?>', PADDING, 9)">
									<?
									$arregloHorario = array($espacio,$grupo,5,$resultado[$i][0],$año,$periodo);
									$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);//echo $this->cadena_sql;
									$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
									?>
									<div id="5_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
								</td>
								<?
							}
							else
							{
								?>
								<td align="center" valign="middle">

								</td>
								<?
							}

							if($i<12)
							{
								if(is_array($rsValida))
								{
									$evt="xajax_verhorario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",6);xajax_horario(document.getElementById('espacio').value,document.getElementById('periodo').value,document.getElementById('grupo').value,document.getElementById('capacidad').value,".$resultado[$i][0].",6)";
									$str="<center>Click para registrar salon el dia Sabado de ".$resultado[$i][1]."</center>";
								}
								else
								{
									$evt="javasript:(alert('Las fechas de Calendario Acad&eacute;mico se encuentran cerradas'))";
									$str="<center>Las fechas de Calendario Acad&eacute;mico se encuentran cerradas</center>";
								}
									?>
									<td align="center" valign="middle" onclick="<?echo $evt?>" onmouseover="Tip('<?echo $str?>', SHADOW, true, TITLE, 'Sabado  <?echo $resultado[$i][1];?>', PADDING, 9)">
										<?
										$arregloHorario = array($espacio,$grupo,6,$resultado[$i][0],$año,$periodo);
										$this->cadena_sql = $this->sql->cadena_sql($configuracion, "verHorarioTemp", $arregloHorario);
										$resultadoHor = $this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql, "busqueda");
										?>
										<div id="6_<? echo $resultado[$i][0] ?>"> <?if(is_array($resultadoHor)){echo "Sede: ".$resultadoHor[0][2]."<br>Salon: ".$resultadoHor[0][1];}else{echo "-";}?> </div>
									</td>
									<?
							}
							else
							{
								?>
								<td align="center" valign="middle">

								</td>
								<?
							}
						?>
						</tr>
						<?
					$i++;
					}
					?>
					</tbody>
					</table>
					</div>
				<?
				}
				?>
			</td>
		</tr>
	</table>
	</form>
	<?    
}//fin function

        function eliminaCurso($configuracion){
             $proyecto=$_REQUEST['proyecto'];
             $plan=$_REQUEST['plan'];
             $espacio=$_REQUEST['espacio'];
             $grupo=$_REQUEST['grupo'];
             $año=substr($_REQUEST['periodo'],-6,4);
             $periodo=substr($_REQUEST['periodo'],-1);

             $variable=array($espacio, $grupo, $año, $periodo);
             $cadena_sql=$this->sql->cadena_sql($configuracion, "infoHorario", $variable);//echo $cadena_sql; exit;
             $rsHorario=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

             $variable=array($espacio, $grupo, $año, $periodo);
             $cadena_sql=$this->sql->cadena_sql($configuracion, "infoCarga", $variable);//echo $cadena_sql; exit;
             $rsCarga=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

             $variable=array($espacio, $grupo, $año, $periodo);
             $cadena_sql=$this->sql->cadena_sql($configuracion, "infoInscritos", $variable);//echo $cadena_sql; exit;
             $rsInscritos=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "busqueda");

		if(is_array($rsHorario))
		{
			echo "<script>alert('No se puede borrar el curso, por que tiene un horario asociado ')</script>";
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=adminHorarios";
			$ruta.="&opcion=verHorarioGrupo";
			$ruta.="&proyecto=".$proyecto;
			$ruta.="&plan=".$plan;
			$ruta.="&grupo=".$grupo;
			$ruta.="&periodo=".$_REQUEST['periodo'];
			$ruta.="&espacio=".$espacio;
		      
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$ruta=$this->cripto->codificar_url($ruta,$configuracion);
			echo "<script>location.replace('".$indice.$ruta."')</script>";  
		}
		else if(is_array($rsCarga))
		{
			echo "<script>alert('No se puede borrar el curso, por que tiene un docente asociado ')</script>";
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=adminHorarios";
			$ruta.="&opcion=verHorarioGrupo";
			$ruta.="&proyecto=".$proyecto;
			$ruta.="&plan=".$plan;
			$ruta.="&grupo=".$grupo;
			$ruta.="&periodo=".$_REQUEST['periodo'];
			$ruta.="&espacio=".$espacio;
		      
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$ruta=$this->cripto->codificar_url($ruta,$configuracion);
			echo "<script>location.replace('".$indice.$ruta."')</script>";
		}  
		else if($rsInscritos[0][0]>0)
		{
			echo "<script>alert('No se puede borrar el curso, por que tiene estudiantes inscritos ')</script>";
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=adminHorarios";
			$ruta.="&opcion=verHorarioGrupo";
			$ruta.="&proyecto=".$proyecto;
			$ruta.="&plan=".$plan;
			$ruta.="&grupo=".$grupo;
			$ruta.="&periodo=".$_REQUEST['periodo'];
			$ruta.="&espacio=".$espacio;
		      
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$ruta=$this->cripto->codificar_url($ruta,$configuracion);
			echo "<script>location.replace('".$indice.$ruta."')</script>";
		}
		else
		{
			$arrayParametros=array($proyecto, $plan, $espacio, $grupo, $año, $periodo);
			$cadena_sql=$this->sql->cadena_sql($configuracion, "eliminaCurso", $arrayParametros);//echo $cadena_sql;exit;
			$rsElimina=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql, "");

			echo "<script>alert('Registro exitoso')</script>";
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=adminHorarios";
			$ruta.="&opcion=consultagrupos";
			$ruta.="&proyecto=".$proyecto;
			$ruta.="&plan=".$plan;
			$ruta.="&grupo=".$grupo;
			$ruta.="&periodo=".$_REQUEST['periodo'];
			$ruta.="&espacio=".$espacio;

			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$ruta=$this->cripto->codificar_url($ruta,$configuracion);
		        echo "<script>location.replace('".$indice.$ruta."')</script>";
             }
        }//fin function
}
