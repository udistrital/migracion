<?php

	if(!isset($GLOBALS["autorizado"]))
	{
		include("../index.php");
		exit;		
	}

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/crud.class.php");
	include_once("funcion.class.php");
	
	class bloqueReportes
	{

		public function __construct($configuracion){
			
		}

		public function  html($configuracion){
			
			
			$funcion=new funcion_Reportes();
			
			$this->acceso=$funcion->conectarDB($configuracion,"");

			$timeIniScript = microtime(true);
			
			$variable="";
			foreach($_REQUEST as $clave=>$valor){
				$variable.="&".$clave."=".$valor;
			}
			if(isset($_REQUEST['no_pagina'])){ unset($_REQUEST['no_pagina']); }
				
			$cadena_sql="SELECT ";
			$cadena_sql.="id_reporte, ";
			$cadena_sql.="titulo, ";
			$cadena_sql.="tipo_usuario, ";
			$cadena_sql.="conexion, ";
			$cadena_sql.="dbms, ";
			$cadena_sql.="tabla, "; //5
			$cadena_sql.="primaria, "; //6
			$cadena_sql.="campos, "; //7
			$cadena_sql.="filtros, "; //8
			$cadena_sql.="descripcion, "; //9
			$cadena_sql.="relaciones, "; //10
			$cadena_sql.="crud, "; //11
			$cadena_sql.="unico "; //12
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."reportes ";
			$cadena_sql.="WHERE  ";
			$cadena_sql.="estado='A' ";
			$cadena_sql.="AND  ";
			$cadena_sql.="tipo_usuario=".$_REQUEST['tipoUser'];
			//echo $cadena_sql;

			if(isset($_REQUEST['opcReporte']) AND $_REQUEST['opcReporte']<>""){
			
				$cadena_sql.=" AND  ";
				$cadena_sql.="id_reporte=".$_REQUEST['opcReporte'];
				
				
				
				$resultado=$funcion->ejecutarSQL($configuracion, $this->acceso, $cadena_sql,"busqueda");

				$crud=new crud($configuracion,$resultado[0][1],$resultado[0][4],$resultado[0][3]);
				
				$crud->setTabla($resultado[0][5],$resultado[0][6]);
				
				$crud->setFormulario($configuracion["host"]."/weboffice/index.php?no_pagina=adminReportes".$variable);
				
				if($resultado[0][12]=="S"){
					$crud->setDistinct('true');
				}
				
				$campos=explode("@@",$resultado[0][7]);
				foreach($campos as $campo){
					$valorc=explode(";",$campo);
					$valorc[2]=isset($valorc[2])?$valorc[2]:"";
					$valorc[3]=isset($valorc[3])?$valorc[3]:"NULL";
					$crud->setCampo($valorc[0],$valorc[1],$valorc[2],$valorc[3]);
				}
				
				if($resultado[0][10]<>""){
					$relaciones=explode("@@",$resultado[0][10]);
					foreach($relaciones as $campo){
						$valorr=explode(";",$campo);
						$crud->setRelacion($valorr[0],$valorr[1]);
					}
				}
				
				if($resultado[0][8]<>""){
					$filtros=explode("@@",$resultado[0][8]);
					foreach($filtros as $filtro){
						$valorf=explode(";",$filtro);
						if(isset($valorf[4])){
							$valorf[1]=$funcion->rescatarVariable($valorf[4],$resultado[0][3],$configuracion,$this->acceso);
						}
							$crud->setFiltro($valorf[0],$valorf[1],$valorf[2],$valorf[3]);
							
					}
				}
				
				
				$crud->armarCrud($configuracion,'false','true','false','false');
			
			}else{
			
				$resultado=$funcion->ejecutarSQL($configuracion, $this->acceso, $cadena_sql,"busqueda");
				
				if(count($resultado)<>0){
					$i=0;
					echo "<div class='bloquelateralencabezado'>Seleccione el Reporte que desea visualizar</div><br/>";
					echo "<ul>";
					while(isset($resultado[$i][0])){
						echo "<li>";
						echo "<b><a class='texto_subtitulo' href='".$configuracion["host"]."/weboffice/index.php?no_pagina=adminReportes&opcReporte=".$resultado[$i][0].$variable."'>".$resultado[$i][1]." </a></b> ";
						echo "<br/><span class='texto_elegante'><b> Descripcion: </b>".$resultado[0][9]."</span>";
						echo "</li>";
						$i++;
					}
					echo "</ul>";
				}else{
					echo "<div class='tabla_alerta  ' >Actualmente no tiene reportes disponibles</div>";
				}
			}
			
			
			
			/*
			
			$crud=new crud($configuracion,'REPORTE DE PERMANENCIA','oracle','coordinador');
			
			$crud->setTabla("V_PERMANENCIA","(COHORTE||ESTADO||ESTUDIANTES||PROMEDIO_SEMESTRE_CON_NOTAS)");
			$crud->setFormulario($configuracion["host"]."/weboffice/index.php?no_pagina=adminReportes");
			$crud->setCampo("COD. FAC","CODIGO_FACULTAD","CODIGO_FACULTAD","CODIGO_FACULTAD"," width:50,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("FACULTAD","FACULTAD","FACULTAD","FACULTAD"," width:200,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("COD. CARRERA","CODIGO_CARRERA","CODIGO_CARRERA","CODIGO_CARRERA"," width:50,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("CARRERA","CARRERA","CARRERA","CARRERA"," width:200,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("COHORTE","COHORTE","COHORTE","COHORTE"," width:100,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("ESTADO","ESTADO","ESTADO","ESTADO"," width:150,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("ESTUDIANTES","ESTUDIANTES","ESTUDIANTES","ESTUDIANTES"," width:50,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("PROMEDIO_SEMESTRE_CON_NOTAS","PROMEDIO_SEMESTRE_CON_NOTAS","PROMEDIO_SEMESTRE_CON_NOTAS","PROMEDIO_SEMESTRE_CON_NOTAS"," width:100,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setFiltro("CODIGO_CARRERA","25","igual","V_PERMANENCIA");
			
			$crud->armarCrud($configuracion,'false','true','false','false');
			
			*/

			/*
			$crud=new funcion_Reportes($configuracion,'REPORTE DE PERMANENCIA','oracle','coordinador');
			
			$crud->setTabla("ACEST","EST_COD");
			$crud->setFormulario($configuracion["host"]."/weboffice/index.php?no_pagina=adminReportes");
			$crud->setCampo("CODIGO","EST_COD","EST_COD","EST_COD"," width:150,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("IDENTIFICACION","EST_NRO_IDEN","EST_NRO_IDEN","EST_NRO_IDEN"," width:150,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setCampo("NOMBRE","EST_NOMBRE","EST_NOMBRE","EST_NOMBRE"," width:450,editable:true,edittype:'select',editoptions:{value:':[seleccione]'},editrules:{required:true},searchtype:'number'");
			$crud->setFiltro("EST_CRA_COD","25","igual","ACEST");		
			
			*/

			

			
			$timeFin = microtime(true);


			
			
		}	
	}



	$esteBloque=new bloqueReportes($configuracion);

	$esteBloque->html($configuracion);

	
	