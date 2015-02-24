
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_admin_actualizacionElectivas extends funcionGeneral
{
    
 	//@ Método costructor que crea el objeto sql de la clase sql_noticia
	function __construct($configuracion)
            {
	    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
	    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
	    include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	    $this->cripto=new encriptar();
	    $this->tema=$tema;
	    $this->sql=new sql_admin_actualizacionElectivas();
	    $this->log_us= new log();


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle produccion
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

            $this->formulario="admin_actualizacionElectivas";
	    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links  
	    $obj_sesion=new sesiones($configuracion);
	    $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
	    $this->id_accesoSesion=$this->resultadoSesion[0][0];	
	    		
	}

        function actualizarElectivas($configuracion)
        {

            $cadena_sql=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",'');//echo $cadena_sql;exit;
            $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $cargados=0;
            $noCargados=0;
            $noActualizados=0;

            for($i=0;$i<count($resultado_proyectos);$i++)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_academicosPlan",$resultado_proyectos[$i][2]);//echo $cadena_sql;exit;
                $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                for($j=0;$j<count($resultado_espacios);$j++)
                {
                    $variable=array($resultado_espacios[$j][1],$resultado_espacios[$j][0]);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_cargado",$variable);//echo "<br>".$cadena_sql;exit;
                    $resultado_cargado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    if(is_array($resultado_cargado))
                    {
//                        $nombreEspacio=strtr(strtoupper($resultado_espacios[$j][2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
//
//                        $variable[2]=$nombreEspacio;
//
//                        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_nombreElectiva",$variable);//echo $cadena_sql;//exit;
//                        $resultado_nombreActualizado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_espacioElectiva",$variable);//echo $cadena_sql;exit;
                        $resultado_actualizado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                        $registro_actualizado=$this->totalAfectados($configuracion, $this->accesoOracle);

                        if($registro_actualizado>=1)
                            {
                                $espaciosCargados[$cargados][0]=$resultado_cargado[0][2];
                                $espaciosCargados[$cargados][1]=$resultado_cargado[0][1];
                                $espaciosCargados[$cargados][2]=$resultado_cargado[0][3];

                                $cargados++;
                            }else
                                {
                                    $espaciosNoActualizados[$noActualizados][0]=$resultado_cargado[0][2];
                                    $espaciosNoActualizados[$noActualizados][1]=$resultado_cargado[0][1];
                                    $espaciosNoActualizados[$noActualizados][2]=$resultado_cargado[0][3];

                                    $noActualizados++;
                                }

                    }else
                        {
                            $espaciosNoCargados[$noCargados][0]=$resultado_espacios[$j][0];
                            $espaciosNoCargados[$noCargados][1]=$resultado_espacios[$j][1];
                            $espaciosNoCargados[$noCargados][2]=$resultado_espacios[$j][2];

                            $noCargados++;
                        }
                }
            }

            ?>
<table class="contenidotabla">
    <tr>
        <td colspan="5" class="cuadro_brownOscuro centrar">
            REPORTE DE ACTUALIZACI&Oacute;N
        </td>
    </tr>
    <tr>
        <td colspan="5" class="cuadro_brownOscuro centrar">
            ESPACIOS ACAD&Eacute;MICOS ACTUALIZADOS
        </td>
    </tr>
    <tr>
        <td class="cuadro_brownOscuro centrar">
            PLAN DE ESTUDIO
        </td>
        <td class="cuadro_brownOscuro centrar">
            C&Oacute;DIGO DE ESPACIO
        </td>
        <td class="cuadro_brownOscuro centrar">
            NOMBRE ESPACIO
        </td>
    </tr>
    <?
        for($q=0;$q<=$cargados;$q++)
        {
            ?>
    <tr>
        <td>
            <?echo $espaciosCargados[$q][0]?>
        </td>
        <td>
            <?echo $espaciosCargados[$q][1]?>
        </td>
        <td>
            <?echo htmlentities($espaciosCargados[$q][2])?>
        </td>
    </tr>
            <?
        }
    ?>
    <tr>
        <td colspan="5" class="cuadro_brownOscuro centrar">
            ESPACIOS ACAD&Eacute;MICOS NO ACTUALIZADOS
        </td>
    </tr>
    <tr>
        <td class="cuadro_brownOscuro centrar">
            PLAN DE ESTUDIO
        </td>
        <td class="cuadro_brownOscuro centrar">
            C&Oacute;DIGO DE ESPACIO
        </td>
        <td class="cuadro_brownOscuro centrar">
            NOMBRE ESPACIO
        </td>
    </tr>
    <?
        for($w=0;$w<=$noActualizados;$w++)
        {
            ?>
    <tr>
        <td>
            <?echo $espaciosNoActualizados[$w][0]?>
        </td>
        <td>
            <?echo $espaciosNoActualizados[$w][1]?>
        </td>
        <td>
            <?echo $espaciosNoActualizados[$w][2]?>
        </td>
    </tr>
            <?
        }
   
    ?>
    <tr>
        <td colspan="5" class="cuadro_brownOscuro centrar">
            ESPACIOS ACAD&Eacute;MICOS NO CARGADOS EN PRODUCCI&Oacute;N
        </td>
    </tr>
    <tr>
        <td class="cuadro_brownOscuro centrar">
            PLAN DE ESTUDIO
        </td>
        <td class="cuadro_brownOscuro centrar">
            C&Oacute;DIGO DE ESPACIO
        </td>
        <td class="cuadro_brownOscuro centrar">
            NOMBRE ESPACIO
        </td>
    </tr>
    <?
        for($e=0;$e<=$noCargados;$e++)
        {
            ?>
    <tr>
        <td>
            <?echo $espaciosNoCargados[$e][0]?>
        </td>
        <td>
            <?echo $espaciosNoCargados[$e][1]?>
        </td>
        <td>
            <?echo $espaciosNoCargados[$e][2]?>
        </td>
    </tr>
            <?
        }

        }
}
?>
