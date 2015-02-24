<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarTablaHomologaciones extends sql
{       
  private $configuracion;
  function  __construct($configuracion)
  {
        $this->configuracion=$configuracion;
  }
	function cadena_sql($opcion,$variable="")
	{

		switch($opcion)
		{
		    
			case 'consultaTablaHomologacion':
                            
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="hom_id            AS ID_HOM, ";
                            $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                            $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                            $cadena_sql.="asi1.asi_nombre   AS NOM_ASI_PPAL, ";
                            $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                            $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                            $cadena_sql.="asi2.asi_nombre   AS NOM_ASI_HOM, ";
                            $cadena_sql.="TRIM(hom_estado)        AS ESTADO, ";
                            $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                            $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION, ";
                            $cadena_sql.="hpo_porcentaje    AS PORCENTAJE, ";
                            $cadena_sql.="hpo_requiere_aprobar AS REQ_APROBAR, ";
                            $cadena_sql.="hpo_estado        AS ESTADO_PORCENTAJE,";
                            $cadena_sql.="hpo_anio          AS ANIO_PORCENTAJE, ";
                            $cadena_sql.="hpo_periodo       AS PERIODO_PORCENTAJE ";
                            $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                            $cadena_sql.="INNER JOIN acasi asi1 ON hom_asi_cod_ppal=asi1.asi_cod  ";
                            $cadena_sql.="INNER JOIN acasi asi2 on hom_asi_cod_hom=asi2.asi_cod  ";
                            $cadena_sql.="LEFT OUTER JOIN ACHOMOLOGACION_PORCENTAJES ON hom_id=hpo_id_hom AND hpo_estado='A' ";
                            $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto'];
                            $cadena_sql.=" AND hom_tipo_hom=".$variable['tipo'];
                            if($variable['tipo']==1 || $variable['tipo']==2)
                                $cadena_sql.=" ORDER BY FEC_REG, COD_ASI_PPAL ";
                            else
                             $cadena_sql.=" ORDER BY COD_ASI_PPAL ";
                            
                        break;

                       case 'consultaProyectosCoordinador':
                            
                            $cadena_sql="SELECT cra_cod, ";
                            $cadena_sql.="cra_nombre  ";
                            $cadena_sql.="FROM accra  ";
                            $cadena_sql.="WHERE CRA_EMP_NRO_IDEN = ". $variable['identificacion'];
                            $cadena_sql.=" AND cra_estado = 'A'";
                            if ($variable['cod_proyecto'])
                                $cadena_sql.=" AND cra_cod= ".$variable['cod_proyecto'];
                            
                        break;
			
		}
		//echo "cadena".$cadena_sql."<br>";exit;
		return $cadena_sql;
	}


}
?>