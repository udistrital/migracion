<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funciones_registroSalon extends funcionGeneral {
    //@ Método costructor
    function __construct($configuracion, $sql) {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->sql=$sql;
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        //Conexion Coordinador
        $this->accesoCoordinador=$this->conectarDB($configuracion,"coordinador");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->formulario='admin_registroSalon';
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }
    // @ Método que invoca el metodo que muestra el formulario que recepciona los datos para crear un nuevo espacio
 function registrarSalon($configuracion) 
    {    $variable=$this->identificacion;
         $varHorario=array('espacio'=>$_REQUEST['espacio'], 'grupo'=>$_REQUEST['grupo'], 'dia'=>$_REQUEST['dia'],'hora'=>$_REQUEST['hora'],'anio'=>$_REQUEST['anio'],"periodo"=>$_REQUEST['periodo']);
         $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorario);
         $resultadoexiste=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
         $cadena_sql_dia=$this->sql->cadena_sql($configuracion,"dia", $_REQUEST['dia']);
         $resultado_dia=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_dia,"busqueda");
         $cadena_sql_hora=$this->sql->cadena_sql($configuracion,"hora", $_REQUEST['hora']);
         $resultado_hora=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_hora,"busqueda");
         ?>
         <form enctype='multipart/form-data' method='POST' action='index.php' name='<?$this->formulario?>'>
         <table class="cuadro_plano centrar" width="95%" border="0" >
                 <thead>
                 <th colspan="2" >BUSCAR SALONES</th>
                 </thead>
                 <thead>
                 <th colspan="2" >DIA:&nbsp;<?echo $resultado_dia[0]['DIA'] ?>&nbsp;&nbsp;-&nbsp;&nbsp;HORA:&nbsp;<?echo $resultado_hora[0]['HORA_L']?></th>
                 </thead>
                 <tbody>
                     <tr>
                         <td width="14%" align="right" >
                             SEDE :
                         </td>
                         <td align="left">
                            <? $varSede=array('sede'=>'-1');
                               $cadena_sql_sede=$this->sql->cadena_sql($configuracion,"sede",$varSede);
                               $resultado_sede=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_sede,"busqueda");
                               
                               if(isset($resultadoexiste[0]['COD_SEDE']))
                                        {  $varSede=array('sede'=>$resultadoexiste[0]['COD_SEDE']);
                                           $cadena_sql_sede=$this->sql->cadena_sql($configuracion,"sede_codigo",$varSede);
                                           $actual_sede=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_sede,"busqueda"); } 
                            ?>
                            <select name='sede' id='sede' onchange="xajax_salones(document.getElementById('sede').value,'<?echo $_REQUEST['hora']?>','<?echo $_REQUEST['dia']?>','<?echo $_REQUEST['capacidad']?>','<?echo $_REQUEST['periodo']?>','<?echo $_REQUEST['anio']?>')">
                            <option value="0" >Seleccione la Sede..</option>
                              <?
                              foreach($resultado_sede as $key => $data) 
                                 { if($actual_sede[0]['COD_SEDE']==$data[0])
                                        {  echo "<option value=".$actual_sede[0]['ID_SEDE']." selected>".$actual_sede[0]['NOML_SEDE']."</option>";  }
                                   else { echo "<option value=".$resultado_sede[$key]['ID_SEDE'].">".$resultado_sede[$key]['NOML_SEDE']."</option>";}
                                 }
                              ?>
                             </select>
                         </td>
                     </tr>
                     <tr>
                         <td align="right">
                             SAL&Oacute;N :
                         </td>
                         <td align="left">
                             <div name="salon" id="salon">
                            <? if(isset($resultadoexiste[0]['SALON']))
                                   {
                                    $varSalon=array('sede'=>$resultadoexiste[0]['COD_SEDE'],'salon'=>$resultadoexiste[0]['SALON'],'dia'=>$_REQUEST['dia'],'hora'=>$_REQUEST['hora'],'anio'=>$_REQUEST['anio'],"periodo"=>$_REQUEST['periodo'],'cupos'=>$_REQUEST['capacidad']);
                                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "salones", $varSalon);
                                    $resSalones=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
                                    
                                    $varSalon=array('salon'=>$resultadoexiste[0]['SALON']);
                                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "salon", $varSalon);
                                    $actualSalon=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
                                    ?> <select name='salon' id='salon'>
                                         <?  foreach ($resSalones as $key => $value) 
                                               { if($actualSalon[0]['COD_SALON_NVO']==$resSalones[$key]['COD_SALON_NVO'])
                                                      { echo "<option value=".$actualSalon[0]['COD_SALON_NVO']." selected>".$actualSalon[0]['COD_SALON_NVO']." - ".$actualSalon[0]['NOM_EDIFICIO']." - ".$actualSalon[0]['NOM_SALON']." - Cap: ".$actualSalon[0]['CUPOS']."</option>";  }
                                                 else { echo "<option value=".$resSalones[$key]['COD_SALON_NVO'].">".$resSalones[$key]['COD_SALON_NVO']." - ".$resSalones[$key]['NOM_EDIFICIO']." - ".$resSalones[$key]['NOM_SALON']." - Cap: ".$resSalones[$key]['CUPOS']."</option>";}
                                               }
                                         ?>    
                                        </select>
                                  <?}        
                                else{ ?>
                                        <select disabled=yes >
                                            <option>Seleccione el Sal&oacute;n</option>
                                        </select>
                                 <? }?>
                             </div>
                         </td>
                     </tr>

                 </tbody>
             </table>
             <table class="cuadro_plano centrar" width="95%" >
                 <tr>
                     <td  align='center'>
                         <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                         <input type='hidden' name='action' value="<? echo $this->formulario ?>">
                         <input type='hidden' name='opcion' value="guardar">
                         <input type='hidden' name='dia' value="<? echo $_REQUEST['dia']?>">
                         <input type='hidden' name='hora' value="<? echo $_REQUEST['hora']?>">
                         <input type='hidden' name='espacio' value="<? echo $_REQUEST['espacio']?>">
                         <input type='hidden' name='anio' value="<? echo $_REQUEST['anio']?>">
                         <input type='hidden' name='periodo' value="<? echo $_REQUEST['periodo']?>">
                         <input type='hidden' name='capacidad' value="<? echo $_REQUEST['capacidad']?>">
                         <input type='hidden' name='grupo' value="<? echo $_REQUEST['grupo']?>">
                         <!--<input type="hidden" name="proyecto" value="<? //+echo$resultado[2][0]?>">-->
                         <input value="Guardar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="submit()">
                     </td>
                     <td>
                         <?
                         $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                         $variable="pagina=adminasignarSalon";
                         $variable.="&opcion=borrarSalon";
                         $variable.="&dia=".$_REQUEST["dia"];
                         $variable.="&hora=".$_REQUEST["hora"];
                         $variable.="&espacio=".$_REQUEST["espacio"];
                         $variable.="&anio=".$_REQUEST["anio"];
                         $variable.="&periodo=".$_REQUEST["periodo"];
                         $variable.="&grupo=".$_REQUEST["grupo"];
                         $variable=$this->cripto->codificar_url($variable,$configuracion);
                         ?>
                         <input type="button" name="borrar" onclick="javascript:location.replace('<?echo $pagina.$variable?>')" value="Borrar">
                     </td>

                 </tr>
             </table>
         </form>
     <?
    }

function guardarSalon($configuracion) {

        $anio=$_REQUEST['anio'];
        $periodo=$_REQUEST['periodo'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $dia=$_REQUEST['dia'];
        $hora=$_REQUEST['hora'];
        $sede=$_REQUEST['sede'];
        $salon=$_REQUEST['salon'];
        $horaAnterior = $hora - 1;
        $horaSiguiente = $hora + 1;
        
        $varSede=array('sede'=>$sede);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "sede", $varSede);
        $nombreSede=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
        $sede=$nombreSede[0]['COD_SEDE'];
        
        //var_dump($nombreSede);exit;
        $varSalon=array('salon'=>$salon);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "salon", $varSalon);
        $nombreSalon=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");

        //$varHorario=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora);
        $varHorario=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorario);
        $resultadoexiste=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
        //var_dump($resultadoexiste);//exit;
        if(isset($resultadoexiste[0]['COD_SEDE']))
            { 
            $varHorarioAnt=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$horaAnterior,'anio'=>$anio,"periodo"=>$periodo);
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorarioAnt);
            $resultadoexisteAnterior=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
            //busca hora siguiente
            $varHorarioSig=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$horaSiguiente,'anio'=>$anio,"periodo"=>$periodo);
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorarioSig);
            $resultadoexisteSiguiente=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
            
            if(is_array($resultadoexisteAnterior) || is_array($resultadoexisteSiguiente))
                {   /* && ($resultadoexisteSiguiente[0]['COD_SEDE']!=0 || $sede!=0)--filtro que permite no tener en cuenta los horarios por asignar */
                   if( is_array($resultadoexisteAnterior) 
                       && ($resultadoexisteAnterior[0]['COD_SEDE']!=$sede)    
                       && ($resultadoexisteAnterior[0]['COD_SEDE']!=0 && $sede!=0)      
                       && ($resultadoexisteAnterior[0]['COD_SEDE']!=1 || $sede!=1)                                 
                       && ($resultadoexisteAnterior[0]['COD_SEDE']!=4 || $sede!=4)                                      
                       && ($resultadoexisteAnterior[0]['COD_SEDE']!=1 || $sede!=4)                                                            
                       && ($resultadoexisteAnterior[0]['COD_SEDE']!=4 || $sede!=1)) 
                        {  echo "<script>alert('Registro fallido! No puede registrar en una sede diferente con diferencia menor a 1 hora');</script>";
                           echo "  <script> window.close(); </script>";exit;
                        }
                    elseif( is_array($resultadoexisteSiguiente) 
                            && ($resultadoexisteSiguiente[0]['COD_SEDE']!=$sede)
                            && ($resultadoexisteSiguiente[0]['COD_SEDE']!=0 && $sede!=0)      
                            && ($resultadoexisteSiguiente[0]['COD_SEDE']!=1 || $sede!=1)                                 
                            && ($resultadoexisteSiguiente[0]['COD_SEDE']!=4 || $sede!=4)                                      
                            && ($resultadoexisteSiguiente[0]['COD_SEDE']!=1 || $sede!=4)                                                            
                            && ($resultadoexisteSiguiente[0]['COD_SEDE']!=4 || $sede!=1) )
                       {   echo "<script>alert('Registro fallido! No puede registrar en una sede diferente con diferencia menor a 1 hora');</script>";
                           echo " <script> window.close(); </script>";exit;
                       }
                   else
                      {   $varHorarioBorra=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo);
                            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "borrarhorario", $varHorarioBorra);
                            $resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"");
                            //registrar horario del curso
                            $varHorarioNvo=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo,"sede"=>$sede,"salon"=>$salon,"estado"=>'A');
                            $cadena_sql = $this->sql->cadena_sql($configuracion, 'registrar_horario', $varHorarioNvo);
                            $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"");
                            $resultado_1="Sede: ".$nombreSede[0]['NOMC_SEDE']."<br>Edificio: ".$nombreSalon[0]['NOM_EDIFICIO']." <br> Salon: ".$nombreSalon[0]['COD_SALON_NVO']."<br>".$nombreSalon[0]['NOM_SALON'];
                            
                            if($resultado==true) 
                                {
                                 echo "  <script>
                                            eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                            alert('Registro exitoso  Sede: ".$nombreSede[0]['NOML_SEDE']." - Salon: ".$nombreSalon[0]['COD_SALON_NVO']." - ".$nombreSalon[0]['NOM_SALON']." ');
                                         </script>";
                                 echo "  <script> window.close(); </script>";exit;
                                }
                           else { echo "<script>alert('Registro fallido');</script>";
                                  echo " <script> window.close(); </script>";exit;
                                }
                        }
                }
           else
                {
                    $varHorarioBorra=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo);
                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "borrarhorario", $varHorarioBorra);
                    $resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"");
                    //registrar horario del curso
                    $varHorarioNvo=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo,"sede"=>$sede,"salon"=>$salon,"estado"=>'A');
                    $cadena_sql = $this->sql->cadena_sql($configuracion, 'registrar_horario', $varHorarioNvo);
                    $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"");
                    $resultado_1="Sede: ".$nombreSede[0]['NOMC_SEDE']."<br>Edificio: ".$nombreSalon[0]['NOM_EDIFICIO']." <br> Salon: ".$nombreSalon[0]['COD_SALON_NVO']."<br>".$nombreSalon[0]['NOM_SALON'];
                    if($resultado==true) 
                        {
                         echo "  <script>
                                    eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                    alert('Registro exitoso  Sede: ".$nombreSede[0]['NOML_SEDE']." - Salon: ".$nombreSalon[0]['COD_SALON_NVO']." - ".$nombreSalon[0]['NOM_SALON']." ');
                                 </script>";
                         echo "  <script> window.close(); </script>";exit;
                        }
                   else { echo "<script>alert('Registro fallido');</script>";
                   
                          echo " <script> window.close(); </script>";exit;
                        }
                }
            }
        else{
                 
                $varCurso=array('espacio'=>$espacio, 'grupo'=>$grupo,'anio'=>$anio,"periodo"=>$periodo);
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultaGrupos", $varCurso);
                $resCurso=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");

                if(isset($resCurso[0]['INSCRITOS']) && $resCurso[0]['INSCRITOS']!=0) 
                    {  echo "  <script> alert('No es posible registrar el salón, ya que el grupo tiene ".$resCurso[0]['INSCRITOS']."  estudiantes inscritos!!');  </script>";
                       echo "  <script> window.close();  </script>";
                    }
                else   
                    {  
                    //consulta hora anterior
                        $varHorarioAnt=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$horaAnterior,'anio'=>$anio,"periodo"=>$periodo);
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorarioAnt);
                        $resultadoexisteAnterior=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
                        //var_dump($resultadoexisteAnterior);
                        //busca hora siguiente
                        $varHorarioSig=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$horaSiguiente,'anio'=>$anio,"periodo"=>$periodo);
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorarioSig);
                        $resultadoexisteSiguiente=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
                        //var_dump($resultadoexisteSiguiente);//exit;
                        //echo $buscarexisteSede."<br>";

                        if(is_array($resultadoexisteAnterior) || is_array($resultadoexisteSiguiente))
                            {/* && ($resultadoexisteSiguiente[0]['COD_SEDE']!=0)--filtro que permite no tener en cuenta los horarios por asignar */
            //                   if( is_array($resultadoexisteAnterior) && ($resultadoexisteAnterior[0]['COD_SEDE']!=$sede) && ($resultadoexisteAnterior[0]['COD_SEDE']!=0 || $sede!=0) && ($resultadoexisteAnterior[0]['COD_SEDE']==1 && $sede!=4) || ($resultadoexisteAnterior[0]['COD_SEDE']==4  && $sede!=1))
                               if( is_array($resultadoexisteAnterior) 
                                   && ($resultadoexisteAnterior[0]['COD_SEDE']!=$sede)    
                                   && ($resultadoexisteAnterior[0]['COD_SEDE']!=0 && $sede!=0)      
                                   && ($resultadoexisteAnterior[0]['COD_SEDE']!=1 || $sede!=1)                                 
                                   && ($resultadoexisteAnterior[0]['COD_SEDE']!=4 || $sede!=4)                                      
                                   && ($resultadoexisteAnterior[0]['COD_SEDE']!=1 || $sede!=4)                                                            
                                   && ($resultadoexisteAnterior[0]['COD_SEDE']!=4 || $sede!=1)) 
                                    {  echo "<script>alert('Registro fallido! No puede registrar en una sede diferente con diferencia menor a 1 hora');</script>";
                                        echo "  <script> window.close(); </script>";exit;
                                    }
            //                   elseif (is_array($resultadoexisteSiguiente) && ($resultadoexisteSiguiente[0]['COD_SEDE']!=$sede) && ($resultadoexisteSiguiente[0]['COD_SEDE']!=0 || $sede!=0) && ($resultadoexisteSiguiente[0]['COD_SEDE']==1 && $sede!=4) || ($resultadoexisteSiguiente[0]['COD_SEDE']==4 && $sede!=1))
                               elseif( is_array($resultadoexisteSiguiente) 
                                       && ($resultadoexisteSiguiente[0]['COD_SEDE']!=$sede)
                                       && ($resultadoexisteSiguiente[0]['COD_SEDE']!=0 && $sede!=0)      
                                       && ($resultadoexisteSiguiente[0]['COD_SEDE']!=1 || $sede!=1)                                 
                                       && ($resultadoexisteSiguiente[0]['COD_SEDE']!=4 || $sede!=4)                                      
                                       && ($resultadoexisteSiguiente[0]['COD_SEDE']!=1 || $sede!=4)                                                            
                                       && ($resultadoexisteSiguiente[0]['COD_SEDE']!=4 || $sede!=1))    
                                    {
                                        echo "<script>alert('Registro fallido! No puede registrar en una sede diferente con diferencia menor a 1 hora');</script>";
                                        echo "<script>  window.close(); </script>";exit;
                                    }
                               else
                                    {   //registrar horario del curso

                                        $varHorarioNvo=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo,"sede"=>$sede,"salon"=>$salon,"estado"=>'A');
                                        $cadena_sql = $this->sql->cadena_sql($configuracion, 'registrar_horario', $varHorarioNvo);
                                        $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"");
                                        $resultado_1="Sede: ".$nombreSede[0]['NOMC_SEDE']."<br>Edificio: ".$nombreSalon[0]['NOM_EDIFICIO']." <br> Salon: ".$nombreSalon[0]['COD_SALON_NVO']."<br>".$nombreSalon[0]['NOM_SALON'];
                                        if($resultado==true) 
                                            {
                                             echo "  <script>
                                                        eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                                        alert('Registro exitoso  Sede: ".$nombreSede[0]['NOML_SEDE']." - Salon: ".$nombreSalon[0]['COD_SALON_NVO']." - ".$nombreSalon[0]['NOM_SALON']." ');
                                                     </script>";
                                             echo "  <script> window.close(); </script>";exit;
                                            }
                                       else { echo "<script>alert('Registro fallido, debe seleccionar un salón');</script>";
                                              echo " <script> window.close(); </script>";exit;
                                            }
                                    }
                            }
                        else
                           {
                                    //registrar horario del curso nuevo
                                   $varHorarioNvo=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo,"sede"=>$sede,"salon"=>$salon,"estado"=>'A');
                                   $cadena_sql = $this->sql->cadena_sql($configuracion, 'registrar_horario', $varHorarioNvo);
                                  //exit;
                                   $resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql,"");
                                   $resultado_1="Sede: ".$nombreSede[0]['NOMC_SEDE']."<br>Edificio: ".$nombreSalon[0]['NOM_EDIFICIO']." <br> Salon: ".$nombreSalon[0]['COD_SALON_NVO']."<br>".$nombreSalon[0]['NOM_SALON'];
                                     if($resultado==true) 
                                         {
                                          echo "  <script>
                                                     eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                                     alert('Registro exitoso  Sede: ".$nombreSede[0]['NOML_SEDE']." - Salon: ".$nombreSalon[0]['COD_SALON_NVO']." - ".$nombreSalon[0]['NOM_SALON']." ');
                                                  </script>";
                                          echo "  <script> window.close(); </script>";exit;
                                         }
                                    else { echo "<script>alert('Registro fallido, debe seleccionar un salón');</script>";
                                           echo " <script> window.close(); </script>";exit;
                                         }
                                }         
               }
        }
    }


  function borrarSalon($configuracion) 
    {
        $anio=$_REQUEST['anio'];
        $periodo=$_REQUEST['periodo'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $dia=$_REQUEST['dia'];
        $hora=$_REQUEST['hora'];
        
        $varCurso=array('espacio'=>$espacio, 'grupo'=>$grupo,'anio'=>$anio,"periodo"=>$periodo);
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultaGrupos", $varCurso);
        $resCurso=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");
            
            if(isset($resCurso[0]['INSCRITOS']) && $resCurso[0]['INSCRITOS']!=0) 
                {  echo "  <script> alert('No es posible borrar el registro, ya que ya que el grupo tiene ".$resCurso[0]['INSCRITOS']."  estudiantes inscritos!!');  </script>";
                   echo "  <script> window.close();  </script>";
                }
            else
                {  
                    $varHorario=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo);
                    $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario", $varHorario);
                    $resultadoexiste=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"busqueda");

                    if(isset($resultadoexiste[0]['COD_SEDE'])) 
                        {
                        $varHorarioBorra=array('espacio'=>$espacio, 'grupo'=>$grupo, 'dia'=>$dia,'hora'=>$hora,'anio'=>$anio,"periodo"=>$periodo);
                        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "borrarhorario", $varHorarioBorra);
                        $resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $this->cadena_sql,"");

                        $resultado_1=" - ";
                        if($resultadoborrar==true) 
                            { echo "  <script>
                                        eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                        alert('Registro eliminado exitosamente');
                                        </script>";
                              echo "  <script> window.close(); </script>";

                            }
                       else {
                            echo "<script>alert('No se pudo borrar el Registro');</script>";
                            echo "  <script> window.close(); </script>";
                            }
                    }else
                        {  echo "  <script> alert('No existe registro de horario para esta hora');  </script>";
                           echo "  <script> window.close();  </script>";
                        }
            }          
    }

    public function corregirRegistro() {
    }
    public function mostrarRegistro($configuracion,$registro,$total,$opcion,$variable) {
    }
    public function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario) {
    }
    public function nuevoRegistro($configuracion,$tema,$acceso_db) {
    }
}
?>
