<?
$ruta = $this->miConfigurador->getVariableConfiguracion("host");
$ruta.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos";

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
$variable['anio']=$_REQUEST['anio'];

$variable['usuario']=$_REQUEST['usuario'];

$cadena_sql = $this->sql->cadena_sql("consultaUvt", $variable);
$registroUvt = $esteRecursoDBPGS->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("cargo", "");
$registroCargo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($_REQUEST['tipoCertificado']=='funcionarios')
{
    $cadena_sql = $this->sql->cadena_sql("certificadoFuncionarios", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    //echo $cadena_sql."<br>";
    setlocale(LC_MONETARY, 'en_US');
    $valorPagado=money_format('$ %!.0i',$registro[0][10]);
    $valorCesantias=money_format('$ %!.0i',$registro[0][11]);
    $valorGastos=money_format('$ %!.0i',$registro[0][12]);
    $valorPensiones=money_format('$ %!.0i',$registro[0][13]);
    $valorOtros=money_format('$ %!.0i',$registro[0][14]);
    $valorTotal=money_format('$ %!.0i',$registro[0][15]);
    $aporteSalud=money_format('$ %!.0i',$registro[0][16]);
    $aportePension=money_format('$ %!.0i',$registro[0][18]);
    $aportePensionVoluntaria=money_format('$ %!.0i',$registro[0][17]);
    $valorRetefuente=money_format('$ %!.0i',$registro[0][20]);
    $anioGravable=$registro[0][1];
    $numeroIdentificacion=$registro[0][0];
    $apellidosNombres=$registro[0][22];
    $periodoCertificacion=$registro[0][5]." - ".$registro[0][4]." - ".$registro[0][3]."&nbsp;&nbsp; A: ".$registro[0][9]." - ".$registro[0][8]." - ".$registro[0][7];
    $fechaExpedicion=$registro[0][26]." - ".$registro[0][25]." - ".$registro[0][24];
    $valorHonorarios=" ";
    $valorRetencion=" "; 
}
elseif($_REQUEST['tipoCertificado']=='contratistasHonorarios')
{
    $cadena_sql = $this->sql->cadena_sql("certificadoContratistas", $variable);
    $registro = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
    
    for($k=0; $k<=count($registro)-1; $k++)
    {
        if($registro[$k][22]=='H')
        {
            setlocale(LC_MONETARY, 'en_US');
            $valorPagado="$0";
            $valorCesantias="$0";
            $valorGastos="$0";
            $valorPensiones="$0";
            $valorOtros="$0";
            $valorTotal="$0";
            $aporteSalud="$0";
            $aportePension="$0";
            $aportePensionVoluntaria="$0";
            $aportePensionVoluntaria="$0";
            $valorRetefuente="$0";
            $anioGravable=$registro[$k][0];
            $numeroIdentificacion=$registro[$k][1];
            $apellidosNombres=$registro[$k][2]." ".$registro[$k][3]." ".$registro[$k][4]." ".$registro[$k][5];
            $periodoCertificacion=$registro[$k][6]."&nbsp;&nbsp; A: ".$registro[$k][7];
            $fecha=  date("d-m-Y");
            $fechaExpedicion=$fecha;
            $valorHonorarios=money_format('$ %!.0i',$registro[$k][21]);
            $valorRetencion=money_format('$ %!.0i',$registro[$k][18]);
        }
    }
}
else
{
    $cadena_sql = $this->sql->cadena_sql("certificadoContratistas", $variable);
    $registro = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
    
    for($k=0; $k<=count($registro)-1; $k++)
    {
        if($registro[$k][22]=='S')
        {
            setlocale(LC_MONETARY, 'en_US');
            $valorPagado=money_format('$ %!.0i',$registro[$k][9]);
            $valorCesantias=money_format('$ %!.0i',$registro[$k][10]);
            $valorGastos=money_format('$ %!.0i',$registro[$k][11]);
            $valorPensiones=money_format('$ %!.0i',$registro[$k][12]);
            $valorOtros=money_format('$ %!.0i',$registro[$k][13]);
            $valorTotal=money_format('$ %!.0i',$registro[$k][14]);
            $aporteSalud=money_format('$ %!.0i',$registro[$k][15]);
            $aportePension=money_format('$ %!.0i',$registro[$k][20]);
            $aportePensionVoluntaria=money_format('$ %!.0i',$registro[$k][16]);
            $valorRetefuente=money_format('$ %!.0i',$registro[$k][18]);
            $anioGravable=$registro[$k][0];
            $numeroIdentificacion=$registro[$k][1];
            $apellidosNombres=$registro[$k][2]." ".$registro[$k][3]." ".$registro[$k][4]." ".$registro[$k][5];
            $periodoCertificacion=$registro[$k][6]."&nbsp;&nbsp; A: ".$registro[$k][7];
            $fecha=  date("d-m-Y");
            $fechaExpedicion=$fecha;
            $valorHonorarios=" ";
            $valorRetencion=" ";
        }
    }    
}    

        
if(is_array($registro))
{    
    //echo "mmm".$registro[0][0];
    $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
    $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos/css/miestilo.css";
    
    //include_once($rutaBloque."css/miestilo.css");
 
    $contenido="
    <page backtop='7mm' backbottom='7mm' backleft='15mm' backright='10mm'>
        <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 14%; text-align: center'>
                    <img src='".$ruta."/images/udistrital.png'> 
                </td>
                <td style='width: 86%; text-align: center; font-size: 18'>
                    Certificado de Ingresos y Retenciones <br>para Personas Naturales Empleados<br> Año Gravable ".$anioGravable."
                </td>
            </tr>
        </table>
        <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 1%' rowspan='2'>
                    <img src='".$ruta."/images/retenedor1.png'> 
                </td>
                <td style='width: 30%;text-align: center'>
                    <font style='font-size: 10'>Número de Identificación Tributaria (NIT).</font><br>
                    <font style='font-size: 12'>899999230 </font>
                </td>
                <td style='width: 3%;text-align: center'>
                    <font style='font-size: 10'> DV</font><br>
                    <font style='font-size: 12'>7</font>
                </td>
                <td style='width: 64%'>
                    &nbsp;
                </td>
            </tr>
           <tr>    
                <td style='text-align: left' colspan='3'>
                    <font style='font-size: 10'>Razón Social</font><br>
                    <font style='text-align: left; font-size: 12; font-weight:bold'>&nbsp;&nbsp;&nbsp;UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS</font>
                </td>
            </tr>
            
        </table>    
        <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>    
            <tr>
                <td style='width: 1%'>
                    <img src='".$ruta."/images/empleado1.png'> 
                </td>
                <td style='width: 8%;height:6 px;text-align:center'>
                    <font style='font-size: 10'>Cod. Tipo<br>Documento</font><br>
                    <font style='font-size: 12'> 13 </font>
                </td>
                <td style='width: 22%;height:6 px;text-align:center'>
                    <font style='font-size: 10'>Número de indentificación</font><br>
                    <font style='font-size: 12'>".$numeroIdentificacion."</font>
                </td>
                <td style='width: 67%;height:6 px;text-align:left' colspan='4'>
                    <font style='font-size: 10'>Apellidos y nombres</font><br>
                    <font style='font-size: 12'> ".$apellidosNombres." </font>
                </td>
            </tr>
            <tr>
                <td style='width: 22%;height:6 px;text-align:center' colspan='3'>
                    <font style='font-size: 10'>Período de la Certificación</font><br>
                    <font style='font-size: 12'>De: ".$periodoCertificacion." </font>
                </td>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10'>Fecha de expedición</font><br>
                    <font style='text-align: center; font-size: 12'>".$fechaExpedicion."</font>
                </td>
                <td style='width: 22%;height:6 px; text-align:center'>
                    <font style='font-size: 10'>Lugar donde se practicó la retención</font><br>
                    <font style='font-size: 12'>BOGOTÁ (CUNDINAMARCA)</font>
                </td>
                <td style='width: 4%;height:6 px; text-align:center'>
                    <font style='font-size: 10'>Cod. Dpto.</font><br>
                    <font style='font-size: 12'>11</font>
                </td>
                <td style='width: 4%;height:6 px; text-align:center'>
                    <font style='font-size: 10'>Cod. Ciudad</font><br>
                    <font style='font-size: 12'>1</font>
                </td>
            </tr>
        </table>
        <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 75%;height:6 px; text-align:center'>
                    <font style='font-size: 9'>Número de agencias, sucursales, filiales o subsidiarias de la empresa retenedora cuyos montos de retención se consolidan</font>
                </td>
                 <td style='width: 5%;height:6 px; text-align:center'>
                    <font style='font-size: 9'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 9'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:center; background-color:#F2F2F2' colspan='2'>
                    <font style='font-size: 10;font-weight:bold'>Concepto de los ingresos</font>
                </td>
                 <td style='height:6 px; text-align:center; background-color:#F2F2F2'>
                    <font style='font-size: 10;font-weight:bold'>Valor</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Pagos al empleado</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorPagado."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Cesantías e intereses de cesantías efectivamente pagadas en el periodo</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorCesantias."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Gastos de representación</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorGastos."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Pensiones de jubilación, vejez o invalidez</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorPensiones."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Otros ingresos como empleado</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorOtros."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10;font-weight:bold'>Total ingresos brutos</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10;font-weight:bold'>".$valorTotal."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:center; background-color:#F2F2F2' colspan='2'>
                    <font style='font-size: 10;font-weight:bold'>Concepto de los aportes</font>
                </td>
                 <td style='height:6 px; text-align:center; background-color:#F2F2F2'>
                    <font style='font-size: 10;font-weight:bold'>Valor</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Aportes obligatorios por salud</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$aporteSalud."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Aportes obligatorios a fondos de pensiones y solidaridad pensional</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$aportePension."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='2'>
                    <font style='font-size: 10'>Aportes voluntarios a fondos de pensiones y cuentas AFC</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$aportePensionVoluntaria."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left; background-color:#6E6E6E' colspan='2'>
                    <font style='font-size: 10; color:white;font-weight:bold'>Valor de la retención en la fuente por pagos al empleado</font>
                </td>
                <td style='height:6 px; text-align:right; background-color:#6E6E6E'>
                    <font style='font-size: 10; color:white;font-weight:bold'>".$valorRetefuente."</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left' colspan='3'>
                    <table>
                        <tr>
                            <td style='width: 30%'>
                                <font style='font-size: 9'>Nombre del  pagador o agente retenedor</font><br>
                                <font style='font-size: 12'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$registroCargo[0][1]."</font>
                            </td>
                            <td style='width: 30%'>
                                <font style='font-size: 11'>CC</font><br>
                                <font style='font-size: 12'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$registroCargo[0][0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
                            </td> 
                            <td style='text-align:center' >
                                <font style='font-size: 12'>SIN FIRMA AUTÓGRAFA, ARTÍCULO<br>
                                10 DECRETO 836 DE 1991</font>
                            </td>
                        </tr>
                    </table>    
                </td>
            </tr>
        </table>
        <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 100%;height:6 px; text-align:center; background-color:#F2F2F2' colspan='3'>
                    <font style='font-size: 10;font-weight:bold'>Datos a cargo del empleado</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>Concepto de otros ingresos</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>Valor recibido</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>Valor retenido</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10'>Arrendamientos</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10'>Honorarios, comisiones y servicios</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorHonorarios."</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:right'>
                    <font style='font-size: 10'>".$valorRetencion."</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10'>Intereses y rendimientos financieros</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10'>Enajenación de activos fijos</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10'>Loterías, rifas, apuestas y similares</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10'>Otros</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 40%;height:6 px; text-align:left'>
                    <font style='font-size: 10;font-weight:bold'>Totales</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
           </tr>     
           <tr>
                <td style='width: 40%;height:6 px; text-align:left; background-color:#FAFAFA' colspan='2'>
                    <font style='font-size: 10;font-weight:bold'>Total retenciones año gravable ".$anioGravable."</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
        </table>
        <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 75%;height:6 px; text-align:center; background-color:#F2F2F2'>
                    <font style='font-size: 10;font-weight:bold'>Identificación de los bienes poseidos</font>
                </td>
                 <td style='width: 25%;height:6 px; text-align:center; background-color:#F2F2F2' colspan='2'>
                    <font style='font-size: 10;font-weight:bold'>Valor Patrimonial</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 59%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='height:6 px; text-align:left; background-color:#6E6E6E'>
                    <font style='font-size: 10; color:white;font-weight:bold'>Deudas vigentes a 31 de Diciembre de ".$anioGravable."</font>
                </td>
                <td style='height:6 px; text-align:right'>
                    <font style='font-size: 10; color:white;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
       </table> 
       <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 100%;height:6 px; text-align:center; background-color:#F2F2F2' colspan='3'>
                    <font style='font-size: 10;font-weight:bold'>Identificación de las personas dependientes de acuerdo al parágrafo 2 del artículo 387 del Estatuto Tributario</font>
                </td>
            </tr>
            <tr>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>C.C. o NIT</font>
                </td>
                <td style='width: 40%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>Apellidos y Nombres</font>
                </td>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>Parentesco</font>
                </td>
            </tr>
            <tr>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 40%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 40%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 40%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
            <tr>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 40%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
                <td style='width: 10%;height:6 px; text-align:center'>
                    <font style='font-size: 10;font-weight:bold'>&nbsp;</font>
                </td>
            </tr>
      </table>      
      <table border='1' style='width: 100%; border-width: 1px 1px 1px 1px; font-family: Arial, Verdana, Trebuchet MS, Helvetica, sans-serif; border-spacing: 1px; border-collapse: collapse; padding:10px'>
            <tr>
                <td style='width: 80%;height:6 px; text-align:left'>
                    <font style='font-size: 9'><br>Certifico que durante el año gravable de ".$anioGravable.":<br>";
                    for($i=0; $i<=count ($registroUvt)-1; $i++)
                    {
                        if($registroUvt[$i]['rtfte_nombre']=='patrimonio')
                        {
                            $valorNumero= $registroUvt[$i]['rtfte_valor_numero'];
                            $valorTotal=money_format('$ %!.0i',$registroUvt[$i]['rtfte_valor_total']);
                            $contenido.="1. Mi patrimonio bruto era igual o inferior a ".$registroUvt[$i]['rtfte_valor_letras']. " (".number_format($valorNumero,0,",",".").") UVT (".$valorTotal."). <br>";
                        }
                        
                        if($registroUvt[$i]['rtfte_nombre']=='ingresos')
                        {
                            $valorNumero= $registroUvt[$i]['rtfte_valor_numero'];
                            $valorTotal=money_format('$ %!.0i',$registroUvt[$i]['rtfte_valor_total']);
                            $contenido.="2. No fui responsable del impuesto sobre las ventas.<br>";
                            $contenido.="3. Mis ingresos brutos fueron inferiores a ".$registroUvt[$i]['rtfte_valor_letras']. " (".number_format($valorNumero,0,",",".").") UVT (".$valorTotal.").<br> ";
                        }
                        if($registroUvt[$i]['rtfte_nombre']=='tarjetaCredito')
                        {
                            $valorNumero= $registroUvt[$i]['rtfte_valor_numero'];
                            $valorTotal=money_format('$ %!.0i',$registroUvt[$i]['rtfte_valor_total']);
                            $contenido.="4. Mis consumos mediante tarjeta crédito no excedieron la suma de ".$registroUvt[$i]['rtfte_valor_letras']. " (".number_format($valorNumero,0,",",".").") UVT (".$valorTotal.").<br> ";
                        }
                        if($registroUvt[$i]['rtfte_nombre']=='comprasConsumos')
                        {
                            $valorNumero= $registroUvt[$i]['rtfte_valor_numero'];
                            $valorTotal=money_format('$ %!.0i',$registroUvt[$i]['rtfte_valor_total']);
                            $contenido.="5. Que el total de mis compras y consumos no superaron la suma de ".$registroUvt[$i]['rtfte_valor_letras']. " (".number_format($valorNumero,0,",",".").") UVT (".$valorTotal.").<br> ";
                        }
                        if($registroUvt[$i]['rtfte_nombre']=='consignaciones')
                        {
                            $valorNumero= $registroUvt[$i]['rtfte_valor_numero'];
                            $valorTotal=money_format('$ %!.0i',$registroUvt[$i]['rtfte_valor_total']);
                            $contenido.="6. Que el valor de mis consignaciones bancarias, depósitos o inversiones financieras no excedieron las ".$registroUvt[$i]['rtfte_valor_letras']. " (".number_format($valorNumero,0,",",".").") UVT (".$valorTotal.").<br> ";
                        }
                    }
                    $contenido.="Por lo tanto, manifiesto que no estoy obligado a presentar declaración de renta y complementarios por el año gravable ".$anioGravable.".<br>&nbsp;";
                    $contenido.="</font>
                </td>
                <td style='width: 20%;height:6 px; text-align:left;vertical-align:text-top'>
                    <font style='font-size: 9'>Firma del empleado</font>
                </td>
            </tr>
     </table>
     <font style='font-size: 9'>NOTA: Este certificado sustituye para todos los efectos legales la declaración de Renta y complementarios para el empleado que lo firme.</font>
    </page>";
    
    //echo $contenido;
    
    $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
    include_once($rutaClases."/html2pdf/html2pdf.class.php");
    //require_once('clase/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','es', array(10, 20, 10, 20));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->WriteHTML($contenido);
    $html2pdf->Output('certificado.pdf');
}
else
{
    $this->funcion->redireccionar ("mostrarMensaje");
}    

?>
