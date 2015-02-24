<?php

	if(!isset($GLOBALS["autorizado"]))
	{
		include("../index.php");
		exit;		
	}
	
	include_once("funcion.class.php");
	
	class bloqueNovedadesNotas
	{

		public function __construct($configuracion){
			array_walk($_REQUEST, array($this,'secureSuperGlobalREQUEST'));
			$this->funcion=new funciones_admin_panelPrincipal($configuracion);
		}
		
		public function jxajax($configuracion){
			switch($_REQUEST['jxajax']){
				case "consultarAsignatura":
					$this->funcion->consultarAsignatura($configuracion,$_REQUEST['asignatura']);
				break;
			}
		}
		
		public function  html($configuracion){
			$carreras=$this->funcion->rescatarCarreras($configuracion);	
                        $mensaje=array();
                        foreach ($carreras as $key => $value) 
                            {
                                if(is_numeric($value[0])){
                                    $fechas=$this->funcion->verificarCalendario($configuracion,$carreras[$key][0]);
                                    if(!is_array($fechas))
                                        { $mensaje[]="Advertencia: Las fechas para registrar novedades de notas para la carrera {$carreras[$key][0]} - {$carreras[$key][1]} se encuentran cerradas";
                                        }
                                }
                            }
                            $total_carreras = count($carreras);
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
                            /*$fechas=$this->funcion->verificarCalendario($configuracion);
                        $mensaje=array();
                        
			if(!is_array($fechas)){
				$i=0;
				while(isset($fechas[$i][0])){
					$mensaje[]="Advertencia: Las fechas para registrar novedades de notas para la carrera {$fechas[$i][0]} se encuentran cerradas";
				$i++;				
				}*/
                            
			echo $this->funcion->template->render(array('carreras'=>$carreras,'mensaje'=>$mensaje));
                        if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case 'consultar':
                                            $this->funcion->consultarRegistros($configuracion,$valor);
                                        break;
					
				
				}
			}
		}
		
		public function  action($configuracion){
			
			$opcion=$_REQUEST['opcion']?$_REQUEST['opcion']:'';
                        //$valor = $_REQUEST;
                        $valor=array();
                        foreach($_REQUEST as $key=>$value)
                                {if($_REQUEST[$key]!='')
                                        {$valor[$key]=$_REQUEST[$key];}
                                }
			
			switch($opcion){
				case 'consultarRegistros':
					$this->funcion->consultarRegistros($configuracion,$valor);
//                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//                                    $variable="pagina=adminNovedadesNotas";
//                                    $variable.="&opcion=consultar";
//                                    $variable.="&proyecto=".$_REQUEST["proyecto"];
//                                    $variable.="&filtroEstudiante=".$_REQUEST["filtroEstudiante"];
//                                    $variable.="&filtroNombre=".$_REQUEST["filtroNombre"];
//                                    $variable.="&filtroPlan=".$_REQUEST["filtroPlan"];
//                                    $variable.="&filtroIdentificacion=".$_REQUEST["filtroIdentificacion"];
//                                    $variable.="&log=Consultar";
//                                    $variable.="&filtroEstado=".$_REQUEST["filtroEstado"];
//                                    $variable.="&filtroCodCarrera=".$_REQUEST["filtroCodCarrera"];
//                                    $variable.="&log=".$_REQUEST["log"];
//                                  
//    //var_dump($_REQUEST);exit;
//                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
//                                    $this->cripto=new encriptar();
//                                    echo "<br>variable ".$pagina.$variable;exit;
//                                    $variable=$this->cripto->codificar_url($variable,$configuracion);
//
//                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				case 'actualizarNota':
					$this->funcion->actualizarRegistroNota($configuracion,$valor);
				break;
				case 'insertarNota':
					$this->funcion->insertarRegistroNota($configuracion,$valor);
				break;
				default:
					
				break;
			}
		}
		
		public function secureSuperGlobalREQUEST(&$value,$key)
		{
			$_REQUEST[$key] = htmlspecialchars(stripslashes($_REQUEST[$key]));
			$_REQUEST[$key] = str_ireplace("script", "blocked", $_REQUEST[$key]);
			$_REQUEST[$key] = str_ireplace("select", "blocked", $_REQUEST[$key]);
			return $_REQUEST[$key];
		}
	
	}

	$esteBloque=new bloqueNovedadesNotas($configuracion);

	
	if(!isset($_REQUEST['jxajax'])){
		
		include_once("funcion.class.php");
		include_once("valida.js.php");
	
		if(!isset($_REQUEST['action'])){
			$esteBloque->html($configuracion);
		}else{
			$esteBloque->action($configuracion);
		}
	}else{
		$esteBloque->jxajax($configuracion);
	}
?>