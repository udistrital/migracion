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
$variable['codigoEstudiante']=$registro[0]['pus_usuario'];

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
       
    $cadena_sql = $this->sql->cadena_sql("datosEmpleados", $variable);
    $datos=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
    //echo "aaa".$cadena_sql."<br>";
    $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
    $caracteresDir = " AB CD E ab cd e ";
    $caracteresMail = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    
    $proveedores=array("gmail.com","yahoo.com","yahoo.es","hotmail.com","latinmail.com","udistrital.edu.co","correo.udistrital.edu.co","terra.com");
        
    $cardinales=array("","Sur","Este");
    $cierto=0;
    if(!is_array($datos))
    {
        $cadena_sql = $this->sql->cadena_sql("datosDocentes", $variable);
        $datos=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
        //echo "bbb".$cadena_sql."<br>";
        if(!is_array($datos))
        {
            $cadena_sql = $this->sql->cadena_sql("datosEstudiantes", $variable);
            $datos=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
            //echo "ccc".$cadena_sql."<br>";
            if(!is_array($datos))
            {
                $cadena_sql = $this->sql->cadena_sql("datosAsistentes", $variable);
                $datos=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
                //echo "ddd".$cadena_sql."<br>";
                if(!is_array($datos))
                {
                    $cadena_sql = $this->sql->cadena_sql("datosEgresados", $variable);
                    $datos=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
                    //echo "ddd".$cadena_sql."<br>";
                    if(is_array($datos))
                    {
                        $cierto=5;
                    }    
                }
                else
                {
                    $cierto=4;
                }    
            }
            else
            {
                $cierto=3;
            }    
        }
        else
        {
             $cierto=2;
        }    
    }
    else
    {
        $cierto=1;
    }    
    if($cierto==1 || $cierto==2 || $cierto==3 || $cierto==4 || $cierto==5)
    {
        //Telefonos
            if($datos[0][3]!='')
            {
                $telefono1=$datos[0][3];
                $telefono2=$datos[0][3]+rand('1','50');
		$telefono3=rand('2222222','9999999');
            }
            else
            {
                $telefono1="Sin registro";
                $telefono2=rand('2222222','5555555');
                $telefono3=rand('5555556','9999999');
            }


            //Direcciones
            if($datos[0][2]!='')
            {    
                $direccion1=$datos[0][2];
                if($datosAleatorios[0][5]!='')
                {    
                    $direccion2=$datosAleatorios[0][5];
                }
                else
                {
                    $direccion2="Cra ".rand('1','50')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." No. ".rand('0','100')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." - ".rand('1','100')." ";
                }    
                $direccion3=substr($datos[0][2], 0, -4);
            }
            else
            {
                $direccion1="Sin registro";
		$direccion2="Cra ".rand('1','50')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." No. ".rand('0','100')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." - ".rand('1','100')." ".$cardinales[rand(0,count($cardinales))]." ";
		$direccion3="Cll ".rand('51','100')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." ".$cardinales[rand(0,count($cardinales))]." No. ".rand('101','200')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." - ".rand('1','100')." ";
            }    
            //Correos
            if($datos[0][5]!='')
            {    
                $correo1=$datos[0][5];
                $corroAleatorio=explode('@',$datos[0][5]);
                
                if($datosAleatorios[0][13]!='')
                {    
                    $correo2=$datosAleatorios[0][13];
                }
                else
                {
                    $correo2=$corroAleatorio[0].substr($caracteres,rand(0,strlen($caracteres)),1).'@'.$corroAleatorio[1];
                }   
                $correo3=$corroAleatorio[0].'@'.$proveedores[rand(0,count($proveedores))];
            }
            else
            {
                $corroAleatorio=explode(' ',$datos[0][1]);
                $correo1="Sin registro";
                $correo2=$corroAleatorio[0].'@'.$proveedores[rand(1,count($proveedores))];
                if($datosAleatorios[0][13]!='')
                {    
                    $correo3=$datosAleatorios[0][13];
                }
                else
                {
                     $correo3=$corroAleatorio[0].'@'.$proveedores[rand(1,count($proveedores))];
                }   
            }    
            
            //Cedulas
            $cedula1=$datos[0][0];
            $cedula2=$datos[0][0]+rand(0,1000);
            $cedula3=$datos[0][0]+rand(1001,10000);
    
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
    
    }
    else
    {
        $tipo = 'information';
        $mensaje = "No se registran datos del usuario, comuníquese con el administrador del sistema.";
        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
    }


//echo $this->miFormulario->division("fin");




