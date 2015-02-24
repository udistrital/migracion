<table class="sigma_borde centrar" width="100%">
        <caption class="sigma">INSCRIPCIONES</caption>
        <tr class="centrar">
        <td colspan="2" class="sigma centrar"  width="50%">
 
            <a href="{{ruta}}">
            <img src="{{guia}}/solo.png" width="50" height="50" border="0" alt="Inscripcion por Estudiante">
            <br>Inscripci&oacute;n<br> por Estudiante
        </a>
    </td>
    <td colspan="2" class="sigma centrar" width="50%">
    
            
            <a href="{{ruta1}}">
                <img src="{{guia}}/personas.png" width="50" height="50" border="0" alt="Inscripcion por Grupo">
                <br>Inscripci&oacute;n<br> por Grupo
            </a>
        </td>

    </tr>
</table>


<!--{%if variable==1 %}
    <table class='sigma_borde centrar' align="center" width="80%" >
        <caption class="sigma">SELECCIONE EL PLAN DE ESTUDIOS</caption>
      {%for variable in resultado_proyectos %}
        {%if loop.last-1%}
        {%if resultado_proyectos[loop.index].CODIGONIVEL != resultado_proyectos[loop.index0].CODIGONIVEL%}
         <tr><th class="sigma_a centrar" colspan="4">NIVEL:<?echo $resultado_proyectos[$i]['NIVEL']?></th></tr>
        <th class="sigma centrar">Carrera</th>
        <th class="sigma centrar">Plan de Estudios</th>
        <th class="sigma centrar">Nombre</th>
        <th class="sigma centrar">Modalidad</th>
        {%endif%}
        {%endif%}
         <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
             {{enlace}}
            {%if loop.last-1%}
            {%if resultado_proyectos[loop.index0].CREDITOS == 'S'|trim%}
            {%endif%}
            {%endif%}
            {%if resultado_proyectos[loop.index0].CREDITOS == 'N'|trim%}
                <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $resultado_proyectos[$i]['PROYECTO']?></a></td>
                <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $resultado_proyectos[$i]['PLAN']?></a></td>
                <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $resultado_proyectos[$i]['NOMBRE']?></a></td>
                <td class="sigma centrar"><a href="<?echo $enlace?>"><?echo $modalidad?></a></td>
            </tr>
         {%endif%}   
        {%endfor%}
         </table>
        {% else %}
        
     <table class='sigma_borde centrar' align="center" width="80%" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <caption class="sigma">No tiene proyectos registrados para realizar inscripci&oacute;n</caption>
          </table>
      
        {%endif%}

-->
