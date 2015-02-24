<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/armarFormulariosEvaldocente/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioEvaldocente/";
//$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['carrera']=$_REQUEST['carrera'];
$variable['docente']=$_REQUEST['docente'];
$variable['tipoEvaluacion']=$_REQUEST['tipoEvaluacion'];

$cadena_sql = $this->sql->cadena_sql("observacionesDocPorEst", $variable);
@$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$conexion1 = "autoevaluadoc";
$esteRecursoBDORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoBDORA) {

    echo "Este se considera un error fatal";
    exit;
}


//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = "<center>OBSERVACIONES DE REALIZADAS A LOS DOCENTES POR LOS ESTUDIANTES</center>";


$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

$variablaVacia=0;
for($i=0; $i<=count($registro)-1; $i++)
{
    if(!is_array($registro))
    {
        $variablaVacia=$variablaVacia.",". $registro[$i][0];
        $variable['vacia']=$variablaVacia;
    }
    else
    {
        $variablaVacia=$variablaVacia.",". $registro[$i][0];
        $variable['vacia']=$variablaVacia;
    }
}
/*
$cadena_sql = $this->sql->cadena_sql("datosObservaciones", "");
$registroDocentesNoEvaluados = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
echo $cadena_sql."<br>";*/
if($registro)
{	
        echo "<table id='tablaDocenteSinEvaluar'>";
        
        echo "<thead>
                <tr>
                    <th>Id</th>
                    <th>No. Identificación</th>
                    <th>Nombre</th>
                    <th>Observación</th>
                    <th>Cod. Carrera</th>
                    <th>Carrera</th>
                    <th>Cod. Facultad</th>
                    <th>Facultad</th>
                    <th>Cod. Curso</th>
                    <th>Curso</th>
                    <th>Grupo</th>
                    <th>Año</th>
                    <th>Periodo</th>
               </tr>
            </thead>
            <tbody>";
            
        for($j=0; $j<=count($registro)-1; $j++)
        {
            $variable['docente']=$registro[$j][1];
            $cadena_sql = $this->sql->cadena_sql("datosDocente", $variable);
            $registroDocentes=$esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
            
            $variable['carrera']=$registro[$j][5];
            $cadena_sql = $this->sql->cadena_sql("datosCarrera", $variable);
            $registroCarrera=$esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
            
            $variable['curso']=$registro[$j][6];
            $cadena_sql = $this->sql->cadena_sql("datosCurso", $variable);
            $registroCurso=$esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
            if($registro[$j][8]!='')
            {    
            echo "<tr>
                    <td align='center'>".$registro[$j][0]."</td>
                    <td align='center'>".$registro[$j][1]."</td>
                    <td>".$registroDocentes[0][1]."</td>
                    <td align='center'>".$registro[$j][8]."</td>    
                    <td align='center'>".$registro[$j][5]."</td>
                    <td>".$registroCarrera[0][1]."</td>
                    <td align='center'>".$registroCarrera[0][2]."</td>
                    <td>".$registroCarrera[0][3]."</td>
                    <td align='center'>".$registro[$j][6]."</td>
                    <td align='center'>".$registroCurso[0][1]."</td>
                    <td align='center'>".$registro[$j][7]."</td>
                    <td align='center'>".$registro[$j][11]."</td>
                    <td align='center'>".$registro[$j][12]."</td>    
                </tr>";
            }
        }
               
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

echo $this->miFormulario->division("fin");







