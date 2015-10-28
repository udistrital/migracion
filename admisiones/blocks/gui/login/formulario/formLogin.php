<?php 

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 * 
 *  La ruta absoluta del bloque está definida en $this->ruta
 */
$ruta = $this->miConfigurador->getVariableConfiguracion("host");
$ruta.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$ruta.=$this->miConfigurador->getVariableConfiguracion("enlace");

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$nombreFormulario = $esteBloque["nombre"];

$valorCodificado = "action=" . $esteBloque["nombre"];
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
$directorio = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque");



$tab=1;
?>
<div id="encabezado_pagina">

</div>  
<?php

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);

//------------------Division para los botones-------------------------
	$atributos["id"]="datos";
	$atributos["estilo"]="jquery";
	echo $this->miFormulario->division("inicio",$atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="usuario";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="30";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";
//$atributos["valor"]="1022350133";

echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="clave";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="10";
$atributos["tipo"]="password";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";

//$atributos["valor"]="sistemasoas";

echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);
echo $this->miFormulario->division("fin");
// Si existe algun tipo de error en el login aparece el siguiente mensaje

if(isset($_REQUEST['error']) && $_REQUEST['error'] == 'usuario')
{
	//------------------Division para los botones-------------------------
	$atributos["id"]="error";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control texto-----------------------
	$esteCampo="mensajeUsuarioError";
	$atributos["tamanno"]="";
	$atributos["estilo"]="errorLogin";
	$atributos["etiqueta"]="";
	$atributos["mensaje"]="El usuario digitado no existe, por favor vuelva a intentar";
	$atributos["columnas"]=""; //El control ocupa 47% del tamaño del formulario
	echo $this->miFormulario->campoMensaje($atributos);
	unset($atributos);
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
}else if(isset($_REQUEST['error']) && $_REQUEST['error'] == 'clave')
{
	//------------------Division para los botones-------------------------
	$atributos["id"]="error";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control texto-----------------------
	$esteCampo="mensajeClaveError";
	$atributos["tamanno"]="";
	$atributos["estilo"]="errorLogin";
	$atributos["etiqueta"]="";
	$atributos["mensaje"]="Nombre de usuario o contraseña incorrectos";
	$atributos["columnas"]=""; //El control ocupa 47% del tamaño del formulario
	echo $this->miFormulario->campoMensaje($atributos);
	unset($atributos);
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
}else if(isset($_REQUEST['error']) && $_REQUEST['error'] == 'valorInscripcion')
{
	//------------------Division para los botones-------------------------
	$atributos["id"]="error";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control texto-----------------------
	$esteCampo="mensajeClaveError";
	$atributos["tamanno"]="";
	$atributos["estilo"]="errorLogin";
	$atributos["etiqueta"]="";
	$atributos["mensaje"]="El valor consignado es menor al costo del formulario. <br><br>El costo del formulario de inscripci&oacute;n, corresponde al 10% de un salario m&iacute;mino  mensual legal vigente.<br>
		   Acuerdo 004 de Enero 25 de 2006, del Consejo Superior. ARTICULO 13.<br><br>Para poder inscribirse a la Universidad Distrital, debe hacer una &uacute;nica consignaci&oacute;n que corresponda al costo total del formulario de inscripci&oacute;n.";
	$atributos["columnas"]=""; //El control ocupa 47% del tamaño del formulario
	echo $this->miFormulario->campoMensaje($atributos);
	unset($atributos);
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
}


//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);

//-------------Control Boton-----------------------
$esteCampo="botonAceptar";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["tipo"]="boton";
$atributos["estilo"]="";
$atributos["verificar"]=""; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
$atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");



//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"]="formSaraData"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);



//Fin del Formulario
echo $this->miFormulario->formulario("fin");


?>

<div id="sabio"> 
</div>

<div id="menu">
    <ul>
        <!--li><a href="http://www.udistrital.edu.co/"target="_blank">Universidad Distrital FJC</a></li-->
        <li><a href="<?
            $variable = "pagina=instructivo"; //pendiente la pagina para modificar parametro
            $variable.= "&tipo=1";
            $variable.= "&opcion=instructivo";
            $variable.= "&seccion=Principal";
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$ruta);
            echo $variable;
        ?>">Instructivo Admisiones </a>
        </li>    
        <li><a href="<?
            $variable = "pagina=instructivo"; //pendiente la pagina para modificar parametro
            $variable.= "&tipo=1";
            $variable.= "&opcion=instructivo";
            $variable.= "&seccion=Reingresos y Transferencias";
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$ruta);
            echo $variable;
        ?>">Instructivo Reingreso </a>
        </li> 
        <li><a href="<?
            $variable = "pagina=instructivo"; //pendiente la pagina para modificar parametro
            $variable.= "&tipo=1";
            $variable.= "&opcion=instructivo";
            $variable.= "&seccion=Reingresos y Transferencias";
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$ruta);
            echo $variable;
        ?>">Instructivo Transferencias </a>
        </li> 
        <li><a href="<?
            $variable = "pagina=resultados"; //pendiente la pagina para modificar parametro
            $variable.= "&tipo=1";
            $variable.= "&opcion=resultados";
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$ruta);
            echo $variable;
        ?>">Consultar Resultados </a>
        </li>
    </ul>
</div>


<div id="escudo"></div>

<div id="pie">
    Universidad Distrital Francisco Jos&eacute; de Caldas <br>
    Oficina Asesora de Sistemas 2013. Todos los derechos reservados.<br>
    Carrera 8 N. 40-78 Piso 1 / Teléfonos 3238400 - 3239300 Ext. 1112 -1113.<br>
    <a href="mailto:computo@udistrital.edu.co" class="enlace">computo@udistrital.edu.co</a>
</div>
