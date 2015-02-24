<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Menu principal
* @usage        
*****************************************************************************/
?>

<style type="text/css">
.contenedor .menudivider
	{
		display:block;
		font-size:1px;
		border-width:0px;
		border-style:solid;
		position:relative;
		z-index:1;
	}
.contenedor .menudividery
	{
		float:left;
		width:0px;
	}
.contenedor .titulo
	{
		display:block;
		cursor:default;
		white-space:nowrap;
		position:relative;
		z-index:1;
	}
.menulimpiar 
	{
		font-size:1px;
		height:0px;
		width:0px;
		clear:left;
		line-height:0px;
		display:block;
		float:none 
		!important;
	}
.contenedor 
	{
	position:relative;
	zoom:1;
	z-index:10;
	}
.contenedor a, .contenedor li 
	{
		float:left;
		display:block;
		white-space:nowrap;
		position:relative;
		z-index:1;
	}
.contenedor div a, .contenedor ul a, .contenedor ul li 
	{
		float:none;
	}
.menush div a 
	{
		float:left;
	}
	
.contenedor div
	{
		visibility:hidden;
		position:absolute;
	}
	
	.contenedor .menucbox
	{
		cursor:default;
		display:block;
		position:relative;
		z-index:1;
	}
	
	.contenedor .menucbox a
	{
		display:inline;
	
	}
	
	.contenedor .menucbox div
	{
		float:none;
		position:static;
		visibility:inherit;
		left:auto;
	}
	
	.contenedor li 
	{
		z-index:auto;
	}
	
	.contenedor ul 
	{
		position:absolute;
		z-index:10;
	}
	
	.contenedor, .contenedor ul 
	{
		list-style:none;
		padding:0px;
		margin:0px;
	}
	
	.contenedor li a 
	{
		float:none
	}
	
	.contenedor li:hover>ul
	{
		left:auto
	}
	#menu0 ul 
	{
		top:100%;
	}
	
	#menu0 ul li:hover>ul
	{
		top:0px;
		left:100%;
	}
	#menu0 a	
	{	
		padding:5px 4px 5px 5px;
		color:#555555;
		font-family:Arial;
		font-size:10px;
		text-decoration:none;
	}
	#menu0 div, #menu0 ul	
	{	
		padding:10px;
		margin:-2px 0px 0px;
		background-color:transparent;
		border-style:none;
	}
	#menu0 div a, #menu0 ul a	
	{	
		padding:3px 10px 3px 5px;
		background-color:transparent;
		font-size:11px;
		border-width:0px;
		border-style:none;
	}
	#menu0 div a:hover	
	{	
		background-color:#dadada;
		color:#cc0000;
	}
	#menu0 ul li:hover>a	
	{	
		background-color:#dadada;
		color:#cc0000;
	}
	body #menu0 div .menuactive, body #menu0 div .menuactive:hover	
	{	
		background-color:#dadada;
		color:#cc0000;
	}
	#menu0 .titulo	
	{	
		cursor:default;
		padding:3px 0px 3px 4px;
		color:#444444;
		font-family:arial;
		font-size:11px;
		font-weight:bold;
	}
	#menu0 .menudividerx	
	{	
		border-top-width:1px;
		margin:4px 0px;
		border-color:#bfbfbf;
	}
	#menu0 .menudividery	
	{	
		border-left-width:1px;
		height:15px;
		margin:4px 2px 0px;
		border-color:#aaaaaa;
	}
	#menu0 .menuritem span	
	{	
		border-color:#dadada;
		background-color:#f7f7f7;
	}
	#menu0 .menuritemcontent	
	{	
		padding:0px 0px 0px 4px;
	}

	ul#menu0 li:hover > a	
	{	
		background-color:#f7f7f7;
	}

	ul#menu0 ul	
	{	
		padding:10px;
		margin:-2px 0px 0px;
		background-color:#f7f7f7;
		border-width:1px;
		border-style:solid;
		border-color:#dadada;
	}
	
	.menufv
	{
		visibility:visible 
		!important;
	}
	
	.menufh
	{
		visibility:hidden 
		!important;
	}



</style>
<script type="text/javascript">
menuad=new Object();
menuad.bvis="";
menuad.bhide="";
var a = menuad.menu0 = new Object();
a.esquinaDerecha_tamanno = 6;
a.esquinaDerecha_border_color = "#dadada";
a.esquinaDerecha_bg_color = "#F7F7F7";
a.esquinaDerecha_apply_corners = new Array(false,true,true,true);
a.esquinaDerecha_top_line_auto_inset = true;

a.ritem_tamanno = 4;
a.ritem_apply = "main";
a.ritem_main_apply_corners = new Array(true,true,false,false);
a.ritem_show_on_actives = true;

/* <![CDATA[ */
var menu_si,menu_li,menu_lo,menu_tt,menu_th,menu_ts,menu_la,menu_ic,menu_ib,menu_ff;
var qp="parentNode";
var qc="className";
var menu_t=navigator.userAgent;
var menu_o=menu_t.indexOf("Opera")+1;
var menu_s=menu_t.indexOf("afari")+1;
var menu_s2=menu_s&&menu_t.indexOf("ersion/2")+1;
var menu_s3=menu_s&&menu_t.indexOf("ersion/3")+1;
var menu_n=menu_t.indexOf("Netscape")+1;
var menu_v=parseFloat(navigator.vendorSub);

function menu_crear(capa,v,ts,th,oc,rl,sh,fl,ft,aux,l)
{
	var w="onmouseover";
	var ww=w;
	var e="onclick";
	
	if(oc)
	{
		if(oc.indexOf("all")+1||(oc=="lev2"&&l>=2))
		{
			w=e;
			ts=0;
		}
		if(oc.indexOf("all")+1||oc=="main")
		{
			ww=e;
			th=0;
		}
	}
	if(!l)
	{
		l=1;menu_th=th;
		capa=document.getElementById("menu"+capa);
		if(window.menu_pure)capa=menu_pure(capa);
		capa[w]=function(e)
		{
			try
			{
				cerrar_menu(e)
			}
			catch(e)
			{
			}
		};
		if(oc!="all-always-open")
		document[ww]=menu_bo;
		if(oc=="main")
		{
			menu_ib=true;
			capa[e]=function(event)
			{
				menu_ic=true;
				menu_oo(new Object(),menu_la,1);
				cerrar_menu(event)
			};
			document.onmouseover=function()
			{
				menu_la=null;
				clearTimeout(menu_tt);
				menu_tt=null;
			};
		}
		capa.style.zoom=1;
		if(sh)x2("menush",capa,1);
		if(!v)capa.ch=1;
	}
	else  if(sh)capa.ch=1;
	if(oc) capa.oc=oc;
	if(sh) capa.sh=1;
	if(fl) capa.fl=1;
	if(ft) capa.ft=1;
	if(rl) capa.rl=1;
	capa.style.zIndex=l+""+1;
	var lsp;
	var sp=capa.childNodes;
	for(var i=0;i<sp.length;i++)
	{
			var b=sp[i];
			//alert(b.tagName);
			if(b.tagName=="A")
			{
				lsp=b;
				b[w]=menu_oo;
				if(w==e)
				b.onmouseover=function(event)
				{
					clearTimeout(menu_tt);
					menu_tt=null;
					menu_la=null;
					cerrar_menu(event);
				};
				b.menuts=ts;
				if(l==1&&v)
				{
					b.style.styleFloat="none";
					b.style.cssFloat="none";
				}
			}
			else  if(b.tagName=="DIV")
				{
					if(window.showHelp&&!window.XMLHttpRequest)
					sp[i].insertAdjacentHTML("afterBegin","<span class='menulimpiar'>&nbsp;</span>");
					x2("menuparent",lsp,1);
					lsp.cdiv=b;
					b.idiv=lsp;
					if(menu_n&&menu_v<8&&!b.style.width)
					b.style.width=b.offsetWidth+"px";
					new menu_crear(b,null,ts,th,oc,rl,sh,fl,ft,aux,l+1);
					//(capa,v,ts,th,oc,rl,sh,fl,ft,aux,l)
				}
	}
};
	function menu_bo(e)
	{
		menu_ic=false;
		menu_la=null;
		clearTimeout(menu_tt);
		menu_tt=null;
		if(menu_li)
		menu_tt=setTimeout("x0()",menu_th);
	};
	function x0()
	{
		var a;
		if((a=menu_li))
		{
			do
			{
				menu_uo(a);
			}while((a=a[qp])&&!menu_a(a))
		}
		menu_li=null;
	};
	
	function menu_a(a)
	{
		if(a[qc].indexOf("contenedor")+1)
			return 1;
	};
	
	function menu_uo(a,go)
	{
		if(!go&&a.menutree)
		return;
		if(window.menuad&&menuad.bhide)
		eval(menuad.bhide);
		a.style.visibility="";
		x2("menuactive",a.idiv);
	};
	
	function qa(a,b)
	{
		return String.fromCharCode(a.charCodeAt(0)-(b-(parseInt(b/2)*2)));
	}
	
	function menu_oo(e,o,nt)
	{
		try
		{
			if(!o)o=this;
			if(menu_la==o&&!nt)return;
			if(window.menuv_a&&!nt)menuv_a(o);
			if(window.menuwait)
			{
				cerrar_menu(e);
				return;
			}
			clearTimeout(menu_tt);
			menu_tt=null;
			menu_la=o;
			if(!nt&&o.menuts)
			{
				menu_si=o;
				menu_tt=setTimeout("menu_oo(new Object(),menu_si,1)",o.menuts);
				return;
			}
			var a=o;
			if(a[qp].isrun)
			{
				cerrar_menu(e);
				return;
			}
			if(menu_ib&&!menu_ic)return;
			var go=true;
			while((a=a[qp])&&!menu_a(a))
			{
				if(a==menu_li)go=false;
			}
			if(menu_li&&go)
			{
				a=o;
				if((!a.cdiv)||(a.cdiv&&a.cdiv!=menu_li))menu_uo(menu_li);
				a=menu_li;
				while((a=a[qp])&&!menu_a(a))
				{
					if(a!=o[qp]&&a!=o.cdiv)menu_uo(a);
					else break;
				}
			}
			var b=o;
			var c=o.cdiv;
			if(b.cdiv)
			{
				var aw=b.offsetWidth;
				var ah=b.offsetHeight;
				var ax=b.offsetLeft;
				var ay=b.offsetTop;
				if(c[qp].ch)
				{
					aw=0;
					if(c.fl)ax=0;
				}
				else 
				{
					if(c.ft)ay=0;
					if(c.rl)
					{
						ax=ax-c.offsetWidth;
						aw=0;
					}
					ah=0;
				}
				if(menu_o)
				{
					ax-=b[qp].clientLeft;
					ay-=b[qp].clientTop;
				}
				if(menu_s2&&!menu_s3)
				{
					ax-=menu_gcs(b[qp],"border-left-width","borderLeftWidth");
					ay-=menu_gcs(b[qp],"border-top-width","borderTopWidth");
				}
				if(!c.ismove)
				{
					c.style.left=(ax+aw)+"px";
					c.style.top=(ay+ah)+"px";
				}
				x2("menuactive",o,1);
				if(window.menuad&&menuad.bvis)eval(menuad.bvis);
				c.style.visibility="inherit";
				menu_li=c;
			}
			else  if(!menu_a(b[qp]))menu_li=b[qp];
			else menu_li=null;cerrar_menu(e);
		}
		catch(e)
		{
		};
	};
	
	function menu_gcs(obj,sname,jname)
	{
		var v;
		if(document.defaultView&&document.defaultView.getComputedStyle)v=document.defaultView.getComputedStyle(obj,null).getPropertyValue(sname);
		else  if(obj.currentStyle)v=obj.currentStyle[jname];
		if(v&&!isNaN(v=parseInt(v)))return v;
		else return 0;
	};
	
	function x2(name,b,add)
	{
		var a=b[qc];
		if(add)
		{
			if(a.indexOf(name)==-1)b[qc]+=(a?' ':'')+name;
		}
		else 
		{
			b[qc]=a.replace(" "+name,"");b[qc]=b[qc].replace(name,"");
		
		}
	};
		
	//Funcion que se encarga de cerrar el menu dinamico
	function cerrar_menu(e)
	{
		if(!e)e=event;
		e.cancelBubble=true;
		if(e.stopPropagation&&!(menu_s&&e.type=="click"))e.stopPropagation();
	};
	
	function qa(a,b)
	{
		return String.fromCharCode(a.charCodeAt(0)-(b-(parseInt(b/2)*2)));
	}
	
	function menu_pure(capa)
	{
		if(capa.tagName=="UL")
		{
			var nd=document.createElement("DIV");
			nd.menupure=1;
			var c;
			if(c=capa.style.cssText)nd.style.cssText=c;
			menu_convert(capa,nd);
			var csp=document.createElement("SPAN");
			csp.className="menulimpiar";
			csp.innerHTML="&nbsp;";
			nd.appendChild(csp);
			capa=capa[qp].replaceChild(nd,capa);
			capa=nd;
		}
		return capa;
	};
	
	function menu_convert(a,bm,l)
	{
		if(!l)bm[qc]=a[qc];
		bm.id=a.id;
		var ch=a.childNodes;
		for(var i=0;i<ch.length;i++)
		{
			if(ch[i].tagName=="LI")
			{
				var sh=ch[i].childNodes;
				for(var j=0;j<sh.length;j++)
				{
					if(sh[j]&&(sh[j].tagName=="A"||sh[j].tagName=="SPAN"))bm.appendChild(ch[i].removeChild(sh[j]));
					if(sh[j]&&sh[j].tagName=="UL")
					{
						var na=document.createElement("DIV");
						var c;
						if(c=sh[j].style.cssText)na.style.cssText=c;
						if(c=sh[j].className)na.className=c;na=bm.appendChild(na);
						new menu_convert(sh[j],na,1)
					}
				}
			}
		}
	}/* ]]> */
	</script>
<script type="text/javascript">
/* <![CDATA[ */
menuad.rcorner=new Object();
menuad.br_ie7=navigator.userAgent.indexOf("MSIE 7")+1;
if(menuad.bvis.indexOf("esquinaDerecha(b.cdiv);")==-1)menuad.bvis+="esquinaDerecha(b.cdiv);";

function esquinaDerecha(a,hide,force)
{
	var z;
	if(!hide&&((z=window.menuv)&&(z=z.addons)&&(z=z.round_corners)&&!z["on"+menu_index(a)]))return;
	var q=menuad.rcorner;
	if((!hide&&!a.hasrcorner)||force)
	{
		var ss;
		if(!a.settingsid)
		{
			var v=a;
			while((v=v.parentNode))
			{
				if(v.className.indexOf("contenedor")+1)
				{
					a.settingsid=v.id;
					break;
				}
			}
		}
		ss=menuad[a.settingsid];
		if(!ss)return;
		if(!ss.esquinaDerecha_tamanno)return;
		q.tamanno=ss.esquinaDerecha_tamanno;
		q.background=ss.esquinaDerecha_bg_color;
		if(!q.background)q.background="transparent";
		q.border=ss.esquinaDerecha_border_color;
		if(!q.border)q.border="#ff0000";
		q.angle=ss.esquinaDerecha_angle_corners;
		q.corners=ss.esquinaDerecha_apply_corners;
		if(!q.corners||q.corners.length<4)q.corners=new Array(true,1,1,1);
		q.tinset=0;
		if(ss.esquinaDerecha_top_line_auto_inset&&menu_a(a[qp]))q.tinset=a.idiv.offsetWidth;
		q.opacity=ss.esquinaDerecha_opacity;
		if(q.opacity&&q.opacity!=1)
		{
			var addf="";
			if(window.showHelp)addf="filter:alpha(opacity="+(q.opacity*100)+");";
			q.opacity="opacity:"+q.opacity+";"+addf;
		}
		else q.opacity="";
		var f=document.createElement("SPAN");
		x2("menurcorner",f,1);
		var fs=f.style;
		fs.position="absolute";
		fs.display="block";
		fs.top="0px";
		fs.left=-"0px";
		var tamanno=q.tamanno;
		q.mid=parseInt(tamanno/2);
		q.ps=new Array(tamanno+1);
		var t2=0;
		q.otamanno=q.tamanno;
		if(!q.angle)
		{
			for(var i=0;i<=tamanno;i++)
			{
				if(i==q.mid)t2=0;q.ps[i]=t2;
				t2+=Math.abs(q.mid-i)+1;
			}
			q.otamanno=1;
		}
		var fi="";
		for(var i=0;i<tamanno;i++)fi+=esquinaDerecha_get_span(tamanno,i,1,q.tinset);
		fi+='<span menurcmid=1 style="background-color:'+q.background+';border-color:'+q.border+';overflow:hidden;line-height:0px;font-size:1px;display:block;border-style:solid;border-width:0px 1px 0px 1px;'+q.opacity+'"></span>';
		//alert(fi);
		
		for(var i=tamanno-1;i>=0;i--)fi+=esquinaDerecha_get_span(tamanno,i);
		f.innerHTML=fi;
		f.noselect=1;
		a.insertBefore(f,a.firstChild);
		a.hasrcorner=f;
	}
	var b=a.hasrcorner;
	if(b){
		if(!a.offsetWidth)a.style.visibility="inherit";
		ft=menu_gcs(b[qp],"border-top-width","borderTopWidth");
		fb=menu_gcs(b[qp],"border-top-width","borderTopWidth");
		fl=menu_gcs(b[qp],"border-left-width","borderLeftWidth");
		fr=menu_gcs(b[qp],"border-left-width","borderLeftWidth");
		b.style.width=(a.offsetWidth-fl)+"px";
		b.style.height=(a.offsetHeight-fr)+"px";
		if(menuad.br_ie7)
		{
			var sp=b.getElementsByTagName("SPAN");
			for(var i=0;i<sp.length;i++)sp[i].style.visibility="inherit";
		}
		b.style.visibility="inherit";
		var s=b.childNodes;
		for(var i=0;i<s.length;i++)
		{
			if(s[i].getAttribute("menurcmid"))s[i].style.height=Math.abs((a.offsetHeight-(q.otamanno*2)-ft-fb))+"px";
		}
	}
};

function esquinaDerecha_get_span(tamanno,i,top,tinset)
{
	var q=menuad.rcorner;
	var mlmr;
	if(i==0)
	{
		var mo=q.ps[tamanno]+q.mid;
		if(q.angle)mo=tamanno-i;mlmr=esquinaDerecha_get_corners(mo,null,top);
		if(tinset)mlmr[0]+=tinset;
		return '<span style="background-color:'+q.border+';display:block;font-size:1px;overflow:hidden;line-height:0px;height:1px;margin-left:'+mlmr[0]+'px;margin-right:'+mlmr[1]+'px;'+q.opacity+'"></span>';
	}
	else 
	{
		var md=tamanno-(i);
		var ih=1;
		var bs=1;
		if(!q.angle)
		{
			if(i>=q.mid)ih=Math.abs(q.mid-i)+1;
			else 
			{
				bs=Math.abs(q.mid-i)+1;
				md=q.ps[tamanno-i]+q.mid;
			}
			if(top)q.otamanno+=ih;
		}
		mlmr=esquinaDerecha_get_corners(md,bs,top);
		return '<span style="background-color:'+q.background+';border-color:'+q.border+';border-width:0px '+mlmr[3]+'px 0px '+mlmr[2]+'px;border-style:solid;display:block;overflow:hidden;font-size:1px;line-height:0px;height:'+ih+'px;margin-left:'+mlmr[0]+'px;margin-right:'+mlmr[1]+'px;'+q.opacity+'"></span>';
	}
};

function esquinaDerecha_get_corners(mval,bval,top)
{
	var q=menuad.rcorner;
	var ml=mval;
	var mr=mval;
	var bl=bval;
	var br=bval;
	if(top)
	{
		if(!q.corners[0])
		{
			ml=0;bl=1;
		}
		if(!q.corners[1])
		{
			mr=0;br=1;
		}
	}
	else 
	{
		if(!q.corners[2])
		{
			mr=0;
			br=1;
		}
		if(!q.corners[3])
		{
			ml=0;
			bl=1;
		}
	}
	return new Array(ml,mr,bl,br);
}/* ]]> */
</script>
<table cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#ffffff" class="header">
	<tr>
		<td align="center" valign="top">
			<ul id="menu0" class="contenedor">
				<li><a href="http://ingenieria.udistrital.edu.co/moodle/mod/resource/view.php?id=3814">Solicitud Recibos</a>
					<ul>
						<li><a href="http://ingenieria.udistrital.edu.co/moodle" >Solicitud Individual</a></li>
						<li><span class="menudivider menudividerx" ></span></li>
						<li><a href="http://ingenieria.udistrital.edu.co/moodle/login">Solicitud por Lote</a></li>
						<li><span class="menudivider menudividerx" ></span></li>
						<li><a href="http://ingenieria.udistrital.edu.co/moodle/message" >Ayuda</a></li>
					</ul>
				</li>
				<li><span class="menudivider menudividery" ></span></li>
				<li class="menulimpiar">&nbsp;</li>
			</ul>
			<script type="text/javascript">
			menu_crear(0,true,0,250,false,false,false,false,false);			
			</script>
			
		</td>
	</tr>
</table> 
<?














































if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
$cripto=new encriptar();
?><table align="center" class="tablaMarcoLateral">
	<tbody>
		<tr>
			<td >
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2">
					<tr class="centralcuerpo">
						<td>
						<b>:.</b> Men&uacute;
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=administrar_recibo";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&opcion=lista";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Solicitudes de Recibos</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=informe_recibo";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=lista";
							$variable.="&no_pagina=true";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Informes</a>
							
						</td>
					</tr>	
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=imprimir_recibo";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=lista";
							$variable.="&no_pagina=true";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Imprimir Recibos</a>
							
						</td>
					</tr>	
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=verificar_pago";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=lista";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Verificar Pagos</a>
							
						</td>
					</tr>		
					<tr>
						<td>
						<br>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>