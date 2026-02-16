<?php
require_once("cabecalho.php");
require_once("conexao.php");
?>

<?php
require_once("cabecalho-busca.php");
@session_start();

if(@$_SESSION['id_usuario'] == null){
    echo "<script language='javascript'> window.location='sistema' </script>";

}

$sessao = $_SESSION['hora_compra'];

$id_venda = @$_GET['id_venda'];
$id_usuario = @$_SESSION['id_usuario'];
$nome_usuario = @$_SESSION['nome_usuario'];
//$cpf_usuario = @$_SESSION['cpf_usuario'];
$email_usuario = @$_SESSION['email_usuario'];
$total = 0;
$frete_correios;


$res = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$cpf_usuario = @$dados[0]['cpf'];


$res = $pdo->query("SELECT * from clientes where cpf = '$cpf_usuario'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$telefone = @$dados[0]['telefone'];
$rua = @$dados[0]['rua'];
$numero = @$dados[0]['numero'];
$bairro = @$dados[0]['bairro'];
$complemento = @$dados[0]['complemento'];
$cep = @$dados[0]['cep'];
$cidade = @$dados[0]['cidade'];
$estado = @$dados[0]['estado'];

?>


<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">

        <div class="checkout__form bg-light pl-4 pt-4">
            <h4>Confirmar Dados (Entrega)</h4>
            <form method="post" id="form-dados">
                <div class="row">
                    <div class="col-lg-8 col-md-6">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Nome Completo<span>*</span></p>
                                    <input type="text" value="<?php echo @$nome_usuario ?>" name="nome" id="nome">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>CPF<span>*</span></p>
                                    <input value="<?php echo $cpf_usuario ?>" type="text" name="cpf" id="cpf">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email<span>*</span></p>
                                    <input value="<?php echo $email_usuario ?>" type="text" name="email" id="email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Telefone<span>*</span></p>
                                    <input type="text" value="<?php echo $telefone ?>" name="telefone" id="telefone" required>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                          <div class="col-lg-3">
                                <div class="checkout__input">
                                    <p><b>CEP</b><span>*</span></p>
                                    <input onChange="alterarcep()" type="text" value="<?php echo $cep ?>" name="cep" id="cep" onblur="pesquisacep(this.value);">
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="checkout__input">
                                    <p>Rua<span>*</span></p>
                                    <input type="text" value="<?php echo $rua ?>" name="rua" id="rua">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="checkout__input">
                                    <p>Número<span>*</span></p>
                                    <input type="text" value="<?php echo $numero ?>" name="numero" id="numero">
                                </div>
                            </div>
                          
                        </div>


                        <div class="row">
                            <div class="col-lg-4">
                                <div class="checkout__input">
                                    <p>Bairro<span>*</span></p>
                                    <input type="text" value="<?php echo $bairro ?>" name="bairro" id="bairro">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="checkout__input">
                                    <p>Complemento<span></span></p>
                                    <input type="text" value="<?php echo $complemento ?>" name="complemento" id="complemento">
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="checkout__input">
                                    <p>Cidade<span>*</span></p>
                                    <input type="text" value="<?php echo $cidade ?>" name="cidade" id="cidade">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="checkout__input">
                                    <p>Estado<span>*</span></p>
                                  <input type="text" value="<?php echo $estado ?>" name="estado" id="estado" placeholder="Sigla UF">
                                </div>
                            </div>

                            <div class="col-md-12">
                                    <div class="checkout__input">
                                    <p>Informações do Pedido <small>(Caso tenha alguma dúvida)</small><span></span></p>
                                    <input type="text" maxlength="1000" name="comentario" id="comentario" style="height:100px;">
                                     </div>
                                        
                                                                  
                            </div>

                        </div>

                        
                            
                        


                         <div class="shoping__continue">
                            <div class="shoping__discount">
                                <h5>Cupom de Desconto</h5>
                                <form method="post">
                                    <div class="row">
                                        <div class="col-lg-6">
                                             <div class="checkout__input">
                                            <input name="cupom" id="cupom" type="text" placeholder="Entre com o código do seu cupom">
                                             </div>
                                        </div>
                                         <div class="col-lg-6">
                                             <div class="checkout__input">
                                             <button id="btn-cupom" type="submit" class="site-btn bg-info">APLICAR CUPOM</button>
                                            </div>
                                        </div>
                                    </div>  
                                    <div id="mensagem-cupom"></div> 
                                </form>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Sua Compra</h4>
                            <div class="checkout__order__products">Produtos <span>Total</span></div>
                            <ul>

                                <?php 
                                $res = $pdo->query("SELECT * from carrinho where (id_usuario = '$id_usuario' or sessao = '$sessao') and id_venda = 0 order by id asc");
                                $dados = $res->fetchAll(PDO::FETCH_ASSOC);
                                $linhas = count($dados);

                                if($linhas == 0){
                                  $linhas = 0;
                                  $total = 0;
                              }

                              $total;
                              $total_peso;
                              
                              for ($i=0; $i < count($dados); $i++) { 
                               foreach ($dados[$i] as $key => $value) {
                               }

                               $id_produto = $dados[$i]['id_produto'];    
                               $quantidade = $dados[$i]['quantidade'];
                               $id_carrinho = $dados[$i]['id'];
                               $combo = $dados[$i]['combo'];

                               //buscar valor das carac
   $query4 = $pdo->query("SELECT * from carac_itens_car where id_carrinho = '$id_carrinho'");
  $res4 = $query4->fetchAll(PDO::FETCH_ASSOC); 
  $total_carac_car = count($res4);
  $valor_carac = 0;
  for ($i2=0; $i2 < $total_carac_car; $i2++) { 
     $valor_carac += $res4[$i2]['adicional'];
  }


                               if($combo == 'Sim'){
                                 $res_p = $pdo->query("SELECT * from combos where id = '$id_produto' ");
                             }else{
                              $res_p = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
                          }

                          $dados_p = $res_p->fetchAll(PDO::FETCH_ASSOC);
                          $nome_produto = $dados_p[0]['nome'];
                          $tipo_envio = $dados_p[0]['tipo_envio'];
                          $valor_frete = $dados_p[0]['valor_frete'];

                           $querye = $pdo->query("SELECT * FROM tipo_envios where id = '$tipo_envio' ");
                              $rese = $querye->fetchAll(PDO::FETCH_ASSOC);
                              if(@count($rese) > 0){
                                 $envio = $rese[0]['nome'];
                               }else{
                                $envio = 'Correios';
                               }
                             

                              if($envio == 'Correios'){
                                $frete_correios = 'Sim';
                                $peso = $dados_p[0]['peso'];
                                 $largura_produto = $dados_p[0]['largura'];
                                  $altura_produto = $dados_p[0]['altura'];
                                  $comprimento_produto = $dados_p[0]['comprimento']; 
                                @$total_peso = @$total_peso + ($peso *  $quantidade);
                                @$existe_frete = 'Sim';
                              }

                              if($envio == 'Valor Fixo'){
                                @$existe_frete = 'Sim';
                              }


                              


                          if($combo == 'Sim'){ 
                              $promocao = ""; 
                              $pasta = "combos";
                          }else{
                              $promocao = $dados_p[0]['promocao']; 
                              $pasta = "produtos";
                          }


                          if($promocao == 'Sim'){
                              $queryp = $pdo->query("SELECT * FROM promocoes where id_produto = '$id_produto' ");
                              $resp = $queryp->fetchAll(PDO::FETCH_ASSOC);
                              $valor = $resp[0]['valor'];

                          }else{
                              $valor = $dados_p[0]['valor'];
                          }


                          $imagem = $dados_p[0]['imagem'];


                          $total_item = $valor * $quantidade + ($valor_carac * $quantidade);
                          @$total = @$total + $total_item;

                          if($valor_frete > 0){
                                
                                @$total = @$total + @$valor_frete;
                              }


                          $valor = number_format( $valor , 2, ',', '.');
                            //$total = number_format( $total , 2, ',', '.');
                          $total_item = number_format( $total_item , 2, ',', '.');


                          ?>
                          <li><?php echo $nome_produto ?> <span>R$<?php echo $total_item ?></span></li>

                          <?php if($valor_frete > 0){ ?>
                            <p align="right" class="text-danger"><small>Frete Fixo : <?php echo $valor_frete ?></small></p>
                          <?php } ?>

                          <?php } 
                          @$total = number_format(@$total, 2, ',', '.');
                           ?>
                      </ul>
                      <div class="checkout__order__subtotal">Subtotal <span>R$ <?php echo $total ?></span></div>

                      <?php if(@$frete_correios == 'Sim' && $retirada_local == 'Sim'){ ?>
                      <div class="row mt-2 pl-3">
                      <div class="form-check">
                          <input type="checkbox" value="sim" class="form-check-input" id="local" name="local">
                         <label class="form-check-label" for="exampleCheck1">Retirar no Local</label>
                      </div>                   
                       
                   </div>
                 <?php } ?>

                   
                   <div class="row mb-4 pl-3">
                    <?php if($pagar_entrega == 'Sim' || $pagar_entrega == 'sim'){ ?>
                   <div class="form-check">
                          <input type="checkbox" value="sim" class="form-check-input" id="entrega" name="entrega">
                         <label class="form-check-label" for="exampleCheck1">Pagar na Entrega</label>
                      </div>
                       <?php } ?>
                    </div>
                

                      <?php if(@$frete_correios == 'Sim'){ ?>

                       <div id="div-frete" class="checkout__order__total">Calcular Frete<br> 
                            <div class="checkout__input py-2">
                               
                                    <form id="frm" method="post">
                                    <div class="row">
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                             <input type="hidden" value="<?php echo @$total_peso ?>" name="total_peso" id="total_peso">

                                             <div class="">

                                               <div>
                                                                   <input class="form-control" type="text" name="cep2" id="cep2" style="width:150px; display:inline-block;">
                                                                   <a onclick="calcularFrete()" class="btn btn-info " style="display:inline-block; color:white">Calcular</a>
                                                               </div>
                                            
                                             </div>
                                        </div>
                                        
                                    </div>                                   
                                    
                                   
                                </form>
                             
                           
                       </div>

                       

                   </div>
                   <div id="listar-frete" style="margin-top:-50px; padding:0"></div>

                    <?php } ?>

                                      

                      <div  class="checkout__order__total">Total <span id="total_final"></span></div>
                     

                      <input type="hidden" value="0" id="vlr_frete" name="vlr_frete">
                      <input type="hidden" value="<?php echo @$frete_correios ?>" id="existe_frete" name="existe_frete">
                      <input type="hidden" value="<?php echo @$total ?>" id="total_compra" name="total_compra">
                      <input type="hidden" value="<?php echo @$cpf_usuario ?>" id="antigo" name="antigo">

                      <input type="hidden" value="" id="formatar" name="formatar">
                       <input type="hidden" value="" id="val_frete" name="val_frete">

                         <input type="hidden" id="frete_selecionado" name="frete_selecionado">

                          <input type="hidden" id="total_final2" name="">

                           <input type="hidden" id="entrega_retirar" name="">

                      <button id="btn-finalizar" type="submit" class="site-btn bg-success">FINALIZAR COMPRA</button>
                      <div id="div_img" style="display:none"><img src="img/loading.gif" width="160px"></div>

                      <small><div class="mt-2" id="mensagem-finalizar"></div></small>
                  </div>
              </div>
          </div>
      </form>
  </div>
</div>
</section>
<!-- Checkout Section End -->



<!-- Modal -->
<div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal Pagamento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    ...
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary">Save changes</button>
</div>
</div>
</div>
</div>


<?php
require_once("rodape.php");
?>




<script type="text/javascript">
  $( document ).ready(function() {
    var total = "<?=$total?>";
    var id_venda = "<?=$id_venda?>";

     total_final = total.replace(".","");
     total_final = total_final.replace(",",".");
    
    $('#total_final2').val(total_final);

    if(total == "0,00" &&  id_venda == ""){
        window.location="produtos.php";
    }
  
    total = "R$ " + total;
    $('#total_final').text(total);
    $('#total_pgto').text(total);

    $('#cep2').val($('#cep').val());
    calcularFrete()


  })
</script>


<script type="text/javascript">
    $('#btn-cupom').click(function(event){
        event.preventDefault();
                
        $.ajax({
            url:"cupom/usar-cupom.php",
            method:"post",
            data: $('form').serialize(),
            dataType: "text",
            success: function(msg){
                if(msg.trim() === 'Insira um valor para o Cupom'){
                    
                    $('#mensagem-cupom').addClass('text-danger')
                    $('#mensagem-cupom').text(msg);
                    }

                 else if (msg.trim() === 'Esse código de cupom é inexistente!'){
                    $('#mensagem-cupom').addClass('text-danger')
                    $('#mensagem-cupom').text(msg);



                    

                 }else{
                    var tot;
                    total_final = $('#total_final').text();
                    total_final = total_final.replace(".","");
                    total_final = total_final.replace(",",".");
                    total_final = total_final.replace("R$","");
                    tot = parseFloat(total_final) - parseFloat(msg);
                    tot = tot.toFixed(2);
                    $('#total_compra').val(tot);
                    tot = "R$ " + tot.replace(".",",");
                    console.log(tot);
                    $('#total_final').text(tot);
                    $('#total_pgto').text(tot);


                    $('#mensagem-cupom').addClass('text-success')
                    $('#mensagem-cupom').text("Cupom de desconto no valor de R$" + msg + " Reais");
                    $('#formatar').val('nao');
                 }
            }
        })
    })
</script>




<script type="text/javascript">
    function totalizarFrete(valor, nome){     
      
        $('#vlr_frete').val(valor);
        $('#frete_selecionado').val(nome);
        var tot_frete = valor;

        tot_frete = tot_frete.replace(",",".");
        $('#val_frete').val(tot_frete);
        vlr_frete_antigo = $('#vlr_frete').val();
        console.log(vlr_frete_antigo);
        console.log(tot_frete);
        total_final = $('#total_final').text();
        total_final = total_final.replace(".","");
        total_final = total_final.replace(",",".");
        total_final = total_final.replace("R$","");

        var total_da_compra = $('#total_final2').val();


        
        
        tot = parseFloat(total_da_compra) + parseFloat(tot_frete);
        tot = tot.toFixed(2);
        $('#total_compra').val(tot);
        tot = "R$ " + tot.replace(".",",");
        console.log(tot);
        $('#total_final').text(tot);
        $('#total_pgto').text(tot);

        
        $('#vlr_frete').val(tot_frete);
        $('#mensagem-finalizar').text("");
        $('#formatar').val('nao');
    
        var cep_buscado = $('#cep2').val();
        $('#cep').val(cep_buscado);
        pesquisacep(cep_buscado)

       

    }
</script>




<script type="text/javascript">
    $('#btn-finalizar').click(function(event){

     $('#btn-finalizar').hide();
     $('#div_img').show();

      var frete = $('#codigo_servico').val();
    

      
      
        event.preventDefault();

        $.ajax({
      url:  "finalizar-compra.php",
      method: "post",
      data: $('#form-dados').serialize(),
      dataType: "html",
      success: function(msg){
        console.log(msg);
        if(msg.trim() === 'Selecione um CEP válido para o Frete!'){
            calcularFrete();
            $('#listar-frete').html('');
            $('#mensagem-finalizar').addClass('text-danger')
            $('#mensagem-finalizar').text('Escolha um Frete para Envio');
        }

        else if(msg.trim() === 'Preencha o Campo Rua!'){
                $('#mensagem-finalizar').addClass('text-danger')
            $('#mensagem-finalizar').text(msg);
         } 

          else if(msg.trim() === 'Preencha o Campo Número!'){
                $('#mensagem-finalizar').addClass('text-danger')
            $('#mensagem-finalizar').text(msg);
         } 

          else if(msg.trim() === 'Preencha o Campo Bairro!'){
                $('#mensagem-finalizar').addClass('text-danger')
            $('#mensagem-finalizar').text(msg);
         }  

          else if(msg.trim() === 'Preencha o Campo Nome!'){
                $('#mensagem-finalizar').addClass('text-danger')
            $('#mensagem-finalizar').text(msg);
         }      
        else{
            
             var check_entrega = $('#entrega_retirar').val();
                      
            if (check_entrega == 'local' || check_entrega == 'entrega'){ 
               window.location="sistema/painel-cliente";
            }else{
              window.location="pagamentos/index.php?id_venda=" + msg;
            }
            
              
           
        }

        $('#btn-finalizar').show();
        $('#div_img').hide();
        
        
      },
     })



    })
</script>




<script type="text/javascript">
    $('#local').change(function(event){
        event.preventDefault();

        total_final = $('#total_final').text();
        total_final2 = $('#total_final2').val();
        total_final = total_final.replace(".","");
        total_final = total_final.replace(",",".");
        total_final = total_final.replace("R$","");

        tot_frete = $('#val_frete').val();
        if(tot_frete == ""){
          tot_frete = 0;
        }


        $('#mensagem-finalizar').text("");
        var check = document.getElementsByName("local"); 
        for (var i=0;i<check.length;i++){ 
        if (check[i].checked == true){ 
           $('#entrega_retirar').val('local');
            document.getElementById("div-frete").style.display = 'none';
            tot = parseFloat(total_final) - parseFloat(tot_frete);
             $('#entrega').prop('checked', false);

        }  else {
           document.getElementById("div-frete").style.display = 'block';
           tot = parseFloat(total_final) + parseFloat(tot_frete);
           $('#entrega_retirar').val('');
        }       
        
         if(tot >= total_final2){
        tot = tot.toFixed(2);
        $('#total_compra').val(tot);
        tot = "R$ " + tot.replace(".",",");
        console.log(tot);
        $('#total_final').text(tot);
        $('#total_pgto').text(tot);
        $('#formatar').val('nao');
      }
    }

    })
</script>



<script type="text/javascript">
    $('#entrega').change(function(event){
        event.preventDefault();


        total_final = $('#total_final').text();
        total_final2 = $('#total_final2').val();
        total_final = total_final.replace(".","");
        total_final = total_final.replace(",",".");
        total_final = total_final.replace("R$","");

        tot_frete = $('#val_frete').val();
        if(tot_frete == ""){
          tot_frete = 0;
        }

        $('#mensagem-finalizar').text("");
        var check = document.getElementsByName("entrega"); 
        for (var i=0;i<check.length;i++){ 
        if (check[i].checked == true){ 
          $('#entrega_retirar').val('entrega');
            document.getElementById("div-frete").style.display = 'none';
            tot = parseFloat(total_final) - parseFloat(tot_frete);
            $('#local').prop('checked', false);

        }  else {
           document.getElementById("div-frete").style.display = 'block';
           tot = parseFloat(total_final) + parseFloat(tot_frete);
           $('#entrega_retirar').val('');
        }
        if(tot >= total_final2){
         tot = tot.toFixed(2);
       
        $('#total_compra').val(tot);
        tot = "R$ " + tot.replace(".",",");
        console.log(tot);
        $('#total_final').text(tot);
        $('#total_pgto').text(tot);
         $('#formatar').val('nao');
          }
    }

    })
</script>



<script type="text/javascript">
     function alterarcep() {

        $('#cep2').val($('#cep').val());
        //calcularFrete();

    }
</script>





<script type="text/javascript">
  function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('estado').value=("");  
            document.getElementById('numero').value=("");  
            document.getElementById('complemento').value=("");            
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('estado').value=(conteudo.uf);
           
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('estado').value="...";
               

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }


        
    };
</script>


  <script type="text/javascript">
                                                function calcularFrete(){
                                                    var cep = $("#cep2").val();
                                                    if(cep == ''){
                                                      return;
                                                    }
                                                    var id = '';   
                                                    var total_peso = '<?=$total_peso?>';   
                                                    var comprimento_produto = '<?=$comprimento_produto?>';   
                                                    var largura_produto = '<?=$largura_produto?>';   
                                                    var altura_produto = '<?=$altura_produto?>';  


                                                    $.ajax({
                                                       url: 'correios/api_melhor_envio.php',
                                                       method: 'POST',
                                                       data: {cep, id, total_peso, comprimento_produto, largura_produto, altura_produto},
                                                       dataType: "html",

                                                       success:function(result){                
                                                        $("#listar-frete").html(result);                
                                                    }
                                                });

                                                }
                                            </script>


<?php 
require_once("modal-pagamento.php");
 ?>