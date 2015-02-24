<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminHomologaciones extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    private $configuracion;
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
        $this->pagina="admin_homologaciones";
        $this->opcion="mostrar";
        //Conexion sga
        $this->configuracion = $configuracion;
   
    }

     /**
     * Funcion que valida si existe un proyecto curricular seleccionado para mostrar el formulario de registro
     * @param <array> $_REQUEST (pagina,opcion,codProyecto)
     */
function crearTablaHomologacion(){
       if (isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:''){ 
          
           $this->enlaceCambiarProyecto() ;
           $this->mostrarFormulario();
           $this->mostrarFormularioUnion();
           $this->mostrarFormularioBifurcacion();
	 }else{
            $this->formSeleccionarProyecto();
        }
    }
    
     /**
     * Funcion que muestra en formulario general para el registro de homologaciones
     * @param <array> $this->verificar
     * @param <array> $this->formulario
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto)
      * Utiliza los metodos camposBusquedaEspaciosPadre, camposBusquedaEspaciosHijo, enlaceRegistrar
     */
function mostrarFormulario(){  
        $cod_proyecto_curricular=$_REQUEST['cod_proyecto'];
	$this->verificar1="control_vacio(".$this->formulario."1,'cod_padre1')";
	$this->verificar1.="&&control_vacio(".$this->formulario."1,'cod_hijo1')";

         ?>
        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>

       <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>1'>
        <div id="normal" <?if($_REQUEST['tipo_hom']<>'normal') echo "style='display: none'"?>>
          <table class="formulario">
              <tr><td align="center"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/hom_normal.png" width="80" height="50" border="0"><br><b>Homologaciones uno a uno</b></td>
                  <td align="center"><a href="#" onclick="$('#normal').hide();$('#union').show();$('#bifurcacion').hide();"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/union.png" width="80" height="50" border="0"><br>Homologaciones dos a uno (Unión)</a></td>
                  <td align="center"><a href="#" onclick="$('#normal').hide();$('#union').hide();$('#bifurcacion').show();"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/bifurcacion.png" width="80" height="50" border="0"><br>Homologaciones uno a dos (Bifurcación)</a></td>
              </tr>
          </table>
            <br>
		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > ESPACIOS DE MI PROYECTO CURRICULAR</th>
                        <th class='espacios_homologos'>ESPACIOS HOMÓLOGOS</th>
                        </thead>
                        
                        
			<tbody>
			<tr>
				<td align="center"  class='cuadro_plano '>
					<?
                                        $this->camposBusquedaEspaciosPadre($cod_proyecto_curricular);
                                        ?>
				</td>
				<td  align='center'  class='cuadro_plano '>
				   <?
                                        $this->camposBusquedaEspaciosHijo($cod_proyecto_curricular);
                                        ?>
				</td>
                               	<td  align='center'><? $this->enlaceRegistrar();?>
                                        
				</td>
			</tr>
                        </tbody>
			
			</tr>
		</table>
            <div id="div_mensaje1" align="center" class="ab_name">
           </div>
           
	    </div>
               
           </form>
        <?
    }

    /**
     * Funcion que muestra los campos del espacio padre
     * @param <int> $cod_proyecto_curricular
     * Utiliza el metodo ajax xajax_buscarPadre
     */

    function camposBusquedaEspaciosPadre($cod_proyecto_curricular){ 
        $_REQUEST['cod_padre1']=(isset($_REQUEST['cod_padre1'])?$_REQUEST['cod_padre1']:'');
        $espacio_ac=(isset($espacio_ac)?$espacio_ac:'');
        if($_REQUEST['cod_padre1']){
            $cod_espacio=$_REQUEST['cod_padre1'];
            $cod_proyecto=$_REQUEST['cod_proyecto'];
            $espacio_ac=$this->consultarEspacioAcademico($cod_espacio,$cod_proyecto);
            
        }
        ?>
    
                
                    <table  class="contenidotabla" border="0" cellpadding="0" cellspacing="0"  >
			<thead class='sigma'>
                            <th >Codigo</th>
                            <th >Espacio Acad&eacute;mico</th>
                            <th >Cr&eacute;ditos</th>
                        </thead>
                        <tr>
				<td align="center">
					<input type="text" name='cod_padre1' id="cod_padre1" size="7" <? if($_REQUEST['cod_padre1'])echo "value='".$_REQUEST['cod_padre1']."'";?> onKeyPress="return solo_numero_sin_slash(event)" onBlur="xajax_buscarPadre(document.getElementById('cod_padre1').value,<? echo $cod_proyecto_curricular;?>,1,1)"  tabindex='1'>
				</td>
				<td  align='center'>
                                        <div id ="div_nomPadre1"> <input type="text" name='nom_padre1' id="nom_padre1" size="18"  <? if($espacio_ac) echo "value='".$espacio_ac[0]['NOM_ASIGNATURA']."'";?>  readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credPadre1"> <input type="text" name='cred_padre1' id="cred_padre1" size="3" <? if($espacio_ac) echo "value='".$espacio_ac[0]['CREDITOS']."'";?> readonly='true'></div>
				</td>
			</tr>
			
		</table>
                
        <?
    }

    /**
     * Funcion que muestra los campos del espacio hijo
     * @param <int> $cod_proyecto_curricular
     * Utiliza el metodo ajax xajax_buscarHijo
     */
    
    function camposBusquedaEspaciosHijo($cod_proyecto_curricular){
        $_REQUEST['cod_hijo1']=(isset($_REQUEST['cod_hijo1'])?$_REQUEST['cod_hijo1']:'');
        $espacio_ac=(isset($espacio_ac)?$espacio_ac:'');
        
        if($_REQUEST['cod_hijo1']){
            $cod_espacio=$_REQUEST['cod_hijo1'];
            $cod_proyecto="";
            $espacio_ac=$this->consultarEspacioAcademico($cod_espacio,$cod_proyecto);
        }
        ?>
    
        <table class="contenidotabla" border="0" cellpadding="0" cellspacing="0"  >
                            <th width="20">Codigo </th>
                            <th width="50">Espacio Acad&eacute;mico</th>
                            <th width="50">Cr&eacute;ditos</th>
                        <tr>
				<td align="center">
					<input type="text" name='cod_hijo1' id="cod_hijo1" size="7" <? if($_REQUEST['cod_hijo1'])echo "value='".$_REQUEST['cod_hijo1']."'";?>  onKeyPress="return solo_numero_sin_slash(event)"  onBlur="xajax_buscarHijo(document.getElementById('cod_hijo1').value,<? echo $cod_proyecto_curricular;?>,1,1)" tabindex='2' >
				</td>
				<td  align='center'>
                                       <div id ="div_nomHijo1"> <input type="text" name='nom_hijo1' id="nom_hijo1" size="18" <? if($espacio_ac) echo "value='".$espacio_ac[0]['NOM_ASIGNATURA']."'";?> readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credHijo1"> <input type="text" name='cred_hijo1' id="cred_hijo1" size="3" <? if($espacio_ac && $espacio_ac[0]['CREDITOS']>0 ) echo "value='".$espacio_ac[0]['CREDITOS']."'";?> readonly='true'></div>
				</td>
			</tr>
			
		</table>
        <?
    }
  
    /**
     * Funcion que muestra el formulario con el listado de proyectos curriculares asociados, para que se seleccione un espacio
     * @param <int> $this->identificacion
     * @param <int> $this->configuracion
     * @param <int> $this->crypto
     * Utiliza el metodo consultarProyectosCoordinador
     */

    function formSeleccionarProyecto() {
        $carreras = $this->consultarProyectosCoordinador();
        $indiceAcademico=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologaciones";
        $variable.="&opcion=crearTablaHomologacion";
        $variable.="&tipo_hom=normal";
  
        if (count($carreras)>1){        ?>
            <p>Seleccione un proyecto curricular:&nbsp;</p>
            <div align="center">
                <table border="0" width="530" cellpadding="0">
                <tr>
                <table border="0" cellpadding="0" cellspacing="0" width="530">
                    
        <?                    
            $i=0;
            
            while(isset($carreras[$i][0]))
            {
                $variable_enlace=$variable."&cod_proyecto=".$carreras[$i][0];
                $variable_enlace=$this->cripto->codificar_url($variable_enlace,$this->configuracion);
                $enlaceHomologaciones=$indiceAcademico.$variable_enlace;
                echo'<tr><td width="100%"><a href="'.$enlaceHomologaciones.'">'.$carreras[$i][0].' - '.$carreras[$i][1].'</a></td></tr>';
                $i++;
            }
        }elseif(isset($carreras[0][0])){ 
            $variable_enlace=$variable."&cod_proyecto=".$carreras[0][0];
            $variable_enlace=$this->cripto->codificar_url($variable_enlace,$this->configuracion);
            $enlaceHomologaciones=$indiceAcademico.$variable_enlace;
            echo "<script>location.replace('".$enlaceHomologaciones."')</script>";

        }else{
            echo "No existen proyectos curriculares asociados.";
        }
           
    }     
    
    /**
     * Funcion que consulta en la base de datos los proyectos curriculares asociados a un coordinador
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
  function consultarProyectosCoordinador() {
      $cadena_sql = $this->sql->cadena_sql("consultaProyectosCoordinador", $this->identificacion);
      return $resultadoProyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

    /**
     * Funcion que muestra el enlace para registrar en la tabla de homologaciones
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
  function enlaceRegistrar() {
       //$pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->formulario ?>">
          <input type='hidden' name='opcion' value="registrar">
          <input type='hidden' name='cod_proyecto' value="<? echo $_REQUEST['cod_proyecto']?>">
          <input type='hidden' name='cod_proyecto_hom' value="0">
          <input value="Registrar" name="aceptar" tabindex='3' type="button" onclick="if(<? echo $this->verificar1; ?>){document.forms['<? echo $this->formulario?>1'].submit()}else{false}">                              
            
            
         <?
    }

function mostrarFormularioUnion(){    
        $cod_proyecto_curricular=$_REQUEST['cod_proyecto'];
	$this->verificar2="control_vacio(".$this->formulario."2,'cod_padre2')";
	$this->verificar2.="&&control_vacio(".$this->formulario."2,'cod_hijo2')";
        $this->verificar2.="&&control_vacio(".$this->formulario."2,'cod_hijo3')";

         ?>
          <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>2'>
        <div  id="union"  <?if($_REQUEST['tipo_hom']<>'union') echo "style='display: none'"?>>
          <table class="formulario">
              <tr><td align="center"><a href="#" onclick="$('#union').hide();$('#normal').show();"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/hom_normal.png" width="80" height="50" border="0"><br>Homologaciones uno a uno</a></b></td>
                  <td align="center"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/union.png" width="80" height="50" border="0"><br><b>Homologaciones dos a uno (Unión)</b></td>
                  <td align="center"><a href="#" onclick="$('#normal').hide();$('#union').hide();$('#bifurcacion').show();"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/bifurcacion.png" width="80" height="50" border="0"><br>Homologaciones uno a dos (Bifurcación)</a></td>
              </tr>
          </table>
            <br>
		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > ESPACIOS DE MI PROYECTO CURRICULAR</th>
                        <th class='espacios_homologos'>ESPACIOS HOMÓLOGOS</th>
                        </thead>
                        
                        
			<tbody>
			<tr>
				<td align="center"  class='cuadro_plano '>
					<?
                                        $this->camposBusquedaEspaciosPadreUnion($cod_proyecto_curricular);
                                        ?>
				</td>
				<td  align='center'  class='cuadro_plano '>
				   <?
                                        $this->camposBusquedaEspaciosHijoUnion($cod_proyecto_curricular);
                                        ?>
				</td>
                               	<td  align='center'><? $this->enlaceRegistrarUnion();?>
                                        
				</td>
			</tr>
			</tbody>
			
			</tr>
		</table>
            <div id="div_mensaje2" align="center" class="ab_name">
           </div>
	</div>
           

       </form>
        <?
    }    
function mostrarFormularioBifurcacion(){   
        $cod_proyecto_curricular=$_REQUEST['cod_proyecto'];
	$this->verificar3="control_vacio(".$this->formulario."3,'cod_padre3')";
	$this->verificar3.="&&control_vacio(".$this->formulario."3,'cod_padre4')";
        $this->verificar3.="&&control_vacio(".$this->formulario."3,'cod_hijo4')";

         ?>
          <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>3'>
        <div  id="bifurcacion"  <?if($_REQUEST['tipo_hom']<>'bifurcacion') echo "style='display: none'"?>>
          <table class="formulario">
              <tr><td align="center"><a href="#" onclick="$('#union').hide();$('#normal').show();$('#bifurcacion').hide();"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/hom_normal.png" width="80" height="50" border="0"><br>Homologaciones uno a uno</a></b></td>
                  <td align="center"><a href="#" onclick="$('#normal').hide();$('#union').show();$('#bifurcacion').hide();"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/union.png" width="80" height="50" border="0"><br>Homologaciones dos a uno (Unión)</a></td>
                  <td align="center"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/bifurcacion.png" width="80" height="50" border="0"><br><b>Homologaciones uno a dos (Bifurcación)</b></td>
                  
              </tr>
          </table>
            <br>
		<table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
			<thead class='sigma'>
                        <th class='espacios_proyecto' > ESPACIOS DE MI PROYECTO CURRICULAR</th>
                        <th class='espacios_homologos'>ESPACIOS HOMÓLOGOS</th>
                        </thead>
			<tbody>
			<tr>
				<td align="center"  class='cuadro_plano '>
					<?
                                        $this->camposBusquedaEspaciosPadreBifurcacion($cod_proyecto_curricular);
                                        ?>
				</td>
				<td  align='center'  class='cuadro_plano '>
				   <?
                                        $this->camposBusquedaEspaciosHijoBifurcacion($cod_proyecto_curricular);
                                        
                                   ?>
				</td>
                               	<td  align='center'><? $this->enlaceRegistrarBifurcacion();?>
                                        
				</td>
			</tr>
			</tbody>
			
			</tr>
		</table>
            <div id="div_mensaje3" align="center" class="ab_name">
           </div>
	</div>
           

       </form>
        <?
    }    
    
 function camposBusquedaEspaciosHijoUnion($cod_proyecto_curricular){ 
     
            $_REQUEST['cod_hijo2']=(isset($_REQUEST['cod_hijo2'])?$_REQUEST['cod_hijo2']:'');
            $_REQUEST['cred_hijo2']=(isset($_REQUEST['cred_hijo2'])?$_REQUEST['cred_hijo2']:'');
            $_REQUEST['porc_hijo2']=(isset($_REQUEST['porc_hijo2'])?$_REQUEST['porc_hijo2']:'');
            $_REQUEST['cod_hijo3']=(isset($_REQUEST['cod_hijo3'])?$_REQUEST['cod_hijo3']:'');
            $_REQUEST['cred_hijo3']=(isset($_REQUEST['cred_hijo3'])?$_REQUEST['cred_hijo3']:'');
            $_REQUEST['porc_hijo3']=(isset($_REQUEST['porc_hijo3'])?$_REQUEST['porc_hijo3']:'');
            $_REQUEST['req_hijo2']=(isset($_REQUEST['req_hijo2'])?$_REQUEST['req_hijo2']:'');
            $_REQUEST['req_hijo3']=(isset($_REQUEST['req_hijo3'])?$_REQUEST['req_hijo3']:'');
            $espacio_ac1=(isset($espacio_ac1)?$espacio_ac1:'');
            $espacio_ac2=(isset($espacio_ac2)?$espacio_ac2:'');
        
            if($_REQUEST['cod_hijo2']){
                $cod_espacio=$_REQUEST['cod_hijo2'];
                $cod_proyecto="";
                $espacio_ac1=$this->consultarEspacioAcademico($cod_espacio,$cod_proyecto);
            }
            if($_REQUEST['cod_hijo3']){
                $cod_espacio2=$_REQUEST['cod_hijo3'];
                $cod_proyecto2="";
                $espacio_ac2=$this->consultarEspacioAcademico($cod_espacio2,$cod_proyecto2);
            }

            ?>
    
        <table class="contenidotabla" border="0" cellpadding="0" cellspacing="0"  >
                            <th width="20">Codigo </th>
                            <th width="40">Espacio Acad&eacute;mico</th>
                            <th width="20">Cr&eacute;ditos</th>
                            <th width="10"><center>%</center></th>
                            <th width="20">Requiere Aprobarlo</th>
                            
                         <tr>
				<td align="center">
					<input type="text" name='cod_hijo2' id="cod_hijo2" size="7"  <? if($_REQUEST['cod_hijo2'])echo "value='".$_REQUEST['cod_hijo2']."'";?>   onKeyPress="return solo_numero_sin_slash(event)"  onBlur="xajax_buscarHijo(document.getElementById('cod_hijo2').value,<? echo $cod_proyecto_curricular;?>,2,2)" tabindex='6' >
				</td>
				<td  align='center'>
                                       <div id ="div_nomHijo2"> <input type="text" name='nom_hijo2' id="nom_hijo2" size="18" <? if($espacio_ac1) echo "value='".$espacio_ac1[0]['NOM_ASIGNATURA']."'";?>  readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credHijo2"> <input type="text" name='cred_hijo2' id="cred_hijo2" size="2"  <? if($espacio_ac1 && $espacio_ac1[0]['CREDITOS']>0 ) echo "value='".$espacio_ac1[0]['CREDITOS']."'";?>    readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_porcHijo2"> <input type="text" name='porc_hijo2' id="porc_hijo2" size="2"   maxlength="2"  tabindex='7' value="<? if($_REQUEST['porc_hijo2'])echo $_REQUEST['porc_hijo2'];else echo "50";?>"></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_reqHijo2">    <input type="radio" name="req_hijo2" value="N" <? if($_REQUEST['req_hijo2']=='N') echo "checked";?>> NO<br>
                                                                    <input type="radio" name="req_hijo2" value="S" <? if($_REQUEST['req_hijo2']=='S' || $_REQUEST['req_hijo2']!='N') echo "checked";?>> SI
                                        </div>
				</td>
			</tr>
                        <tr>
				<td align="center">
					<input type="text" name='cod_hijo3' id="cod_hijo3" size="7"  <? if($_REQUEST['cod_hijo3'])echo "value='".$_REQUEST['cod_hijo3']."'";?>   onKeyPress="return solo_numero_sin_slash(event)"  onBlur="xajax_buscarHijo(document.getElementById('cod_hijo3').value,<? echo $cod_proyecto_curricular;?>,3,2)" tabindex='8' >
				</td>
				<td  align='center'>
                                       <div id ="div_nomHijo3"> <input type="text" name='nom_hijo3' id="nom_hijo3" size="18" <? if($espacio_ac2) echo "value='".$espacio_ac2[0]['NOM_ASIGNATURA']."'";?> readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credHijo3"> <input type="text" name='cred_hijo3' id="cred_hijo3" size="2"  <? if($espacio_ac2 && $espacio_ac2[0]['CREDITOS']>0 ) echo "value='".$espacio_ac2[0]['CREDITOS']."'";?>  readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_porcHijo3"> <input type="text" name='porc_hijo3' id="porc_hijo3" size="2"  maxlength="2"  tabindex='8' value="<? if($_REQUEST['porc_hijo3'])echo $_REQUEST['porc_hijo3'];else echo "50";?>"></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_reqHijo3"> 
                                            <input type="radio" name="req_hijo3" value="N" <? if($_REQUEST['req_hijo3']=='N') echo "checked";?>> NO<br>
                                            <input type="radio" name="req_hijo3" value="S" <? if($_REQUEST['req_hijo3']=='S'  || $_REQUEST['req_hijo3']!='N') echo "checked";?>> SI
                                    </div>
				</td>
			</tr>
                        
			
		</table>
        <?
    }

       function camposBusquedaEspaciosPadreUnion($cod_proyecto_curricular){  
           $_REQUEST['cod_padre2']=(isset($_REQUEST['cod_padre2'])?$_REQUEST['cod_padre2']:'');           
           $espacio_ac=(isset($espacio_ac)?$espacio_ac:'');
        
        if($_REQUEST['cod_padre2']){
            $cod_espacio=$_REQUEST['cod_padre2'];
            $cod_proyecto=$_REQUEST['cod_proyecto'];
            $espacio_ac=$this->consultarEspacioAcademico($cod_espacio,$cod_proyecto);
        }
                    
        ?>
                    
                    <table  class="contenidotabla" border="0" cellpadding="0" cellspacing="0"  >
			<thead class='sigma'>
                            <th >Codigo</th>
                            <th >Espacio Acad&eacute;mico</th>
                            <th >Cr&eacute;ditos</th>
                        </thead>
                        <tr>
				<td align="center">
					<input type="text" name='cod_padre2' id="cod_padre2" size="7"  <? if($_REQUEST['cod_padre2'])echo "value='".$_REQUEST['cod_padre2']."'";?>  onKeyPress="return solo_numero_sin_slash(event)" onBlur="xajax_buscarPadre(document.getElementById('cod_padre2').value,<? echo $cod_proyecto_curricular;?>,2,2)"  tabindex='4'>
				</td>
				<td  align='center'>
                                        <div id ="div_nomPadre2"> <input type="text" name='nom_padre2' id="nom_padre1" size="18" <? if($espacio_ac) echo "value='".$espacio_ac[0]['NOM_ASIGNATURA']."'";?> readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credPadre2"> <input type="text" name='cred_padre2' id="cred_padre1" size="3" <? if($espacio_ac) echo "value='".$espacio_ac[0]['CREDITOS']."'";?>  readonly='true'></div>
				</td>
			</tr>
			
		</table>
                
        <?
    }
   
   
  function camposBusquedaEspaciosHijoBifurcacion($cod_proyecto_curricular){      
           $_REQUEST['cod_hijo4']=(isset($_REQUEST['cod_hijo4'])?$_REQUEST['cod_hijo4']:'');
           $espacio_ac=(isset($espacio_ac)?$espacio_ac:'');
        
        if($_REQUEST['cod_hijo4']){
            $datos['cod_espacio']=$_REQUEST['cod_hijo4'];
            $datos['cod_proyecto']=$_REQUEST['cod_proyecto'];
            $espacio_ac=$this->consultarEspacioAcademico($datos);
        }
                    
        ?>    
                    <table  class="contenidotabla" border="0" cellpadding="0" cellspacing="0"  >
			<thead class='sigma'>
                            <th >Codigo</th>
                            <th >Espacio Acad&eacute;mico</th>
                            <th >Cr&eacute;ditos</th>
                        </thead>
                        <tr>
				<td align="center">
					<input type="text" name='cod_hijo4' id="cod_hijo4" size="7"  <? if($_REQUEST['cod_hijo4'])echo "value='".$_REQUEST['cod_hijo4']."'";?>  onKeyPress="return solo_numero_sin_slash(event)" onBlur="xajax_buscarHijo(document.getElementById('cod_hijo4').value,<? echo $cod_proyecto_curricular;?>,4,3)"  tabindex='4'>
				</td>
				<td  align='center'>
                                        <div id ="div_nomHijo4"> <input type="text" name='nom_hijo4' id="nom_Hijo4" size="18" <? if($espacio_ac) echo "value='".$espacio_ac[0]['NOM_ASIGNATURA']."'";?> readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credHijo4"> <input type="text" name='cred_hijo4' id="cred_Hijo4" size="3" <? if($espacio_ac) echo "value='".$espacio_ac[0]['CREDITOS']."'";?>  readonly='true'></div>
				</td>
			</tr>
			
		</table>
                
        <?
    }
   
  function camposBusquedaEspaciosPadreBifurcacion($cod_proyecto_curricular){       
            $_REQUEST['cod_padre3']=(isset($_REQUEST['cod_padre3'])?$_REQUEST['cod_padre3']:'');           
            $_REQUEST['cred_padre3']=(isset($_REQUEST['cred_padre3'])?$_REQUEST['cred_padre3']:'');
            $_REQUEST['cod_padre4']=(isset($_REQUEST['cod_padre4'])?$_REQUEST['cod_padre4']:'');
            $_REQUEST['cred_padre4']=(isset($_REQUEST['cred_padre4'])?$_REQUEST['cred_padre4']:'');
            $_REQUEST['req_padre3']=(isset($_REQUEST['req_padre3'])?$_REQUEST['req_padre3']:'');
            $_REQUEST['req_padre4']=(isset($_REQUEST['req_padre4'])?$_REQUEST['req_padre4']:'');
            $espacio_ac1=(isset($espacio_ac1)?$espacio_ac1:'');
            $espacio_ac2=(isset($espacio_ac2)?$espacio_ac2:'');
        
            if($_REQUEST['cod_padre3']){
                $datos['cod_espacio']=$_REQUEST['cod_padre3'];             
                $datos['cod_proyecto']="";
                $espacio_ac1=$this->consultarEspacioAcademico($datos);
            }
            if($_REQUEST['cod_padre4']){
                $datos2['cod_espacio']=$_REQUEST['cod_padre4'];               
                $datos2['cod_proyecto']="";
                $espacio_ac2=$this->consultarEspacioAcademico($datos2);
            }

            ?>
    
        <table class="contenidotabla" border="0" cellpadding="0" cellspacing="0"  >
                            <th width="20">Codigo </th>
                            <th width="40">Espacio Acad&eacute;mico</th>
                            <th width="20">Cr&eacute;ditos</th>
                            
                         <tr>
				<td align="center">
					<input type="text" name='cod_padre3' id="cod_padre3" size="7"  <? if($_REQUEST['cod_padre3'])echo "value='".$_REQUEST['cod_padre3']."'";?>   onKeyPress="return solo_numero_sin_slash(event)"  onBlur="xajax_buscarPadre(document.getElementById('cod_padre3').value,<? echo $cod_proyecto_curricular;?>,3,3)" tabindex='6' >
				</td>
				<td  align='center'>
                                       <div id ="div_nomPadre3"> <input type="text" name='nom_padre3' id="nom_padre3" size="18" <? if($espacio_ac1) echo "value='".$espacio_ac1[0]['NOM_ASIGNATURA']."'";?>  readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credPadre3"> <input type="text" name='cred_padre3' id="cred_padre3" size="2"  <? if($espacio_ac1 && $espacio_ac1[0]['CREDITOS']>0 ) echo "value='".$espacio_ac1[0]['CREDITOS']."'";?>    readonly='true'></div>
				</td>

			</tr>
                         <tr>
				<td align="center">
					<input type="text" name='cod_padre4' id="cod_padre4" size="7"  <? if($_REQUEST['cod_padre4'])echo "value='".$_REQUEST['cod_padre4']."'";?>   onKeyPress="return solo_numero_sin_slash(event)"  onBlur="xajax_buscarPadre(document.getElementById('cod_padre4').value,<? echo $cod_proyecto_curricular;?>,4,3)" tabindex='6' >
				</td>
				<td  align='center'>
                                       <div id ="div_nomPadre4"> <input type="text" name='nom_padre4' id="nom_padre4" size="18" <? if($espacio_ac1) echo "value='".$espacio_ac1[0]['NOM_ASIGNATURA']."'";?>  readonly='true'></div>
				</td>
                                <td  align='center'>
                                        <div id ="div_credPadre4"> <input type="text" name='cred_padre4' id="cred_padre4" size="2"  <? if($espacio_ac1 && $espacio_ac1[0]['CREDITOS']>0 ) echo "value='".$espacio_ac1[0]['CREDITOS']."'";?>    readonly='true'></div>
				</td>

			</tr>
		</table>
        <?
    }

  function enlaceRegistrarUnion() {
       $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->formulario ?>">
          <input type='hidden' name='opcion' value="registrarUnion">
          <input type='hidden' name='cod_proyecto' value="<? echo $_REQUEST['cod_proyecto']?>">
          <input type='hidden' name='cod_proyecto_hom' value="0">
          <input value="Registrar" name="aceptar" tabindex='9' type="button" onclick="if(<? echo $this->verificar2; ?>){document.forms['<? echo $this->formulario?>2'].submit()}else{false}">                              
            
            
         <?
    }
    
  function enlaceRegistrarBifurcacion() {
       $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->formulario ?>">
          <input type='hidden' name='opcion' value="registrarBifurcacion">
          <input type='hidden' name='cod_proyecto' value="<? echo $_REQUEST['cod_proyecto']?>">
          <input type='hidden' name='cod_proyecto_hom' value="0">
          <input value="Registrar" name="aceptar" tabindex='9' type="button" onclick="if(<? echo $this->verificar3; ?>){document.forms['<? echo $this->formulario?>3'].submit()}else{false}">                              
            
            
         <?
    }
    
    function consultarEspacioAcademico($cod_espacio, $cod_proyecto){
          $datos=array('cod_espacio'=>$cod_espacio,
                         'cod_proyecto'=>$cod_proyecto
                );              
          $cadena_sql = $this->sql->cadena_sql("consultaEspacioAcademico", $datos);
          return $resultadoEspacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

/**
     * Funcion que muestra el enlace para redireccionar y cambiar de proyecto curricular
     */
    function enlaceCambiarProyecto() {
        $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologaciones";
        $variable.="&opcion=crearTablaHomologacion";
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
       echo "<br><div align='right' > <a href='".$pagina.$variable."'  class='enlaceHomologaciones'>::Cambiar de Proyecto Curricular</a></div><br>";
      
    }    
}


?>
