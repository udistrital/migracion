<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/****************************************************************************
  
estilo.php 

Paulo Cesar Coronado
Copyright (C) 2001-2007

Última revisión 6 de junio de 2007

******************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Definicion de estilos - es una pagina CSS
* @usage        
*****************************************************************************/


   include_once("../../clase/config.class.php");
   include_once("tema.php");

   $esta_configuracion=new config();
   $configuracion=$esta_configuracion->variable("../../"); 

    if (!isset($mi_tema)) 
    {
        $mi_tema = "basico";
	
    }

?>


body, td, th, li 
{
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-size 10px;
}

body 
{
	text-align:center;
}

th 
{
    font-weight: bold;
    background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg);
}

DIV
{
	-moz-box-sizing:border-box;
	box-sizing:border-box;
	margin:0;
	padding:0;
}

a:link 
{
    text-decoration: none;
    color: <? echo $tema->enlace ?>;
}

a:visited 
{
    text-decoration: none;
    color: <? echo $tema->enlace ?>;
}

a:hover 
{
    text-decoration: underline;
    color: <? echo $tema->sobre ?>;
}

a.enlace:link 
{
    text-decoration: none;
    color: #FFFFFF;
}

a.enlace:visited {
    text-decoration: none;
    color: #FFFFFF;
}


a.linkHorizontal:link 
{
    color: #000000;
}

a.linkHorizontal:visited {
    color: #000000;
}

a.linkHorizontal:hover {
    text-decoration: underline;
    color: <? echo $tema->sobreOscuro ?>;
     background-image: url(<?PHP echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"].'/'.$mi_tema ?>/gradient2.jpg)
}

a.wiki:link 
{
    text-decoration: none;
    color: #0000FF;    
}

a.wiki:visited {
    text-decoration: none;
    color: #0000FF;
}

a.wiki:hover {
    text-decoration: underline;
    color: #FF0000;
    
}

hr.hr_subtitulo
{
	border: 0;
	color: #000000;
	background-color: #999999;
	height: 1px;
	width: 100%;
	text-align: left;
}

.fondoprincipal {
    background-color: <?PHP echo $tema->fondo?>;
}

/*funciones para la preinscripcion*/

.blocker {
        position: absolute;
        visibility: hidden;
        top: 0px;
        left: 0px;
        height:100%;
        width:100%;        /* hacemos que ocupe toda la pantalla a cualquier resolución*/
        z-index: 50;        /* lo colocamos por encima del resto de componentes*/
        background: url(fondo_bloqueo.png);
}


.fondoBloqueo{
        position: absolute;
        visibility: hidden;
        top: 0;
        left: 0;
        z-index: 100;
        width: 100%;
        height: 100%;
        background: url(fondo_bloqueo.png);
    }


.popup{
        position: absolute;
        top: 50%;
        left: 46%;
        --z-index: 100; /* un z-index mayor al del blocker */
        background-color: white;  /* Un color de fondo para que se vea sobre la capa anterior*/

}

.popupGrande{
        width: 50%;
        height: 50%;
        position: absolute;
        top: 25%;
        left: 25%;
        --z-index: 100; /* un z-index mayor al del blocker */
        background-color: white;  /* Un color de fondo para que se vea sobre la capa anterior*/
        border: 2px solid #000000;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        padding: 10px;
}

.fondoPie {
    background-color: #242f47;
}

.fondoImportante 
{
    background-color: <?PHP echo $tema->apuntado?>;;
}


.tabla_general {
	/*padding:15px;*/
	background-color: <?PHP echo $tema->cuerpotabla ?>;
	width:<?PHP echo $configuracion["tamanno_gui"]?>;
	border-width: 1px;
	border-color: <?PHP echo $tema->bordes?>;
	border-style: solid;
	margin-left:auto; 
	margin-right:auto;
	
	

}

form {
    margin-bottom: 0;
}


.highlight {
    background-color: <?PHP echo $tema->highlight?>;
}

.bloquelateral {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    -moz-border-radius-bottomleft: 10px;
    -moz-border-radius-bottomright: 10px;
    background-color: <?PHP echo $tema->cuerpotabla?>;
}

.bloquelateral_2 {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    background-color: <?PHP echo $tema->celda_clara?>;
    width:100%;
    
}


.seccion_B {
    background-color: <?PHP echo $tema->fondo_B ?>;
    width:<?PHP echo $configuracion["tamanno_gui"]*(0.2) ?>%;
}


td.seccion_B
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.15) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}

td.seccion_C
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.6) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}


td.seccion_D
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.15) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}

td.seccion_C_colapsada
{
	width:<?PHP echo $configuracion["tamanno_gui"]*(0.8) ?>%;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;
}

toolTipBox
{
       display: none;
       padding: 5;
       font-size: 12px;
       border: black solid 1px;
       font-family: verdana;
       position: absolute;
       background-color: #ffd038;
       color: 000000;
}

.login_celda1 
{
    background-color: #f4f5eb;
}

.cuadro_color
{
    background-color: #f4f5eb;
}

.cuadro_brown
{
    background-color: LemonChiffon;
    color:brown;
}


.cuadro_azul
{
    background-color: #e0f5ff;
}

.cuadro_login {
    border-width: 1px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
}

.cuadro_plano {
    border-width: 1px;
    border:1px solid #AAAAAA;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
}

.cuadro_planito {
    border-width: 1px;
    border:1px solid #AAAAAA;
    font-size: 8;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
}


.cuadro_simple {
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    background-color: <?PHP echo $tema->celda_oscura?>;
}

.cuadro_corregir {
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;	
    background-color: #FF0000;
}

<? /*===================Estilos de Texto ===================================*/
/**************Encabezado cuando se muestra un registro*********************/?>
.encabezado_registro 
{
    border-width: 0px;
    font-size: 16;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;	
}

<?/***************************************************************************/?>
<?/*==========================Estilo SIGMA============================*/
 /**************************************************************************/?>

BODY{
	background: #D7E3F1;
}
BODY.b{
	background: #FFFFFF;
}
BODY.c{
	background: #D7E3F1;
}
h1.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 16px;
	text-transform: UPPERCASE;
}
h2.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 14px;
	text-transform: UPPERCASE;
}
h3.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 12px
}
P.sigma
{
	font-family: verdana,sans serif,helvetica;
	font-size: 10px
}
th.sigma
{
	background: #d5d5d5;
	text-transform: capitalize;
	font-size: 11px;
	color: #000000;
}
th.sigma_a
{
	background: #23b0be;
	-moz-border-radius:7px 7px 7px 7px;
	font-weight:bold;
	font-family: verdana,sans serif,helvetica;
	font-size: 14px;
	color: #FFFFFF;
	text-transform: uppercase;
	height: 16px;
}
td.sigma
{
	font-size: 11px;
	color: #000000;
}

td.sigma_a
{
    font-size: 11px;
    color: #FFFFFF;
    background: #1E3B86;
}

tr.sigma
{
	background: #E4EBFE;
}

caption.sigma
{
	background: #0A0A2A;
	-moz-border-radius:7px 7px 7px 7px;
	font-weight:bold;
	font-family: verdana,sans serif,helvetica;
	font-size: 14px;
	color: #FFFFFF;
	text-transform: uppercase;
	height: 16px;
}
BODY.izq{
	background-repeat:repeat-y;
	background-position: right;
	font-family: verdana,sans-serif,helvetica
}
BODY.der{
	background-repeat:repeat-y;
	background-position: left;
}
table.sigma
{
	font-family: verdana,sans serif,helvetica;
	border-spacing: 3px;
	border-style: solid;
	border-width: thin;
        border: 0px;
}
table.sigma_borde
{
	font-family: verdana,sans serif,helvetica;
        font-size: 10;
        text-align: justify;
	border-collapse: collapse;
        border-spacing: 0px;
}

select.sigma
{
    font-family: verdana;
    font-size: 12px;
    color: #1E3B86;
    font-color: #1E3B86;
    background-color:#EAF0F6;
    padding-left:10px;
    border:2px solid #1E3B86;
}

option.sigma
{
    font-family: verdana;
    font-size: 10px;
    color: #1E3B86;
    background-color:#EAF0F6;
    font-color: #1E3B86;
}

optgroup.sigma
{
    font-family: verdana;
    font-size: 10px;
    color: #1E3B86;
    background-color:#EAF0F6;
    font-color: #1E3B86;
}

.cajatexto
{
    -moz-border-radius: 18px 18px 18px 18px;
    border: 1px solid #CCCCCC;
    color: #000000;
    font-size: 11px;
    padding: 4px;
    
}

.noeditable
{
    -moz-border-radius: 18px 18px 18px 18px;
    border: 1px solid #CCCCCC;
    color: #000000;
    font-size: 11px;
    padding: 4px;
    background-color:#D8D8D8;
}

caption {
    -moz-border-radius: 3px 3px 3px 3px;
    background: none repeat scroll 0 0 #133969;
    color: #FFFFFF;
    font-family: verdana,sans serif,helvetica;
    font-size: 14px;
    font-weight: bold;
    height: 16px;
    text-transform: uppercase;
}

.renglones {
    background: none repeat scroll 0 0 #D5D5D5;
    color: #000000;
    font-size: 11px;
    text-transform: capitalize;
}

input.boton
{
    border-width:1px;
    border-color:#1E3B86;
    border-style:solid;
    text-align:center;
    font-weight:bold;
    font-size:10pt;
    font-family:arial;
    background-color:#1E3B86;
    color:#FFFFFF;
}

#toolTipBox {
                display: none;
                position:absolute;
                background:#E9EFE6;
                border:4px double #fff;
                text-align:left;
                padding:5px;
                -moz-border-radius:8px;
                z-index:1000;
                margin:0;
                padding:0;
                color:#1E3B86;
                font:11px/12px verdana,arial,serif;
                margin-top:3px;
                font-style:normal;
                font-weight:bold;
                opacity:0.85;
        }

p.sigma
{
   color:red;
   font-size:10px;
   font-family:Courier;
   border:2px;
   border-color:white;
   border-style:solid;
   width:200px;
   height:100px;
}


<?/***************************************************************************/?>

.texto_negrita {
    font-weight: bold;	
}


.texto_subtitulo 
{
    border-width: 0px;
    font-size: 14;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;    
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_elegante 
{
    border-width: 0px;
    font-size: 13;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;    
    color:  <?PHP echo $tema->subtitulo?>;
}


.texto_subtitulo_verde
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #D5F5B1;
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_subtitulo_amarillo
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #FFFFBB;
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_subtitulo_rojo
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #FBBFB6;     
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_subtitulo_gris
{
    border-width: 0px;
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;   
    background-color: #CCCCCC;     
    color: #000000;
}

.texto_titulo_negro
{
    border-width: 0px;
    font-size: 14;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;    
    color:  <?PHP echo $tema->subtitulo?>;
}

.texto_azul
{
    color:#0000FF;
}

.texto_gris
{
    color:#555555;
}

.textoBlanco
{
    color:#FFFFFF;
}

.texto_titulo 
{
    border-width: 0px;
    font-size: 18;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    color:  <?PHP echo $tema->titulo?>;
}


<? /*===================Estilos de Tablas ===================================*/?>

table.tablaBase
{
	width:100%;
	border-collapse: collapse;
}

table.tablaBase td
{
	padding: 0px;
	border: 0px;
	border-collapse: collapse;
	border-spacing: 0px;	
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid
}





table.tablaMarcoLateral
{
	width:100%;
	
}

table.tablaMarco
{
	width:100%;
	padding:10px;
	border-collapse: collapse;
	border-spacing: 0px;	
	
}

table.tablaMarco td
{
	padding:5px;
	
}

table.tablaMarcoGeneral
{
	width:100%;
	padding:30px;
	border-collapse: collapse;
	border-spacing: 0px;	
	
}

table.tablaMarcoGeneral td
{
	padding:10px;
	
}

table.tablaMarcoPequenno
{
	width:100%;
	padding:5px;
	
}


table.contenidotabla 
{
	font-size: 10;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	text-align: justify;
	width:100%;
	border-collapse: collapse;
	border-spacing: 0px;	
    }
    
table.contenidotabla2 td
{
	padding:3px;
}

table.contenidotabla2 
{
	font-size: 10;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	text-align: justify;
	width:70%;
	border-collapse: collapse;
	border-spacing: 0px;	
    }
    
table.contenidotabla td
{
	padding:3px;
}


table.Cuadricula 
{
	font-size: 11px;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	width:100%;
	border:1px;
	border-collapse: collapse;
	border-spacing: 0px;	
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}
    
table.Cuadricula td
{
	padding:3px;
	border:1px;
	border-collapse: collapse;
	border-spacing: 0px;
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}


table.tarjeton
{
	font-size: 12px;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	width:100%;
	border:0px;
	border-spacing: 2px;	
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}
    
table.tarjeton td
{
	padding:5px;
	border:0px;
	border-spacing: 3px;
	border-color: <?PHP echo $tema->bordes?>;
    	border-style: solid;
}

.tabla_basico {
	background-color:#F9F9F9;
	border:1px solid #AAAAAA;
	font-size:95%;
	padding:5px;
	width:90%;
	margin-left:10%; 
	margin-right:10%;
}


table.normalTarjeton 
{
	font-size: 11px;
	font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
	width:100%;
	border:0px;
	border-collapse: collapse;
	border-spacing: 0px;	
	border-color: <?PHP echo $tema->bordes?>;
<!--     	border-style: solid; -->
}
    
table.normalTarjeton td
{
	padding:3px;
	border:0px;
	border-collapse: collapse;
	border-spacing: 0px;
	border-color: <?PHP echo $tema->bordes?>;
<!--     	border-style: solid; -->
}


table.normalTarjeton tr
{
	padding:3px;
	border:0px;
	border-collapse: collapse;
	border-spacing: 0px;
	border-color: <?PHP echo $tema->bordes?>;
<!--     	border-style: solid; -->
}

.tabla_organizacion
{
	border:0px;
	padding:10px;
	width:100%;	
}

.tabla_alerta {
	background-color:#fdffe5;
	border:1px solid #AAAAAA;
	font-size:95%;
	padding:5px;
	width:90%;
	margin-left:10%; 
	margin-right:10%;
}


.posicionTarjeton {
	background-color:#fdffe5;
	padding:5px;
	margin-left:10%; 
	margin-right:10%;
	text-align: center;
	font-size:20px;
	    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-weight: bold;	
}


.tabla_simple 
{
	background-color:#FFFFF5;
	border:1px solid #CCCCCC;
	font-size:11px;
	width:100%;
	text-align: center;
}



.paginacentral {
    border-width: 1px;
    border-color: <?PHP echo $tema->bordes?>;
    border-style: solid;
    -moz-border-radius-bottomleft: 10px;
    -moz-border-radius-bottomright: 10px;
    -moz-border-radius-topleft: 10px;
    -moz-border-radius-topright: 10px;
    background-color: <?PHP echo $tema->cuerpotabla?>;
}

.bloquelateralencabezado {
    font-size: 13;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-weight: bold;	
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"].'/'.$mi_tema ?>/gradient.jpg) -->
}

.bloquelateralcuerpo {
    font-size: 11;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    }
    
.bloquecentralencabezado {
    font-size: 13;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    font-weight: bold;	
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg) -->
}

.bloquecentralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: justify;
    }


    
.bloquelateralayuda {
    font-size: 10;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-decoration: italic;
    }    
    
.centralcuerpo {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    color: #0F0F0F;
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg) -->
}  
  
 .menuHorizontal {
    font-size: 12;
    text-align: center;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg)
}   
.centralencabezado {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    text-align: center;
    background-color: <?PHP echo $tema->encabezado?>;
<!--     background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['estilo'].'/'.$mi_tema ?>/gradient.jpg) -->
}


.centrar {
    text-align: center;
}

.derecha {
    text-align: right;
}

.textoCentral {
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
    }
    
<? /*===================Estilos Especificos para el Proyecto WebOffice===================================*/?>
.webofficeFondoOscuro
{
	background-color: #2d3750;
	heigth:10px;
}

#cuadroLogin
{
	position:relative;
	top:0px;
	left:0px;
	
}

#menu1
{
	list-style-image:url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['grafico'].'/'?>/bullet1.jpg);
	
}

#menu1 li
{
	padding: 2px 0px 0px 0px;
}

#divMenu2 
{  
	padding:0px 0px 0px 0px;
	heigth:10px;
	margin:0;
	padding:0;
}

#menu2 {  
	list-style:none;
	margin:0;
	padding:0;
}

#menu2 li {
	margin:0px;
	padding:0px 5px 0px 5px;
	border:0px solid #CCCCCC;
	float:left; 
	color:#FFFFFF;
	text-align:center;
}


#mensaje1
{
	padding:5px;
}

.textoNivel1 
{
    font-size: 10px;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}

html>body .textoNivel1 
{
    font-size: 12;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}


.textoNivel0 
{
    font-size: 11;
    font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
}

.textoTema
{
	color: #2d3750;
}

#notiCondor
{
	background-color:#EEEEEE;
	heigth:127px;
	
	
}

html>body #notiCondor
{
	background-image: url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['grafico'].'/login_principal'?>/index_2_5.jpg);
	
}

#noticiero 
{  
	position:relative;
	overflow:auto;
	margin:2px;
	padding:0px 5px 0px 5px;
	height:110px;	
}


table.formulario td {
	border-width: 1px 1px 1px 1px;
	padding: 5px 5px 5px 5px;
	border-style: inset inset inset inset;
	border-color: #DEDEDE #DEDEDE #DEDEDE #DEDEDE;
	-moz-border-radius: 0px 0px 0px 0px;
}


table.formulario {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: outset outset outset outset;
	border-color: #DEDEDE #DEDEDE #DEDEDE #DEDEDE;
	border-collapse: collapse;
	background-color:#FFFFFF;
	border:1px solid #DEDEDE;
	font-size:12px;
	padding:10px;
	width:100%;
}


table.tablaImportante
{
	border-width: 0px 0px 1px 15px;
	border-style:solid;
	border-color:#b30012;
	width:100%;
	padding:10px;
	border-collapse: collapse;
	border-spacing: 0px;	
	
}

table.tablaImportante td
{
	padding:5px;
	background-color:#FFFFFF;
	
}

.ancho10
{
	width: 10%;
}


#datosbasicos {
width:100%;
display: block;
background-color:#FDF4E1;
}
#infonbc {
width:100%;
display: none;
background-color:#FDF4E1;
}
#infoduracion {
width:100%;
display: none;
background-color:#FDF4E1;
}
#infoacreditacion {
width:100%;
display: none;
background-color:#FDF4E1;
}
#infoadicional {
width:100%;
display: none;
background-color:#FDF4E1;
}

.mostrar{
	background:transparent url(<?PHP echo $configuracion['host'].$configuracion['site'].$configuracion['grafico'].'/'?>tab.jpg) repeat scroll 0 0;
	display:block;
	font-size:12px;
	height:15px;
	padding:5px;
	width:120px;
	text-decoration: none;
	background-repeat:no-repeat;
}

#toolTipBox {
                display: none;
                position:absolute;
                background:#E9EFE6;
                border:4px double #fff;
                text-align:left;
                padding:5px;
                -moz-border-radius:8px;
                z-index:1000;
                margin:0;
                padding:0;
                color:#1E3B86;
                font:11px/12px verdana,arial,serif;
                margin-top:3px;
                font-style:normal;
                font-weight:bold;
                opacity:0.85;
        }

.marcoSombraGris{
background-color: #DDDDDD;
border: 1px solid #555555;
border-radius: 7px 7px 7px 7px;
box-shadow: 0 10px 6px -6px #777777;
padding: 30px;
width: 90%;
}

.marcoSombraBlanco{
background-color: #FFFFFF;
border: 1px solid #555555;
border-radius: 1px 1px 1px 1px;
padding: 30px;
width: 100%;
}
