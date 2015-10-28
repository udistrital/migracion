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

$conexion1 = "admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoDB1) {

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

if(isset($_REQUEST['consulta']))
{
    $variable['consulta']=$_REQUEST['consulta'];
}    
else
{
    $variable['consulta']=1;
}
$valorCodificado="pagina=administracion";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=consultarInscritosFacultades";
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
$atributos["leyenda"] = "Consulta Referencia Bancaria";
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

$tipo = 'message';
$mensaje = "<center><h3>CONSULTA DE INSCRIPCIONES POR FACULTAD 
    <br>".$periodo." PERIODO ACADÉMICO ".$variable['anio']."<br></h3></center>
            Los campos con * son obligatorios.";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

//------------------Control Lista Desplegable------------------------------
$esteCampo = "facultades";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = 1;
$atributos["evento"] = 2;
$atributos["columnas"] = "1";
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["ancho"] = "225px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 250;
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Seleccione la facultad: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("facultades");
$atributos["baseDatos"] = "admisionesAdmin";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

    
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);   

$esteCampo = "botonConsultar";
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
//-------------Fin Control Boton----------------------

echo $this->miFormulario->division("fin");  

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

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

echo "Este se considera un error fatal";
exit;
}


if(isset($_REQUEST['facultad'])){
    $variable['facultad']=$_REQUEST['facultad'];
}else{
    $variable['facultad']=0;
}

$cadena_sql = $this->sql->cadena_sql("facultades", $variable);
$registroFacultad = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultaCarrerasxFacultad", $variable);
$registroCarrerasFac = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
$codCra=0;
for($j=0; $j<=count($registroCarrerasFac)-1; $j++)
{
    $codCra=$codCra.",".$registroCarrerasFac[$j][0];
}
$carrera=explode(",",$codCra);
$cadena_sql = $this->sql->cadena_sql("cuentaInscritos", $variable);
$registroInscritos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(isset($_REQUEST['facultad']))
{
    if($registroInscritos)
    {
        echo "<hr><center>TOTAL INSCRITOS ".$registroFacultad[0][1]."</center><hr/>";
        echo "<table id='tablaNoInscritos'>";
        echo "<thead>
                <tr>
                    <th>Cod. Cra</th>
                    <th>Nombre Cra</th>
                    <th>No. inscritos</th>
                    <th>Tipo inscripción</th>
               </tr>
            </thead>
            <tbody>";
        for($i=0;$i<count($registroInscritos)-1;$i++)
        {
            if(in_array($registroInscritos[$i]['cod_cra'],$carrera))
            {
                $variable['codCra']=$registroInscritos[$i]['cod_cra'];
                $cadena_sql = $this->sql->cadena_sql("consultarCodCarrera", $variable);
                $nombreCra = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
                echo "<tr>
                    <td align='center'>".$registroInscritos[$i]['cod_cra']."</td>
                    <td align='center'>".$nombreCra[0][1]."</td>    
                    <td align='center'>".$registroInscritos[$i]['total']."</td>
                   <td align='center'>".$registroInscritos[$i]['tipo_inscripcion']."</td>
                </tr>";
            }
        }
        echo "</tbody>";
        echo "</table>";
        echo "<hr><center>TOTAL INSCRITOS</center><hr/>";
        
        $cadena_sql = $this->sql->cadena_sql("cuentaAspirantes", $variable);
        $registroAspirantes = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
       
        $cadena_sql = $this->sql->cadena_sql("cuaentaTransExt", $variable);
        $registroTransExt = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $cadena_sql = $this->sql->cadena_sql("cuaentaReingTransInt", $variable);
        $registroReingreso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $total=$registroAspirantes[0]['totalaspirantes']+$registroTransExt[0]['totaltransferenciaext']+$registroReingreso[0]['totalreingreso']; 
        
        echo "<table id='tablaTotalInscritos'>";
        echo "<thead>
                <tr>
                    <th>Tipo Inscripción</th>
                    <th>Total</th>
                </tr>
            </thead>";
        echo "<tbody>";
            echo "<tr>";
                echo "<td align='center'>Aspirantes</td>";
                echo "<td align='center'>".$registroAspirantes[0]['totalaspirantes']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Transferencia externa</td>";
                echo "<td align='center'>".$registroTransExt[0]['totaltransferenciaext']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Transferencia interna y reingresos</td>";
                echo "<td align='center'>".$registroReingreso[0]['totalreingreso']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Total</td>";
                echo "<td align='center'>".$total."</td>";
            echo "</tr>";
        echo "</tbody>";
        echo "</table>";

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
}