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

    echo "Este se considera un error fatal";
    exit;
}

$conexion1="admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
if (!$esteRecursoDB1) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

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
    $variable['carreras']=9002; 
    $cadena_sql = $this->sql->cadena_sql("buscarContenidoColilla", $variable);
    $registroContenido = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $valorCodificado="pagina=administracion";
    $valorCodificado.="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardar";
    if(isset($_REQUEST['usuario']))
    {    
        $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    }
    if(isset($_REQUEST['tipo']))
    {    
        $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    }
    if(isset($_REQUEST['rba_id']))
    {    
        $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
    }
    
    $variable['credencial']=$_REQUEST['credencial'];
    
    $cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $snpIcfes=substr($registro[0]['asp_snp'],2,5);
    
    $variable['carrera']=$registro[0]['asp_cra_cod'];
    $cadena_sql = $this->sql->cadena_sql("consultarCarreras", $variable);
    $registroCarreras = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
    
    $variable['tipoInscripcion']=$registro[0]['ti_id'];
    $cadena_sql = $this->sql->cadena_sql("tiposInscripcion", $variable);
    $registroTipIns = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    if($registro[0]['asp_admitido'] == 'A')
    {
        $estadoAdmision = '<font color="#009900" size="3"><b>ADMITIDO</b></font>';
    }
    elseif($registro[0]['asp_admitido']== 'O')
    {
        $estadoAdmision = '<font color="#FB9524" size="3"><b>OPCIONADO</b></font>';
    }
    elseif($registro[0]['asp_admitido']== 'N')
    {
        $estadoAdmision='<font color="#FF0000" size="2"><b>NO ADMITIDO</b></font>';
    }
    else
    {
        $estadoAdmision='';
    }
    
    
    if($registro[0]['asp_tip_icfes']=='A')
    {
        $tipoIcfes='ANTIGUO';
    }
    else
    {
        $tipoIcfes='NUEVO';
    }
    
    
    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionFechas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = " ";
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
    $tipo = 'message';
    $mensaje = "<center><h3>RESULTADOS ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</h3></center><br>";
    $mensaje.="<table width='100%' border='1'>";
        $mensaje.="<tr>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="CREDENCIAL:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$registro[0]['rba_asp_cred'];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="NOMBRE:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$registro[0]['asp_nombre']." ".$registro[0]['asp_apellido'];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="COD. CARRERA";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$registro[0]['asp_cra_cod'];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="CARRERA:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$registroCarreras[0][1];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="AÑO:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$variable['anio'];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="PERIODO:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$periodo;
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="TIPO DE INSCRIPCION:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$registroTipIns[0]['nombre'];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="TIPO DE ICFES:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$tipoIcfes;
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="PUESTO:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$registro[0]['asp_puesto'];
            $mensaje.="</td>";
        $mensaje.="</tr>";
        $mensaje.="<tr>";    
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.="ESTADO ADMISION:";
            $mensaje.="</td>";
            $mensaje.="<td style='font-size:10pt;'>";
            $mensaje.=$estadoAdmision;
            $mensaje.="</td>";
        $mensaje.="</tr>";
    $mensaje.="</table>";
    
    $mensaje.="<br>".$registroContenido[0]['colilla_contenido'];
    
    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    $atributos["id"] = "marcoAgrupacionFechas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "RESULTADOS DEL ICFES";
    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
    unset($atributos);
    
    echo "<table id='tablaArchivosResultados'>";
    echo "<thead>
            <tr>";
                echo "<th>";
                echo "Registro ICFES";
                echo"</th>";
                echo "<th>";
                echo $registro[0]['asp_snp'];
                echo"</th>
           </tr>
        </thead>
        <tbody>";
    if($registro[0]['asp_bio'] != '')
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
                    $atributos["mensaje"] = "Biología:";
                }
                else 
                {
                    $atributos["mensaje"] ="Ciencias Naturales";
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
                $atributos["mensaje"] = $registro[0]['asp_bio'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_qui'] != '')
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
                $atributos["mensaje"] = "Química:";
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
                $atributos["mensaje"] = $registro[0]['asp_qui'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_fis'] != '')
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
                $atributos["mensaje"] = "Física: ";
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
                $atributos["mensaje"] = $registro[0]['asp_fis'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_soc'] != '')
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
                    $atributos["mensaje"] = "Sociales: ";
                }
                else 
                {
                    $atributos["mensaje"] = "Ciencias Ciudadanas";
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
                $atributos["mensaje"] = $registro[0]['asp_soc'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_apt_verbal'] != '')
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
                $atributos["mensaje"] = "Aptitud verbal:";
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
                $atributos["mensaje"] =$registro[0]['asp_apt_verbal'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_esp_y_lit'] != '')
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
                    $atributos["mensaje"] = "Español y literatura: ";
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
                $atributos["mensaje"] = $registro[0]['asp_esp_y_lit'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_apt_mat'] != '')
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
                    $atributos["mensaje"] = "Aptitud matemática:";
                }
                else 
                {
                    $atributos["mensaje"] = "Razonamiento Cuantitativo";
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
                $atributos["mensaje"] = $registro[0]['asp_apt_mat'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_con_mat'] != '')
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
                    $atributos["mensaje"] = "Conocimiento matemático:";
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
                $atributos["mensaje"] = $registro[0]['asp_con_mat'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_fil'] != '')
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
                $atributos["mensaje"] = "Filosofía: ";
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
                $atributos["mensaje"] = $registro[0]['asp_fil'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_his'] != '')
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
                $atributos["mensaje"] = "Historia: ";
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
                $atributos["mensaje"] = $registro[0]['asp_his'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_geo'] != '')
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
                $atributos["mensaje"] = "Geografía:";
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
                $atributos["mensaje"] = $registro[0]['asp_geo'];
                echo $this->miFormulario->campoMensaje($atributos);
                unset($atributos);
                echo"</td>";
            echo "</tr>";
    }
    if($registro[0]['asp_idioma'] != '')
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
                $atributos["mensaje"] = "Idioma:";
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
            $atributos["mensaje"] = $registro[0]['asp_idioma'];
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);
            echo"</td>";
        echo "</tr>";
    }
    if($registro[0]['asp_interdis'] != '')
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
                $atributos["mensaje"] = "Interdisciplinaria: ";
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
            $atributos["mensaje"] = $registro[0]['asp_interdis'];
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);  
            echo"</td>";
        echo "</tr>";
    }
    if($registro[0]['asp_cie_soc'] != '')
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
            $atributos["mensaje"] = "Ciencias sociales: ";
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
            $atributos["mensaje"] = $registro[0]['asp_cie_soc'];
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);    
            echo"</td>";
        echo "</tr>";
    }
    echo "<tbody>";
    echo "</table>";
    echo $this->miFormulario->marcoAGrupacion("fin");
}




