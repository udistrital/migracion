
 <table class='contenidotabla centrar'>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='{{formulario}}'>
        <tr align="center">
          <td class="centrar" colspan="4">
            <h4>Digite el c&oacute;digo del estudiante que desea consultar</h4>
          </td>
        </tr>
         <tr align="center">
          <td class="centrar" colspan="4">
            <input type="text" name="codEstudiante" size="11" maxlength="11">
            <input type="hidden" name="opcion" value="validar">
            <input type="hidden" name="action" value="{{formulario}}">
            <input type="hidden" name="codProyecto" value="{{a}}">
            <input type="hidden" name="planEstudio" value="{{b}}">
            <input type="hidden" name="nombreProyecto" value="{{c}}">
            <input type="button" name="Consultar" value="Consultar" onclick="if('{verificar}'){document.forms['{{formulario}}'].submit()}else{false}">
             
          </td>
        </tr>
        <tr>
          <td>
            <hr align="center">
          </td>
        </tr>
      </form>
    </table>
