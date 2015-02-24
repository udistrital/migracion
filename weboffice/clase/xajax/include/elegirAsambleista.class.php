<?php

function elegirAsambleista($valor)
{
	require_once("clase/config.class.php");
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
	
	
	$nueva_sesion=new sesiones($configuracion);
	$nueva_sesion->especificar_enlace($enlace);
	$esta_sesion=$nueva_sesion->numero_sesion();
	//Rescatar el valor de la variable usuario de la sesion
	$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
	if($registro)
	{
		
		$usuario=$registro[0][0];
	}
	
	
	$acceso_db=new dbms($configuracion);
	$enlace=$acceso_db->conectar_db();
	
	$valor=$acceso_db->verificar_variables($valor);

	if (is_resource($enlace))
	{
		$cadena_html="";
		$log=$acceso_db->logger($configuracion,$usuario,"Eleccion");
		
		$elegido="false";
		while($elegido=="false")
		{
			//Crear un matriz con los 75 numeros de los candidatos. El primer indice de esta matriz es cero.
		
			$candidatos = range (1,75);
			
			//Desordenar aleatoriamente la matriz		
			for($a=0;$a<150;$a++)
			{
			
				shuffle ($candidatos);
			
			}
			
				
			//Escoger quince numeros aleatorios entre 0 y 74.
			
			for($a=0;$a<15;$a++)
			{
				$matrizPreliminar[$a]=rand(0,74);
			}
			
			
			
			//Escoger un numero aleatorio entre  0 y 14
			$indicePreliminarElegido=rand(0,14);
			
			$indiceElegido=$matrizPreliminar[$indicePreliminarElegido];
			
			$candidatoElegido=$candidatos[$indiceElegido];
			
			$cadena_sql="SELECT ";
			$cadena_sql.="* ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."consultiva ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id='".$candidatoElegido."' ";
			$cadena_sql.="AND ";
			$cadena_sql.="eleccion ='' ";
			$conteo=$acceso_db->registro_db($cadena_sql,0);
			$registro=$acceso_db->obtener_registro_db();
			if($conteo>0)
			{
				$cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."consultiva ";
				$cadena_sql.="SET ";
				$cadena_sql.="eleccion = '".time()."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`id` = ".$candidatoElegido;
				$acceso_db->ejecutar_acceso_db($cadena_sql);
				//echo $cadena_sql;
				
				$i=0;
				$cadena_html.="<table class='formulario'>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td colspan='2' align='center' bgcolor='#FFFFD6'>\n";
				$cadena_html.="<span class='numeroGrande'>".$candidatoElegido."</span>";
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="</table>\n";
				$cadena_html.="<table class='formulario'>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td colspan='2' align='center' bgcolor='#FFFFD6' class='letraGrande'>\n";
				$cadena_html.="<b>".htmlentities($registro[$i][6]." ".$registro[$i][5])."</b>";
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td width='30%'>\n";
				$cadena_html.="Identificaci&oacute;n ";
				$cadena_html.="</td>\n";
				$cadena_html.="<td>\n";
				$cadena_html.=$registro[$i][4];
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td width='30%'>\n";
				$cadena_html.="Estamento ";
				$cadena_html.="</td>\n";
				$cadena_html.="<td width='30%'>\n";
				$cadena_html.="<b>".htmlentities($registro[$i][1])."</b>";
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td width='30%'>\n";
				$cadena_html.="Facultad o Dependencia ";
				$cadena_html.="</td>\n";
				$cadena_html.="<td>\n";
				$cadena_html.=$registro[$i][2];
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td width='30%'>\n";
				$cadena_html.="Movimiento: ";
				$cadena_html.="</td>\n";
				$cadena_html.="<td>\n";
				$cadena_html.=htmlentities($registro[$i][3]);
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="</table>\n";
				$cadena_html.="<hr class='hr_subtitulo'>\n";
				$elegido="verdadero";
						
			}
			else
			{
				$elegido="false";
						
			}
		}
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divRegistro","innerHTML",$cadena_html);
		
		//Rescatar los candidatos elegidos
		//$cadena_html="";
		$cadena_sql="SELECT ";
		$cadena_sql.="* ";
		$cadena_sql.="FROM ";
		$cadena_sql.=$configuracion["prefijo"]."consultiva ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="eleccion> 0 ";
		$cadena_sql.="ORDER BY ";
		$cadena_sql.="eleccion ASC ";
		$conteo=$acceso_db->registro_db($cadena_sql,0);
		$registroElegido=$acceso_db->obtener_registro_db();
		if($conteo>0)
		{
			$cadena_html="";
			for($i=0;$i<$conteo;$i++)
			{
				$cadena_html.="<table>\n";
				$cadena_html.="<tr class='bloquecentralcuerpo'>\n";
				$cadena_html.="<td colspan='2' align='center' bgcolor='#FFFFD6'>\n";
				$cadena_html.=$registroElegido[$i][0]." <b>".htmlentities($registroElegido[$i][6]." ".$registroElegido[$i][5])."</b>";
				$cadena_html.="</td>\n";
				$cadena_html.="</tr>\n";
				$cadena_html.="</table>\n";
				$cadena_html.="<hr class='hr_subtitulo'>\n";
			}
			$respuesta->addAssign("divElecto","innerHTML",$cadena_html);
		}
		
		
		
	}
	return $respuesta;
	
}
?>
