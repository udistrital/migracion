<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroVinculacion extends funcionGeneral {
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
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
        //Datos de sesion
        $this->formulario="registro_adicionarTablaHomologacion";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        switch ($this->nivel)
            { case 88:
               //Conexion Oracle docencia
               $this->accesoOracle=$this->conectarDB($configuracion,"docencia");
                break;
              case 83:
               //Conexion Oracle docencia
               $this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");
                break;
            }
        
        $this->pagina="admin_homologaciones";
        $this->opcion="mostrar";
        //Conexion sga
        $this->configuracion = $configuracion;
        //definimos el directorio donde se guadan los archivos
        $this->path = $this->configuracion["raiz_documento"]."/documentos/docentes/";  ?>
         <script language="javascript">
            function confirmar (mensaje)
                {return confirm('¿Estas seguro de querer se borrar el Archivo '+mensaje+' ?');}
         </script>  <?
    }

     /**
     * Funcion que da la bienvenida la usuario
     * @param <array> $this->verificar
     * @param <array> $this->formulario
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto)
      * Utiliza los metodos camposBusquedaEspaciosPadre, camposBusquedaEspaciosHijo, enlaceRegistrar
     */
function mostrarDatos($docente){  
        
      if($docente>0)
            {
            if(date('m')<='07'){$per='1';}
            else {$per='3';}
            $docente=array('identificacion'=>$docente,
                           'anio'=>date('Y'),
                           'periodo'=>$per);
            $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
            $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            }
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
    

function formBuscar(){  
       ?>
        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>
		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > BUSCAR DOCENTE </th>
                        </thead>
                        <tbody>
			<tr>
			   <td align="center"  class='cuadro_plano '>
                           <? $formulario='registro_DocumentosVinculacion'?>
                           <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $formulario?>'>    
                               <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                                <tr>
                                    <td valign='top'>IDENTIFICACI&Oacute;N:
                                        <input class="required" type="text" name="docente" id="espacio" value="<? if(isset($_REQUEST['docente'])){echo strtoupper($_REQUEST['docente']);} ?>"/>
                                    </td>
                                    <td valign='middle' width="50px" colspan="2" align='center' >
                                        <input type='hidden' name='action' value='<? echo $formulario;?>'>     
                                        <input type='hidden' name='opcion' value='buscar'>
                                        <input name='buscar' value='Buscar' type='submit'>    
                                    </td>
                                 </tr>	
                              </table>
                              </center></form>
			    </td>
			</tr>
                        </tbody>
			</tr>
		</table>
                <div id="div_mensaje1" align="center" class="ab_name">
                </div>
	    </div>
        <?
    }        
    
    
function historialVinculacion($docente)
    {   
        if($docente>0)
            {
            $docente=array('identificacion'=>$docente);
            $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
            $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            //buscavinculaciones activas
            $cadena_sql = $this->sql->cadena_sql("vinculaciones", $docente);
            $datosVinculacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); 
            }
         else {$datosusuario[0]['DOC_APEL']='';$datosusuario[0]['DOC_NOM']='';$datosVinculacion='';}   
            ?>
           
        <table id="tabla"  class="contenidotabla" width="100%" border ="1">
                    <thead class='sigma'>
                        <th class='espacios_proyecto' colspan ="6"><?echo "HISTORIAL DE VINCULACIONES - ". $datosusuario[0]['DOC_APEL'].' '.$datosusuario[0]['DOC_NOM']; ?></th>
                    </thead>
                    <thead class='sigma'>
                    <th class='niveles centrar' > Periodo </th>    
                    <th class='niveles centrar' > Proyecto Curricular</th>    
                    <th class='niveles centrar' > Tipo Vinculacioón</th>
                    <th class='niveles centrar' > Estado</th>
                    <th class='niveles centrar' > Resoluci&oacute;n</th>
                    <th class='niveles centrar' > Acci&oacute;n</th>
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
                                        <td width="25%" class='cuadro_plano'>
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
                                        <td width="5%" class='cuadro_plano centrar'>
                                         <? $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                            $ruta="pagina=registroDocumentosVinculacion";
                                            $ruta.="&docente=".$_REQUEST['docente'];
                                            $ruta.="&carpeta=".$this->path.'resoluciones/';
                                            $ruta.="&vinCod=".$datosVinculacion[$key]['VIN_COD'];
                                            $ruta.="&vinAnio=".$datosVinculacion[$key]['VIN_ANIO'];
                                            $ruta.="&vinPer=".$datosVinculacion[$key]['VIN_PER'];
                                            $ruta.="&vinCra=".$datosVinculacion[$key]['VIN_CRA_COD'];
                                       
                                            if(isset($datosVinculacion[$key]['VIN_RESOLUCION']))
                                                {
                                                    $ruta.="&opcion=borrarVinculacion";
                                                    $ruta.="&archivo=".$datosVinculacion[$key]['VIN_INT_RES'];
                                                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);   ?>
                                                    <a href="<?echo $indice.$ruta;?>" onmouseover="Tip('<center>Borrar Archivo</center>', SHADOW, true, TITLE, 'Resoluciones', PADDING, 9)" onclick="return confirmar('<? echo $datosVinculacion[$key]['VIN_RESOLUCION'];?>')" >
                                                    <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/x.png" width="15" height="15">
                                                    </a>
                                              <?}
                                            else
                                                { $ruta.="&opcion=nuevoVinculacion";
                                                  $ruta=$this->cripto->codificar_url($ruta,$this->configuracion); ?>
                                                <a href="<?echo $indice.$ruta;?>" onmouseover="Tip('<center>Registrar Resoluciòn </center>', SHADOW, true, TITLE, 'Resoluciones', PADDING, 9)" >
                                                 <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/asociar.png" width="25" height="25"> Subir Resoluciòn </a>
                                             <? } ?>
                                        </td>
                                    </tr>
                            <? }
                         }
                    else {      include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php"); 
                                $cadena=".:: No existen Vinculaciones registradas para el Docente ::."; 
                                $cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
                                alerta::sin_registro($this->configuracion,$cadena);
                          }  ?>
                </table>
 <? }
    
    
function consultarArchivos($tipo,$titulo)
    {   
        $directorio=  $this->path.$tipo."/";
        $dir = opendir($directorio);
        //guardamos los archivos en un arreglo
         $archivosVar=array('opcion' => $tipo,
                            'docente' => isset($_REQUEST['docente'])?$_REQUEST['docente']:'',
                            'nivel'=>  $this->nivel); 
         if($tipo=='convocatoria' /* && $this->nivel==83*/)
             {  
             /*
                $secretario=array('identificacion'=>$this->usuario);
                $cadena_sql = $this->sql->cadena_sql("datosSecretario", $secretario);
                $datosSecretario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");       
                switch($datosSecretario[0]['SEC_FACULTAD'])
                    {   case '23': $facultad='MedioAmbiente';
                            break;
                        case '24': $facultad='Ciencias';
                            break;
                        case '32': $facultad='Tecnólogica';
                            break;
                        case '33': $facultad='Ingeniería';
                            break;
                        case '101': $facultad='Artes';
                            break;
                    }
               $rutaDir=$directorio.$facultad."/";
               */
         ?>      
               <div style='width:100%; height: 650px'>
                <iframe src="http://www.udistrital.edu.co/#/contratacion.php" style="width: 100%; height: 100%"></iframe>
               </div> <?exit;
             } 
          else
             {  $rutaDir=$directorio;
             }     
             
         $this->formCargarArchivo($rutaDir,$tipo,$archivosVar);
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
                    
  if(isset($this->nivel))              
      {  ?>
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
                                {if(($img_array[$key]!='index.php' && !isset($facultad)) || ($img_array[$key]!='index.php' && isset($facultad) && $img_array[$key]==$facultad ))
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
                      if(($img_array[$key]!='index.php' && !isset($facultad)) || ($img_array[$key]!='index.php' && isset($facultad) && $img_array[$key]==$facultad ))
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
                                             {?><tr><? } 
                                                        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $ruta="pagina=registroDocumentosVinculacion";
                                                        $ruta.="&opcion=borrar".$tipo;
                                                        $ruta.="&carpeta=".$pathDir[$key];
                                                        $ruta.="&archivo=".$img_arrayDir[$keyDir];
                                                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                                                    ?>
                                                  <td width="<?echo $tamDir;?>">
                                                  <li class="formal">
                                                      &nbsp;<a href="<?echo $urlDir[$key].$img_arrayDir[$keyDir];?>"><?echo $img_arrayDir[$keyDir];?></a>.
                                                      &nbsp;<a href="<?echo $indice.$ruta;?>" onmouseover="Tip('<center>Borrar Archivo</center>', SHADOW, true, TITLE, 'Archivo', PADDING, 9)" onclick="return confirmar('<? echo $img_arrayDir[$keyDir];?>')" >
                                                            <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/x.png" width="15" height="15">
                                                            </a>
                                                  
                                                  </li>
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
      }
    }    //fin funcion consultar archivos
    
function borrarArchivo($ubicacion,$archivo)
    {       
        //echo "<br>".$ubicacion.$archivo;
        unlink($ubicacion.$archivo);
    }    

function formCargarArchivo($ruta,$titulo,$controlArchivo){  
       ?>
       		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > Cargar <?echo ucfirst($titulo);?> </th>
                        </thead>
                        <tbody>
			<tr>
			   <td align="center"  class='cuadro_plano '>
                           <? $formulario='registro_DocumentosVinculacion';
                              $verificar="control_vacio(".$formulario.",'archivo')";	
                              if($controlArchivo['opcion']=='actos')
                               {$verificar="control_vacio(".$formulario.",'descripcion')";}
                              if($controlArchivo['opcion']=='resolucion')
                               {$verificar="control_vacio(".$formulario.",'resolucion')";}
                              
                               
                           ?>
                           <form enctype='multipart/form-data' method="POST" action="index.php" name="<? echo $formulario?>">    
                               <center>
                             <table style="width:100%" class="formulario contenidotabla centrar">
                           <? if($controlArchivo['opcion']=='resolucion')
                               { ?>     
                                <tr>
                                    <td valign='top'> Resoluci&oacute;n:</td>
                                    <td valign='top' colspan='4'>
                                        <input tipe="text" name="resolucion" cols="10" value="<? echo isset($_REQUEST['resolucion'])?$_REQUEST['resolucion']:''; ?>"  />
                                    </td>
                                </tr>	
                            <? }  ?> 

                           <? if($controlArchivo['opcion']=='actos')
                               { ?>     
                                <tr>
                                    <td valign='top'> Descripci&oacute;n:</td>
                                    <td valign='top' colspan='4'>
                                        <textarea name="descripcion" rows="3" cols="50"><? echo isset($_REQUEST['descripcion'])?$_REQUEST['descripcion']:''; ?></textarea>
                                    </td>
                                </tr>	
                            <? }  ?>     
                           <? if($controlArchivo['opcion']=='normatividad')
                               { ?>     
                                <tr>
                                    <td valign='top' colspan='5'>
                                        <input type="radio" name="normatividad" value="Planta" />Planta
                                        <input type="radio" name="normatividad" value="Vinculación_Especial" checked/>Vinculación Especial
                                    </td>
                                </tr>	
                            <? }  
                            elseif($controlArchivo['opcion']=='convocatoria' && $controlArchivo['nivel']==88)
                               { ?>     
                                <tr><td valign='top'> Facultad : </td>
                                    <td valign='top' colspan='4'>
                                        <input type="radio" name="facultad" value="MedioAmbiente" />Medio Ambiente
                                        <input type="radio" name="facultad" value="Ciencias" />Ciencias
                                        <input type="radio" name="facultad" value="Tecnólogica" />Tecnólogica
                                        <input type="radio" name="facultad" value="Ingeniería" />Ingeniería
                                        <input type="radio" name="facultad" value="Artes" />Artes
                                        <input type="radio" name="facultad" value="Otras" checked/>Otras
                                    </td>
                                </tr>	
                            <? }  ?>      
                                                                
                                <tr>
                                    <td valign='top'>Archivo:</td>
                                    <td valign='top'> 
                                        <input type="file" name="archivo" />
                                        <br><br>
                                        <font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>* Tenga en cuenta tamaño m&aacute;ximo permitido del archivo es de 2 megas y la extensi&oacute;n permitida es pdf. </font>
                                    </td>
                                     <td valign='top'> 
                                     <?  if(isset($_REQUEST['msgError'])){
                                               $error=$_REQUEST['msgError'];
                                               echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
                                                    <img src='".$this->configuracion['site'] . $this->configuracion['grafico']."/asterisco.gif'>".$error."</font>";
                                            }
                                     ?>
                                    </td>
                                    <td valign='middle' width="50px" colspan="2" align='center' />
                                        <input type='hidden' name='action' value='<? echo $formulario;?>'/>     
                                        <input type='hidden' name='ruta' value='<? echo $ruta;?>'/>
                                       <? //reenvia las varibles de contro de archivo
                                        foreach($controlArchivo as $arc=>$value)
                                            { 
                                             ?> <input type='hidden' name='<? echo $arc;?>' value='<? echo $controlArchivo[$arc];?>'/> <?}
                                        ?>
                                        <input value="Guardar Archivo" id="btnGrabar" name="aceptar" type="button" onclick="if(<? echo $verificar; ?>){document.forms['<? echo $formulario?>'].submit()}else{false}" >
                                    </td>
                                  </tr>	
                              </table>
                              </center></form>
			    </td>
			</tr>
                        </tbody>
			</tr>
		</table>
            <div id="div_mensaje1" align="center" class="ab_name">
           </div>
           
	    </div>

        <?
    }       
    

function cargarArchivo($ruta,$archivo)
    {
            $parametro["directorio"]=$ruta;
            $parametro["nombreCampo"]="archivo";
            //(0)false->no permite cambiar el nombre; (1)true ->permite cambiar el nombre
            if ($archivo=='resolucion' || $archivo=='actos')
               {$parametro["nombreUnico"]=TRUE; }
            else
               {$parametro["nombreUnico"]=0;}
            
            $tipoArchivo= array("pdf","PDF");
            $resultado=$this->cargarArchivoServidor($this->configuracion, $parametro,$tipoArchivo);
            return $resultado;

    }

    
function historialActos($docente)
    {
    if($docente>0)
            {   $docente=array('identificacion'=>$docente);
                $cadena_sql = $this->sql->cadena_sql("datosUsuario", $docente);
                $datosusuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                //buscavinculaciones activas
                $cadena_sql = $this->sql->cadena_sql("actosAdministrativos", $docente);
                $datosActos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
            }
     else{ $datosusuario[0]['DOC_APEL']='';$datosusuario[0]['DOC_NOM']=''; $datosActos='';}       
    
    ?>
        <script language="javascript">
        function confirmar (mensaje)
            {return confirm('¿Estas seguro de querer se borrar el acto Administrativo '+mensaje+' ?');}
        </script>    
        <table id="tabla"  class="contenidotabla" width="100%" border ="1">
                     <thead class='sigma'>
                        <th class='espacios_proyecto' colspan ="4">
                             <? $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                $ruta="pagina=registroDocumentosVinculacion";
                                $ruta.="&opcion=nuevoActo";
                                $ruta.="&docente=".$_REQUEST['docente'];
                                $ruta.="&carpeta=".$this->path.'actosAdministrativos/';
                                $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                              ?>
                             <a href="<?echo $indice.$ruta;?>" onmouseover="Tip('<center>Registrar Acto Administrativo</center>', SHADOW, true, TITLE, 'Acto Administrativo', PADDING, 9)" >
                             <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/asociar.png" width="25" height="25"> Registrar Nuevo Acto Administrativo
                             </a>
                        </th>
                    </thead>
            
                    <thead class='sigma'>
                        <th class='espacios_proyecto' colspan ="4"><?echo "HISTORIAL DE ACTOS ADMINISTRATIVOS - ". $datosusuario[0]['DOC_APEL'].' '.$datosusuario[0]['DOC_NOM']; ?></th>
                    </thead>
                    <thead class='sigma'>
                    <th class='niveles centrar' > Fecha Registro </th>    
                    <th class='niveles centrar' > Descripci&oacute;n</th>    
                    <th class='niveles centrar' > Acto</th>
                    <th class='niveles centrar' > Acci&oacute;n</th>
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
                                        <td width="30%" class='cuadro_plano centrar'>
                                            <? $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                               $ruta="pagina=registroDocumentosVinculacion";
                                               $ruta.="&opcion=borrarActo";
                                               $ruta.="&docente=".$_REQUEST['docente'];
                                               $ruta.="&carpeta=".$this->path.'actosAdministrativos/';
                                               $ruta.="&archivo=".$datosActos[$key]['ACTO_AINT'];
                                               $ruta.="&cod_acto=".$datosActos[$key]['ACTO_COD'];
                                               
                                               $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                                             ?>
                                            <a href="<?echo $indice.$ruta;?>" onmouseover="Tip('<center>Borrar Acto Administrativo</center>', SHADOW, true, TITLE, 'Acto Administrativo', PADDING, 9)" onclick="return confirmar('<? echo $datosActos[$key]['ACTO_NOM_ARCHIVO'];?>')"  >
                                            <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/x.png" width="15" height="15">
                                            </a>
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
    

function borrarRegistro($registro,$dato)
    {       
        $cadenaBorrar=$this->sql->cadena_sql($registro,$dato);
        $resBorrado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadenaBorrar,'');
        if($resBorrado)
            {return true;}
        else
            {return false;}
    }     
    
function guardarRegistro($archivo)
    {       
        switch ($archivo['opcion'])
            { case 'resolucion':
                     $cadena_Arch = $this->sql->cadena_sql("actualizaVinculacion", $archivo);
                     $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_Arch, "");
                   break;
              case 'actos':
                     $cadena_cod = $this->sql->cadena_sql("codActo", '');
                     $codigo=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_cod, "busqueda");   
                     $archivo['codigo']=isset($codigo[0]['cod'])?($codigo[0]['cod']):1;
                     $archivo['usuario']=$this->usuario;
                  
                     $cadena_Arch = $this->sql->cadena_sql("insertarActo", $archivo);
                     $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_Arch, "");
                   break; 
            }
        //exit;    
        
    } 
    
}


?>

            