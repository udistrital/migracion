<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCambiarGrupoCIGrupoCoordinador extends funcionGeneral
{

    function __construct($configuracion, $sql) {

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
        $this->formulario="registroCambiarGrupoCIGrupoCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }


    function buscarGrupo($configuracion)
    {
        $codEstudiante=$_REQUEST['codEstudiante'];
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nroGrupo=$_REQUEST['nroGrupo'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nombreEstudiante=$_REQUEST['nombreEstudiante'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);//echo $cadena_sql;exit;
        $resultado_craCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $codProyecto=$resultado_craCoordinador[0][1];
        $variables=array($codEspacio,$codProyecto,$planEstudio,$nroGrupo);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);//echo $cadena_sql;exit;
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        ?>
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="33%">
                    <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$codEspacio;
                    $variable.="&nroGrupo=".$nroGrupo;
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&codProyecto=".$resultado_craCoordinador[0][1];
                    $variable.="&nombreEspacio=".$nombreEspacio;
                    
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br><b>Atras</b>
            </a>

        <td class="centrar" width="33%">
                    <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminCIGrupoCoordinador";
                    $variable.="&opcion=consultar";
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
            </a>
        </td>
        <td class="centrar" width="33%">
             <a href="javascript:history.forward();" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0"><br><b>Adelante</b>
            </a>
        </td>
    </tr>
</table>
        <?

        if(is_array($resultado_grupos)) {
            ?>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                        <tr>
                            <td><center><?echo $codEspacio." - ".$nombreEspacio;?></center></td>
                        </tr>
                        <tr>
                            <td><center><?echo $codEstudiante." - ".htmlentities($nombreEstudiante);?></center></td>
                        </tr>
                    </thead>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo </td>
                                <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                </thead>

                                            <?


                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[3]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codEstudiante;
                                                $variableCodigo[1]=$codEspacio;

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);//echo $cadena_sql;exit;
                                                $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                unset($cruce);
//echo $resultado_horarios_registrar[0]."-".$resultado_horarios_registrado[0];
                                                for($n=0;$n<count($resultado_horarios_registrado);$n++) {

                                                                for($m=0;$m<count($resultado_horarios_registrar);$m++) {
                                                                    
                                                                    if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {
                                                                       // echo "Hola";
                                                                        $cruce=true;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                $cupoDisponible=($resultado_horarios[$j][4]-$resultado_horarios[$j][5]);

                                                $variableCupo=array($codEstudiante,$resultado_grupos[$j][0],$codEspacio);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);

                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                                            for ($k=0;$k<count($resultado_horarios);$k++) {

                                                                if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {
                                                                    $l=$k;
                                                                    while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {

                                                                        $m=$k;
                                                                        $m++;
                                                                        $k++;
                                                                    }
                                                                    $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0]) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                    $k++;
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3])) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]!=$i) {

                                                                }
                                                            }
                                                            ?></td><?
                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                    <td class='cuadro_plano centrar'>

                                                        <?


                                         if($cruce!=true){
                                                            ?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="nroGrupoAnt" value="<?echo $nroGrupo?>">
                                            <input type="hidden" name="nroGrupoNue" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codEstudiante?>">
                                            <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                                            <input type="hidden" name="opcion" value="cambiar">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="cambiar" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" >

                                        </form>
                                    </td>

                                                    <?
                                                    }
                                                    else
                                                        {
                                                        ?>No puede adicionar por cruce</td><?
                                                        }



                                            }
                                ?>
                </table>
            </td>

        </tr>

</table>
</td>
</tr>
        <?
        }else {
            ?>
<tr>
    <td class="cuadro_plano centrar">
        No existen grupos registrados
    </td>
</tr>
        <?
        }
        ?>
</tbody>
</table>

    <?


    }

    function cambiarGrupoEstudiante($configuracion)
    {
        $variable['codEspacio']=$_REQUEST['codEspacio'];
        $variable['planEstudio']=$_REQUEST['planEstudio'];
        $variable['codProyecto']=$_REQUEST['codProyecto'];
        $variable['codEstudiante']=$_REQUEST['codEstudiante'];
        $variable['nroGrupoNue']=$_REQUEST['nroGrupoNue'];
        $variable['nroGrupoAnt']=$_REQUEST['nroGrupoAnt'];
        $variable['nroCreditos']=$_REQUEST['nroCreditos'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoOracle", $variable);
        $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

        if($resultado_actGrupo)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", $variable);//echo $cadena_sql;exit;
                $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $variable['anno']=$resultadoPeriodo[0][0];
                $variable['periodo']=$resultadoPeriodo[0][1];

                $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoMySQL", $variable);//echo $cadena_sql;exit;
                $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                $variablesRegistro[0]=$this->usuario;
                $variablesRegistro[1]=date('YmdGis');
                $variablesRegistro[2]='3';
                $variablesRegistro[3]='Cambio grupo del Espacio Académico';
                $variablesRegistro[4]=$variable['anno']."-".$variable['periodo'].",".$variable['codEspacio'].",".$variable['nroGrupoAnt'].",".$variable['nroGrupoNue'].",".$variable['planEstudio'].",".$variable['codProyecto'];
                $variablesRegistro[5]=$variable['codEstudiante'];

                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                $variablesInscritosAnt=array($variable['codEspacio'],$variable['nroGrupoAnt']);

                $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosAnt);
                $resultado_InscritosAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $variablesInscritosAnt[2]=$resultado_InscritosAnt[0][0];
                $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosAnt);
                $resultado_ActualizacionAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                //Actualiza el cupo del nuevo grupo de los estudiantes
                $variablesInscritosNue=array($variable['codEspacio'],$variable['nroGrupoNue']);
                $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosNue);
                $resultado_InscritosNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $variablesInscritosNue[2]=$resultado_InscritosNue[0][0];
                $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosNue);
                $resultado_ActualizacionNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                echo "<script>alert ('El cambio de grupo para el estudiante ".$variable['codEstudiante']." se ejecuto exitosamente. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=adminConsultarCIGrupoCoordinador";
                $ruta.="&opcion=verGrupo";
                $ruta.="&opcion2=cuadroRegistro";
                $ruta.="&codEspacio=".$variable['codEspacio'];
                $ruta.="&nroGrupo=".$variable['nroGrupoAnt'];
                $ruta.="&planEstudio=".$variable['planEstudio'];
                $ruta.="&codProyecto=".$variable['codProyecto'];
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }else
                {
                    echo "<script>alert ('El cambio de grupo para el estudiante ".$variable['codEstudiante']." no se pudo ejecutar');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarCIGrupoCoordinador";
                    $ruta.="&opcion=verGrupo";
                    $ruta.="&opcion2=cuadroRegistro";
                    $ruta.="&codEspacio=".$variable['codEspacio'];
                    $ruta.="&nroGrupo=".$variable['nroGrupoAnt'];
                    $ruta.="&planEstudio=".$variable['planEstudio'];
                    $ruta.="&codProyecto=".$variable['codProyecto'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                    echo "<script>location.replace('".$pagina.$ruta."')</script>";
                    exit;
                }
    }

    function buscarGrupoVariosEstudiantes($configuracion)
    {
        
        $totalSeleccionados=0;
        $total=$_REQUEST['total'];
        
            for($i=0;$i<$total;$i++)
            {
                $codigo['codEstudiante-'.$i]=$_REQUEST['codEstudiante-'.$i];
                if($codigo['codEstudiante-'.$i]!=NULL)
                    {
                        $totalSeleccionados++;
                    }
            }

        
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nroGrupo=$_REQUEST['nroGrupo'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nombreEstudiante=$_REQUEST['nombreEstudiante'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);//echo $cadena_sql;exit;
        $resultado_craCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if($totalSeleccionados==0)
            {
                echo "<script>alert('Por favor seleccione los estudiantes a los que desea cambiar de grupo')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarCIGrupoCoordinador";
                $variable.="&opcion=verGrupo";
                $variable.="&opcion2=cuadroRegistro";
                $variable.="&codEspacio=".$codEspacio;
                $variable.="&nroGrupo=".$nroGrupo;
                $variable.="&planEstudio=".$planEstudio;
                $variable.="&codProyecto=".$resultado_craCoordinador[0][1];
                $variable.="&nombreEspacio=".$nombreEspacio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }

        $codProyecto=$resultado_craCoordinador[0][1];

        $variables=array($codEspacio,$codProyecto,$planEstudio,$nroGrupo);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);//echo $cadena_sql;exit;
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        ?>
<style type="text/css">
#toolTipBox {
       display: none;
       padding: 5;
       font-size: 12px;
       border: black solid 1px;
       font-family: verdana;
       position: absolute;
       background-color: #ffd038;
       color: 000000;
}
</style> 
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">
        
        <td class="centrar" width="33%">
                    <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$codEspacio;
                    $variable.="&nroGrupo=".$nroGrupo;
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&codProyecto=".$resultado_craCoordinador[0][1];
                    $variable.="&nombreEspacio=".$nombreEspacio;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
            </a>
        </td>
        
    </tr>
</table>
        <?

        if(is_array($resultado_grupos)) {
            ?>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                        <tr>
                            <td><center><?echo $codEspacio." - ".$nombreEspacio;?></center></td>
                        </tr>
                        <tr>
                            <td><center><?echo "Estudiantes seleccionados: ".$totalSeleccionados;?></center></td>
                        </tr>
                    </thead>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo Disponible </td>
                                <td class='cuadro_plano centrar' width="30">Estudiantes con Cruce </td>
                                <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                </thead>

                                            <?


                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[3]=$resultado_grupos[$j][0];
                                               
                                                $cruce=0;

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                                                
                                                unset($estudiantesCruce);

                                                for($e=1;$e<$total;$e++)
                                                {
                                                    $band=0;
                                                    $variableCodigo=$codigo['codEstudiante-'.$e];
//var_dump($variableCodigo);exit;
                                                    if($variableCodigo[0]!=NULL)
                                                    {
                                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);//echo $cadena_sql;exit;
                                                    $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                    for($n=0;$n<count($resultado_horarios_registrado);$n++) {
                                                                    for($m=0;$m<count($resultado_horarios_registrar);$m++) {

                                                                        if($band==0){

                                                                        if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {

                                                                            $estudiantesCruce[$cruce]=$variableCodigo;
                                                                            $band=1;
                                                                            $cruce++;
                                                                            break;
                                                                        }
                                                                        }
                                                                    }
                                                                }
                                                    }
                                                }
                                                
                                                $cupoDisponible=($resultado_horarios[$j][4]-$resultado_horarios[$j][5]);

                                                $variableCupo=array($codEstudiante,$resultado_grupos[$j][0],$codEspacio);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);

                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                                            for ($k=0;$k<count($resultado_horarios);$k++) {

                                                                if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {
                                                                    $l=$k;
                                                                    while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {

                                                                        $m=$k;
                                                                        $m++;
                                                                        $k++;
                                                                    }
                                                                    $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0]) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                    $k++;
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3])) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]!=$i) {

                                                                }
                                                            }
                                                            ?></td><?
                                                        }
                                                        if($cruce>0)
                                                            {
                                                                                                                                                                                                                                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                                        <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                                                        
                                                            <td class='cuadro_plano centrar' onmouseover="toolTip('<?if($cruce>0){for($v=0;$v<=$cruce;$v++){ echo $estudiantesCruce[$v]."<br>";}}else{echo "No hay cruce";}?>',this)">
                                                                <div class="centrar">
                                                                <span id="toolTipBox" width="200" ></span>
                                                                <?echo $cruce; unset($cruce);?>
                                                                </div>
                                                            </td>
                                                        
                                    <td class='cuadro_plano centrar'>
                                                       
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            <?
                                                for($r=0;$r<$total;$r++)
                                                {
                                                    echo "<input type='hidden' name='codEstudiante-".$r."' value='".$codigo['codEstudiante-'.$r]."'> ";
                                                }
                                            ?>
                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="nroGrupoAnt" value="<?echo $nroGrupo?>">
                                            <input type="hidden" name="nroGrupoNue" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codEstudiante?>">
                                            <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                                            <input type="hidden" name="totalEstudiantes" value="<?echo $total?>">
                                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                                            <input type="hidden" name="opcion" value="cambiarVarios">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="cambiar" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" >

                                        </form>
                                    </td>
                                        <?
                                        }
                                        ?>
                </table>
            </td>

        </tr>

</table>
</td>
</tr>
        <?
        }else {
            ?>
<tr>
    <td class="cuadro_plano centrar">
        No existen grupos registrados
    </td>
</tr>
        <?
        }
        ?>
</tbody>
</table>

    <?
    }

    function cambiarGrupoVarios($configuracion)
    {
        
        $variable['codEspacio']=$_REQUEST['codEspacio'];
        $variable['planEstudio']=$_REQUEST['planEstudio'];
        $variable['codProyecto']=$_REQUEST['codProyecto'];
        $variable['nroGrupoNue']=$_REQUEST['nroGrupoNue'];
        $variable['nroGrupoAnt']=$_REQUEST['nroGrupoAnt'];
        $variable['nroCreditos']=$_REQUEST['nroCreditos'];
        $variable['nombreEspacio']=$_REQUEST['nombreEspacio'];

        $variables=array($variable['codEspacio'],$variable['codProyecto'],$variable['planEstudio'],$variable['nroGrupoNue']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);//echo $cadena_sql;exit;
        $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $totalSeleccionados=0;
        $total=$_REQUEST['totalEstudiantes'];//echo $_REQUEST['totalEstudiantes'];exit;
        $j=0;
            for($i=0;$i<$total;$i++)
            {//echo $_REQUEST['codEstudiante-'.$i];
                if($_REQUEST['codEstudiante-'.$i]!=NULL)
                    {
                        $codigo['codEstudiante-'.$j]=$_REQUEST['codEstudiante-'.$i];
                        $j++;
                        $totalSeleccionados++;
                    }
               
            }
//            var_dump($codigo);exit;
            $noexito=1;
            $exito=1;
        for($q=0;$q<$totalSeleccionados;$q++)
        {
            
            unset ($cruce);
            $band=0;
            $variableCodigo=$codigo['codEstudiante-'.$q];

            
            $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);//echo $cadena_sql;exit;
            $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            for($n=0;$n<count($resultado_horarios_registrado);$n++)
                {
                    for($m=0;$m<count($resultado_horarios_registrar);$m++)
                    {
                        if($band==0)
                            {
                                if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                                    {
                                        $band=1;
                                        $cruce=true;
                                        break;
                                    }
                            }
                     }
                }

           if($cruce==true)
               {
                    $variable['codEstudiante']=$variableCodigo;

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", $variable);//echo $cadena_sql;exit;
                    $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variable['anno']=$resultadoPeriodo[0][0];
                    $variable['periodo']=$resultadoPeriodo[0][1];

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variableCodigo);//echo $cadena_sql;exit;
                    $resultado_datosEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $reporteNoExitos[$noexito]=$variableCodigo.",".$resultado_datosEstudiante[0][2].",".$resultado_datosEstudiante[0][3].",Presenta cruce con el horario que tiene registrado el estudiante";
                    //echo $reporteNoExitos[$noexito];
                    $noexito++;

                    $variablesRegistro[0]=$this->usuario;
                    $variablesRegistro[1]=date('YmdGis');
                    $variablesRegistro[2]='32';
                    $variablesRegistro[3]='No pudo cambiar de grupo, problemas estudiante';
                    $variablesRegistro[4]=$variable['anno']."-".$variable['periodo'].",".$variable['codEspacio'].",".$variable['nroGrupoAnt'].",".$variable['nroGrupoNue'].",".$variable['planEstudio'].",".$variable['codProyecto'];
                    $variablesRegistro[5]=$variable['codEstudiante'];

                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
               }else
                   {
                    $variable['codEstudiante']=$variableCodigo;
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoOracle", $variable);//echo $cadena_sql;exit;
                    $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                    if($resultado_actGrupo==true)
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", $variable);//echo $cadena_sql;exit;
                            $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $variable['anno']=$resultadoPeriodo[0][0];
                            $variable['periodo']=$resultadoPeriodo[0][1];

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoMySQL", $variable);//echo $cadena_sql;exit;
                            $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $variablesRegistro[0]=$this->usuario;
                            $variablesRegistro[1]=date('YmdGis');
                            $variablesRegistro[2]='3';
                            $variablesRegistro[3]='Cambio grupo del Espacio Académico';
                            $variablesRegistro[4]=$variable['anno']."-".$variable['periodo'].",".$variable['codEspacio'].",".$variable['nroGrupoAnt'].",".$variable['nroGrupoNue'].",".$variable['planEstudio'].",".$variable['codProyecto'];
                            $variablesRegistro[5]=$variable['codEstudiante'];

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variableCodigo);//echo $cadena_sql;exit;
                            $resultado_datosEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $reporteExitos[$exito]=$variableCodigo.",".$resultado_datosEstudiante[0][2].",".$resultado_datosEstudiante[0][3].",Nuevo grupo: ".$variable['nroGrupoNue'];
//                            var_dump($reporteExitos);
                            $exito++;
                        }else
                            {
                                $reporteNoExitos[$noexito]=$variableCodigo.",".$resultado_datosEstudiante[0][2].",".$resultado_datosEstudiante[0][3].",¡OPPPSS problemas no se pueden rescatar los datos!";
                                //echo $reporteNoExitos[$noexito];
                                $noexito++;
                            }
                   }
        }
//var_dump($reporteNoExitos);exit;
        $this->generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variable);

        
    }

    function generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variableRetorno)
     {
        //Actualiza los cupos del grupo por el que se hizo el cambio
        $variablesInscritosAnt=array($variableRetorno['codEspacio'],$variableRetorno['nroGrupoAnt']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosAnt);
        $resultado_InscritosAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritosAnt[2]=$resultado_InscritosAnt[0][0];
        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosAnt);
        $resultado_ActualizacionAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

        //Actualiza el cupo del nuevo grupo de los estudiantes
        $variablesInscritosNue=array($variableRetorno['codEspacio'],$variableRetorno['nroGrupoNue']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosNue);
        $resultado_InscritosNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritosNue[2]=$resultado_InscritosNue[0][0];
        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosNue);
        $resultado_ActualizacionNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );
            ?>
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">

        <td class="centrar">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$variableRetorno['codEspacio'];
                    $variable.="&nroGrupo=".$variableRetorno['nroGrupoAnt'];
                    $variable.="&planEstudio=".$variableRetorno['planEstudio'];
                    $variable.="&codProyecto=".$variableRetorno['codProyecto'];
                    $variable.="&nombreEspacio=".$variableRetorno['nombreEspacio'];
                    $variable.="&nroCreditos=".$variableRetorno['nroCreditos'];
                    
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
            </a>
        </td>
        
    </tr>
    <tr>
        <td class="cuadro_brownOscuro centrar">
            <font size=2><?echo $variableRetorno['codEspacio']." - ".$variableRetorno['nombreEspacio']?></font>
        </td>
    </tr>
</table>

<table class="contenidotabla">
    <tr class="cuadro_brownOscuro centrar">
        <td colspan="4">
            <font size="2"><b>REPORTE DEL PROCESO DE CAMBIO DE GRUPO</b></font>
        </td>
    </tr>
    <?
//   var_dump($reporteNoExitos);
//   echo count($reporteNoExitos);

        if(is_array($reporteExitos))
            {
                echo "<tr class='cuadro_brownOscuro centrar'><td colspan='4'><b>REGISTROS EXITOSOS</b></td></tr>";
                for($i=1;$i<=count($reporteExitos);$i++)
                {
                    $arreglo=explode(",",$reporteExitos[$i]);
                    $codEstudiante=$arreglo[0];
                    $nombreEstudiante=$arreglo[1];
                    $proyectoEstudiante=$arreglo[2];
                    $Descripcion=$arreglo[3];

                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $codEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo htmlentities($nombreEstudiante)?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo htmlentities($proyectoEstudiante)?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $Descripcion?>
                            </td>
                        </tr>
                    <?
                }
            }
        if(is_array($reporteNoExitos))
            {
                echo "<tr class='cuadro_brownOscuro centrar'><td colspan='4'><b>REGISTROS NO EXITOSOS</b></td></tr>";
                for($p=1;$p<=count($reporteNoExitos);$p++)
                {
                    $arregloNo=explode(",",$reporteNoExitos[$p]);
                    $codEstudianteNo=$arregloNo[0];
                    $nombreEstudianteNo=$arregloNo[1];
                    $proyectoEstudianteNo=$arregloNo[2];
                    $DescripcionNo=$arregloNo[3];

                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $codEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo htmlentities($nombreEstudianteNo)?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo htmlentities($proyectoEstudianteNo)?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $DescripcionNo?>
                            </td>
                        </tr>
                    <?
                }
            }
    ?>
</table>
            <?
        }
}

?>