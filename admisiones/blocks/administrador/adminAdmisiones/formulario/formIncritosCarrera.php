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


$variable['codcra']=$_REQUEST['codcra'];

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=administracion";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=consultarInscripcionCarrera";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&id_periodo=".$variable['id_periodo'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
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
$atributos["leyenda"] = "Inscritos Proyecto Curricular ".$registro[0]["asp_cra_cod"];
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

if($registro)
{
    //-------------Control Mensaje-----------------------
    $tipo = 'message';
    $mensaje = "<b>".count($registro)."</b> aspirantes inscritos, periodo académico ".$variable['anio']." - ".$variable['periodo'].",  para la carrera ".$registro[0]["asp_cra_cod"]."";


    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    echo "<table width='100%'>";

    echo "<thead>
            <tr>
                <th>Credencial</th>
                <th>Tipo Inscripción</th>
                <th>Nombre Apellido</th>
                <th>SNP</th>
                <th>Localidad residencia</th>
                <th>Localidad colegio</th>
                <th>Estrato</th>
           </tr>
        </thead>
        <tbody>";

    for($i=0;$i<count($registro);$i++)
    {
        $variable['Idacasp']=$registro[$i]['asp_id'];
        $variable['id']=$i;
        echo "<tr>
            <td align='center'>".$registro[$i]["rba_asp_cred"]."</td>
            <td align='center'>".$registro[$i]["ti_nombre"]."</td>
            <td align='center'>".$registro[$i]["asp_nombre"]." ".$registro[$i]["asp_apellido"] ."</td>
            <td align='center'>".$registro[$i]["asp_snp"]."</td>
            <td align='center'>";
                $esteCampo = "localidadRes".$i;
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "75px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = false;
                $atributos["validar"] = "";
                $atributos["anchoEtiqueta"] = 10;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] = " ";
                $atributos["seleccion"] = $registro[$i]["asp_localidad"]."-".$variable['Idacasp']."-".$variable['id'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);
            echo "</td>
            <td align='center'>";
                $esteCampo = "localidadCol".$i;
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "75px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = false;
                $atributos["validar"] = "";
                $atributos["anchoEtiqueta"] = 10;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] = " ";
                $atributos["seleccion"] = $registro[$i]["asp_localidad_colegio"]."-".$variable['Idacasp']."-".$variable['id'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("localidad",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);
            echo"</td>
            <td align='center'>";
                $esteCampo = "estrato".$i;
                $atributos["id"] = $esteCampo;
                $atributos["tabIndex"] = $tab++;
                $atributos["seleccion"] = 1;
                $atributos["evento"] = 2;
                $atributos["columnas"] = "1";
                $atributos["limitar"] = false;
                $atributos["tamanno"] = 1;
                $atributos["ancho"] = "85px";
                $atributos["estilo"] = "jqueryui";
                $atributos["etiquetaObligatorio"] = false;
                $atributos["validar"] = "";
                $atributos["anchoEtiqueta"] = 10;
                $atributos["obligatorio"] = true;
                $atributos["etiqueta"] = " ";
                $atributos["seleccion"] = $registro[$i]["asp_estrato"]."-".$variable['Idacasp']."-".$variable['id'];
                //-----De donde rescatar los datos ---------
                $atributos["cadena_sql"] = $this->sql->cadena_sql("estrato",$variable);
                $atributos["baseDatos"] = "admisiones";
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);
            echo "</td>
        </tr>";
    }

    echo "</tbody>";

    echo "</table>";
    
    echo $this->miFormulario->formulario("fin");
echo $this->miFormulario->marcoAGrupacion("fin");
echo $this->miFormulario->division("fin");

}else
{
    $atributos["id"]="divNoEncontroRegistro";
    $atributos["estilo"]="marcoBotones";
    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio",$atributos);

    //-------------Control Boton-----------------------
    $esteCampo = "noEncontroRegistro";
    $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = 'error';
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
    echo $this->miFormulario->cuadroMensaje($atributos);
     unset($atributos); 
    //-------------Fin Control Boton----------------------

    //------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");
}

//echo $this->miFormulario->division("fin");




