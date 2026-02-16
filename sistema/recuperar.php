<?php


require_once("../conexao.php");

$email = filter_var(@$_POST['email-recuperar'], @FILTER_SANITIZE_STRING);

if($email == ""){
    echo 'Preencha o Campo Email!';
    exit();
}

$res = $pdo->prepare("SELECT * FROM usuarios where email = :email and nivel != 'Admin'"); 
 $res->bindValue(":email", "$email");
    $res->execute();
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$telefone = @$dados[0]['telefone'];

if(@count($dados) > 0){
    
    $nova_senha = rand(100000, 100000000);
    $senha_crip = password_hash($nova_senha, PASSWORD_DEFAULT);
    $query = $pdo->prepare("UPDATE usuarios SET senha_crip = '$senha_crip' where email = :email");
    $query->bindValue(":email", "$email");
    $query->execute();
   
   //ENVIAR O EMAIL COM A SENHA
    $destinatario = $email;
    $assunto = $nome_loja . ' - Recuperação de Senha';
    $mensagem = utf8_decode('Sua senha é ' .$nova_senha);
    $cabecalhos = "From: ".$email;
    mail($destinatario, $assunto, $mensagem, $cabecalhos);

    echo 'Senha Enviada para o Email!';
    exit();
}else{
   echo 'Este email não está cadastrado!';
   exit();

}



$res = $pdo->prepare("SELECT * FROM clientes where email = :email "); 
$res->bindValue(":email", "$email");
$res->execute();
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$telefone = $dados[0]['telefone'];

//enviar whatsapp
if($token != "" and $instancia != ''){
//enviar dados da compra via whatsapp cliente
$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

$mensagem = '😀😀%0A';
$mensagem .= '*'.$nome_loja.'*%0A';
$mensagem .= '_Sua nova senha é_ %0A';
$mensagem .= '*'.$nova_senha.'*';
require("../api_whatsapp/texto.php");
}

?>