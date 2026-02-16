<?php

require_once("../../../conexao.php"); 

$nome = $_POST['nome-cat'];


$antigo = $_POST['antigo'];
$id = $_POST['txtid2'];

if($nome == ""){
	echo 'Preencha o Campo Nome!';
	exit();
}


if($nome != $antigo){
	$res = $pdo->query("SELECT * FROM tipos_frete where nome = '$nome'"); 
	$dados = $res->fetchAll(PDO::FETCH_ASSOC);
	if(@count($dados) > 0){
			echo 'Tipo já Cadastrado no Banco!';
			exit();
		}
}



if($id == ""){
	$res = $pdo->prepare("INSERT INTO tipos_frete (nome) VALUES (:nome)");
	
}else{

	$res = $pdo->prepare("UPDATE tipos_frete SET nome = :nome WHERE id = :id");
	

	$res->bindValue(":id", $id);
}

	$res->bindValue(":nome", $nome);
	
	$res->execute();


echo 'Salvo com Sucesso!!';

?>