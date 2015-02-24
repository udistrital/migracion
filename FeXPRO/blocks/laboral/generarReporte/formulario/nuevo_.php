<?

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");

$nombreFormulario=$esteBloque["nombre"];

include_once("core/crypto/Encriptador.class.php");
$cripto=Encriptador::singleton();
$valorCodificado="pagina=".$esteBloque["nombre"];
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=generarReporte";
//$valorCodificado.="&valor=".$key;
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$cripto->codificar($valorCodificado);

$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

$tab=1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->formulario("inicio",$atributos);


//-------------------------------Mensaje-------------------------------------
$esteCampo="mensaje1";
$atributos["id"]=$esteCampo;
$atributos["obligatorio"]=false;
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]="";
$atributos["mensaje"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoMensaje($atributos);

//---------------Inicio de Lista de valores de Tipo de Documento----------
$esteCampo="marcoDatosBasicos";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);


//-------------Control cuadroTexto---Tipo de Certificado-----------------------
$esteCampo="idTipoCertificado";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["seleccion"]=0;
$atributos["evento"]="onchange='mostrar();'";
$atributos["limitar"]=false;
$atributos["tamanno"]=1;
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"]=$this->sql->cadena_sql("buscarTipoCertificado");
$atributos["baseDatos"]="laboral";
echo $this->miFormulario->campoCuadroLista($atributos);

echo $this->miFormulario->marcoAGrupacion("fin",$atributos);

// //---------------Inicio de Lista de valores de Tipo de Documento----------
// $esteCampo="marcoDatosBasicos";
// $atributos["estilo"]="jqueryui";
// $atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
// echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

// $atributos['id']="texto";
// $atributos['estilo']="query";
// $atributos['estiloEnLinea']="display:none";
// echo $this->miFormulario->division("inicio",$atributos);


// // //-------------Control cuadroTexto-----------------------
// $esteCampo="avisoIndefinido";
// $atributos["id"]=$esteCampo;
// $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
// $atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
// $atributos["tabIndex"]=$tab++;
// //$atributos["obligatorio"]=true;
// $atributos["tamanno"]="";
// $atributos["tipo"]="";
// $atributos["estilo"]="jqueryui";
// //$atributos["columnas"]="1";
// $atributos["validar"]="required";
// echo $this->miFormulario->campoCuadroTexto($atributos);

// echo $this->miFormulario->division("fin",$atributos);

//echo $this->miFormulario->marcoAGrupacion("fin",$atributos);
//---------------Inicio de Lista de valores de Tipo de Documento----------
//var_dump($esteCampo="idTipoCertificado");exit;

//Linea que me permite conectar a cualquier base de datos
$conexionOracle = "oracle";
$esteRecursoDBOracle = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionOracle);
if (!$esteRecursoDBOracle) {
	//Este se considera un error fatal
	exit;
}


//------------------------------Inicio de Marco --------------------------

$esteCampo="marcoDatosBasicos2";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);


//$cadena_sql = $this->sql->cadena_sql("buscarTablas", "");
$cadena_sql = $this->sql->cadena_sql("buscarInformacionBasica", "");
$resultado = $esteRecursoDBOracle->ejecutarAcceso($cadena_sql, "busqueda");
//var_dump($resultado);
if ($resultado) {
	?>
<br>
<div align="center">
	<h1>Base de Datos Completa</h1>
</div>
<div id='demo'>
	<div id='example_wrapper' class='dataTables_wrapper' role='grid'>
		<table aria-describedby='example_info' class='display dataTable'
			id='example' border='0' cellpadding='0' cellspacing='0'>
			<!--Columnas-->
			<thead>
				<tr role='row'>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>√</th>
					<th aria-label='Documento' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Documento</th>
					<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Apellidos</th>
					<!--<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Nombres</th>!-->
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Fecha de Vinculacion</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Fecha de Terminacion</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Resolucion</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Dependencia</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Cargo</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Salario Mensual</th>
				</tr>
			</thead>

			<!--Pie de Pagina-->
			<tfoot>
				<tr>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>√</th>
					<th aria-label='Documento' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Documento</th>
					<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Apellidos</th>
					<!--<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Nombres</th>!-->
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Fecha de Vinculacion</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Fecha de Terminacion</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Resolucion</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Dependencia</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Cargo</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Salario Basico Mensual</th>
				</tr>
			</tfoot>
			<tbody aria-relevant='all' aria-live='polite' role='alert'>

				<?php				
				foreach ($resultado as $key => $row) {
				?>
				<tr class='gradeA odd'>
					<td align="center">
					<input type="checkbox" id="seleccion<?php echo $key?>" name="id_tipo<?php echo $key?>" value="<?php echo $row[5]; ?>" class="validate[required]">
					</td>
					<td class='  sorting_1'><?php echo $row[5]; ?></td>
					<td><?php echo $row[0]." ".$row[1]." ".$row[2]." ".$row[3]; ?></td>
					<td><?php echo $row[6]; ?></td>
					<td><?php echo $row[7]; ?></td>
					<td><?php echo $row[8]; ?></td>
					<td><?php echo $row[11]; ?></td>
					<td><?php echo $row[10]; ?></td>
					<td><?php echo $row[12]; ?></td>

				</tr>

				<?php
                    }
                    ?>
			</tbody>
		</table>
		<!--div id='example_info' class='dataTables_info'>Showing 1 to 10 of 57 entries</div><div id='example_paginate' class='dataTables_paginate paging_two_button'><a aria-controls='example' id='example_previous' class='paginate_disabled_previous' tabindex='0' role='button'>Previous</a><a aria-controls='example' id='example_next' class='paginate_enabled_next' tabindex='0' role='button'>Next</a></div-->
	</div>
</div>
<?php

//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);
//-------------Control Boton-----------------------
//-------------Control Boton-----------------------
$esteCampo = "botonAceptar";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["tipo"] = "boton";
$atributos["estilo"] = "";
$atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
$atributos["tipoSubmit"] = "jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
$atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"] = $nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
//-------------Fin Control Boton----------------------

//-------------Control Boton-----------------------
// $esteCampo="botonCancelar";
// $atributos["verificar"]="";
// $atributos["tipo"]="boton";
// $atributos["id"]=$esteCampo;
// $atributos["cancelar"]="true";
// $atributos["tabIndex"]=$tab++;
// $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
// echo $this->miFormulario->campoBoton($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin",$atributos);

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"]="formSaraData"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);

//Fin de Conjunto de Controles
echo $this->miFormulario->marcoAGrupacion("fin",$atributos);

//---------------Fin formulario (</form>)--------------------------------
echo $this->miFormulario->formulario("fin",$atributos);
}
?>