<?php
$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivos");

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];
//Valida que el archivo sea tipo texto.
if ($_FILES["subirArchivo"]["type"] == "text/plain"){
	// obtenemos los datos del archivo 
	$tamano = $_FILES["subirArchivo"]['size'];
	$tipo = $_FILES["subirArchivo"]['type'];
	$archivo = $_FILES["subirArchivo"]['name'];
        $temporal=$_FILES["subirArchivo"]['tmp_name'];
        $prefijo=substr(md5(uniqid(rand())),0,6);
        $fecha=date("dmYhm");
        $nombreArchivo=$fecha."-".$prefijo."_".$archivo;
        $destino =  $rutaArchivos.$nombreArchivo;
        
        //Copiamos el archivo en el servidor
        if (move_uploaded_file($_FILES['subirArchivo']['tmp_name'], $destino))
        {
            //echo "El archivo es válido y fue cargado exitosamente.\n";
        }
        else
        {
            echo "¡Posible ataque de carga de archivos!\n";
        }
        
        //Lee el archivo en el servidor                
        $file = fopen("$rutaArchivos".$nombreArchivo,"r") or exit("Imposible abrir el archivo!");
                
        while(!feof($file))
        {
            //echo fgets($file). "<br />";
            $valores=explode(",",fgets($file));
            ////$valor=substr($valores,67,15);
            
            //Se valida que el campo correspondiente al valor, inicie en valor diferente a 0, debido a que en el archivo texto que
            //reporta el banco, algunos registros aparentemente aparecen corridos.
           
            //Descomponemos la cadena que coneitne el SNP para rescatar el año y el semestre que se presentó el ICFES
            $numSnpIcfes=isset($valores[0])?$valores[0]:'';
            $snpIcfes=substr($numSnpIcfes,2,5);
            
            //echo "<br>MM ".$snpIcfes."<br>";
            
            if($snpIcfes<=20141 && $snpIcfes!=20102 && $snpIcfes>=20061)
            {    
                //ICFES antes del 2014-2
                $nombreApellido=isset($valores[1])?$valores[1]:'';
                $nombre=explode(" ",$nombreApellido);
                $apellido1=isset($nombre[0])?$nombre[0]:'';
                $apellido2=isset($nombre[1])?$nombre[1]:'';
                $nombre1=isset($nombre[2])?$nombre[2]:'';
                $nombre2=isset($nombre[3])?$nombre[3]:'';
                $variable['cod_elec']='null';
                $variable['apellidos']=$apellido1." ".$apellido2;
                $variable['nombres']=$nombre1." ".$nombre2;
                $variable['electiva']=0;
                $variable['snp']=isset($valores[0])?$valores[0]:'';
                $variable['nombre']=isset($valores[1])?$valores[1]:'';
                $variable['tipoIdenIcfes']=isset($valores[2])?$valores[2]:'';
                $variable['idenIcfes']=isset($valores[3])?$valores[3]:'';
                $variable['nomCiudadColegio']=isset($valores[4])?$valores[4]:'';
                $codColegio=isset($valores[5])?$valores[5]:'';
                if($codColegio=='')
                {
                    $variable['codColegio']='null';
                }
                else
                {    
                    $variable['codColegio']=$codColegio;
                }
                $variable['puestoIcfes']=isset($valores[6])?$valores[6]:'';
                $variable['lenguaje']=isset($valores[7])?$valores[7]:'';
                $variable['aptVerbal']=isset($valores[7])?$valores[7]:'';
                $variable['matematica']=isset($valores[8])?$valores[8]:'';
                $variable['aptMatematica']=isset($valores[8])?$valores[8]:'';
                $variable['sociales']=isset($valores[9])?$valores[9]:'';
                $variable['filosofia']=isset($valores[10])?$valores[10]:'';
                $variable['biologia']=isset($valores[11])?$valores[11]:'';
                $variable['quimica']=isset($valores[12])?$valores[12]:'';
                $variable['fisica']=isset($valores[13])?$valores[13]:'';
                $ingles=isset($valores[14])?$valores[14]:'';
                if($ingles==''){
                    $variable['ingles']=isset($valores[15])?$valores[15]:'';
                }else{
                    $variable['ingles']=isset($valores[14])?$valores[14]:'';
                }
                $variable['historia']=0;
                $variable['geografia']=0;
                $variable['interdis']='null';
                $variable['cod_inter']='null';
                $variable['asp_ptos']='null'; //Colocar valor
                $variable['asp_ptos_hom']='null'; //Colocar valor
                $variable['asp_profund']='null'; 
                $variable['asp_val_prof']='null';
                $variable['asp_profund2']='null';
                $variable['asp_val_prof2']='null';
                $variable['asp_profund3']='null';
                $variable['asp_val_prof3']='null';
                $variable['asp_entrevista']='null';
                $variable['admitido']='';
                $variable['secuencia']='null';
                $variable['convertir']='null';
                $variable['procesando']='N';
                $variable['tipoIcfes']='A'; //ICFES ANTERIOR
                $variable['ptos_cal']='null'; //Colocar valor
                $variable['cienciasSociales']=isset($valores[9])?$valores[9]:'';
                $variable['puesto']='null'; //Colocar valor
                $variable['cod_plantel']='null';
                
            }
            elseif($snpIcfes==20102)
            {
                //ICFES antes del 2010-2
                $nombreApellido=isset($valores[1])?$valores[1]:'';
                $nombre=explode(" ",$nombreApellido);
                $apellido1=isset($nombre[0])?$nombre[0]:'';
                $apellido2=isset($nombre[1])?$nombre[1]:'';
                $nombre1=isset($nombre[2])?$nombre[2]:'';
                $nombre2=isset($nombre[3])?$nombre[3]:'';
                $variable['cod_elec']='null';
                $variable['apellidos']=$apellido1." ".$apellido2;
                $variable['nombres']=$nombre1." ".$nombre2;
                $variable['electiva']=0;
                $variable['snp']=isset($valores[0])?$valores[0]:'';
                $variable['nombre']=isset($valores[1])?$valores[1]:'';
                $variable['tipoIdenIcfes']=isset($valores[2])?$valores[2]:'';
                $variable['idenIcfes']=isset($valores[3])?$valores[3]:'';
                $variable['nomCiudadColegio']=isset($valores[4])?$valores[4]:'';
                $codColegio=isset($valores[5])?$valores[5]:'';
                if($codColegio=='')
                {
                    $variable['codColegio']='null';
                }
                else
                {    
                    $variable['codColegio']=$codColegio;
                }
                $quimica=isset($valores[12])?$valores[12]:'';
                $fisica=isset($valores[13])?$valores[13]:'';
                $biologia=isset($valores[11])?$valores[11]:'';
                $sociales=isset($valores[9])?$valores[9]:'';
                $filosofia=isset($valores[10])?$valores[10]:'';
                $matematia=isset($valores[8])?$valores[8]:'';
                $lenguaje=isset($valores[7])?$valores[7]:'';        
                $variable['puestoIcfes']=isset($valores[6])?$valores[6]:'';
                $variable['lenguaje']=$lenguaje*0.6964+11.0304;
                $variable['aptVerbal']=$lenguaje*0.6964+11.0304;
                $variable['matematica']=$matematia*1.0410-7.7200;
                $variable['aptMatematica']=$matematia*1.0410-7.7200;
                $variable['sociales']='null';
                $variable['filosofia']=$filosofia*0.8155+0.0975;
                $variable['biologia']=$biologia*0.7247+9.4138;
                $variable['quimica']=$quimica*0.717+9.6;
                $variable['fisica']=$fisica*0.7904+4.2614;
                $variable['ingles']=isset($valores[14])?$valores[14]:'';
                $variable['historia']=0;
                $variable['geografia']=0;
                $variable['interdis']='null';
                $variable['cod_inter']='null';
                $variable['asp_ptos']='null'; //Colocar valor
                $variable['asp_ptos_hom']='null'; //Colocar valor
                $variable['asp_profund']='null'; 
                $variable['asp_val_prof']='null';
                $variable['asp_profund2']='null';
                $variable['asp_val_prof2']='null';
                $variable['asp_profund3']='null';
                $variable['asp_val_prof3']='null';
                $variable['asp_entrevista']='null';
                $variable['admitido']='';
                $variable['secuencia']='null';
                $variable['convertir']='null';
                $variable['procesando']='N';
                $variable['tipoIcfes']='A'; //ICFES ANTERIOR
                $variable['ptos_cal']='null'; //Colocar valor
                $variable['cienciasSociales']=$sociales*0.8491+2.6368;
                $variable['puesto']='null'; //Colocar valor
                $variable['cod_plantel']='null';
            }    
            elseif($snpIcfes>=20142)
            {
                //ICFES a partir del 2014-2
                $nombreApellido=isset($valores[1])?$valores[1]:'';
                $nombre=explode(" ",$nombreApellido);
                $apellido1=isset($nombre[0])?$nombre[0]:'';
                $apellido2=isset($nombre[1])?$nombre[1]:'';
                $nombre1=isset($nombre[2])?$nombre[2]:'';
                $nombre2=isset($nombre[3])?$nombre[3]:'';
                $variable['cod_elec']='null';
                $variable['apellidos']=$apellido1." ".$apellido2;
                $variable['nombres']=$nombre1." ".$nombre2;
                $variable['electiva']=0;
                $variable['snp']=isset($valores[0])?$valores[0]:'';
                $variable['nombre']=isset($valores[1])?$valores[1]:'';
                $variable['tipoIdenIcfes']=isset($valores[2])?$valores[2]:'';
                $variable['idenIcfes']=isset($valores[3])?$valores[3]:'';
                $variable['nomCiudadColegio']=isset($valores[5])?$valores[5]:'null';
                if($valores[4]==''){
                    $variable['codColegio']='null';
                }else{
                    $variable['codColegio']=isset($valores[4])?$valores[4]:'null';
                }
                $variable['puestoIcfes']=isset($valores[8])?$valores[8]:'';
                $variable['lenguaje']=isset($valores[10])?$valores[10]:'';
                $variable['matematica']=isset($valores[11])?$valores[11]:'';
                //$variable['aptMatematica']=isset($valores[15])?$valores[15]:'';
                $variable['aptMatematica']='null';
                $variable['sociales']=isset($valores[12])?$valores[12]:'';
                $variable['filosofia']='null';
                $variable['aptVerbal']='null';
                $variable['biologia']=isset($valores[13])?$valores[13]:'';
                $variable['quimica']='null';
                $variable['fisica']='null';
                $variable['ingles']=isset($valores[14])?$valores[14]:'';
                $variable['historia']='null';
                $variable['geografia']='null';
                //$variable['interdis']=isset($valores[16])?$valores[16]:'';
                $variable['interdis']='null';
                $variable['cod_inter']='null';
                $variable['asp_ptos']=isset($valores[9])?$valores[9]:'';
                $variable['asp_ptos_hom']='null'; //Colocar valor
                $variable['asp_profund']='null'; 
                $variable['asp_val_prof']='null';
                $variable['asp_profund2']='null';
                $variable['asp_val_prof2']='null';
                $variable['asp_profund3']='null';
                $variable['asp_val_prof3']='null';
                $variable['asp_entrevista']='null';
                $variable['admitido']='';
                $variable['secuencia']='null';
                $variable['convertir']='null';
                $variable['procesando']='N';
                $variable['tipoIcfes']='N'; //ICFES NUEVO
                $variable['ptos_cal']='null'; //Colocar valor
                $variable['cienciasSociales']='null';
                $variable['puesto']='null'; //Colocar valor
                $variable['cod_plantel']='null';
            }
            elseif($snpIcfes==20052 || $snpIcfes==20051)
            {
                //ICFES del 2005-2
                $nombreApellido=isset($valores[1])?$valores[1]:'';
                $nombre=explode(" ",$nombreApellido);
                $apellido1=isset($nombre[0])?$nombre[0]:'';
                $apellido2=isset($nombre[1])?$nombre[1]:'';
                $nombre1=isset($nombre[2])?$nombre[2]:'';
                $nombre2=isset($nombre[3])?$nombre[3]:'';
                $variable['cod_elec']='null';
                $variable['apellidos']=$apellido1." ".$apellido2;
                $variable['nombres']=$nombre1." ".$nombre2;
                $variable['electiva']=0;
                $variable['snp']=isset($valores[0])?$valores[0]:'';
                $variable['nombre']=isset($valores[1])?$valores[1]:'';
                $variable['tipoIdenIcfes']=isset($valores[2])?$valores[2]:'';
                $variable['idenIcfes']=isset($valores[3])?$valores[3]:'';
                $variable['nomCiudadColegio']=isset($valores[4])?$valores[4]:'';
                $variable['codColegio']=isset($valores[5])?$valores[5]:'';
                $variable['puestoIcfes']=isset($valores[6])?$valores[6]:'';
                $variable['lenguaje']=isset($valores[7])?$valores[7]:'';
                $variable['matematica']=isset($valores[8])?$valores[8]:'';
                $variable['aptMatematica']='null';
                $variable['sociales']='null';
                $variable['filosofia']=isset($valores[9])?$valores[9]:'';
                $variable['aptVerbal']='null';
                $variable['biologia']=isset($valores[10])?$valores[10]:'';
                $variable['quimica']=isset($valores[11])?$valores[11]:'';
                $variable['fisica']=isset($valores[12])?$valores[12]:'';
                $variable['ingles']=isset($valores[15])?$valores[15]:'';
                $variable['historia']=isset($valores[14])?$valores[14]:'';
                $variable['geografia']=isset($valores[13])?$valores[13]:'';
                //$variable['interdis']=isset($valores[16])?$valores[16]:'';
                $variable['interdis']='null';
                $variable['cod_inter']='null';
                $variable['asp_ptos']='null';
                $variable['asp_ptos_hom']='null'; //Colocar valor
                $variable['asp_profund']='null'; 
                $variable['asp_val_prof']='null';
                $variable['asp_profund2']='null';
                $variable['asp_val_prof2']='null';
                $variable['asp_profund3']='null';
                $variable['asp_val_prof3']='null';
                $variable['asp_entrevista']='null';
                $variable['admitido']='';
                $variable['secuencia']='null';
                $variable['convertir']='null';
                $variable['procesando']='N';
                $variable['tipoIcfes']='V'; //ICFES VIEJO
                $variable['ptos_cal']='null'; //Colocar valor
                $variable['cienciasSociales']='null';
                $variable['puesto']='null'; //Colocar valor
                $variable['cod_plantel']='null';
            }    
            elseif($snpIcfes<=20042)
            {
                //menor 2004 2
                $nombreApellido=isset($valores[1])?$valores[1]:'';
                $nombre=explode(" ",$nombreApellido);
                $apellido1=isset($nombre[0])?$nombre[0]:'';
                $apellido2=isset($nombre[1])?$nombre[1]:'';
                $nombre1=isset($nombre[2])?$nombre[2]:'';
                $nombre2=isset($nombre[3])?$nombre[3]:'';
                $variable['cod_elec']='null';
                $variable['apellidos']=$apellido1." ".$apellido2;
                $variable['nombres']=$nombre1." ".$nombre2;
                $variable['electiva']=0;
                $variable['snp']=isset($valores[0])?$valores[0]:'';
                $variable['nombre']=isset($valores[1])?$valores[1]:'';
                $variable['tipoIdenIcfes']=isset($valores[2])?$valores[2]:'';
                $variable['idenIcfes']=isset($valores[3])?$valores[3]:'';
                $variable['nomCiudadColegio']=isset($valores[4])?$valores[4]:'';
                $codColegio=isset($valores[5])?$valores[5]:'';
                if($codColegio=='')
                {
                    $variable['codColegio']='null';
                }
                else
                {    
                    $variable['codColegio']=$codColegio;
                }
                $variable['puestoIcfes']=isset($valores[6])?$valores[6]:'';
                $variable['lenguaje']=isset($valores[13])?$valores[13]:'';
                $variable['matematica']=isset($valores[8])?$valores[8]:'';
                $variable['aptMatematica']='null';
                $variable['sociales']='null';
                $variable['filosofia']=isset($valores[9])?$valores[9]:'';
                $variable['aptVerbal']='null';
                $variable['biologia']=isset($valores[7])?$valores[7]:'';
                $variable['quimica']=isset($valores[12])?$valores[12]:'';
                $variable['fisica']=isset($valores[10])?$valores[10]:'';
                $variable['ingles']=isset($valores[15])?$valores[15]:'';
                $variable['historia']=isset($valores[11])?$valores[11]:'';
                $variable['geografia']=isset($valores[14])?$valores[14]:'';
                //$variable['interdis']=isset($valores[16])?$valores[16]:'';
                $variable['interdis']='null';
                $variable['cod_inter']='null';
                $variable['asp_ptos']='null';
                $variable['asp_ptos_hom']='null'; //Colocar valor
                $variable['asp_profund']='null'; 
                $variable['asp_val_prof']='null';
                $variable['asp_profund2']='null';
                $variable['asp_val_prof2']='null';
                $variable['asp_profund3']='null';
                $variable['asp_val_prof3']='null';
                $variable['asp_entrevista']='null';
                $variable['admitido']='';
                $variable['secuencia']='null';
                $variable['convertir']='null';
                $variable['procesando']='N';
                $variable['tipoIcfes']='V'; //ICFES VIEJO
                $variable['ptos_cal']='null'; //Colocar valor
                $variable['cienciasSociales']='null';
                $variable['puesto']='null'; //Colocar valor
                $variable['cod_plantel']='null';
            }
               
            $cadena_sql = $this->sql->cadena_sql("consultarAcaspw", $variable);
            $registroInscripcionAcaspw = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            $cadena_sql = $this->sql->cadena_sql("consultarTransferencia", $variable);
            $registroInscripcionTransferencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
            $cierto=0;
            if(is_array($registroInscripcionAcaspw))
            {
                for($i=0; $i<=count($registroInscripcionAcaspw)-1; $i++)
                {
                    $variable['rba_id']=$registroInscripcionAcaspw[$i]['rba_id'];
                    $variable['carreras']=$registroInscripcionAcaspw[$i]['aspw_cra_cod'];
                    $variable['medio']=$registroInscripcionAcaspw[$i]['med_id'];
                    $variable['prestentaPor']=$registroInscripcionAcaspw[$i]['aspw_veces'];
                    $variable['tipoInscripcion']=$registroInscripcionAcaspw[$i]['ti_id'];
                    $variable['pais']=$registroInscripcionAcaspw[$i]['aspw_nacionalidad'];
                    $variable['departamento']=$registroInscripcionAcaspw[$i]['aspw_dep_cod_nac'];
                    $variable['municipio']=$registroInscripcionAcaspw[$i]['aspw_mun_cod_nac'];
                    $variable['fechaNac']=$registroInscripcionAcaspw[$i]['aspw_fec_nac'];
                    $variable['sexo']=$registroInscripcionAcaspw[$i]['aspw_sexo'];
                    $variable['estadoCivil']=$registroInscripcionAcaspw[$i]['aspw_estado_civil'];
                    $variable['direccionResidencia']=$registroInscripcionAcaspw[$i]['aspw_direccion'];
                    $variable['localidadResidencia']=$registroInscripcionAcaspw[$i]['aspw_localidad'];
                    $variable['estratoResidencia']=$registroInscripcionAcaspw[$i]['aspw_estrato'];
                    $variable['estratoCosteara']=$registroInscripcionAcaspw[$i]['aspw_estrato_costea'];
                    $variable['telefono']=$registroInscripcionAcaspw[$i]['aspw_telefono'];
                    $variable['email']=$registroInscripcionAcaspw[$i]['aspw_email'];
                    $variable['tipDocActual']=$registroInscripcionAcaspw[$i]['aspw_nro_tip_act'];
                    $variable['documentoActual']=$registroInscripcionAcaspw[$i]['aspw_nro_iden_act'];
                    $variable['tipDocIcfes']=$registroInscripcionAcaspw[$i]['aspw_nro_tip_icfes'];
                    $variable['documentoIcfes']=$registroInscripcionAcaspw[$i]['aspw_nro_iden_icfes'];
                    $variable['tipoSangre']=$registroInscripcionAcaspw[$i]['aspw_tipo_sangre'];
                    $variable['rh']=$registroInscripcionAcaspw[$i]['aspw_rh'];
                    $variable['localidadColegio']=$registroInscripcionAcaspw[$i]['aspw_localidad_colegio'];
                    $variable['tipoColegio']=$registroInscripcionAcaspw[$i]['aspw_tipo_colegio'];
                    $variable['valido']=$registroInscripcionAcaspw[$i]['aspw_valida_bto'];
                    $variable['numSemestres']=$registroInscripcionAcaspw[$i]['aspw_sem_transcurridos'];
                    $variable['discapacidad']=$registroInscripcionAcaspw[$i]['aspw_tipo_discap'];
                    $variable['credencial']=$registroInscripcionAcaspw[$i]['rba_asp_cred'];
                    $variable['observaciones']=$registroInscripcionAcaspw[$i]['aspw_observacion'];
                    //echo $variable['snp']." - ".$variable['rba_id']." - ".$variable['carreras']."<br><br>";
                    
                    $cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
                    $registroAcaspRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    
                    if($registroAcaspRegistrados[0]['rba_id']!=$variable['rba_id'])
                    {
                        $cadena_sql = $this->sql->cadena_sql("insertaAcasp", $variable);
                        @$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
                        
                        if ($registro==true)
                        {
                            $cierto=1;
                        }
                        else
                        {
                            echo "Ups... error!!!";
                        }
                    }    
                    
                    //echo $cadena_sql."<br><br><br>";
                }
            }
            elseif(is_array($registroInscripcionTransferencia))
            {
                for($i=0; $i<=count($registroInscripcionTransferencia)-1; $i++)
                {    
                    $variable['rba_id']=$registroInscripcionTransferencia[$i]['rba_id'];
                    $variable['carreras']=$registroInscripcionTransferencia[$i]['atr_cra_cod'];
                    $variable['medio']=1;
                    $variable['prestentaPor']='null';
                    $variable['tipoInscripcion']=17;
                    $variable['pais']=$registroInscripcionTransferencia[$i]['atr_nacionalidad'];
                    $variable['departamento']=$registroInscripcionTransferencia[$i]['atr_dep_cod_nac'];
                    $variable['municipio']=$registroInscripcionTransferencia[$i]['atr_mun_cod_nac'];
                    $variable['fechaNac']=$registroInscripcionTransferencia[$i]['atr_fec_nac'];
                    $variable['sexo']=$registroInscripcionTransferencia[$i]['atr_sexo'];
                    $variable['estadoCivil']=$registroInscripcionTransferencia[$i]['atr_estado_civil'];
                    $variable['direccionResidencia']=$registroInscripcionTransferencia[$i]['atr_direccion'];
                    $variable['localidadResidencia']=$registroInscripcionTransferencia[$i]['atr_localidad'];
                    $variable['estratoResidencia']=$registroInscripcionTransferencia[$i]['atr_estrato'];
                    $variable['estratoCosteara']=$registroInscripcionTransferencia[$i]['atr_estrato'];
                    $variable['telefono']=$registroInscripcionTransferencia[$i]['atr_telefono'];
                    $variable['email']=$registroInscripcionTransferencia[$i]['atr_email'];
                    $variable['tipDocActual']=$registroInscripcionTransferencia[$i]['atr_nro_tip_act'];
                    $variable['documentoActual']=$registroInscripcionTransferencia[$i]['atr_nro_iden_act'];
                    $variable['tipDocIcfes']=$registroInscripcionTransferencia[$i]['atr_nro_tip_icfes'];
                    $variable['documentoIcfes']=$registroInscripcionTransferencia[$i]['atr_nro_iden_icfes'];
                    $variable['tipoSangre']=$registroInscripcionTransferencia[$i]['atr_tipo_sangre'];
                    $variable['rh']=$registroInscripcionTransferencia[$i]['atr_rh'];
                    $variable['localidadColegio']=$registroInscripcionTransferencia[$i]['atr_localidad_colegio'];
                    $variable['tipoColegio']='';
                    $variable['valido']="";
                    $variable['numSemestres']='null';
                    $variable['discapacidad']='null';
                    $variable['credencial']=$registroInscripcionAcaspw[$i]['rba_asp_cred'];
                    $variable['observaciones']=$registroInscripcionTransferencia[$i]['atr_observacion'];
                    //echo $variable['snp']." - ".$variable['rba_id']." - ".$variable['carreras']."<br><br>";
                    
                    $cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
                    $registroAcaspRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    if($registroAcaspRegistrados[0]['rba_id']!=$variable['rba_id'])
                    {
                        $cadena_sql = $this->sql->cadena_sql("insertaAcasp", $variable);
                        $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
                        
                        if ($registro==true)
                        {
                            $cierto=1;
                        }
                        else
                        {
                            echo "Ups... error!!!";
                        }
                    }
                    //echo $cadena_sql."<br><br><br>";
                }
            }
            else
            {
                $valor['opcionPagina']="registrarIcfes";
                $this->funcion->redireccionar ("regresar",$valor);
            }    
            
            
        }
        
        fclose($file);
        if($cierto==1)
        {
            $valor['opcionPagina']="registrarIcfes";
            //$this->funcion->redireccionar ("regresar",$valor);
        }
}
else
{
    $this->funcion->redireccionar ("mostrarMensajeArchivoPines");
}    
?>

