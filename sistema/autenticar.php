<?php
    @session_set_cookie_params(['httponly' => true]);
@session_start();
@session_regenerate_id(true);
    require_once("../conexao.php");
   
    $email = filter_var(@$_POST['email_login'], @FILTER_SANITIZE_STRING);
    $senha = filter_var(@$_POST['senha_login'], @FILTER_SANITIZE_STRING);

    $res = $pdo->prepare("SELECT * FROM usuarios where email = :email or cpf = :email "); 
    $res->bindValue(":email", "$email");
    $res->execute();
    $dados = $res->fetchAll(PDO::FETCH_ASSOC);
    
    if(@count($dados) > 0){

        if(!password_verify($senha, $dados[0]['senha_crip'])){
        echo '<script>window.alert("Dados Incorretos!!")</script>'; 
        echo '<script>window.location="index.php"</script>';  
        exit();
    }


    	$_SESSION['id_usuario'] = $dados[0]['id'];
    	$_SESSION['nome_usuario'] = $dados[0]['nome'];
    	$_SESSION['email_usuario'] = $dados[0]['email'];
    	$_SESSION['cpf_usuario'] = $dados[0]['cpf'];
    	$_SESSION['nivel_usuario'] = $dados[0]['nivel'];
        $_SESSION['54548df5s4dfd54'] = 'fdsfdsfds4f5sdf45';

    	if($_SESSION['nivel_usuario'] == 'Admin'){
    		echo "<script language='javascript'> window.location='painel-admin' </script>";
    	}

    	if($_SESSION['nivel_usuario'] == 'Cliente'){
            if(@$_SESSION['hora_compra'] != ""){
                echo "<script language='javascript'> window.location='../checkout.php' </script>";
            }
    		echo "<script language='javascript'> window.location='../' </script>";
    	}



    }else{
    	echo "<script language='javascript'> window.alert('Dados Incorretos!') </script>";
    	echo "<script language='javascript'> window.location='index.php' </script>";

    }

?>