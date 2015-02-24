<?PHP
    //Llamado de coor_actualiza_datos_est.php
    $cambios='';
    $consecutivo=0;
    $cadena_sql=" SELECT EOB_CONSECUTIVO CONSECUTIVO";
    $cadena_sql.=" FROM ACESTOBS";
    $cadena_sql.=" WHERE EOB_EST_COD ='".$_REQUEST['estcod']."' ";
    $cadena_sql.=" AND (EOB_CRA_COD IN (SELECT cra_cod FROM accra WHERE cra_emp_nro_iden = $usuario)";
    $cadena_sql.=" OR EOB_CRA_COD IN (SELECT DISTINCT usuweb_codigo_dep DEPENDENCIA
                    FROM geusuweb
                    LEFT OUTER JOIN accra ON usuweb_codigo_dep=cra_cod
                    WHERE usuweb_codigo=$usuario
                    AND usuweb_estado='A'
                    AND usuweb_tipo='".$_SESSION['usuario_nivel']."'
                    AND (NVL(TO_CHAR(usuweb_fecha_fin,'yyyymmddhh24miss'),0)>=TO_CHAR(sysdate,'yyyymmddhh24miss') OR usuweb_fecha_fin is null ) )) ";
    $cadena_sql.=" AND EOB_APE_ANO ='".$_REQUEST['ano']."' ";
    $cadena_sql.=" AND EOB_APE_PER ='".$_REQUEST['periodo']."' ";
    $cadena_sql.=" ORDER BY EOB_CONSECUTIVO DESC";
    $row_qry_obs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
    if (isset($row_qry_obs)&&!empty($row_qry_obs)&&$accesoOracle)
    {
        $consecutivo=$row_qry_obs[0][0]+1;
    }elseif(isset($row_qry_obs)&&$accesoOracle)
        {
            $consecutivo=1;
        }else
            {
                die("<h3>No se pudo insertar el registro. Intente nuevamente por favor.</h3>");
                exit;
            }
            $observacionEst=trim($_REQUEST['obs']);
    if (strlen($observacionEst)>256)
    {
        die("<h3>No se pudo insertar el registro. La Observaci&oacute;n no puede tener m&aacute;s de 256 caracteres.</h3>");
        exit;
    }
    if ($observacionEst=='')
    {
        die("<h3>No se pudo insertar el registro. la Observaci&oacute;n no tiene texto.</h3>");
        exit;
    }
    $qery="INSERT INTO ";
    $qery.="ACESTOBS ";
    $qery.=" (EOB_EST_COD,EOB_APE_ANO,EOB_APE_PER,EOB_CONSECUTIVO,EOB_OBSERVACION,EOB_ESTADO,EOB_CRA_COD)";
    $qery.="VALUES";
    $qery.="('".$_REQUEST['estcod']."','".$_REQUEST['ano']."','".$_REQUEST['periodo']."','".$consecutivo."','".$observacionEst."','A','".$_REQUEST['cracod']."')";
    
    $row_qry_obs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qery,"");
    $observacionEst=".$observacionEst.";
    $cadena_registro = " INSERT INTO sga_log_eventos"; 
    $cadena_registro .= " VALUES('','".$usuario."',"; 
    $cadena_registro .= " '".date('YmdHis')."',"; 
    $cadena_registro .= " '79',"; 
    $cadena_registro .= "  'Registra observacion estudiante',"; 
    $cadena_registro .= " '".$_REQUEST['estcod'].",".$_REQUEST['ano'].",".$_REQUEST['periodo'].",".$consecutivo.", ".$observacionEst."',"; 
    $cadena_registro .= " '".$_REQUEST['estcod']."')";
    $resultado_evento = $conexion->ejecutarSQL($configuracion,$accesoSGA,$cadena_registro,"");

?>
