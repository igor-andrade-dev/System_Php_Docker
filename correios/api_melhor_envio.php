<?php
include("../conexao.php");
$cep = filter_var(@$_POST['cep'], @FILTER_SANITIZE_STRING);
$id = filter_var(@$_POST['id'], @FILTER_SANITIZE_STRING);

$peso_produto = filter_var(@$_POST['total_peso'], @FILTER_SANITIZE_STRING);
$largura_produto = filter_var(@$_POST['largura_produto'], @FILTER_SANITIZE_STRING);
$altura_produto = filter_var(@$_POST['altura_produto'], @FILTER_SANITIZE_STRING);
$comprimento_produto = filter_var(@$_POST['comprimento_produto'], @FILTER_SANITIZE_STRING);


$cep = str_replace('-', '', $cep);
$cep_origem = str_replace('-', '', $cep_origem);

$token_frete = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiOTZlN2UwYTE4OWEzMjNmMDE4NTI3NTQ2YzEyNjk3ZTRjODI2YjIxZmQyZjkyMzQ2NzYxM2IzMTBjMjQ0NjU2YTI5M2Q0ZTA3ZjFhNTJiZWUiLCJpYXQiOjE3MTQ0ODk4NDAuNzU5MzI2LCJuYmYiOjE3MTQ0ODk4NDAuNzU5MzI4LCJleHAiOjE3NDYwMjU4NDAuNzQwNjYxLCJzdWIiOiI5YmVlOTEzNy0yMGEwLTQ2YzctYjEyMS1mNmM2ZWZiYTYzMTAiLCJzY29wZXMiOlsic2hpcHBpbmctY2FsY3VsYXRlIl19.lcZW5tA2QZnW1fU0wIZGJ_Kt6cDVVOkrj50vnMBhoYkmETtnW-ONgNSIrcpPNrITPkODelffKugINBSG-z5-2WtZPqBoftqiY28hpVzZF2cr8SOuVO6p8pjmwFX6DB2JUVpYUqmpJ0JSd0qGgf3IqyDJUzaxKW404exToO_3jYeQN7nCdRQihDYdWZVj2RDFlXFC8H7qqDm5r6DKShAuhpJTn-Ca3NF43Eb0tJndfJTuYhry5VsvsL8jiSQ7Qeyr6S0tw5E-Jf0up0D1UrDzzaRph_vjDXSlhpvi9QA1Cr6OMS9rXUb1-mI18H0YOrAv_IqOv_4xhYyM9vEoI3knpaMzjcTPFxECaTRAdMBbm8ZAMlcA0seU_CvYkiEzLnI2t9SglAB198fRmn07Thi9x4PHDfnAJGaT-B9Owii3GHPjvih4h8Fs2-od6z1ROJ8SWarP8eFKt2i5Moe1PrZxHgs_flCfGSA93TD4gBZCK7uqJNsnlUWlgPFFUnO_a1TbPlNck3h6hTG4qQEZEM9VY36x4j7ncsx6fjQdbiecdf2VJckbET0tsSn23pr2gFvqqPkvKULE8NVkAqp0l1S6ERjlz9j6tbpVtilHMJIMvRM7r4jEaLAuxTmYNzI7PGIYG9i47O0cIdRzR1_Gwe62p3sKn1lgMrpkAUag6sFmX2M';

$query = $pdo->query("SELECT * from produtos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
    
    $peso_produto = $res[0]['peso'];
    $largura_produto = $res[0]['largura'];
    $altura_produto = $res[0]['altura'];
    $comprimento_produto = $res[0]['comprimento']; 
  }

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://www.melhorenvio.com.br/api/v2/me/shipment/calculate",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'from' => [
        'postal_code' => $cep_origem
    ],
    'to' => [
        'postal_code' => $cep
    ],
    'package' => [
        'height' => $altura_produto,
        'width' => $largura_produto,
        'length' => $comprimento_produto,
        'weight' => $peso_produto
    ]
  ]),
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Authorization: Bearer ".$token_frete,
    "Content-Type: application/json",
    "User-Agent: Aplicação"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
  $resp = json_decode($response);

   echo '<table class="table" style="font-size:13px">
   <tr style="font-weight:bold">
   <td></td>
   <td>Nome</td>
   <td>Valor</td>
   <td>Prazo</td>
   </tr>';

  foreach($resp as $index => $res){  
    
    $nome_frete = @$res->name;
    $valor_frete = @$res->price;
    $valor_freteF = @number_format($valor_frete, 2, ',', '.');
    $prazo_frete = @$res->delivery_time;
    $link_imagem = @$res->company->picture;
    $nome_companhia = @$res->company->name;

     $query2 = $pdo->query("SELECT * FROM tipos_frete where nome = '$nome_frete' ");
     $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);

    if($valor_frete > 0 and @count($res2) > 0){

  echo '
   <tr>
   ';
    echo '<td><input onchange="totalizarFrete('."'$valor_frete'".', '."'$nome_frete'".')" type="radio" class="" name="frete_sel" id="frete_sel_'.@$res->name.'" style="width:15px !important; height:15px !important; padding:0 !important; margin:0 !important; position:absolute !important"></td>';
    echo '<td><img src="'.$link_imagem.'" width="55px"> '.@$res->name.'</td>';
    echo '<td style="color:red">'.@$valor_freteF.'</td>';
    echo '<td>'.@$prazo_frete.' dias</td>';
  
  '</tr>';

   }
  }
 echo' </table>
  ';

}


?>

<script type="text/javascript">
  function selecionarFrete(nome, preco, prazo, companhia){
    $("#nome_frete").val(nome);
    $("#valor_frete").val(preco);
  }
</script>