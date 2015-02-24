<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroConsultarAgrupacionEspaciosCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        if ($this->nivel==28||$this->nivel==4)
        {
            $this->pagina="adminConfigurarPlanEstudioCoordinador";
            $this->opcion="mostrar";
        }
        elseif($this->nivel==61)
        {
            $this->pagina="adminAprobarEspacioPlan";
            $this->opcion="mostrar";
        }
        $this->formulario="registroConsultarAgrupacionEspaciosCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $this->clasificaciones=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;


    }
    
    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>ESPACIOS ACAD&Eacute;MICOS CON OPCIONES PARA EL PROYECTO CURRICULAR<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="2">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&opcion=".$this->opcion;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Volver al Plan de estudios
            </a>
        </td>
        <td class="centrar" colspan="2">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroCrearAgrupacionEspaciosCoordinador";
                    $variables.="&opcion=determinarClasificacion";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="35" height="35" border="0"><br>Crear nombre general de<br>Espacio Acad&eacute;mico con opciones
            </a>
        </td>
    </tr>
</table><?
    }

    function clasificacion($configuracion) {
    //Consultamos los proyectos curriculares con su respectivo
    //  plan de estudio, y los mostramos en un <select>        
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyectos_curriculares",$this->usuario);
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo
                         $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png
                 " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>ADMINISTRACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA
                POSTERIOR APROBACI&Oacute;N DE VICERRECTORIA ACAD&Eacute;MICA</h4>
            <hr noshade class="hr">
        </td>
    </tr><br><br>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain'
          method='GET,POST' action='index.php' name='<?echo
                  $this->formulario?>'>

        <tr class="centrar">
            <td>
                <h3>Seleccione la clasificaci&oacute;n</h3>
            </td>
        </tr>
        <tr class="centrar">
            <td>
                <select name="clasificacion" id="clasificacion" style="width:250px">
                    <option value="1">OBLIGATORIO B&Aacute;SICO</option>
                    <option value="2">OBLIGATORIO COMPLEMENTARIO</option>
                    <option value="3">ELECTIVO INTR&Iacute;NSECO</option>
                    <option value="4">ELECTIVO EXTR&Iacute;NSECO</option>
                </select>
            </td>
        </tr>        
        <tr class="cuadro_plano centrar">
            <td>
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"];?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST["planEstudio"];?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST["nombreProyecto"];?>">
                <input type="hidden" name="opcion" value="verEncabezado">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='enviar' value='Enviar' type='submit' >
            </td>
        </tr>
    </form>
        <?
        #enlace regreso  listado de planes        

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAdministrarPlanCoordinador";
        $ruta.="&opcion=registrados";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar">
            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
</table>
    <?
    }

    function ver_Encabezados($configuracion) {
        $nombreProyecto=$_REQUEST["nombreProyecto"];
        $codProyecto=$_REQUEST["codProyecto"];
        $planEstudio=$_REQUEST["planEstudio"];
$this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
        ?>
<table class='sigma' align="center" width="100%">
  <tr><th colspan="6" class="sigma_a centrar">ADMINISTRACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS CON OPCIONES<BR>PARA POSTERIOR APROBACI&Oacute;N DE VICERRECTOR&Iacute;A ACAD&Eacute;MICA</th></tr>
    <tr align="center">

    <?
          $variables=array($planEstudio, $codProyecto, $clasificacion);
            $cadena_sql_encabezados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEncabezados", $variables);//echo $cadena_sql_encabezados;exit;
            $resultado_encabezados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_encabezados,"busqueda" );

      if($resultado_encabezados==true)
       {
    
?>
</table>
            <?
       }
       else
          {
              echo "<tr class='cuadro_plano centrar'><td class='cuadro_plano centrar'><font size='2'>NO EXISTEN ENCABEZADOS CREADOS</font></td></tr>";
          }
     for($i=0; $i<count($resultado_encabezados);$i++)
            {
              foreach ($this->clasificaciones as $key=>$value)
              {
                if($this->clasificaciones[$key][0]==$resultado_encabezados[$i][8])
                {
                  $clas=$this->clasificaciones[$key][2];
                }else{}
              }

            ?>
    <table class='contenidotabla centrar'>
    <tr>
        <th class="sigma centrar" width="5%">
            C&oacute;digo
        </th>
        <th class="sigma centrar" width="50%">
            Nombre
        </th>
        <th class="sigma centrar" width="50%">
            Descripci&oacute;n
        </th>
        <th class="sigma centrar" width="50%">
            Estado
        </th>
        <th class="sigma centrar" width="50%">
            Creditos
        </th>
        <th class="sigma centrar" width="50%">
            Nivel
        </th>
        <th class="sigma centrar" width="50%">
            Clasificaci&oacute;n
        </th>
        <th class="sigma centrar" width="50%">
            Asociar Espacio
        </th>
        <th class="sigma centrar" width="50%">
            Desasociar Espacio
        </th>
        <th class="sigma centrar" width="50%">
            Editar
        </th>
        <th class="sigma centrar" width="50%">
            Borrar
        </th>
    </tr>
            <?
                 $id_encabezado=$resultado_encabezados[$i][0];
                 $nivel=$resultado_encabezados[$i][7];
                 $creditos=$resultado_encabezados[$i][6];
                ?>
    <tr class="cuadro_plano centrar">
        
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][0]; ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][1]; ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][2]; ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? if ($resultado_encabezados[$i][4]==0 or $resultado_encabezados[$i][5]==0) {
                            echo "En Proceso";
                        }
                        else if($resultado_encabezados[$i][4]==1 and $resultado_encabezados[$i][5]==1) {
                                echo "Aprobado";
                            }else
                                {
                                    echo "No Aprobado";
                                }
                        ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][6];   ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][7]; ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $clas;
                        ?>
        </td>
        <td class="cuadro_plano centrar">
                        <?

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroAsociarEspaciosCoordinador";
                        $variable.="&opcion=ver_registrados";
                        $variable.="&codProyecto=".$codProyecto;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&nombreProyecto=".$nombreProyecto;
                        $variable.="&id_encabezado=".$id_encabezado;
                        $variable.="&clasificacion=".$resultado_encabezados[$i][8];
                        $variable.="&nivel=".$nivel;
                        $variable.="&creditos=".$creditos;


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/agrupar.png" width="30" height="30" border="0" alt="Editar Requisito">
            </a>
        </td>
        <td class="cuadro_plano centrar">
                        <?
                        

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroDesasociarEspaciosCoordinador";
                        $variable.="&opcion=ver_registrados";
                        $variable.="&codProyecto=".$codProyecto;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&nombreProyecto=".$nombreProyecto;
                        $variable.="&id_encabezado=".$id_encabezado;
                        $variable.="&clasificacion=".$resultado_encabezados[$i][8];
                        $variable.="&nivel=".$nivel;
                        $variable.="&creditos=".$creditos;


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        
                        ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/favorito.png" width="30" height="30" border="0" alt="Editar Requisito">
            </a>                  
        </td>
        <td class="cuadro_plano centrar">
                        <?
                        if($resultado_encabezados[$i][4]==1 or $resultado_encabezados[$i][5]==1) {
                            if($this->nivel==61)
                            {
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=registroEditarAgruparEspaciosCoordinador";
                                $variable.="&opcion=modificar";
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&planEstudio=".$planEstudio;
                                $variable.="&nombreProyecto=".$nombreProyecto;
                                $variable.="&id_encabezado=".$id_encabezado;
                                $variable.="&clasificacion=".$resultado_encabezados[$i][8];
                                $variable.="&nivel=".$nivel;
                                $variable.="&creditos=".$creditos;


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                ?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kedit.png" width="30" height="30" border="0" alt="Editar Requisito">
                                </a>
                                      <?
                            }else{
                                echo "No<br>Permitido";
                            }
                        }
                        else if($resultado_encabezados[$i][4]==0 and $resultado_encabezados[$i][5]==0)
                        {
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroEditarAgruparEspaciosCoordinador";
                        $variable.="&opcion=modificar";
                        $variable.="&codProyecto=".$codProyecto;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&nombreProyecto=".$nombreProyecto;
                        $variable.="&id_encabezado=".$id_encabezado;
                        $variable.="&clasificacion=".$resultado_encabezados[$i][8];
                        $variable.="&nivel=".$nivel;
                        $variable.="&creditos=".$creditos;


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kedit.png" width="30" height="30" border="0" alt="Editar Requisito">
            </a>
                  <?
                     }
                  ?>
        </td>
        <td class="cuadro_plano centrar">
                        <?
                        if($resultado_encabezados[$i][4]==1 or $resultado_encabezados[$i][5]==1) {
                            if($this->nivel==61)
                            {
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=registroBorrarAgruparEspaciosCoordinador";
                                $variable.="&opcion=borrarEncabezado";
                                $variable.="&codProyecto=".$codProyecto;
                                $variable.="&planEstudio=".$planEstudio;
                                $variable.="&nombreProyecto=".$nombreProyecto;
                                $variable.="&id_encabezado=".$id_encabezado;
                                $variable.="&clasificacion=".$resultado_encabezados[$i][8];
                                $variable.="&nivel=".$nivel;
                                $variable.="&creditos=".$creditos;


                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                ?>
                                <a href="<?= $pagina.$variable ?>" >
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="30" height="30" border="0" alt="Borrar Requisito">
                                </a>
                                   <?
                            }else{
                                echo "No<br>Permitido";
                            }
                        }
                        else if($resultado_encabezados[$i][4]==0 and $resultado_encabezados[$i][5]==0)
                        {
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroBorrarAgruparEspaciosCoordinador";
                        $variable.="&opcion=borrarEncabezado";
                        $variable.="&codProyecto=".$codProyecto;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&nombreProyecto=".$nombreProyecto;
                        $variable.="&id_encabezado=".$id_encabezado;
                        $variable.="&clasificacion=".$resultado_encabezados[$i][8];
                        $variable.="&nivel=".$nivel;
                        $variable.="&creditos=".$creditos;


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="30" height="30" border="0" alt="Borrar Requisito">
            </a>
               <?
                     }
                  ?>
        </td>
    </tr>
       <?
         $cadena_sql_espaciosAsociados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioAsociados", $id_encabezado);//echo $cadena_sql_espaciosAsociados;exit;
         $resultado_espaciosAsociados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espaciosAsociados,"busqueda" );
           if($resultado_espaciosAsociados==true)
           {
           ?>
     <tr class="cuadro_color centrar">
<td class="sigma_a" colspan="11" align="center"><b>ESPACIOS ACAD&Eacute;MICOS ASOCIADOS</b></td>
        </td>
    </tr>
    <tr>
        <th class="sigma centrar" colspan="2">
            C&oacute;digo
        </th>
        <th class="sigma centrar" colspan="3">
            Nombre
        </th>
        <th class="sigma centrar" colspan="2">
            H.T.D.
        </th>
        <th class="sigma centrar" colspan="2">
            H.T.C
        </th>
        <th class="sigma centrar" colspan="2">
            H.T.A
        </th>
    </tr>
           <?
             for($j=0; $j<count($resultado_espaciosAsociados); $j++)
            {
        ?>
    <tr>
        <td class="cuadro_plano centrar" colspan="2">
            <? echo $resultado_espaciosAsociados[$j][0];?>
        </td>
        <td class="cuadro_plano centrar" colspan="3">
            <? echo $resultado_espaciosAsociados[$j][1];?>
        </td>
        <td class="cuadro_plano centrar" colspan="2">
            <? echo $resultado_espaciosAsociados[$j][2];?>
        </td>
        <td class="cuadro_plano centrar" colspan="2">
            <? echo $resultado_espaciosAsociados[$j][3];?>
        </td>
        <td class="cuadro_plano centrar" colspan="2">
            <? echo $resultado_espaciosAsociados[$j][4];?>
        </td>
    </tr>
        <?
            }
           }
           else
           {
           ?>
       <tr class="cuadro_plano centrar">
        <td class="sigma_a" colspan="11" align="center">
            <b>NO TIENE ESPACIOS ACAD&Eacute;MICOS ASOCIADOS</b>
        </td>
       </tr>
        <?
           }
        ?>
       <tr>
        <td align="center" colspan="11">
          <hr><br>
        </td>
    </tr>
    
</table>
    <?
      }
    }

    function verEspaciosxEstado($configuracion) {
        $nombreProyecto=$_REQUEST["nombreProyecto"];
        $codProyecto=$_REQUEST["codProyecto"];
        $planEstudio=$_REQUEST["planEstudio"];
        $id_encabezado=$_REQUEST["id_encabezado"];
        $clasificacion=$_REQUEST["clasificacion"];
        $nivel=$_REQUEST["nivel"];
        $creditos=$_REQUEST["creditos"];

        // Consulta par ver todos los niveles de la carrera
        // $cadena_sql_niveles=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarNivel", $planEstudio);
        // $resultado_niveles=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_niveles,"busqueda" );
        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo
                         $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png
                 " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>ADMINISTRACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA
                POSTERIOR APROBACI&Oacute;N DE VICERRECTORIA ACAD&Eacute;MICA</h4>
            <hr noshade class="hr">
        </td>
    </tr>
    <?
        #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAgruparEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar" colspan="9">
            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            Plan de Estudios:<br> <? echo $planEstudio." - ".$_REQUEST["nombreProyecto"];?><br><br>
            Seleccione los Espacios Acad&eacute;micos a agrupar
        </td>
    </tr><br><br>
            <?
            //ciclo para ver todos los niveles
            //for($j=0; $j<count($resultado_niveles); $j++) {
            //   echo "<tr class='cuadro_plano centrar'><td class='centrar' colspan='10'>Nivel ".$resultado_niveles[$j][0]." <td><tr>";
            //     $variables=array($planEstudio, $clasificacion, $resultado_niveles[$j][0]);
            
            echo "<tr class='cuadro_plano centrar'><td class='cuadro_color centrar' colspan='9'>Nivel ".$nivel." <td><tr>";
            $variables=array($planEstudio, $clasificacion, $nivel, $creditos);
            $cadena_sql_espacios=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspaciosxEstado", $variables);
            $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacios,"busqueda" );

            ?>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            id
        </td>
        <td class="cuadro_color centrar">
            Nombre
        </td>
        <td class="cuadro_color centrar">
            Cr&eacute;ditos
        </td>        
        <td class="cuadro_color centrar">
            HTD
        </td>
        <td class="cuadro_color centrar">
            HTC
        </td>
        <td class="cuadro_color centrar">
            HTA
        </td>
        <td class="cuadro_color centrar">
            Estado 1
        </td>
        <td class="cuadro_color centrar">
            Estado 2
        </td>
        <td class="cuadro_color centrar">
            
        </td>
    </tr>    
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain'
          method='GET,POST' action='index.php' name='<?echo
                  $this->formulario?>'>
          <?
             for($i=0; $i<count($resultado_espacios);$i++) {

            $variables=array($planEstudio, $codProyecto, $resultado_espacios[$i][0], $id_encabezado);
            $cadena_sql_espacioGrupo=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioGrupo", $variables);
            $resultado_espacioGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacioGrupo,"busqueda" );

            if($resultado_espacioGrupo==true)
            {
          ?>
                    <tr class="cuadro_plano centrar">
            <td>
                            <? echo $resultado_espacios[$i][0]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][1]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][2]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][5]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][6]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][7]; ?>
            </td>
            <td>
                            <? if ($resultado_espacios[$i][8]==0) {
                                echo "Inactivo";
                            }
                            else if($resultado_espacios[$i][8]==1) {
                                    echo "Activo";
                                }
                            ?>
            </td>
            <td>
                            <? if ($resultado_espacios[$i][9]==0 or $resultado_espacios[$i][10]==0) {
                                echo "En proceso";
                            }
                            else if($resultado_espacios[$i][9]==1 and $resultado_espacios[$i][10]==1) {
                                    echo "Aprobado";
                                }
                            ?>
            </td>

            <td>
                <? echo "Este Espacio<br>";
                   echo "es una opci&oacute;n<br>seleccionada";
                ?>
            </td>
        </tr>
          <?
            }
            else
            {
           ?>
                               <tr class="cuadro_plano centrar">
            <td>
                            <? echo $resultado_espacios[$i][0]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][1]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][2]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][5]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][6]; ?>
            </td>
            <td>
                            <? echo $resultado_espacios[$i][7]; ?>
            </td>
            <td>
                            <? if ($resultado_espacios[$i][8]==0) {
                                echo "Inactivo";
                            }
                            else if($resultado_espacios[$i][8]==1) {
                                    echo "Activo";
                                }
                            ?>
            </td>
            <td>
                            <? if ($resultado_espacios[$i][9]==0 or $resultado_espacios[$i][10]==0) {
                                echo "En proceso";
                            }
                            else if($resultado_espacios[$i][9]==1 and $resultado_espacios[$i][10]==1) {
                                    echo "Aprobado";
                                }
                            ?>
            </td>
            <td>
                <input type=checkbox name="<? echo "codEspacio".$i;?>" value="<?echo $resultado_espacios[$i][0];?>" >
            </td>
        </tr>
          <?
                   }
           }
                // cierra for para ver todos los niveles
                // }
                ?>
        <tr align="center">
        <hr noshade class="hr">
        </tr>
        </table>
        <table class='contenidotabla centrar'>

            <tr class="centrar">
                <td>
                    <input type="hidden" name="nombreProyecto" value="<? echo $nombreProyecto; ?>">
                    <input type="hidden" name="planEstudio" value="<? echo $planEstudio; ?>">
                    <input type="hidden" name="codProyecto" value="<? echo $codProyecto; ?>">
                    <input type="hidden" name="id_encabezado" value="<? echo $id_encabezado; ?>">
                    <input type="hidden" name="clasificacion" value="<? echo $clasificacion; ?>">
                    <input type="hidden" name="nivel" value="<? echo $nivel; ?>">
                    <input type="hidden" name="creditos" value="<? echo $creditos; ?>">
                    <input type="hidden" name="opcion" value="agrupar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input name='agrupar' value='Agrupar' type='submit' >
                </td>
            </tr>


        </table>
    </form>

        <?
        }

        function agruparEspacios($configuracion)
        {
          //var_dump($_REQUEST);exit;
          $usuario=$this->usuario;
          $planEstudio=$_REQUEST["planEstudio"];
          $codProyecto=$_REQUEST["codProyecto"];
          $id_encabezado=$_REQUEST["id_encabezado"];
          $band=0;
         for($i=0;$i<=100;$i++)
            { 
              if($_REQUEST['codEspacio'.$i])
                {$band=1;
                 $codEspacio=$_REQUEST['codEspacio'.$i];
                 $variables=array($planEstudio, $codProyecto, $codEspacio, $id_encabezado);
                 $cadena_sql_agruparEspacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"agruparEspacio", $variables);
                 $resultado_agruparEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_agruparEspacio,"" );

                 $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                 $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                 $ano=$resultadoPeriodo[0][0];
                 $periodo=$resultadoPeriodo[0][1];

                 $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $id_encabezado, $planEstudio, $codProyecto );
                 $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroAgrupar",$variablesRegistro);
                 $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");
                }
           }
           if($band==1)
                { 
                   echo "<script>alert ('Las agrupacion de los Espacios Académicos ha sido exitosa');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAgruparEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   
                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else 
                { 
                   echo "<script>alert ('Debe seleccionar por lo menos un Espacio Académico');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAgruparEspaciosCoordinador";
		   $variable.="&opcion=ver_registrados";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&id_encabezado=".$_REQUEST["id_encabezado"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   $variable.="&nivel=".$_REQUEST["nivel"];
                   $variable.="&creditos=".$_REQUEST["creditos"];
                   

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }       
        }

  function crearEncabezado($configuracion) {
      
       $nombreProyecto=$_REQUEST["nombreProyecto"];
       $codProyecto=$_REQUEST["codProyecto"];
       $planEstudio=$_REQUEST["planEstudio"];       
       $clasificacion=$_REQUEST["clasificacion"];

       $cadena_sql_nombrePlan=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombrePlanEstudio", $planEstudio);
       $resultado_nombrePlan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_nombrePlan,"busqueda" );
       
        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo
                         $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png
                 " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>CREACI&Oacute;N DE GRUPOS DE OPCIONES DE ESPACIOS ACAD&Eacute;MICOS</h4>
            <hr noshade class="hr">
        </td>
    </tr>
    <?
        #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAgruparEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar" colspan="9">
            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            Grupos con clasificaci&oacute;n: <br>
        </td>
    </tr>     
    <tr align="center">
        <td class="cuadro_color centrar" colspan="9">
            -
        </td>
    </tr>
</table>
   <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain'
          method='GET,POST' action='index.php' name='<?echo
                  $this->formulario?>'>
    <table class='contenidotabla centrar'>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
           Plan de Estudios:
        </td>
        <td class="cuadro_plano">
            <? echo $planEstudio." - ".$resultado_nombrePlan[0][0];?>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
           Proyecto Curricular:
        </td>
        <td class="cuadro_plano">
            <? echo $codProyecto." - ".$nombreProyecto;?>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Clasificaci&oacute;n:
        </td>
        <td class="cuadro_plano">
            <?
                switch ($clasificacion)
                    {
                    case "1":
                        echo "OBLIGATORIO B&Aacute;SICO";
                        break;
                    case "2":
                        echo "OBLIGATORIO COMPLEMENTARIO";
                        break;
                    case "3":
                        echo "ELECTIVO INTR&Iacute;NSECO";
                        break;
                    case "4":
                        echo "ELECTIVO EXTR&Iacute;NSECO";
                        break;
                    }
                    ?>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
           Nombre del grupo de opciones:
        </td>
        <td class="cuadro_plano">
            <input type="text" name="encabezadoNombre">
        </td>  
    </tr>
     <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Descripci&oacute;n del grupo de opciones:
        </td>
        <td class="cuadro_plano">
            <textarea name="encabezadoDescripcion"></textarea>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Cr&eacute;ditos del grupo:
        </td>
        <td class="cuadro_plano">
            <select name="encabezadoCreditos" id="encabezadoCreditos" style="width:50px">
                         <?
                            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarCreditos", $planEstudio);
                            
                            $resultadoCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda" );

                            for($i=0;$i<count($resultadoCreditos);$i++) {
                                ?>
                    <option value="<?echo $resultadoCreditos[$i][0]?>"><?echo $resultadoCreditos[$i][0]?></option>
                                <?
                            }
                            ?>
           </select>
        </td>       
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Nivel del grupo:
        </td>
        <td class="cuadro_plano">
            <select name="encabezadoNivel" id="encabezadoNivel" style="width:50px">
                            <?

                            $cadena_sql_nivel=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarNivel", $planEstudio);
                            $resultadoNivel=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_nivel,"busqueda" );

                            for($i=0;$i<count($resultadoNivel);$i++) {
                                ?>
                    <option value="<?echo $resultadoNivel[$i][0]?>"><?echo $resultadoNivel[$i][0]?></option>
                                <?
                            }
                            ?>
           </select>
        </td>
    </tr>
    <tr class="centrar">
        <td class="cuadro_plano centrar" colspan="2">
                    <input type="hidden" name="nombreProyecto" value="<? echo $nombreProyecto; ?>">
                    <input type="hidden" name="planEstudio" value="<? echo $planEstudio; ?>">
                    <input type="hidden" name="codProyecto" value="<? echo $codProyecto; ?>">                    
                    <input type="hidden" name="clasificacion" value="<? echo $clasificacion; ?>">                    
                    <input type="hidden" name="opcion" value="generar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input name='crear' value='Crear' type='submit' >
                           
        <?      #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAgruparEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        
        ?>       
            <a href="<?= $indice.$ruta ?>">
                <input src="<?echo $configuracion['site'].$configuracion['grafico']?>" name='crear' value='Cancelar' type='submit' >
               
            </a>
        </td>
        </tr>


        </table>
    </form>

        <?
        }

          function generarEncabezado($configuracion)
        {
          //var_dump($_REQUEST);exit;
          $usuario=$this->usuario;
          $planEstudio=$_REQUEST["planEstudio"];
          $codProyecto=$_REQUEST["codProyecto"];          
          $nombreProyecto=$_REQUEST["nombreProyecto"];
          $clasificacion=$_REQUEST["clasificacion"];
          $encabezadoNombre=$_REQUEST["encabezadoNombre"];
          $encabezadoDescripcion=$_REQUEST["encabezadoDescripcion"];
          $encabezadoCreditos=$_REQUEST["encabezadoCreditos"];
          $encabezadoNivel=$_REQUEST["encabezadoNivel"];
               
          if($_REQUEST['encabezadoNombre'] and $_REQUEST["encabezadoDescripcion"] and $_REQUEST["encabezadoCreditos"] and $_REQUEST["encabezadoNivel"])
                {$band=1;                 
                 
                }
           
           if($band==1)
                {                 
                 $variables=array($encabezadoNombre, $encabezadoDescripcion, $planEstudio, $codProyecto, $clasificacion, $encabezadoCreditos, $encabezadoNivel);
                 $cadena_sql_crearEncabezado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"crearEncabezado", $variables);
                 $resultadoCrearEncabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEncabezado,"" );
                   
                 $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');                 
                 $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                 $ano=$resultadoPeriodo[0][0];
                 $periodo=$resultadoPeriodo[0][1];

                 $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $encabezadoNombre, $planEstudio, $codProyecto );
                 $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroNombreGeneral",$variablesRegistro);
                 $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                   echo "<script>alert ('El Encabezado de Espacios Académicos ha sido registrado de forma exitosa');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAgruparEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else
                {
                   echo "<script>alert ('Debe utilizar todos los campos');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAgruparEspaciosCoordinador";
		   $variable.="&opcion=crear";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];


                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
        }

    }

?>