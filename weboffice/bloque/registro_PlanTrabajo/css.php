
<style>

      .celda_hora{
      	  font-family:Arial, Helvetica, sans-serif;
	  padding:2px;
	  font-size:10px;
	  background: #ffffff; /* Old browsers */
	  background: -moz-linear-gradient(top,  #ffffff 0%, #f3f3f3 50%, #ededed 51%, #ffffff 100%); /* FF3.6+ */
	  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(50%,#f3f3f3), color-stop(51%,#ededed), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
	  background: -webkit-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
	  background: -o-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Opera 11.10+ */
	  background: -ms-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* IE10+ */
	  background: linear-gradient(to bottom,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* W3C */
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
	  border: 1px solid #DDDDDD;
      }
  
      .celda_titulo_horario{
	  background: #f2f6f8; /* Old browsers */
	  background: -moz-linear-gradient(top,  #f2f6f8 0%, #d8e1e7 50%, #b5c6d0 51%, #e0eff9 100%); /* FF3.6+ */
	  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f2f6f8), color-stop(50%,#d8e1e7), color-stop(51%,#b5c6d0), color-stop(100%,#e0eff9)); /* Chrome,Safari4+ */
	  background: -webkit-linear-gradient(top,  #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%); /* Chrome10+,Safari5.1+ */
	  background: -o-linear-gradient(top,  #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%); /* Opera 11.10+ */
	  background: -ms-linear-gradient(top,  #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%); /* IE10+ */
	  background: linear-gradient(to bottom,  #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%); /* W3C */
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f6f8', endColorstr='#e0eff9',GradientType=0 ); /* IE6-9 */
	  height: 40px;

      }
      
      .legend{
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f0f9ff+0,cbebff+47,a1dbff+100;Blue+3D+%2313 */
            background: #f0f9ff; /* Old browsers */
            background: -moz-linear-gradient(top,  #f0f9ff 0%, #cbebff 47%, #a1dbff 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top,  #f0f9ff 0%,#cbebff 47%,#a1dbff 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom,  #f0f9ff 0%,#cbebff 47%,#a1dbff 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f0f9ff', endColorstr='#a1dbff',GradientType=0 ); /* IE6-9 */
            padding-left: 10px;
            padding-right: 10px;
            border: 2px solid #DDDDDD;
            
      }
      .fieldset{
	  background: #EFF5FB;
              /*d6dbe1;*/
      }
 
      .celda_tit_hor_disp{
	  background: ""; /* Old browsers */
      }
      
      .celda_tit_carga{
	  background: #FFFFCC; /* Old browsers */
      }
      
      .celda_dia_hora{
	  background: #D0D0D0; /* Old browsers */
      }
      
      .celda_tit_hor_no_disp{
	  background: #E0F5FF; /* Old browsers */
      }
      
      #info_salon{
	  height:50px;
	  width:50px;
	  display:none;
	  background: yellow;
      
      }
      
      .encabezado_curso{
	  background-color: #ffffff;
	  -moz-border-radius: 10px;
	  -webkit-border-radius: 10px;
	  border-radius: 10px;
	  /*IE 7 AND 8 DO NOT SUPPORT BORDER RADIUS*/
	  -moz-box-shadow: 0px 0px 3px #000000;
	  -webkit-box-shadow: 0px 0px 3px #000000;
	  box-shadow: 0px 0px 3px #000000;
	  /*IE 7 AND 8 DO NOT SUPPORT BLUR PROPERTY OF SHADOWS*/
	  font-size: 8pt;
	  padding: 0px;
	  text-align: center;
      
      }
      .celda_curso{
	  font-size: 8pt;
	  padding: 0px;
          border: 1px solid #DDDDDD;
      }
      
      .encabezado_curso_salon{
	  background-color: #ffffff;
	  -moz-border-radius: 10px;
	  -webkit-border-radius: 10px;
	  border-radius: 10px;
	  /*IE 7 AND 8 DO NOT SUPPORT BORDER RADIUS*/
	  -moz-box-shadow: 0px 0px 3px #000000;
	  -webkit-box-shadow: 0px 0px 3px #000000;
	  box-shadow: 0px 0px 3px #000000;
	  /*IE 7 AND 8 DO NOT SUPPORT BLUR PROPERTY OF SHADOWS*/
	  font-size: 8pt;
	  padding: 10px;
	  text-align: center;
      
      }
      
      
      .float-left{
	  float:left;
      }
      
      .float-right{
	  float:right;
      }
      
      .float-inherit{
	  float:inherit;
          text-align: left;
      }
      
      .tabla_general {
	  width: 100%;
      }
      
       .borrar_horario{
	  cursor:pointer;
	  display:block;
	  float: right;
	  font-size: 12pt;
       }
       
       .contenido_horario{
	  cursor:pointer;
	  display:block;
	  float: left;
	  height: 100%;
	  width: 90%;
       }

        table.contentabla{
         
         font-size: 12;
         font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
         text-align: justify;
         border-collapse: collapse;
         border-spacing: 0px;	
        }

        table.contentablaCopia{
         
         font-size: 14;
         font-family: "Arial", Verdana, Trebuchet MS, Helvetica, sans-serif;
         text-align: justify;
         border-collapse: collapse;
         border-spacing: 0px;	
        }

    .centrar {
        text-align: center;
        }

       
       h2 {
	  background-position: center center;
	  color: #000000;
	  font-family: Arial;
	  font-size: 10pt;
	  text-align: center;
      }
</style>
