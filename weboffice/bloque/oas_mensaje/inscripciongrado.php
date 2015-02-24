<?
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

?><table align="center" class="tablaMarco">
	<tbody>
		<tr>
			<td >
				<table class="tablaMarco">
					<tbody>
						<tr class=texto_elegante >
							<td>
							<b>::::..</b>  Inscripci&oacute;n para Grados
							<hr class=hr_subtitulo>
							</td>
						</tr>
						<tr class="bloquecentralcuerpo">
							<td valign="top" colspan=2>
							<p>El <span class="texto_negrita">m&oacute;dulo de Inscripci&oacute;n para Grados</span> de CONDOR 
							le ofrece una interfaz Web para que pueda, de forma din&aacute;mica, realizar el proceso de Grado.</p>							
							</td>							
						</tr>
						<tr class="bloquecentralcuerpo">
							<td valign="top">
								<p>Tenga en cuenta que la informaci&oacute;n que suministre debe ser &iacute;ntegra y completa pues a partir
								de ella se generer&aacute;n los documentos oficiales de su grado - Actas, Diplomas y Certificados.</p>
								<p>Una vez diligenciado en formulario de inscripci&oacute;n lleve a la Secretaria Acad&eacute;mica de su Facultad
								los soportes y documentaci&oacute;n requerida.</p>
								<p class="texto_negrita">
								Importante!!!<hr class="hr_subtitulo">
								</p>
								<p>
								Lea completamente las instrucciones de diligenciamiento del formulario de inscripci&oacute;n antes de presentar su solicitud.
								</p>
								<p>
								<table class="tablaImportante">
									<tr class="bloquecentralcuerpo">
										<td class="centrar">										
										<a href="<? echo $configuracion['host'].$configuracion['site'].$configuracion['documento']?>/inscripcionGrado.pdf"><img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfGrande.png"?>" /><hr class="hr_subtitulo">Archivo PDF</a>
										</td>
										<td>
										<a href="<? echo $configuracion['host'].$configuracion['site'].$configuracion['documento']?>/inscripcionGrado.pdf">Intrucciones para llenar el formulario.</a>
										</td>
									</tr>
								</table>
								</p>
								<table class="tablaMarco">
									<tr class="bloquecentralcuerpo">
										<td class="centrar">										
										<a href="http://ingenieria.udistrital.edu.co/moodle/mod/resource/view.php?id=4276"><img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/recursoWeb.png"?>" /><hr class="hr_subtitulo">Enlace Web</a>
										</td>
										<td>
										<a href="http://ingenieria.udistrital.edu.co/moodle/mod/resource/view.php?id=4276">Requisitos para Grado Facultad de Ingenier&iacute;a.</a>
										</td>
									</tr>
								</table>
								</p>								
							</td>
							<td class="centrar">
								<p>
								<img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/" ?>grado.png">
								</p>
							</td>
						</tr>
					</tbody>
				</table>

			</td>
		</tr>
	</tbody>
</table>

