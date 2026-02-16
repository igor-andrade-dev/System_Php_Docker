<?php

require_once("../../conexao.php"); 

$nome_loja = $_POST['nome_loja'];
$telefone_loja = $_POST['telefone_loja'];
$whatsapp_loja = $_POST['whatsapp_loja'];
$email_adm = $_POST['email_adm'];

$endereco_loja = $_POST['endereco_loja'];
$cep_origem = $_POST['cep_origem'];
$texto_destaque = $_POST['texto_destaque'];
$itens_por_pagina = $_POST['itens_por_pagina'];

$comprimento_caixa = $_POST['comprimento_caixa'];
$largura_caixa = $_POST['largura_caixa'];
$altura_caixa = $_POST['altura_caixa'];
$diametro_caixa = $_POST['diametro_caixa'];

$mao_propria = $_POST['mao_propria'];
$valor_declarado = $_POST['valor_declarado'];
$aviso_recebimento = $_POST['aviso_recebimento'];
$formato_frete = $_POST['formato_frete'];

$total_cartoes_troca = $_POST['total_cartoes_troca'];
$valor_cupom_cartao = $_POST['valor_cupom_cartao'];
$dias_uso_cupom = $_POST['dias_uso_cupom'];
$nivel_estoque = $_POST['nivel_estoque'];


$dias_limpar_carrinho = $_POST['dias_limpar_carrinho'];
$nota_minima = $_POST['nota_minima'];
$retirada_local = $_POST['retirada_local'];
$pagar_entrega = $_POST['pagar_entrega'];



$enviar_total_emails = $_POST['enviar_total_emails'];
$intervalo_envio_email = $_POST['intervalo_envio_email'];
$relatorio_pdf = $_POST['relatorio_pdf'];
$rodape_relatorios = $_POST['rodape_relatorios'];

$api_pix = $_POST['api_pix'];
$token = $_POST['token'];
$instancia = $_POST['instancia'];

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = 'logo.png';
$caminho = '../../img/' .$nome_img;
if (@$_FILES['logo']['name'] == ""){
  $logo = "logo.png";
}else{
  
  $logo = $nome_img;

  
}

$imagem_temp = @$_FILES['logo']['tmp_name']; 

$ext = pathinfo($logo, PATHINFO_EXTENSION);   
if($ext == 'png'){ 
move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}




//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = 'favicon.png';
$caminho = '../../img/' .$nome_img;
if (@$_FILES['favicon']['name'] == ""){
  $favicon = "favicon.png";
}else{
  
  $favicon = $nome_img;

  
}

$imagem_temp = @$_FILES['favicon']['tmp_name']; 

$ext = pathinfo($favicon, PATHINFO_EXTENSION);   
if($ext == 'png'){ 
move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}




$res = $pdo->prepare("UPDATE config SET nome_loja = :nome_loja, telefone_loja = :telefone_loja, email_loja = :email_loja, whatsapp = :whatsapp, texto_destaque = :texto_destaque, endereco_loja = :endereco_loja, itens_por_pagina = :itens_por_pagina, cep_origem = :cep_origem, comprimento_caixa = :comprimento_caixa, largura_caixa = :largura_caixa, altura_caixa = :altura_caixa, diametro_caixa = :diametro_caixa, mao_propria = :mao_propria, formato_frete = :formato_frete, valor_declarado = :valor_declarado, aviso_recebimento = :aviso_recebimento, total_cartoes_troca = :total_cartoes_troca, valor_cupom_cartao = :valor_cupom_cartao, dias_uso_cupom = :dias_uso_cupom, nivel_estoque = :nivel_estoque, dias_limpar_carrinho = :dias_limpar_carrinho, retirada_local = :retirada_local, nota_minima = :nota_minima, enviar_total_emails = :enviar_total_emails, intervalo_envio_email = :intervalo_envio_email, pagar_entrega = :pagar_entrega, relatorio_pdf = :relatorio_pdf, rodape_relatorios = :rodape_relatorios, logo = :logo, favicon = :favicon, api_pix = :api_pix, token = :token, instancia = :instancia ");
	
	$res->bindValue(":nome_loja", $nome_loja);
	$res->bindValue(":telefone_loja", $telefone_loja);
	$res->bindValue(":whatsapp", $whatsapp_loja);
	$res->bindValue(":email_loja", $email_adm);
	$res->bindValue(":texto_destaque", $texto_destaque);
	$res->bindValue(":endereco_loja", $endereco_loja);
	$res->bindValue(":itens_por_pagina", $itens_por_pagina);
	$res->bindValue(":cep_origem", $cep_origem);
	$res->bindValue(":comprimento_caixa", $comprimento_caixa);
	$res->bindValue(":largura_caixa", $largura_caixa);
	$res->bindValue(":altura_caixa", $altura_caixa);
	$res->bindValue(":diametro_caixa", $diametro_caixa);
	$res->bindValue(":mao_propria", $mao_propria);
	$res->bindValue(":formato_frete", $formato_frete);
	$res->bindValue(":valor_declarado", $valor_declarado);
	$res->bindValue(":aviso_recebimento", $aviso_recebimento);
	$res->bindValue(":total_cartoes_troca", $total_cartoes_troca);
	$res->bindValue(":valor_cupom_cartao", $valor_cupom_cartao);
	$res->bindValue(":dias_uso_cupom", $dias_uso_cupom);
	$res->bindValue(":nivel_estoque", $nivel_estoque);
	$res->bindValue(":dias_limpar_carrinho", $dias_limpar_carrinho);
	$res->bindValue(":retirada_local", $retirada_local);
	$res->bindValue(":nota_minima", $nota_minima);
	$res->bindValue(":enviar_total_emails", $enviar_total_emails);
	$res->bindValue(":intervalo_envio_email", $intervalo_envio_email);
	$res->bindValue(":pagar_entrega", $pagar_entrega);
	$res->bindValue(":relatorio_pdf", $relatorio_pdf);
	$res->bindValue(":rodape_relatorios", $rodape_relatorios);
	$res->bindValue(":logo", $logo);
	$res->bindValue(":favicon", $favicon);

	$res->bindValue(":api_pix", $api_pix);
	$res->bindValue(":token", $token);
	$res->bindValue(":instancia", $instancia);

	$res->execute();




echo 'Salvo com Sucesso!';


 ?>