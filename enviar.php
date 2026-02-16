<?php 

require_once("conexao.php");

$nome = filter_var(@$_POST['nome'], @FILTER_SANITIZE_STRING);
$email = filter_var(@$_POST['email'], @FILTER_SANITIZE_STRING);
$mensagem = filter_var(@$_POST['mensagem'], @FILTER_SANITIZE_STRING);
$telefone = filter_var(@$_POST['telefone'], @FILTER_SANITIZE_STRING);

if($_POST['nome'] == ""){
	echo 'Preecha o Campo Nome';
	exit();
}

if($_POST['email'] == ""){
	echo 'Preecha o Campo Email';
	exit();
}

if($_POST['mensagem'] == ""){
	echo 'Preecha o Campo Mensagem';
	exit();
}

$destinatario = $email;
$assunto = $nome_loja . ' - Email da Loja';
$mensagem = utf8_decode('Nome: '.$nome. "\r\n"."\r\n" . 'Telefone: '.$telefone. "\r\n"."\r\n" . 'Mensagem: ' . "\r\n"."\r\n" .$mensagem);
$cabecalhos = "From: ".$email;
mail($destinatario, $assunto, $mensagem, $cabecalhos);

echo 'Enviado com Sucesso!';


//ENVIAR PARA O BANCO DE DADOS O EMAIL E NOME DOS CAMPOS
$res = $pdo->prepare("SELECT * FROM emails where email = :email"); 
$res->bindValue(":email", $email);
$res->execute();
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
if(@count($dados) == 0){
	$res = $pdo->prepare("INSERT into emails (nome, email, ativo) values (:nome, :email, :ativo)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":ativo", "Sim");
	$res->execute();
}




 ?>