<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminVinculacion extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    private $configuracion;
    private $path; 
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
        require_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf_sab_notas/pdf/mpdf.php");
        $this->html=new html();
    
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"docente");

        //Datos de sesion
        $this->formulario="registro_adicionarTablaHomologacion";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->pagina="adminDocumentosVinculacion";
        $this->opcion="mostrar";
        //Conexion sga
        $this->configuracion = $configuracion;
        //definimos el directorio donde se guadan los archivos
        $this->path = $this->configuracion["raiz_documento"]."/documentos/docentes/";
        $this->mpdf=new mPDF('','LETTER',9,'ARIAL',5,5,55,25,7,12);
   
    }

    
     /**
     * Funcion que da la bienvenida la usuario
     * @param <array> $this->verificar
     * @param <array> $this->formulario
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto)
      * Utiliza los metodos camposBusquedaEspaciosPadre, camposBusquedaEspaciosHijo, enlaceRegistrar
     */
function mostrarInicio(){  
        if(date('m')<='07'){$per='1';}
        else {$per='3';}
        $docente=array('identificacion'=>$this->usuario,
                       'anio'=>date('Y'),
                       'periodo'=>$per);
        $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
        $datosusuario=$this->ejecutarSQL($this->configuracion , $this->accesoOracle, $cadena_sql, "busqueda");
        //buscavinculaciones activas
        $cadena_sql = $this->sql->cadena_sql("vinculaciones", $docente);
        $datosVinculacion=$this->ejecutarSQL($this->configuracion , $this->accesoOracle, $cadena_sql, "busqueda");
       ?>
        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>

		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > DOCENTE </th>
                        </thead>
                        <tbody>
			<tr>
			   <td align="center"  class='cuadro_plano '>
				<table class="contenidotabla"  width="100%">
                                        <tr>
                                                <td width='20%' height="30">
                                                        Docente:
                                                </td>
                                                <td width='30%' align="left">
                                                        <? echo $datosusuario[0]['DOC_APEL'].' '.$datosusuario[0]['DOC_NOM'];?>
                                                </td>
                                               <td width='20%'>
                                                        Identificaci&oacute;n:
                                                </td>
                                                <td width='30%' align="left">
                                                        <? echo $datosusuario[0]['DOC_TIP_IDEN'].' '.$datosusuario[0]['DOC_NRO_IDEN'];?>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width='20%' valign="top">
                                                        Vinculaciones Activas:
                                                </td>
                                                <td align="left" colspan="3">
                                                   <? if(is_array($datosVinculacion))
                                                            {  
                                                            echo "<ul>";
                                                                foreach ($datosVinculacion as $key => $value) 
                                                                    { echo "<li> ".$datosVinculacion[$key]['VIN_NOMBRE']." - (".$datosVinculacion[$key]['VIN_CRA_COD'].") ".$datosVinculacion[$key]['VIN_CRA_NOM']."</li>";
                                                                    }
                                                                echo "</ul>";
                                                            }
                                                        else {  include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                                $cadena=".::El Docente no registra vinculaciones para el período actual::."; 
                                                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                                alerta::sin_registro($this->configuracion,$cadena);}    
                                                        ?>
                                                </td>
                                        </tr>
                                </table>
			    </td>
			</tr>
                        <tr>
			   <td align="center"  class='cuadro_plano'>
                               <p>Bienvenido al m&oacute;dulo de Documentos de Vinculaci&oacute;n, aqui podr&aacute;:</p>
                                    <ul>
                                    <li>Consultar e Imprimir los desprendibles de pago</li>
                                    <li>Consultar e Imprimir las Resoluciones de Vinculaci&oacute;n laboral con la Universidad Distrital Francisco Jos&eacute; de Caldas</li>
                                    <li>Consultar e Imprimir la(s) Norma(s) Institucional(es) que rige(n) la vinculaci&oacute;n y permanencia con la Universidad Distrital Francisco Jos&eacute; de Caldas</li>
                                    <li>Consultar e Imprimir los Actos Administrativos relacionados con la Universidad Distrital Francisco Jos&eacute; de Caldas</li>
                                    <li>Consultar las convocatorias para concurso p&uacute;blico Docente con la Universidad Distrital Francisco Jos&eacute; de Caldas</li>
                                    </ul> 
 			    </td>
			</tr>
                        
                        </tbody>
			
			</tr>
		</table>
                <div id="div_mensaje1" align="center" class="ab_name">
               </div>
        <?
    }    
    
function mostrarDatos(){  

            if(date('m')<='07'){$per='1';}
            else {$per='3';}
            $docente=array('identificacion'=>$this->usuario,
                           'anio'=>date('Y'),
                           'periodo'=>$per);
            $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
            $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
        if(isset($datosusuario))
          {     //buscavinculaciones activas
                $cadena_sql = $this->sql->cadena_sql("vinculaciones", $docente);
                $datosVinculacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
               ?>
                <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>
                        <table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                                <thead class='sigma'>
                                <th class='espacios_proyecto' > DOCENTE </th>
                                </thead>
                                <tbody>
                                <tr>
                                   <td align="center"  class='cuadro_plano '>
                                        <table class="contenidotabla"  width="100%">
                                                <tr>
                                                        <td width='20%' height="30">
                                                                Docente:
                                                        </td>
                                                        <td width='30%' align="left">
                                                                <? echo $datosusuario[0]['DOC_APEL'].' '.$datosusuario[0]['DOC_NOM'];?>
                                                        </td>
                                                       <td width='20%'>
                                                                Identificaci&oacute;n:
                                                        </td>
                                                        <td width='30%' align="left">
                                                                <? echo $datosusuario[0]['DOC_TIP_IDEN'].' '.$datosusuario[0]['DOC_NRO_IDEN'];?>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width='20%' valign="top">
                                                                Vinculaciones Activas:
                                                        </td>
                                                        <td align="left" colspan="3">
                                                           <? if(is_array($datosVinculacion))
                                                                    {  
                                                                    echo "<ul>";
                                                                        foreach ($datosVinculacion as $key => $value) 
                                                                            { echo "<li> ".$datosVinculacion[$key]['VIN_NOMBRE']." - (".$datosVinculacion[$key]['VIN_CRA_COD'].") ".$datosVinculacion[$key]['VIN_CRA_NOM']."</li>";
                                                                            }
                                                                        echo "</ul>";
                                                                    }
                                                                else {  include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                                        $cadena=".::El Docente no registra vinculaciones para el período actual::."; 
                                                                        $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                                        alerta::sin_registro($this->configuracion,$cadena);}    
                                                                ?>
                                                        </td>
                                                </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                                </tr>
                        </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                   </div>
                <?
          }
     else { include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
            $cadena=".::No existe un Docente registrado con la identificación ".$_REQUEST['docente']." ::."; 
            $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
            alerta::sin_registro($this->configuracion,$cadena);
          }         
    }        
    
function historialVinculacion()
    {
    
        $docente=array('identificacion'=>$this->usuario);
        $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
        $datosusuario=$this->ejecutarSQL($this->configuracion , $this->accesoOracle, $cadena_sql, "busqueda");
        //buscavinculaciones activas
        $cadena_sql = $this->sql->cadena_sql("vinculaciones", $docente);
        $datosVinculacion=$this->ejecutarSQL($this->configuracion , $this->accesoOracle, $cadena_sql, "busqueda");
    
    ?>
        <table id="tabla"  class="contenidotabla" width="100%" border ="1">
                    <thead class='sigma'>
                        <th class='espacios_proyecto' colspan ="5"><?echo "HISTORIAL DE VINCULACIONES - ". $datosusuario[0]['DOC_APEL'].' '.$datosusuario[0]['DOC_NOM']; ?></th>
                    </thead>
                    <thead class='sigma'>
                    <th class='niveles centrar' > Periodo </th>    
                    <th class='niveles centrar' > Proyecto Curricular</th>    
                    <th class='niveles centrar' > Tipo Vinculacioón</th>
                    <th class='niveles centrar' > Estado</th>
                    <th class='niveles centrar' > Resoluci&oacute;n</th>
                    </thead>
                    <?
                    if(is_array($datosVinculacion))
                        {   foreach ($datosVinculacion as $key => $value) 
                                { ?> <tr >
                                        <td width="10%" class='cuadro_plano centrar'>
                                         <? echo $datosVinculacion[$key]['VIN_ANIO']." - ".$datosVinculacion[$key]['VIN_PER'];?>
                                        </td>
                                        <td width="40%" class='cuadro_plano'>
                                         <? echo $datosVinculacion[$key]['VIN_CRA_COD']." - ".$datosVinculacion[$key]['VIN_CRA_NOM'];?>
                                        </td>
                                        <td width="30%" class='cuadro_plano'>
                                         <? echo $datosVinculacion[$key]['VIN_NOMBRE'];?>
                                        </td>
                                        <td width="5%" class='cuadro_plano centrar'>
                                         <? echo $datosVinculacion[$key]['VIN_ESTADO'];?>
                                        </td>
                                        <td width="15%" class='cuadro_plano centrar'>
                                            <? if(isset($datosVinculacion[$key]['VIN_RESOLUCION']))
                                                {?>
                                                <a href="<?echo $this->configuracion['host'].$this->configuracion['site'].'/documentos/docentes/resoluciones/'.$datosVinculacion[$key]['VIN_INT_RES'];?>"
                                                   onmouseover="Tip('<center>Ver Archivo</center>', SHADOW, true, TITLE, 'Archivo', PADDING, 9)"><? echo $datosVinculacion[$key]['VIN_RESOLUCION'];?></a>
                                              <? }  ?>
                                        </td>
                                    </tr>
                                  <?
                            
                                }
                         }
                    else { 
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".::No existen Vinculaciones registradas para el Docente::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                            }    
                    ?>
                </table>
    <?
    }

function historialActos()
    {
        $docente=array('identificacion'=>  $this->usuario);
        $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
        $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        //buscavinculaciones activas
        $cadena_sql = $this->sql->cadena_sql("actosAdministrativos", $docente);
        $datosActos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    
    ?>
        <script language="javascript">
        function confirmar (mensaje)
            {return confirm('¿Estas seguro de querer se borrar el acto Administrativo '+mensaje+' ?');}
        </script>    
        <table id="tabla"  class="contenidotabla" width="100%" border ="1">
                    <thead class='sigma'>
                        <th class='espacios_proyecto' colspan ="4"><?echo "HISTORIAL DE ACTOS ADMINISTRATIVOS - ". $datosusuario[0]['DOC_APEL'].' '.$datosusuario[0]['DOC_NOM']; ?></th>
                    </thead>
                    <thead class='sigma'>
                    <th class='niveles centrar' > Fecha Registro </th>    
                    <th class='niveles centrar' > Descripci&oacute;n</th>    
                    <th class='niveles centrar' > Acto</th>
                    </thead>
                    <?
                    
                    if(is_array($datosActos))
                        {   foreach ($datosActos as $key => $value) 
                                { ?> <tr >
                                        <td width="20%" class='cuadro_plano centrar'>
                                         <? echo $datosActos[$key]['ACTO_FECHA'];?>
                                        </td>
                                        <td width="50%" class='cuadro_plano'>
                                         <? echo $datosActos[$key]['ACTO_DESC'];?>
                                        </td>
                                        <td width="30%" class='cuadro_plano centrar'>
                                         <a href="<?echo $this->configuracion['host'].$this->configuracion['site'].'/documentos/docentes/actosAdministrativos/'.$datosActos[$key]['ACTO_AINT'];?>"><? echo $datosActos[$key]['ACTO_NOM_ARCHIVO'];?></a>   
                                        </td>
                                    </tr>
                                  <?
                            
                                }
                         }
                    else { 
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".::No existen Actos Administrativos registrados para el Docente::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                            }    
                    ?>
                </table>
    <?
    }
  
function consultarArchivos($tipo,$titulo)
    {   // se actualiza para m,ostrar convocatorias desde la pagina de la U
         if($tipo=='convocatoria' )
             { ?>
               <div style='width:100%; height: 650px'>
                <iframe src="http://www.udistrital.edu.co/#/contratacion.php" style="width: 100%; height: 100%"></iframe>
               </div> <?exit;
             } 
    
        $directorio=  $this->path.$tipo."/";
        $dir = opendir($directorio);
        //guardamos los archivos en un arreglo
         $img_total=0;
                    while ($elemento = readdir($dir))
                    {   if (strlen($elemento)>3)
                        {
                        $img_array[$img_total]=$elemento;
                        }
                    $img_total++;
                    }
      //              closedir($path); 
      /*TABLA QUE CONTIENE Las carpetas relacionadas con el tipo de archivo*/
                  ?>
             <p><a name="menu"/>
     <table class='contenidotabla'>  
         <thead class='sigma'>
          <th class='espacios_proyecto' ><?echo ".:: $titulo ::. "?></th>
         </thead> 
         <tr>
                <td>
                    <table width="100%" border="0">
                     <?     $t_reg=count($img_array);
                            if($t_reg<4){$tam="33%";}else{$tam="25%";}
                            $aux=1;
                            foreach($img_array as $key => $value)
                                {if($img_array[$key]!='index.php')
                                    {if($aux==1)
                                        {?><tr><? } ?>
                                             <td width="<?echo $tam;?>">
                                                 <li class="formal">&nbsp;<a href="#<?echo $img_array[$key];?>"><b><?echo $img_array[$key];?></b></a>.</li>
                                             </td>
                                        <? $t_reg--;
                                        if($aux==4 || $t_reg==0)
                                            {?></tr><?
                                             $aux=1;
                                            }
                                        else{$aux++;}    
                                    }
                                } 
                          ?>
                       </table>      
                   </td>
               </tr> <?
               /*busca los archivos guardados en cada carpeta*/
               foreach($img_array as $key => $value)
                   {
                      if($img_array[$key]!='index.php')
                         { ?> 
                          <tr>
                            <td>
                             <hr noshade style="height:1"/>
                             <p class="Estilo5"><a href="#<?echo $img_array[$key];?>" name="<?echo $img_array[$key];?>"><strong>.:: <?echo ucfirst($tipo)." ".$img_array[$key].' ::.';?></strong></a> <a href="#menu" title="Ir al inicio"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/arriba.png" border="0" width="16" height="16"/></a></p>                    
                                <ul>
                                 <table width="100%" border="0"> <?
                                 //definimos el directorio donde se guadan los archivos
                                 $pathDir[$key] = $directorio.$img_array[$key]."/";
                                 $urlDir[$key]=$this->configuracion['host'].$this->configuracion['site'].'/documentos/docentes/'.$tipo.'/'.$img_array[$key].'/';
                                 //abrimos el directorio
                                 $dirDir = opendir($pathDir[$key]);
                                 //guardamos los archivos en un arreglo
                                 $img_totalDir=0;
                                 while ($elementoDir = readdir($dirDir))
                                 {
                                     if (strlen($elementoDir)>3)
                                     {
                                     $img_arrayDir[$img_totalDir]=$elementoDir;
                                     }
                                 $img_totalDir++;
                                 }
                   //              closedir($path); 

                                 $t_regDir=count($img_arrayDir);
                                 if($t_regDir<3){$tamDir="50%";}else{$tamDir="33%";}
                                 $auxDir=1;
                                 //var_dump($img_arrayDir);

                                 foreach($img_arrayDir as $keyDir => $value)
                                     {
                                      if($img_arrayDir[$keyDir]!='index.php')
                                        {
                                         if($auxDir==1)
                                             {?><tr><? } ?>
                                                  <td width="<?echo $tamDir;?>">
                                                  <li class="formal">&nbsp;<a href="<?echo $urlDir[$key].$img_arrayDir[$keyDir];?>"><?echo $img_arrayDir[$keyDir];?></a>.</li>
                                                  </td>
                                             <?
                                             $t_regDir--;
                                             if($auxDir==3 || $t_regDir==0)
                                                 {?></tr><?
                                                  $auxDir=1;
                                                 }
                                             else{$auxDir++;} 
                                         }  
                                     } 

                                 unset($dirDir);
                                 unset($img_totalDir);
                                 unset($elementoDir);
                                 unset($img_arrayDir);
                                 unset($img_totalDir);
                                 unset($t_regDir);
                                 unset($tamDir);
                                 unset($keyDir);
                                 unset($auxDir);
                               ?></table>      
                            </td>
                          </tr> 
                    <? }
          }?>   
            </table>  
        <script type="text/javascript">
        <!--
        swfobject.registerObject("FlashID");
        //-->
        </script><?
    }    //fin funcion consultar archivos
    
function formBuscarpagos(){  

       ?>
        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>
		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > BUSCAR PAGOS </th>
                        </thead>
                        <tbody>
			<tr>
			   <td align="center"  class='cuadro_plano '>
                           <? $formulario='admin_DocumentosVinculacion'?>
                           <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $formulario?>'>    
                            <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                                <tr>
                                    <td valign='top'>A&Ntilde;O:
                                        <?  $varPer=array('identificacion'=>$this->usuario);
                                            $cadena_sql=$this->sql->cadena_sql("vigenciaVinculacion",$varPer);
                                            $resultadoPer=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                                            /*$configuracion["ajax_function"]="xajax_nombreCurso";
                                            $configuracion["ajax_control"]="periodoAnterior";*/
                                            foreach ($resultadoPer as $key => $value) 
                                               {    $registro[$key][0]=$resultadoPer[$key]['COD_ANIO'];
                                                    $registro[$key][1]=$resultadoPer[$key]['ANIO'];
                                               }
                                            $defecto=  isset( $_REQUEST['vigenciaPago'])?$_REQUEST['vigenciaPago']:0;   
                                            $mi_cuadro=$this->html->cuadro_lista($registro,'vigenciaPago',  $this->configuracion,$defecto,0,TRUE,100,'vigenciaPago');
                                            echo $mi_cuadro;
                                        ?>
                                    </td>
                                    <td valign='top'>MES:
                                        <?  
                                            $cadena_sql=$this->sql->cadena_sql("mes",'');
                                            $resultadoMes=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                            /*$configuracion["ajax_function"]="xajax_nombreCurso";
                                            $configuracion["ajax_control"]="periodoAnterior";*/
                                            foreach ($resultadoMes as $key => $value) 
                                               {    $registroMes[$key][0]=$resultadoMes[$key]['COD_MES'];
                                                    $registroMes[$key][1]=$resultadoMes[$key]['MES'];
                                               }
                                            $defectoMes=  isset( $_REQUEST['mesPago'])?$_REQUEST['mesPago']:0;   
                                            $mi_cuadroMes=$this->html->cuadro_lista($registroMes,'mesPago',$this->configuracion,$defectoMes,0,TRUE,100,'mesPago');
                                                                                   
                                            echo $mi_cuadroMes;
                                        ?>
                                     </td>
                                    <td valign='middle' width="50px" colspan="2" align='center'>
                                        <input type='hidden' name='action' value='<? echo $formulario;?>'/>     
                                        <input type='hidden' name='opcion' value='buscarPagos'/>
                                        <input name='buscar' value='Buscar' type='submit'/>    
                                    </td>
                                 </tr>	
                              </table>
                              </center>
                             </form>
			    </td>
			 </tr>
                        </tbody>
	  	  </table>
                <div id="div_mensaje1" align="center" class="ab_name">
                </div>
        <?
    }        
        
function mostrar_pagos()
    {  $this->mostrarEnlaceGenerarReporte();
       $htmlPagos=$this->historialPagosSIC(); 
       echo $htmlPagos;
    }
/**Funcion que genera el html del hhistorial de pagos del mes */            
function historialPagos()
    {   
        $docente=array('identificacion'=>$this->usuario);
        $cadena_doc = $this->sql->cadena_sql("datosUsuario", $docente);
        $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_doc, "busqueda");
        
        //$this->acceso_nominaVE=$this->conectarDB($this->configuracion,"docentesVE"); //parapostgres
        $this->acceso_nominaVE=$this->conectarDB($this->configuracion,"oracleSIC"); //para Oracle
        //var_dump($this->acceso_nominaVE);
        
        $pagos=array('identificacion'=>  $this->usuario,
                     'anio'=> $_REQUEST['vigenciaPago'],
                     'mes'=> $_REQUEST['mesPago']);
       echo  $cadena_sql = $this->sql->cadena_sql("pagos", $pagos);
        $datoPagos=$this->ejecutarSQL($this->configuracion,$this->acceso_nominaVE, $cadena_sql, "busqueda");
        // var_dump($datoPagos);
        $htmlNomina='<table id="tabla"  class="contenidotabla" width="100%" border ="1">'; 
        $htmlNomina.='<thead class="sigma">'; 
        $htmlNomina.='<th class="espacios_proyecto" colspan ="7">PAGOS DOCENTES VINCULACIÓN ESPECIAL - VIGENCIA '.$_REQUEST['vigenciaPago'].'</th>'; 
        $htmlNomina.='</thead>'; 
        $htmlNomina.='<thead class="sigma">'; 
        $htmlNomina.='<th class="niveles centrar" > Proyecto </th>    '; 
        $htmlNomina.='<th class="niveles centrar" > Pago mes </th>'; 
        $htmlNomina.='<th class="niveles centrar" > Docente </th>'; 
        $htmlNomina.='<th class="niveles centrar" > Nro. Disponibilidad </th>'; 
        $htmlNomina.='<th class="niveles centrar" > Nro. Registro </th>'; 
        $htmlNomina.='<th class="niveles centrar" > Vinculación </th>'; 
        $htmlNomina.='<th class="niveles centrar" > Bruto a Pagar</th>'; 
        
        $htmlNomina.='</thead>'; 
                  if(is_array($datoPagos))
                        {   foreach ($datoPagos as $key => $value) 
                                { 
                                $proyecto=array('proyecto'=>  $datoPagos[$key]['PROYECTO']);
                                $cadena_proy = $this->sql->cadena_sql("proyectos", $proyecto);
                                $datosProy=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_proy, "busqueda");
                            
                                $htmlNomina.='<tr>'; 
                                $htmlNomina.='<td width="30%" class="cuadro_plano centrar" rowspan="2">'.$datosProy[0]['CRA_COD'].' - '.$datosProy[0]['CRA_NOMBRE'].'</td>'; 
                                $htmlNomina.='<td width="8%" class="cuadro_plano">'. $datoPagos[$key]['NOM_MES'].' de '.$_REQUEST['vigenciaPago'].'</td>'; 
                                $htmlNomina.='<td width="23%" class="cuadro_plano">'.$datoPagos[$key]['IDENTIFICACION'].' - '. $datosusuario[0]['DOC_NOM'].' '. $datosusuario[0]['DOC_APEL'].'</td>'; 
                                $htmlNomina.='<td width="8%" class="cuadro_plano centrar">'.$datoPagos[$key]['CDP'].'</td>';     
                                $htmlNomina.='<td width="8%" class="cuadro_plano centrar">'.$datoPagos[$key]['CRP'].'</td>'; 
                                $htmlNomina.='<td width="10%" class="cuadro_plano">'.$datoPagos[$key]['TIPO_NOM'].'</td>'; 
                                $htmlNomina.='<td width="15%" class="cuadro_plano centrar"><b>$ '.number_format ( $datoPagos[$key]['VALOR_BRUTO'],2,'.',',' ).'</b></td>'; 
                                $htmlNomina.='</tr>'; 
                                $htmlNomina.='<tr>'; 
                                $htmlNomina.='<td class="cuadro_plano centrar" colspan ="6">'; 
                                        $htmlNomina.='<table id="tabla"  class="contenidotabla" width="100%" border ="1">'; 
                                        $htmlNomina.='<thead class="sigma">'; 
                                        $htmlNomina.='<th class="espacios_proyecto" colspan ="7">DETALLE</th>'; 
                                        $htmlNomina.='</thead>'; 
                                        $htmlNomina.='<thead class="sigma">'; 
                                        $htmlNomina.='<th class="niveles centrar" > Detalle descuento </th>    '; 
                                        $htmlNomina.='<th class="niveles centrar" > Valor descuento </th>    '; 
                                        $htmlNomina.='</thead>'; 
                                        $desPagos=array('vigencia'=> $datoPagos[$key]['VIGENCIA'],
                                                        'mes'=> $datoPagos[$key]['MES'],
                                                        'identificacion'=>  $datoPagos[$key]['IDENTIFICACION'],
                                                        'cdp'=> $datoPagos[$key]['CDP'],    
                                                        'crp'=> $datoPagos[$key]['CRP'],    
                                                        'facultad'=> $datoPagos[$key]['FACULTAD'],    
                                                        'proyecto'=> $datoPagos[$key]['PROYECTO']);
                                        
                                        $cadena_desc = $this->sql->cadena_sql("descuentos", $desPagos);
                                        $datoDesc=$this->ejecutarSQL($this->configuracion, $this->acceso_nominaVE, $cadena_desc, "busqueda");
                                         if(is_array($datoDesc))
                                                {$totalDesc=0;
                                                    foreach ($datoDesc as $desc => $value) 
                                                       {$totalDesc=($totalDesc+$datoDesc[$desc]['VALOR_DESCUENTO']); 
                                                           $htmlNomina.='<tr> '; 
                                                           $htmlNomina.='<td width="60%" class="cuadro_plano">'.$datoDesc[$desc]['DESCUENTO'].'</td> '; 
                                                           $htmlNomina.='<td width="40%" class="cuadro_plano centrar">$ '.number_format($datoDesc[$desc]['VALOR_DESCUENTO'],2,'.',',' ).'</td> '; 
                                                           $htmlNomina.='</tr>'; 
                                                      }
                                                    $htmlNomina.='<tr> '; 
                                                    $htmlNomina.='<td width="60%" class="cuadro_plano">Total Descuento</td> '; 
                                                    $htmlNomina.='<td width="40%" class="cuadro_plano centrar"><b>$ '.number_format ($totalDesc,2,'.',',' ).'</b></td>'; 
                                                    $htmlNomina.='</tr> '; 
                                                    $htmlNomina.='<tr> '; 
                                                    $htmlNomina.='<td width="60%" class="niveles centrar" ><b>NETO A PAGAR</b></td> '; 
                                                    $htmlNomina.='<td width="40%" class="niveles centrar"><b>$ '.number_format (($datoPagos[$key]['VALOR_BRUTO']-$totalDesc),2,'.',',' ).'</b></td> '; 
                                                    $htmlNomina.='</tr> '; 
                                                    
                                                  }
                                           else { 
                                                    $htmlNomina.='<tr>'; 
                                                    $htmlNomina.='<td width="75%" colspan="2" class="cuadro_plano">'; 
                                                     include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                      $cadena=".:: No existen DescuentosRegistrados para el pago ::."; 
                                                      $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                      alerta::sin_registro($this->configuracion,$cadena);
                                                    $htmlNomina.='</td>'; 
                                                    $htmlNomina.='</tr>'; 
                                                }
                                                    $htmlNomina.='</table>'; 
                                                    $htmlNomina.='</td>'; 
                                                    $htmlNomina.='</tr>'; 
                                }
                         }
                    else { 
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".:: No existen Pagos Registrados para el Año y Mes seleccionados ::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                            }    
          $htmlNomina.='</table>'; 
          return($htmlNomina);
    }    
    
function historialPagosSIC()
    {   unset($datoPagos);
        unset($pagos);
        $this->accesoOracleFin=$this->conectarDB($this->configuracion,"oracleSIC");
        $pagos=array('identificacion'=>  $this->usuario,
                     'anio'=> $_REQUEST['vigenciaPago'],
                     'mes'=> $_REQUEST['mesPago']);
        $cadena_sql = $this->sql->cadena_sql("pagos_SIC", $pagos);
        $datoPagos=$this->ejecutarSQL($this->configuracion, $this->accesoOracleFin, $cadena_sql, "busqueda");
        //var_dump($datoPagos);
        
    ?>
        <table id="tabla"  class="contenidotabla" width="100%" border ="1">
                    <thead class='sigma'>
                        <th class='espacios_proyecto' colspan ="5"><?echo " PAGOS ".$_REQUEST['vigenciaPago'].'-'.$_REQUEST['mesPago']; ?></th>
                    </thead>
                    <thead class='sigma'>
                    <th class='niveles centrar' > Rubro </th>    
                    <th class='niveles centrar' > Beneficiario </th>    
                    <th class='niveles centrar' > Orden de Pago </th>    
                    <th class='niveles centrar' > Fecha </th>    
                    <th class='niveles centrar' > Concepto </th>    
                    </thead>
                    <?
                    
                    if(is_array($datoPagos))
                        {   foreach ($datoPagos as $key => $value) 
                                { 
                                    $desPagos=array('orden'=>  $datoPagos[$key]['ORDEN_PAGO'],
                                                    'vigencia'=> $datoPagos[$key]['VIGENCIA'],
                                                    'unidad'=> $datoPagos[$key]['UNIDAD_EJECUTORA']);
                                    $cadena_desc = $this->sql->cadena_sql("descuentosSIC", $desPagos);
                                    $datoDesc=$this->ejecutarSQL($this->configuracion, $this->accesoOracleFin, $cadena_desc, "busqueda");
                                    $cadena_dev = $this->sql->cadena_sql("devengosSIC", $desPagos);
                                    $datoDev=$this->ejecutarSQL($this->configuracion, $this->accesoOracleFin, $cadena_dev, "busqueda");
                                    //var_dump($datoDev);
                            
                                ?> <tr >
                                        <td width="20%" class='cuadro_plano centrar'rowspan='2' >
                                         <? echo $datoPagos[$key]['RUBRO'];?>
                                        </td>
                                        <td width="20%" class='cuadro_plano'  style="text-align:center">
                                         <? echo $datoPagos[$key]['BENEFICIARIO'];?>
                                        </td>
                                        <td width="6%" class='cuadro_plano' style="text-align:center">
                                         <? echo $datoPagos[$key]['ORDEN_PAGO'];?>
                                        </td>
                                        <td width="10%" class='cuadro_plano'>
                                         <? echo $datoPagos[$key]['FECHA_ORDEN'];?>
                                        </td>
                                        <td width="35%" class='cuadro_plano'>
                                         <? echo $datoPagos[$key]['DETALLE_ORDEN'];?>
                                        </td>
                                    </tr>
                                    <tr >
                                        
                                        <td class='cuadro_plano centrar' colspan ='5'>
                                             <table id="tabla"  class="contenidotabla" width="100%" border ="1">
                                                    <thead class='sigma'>
                                                        <th class='espacios_proyecto' colspan ="2"><?echo "DETALLE DE PAGO"; ?></th>
                                                    </thead>
                                                    <thead class='sigma'>
                                                    <th class='niveles centrar' > DEVENGOS </th>    
                                                    <th class='niveles centrar' > Valor </th>    
                                                    </thead>
                                                    <? if(is_array($datoDev))
                                                            {$totalDev=0;
                                                             foreach ($datoDev as $dev => $value) 
                                                                {$totalDev=($totalDev+$datoDev[$dev]['VALOR']); 
                                                                 ?>
                                                                <tr>
                                                                    <td width="40%" class='cuadro_plano'>
                                                                     <? echo $datoDev[$dev]['NOMBRE'];?>
                                                                    </td>
                                                                    <td width="25%" class='cuadro_plano centrar' style="text-align:right">
                                                                     <? echo number_format($datoDev[$dev]['VALOR'],2,'.',',' ); ?>
                                                                    </td>
                                                                </tr>
                                                              <? }
                                                            ?>
                                                            <tr>
                                                                <td width="75%" class='niveles centrar' style="text-align:right">
                                                                    <b>Total Devengos</b>
                                                                </td>
                                                                <td width="25%" class='niveles centrar' style="text-align:right">
                                                                 <b><? echo number_format (($totalDev),2,'.',',' ); ?></b>
                                                                </td>
                                                            </tr>
                                                        <?  }
                                                       else {  ?>
                                                            <tr>
                                                                <td width="75%" colspan='2' class='cuadro_plano'>
                                                                 <?
                                                                 include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                                  $cadena=".:: No existen Devengos Registrados para el pago ::."; 
                                                                  $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                                  alerta::sin_registro($this->configuracion,$cadena);
                                                                  ?>
                                                                </td>
                                                            </tr>
                                                        <?  
                                                            }
                                                    ?>                                                    
                                                    <thead class='sigma'>
                                                    <th class='niveles centrar' > DEDUCCIONES </th>    
                                                    <th class='niveles centrar' > Valor </th>    
                                                    </thead>
                                                    <? if(is_array($datoDesc))
                                                            {$totalDesc=0;
                                                             foreach ($datoDesc as $desc => $value) 
                                                                {$totalDesc=($totalDesc+$datoDesc[$desc]['VALOR_DESC']); 
                                                                 ?>
                                                                <tr>
                                                                    <td width="40%" class='cuadro_plano'>
                                                                     <? echo $datoDesc[$desc]['NOMBRE_DESC'];?>
                                                                    </td>
                                                                    <td width="25%" class='cuadro_plano centrar' style="text-align:right">
                                                                     <? echo number_format($datoDesc[$desc]['VALOR_DESC'],2,'.',',' ); ?>
                                                                    </td>
                                                                </tr>
                                                              <? }
                                                            ?>
                                                            <tr>
                                                                <td width="75%" class='niveles centrar' style="text-align:right">
                                                                    <b>Total Deducciones</b>
                                                                </td>
                                                                <td width="25%" class='niveles centrar' style="text-align:right">
                                                                 <b><? echo number_format ($totalDesc,2,'.',',' ); ?></b>
                                                                </td>
                                                            </tr>
                                                        <?  }
                                                       else {  ?>
                                                            <tr>
                                                                <td width="75%" colspan='4' class='cuadro_plano'>
                                                                 <?
                                                                 include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                                  $cadena=".:: No existen Descuentos Registrados para el pago ::."; 
                                                                  $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                                  alerta::sin_registro($this->configuracion,$cadena);
                                                                  ?>
                                                                </td>
                                                            </tr>
                                                        <?  
                                                            }
                                                    ?>
                                                    <thead class='sigma'>
                                                        <th class='espacios_proyecto' style="text-align:right" > <b>TOTAL A PAGAR</b></th>    
                                                        <th class='espacios_proyecto' style="text-align:right" > <b><? echo number_format (($totalDev-$totalDesc),2,'.',',' ); ?></b> </th>  
                                                    </thead>
                                             </table>           
                                        </td>
                                    </tr>
                                    
                                  <?
                            
                                }
                         }
                    else { 
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".:: No existen Pagos Registrados para el Año y Mes seleccionados ::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                            }    
                    ?>
                </table>
    <?
    }    

function generarDespendible()
    {   $encabezado = $this->armarEncabezado();
        $documento = $this->historialPagosPdfSIC();
        $pie_pagina = $this->armarPiePagina('Generado por:Sistema de Gestión Académico - Cóndor','Fuente datos: Sistema de Gestión Financiera','Diseñado por: JLH');
        $this->generarPDF($documento,$encabezado,$pie_pagina,$this->identificacion);  
    } 
    
/**Funcion que genera el html del hhistorial de pagos del mes */            
function historialPagosPdf()
    {   
        $docente=array('identificacion'=>$this->usuario);
        $cadena_doc = $this->sql->cadena_sql("datosUsuario", $docente);
        $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_doc, "busqueda");
        
        //$this->acceso_nominaVE=$this->conectarDB($this->configuracion,"docentesVE");//POSTGRES
        $this->acceso_nominaVE=$this->conectarDB($this->configuracion,"oracleSIC");//ORACE SIC
        $pagos=array('identificacion'=>  $this->usuario,
                     'anio'=> $_REQUEST['vigenciaPago'],
                     'mes'=> $_REQUEST['mesPago']);
        $cadena_sql = $this->sql->cadena_sql("pagos", $pagos);
        $datoPagos=$this->ejecutarSQL($this->configuracion,$this->acceso_nominaVE, $cadena_sql, "busqueda");
        
        //var_dump($datoPagos);

        $htmlNomina='<table id="tabla"  class="contenidotabla" width="800" border="1" cellspacing="0" cellpadding="0" >'; 
        $htmlNomina.='<tr>'; 
        $htmlNomina.='<td align="center" colspan ="7">PAGOS DOCENTES VINCULACIÓN ESPECIAL - VIGENCIA '.$_REQUEST['vigenciaPago'].'</td>'; 
        $htmlNomina.='</tr>'; 
        $htmlNomina.='<tr>'; 
        $htmlNomina.='<td align="center" > Proyecto </td>    '; 
        $htmlNomina.='<td align="center" > Pago mes </td>'; 
        $htmlNomina.='<td align="center" > Docente </td>'; 
        $htmlNomina.='<td align="center"> Nro. Disponibilidad </td>'; 
        $htmlNomina.='<td align="center" > Nro. Registro </td>'; 
        $htmlNomina.='<td align="center" > Vinculación </td>'; 
        $htmlNomina.='<td align="center" > Bruto a Pagar</td>'; 
        $htmlNomina.='</tr>'; 

                  if(is_array($datoPagos))
                        {   foreach ($datoPagos as $key => $value) 
                                { 
                                $proyecto=array('proyecto'=>  $datoPagos[$key]['PROYECTO']);
                                $cadena_proy = $this->sql->cadena_sql("proyectos", $proyecto);
                                $datosProy=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_proy, "busqueda");
                            
                                $htmlNomina.='<tr>'; 
                                $htmlNomina.='<td width="28%" align="center"  rowspan="2">'.$datosProy[0]['CRA_COD'].' - '.$datosProy[0]['CRA_NOMBRE'].'</td>'; 
                                $htmlNomina.='<td width="8%"  align="center" >'. $datoPagos[$key]['NOM_MES'].' de '.$_REQUEST['vigenciaPago'].'</td>'; 
                                $htmlNomina.='<td width="23%"  align="center" >'.$datoPagos[$key]['IDENTIFICACION'].' - '. $datosusuario[0]['DOC_NOM'].' '. $datosusuario[0]['DOC_APEL'].'</td>'; 
                                $htmlNomina.='<td width="8%"  align="center" >'.$datoPagos[$key]['CDP'].'</td>';     
                                $htmlNomina.='<td width="8%"  align="center" >'.$datoPagos[$key]['CRP'].'</td>'; 
                                $htmlNomina.='<td width="10%"  align="center" >'.$datoPagos[$key]['TIPO_NOM'].'</td>'; 
                                $htmlNomina.='<td width="15%"  align="center" ><b>$ '.number_format ( $datoPagos[$key]['VALOR_BRUTO'],2,'.',',' ).'</b></td>'; 
                                $htmlNomina.='</tr>'; 
                                $htmlNomina.='<tr>'; 
                                $htmlNomina.='<td colspan ="6">'; 
                                        $htmlNomina.='<table id="tabla" width="580" border="1" cellspacing="0" cellpadding="0" >'; 
                                        $htmlNomina.='<tr>';  
                                        $htmlNomina.='<td align="center" colspan ="2">DETALLE</th>'; 
                                        $htmlNomina.='</tr>'; 
                                        $htmlNomina.='<tr>'; 
                                        $htmlNomina.='<td align="center" > Detalle descuento </td>'; 
                                        $htmlNomina.='<td align="center" > Valor descuento </td>'; 
                                        $desPagos=array('vigencia'=> $datoPagos[$key]['VIGENCIA'],
                                                        'mes'=> $datoPagos[$key]['MES'],
                                                        'identificacion'=>  $datoPagos[$key]['IDENTIFICACION'],
                                                        'cdp'=> $datoPagos[$key]['CDP'],    
                                                        'crp'=> $datoPagos[$key]['CRP'],    
                                                        'facultad'=> $datoPagos[$key]['FACULTAD'],    
                                                        'proyecto'=> $datoPagos[$key]['PROYECTO']);
                                        $cadena_desc = $this->sql->cadena_sql("descuentos", $desPagos);
                                        $datoDesc=$this->ejecutarSQL($this->configuracion, $this->acceso_nominaVE, $cadena_desc, "busqueda");
                                         if(is_array($datoDesc))
                                                {$totalDesc=0;
                                                    foreach ($datoDesc as $desc => $value) 
                                                       {$totalDesc=($totalDesc+$datoDesc[$desc]['VALOR_DESCUENTO']); 
                                                           $htmlNomina.='<tr> '; 
                                                           $htmlNomina.='<td width="60%" class="cuadro_plano">'.$datoDesc[$desc]['DESCUENTO'].'</td> '; 
                                                           $htmlNomina.='<td width="40%" class="cuadro_plano centrar">$ '.number_format($datoDesc[$desc]['VALOR_DESCUENTO'],2,'.',',' ).'</td> '; 
                                                           $htmlNomina.='</tr>'; 
                                                      }
                                                    $htmlNomina.='<tr> '; 
                                                    $htmlNomina.='<td width="60%" >Total Descuento</td> '; 
                                                    $htmlNomina.='<td width="40%" align="center"><b>$ '.number_format ($totalDesc,2,'.',',' ).'</b></td>'; 
                                                    $htmlNomina.='</tr> '; 
                                                    $htmlNomina.='<tr> '; 
                                                    $htmlNomina.='<td width="60%" align="center" ><b>NETO A PAGAR</b></td> '; 
                                                    $htmlNomina.='<td width="40%" align="center"><b>$ '.number_format (($datoPagos[$key]['VALOR_BRUTO']-$totalDesc),2,'.',',' ).'</b></td> '; 
                                                    $htmlNomina.='</tr> '; 
                                                    
                                                  }
                                           else { 
                                                    $htmlNomina.='<tr>'; 
                                                    $htmlNomina.='<td width="75%" colspan="2" class="cuadro_plano">'; 
                                                     include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                      $cadena=".:: No existen DescuentosRegistrados para el pago ::."; 
                                                      $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                      alerta::sin_registro($this->configuracion,$cadena);
                                                    $htmlNomina.='</td>'; 
                                                    $htmlNomina.='</tr>'; 
                                                }
                                                    $htmlNomina.='</table>'; 
                                                    $htmlNomina.='</td>'; 
                                                    $htmlNomina.='</tr>'; 
                                }
                         }
                    else { 
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".:: No existen Pagos Registrados para el Año y Mes seleccionados ::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                            }    
          $htmlNomina.='</table>'; 
          return($htmlNomina);
    }      
    

function historialPagosPdfSIC()
    {   
        $this->accesoOracleFin=$this->conectarDB($this->configuracion,"oracleSIC");
        $pagos=array('identificacion'=>  $this->usuario,
                     'anio'=> $_REQUEST['vigenciaPago'],
                     'mes'=> $_REQUEST['mesPago']);
        $cadena_sql = $this->sql->cadena_sql("pagos_SIC", $pagos);
        $datoPagos=$this->ejecutarSQL($this->configuracion, $this->accesoOracleFin, $cadena_sql, "busqueda");
        //var_dump($datoPagos);

        $htmlNomina='<table id="tabla"  class="contenidotabla" width="800" border="1" cellspacing="0" cellpadding="0" >'; 
        $htmlNomina.='<tr>'; 
        $htmlNomina.='<td colspan ="5" style="text-align:center" > PAGOS '.$_REQUEST['vigenciaPago'].'-'.$_REQUEST['mesPago'].'</td>'; 
        $htmlNomina.='</tr>'; 
        $htmlNomina.='<tr>'; 
        $htmlNomina.='<td align="center" > <b>Rubro </b></td>    '; 
        $htmlNomina.='<td align="center" > <b>Beneficiario </b></td>'; 
        $htmlNomina.='<td align="center" > <b>Orden de Pago </b></td>'; 
        $htmlNomina.='<td align="center" > <b>Fecha </b></td>'; 
        $htmlNomina.='<td align="center" > <b>Concepto </b></td>'; 
        $htmlNomina.='</tr>';         

        
                    if(is_array($datoPagos))
                        {   foreach ($datoPagos as $key => $value) 
                                { 
                                    $desPagos=array('orden'=>  $datoPagos[$key]['ORDEN_PAGO'],
                                                    'vigencia'=> $datoPagos[$key]['VIGENCIA'],
                                                    'unidad'=> $datoPagos[$key]['UNIDAD_EJECUTORA']);
                                    $cadena_desc = $this->sql->cadena_sql("descuentosSIC", $desPagos);
                                    $datoDesc=$this->ejecutarSQL($this->configuracion, $this->accesoOracleFin, $cadena_desc, "busqueda");
                                    $cadena_dev = $this->sql->cadena_sql("devengosSIC", $desPagos);
                                    $datoDev=$this->ejecutarSQL($this->configuracion, $this->accesoOracleFin, $cadena_dev, "busqueda");
                                    //var_dump($datoDev);
                        $htmlNomina.='<tr> ';
                        $htmlNomina.='<td width="20%" rowspan="2" >'.$datoPagos[$key]['RUBRO'].'</td> ';
                        $htmlNomina.='<td width="20%" style="text-align:center">'.$datoPagos[$key]['BENEFICIARIO'].'</td> ';
                        $htmlNomina.='<td width="10%" style="text-align:center">'.$datoPagos[$key]['ORDEN_PAGO'].'</td> ';
                        $htmlNomina.='<td width="10%" >'.$datoPagos[$key]['FECHA_ORDEN'].'</td> ';
                        $htmlNomina.='<td width="40%" >'.$datoPagos[$key]['DETALLE_ORDEN'].'</td> ';
                        $htmlNomina.='</tr> ';
                        $htmlNomina.='<tr> ';
                        $htmlNomina.='<td class="cuadro_plano centrar" colspan ="4"> ';
                            $htmlNomina.='<table id="tabla" width="620" border="1" cellspacing="0" cellpadding="0" > ';
                            $htmlNomina.='<tr > ';
                            $htmlNomina.='<td colspan ="2" style="text-align:center" > <b>DETALLE DE PAGO</b> </td> ';
                            $htmlNomina.='</tr> ';
                            $htmlNomina.='<tr > ';
                            $htmlNomina.='<td style="text-align:center" width="70%"  > <b>DEVENGOS</b> </td> ';
                            $htmlNomina.='<td style="text-align:center" width="30%"  > <b>Valor</b> </td> ';
                            $htmlNomina.='</tr> ';
                                                    if(is_array($datoDev))
                                                            {$totalDev=0;
                                                             foreach ($datoDev as $dev => $value) 
                                                                {$totalDev=($totalDev+$datoDev[$dev]['VALOR']); 
                                                                $htmlNomina.='<tr> ';
                                                                $htmlNomina.=' <td width="70%" >'.$datoDev[$dev]['NOMBRE'].'</td> ';
                                                                $htmlNomina.=' <td width="30%" class="cuadro_plano centrar" style="text-align:right">'.number_format($datoDev[$dev]['VALOR'],2,'.',',' ).'</td> ';
                                                                $htmlNomina.='</tr> ';
                                                                }
                                                                $htmlNomina.='<tr> ';
                                                                $htmlNomina.=' <td width="70%" style="text-align:right"> <b>Total Devengos</b></td> ';
                                                                $htmlNomina.=' <td width="30%" style="text-align:right">'.number_format (($totalDev),2,'.',',' ).'</b></td> ';
                                                                $htmlNomina.='</tr> ';
                                                            }
                                                       else {   
                                                                $htmlNomina.='<tr> ';
                                                                $htmlNomina.=' <td width="100%" colspan="2" class="cuadro_plano"> ';
                                                                 include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                                  $cadena=".:: No existen Devengos Registrados para el pago ::."; 
                                                                  $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                                  alerta::sin_registro($this->configuracion,$cadena);
                                                                $htmlNomina.=' </td> ';
                                                                $htmlNomina.='</tr> ';
                                                            }
                            $htmlNomina.='</tr> ';
                            $htmlNomina.='<tr > ';
                            $htmlNomina.='<td style="text-align:center" > <b>DEDUCCIONES</b></td> ';
                            $htmlNomina.='<td style="text-align:center" > <b>Valor</b> </td> ';
                            $htmlNomina.='</tr> ';  
                                                   if(is_array($datoDesc))
                                                            {$totalDesc=0;
                                                             foreach ($datoDesc as $desc => $value) 
                                                                {$totalDesc=($totalDesc+$datoDesc[$desc]['VALOR_DESC']); 
                                                                $htmlNomina.='<tr> ';
                                                                $htmlNomina.=' <td width="70%" >'.$datoDesc[$desc]['NOMBRE_DESC'].'</td> ';
                                                                $htmlNomina.=' <td width="30%" style="text-align:right">'.number_format($datoDesc[$desc]['VALOR_DESC'],2,'.',',' ).'</td> ';
                                                                $htmlNomina.='</tr> ';
                                                               }
                                                                $htmlNomina.='<tr> ';
                                                                $htmlNomina.='<td width="70%" style="text-align:right"><b>Total Deducciones</b></td> ';
                                                                $htmlNomina.='<td width="30%" style="text-align:right">'.number_format ($totalDesc,2,'.',',' ).'</b></td> ';
                                                                $htmlNomina.='</tr> ';
                                                           }
                                                       else {   
                                                                $htmlNomina.='<tr> ';
                                                                $htmlNomina.='<td width="100%" colspan="4" class="cuadro_plano"> ';
                                                                 include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                                                  $cadena=".:: No existen Descuentos Registrados para el pago ::."; 
                                                                  $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                                                  alerta::sin_registro($this->configuracion,$cadena);
                                                                $htmlNomina.=' </td> ';
                                                                $htmlNomina.='</tr> ';
                                                            }
                            $htmlNomina.='<tr> ';
                            $htmlNomina.='<td style="text-align:right" > <b>TOTAL A PAGAR</b></td>     ';
                            $htmlNomina.='<td style="text-align:right" > <b>'.number_format (($totalDev-$totalDesc),2,'.',',' ).'</b> </td>  ';
                            $htmlNomina.='</tr> ';
                            $htmlNomina.='</table> ';
                            $htmlNomina.='</td> ';
                            $htmlNomina.='</tr> ';
                                }
                         }
                    else { 
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".:: No existen Pagos Registrados para el Año y Mes seleccionados ::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                            }    
          $htmlNomina.='</table>'; 
          return($htmlNomina);
    
    }    

    
    
    /**
   * Función para generar el archivo pdf con los htmls creados de documento y pie de pagina
   * @param string $doc_html
   * @param string $pie_pagina
   * @param int $cod_estudiante 
   */
  function generarPDF($doc_html,$encabezado, $pie_pagina,$cod_usuario){
            $this->mpdf->AddPage();
            $ruta_estilo = $this->configuracion["raiz_documento"].$this->configuracion["bloques"]."/admin_reporteSabanaDeNotas/clase/estilos_pdf.css";
            //establecemos el archivo de estilos
            $stylesheet =file_get_contents($ruta_estilo);                    
            $this->mpdf->WriteHTML($stylesheet,1);
            //colocamos el html para el encabezado de pagina
            $this->mpdf->SetHTMLHeader($encabezado,'O',true);
            //colocamos el html para el pie de pagina
            $this->mpdf->setHTMLFooter($pie_pagina) ;
            //colocamos el html para el documento
            $this->mpdf->WriteHTML($doc_html); 
            //establecemos el nombre del archivo
            $nombre_archivo = "desprendible_pago_".$cod_usuario;
            $this->mpdf->Output($nombre_archivo.'.pdf','D');
            
        } 

    
     /**
     * Función para armar el html con los datos del estudiante
     * @param <array> $datos_estudiante
     * @param <array> $proyecto
     * @return string 
     */
    function armarEncabezado(){
        setlocale(LC_ALL,"es_ES");
        $html='<div class="datos">';
        $html.='<table border="0" cellpadding="0"  cellpadding="0">';
        $html.='<tr >';
        $html.='<td class="columnaNombre" width="25%"> ';
        $html.='<div class="datos" >';
        $html.='<img alt=" " src="'.$this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"].'/logoUniversidadEsc.png" ></p>';
        $html.='</div>';
        $html.='</td>';
        $html.='<td class="columna1">&nbsp; </td>';
        $html.='<td class="columnaIdentificacion"> ';
        $html.='    <div class="datos">';
        $html.='<p><font face="arial" size=6 >&nbsp;DETALLE DE PAGOS</b></font><b></p>';
        $html.='<p><font face="arial" size=3 >&nbsp;&nbsp;Impreso: '.date('Y-m-d H:m:s').'</b></font><b></p></div>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</div>';
        return $html;
    }

    /**
     * Función para armar el html del pie de pagina de la sabana de notas
     * @param string $marca
     * @param string $secretario
     * @return string 
     */
    function armarPiePagina($left,$center,$right){
            $html= '<div class="pie">';
            $html.= '<table border=0 width=100% >';
            $html.= '<tr>';
            $html.= '<td width=40%>'.$left.'</td>';
            $html.= '<td width=40% >'.$center.'</td>';
            $html.= '<td width=20%>'.$right.'</td>';
            $html.= '</tr>';
            $html.= '</table>';
            $html.= '</div>';
            return $html;
        }        
    
        
  function mostrarEnlaceGenerarReporte() {
            //var_dump($_REQUEST);exit;
            $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=adminDocumentosVinculacion";
            $variable.="&action=admin_DocumentosVinculacion";
            $variable.="&opcion=generarDespendible";
            $variable.="&vigenciaPago=".$_REQUEST['vigenciaPago'];
            $variable.="&mesPago=".$_REQUEST['mesPago'];
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            echo "<div align='center' id='right' style='float:right;width:50%;' ><p> <a href='".$pagina.$variable."' target='popup' onClick='window.open(this.href, this.target, 'width=600,height=700'); return false; class='enlaceHomologaciones'><font face='arial' size=3 color=green >:: Descargar detalle de pagos</font></a></p></div>";
            echo "<div align='center' id='left' style='float:left;width:50%;' ><p><font face='arial' size=1,5 color=green >Piense antes de imprimir. Ahorrar papel es cuidar nuestro ambiente</font>";
	    echo '<center><img alt=" " src="'.$this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"].'/ambiente.jpeg" width=60 height=80></center></p></div>';
            
        }    

}


?>

        