<?php

require_once("../../../conexao.php"); 

$id = $_POST['id'];

//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
$query_con = $pdo->query("SELECT * FROM produtos WHERE id = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
$imagem = $res_con[0]['imagem'];
if($imagem != 'sem-foto.jpg'){
	@unlink('../../../img/produtos/'.$imagem);
}


//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
$query_con = $pdo->query("SELECT * FROM imagens WHERE id_produto = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < count($res_con); $i++) { 
$imagem = $res_con[$i]['imagem'];
	if($imagem != 'sem-foto.jpg'){
		@unlink('../../../img/produtos/detalhes/'.$imagem);
	}
}


$pdo->query("DELETE from produtos WHERE id = '$id'");
$pdo->query("DELETE from imagens WHERE id_produto = '$id'");

echo 'Excluído com Sucesso!!';

?>