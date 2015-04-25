<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
require_once($configuracion["raiz_documento"].$configuracion["javascript"]."/Twig/Autoloader.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");	
include_once("sql.class.php");	
		

class funciones_admin_panelPrincipal extends funcionGeneral
{

	function __construct($configuracion)
	{
		$this->acceso_mysql=$this->conectarDB($configuracion,'');
		$this->acceso_mysql_log=$this->conectarDB($configuracion,'mysqlsga');
		$this->acceso_oci=$this->conectarDB($configuracion,'coordinador');
		$this->usuario=$this->rescatarValorSesion($configuracion,$this->acceso_mysql,"usuario");
		$this->cripto=new encriptar();
		$this->sql=new sql_panelPrincipal();
		$this->configuracion=$configuracion;
		$this->indice=$configuracion["host"]."/weboffice/index.php?";
		$this->error=array();
		$this->confirm = "";
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem(array($configuracion["raiz_documento"]."/bloque/admin_novedadesNotas",$configuracion["raiz_documento"]."/estilo/templates/template_1"));
		//$this->twig = new Twig_Environment($loader, array('cache' => 'cache'));
		$twig = new Twig_Environment($loader,array('debug' => true));
		$this->template = $twig->loadTemplate('html.php');
		

	}
	
	function rescatarCarreras($configuracion){
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarCarreras",$this->usuario);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda"); 
		return $registro;
	}
	
	function verificarCalendario($configuracion,$carrera=""){
		$valor[0]=$this->usuario;
		$valor[1]=$carrera;
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"verificarCalendario",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda"); 
                return $registro;
	}	
	
	function consultarRegistros($configuracion,$valor,$mensaje=array(),$confirm=array()){
                $mensaje[0]=  (isset($mensaje[0])?$mensaje[0]:'');
		//consulto todos los registros para los valores de filtro
		$valor['usuario']=$this->usuario;
		$carreras=$this->rescatarCarreras($configuracion);		
                $total_carreras=count($carreras);
                if($total_carreras >1){
                        for($key=$total_carreras;$key>=0;$key--) {
                            if($key>0){
                                $carreras[$key][0]=$carreras[$key-1][0];
                                $carreras[$key][1]=$carreras[$key-1][1];
                            }
                        }
                        $carreras[0][0]='';
                        $carreras[0][1]=' ';
                                
                }
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarRegistros",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda"); //adicionar el rownum
		
		if(!is_array($registro)){
			
			echo $this->template->render(array('filtro'=>$valor,
												'carreras'=>$carreras,
												'mensaje'=>array(' .:: No existen registros de estudiantes para esta consulta ::.')));
			
		}else{
			
			$totalRegistros=count($registro);
			
			//si no existe un registro actual le asigno el registro 1
			$valor['registroActual']=isset($valor['registroActual'])?($valor['registroActual']*1):1;		
			
			//como solo voy a consultar un estudiante rescato el numero de registro q corresponde 
			//Suponiendo q el registroActual es 1 este corresponde con el registro 0 que arroja la consulta 
			//por esto debo consultar el registro ubicando la posicion --
			$valor['estudiante']=$registro[($valor['registroActual']-1)][1];
			
			$enlace=$this->rescatarNavegacion($totalRegistros,$valor);
			
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarObservaciones",$valor);
			$observaciones=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
			
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarClasificacion",$valor);
			$clasificaciones=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarNotas",$valor);
			$notas=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
			if(!is_array($notas)){
				$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarEstudiante",$valor);
				$estudiante=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
				$notas[0][13]=$estudiante[0][0];
				$notas[0][11]=$estudiante[0][1];
				$notas[0][12]=$estudiante[0][2];
				$notas[0][10]=$estudiante[0][4];
				$notas[0][20]=$estudiante[0][5];
                                if(!$mensaje[0]){
                                    $mensaje=array("No existen registros de notas para este estudiante");
                                }
			}
                        //var_dump($notas);
			$confirm['mensaje']=(isset($confirm['mensaje'])?$confirm['mensaje']:'');
                        $confirm['si']=(isset($confirm['si'])?$confirm['si']:'');
                        $confirm['no']=(isset($confirm['no'])?$confirm['no']:'');
			echo $this->template->render(array('notas'=>$notas,
								'filtro'=>$valor,
								'observaciones'=>$observaciones,
								'clasificaciones'=>$clasificaciones,
								'mensaje'=>$mensaje,
								'carreras'=>$carreras,
								'msgConfirm'=>array($confirm['mensaje'],$confirm['si'],$confirm['no']),
								'totalRegistros'=>$totalRegistros,
								'registroActual'=>($valor['registroActual']),
								'URLregistroAnterior'=>$enlace['Anterior'],
								'URLregistroSiguiente'=>$enlace['Siguiente'],
								));
		}
	
	}
	
	function rescatarConfirmacion($mensaje,$valor){
		$variable="";
		$confirm=array();
		foreach($valor as $clave => $dato){
			$variable.="&".$clave."=".$dato;
		}		
		$confirm["mensaje"]=$mensaje;
		$confirm["si"]=$this->indice."confirm=true".$variable;
		$confirm["no"]=$this->indice."confirm=false".$variable;
		return $confirm;
	
	}
	
	function rescatarNavegacion($totalRegistros,$valor){
		
		$registroActual=$valor['registroActual']*1;
		$enlace=array();
		
		unset($valor['registroActual']);

		$variable="&no_pagina=adminNovedadesNotas";
		foreach($valor as $clave => $dato){
			$variable.="&".$clave."=".$dato;
		}	
		
		if($totalRegistros==0 || $totalRegistros==1  ){
			$enlace['Anterior']="";
			$enlace['Siguiente']="";
		}elseif($totalRegistros==$registroActual){
			$enlace['Anterior']=$this->indice.$this->cripto->codificar_url("registroActual=".($registroActual-1).$variable,$this->configuracion);	
			$enlace['Siguiente']="";		
		}elseif($registroActual==1){
			$enlace['Anterior']="";
			$enlace['Siguiente']=$this->indice.$this->cripto->codificar_url("registroActual=".($registroActual+1).$variable,$this->configuracion);	
		}else{
			$enlace['Anterior']=$this->indice.$this->cripto->codificar_url("registroActual=".($registroActual-1).$variable,$this->configuracion);
			$enlace['Siguiente']=$this->indice.$this->cripto->codificar_url("registroActual=".($registroActual+1).$variable,$this->configuracion);
		}
		return $enlace;
	}
	
	
	function consultarAsignatura($configuracion,$codAsignatura){
	
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignatura",array('asignatura'=>$codAsignatura));
		$asignatura=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");	
		
		$responce=new stdClass();
		
		//valido si la asignatura existe
		if(is_array($asignatura)){
			
			$responce->nombreAsignatura = $asignatura[0][2];
			
			//valido si la asignatura existe en el pensum del estudiante
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturaPlan",array('asignatura'=>$codAsignatura,'estudiante'=>$_REQUEST['estudiante']));
			$plan=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
			
			if(!is_array($plan)){ //si no pertenece a su plan de estudios solo se rescata el nombre de la asignatura
			

			
			}else{ //si pertenece al plan de estudios
			
				//valido si la asignatura es de creditos

				if($asignatura[0][1]=="S"){ //si es de creditos
				
					$responce->nivel=$plan[0][5];
					$responce->cred=$plan[0][10];
					$responce->htd=$plan[0][7];
					$responce->htc=$plan[0][8];
					$responce->hta=$plan[0][12];
						
					$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarClasificacionAsignatura",array('asignatura'=>$codAsignatura,'plan'=>$plan[0][11]));
					$clasificacion=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
					
					$responce->ceacod=$clasificacion[0][0];
					
				}elseif($asignatura[0][1]=="N"){ //si es de horas
				
					$responce->nivel=$plan[0][5];
					$responce->ht=$plan[0][7];
					$responce->hp=$plan[0][8];		
				
				}			
				
			
			
			}

		}else{
			
			$responce->nombreAsignatura = "Asignatura no existe";
		
		}
		

		echo json_encode($responce);
	}
	
	
	function actualizarRegistroNota($configuracion,$valor){
	
		$valor['usuario']=$this->usuario;
		$registro2='';
		$valor['operacion']='M';
		
                $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarNotaACambiar",$valor);
		$regNota=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		
                //verifico calendario, si la carrera tiene las fechas cerradas rescato el codigo de la carrera
		$fechas=$this->verificarCalendario($configuracion,$valor['carrera']);
 
                if(!is_array($fechas))
                {
                    if (($regNota[0][6]<>$valor['nota'] || $regNota[0][8]<>$valor['estado']) || ($regNota[0][13]=='S' && $regNota[0][14]<> (isset($valor['creditos'])?$valor['creditos']:'')))
                    {
			//$this->error[]="Las fechas para registrar novedades de notas para la carrera {$fechas[0][0]} se encuentran cerradas";
			$this->error[]="Las fechas para registrar novedades de notas para la carrera {$valor['carrera']} se encuentran cerradas";
                        $this->consultarRegistros($configuracion,$valor,$this->error);
			exit;
                    }
		}
		
//                $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarNotas",$valor);
//		$registros=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		$registros = $regNota;
		if(is_array($registros) ){
                    //verificar valores
                    $resultado_validacion = $this->validarValores($valor);
                    $cadenaActualizar='';
                    $logModificaInfoBasica='';
                    $logNovedadNota='';
                    if( $resultado_validacion=='ok'){
                        
			//reviso que los valores de la consulta sean diferentes a los valores enviados para Modificar el registro e insertar en el log solo los registros actualizados
			if($registros[0][2]<>$valor['semestre']){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_SEM=".$valor['semestre']."";
                                    $logModificaInfoBasica = "NOT_SEM: ".$registros[0][2]."=>".$valor['semestre'];
                                }else{
                                    $cadenaActualizar .= ", NOT_SEM=".$valor['semestre']."";
                                    $logModificaInfoBasica .= "NOT_SEM: ".$registros[0][2]."=>".$valor['semestre'];
                                }
			}	
			if($registros[0][1]<>$valor['grupo']){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_GR=".$valor['grupo']."";
                                    $logModificaInfoBasica = "NOT_GR: ".$registros[0][1]."=>".$valor['grupo'];
                                }else{
                                    $cadenaActualizar .= ", NOT_GR=".$valor['grupo']."";
                                    $logModificaInfoBasica .= "NOT_GR: ".$registros[0][1]."=>".$valor['grupo'];
                                }
			}	
			if($registros[0][6]<>$valor['nota'] && is_array($fechas)){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_NOTA=".$valor['nota']."";
                                    $logNovedadNota = "NOT_NOTA: ".$registros[0][6]."=>".$valor['nota'];
                                }else{
                                    $cadenaActualizar .= ", NOT_NOTA=".$valor['nota']."";
                                    $logNovedadNota .= ", NOT_NOTA: ".$registros[0][6]."=>".$valor['nota'];
                                }
			}	
			if($registros[0][7]<>$valor['obs'] && ($valor['obs']==19 || $valor['obs']==20 || $valor['obs']==22 || $valor['obs']==23 || $valor['obs']==24 || $valor['obs']==25 || $registros[0][7]==19 || $registros[0][7]==20 || $registros[0][7]==22 || $registros[0][7]==23 || $registros[0][7]==24 || $registros[0][7]==25 ) && is_array($fechas) ){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_OBS='".$valor['obs']."'";
                                    $logNovedadNota = "NOT_OBS: ".$registros[0][7]."=>".$valor['obs'];
                                }else{
                                    $cadenaActualizar .= ", NOT_OBS='".$valor['obs']."'";
                                    $logNovedadNota .= ", NOT_OBS: ".$registros[0][7]."=>".$valor['obs'];
                                }
			}
			if($registros[0][7]<>$valor['obs'] && $valor['obs']!=19 && $valor['obs']!=20 && $valor['obs']!=22 && $valor['obs']!=23 && $valor['obs']!=24 && $valor['obs']!=25 && $registros[0][7]!=19 && $registros[0][7]!=20 && $registros[0][7]!=22 && $registros[0][7]!=23 && $registros[0][7]!=24 && $registros[0][7]!=25 ){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_OBS='".$valor['obs']."'";
                                    $logModificaInfoBasica = "NOT_OBS: ".$registros[0][7]."=>".$valor['obs'];
                                }else{
                                    $cadenaActualizar .= ", NOT_OBS='".$valor['obs']."'";
                                    $logModificaInfoBasica .= ", NOT_OBS: ".$registros[0][7]."=>".$valor['obs'];
                                }
			}
			if($registros[0][8]<>$valor['estado'] && is_array($fechas)){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_EST_REG='".$valor['estado']."'";
                                    $logNovedadNota = "NOT_EST_REG: ".$registros[0][8]."=>".$valor['estado'];
                                }else{
                                    $cadenaActualizar .= ", NOT_EST_REG='".$valor['estado']."'";
                                    $logNovedadNota .= ", NOT_EST_REG: ".$registros[0][8]."=>".$valor['estado'];
                                }
			}
			if(isset($valor['creditos']) AND $registros[0][14]<>$valor['creditos'] && is_array($fechas)){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_CRED=".$valor['creditos']."";
                                    $logNovedadNota = "NOT_CRED: ".$registros[0][14]."=>".$valor['creditos'];
                                }else{
                                    $cadenaActualizar .= ", NOT_CRED=".$valor['creditos']."";
                                    $logNovedadNota = ", NOT_CRED: ".$registros[0][14]."=>".$valor['creditos'];
                                }
			}
			if(isset($valor['hteoricas']) AND $registros[0][15]<>$valor['hteoricas']){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_NRO_HT='".$valor['hteoricas']."'";
                                    $logModificaInfoBasica = "NOT_NRO_HT: ".$registros[0][15]."=>".$valor['hteoricas'];
                                }else{
                                    $cadenaActualizar .= ", NOT_NRO_HT='".$valor['hteoricas']."'";
                                    $logModificaInfoBasica .= ", NOT_NRO_HT: ".$registros[0][15]."=>".$valor['hteoricas'];
                                }
			}
			if(isset($valor['hpracticas']) AND $registros[0][16]<>$valor['hpracticas']){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_NRO_HP='".$valor['hpracticas']."'";
                                    $logModificaInfoBasica = "NOT_NRO_HP: ".$registros[0][16]."=>".$valor['hpracticas'];
                                }else{
                                    $cadenaActualizar .= ", NOT_NRO_HP='".$valor['hpracticas']."'";
                                    $logModificaInfoBasica .= ", NOT_NRO_HP: ".$registros[0][16]."=>".$valor['hpracticas'];
                                }
			}
			if(isset($valor['hautonomo']) AND $registros[0][17]<>$valor['hautonomo']){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_NRO_AUT=".$valor['hautonomo']."";
                                    $logModificaInfoBasica = "NOT_NRO_AUT: ".$registros[0][17]."=>".$valor['hautonomo'];
                                }else{
                                    $cadenaActualizar .= ", NOT_NRO_AUT=".$valor['hautonomo']."";
                                    $logModificaInfoBasica .= ", NOT_NRO_AUT: ".$registros[0][17]."=>".$valor['hautonomo'];
                                }
			}
                        
			if(isset($valor['ceacod']) AND $registros[0][18]<>$valor['ceacod']){
                                if(!$cadenaActualizar ){
                                    $cadenaActualizar = "NOT_CEA_COD=".$valor['ceacod']."";
                                    $logModificaInfoBasica = "NOT_CEA_COD: ".$registros[0][18]."=>".$valor['ceacod'];
                                }else{
                                    $cadenaActualizar .= ", NOT_CEA_COD=".$valor['ceacod']."";
                                    $logModificaInfoBasica .= ", NOT_CEA_COD: ".$registros[0][18]."=>".$valor['ceacod'];
                                }
			}
                        if($cadenaActualizar){
                            $valor['cadenaActualizar']=$cadenaActualizar;
                            $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"actualizarRegistroNota",$valor);
                            $registro2=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");
                           
                        }
                    }
                        //verificamos que se halla realizado la actualización
                        if($registro2){
                                if($logModificaInfoBasica){
                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'78',
                                                          'descripcion'=>'Modifica info. básica de nota',
                                                          'registro'=>"Proy: ".$valor['carrera'].", Espacio: ".$valor['asignatura'].", Año:".$valor['anio'].", Per:".$valor['per'].", Campos: ".$logModificaInfoBasica,
                                                          'afectado'=>$valor['estudiante']);
                                        $sqlLog=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql_log,"registro_evento",$variablesRegistro);
                                        $regLog=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql_log,$sqlLog,"");
                                        //Para insertar en tabla de auditoria
                                        $campos = explode(",", $logModificaInfoBasica);
                                        if($campos){
                                            $auditoria=$valor;
                                            foreach ($campos as $key => $campo) {
                                                $pos_campo = strpos($campo, ":");
                                                $pos_valIni = strpos($campo, "=");
                                                $longitud = strlen($campo);
                                                $auditoria['campo'] = trim(substr($campo,0,$pos_campo));
                                                $pos_campo++;
                                                $extraer = $pos_valIni-$pos_campo;
                                                $auditoria['valIni'] = trim(substr($campo,$pos_campo,$extraer));
                                                $pos_valIni=$pos_valIni+2;
                                                $auditoria['valFin'] = substr($campo,$pos_valIni);
                                                $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"insertarAuditoria",$auditoria);
                                                $registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");

                                            }
                                        }
                                }

                                if($logNovedadNota){
                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'77',
                                                          'descripcion'=>'Novedad de nota',
                                                          'registro'=>"Proy: ".$valor['carrera'].", Espacio: ".$valor['asignatura'].", Año:".$valor['anio'].", Per:".$valor['per'].", Campos: ".$logNovedadNota,
                                                          'afectado'=>$valor['estudiante']);
                                        $sqlLog=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql_log,"registro_evento",$variablesRegistro);
                                        $regLog=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql_log,$sqlLog,"");
                                        //Para insertar en tabla de auditoria
                                        $campos = explode(",", $logNovedadNota);
                                        if($campos){
                                            $auditoria=$valor;
                                            foreach ($campos as $key => $campo) {
                                                $pos_campo = strpos($campo, ":");
                                                $pos_valIni = strpos($campo, "=");
                                                $longitud = strlen($campo);
                                                $auditoria['campo'] = trim(substr($campo,0,$pos_campo));
                                                $pos_campo++;
                                                $extraer = $pos_valIni-$pos_campo;
                                                $auditoria['valIni'] = trim(substr($campo,$pos_campo,$extraer));
                                                $pos_valIni=$pos_valIni+2;
                                                $auditoria['valFin'] = substr($campo,$pos_valIni);
                                                $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"insertarAuditoria",$auditoria);
                                                $registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");

                                            }
                                        }
                                }
				
                                $this->consultarRegistros($configuracion,$valor,array(' .:: El registro se actualizó exitosamente ::.'));
			}else{
                                if($logModificaInfoBasica){
                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'78',
                                                          'descripcion'=>'Error al Modificar info. básica de nota',
                                                          'registro'=>"Proy: ".$valor['carrera'].", Espacio: ".$valor['asignatura'].", Año:".$valor['anio'].", Per:".$valor['per'].", Campos: ".$logModificaInfoBasica,
                                                          'afectado'=>$valor['estudiante']);
                                        $sqlLog=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql_log,"registro_evento",$variablesRegistro);
                                        $regLog=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql_log,$sqlLog,"");
                                }
                                if($logNovedadNota){
                                        if(!is_array($fechas)){
                                            $estado_fechas = "- fechas cerradas";
                                        }else{
                                            $estado_fechas = "";
                                        }
                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'77',
                                                          'descripcion'=>'Error en Novedad de nota'.$estado_fechas,
                                                          'registro'=>"Proy: ".$valor['carrera'].", Espacio: ".$valor['asignatura'].", Año:".$valor['anio'].", Per:".$valor['per'].", Campos: ".$logNovedadNota,
                                                          'afectado'=>$valor['estudiante']);
                                        $sqlLog=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql_log,"registro_evento",$variablesRegistro);
                                        $regLog=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql_log,$sqlLog,"");
                                }
                                $this->consultarRegistros($configuracion,$valor,array(' .:: El registro no se pudo actualizar ::.'));
			}

		}else{
			
				echo $this->template->render(array('filtro'=>$valor,'mensaje'=>array(' .:: El registro no se pudo actualizar ::.')));
		}
	}
	

	function insertarRegistroNota($configuracion,$valor){
	
		$valor['usuario']=$this->usuario;
		$valor['operacion']='C';
		
		//aqui hago todas las validaciones respectivas para insercion de notas
		$valida=$this->validarRegistroNota($valor);
		if($valida){
		
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"insertarAuditoria",$valor);
			$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");

			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"insertarRegistroNota",$valor);
			$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"");
			
			if($registro){
                                $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'77',
                                                          'descripcion'=>'Registro Novedad de nota ',
                                                          'registro'=>"Proy: ".$valor['carrera'].", Espacio: ".$valor['asignatura'].", Año:".$valor['anio'].", Per:".$valor['per'].", nota:".$valor['nota'].", Obs.:".$valor['obs'].", Cred.:".$valor['creditos'],
                                                          'afectado'=>$valor['estudiante']);
                                $sqlLog=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql_log,"registro_evento",$variablesRegistro);
                                $regLog=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql_log,$sqlLog,"");
                                       
				$this->consultarRegistros($configuracion,$valor,array(' .:: El registro se inserto exitosamente ::.'));
			}else{
				$variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'77',
                                                          'descripcion'=>'Error al registrar Novedad de nota '.$this->error,
                                                          'registro'=>"Proy: ".$valor['carrera'].", Espacio: ".$valor['asignatura'].", Año:".$valor['anio'].", Per:".$valor['per'].", nota:".$valor['nota'].", Obs.:".$valor['obs'].", Cred.:".$valor['creditos'],
                                                          'afectado'=>$valor['estudiante']);
                                $sqlLog=$this->sql->cadena_sql($this->configuracion,$this->acceso_mysql_log,"registro_evento",$variablesRegistro);
                                $regLog=$this->ejecutarSQl($this->configuracion,$this->acceso_mysql_log,$sqlLog,"");
                                
				echo $this->template->render(array('mensaje'=>' .:: El registro no se pudo insertar ::.'));
			}

		}else{
			//alerta envia un mensaje de confirm
			if($this->confirm<>""){
				$confirm=$this->rescatarConfirmacion($this->confirm,$valor);
				$this->consultarRegistros($configuracion,$valor,$this->error,$confirm);				
			}else{
				$this->consultarRegistros($configuracion,$valor,$this->error);
			}			
		}
	}
	
	function validarRegistroNota($valor){
		
		$valor['usuario']=$this->usuario;
		
		//verifico calendario, si la carrera tiene las fechas cerradas rescato el codigo de la carrera
		$fechas=$this->verificarCalendario($this->configuracion,$valor['carrera']);
                                
		if(!is_array($fechas)){
			//$this->error[]="Las fechas para registrar novedades de notas para la carrera {$fechas[0][0]} se encuentran cerradas";
			$this->error[]="Las fechas para registrar novedades de notas para la carrera {$valor['carrera']} se encuentran cerradas";                        
			return false;
		}

		//verificar si el estudiante pertenece al coordinador y rescato si el estudiante es de horas o de creditos
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarEstudiante",$valor);
		$estudiante=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		
		if(!is_array($estudiante)){
			$this->error[]="El estudiante no pertenece a su(s) Proyecto(s) Curricular(es)";
			return false;
		}

		//verificar si la asignatura existe 
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignatura",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
		
		if(!is_array($registro)){
			$this->error[]="No existe ninguna asignatura asociada al código ". $valor['asignatura'];
			return false;
		}
		
		//verifico la obligatoriedad del año y el periodo
		if($valor['anio']=="" || $valor['per']==""){
			$this->error[]="Los campos año y periodo carácter obligatorio";
			return false;		
		}		
		
		//verifico la obligatoriedad de la nota
		if($valor['nota']==""){
			$this->error[]="El campo nota es de carácter obligatorio";
			return false;		
		}		
		
                //verifico valor de la nota
		if($valor['nota']<=0 && $valor['nota']>=50){
			$this->error[]="La nota no tiene un valor valido";
			return false;		
		}
                
		//verificar si tiene un registro activo y si ya esta aprobado
		$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarActivayAprobada",$valor);
		$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");	

		if(is_array($registro)){
			$this->error[]="El estudiante ya posee una nota asociada a la asignatura {$valor['asignatura']} ACTIVA y APROBADA";
			return false;
		}	
		
                if($estudiante[0][5]=="PREGRADO"){ //verifica si es pregrado
                    if($estudiante[0][0]=="N"){ //N=Estudiante de Horas
		
			//verifico la obligatoriedad de los campos horas teoricas y practicas
			if($valor['hteoricas']=="" || $valor['hpracticas']==""){
				$this->error[]="Los Campos HT(Horas Teóricas) y HP(Horas prácticas) son de carácter obligatorio";
				return false;		
			}
		
			//verificar si la asignatura corresponde con el plan de estudios de horas si no mostrar advertencia
			$sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturaPlan",$valor);
			$registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");
			if(!is_array($registro)){
				if(!isset($valor['confirm'])){
					$this->confirm="Advertencia: La asignatura asociada al código {$valor['asignatura']} no pertenece al plan de estudios del estudiante. Desea Continuar?";
					return false;
				}elseif(isset($valor['confirm']) and $valor['confirm']=='false'){
					$this->error[]="El registro no fue insertado";
					return false;
				}
			}
                    }elseif($estudiante[0][0]=="S"){ //S=Estudiante de Creditos	

                            //verifico la obligatoriedad de los campos CRED HTD HTC HTA

                            if($valor['creditos']=="" || $valor['hteoricas']=="" || $valor['hpracticas']=="" || $valor['hautonomo']=="" || $valor['ceacod']=="" ){
                                    $this->error[]="Los Campos CRED, HTC, HTD, HTA y Clasificacion son de caracter obligatorio";
                                    return false;		
                            }

                            //verificar si la asignatura corresponde con el plan de estudios de creditos
                            $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturaPlan",$valor);
                            $registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");	

                            //si no pertence compruebo si la asignatura es de tipo extrinseco
                            if(!is_array($registro)){

                                    //verifico si la asignatura es de tipo extrinseca
                                    $sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_oci,"consultarAsignaturaExtrinseca",$valor);
                                    $registro=$this->ejecutarSQl($this->configuracion,$this->acceso_oci,$sql,"busqueda");	

                                    if(!is_array($registro)){				
                                            $this->error[]="El espacio académico {$valor['asignatura']} no pertenece al plan de estudios ";
                                            return false;
                                    }	
                            }
                        }	
                }else{
                    //verifico la obligatoriedad de los campos CRED HTD HTC HTA

                            if($valor['creditos']=="" || $valor['hteoricas']=="" || $valor['hpracticas']=="" ){
                                    $this->error[]="Los Campos CRED, HTC, HTD son de caracter obligatorio";
                                    return false;		
                            }
                           
                }
                
                

		return true;
	}
	
        function validarValores($valor){
            if($valor['nota']>=0 && $valor['nota']<=50){
                $resultado='ok';
            }else{
                return $resultado='Valor de nota no valido';
            }
           
            return $resultado;
        }
		
}
	

?>

