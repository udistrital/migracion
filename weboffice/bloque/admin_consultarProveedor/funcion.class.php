<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//require_once('dir_relativo.cfg');
require_once($configuracion["raiz_condor"].'/script/mensaje_error.inc.php');

class funciones_adminConsultarProveedor extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/estilo.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->sql = new sql_adminConsultarProveedor();
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
                
                $this->accesoOracle=$this->conectarDB($configuracion,"conexion_proveedor");
                $this->conexion=$this->conectarDB($configuracion,"proveedor");

                $this->formulario="admin_consultarProveedor";
                $this->verificar='control_vacio("'.$this->formulario.'","cedula")';
                $this->addModulo="addModulo(".$this->formulario.",tablaActividad,actividad,especialidad, 'tablaActividad')";

                $url = explode("?",$_SERVER['HTTP_REFERER']);
                $this->redir = $url[0]."?pagina=adminConsultarProveedor&opcion=consultar&modulo=adminProveedor";//echo $this->redir."<br>"; var_dump($url);
                $numero = $_POST['numero'];
	}
	
	//Rescata los valores del formulario para guardarlos en la base de datos.
	
	
	function encabezado($configuracion){
        ?>
           <table class='contenidotabla centrar'>
                <tr align="center">
                    <td class="centrar"><h4>OFICINA ASESORA DE SISTEMAS</h4>
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                    </td>
                </tr>
                <tr align="center">
                    <td class="centrar"><h4>PROVEEDORES<br>
                        UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</h4>
                      <hr noshade class="hr">   
                    </td>
                </tr>
           </table>
        <?    
        }//fin funcion encabezado



        //***********************************************************************************************************************//
        //******************************************FUNCION CONSULTAREGISTRO*****************************************************//
        //***********************************************************************************************************************//

        function verProveedor($configuracion){
         
            $this->encabezado($configuracion);
            $this->consultaProveedor($configuracion);
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

            $documento=$_REQUEST['documentoEsp'];
            $digito=$_REQUEST['digitoEsp'];
           
            $variable=array($documento, $digito);
            $cadena_sql=$this->sql->cadena_sql($configuracion, "proveedor", $variable);
            $proveedor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        //----------------------------------------------------TAB'S---------------------------------------------------------------------------------------

            $html.="<form enctype='multipart/form-data' method='POST' action='index.php' name='".$this->formulario."'>";
            $html_1='            <br>';
            $html_1.='    <fieldset>';
            $html_1.='        <table class="sigma_borde">';
            $html_1.='      <tr>
                                <td class="renglones">Fecha de Registro</td>
                                <td><input type="text" name="registro" value="'.$proveedor[0][20].'" class="cajatexto"> </td>
                                <td>Ultima actualizaci&oacute;n </td>
                                <td><input type="text" name="registro" value="'.$proveedor[0][21].'" class="cajatexto"> </td>
                            </tr>';
            $html_1.='            <tr>
                            <td class="renglones"><font color="red">* </font>Nit o cedula:</td>
                            <td colspan="3"><input type="text" name="cedula" class="cajatexto" size="12px" value="'.$proveedor[0][0].'" readonly="readonly"> -
                                            <input type="text" name="digitoVerificacion" class="cajatexto" size="2px" value="'.$proveedor[0][1].'" readOnly></td>
                        </tr>';
            $html_1.='            <tr>
                            <td class="renglones"><font color="red">* </font>Nombre de la empresa:</td>
                            <td colspan="3"><input type="text" name="nomEmpresa" class="cajatexto" size="70px" value="'.$proveedor[0][2].'" readOnly></td>
                        </tr>';
            $html_1.='            <tr>
                        <td class="renglones"><font color="red">* </font>K de contrataci&oacute;n:</td>
                        <td colspan="3"><input type="text" name="kcontratacion" class="cajatexto" size="12px" value="'.$proveedor[0][28].'" readonly="readonly"></td>
                    </tr>';

            if ($proveedor[0][27]=='S'){
                $check='Si';
               
            }
            elseif ($proveedor[0][27]=='N'){
                $check='No';
            }

            $html_1.='   <tr>
                                    <td class="renglones"><font color="red">* </font>Rup:</td>
                                    <td><input type="text" name="cedula" class="cajatexto" size="12px" value="'.$check.'" readonly="readonly"></td>
                         </tr>';

            if ($proveedor[0][32]=='S'){
                $check='Si';
                
            }
            elseif ($proveedor[0][32]=='N'){
                $check='No';
            }
            $html_1.='   <tr>
                            <td class="renglones"><font color="red">* </font>Registro mercantil:</td>
                            <td><input type="text" name="registroMercantil" class="cajatexto" size="12px" value="'.$check.'" readonly="readonly"></td>
                        </tr>';
            $html_1.='        </table>';
            $html_1.='    </fieldset>';

//--------------------------------------------------------------------------------------------------------------------------------------

            $html_2='            <br>';
            $html_2.='            <fieldset>';
            $html_2.='                <table class="sigma_borde">';

            $variable=$proveedor[0][24];
            $cadena_sql=$this->sql->cadena_sql($configuracion, "pais", $variable);
            $resultadoP=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            $variable=$proveedor[0][22];
            $cadena_sql=$this->sql->cadena_sql($configuracion, "departamento", $variable);
            $resultadoD=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
            $html_2.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Pa&iacute;s:</td>
                                    <td colspan="3"><input type="text" class="cajatexto" value="'.$resultadoP[0][0].'" readonly="readonly"></td>
                                </tr>';

            $html_2.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Departamento:</td>
                                    <td colspan="3">
                                        <input type="text" class="cajatexto" value="'.$resultadoD[0][0].'" readonly="readonly"></td>
                                </tr>';

            $variable=$proveedor[0][23];
            $cadena_sql=$this->sql->cadena_sql($onfiguracion, "municipio", $variable);
            $resultadoM=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            
            $html_2.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Ciudad:</td>
                                    <td colspan="3">
                                        <input type="text" class="cajatexto" value='.$resultadoM[0][0].' readonly="readonly"></td>
                                </tr>';
            $html_2.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Direcci&oacute;n:</td>
                                    <td colspan="3"><input type="text" name="direccion" class="cajatexto" size="80px" value="'.$proveedor[0][3].'" readonly="readonly"></td>
                                </tr>';
            $html_2.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Correo:</td>
                                    <td colspan="3"><input type="text" name="correo" class="cajatexto" size="40px" value="'.$proveedor[0][8].'" readonly="readonly"></td>
                                </tr>';
            $html_2.='                    <tr>
                                    <td class="renglones">Sitio Web:</td>
                                    <td colspan="3"><input type="text" name="sitioWeb" class="cajatexto" size="40px" value="'.$proveedor[0][9].'" readonly="readonly"></td>
                                </tr>';
            $html_2.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Tel&eacute;fono 1:</td>
                                    <td><input type="text" name="telefono1" class="cajatexto" size="10px" value="'.$proveedor[0][4].'" readonly="readonly"></td>
                                    <td class="renglones">Tel&eacute;fono 2:</td>
                                    <td><input type="text" name="telefono2" class="cajatexto" size="10px" value="'.$proveedor[0][5].'" readonly="readonly"></td>
                                </tr>';
            $html_2.='                    <tr>
                                    <td class="renglones">Movil:</td>
                                    <td><input type="text" name="movil" class="cajatexto" size="10px" value="'.$proveedor[0][6].'" readonly="readonly"></td>
                                    <td class="renglones">Fax:</td>
                                    <td><input type="text" name="fax" class="cajatexto" size="10px" value="'.$proveedor[0][7].'" readonly="readonly"></td>
                                </tr>';
            $html_2.='                   <tr>
                                    <td class="renglones"><font color="red">* </font>Asesor Comercial:</td>
                                    <td><input type="text" name="nomAsesor" class="cajatexto" size="40px" value="'.$proveedor[0][25].'" readonly="readonly"></td>
                                    <td class="renglones"><font color="red">* </font>Telefono del Asesor:</td>
                                    <td><input type="text" name="telAsesor" class="cajatexto" size="10px" value="'.$proveedor[0][26].'" readonly="readonly"></td>
                                </tr>';
            $html_2.='                </table>';
            $html_2.='            </fieldset>';

//-------------------------------------------------------------------------------------------------------------------------------

            $html_3='            <br>';
            $html_3.='            <fieldset>';
            $html_3.='                <table class="sigma_borde" width="100%">';
            $html_3.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Documento:</td>
                                    <td><input type="text" name="cedulaRepresentante" class="cajatexto" size="10px" value="'.$proveedor[0][11].'" readonly="readonly"></td>
                                </tr>';
            $html_3.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Primer Apellido:</td>
                                    <td><input type="text" name="ape1" class="cajatexto" size="30px" value="'.$proveedor[0][12].'" readonly="readonly"></td>
                                    <td class="renglones">Segundo Apellido:</td>
                                    <td><input type="text" name="ape2" class="cajatexto" size="30px" value="'.$proveedor[0][13].'" readonly="readonly"></td>
                                </tr>';
            $html_3.='                    <tr>
                                    <td class="renglones"><font color="red">* </font>Primer Nombre:</td>
                                    <td><input type="text" name="nom1" class="cajatexto" size="30px" value="'.$proveedor[0][14].'" readonly="readonly"></td>
                                    <td class="renglones">Segundo Nombre:</td>
                                    <td><input type="text" name="nom2" class="cajatexto" size="30px" value="'.$proveedor[0][15].'" readonly="readonly"></td>
                                </tr>';
            $html_3.='                </table>';
            $html_3.='            </fieldset>';

//----------------------------------------------------------------------------------------------------------------------------------

            $html_4='        <br>';
            $html_4.='            <fieldset>';
            $html_4.='            <table class="sigma_borde">';
           
            $variable=array($documento, $digito);
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaActividad", $variable);
            $actividad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            $html_4.='                <tr>
                                <td colspan="2">
                                <div>
                                    <table class="sigma_borde">
                                    <br>
                                        <tbody id="tablaActividad">';
         if (is_array($actividad)){
                $html_4.='                            <tr>';
                $html_4.='                            <th>Actividad</th>';
                $html_4.='                            <th>Especialidad</th>';
                $html_4.='                            </tr>';
         $i=1;
         foreach ($actividad as $act){

                $html_4.='                            <tr>';
                $html_4.='                                <td><input type="text" value="'.$act[1].'" readonly="readonly" size="20%"></td>';
                $html_4.='                                <input type="hidden" name="idMod'.$i.'" value="'.$act[0].'" id="idMod'.$i.'">';
                $html_4.='                                <input type="hidden" name="valor_mod" value="'.$i.'">';
                $html_4.='                                <td><input type="text" value="'.$act[3].'" readonly="readonly" size="45%">';
                $html_4.='                            </tr>';

                $i++;
                }
            }
            $html_4.='</tbody>';
            $html_4.='                        </table>
                                </div>
                                </td>
                            </tr>';
            $html_4.='            </table>';
            $html_4.='            </fieldset>';

//---------------------------------------------------------------------------------------------------------------------------------

            $html_5='        <br>';
            $html_5.='            <fieldset>';
            $html_5.='            <table class="sigma_borde">';
            $html_5.='                <tr>
                                <td class="renglones"><font color="red">* </font>Realice una descripcion detallada del produto:</td>
                            </tr>';
            $html_5.='                <tr>                                                                
                                <td colspan="2"><textarea name="descripcion" cols="100" rows="8" readonly="readonly">'.UTF8_DECODE($proveedor[0][31]).'</textarea>

                                </td>
                            </tr>';
            $html_5.='            </table>';
            $html_5.='            </fieldset>';

//-------------------------------------------------------------------------------------------------------------------------------------------------

            $html_6='<div>';
            $html_6.='    <br>';
            $html_6.='<table class="sigma_borde" width="100%">';
            $html_6.='    <tr>';
            $html_6.='        <td>';
            $html_6.='            <fieldset>';
            $html_6.='                <table>';

            if ($proveedor[0][16]=='N'){
                $check='Natural';
                
            }
            elseif ($proveedor[0][16]=='J'){
                $check='Juridica';
            }

            $html_6.='         <tr>
                                    <td class="renglones" rowspan="2" width="40%">Tipo de Persona</td>
                                    <td width="5%" rowspan="2"><input type="text" name="tipopersona" value="'.$check.'" size="12px" class="cajatexto"></td>
                               </tr>';
            $html_6.='         </table>';
            $html_6.='         </fieldset>';
            $html_6.='        </td>';
            $html_6.='        <td>';

            if ($proveedor[0][17]=='C'){
                $check='Comun';
            }
            elseif ($proveedor[0][17]=='S'){
                $check='Simplificado';
            }
            $html_6.='            <fieldset>
                            <table>
                                <tr>
                                    <td class="renglones" rowspan="2" width="40%">Regimen Contributivo</td>
                                    <td width="5%"><input type="text" name="regimen" value="'.$check.'" size="12px" class="cajatexto"></td>
                                </tr>
                            </table>
                        </fieldset>';
             $html_6.='        </td>';
             $html_6.='       <td>';

             if ($proveedor[0][29]=='S'){
                    $check='Si';
                }
                elseif ($proveedor[0][29]=='N'){
                    $check='No';
                }
             $html_6.='           <fieldset>
                            <table>
                                <tr>
                                    <td class="renglones" rowspan="2" width="50%">Exclusividad Producto <br>de importaci&oacute;n</td>
                                    <td width="5%"><input type="text" name="importacion" value="'.$check.'" size="5px" class="cajatexto"></td>
                                </tr>
                            </table>
                        </fieldset>';
             $html_6.='       </td>';
             $html_6.='   </tr>';
             $html_6.='   <tr>';
             $html_6.='       <td>';

             if ($proveedor[0][18]=='S'){
                $check='Si';
             }
             elseif ($proveedor[0][18]=='N'){
               $check='No';
            }
             $html_6.='           <fieldset>
                            <table>
                                <tr>
                                    <td class="renglones" rowspan="2" width="25%">Registro Unico<br> de proponentes</td>
                                    <td width="25%"><input type="text" name="registroUnico" value="'.$check.'" size="5px" class="cajatexto"></td>
                                </tr>
                            </table>
                        </fieldset>';
             $html_6.='       </td>';
             $html_6.='       <td>';

             if ($proveedor[0][19]=='S'){
                $check='Si';
             }
             elseif ($proveedor[0][19]=='N'){
                $check='No';
            }
             $html_6.='           <fieldset>
                            <table>
                                <tr>
                                    <td class="renglones" rowspan="2" width="25%">Es usted PYME:</td>
                                    <td width="25%"><input type="text" name="pyme" value="'.$check.'" size="5px" class="cajatexto"></td>
                                </tr>
                            </table>
                        </fieldset>';
             $html_6.='       </td>';
             $html_6.='   </tr>';

             $html_6.='</table>';
             $html_6.='</div>';



                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/tabs.class.php");
		$tabs=new tabs($configuracion);

		$tabs->tab($html_1,"Info. B&aacute;sica");
		$tabs->tab($html_2,"Info. Contacto");
		$tabs->tab($html_3,"Representante Legal");
		$tabs->tab($html_4,"Actividad Comercial");
		$tabs->tab($html_5,"Descrip. Producto");
		$tabs->tab($html_6,"Info.Adicional");

		$html.=$tabs->armar_tabs($configuracion);

                $html.="<table width='100%'>";
		$html.="<tr>";
		$html.="<td align='center'>";
		$html.="<input type='hidden' name='opcion' value='buscador'>";
                $html.="<input type='hidden' name='action' value='".$this->formulario."'>";
		$html.="<input type='hidden' name='documento' value='".$_REQUEST['documento']."'>";
		$html.="<input type='hidden' name='digito' value='".$_REQUEST['digito']."'>";
		$html.="<input type='hidden' name='razonSocial' value='".$_REQUEST['razonSocial']."'>";
		$html.="<input type='hidden' name='actividad' value='".$_REQUEST['actividad']."'>";
		$html.="<input type='hidden' name='especialidad' value='".$_REQUEST['especialidad']."'>";
		$html.="<input type='hidden' name='codigorad' value='".$_REQUEST['codigoRad']."'>";
		$html.="<input value='Regresar' name='aceptar' tabindex='".$tab++."' type='submit'><br>";
		$html.="</td>";
		$html.="</tr>";
		$html.="</table>";
                $html.="</form>";

                echo $html;

        }//fin function consultaRegistro

     
        //*********************************************************************************************************************************//
        //******************************************FUNCION PARA LA CONSULTA DE LOS PROVEEDORES********************************************//
        //*********************************************************************************************************************************//

        function consultaProveedor($configuracion){
            ?>
             <script>
                    function mostrar_div(elemento) {

                        if(elemento.value=="cod") {
                            document.getElementById("campo_palabra").style.display = "none";
                            document.getElementById("campo_codigo").style.display = "block";
                            document.getElementById("campo_actividad").style.display = "none";
                            document.forms[0].palabraEA.value='';
                        }else if(elemento.value=="razon") {
                            document.getElementById("campo_codigo").style.display = "none";
                            document.getElementById("campo_palabra").style.display = "block";
                            document.getElementById("campo_actividad").style.display = "none";
                            document.forms[0].codigoEA.value='';
                        }else if(elemento.value=="activ") {
                            document.getElementById("campo_codigo").style.display = "none";
                            document.getElementById("campo_palabra").style.display = "none";
                            document.getElementById("campo_actividad").style.display = "block";
                            document.forms[0].codigoEA.value='';
                        }else {
                            document.getElementById("campo_codigo").style.display = "block";
                        }

                    }
                </script>
            <?
            $this->encabezado($configuracion);

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

            $cadena_sql=$this->sql->cadena_sql($configuracion, "actividad");
            $resultadoActividad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $select=new html();
            $configuracion["ajax_function"]="xajax_consultaEspecialidad";
            $configuracion["ajax_control"]="actividad";
            $actividad=$select->cuadro_lista($resultadoActividad,"actividad",$configuracion,$resultadoActividad[0][0],2,100,0,"actividad",150);

            $variable=$resultadoActividad[0][0];
            $cadena_sql=$this->sql->cadena_sql($configuracion, "especialidad", $variable);
            $resultadoEspecialidad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            for ($i=0; $i<count($resultadoEspecialidad);$i++)
            {
               $registroEsp[$i][0]=$resultadoEspecialidad[$i][0];
               $registroEsp[$i][1]=UTF8_DECODE($resultadoEspecialidad[$i][1]);
            }

            $select= new html();
            $especialidad=$select->cuadro_lista($registroEsp,"especialidad",$configuracion,$resultadoEspecialidad[0][0],2,100,0,"especialidad", 150);
            
            ?>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                    <div id="campo_documento">
                 <table class="sigma_borde centrar" width="100%">
                    <caption class="sigma centrar">
                        SELECCIONE LA OPCI&Oacute;N PARA BUSCAR EL PROVEEDOR
                    </caption>
                    <tr class="sigma">
                        <td class="sigma derecha" width="20%">
                            NIT o documento del proveedor<br>
                            Raz&oacute;n Social<br>
                            Actividad Comercial
                        </td>
                        <td class="sigma centrar" width="2%">
                            <input type="radio" name="codigorad" value="cod" checked onclick="javascript:mostrar_div(this)"><br>
                            <input type="radio" name="codigorad" value="razon" onclick="javascript:mostrar_div(this)">
                            <input type="radio" name="codigorad" value="activ" onclick="javascript:mostrar_div(this)">
                        </td>
                        <td  class="sigma centrar">
                            <div align="center" id="campo_codigo">
                                <table class="sigma centrar" width="80%" border="0">
                                    <tr>
                                        <td class="sigma centrar" colspan="2">
                                            <font size="1">Digite el NIT o documento del proveedor</font><br>
                                            <input type="text" name="documento" value="" size="8" maxlength="10">
                                            <input type="text" name="digito" value="" size="2" maxlength="1">
                                        </td>
                                        <td class="sigma centrar" rowspan="2">
                                            <input type="hidden" name="opcion" value="buscador">
                                            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                            <small><input class="boton" type="submit" value=" Buscar "></small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div align="center" id="campo_palabra" style="display:none">
                                <table class="sigma centrar"  width="80%" border="0" >
                                    <tr>
                                        <td class="sigma centrar" colspan="3">
                                            <font size="1">Digite la raz&oacute;n social que desea buscar</font><br>
                                            <input type="text" name="razonSocial" value="" size="30" maxlength="30">
                                        </td>
                                        <td class="sigma centrar" rowspan="2">
                                            <input type="hidden" name="opcion" value="buscador">
                                            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                            <small><input class="boton" type="submit" value=" Buscar "></small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div align="center" id="campo_actividad" style="display:none">
                                <table class="sigma centrar"  width="80%" border="0" >
                                    <tr>
                                        <td class="sigma centrar" colspan="3">
                                            <font size="1">Selecciona la actividad y Especialidad que desea buscar:</font><br>
                                            <?echo $actividad?>
                                            <div id="divEspecialidad">
                                                <?echo $especialidad?>
                                            </div>
                                        </td>
                                        <td class="sigma centrar" rowspan="2">
                                            <input type="hidden" name="opcion" value="buscador">
                                            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                            <small><input class="boton" type="submit" value=" Buscar "></small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>

                    </tr></table>   
                    </div>
                </form>
            <?
            }//fin function consultaProveedor


            function buscaProveedor($configuracion){
    
                $this->consultaProveedor($configuracion);
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                $codigoRad=$_REQUEST["codigoRad"];
                
                if ($_REQUEST['documento'] && $codigoRad=="cod") {

                if (!is_numeric($_REQUEST['documento'])) {
                        echo "<script>alert('El documento debe ser numerico')</script>";
                        $this->consultaProveedor($configuracion);
                    }

                    $identificacion=$_REQUEST['documento'];
                    $digito=$_REQUEST['digito'];
                    $variable=array($identificacion,$digito);
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "proveedor", $variable);
                    $proveedor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }
                else if ($_REQUEST['razonSocial'] && $codigoRad=="razon") {
                    $variable=strtr(strtoupper($_REQUEST['razonSocial']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "proveedorRazonSocial", $variable);
                    $proveedor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }

                else if ($_REQUEST['actividad'] && $_REQUEST['especialidad'] && $codigoRad=="activ"){
                    $actividadSeleccionada=$_REQUEST['actividad'];
                    $especialidadSeleccionada=$_REQUEST['especialidad'];

                    $variable=array($actividadSeleccionada, $especialidadSeleccionada);
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "proveedorActividadComercial", $variable);
                    $proveedor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }
                 if (is_array($proveedor)){
                    ?>
                    <br>
                        <table class="sigma contenidotabla centrar">
                            <caption class="sigma centrar">
                                PROVEEDORES REGISTRADOS
                            </caption>
                            <tr>
                                <th class="sigma centrar">NIT</th>
                                <th class="sigma centrar">Razon Social</th>
                                <th class="sigma centrar">k de contrataci&oacute;n</th>
                                <th class="sigma centrar">Correo</th>
                                <th class="sigma centrar">Ver</th>
                            </tr>
                            <?foreach ($proveedor as $provee){
                                 $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                 $variable="pagina=adminConsultarProveedor";
                                 $variable.="&opcion=verProveedor";
                                 $variable.="&documentoEsp=".$provee[0];
                                 $variable.="&digitoEsp=".$provee[1];
                                 $variable.="&codigoRad=".$_REQUEST['codigoRad'];
                                 $variable.="&razonSocial=".$_REQUEST['razonSocial'];
                                 $variable.="&actividad=".$_REQUEST['actividad'];
                                 $variable.="&especialidad=".$_REQUEST['especialidad'];
                                 $variable.="&documento=".$identificacion;
                                 $variable.="&digito=".$digito;
                                        
                                 include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                 $this->cripto=new encriptar();
                                 $variable=$this->cripto->codificar_url($variable,$configuracion);
                            ?>
                            
                            <tr>
                                <td  class="cuadro_plano centrar"><?echo $provee[0]."-".$provee[1]?></td>
                                <td  class="cuadro_plano centrar"><?echo $provee[2]?></td>
                                <td  class="cuadro_plano centrar"><?echo $provee[28]?></td>
                                <td  class="cuadro_plano centrar"><?echo $provee[8]?></td>
                                <td  class="cuadro_plano centrar">
                                    <a href="<?echo $pagina.$variable?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/bookmark_folder.png" border="0" >
                                    </a>
                                </td>
                            </tr>
                           
                            <?}?>
                        </table>

                    <?

                    }
                    else {
                        if($codigoRad=="cod"){
                            $stg="***No existe registro de proveedores para el NIT consultado***";
                        }
                        elseif ($codigoRad=="razon"){
                            $stg="***No existe registro de proveedores para la Raz&oacute;n Social consultada***";
                        }
                        elseif ($codigoRad=="activ"){
                            $stg="***No exite registro de proveedores para la actividad y especialidad seleccionadas***";
                        }

                        ?>
                    <br>
                        <table class="sigma contenidotabla centrar" width="100%">
                            <tr>
                                <td class="centrar"><font color="red"><?echo $stg?></font></td>
                            </tr>
                        </table>
                    <br>

                        <?
                        
                    }
        }//fin function buscaProveedor
}//fin de la clase
        
	

