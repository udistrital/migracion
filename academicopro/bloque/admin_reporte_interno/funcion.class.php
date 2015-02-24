<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf_interno/fpdf.php");
include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/admin_reporte_interno/sql.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf_int/pdf/fpdf.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
//06/11/2012 Milton Parra: Se ajustan mensajes cuando faltan datos en las notas. Se coloca mensaje cuando no presenta Promedio Acumulado
class funcion_reporteInterno extends funcionGeneral
{
	//@ Método costructor
	function __construct($configuracion)
	{
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        //Se llama a la clase que carga el documento e imprime la tabla
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf_int/pdf/ejemplo.php");


		$this->cripto=new encriptar();
		//$this->tema=$tema;
                $this->sql=new sql_reporteInterno();

                 //Conexion General
                $this->acceso_db=$this->conectarDB($configuracion,"");

                //Conexion sga
                $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

                //Conexion Oracle
                $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

                //Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");

                $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                $this->formulario='admin_reporte_interno';

	}

	// @ Método para generar informes en PDF
	function generarInforme($configuracion)
        {
            $creditosCursados=0;
            $pdf=new PDF();
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/numerosALetras.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/promedios.class.php");
            //Esta funcion la utilizaremos cuando integremos el subsistema
            //aqui seleccionamos el codigo si viene de una tabla o ingresado
            //o en el caso de cert internos para capturar la sesion.

            if(!isset($_REQUEST['codigo'])||$_REQUEST['codigo']==NULL)
            {
                $codEstudiante=$this->usuario;
                
            }else{
                $codEstudiante=$_REQUEST['codigo'];
            }
            //Instanciar el objeto
            $letra = new numerosALetras();
            $prom = new promedios();

            //busca si el estudiante es de creditos
            $cadena_sql_est=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_estudiante", $codEstudiante);
            $resultado_est=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_est,"busqueda");

            $cadena_sql_cra=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_carrera", $resultado_est);
            $resultado_cra=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cra,"busqueda");

            $cadena_sql_prom=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_promedio", $codEstudiante);
            $resultado_promedio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_prom,"busqueda");

            if (is_null($resultado_promedio)||$resultado_promedio===false)
            {
                $resultado_promedio[0]['PROMEDIO']='**';
            }

            if(trim($resultado_est[0]['IND_CRED'])=='S') {
            //buscar espacios cursados para estudiantes creditos pregrado
            if($resultado_cra[0]['NIVEL']==1)
              {
                  $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarEspacioPregrado",$resultado_est);
                  $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                  $vistaRangos=$this->porcentajeParametros($configuracion, $codEstudiante);
                  $cadena_sql_clasif=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"descrip_clasif","");
                  $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_clasif, "busqueda");
              }
              else
            //buscar espacios cursados para estudiantes creditos posgrado
              {
                  $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarEspacioPosgrado",$resultado_est);
                  $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                  $vistaRangos='';
                  $resultado_clasif[0][0]='OB';
                  $resultado_clasif[0][1]='OBLIGATORIO';
                  $resultado_clasif[1][0]='EL';
                  $resultado_clasif[1][1]='ELECTIVO';
              }

            for($i=0;$i<=count($resultado);$i++)
                {
                    $creditosCursados+=(isset($resultado[$i]['CREDITOS'])?$resultado[$i]['CREDITOS']:'');
                }
            if($resultado == false)
            {
                echo "<script>alert('El Estudiante con Codigo ".$codEstudiante." no tiene notas registradas');</script>";
                echo "<script>javascript:window.history.back();</script>";
                exit;
            }
            //Titulos de las columnas
            $titulo=array(utf8_decode('CÓDIGO'),utf8_decode('ESPACIO ACADÉMICO'),'CREDITOS','CLASIF','H.T.D','H.T.C','H.T.A',utf8_decode('AÑO'),'PER','NOTA','OBS');

            //Array que contiene la informacion del estudiante
            $encabezado=array();

            // Nombre del estudiante
            $encabezado[0][0] = $resultado_est[0]['NOMBRE'];
            // Numero de identificación
            $encabezado[0][1] = $resultado_est[0]['DOCUMENTO'];
            // Promedio
            $encabezado[0][2] = $resultado_promedio[0]['PROMEDIO'];
            // Siguiente fila, nombre de la carrera
            $encabezado[1][0] = $resultado_cra[0]['NOMBRE'];
            // Codigo del estudiante
            $encabezado[1][1] = $resultado_est[0]['CODIGO'];
            // Fecha de generacion del certificado
            $encabezado[1][2] = date("j/M/Y", time());
            //Plan de estudios del estudiante
            $encabezado[2][0] = $resultado_est[0]["PLAN_ESTUDIO"];
            //Carga de datos
            $data=array();
            $row=0;
              for($i=0;$i<count($resultado);$i++)
              {
                  $nivel=(isset($resultado[$i]['NIVEL'])?$resultado[$i]['NIVEL']:'');
                  $creditos=(isset($resultado[$i]['CREDITOS'])?$resultado[$i]['CREDITOS']:'');
                  $clasificacion=(isset($resultado[$i]['CLASIFICACION'])?$resultado[$i]['CLASIFICACION']:'');
                  $data[$row][11]=$nivel;//nivel
                    if((isset($resultado[$i-1]['NIVEL'])?$resultado[$i-1]['NIVEL']:'')!=$nivel)//
                    {
                        if ((is_null($nivel)||$nivel=='') && $resultado_cra[0]['NIVEL']==1)
                          {
                            $data[$row][1]="XYZSIN NIVEL";//XYZ se usa para reconocer el nombre del nivel en el pdf
                            $row++;
                          }
                        elseif ($nivel==0 && $resultado_cra[0]['NIVEL']==1)
                          {
                            $data[$row][1]="XYZELECTIVAS";//XYZ se usa para reconocer el nombre del nivel en el pdf
                            $row++;
                          }
                          elseif($nivel==98)
                            {
                              $data[$row][1]="XYZCOMPONENTE PROPEDEUTICO";//XYZ se usa para reconocer el nombre del nivel en el pdf
                              $row++;
                              }
                          else
                            {
                              $data[$row][1]="XYZNIVEL ".$nivel;//XYZ se usa para reconocer el nombre del nivel en el pdf
                              $row++;
                              }
                    }
                        $data[$row][0]=$resultado[$i]['CODIGO'];//codigo
                        $data[$row][1]=utf8_decode($resultado[$i]['NOMBRE']);//nombre
                        $data[$row][2]=$creditos;//creditos
                        switch (trim($clasificacion)){
                        case 'S':
                          $data[$row][3]="EL";//abrev clasif
                          break;
                        case 'N':
                          $data[$row][3]="OB";//abrev clasif
                          break;
                        default :
                          $data[$row][3]=$clasificacion;//abrev clasif
                          break;
                        }
                        $data[$row][4]=(isset($resultado[$i]['HTD'])?$resultado[$i]['HTD']:'');//htd
                        $data[$row][5]=(isset($resultado[$i]['HTC'])?$resultado[$i]['HTC']:'');//htc
                        $data[$row][6]=(isset($resultado[$i]['HTA'])?$resultado[$i]['HTA']:'');//hta
                        $data[$row][7]=(isset($resultado[$i]['ANO'])?$resultado[$i]['ANO']:'');//ano
                        $data[$row][8]=(isset($resultado[$i]['PERIODO'])?$resultado[$i]['PERIODO']:'');//per
                        $data[$row][9]=number_format(($resultado[$i]['NOTA']/10),2);//nota
                        if (trim($resultado[$i]['OBSERVACION'])=='SIN OBSERVACION')
                          {}else
                          {$data[$row][10]=$resultado[$i]['OBSERVACION'];}//obs
                        if (is_null($creditos)||$creditos==''||is_null($clasificacion)||$clasificacion==''||is_null($nivel)||$nivel=='')
                            {
                                $data[$row][10]='DATOS INCOMPLETOS';
                            }
                            $row++;
              }
              //var_dump($data);exit;
                $pdf->SetTitle('');
                $pdf->AddPage();
                $pdf->SetFont('Arial','',12);
                $pdf->SetY(25);
                $pdf->SetFont('Arial','B',8);
                $pdf->Ln(10);
                $pdf->SetFont('Arial','B',8);
                $pdf->FancyTableCred($titulo, $data, $encabezado,$resultado_clasif, $creditosCursados,$vistaRangos);
               
                $pdf->Ln(5);

                $pdf->Output('','',$encabezado[1][1]);
              }else if(trim($resultado_est[0]['IND_CRED'])=='N')
                  {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_espacioHoras",$resultado_est);
                    $resultadoEspacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_promedio",$codEstudiante);
                    $resultadoPromedio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secretario", $codEstudiante);//echo $cadena_sql;exit;
                    $resultado_secre=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                    if($resultadoEspacios == false )
                    {
                        echo "<script>
                            alert('El Estudiante con Codigo ".$codEstudiante." no tiene notas registradas');
                            </script>
                                    ";
                        echo "<script>javascript:window.history.back();</script>";
                        exit;
                    }

                    //$promedio = $prom->acumuladohoras($resultadoPromedio);
                    $promedio = $resultadoPromedio[0]['PROMEDIO'];

                    //Titulos de las columnas
                    $titulo=array(utf8_decode('CÓDIGO'),utf8_decode('ASIGNATURA'),'H.T.T','H.T.P',utf8_decode('AÑO'),'PER','NOTA','OBS');

                    //Array que contiene la informacion del estudiante
                    $encabezado=array();
                    $firma = $resultado_secre[0][0].' '.$resultado_secre[0][1];

                    // Nombre del estudiante
                    $encabezado[0][0] = $resultadoEspacios[0][5];
                    // Numero de identificación
                    $encabezado[0][1] = $resultadoEspacios[0][6];
                    // Promedio
                    $encabezado[0][2] = number_format($promedio,2);
                    // Siguiente fila, nombre de la carrera
                    $encabezado[1][0] = $resultado_cra[0]['NOMBRE'];
                    // Codigo del estudiante
                    $encabezado[1][1] = $resultadoEspacios[0][4];
                    // Fecha de generacion del certificado
                    $encabezado[1][2] = date("j/M/Y", time());

                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarPlan",$codEstudiante);
                    $registroPlan=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                    $planEstudiante=$registroPlan[0][1];

                    $variableEspacio[0]=$planEstudiante;
                    //Carga de datos
                    $data=array();
                    $totalEspacios=count($resultadoEspacios);
                      for($i=0;$i<$totalEspacios;$i++)
                      {
                          $variableEspacio[1]=$resultadoEspacios[$i][0];
                            $data[$i][11]=(isset($resultadoEspacios[$i][3])?$resultadoEspacios[$i][3]:'');
                            if($data[$i][11]==0 AND $data[$i][11]!=(isset($data[$i-1][11])?$data[$i-1][11]:''))
                            {
                                $data[$i+$data[$i][11]][1]="XYZELECTIVAS" ;
                                $data[($i+1)+($data[$i][11])][0]=$resultadoEspacios[$i][0];
                                $data[($i+1)+($data[$i][11])][1]=utf8_decode($resultadoEspacios[$i][1]);
                                $data[($i+1)+($data[$i][11])][2]=(isset($resultadoEspacios[$i][13])?$resultadoEspacios[$i][13]:'');
                                $data[($i+1)+($data[$i][11])][3]=(isset($resultadoEspacios[$i][14])?$resultadoEspacios[$i][14]:'');
                                $data[($i+1)+($data[$i][11])][4]=$resultadoEspacios[$i][10];
                                $data[($i+1)+($data[$i][11])][5]=$resultadoEspacios[$i][12];
                                $data[($i+1)+($data[$i][11])][6]=number_format(((isset($resultadoEspacios[$i][2])?$resultadoEspacios[$i][2]:0)/10),2);
                                if (trim($resultadoEspacios[$i][11])=='SIN OBSERVACION')
                                  {}else
                                  {$data[($i+1)+($data[$i][11])][7]=$resultadoEspacios[$i][11];}//obs
                            }else
                            {

                               if($data[$i][11]!=(isset($data[$i-1][11])?$data[$i-1][11]:'') )
                                 {
                                        $data[$i+$data[$i][11]][1]="XYZSEMESTRE ".$data[$i][11];
                                        $data[($i+1)+($data[$i][11])][0]=$resultadoEspacios[$i][0];
                                        $data[($i+1)+($data[$i][11])][1]=utf8_decode($resultadoEspacios[$i][1]);
                                        $data[($i+1)+($data[$i][11])][2]=(isset($resultadoEspacios[$i][13])?$resultadoEspacios[$i][13]:'');
                                        $data[($i+1)+($data[$i][11])][3]=(isset($resultadoEspacios[$i][14])?$resultadoEspacios[$i][14]:'');
                                        $data[($i+1)+($data[$i][11])][4]=$resultadoEspacios[$i][10];
                                        $data[($i+1)+($data[$i][11])][5]=$resultadoEspacios[$i][12];
                                        $data[($i+1)+($data[$i][11])][6]=number_format(((isset($resultadoEspacios[$i][2])?$resultadoEspacios[$i][2]:0)/10),2);
                                        if (trim($resultadoEspacios[$i][11])=='SIN OBSERVACION')
                                          {}else
                                          {$data[($i+1)+($data[$i][11])][7]=$resultadoEspacios[$i][11];}//obs

                                }else {
                                        $data[($i+1)+($data[$i][11])][0]=$resultadoEspacios[$i][0];
                                        $data[($i+1)+($data[$i][11])][1]=utf8_decode($resultadoEspacios[$i][1]);
                                        $data[($i+1)+($data[$i][11])][2]=(isset($resultadoEspacios[$i][13])?$resultadoEspacios[$i][13]:'');
                                        $data[($i+1)+($data[$i][11])][3]=(isset($resultadoEspacios[$i][14])?$resultadoEspacios[$i][14]:'');
                                        $data[($i+1)+($data[$i][11])][4]=$resultadoEspacios[$i][10];
                                        $data[($i+1)+($data[$i][11])][5]=$resultadoEspacios[$i][12];
                                        $data[($i+1)+($data[$i][11])][6]=number_format(((isset($resultadoEspacios[$i][2])?$resultadoEspacios[$i][2]:0)/10),2);
                                        if (trim($resultadoEspacios[$i][11])=='SIN OBSERVACION')
                                          {}else
                                          {$data[($i+1)+($data[$i][11])][7]=$resultadoEspacios[$i][11];}//obs
                                      }

                          }
                      }

                      
                        $pdf->SetTitle('');
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','',12);
                        $pdf->SetY(25);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->FancyTableHoras($titulo, $data, $encabezado);

                        $pdf->Ln(5);

                        $pdf->Output('','',$encabezado[1][1]);
                    
                  }
            
	}
	
	function ingresarCodigo($configuracion)
	{
            $tab=0;
//        echo "<center><img src='".$configuracion['site'].$configuracion['grafico']."/enconstruccion.jpg' alt='En Construccíon'></center>";
//        exit;
        
   	?>


            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo  $this->formulario?>'>
          <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr align="center">
                    <td>
                        <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4> 
                	</td>
                </tr>
                <tr align="center">
                    <td>
                        <h4>GENERACI&Oacute;N DE CERTIFICADOS INTERNOS</h4>
                		  <hr noshade class="hr">
                      <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                  </td>
                </tr>
                 <tr>
                    <td align='center' class="bloquelateralcuerpo">C&Oacute;DIGO DEL ESTUDIANTE</td>
                </tr>
                <tr>
                    <td align='center'>
                        <input type='text' name='codigo' size='15' maxlength='15' >
					</td>
                </tr>
            <tr>
                <td  align='center'>
                    <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
					<input type='hidden' name='action' value='<? echo  $this->formulario ?>'>
					<input value="Generar Certificado" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
				</td>
			</tr>
		 </table>
       </form>

    <?
    }

        function generarCodigo($configuracion)
	{
        $tab=0;
        
      ?>  <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>

          <table align="center" border="0" width="500" height="300" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
         
          <tr align="center">
                    <td>
                        <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4> 
                	</td>
                </tr>
          <tr align="center">
          <td   >
           <h4>GENERACI&Oacute;N DE CERTIFICADOS INTERNOS</h4>
           
		  <hr noshade class="hr">
	      
          <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png "> <p style="line-height: 100%" align="center">
          <p align="justify" style="line-height: 100%">Si cambia su correo electr&oacute;nico, direcci&oacute;n o tel&eacute;fono; no olvide actualizarlos en el men&uacute;  Datos Personales. Recuerde que de la veracidad de sus datos, depende un efectivo ingreso al aplicativo.</p>
		
          </p>
          </td>
          </tr>
          <tr align="center">
          <td>
            <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
			<input value="Generar Certificado" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">
          </td>
          </tr>
          </table>
          </form>
    <?
    }


        function porcentajeParametros($configuracion,$codEstudiante)
        {
            $OBEst=$OCEst=$EIEst=$EEEst=$CPEst=$totalCreditosEst=0;
            $this->cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarPlan",$codEstudiante);
            $registroPlan=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
            $planEstudiante=$registroPlan[0][1];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"creditosPlan",$planEstudiante);//echo $cadena_sql;exit;
            $registroCreditosGeneral=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $totalCreditos= $registroCreditosGeneral[0][0];
            $OB= $registroCreditosGeneral[0][1];
            $OC= $registroCreditosGeneral[0][2];
            $EI= $registroCreditosGeneral[0][3];
            $EE= $registroCreditosGeneral[0][4];

            $this->cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"espaciosAprobados",$codEstudiante);//echo $this->cadena_sql;exit;
            $registroEspaciosAprobados=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");


            for($i=0;$i<count($registroEspaciosAprobados);$i++)
            {
            $idEspacio= $registroEspaciosAprobados[$i][0];
            $variables=array($idEspacio, $planEstudiante);
//            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"valorCreditosPlan",$variables);echo "<br>".$cadena_sql;//exit;
//            $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

           switch((isset($registroEspaciosAprobados[$i][3])?$registroEspaciosAprobados[$i][3]:''))
            {
                case 1:
                        $OBEst=$OBEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 2:
                        $OCEst=$OCEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 3:
                        $EIEst=$EIEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 4:
                        $EEEst=$EEEst+$registroEspaciosAprobados[$i][2];
                    break;

                case '':
                        $totalCreditosEst=$totalCreditosEst+0;
                    break;

                 }
            }
            //exit;
           $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;


            $porcentajeCursado=$totalCreditosEst*100/$totalCreditos;
            if($porcentajeCursado==0){$totalCreditosEst=0;}
            $porcentajeOBCursado=$OBEst*100/$OB;
            if($porcentajeOBCursado==0){$OBEst=0;}
            $porcentajeOCCursado=$OCEst*100/$OC;
            if($porcentajeOCCursado==0){$OCEst=0;}
            $porcentajeEICursado=$EIEst*100/$EI;
            if($porcentajeEICursado==0){$EIEst=0;}
            $porcentajeEECursado=$EEEst*100/$EE;
            if($porcentajeEECursado==0){$EEEst=0;}
           // $porcentajeCursado=100;

            if($totalCreditos>0)
            {
            $vista[0][0]="1";
            $vista[0][1]="Créditos Acádemicos";
            $vista[1][0]="Clasificación";
            $vista[1][1]="Total";
            $vista[1][2]="Aprobados";
            $vista[1][3]="Por Aprobar";
            $vista[1][4]="% Cursado";

            $vista[2][0]="OB";
            $vista[2][1]=$OB;
            $vista[2][2]=$OBEst;
            $vista[2][3]=$FaltanOB=$OB-$OBEst;

           if($porcentajeOBCursado==0)
             {
             $vista[2][4]="0";
             $OBEst=0;
             }else
                 {
                    $vista[2][4]=round($porcentajeOBCursado,1);
                 }
             

             $vista[3][0]="OC";
             $vista[3][1]=$OC;
             $vista[3][2]=$OCEst;
             $vista[3][3]=$FaltanOC=$OC-$OCEst;

            
           if($porcentajeOCCursado==0)
             {
             $vista[3][4]="0";
             $OCEst=0;
             }else
                 {
                    $vista[3][4]=round($porcentajeOCCursado,1);
                 }

             $vista[4][0]="EI";
             $vista[4][1]=$EI;
             $vista[4][2]=$EIEst;
             $vista[4][3]=$FaltanEI=$EI-$EIEst;


           if($porcentajeEICursado==0)
             {
             $vista[4][4]="0";
             $EIEst=0;
             }else
                 {
                    $vista[4][4]=round($porcentajeEICursado,1);
                 }
             

             $vista[5][0]="EE";
             $vista[5][1]=$EE;
             $vista[5][2]=$EEEst;
             $vista[5][3]=$FaltanEE=$EE-$EEEst;


           if($porcentajeEECursado==0)
             {
             $vista[5][4]="0";
             $EEEst=0;
             }else
                 {
                    $vista[5][4]=round($porcentajeEECursado,1);
                 }

             $vista[6][0]="Total";
             $vista[6][1]=$totalCreditos;
             $vista[6][2]=$totalCreditosEst;
             $vista[6][3]=$Faltan=$totalCreditos-$totalCreditosEst;


           if($porcentajeCursado==0)
             {
             $vista[6][4]="0";
             $totalCreditosEst=0;
             }else
                 {
                    $vista[6][4]=round($porcentajeCursado,1);
                 }
             

            }
            else
            {
            $vista[0][0]="0";
            $vista[0][1]="El plan de estudio no ha definido los parámetros";
            $vista[1][0]="Clasificación";
            $vista[1][1]="Total";
            $vista[1][2]="Aprobados";
            $vista[1][3]="Por Aprobar";
            $vista[1][4]="% Cursado";

            $vista[2][0]="OB";
            $vista[2][1]="-";
            $vista[2][2]="-";
            $vista[2][3]="-";
            $vista[2][4]="0";

            $vista[3][0]="OC";
            $vista[3][1]="-";
            $vista[3][2]="-";
            $vista[3][3]="-";
            $vista[3][4]="0";

            $vista[4][0]="EI";
            $vista[4][1]="-";
            $vista[4][2]="-";
            $vista[4][3]="-";
            $vista[4][4]="0";

            $vista[5][0]="EE";
            $vista[5][1]="-";
            $vista[5][2]="-";
            $vista[5][3]="-";
            $vista[5][4]="0";

            $vista[6][0]="Total";
            $vista[6][1]="-";
            $vista[6][2]="-";
            $vista[6][3]="-";
            $vista[6][4]="0";
            }
            return $vista;

    }

    
        function generarReporte($configuracion,$codEstudiante)
        {
            if($codEstudiante != "")
                {
                    $dir =$configuracion['raiz_documento'].$configuracion['bloques']."/".$this->formulario."/";
                    $informe = "report1";
                    $jrDirLib = "/usr/lib/jvm/jre-1.6.0/lib/ext";

                    $handle = @opendir($jrDirLib);

                    while(($lib = readdir($handle)) !== false)
                        {
                            $classpath .= 'file:'.$jrDirLib.'/'.$lib .';';
                        }
                    java_require($classpath);
                    
                     $jcm = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
                     $report = $jcm->compileReport($dir .$informe.".jrxml");

                     $jfm = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
                     $print = $jfm->fillReport($report,new Java("java.util.HashMap"),new Java("net.sf.jasperreports.engine.JREmptyDataSource"));

                     $jem = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
                     $jem->exportReportToPdfFile($print, $dir .$informe.".pdf");
                }
        }
}
?>
