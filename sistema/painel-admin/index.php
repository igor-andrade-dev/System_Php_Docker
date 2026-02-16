<?php 
require_once("../../conexao.php"); 
@session_start();
    //verificar se o usuário está autenticado

require_once("verificar.php"); 

//verificar se tem estoque baixo
$query = $pdo->query("SELECT * FROM produtos where estoque <= '$nivel_estoque' order by estoque asc ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
    $classe_estoque = 'text-warning';
}else{
    $classe_estoque = '';
}



//verificar se a tabela envio emails está vazia, se tiver inserir um registro
$query = $pdo->query("SELECT * FROM envios_email");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res)==0){
    $pdo->query("INSERT INTO envios_email (data, final, assunto, mensagem, link) values (curDate(), '0', '', '', '') ");
}



$agora = date('Y-m-d');

    //variaveis para o menu
$pag = @$_GET["pag"];
$menu1 = "produtos";
$menu2 = "categorias";
$menu3 = "sub-categorias";
$menu4 = "combos";
$menu5 = "promocoes";
$menu6 = "clientes";
$menu7 = "vendas";
$menu8 = "backup";
$menu9 = "tipo-envios";
$menu10 = "carac";
$menu11 = "alertas";
$menu12 = "cupons";
$menu13 = "estoque";
$menu14 = "blog";
$menu15 = "banner";
$menu16 = "tipos_frete";

//CONSULTAR O BANCO DE DADOS E TRAZER OS DADOS DO USUÁRIO
$res = $pdo->query("SELECT * FROM usuarios where id = '$_SESSION[id_usuario]'"); 
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_usu = @$dados[0]['nome'];
$email_usu = @$dados[0]['email'];
$cpf_usu = @$dados[0]['cpf'];
$imagem_usu = @$dados[0]['imagem'];


//SCRIPT PARA VERIFICAR OS PRODUTOS QUE ESTÃO EM PROMOÇÃO
$pdo->query("UPDATE produtos SET promocao = 'Não' "); 
$res = $pdo->query("SELECT * FROM promocoes where ativo = 'Sim' and data_inicio <= curDate() and data_final >= curDate() "); 
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < count($dados); $i++) { 
  foreach ($dados[$i] as $key => $value) {
  }
  $id_pro = @$dados[$i]['id_produto'];

  $pdo->query("UPDATE produtos SET promocao = 'Sim' where id = $id_pro"); 
}
?>






<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Hugo Vasconcelos">

    <title>Painel Administrativo</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <link rel="icon" href="../../img/favicon.png" type="image/x-icon">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">

                <div class="sidebar-brand-text mx-3">Administrador</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">



            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Cadastros
            </div>



            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-box-open"></i>
                    <span>Produtos</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item" href="index.php?pag=<?php echo $menu1 ?>">Produtos</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu2 ?>">Categorias</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu3 ?>">Sub Categorias</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu9 ?>">Tipo Envios</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu16 ?>">Tipos Frete</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu10 ?>">Características</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-percent"></i>
                    <span>Combos e Promoções</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item" href="index.php?pag=<?php echo $menu4 ?>">Combos</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu5 ?>">Promoções</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu11 ?>">Alertas</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu15 ?>">Banner Principal</a>

                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Consultas
            </div>



            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?pag=<?php echo $menu12 ?>">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Cupons</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php?pag=<?php echo $menu6 ?>">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Clientes</span></a>
                    </li>

                    <!-- Nav Item - Tables -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pag=<?php echo $menu7 ?>">
                            <i class="fas fa-fw fa-table"></i>
                            <span>Vendas</span></a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="index.php?pag=<?php echo $menu13 ?>">
                                <i class="fas fa-fw fa-table <?php echo $classe_estoque ?>"></i>
                                <span class="<?php echo $classe_estoque ?>">Estoque Baixo</span></a>
                            </li>




                            <!-- Divider -->
                            <hr class="sidebar-divider d-none d-md-block">


                            <li class="nav-item">
                                <a class="nav-link" href="" data-toggle="modal" data-target="#ModalEmail">
                                    <i class="fas fa-fw fa-table"></i>
                                    <span>Email Marketing</span></a>
                                </li>


                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?pag=<?php echo $menu14 ?>">
                                        <i class="fas fa-fw fa-table"></i>
                                        <span class="">Blog</span></a>
                                    </li>

                                

                                        <!-- Divider -->
                                        <hr class="sidebar-divider d-none d-md-block">



                                        <div class="sidebar-heading">
                                            Relatórios
                                        </div>



                                        <!-- Nav Item - Charts -->
                                        <li class="nav-item">
                                            <a class="nav-link" href="" data-toggle="modal" data-target="#RelVendas">
                                                <i class="fas fa-fw fa-chart-area"></i>
                                                <span>Vendas</span></a>
                                            </li>

                                            <!-- Divider -->
                                            <hr class="sidebar-divider d-none d-md-block">


                                            <!-- Sidebar Toggler (Sidebar) -->
                                            <div class="text-center d-none d-md-inline">
                                                <button class="rounded-circle border-0" id="sidebarToggle"></button>
                                            </div>

                                        </ul>
                                        <!-- End of Sidebar -->

                                        <!-- Content Wrapper -->
                                        <div id="content-wrapper" class="d-flex flex-column">

                                            <!-- Main Content -->
                                            <div id="content">

                                                <!-- Topbar -->
                                                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                                                    <!-- Sidebar Toggle (Topbar) -->
                                                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                                        <i class="fa fa-bars"></i>
                                                    </button>



                                                    <!-- Topbar Navbar -->
                                                    <ul class="navbar-nav ml-auto">



                                                        <!-- Nav Item - User Information -->
                                                        <li class="nav-item dropdown no-arrow">
                                                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo @$nome_usu ?></span>
                                                                <img class="img-profile rounded-circle" src="../../img/<?php echo $imagem_usu ?>">

                                                            </a>
                                                            <!-- Dropdown - User Information -->
                                                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                                                <a class="dropdown-item" href="" data-toggle="modal" data-target="#ModalPerfil">
                                                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                                                                    Editar Perfil
                                                                </a>

                                                                <a class="dropdown-item" href="" data-toggle="modal" data-target="#ModalConfig">
                                                                    <i class="fas fa-cog fa-sm fa-fw mr-2 text-primary"></i>
                                                                    Configurações
                                                                </a>

                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="../logout.php">
                                                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                                                                    Sair
                                                                </a>
                                                            </div>
                                                        </li>

                                                    </ul>

                                                </nav>
                                                <!-- End of Topbar -->

                                                <!-- Begin Page Content -->
                                                <div class="container-fluid">

                                                    <?php if ($pag == null) { 
                                                        include_once("home.php"); 

                                                    } else if ($pag==$menu1) {
                                                        include_once($menu1.".php");

                                                    } else if ($pag==$menu2) {
                                                        include_once($menu2.".php");

                                                    } else if ($pag==$menu3) {
                                                        include_once($menu3.".php");

                                                    } else if ($pag==$menu4) {
                                                        include_once($menu4.".php");

                                                    } else if ($pag==$menu5) {
                                                        include_once($menu5.".php");

                                                    } else if ($pag==$menu6) {
                                                        include_once($menu6.".php");

                                                    } else if ($pag==$menu7) {
                                                        include_once($menu7.".php");

                                                    } else if ($pag==$menu8) {
                                                        include_once($menu8.".php");

                                                    } else if ($pag==$menu9) {
                                                        include_once($menu9.".php");

                                                    } else if ($pag==$menu10) {
                                                        include_once($menu10.".php");

                                                    } else if ($pag==$menu11) {
                                                        include_once($menu11.".php");

                                                    } else if ($pag==$menu12) {
                                                        include_once($menu12.".php");

                                                    } else if ($pag==$menu13) {
                                                        include_once($menu13.".php");

                                                    } else if ($pag==$menu14) {
                                                        include_once($menu14.".php");

                                                    } else if ($pag==$menu15) {
                                                        include_once($menu15.".php");

                                                    } else if ($pag==$menu16) {
                                                        include_once($menu16.".php");


                                                    } else {
                                                        include_once("home.php");
                                                    }
                                                    ?>



                                                </div>
                                                <!-- /.container-fluid -->

                                            </div>
                                            <!-- End of Main Content -->



                                        </div>
                                        <!-- End of Content Wrapper -->

                                    </div>
                                    <!-- End of Page Wrapper -->

                                    <!-- Scroll to Top Button-->
                                    <a class="scroll-to-top rounded" href="#page-top">
                                        <i class="fas fa-angle-up"></i>
                                    </a>




                                    <!--  Modal Perfil-->
                                    <div class="modal fade" id="ModalPerfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Editar Perfil</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>



                                                <form id="form-perfil" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-md-8">
                                                             <div class="form-group">
                                                                <label >Nome</label>
                                                                <input value="<?php echo @$nome_usu ?>" type="text" class="form-control" id="nome-usuario" name="nome-usuario" placeholder="Nome">
                                                            </div>

                                                            <div class="form-group">
                                                                <label >CPF</label>
                                                                <input value="<?php echo @$cpf_usu ?>" type="text" class="form-control" id="cpf-usuario" name="cpf-usuario" placeholder="CPF">
                                                            </div>

                                                            <div class="form-group">
                                                                <label >Email</label>
                                                                <input value="<?php echo @$email_usu ?>" type="email" class="form-control" id="email-usuario" name="email-usuario" placeholder="Email">
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label >Senha</label>
                                                                        <input value="" type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                 <div class="form-group">
                                                                    <label >Confirmar Senha</label>
                                                                    <input value="" type="password" class="form-control" id="conf-senha" name="conf-senha" placeholder="Senha">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label >Imagem</label>
                                                        <input type="file" value="<?php echo @$imagem_usu ?>"  class="form-control-file" id="imagem" name="imagem" onChange="carregarImg();">
                                                    </div>

                                                    <?php if(@$imagem_usu != ""){ ?>
                                                     <img src="../../img/<?php echo $imagem_usu ?>" width="200" height="200" id="target">
                                                 <?php  }else{ ?>
                                                    <img src="../../img/sem-foto.jpg" width="200" height="200" id="target">
                                                <?php } ?>

                                            </div>
                                        </div>




                                        <small>
                                            <div id="mensagem-perfil" class="mr-4">

                                            </div>
                                        </small>



                                    </div>
                                    <div class="modal-footer">



                                        <input value="<?php echo $_SESSION['id_usuario'] ?>" type="hidden" name="txtid" id="txtid">
                                        <input value="<?php echo $_SESSION['cpf_usuario'] ?>" type="hidden" name="antigo" id="antigo">

                                        <button type="button" id="btn-fechar-perfil" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="btn-salvar-perfil" id="btn-salvar-perfil" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>





                    <!--  Modal Email-->
                    <div class="modal fade" id="ModalEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Email Marketing</h5>

                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>



                                <form id="form-perfil" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">

                                        <?php 

                                        $query = $pdo->query("SELECT * FROM emails where ativo = 'Sim' ");
                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                        $total_emails = @count($res);
                                        ?>

                                        <p><small>Total de Emails Cadastrados - <?php echo $total_emails ?></small></p>


                                        <div class="form-group">
                                            <label >Assunto Email</label>
                                            <input  type="text" class="form-control" id="assunto-email" name="assunto-email" placeholder="Assunto do Email">
                                        </div>

                                        <div class="form-group">
                                            <label >Link <small>(Se Tiver)</small></label>
                                            <input  type="text" class="form-control" id="link-email" name="link-email" placeholder="Link Caso Exista">
                                        </div>


                                        <div class="form-group">
                                            <label >Mensagem </label>
                                            <textarea maxlength="1000" class="form-control" id="mensagem-email" name="mensagem-email"></textarea>
                                        </div>


                                        <small>
                                            <div id="mensagem-email-marketing" class="mr-4">

                                            </div>
                                        </small>



                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" id="btn-fechar-email" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="btn-salvar-email" id="btn-salvar-email" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>






                    <!--  Modal Rel Mov-->
                    <div class="modal fade" id="RelVendas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Vendas</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>



                                <form action="rel/rel_vendas.php" method="POST" target="_blank">
                                    <div class="modal-body">

                                       <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label >Data Inicial</label>
                                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control"  name="dataInicial" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label >Data Final</label>
                                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control"  name="dataFinal" >
                                            </div>


                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label >Paga</label>
                                                <select class="form-control" name="status" >
                                                    <option value="">Todos</option>
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não">Não</option>

                                                </select>
                                            </div>


                                        </div>

                                    </div>     

                                </div>
                                <div class="modal-footer">

                                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>








                <!--  Modal Config-->
                <div class="modal fade" id="ModalConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Configurações</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>



                            <form id="form-config" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-3">
                                         <div class="form-group">
                                            <label >Nome Loja</label>
                                            <input value="<?php echo @$nome_loja ?>" type="text" class="form-control" id="nome_loja" name="nome_loja" placeholder="Nome da Loja">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label >Telefone Loja</label>
                                            <input value="<?php echo @$telefone_loja ?>" type="text" class="form-control" id="telefone_loja" name="telefone_loja" placeholder="Telefone Loja">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label >Whatsapp Loja</label>
                                            <input value="<?php echo @$whatsapp_loja ?>" type="text" class="form-control" id="whatsapp_loja" name="whatsapp_loja" placeholder="Whatsapp Loja">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label >Email</label>
                                            <input value="<?php echo @$email_adm ?>" type="email" class="form-control" id="email_adm" name="email_adm" placeholder="Email">
                                        </div>
                                    </div>


                                </div>



                                <div class="row">
                                    <div class="col-md-9">
                                      <div class="form-group">
                                        <label >Endereço Loja</label>
                                        <input value="<?php echo @$endereco_loja ?>" type="text" class="form-control" id="endereco_loja" name="endereco_loja" placeholder="Endereço da Loja">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label >CEP Loja</label>
                                    <input value="<?php echo @$cep_origem ?>" type="text" class="form-control" id="cep_origem" name="cep_origem" placeholder="CEP da Loja">
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-8">
                              <div class="form-group">
                                <label >Texto Destaque Cabeçalho</label>
                                <input value="<?php echo @$texto_destaque ?>" type="text" class="form-control" id="texto_destaque" name="texto_destaque" placeholder="Texto do Cabeçalho">
                            </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label >Itens Paginação</label>
                            <input value="<?php echo @$itens_por_pagina ?>" type="number" class="form-control" id="itens_por_pagina" name="itens_por_pagina" placeholder="Itens Por Página">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label >Comprimento Caixa</label>
                        <input value="<?php echo @$comprimento_caixa ?>" type="number" class="form-control" id="comprimento_caixa" name="comprimento_caixa" placeholder="cm">
                    </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label >Largura Caixa</label>
                    <input value="<?php echo @$largura_caixa ?>" type="number" class="form-control" id="largura_caixa" name="largura_caixa" placeholder="cm">
                </div>
            </div>


            <div class="col-md-3">
              <div class="form-group">
                <label >Altura Caixa</label>
                <input value="<?php echo @$altura_caixa ?>" type="number" class="form-control" id="altura_caixa" name="altura_caixa" placeholder="cm">
            </div>
        </div>


        <div class="col-md-3">
          <div class="form-group">
            <label >Diâmetro Caixa</label>
            <input value="<?php echo @$diametro_caixa ?>" type="number" class="form-control" id="diametro_caixa" name="diametro_caixa" placeholder="cm">
        </div>
    </div>


</div>




<div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label >Mão Própria</label>        
        <select class="form-control" name="mao_propria" id="mao_propria">
            <option <?php if($mao_propria == 'N'){ ?> selected <?php } ?> value="N">Não</option>
            <option <?php if($mao_propria == 'S'){ ?> selected <?php } ?> value="S">Sim</option>                               

        </select>
    </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <label >Valor Declarado</label>       
    <select class="form-control" name="valor_declarado" id="valor_declarado">
        <option <?php if($valor_declarado == '0'){ ?> selected <?php } ?> value="0">Não</option>
        <option <?php if($valor_declarado == '1'){ ?> selected <?php } ?> value="1">Sim</option>                               

    </select>
</div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <label >Aviso Recebimento AR</label>        
    <select class="form-control" name="aviso_recebimento" id="aviso_recebimento">
        <option <?php if($aviso_recebimento == 'N'){ ?> selected <?php } ?> value="N">Não</option>
        <option <?php if($aviso_recebimento == 'S'){ ?> selected <?php } ?> value="S">Sim</option>                               

    </select>
</div>
</div>


<div class="col-md-3">
  <div class="form-group">
    <label >Formato Frete</label>       
    <select class="form-control" name="formato_frete" id="formato_frete">
        <option <?php if($formato_frete == '1'){ ?> selected <?php } ?> value="1">Caixa / Pacote</option>
        <option <?php if($formato_frete == '2'){ ?> selected <?php } ?> value="2">Rolo / Prisma</option>         

        <option <?php if($formato_frete == '3'){ ?> selected <?php } ?> value="3">Envelope</option>                           

    </select>
</div>
</div>

</div>




<div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label >Cartões Troca</label>        
        <input value="<?php echo @$total_cartoes_troca ?>" type="number" class="form-control" id="total_cartoes_troca" name="total_cartoes_troca" placeholder="">
    </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <label >Valor Cupom Cartão</label>       
    <input value="<?php echo @$valor_cupom_cartao ?>" type="text" class="form-control" id="valor_cupom_cartao" name="valor_cupom_cartao" placeholder="Valor R$ Cupom">
</div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <label >Dias Uso Cupom</label>        
    <input value="<?php echo @$dias_uso_cupom ?>" type="number" class="form-control" id="dias_uso_cupom" name="dias_uso_cupom" placeholder="">
</div>
</div>


<div class="col-md-3">
  <div class="form-group">
    <label >Nível Estoque</label>        
    <input value="<?php echo @$nivel_estoque ?>" type="number" class="form-control" id="nivel_estoque" name="nivel_estoque" placeholder="Alerta Estoque">
</div>
</div>

</div>






<div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label >Dias Limpar Carrinho</label>        
        <input value="<?php echo @$dias_limpar_carrinho ?>" type="number" class="form-control" id="dias_limpar_carrinho" name="dias_limpar_carrinho" placeholder="">
    </div>
</div>



<div class="col-md-3">
  <div class="form-group">
    <label >Nota Mínima</label>        
    <input value="<?php echo @$nota_minima ?>" type="number" class="form-control" id="nota_minima" name="nota_minima" placeholder="">
</div>
</div>


<div class="col-md-3">
  <div class="form-group">
    <label >Retirada Local</label>       
    <select class="form-control" name="retirada_local" id="retirada_local">
        <option <?php if($retirada_local == 'Não'){ ?> selected <?php } ?> value="Não">Não</option>
        <option <?php if($retirada_local == 'Sim'){ ?> selected <?php } ?> value="Sim">Sim</option>                               

    </select>
</div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <label >Pagar na Entrega</label>       
    <select class="form-control" name="pagar_entrega" id="pagar_entrega">
        <option <?php if($pagar_entrega == 'Não'){ ?> selected <?php } ?> value="Não">Não</option>
        <option <?php if($pagar_entrega == 'Sim'){ ?> selected <?php } ?> value="Sim">Sim</option>                               

    </select>
</div>
</div>


</div>








<div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label >Enviar Total Emails</label>        
        <input value="<?php echo @$enviar_total_emails ?>" type="number" class="form-control" id="enviar_total_emails" name="enviar_total_emails" placeholder="">
    </div>
</div>



<div class="col-md-4">
  <div class="form-group">
    <label >Intervalo Envio Email</label>        
    <input value="<?php echo @$intervalo_envio_email ?>" type="number" class="form-control" id="intervalo_envio_email" name="intervalo_envio_email" placeholder="">
</div>
</div>


<div class="col-md-4">
  <div class="form-group">
    <label >Relatório PDF</label>       
    <select class="form-control" name="relatorio_pdf" id="relatorio_pdf">
     <option <?php if($relatorio_pdf == 'Sim'){ ?> selected <?php } ?> value="Sim">Sim</option>
     <option <?php if($relatorio_pdf == 'Não'){ ?> selected <?php } ?> value="Não">Não</option>


 </select>
</div>
</div>



</div>


<div class="row">
 <div class="col-md-12">
  <div class="form-group">
    <label >Texto para Rodapé Relatórios</label>       
    <input value="<?php echo @$rodape_relatorios ?>" type="text" class="form-control" id="rodape_relatorios" name="rodape_relatorios" placeholder="Texto para o Rodapé dos Relatórios">
</div>
</div>
</div>


<div class="row">

  <div class="col-md-2">
  <div class="form-group">
    <label >Pix Api</label>       
    <select class="form-control" name="api_pix" id="api_pix">
        <option <?php if($api_pix == 'Sim'){ ?> selected <?php } ?> value="Sim">Sim</option>
        <option <?php if($api_pix == 'Não'){ ?> selected <?php } ?> value="Não">Não</option>         

    </select>
</div>
</div> 


  <div class="col-md-3">
  <div class="form-group">
    <label >Token Whatsapp</label>       
    <input value="<?php echo @$token ?>" type="text" class="form-control" id="token" name="token" placeholder="Token Whatsapp">
</div>
</div>


 <div class="col-md-3">
  <div class="form-group">
    <label >Instancia Whats</label>       
    <input value="<?php echo @$instancia ?>" type="text" class="form-control" id="instancia" name="instancia" placeholder="Instância Whatsapp">
</div>
</div>

</div>



<div class="row">
    <div class="col-md-6">
     <div class="form-group">
        <label >Logo</label>
        <input class="form-control" type="file" value="<?php echo @$logo ?>"  class="form-control-file" id="logo" name="logo" onChange="carregarImgLogo();">
    </div>

    <img src="../../img/<?php echo $logo ?>" width="200px" height="" id="target_logo">

</div>


<div class="col-md-6">
 <div class="form-group">
    <label >Favicon Ícone</label>
    <input class="form-control" type="file" value="<?php echo @$favicon ?>"  class="form-control-file" id="favicon" name="favicon" onChange="carregarImgFavicon();">
</div>

<img src="../../img/<?php echo $favicon ?>" width="25px" height="25px" id="target_favicon">

</div>
</div>


<small>
    <div id="mensagem-config" class="mr-4">

    </div>
</small>



</div>
<div class="modal-footer">



    <input value="<?php echo $_SESSION['id_usuario'] ?>" type="hidden" name="txtid" id="txtid">
    <input value="<?php echo $_SESSION['cpf_usuario'] ?>" type="hidden" name="antigo" id="antigo">

    <button type="button" id="btn-fechar-config" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="submit" name="btn-salvar-config" id="btn-salvar-config" class="btn btn-primary">Salvar</button>
</div>
</form>


</div>
</div>
</div>





<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../js/demo/chart-area-demo.js"></script>
<script src="../js/demo/chart-pie-demo.js"></script>

<!-- Page level plugins -->
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../js/demo/datatables-demo.js"></script>

</body>

</html>



<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->
<script type="text/javascript">
    $("#form-perfil").submit(function () {

        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url:"editar-perfil.php",
            type: 'POST',
            data: formData,

            success: function (msg) {

                $('#mensagem').removeClass()

                if(msg.trim() === 'Salvo com Sucesso!'){

                    $('#btn-fechar-perfil').click();
                    window.location='index.php';

                }
                else{
                    $('#mensagem-perfil').addClass('text-danger')
                    $('#mensagem-perfil').text(msg);


                }



            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function () {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
                return myXhr;
            }
        });
    });
</script>








<script type="text/javascript">
    $('#btn-salvar-email').click(function(event){
        event.preventDefault();
        $('#mensagem-email-marketing').addClass('text-info')
        $('#mensagem-email-marketing').removeClass('text-danger')
        $('#mensagem-email-marketing').removeClass('text-success')
        $('#mensagem-email-marketing').text('Enviando')
        $.ajax({
            url:"email-marketing.php",
            method:"post",
            data: $('form').serialize(),
            dataType: "text",
            success: function(msg){
               if(msg.trim() === 'Enviado com Sucesso!'){
                $('#mensagem-email-marketing').removeClass('text-info')
                $('#mensagem-email-marketing').addClass('text-success')
                $('#mensagem-email-marketing').text(msg);
                $('#assunto-email').val('');
                $('#link-email').val('');
                $('#mensagem-email').val('');


            }else if(msg.trim() === 'Preencha o Campo Assunto!'){

                $('#mensagem-email-marketing').addClass('text-danger')
                $('#mensagem-email-marketing').text(msg);


            }else if(msg.trim() === 'Preencha o Campo Mensagem!'){

                $('#mensagem-email-marketing').addClass('text-danger')
                $('#mensagem-email-marketing').text(msg);



            }

            else{
                $('#mensagem-email-marketing').addClass('text-danger')
                $('#mensagem-email-marketing').text('Deu erro ao Enviar o Formulário! Provavelmente seu servidor de hospedagem não está com permissão de envio habilitada ou você está em um servidor local!');
                    //$('#div-mensagem').text(msg);

                }
            }
        })
    })
</script>



<!--SCRIPT PARA CARREGAR IMAGEM -->
<script type="text/javascript">

    function carregarImg() {

        var target = document.getElementById('target');
        var file = document.querySelector("#imagem").files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }


    function carregarImgLogo() {

        var target = document.getElementById('target_logo');
        var file = document.querySelector("#logo").files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }


    function carregarImgFavicon() {

        var target = document.getElementById('target_favicon');
        var file = document.querySelector("#favicon").files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }

</script>





<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->
<script type="text/javascript">
    $("#form-config").submit(function () {

        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url:"editar-config.php",
            type: 'POST',
            data: formData,

            success: function (msg) {

                $('#mensagem-config').removeClass()

                if(msg.trim() === 'Salvo com Sucesso!'){

                    $('#btn-fechar-config').click();
                    window.location='index.php';

                }
                else{
                    $('#mensagem-config').addClass('text-danger')
                    $('#mensagem-config').text(msg);


                }



            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function () {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
                return myXhr;
            }
        });
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<script src="../../js/mascara.js"></script>
