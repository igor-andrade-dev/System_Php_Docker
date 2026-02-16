 <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
<?php 
require_once('apiConfig.php');
require_once("../conexao.php");
$data_atual = date('Y-m-d');
if($access_token == ""){
	echo 'Configure o Token da Api Pix no arquivo ApiConfig';
	exit();
}

$vlr_venda_sem_format = $_POST['valor'];
$id_venda = $_POST['id_venda'];

$vlr_venda = number_format($vlr_venda_sem_format, 2, ',', '.');

if($vlr_venda_sem_format <= 0){
    echo 'O valor da venda tem que ser maior que zero!';
    exit();
}

$curl = curl_init();

    $dados["transaction_amount"]                    = (float)$vlr_venda_sem_format;
    $dados["description"]                           = 'Venda de Produtos';
    $dados["external_reference"]                    = $id_venda;
    $dados["payment_method_id"]                     = "pix";
    $dados["notification_url"]                      = "https://google.com";
    $dados["payer"]["email"]                        = "teste@hotmail.com";
    $dados["payer"]["first_name"]                   = "User";
    $dados["payer"]["last_name"]                    = "Teste";
    
    $dados["payer"]["identification"]["type"]       = "CPF";
    $dados["payer"]["identification"]["number"]     = "34152426764";
    
    $dados["payer"]["address"]["zip_code"]          = "06233200";
    $dados["payer"]["address"]["street_name"]       = "Av. das Nações Unidas";
    $dados["payer"]["address"]["street_number"]     = "3003";
    $dados["payer"]["address"]["neighborhood"]      = "Bonfim";
    $dados["payer"]["address"]["city"]              = "Osasco";
    $dados["payer"]["address"]["federal_unit"]      = "SP";

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($dados),
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer '.$access_token
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);

    $id = $dados["external_reference"];
    //var_dump($response);
curl_close($curl);
$codigo_pix = $resultado->point_of_interaction->transaction_data->qr_code;

$id_ref = $resultado->id;

echo "
<div class='row' align='center' style='margin-top:10px;'>
<div class='col-md-4' align='center'>
<span><b>Pix Valor:</b> R$ ".$vlr_venda."</span><br>
<img style='display:block;' width='200px' id='base64image'
       src='data:image/jpeg;base64, ".$resultado->point_of_interaction->transaction_data->qr_code_base64."'/>";
echo '
</div>
<div class="col-md-8" style="margin-top:15px" align="center">
 <a style="" class="btn btn-primary" href="#" onClick="copiar()"><i class="fa fa-clipboard"></i> <span ><small><small>Copiar Chave Pix </small></small></span> </a> <br>';
echo '<div style="margin:10px; border:1px solid #000; font-size:11px; overflow: scroll; width:100%; scrollbar-width: thin;" >'.$codigo_pix.'</div> <input type="text" id="chave_pix_copia" value="'.$codigo_pix.'" style="background: transparent; border:none; width:100px; opacity:0" readonly>';
echo '<input type="hidden" id="codigo_pix" value="'.$id_ref.'">';

echo '<div align="center"><small> Se já efetuou o pagamento <a title="Ir para o Painel" href="sistema/painel-cliente/index.php?pag=pedidos" class="text-primary" target="_blank">Clique aqui</a> </small></div>';

echo '</div>';

//inserir na conta a ref pix
$pdo->query("UPDATE vendas SET ref_pix = '$id_ref' where id = '$id_venda'");

?>   

<script>
  function copiar(){
    document.querySelector("#chave_pix_copia").select();
    document.querySelector("#chave_pix_copia").setSelectionRange(0, 99999); /* Para mobile */
    document.execCommand("copy");
    //$("#chave_pix_copia").hide();
    alert('Chave Pix Copiada! Use a opção Copie e Cole para Pagar')
  }

  function reload(){
    location.reload();
  }
</script>
