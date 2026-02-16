<?php 

require_once("conexao.php");
@session_start();
$id_usuario = @$_SESSION['id_usuario'];
$formatar = $_POST['formatar'];
$total = $_POST['total_compra'];

$sessao =  @$_SESSION['hora_compra'];

if($formatar == ''){
	$total = str_replace('.', '', $total);
	$total = str_replace(',', '.', $total);	
}

$valor_frete =  $_POST['vlr_frete'];
$tem_frete = $_POST['existe_frete'];
$antigo = $_POST['antigo'];
@$sub_total = @$total - @$valor_frete;

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$complemento = $_POST['complemento'];
$cep = $_POST['cep'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$comentario = $_POST['comentario'];
$local = @$_POST['local'];
$entrega = @$_POST['entrega'];
$frete_selecionado = @$_POST['frete_selecionado'];


if($local != ""){
	$status_venda = 'Retirada';
}else{
	$status_venda = 'Não Enviado';
}


if($tem_frete == 'Sim' && $local == "" && $entrega == ""){
	if($valor_frete == '0' || $valor_frete == ""){
		echo ' Selecione um CEP válido para o Frete!';
		exit();
	}
}



if($nome == ""){
	echo 'Preencha o Campo Nome!';
	exit();
}

if($rua == ""){
	echo 'Preencha o Campo Rua!';
	exit();
}

if($numero == ""){
	echo 'Preencha o Campo Número!';
	exit();
}

if($bairro == ""){
	echo 'Preencha o Campo Bairro!';
	exit();
}




$res = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email where id = '$id_usuario'");
$res->bindValue(":nome", $nome);
$res->bindValue(":email", $email);
$res->bindValue(":cpf", $cpf);

$res->execute();


$res = $pdo->prepare("UPDATE clientes SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, rua = :rua, numero = :numero, complemento = :complemento, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep where cpf = '$antigo' ");
$res->bindValue(":nome", $nome);
$res->bindValue(":email", $email);
$res->bindValue(":cpf", $cpf);
$res->bindValue(":telefone", $telefone);
$res->bindValue(":rua", $rua);
$res->bindValue(":numero", $numero);
$res->bindValue(":complemento", $complemento);
$res->bindValue(":bairro", $bairro);
$res->bindValue(":cidade", $cidade);
$res->bindValue(":estado", $estado);
$res->bindValue(":cep", $cep);

$res->execute();



$res = $pdo->prepare("INSERT vendas SET total = :total, frete = :frete, sub_total = :sub_total, id_usuario = :id_usuario, pago = :pago, data = curDate(), status = :status, pgto_entrega = '$entrega', tipo_frete = :tipo_frete ");
$res->bindValue(":total", $total);
$res->bindValue(":frete", $valor_frete);
$res->bindValue(":sub_total", $sub_total);
$res->bindValue(":id_usuario", $id_usuario);
$res->bindValue(":pago", 'Não');
$res->bindValue(":status", $status_venda);
$res->bindValue(":tipo_frete", $frete_selecionado);


$res->execute();

$id_venda = $pdo->lastInsertId();


	//MUDAR ID DA VENDA NOS ITENS DO CARRINHO
$pdo->query("UPDATE carrinho SET id_venda = '$id_venda' where (id_usuario = '$id_usuario' or sessao = '$sessao') and id_venda = 0");


if($comentario != ""){
	$res = $pdo->prepare("INSERT mensagem SET id_venda = :id_venda, texto = :texto, usuario = :usuario, data = curDate(), hora = curTime()");
	$res->bindValue(":id_venda", $id_venda);
	$res->bindValue(":texto", $comentario);
	$res->bindValue(":usuario", 'Cliente');
	$res->execute();
}

echo $id_venda;



//ENVIAR EMAIL DA VENDA PARA O ADM
$dest = $email_adm;
$subject = "Nova Venda Efetuada ".$nome_loja;

$message = 'Foi Efetuada uma nova venda na loja, o cliente é '.$nome.' e o valor vendido foi de '.$total;

$remetente = $email_adm;
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8;' . "\r\n";
$headers .= "From: " .$remetente;
@mail($dest, $subject, $message, $headers);


//ENVIAR EMAIL DA VENDA PARA O CLIENTE
$dest = $email;
$subject = "Nova Compra Efetuada ".$nome_loja;

$message = 'Você efetuou uma compra em nossa loja, acesse o painel do cliente para maiores informações e detalhes sobre o envio!';

$remetente = $email_adm;
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8;' . "\r\n";
$headers .= "From: " .$remetente;
@mail($dest, $subject, $message, $headers);


if($token != ""){
//enviar dados da compra via whatsapp cliente
$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$link_painel = $url_loja.'sistema/painel-cliente/index.php?pag=pedidos';
$sub_totalF = number_format( $total , 2, ',', '.');
$valor_freteF = number_format( $valor_frete , 2, ',', '.');

$mensagem = '*'.$nome_loja.'* %0A';
$mensagem .= '_Obrigado pela Compra!_ %0A';
$mensagem .= '*Valor* R$ '.$sub_totalF.' %0A';
if($frete_selecionado != ""){
	$mensagem .= '*Tipo Entrega* '.$frete_selecionado.' %0A';	
}

if($valor_frete > 0){
$mensagem .= '*Valor Frete* R$ '.$valor_freteF.' %0A';
}
$mensagem .= '%0A_*Detalhes do Pedido*_ %0A';

$res = $pdo->query("SELECT * from carrinho where id_venda = '$id_venda' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < count($dados); $i++) { 

 $id_produto = $dados[$i]['id_produto'];	
 $id_carrinho = $dados[$i]['id'];
 $quantidade = $dados[$i]['quantidade'];

 $combo = $dados[$i]['combo'];	

if($combo != 'Sim'){
	$res2 = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
}else{
	$res2 = $pdo->query("SELECT * from combos where id = '$id_produto' ");
}


$dados2 = $res2->fetchAll(PDO::FETCH_ASSOC);
$nome_produto = $dados2[0]['nome'];
$valor = $dados2[0]['valor'];


//buscar valor das carac
   $query4 = $pdo->query("SELECT * from carac_itens_car where id_carrinho = '$id_carrinho'");
  $res4 = $query4->fetchAll(PDO::FETCH_ASSOC); 
  $total_carac_car = count($res4);
  $valor_carac = 0;
  for ($i2=0; $i2 < $total_carac_car; $i2++) { 
     $valor_carac += $res4[$i2]['adicional'];
  }
  
$valor_final = $valor * $quantidade + ($valor_carac * $quantidade);

$valor = number_format( $valor , 2, ',', '.');
$valor_finalF = number_format( $valor_final , 2, ',', '.');

$mensagem .= '✅ ('.$quantidade.') '.$nome_produto.' R$ '.$valor_finalF.' %0A';

}

$mensagem .= '%0A _Acesse o painel do cliente para maiores informações e detalhes sobre o envio!_ %0A';
$mensagem .= $link_painel;

require("api_whatsapp/texto.php");

}
?>