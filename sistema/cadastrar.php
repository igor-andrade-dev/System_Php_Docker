<?php 

require_once("../conexao.php");

$nome = filter_var(@$_POST['nome'], @FILTER_SANITIZE_STRING);
$cpf = filter_var(@$_POST['cpf'], @FILTER_SANITIZE_STRING);
$email = filter_var(@$_POST['email'], @FILTER_SANITIZE_STRING);
$senha = filter_var(@$_POST['senha'], @FILTER_SANITIZE_STRING);
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

if($nome == ""){
	echo 'Preencha o Campo nome!';
	exit();
}

if($cpf == ""){
	echo 'Preencha o Campo cpf!';
	exit();
}

if($email == ""){
	echo 'Preencha o Campo email!';
	exit();
}

if($senha == ""){
	echo 'Preencha o Campo senha!';
	exit();
}

if($senha != $_POST['confirmar-senha']){
	echo 'As senhas não coincidem!';
	exit();
}



$res = $pdo->prepare("SELECT * FROM usuarios where cpf = :cpf"); 
  $res->bindValue(":cpf", "$cpf");
    $res->execute();
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
if(@count($dados) == 0){

	$res = $pdo->prepare("INSERT into usuarios (nome, cpf, email, senha, senha_crip, nivel) values (:nome, :cpf, :email, :senha, :senha_crip, :nivel)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":cpf", $cpf);
	$res->bindValue(":senha", '');
	$res->bindValue(":senha_crip", $senha_crip);
	$res->bindValue(":nivel", 'Cliente');

	$res->execute();


	$res = $pdo->prepare("INSERT into clientes (nome, cpf, email) values (:nome, :cpf, :email)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":cpf", $cpf);
	
	$res->execute();


	$res = $pdo->query("SELECT * FROM emails where email = '$email'"); 
	$dados = $res->fetchAll(PDO::FETCH_ASSOC);
	if(@count($dados) == 0){
	$res = $pdo->prepare("INSERT into emails (nome, email, ativo) values (:nome, :email, :ativo)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":ativo", "Sim");
	$res->execute();
}


	echo 'Cadastrado com Sucesso!';
}else{
	echo 'CPF já Cadastrado!';
}


?>