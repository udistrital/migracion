
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_adminConsultarIncritosEspacioPorFacultadAsisVice extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminConsultarIncritosEspacioPorFacultadAsisVice();
        $this->log_us= new log();
        $this->formulario="adminConsultarIncritosEspacioPorFacultadAsisVice";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");




    }#Cierre de constructor

    //inicia lista de planes de estudios
    function facultad($configuracion) {

        ?>
<table width="90%" border="0" align="center"  cellpadding="5" cellspacing="2">
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
        <?
                    $variable="";
                    #Consulta por planes de estudios existentes
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"listaFacultades", "");
                    $registro1=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                    if ($this->nivel==61)
                    {
                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacios", "");
                        $registro2=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                    }elseif($this->nivel==87)
                        {
                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarCatedras", "");
                            $registro2=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");                        
                        }else{}

                    $this->listaFacultad($configuracion, $registro1, $registro2);?>
        </td>
    </tr>
</table>
        <?
    }#Cierre de funcion verRegistro

    //presenta lista de facultades
    function listaFacultad($configuracion, $registro, $registroEspacio) {


        ?>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>REPORTE DE ESTUDIANTES INSCRITOS EN ESPACIOS ACAD&Eacute;MICOS</h4>
            <hr noshade class="hr">

        </td>
    </tr><br><br>
    <tr>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class="contenidotabla centrar">
        <tr class="centrar">
            <td class="cuadro_color centrar" colspan="2">
                SELECCIONE LA FACULTAD
            </td>
        </tr>
        <tr>
            <td class="centrar">
                <select id="id_facultad" name="id_facultad" >
                <?
                    for($i=0;$i<count($registro);$i++)
                    {

                        ?>
                        <option value="<?echo $registro[$i][0].":".$registro[$i][1]?>"><?echo $registro[$i][1]?></option>
                        <?

                    }
                ?>
                </select><br><br>
            </td>
        </tr>
        
        <tr class="centrar">
            <td class="cuadro_color centrar" colspan="2">
                SELECCIONE EL ESPACIO ACAD&Eacute;MICO
            </td>
        </tr>
        <tr>
            <td class="centrar">
                <select id="id_espacio" name="id_espacio" >
                <?
                    for($i=0;$i<count($registroEspacio);$i++)
                    {

                        ?>
                        <option value="<?echo $registroEspacio[$i][0].":".$registroEspacio[$i][1]?>"><?echo $registroEspacio[$i][0]."-".$registroEspacio[$i][1]?></option>
                        <?

                    }
                ?>
                </select>
            </td>
        </tr>
        <tr class="centrar">
            <td>
                <input type="hidden" name="opcion" value="consultar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png">

            </td>
        </tr>
    </table>
</form>
    </tr>
</table>
<?
    }

    #Llama las funciones "verPlanEstudios", "listaNiveles" y "listaInscritos" para visualizar
    #la informacion general del Plan de Estudios y los Espacios Academicos que lo componen agrupados por niveles
    function mostrarRegistro($configuracion) {

        list($codFacultad, $facultad) = explode(":", $_REQUEST["id_facultad"]);
        list($codEspacio, $espacio) = explode(":", $_REQUEST["id_espacio"]);
        #Consulta los Espacios Academicos del plan de estudios
        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarPeriodoActivo","");
        $periodo=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
        $ano=$periodo[0][0];
        $per=$periodo[0][1];
        $consulta=array($codFacultad,$codEspacio,$ano,$per);
        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarInscritosFacultad",$consulta);
        $inscritosEspacio=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
        $totalInscritos=$this->accesoOracle->obtener_conteo_db($inscritosEspacio);

        if(is_array($inscritosEspacio)&&$totalInscritos>0) {
            #Muestra los niveles de un plan de estudios
            $this->listaInscritos($configuracion,$inscritosEspacio,$totalInscritos, $facultad, $espacio, $consulta);
        }else {
            echo "<table class='contenidotabla'>
                        <tr>
                            <td class='cuadro_plano centrar'><h2>
                            No hay estudiantes inscritos en la ".$facultad." en ".$espacio."
                            </h2></td>
                            </tr>
                            </table>";
        }


    }

   #Muestra los niveles existentes para el Plan de Estudios
    function listaInscritos($configuracion, $registroInscritos, $totalInscritosEsp, $facultad, $espacio, $valores) {
$b=1;
        ?>

        <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
            <tr class="cuadro_plano">
                <td  align="center">
                    <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
                        <tbody>
                            <tr>
                                <td>
                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <tr><td colspan='10' align='center'><h2>ESTUDIANTES INSCRITOS EN <?echo $espacio?><br>
                                                    EN LA <?echo $facultad?></h2></td></tr>
                                                    <?
                                                for($a=0; $a<$totalInscritosEsp; $a++)
                                                {
                                                    if($registroInscritos[$a][5]!=(isset($registroInscritos[$a-1][5])?$registroInscritos[$a-1][5]:'')){
                                                    ?>
                                                    <tr><td colspan="5"><br></td></tr>
                                                    <tr class="cuadro_color">
                                                        <td class='cuadro_plano centrar' colspan="2">Grupo</td>
                                                        <td class='cuadro_plano centrar' colspan="2">Proyecto que lo ofrece</td>
                                                        <td class='cuadro_plano centrar'>Cupo: <?echo $registroInscritos[$a][8]?></td>
                                                    </tr>
                                                    <tr class="cuadro_color">
                                                        <td class='cuadro_plano centrar' colspan="2"><?echo $registroInscritos[$a][5];?></td>
                                                        <td class='cuadro_plano centrar' colspan="2"><?echo $registroInscritos[$a][7];?></td>
                                                        <td class='cuadro_plano centrar'>Inscritos: <?echo $registroInscritos[$a][9];?></td>
                                                    </tr>
                                                    <?
                                                    $curso=array($registroInscritos[$a][6], $registroInscritos[$a][10]);
                                                    $datos=array_merge($valores, $curso);
                                                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaDocenteGrupo",$datos);
                                                    $docentesGrupo=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                                                    $b=1;
                                                    ?>
                                                    <tr>
                                                        <td class='cuadro_plano centrar' colspan="2">Docente(s):</td>
                                                        <td class='cuadro_plano centrar' colspan="3">
                                                    <?
                                                    if(is_array($docentesGrupo))
                                                    {
                                                        ?><font color=blue><?
                                                        for($d=0; $d<count($docentesGrupo); $d++)
                                                        {
                                                            echo $docentesGrupo[$d][0]." ".$docentesGrupo[$d][1]."<br>";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?><font color=red><?
                                                        echo "No hay docentes asignados al grupo";
                                                    }
                                                    ?>
                                                            </font>
                                                        </td>
                                                    </tr>
                                                    <tr class="cuadro_color">
                                                        <td class='cuadro_plano centrar'>Nro</td>
                                                        <td class='cuadro_plano centrar'>Cod. Estudiante</td>
                                                        <td class='cuadro_plano centrar' width="250">Nombre</td>
                                                        <td class='cuadro_plano centrar'>Estado</td>
                                                        <td class='cuadro_plano centrar'>Proyecto Curricular del Estudiante</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='cuadro_plano centrar'><?echo $b;?></td>
                                                        <td class='cuadro_plano centrar'><?echo $registroInscritos[$a][1];?></td>
                                                        <td class='cuadro_plano centrar'><?echo $registroInscritos[$a][2];?></td>
                                                        <?
                                                        switch($registroInscritos[$a][3])
                                                        {
                                                        case "A":
                                                            $estado="Activo";
                                                            break;

                                                        case "B":
                                                            $estado="Prueba y Activo";
                                                            break;

                                                        case "J":
                                                            $estado="Prueba y Vacaciones";
                                                            break;

                                                        case "V":
                                                            $estado="Vacaciones";
                                                            break;

                                                        }
                                                        $b++;
                                                        ?>
                                                        <td class='cuadro_plano centrar'><?echo (isset($estado)?$estado:'');?>
                                                        <td class='cuadro_plano centrar'><?echo $registroInscritos[$a][4];?>
                                                    </tr>

                                                    <?
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                        <tr>
                                                            <td class='cuadro_plano centrar'><?echo $b;?>
                                                            <td class='cuadro_plano centrar'><?echo $registroInscritos[$a][1];?>
                                                            <td class='cuadro_plano centrar'><?echo $registroInscritos[$a][2];?>
                                                        <?
                                                        switch($registroInscritos[$a][3])
                                                        {
                                                        case "A":
                                                            $estado="Activo";
                                                            break;

                                                        case "B":
                                                            $estado="Prueba";
                                                            break;

                                                        case "J":
                                                            $estado="Prueba y Vacaciones";
                                                            break;

                                                        case "V":
                                                            $estado="Vacaciones";
                                                            break;

                                                        }
                                                        $b++;
                                                        ?>
                                                        <td class='cuadro_plano centrar'><?echo (isset($estado)?$estado:'');?>
                                                            <td class='cuadro_plano centrar'><?echo $registroInscritos[$a][4];?>
                                                        </tr>

                                                    <?
                                                    }
                                                }
?>
                                                        <tr><td colspan='10' align='center'><h2>TOTAL DE ESTUDIANTES INSCRITOS:<?echo $a?></h2></td></tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>



        <?

    }
    



}
?>
