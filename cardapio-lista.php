<?php
ob_start();
session_start();

require_once("conexao/conexao.php");

function paraMoeda($valor){
	$recebido = $valor;
	$transformado = str_replace(".",",",$recebido);
	$transformado = "R$ ".$transformado;
	return ($transformado);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Sego - Restaurant Bootstrap Admin Dashboard</title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

	<?php include("include/css.php"); ?>

	<link
		href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
		rel="stylesheet">
</head>

<body class="bg-modal-cardapio">
	<h5 class="mb-0 font-gilroy-bold valor_modal_cardapio fake-h5">R$ 0,00</h5>

	<!--*******************
        Preloader start
    ********************-->
	<div id="preloader">
		<div class="sk-three-bounce">
			<div class="sk-child sk-bounce1"></div>
			<div class="sk-child sk-bounce2"></div>
			<div class="sk-child sk-bounce3"></div>
		</div>
	</div>
	<!--*******************
        Preloader end
    ********************-->

	<!--**********************************
        Main wrapper start
    ***********************************-->
	<div id="main-wrapper">

		<?php include("include/nav_header.php"); ?>
		<?php include("include/header.php"); ?>
		<?php include("include/sidebar.php"); ?>

		<!--**********************************
            Content body start
        ***********************************-->
		<div class="content-body">
			<div class="container-fluid">
				<div class="row mb-5">
                    <div class="col-auto mr-auto">
                        <h3 class="text-black mb-0 pt-2 mt-1">Cardapio</h3>
                        <p class="mb-0">Produtos disponiveis para venda.</p>
                    </div>
                    <div class="col-3 bg-white btn py-2 shadow-sm">
                        <div class="row">
                            <div class="col-2 text-center pt-2">
                                <i class="far fa-search fs-30 mt-1 text-primary"></i>
                            </div>
                            <div class="col-10 px-2">
                                <div class="form-group m-0">
                                    <input name="pesquisa" type="text" class="form-control p-0 border-0 text-dark placeholder-dark" style="font-size: 16px; font-weight: 600;" placeholder="Procure aqui" id="inputBusca">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary d-flex noBorderFocus h-100 pt-3 shadow-sm" data-target="#addCardapio" data-toggle="modal">
                            <span class="" style="font-size: 18px; padding-top: 7px;"><i class="fa fa-plus"></i> Adicionar</span>
                        </button>
                    </div>
                </div>

				<div class="row">
					<div class="col-lg-12 col-sm-12">
						<div class="row box-itens-carapio-loja">
							
						</div>
					</div>
					<div class="col-lg-4 d-none"> <!-- d-sm-none d-md-block -->
						<div class="row">
							<div class="col-12">
								<div class="card shadow">
									<div class="card-header border-0 px-4 pt-3 pb-1">
										<p class="font-gilroy-semibold text-terceiro f-17 mb-0">Morango com suspiro</p>
										<p class="font-gilroy-bold text-quarta f-17 mb-0">210ml</p>
									</div>
									<div class="card-body bg-white px-4 py-3">
										<div class="media">
											<img class="img-fluid mr-2 mt-1" width="50" src="./images/logo_ifood.png" alt="ifood">
											<div class="px-1">
												<small class="font-gilroy-medium desc_valor">PRODUTO</small>
												<h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">R$ 3,40</h5>
											</div>
											<h5 class="px-1 mt-4">.....</h5>
											<div class="px-1">
												<small class="font-gilroy-medium desc_valor">EMBALAGENS</small>
												<h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">R$ 1,05</h5>
											</div>
											<div class="media-footer media-footer-valor-variacao">
												<small class="font-gilroy-medium desc_valor">VENDA</small>
												<h5 class="mb-0 font-gilroy-bold valor_modal_cardapio text-quinta">R$ 13,00</h5>
											</div>
										</div>
									</div>
									<div class="card-body bg-faixa-footer px-4 py-2">
										<div class="media">
											<div class="pl-1 pr-4">
												<img class="img-fluid mr-1" width="26" src="./images/icon_ifood.png" alt="mastercard">
												<small class="font-gilroy-semibold footer-valor text-quinta">R$ 12,70</small>
											</div>
											<div class="pr-3">
												<img class="img-fluid mr-1" width="26" src="./images/icon_pix.png" alt="maestro">
												<small class="font-gilroy-semibold footer-valor text-quinta">R$ 12,85</small>
											</div>
											<div class="">
												<img class="img-fluid" width="26" src="./images/icon_dinheiro.png" alt="dinheiro">
												<small class="font-gilroy-semibold footer-valor text-quinta">R$ 14,00</small>
											</div>
											<div class="media-footer media-footer-valor-variacao">
												<small class="font-gilroy-medium footer-valor-2 text-quinta">+100% | R$ 4,75</small>
											</div>
										</div>
									</div>
									<div class="card-body bg-white px-4 py-3">
										<div class="media">
											<img class="img-fluid mr-2 mt-1" width="50" src="./images/logo_whatsapp.png" alt="whatsapp">
											<div class="px-1">
												<small class="font-gilroy-medium desc_valor">PRODUTO</small>
												<h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">R$ 12,70</h5>
											</div>
											<h5 class="px-1 mt-4">.....</h5>
											<div class="px-1">
												<small class="font-gilroy-medium desc_valor">EMBALAGENS</small>
												<h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">R$ 12,85</h5>
											</div>
											<div class="media-footer media-footer-valor-variacao">
												<small class="font-gilroy-medium desc_valor">VENDA</small>
												<h5 class="mb-0 font-gilroy-bold valor_modal_cardapio text-quinta">R$ 13,00</h5>
											</div>
										</div>
									</div>
									<div class="card-body bg-faixa-footer px-4 py-2">
										<div class="media">
											<div class="pl-1 pr-4">
												<img class="img-fluid mr-1" width="26" src="./images/icon_mastercard.png" alt="mastercard">
												<small class="font-gilroy-semibold footer-valor text-quinta">R$ 12,70</small>
											</div>
											<div class="pr-3">
												<img class="img-fluid mr-1" width="26" src="./images/icon_maestro.png" alt="maestro">
												<small class="font-gilroy-semibold footer-valor text-quinta">R$ 12,85</small>
											</div>
											<div class="">
												<img class="img-fluid" width="26" src="./images/icon_dinheiro.png" alt="dinheiro">
												<small class="font-gilroy-semibold footer-valor text-quinta">R$ 13,00</small>
											</div>
											<div class="media-footer media-footer-valor-variacao">
												<small class="font-gilroy-medium footer-valor-2 text-quinta">+100% | R$ 5,75</small>
											</div>
										</div>
									</div>
								</div>  
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Content body end
        ***********************************-->

		<!-- Add Order -->
		<?php include("include/modal_order.php"); ?>
		<?php include("include/modal_add_cardapio.php"); ?>
		<!--**********************************
            Footer start
        ***********************************-->
		<div class="footer">
			<div class="copyright">
				<p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/"
						target="_blank">DexignZone</a> 2021</p>
			</div>
		</div>
		<!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

		<!--**********************************
           Support ticket button end
        ***********************************-->


	</div>
	<!--**********************************
        Main wrapper end
    ***********************************-->

	<!--**********************************
		Scripts
	***********************************-->
	<?php include("include/js.php"); ?>

	<script type="text/javascript">

	</script>

	<script src="./js/cardapio.js"></script>
</body>

</html>