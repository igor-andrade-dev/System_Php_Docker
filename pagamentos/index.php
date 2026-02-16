<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);

include("./config.php");
include("../conexao.php");

@session_start();
$id_venda = @$_GET['id_venda'];
$id_usuario = @$_SESSION['id_usuario'];
$nome_usuario = @$_SESSION['nome_usuario'];
$email_usuario = @$_SESSION['email_usuario'];

$res = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$cpf_usuario = $dados[0]['cpf'];


$res = $pdo->query("SELECT * from clientes where cpf = '$cpf_usuario'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$telefone = $dados[0]['telefone'];
$rua = $dados[0]['rua'];
$numero = $dados[0]['numero'];
$bairro = $dados[0]['bairro'];
$complemento = $dados[0]['complemento'];
$cep = $dados[0]['cep'];
$cidade = $dados[0]['cidade'];
$estado = $dados[0]['estado'];

$query = $pdo->query("SELECT * FROM vendas where id = '" . $id_venda . "' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$vlr_venda = $res[0]['total'];
$vlr_venda_sem_format = $vlr_venda;
$vlr_venda = number_format($vlr_venda, 2, ',', '.');

$valor = $vlr_venda_sem_format;
$token_valor = ($valor!="")? sha1($valor) : "";
$doc = $cpf_usuario;
$doc =  str_replace(array(",", ".", "-", "/", " "), "", $doc);
$ref = $_REQUEST["ref"];
$email = $email_usuario;
$gerarDireto = $_REQUEST["gerarDireto"];
$descricao = ($_REQUEST["descricao"]!="")? $_REQUEST["descricao"] : $DESCRICAO_PAGAMENTO;
$nome = $nome_usuario;
$sobrenome = $_REQUEST["sobrenome"];
?>
<html lang="pt-br">
<head>
    <title>Pagamento</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link href="./assets/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/signin.css" rel="stylesheet">
    <script src="./assets/jquery-3.6.4.min.js"></script>
</head>
<body  class="text-center">





<div style="max-width: 500px; max-height: 800px; margin: 0 auto;  text-align: center; margin-bottom: 20px; word-break: break-all;" >


<div id="info_pagamento" style="text-align: center; margin-top: 70px">
        
 <img class="mb-4" src="assets/img/logo.png" alt="" style="width:130px;height:auto; display:inline-block; margin-right: 20px; margin-top: 15px">       
       <h4 class="h3 mb-3 font-weight-normal" style=" font-size: 22px; border-radius: 4px; display:flex; display:inline-block;">R$ <?=$vlr_venda;?></h4>  


</div>    

<div  id="paymentBrick_container">
        </div>
        <div id="statusScreenBrick_container">
        </div>
        <div class="form-signin" id="form-pago" style="display:none;text-align: center;">
            <h1 class="h3 mb-3 font-weight-normal">Obrigado!</h1>
            <img class="mb-4"  src="./assets/check_ok.png" alt="" width="120" height="120">
            <br>
            <h3><?=$MSG_APOS_PAGAMENTO;?></h3>
            <br>
            Código do pagamento: <?php echo $_GET["id"]; ?>
        </div>
    </div>
    <style>
        body{font-family:arial}
    </style>
    <script>
        var payment_check;
        const mp = new MercadoPago('<?=$TOKEN_MERCADO_PAGO_PUBLICO;?>', {
            locale: 'pt-BR'
        });
        const bricksBuilder = mp.bricks();
        const renderPaymentBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    amount: '<?=$valor;?>',
                    payer: {
                        firstName: "<?=$nome;?>",
                        lastName: "<?=$sobrenome;?>",
                        email: "<?=$email;?>",
                        identification: {
                            type: '<?=(strlen($doc)>11? "CNPJ" : "CPF");?>',
                            number: '<?=$doc;?>',
                        },
                        address: {
                            zipCode: '',
                            federalUnit: '',
                            city: '',
                            neighborhood: '',
                            streetName: '',
                            streetNumber: '',
                            complement: '',
                        }
                    },
                },
                customization: {
                    visual: {
                        style: {
                            theme: "dark",
                        },
                    },
                    paymentMethods: {
                        <?php if($ATIVAR_CARTAO_CREDITO=="1"){?>creditCard: "all",<?php } ?>
                        <?php if($ATIVAR_CARTAO_DEBIDO=="1"){?>debitCard: "all",<?php } ?>
                        <?php if($ATIVAR_BOLETO=="1"){?>ticket: "all",<?php } ?>
                        <?php if($ATIVAR_PIX=="1"){?>bankTransfer: "all",<?php } ?>
                        maxInstallments: 12
                    },
                },
                callbacks: {
                    onReady: () => {
                    },
                    onSubmit: ({ selectedPaymentMethod, formData }) => {

                        formData.external_reference = '<?=$ref;?>';
                        formData.description = '<?=$descricao;?>';
                        var id_venda = '<?=$id_venda;?>';

                        return new Promise((resolve, reject) => {
                            fetch("./process_payment.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify(formData),
                            })
                            .then((response) => response.json())
                            .then((response) => {
                // receber o resultado do pagamento
                                if(response.status==true){
                                    window.location.href = "./index.php?id="+response.id+"&id_venda="+id_venda;
                                }
                                if(response.status!=true){
                                    alert(response.message);
                                }
                                resolve();
                            })
                            .catch((error) => {
                                reject();
                            });
                        });
                    },
                    onError: (error) => {
                        console.error(error);
                    },
                },
            };
            window.paymentBrickController = await bricksBuilder.create(
                "payment",
                "paymentBrick_container",
                settings
                );
        };

        const renderStatusScreenBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    paymentId: '<?=$_GET["id"];?>',
                },
                customization: {
                    visual: {
                        hideStatusDetails: false,
                        hideTransactionDate: false,
                        style: {
            theme: 'dark', // 'default' | 'dark' | 'bootstrap' | 'flat'
        }
    },
    backUrls: {
        //'error': '<http://<your domain>/error>',
        //'return': '<http://<your domain>/homepage>'
    }
},
callbacks: {
    onReady: () => {
        check("<?=$_GET["id"];?>", "<?=$_GET["id_venda"];?>");
    },
    onError: (error) => {
    },
},
};
window.statusScreenBrickController = await bricksBuilder.create('statusScreen', 'statusScreenBrick_container', settings);
};

<?php if($_GET["id"]!=""){ ?>
    renderStatusScreenBrick(bricksBuilder);
<?php } else { ?>
    <?php if($valor==""){?>
        alert("O valor do pagamento está vazio.");
    <?php } ?>
    renderPaymentBrick(bricksBuilder);
<?php } ?>
var redi = "<?=$URL_REDIRECIONAR;?>";
function check(id, id_venda) {
    var settings = {
        "url": "./process_payment.php?acc=check&id=" + id + "&id_venda="+id_venda,
        "method": "GET",
        "timeout": 0
    };
    $.ajax(settings).done(function(response) {
        try {
            if (response.status == "pago") {
                $("#statusScreenBrick_container").slideUp("fast");
                $("#form-pago").slideDown("fast");
                if (redi != "") {
                    setTimeout(() => {
                        window.location = redi;
                    }, 5000);
                }
            } else {
                setTimeout(() => {
                    check(id)
                }, 3000);
            }
        } catch (error) {
            alert("Erro ao localizar o pagamento, contacte com o suporte");
        }
    });
}
</script>
</body>
</html>