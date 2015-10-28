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
    $cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
    @$registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $variable['evento']=$_REQUEST['evento'];
    $cadena_sql = $this->sql->cadena_sql("consultarEventosRegistrados", $variable);
    $registroEventosRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $variable['carrera']=$_REQUEST['carreras'];
    
    $cadena_sql = $this->sql->cadena_sql("consultarCarrera", $variable);
    $registroCarreras = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
    
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
        
        if(isset($_REQUEST['carreras']))
        {
            if($_REQUEST['evento']==4)
            {    
                //-------------Control Mensaje-----------------------
               $tipo = 'message';
           $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA ".strtoupper($registroEventosRegistrados[0]['des_nombre']).",  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</p>
                       Revise cuidadosamente la información consignada. Si es correcta la información haga click en 'GUARDAR', de lo contrario haga click en 'Cancelar'.</center></H3>";

               $esteCampo = "mensaje";
               $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
               $atributos["etiqueta"] = "";
               $atributos["estilo"] = "centrar";
               $atributos["tipo"] = $tipo;
               $atributos["mensaje"] = $mensaje;
               echo $this->miFormulario->cuadroMensaje($atributos);
               unset($atributos);
               
               $variable['evento']=$_REQUEST['evento'];
               
               $cadena_sql = $this->sql->cadena_sql("consultarEncabezados", $variable);
               $registroEncabezados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

               $cadena_sql = $this->sql->cadena_sql("consultarPreguntas", $variable);
               $registroPreguntas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $campovacio=0;
                foreach ($_REQUEST as $clave => $valor) {
                    //echo $clave ."=>". $valor."<br>";
                    if($valor=='' && $clave!='true' && $clave!='observaciones' && $clave!='motivoTransferencia')
                    {
                        $mensaje="El campo ".$clave." está vacío, intente nuevamente.";
                        $html="<script>alert('".$mensaje."');</script>";
                        echo $html;
                        echo "Redireccionando.";
                       // $indice = $registro[0][10];
                        echo "<script>location.replace('')</script>";
                    }
                    if($clave=='registroIcfes1' || $clave=='registroIcfes2')
                    {
                        $registroIcfes=$valor;
                    }
                    if($clave=='confirmarRegistroIcfes')
                    {    
                        if($registroIcfes != $valor)
                        {
                            $mensaje="El número de registro de ICFES no coiciden.";
                            $html="<script>alert('".$mensaje."');</script>";
                            echo $html;
                            echo "Redireccionando.";
                           // $indice = $registro[0][10];
                            echo "<script>location.replace('')</script>";
                        }
                        else
                        {
                            $registroIcfes=$valor;
                        }
                    }
                }

                $cadena_sql = $this->sql->cadena_sql("gedepartamento", $variable);
                $registroDepartamento = $esteRecursoDB2->ejecutarAcceso($cadena_sql, "busqueda");
                for($i=0; $i<=count($registroDepartamento)-1; $i++)
                {
                    if($registroDepartamento[$i][0]==$_REQUEST['departamento'])
                    {
                        $departamento=$registroDepartamento[$i][1];
                    }    
                }

                $cadena_sql = $this->sql->cadena_sql("municipio", $variable);
                $registroMunicipio = $esteRecursoDB2->ejecutarAcceso($cadena_sql, "busqueda");
                for($i=0; $i<=count($registroMunicipio)-1; $i++)
                {
                    if($registroMunicipio[$i][0]==$_REQUEST['municipio'])
                    {
                        $municipio=$registroMunicipio[$i][1];
                    }    
                }

                if($_REQUEST['sexo']=='M')
                {
                    $sexo='Masculino';
                }
                else
                {
                    $sexo='Femenino';
                }

                $cadena_sql = $this->sql->cadena_sql("estadoCivil", $variable);
                $registroEstadoCivil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                for($i=0; $i<=count($registroEstadoCivil)-1; $i++)
                {
                    if($registroEstadoCivil[$i][0]==$_REQUEST['estadoCivil'])
                    {
                        $estadoCivil=$registroEstadoCivil[$i][1];
                    }    
                }

                $cadena_sql = $this->sql->cadena_sql("localidad", $variable);
                $registroLocalidad = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                for($i=0; $i<=count($registroLocalidad)-1; $i++)
                {
                    if($registroLocalidad[$i][0]==$_REQUEST['localidadResidencia'])
                    {
                        $localidadResidencia=$registroLocalidad[$i][1];
                    }
                    if($registroLocalidad[$i][0]==$_REQUEST['localidadColegio'])
                    {
                        $localidadColegio=$registroLocalidad[$i][1];
                    }

                }

                $cadena_sql = $this->sql->cadena_sql("estrato", $variable);
                $estratoResidencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                for($i=0; $i<=count($estratoResidencia)-1; $i++)
                {
                    if($estratoResidencia[$i][0]==$_REQUEST['estratoResidencia'])
                    {
                        $estratoResidencia=$estratoResidencia[$i][1];
                    }    
                }

                $cadena_sql = $this->sql->cadena_sql("tipDocumento", $variable);
                $registroTipDoc = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                for($i=0; $i<=count($registroTipDoc)-1; $i++)
                {
                    if($registroTipDoc[$i][0]==$_REQUEST['tipDocActual'])
                    {
                        $tipoDocumento=$registroTipDoc[$i][1];
                    }
                    if($registroTipDoc[$i][0]==$_REQUEST['tipDocIcfes'])
                    {
                        $tipoDocumentoIcfes=$registroTipDoc[$i][1];
                    }
                }
                
                if($_REQUEST['rh']==0)
                {    
                    $rh="+";
                }
                else
                {
                    $rh="-";
                }    

                //$valorCodificado="pagina=habilitarEvaluacion";
                $valorCodificado="&action=".$esteBloque["nombre"];
                $valorCodificado.="&opcion=guardarInscripcion";
                $valorCodificado.="&usuario=".$_REQUEST['usuario'];
                $valorCodificado.="&tipo=".$_REQUEST['tipo'];
                $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
                $valorCodificado.="&id_periodo=".$variable['id_periodo'];
                $valorCodificado.="&anio=".$variable['anio'];
                $valorCodificado.="&carreras=".$_REQUEST['carreras'];
                $valorCodificado.="&universidadProviene=".$_REQUEST['universidadProviene'];
                $valorCodificado.="&carreraVeniaCursando=".$_REQUEST['carreraVeniaCursando'];
                $valorCodificado.="&semestreCursado=".$_REQUEST['semestreCursado'];
                $valorCodificado.="&motivoTransferencia=".$_REQUEST['motivoTransferencia'];
                $valorCodificado.="&pais=".$_REQUEST['pais'];
                $valorCodificado.="&departamento=".$_REQUEST['departamento'];
                $valorCodificado.="&municipio=".$_REQUEST['municipio'];
                $valorCodificado.="&fechaNac=".$_REQUEST['fechaNac'];
                $valorCodificado.="&sexo=".$_REQUEST['sexo'];
                $valorCodificado.="&estadoCivil=".$_REQUEST['estadoCivil'];
                $valorCodificado.="&direccionResidencia=".$_REQUEST['direccionResidencia'];
                $valorCodificado.="&localidadResidencia=".$_REQUEST['localidadResidencia'];
                $valorCodificado.="&estratoResidencia=".$_REQUEST['estratoResidencia'];
                $valorCodificado.="&telefono=".$_REQUEST['telefono'];
                $valorCodificado.="&email=".$_REQUEST['email'];
                $valorCodificado.="&tipDocActual=".$_REQUEST['tipDocActual'];
                $valorCodificado.="&documentoActual=".$_REQUEST['documentoActual'];
                $valorCodificado.="&tipDocIcfes=".$_REQUEST['tipDocIcfes'];
                $valorCodificado.="&documentoIcfes=".$_REQUEST['documentoIcfes'];
                $valorCodificado.="&tipoSangre=".$_REQUEST['tipoSangre'];
                $valorCodificado.="&rh=".$_REQUEST['rh'];
                $valorCodificado.="&registroIcfes=".$registroIcfes;
                $valorCodificado.="&localidadColegio=".$_REQUEST['localidadColegio'];
                $valorCodificado.="&observaciones=".$_REQUEST['observaciones'];
                $valorCodificado.="&evento=".$_REQUEST['evento'];
                $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                $valorCodificado.="&periodo=".$variable['periodo'];
                $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[0]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                echo "<table border='0' width='100%'>";
                echo "<tr>";        
                    echo"<td width='50%'>";   
                    //Carrera a la que se inscribe
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "Carrera a la que se inscribe: ";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                    echo "<td>";
                    //Carrera a la que se inscribe
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = " <strong>".$_REQUEST['carreras']." - ".$registroCarreras[0][1]."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo"<td width='50%'>";
                     //Universidad de donde viene
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[0]['preg_nombre'].":";
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
                    $atributos["mensaje"] = "<strong>".strtoupper($_REQUEST['universidadProviene'])."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                echo "</tr>";
                echo "<tr>"; 
                    echo"<td width='50%'>";
                     //Carrera que venía cursando
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[1]['preg_nombre'].":";
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
                    $atributos["mensaje"] = "<strong>".strtoupper($_REQUEST['carreraVeniaCursando'])."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                echo "</tr>";
                echo "<tr>"; 
                    echo"<td width='50%'>";
                     //Último semestre cursado
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
                    $atributos["mensaje"] = "<strong>".strtoupper($_REQUEST['semestreCursado'])."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                echo "</tr>";
                echo "<tr>"; 
                    echo"<td width='50%'>";
                     //Motivo de la transferencia
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[23]['preg_nombre'].":";
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
                    $atributos["mensaje"] = "<strong>".$_REQUEST['motivoTransferencia']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo "</td>";
                echo "</tr>";
                echo "</table>";

                echo $this->miFormulario->marcoAGrupacion("fin");

                //LUGAR Y FECHA DE NACIMIENTO
                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[2]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                echo "<table border='0' width='100%'>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Pais
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    //$atributos ["linea"]=true;
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[3]['preg_nombre'].":";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                    echo"<td>";
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    //$atributos ["linea"]=true;
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] ="<strong>".$_REQUEST['pais']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Departamento
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[4]['preg_nombre'].": ";
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
                    $atributos["mensaje"] ="<strong>".$departamento."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Municipio
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[5]['preg_nombre'].": ";
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
                    $atributos["mensaje"] = " <strong>".$municipio."</strong>";
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
                    $atributos["mensaje"] = $registroPreguntas[6]['preg_nombre'].": ";
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
                    $atributos["mensaje"] = " <strong>".$_REQUEST['fechaNac']."</strong>";
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
                    $atributos["mensaje"] = $registroPreguntas[7]['preg_nombre'].": ";
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
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Estado civil
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[8]['preg_nombre'].": ";
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
                    $atributos["mensaje"] = " <strong>".$estadoCivil."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Dirección de residencia
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
                    //Dirección de residencia
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = " <strong>".$_REQUEST['direccionResidencia']."</strong>";
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
                    $atributos["mensaje"] = $registroPreguntas[10]['preg_nombre'].":";
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
                    $atributos["mensaje"] = $registroPreguntas[11]['preg_nombre'].": ";
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
                    $atributos["mensaje"] = " <strong>".$_REQUEST['telefono']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
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
                    //Correo electrónico
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = " <strong>".$_REQUEST['email']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "</table>";

                echo $this->miFormulario->marcoAGrupacion("fin");

                //DOCUMENTO DE INDENTIDAD Y GRUPO SANGUINEO
                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[4]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                echo "<table border='0' width='100%'>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Tipo de documento actual
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[14]['preg_nombre']." actual:";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                    echo "<td width='50%'>";
                    //Tipo de documento actual
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] ="<strong>".$tipoDocumento."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Número de documento actual
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[15]['preg_nombre'].": ";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                    echo "<td width='50%'>";
                    //Número de documento actual
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$_REQUEST['documentoActual']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Tipo de documento presento ICFES
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[14]['preg_nombre']." con el que presentó el ICFES o SABER 11:";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                    echo "<td width='50%'>";
                    //Tipo de documento presento ICFES
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$tipoDocumentoIcfes."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Número de documento ICFES
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[16]['preg_nombre'].":";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                    echo "<td width='50%'>";
                   //Número de documento ICFES
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$_REQUEST['documentoIcfes']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //Tipo de sangre
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[17]['preg_nombre'].": ";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                    echo "<td width='50%'>";
                   //Tipo de sangre
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$_REQUEST['tipoSangre']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "<tr>";        
                    echo "<td width='50%'>";
                    //RH
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = $registroPreguntas[18]['preg_nombre'].": ";
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
                    $atributos["mensaje"] = " <strong>".$rh."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                    echo"</td>";
                echo "</tr>";
                echo "</table>";

                echo $this->miFormulario->marcoAGrupacion("fin");

                //REGISTRO ICFES Y COLEGIO
                $atributos["id"] = "marcoAgrupacionFechas";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = stripslashes(html_entity_decode($registroEncabezados[5]['enc_nombre']));
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);

                echo "<table border='0' width='100%'>";
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
                    $atributos["mensaje"] = "<strong>".$registroIcfes."</strong>";
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
                    //Observaciones
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "Observaciones :";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);  
                    echo"</td>";
                    echo "<td width='50%'>";
                    //Observaciones
                    $esteCampo = "mensaje";
                    $atributos["id"] = "mensaje"; 
                    $atributos["etiqueta"] = "";
                    $atributos["estilo"] = "campoCuadroTexto";
                    $atributos ["tamanno"]="pequenno";
                    $atributos["tipo"] = $tipo;
                    $atributos["mensaje"] = "<strong>".$_REQUEST['observaciones']."</strong>";
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);  
                    echo"</td>";
                echo "</tr>";
                echo "</table>";

                echo $this->miFormulario->marcoAGrupacion("fin");

                //Mensaje que cuenta de manera regressiva el número de caracteres digitados en el campo textarea 
                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; 
                $atributos["etiqueta"] = "";
                $atributos ["tamanno"]="pequenno";
                $atributos["estilo"] = "campoCuadroTexto";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = 'Los datos consignados en el formulario serán guardados bajo la gravedad del juramento, y en el momento de guardar y enviar la información equivale a la firma de la inscripción.<br><br> <center>¿ESTÁ SEGURO DE GUARDAR ESTA INFORMACIÓN?</center></span>';
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);

             }
             else
             {  
                //-------------Control Mensaje-----------------------
                $tipo = 'message';
                $mensaje = "<H3><center>FORMULARIO DE INSCRIPCIÓN PARA REINGRESO O TRANSFERENCIA INTERNA ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</p>
                            CARRERA A LA QUE SE INSCRIBE:  ".$_REQUEST['carreras']." - ".$registroCarreras[0][1]." </center></H3>";

                $esteCampo = "mensaje";
                $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                $atributos["etiqueta"] = "";
                $atributos["estilo"] = "centrar";
                $atributos["tipo"] = $tipo;
                $atributos["mensaje"] = $mensaje;
                echo $this->miFormulario->cuadroMensaje($atributos);
                unset($atributos);
                 
                //$valorCodificado="pagina=habilitarEvaluacion";
                $valorCodificado="&action=".$esteBloque["nombre"];
                $valorCodificado.="&opcion=guardarInscripcion";
                $valorCodificado.="&usuario=".$_REQUEST['usuario'];
                $valorCodificado.="&tipo=".$_REQUEST['tipo'];
                $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
                $valorCodificado.="&id_periodo=".$variable['id_periodo'];
                $valorCodificado.="&anio=".$variable['anio'];
                $valorCodificado.="&evento=".$_REQUEST['evento'];
                $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                $valorCodificado.="&periodo=".$variable['periodo'];
                $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
                
             }    
                //------------------Division para los botones-------------------------
                $atributos["id"]="botones";
                $atributos["estilo"]="marcoBotones";
                echo $this->miFormulario->division("inicio",$atributos);
               //-------------Control Boton-----------------------
                $esteCampo = "botonGuardar";
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
               // $atributos["tipoSubmit"] = "jquery";
                //$atributos["onclick"]=true;
                $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
                echo $this->miFormulario->campoBoton($atributos);
                unset($atributos);
                //-------------Fin Control Boton----------------------

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

                //-------------Fin de Conjunto de Controles----------------------------
                echo $this->miFormulario->marcoAgrupacion("fin");

                //------------------Fin Division para los botones-------------------------
                echo $this->miFormulario->division("fin");

                //$tab = 2;


                //echo $this->miFormulario->division("fin");
            
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
