<?php
  $url = "http://api.wordmensagens.com.br/send-text";

  $data = array('instance' => "54T270623112109OWN96",
                'to' => "5531995348118",
                'token' => "DBFY7-5NP-090U0",
                'message' => "Mensagem a ser Enviada");


  $options = array('http' => array(
                 'method' => 'POST',
                 'content' => http_build_query($data)
  ));

  $stream = stream_context_create($options);

  $result = @file_get_contents($url, false, $stream);

  echo $result;
?>
  