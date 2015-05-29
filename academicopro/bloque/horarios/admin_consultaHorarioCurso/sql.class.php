<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminHorarios extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function cadena_sql($configuracion, $tipo, $variable="") {

        switch ($tipo) {

        
            case "datosCoordinadorCarrera":
                $this->cadena_sql = "SELECT cra_cod, ";
                $this->cadena_sql.="cra_nombre, ";
                $this->cadena_sql.="cra_dep_cod ";
                $this->cadena_sql.="FROM accra ";
                $this->cadena_sql.="WHERE cra_emp_nro_iden='" . $variable . "' ";
                break;

            case "proyecto_curricular":
                $this->cadena_sql=" SELECT CRA_COD, CRA_NOMBRE ";
                $this->cadena_sql.="FROM accra ";
                $this->cadena_sql.="WHERE cra_cod = ".$variable;
                $this->cadena_sql.=" ORDER BY CRA_NOMBRE";
                break;

            case "espacios_academicos":
                $this->cadena_sql=" SELECT asi_cod, asi_nombre, pen_sem FROM acasi, acpen ";
                $this->cadena_sql.=" WHERE pen_nro=".$variable[0]. " AND pen_cra_cod=".$variable[1];
                $this->cadena_sql.=" AND pen_asi_cod=asi_cod ";
		$this->cadena_sql.=" AND asi_estado='A' ";
		$this->cadena_sql.=" AND pen_estado='A' ";
                $this->cadena_sql.=" ORDER BY pen_sem,asi_cod";
                break;

            case "rescatarAsignatura":
                $this->cadena_sql=" SELECT asi_cod COD_ESPACIO , ";
                $this->cadena_sql.=" asi_nombre NOM_ESPACIO ";
                $this->cadena_sql.=" FROM acasi, acpen ";
                $this->cadena_sql.=" WHERE asi_cod=".$variable['espacio']. " AND pen_cra_cod=".$variable['proyecto'];
                $this->cadena_sql.=" AND pen_asi_cod=asi_cod ";
		$this->cadena_sql.=" AND asi_estado='A' ";
		$this->cadena_sql.=" AND pen_estado='A' ";
                $this->cadena_sql.=" ORDER BY pen_sem,asi_cod";
                break;
				
            case "periodo":
                $this->cadena_sql=" SELECT DISTINCT ape_ano ANIO, ";
                $this->cadena_sql.=" ape_per PERIODO, ";
                $this->cadena_sql.=" ape_estado ESTADO ";
                $this->cadena_sql.="  FROM acasperi ";
                if(isset($variable['periodo']))
                    {//$this->cadena_sql.=" WHERE (ape_estado != 'I' and ape_estado != 'P' )";
                      $this->cadena_sql.=" WHERE ";
                      $this->cadena_sql.=" ape_ano='".$variable['anio'] ."'";
                      $this->cadena_sql.=" AND ape_per='".$variable['periodo'] ."'";
                    }
                else    
                    {
                      $this->cadena_sql.=" WHERE ape_estado IN ('A','V','X')";
                      $this->cadena_sql.=" ORDER BY ape_estado  ASC";
                    }
                break;

            case "periodoconanterior":
                $this->cadena_sql=" SELECT ape_ano, ape_per, ape_estado FROM acasperi ";
                $this->cadena_sql.=" WHERE ape_estado <> 'I' ";
                $this->cadena_sql.=" ORDER BY ape_estado  ASC";
                break;
			
            case "hora":
                $this->cadena_sql="SELECT HOR_COD HORA_C, ";
                $this->cadena_sql.="HOR_LARGA HORA_L ";
                $this->cadena_sql.=" FROM gehora ";
                $this->cadena_sql.=" WHERE HOR_COD<=21 ORDER BY HOR_COD ";
                break;

            case "infoAsignatura":
                $this->cadena_sql="SELECT pen_sem FROM acasi, acpen ";
                $this->cadena_sql.="WHERE asi_cod=".$variable[0]." and pen_cra_cod=".$variable[1]." ";
                $this->cadena_sql.="AND asi_cod=pen_asi_cod ";
                break;

            case "infoCurso":
                $this->cadena_sql="SELECT cur_id CURSO,";
                $this->cadena_sql.=" cur_nro_cupo CUPOS,   ";
                $this->cadena_sql.=" cur_cap_max MAX_CAPACIDAD ";
                $this->cadena_sql.=" FROM accursos ";
                $this->cadena_sql.=" WHERE cur_asi_cod=".$variable['espacio'];
                $this->cadena_sql.=" AND cur_grupo=".$variable['grupo'];
                $this->cadena_sql.=" AND cur_cra_cod=".$variable['proyecto'];
                $this->cadena_sql.=" AND cur_ape_ano=".$variable['anio'];
                $this->cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                break;

            case "carreraCurso":
                $this->cadena_sql="SELECT cur_id CURSO,";
                $this->cadena_sql.=" cur_cra_cod CARRERA ";
                $this->cadena_sql.=" FROM accursos ";
                $this->cadena_sql.=" WHERE cur_asi_cod=".$variable['espacio'];
                $this->cadena_sql.=" AND cur_grupo=".$variable['grupo'];
                $this->cadena_sql.=" AND cur_ape_ano=".$variable['anio'];
                $this->cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                break;

            case "insertarCurso":

                $this->cadena_sql=" INSERT INTO accursos ";
                $this->cadena_sql.="(cur_id,cur_ape_ano, cur_ape_per,cur_asi_cod,cur_grupo,cur_cra_cod,cur_nro_cupo,cur_nro_ins,cur_estado,cur_dep_cod,cur_hor_alternativo,";
                $this->cadena_sql.="cur_exa,";
                $this->cadena_sql.="cur_hab,";
                $this->cadena_sql.="cur_tipo,";
                $this->cadena_sql.="cur_cap_max) ";
                $this->cadena_sql.=" VALUES(";
                $this->cadena_sql.="'".$variable['curso']."',";
                $this->cadena_sql.="'".$variable['anio']."',";
                $this->cadena_sql.="'".$variable['periodo']."',";
                $this->cadena_sql.="'".$variable['espacio']."',";
                $this->cadena_sql.="'".$variable['grupo']."',";
                $this->cadena_sql.="'".$variable['proyecto']."',";
                $this->cadena_sql.="'".$variable['cupos']."',";
                $this->cadena_sql.="'0',";
                $this->cadena_sql.="'".$variable['estado']."',";
                $this->cadena_sql.="(SELECT cra_dep_cod FROM accra WHERE cra_cod='".$variable['proyecto']."'),";
                $this->cadena_sql.="'0',";
                $this->cadena_sql.="'30',";
                $this->cadena_sql.="'70',";
                $this->cadena_sql.="".$variable['tipocurso'].",";
                $this->cadena_sql.="'".$variable['max_capacidad']."')";
                break;

            case "actualizarCurso":
                $this->cadena_sql=" UPDATE accursos ";
                $this->cadena_sql.=" SET cur_nro_cupo = ".$variable['cupos'];
                $this->cadena_sql.=" ,cur_cap_max = ".$variable['max_capacidad'];
                $this->cadena_sql.=" WHERE cur_ape_ano = '".$variable['anio']."'";
                $this->cadena_sql.=" AND cur_ape_per = '".$variable['periodo']."'";
                $this->cadena_sql.=" AND cur_asi_cod = '".$variable['espacio']."'";
                $this->cadena_sql.=" AND cur_cra_cod = '".$variable['proyecto']."'";
                $this->cadena_sql.=" AND cur_grupo = '".$variable['grupo']."'";
                break;

            case "verHorarioTemp":

                $this->cadena_sql="SELECT ";
                $this->cadena_sql.=" SEDE.SED_NOMBRE NOM_SEDE,";
                $this->cadena_sql.=" SALON.SAL_COD SALON_OLD,";
                $this->cadena_sql.=" SALON.SAL_ID_ESPACIO SALON_NVO, ";
                $this->cadena_sql.=" SALON.SAL_NOMBRE NOM_SALON , ";
                $this->cadena_sql.=" SALON.SAL_EDIFICIO ID_EDIFICIO, ";
                $this->cadena_sql.=" EDIF.EDI_NOMBRE NOM_EDIFICIO ";
                $this->cadena_sql.=" FROM achorarios HOR_G ";
                $this->cadena_sql.=" INNER JOIN gesalones SALON ON HOR_G.HOR_SAL_ID_ESPACIO=SALON.SAL_ID_ESPACIO";
                $this->cadena_sql.=" INNER JOIN geedificio EDIF ON SALON.SAL_EDIFICIO=EDIF.EDI_COD";
                $this->cadena_sql.=" INNER JOIN gesede SEDE ON EDIF.EDI_SED_ID=SEDE.SED_ID";
                $this->cadena_sql.=" WHERE hor_id_curso=".$variable['curso']." AND hor_dia_nro=".$variable['dia']." AND hor_hora=".$variable['hora'];
                break;
	    
	      /* borreme despues de pasar pruebas
                
              case "verHorarioTemp":

                $this->cadena_sql="SELECT HOR_G.HOR_SED_COD COD_SEDE, ";
                $this->cadena_sql.="SEDE.SED_ID NOM_SEDE, ";
                $this->cadena_sql.="SALON.SAL_COD SALON_OLD,";
                $this->cadena_sql.="SALON.SAL_ID_ESPACIO SALON_NVO, ";
                $this->cadena_sql.="SALON.SAL_NOMBRE NOM_SALON , ";
                $this->cadena_sql.="SALON.SAL_EDIFICIO ID_EDIFICIO, ";
                $this->cadena_sql.="EDIF.EDI_NOMBRE NOM_EDIFICIO ";
                $this->cadena_sql.="FROM achorarios HOR_G ";
                $this->cadena_sql.=" INNER JOIN gesede SEDE ON HOR_G.HOR_SED_COD=SEDE.SED_COD";
                $this->cadena_sql.=" INNER JOIN mntge.gesalones SALON ON HOR_G.HOR_SAL_ID_ESPACIO=SALON.SAL_ID_ESPACIO";
                $this->cadena_sql.=" INNER JOIN geedificio EDIF ON SALON.SAL_EDIFICIO=EDIF.EDI_COD";
                $this->cadena_sql.=" WHERE hor_asi_cod=".$variable['espacio']." AND hor_nro=".$variable['grupo']." AND hor_dia_nro=".$variable['dia']." AND hor_hora=".$variable['hora'];
                $this->cadena_sql.=" AND hor_ape_ano=".$variable['anio']." AND hor_ape_per=".$variable['periodo'];
                break;*/              
                
                
                
            case "consultaDependencia":
                $this->cadena_sql="SELECT emp_dep_cod FACULTAD ";
                $this->cadena_sql.="FROM peemp ";
                $this->cadena_sql.="WHERE emp_nro_iden='".$variable."' ";
                break;
				
             case "infoGrupo":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="cur_id CURSO ";
                $this->cadena_sql.=",cur_asi_cod ASI_CODIGO ";
                $this->cadena_sql.=",asi_nombre ASI_NOMBRE ";
                $this->cadena_sql.=",cur_nro_cupo CUPOS ";
                $this->cadena_sql.=",cur_ape_ano ANIO ";
                $this->cadena_sql.=",cur_ape_per PERIODO ";
                $this->cadena_sql.=",cur_cra_cod PROYECTO ";
                $this->cadena_sql.=",cur_cap_max MAX_CAPACIDAD ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="accursos ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="asi_cod=CUR_ASI_COD ";
                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                $this->cadena_sql.="and cur_asi_cod='".$variable['espacio']."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                if (isset($variable['grupo']))
                    {$this->cadena_sql.="and cur_grupo='".$variable['grupo']."' ";}
                break;
            
            case "CursosEspacio":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="cur_id CURSO ";
                $this->cadena_sql.=",cur_grupo GRUPO ";
                $this->cadena_sql.=",cur_asi_cod ASI_CODIGO ";
                $this->cadena_sql.=",cur_ape_ano ANIO ";
                $this->cadena_sql.=",cur_ape_per PERIODO ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="accursos ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="asi_cod=CUR_ASI_COD ";
                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                $this->cadena_sql.="and cur_asi_cod='".$variable['espacio']."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                $this->cadena_sql.="ORDER BY cur_grupo ASC ";
                break;    

            case "eliminaCurso":
                $this->cadena_sql="DELETE from ";
                $this->cadena_sql.="accursos ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="cur_cra_cod='".$variable['proyecto']."' ";
                $this->cadena_sql.="AND cur_asi_cod='".$variable['espacio']."' ";
                $this->cadena_sql.="AND cur_grupo='".$variable['grupo']."' ";
                $this->cadena_sql.="AND cur_ape_ano='".$variable['anio']."' ";
                $this->cadena_sql.="AND cur_ape_per='".$variable['periodo']."' ";
                $this->cadena_sql.="AND cur_id='".$variable['curso']."' ";
                break;
        
            case "infoHorario":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="HOR_SAL_ID_ESPACIO SALON, ";
                $this->cadena_sql.="DIA_NOMBRE DIA, ";
                $this->cadena_sql.="HOR_HORA HORA ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="gedia,achorarios ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="dia_cod=HOR_DIA_NRO ";
                $this->cadena_sql.="AND hor_id_curso='".$variable['curso']."' ";
                break;

            case "infoCarga":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="'S' ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="accursos ";
                $this->cadena_sql.="INNER JOIN accra ON cra_cod=cur_cra_cod ";
                $this->cadena_sql.="INNER JOIN achorarios ON hor_id_curso=cur_id ";
                $this->cadena_sql.="INNER JOIN accargas ON car_hor_id=hor_id ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="cur_id='".$variable['curso']."' ";
                if(isset($variable['espacio'])&&$variable['espacio']!='')
                {
                    $this->cadena_sql.="AND cur_asi_cod='".$variable['espacio']."' ";
                }
                if(isset($variable['dia'])&&$variable['dia']!='')
                {
                    $this->cadena_sql.="AND hor_dia_nro='".$variable['dia']."' ";
                    $this->cadena_sql.="AND hor_hora='".$variable['hora']."' ";
                }
                $this->cadena_sql.="AND cur_ape_ano='".$variable['anio']."' ";
                $this->cadena_sql.="AND cur_ape_per='".$variable['periodo']."' ";
                $this->cadena_sql.="AND car_estado= 'A' ";
                break;

            case "infoInscritos":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="count(ins_est_cod) INSCRITOS ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="acins,accursos ";
                $this->cadena_sql.="WHERE ins_asi_cod=cur_asi_cod ";
                $this->cadena_sql.="AND ins_ano=cur_ape_ano ";
                $this->cadena_sql.="AND ins_per=cur_ape_per ";
                //$this->cadena_sql.="AND ins_cra_cod=cur_cra_cod ";
                $this->cadena_sql.="AND ins_gr=cur_id ";
                $this->cadena_sql.="AND cur_id='".$variable['curso']."' ";				
                break;

            case "valida_fecha":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24mmss') FEC_INI, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                $this->cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24mmss') FEC_FIN, ";
                $this->cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') FEC_FIN_DIA ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="accaleventos ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="ACE_ANIO =".$variable['anio'];
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="ACE_PERIODO =".$variable['periodo'];
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="ACE_CRA_COD =".$variable['proyecto'];
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="ACE_COD_EVENTO = 87 ";
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="'".$variable['fecha']."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
                break;

            case "infoSalon":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="SAL_OCUPANTES CAPACIDAD, ";
                $this->cadena_sql.="SAL_SED_COD COD_SEDE ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="gesalones ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="SAL_SED_COD='".$variable['sede']."' ";
                $this->cadena_sql.="AND ";
                $this->cadena_sql.="SAL_ID_ESPACIO='".$variable['salon']."' ";
                break;
            
            case "infoDocente":
                $this->cadena_sql="SELECT DISTINCT ";
                $this->cadena_sql.="doc.doc_nro_iden DOC_DOCENTE  ";
                $this->cadena_sql.=",doc.doc_nombre NOM_DOCENTE ";
                $this->cadena_sql.=",doc.doc_apellido APE_DOCENTE ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="acdocente doc ";
                $this->cadena_sql.="INNER JOIN accargas carg on carg.car_doc_nro=doc.doc_nro_iden ";
                $this->cadena_sql.="INNER JOIN achorarios on hor_id=car_hor_id ";
                $this->cadena_sql.="INNER JOIN accursos on cur_id=hor_id_curso ";
                $this->cadena_sql.="WHERE cur_ape_ano='".$variable['anio']."' ";
                $this->cadena_sql.="AND cur_ape_per='".$variable['periodo']."' ";
	        $this->cadena_sql.="AND cur_cra_cod='".$variable['proyecto']."' ";
                $this->cadena_sql.="AND cur_asi_cod='".$variable['espacio']."' ";
                $this->cadena_sql.="AND cur_id='".$variable['curso']."' ";
                $this->cadena_sql.="AND car_estado='A' ";
                $this->cadena_sql.="ORDER BY doc.doc_apellido ";
                
                break;
            
         case "info_proyecto_curricular":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="cra_cod CODIGO, ";
                $this->cadena_sql.="cra_abrev NOMBRE, ";
                $this->cadena_sql.="cra_dep_cod FACULTAD ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="ACCRA ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="CRA_COD='".$variable."'";
                break;
            
         case "sede":
                $this->cadena_sql="SELECT sed_cod COD_SEDE ";
                $this->cadena_sql.=",sed_id NOMC_SEDE ";
                $this->cadena_sql.=",sed_nombre NOML_SEDE ";
                $this->cadena_sql.=",sed_id ID_SEDE ";
                $this->cadena_sql.=" FROM gesede  ";
                $this->cadena_sql.=" WHERE sed_estado = 'A' ";
                $this->cadena_sql.=" AND sed_id IS NOT null ";
                if($variable['sede']>=0){
                    $this->cadena_sql.=" AND sed_id='".$variable['sede']."'";
                }
                $this->cadena_sql.=" ORDER BY sed_nombre ";                       
                break;
        
	case "sede_codigo":
		$this->cadena_sql="SELECT sed_cod COD_SEDE ";
		$this->cadena_sql.=",sed_id NOMC_SEDE ";
		$this->cadena_sql.=",sed_nombre NOML_SEDE ";
		$this->cadena_sql.=",sed_id ID_SEDE ";
		$this->cadena_sql.=" FROM gesede  ";
		$this->cadena_sql.=" WHERE sed_estado = 'A' ";
		$this->cadena_sql.=" AND sed_id IS NOT null ";
		$this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        
                break;
     
	 case "salones":
		 $this->cadena_sql=" select 
                            sal_id_espacio COD_SALON,
                            sal_nombre NOM_SALON,
                            sal_ocupantes CUPOS,
                            sal_tipo TIPO_SALON,
                            sal_edificio ID_EDIFICIO,
                            edi_nombre NOM_EDIFICIO,
                            ges_asigna_clase ASIGNA_CLASE
                            from gesalones, geedificio, gesubtipo_espacio
                            where SAL_ESTADO='A' AND sal_edificio=edi_cod
                            AND sal_sed_id='".$variable['sede']."'
                            AND (sal_ocupantes >=0
                            OR sal_id_espacio ='PAS0000000')
                            AND sal_cod_sub=ges_cod_sub
                            AND (ges_asigna_clase=1 or ges_cod_sub=0)
                            ORDER BY NOM_EDIFICIO,NOM_SALON";
                            //el siguiente filtro se elimina para que presente todos los salones 2013/12/23
                            //AND (sal_ocupantes >=".$variable['capacidad']." AND sal_ocupantes <".($variable['capacidad']*1.5)."
                            //este filtro solo se deberia activar para rescatar solo salones disponibles
                            
			    /* if($valor!='PAS'){
				//salones diferentes a PAS (Por ASignar)
				//filtro para salones con capacidad > 0, excepto el sin asignar  
				//restriccion para busqueda salon segun capacidad
				//$busqueda.=" AND sal_ocupantes >=".$capacidad." AND sal_ocupantes <".($capacidad*1.5);
				
				$this->cadena_sql.=" AND sal_ocupantes>1  ";
				$this->cadena_sql.=" AND sal_id_espacio not in
					    (SELECT hor_sal_id_espacio
						FROM achorario_2012,gesede
						    WHERE
						    hor_sed_cod=sed_cod AND
						    sed_id='".$valor."' AND
						    hor_dia_nro=".$dia." AND
						    hor_hora=".$hora." AND
						    hor_ape_ano=".$año." AND
						    hor_ape_per=".$periodo." )
						ORDER BY sal_cod"; 
			     }   */      

                break;               
	 case "salones_ocupados":
		$this->cadena_sql="SELECT hor_dia_nro||'-'||hor_hora cod_hora ";
		$this->cadena_sql.=" FROM achorarios ";
		$this->cadena_sql.=" INNER JOIN accursos ON  cur_id=hor_id_curso ";
		$this->cadena_sql.=" WHERE hor_sal_id_espacio='".$variable['cod_salon']."' ";
		$this->cadena_sql.=" AND cur_ape_ano=".$variable['anio'];
		$this->cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
		//$this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        
                break;
            
	 case "salon_ocupado":
		$this->cadena_sql="SELECT hor_dia_nro||'-'||hor_hora cod_hora, ";
		$this->cadena_sql.=" hor_id_curso ";
		$this->cadena_sql.=" FROM achorarios ";
		$this->cadena_sql.=" INNER JOIN accursos ON  cur_id=hor_id_curso ";
		/*$this->cadena_sql.=" WHERE hor_ape_ano='".$variable['anio']."' ";
		$this->cadena_sql.=" AND hor_ape_per='".$variable['periodo']."' ";*/
		$this->cadena_sql.=" WHERE cur_ape_ano=".$variable['anio'];
		$this->cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
		$this->cadena_sql.=" AND hor_sal_id_espacio='".$variable['cod_salon']."' ";
		$this->cadena_sql.=" AND hor_sal_id_espacio<>'PAS0000000'";
		$this->cadena_sql.=" AND (hor_dia_nro||'-'||hor_hora)='".$variable['cod_hora']."' ";
		//$this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        
                break;
            
	 case "curso_con_horario":
		$this->cadena_sql="SELECT hor_dia_nro||'-'||hor_hora cod_hora, ";
		$this->cadena_sql.=" hor_id_curso  ";
		$this->cadena_sql.=" FROM achorarios ";
		$this->cadena_sql.=" WHERE hor_id_curso='".$variable['curso']."' ";
		$this->cadena_sql.=" AND (hor_dia_nro||'-'||hor_hora)='".$variable['cod_hora']."' ";
		//$this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        
                break;
	 
	 case "actualizar_horario":
		$this->cadena_sql="UPDATE achorarios ";
		$this->cadena_sql.=" SET hor_sal_id_espacio='".$variable['salon']."' ";
		$this->cadena_sql.=" WHERE hor_id_curso='".$variable['curso']."' ";
		$this->cadena_sql.=" AND (hor_dia_nro||'-'||hor_hora)='".$variable['cod_hora']."' ";
		//$this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        
                break;
	 
	 case "registrar_horario":
                $this->cadena_sql='INSERT INTO achorarios ';
                $this->cadena_sql.='(hor_id,hor_id_curso,hor_dia_nro,hor_hora,hor_alternativa,hor_sal_id_espacio,hor_estado)';
                $this->cadena_sql.=' VALUES(';
                $this->cadena_sql.="".$variable['id_horario'].", ";
                $this->cadena_sql.="".$variable['curso'].", ";
                $this->cadena_sql.="".$variable['dia'].", ";
                $this->cadena_sql.="".$variable['hora'].", ";
                $this->cadena_sql.="'0', ";
                $this->cadena_sql.="'".$variable['salon']."', ";
                $this->cadena_sql.="'".$variable['estado']."'";
                $this->cadena_sql.=")";
                break;
         
         case "borrar_horario":
                $this->cadena_sql="DELETE ";
                $this->cadena_sql.=" FROM achorarios ";
                $this->cadena_sql.=" WHERE hor_id_curso='".$variable['curso']."' ";
                $this->cadena_sql.=" AND (hor_dia_nro||'-'||hor_hora)='".$variable['cod_hora']."' ";
		//$this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        
                break;

        case 'siguienteCurso':
                $this->cadena_sql="select nextval('cursos')";
                break; 

        case 'siguienteHorario':
                $this->cadena_sql="select nextval('horarios')";
                break; 

        case 'consultarInscripcionAutomatica':
                $this->cadena_sql= "SELECT ace_cod_evento EVENTO, ace_cra_cod PROYECTO, ace_fec_ini INICIO, ace_fec_fin FIN";
                $this->cadena_sql.=" FROM accaleventos";
                $this->cadena_sql.=" WHERE ace_cra_cod=" . $variable['codProyecto'];
                $this->cadena_sql.=" AND ace_cod_evento=14";
                $this->cadena_sql.=" AND ace_anio=".$variable['ano'];
                $this->cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $this->cadena_sql.=" AND ace_estado='A'";
            break;

            
        }

        return $this->cadena_sql;
    }

}

?>
