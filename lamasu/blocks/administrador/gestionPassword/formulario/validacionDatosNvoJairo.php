<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['usuario_id']=$_REQUEST['nombreUsuario'];
$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['documentoActual']=$registro[0]['usu_nro_doc_actual'];

$conexion1="wconexionclave";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}

$valorCodificado="pagina=gestionPassword";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=validarDatos";
$valorCodificado.="&nombreUsuario=".$_REQUEST['nombreUsuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&documentoActual=".$registro[0]['usu_nro_doc_actual'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionFechas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Formulario para validación de información";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);


$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);
    unset($atributos);
    
    //-------------Control Mensaje-----------------------
    $esteCampo = "informacionValidaDatos";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "";
    $atributos["tipo"] = "message";
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->cuadroMensaje($atributos);
    
    $variable['nombreUsuario']=$_REQUEST['nombreUsuario'];
    $pregunta1="¿Cuál de los siguientes números de teléfono tiene registrado en el sistema?";
    $pregunta2="¿Cuál de las siguientes direcciones tiene registrada en el sistema?";
    $pregunta3="¿Cuál de los siguientes correos electrónicos le pertenece?";
    $pregunta4="¿Cuál de los siguientes números de identificación le pertenece?";
        
    $preguntas = array("$pregunta1", "$pregunta2", "$pregunta3", "$pregunta4");
    $randomPreguntas = array_rand($preguntas, 3);
    $cuenta=count($randomPreguntas);
    
    
    $cadena_sql = $this->sql->cadena_sql("datosAleatorios", $variable);
    $datosAleatorios=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
       
    $cadena_sql = $this->sql->cadena_sql("datosDocentes", $variable);
    $datosDocentes=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
	
    $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $caracteresDir = " AB CD E ab cd e ";
    $caracteresMail = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    if(!is_array($datosDocentes))
    {
        $cadena_sql = $this->sql->cadena_sql("datosEmpleados", $variable);
        $datosEmpleados=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
        
        if(!is_array($datosEmpleados))
        {
            $cadena_sql = $this->sql->cadena_sql("datosEstudiantes", $variable);
            $datosEstudiantes=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
           
            //Telefonos
            if($datosEstudiantes[0][3]!='')
            {
                $telefono1=$datosEstudiantes[0][3];
                //$telefono2=$datosEstudiantes[0][3]+328;
                //$telefono3=$datosEstudiantes[0][3]+4;
		$telefono2=$datosEstudiantes[0][3]+rand('1','50');
		$telefono3=rand('2222222','9999999');
            }
            else
            {
              //$celular1="Sin registro";
              //$celular2=2444556+3;
              //$celular3=4444546+4;
                $telefono1="Sin registro";
                $telefono2=rand('2222222','5555555');
                $telefono3=rand('5555556','9999999');
            }


            //Direcciones
            if($datosEstudiantes[0][2]!='')
            {    
                $direccion1=$datosEstudiantes[0][2];
                if($datosAleatorios[0][5]!='')
                {    
                    $direccion2=$datosAleatorios[0][5];
                }
                else
                {
                    $direccion2="Cra ".rand('1','50')." ".substr($caracteres,rand(0,strlen($caracteresDir)),1)." No. ".rand('0','100')." ".substr($caracteresDir,rand(0,strlen($caracteres)),1)." - ".rand('1','100')." ";
                }    
                $direccion3=substr($datosEstudiantes[0][2], 0, -4);
            }
            else
            {
                $direccion1="Sin registro";
		$direccion2="Cra ".rand('1','50')." ".substr($caracteres,rand(0,strlen($caracteresDir)),1)." No. ".rand('0','100')." ".substr($caracteresDir,rand(0,strlen($caracteres)),1)." - ".rand('1','100')." ";
		$direccion3="Cra ".rand('51','100')." ".substr($caracteres,rand(0,strlen($caracteresDir)),1)." No. ".rand('101','200')." ".substr($caracteresDir,rand(0,strlen($caracteres)),1)." - ".rand('1','100')." ";
                //$direccion2="cra 8 No. 42-19";
                //$direccion3="Cra 80 No. 15-32";
            }    
            //Correos
            if($datosEstudiantes[0][5]!='')
            {    
                $correo1=$datosEstudiantes[0][5];
                if($datosAleatorios[0][13]!='')
                {    
                    $correo2=$datosAleatorios[0][13];
                }
                else
                {
                    $correo2="ajekmn@gmail.com";
                }   
                $correo3=substr($datosEstudiantes[0][5], 0, -2);
            }
            else
            {
                $correo1="Sin registro";
                $correo2="kfff@udistrital.edu.co";
                if($datosAleatorios[0][13]!='')
                {    
                    $correo3=$datosAleatorios[0][13];
                }
                else
                {
                    $correo3="ajekmn@gmail.com";
                }   
            }    
            
            //Cedulas
            $cedula1=$datosEstudiantes[0][0];
            $cedula2=$datosEstudiantes[0][0]+rand(0,10);
            $cedula3=$datosEstudiantes[0][0]+4;

        }
        else
        {    
            //Telefonos
            if($datosEmpleados[0][3]!='')
            {    
                $telefono1=$datosEmpleados[0][3];
                $telefono2=$datosEmpleados[0][3]+3;
                $telefono3=$datosEmpleados[0][3]+4;
            }
            else
            {
                $telefono1="Sin registro";
                $telefono2=2444556+3;
                $telefono3=4444546+4;
            }

            //Direcciones
            if($datosEmpleados[0][2]!='')
            {    
                $direccion1=$datosEmpleados[0][2];
                if($datosAleatorios[0][5]!='')
                {    
                    $direccion2=$datosAleatorios[0][5];
                }
                else
                {
                    $direccion2="cra 8 No. 42-19";
                }    
                $direccion3=substr($datosEmpleados[0][2], 0, -4);
            }
            else
            {
                $direccion1="Sin registro";
                $direccion2="cra 8 No. 42-19";
                $direccion3="Cra 80 No. 15-32";
            }
            
            //Correos
            if($datosEmpleados[0][5]!='')
            {    
                $correo1=$datosEmpleados[0][5];
                if($datosAleatorios[0][13]!='')
                {    
                    $correo2=$datosAleatorios[0][13];
                }
                else
                {
                    $correo2="ajekmn@gmail.com";
                }
                $correo3=substr($datosEmpleados[0][2], 0, -2);
            }
            else
            {
                $correo1="Sin registro";
                $correo2="kfff@udistrital.edu.co";
                if($datosAleatorios[0][13]!='')
                {    
                    $correo3=$datosAleatorios[0][13];
                }
                else
                {
                    $correo3="ajekmn@gmail.com";
                }   
            }      
            //Cedulas
            $cedula1=$datosEmpleados[0][0];
            $cedula2=$datosEmpleados[0][0]+3;
            $cedula3=$datosEmpleados[0][0]+4;
        } 
                
    }    
    else
    {   
        //Telefonos
        if($datosDocentes[0][3]!='')
        {
            $telefono1=$datosDocentes[0][3];
            $telefono2=$datosDocentes[0][3]+328;
            $telefono3=$datosDocentes[0][3]+4;
        }
        else
        {
            $celular1="Sin registro";
            $celular2=2444556+3;
            $celular3=4444546+4;
        }
        
        //Direcciones
        if($datosDocentes[0][2]!='')
        {    
            $direccion1=$datosDocentes[0][2];
            if($datosAleatorios[0][5]!='')
            {    
                $direccion2=$datosAleatorios[0][5];
            }
            else
            {
                $direccion2="cra 8 No. 42-19";
            }    
            $direccion3=substr($datosDocentes[0][2], 0, -4);
        }
        else
        {
            $direccion1="Sin registro";
            $direccion2="cra 8 No. 42-19";
            $direccion3="Cra 80 No. 15-32";
        }
        //Correos
        if($datosDocentes[0][5]!='')
        {    
            $correo1=$datosDocentes[0][5];
            if($datosAleatorios[0][13]!='')
            {    
                $correo2=$datosAleatorios[0][13];
            }
            else
            {
                $correo2="ajekmn@gmail.com";
            }   
            $correo3=substr($datosDocentes[0][5], 0, -2);
        }
        else
        {
            $correo1="Sin registro";
                $correo2="kfff@udistrital.edu.co";
                if($datosAleatorios[0][13]!='')
                {    
                    $correo3=$datosAleatorios[0][13];
                }
                else
                {
                    $correo3="ajekmn@gmail.com";
                }  
        }    
        
        //Cedulas
        $cedula1=$datosDocentes[0][0];
        $cedula2=$datosDocentes[0][0]+328;
        $cedula3=$datosDocentes[0][0]+4;
    }
        
    $html="<table>";
    
    for($i=0; $i<$cuenta; $i++)
    {
        $numPreg=$i+1;
        if($preguntas[0]==$preguntas[$randomPreguntas[$i]])
        {    
            $respuestas=array($telefono1,$telefono2,$telefono3);
        }
        if($preguntas[1]==$preguntas[$randomPreguntas[$i]])
        {
            $respuestas=array($direccion1,$direccion2,$direccion3);
        }
        if($preguntas[2]==$preguntas[$randomPreguntas[$i]])
        {
            $respuestas=array($correo1,$correo2,$correo3);
        }
        if($preguntas[3]==$preguntas[$randomPreguntas[$i]])
        {
            $respuestas=array($cedula1,$cedula2,$cedula3);
        }
                
        shuffle($respuestas);
        
        $html.="<tr>";
        $html.="<td>";
        $html.=$numPreg.". ".$preguntas[$randomPreguntas[$i]];   
        $html.="</td>";
        $html.="</tr>";
        $html.="<tr>";
        $html.="<td>";
        //------------------Control Lista Desplegable------------------------------
            $html.="<table>";
            $html.="<tr>";
            foreach ($respuestas as $respuestas)
            {
                $html.="<td>";
                $esteCampo = "valores";
                $atributos["id"] = $esteCampo.$i;
                $atributos["tabIndex"] = $tab++;
                $atributos["etiqueta"]=$respuestas;
                $atributos["anchoEtiqueta"] = 225;
                   //$atributos["etiqueta"]=  array_push($respuestas, $aleatorio);
                $atributos["seleccionado"] = false;
                $atributos["estilo"]="jqueryui";
                $atributos["obligatorio"] = true;
                $atributos["validar"]="required";
                $atributos["seleccion"]='';
                $atributos["opciones"]=$respuestas;
                $atributos["valor"]=$respuestas;
                $html.=$this->miFormulario->campoBotonRadial($atributos);
                unset($atributos);
                $html.="</td>";
            }
            $html.="</tr>";
            $html.="</table>";
        $html.="</td>";    
        $html.="</tr>"; 
    }
   
    $html.="</table>";
    echo $html;
    
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);   
    
    $esteCampo = "botonEnviar";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["tipo"] = "boton";
    $atributos["estilo"] = ""; 
   //$atributos["estilo"]="jqueryui";
    $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
    //$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos["tipoSubmit"]="jquery";
    $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["nombreFormulario"] = $nombreFormulario;
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
   
    //-------------Fin Control Boton----------------------
    //-------------Control Boton-----------------------
    $esteCampo="botonCancelar";
    $atributos["id"]=$esteCampo;
    $atributos["tabIndex"]=$tab++;
    $atributos["verificar"]="";
    $atributos["tipo"]="boton";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $atributos["cancelar"]=true;
    //$atributos["tipoSubmit"] = "jquery";
    //$atributos["onclick"]=true;
    $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
    
   //-------------Control cuadroTexto con campos ocultos-----------------------
    //Para pasar variables entre formularios o enviar datos para validar sesiones
    $atributos["id"] = "formSaraData"; //No cambiar este nombre
    $atributos["tipo"] = "hidden";
    $atributos["obligatorio"] = false;
    $atributos["etiqueta"] = "";
    $atributos["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //Fin del Formulario
    echo $this->miFormulario->formulario("fin");
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");
    



//echo $this->miFormulario->division("fin");




