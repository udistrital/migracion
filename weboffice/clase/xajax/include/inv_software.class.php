<?php
function recarga($nameform,$variable=""){

			require_once("clase/encriptar.class.php");
			require_once("clase/config.class.php");
			$cripto=new encriptar();
			$esta_configuracion=new config();
			$configuracion=$esta_configuracion->variable();
			
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$mi_variable="pagina=".$nameform; ///Se encuentra registrada en backoffice_pagina
			$mi_variable.="&accion=1";
			$mi_variable.="&hoja=1";
			$mi_variable.="&opcion=lista";
			$mi_variable.="&esp=".$variable;
			$mi_variable.="&xajax=recarga";			

			$mi_variable=$cripto->codificar_url($mi_variable,$configuracion);			
	
	
						//
						
							//echo "<script>location.replace('".$indice.$variable."')</script>"; 

			$respuesta = new xajaxResponse();
			$respuesta->addScript("location.replace('".$indice.$mi_variable."');");
			$respuesta->addScript("document.getElementById(\'esp\').value=$variable;");
		return $respuesta;
}

function valida($requeridos,$longitud,$estructura,$form,$nameform){
		
		$respuesta = new xajaxResponse();
		$salida="";
		//$salida.="HOLA HOLA".print_r($form).">>";
		//$salida.=$requeridos;
		
		$requeridos = explode("@",$requeridos);
		$longitud = explode("@",$longitud);
		$estructura = explode("@",$estructura);
		array_pop($requeridos);
		
		
		//$tam=count($req);
		$m=0;
		unset($form['action']);
		unset($form['aceptar']);
		
		foreach($requeridos as $campo){
					
						
						
						if($form[$campo]==''){
							//$salida.=$campo."= ".$form[$campo]."<br>";
							//$salida.="<br>".$form[$campo].">";
							$m++;
							$salida ="<br>..::  Faltan $m campos Obligatorios  ::.. ";
							$respuesta->addAssign($campo,"className","errorRequerido");
							$respuesta->addAssign("mensaje","innerHTML",$salida);
							
						}
						else{
							$respuesta->addAssign($campo,"className","txt");
							//$salida.=":P";
							//$respuesta='true';
						}
		}
			//***********************************************************************************************************************//
			//									VALIDACION CAMPOS INVALIDOS										//
			//***********************************************************************************************************************//			
			
		/*	
			foreach($form as $campo=>$valor){
			
				if(eregi('[%í&ó+$/"=?^_|~`é<>ºó¿(¡!á]',$valor)){
				
					$salida ="<br>..::  Existen Caracteres Invalidas  ::.. ";
					$respuesta->addAssign($campo,"className","error2");
					$m++;
					
				}
				else{*/
				
					//***********************************************************************************************************************//
					//									VALIDACION CAMPOS REQUERIDOS										//
					//************************************************************************************************************************//				
					/*foreach($requeridos as $campo){
					
						
						
						if($form[$campo]==''){
						$salida.=$campo."= ".$form[$campo]."<br>";
							//$salida.="<br>".$form[$campo].">";
							$m++;
							$salida.="<br>..::  Faltan $m campos Obligatorios  ::.. ";
							$respuesta->addAssign($campo,"className","errorRequerido");
							$respuesta->addAssign("mensaje","innerHTML",$salida);
							
						}
						else{
							$respuesta->addAssign($campo,"className","txt");
							//$salida.=":P";
							//$respuesta='true';
						}
					}
				}
			}*/
					



		//***********************************************************************************************************************//
		//											VALIDACION ESTRUCTURA										//
		//************************************************************************************************************************//			
			/*if($m<>0){			
			foreach($estructura as $campo){
				if(!ereg("^[^@]{1,64}@[^@]{1,255}$", $form[$campo])){
					$salida="<br>..::  No cumple la estructura  ::.. ";
					$respuesta->addAssign($campo,"className","error2");
					$respuesta->addAssign("mensaje","innerHTML",$salida);
					$m++;					
				}
				else{
					$respuesta->addAssign($campo2,"className","txt");
					
					//$respuesta='true';
				}
			}				
			}*/
			
		//***********************************************************************************************************************//
		//																FIN										//
		//************************************************************************************************************************//			
			
			if($m==0){
				$salida="";
				$respuesta->addScript("document.forms['$nameform'].submit();");
			}
			
			//$salida.="hola";
			$respuesta->addAssign("mensaje","innerHTML",$salida);
			return $respuesta;
		
		}

		
		
	












function datos_basicos($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();
	
	$valor=$acceso_db->verificar_variables($valor);

	if (is_resource($enlace))
	{
		
		$cadena_sql="SELECT ";
		$cadena_sql.="* ";
		$cadena_sql.="FROM ";
		$cadena_sql.=$configuracion["prefijo"]."estudiante ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="codigo_est='".$valor."' ";
		$cadena_sql.="LIMIT 1";
		
		$conteo=$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		if($conteo>0)
		{
			$cadena_html="<table class='bloquelateral' width='100%'>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Nombre: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=$registro[0][2];
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Identificaci&oacute;n: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=$registro[0][1];
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Matr&iacute;cula Base: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=money_format('$%!.0i', $registro[0][4]);
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td>\n";
			$cadena_html.="Matr&iacute;cula Reliquidada: ";
			$cadena_html.="</td>\n";
			$cadena_html.="<td>\n";
			$cadena_html.=money_format('$%!.0i', $registro[0][5]);
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="</table>\n";
			$respuesta = new xajaxResponse();
			$respuesta->addAssign("registro","innerHTML",$cadena_html);
			
			
			unset($registro);
			unset($conteo);
			
			$cadena_sql="SELECT ";
			$cadena_sql.="* ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."exencion ";
			$conteo=$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			
			if($conteo>0)
			{
				for($i=0;$i<$conteo;$i++)
				{
					$respuesta->addAssign("exencion_".$registro[$i][0],"checked",false);
					
				}
				unset($registro);
				unset($conteo);
				$cadena_sql="SELECT ";
				$cadena_sql.="`codigo_est`, ";
				$cadena_sql.="`id_programa`, ";
				$cadena_sql.="`id_exencion`, ";
				$cadena_sql.="`anno`, ";
				$cadena_sql.="`periodo`, ";
				$cadena_sql.="`fecha` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="codigo_est='".$valor."' ";
				$conteo=$acceso_db->registro_db($cadena_sql,0);
				$registro=$acceso_db->obtener_registro_db();
				
				if($conteo>0)
				{
					for($i=0;$i<$conteo;$i++)
					{
						$respuesta->addAssign("exencion_".$registro[$i][2],"checked",1);
						
					}	
				}	
			}
			
		}
		else
		{
			$cadena_html="<table class='bloquelateral' width='100%'>\n";
			$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
			$cadena_html.="<td class='centrar'>\n";
			$cadena_html.="<span class='texto_negrita'>El c&oacute;digo del estudiante no se encuentra registrado.</span>";
			$cadena_html.="</td>\n";
			$cadena_html.="</tr>\n";
			$cadena_html.="</table>\n";
			
			
			$respuesta = new xajaxResponse();
			$respuesta->addAssign("registro","innerHTML",$cadena_html);
			
			
		
		}
		//echo $respuesta;
		return $respuesta;
	}
		
	
}



function registro($id){
	
	require_once("clase/forms.class.php");
	$salida	= "id= ".$id;
	

	$esp = new form();
	$esp->setTabla('especialidad');
	$esp->agregarcampo('esp','select','col','Especialidad:',405,1,'');

	
	$salida	.= "<div id='especialidad'>";
	$salida	.= $esp->tohtml();
	
	
	$response = new xajaxResponse();
    $response->assign("especialidad","innerHTML", $salida);
    return $response;
}



function generarConsulta($id,$consulta,$columna,$consultar,$insertar,$editar,$borrar){



	require_once("clase/klassAdmin.php");
	//////////////GENERA CONSULTA POR COLUMNA//////////////////////////////
	
	$salida="";
	
	$salida .="<a id='insertar' $insertar";
	
	$admin=new klassAdmin();
	
    $registros=$admin->sql($consulta);

    if($registros){
    $i=0;
		
		if($columna<>''){

        $salida .= "<table>";
        foreach($registros as $dato){
			$salida .= "<tr><td width='50%'>".$dato[$columna]."</td><td><img id='".$dato[$id]."' $consultar </td><td><img id='".$dato[$id]."' $editar</td><td><img id='".$dato[$id]."' $borrar</td></tr>";
            $i++;
        }
        $salida .= "</table>";
		}
    }   
    else{
   
        $salida.= "Error en la consulta ahhhhhhhhhhhhhhhhhhhhhhhh";
    }
	/////////////////////////////////////////////////////////////////////////////////
	
	$response = new xajaxResponse();
    $response->assign("answer","innerHTML", $salida);
    return $response;
}
?>
