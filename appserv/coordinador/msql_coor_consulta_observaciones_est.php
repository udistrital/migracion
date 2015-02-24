<?PHP
//Llamado de coor_actualiza_datos_est.php
$consulta = "SELECT EOB_EST_COD CODIGO,
                EOB_APE_ANO ANO,
                EOB_APE_PER PERIODO,
                EOB_CONSECUTIVO CONSECUTIVO,
                EOB_OBSERVACION OBSERVACION,
                EOB_ESTADO ESTADO,
                EOB_CRA_COD CARRERA
                FROM ACESTOBS
                WHERE EOB_EST_COD=".$_REQUEST['estcod']."
                AND (EOB_CRA_COD IN (SELECT cra_cod
                    FROM accra
                    WHERE cra_emp_nro_iden = $usuario)
                    OR EOB_CRA_COD IN (SELECT DISTINCT usuweb_codigo_dep DEPENDENCIA
                    FROM geusuweb
                    LEFT OUTER JOIN accra ON usuweb_codigo_dep=cra_cod
                    WHERE usuweb_codigo=$usuario
                    AND usuweb_estado='A'
                    AND usuweb_tipo='".$_SESSION['usuario_nivel']."'
                    AND (NVL(TO_CHAR(usuweb_fecha_fin,'yyyymmddhh24miss'),0)>=TO_CHAR(sysdate,'yyyymmddhh24miss') OR usuweb_fecha_fin is null ) )) ";

?>
