<?
/*
/***************************************************************************
******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.1
* @author      	Kelly K. López
* @description  Clase para el manejo de muestras en  arboles de consulta
* @fecha        Febrero 2009
*******************************************************************************
*/
class  arbol
{
  var $conexion_id;
  
  #Constructor de clase 
  function arbol(){

  }#Cierre de constructor
  /**
         * @param array  $items_lista  	 Vector que contiene los items que van a ser cargados
	 * @param int    $nivel          Variable bandera que controla el nivel del arbol que *                               se esta mostrando  
	 * @param int    $link           Variable bandera que controla si el elemento tiene 
         *                               acceso a visualizar elementos asociados     
	 * @param int    $botones        Array  que controla si el elemento 				 	 	 tiene botones adicionales.
                                         El tamaño del vector corresponde al numero de botones que se van a generar, cada posicion del vector contiene otro vector con la informacion de cada boton. (En el caso que la variable vinculoBotones indique si es un parametro que se debe adicionar al vinculo)
                                         Y en las posiciones que siguen un vector:
                                         1. Nombre del boton.
                                         2. Ruta para el vinculo 
                                         3. Ruta de la imagen
         * @param string $vinculoBotones Variable que controla la forma de adicion de 				 un id especifico al elemento html que se 				 quiere generar. Las posibilidades son:
                                         1. Como parametro de adicion a vinculo cuyo     valor de identificacion seria: Parametro
					 2. Como parametro de id de identificacion   
                                            para elemento que se esta generando cuyo  valor seria: id 
      
         
         * @param int    $descripcion    
	 * @param array  $configuracion  Contiene las variables de  configuracion de todo  *				 sitio 
	 */     

  #Descripcion: Funcion que genera la tabla con los items que se estan pasando por parametro 
  function generar_seccion($items_lista,$nivel,$link,$botones,$tipoElemento,$descripcion,$color,$configuracion){
          

         #Construye la tabla donde se va a mostrar el listado
         #Fila cero y filas pares, contienen el nombre y el vinculo para mostrar la div que contiene
         #los objetos del nivel siguiente que estan ligados al nivel actual
         $this->seccionGenerada="";
         
         $this->seccionGenerada="<table border='0' width='100%' cellpadding='3' cellspacing='0'>" ;
         $this->contador=1;
         
       
         while($this->contador<=count($items_lista))
         {
            $id_div="div_".$nivel.$items_lista[$this->contador-1];
            $id_elemento=$items_lista[$this->contador-1];
            $nivel_superior=$nivel+1;
            $id_imagen="img_".$nivel.$items_lista[$this->contador-1];

            #Fila de link de apertura y nombre de item
            $this->seccionGenerada.="<tr class='texto_subtitulo'  bgcolor='".$color."'>"; 

           
            //$this->link_descripcion($descripcion,$link,$id_div,$id_elemento,$nivel_superior,$id_imagen);
            #Si el item va a permitir ver elementos asociados
            if ($link==1){
                $describe="";
                if($descripcion==1){
                   $describe=0;
                }
                
                $this->seccionGenerada.="<td width='20px'>";
                $this->seccionGenerada.="<a class='texto_pequeño' onclick="; $this->seccionGenerada.="\"arbolesConsulta(";
                $this->seccionGenerada.="'".$id_div."',";
                $this->seccionGenerada.="'".$id_elemento."',";
                $this->seccionGenerada.="'".$nivel_superior."',";
		$this->seccionGenerada.="'".$id_imagen."',"; 
                $this->seccionGenerada.="'".$describe."'";
                $this->seccionGenerada.=")\" >";
                $this->seccionGenerada.="<img src='<?echo $configuracion['site'].$configuracion['grafico']?>/mas.png'";
                $this->seccionGenerada.="border='0' id='".$id_imagen."'>"; 
                $this->seccionGenerada.="</a>";
                $this->seccionGenerada.="</td>"; 
            } 
            $this->seccionGenerada.="<td>";
            if($descripcion=="1")
            { 
               $this->seccionGenerada.="<a class='texto_pequeño' onclick="; $this->seccionGenerada.="\"arbolesConsulta(";
               $this->seccionGenerada.="'".$id_div."',";
               $this->seccionGenerada.="'".$id_elemento."',";
               $this->seccionGenerada.="'".$nivel_superior."',";
	       $this->seccionGenerada.="'".$id_imagen."',"; 
               $this->seccionGenerada.="'".$descripcion."'";
               $this->seccionGenerada.=")\">";
               $this->seccionGenerada.=utf8_encode($items_lista[$this->contador]); //Nombre
               $this->seccionGenerada.="</a>";
              
              
            }   
            else
            { 
               $this->seccionGenerada.=utf8_encode($items_lista[$this->contador]); //Nombre
               $colspan="";
            } 
            $this->seccionGenerada.="</td>";
            $this->seccionGenerada.="<td  align='right'>";

            #Si el nivel del arbol lleva algun tipo de botones  Ej. Editar, Eliminar, invoca a la funcion que visualiza y asigna atributos a los botones
            if($botones!="")
            {
               $this->seccionBotones=$this->generar_accesos($botones,$id_elemento,$tipoElemento,$configuracion);
               $this->seccionGenerada.=$this->seccionBotones;
            } 
            
            $this->seccionGenerada.="</td>";
            $this->seccionGenerada.="</tr>";	
            $this->seccionGenerada.="</tr>";
	    	
            #Fila contendora de capa donde se mostraran los elementos ligados de nivel inferior
            $this->seccionGenerada.="<tr>";
            #Si no esta mostrando una descripcion genera la celda para que el arbol se vea 
            #tabulado 
            if ($descripcion=="0"){ 
                $this->seccionGenerada.="<td></td>";
            }
            $this->seccionGenerada.="<td colspan='2'>";
            $this->seccionGenerada.="<div style='display:block'; "; 
            $this->seccionGenerada.="id='".$id_div."' name='".$id_div."'>";
            $this->seccionGenerada.="</div>";
            $this->seccionGenerada.="</td>";
            $this->contador=$this->contador+2;
         }
         //$this->seccionGenerada.="<td>dsasd</td>";
         
         $this->seccionGenerada.="</table>";
             
         return $this->seccionGenerada;
  }
  
  #Descripcion: Funcion que genera botones en caso que el nivel del arbol los requiera, asigna
  function generar_accesos($arr_botones,$id_elemento,$tipoElemento,$configuracion){
   
   #Segun el tipo de boton o si son elementos html invoca a la funcion correspondiente   
   switch($tipoElemento){
     case "botonImagen":
        $this->seccionBotones=$this->botonesImagen($arr_botones,$id_elemento,$configuracion);
     break;
     case "elementoHtml":
	$this->seccionBotones=$this->elementosHtml($arr_botones,$id_elemento,$configuracion);
     break;
     }#Cierre de switch         
     
     return  $this->seccionBotones;
   }#Cierre de funcion generar_botones

  #Si se requiren formar botones tipo imagen
  function botonesImagen($arr_botones,$id_elemento,$configuracion){
        
        $this->seccionBotones="<table border='0'><tr>";
        $this->array_botones=""; 
    
         #Si se debe generar un boton y el vinculo debe llevar como parametro un id de elemento. 
	/*Incluye clases para encriptar la url*/
	$this->indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	/*Cierre de clases para encriptar la url*/
	#Recorre el vector botones, para traer la informacion de los botones que se van 
        #a adicionar, por cada posicion del vector $botones, existe un vector 
	#donde se encuntra almacenada la informacion del boton 
	for($this->cont_botones=0; $this->cont_botones<count($arr_botones); $this->cont_botones++)
	{
           
           $this->seccionBotones.="<td>";
           #array_botones:
	   # 0. Nombre de boton
	   # 1. Ruta para el vinculo del boton
	   # 2. Ruta para la imagen del boton
	   $this->array_botones=$arr_botones[$this->cont_botones];  

           $this->evento=$this->array_botones[3];

           #Construye la ruta, con la ruta asignada desde el ajax, y adiciona el id del elemento porque son botones de eliminacion y edicion
	   $this->vinculo=$this->array_botones[1].$id_elemento;
	   $this->vinculo=$cripto->codificar_url($this->vinculo,$configuracion);
	   $this->vinculo=$this->indice.$this->vinculo;
             
           
           /*Evalua si el evento que va a ejecutar el boton es un href o un onclick*/ 
           switch($this->evento){
                 case "href":
		    $this->seccionBotones.="<a href='".$this->vinculo."'>";
                 break;
                 case "onclick":
                    $this->seccionBotones.="<a onclick=\"abrir_emergenteUbicada('";
                    $this->seccionBotones.=$this->vinculo;
                    $this->seccionBotones.=$this->array_botones[4];
                 break;
           } 
	         	
	   $this->seccionBotones.="<img alt='".$this->array_botones[0]."' ";
	   $this->seccionBotones.="src='".$this->array_botones[2]."' border='0' >";
	   $this->seccionBotones.="</a>";
	   $this->seccionBotones.="</td>";
	}#Cierre de for 
        
       $this->seccionBotones.="</tr></table>"; 
       return $this->seccionBotones;
  }#Cierre de funcion botonesImagen
 

  #Permite incluir elementos html y si lo requieren asignarles propiedades como,
  #invocacion a una funcion javascript cons sus correspondientes parametros
  function elementosHtml($arr_botones,$id_elemento,$configuracion){
        #Construye la tabla  
        $this->seccionBotones="<table border='0'><tr>";
        #Recorre el array botones que contiene, arrays en cada posicion
        #Cada array contiene las caracteristicas especificas para cada elemento
        #que se esta generando 
        for($this->cont_botones=0; $this->cont_botones<count($arr_botones); $this->cont_botones++)
	{      
               #Almacena la informacion correspondiente a las caracterisiticas del elemento 
              $this->caracteristicasElemento=$arr_botones[$this->cont_botones]; 

               
               #Prefijo para el id del elmento
               $this->prefijoElemento=$this->caracteristicasElemento[0];
               #Nombre de la funcion javascript a invocar
               $this->funcionJavascript=$this->caracteristicasElemento[1];
               #Parametros que lleva la funcion javascript a invocar
               $this->parametrosJavascript=$this->caracteristicasElemento[2];
               #Elemento que se planea crear (tag)
               $this->etiquetaElemento=$this->caracteristicasElemento[3];
               #Cierre de tag que se planea crear 
               $this->cierreEtiqueta=$this->caracteristicasElemento[4]; 
                            

	       #Construye celda para el elemento	
	       $this->seccionBotones.="<td>";
            
               #Apertura de tag  
               $id_tag=$this->prefijoElemento.$id_elemento;
               $this->seccionBotones.=$this->etiquetaElemento;
               $this->seccionBotones.="name='".$this->prefijoElemento.$id_elemento."' ";
               $this->seccionBotones.="id='".$this->prefijoElemento.$id_elemento."' ";
              
               #Si el elemento html tiene algun tipo de funcion javascript  
               if($this->funcionJavascript!=""){
                  $this->seccionBotones.="onclick='".$this->funcionJavascript."(\"";
                  #Si la funcion javascript lleva parametros  
                  if(count($this->parametrosJavascript)>=1){ 
                 	$this->contParametros=0;
                        $this->seccionParametros="";
                  	#Recorre vector de parametros para funcion javascript, si la funcion tiene
                        #paso de parametros, se agregan aqui, los valores de las variables que se envian desde el ajax
                  	while($this->contParametros<count($this->parametrosJavascript))
 		  	{
                           
                  	  $parametro=$this->parametrosJavascript[$this->contParametros];
                          #Convierte en variable el parametro del vector y captura su valor
                    	  $this->seccionParametros.=$$parametro."\",\"";
                          
                          $this->contParametros=$this->contParametros+1;
                  	}#Cierre de while parametros de funcion javascript 	 
                        
                        #Elmina la ultima coma                 
                        $this->seccionParametros=substr($this->seccionParametros,0,strlen($this->seccionParametros)-2);
                        #Adiciona a variable $this->seccionBotones 
                        $this->seccionBotones.=$this->seccionParametros;
                  }#Cierre de if count($this->parametrosJavascript)>=1
                  $this->seccionBotones.=")'";   
               }#Cierre de if($this->funcionJavascript!="")

               #Cierre de tag
               $this->seccionBotones.=$this->cierreEtiqueta;
       	       $this->seccionBotones.="</td>";
        }#Cierre de for
        $this->seccionBotones.="</tr></table>"; 
        return ($this->seccionBotones); 
  }#Cierre de funcion elementosHtml*/


   
}#Cierre de class arboles
?>