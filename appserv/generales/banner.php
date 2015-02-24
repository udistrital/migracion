<?PHP
$numbanners = 4; //numero de imagenes que se rotarán
$random = rand(1,$numbanners);

$img = array();
$url = array();
$alt = array();

$img[1] = "img/cdr1.jpg";
$url[1] = "http://www.enlace1.com";
$alt[1] = "Aplicación CONDOR";

$img[2] = "img/cdr2.jpg";
$url[2] = "http://www.enlace2.com";
$alt[2] = "Aplicación CONDOR";

$img[3] = "img/cdr3.jpg";
$url[3] = "http://www.enlace3.com";
$alt[3] = "Aplicación CONDOR";

$img[4] = "img/cdr4.jpg";
$url[4] = "http://www.enlace4.com";
$alt[4] = "Aplicación CONDOR";

echo "<p align='center'><a href='$url[$random]' target='_blank'><img src='$img[$random]' alt='$alt[$random]' width='44' height='41 border='0'></a></p>";
?>