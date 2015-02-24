<?PHP
    //Llamado de coor_actualiza_datos_est.php
    $cambios='';
    $qery="UPDATE ";
    $qery.="ACESTOBS ";
    $qery.="SET ";
    
    if( is_numeric($_REQUEST['estcod']) && is_numeric($_REQUEST['ano']) && $_REQUEST['ano']==$obs_estudiante[$_REQUEST['nro']][1] && is_numeric($_REQUEST['per']) && $_REQUEST['per']==$obs_estudiante[$_REQUEST['nro']][2] && is_numeric($_REQUEST['cons']) && $_REQUEST['cons']==$obs_estudiante[$_REQUEST['nro']][3] &&  $_REQUEST['cracod']==$obs_estudiante[$_REQUEST['nro']][6]){
        if($_REQUEST['obs'] && $_REQUEST['obs'] != ' ' && trim($obs_estudiante[$_REQUEST['nro']][4]) !=trim($_REQUEST['obs']) && strlen(trim($_REQUEST['obs']))<=256){
                if($cambios){$qery.=",";}
                $qery.=" EOB_OBSERVACION ='".trim($_REQUEST['obs'])."' ";
                $cambios.=', Observacion "'.$obs_estudiante[$_REQUEST['nro']][4] .'"->"'.$_REQUEST['obs'].'"';
        }

        if($_REQUEST['estado'] && $_REQUEST['estado'] != ' ' && trim($obs_estudiante[$_REQUEST['nro']][5]) !=trim($_REQUEST['estado'])){
                if($cambios){$qery.=",";}
                $qery.=" EOB_ESTADO ='".trim($_REQUEST['estado'])."' ";
                $cambios.=', Estado "'.$obs_estudiante[$_REQUEST['nro']][5] .'"->"'.$_REQUEST['estado'].'"';
        }
    }
    $qery.="WHERE ";
    $qery.="EOB_EST_COD ='".$_REQUEST['estcod']."' ";
    $qery.="AND EOB_CRA_COD ='".$_REQUEST['cracod']."' ";
    $qery.="AND EOB_APE_ANO ='".$_REQUEST['ano']."' ";
    $qery.="AND EOB_APE_PER ='".$_REQUEST['per']."' ";
    $qery.="AND EOB_CONSECUTIVO ='".$_REQUEST['cons']."' ";
    if($cambios){
        $row_qry_act_obs = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qery,"");

        $cadena_registro = " INSERT INTO sga_log_eventos"; 
        $cadena_registro .= " VALUES('','".$usuario."',"; 
        $cadena_registro .= " '".date('YmdHis')."',"; 
        $cadena_registro .= " '80',"; 
        $cadena_registro .= "  'Actualiza observacion estudiante',"; 
        $cadena_registro .= " '".$_REQUEST['estcod'].",".$_REQUEST['ano'].",".$_REQUEST['per'].",".$_REQUEST['cons']." ".$cambios."',"; 
        $cadena_registro .= " '".$_REQUEST['estcod']."')";
        $resultado_evento = $conexion->ejecutarSQL($configuracion,$accesoSGA,$cadena_registro,"");
    }

?>
