<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */


$conexion="icetex";


$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}

//procesa mover el archivo

$this->moverArchivo();

//Obtiene array de codigos y valor individual para la resolucion

$this->procesarExcelCodigos();

//asigna valores

$parametros = array();
$parametros["archivo"] = $this->rutaArchivo;
$parametros["resolucion"] = $_REQUEST['resolucion'];
$parametros["valorTotal"] = $_REQUEST['valorTotal'];
$parametros["periodo"] = $_REQUEST['periodo'];
$parametros["anio"] = substr($_REQUEST['periodo'], 0, 4);
$parametros["per"] = substr($_REQUEST['periodo'], 5, 1);

$listaError = array();
$listaExito = array();

$errorUpdate = false;
$errorInsert = false;
$bandera =0;
//procesa el listado registra la resolucion para cada uno del listado
foreach ($this->listadoCodigos as $lista){
	
		
		if(isset($lista[0])) {
			$parametros["codigo"] = $lista[0];
			$_REQUEST["codigo"] = $lista[0];
		}		else{
			$parametros["codigo"] =0;
			$_REQUEST["codigo"] = 0;
		}
		if(isset($lista[1])) {
			$parametros["valorIndividual"] = $lista[1];
			$_REQUEST['valorIndividual'] = $lista[1];
		}else {
			$parametros["valorIndividual"]=0;
			$_REQUEST['valorIndividual'] = 0;
		}
		$parametros['aprobado'] = 'S';
		
		
		//Se agrega el cambio ya que no se habia tenido en cuenta que para hacer la solicitud el 
		//credito debe encontrarse aprobado
		//Insert
		//aprueba credito
		$cadena_sql = $this->sql->cadena_sql("aprobarCredito",$parametros);
		$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);
		
		
		if($registros!=false){
			$errorInsert = true ;
		}
		
		//Update
		//Reegistra en la resolucion
		$cadena_sql = $this->sql->cadena_sql("registroResolucion",$parametros);
		$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);
		
		

		if($registros!=false){
			$errorUpdate = true;
		}
		
		$tema = "Estado aprobación Crédito ICETEX";
		
		
		$cuerpo = "Buenos Días, <br><br><br>";
		$cuerpo .="<br>Le informamos que el giro del ICETEX a su nombre ha llegado, por lo anterior debe acercarse a realizar el tramite correspondiente dependiendo de cada caso.";
		$cuerpo .="<br><br>1. SI EL ESTUDIANTE NO HA CANCELADO A LA FECHA LA MATRICULA: para la legalización de la matricula en el proyecto curricular debe realizar la solicitud del certificado de desembolso en tesorería (7 piso)   y entregar los siguientes documentos:";
		$cuerpo .="<br>   *. Carta de solicitud de certificado de desembolso dirigida a tesorería general.";
		$cuerpo .="<br>   *. Recibo de pago 2014-1 ";
		$cuerpo .="<br>   *. Resolución del Icetex la cual se entrega en Bienestar Institucional ";
		$cuerpo .="<br>   *. Consignación Banco de Occidente, cuenta de ahorros No 23081461-8 cod 41 por valor de 5.100. ";
		$cuerpo .="<br><br><br>2. SI EL ESTUDIANTE YA CANCELO A LA FECHA LA MATRICULA: Para el reintegro del dinero debe radicar en Bienestar Institucional los siguientes documentos:";
		$cuerpo .="<br>   *. Carta de Solicitud dirigida al Ing. Jorge Federico Ramirez Escobar Dir de Bienestar Institucional. ";
		$cuerpo .="<br>   *. Dos (2) Fotocopias del documento de identidad. ";
		$cuerpo .="<br>   *. Fotocopia del recibo cancelado que se note el sello del Banco. ";
		$cuerpo .="<br><br><br><br>Cualquier duda o inquietud pueden comunicarse.";
		$cuerpo .="<br><br>Cordialmente,";
		$cuerpo .="<br>SONIA YANQUEN M.<br>Bienestar Institucional";
		$cuerpo .="<br>Horario de atención:<br>Lunes a Viernes 09:00 am a 12:30 pm y 02:00 pm a 05:00 pm.";
		
		
		//Aviso de prueba
		//$cuerpo .="<h1>SI RECIBE ESTE CORREO POR FAVOR OBVIELO ES UNA PRUEBA</h1>";
		
		$this->notificarEstudiante($cuerpo, $tema , $this->rutaArchivo);
		
		$_REQUEST["valorConsulta"] = $_REQUEST["codigo"];
		
		
		
		$this->actualizarEstadoFlujo();
		
		if($errorUpdate==false&&$errorInsert==false){
                        $listaExito[$bandera]['CODIGO']=$parametros["codigo"];

                        //1. se consulta la suma de las matriculas ordinarias y extra ordinarias+
                $cadena_sqlS = $this->sql->cadena_sql("consultarValorMatricula",$parametros);
                $registrosS = $esteRecursoDB->ejecutarAcceso($cadena_sqlS,"busqueda");

                $valorOrdinaria = $registrosS[0][0] ;
                //$this->valorExtraOrdinaria = $registrosS[0][1] ;  
                        $diferencia=$valorOrdinaria - $parametros['valorIndividual'];
                        if($diferencia!=0){
                            $listaExito[$bandera]['DIFERENCIA']=$diferencia;
                        }
                        $bandera++;
		}else{
			$arrayp = array();
			if($errorInsert!=false) array_push($arrayp,array($parametros["codigo"],'Insertar'));
			if($errorUpdate!=false) array_push($arrayp,array($parametros["codigo"],'Actualizar'));
			array_push($listaError,$arrayp );
				
		}


		$errorUpdate = false;
		$errorInsert = false;
}

//Exitoso
if(count($listaExito)>0){
	echo '<div style="text-align: center;color:green"><p><b>';
	echo $this->lenguaje->getCadena("exitoRegistroResolucion")." <br>";
        echo '<table style="margin: 0 auto;">';
	foreach($listaExito as $li){
		echo '<tr><td>'.$li['CODIGO'].'</td>';
                if(isset($li['DIFERENCIA'])?$li['DIFERENCIA']:'') {
                    echo "<td> - Presenta diferencia ";
                    if($li['DIFERENCIA']>0){echo "FALTANTE $".$li['DIFERENCIA'].'</td>';}else {echo "EXCEDENTE $".($li['DIFERENCIA']*-1).'</td>';;}    
                    
                }else{
                    echo '<td>&nbsp;</td>';
                }
		echo "</tr>";
	}
	echo "</table></b></p></div><br>";
}
			
			
//Error
if(count($listaError)>0){
	echo '<div style="text-align: center;color:red"><p><b>';
	echo $this->lenguaje->getCadena("errorRegistroResolucion")." <br>";
	foreach($listaError as $li){
		echo $li[0][0]." - ".$li[0][1]."<br>";
	}
	echo "</b></p></div><br><br>";
}
$this->notificarTesoreria($listaExito);

exit;






