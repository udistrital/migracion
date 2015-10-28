<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1="admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
if (!$esteRecursoDB1) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("buscarLocalidades", $variable);
$registroLocalidades = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("buscarEstratos", $variable);
$registroEstratos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registro))
{	  
    for($i=0; $i<=count($registro)-1; $i++)
    {
        $variable['carrera']=$registro[$i]['asp_cra_cod'];
        $cadena_sql = $this->sql->cadena_sql("carrerasOfrecidas", $variable);
        $registroCarreras = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
        
        if($registroCarreras[0][0]==$registro[$i]['asp_cra_cod']){
        
        $variable['asp_id']=$registro[$i]['asp_id'];
        $snpIcfes=substr($registro[$i]['asp_snp'],2,5);
        $snp=substr($registro[$i]['asp_snp'],2,4);
       
        if($snpIcfes>=20142)
        {
            //Valida faculatades para asingar puntaje global
            $codDepto=array(23,24,101);
            if(in_array($registroCarreras[0][4], $codDepto))
            {
                $ptos=$registro[$i]['asp_ptos'];
            
            }
                        
            elseif($registroCarreras[0][4]==33 && $registro[$i]['asp_tip_icfes']=='N' && $registro[$i]['asp_cra_cod']!=25)
            {
                $ptos=$registro[$i]['asp_ptos'];
                $ptos_calculados=(($registro[$i]['asp_con_mat']*0.35)+($registro[$i]['asp_soc']*0.10)+($registro[$i]['asp_bio']*0.35)+($registro[$i]['asp_idioma']*0.05)+($registro[$i]['asp_esp_y_lit']*0.15))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            //valida la facultad para calcular puntaje calculado, solamente para ing. catastral
            elseif($registroCarreras[0][4]==33 && $registro[$i]['asp_tip_icfes']=='N' && $registro[$i]['asp_cra_cod']==25)
            {
                $ptos=$registro[$i]['asp_ptos'];
                $ptos_calculados=(($registro[$i]['asp_con_mat']*0.35)+($registro[$i]['asp_soc']*0.15)+($registro[$i]['asp_bio']*0.30)+($registro[$i]['asp_idioma']*0.05)+($registro[$i]['asp_esp_y_lit']*0.15))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            //Se asigna puntaje global a la facultad 33
            elseif($registroCarreras[0][4]==33)
            {
                $ptos=$registro[$i]['asp_ptos'];
            }
            
            elseif($registroCarreras[0][4]==32 && $registroCarreras[0][5]=='N')
            {
                $ptos=$registro[$i]['asp_ptos'];
                //Calcula puntos ponderado
                $ptos_pon=($registro[$i]['asp_con_mat']*0.35)+($registro[$i]['asp_soc']*0.10)+($registro[$i]['asp_bio']*0.35)+($registro[$i]['asp_idioma']*0.05)+($registro[$i]['asp_esp_y_lit']*0.15);
                
                //Rescata las localidades para calcular el puntaje
                for($j=0; $j<=count($registroLocalidades)-1; $j++)
                {
                    if($registroLocalidades[$j]['loc_id']==$registro[$i]['asp_localidad'])
                    {
                        $localidad_puntos_n=$registroLocalidades[$j]['loc_puntos_n'];
                    }
                    if($registroLocalidades[$j]['loc_id']==$registro[$i]['asp_localidad_colegio'])
                    {
                        $localidad_colegio_puntos_n=$registroLocalidades[$j]['loc_puntos_n'];
                    }
                }
                
                for($k=0; $k<=count($registroEstratos)-1; $k++)
                {
                    if($registroEstratos[$k]['estrato_id']==$registro[$i]['asp_estrato'])
                    {
                        $estrato_puntos_n=$registroEstratos[$k]['estrato_puntos_n'];
                    }    
                }
                
                //Puntos calculados para la facultad 32
                $ptos_calculados=round((($ptos_pon+(($localidad_puntos_n+$localidad_colegio_puntos_n)/2)-$estrato_puntos_n)*100),0);
                $ptos_cal=$ptos_calculados/100;
            }
            elseif($registroCarreras[0][4]==32 && $registroCarreras[0][5]=='S')
            {
                $ptos=$registro[$i]['asp_ptos'];
                $ptos_calculados=(($registro[$i]['asp_con_mat']*0.35)+($registro[$i]['asp_soc']*0.10)+($registro[$i]['asp_bio']*0.35)+($registro[$i]['asp_idioma']*0.05)+($registro[$i]['asp_esp_y_lit']*0.15))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            if(in_array($registroCarreras[0][4], $codDepto))
            {
                $ptos_cal=$ptos;
            }
            
            if($registro[$i]['asp_ser_militar']=='S')
            {
                $ptos_cal=$ptos_cal*1.10;
            }
            
            //valida la facultad para calcular puntaje ponderado exceptuando ing. catastral
            if(isset($ptos)){
                $variable['ptos']=$ptos;
                $variable['pts_cal']=$ptos_cal;
                $variable['pts_hom']=$ptos;
            }else{
                $variable['ptos']=0;
                $variable['pts_cal']=$ptos_cal;
                $variable['pts_hom']=0;
            }
        }
        else
        {
            if($registro[$i]['asp_electiva']>0 && $registro[$i]['asp_electiva']>0)
            {
                $ptos=((($registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis'])/3)+($registro[$i]['asp_soc'])+(($registro[$i]['asp_apt_verbal']+$registro[$i]['asp_esp_y_lit'])/2)+(($registro[$i]['asp_apt_mat']+$registro[$i]['asp_con_mat'])/2)+$registro[$i]['asp_electiva']);
            }
            if($registro[$i]['asp_tip_icfes']=='V')  //Icfes Viejo 
            {
                $ptos=((($registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis'])/3)+($registro[$i]['asp_soc'])+(($registro[$i]['asp_apt_verbal']+$registro[$i]['asp_esp_y_lit'])/2)+(($registro[$i]['asp_apt_mat']+$registro[$i]['asp_con_mat'])/2)+$registro[$i]['asp_electiva']);
            }
            if($registro[$i]['asp_tip_icfes']=='A') //Icfes Anterior
            {
                //$ptos=0;
                if($snp>=2006 && $registroCarreras[0][5]=='N')
                {
                    $ptos=($registro[$i]['asp_cie_soc']+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registroCarreras[0][5]=='S')
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp<2006)
                {
                    $ptos=($registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_geo']+$registro[$i]['asp_his']+$registro[$i]['asp_fil']);
                }
                //se agrego duplicacion de sociales para transferencias externas
                elseif($snp>=2006 && $registro[$i]['ti_id']==17 && $registro[$i]['asp_cra_cod']==10)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==17 && $registro[$i]['asp_cra_cod']==32)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==17 && $registro[$i]['asp_cra_cod']==180)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==17 && $registro[$i]['asp_cra_cod']==185)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==22 && $registro[$i]['asp_cra_cod']==10)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==22 && $registro[$i]['asp_cra_cod']==32)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==22 && $registro[$i]['asp_cra_cod']==180)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
                elseif($snp>=2006 && $registro[$i]['ti_id']==22 && $registro[$i]['asp_cra_cod']==185)
                {
                    $ptos=(($registro[$i]['asp_cie_soc']*2)+$registro[$i]['asp_bio']+$registro[$i]['asp_qui']+$registro[$i]['asp_fis']+$registro[$i]['asp_apt_verbal']+$registro[$i]['asp_apt_mat']+$registro[$i]['asp_fil']);
                }
            }
            
            //validar la facultad para calcular puntaje ponderado ING. TOPOGRAFICA
            if($registroCarreras[0][4]==23 && $registro[$i]['asp_cra_cod']==32 && $registro[$i]['asp_tip_icfes']=='A')
            {
                $ptos_calculados=(($registro[$i]['asp_apt_mat']*0.30)+($registro[$i]['asp_fis']*0.30)+($registro[$i]['asp_qui']*0.05)+($registro[$i]['asp_cie_soc']*0.10)+($registro[$i]['asp_apt_verbal']*0.20)+($registro[$i]['asp_bio']*0.05))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            $ptos_hom=$ptos;
            //validar la facultad para calcular puntaje ponderado
            if($registroCarreras[0][4]==33 && $registro[$i]['asp_tip_icfes']=='V')
            {
                $ptos_calculados=(($registro[$i]['asp_apt_mat']*0.25)+($registro[$i]['asp_fis']*0.30)+($registro[$i]['asp_qui']*0.10)+($registro[$i]['asp_soc']*0.10)+($registro[$i]['asp_apt_verbal']*0.15)+($registro[$i]['asp_electiva']*0.05)+($registro[$i]['asp_bio']*0.05))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            if($registroCarreras[0][4]==33 && $registro[$i]['asp_tip_icfes']=='A' && $registro[$i]['asp_cra_cod']!=25)
            {
                $ptos_calculados=(($registro[$i]['asp_bio']*0.05)+($registro[$i]['asp_qui']*0.10)+($registro[$i]['asp_fil']*0.05)+($registro[$i]['asp_his']*0.05)+($registro[$i]['asp_fis']*0.30)+($registro[$i]['asp_apt_verbal']*0.15)+($registro[$i]['asp_geo']*0.05)+($registro[$i]['asp_cie_soc']*0.10)+($registro[$i]['asp_apt_mat']*0.25))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            if($registroCarreras[0][4]==33 && $registro[$i]['asp_tip_icfes']=='A' && $registro[$i]['asp_cra_cod']==25)
            {
                $ptos_calculados=(($registro[$i]['asp_bio']*0.05)+($registro[$i]['asp_qui']*0.10)+($registro[$i]['asp_fil']*0.05)+($registro[$i]['asp_his']*0.05)+($registro[$i]['asp_fis']*0.25)+($registro[$i]['asp_apt_verbal']*0.15)+($registro[$i]['asp_geo']*0.05)+($registro[$i]['asp_cie_soc']*0.15)+($registro[$i]['asp_apt_mat']*0.25))*100;
                $ptos_cal=$ptos_calculados/100;
            }
            
            if($registroCarreras[0][4]==32 && $registroCarreras[0][5]=='N')
            {
                $ptos_pon= (($registro[$i]['asp_apt_mat']*0.30)+($registro[$i]['asp_fis']*0.30)+($registro[$i]['asp_qui']*0.10)+($registro[$i]['asp_apt_verbal']*0.15)+($registro[$i]['asp_bio']*0.05)+($registro[$i]['asp_fil']*0.033)+($registro[$i]['asp_cie_soc']*0.067));   
                
                for($j=0; $j<=count($registroLocalidades)-1; $j++)
                {
                    if($registroLocalidades[$j]['loc_id']==$registro[$i]['asp_localidad'])
                    {
                        $localidad_puntos_n=$registroLocalidades[$j]['loc_puntos_n'];
                        $localidad_puntos_v=$registroLocalidades[$j]['loc_puntos_v'];
                    }
                    if($registroLocalidades[$j]['loc_id']==$registro[$i]['asp_localidad_colegio'])
                    {
                        $localidad_colegio_puntos_n=$registroLocalidades[$j]['loc_puntos_n'];
                        $localidad_colegio_puntos_v=$registroLocalidades[$j]['loc_puntos_v'];
                    }
                }
                
                for($k=0; $k<=count($registroEstratos)-1; $k++)
                {
                    if($registroEstratos[$k]['estrato_id']==$registro[$i]['asp_estrato'])
                    {
                        $estrato_puntos_n=$registroEstratos[$k]['estrato_puntos_n'];
                        $estrato_puntos_v=$registroEstratos[$k]['estrato_puntos_v'];
                    }    
                }
                
                if($registro[$i]['asp_tip_icfes']=='V')
                {    
                    $ptos_calculados=round((($ptos_pon+(($localidad_puntos_v+$localidad_colegio_puntos_v)/2)-$estrato_puntos_v)*100),0);
                    $ptos_cal=$ptos_calculados/100;
                }
                elseif($registro[$i]['asp_tip_icfes']=='A')
                {
                    $ptos_calculados=round((($ptos_pon+(($localidad_puntos_n+$localidad_colegio_puntos_n)/2)-$estrato_puntos_n)*100),0);
                    $ptos_cal=$ptos_calculados/100;
                }
            }
            
            // para dejar el calculo relizado anteriormente enla 32 
            $codDepto=array(23,24,101);
            if((in_array($registroCarreras[0][4], $codDepto)) && $registro[$i]['asp_cra_cod']!=32)
            {
                $ptos_cal=$ptos;
            }
            
            if($registro[$i]['asp_ser_militar']=='S')
            {
                $ptos_cal=$ptos_cal*1.10;
            }
            $variable['pts_cal']=$ptos_cal;
            $variable['pts_hom']=$ptos_hom;
            $variable['ptos']=$ptos;
            $variable['tipoIcfes']='A';
        }
            //Actualizamos los pntajes en la base de datos.
           $cadena_sql = $this->sql->cadena_sql("actualizaAcaspResultados", $variable);
           $registroUpdate = $esteRecursoDB->ejecutarAcceso($cadena_sql,"");
    }
    }
    if($registroUpdate==true)
    {
        $this->funcion->redireccionar('regresaraCalculoResultados');
    }    
}
else
{
    echo "No hay registros";
}    
?>

