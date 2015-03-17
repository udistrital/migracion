<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

//El tiempo que se utiliza para agregar al nombre del campo se declara en ready.php


/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 *
 * La ruta absoluta del bloque está definida en $this->ruta
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$nombreFormulario = sha1($esteBloque ['nombre'].$_REQUEST['tiempo']);

$tab = 1;

$valorCodificado = "action=" . $esteBloque ["nombre"];
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$valorCodificado .= "&tiempo=" . $_REQUEST ['tiempo'];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" );
$directorioImagenes = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" )."/imagenes";

?>

                <header>
			<div id="fondo_base"> </div>
			<div id="fondo_menu"> </div>
                        <!--
			<ul id="menu">
                        <li>
                                <a href="#">Administrativa</a>
                        <ul>
                        <li><a href="#">Docencia</a></li>
                        <li><a href="#">Recursos Humanos</a></li>
                            <li><a href="#">Inventarios y Almacen</a></li>
                            <li><a href="#">Parqueaderos</a></li>
                            <li><a href="#">Solicitudes</a></li>
                        </ul>
                        </li>
                        <li>
                                <a href="#">Académica</a>
                        <ul>
                                <li><a href="#">Condor</a></li>
                        </ul>    	
                        </li>
                        <li>
                                <a href="#">Financiera</a>
                        <ul>
                                <li><a href="#">Cuotas Partes</a></li>
                                <li><a href="#">Item 1</a></li>
                                <li><a href="#">Item 2</a></li>
                        </ul>	
                        </li>
                        <li>
                                <a href="#">Inteligencia de negocios</a>
                        <ul>
                                <li><a href="#">Indicadores</a></li>
                                <li><a href="#">Item 1</a></li>
                        </ul>	
                        </li>
                        </ul>
                        -->
                        <div id="logo_osiris">
                                <div id="logo_o">
                                        <img src="<?php echo $directorioImagenes?>/osiris.png" />
                                </div>
                        </div>
                </header>
		<section>
			<article id="fondo_login"> 
				<form id="form_login">
					<?php
                            
                                        // ---------------Inicio Formulario (<form>)--------------------------------
                                        $atributos ["id"] = "formLogin";
                                        $atributos ["tipoFormulario"] = "multipart/form-data";
                                        $atributos ["metodo"] = "POST";
                                        $atributos ["nombreFormulario"] = $nombreFormulario;
                                        $verificarFormulario = "1";
                                        echo $this->miFormulario->formulario ( "inicio", $atributos );

                                        ?>

                                        <input class="login validate[required]" type="text" size="30" name="<?php echo sha1('usuario'.$_REQUEST['tiempo'])?>" id="<?php echo sha1('usuario'.$_REQUEST['tiempo'])?>" placeholder="Usuario" value=""/><br />

                                        <input class="login validate[required]" type="password" size="30" name="<?php echo sha1('clave'.$_REQUEST['tiempo'])?>" id="<?php echo sha1('clave'.$_REQUEST['tiempo'])?>" placeholder="Contraseña" value=""/><br />
                                        <?php


                                        // ------------------Division para los botones-------------------------
                                        $atributos ["id"] = "botones";
                                        $atributos ["estilo"] = "marcoBotones";
                                        echo $this->miFormulario->division ( "inicio", $atributos );

                                        // -------------Control Boton-----------------------
                                        $esteCampo = "enviar";
                                        $atributos ["id"] = $esteCampo;
                                        $atributos ["tabIndex"] = $tab ++;
                                        $atributos ["tipo"] = "submit";
                                        $atributos ["estilo"] = "";
                                        $atributos ["verificar"] = ""; // Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
                                        $atributos ["tipoSubmit"] = ""; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
                                        $atributos ["valor"] = "Ingresar";
                                        $atributos ["nombreFormulario"] = $nombreFormulario;
                                        echo $this->miFormulario->campoBoton ( $atributos );
                                        unset ( $atributos );
                                        // -------------Fin Control Boton----------------------

                                        // ------------------Fin Division para los botones-------------------------
                                        echo $this->miFormulario->division ( "fin" );

                                        // -------------Control cuadroTexto con campos ocultos-----------------------
                                        // Para pasar variables entre formularios o enviar datos para validar sesiones
                                        $atributos ["id"] = "formSaraData"; // No cambiar este nombre
                                        $atributos ["tipo"] = "hidden";
                                        $atributos ["obligatorio"] = false;
                                        $atributos ["etiqueta"] = "";
                                        $atributos ["valor"] = $valorCodificado;
                                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                        unset ( $atributos );


                                        echo $this->miFormulario->formulario ( "fin" );
                                        ?>
				</form>	
			</article>
		<section id="logo_universidad">
			<article id="contenedor_logo_u">
				<div id="logo_u">
					<img src="<?php echo $directorioImagenes?>/UD_logo.png" />
				</div>
			</article>
			<article id="datos">
				<p>Universidad Distrital Francisco José de Caldas</p>
			</article>
		</section>	
		</section>
		<section>
			<div id="fondo_texto">
				<div id="texto">
					<h1>SISTEMA DE INFORMACIÓN</h1>
					<h3>RECURSOS HUMANOS</h3>
				</div>
			</div>
		</section>
		<footer>
			<div id="datos_contacto_pie">
				<p>Todos los derechos reservados.</p>
				<p>Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300</p>
				<a href="">computo@udistrital.edu.co</a>
			</div>
		</footer>