<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect . 'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect . 'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

/* Retorna $oci_conecta que es el acceso a la base de datos dependiendo del usuario */

/* * ****** */


fu_tipo_user(50);


$conexion = new multiConexion();
$accesoOracle = $conexion->estableceConexion($_SESSION['usuario_nivel']);


$cripto = new encriptar();

$indice = "http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
//$indice="http://oas2.udistrital.edu.co/weboffice/index.php?";

$variable = "pagina=login";
$variable.="&usuario=" . $_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=51";
$variable.="&modulo=inscripcionGrado";
$variable.="&tiempo=" . $_SESSION['usuario_login'];
$variable = $cripto->codificar_url($variable, $configuracion);
$enlaceWeboffice = $indice . $variable;


$variable = "pagina=login";
$variable.="&usuario=" . $_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=51";
$variable.="&modulo=matriculaEstudiante";
$variable.="&tiempo=" . $_SESSION['usuario_login'];
$variable = $cripto->codificar_url($variable, $configuracion);
$enlaceMatricula = $indice . $variable;

$variable = "pagina=login";
$variable.="&usuario=" . $_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=51";
$variable.="&modulo=ActualizaDatos";
$variable.="&tiempo=" . $_SESSION['usuario_login'];
$variable = $cripto->codificar_url($variable, $configuracion);
$enlaceActualizaDatos = $indice . $variable;
?>

<html>
    <head>
        <script language="JavaScript" src="../script/clicder.js"></script>
        <script language="JavaScript" src="../script/SlideMenu.js"></script>
        <script language="JavaScript" src="../script/ventana.js"></script>
        <script language="JavaScript" src="../script/BorraLink.js"></script>
    </head>
    <body class='menu'>
<? require_once('../usuarios/usuarios.php'); ?>

        <script src="../script/jquery.min.js"></script>
        <link href="../estilo/menu.css" rel="stylesheet" type="text/css">

        <ul class="menu">

            <li class="item5">
                <a href="#">Instructivo</a>
                <ul class="submenus">
                    <li class="subitem1"><a target="principal" href="../instructivo/index.php">Instructivo Admisiones</a></li>
                    <li class="subitem1"><a target="principal" href="../instructivo/reingreso.php">Instructivo Reingreso</a></li>
                    <li class="subitem1"><a target="principal" href="../instructivo/reingreso.php">Instructivo Tranferencias</a></li>
                </ul>
            </li>

            <li class="item1">
                <a href="#">Inscripci&oacute;n</a>
                <ul class="submenus">
		<?/*?>    <li class="subitem1"><a target="principal" href="aceptar.php?form=acasp">Ingreso primer semestre</a></li>
                    <li class="subitem1"><a target="principal" href="aceptar.php?form=reingreso">Reingreso y Transferencia Interna</a>
                    <li class="subitem1"><a target="principal" href="aceptar.php?form=transferencia">Transferencia Externa</a></li><?*/?>
                    <li class="subitem1"><a target="principal" href="aceptar.php?form=reingreso">Reingreso</a>
                </ul>
            </li>

            <li class="item5">
                <a href="#">Ver inscripci&oacute;n</a>
                <ul class="submenus">
                    <li class="subitem1"><a target="principal" href="../generales_asp/imprime_colilla_general.php?opcion=consultar">Consultar</a></li>
                </ul>
            </li>

            <li class="item5">
                <a href="#">Ver resultados</a>
                <ul class="submenus">
                    <li class="subitem1"><a target="principal" href="../resultados/aviso_resultados.php">Ver resultado</a></li>
                </ul>
            </li>


            <li class=""><a target="_top" href="../conexion/salir.php"><font color="red">Cerrar Sesi&oacute;n </font></a>
        </ul>

        <!--initiate accordion-->
        <script type="text/javascript">
            $(function() {

                var menu_ul = $('.menu .submenus'),
                        menu_a = $('.menu a');
                var clase;
                var link;
                menu_ul.hide();

                menu_a.click(function(e) {
                    link = $(this).attr('href');
                    if (link == '#')
                    {
                        clase = $(this).attr('class');
                        menu_a.removeClass('active');
                        $(this).addClass('active');
                        if ($(this).next().css('display') == 'none') {
                            $(this).next().slideDown('fast');
                        }
                        else
                        {
                            $(this).next().slideUp('fast');
                        }

                    }
                });

            });
            onload = SlideMenuInit;
        </script>
    </body>
</html>
