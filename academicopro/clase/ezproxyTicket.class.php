<?php

// Esta clase PHP puede ser usada para generar tickets para ezproxy.
// Cuando se crear una nueva opcion de ticket ezproxy, el primer parametro
// es la URL base del servidor ezproxy
// el segundo es la palabra secreta compartida que aparece en el user.txt del ezproxy
// y el ultimo es opcional, este especifica algun grupo  de usuarios ezproxy que pueden estar asocidos a el 
// 
// If usted utiliza como palabra secreta 'shhh' entonces en el archivo user.txt debe aparecer como sigue 
// 
//      ::Ticket
//      MD5 shhhh
//      /Ticket
// 
// para permitor que ezproxy reconozca el ticket
//
// Una vez el objeto es creado, usted puede llamar su metodo url con una
// base de datos url para generar el ticket

class EZproxyTicket {
  var $EZproxyStartingPointURL;

  function EZproxyTicket(
    $EZproxyServerURL,
    $secret,
    $user,
    $groups = "")
  {
    if (strcmp($secret, "") == 0) {
      echo("EZproxyURLInit secret cannot be blank");
      exit(1);
    }

    $packet = '$u' . time();
    if (strcmp($groups, "") != 0) {
      $packet .=  '$g' . $groups;
    }
    $packet .= '$e';
    $EZproxyTicket = urlencode(md5($secret . $user . $packet) . $packet);
    $this->EZproxyStartingPointURL = $EZproxyServerURL . "/login?user=" . 
      urlencode($user) . "&ticket=" . $EZproxyTicket;
  }

  function URL($url)
  {
    return $this->EZproxyStartingPointURL . "&url=" . $url;
  }
}
?>

