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

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($registro)
{	
    echo "<table id='tablaInscripciones'>";

    echo "<thead>
            <tr>
                <th>Año</th>
                <th>Periodo</th>
                <th>Credencial</th>
                <th>Tipo Inscripción</th>
                <th>Nombre </th>
                <th>Apellido </th>
                <th>No. Identificación</th>
                <th>Cod. carrera</th>
                <th>No. SNP o ICFES</th>
           </tr>
        </thead>
        <tbody>";

    /*for($i=0;$i<count($registro);$i++)
    {
        echo "<tr>
                <td align='center'>".$registro[$i][0]."</td>
                <td align='center'>".$registro[$i][1]."</td>
                <td align='center'>".$registro[$i][2]."</td>
                <td align='center'>".$registro[$i][3]."</td>
                <td align='center'>".$registro[$i][4]."</td>    
                <td align='center'>".$registro[$i][5]."</td>
                <td align='center'>".$registro[$i][6]."</td>
                <td align='center'>".$registro[$i][7]."</td>    
            </tr>";
    }*/

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

//echo $this->miFormulario->division("fin");




