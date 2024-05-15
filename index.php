<?php 
	$diretorio = "../../"; 
	include_once "../../conectar.php"; 
	if((!empty($_GET['DataInicial']))and(!empty($_GET['DataFinal']))){
		$DataInicial = $_GET['DataInicial'];
		$DataFinal = $_GET['DataFinal'];
	}
	else{
		$DataInicial = "";
		$DataFinal = "";
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Noromix - Papeleta Eletrônica</title>
		<link rel="shortcut icon" href="<?php echo $diretorio; ?>favicon.ico" />
		<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />

		<meta http-equiv="Content-Language" content="pt-br">
		<script src="<?php echo $diretorio; ?>ajax/jszip.min.js"></script>
		<script src="<?php echo $diretorio; ?>DataTables/jQuery-3.3.1/jquery-3.3.1.js"></script>
		<link href="<?php echo $diretorio; ?>Font-Awesome-6.x/css/all.css" rel="stylesheet">

		<!-- DATA TABLES -->
		<style type="text/css">
			@import "<?php echo $diretorio; ?>DataTables/DataTables-1.10.18/css/bootstrap2.css";
		</style>
		<link href="links_includes/estilos.css" rel="stylesheet" type="text/css">
		
		<!-- Popper.JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
		<!-- Bootstrap JS -->
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
		<!-- jQuery Custom Scroller CDN -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
	</head>

	<body>
		<h1>Tela de autorização da Papeleta Eletrônica</h1>
		<div class="col-md-12 form-row" style="font-size:14px;">
			<div class="col-md-3"></div>
			<div class="col-md-5 form-row" id='periodo_datas'>
				<div class="col-md-5">
					<p class="col-md-12 ">Data Inicial Vencimento:
						<input type="date" id="data_inicial" name="data_inicial" class="form-control col-md-12" value="<?php echo $DataInicial;?>" tabindex="7">
					</p>
				</div>

				<div class="col-md-5">
					<p class="col-md-12 ">Data Final Vencimento:
						<input type="date" id="data_final" name="data_final" class="form-control col-md-12" value="<?php echo $DataFinal;?>" tabindex="8">
					</p>
				</div>
				
				<div class="col-md-2" id="" style='text-align: right'>
					<br>
					<button id="" class="btn btn-default btn-xs" title="Recarregar Informações" onClick="carregarLista()">
						Pesquisar
					</button>
				</div>
			</div>
		</div>
      
		<div id="conteudo"> 
			<input type="hidden" id="retorno_id_usuario" name="retorno_id_usuario" value="" readonly />
			<div id='imprimir_contas_pagar' class=' '>

			</div>
		</div>
		
		<?php 
			include_once "processa/div_modal_lista.php"; 
		?>
		
		<div id="cont_position_topo" style="z-index:999999">
			<div id="cont_boton_top" class="trans">
				<a style="cursor:pointer;" id="top">. </a>
			</div>
		</div>
		<script src="js/custom.js"></script>
	</body>
</html>




