<?php
/**
 * TransaccionWSService class file
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */

/**
 * creaTransaccion class
 */
require_once 'creaTransaccion.php';
/**
 * transaccion class
 */
require_once 'transaccion.php';
/**
 * creaTransaccionResponse class
 */
require_once 'creaTransaccionResponse.php';
/**
 * actualizarTransaccion class
 */
require_once 'actualizarTransaccion.php';
/**
 * actualizarTransaccionResponse class
 */
require_once 'actualizarTransaccionResponse.php';

/**
 * TransaccionWSService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class TransaccionWSService extends SoapClient {

  public function TransaccionWSService($wsdl = "TransaccionWS.wsdl", $options = array()) {
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param actualizarTransaccion $parameters
   * @return actualizarTransaccionResponse
   */
  public function actualizarTransaccion(actualizarTransaccion $parameters) {
    return $this->__call('actualizarTransaccion', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'https://oas.udistrital.edu.co/reportePagoUD/ws/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param creaTransaccion $parameters
   * @return creaTransaccionResponse
   */
  public function creaTransaccion(creaTransaccion $parameters) {
    return $this->__call('creaTransaccion', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'https://oas.udistrital.edu.co/reportePagoUD/ws/',
            'soapaction' => ''
           )
      );
  }

}

?>
