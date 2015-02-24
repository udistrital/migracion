<?PHP
ob_start();
if($_POST['n1'] != " " && $_POST['p1'] == " "){
   die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del primer parcial.</u></font></b></p>');
   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   exit;
}
elseif($_POST['n2'] != " " && $_POST['p2'] == " "){
       die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del segundo parcial.</u></font></b></p>');
	   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   	   exit;
}
elseif($_POST['n3'] != " " && $_POST['p3'] == " "){
       die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del tercer parcial.</u></font></b></p>');
	   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   	   exit;
}
elseif($_POST['n4'] != " " && $_POST['p4'] == " "){
       die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del cuarto parcial.</u></font></b></p>');
	   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   	   exit;
}
elseif($_POST['n5'] != " " && $_POST['p5'] == " "){
       die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del quinto parcial.</u></font></b></p>');
	   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   	   exit;
}
elseif($_POST['la'] != " " && $_POST['pl'] == " "){
       die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del laboratorio.</u></font></b></p>');
	   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   	   exit;
}
elseif($_POST['ex'] != " " && $_POST['pe'] == " "){
       die('<p align="center"><b><font color="#FF0000"><u>Digite el porcentaje del examen.</u></font></b></p>');
	   echo"<p align='center'><b><font size='2' color='#0000FF' face='Tahoma'><a OnMouseOver='history.go(-1)'>...: Regresar :...</a></font></b>";
   	   exit;
}
ob_end_flush();
?>