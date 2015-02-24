{% extends app.request.isXmlHttpRequest ? "" : "simple.twig.html" %}

{% block titulo_bloque1 %}
	NOVEDADES DE NOTAS
{% endblock %}
{% block contenido_bloque1 %}
	<fieldset>
		<legend>Consultar Estudiante</legend>
		
		<form action='index.php' id='form_consulta' name='form_consulta'>
			<table border='0' style='font-size:10pt; margin: auto;'>
					<tr>
						<td COLSPAN='2'>Código</td>			
						<td>Apellidos y Nombres</td>
						<td>Pensum</td>
						<td>Identificación</td>	
						<td></td>
					</tr>
					<tr>
						<td COLSPAN='2'><input type='text' name='filtroEstudiante' class='digits' maxlength='12' value='{{ filtro.filtroEstudiante }}' /></td>			
						<td><input type='text' name='filtroNombre'  maxlength='50' value='{{ filtro.filtroNombre }}' /></td>
						<td><input type='text' name='filtroPlan'   maxlength='3' size='3'  value='{{ filtro.filtroPlan }}'  /></td>	
						<td><input type='text' name='filtroIdentificacion'  maxlength='20'  value='{{ filtro.filtroIdentificacion }}' /></td>				
						<td ROWSPAN='3'><input type='submit'  class='submit'  name='log' style="height='100px' background:url('estilo/templates/template_1/images/search.png')" value='Consultar'/></td>
						<td></td>
					</tr>
					<tr>
						<td>Carrera</td>	
						<td>
							<select style="width:70px" id="filtroCodCarrera" name="filtroCodCarrera">
								{% for carrera in carreras %}
									{% if  filtro.filtroCodCarrera  == carrera.0 %}
										<option type='text' size='5' value='{{carrera.0}}' selected >{{carrera.0}}</option>
									{% else %}
										<option type='text' size='5' value='{{carrera.0}}' >{{carrera.0}}</option>
									{% endif %}
								{% endfor %}
							</select>
						</td>
						<td></td>
						<td>Estado</td>
						<td><input type='text' name='filtroEstado'  size='2' maxlength='1'   value='{{ filtro.filtroEstado }}' /></td>
						<td>{{textoestado}}</td>
					</tr>			
			</table>
			<input type='hidden' name='action' value='admin_novedadesNotas'>
			<input type='hidden' name='opcion' value='consultarRegistros'>
		</form>
	</fieldset>
{% endblock %}

{% block mensaje %}
	
	
	{% for linea in mensaje %}
		<span class='ui-state-error ui-corner-all'>{{ linea }}</span><br/><br/>
	{% endfor %}
	


	
	{% if msgConfirm.0 %}
		<form action='index.php' id='caja_confirmacion' name='caja_confirmacion'>
			<div class="alert_box">
				{{ msgConfirm.0 }}
				<center>
					<a href="{{ msgConfirm.1 }}">SI</a>
					<a href="{{ msgConfirm.2 }}">NO</a>
				</center>
			</div>	
		</form>
	{% endif %}
			
			
	<b style='color:blue'>
		{% if URLregistroAnterior %}
			<a href="{{URLregistroAnterior}}">< Anterior</a> 
		{% endif %}
		{% if totalRegistros %}
			Estudiante {{ registroActual }} de {{ totalRegistros }} 
		{% endif %}	
			
		{% if URLregistroSiguiente %}	
			<a href="{{URLregistroSiguiente}}"> Siguiente ></a>
		{% endif %}
		
	</b>
{% endblock %}

{% block contenido_bloque2 %}
	
	{% if totalRegistros %}
	<fieldset>
		<legend>REGISTRO ACTUAL:<b style='color:#444444; font-size:14pt'> CÓDIGO: {{ notas.0.0.11 }} NOMBRE: {{ notas.0.0.12 }}</b></legend>
		<center>
		<br/>
			<div class="formularioInsertarNota" style="display:none">
			
				<form action='index.php' id='form_insertar' name='form_insertar'>
					
					<table class="content_box">
						<tr><th><a onclick="javascript:$('.formularioInsertarNota').hide();" style="cursor:pointer">Cerrar</a></th></tr>
						<tr><th colspan='7' ><span id="nombreAsignaturaInsertar"></span></th></tr>
						<tr><td>Asignatura</td>
							<td>Gr</td>
							<td>Sem</td>
							<td>Año</td>
							<td>Per</td>
							<td>Nota</td>
							
							{% if notas.0.0.13=='S' %}
								<td>#Cred</td>
								<td>#HTD</td>
								<td>#HTC</td>
								<td>#AUT</td>
								<td>Clasificación</td>	
							{% endif %}
							
							{% if notas.0.0.13=='N' %}
								<td>#HT</td>
								<td>#HP</td>
							{% endif %}		
							
							<td>Obs</td>
							<td>Est</td>
							<td></td>
						</tr>
						<tr style='background:#FFFFFF'>
							<td><input type='text' class="required digits" size='5' id="asignatura" name="asignatura" onchange="javascript:consultarAsignatura($(this).val())" ></td>
							<td><input type='text' class="digits" size='2' id="grupo" maxlenght="3" name="grupo" value="0"></td>
							<td><input type='text' class="{required: true, range: [0,20]}" size='2' id="semestre" name="semestre" ></td>
							<td><input type='text' class="{digits: true, required: true, range: [1900,2200]}" size='2' id="anio" name="anio" ></td>
							<td><input type='text' class="{digits: true, required: true, range: [1,3]}" size='2' id="per" name="per" ></td>
							<td><input type='text' class="{digits: true, required: true, range: [0,50]}" size='2' id="nota" name="nota" ></td>
							
							{% if notas.0.0.13=='S' %}
								<td><input type='text' class="{digits: true, required: true, range: [0,99]}" size='2' id="creditos" name="creditos"  value="" ></td>
								<td><input type='text' class="{digits: true, required: true, range: [0,99]}" size='2' id="hteoricas" name="hteoricas"  value=""  ></td>
								<td><input type='text' class="{digits: true, required: true, range: [0,99]}" size='2' id="hpracticas" name="hpracticas"  value=""  ></td>
								<td><input type='text' class="{digits: true, required: true, range: [0,99]}" size='2' id="hautonomo" name="hautonomo"  value=""  ></td>
								<td>
									<select style="width:45px" id="ceacod" name="ceacod">
										{% for clasificacion in clasificaciones %}
											<option type='text' size='3' value='{{clasificacion.0}}' >{{clasificacion.2}} - {{clasificacion.1}} </option>
										{% endfor %}
									</select>	
								</td>
							{% endif %}

							{% if notas.0.0.13=='N' %}
								<td><input type='text' class="{digits: true, required: true, range: [0,99]}" size='2' id="hteoricas" name="hteoricas" ></td>
								<td><input type='text' class="{digits: true, required: true, range: [0,99]}" size='2' id="hpracticas" name="hpracticas" ></td>
							{% endif %}

							
							<td>
								<select style="width:35px" id="obs" name="obs">
									{% for observacion in observaciones %}
										{% if registro.7 == observacion.0 %}
											<option type='text' size='3' value='{{observacion.0}}' selected >{{observacion.0}} - {{observacion.1}} </option>
										{% else %}
											<option type='text' size='3' value='{{observacion.0}}' >{{observacion.0}} - {{observacion.1}} </option>
										{% endif %}
									{% endfor %}
								</select>	
							</td>
							<td>
								A	
							</td>
							<td>
								<button onclick='$(this).submit()' value='Modificar' style="width:45px; height:45px">
									<img src='estilo/templates/template_1/images/save.png'>
								</button>
							</td>
						</tr>
					</table>
						
					<input type='hidden' size='2' id="action" name='action' value='admin_novedadesNotas'> <!--action-->
					<input type='hidden' size='2' id="opcion" name='opcion' value='insertarNota'> <!--action-->
					<input type='hidden' size='2' id="estudiante" name='estudiante' value='{{ notas.0.0.11 }}'> <!--action-->
					<input type='hidden' size='2' id="carrera" name='carrera' value='{{ notas.0.0.10 }}'> 
					<input type='hidden' name='filtroEstudiante' value='{{ filtro.filtroEstudiante }}' />		
					<input type='hidden' name='filtroNombre' value='{{ filtro.filtroNombre }}'/>
					<input type='hidden' name='filtroPlan'  value='{{ filtro.filtroPlan }}' />	
					<input type='hidden' name='filtroIdentificacion' value='{{ filtro.filtroIdentificacion }}'/>			
					<input type='hidden' name='filtroCodCarrera' value='{{ filtro.filtroCodCarrera }}'/>
					<input type='hidden' name='filtroCarrera' value='{{ filtro.filtroCarrera }}'/>
					<input type='hidden' name='filtroEstado'  value='{{ filtro.filtroEstado }}' />
					<input type='hidden' name='registroActual'  value='{{ registroActual }}' />
					
				</form>
			</div>
				
			<a onclick="javascript:$('.formularioInsertarNota').show();" style="cursor:pointer">
				<img src='estilo/templates/template_1/images/save.png'>
			</br>ADICIONAR NOVEDAD DE NOTA
			</a>
		
		</br>
		</br>
		</center>
		
		{% if notas.0.0.0 %}
		
		<table class="content_box" style="margin: auto;" >
			<tr>
			<td>Asignatura</td>
			<td>Gr</td>
			<td>Sem</td>
			<td>Nombre de la Asignatura</td>
			<td>Año</td>
			<td>Per</td>
			<td>Nota</td>
			{% if notas.0.0.13=='S' %}
				<td>Cred</td>
				<td>Ht</td>
				<td>Hp</td>
				<td>Aut</td>
			{% endif %}
			<td>Observación</td>
			<td>Est</td>
			<td></td>
			</tr>
			
			{% for registro in notas %}
			
				<form action='index.php'>
			
					<tr id='reg_{{registro.9}}' style='background:#FFFFFF'>

						<input type='hidden' name='filtroEstudiante' value='{{ filtro.filtroEstudiante }}' />		
						<input type='hidden' name='filtroNombre' value='{{ filtro.filtroNombre }}'/>
						<input type='hidden' name='filtroPlan'  value='{{ filtro.filtroPlan }}' />	
						<input type='hidden' name='filtroIdentificacion' value='{{ filtro.filtroIdentificacion }}'/>			
						<input type='hidden' name='filtroCodCarrera' value='{{ filtro.filtroCodCarrera }}'/>
						<input type='hidden' name='filtroCarrera' value='{{ filtro.filtroCarrera }}'/>
						<input type='hidden' name='filtroEstado'  value='{{ filtro.filtroEstado }}' />
						<input type='hidden' name='registroActual'  value='{{ registroActual }}' />

					
						<input type='hidden' size='2' id="carrera" name="carrera" value='{{ registro.10 }}'> <!--carrera-->
						<input type='hidden' size='2' id="estudiante" name="estudiante" value='{{ registro.11 }}'> <!--estudiante-->
						<input type='hidden' size='2' id="asignatura" name="asignatura" value='{{ registro.0 }}'> <!--asignatura-->
						<input type='hidden' size='2' id="anio" name="anio" value='{{ registro.4 }}'> <!--año-->
						<input type='hidden' size='2' id="per" name="per" value='{{ registro.5 }}'> <!--periodo-->
					
						<input type='hidden' size='2' id="nota_org" name="nota_org" value='{{ registro.6 }}'> <!--nota original-->
						<input type='hidden' size='2' id="obs_org" name="obs_org" value='{{ registro.7 }}'> <!--observacion original-->
						<input type='hidden' size='2' id="estado_org" name="estado_org" value='{{ registro.8 }}'> <!--estado original-->
						<input type='hidden' size='2' id="creditos_org" name="creditos_org" value='{{ registro.14 }}'></td>
						<input type='hidden' size='2' id="hteoricas_org" name="hteoricas_org" value='{{ registro.15 }}'></td>
						<input type='hidden' size='2' id="hpracticas_org" name="hpracticas_org" value='{{ registro.16 }}'></td>
						<input type='hidden' size='2' id="hautonomo_org" name="hautonomo_org" value='{{ registro.17 }}'></td>
						
						<input type='hidden' size='2' id="action" name='action' value='admin_novedadesNotas'> <!--action-->
						<input type='hidden' size='2' id="opcion" name='opcion' value='actualizarNota'> <!--action-->
						
						
						<td>{{ registro.0 }}</td>
						<td>{{ registro.1 }}</td>
						<td>{{ registro.2 }}</td>
						<td>{{ registro.3 }}</td>
						<td>{{ registro.4 }}</td>
						<td>{{ registro.5 }}</td>
						<td>
							<input type='text' size='2' id="nota" name="nota" value='{{ registro.6 }}'>
						</td>
						{% if notas.0.0.13=='S' %}
							<td><input type='text' size='2' id="creditos" name="creditos" value='{{ registro.14 }}'></td>
							<td><input type='text' size='2' id="hteoricas" name="hteoricas" value='{{ registro.15 }}'></td>
							<td><input type='text' size='2' id="hpracticas" name="hpracticas" value='{{ registro.16 }}'></td>
							<td><input type='text' size='2' id="hautonomo" name="hautonomo" value='{{ registro.17 }}'></td>
						{% endif %}
						<td>
							<select style="width:35px" id="obs" name="obs">
								{% for observacion in observaciones %}
									{% if registro.7 == observacion.0 %}
										<option type='text' size='2' value='{{observacion.0}}' selected >{{observacion.0}} - {{observacion.1}} </option>
									{% else %}
										<option type='text' size='2' value='{{observacion.0}}' >{{observacion.0}} - {{observacion.1}} </option>
									{% endif %}
								{% endfor %}
							</select>	
						</td>
						
						<td>
							<select style="width:35px" id="estado" name="estado" value='{{ registro.8 }}'> 
									{% if registro.8 == 'A' %}
										<option type='text' size='2' value='A' selected >A</option>
									{% else %}
										<option type='text' size='2' value='A' >A</option>
									{% endif %}
									{% if registro.8 == 'I' %}
										<option type='text' size='2' value='I' selected >I</option>
									{% else %}
										<option type='text' size='2' value='I' >I</option>
									{% endif %}
							</select>		
						</td>
						<td>
							<button onclick='$(this).submit()' value='Modificar' style="width:45px; height:45px">
								<img src='estilo/templates/template_1/images/save.png'>
							</button>
						</td>
					</tr>
				</form>
			{% endfor %}
		</table>
		{% endif %}
		
	</fieldset>
	{% endif %}
	<h3>Recomendaciones para su consulta:</h3>
	<ul class="tmo_list">
		<li>Solo podrá consultar los estudiantes que pertenecen a su(s) Proyecto(s) Curriculare(s).</li>
		<li>El único parámetro obligatorio es el código de la CARRERA.</li>
		<li>Puede colocar uno o más parámetros de búsqueda Ejemplos:.</li>
			<ul>
				<li><b>Consulta 1:</b>Carrera: 32 <b>Resultado:</b> Arrojará todos los estudiantes de la carrera 32</li>
				<li><b>Consulta 2:</b>Carrera: 20 Estado: J  <b>Resultado:</b> Arrojará todos los estudiantes de la carrera 20 en estado J</li>
				<li><b>Consulta 3:</b>Carrera: 7 Estado: A  Pensum:1 <b>Resultado:</b> Arrojará todos los estudiantes de la carrera 7 en estado A y del Pensum 1</li>
			</ul>
		<li>Los parámetros de búsqueda pueden ser exactos o aproximados Ejemplos:.</li>
			<ul>
				<li><b>Consulta 1:</b>Carrera: 25 Código: 20032025075 <b>Resultado:</b> Arrojará únicamente el estudiante que corresponde al código 20032025075</li>
				<li><b>Consulta 2:</b>Carrera: 7 Código: 20051007  <b>Resultado:</b> Arrojará todos los estudiantes de la carrera 7 cuyo código contenga los digitos 20051007 es decir los estudiantes matriculados en el periodo 2005-1</li>
				<li><b>Consulta 3:</b>Carrera: 14 Apellidos y Nombres: DIANA  Pensum:1 <b>Resultado:</b> Arrojará todos los estudiantes de la carrera 14 cuyo nombre o apellido sea DIANA</li>
			</ul>		
	</ul>
{% endblock %}


