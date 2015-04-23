<?PHP
    //Llamado de coor_actualiza_datos_est.php
    $cambios='';
    $cambios2='';
    $qery="UPDATE ";
    $qery.="ACEST ";
    $qery.="SET ";
    
    if( is_numeric($_REQUEST['nroiden']) &&  strtoupper($datos_estudiante[0][2]) !=strtoupper($_REQUEST['nroiden'])&& $datos_estudiante[0][2] !=$_REQUEST['nroiden']){ 
            $qery.=" EST_NRO_IDEN ='". $_REQUEST['nroiden']."' ";
            $cambios.=", identificación ".$datos_estudiante[0][2] ."->".$_REQUEST['nroiden'];
    }
    $_REQUEST['estnom']=trim($_REQUEST['estnom']);
    $valida_nombre=solo_letras($_REQUEST['estnom']);
    
    if($_REQUEST['estnom'] && $_REQUEST['estnom'] != ' ' && strtoupper($datos_estudiante[0][1]) !=strtoupper($_REQUEST['estnom']) && $valida_nombre){
            if($cambios){$qery.=",";}
            $qery.=" EST_NOMBRE ='".strtoupper($_REQUEST['estnom'])."' ";
            $cambios.=", Nombre ".$datos_estudiante[0][1] ."->".$_REQUEST['estnom'];
    }
    
    if( strtoupper($datos_estudiante[0][3]) !=strtoupper($_REQUEST['dir']) && $datos_estudiante[0][3] !=$_REQUEST['dir']){
            if($cambios){$qery.=",";}
            $qery.=" EST_DIRECCION ='".$_REQUEST['dir']."' ";
            $cambios.=", dir ".$datos_estudiante[0][3] ."->".$_REQUEST['dir'];
    }
    if( is_numeric($_REQUEST['tel']) && $_REQUEST['tel'] && $datos_estudiante[0][4] !=$_REQUEST['tel']){ 
            if($cambios){$qery.=",";}
            $qery.=" EST_TELEFONO ='".$_REQUEST['tel']."' ";
            $cambios.=", tel ".$datos_estudiante[0][4] ."->".$_REQUEST['tel'];
    }
    if( $datos_estudiante[0][6] !=$_REQUEST['SX']){ 
            if($cambios){$qery.=",";}
            $qery.=" EST_SEXO ='".$_REQUEST['SX']."' ";
            $cambios.=" sexo ".$datos_estudiante[0][6] ."->".$_REQUEST['SX'];
    }
    if( $datos_estudiante[0][5] !=$_REQUEST['zonap']){ 
            if($cambios){$qery.=",";}
            $qery.=" EST_ZONA_POSTAL ='".$_REQUEST['zonap']."' ";
            $cambios.=", zona postal ".$datos_estudiante[0][5] ."->".$_REQUEST['zonap'];
    }
    if( $datos_estudiante[0][20] !=$_REQUEST['estincred']){ 
            if($cambios){$qery.=",";}
            $qery.=" EST_IND_CRED ='".$_REQUEST['estincred']."' ";
            $cambios.=", tipo estudiante ".$datos_estudiante[0][20] ."->".$_REQUEST['estincred'];
    }
    if( $datos_estudiante[0][19] !=$_REQUEST['pennro'] && is_numeric($_REQUEST['pennro'])){ 
            if($cambios){$qery.=",";}
            $qery.=" EST_PEN_NRO ='".$_REQUEST['pennro']."' ";
            $cambios.=", pensum ".$datos_estudiante[0][19] ."->".$_REQUEST['pennro'];
    }
    if(is_array($datos_estudiante) && $datos_estudiante[0][24] !='E' && $datos_estudiante[0][24] !=$_REQUEST['cod_estado'] && $_REQUEST['cod_estado']!='E' && $_REQUEST['cod_estado']!=''){ 
            if($cambios){$qery.=",";}
            $qery.=" EST_ESTADO_EST ='".$_REQUEST['cod_estado']."' ";
            $cambios.=", estado ".$datos_estudiante[0][24] ."->".$_REQUEST['cod_estado'];
    }
    if(is_array($datos_estudiante) && $datos_estudiante[0][25] !='' && $datos_estudiante[0][25] !=$_REQUEST['tipo_iden']){
            if($cambios){$qery.=",";}
            $qery.=" EST_TIPO_IDEN ='".$_REQUEST['tipo_iden']."' ";
            $cambios.=", tipo_iden ".$datos_estudiante[0][25] ."->".$_REQUEST['tipo_iden'];
    }
    $qery.="WHERE ";
    $qery.="EST_COD ='".$_REQUEST['estcod']."'";

     
    $reg="UPDATE ";
    $reg.="ACESTOTR ";
    $reg.="SET ";
    if( $datos_estudiante[0][12] !=$_REQUEST['fecnac']){ 
        $reg.="EOT_FECHA_NAC =TO_DATE('".$_REQUEST['fecnac']."','dd/mm/YYYY')  ";
        $cambios2.=", fecha nac ".$datos_estudiante[0][12] ."->".$_REQUEST['fecnac'];
    }
    
    if( $datos_estudiante[0][13] !=$_REQUEST['lugnac']){ 
        if($cambios2){$reg.=",";}
        $reg.="EOT_COD_LUG_NAC ='".$_REQUEST['lugnac']."' ";
        $cambios2.=", lugar ".$datos_estudiante[0][13] ."->".$_REQUEST['lugnac'];
    }
    if( $datos_estudiante[0][9] !=$_REQUEST['mail']){ 
        if($cambios2){$reg.=",";}
        $reg.="EOT_EMAIL ='".trim(strtolower($_POST['mail']))."' ";
        $cambios2.=", email ".$datos_estudiante[0][9] ."->".$_REQUEST['mail'];
    }
    if( $datos_estudiante[0][7] !=$_REQUEST['tisa']){ 
        if($cambios2){$reg.=",";}
        $reg.="EOT_TIPOSANGRE ='".$_REQUEST['tisa']."' ";
        $cambios2.=", tipo sangre ".$datos_estudiante[0][7] ."->".$_REQUEST['tisa'];
    }
    if(($_REQUEST['rh'] =='+' || $_REQUEST['rh'] =='-') && $datos_estudiante[0][8] !=$_REQUEST['rh'] ){ 
        if($cambios2){$reg.=",";}
        $reg.="EOT_RH ='".$_REQUEST['rh']."' ";
        $cambios2.=", rh ".$datos_estudiante[0][8] ."->".$_REQUEST['rh'];
    }
    
    if( $datos_estudiante[0][10] !=$_REQUEST['LEC']){ 
        if($cambios2){$reg.=",";}
        $reg.="EOT_ESTADO_CIVIL ='".$_REQUEST['LEC']."' ";
        $cambios2.=", estado civil ".$datos_estudiante[0][10] ."->".$_REQUEST['LEC'];
    }
    $reg.="WHERE ";
    $reg.="EOT_COD ='".$_REQUEST['estcod']."'";

    
    if($cambios){
        $row_qry = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qery,"");
    }
    if($cambios2){
        $row_reg = $conexion->ejecutarSQL($configuracion,$accesoOracle,$reg,"");

    }
    if($cambios || $cambios2){
        $cadena_registro = " INSERT INTO sga_log_eventos"; 
        $cadena_registro .= " VALUES(0,'".$usuario."',"; 
        $cadena_registro .= " '".date('YmdHis')."',"; 
        $cadena_registro .= " '64',"; 
        $cadena_registro .= "  'Actualiza datos basicos de estudiante',";
        $cadena_registro .= " '".$_REQUEST['estcod']." ".$cambios." ".$cambios2."',"; 
        $cadena_registro .= " '".$_REQUEST['estcod']."')";

        $resultado_evento = $conexion->ejecutarSQL($configuracion,$accesoSGA,$cadena_registro,"");
    }

    function solo_letras($cadena){
        $permitidos = "abcdefghijklmnñopqrstuvwxyzáéíóúABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ ";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  
?>
