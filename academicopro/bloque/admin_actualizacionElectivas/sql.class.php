<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_actualizacionElectivas extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

    function cadena_sql($configuracion,$tipo,$variable="") {

        switch($tipo) {

            case 'proyectos_curriculares':

                $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from accra ";
                $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="where pen_nro>200 ";
                $cadena_sql.=" order by 3";

                break;

            case 'espacios_academicosPlan':

                $cadena_sql="SELECT id_planEstudio, EA.id_espacio, EA.espacio_nombre ";
                $cadena_sql.="FROM sga_planEstudio_espacio PEE ";
                $cadena_sql.="inner join sga_espacio_academico EA on EA.id_espacio=PEE.id_espacio ";
                $cadena_sql.="WHERE id_planEstudio =".$variable;
                $cadena_sql.=" AND id_clasificacion";
                $cadena_sql.=" IN (";
                $cadena_sql.=" '3', '4'";
                $cadena_sql.=" )";

                break;

            case 'espacios_cargado':
   
                $cadena_sql="select pen_cra_cod, pen_asi_cod, pen_nro, asi_nombre ";
                $cadena_sql.="FROM acpen  ";
                $cadena_sql.="inner join acasi on acpen.pen_asi_cod=acasi.asi_cod ";
                $cadena_sql.="WHERE pen_asi_cod=".$variable[0];
                $cadena_sql.=" and pen_nro=".$variable[1];

                break;

            case 'actualizar_espacioElectiva':

                $cadena_sql="update acpen ";
                $cadena_sql.="set pen_ind_ele='S' ";
                $cadena_sql.="WHERE pen_asi_cod=".$variable[0];
                $cadena_sql.=" and pen_nro=".$variable[1];

                break;

            case 'actualizar_nombreElectiva':

                $cadena_sql="update acasi ";
                $cadena_sql.="set asi_nombre='".$variable[2]."' ";
                $cadena_sql.="WHERE asi_cod=".$variable[0];

                break;



        }#Cierre de switch

        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
