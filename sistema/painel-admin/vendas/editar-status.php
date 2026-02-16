<?php

require_once("../../../conexao.php"); 

$id = $_POST['txtid2'];
$status = $_POST['status2'];
$rastreio = $_POST['rastreio'];

$texto = 'Mudança de status no pedido, pedido '.$status;

if($status == 'Enviado'){
	$texto = 'Seu pedido foi Enviado, o código de rastreio é '. $rastreio;
	if($rastreio == ""){
		echo 'Preencha o código de Rastreio!';
		exit();
	}
}



$pdo->query("UPDATE vendas SET status = '$status', rastreio = '$rastreio' WHERE id = '$id'");


$pdo->query("INSERT mensagem SET id_venda = '$id', texto = '$texto', usuario = 'Admin', data = curDate(), hora = curTime()");



$res2 = $pdo->query("SELECT * FROM vendas where id = '$id'");
$dados2 = $res2->fetchAll(PDO::FETCH_ASSOC);
$id_usuario = $dados2[0]['id_usuario'];	

$res2 = $pdo->query("SELECT * FROM usuarios where id = '$id_usuario'");
$dados2 = $res2->fetchAll(PDO::FETCH_ASSOC);
$email_usuario = $dados2[0]['email'];	
$cpf_usuario = $dados2[0]['cpf'];

$res2 = $pdo->query("SELECT * FROM clientes where cpf = '$cpf_usuario'");
$dados2 = $res2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $dados2[0]['telefone'];	


//ENVIAR EMAIL PARA O CLIENTE INFORMANDO DA COMPRA
$destinatario = $email_usuario;
$assunto = $nome_loja . utf8_decode(' - Atualização no Status da Sua Compra');
$mensagem = utf8_decode('Seu pedido teve uma nova atualização, pedido '.$status);
$cabecalhos = "From: ".$email;
@mail($destinatario, $assunto, $mensagem, $cabecalhos);


echo 'Editado com Sucesso!!';


//enviar whatsapp
if($token != "" and $status == 'Enviado'){
//enviar dados da compra via whatsapp cliente
$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$link_envio = 'https://rastreamento.correios.com.br/app/index.php';

$mensagem = '*'.$nome_loja.'* %0A';
$mensagem .= '_Seu pedido foi enviado!_ %0A';
$mensagem .= '*Código de Rastreio* '.$rastreio.' %0A%0A';
$mensagem .= '_Acompanhe seu pedido no link abaixo_ %0A';
$mensagem .= $link_envio;
require("../../../api_whatsapp/texto.php");
}


if($token != "" and $status != 'Enviado'){
//enviar dados da compra via whatsapp cliente
$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

$mensagem = '*'.$nome_loja.'* %0A';
$mensagem .= '_Seu pedido teve uma nova atualização_ %0A';
$mensagem .= '✅ *Pedido '.$status.'* %0A%0A';
$mensagem .= '_Obrigado por comprar conosco!_';
require("../../../api_whatsapp/texto.php");
}

?>