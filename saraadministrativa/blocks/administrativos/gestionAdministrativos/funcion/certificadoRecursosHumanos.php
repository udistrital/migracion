<?
$ruta = $this->miConfigurador->getVariableConfiguracion("raizDocumento");
$ruta.="/blocks/administrativos/gestionAdministrativos";

$conexion = "funcionario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1="soporteoas";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
                                        
if (!$esteRecursoDB1) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1 = "funcionarios";
$esteRecursoDBPGS = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
                                        
if (!$esteRecursoDBPGS) {

    echo "//Este se considera un error fatal";
    exit;
}
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$variable['usuario']=$_REQUEST['usuario'];

$cadena_sql = $this->sql->cadena_sql("datosCertificacion", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//echo $ruta."MMM<br>"; exit;
if(is_array($registro))
{
    if($_REQUEST['tipoCertificado']=='certificado0')
    {
        //echo $cadena_sql."<br>";
        setlocale(LC_MONETARY, 'en_US');
        $sueldoBasicoMensual=money_format('$ %!.0i',$registro[0][12]);
        $salarioPromedio=money_format('$ %!.0i',$registro[0][13]);
        $mesada=money_format('$ %!.0i',$registro[0][15]);
        if($registro[0][4]=="CEDULA CIUDADANIA")
        {
            $tipoDocumento="cédula de ciudadanía";
        }
        else
        {    
            $tipoDocumento=strtolower($registro[0][4]);
        }
        $numeroIdentificacion=$registro[0][5];
        $fechaIng=$registro[0][6];
        $fechaRet=$registro[0][7];
        $fecIngreso=date("d-M-Y",strtotime($fechaIng));
        $fecRetiro=date("d-M-Y",strtotime($fechaRet));
        $fecIng=explode('-', $fecIngreso);
        $fecRet=explode('-', $fecRetiro);
        $apellidosNombres=$registro[0][0].' '.$registro[0][1].' '.$registro[0][2].' '.$registro[0][3];
        $cargo=$registro[0][10];
        $dependencia=$registro[0][11];
        $generoBD=$registro[0][16];
        $lugarExpedicion=$registro[0][17];
        $valorHonorarios=" ";
        $valorRetencion=" "; 
        setlocale(LC_ALL,"es_ES");
        $fechaHoy=strftime("%d dias del mes de %B del %Y");
        $jefe=$registro[0][14];
        
        if($fecIng[1]=='Jan')
        {
            $mesIng='Enero';
        }
        elseif($fecIng[1]=='Feb')
        {
            $mesIng='Febrero';
        }
        elseif($fecIng[1]=='Mar')
        {
            $mesIng='Marzo';
        }
        elseif($fecIng[1]=='Apr')
        {
            $mesIng='Abril';
        }
        elseif($fecIng[1]=='May')
        {
            $mesIng='Mayo';
        }
        elseif($fecIng[1]=='Jun')
        {
            $mesIng='Junio';
        }
        elseif($fecIng[1]=='Jul')
        {
            $mesIng='Julio';
        }
        elseif($fecIng[1]=='Aug')
        {
            $mesIng='Agosto';
        }
        elseif($fecIng[1]=='Sep')
        {
            $mesIng='Septiembre';
        }
        elseif($fecIng[1]=='Oct')
        {
            $mesIng='Octubre';
        }
        elseif($fecIng[1]=='Nov')
        {
            $mesIng='Noviembre';
        }
        elseif($fecIng[1]=='Dec')
        {
            $mesIng='Diciembre';
        }
        //Fecha retiro
        if( $fecRet[1]=='Jan')
        {
            $mesRet='Enero';
        }
        elseif($fecRet[1]=='Feb')
        {
            $mesRet='Febrero';
        }
        elseif($fecRet[1]=='Mar')
        {
            $mesRet='Marzo';
        }
        elseif($fecRet[1]=='Apr')
        {
            $mesRet='Abril';
        }
        elseif($fecRet[1]=='May')
        {
            $mesRet='Mayo';
        }
        elseif($fecRet[1]=='Jun')
        {
            $mesRet='Junio';
        }
        elseif($fecRet[1]=='Jul')
        {
            $mesRet='Julio';
        }
        elseif($fecRet[1]=='Aug')
        {
            $mesRet='Agosto';
        }
        elseif($fecRet[1]=='Sep')
        {
            $mesRet='Septiembre';
        }
        elseif($fecRet[1]=='Oct')
        {
            $mesRet='Octubre';
        }
        elseif($fecRet[1]=='Nov')
        {
            $mesRet='Noviembre';
        }
        elseif($fecRet[1]=='Dec')
        {
            $mesRet='Diciembre';
        }
        
        $fechaIngreso=$fecIng[0]." de ".$mesIng." del ".$fecIng[2];
        
        if($fechaRet==null)
        {
            $fechaRetiro="";
        }
        else
        {    
            $fechaRetiro=", hasta el ".$fecRet[0]." de ".$mesRet." del ".$fecRet[2];
        }
        if($generoBD=='F')
        {
            $genero='la señora';
            $identificado='identificada';
            $adscrito='adscrita';
            $interesado='de la interesada';
            $vinculado='vinculada';
        }
        else
        {
            $genero='el señor';
            $identificado='identificado';
            $adscrito='adscrito';
            $interesado='del interesado';
            $vinculado='vinculado';
        }    
        
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos/css/miestilo.css";

        //include_once($rutaBloque."css/miestilo.css");
        if($registro[0][7]==null && $registro[0][15]==null)
        {    
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <br>
            <br>
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." con ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            se encuentra ".$vinculado." a esta institución desde el ".$fechaIngreso.", actualmente desempeña el cargo de ".$cargo.", adscrito a la
            ".$dependencia.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        elseif($registro[0][7]!=null && $registro[0][15]==null)
        {
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." con ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            estuvo ".$vinculado." a esta institución desde el ".$fechaIngreso."".$fechaRetiro.", desempeñó el cargo de ".$cargo.", adscrito a la
            ".$dependencia.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        elseif($registro[0][13]==null && $registro[0][15]!=null)
        {
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." con ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            estuvo ".$vinculado." a esta institución desde el ".$fechaIngreso."".$fechaRetiro.", actualmente se encuentra ".$vinculado." como
            ".$cargo.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        //echo $contenido;

        $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
        include_once($rutaClases."/html2pdf/html2pdf.class.php");
        //require_once('clase/html2pdf/html2pdf.class.php');
        $html2pdf = new HTML2PDF('P','A4','es', array(10, 20, 10, 20));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($contenido);
        $html2pdf->Output('certificado.pdf');
    }
    if($_REQUEST['tipoCertificado']=='certificado1')
    {
        //echo $cadena_sql."<br>";
        setlocale(LC_MONETARY, 'it_IT');
        $sueldoBasicoMensual=money_format('$ %!.2n',$registro[0][12]);
        $valorMesada=money_format('$ %!.2n',$registro[0][15]);
        $salarioPromedio=money_format('$ %!.0i',$registro[0][13]);
        $mesada=money_format('$ %!.0i',$registro[0][15]);
        if($registro[0][4]=="CEDULA CIUDADANIA")
        {
            $tipoDocumento="cédula de ciudadanía";
        }
        else
        {    
            $tipoDocumento=strtolower($registro[0][4]);
        }
        $numeroIdentificacion=$registro[0][5];
        $fechaIng=$registro[0][6];
        $fechaRet=$registro[0][7];
        $fecIngreso=date("d-M-Y",strtotime($fechaIng));
        $fecRetiro=date("d-M-Y",strtotime($fechaRet));
        $fecIng=explode('-', $fecIngreso);
        $fecRet=explode('-', $fecRetiro);
        $apellidosNombres=$registro[0][0].' '.$registro[0][1].' '.$registro[0][2].' '.$registro[0][3];
        $cargo=$registro[0][10];
        $dependencia=$registro[0][11];
        $generoBD=$registro[0][16];
        $lugarExpedicion=$registro[0][17];
        $valorHonorarios=" ";
        $valorRetencion=" "; 
        setlocale(LC_ALL,"es_ES");
        $fechaHoy=strftime("%d dias del mes de %B del %Y");
        $jefe=$registro[0][14];
        
               
        if($fecIng[1]=='Jan')
        {
            $mesIng='Enero';
        }
        elseif($fecIng[1]=='Feb')
        {
            $mesIng='Febrero';
        }
        elseif($fecIng[1]=='Mar')
        {
            $mesIng='Marzo';
        }
        elseif($fecIng[1]=='Apr')
        {
            $mesIng='Abril';
        }
        elseif($fecIng[1]=='May')
        {
            $mesIng='Mayo';
        }
        elseif($fecIng[1]=='Jun')
        {
            $mesIng='Junio';
        }
        elseif($fecIng[1]=='Jul')
        {
            $mesIng='Julio';
        }
        elseif($fecIng[1]=='Aug')
        {
            $mesIng='Agosto';
        }
        elseif($fecIng[1]=='Sep')
        {
            $mesIng='Septiembre';
        }
        elseif($fecIng[1]=='Oct')
        {
            $mesIng='Octubre';
        }
        elseif($fecIng[1]=='Nov')
        {
            $mesIng='Noviembre';
        }
        elseif($fecIng[1]=='Dec')
        {
            $mesIng='Diciembre';
        }
        //Fecha retiro
        if( $fecRet[1]=='Jan')
        {
            $mesRet='Enero';
        }
        elseif($fecRet[1]=='Feb')
        {
            $mesRet='Febrero';
        }
        elseif($fecRet[1]=='Mar')
        {
            $mesRet='Marzo';
        }
        elseif($fecRet[1]=='Apr')
        {
            $mesRet='Abril';
        }
        elseif($fecRet[1]=='May')
        {
            $mesRet='Mayo';
        }
        elseif($fecRet[1]=='Jun')
        {
            $mesRet='Junio';
        }
        elseif($fecRet[1]=='Jul')
        {
            $mesRet='Julio';
        }
        elseif($fecRet[1]=='Aug')
        {
            $mesRet='Agosto';
        }
        elseif($fecRet[1]=='Sep')
        {
            $mesRet='Septiembre';
        }
        elseif($fecRet[1]=='Oct')
        {
            $mesRet='Octubre';
        }
        elseif($fecRet[1]=='Nov')
        {
            $mesRet='Noviembre';
        }
        elseif($fecRet[1]=='Dec')
        {
            $mesRet='Diciembre';
        }
        
        $fechaIngreso=$fecIng[0]." de ".$mesIng." del ".$fecIng[2];
        
        if($fechaRet==null)
        {
            $fechaRetiro="";
        }
        else
        {    
            $fechaRetiro=", hasta el ".$fecRet[0]." de ".$mesRet." del ".$fecRet[2];
        }
        
        if($generoBD=='F')
        {
            $genero='la señora';
            $identificado='identificada';
            $adscrito='adscrita';
            $interesado='de la interesada';
            $vinculado='vinculada';
        }
        else
        {
            $genero='el señor';
            $identificado='identificado';
            $adscrito='adscrito';
            $interesado='del interesado';
            $vinculado='vinculado';
        }    
        
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos/css/miestilo.css";

        //include_once($rutaBloque."css/miestilo.css");
        if($registro[0][7]==null && $registro[0][15]==null)
        {    
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            se encuentra ".$vinculado." a esta institución desde el ".$fechaIngreso.", actualmente desempeña el cargo de ".$cargo.", adscrito a la
            ".$dependencia.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            VALOR SUELDO BASICO MENSUAL......................................................................".$sueldoBasicoMensual."
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        elseif($registro[0][7]!=null && $registro[0][15]==null)
        {
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." con ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            estuvo ".$vinculado." a esta institución desde el ".$fechaIngreso."".$fechaRetiro.", desempeñó el cargo de ".$cargo.", adscrito a la
            ".$dependencia.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        
        elseif($registro[0][13]==null && $registro[0][15]!=null)
        {
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." con ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            estuvo ".$vinculado." a esta institución desde el ".$fechaIngreso."".$fechaRetiro.", actualmente se encuentra ".$vinculado." como
            ".$cargo.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            VALOR MESADA....................................................................................................".$valorMesada."
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        //echo $contenido;

        $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
        include_once($rutaClases."/html2pdf/html2pdf.class.php");
        //require_once('clase/html2pdf/html2pdf.class.php');
        $html2pdf = new HTML2PDF('P','A4','es', array(10, 20, 10, 20));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($contenido);
        $html2pdf->Output('certificado.pdf');
    }
   
    if($_REQUEST['tipoCertificado']=='certificado2')
    {
        //echo $cadena_sql."<br>";
        setlocale(LC_MONETARY, 'es_ES');
        $sueldoBasicoMensual=money_format('$ %!.2n',$registro[0][12]);
        $valorMesada=money_format('$ %!.2n',$registro[0][15]);
        $salarioPromedio=money_format('$ %!.2n',$registro[0][13]);
        $mesada=money_format('$ %!.0i',$registro[0][15]);
        if($registro[0][4]=="CEDULA CIUDADANIA")
        {
            $tipoDocumento="cédula de ciudadanía";
        }
        else
        {    
            $tipoDocumento=strtolower($registro[0][4]);
        }
        $numeroIdentificacion=$registro[0][5];
        $fechaIng=$registro[0][6];
        $fechaRet=$registro[0][7];
        $fecIngreso=date("d-M-Y",strtotime($fechaIng));
        $fecRetiro=date("d-M-Y",strtotime($fechaRet));
        $fecIng=explode('-', $fecIngreso);
        $fecRet=explode('-', $fecRetiro);
        $apellidosNombres=$registro[0][0].' '.$registro[0][1].' '.$registro[0][2].' '.$registro[0][3];
        $cargo=$registro[0][10];
        $dependencia=$registro[0][11];
        $generoBD=$registro[0][16];
        $lugarExpedicion=$registro[0][17];
        $valorHonorarios=" ";
        $valorRetencion=" "; 
        setlocale(LC_ALL,"es_ES");
        $fechaHoy=strftime("%d dias del mes de %B del %Y");
        $jefe=$registro[0][14];
        
        if($fecIng[1]=='Jan')
        {
            $mesIng='Enero';
        }
        elseif($fecIng[1]=='Feb')
        {
            $mesIng='Febrero';
        }
        elseif($fecIng[1]=='Mar')
        {
            $mesIng='Marzo';
        }
        elseif($fecIng[1]=='Apr')
        {
            $mesIng='Abril';
        }
        elseif($fecIng[1]=='May')
        {
            $mesIng='Mayo';
        }
        elseif($fecIng[1]=='Jun')
        {
            $mesIng='Junio';
        }
        elseif($fecIng[1]=='Jul')
        {
            $mesIng='Julio';
        }
        elseif($fecIng[1]=='Aug')
        {
            $mesIng='Agosto';
        }
        elseif($fecIng[1]=='Sep')
        {
            $mesIng='Septiembre';
        }
        elseif($fecIng[1]=='Oct')
        {
            $mesIng='Octubre';
        }
        elseif($fecIng[1]=='Nov')
        {
            $mesIng='Noviembre';
        }
        elseif($fecIng[1]=='Dec')
        {
            $mesIng='Diciembre';
        }
        //Fecha retiro
        if( $fecRet[1]=='Jan')
        {
            $mesRet='Enero';
        }
        elseif($fecRet[1]=='Feb')
        {
            $mesRet='Febrero';
        }
        elseif($fecRet[1]=='Mar')
        {
            $mesRet='Marzo';
        }
        elseif($fecRet[1]=='Apr')
        {
            $mesRet='Abril';
        }
        elseif($fecRet[1]=='May')
        {
            $mesRet='Mayo';
        }
        elseif($fecRet[1]=='Jun')
        {
            $mesRet='Junio';
        }
        elseif($fecRet[1]=='Jul')
        {
            $mesRet='Julio';
        }
        elseif($fecRet[1]=='Aug')
        {
            $mesRet='Agosto';
        }
        elseif($fecRet[1]=='Sep')
        {
            $mesRet='Septiembre';
        }
        elseif($fecRet[1]=='Oct')
        {
            $mesRet='Octubre';
        }
        elseif($fecRet[1]=='Nov')
        {
            $mesRet='Noviembre';
        }
        elseif($fecRet[1]=='Dec')
        {
            $mesRet='Diciembre';
        }
        
        $fechaIngreso=$fecIng[0]." de ".$mesIng." del ".$fecIng[2];
        
        if($fechaRet==null)
        {
            $fechaRetiro="";
        }
        else
        {    
            $fechaRetiro=", hasta el día ".$fecRet[0]." de ".$mesRet." del ".$fecRet[2];
        }
        
        if($generoBD=='F')
        {
            $genero='la señora';
            $identificado='identificada';
            $adscrito='adscrita';
            $interesado='de la interesada';
            $vinculado='vinculada';
        }
        else
        {
            $genero='el señor';
            $identificado='identificado';
            $adscrito='adscrito';
            $interesado='del interesado';
            $vinculado='vinculado';
        }    
        
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos/css/miestilo.css";

        //include_once($rutaBloque."css/miestilo.css");
        if($registro[0][13]!=null)
        {    
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            se encuentra ".$vinculado." a esta institución desde el ".$fechaIngreso.", actualmente desempeña el cargo de ".$cargo.", adscrito a la
            ".$dependencia.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            VALOR SALARIO PROMEDIO MENSUAL..............................................................".$salarioPromedio."
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        else
        {
            $contenido="<page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            El documento consultado no cuenta con registro de salario promedio mensual.
            </page>";
        }    
        
        $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
        include_once($rutaClases."/html2pdf/html2pdf.class.php");
        //require_once('clase/html2pdf/html2pdf.class.php');
        $html2pdf = new HTML2PDF('P','A4','es', array(10, 20, 10, 20));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($contenido);
        $html2pdf->Output('certificado.pdf');
    }
    
    if($_REQUEST['tipoCertificado']=='certificado3')
    {
        $cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
        $registroUsuario = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        $variable['codEmpleado']=$registroUsuario[0][2];
        $variable['codDetalleSalBasico']=1;
        $variable['codDetalleOtrosIng']=2;
        $variable['codDetalleDoceavas']=3;
        $variable['codDetalleSalProm']=4;
        
        $cadena_sql = $this->sql->cadena_sql("sueldoBasico", $variable);
        $registroSueldoBasico = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $cadena_sql = $this->sql->cadena_sql("otrosIngresos", $variable);
        $registroOtrosIngresos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $cadena_sql = $this->sql->cadena_sql("doceavas", $variable);
        $registroDoceavas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $cadena_sql = $this->sql->cadena_sql("salarioPromedio", $variable);
        $registroSalarioPromedio = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        //echo $cadena_sql."<br>";
        setlocale(LC_MONETARY, 'es_ES');
        $sueldoBasico=money_format('$ %!.2n',$registroSueldoBasico[0][0]);
        $otrosIngresos=money_format('$ %!.2n',$registroOtrosIngresos[0][0]);
        $doceavas=money_format('$ %!.2n',$registroDoceavas[0][0]);
        $salarioPromedio=money_format('$ %!.2n',$registroSalarioPromedio[0][0]);
        $mesada=money_format('$ %!.0i',$registro[0][15]);
        if($registro[0][4]=="CEDULA CIUDADANIA")
        {
            $tipoDocumento="cédula de ciudadanía";
        }
        else
        {    
            $tipoDocumento=strtolower($registro[0][4]);
        }                               
        $numeroIdentificacion=$registro[0][5];
        $fechaIng=$registro[0][6];
        $fechaRet=$registro[0][7];
        $fecIngreso=date("d-M-Y",strtotime($fechaIng));
        $fecRetiro=date("d-M-Y",strtotime($fechaRet));
        $fecIng=explode('-', $fecIngreso);
        $fecRet=explode('-', $fecRetiro);
        $apellidosNombres=$registro[0][0].' '.$registro[0][1].' '.$registro[0][2].' '.$registro[0][3];
        $cargo=$registro[0][10];
        $dependencia=$registro[0][11];
        $generoBD=$registro[0][16];
        $lugarExpedicion=$registro[0][17];
        $valorHonorarios=" ";
        $valorRetencion=" "; 
        setlocale(LC_ALL,"es_ES");
        $fechaHoy=strftime("%d dias del mes de %B del %Y");
        $jefe=$registro[0][14];
        
        if($fecIng[1]=='Jan')
        {
            $mesIng='Enero';
        }
        elseif($fecIng[1]=='Feb')
        {
            $mesIng='Febrero';
        }
        elseif($fecIng[1]=='Mar')
        {
            $mesIng='Marzo';
        }
        elseif($fecIng[1]=='Apr')
        {
            $mesIng='Abril';
        }
        elseif($fecIng[1]=='May')
        {
            $mesIng='Mayo';
        }
        elseif($fecIng[1]=='Jun')
        {
            $mesIng='Junio';
        }
        elseif($fecIng[1]=='Jul')
        {
            $mesIng='Julio';
        }
        elseif($fecIng[1]=='Aug')
        {
            $mesIng='Agosto';
        }
        elseif($fecIng[1]=='Sep')
        {
            $mesIng='Septiembre';
        }
        elseif($fecIng[1]=='Oct')
        {
            $mesIng='Octubre';
        }
        elseif($fecIng[1]=='Nov')
        {
            $mesIng='Noviembre';
        }
        elseif($fecIng[1]=='Dec')
        {
            $mesIng='Diciembre';
        }
        //Fecha retiro
        if( $fecRet[1]=='Jan')
        {
            $mesRet='Enero';
        }
        elseif($fecRet[1]=='Feb')
        {
            $mesRet='Febrero';
        }
        elseif($fecRet[1]=='Mar')
        {
            $mesRet='Marzo';
        }
        elseif($fecRet[1]=='Apr')
        {
            $mesRet='Abril';
        }
        elseif($fecRet[1]=='May')
        {
            $mesRet='Mayo';
        }
        elseif($fecRet[1]=='Jun')
        {
            $mesRet='Junio';
        }
        elseif($fecRet[1]=='Jul')
        {
            $mesRet='Julio';
        }
        elseif($fecRet[1]=='Aug')
        {
            $mesRet='Agosto';
        }
        elseif($fecRet[1]=='Sep')
        {
            $mesRet='Septiembre';
        }
        elseif($fecRet[1]=='Oct')
        {
            $mesRet='Octubre';
        }
        elseif($fecRet[1]=='Nov')
        {
            $mesRet='Noviembre';
        }
        elseif($fecRet[1]=='Dec')
        {
            $mesRet='Diciembre';
        }
        
        $fechaIngreso=$fecIng[0]." de ".$mesIng." del ".$fecIng[2];
        
        if($fechaRet==null)
        {
            $fechaRetiro="";
        }
        else
        {    
            $fechaRetiro=", hasta el día ".$fecRet[0]." de ".$mesRet." del ".$fecRet[2];
        }
        
        if($generoBD=='F')
        {
            $genero='la señora';
            $identificado='identificada';
            $adscrito='adscrita';
            $interesado='de la interesada';
            $vinculado='vinculada';
        }
        else
        {
            $genero='el señor';
            $identificado='identificado';
            $adscrito='adscrito';
            $interesado='del interesado';
            $vinculado='vinculado';
        }    
        
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos/css/miestilo.css";

        //include_once($rutaBloque."css/miestilo.css");
        if($registro[0][13]!=null)
        {    
            $contenido="
            <page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            <p align='center'><img src='".$ruta."/images/udistrital1.png'  width='125' height='98'></p> 
            <p style='font-size: 14; text-align:center'>EL JEFE DE LA DIVISIÓN DE RECURSOS HUMANOS DE LA<br>
            UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS<br>
            CON NIT.899.999.230 - 7 </p>
            <p style='font-size: 14; text-align:center'>CERTIFICA: </p>
            <p style='font-size: 14; text-align:justify'>
            Que ".$genero." ".$apellidosNombres.", ".$identificado." ".$tipoDocumento." No. ".$numeroIdentificacion." de ".$lugarExpedicion.",
            se encuentra ".$vinculado." a esta institución desde el ".$fechaIngreso.", actualmente desempeña el cargo de ".$cargo.", adscrito a la
            ".$dependencia.".    
            </p>
            <p style='font-size: 14; text-align:justify'>
            VALOR SUELDO BASICO..............................................................................".$sueldoBasico."
            </p>
            <p style='font-size: 14; text-align:justify'>
            OTROS INGRESOS...........................................................................................".$otrosIngresos."
            </p>
            <p style='font-size: 14; text-align:justify'>
            DOCEAVAS.....................................................................................................".$doceavas."
            </p>
            <p style='font-size: 14; text-align:justify'>
            SALARIO PROMEDIO.....................................................................................".$salarioPromedio."
            </p>
            <p style='font-size: 14; text-align:justify'>
            ".$_REQUEST['observacion']."
            </p>
            <p style='font-size: 14; text-align:justify'>
            Se expide  en Bogotá  a los ".$fechaHoy.", a solicitud ".$interesado.".
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 14; text-align:center'>
            __________________________________________<br><br>
            ".$jefe."<br>
            Jefe de Division de Recursos Humanos
            </p>
            <p align='justify'>
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Certificado generado por el Sistema de Gestión Financiera, la firma del Jefe de la División de Recursos Humanos, certifica su aprobación.
            </p>
            <p align='justify'>
            </p>
            <p style='font-size: 10; text-align:center'>
            Division de Recursos Humanos<br>
            Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1627<br>
            Bogota D.C
            </p>
            </page>";
        }
        else
        {
            $contenido="<page backtop='17mm' backbottom='10mm' backleft='20mm' backright='15mm'>
            El documento consultado no cuenta con registro de salario promedio mensual.
            </page>";
        }    
        
        //echo $contenido;
        
        $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
        include_once($rutaClases."/html2pdf/html2pdf.class.php");
        //require_once('clase/html2pdf/html2pdf.class.php');
        $html2pdf = new HTML2PDF('P','A4','es', array(10, 20, 10, 20));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($contenido);
        $html2pdf->Output('certificado.pdf');
    }
}
else
{
    $this->funcion->redireccionar ("mostrarMensaje");
}    

?>
