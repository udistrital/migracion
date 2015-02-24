<html>
<head>
<title>Calculadora</title>
<script language="JavaScript" src="calc.js"></script>
</head>
<body topmargin="10" leftmargin="0" bgColor="#ECE9D8">
<?PHP
echo '<center>
<FORM NAME="scicalc">
<TABLE cellspacing="0" border="1" bordercolorlight="#FFFFFF" bordercolordark="#006600">
<TR>
<TD COLSPAN="5" ALIGN="middle"><INPUT NAME="display" VALUE="0" SIZE="25" MAXLENGTH="25" style="text-align: right; color: #000000; background-color: #A0B395; font-size: 10 pt; font-weight: bold"></TD>
</TR>
<TR>
<TD ALIGN="middle" colspan="3">
  <p align="center"><INPUT TYPE="button" VALUE="  Retroceso  " ONCLICK="deleteChar(this.form.display)" style="color: #FF0000; font-size: 10 pt; font-family: Tahoma"></p>
</TD>
<TD ALIGN="middle" colspan="2">
  <p align="center"><INPUT TYPE="button" VALUE="  Borrar " ONCLICK="this.form.display.value = 0 " style="color: #FF0000; font-size: 10 pt; font-family: Tahoma"></p>
</TD>
</TR>
<TR>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  ln  " ONCLICK="if (checkNum(this.form.display.value)) { ln(this.form) }"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE=" sin " ONCLICK="if(checkNum(this.form.display.value)) { sin(this.form) }"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="cos" ONCLICK="if(checkNum(this.form.display.value)) { cos(this.form) }"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="tan " ONCLICK="if(checkNum(this.form.display.value)) { tan(this.form) }"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="exp" ONCLICK="if (checkNum(this.form.display.value)) { exp(this.form) }"></TD>
</TR>
<TR>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE=" sq  " ONCLICK="if (checkNum(this.form.display.value)) { square(this.form) }"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   (   " ONCLICK="addChar(this.form.display, '(')"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   )   " ONCLICK="addChar(this.form.display, ')')"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  pi  " ONCLICK="if(checkNum(this.form.display.value)) { pi(this.form) }"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE=" x^2" ONCLICK="if(checkNum(this.form.display.value)) { pow2(this.form) }"></TD>
</TR>
<TR>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  7  " ONCLICK="addChar(this.form.display, '7')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  8  " ONCLICK="addChar(this.form.display, '8')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  9  " ONCLICK="addChar(this.form.display, '9')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   /   " ONCLICK="addChar(this.form.display, '/')" style="color: #FF0000"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="sqrt" ONCLICK="if (checkNum(this.form.display.value)) { sqrt(this.form) }" style="color: #0000FF; font-family: Tahoma"></TD>
</TR>
<TR>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  4  " ONCLICK="addChar(this.form.display, '4')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  5  " ONCLICK="addChar(this.form.display, '5')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  6  " ONCLICK="addChar(this.form.display, '6')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   *   " ONCLICK="addChar(this.form.display, '*')" style="color: #FF0000"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE=" % " NAME="porcen" ONCLICK="if(checkNum(this.form.display.value)) { compute2(this.form) }" style="color: #0000FF; font-family: Tahoma"></TD>
</TR>
<TR>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  1  " ONCLICK="addChar(this.form.display, '1')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  2  " ONCLICK="addChar(this.form.display, '2')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  3  " ONCLICK="addChar(this.form.display, '3')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   -   " ONCLICK="addChar(this.form.display, '-')" style="color: #FF0000"></TD>
<TD ALIGN="middle"><div align="center"><img src="../img/oas_block.gif" width="30" height="22"></div></TD>
</TR>
<TR>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="  0  " ONCLICK="addChar(this.form.display, '0')" style="font-weight: bold; color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE=" +/- " ONCLICK="changeSign(this.form.display)" style="color: #0000FF; font-family: Tahoma"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="    .   " ONCLICK="addChar(this.form.display, '.')" style="color: #FF0000"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   +  " ONCLICK="addChar(this.form.display, '+')" style="color: #FF0000"></TD>
<TD ALIGN="middle"><INPUT TYPE="button" VALUE="   =   " NAME="enter" ONCLICK="if(checkNum(this.form.display.value)) { compute(this.form) }" style="color: #FF0000"></TD>
</TR>
</TABLE>
</FORM>
</center>';
?>
</body>
</html>