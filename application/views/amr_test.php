<?php
  // $headers = array(
  //   'Content-Type:application/json',
  //   'Authorization: Basic '. base64_encode("3802/6:IUUIASUX") // <---
  // );
  $login = '3802/6';
  $password = 'IUUIASUX';
  $url = 'http://www.amr.org.ar/gestion/webServices/autorizador/test/profesiones';
  // $url = 'http://www.amr.org.ar/gestion/webServices/autorizador/v3/ambulatorio/realizadas?codigoConvenio=5&fecha=2016/06/24';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
  $result = curl_exec($ch);
  curl_close($ch);
  echo($result);

  /*

  curl -D- -X GET -H "Authorization: Basic MzgwMi82OklVVUlBU1VY" -H "Content-Type: application/json" https://www.amr.org.ar/gestion/webServices/autorizador/test/convenios

  */
?>
