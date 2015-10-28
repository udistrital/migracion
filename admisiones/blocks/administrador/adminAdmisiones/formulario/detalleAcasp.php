<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivoDocumentos");

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1="admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
if (!$esteRecursoDB1) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion2="aspirantes";
$esteRecursoDB2 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion2);
if (!$esteRecursoDB2) {

    echo "//Este se considera un error fatal";
    exit;
}

if(!isset($_REQUEST['rba_id']))
{
    $miSesion = Sesion::singleton();
    $variable['sesionId']=$miSesion->getsesionId();
    $cadena_sql = $this->sql->cadena_sql("buscarSesion", $variable);
    $registroSesion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $variable['rba_id']=$registroSesion[0]['valor'];
}
else
{
    $variable['rba_id']=$_REQUEST['rba_id'];
}    

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//var_dump($_REQUEST);
$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}

if($cierto==1)
{
    $variable['aspirantes_id']=$_REQUEST['aspirantes_id'];
    
    $cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
    $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    //Descomponemos la cadena que coneitne el SNP para rescatar el año y el semestre que se presentó el ICFES
    $snpIcfes=substr($registroInscripcion[0]['asp_snp'],2,5);
        
    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
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
        
        if($variable['periodo']==1)
        {
            $periodo="PRIMER";
        }
        elseif($variable['periodo']==3)
        {
            $periodo="SEGUNDO";
        } 
        else
        {
            $periodo=" ";
        }
        
        //Colilla de inscripción estudiantes nuevos.
        if(is_array($registroInscripcion))
        {
            //-------------Control Mensaje-----------------------
           $tipo = 'message';
           $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA INGRESO  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."";

           $esteCampo = "mensaje";
           $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
           $atributos["etiqueta"] = "";
           $atributos["estilo"] = "centrar";
           $atributos["tipo"] = $tipo;
           $atributos["mensaje"] = $mensaje;
           echo $this->miFormulario->cuadroMensaje($atributos);
           unset($atributos);
           
           $variable['evento']=1;
           
           $cadena_sql = $this->sql->cadena_sql("consultarEncabezados", $variable);
           $registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
           
           $cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
           $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        //if(is_array($registroInscripcion))
        //{
            $credencial=  str_pad($registroInscripcion[0]['rba_asp_cred'],5,"0",STR_PAD_LEFT);
            
            $variable['carrera']=$registroInscripcion[0]['asp_cra_cod'];
            
            $cadena_sql = $this->sql->cadena_sql("consultarCarrera", $variable);
            $registroCarreras = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
            
            $cadena_sql = $this->sql->cadena_sql("buscartipInscripcion", $variable);
            $registroTipIns = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            for($i=0; $i<=count($registroTipIns)-1; $i++)
            {
                if($registroTipIns[$i]['ti_id']==$registroInscripcion[0]['ti_id'])
                {
                    $tipoInscripcion=$registroTipIns[$i]['ti_nombre'];
                }    
            }
            
            if($registroInscripcion[0]['asp_sexo']=='M')
            {
                $sexo='Masculino';
            }
            else
            {
                $sexo='Femenino';
            }
            
            $cadena_sql = $this->sql->cadena_sql("localidad", $variable);
            $registroLocalidad = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            for($i=0; $i<=count($registroLocalidad)-1; $i++)
            {
                if($registroLocalidad[$i][0]==$registroInscripcion[0]['asp_localidad'])
                {
                    $localidadResidencia=$registroLocalidad[$i][1];
                }
                if($registroLocalidad[$i][0]==$registroInscripcion[0]['asp_localidad_colegio'])
                {
                    $localidadColegio=$registroLocalidad[$i][1];
                }
                
            }
            
            $cadena_sql = $this->sql->cadena_sql("estrato", $variable);
            $estratoResidencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            for($i=0; $i<=count($estratoResidencia)-1; $i++)
            {
                if($estratoResidencia[$i][0]==$registroInscripcion[0]['asp_estrato'])
                {
                    $estratoResidencia=$estratoResidencia[$i][1];
                }
                else
                {
                    $estratoResidencia="";
                }    
            }
            
            $cadena_sql = $this->sql->cadena_sql("estrato", $variable);
            $estrato = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            if($registroInscripcion[0]['asp_estrato_costea']!="")
            {    
                for($i=0; $i<=count($estrato)-1; $i++)
                {
                    if($estrato[$i][0]==$registroInscripcion[0]['asp_estrato_costea'])
                    {
                        $estratoCosteara=$estrato[$i][1];
                    }
                    else
                    {
                        $estratoCosteara="";
                    }    
                }
            }
            else
            {
                $estratoCosteara="";
            }    
            
            if($registroInscripcion[0]['asp_tipo_colegio']=='O')
            {
                $tipoColegio="Oficial";
            }
            else
            {
                $tipoColegio="Privado";
            }
            
            $cadena_sql = $this->sql->cadena_sql("discapacidad", $variable);
            $registroDiscapacidad = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            if($registroInscripcion[0]['asp_tip_discap']!="")
            {    
                for($i=0; $i<=count($registroDiscapacidad)-1; $i++)
                {
                    if($registroDiscapacidad[$i]['dis_id']==$registroInscripcion[0]['asp_tip_discap'])
                    {
                        $discapacidad=$registroDiscapacidad[$i]['dis_nombre'];
                    }
                }
            }
            else
            {
                $discapacidad="";
            }
            
            $fecha=(explode('/',$registroInscripcion[0]['asp_fecha_nac'] ));
            $dia=$fecha[0];
            $mes=$fecha[1];
            $anio=$fecha[2];
            
            $fechaIns =date($mes."/".$dia."/".$anio);
            $fechaNacimiento=strtotime($fechaIns);
            
            $codigoSeguridad=$fechaNacimiento/($registroInscripcion[0]['rba_asp_cred']+$registroInscripcion[0]['asp_cra_cod']);
            
            //División para imprimir
            $atributos["id"] = "printthisdiv";
            $atributos["estilo"] = "PrintArea";
            echo $this->miFormulario->division("inicio", $atributos);
            unset($atributos);
            
            $atributos["id"] = "marcoAgrupacionFechas";
            $atributos["estilo"] = "jqueryui";
            $atributos["leyenda"] = "COMPROBANTE DE INSCRIPCIÓN";
            echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
            unset($atributos);
            
            echo "<table border='0' width='100%'>";
            echo "<tr>";        
                echo"<td width='50%'>";   
                //Periodo académico
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "Periodo académico: ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
               
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$periodo." PERIODO ".$variable['anio']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>"; 
            echo "</tr>";
            echo "<tr>";        
                echo"<td width='50%'>";   
                //Número de credencial
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "Credencial: ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$credencial."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>"; 
            echo "</tr>";
            echo "<tr>";        
                echo"<td width='50%'>";   
                //Carrera a la que se inscribe
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "Proyecto Curricular: ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_cra_cod']." - ".$registroCarreras[0][1]."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>"; 
            echo "</tr>";
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //Nombres y apellidos
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[28]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_nombre']." ".$registroInscripcion[0]['asp_apellido']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";
            echo "<tr>"; 
                echo "<td width='50%'>";
                //Correo electrónico
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[13]['preg_nombre'].": ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo"<td>";
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_email']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>"; 
                echo "<td width='50%'>";
                //Teléfono
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[12]['preg_nombre'].": ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo"<td>";
                //Teléfono
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_telefono']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";    
            echo "<tr>"; 
                echo"<td width='50%'>";
                 //Tipo de inscripción
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[2]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
                echo "<td>";
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$tipoInscripcion."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo "</td>";
            echo "</tr>";            
            echo "<tr>";        
                echo "<td width='50%'>";
                //Registro ICFES
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[20]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo "<td width='50%'>";
                //Registro ICFES
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_snp']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Localidad de residencia
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[9]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo"<td>";
                //Localidad de residencia
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$localidadResidencia."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Estrato de residencia
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[10]['preg_nombre'].": ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo"<td>";
                //Estrato de residencia
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$estratoResidencia."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Estrato de quien costeará los estudios
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[11]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo"<td>";
                //Estrato de quien costeará los estudios
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$estratoCosteara."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Localidad Colegio
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[22]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo "<td width='50%'>";
               //Localidad Colegio
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".$localidadColegio."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Fecha de nacimiento
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[27]['preg_nombre'].": ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
                echo"<td>";
                //Fecha de nacimiento
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_fecha_nac']."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //sexo
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[6]['preg_nombre'].": ";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos); 
                echo"</td>";
                echo"<td>";
                //sexo
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$sexo."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos); 
                echo"</td>";
            echo "</tr>";
            
            if($registroInscripcion[0]['asp_bio'] != '')
            {
            	echo "<tr>";        
	                echo "<td width='50%'>";
	                //Biologia
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {	
	                	$atributos["mensaje"] = $registroPreguntas[29]['preg_nombre'].": ";
	                }
	                else 
	                {
	                	$atributos["mensaje"] ="Ciencias Naturales";
	                }	
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo"<td>";
	                //Estado civil
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_bio']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if(($registroInscripcion[0]['asp_qui']) != '')
            {
            	echo "<tr>";        
	                echo "<td width='50%'>";
	                //Química
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[30]['preg_nombre'].":";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo"<td>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_qui']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_fis'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Fisica
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[31]['preg_nombre'].": ";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo"<td>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_fis']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_soc'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Sociales
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {
	                	$atributos["mensaje"] = $registroPreguntas[32]['preg_nombre'].": ";
	                }
	                else 
	                {
	                	$atributos["mensaje"] ="Sociales y Ciudadanas";
	                }
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo"<td>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_soc']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_apt_verbal'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Aptitud Verbal 
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[33]['preg_nombre'].":";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] ="<strong>".$registroInscripcion[0]['asp_apt_verbal']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_esp_y_lit'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Español y Literatura 
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {
	                	$atributos["mensaje"] = $registroPreguntas[34]['preg_nombre'].": ";
	                }
	                else
	                {
	                	$atributos["mensaje"] = "Lectura Critica";
	                }
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_esp_y_lit']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_apt_mat'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Aptitud Matemática 
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {
	                	$atributos["mensaje"] = $registroPreguntas[35]['preg_nombre'].":";
	                }
	                else 
	                {
	                	$atributos["mensaje"] = "Razonamiento Cuántico";
	                }
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_apt_mat']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_con_mat'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Conocimiento Matemático 
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {
	                	$atributos["mensaje"] = $registroPreguntas[36]['preg_nombre'].":";
	                }
	                else 
	                {
	                	$atributos["mensaje"] = "Matemáticas";
	                }
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_con_mat']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_fil'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Filosofía
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[37]['preg_nombre'].": ";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_fil']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_his'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Historia
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[38]['preg_nombre'].": ";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	               //RH
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = " <strong>".$registroInscripcion[0]['asp_his']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_geo'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Geografía
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[39]['preg_nombre'].":";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                //Registro ICFES
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_geo']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_idioma'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Idioma
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {
	                	$atributos["mensaje"] = $registroPreguntas[40]['preg_nombre'].":";
	                }
	                else
	                {
	                	$atributos["mensaje"] = "Ingles";
	                }
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_idioma']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_interdis'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                 //Interdisciplinario
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                if($snpIcfes <= 20141)
	                {
	                	$atributos["mensaje"] = $registroPreguntas[41]['preg_nombre'].": ";
	                }
	                else
	                {
	                	$atributos["mensaje"] = "Competencias Ciudadanas";
	                }
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);  
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_interdis']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);  
	                echo"</td>";
	            echo "</tr>";
            }
            if($registroInscripcion[0]['asp_cie_soc'] != '')
            {
	            echo "<tr>";        
	                echo "<td width='50%'>";
	                //Ciencias Sociales 
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = $registroPreguntas[42]['preg_nombre'].": ";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);    
	                echo"</td>";
	                echo "<td width='50%'>";
	                $esteCampo = "mensaje";
	                $atributos["id"] = "mensaje"; 
	                $atributos["etiqueta"] = "";
	                $atributos["estilo"] = "campoCuadroTexto";
	                $atributos ["tamanno"]="pequenno";
	                $atributos["tipo"] = $tipo;
	                $atributos["mensaje"] = "<strong>".$registroInscripcion[0]['asp_cie_soc']."</strong>";
	                echo $this->miFormulario->campoMensaje($atributos);
	                unset($atributos);    
	                echo"</td>";
	            echo "</tr>";
            }
            echo "<tr>";        
                echo "<td width='50%'>";
                //Tipo de discapacidad
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $registroPreguntas[26]['preg_nombre'].":";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);    
                echo"</td>";
                echo "<td width='50%'>";
                //Tipo de discapacidad
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = " <strong>".$discapacidad."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Codigo de seguridad
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "Código de seguridad :";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
                echo "<td width='50%'>";
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".round($codigoSeguridad)."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
            echo "</tr>";
            echo "<tr>";        
                echo "<td width='50%'>";
                //Observaciones
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "Fecha de impresión :";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
                echo "<td width='50%'>";
                //Fecha de impresión
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["tamanno"]="pequenno";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = "<strong>".date('d/m/Y h:i:s A')."</strong>";
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);  
                echo"</td>";
            echo "</tr>";
            echo "</table>";
           
            echo $this->miFormulario->marcoAGrupacion("fin");
             
           /* if($variable['carrera']==$registroColilla[0]['colilla_carreras'])
            {    
                //Mensaje comprobante de inscripción
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos ["tamanno"]="pequenno";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos ["estiloEnLinea"]="width: 99%;";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroColilla[0]['colilla_contenido']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
            }*/
            $cadena_sql = $this->sql->cadena_sql("buscarDocumentos", $variable);
            $registroDocumentos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            unset($variable);

            if(is_array($registroDocumentos))
            {    
                $directorioArchivos = opendir($rutaArchivos); //ruta actual

                //DOCUMENTOS CARGADOS
                $atributos["id"] = "marcoDocumentosAdjuntos";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = "Lista de documentos subidos.";
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);
                
                echo "<table width='100%'>";
                $i=0;
                $flag=0;
                while ($archivo = readdir($directorioArchivos)) //obtenemos un archivo y luego otro sucesivamente
                {
                    if ($archivo!='..' && $archivo!='.')
                    {
                        $dividimos=  explode('_', $archivo);
                        if(trim($registroInscripcion[0]['rba_id'])==trim($dividimos[1]))
                        {
                            if(trim($registroInscripcion[0]['asp_cra_cod'])==$dividimos[2])
                            {
                                $variable['carrera']=$registroInscripcion[0]['asp_cra_cod'];
                                $variable['prefijo']=$dividimos[0];
                                $cadena_sql = $this->sql->cadena_sql("buscarDocumentos", $variable);
                                $registroDocumentos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                
                                $variables ="pagina=admisiones"; //pendiente la pagina para modificar parametro                                                        
                                $variables.="&opcion=verArchivo";
                                $variables.="&action=".$esteBloque["nombre"];
                                $variables.="&usuario=". $_REQUEST['usuario'];
                                $variables.="&tipo=".$_REQUEST['tipo'];
                                //$variables.="&id_tipIns=".$registro[$i][0];
                                $variables.="&archivo=".$archivo;
                                $variables.="&bloque=".$esteBloque["id_bloque"];
                                $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                                $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);
                                
                                echo "<tr>";
                                echo "<td>";
                                echo "<a href='".$variables."' TARGET='_blank'> ";
                                $esteCampo = "mensaje";
                                $atributos["id"] = "mensaje"; 
                                $atributos["etiqueta"] = "";
                                $atributos["estilo"] = "campoCuadroTexto";
                                $atributos ["tamanno"]="pequenno";
                                $atributos["tipo"] = $tipo;
                                $atributos["mensaje"] = $registroDocumentos[0]['doc_nombre'];
                                echo $this->miFormulario->campoMensaje($atributos);
                                unset($atributos);
                                echo "</a>";
                                echo "</td>";
                                echo "<td>";
                                echo "<a href='".$variables."' TARGET='_blank'> ";
                                echo "<img src='".$rutaBloque."/images/pdfmini.png' width='15px'> ";
                                echo "</a>";
                                echo "</td>";
                                echo "</tr>";
                                
                            }
                        }
                    $i++;    
                    }
                }
                echo "</table>";
                
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = stripslashes(html_entity_decode($registroEncabezados[9]['enc_nombre']));
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo $this->miFormulario->marcoAGrupacion("fin");
            }
            //------------------Fin Division para los botones-------------------------
            //-------------Fin Control Boton----------------------
            
            echo $this->miFormulario->division("fin");
            
            
            //------------------Division para los botones-------------------------
            $atributos["id"]="botones";
            $atributos["estilo"]="marcoBotones";
            echo $this->miFormulario->division("inicio",$atributos);
           //-------------Control Boton-----------------------
            
            $esteCampo="botonImprimir";
            $atributos["id"]=$esteCampo;
            $atributos["tabIndex"]=$tab++;
            $atributos["verificar"]="";
            $atributos["tipo"]="boton";
            $atributos["nombreFormulario"] = "PrintButton";
            $atributos["valor"]="Imprimir";
            echo $this->miFormulario->campoBoton($atributos);
            unset($atributos);
            
            echo $this->miFormulario->division("fin");
            
            //Mensaje comprobante de inscripción
            $esteCampo = "mensaje";
            $atributos["id"] = "mensaje"; 
            $atributos["etiqueta"] = "";
            $atributos ["tamanno"]="pequenno";
            $atributos["estilo"] = "campoCuadroTexto";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = '<p align="justify">NOTA: En el evento de no quedar registrados los datos de la inscripción en el presente comprobante, vuelva a ingresar y realice nuevamente el proceso de inscripción; de llegar a persisitir esta situación, favor acercarse a la Oficina de Admisiones ubicada en la carrera 8 No 40-62, primer piso, Edificio Sabio Caldas, telefonos 3239300 Ext. 1102, o remitir su inquietud al correo electrónico admisiones@udistrital.edu.co, dentro de los plazos asignados en el proceso de admisiones. </p>';
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);
           
            echo $this->miFormulario->division("fin");
        }
        else
        {
            $nombreFormulario=$esteBloque["nombre"];

            include_once("core/crypto/Encriptador.class.php");
            $cripto=Encriptador::singleton();
            $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

            $miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
            $tab=1;
            //---------------Inicio Formulario (<form>)--------------------------------
            $atributos["id"]=$nombreFormulario;
            $atributos["tipoFormulario"]="multipart/form-data";
            $atributos["metodo"]="POST";
            $atributos["nombreFormulario"]=$nombreFormulario;
            $verificarFormulario="1";
            echo $this->miFormulario->formulario("inicio",$atributos);

            $atributos["id"]="divErrores";
            $atributos["estilo"]="marcoBotones";
            //$atributos["estiloEnLinea"]="display:none"; 
            echo $this->miFormulario->division("inicio",$atributos);


                $tipo = 'information';
                $mensaje = 'No se encontraron registros de inscripción con los datos suministrados.';
                $boton = "regresar";

                $valorCodificado="&opcion=nuevo"; 
                //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso']; 
                $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                $valorCodificado=$cripto->codificar($valorCodificado);


            $esteCampo = "botonContinuar";
            $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = $mensaje;
            echo $this->miFormulario->cuadroMensaje($atributos);
            unset($atributos); 

            //------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");

            //------------------Division para los botones-------------------------
            $atributos["id"]="botones";
            $atributos["estilo"]="marcoBotones";
            echo $this->miFormulario->division("inicio",$atributos);

            //-------------Control Boton-----------------------
            $esteCampo ="botonContinuar" ;
            $atributos["id"]=$esteCampo;
            $atributos["tabIndex"]=$tab++;
            $atributos["tipo"]="boton";
            $atributos["estilo"]="jquery";
            $atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
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
            echo $this->miFormulario->division("fin");
        }
}
else
{
    $nombreFormulario=$esteBloque["nombre"];

    include_once("core/crypto/Encriptador.class.php");
    $cripto=Encriptador::singleton();
    $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

    $miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
    $tab=1;
    //---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"]=$nombreFormulario;
    $atributos["tipoFormulario"]="multipart/form-data";
    $atributos["metodo"]="POST";
    $atributos["nombreFormulario"]=$nombreFormulario;
    $verificarFormulario="1";
    echo $this->miFormulario->formulario("inicio",$atributos);

	$atributos["id"]="divErrores";
	$atributos["estilo"]="marcoBotones";
        //$atributos["estiloEnLinea"]="display:none"; 
	echo $this->miFormulario->division("inicio",$atributos);
	
	
            $tipo = 'information';
            $mensaje = 'No se encontrararon períodos académicos activos, para activar o registrar un periodo académico activo haga click en "Continuar"...';
            $boton = "regresar";
                        
            $valorCodificado="&opcion=nuevo"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso']; 
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	
	
	$esteCampo = "botonContinuar";
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos); 
        
        //------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
        
        //------------------Division para los botones-------------------------
	$atributos["id"]="botones";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control Boton-----------------------
	$esteCampo ="botonContinuar" ;
	$atributos["id"]=$esteCampo;
	$atributos["tabIndex"]=$tab++;
	$atributos["tipo"]="boton";
	$atributos["estilo"]="jquery";
	$atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
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
        echo $this->miFormulario->division("fin");

}
