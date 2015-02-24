<?php
/**
* Funcion nombreFuncion
*
* Esta clase se encarga de crear la logica
*
* @package nombrePaquete
* @subpackage nombreSubpaquete
* @author Karen Palacios
* @version 0.0.0.1
* Fecha: 26/02/2013
*/

if(!isset($GLOBALS["autorizado"]))
{
include("../index.php");
exit; 
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

/**
* Descripcion de la clase
*
* @package paquete de la Clase
* @subpackage Subpaquete de la Clase
*/
class funcion_reading extends funcionGeneral
{
    
    private $configuracion;

    /**
*
* @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
*/
    function __construct($configuracion) {

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->sql=new sql_reading($configuracion);
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->accesoEncuesta=$this->conectarDB($configuracion,"mysqlEncuestaDocente");
        
        $this->proceso=1;
        $this->prueba=1;

    }
                       
    /**
*
*/
    function htmlFormulario($seccion) {
    
	$this->seccion=$seccion;
	$infoSeccion=$this->consultarSeccion($this->seccion);
	$this->preguntas=$this->armarPreguntas();
	$this->respuestasUnicaRespuesta=$this->armarRespuestasUnicaRespuesta();
	$this->respuestasAbiertas=$this->armarRespuestasAbiertaRespuesta();
	
	$preguntasSeccion=$this->armarPreguntasSeccion($this->seccion,0);
	
	echo "<div class='wrap'>";
	echo "<form method='POST' action='index.php' >";
	echo "
	      <div class='main_content'>
		  
		    <h1>".$infoSeccion[0]['seccion_nombre']."</h1>
			<br/>
			<div class='about'>
			    <p>".$infoSeccion[0]['seccion_presentacion']."</p>

			    <br/>
			</div> 
			<br/>
	      </div>
	  ";
	    
	echo "<div class='main_question'>";
	   $this->imprimirPreguntas($preguntasSeccion);
	echo "</div>";
	
	echo "<input type='hidden' name='proceso' value='{$this->proceso}'>"; //los dejo quemados para la primera implementacion por favor cambiar
	echo "<input type='hidden' name='prueba' value='{$this->prueba}'>"; //los dejo quemados para la primera implementacion por favor cambiar
	echo "<input type='hidden' name='seccion' value='{$this->seccion}'>";
	echo "<input type='hidden' name='opcion' value='guardar'>";
	echo "<input type='hidden' name='action' value='encuestasDocente/registro_encuestaDocente'>";
	echo "<center>";
	echo "<input style='padding:15px' type='submit' value='GUARDAR'>";
	echo "</center>";
	echo "<br/>";
	echo "<br/>";
	echo "</form>";
	echo "</div>";

    }

    /**
*
*/
/////

    function consultarSeccion($valor) {

 	$cadena_sql=$this->sql->cadena_sql("rescatar_secciones",$valor);
	$resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
               
        return $resultado;
    }
    
    
    function imprimirPreguntas($matriz){
 
	foreach($matriz as $clave=>$valor){
	    
		if(is_array($valor)){  
		    //si es un array sigo recorriendo
		    if($clave<>'dependencias'){
			
			echo $this->procesarPreguntaAbierta($valor['id'],$this->preguntas[$clave]['tippregunta_nombre'],"<b>".$valor['literal']."  </b>"." ".$this->preguntas[$clave]['pregunta_encabezado']);
			
			if(isset($this->preguntas[$clave]['opciones'])){
			    echo "<blockquote>";
			    echo $this->imprimirOpciones($valor['id'],$this->preguntas[$clave]['opciones'],$this->preguntas[$clave]['tippregunta_nombre']);
			    echo "</blockquote>";
			    
			}
			
			//echo $pregunta;
		    }
		    echo "<blockquote>";
		    $this->imprimirPreguntas($valor);
		    echo "</blockquote>";
		} 
	}
 
    } 

     function procesarPreguntaAbierta($idPRegunta,$tipoPregunta,$encabezadoPregunta){
    
	$html="";
	
	switch($tipoPregunta){
	
	  case "completar_respuesta":
	    
	    if(isset($this->respuestasAbiertas[$idPRegunta])){
	      $respuesta=$this->respuestasAbiertas[$idPRegunta]['respuesta_respuesta_usuario'];
	    }else{
	      $respuesta="";
	    }
            
	    $html.=str_replace("***", "<input type='text' value='{$respuesta}' name='OQ_{$idPRegunta}' >",$encabezadoPregunta);
            
	  break;
           case "respuesta_parrafo":
	    
	    if(isset($this->respuestasAbiertas[$idPRegunta])){
	      $respuesta=$this->respuestasAbiertas[$idPRegunta]['respuesta_respuesta_usuario'];
	    }else{
	      $respuesta="";
	    }
            
	    
            $html.=str_replace("/*/", "<textarea name='OQ_{$idPRegunta}' rows='5' cols='100'>{$respuesta}</textarea>",$encabezadoPregunta);
	  break;
	  default:
	  
	    $html=$encabezadoPregunta;
	  
	  break;
          
          
	
	}
	
	return $html;
    }
    
    
    
    function imprimirOpciones($idPRegunta,$opciones,$tipoPregunta){
    
	$html="";
	
	switch($tipoPregunta){
	  case "unica_respuesta_check":
	    
	    if(isset($this->respuestasUnicaRespuesta[$idPRegunta])){
	      $respuestaSeleccionada=$this->respuestasUnicaRespuesta[$idPRegunta]['respuesta_respuesta_usuario'];
	    }else{
	      $respuestaSeleccionada="";
	    }

	    foreach($opciones as $clave=>$valor){
	    
	      if($valor['opcion_id']==$respuestaSeleccionada){
		
		$checked="checked";
	     
	      }else{
		
		$checked="";
		
	      }
	
	      $html.="<input type='radio' name='UQ_".$valor['opcion_id_pregunta']."' value='".$valor['opcion_id']."' $checked >".$valor['opcion_literal']." ".$valor['opcion_etiqueta']."<br/>";
	      
	    }
	    
	  break;
	  case "unica_respuesta_list":
	  
	    if(isset($this->respuestasUnicaRespuesta[$idPRegunta])){
	      $respuestaSeleccionada=$this->respuestasUnicaRespuesta[$idPRegunta]['respuesta_respuesta_usuario'];
	    }else{
	      $respuestaSeleccionada="";
	    }

	    $html.="<select name='UQ_".$idPRegunta."'>";
	      
	    $html.="<option value=''> [ seleccione una opcion ] </option>";
	      
	    foreach($opciones as $clave=>$valor){
	    
		if($valor['opcion_id']==$respuestaSeleccionada){
		
		    $selected="selected";
	     
		}else{
		    
		    $selected="";
		    
		}
	    
		$html.="<option value='".$valor['opcion_id']."' $selected >".$valor['opcion_literal']." ".$valor['opcion_etiqueta']."</option>";
		
	    }
	    $html.="</select>";
	  break;	  
	}
	
	return $html;
    }
    
    
    function armarPreguntas() {

 	$pregunta=$this->consultarPreguntas();
 	$opcion=$this->armarOpcionesPreguntas();
        $salida=array();
        $i=0;
	while(isset($pregunta[$i][0])){
	  foreach($pregunta[$i] as $clave=>$valor){
	      $salida[$pregunta[$i]['pregunta_id']][$clave]=$valor;
	      if(isset($opcion[$pregunta[$i]['pregunta_id']])){
		  $salida[$pregunta[$i]['pregunta_id']]['opciones']=$opcion[$pregunta[$i]['pregunta_id']];
	      }
	  }
	  $i++;
        }
        return $salida;
    }
    
    
    
    function consultarOpcionesPreguntas() {

 	$cadena_sql=$this->sql->cadena_sql("rescatar_opciones_preguntas");
	$resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
               
        return $resultado;
    }
    
 
     function armarOpcionesPreguntas() {

 	$opcion=$this->consultarOpcionesPreguntas();
        
        $salida=array();
        $i=0;
	while(isset($opcion[$i][0])){
	  foreach($opcion[$i] as $clave=>$valor){
	      $salida[$opcion[$i]['opcion_id_pregunta']][$opcion[$i]['opcion_id']][$clave]=$valor;
	  }
	  $i++;
        }
        return $salida;
    }
    
    
    
    function consultarPreguntas() {

 	$cadena_sql=$this->sql->cadena_sql("rescatar_preguntas");
	$resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
               
        return $resultado;
    }
    

    function armarPreguntasSeccion($seccion,$padre) {

        $resultado=$this->consultarPreguntasSeccion($seccion,$padre);
	    
	    $salida = array();

	    $i=0;
	    while(isset($resultado[$i][0])){
		if(array_key_exists($resultado[$i]['secpreg_id_pregunta_padre'], $salida)){
		    $salida[$resultado[$i]['secpreg_id_pregunta_padre']][$resultado[$i]['secpreg_id_pregunta']]['literal'] = $resultado[$i]['secpreg_literal'];
		    $salida[$resultado[$i]['secpreg_id_pregunta_padre']][$resultado[$i]['secpreg_id_pregunta']]['id'] = $resultado[$i]['secpreg_id_pregunta'];
		    $salida[$resultado[$i]['secpreg_id_pregunta_padre']][$resultado[$i]['secpreg_id_pregunta']]['dependencias'] = (count($this->armarPreguntasSeccion($seccion,$resultado[$i]['secpreg_id_pregunta']))?$this->armarPreguntasSeccion($seccion,$resultado[$i]['secpreg_id_pregunta']):"Ninguna");
		}
		else{
		    $salida[$resultado[$i]['secpreg_id_pregunta']]['literal'] = $resultado[$i]['secpreg_literal'];
		    $salida[$resultado[$i]['secpreg_id_pregunta']]['id'] = $resultado[$i]['secpreg_id_pregunta'];
		    $salida[$resultado[$i]['secpreg_id_pregunta']]['dependencias'] = (count($this->armarPreguntasSeccion($seccion,$resultado[$i]['secpreg_id_pregunta']))?$this->armarPreguntasSeccion($seccion,$resultado[$i]['secpreg_id_pregunta']):"Ninguna");
		}
	    $i++;
	    }

	    return $salida;
    }
    
    
    
    function consultarPreguntasSeccion($seccion,$padre) {

	  $cadena_sql=$this->sql->cadena_sql("rescatar_preguntas_seccion",array('padre'=>$padre,'seccion'=>$seccion));
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
     
	  return $resultado;
    }    
    
    
    function guardarFormulario($formulario,$respuestas){
//	echo "<pre>";
// 	var_dump($respuestas);
//        echo "</pre>";
        $parametro['usuario']=$this->usuario;
        $parametro['proceso']=$formulario['proceso'];
        $parametro['prueba']=$formulario['prueba'];
        $parametro['seccion']=$formulario['seccion'];
        
        if(isset($respuestas['UQ'])){
        
	    foreach($respuestas['UQ'] as $clave=>$valor){
	    
	    
		  $parametro['pregunta']=$clave;
		  $parametro['respuesta']=$valor;
		  $parametro['tiempo']=time();
		  
		  $respuesta=$this->consultarRespuestaUnica($parametro);
		  
		  if(!is_array($respuesta)){
		      $resultado=$this->insertarRespuestaUnica($parametro);
		      if(!$resultado){
			return false;
		      }
		  }else{
		      $parametro['identificador']=$respuesta[0]['respuesta_id'];
		      $resultado=$this->actualizarRespuestaUnica($parametro);
		      if(!$resultado){
			return false;
		      }
		  }
	    }
	} 
	
	if(isset($respuestas['OQ'])){
	    foreach($respuestas['OQ'] as $clave=>$valor){
	    
	    
		  $parametro['pregunta']=$clave;
		  $parametro['respuesta']=$valor;
		  $parametro['tiempo']=time();
		  
		  $respuesta=$this->consultarRespuestaAbierta($parametro);
		  
		  if(!is_array($respuesta)){
		      $resultado=$this->insertarRespuestaAbierta($parametro);
		      if(!$resultado){
			return false;
		      }
		  }else{
		      $parametro['identificador']=$respuesta[0]['respuesta_id'];
		      $resultado=$this->actualizarRespuestaAbierta($parametro);
		      if(!$resultado){
			return false;
		      }
		  }
	    }
	} 
    
	return true;
    }
    
    function armarRespuestasUnicaRespuesta(){
	  
        $parametro['usuario']=$this->usuario;
        $parametro['proceso']=$this->proceso;
        $parametro['prueba']=$this->prueba;
        $parametro['seccion']=$this->seccion;
        
	$respuesta=$this->consultarRespuestasUnicas($parametro);
 	
        $salida=array();
        
        $i=0;
	while(isset($respuesta[$i][0])){
	  foreach($respuesta[$i] as $clave=>$valor){
	      $salida[$respuesta[$i]['respuesta_id_pregunta']][$clave]=$valor;
	  }
	  $i++;
        }
        
        return $salida;
    }

    
    function armarRespuestasAbiertaRespuesta(){
	  
        $parametro['usuario']=$this->usuario;
        $parametro['proceso']=$this->proceso;
        $parametro['prueba']=$this->prueba;
        $parametro['seccion']=$this->seccion;
        
	$respuesta=$this->consultarRespuestasAbiertas($parametro);
 	
        $salida=array();
        
        $i=0;
	while(isset($respuesta[$i][0])){
	  foreach($respuesta[$i] as $clave=>$valor){
	      $salida[$respuesta[$i]['respuesta_id_pregunta']][$clave]=$valor;
	  }
	  $i++;
        }
        
        return $salida;
    }    
    
    
    function consultarRespuestasUnicas($parametro){
	  
	  $cadena_sql=$this->sql->cadena_sql("rescatar_respuestas_unicas",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
     
	  return $resultado;
    }
    
    function consultarRespuestaUnica($parametro){
	  
	  $cadena_sql=$this->sql->cadena_sql("rescatar_respuesta_unica",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
     
	  return $resultado;
    }
    
    function insertarRespuestaUnica($parametro){

	  $cadena_sql=$this->sql->cadena_sql("insertar_respuesta_unica",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql,"");
     
	  return $resultado;
    }
    
    function actualizarRespuestaUnica($parametro){

	  $cadena_sql=$this->sql->cadena_sql("actualizar_respuesta_unica",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql,"");
     
	  return $resultado;
    }
    
    function consultarRespuestasAbiertas($parametro){
	  
	  $cadena_sql=$this->sql->cadena_sql("rescatar_respuestas_abiertas",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
     
	  return $resultado;
    }
    
    function consultarRespuestaAbierta($parametro){
	  
	  $cadena_sql=$this->sql->cadena_sql("rescatar_respuesta_abierta",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql, "busqueda");
     
	  return $resultado;
    }
    
    function insertarRespuestaAbierta($parametro){

	  $cadena_sql=$this->sql->cadena_sql("insertar_respuesta_abierta",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql,"");
     
	  return $resultado;
    }
    
    function actualizarRespuestaAbierta($parametro){

	  $cadena_sql=$this->sql->cadena_sql("actualizar_respuesta_abierta",$parametro);
	  $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoEncuesta,$cadena_sql,"");
     
	  return $resultado;
    }
}
?>
