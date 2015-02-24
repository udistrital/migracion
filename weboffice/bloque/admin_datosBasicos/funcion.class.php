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

class funciones_adminSolicitud extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=$sql;
		$this->formulario="admin_generados";
		$this->verificar="control_vacio(".$this->formulario.",'estudiante')";
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                $this->accesoPG=$this->conectarDB($configuracion,"evaldocentes");//PostgreSql
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->accesoEncuesta=$this->conectarDB($configuracion,"mysqlEncuestaEstudiante");
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->usuario=$_REQUEST['usuario'];
                
         }
                        
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	

		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarEstudiante($configuracion)
	{
		//Conexion ORACLE

		//$this->cambiarPassword($configuracion);			
		$accesoOracle=$this->conectarDB($configuracion,"coordinador");
		$conexion=$accesoOracle;
                
                //Conexion POSTGRESQL
                $accesoPG=$this->conectarDB($configuracion,"evaldocentes");
		$conexion1=$accesoPG;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"registroTotal",$this->usuario);
		$registroTotal=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");		
                
		$dia= array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","S&aacute;bado");
		$mes= array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$fecha="Hoy es ".$dia[date('w')]." ".date('d')." de ".$mes[date('n')-1]." de ".date('Y');
		//$formatoAcuerdo='<a href="https://condor.udistrital.edu.co/appserv/manual/formato_004.doc"><font color="blue">Descargue aqu&iacute; el formato para acogerse al acuerdo 004.</font></a>';
		
                
                //$this->mostrarMensajeInicial($configuracion);
		$html="
			<style>
			.tabla_general {
				padding:20px;
				width:100%;
			}
			
			.superior{
				-webkit-border-radius: 10px;
				-moz-border-radius: 10px;
				border-radius: 10px;
				border:1px solid #CCCCCC;
				-webkit-box-shadow:  2px 2px 5px 1px rgba(0, 0,0 , 1);
				box-shadow:  2px 2px 5px 1px rgba(0, 0,0 , 1);
				padding:20px;
				width:100%;
			}
			


			fieldset {
				padding: 26px;
				border: 1px solid #b4b4b4;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
			}

			legend {
				padding: 5px 20px 5px 20px;
				color: #030303;
				-moz-border-radius: 6px;
				-webkit-border-radius: 6px;
				border: 1px solid #b4b4b4;
			}

			ol {
				list-style: none;
				margin-bottom: 20px;
				border: 1px solid #b4b4b4;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				padding: 10px;
			}

			ol, fieldset {
				background-image: -moz-linear-gradient(top, #f7f7f7, #e5e5e5); /* FF3.6*/
				background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, #e5e5e5),color-stop(1, #f7f7f7)); /* Saf4+, Chrome */
			}

			ol.buttons {
				overflow: auto;
			}

			ol li label {
				width: 160px;
				font-weight: bold;
				
			}


			label.info {
				position: absolute;
				color: #000;
				top:0px;
				left: 50px;
				line-height: 15px;
				width: 200px;
			}



			.message{
				background-size: 40px 40px;
				background-image: linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
									transparent 75%, transparent);										
				 box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
				 width: 100%;
				 border: 1px solid;
				 color: #fff;
				 padding: 15px;
				 text-shadow: 0 1px 0 rgba(0,0,0,.5);
				 animation: animate-bg 5s linear infinite;
			}

			.info{
					 background-color: #4ea5cd;
					 border-color: #3b8eb5;
			}
				
			.error{
				 background-color: #de4343;
				 border-color: #c43d3d;
			}
			</style>
		";
		$html.="<div class='superior'>Bienvenido(a) ".$registroTotal[0][0]."<br>";
		$html.="<span style='font-size:9pt;'>".$fecha."</span></div>";

		$html.="<table  width='99%' height='400px'>";
	
		$html.="<tr>";
		$html.="	<td width='50%'>";
		///////////////////////////////////////
		$variable['estudiante']=$this->usuario;
                $variable['anio']=$annoActual;
                $variable['per']=$periodoActual;
                
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoPG,"evaluacionDocente",$variable);
		$evaluacionDocente=$this->ejecutarSQL($configuracion,$this->accesoPG,$cadena_sql,"busqueda");
                
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"evaluacionCalendario",$registroTotal[0][1]);
		$evaluacionCalendario=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");	
                
		if(is_array($evaluacionCalendario) && !is_array($evaluacionDocente)){
			$html.="<div class='error message'>";
			$html.="<b>Evaluaci&oacute;n Docente:</b><br>Se&ntilde;or estudiante a la fecha usted no ha evaluado ning&uacute;n docente, lo invitamos a participar en el proceso.";
			$html.="</div>";
		}
                /////////////////////////
                //Encuesta estudiantes construcciones civiles sede tecnológica
                if($registroTotal[0][1]==79 || $registroTotal[0][1]==279 || $registroTotal[0][1]==379 || $registroTotal[0][1]==479 || $registroTotal[0][1]==579){
                    $html .=$this->enlaceEncuestaCiviles($configuracion);
                }
                /////////////////////
		$html_noticias= $this->obtenerHtmlNoticias($configuracion);
		$html = $html.$html_noticias;


///////////////////////////////////////
		$html.="	</td>";
		$html.="	<td width='50%'>";
///////////////////////////////////////
                if(strpos($registroTotal[0][6],'CALIDAD')!==FALSE)
                {$registroTotal[0][6]='EN TR&Aacute;MITE DE ACTO ADMINISTRATIVO POR P&Eacute;RDIDA DE LA CALIDAD DE ESTUDIANTE';}
                
		$html.="

			<form id='form-1' action='' method='post'>
			  <fieldset>
				<legend>Estado: {$registroTotal[0][6]}<br><br>{$registroTotal[0][10]}</legend>
				<img height='100px' src='../../appserv/est_fotos/{$this->usuario}.jpg'>
				<ol>
					<li>{$registroTotal[0][3]}</li>
				</ol>
				<ol>
				  <li><label>Usuario: </label>{$this->usuario}</li>
				  <li><label >Documento: </label>{$registroTotal[0][8]} {$registroTotal[0][7]}</li>
				  <li><label >Acuerdo: </label>".substr($registroTotal[0][9],-3).' de '.substr($registroTotal[0][9],0,-3)."</li>
				</ol>
				<ol>
				  <li><label>Correo: </label>{$registroTotal[0][4]}</li>
				  <li><label>Correo Inst: </label>{$registroTotal[0][5]}</li>
				</ol>

			  </fieldset>

			</form>		
					
		";
                  /*
		$html.="

			<form id='form-1' action='' method='post'>
			  <fieldset>
				<legend>Cierre de Semestre: Su estado académico estará disponible a partir del 20 de junio de 2013. </legend>
				<img height='100px' src='../../appserv/est_fotos/{$this->usuario}.jpg'>
				<ol>
					<li>{$registroTotal[0][3]}</li>
				</ol>
				<ol>
				  <li><label>Usuario: </label>{$this->usuario}</li>
				  <li><label >Documento: </label>{$registroTotal[0][8]} {$registroTotal[0][7]}</li>
				  <li><label >Acuerdo: </label>".substr($registroTotal[0][9],-3).' de '.substr($registroTotal[0][9],0,-3)."</li>
				</ol>
				<ol>
				  <li><label>Correo: </label>{$registroTotal[0][4]}</li>
				  <li><label>Correo Inst: </label>{$registroTotal[0][5]}</li>
				</ol>

			  </fieldset>

			</form>		
					
		";*/
                
                
		$html.="</td>";		
		$html.="</tr>";
		$html.="</table>";

		echo $html;	
	}
	
	function consultarAyuda($configuracion,$usuario)
	{	
	
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		$ayuda="pagina=admin_datos_basicos";
		$ayuda.="&opcion=ayuda";		
		$ayuda.="&usuario=".$usuario;
			
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"tipoUsuario",$this->usuario);
		$tipo=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");
		
				

		
		
					
		$html="<div style='padding:10px'>";
		

		$h=0;
		while(isset($tipo[$h][0])){
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"menuAyuda",$tipo[$h][0]);
		$menu=$this->ejecutarSQL($configuracion,$this->acceso_db,$cadena_sql,"busqueda");		
		

				$html.="	<table class='bloquelateral' width='100%'>";		
				$html.="		<tr class='bloquecentralcuerpo' align='right'>";
				$html.="			<td align='center' valign='middle'>";
				$html.="				<b>CONTENIDO DE LA DOCUMENTACI&Oacute;N</b>";		
				$html.="			</td>";
				$html.="			<td align='right' width='50px' valign='middle'>";
				$html.="				<a href='".$indice.$cripto->codificar_url("pagina=admin_datos_basicos&usuario=".$usuario,$configuracion)."'><img border='0' src='grafico/datosbasicos/inicio.png'/></a><br>Inicio";		
				$html.="			</td>";				
				$html.="		</tr>";	
				$html.="	</table>";				


		
			$i=0;
			while(isset($menu[$i][0])){
		
				$html.="	<table class='bloquelateral' width='100%'>";		
				$html.="		<tr class='bloquecentralcuerpo'>";
				$html.="			<td align='right' width='10%' valign='middle'>";
				$html.="				<img border='0' src='grafico/datosbasicos/indice.png'/>";		
				$html.="			</td>";
				$html.="			<td align='left'>";
				$html.=$menu[$i][1];		
				$html.="			</td>";		
				$html.="		</tr>";	
				$html.="		<tr class='bloquecentralcuerpo'>";
				$html.="			<td colspan='2' align='left'>";
				$html.="				<ul>";	
				
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"contAyuda",$menu[$i][0]);
					$submenu=$this->ejecutarSQL($configuracion,$this->acceso_db,$cadena_sql,"busqueda");
					
					$j=0;
					while(isset($submenu[$j][0])){				
							$html.="<li><a href='".$indice.$cripto->codificar_url($ayuda."&submenu=".$submenu[$j][0],$configuracion)."'>".$submenu[$j][1]."</a></li>";	
					$j++;		
					}


				$html.="				</ul>";				
				$html.="			</td>";		
				$html.="		</tr>";			
				$html.="	</table><br>";
			$i++;
			}
		$h++;
		}
		$html.="</div>";		
			

		
		echo $html;
	
	
	}
	
	
	function consultarContenidoAyuda($configuracion,$submenu)
	{	
	
	
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		$ayuda="pagina=admin_datos_basicos";
		$ayuda.="&usuario=".$this->usuario;
		$ayuda.="&opcion=ayuda";
			
		$ayuda=$cripto->codificar_url($ayuda,$configuracion);
		
					
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"consultarAyuda",$submenu);
		$submenu=$this->ejecutarSQL($configuracion,$this->acceso_db,$cadena_sql,"busqueda");
		
		
		
				$html="	<br><table class='bloquelateral' width='100%'>";		
				$html.="		<tr class='bloquecentralcuerpo'>";
				$html.="			<td align='right' width='50px' valign='middle'>";
				$html.="				<img border='0' src='grafico/datosbasicos/contenido.png'/>";		
				$html.="			</td>";
				$html.="			<td align='left' width='300px' >";
				$html.=$submenu[0][0];		
				$html.="			</td>";	
				$html.="			<td align='right' valign='middle'>";
				$html.="				<a href='".$indice.$ayuda."'><img border='0' src='grafico/datosbasicos/indice.png'/></a><br>Contenido";		
				$html.="			</td>";					
				$html.="		</tr>";	
				$html.="		<tr class='bloquecentralcuerpo'>";
				$html.="			<td  style='padding: 20px;' colspan='3' align='left'><br>";								
				$html.=$submenu[0][1];	
				$html.="			</td>";		
				$html.="		</tr>";			
				$html.="	</table>";
				
				
						
	
		echo $html;
	
	
	}
	
	
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
		//var_dump($valor);
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "siguiente":
				$variable="pagina=admin_capacitacion_funcionario";
				$variable.="&opcion=ayuda";
				$variable.="&usuario=estudiante";				
				
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);

		//echo $indice.$variable;
		
		//header("Location: ".$indice.$variable);
		
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		

        function obtenerHtmlNoticias($configuracion){
            $html='<fieldset class="info">'; 
		$html.='	<legend>NOTICIAS</legend>'; 

		$html.='<div  style="height:208px; width:90%;  background-color:white; border:1px solid black;" >';
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"recibosPendientes",$this->usuario);
		$registroRecPen=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");
				
		if($registroRecPen[0][0]==0)
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"noticias",$this->usuario);
			$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");
			
			if(!is_array($registro)){
				$html.='<marquee scrolldelay="100" scrollamount="3" style="height:205px; width:100%;" onmouseout="this.start()" onmouseover="this.stop()" direction="up" behavior="scroll">';
				$html.='<br><span style="font-size:1em;"><b>Mensaje de: </b>Vicerrector&iacute;a acad&eacute;mica</span><br>';
				$html.='<span style="font-size:1em;">Señor estudiante, <br>recuerde que se encuentra en curso el proceso de inscripci&oacute;n a SABER-PRO hasta el 28 de Julio de 2014, para mayor informaci&oacute;n debe comunicarse con su proyecto curricular.</span><br><br>';
				$html.='<br><span style="font-size:1em;"><b>Mensaje de: </b>Oficina Asesora de Sistemas</span><br>';
				$html.='<span style="font-size:1em;">Si cambia su correo electr&oacute;nico, direcci&oacute;n o tel&eacute;fono no olvide actualizarlos en el men&uacute; Datos Personales. Recuerde que de la veracidad de sus datos, depende un efectivo ingreso al aplicativo.</span><br><br>';
				$html.='<br><span style="font-size:1em;"><b>Mensaje de: </b>Oficina Asesora de Sistemas</span><br>';
				$html.='<span style="font-size:1em;">La manera segura de salir de esta página, es haciendo clic en el v&iacute;nculo "Cerrar Sesi&oacute;n". De esta forma nos aseguramos que otras personas no puedan manipular sus datos.</span><br>';
				$html.='</marquee>';
			}
			else{
			$html.='<marquee scrolldelay="100" scrollamount="3" style="height:205px; width:100%;" onmouseout="this.start()" onmouseover="this.stop()" direction="up" behavior="scroll">';
				$i=0;
				while(isset($registro[$i][0])){
					
					$html.='<br><span style="font-size:1em;"><b>Mensaje de: </b>'.$registro[$i][0].'</span><br>';
					$html.='<span style="font-size:1em;"><b>Asunto: </b>'.$registro[$i][1].'</span><br><br>';
					$html.='<span style="font-size:1em;">'.$registro[$i][5].'</span><br>';
					$i++;
				}
			$html.='</marquee>';
			}	
		
		}
		else
		{
			$html.='<br><span style="font-size:10pt;"><b>Mensaje urgente</b></span><br>';
			$html.='<span style="font-size:9pt;">Estimado(a) estudiante, para el presente periodo acad&eacute;mico, usted tiene <b> '.$registroRecPen[0][0].' </b>recibo(s) de pago de matr&iacute;cula pendiente(s) por pagar.</span><br><br>';
		}
		$html.='	</div>'; 		
		$html.='</fieldset>'; 			
		
		return $html;
        }

        /*function cambiarPassword($configuracion){
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/crypto/Encriptador.class.php");
            //include_once("../clase/crypto/Encriptador.class.php");
            $miCodificador=Encriptador::singleton();
            $usuario = $this->usuario;
            $identificacion = $_SESSION['usuario_login'];
            $indiceSaraLaverna = $configuracion["host"]."/laverna/index.php?";
            $tokenCondor = "condorSara2013!";
            $tipo=51;
            $tokenCondor = $miCodificador->codificar($tokenCondor);
            $opcion="temasys=";
            $variable.="gestionPassword&pagina=validarActualizacion";                                                        
            $variable.="&usuario=".$usuario;
            $variable.="&tipo=".$tipo;
            $variable.="&token=".$tokenCondor;
            $variable.="&opcionPagina=validaActualizacion";
            //$variable=$cripto->codificar_url($variable,$configuracion);
            $variable=$miCodificador->codificar($variable);
            $enlaceCambioPassword=$indiceSaraLaverna.$opcion.$variable;
           // $pagpal=$indiceSaraLaverna.$opcion.$variable;
            echo "<script>location.replace('".$indiceSaraLaverna.$opcion.$variable."')</script>";          
        }*/   
        
        function mostrarMensajeInicial($configuracion){
                $mensaje = "IMPORTANTE";
                $mensaje .= "\\n\\nSe invita a los docentes y estudiantes de la Universidad Distrital a participar en la jornada electoral que se llevará a cabo el día jueves 21 de noviembre de 2013 entre las 8:00 a.m. y las 8:00 p.m. (Hora legal Colombiana) para elegir mediante voto electrónico, urna y tarjetón virtual:";
                $mensaje.="\\n\\na.) Un (1) representante de los Docentes, principal y su suplente ante el Consejo Superior Universitario, para un período institucional de tres (3) años.";
                $mensaje.='\\nb.) Un (1) representante de los Docentes, principal y su suplente ante el Consejo Académico, para un período institucional de tres (3) años.';
                $mensaje.='\\nc.) Un (1) representante de los Docentes, principal y su suplente ante los Consejos de  facultad de Ingeniería, Ciencias y Educación, Medio Ambiente y Recursos Naturales, Tecnológica y Artes ASAB, para un período institucional de tres (3) años';
                $mensaje.='\\nd.) Dos (2) representantes de los Docentes de carrera de la Universidad ante el Comité de Personal Docente y de Asignación de Puntaje, para un periodo institucional de dos (2) años.';
                $mensaje.='\\ne.) Un (1) representante de los Estudiantes de la Universidad y su suplente ante  el Consejo Superior Universitario, para un período institucional de dos (2) años.';
                $mensaje.='\\nf.) Un (1) representante de los Estudiantes de la Universidad y su suplente ante el Consejo Académico, para un período institucional de dos (2) años.';
                $mensaje.='\\ng.) Un (1) representante de los Estudiantes de la Universidad y su suplente ante los Consejos de Facultad de: Ingeniería, Ciencias y Educación, Medio Ambiente y Recursos Naturales, Tecnológica y Artes ASAB, para un período institucional de dos (2) años.';
                $mensaje.='\\nPara mayor información consultar la Resolución 559 del 07 de octubre del 2013 ';
                $mensaje.='<a href="'.$configuracion["host"].'/academicopro/documentos/Resolucion_No.559.pdf"> Aqu&iacute;</a>';
                $html="<script>alert('".$mensaje."');</script>";
                echo $html;	
        }
        
        function enlaceEncuestaCiviles($configuracion){
                require_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $cripto=new encriptar();
                $indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";

                $variable="pagina=registro_encuestaEstudiantesCC";
                $variable.="&usuario=".$this->usuario;
                $variable.="&opcion=2";
                $variable.="&tipoUser=52";
                $variable.="&modulo=Estudiante";
                $variable.="&aplicacion=Condor";
                $variable.="&action=loginCondor";

                $variable=$cripto->codificar_url($variable,$configuracion);
                $enlaceEncuesta=$indiceAcademico.$variable;
                $html = "<br><div align='center' >"; 
                $html .= "<a href='".$enlaceEncuesta."'>";
                $html .= "<img alt='ENCUESTA' src='".$configuracion["host"].$configuracion["raiz_sga"].$configuracion["grafico"]."/encuesta.png'>";
                $datos=array('usuario'=>  $this->usuario,
                            'proceso'=>  2,
                            'seccion'=>  2,
                            'prueba'=>  2);
                $yaDiligencio=  $this->consultarEncuestaDiligenciada($configuracion,$datos);
                if(!is_array($yaDiligencio)){
                    $html .= "<br><font color='red'>Se&ntilde;or(a) Estudiante </font>por favor diligencie la Encuesta";
                }else{
                    $html .= "<br>Ver Encuesta";
                }                
                $html .= "</a></div>";
                $html .= "<br>";	
                return $html;
        }
        
        function consultarEncuestaDiligenciada($configuracion,$parametro){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoEncuesta,"encuesta_diligenciada",$parametro);
            $registro=$this->ejecutarSQL($configuracion,$this->accesoEncuesta,$cadena_sql,"busqueda");
	    return $registro;
        }
}

?>
