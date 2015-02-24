<?
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=".$_REQUEST['archivo'].".xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 

	/*include_once("../clase/excel.class.php");
	include_once("../clase/excel-ext.class.php");	*/	

	$filas=file("listados/".$_REQUEST['archivo'].".txt");
	$salida=array();
	$i=0;
	echo "<table>";
	while($filas[$i]!=NULL){
		$row=$filas[$i];
		$sql=explode(",",$row);
		echo "<tr>";
		foreach($sql as $clave){
			echo "<td>".$clave."</td>";
		}
		echo "</tr>";
	$i++;
	}
	echo "</table>";
?>