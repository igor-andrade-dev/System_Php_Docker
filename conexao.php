<?php

date_default_timezone_set('America/Sao_Paulo');

$url_loja = "https://$_SERVER[HTTP_HOST]/";
$url = explode("//", $url_loja);
if($url[1] == 'localhost/'){
    $url_loja = "http://$_SERVER[HTTP_HOST]/loja/";
}


//VARIAVEIS DO BANCO DE DADOS
$servidor = 'db'; // nome do serviço MySQL no docker-compose
$usuario = 'root';
$senha = 'root';  // ou a senha definida no docker-compose.yml
$banco = 'loja';


try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");

	//CONEXAO MYSQLI PARA O BACKUP
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

} catch (Exception $e) {
	echo "Erro ao conectar com o banco de dados! " . $e;
}



//verificar config
 $res = $pdo->query("SELECT * FROM config"); 
    $dados_res = $res->fetchAll(PDO::FETCH_ASSOC);    
    if(@count($dados_res) == 0){
       $pdo->query("INSERT into config SET id = '0', nome_loja = 'Boutique Freitas', telefone_loja = '(31) 3333-3333', email_loja = 'contato@hugocursos.com.br', whatsapp = '(31) 97527-5084', texto_destaque = 'Aproveite as nossas Promoções!', endereco_loja = 'Rua Alameda Campos, Número 50, Bairro Centro, Belo Horizonte - MG - CEP 31535-800', itens_por_pagina = '6', cep_origem = '30512-660', comprimento_caixa = '30', largura_caixa = '20', altura_caixa = '20', diametro_caixa = '25', mao_propria = 'N', formato_frete = '1', valor_declarado = '0', aviso_recebimento = 'N', total_cartoes_troca = '10', valor_cupom_cartao = '20', dias_uso_cupom = '7', nivel_estoque = '5', dias_limpar_carrinho = '2', retirada_local = 'Sim', nota_minima = '3', enviar_total_emails = '480', intervalo_envio_email = '70', pagar_entrega = 'Sim', relatorio_pdf = 'Sim', rodape_relatorios = 'Desenvolvido por Hugo Vasconcelos do Portal Hugo Cursos!!', logo = 'logo.png', favicon = 'favicon.png', api_pix = 'Sim', ativo = 'Sim'");
    }else{
    	$nome_loja = $dados_res[0]['nome_loja'];
    	$email_loja = $dados_res[0]['email_loja'];
    	$telefone_loja = $dados_res[0]['telefone_loja'];
    	$whatsapp_loja = $dados_res[0]['whatsapp'];
    	$texto_destaque = $dados_res[0]['texto_destaque'];
    	$endereco_loja = $dados_res[0]['endereco_loja'];
    	$itens_por_pagina = $dados_res[0]['itens_por_pagina'];
    	$cep_origem = $dados_res[0]['cep_origem'];
    	$comprimento_caixa = $dados_res[0]['comprimento_caixa'];
    	$largura_caixa = $dados_res[0]['largura_caixa'];
    	$altura_caixa = $dados_res[0]['altura_caixa'];
    	$diametro_caixa = $dados_res[0]['diametro_caixa'];
    	$mao_propria = $dados_res[0]['mao_propria'];
    	$formato_frete = $dados_res[0]['formato_frete'];
    	$valor_declarado = $dados_res[0]['valor_declarado'];
    	$aviso_recebimento = $dados_res[0]['aviso_recebimento'];
    	$total_cartoes_troca = $dados_res[0]['total_cartoes_troca'];
    	$valor_cupom_cartao = $dados_res[0]['valor_cupom_cartao'];
    	$dias_uso_cupom = $dados_res[0]['dias_uso_cupom'];
    	$nivel_estoque = $dados_res[0]['nivel_estoque'];
    	$dias_limpar_carrinho = $dados_res[0]['dias_limpar_carrinho'];
    	$retirada_local = $dados_res[0]['retirada_local'];
    	$nota_minima = $dados_res[0]['nota_minima'];
    	$enviar_total_emails = $dados_res[0]['enviar_total_emails'];
    	$intervalo_envio_email = $dados_res[0]['intervalo_envio_email'];
    	$pagar_entrega = $dados_res[0]['pagar_entrega'];
    	$relatorio_pdf = $dados_res[0]['relatorio_pdf'];
    	$rodape_relatorios = $dados_res[0]['rodape_relatorios'];
    	$logo = $dados_res[0]['logo'];
    	$favicon = $dados_res[0]['favicon'];
    	$email_adm = $email_loja;
    	$whatsapp_link = '55'.preg_replace('/[ ()-]+/' , '' , $whatsapp_loja);
    	$email = $email_loja;

        $api_pix = $dados_res[0]['api_pix'];
        $instancia = $dados_res[0]['instancia'];
        $token = $dados_res[0]['token'];

        $ativo_sistema = $dados_res[0]['ativo'];


        if($ativo_sistema != 'Sim' and $ativo_sistema != ''){ ?>
    <style type="text/css">
        @media only screen and (max-width: 700px) {
  .imgsistema_mobile{
    width:300px;
  }
    
}
    </style>
    <div style="text-align: center; margin-top: 100px">
    <img src="<?php echo $url_loja ?>img/bloqueio.png" class="imgsistema_mobile">    
    </div>
<?php 
exit();
} 


    }





?>