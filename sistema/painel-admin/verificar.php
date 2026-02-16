<?php 
@session_start();
    //verificar se o usuário está autenticado
if(@$_SESSION['id_usuario'] == null || @$_SESSION['nivel_usuario'] != 'Admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";

}


if(@$_SESSION['54548df5s4dfd54'] != 'fdsfdsfds4f5sdf45'){
    echo "<script language='javascript'> window.location='../index.php' </script>";

}

 ?>