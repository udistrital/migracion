<?php

/**
 /* Clase que convierte un XML a GUI usando la clase formularioHtml 
 *  
 *  EL XML de prueba se encuentra en la ruta <sara home>/development/XML2GUI
 *  el archivo se llama test.xml
 *  
 *  En la UrL http://<sara home>/development/XML2GUI se encuentra una interfaz
 *  que convierte el xml dado a GUI usando esta clase.
 *  
 *  la �nica clase p�blica se llama convertir($xml) y recibe como parametro un XML
 *  
 *  Para ver un ejemplos del uso ir a la ruta <sara home>/development/XML2GUI
 *  el archivo XML2GUI.php tiene un ejemplo del uso
 *  
 * @author Carlos A Romero Arvilla
 */

include_once("core/builder/FormularioHtml.class.php");
include_once("core/locale/Lenguaje.class.php");


class XML2GUI{

	var $miFormulario;
	var $lenguaje;

	function __construct(){
		$this->miFormulario=new formularioHtml();
		$this->lenguaje=Lenguaje::singleton();
	}
	
	//Funcion que convierte el XML a GUI
	public function convertir($xml){
		$xmlDoc = simplexml_load_string($xml) or die($this->lenguaje->getCadena('errorXML'));
		//echo $xmlDoc;
		
		if($xmlDoc->getName()!="XML2GUI")
		{
			echo $this->lenguaje->getCadena('errorXML');
		}else{
			$this->procesarNodo($xmlDoc);
		}
	}
	
	//Funcion que prueba si el nodo es contenedor o no y usa las respectivas funciones para crear los elementos
	private function procesarNodo($nodo){
		//$miFormulario=new formularioHtml();
		foreach ($nodo->children() as $child)	{
			if($this->esContenedor($child)==true){
				$this->procesarContenedor($child);
			}elseif($child->getName()=="campoEspacio"){
				echo $this->miFormulario->{$child->getName()}();
			}elseif($child->getName()=="cadenaCodificada") {
				$atributos=$this->getChildValues($child->children());
				echo base64_decode ($atributos['cadena']);
			}else {
				$atributos=$this->getChildValues($child->children());
				echo $this->miFormulario->{$child->getName()}($atributos);
			}
		}
	}
	
	//Prueba si la etiqueta es un contenedor
	private function esContenedor($nodo){
		$attributes=$nodo->attributes();
		foreach($attributes as $key => $value) {
			if($key=="contenedor"&&$value=="si")
				return true;
		}
		return false;
	}
	
	//Crea contenedor y verifica contenido
	private function procesarContenedor($nodo){
		//$miFormulario=new formularioHtml();
		$atributos=$this->getAttributes($nodo->attributes());
	
		//---------------Inicio Contenedor (<form><div><fieldset>...)--------------------------------
		echo $this->miFormulario->{$nodo->getName()}("inicio",$atributos);
		unset($atributos);
	
		//Inputs y Demas
		$this->procesarNodo($nodo);
	
		//Fin del Contenedor
		echo $this->miFormulario->{$nodo->getName()}("fin");
	
	}
	
	//Obtiene los atributos de la etiqueta y retorna un array
	private function getAttributes($attributes){
		foreach($attributes as $key => $value) {
			$atributos[$key]=(string) $value;
		}
		return $atributos;
	}
	
	//Obtiene los valores de nodos hijos y retorna un array
	private function getChildValues($prop){
		foreach ($prop as $child)	{
			$atributos[$child->getName()]=(string) $child;
		}
		return $atributos;
	}
	
	
		
}
?>