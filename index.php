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

		<script>
	
	$(document).ready(function () {
		carregarLista();
		
		
		$("#cont_position_topo").hide();
		$(window).scroll(function(){
			if($(window).scrollTop() >= 200)
			{			 
				$("#cont_position_topo").fadeIn(1000);
			}
			else
			{
				$("#cont_position_topo").fadeOut(1000);
			}
		});

		$('#top').click(function(){ 
		$('html, body').animate({scrollTop:0}, 'slow');
			return false;
		});
	});
	
	
	function carregarLista() {
        var dataEscolhida = window.prompt("Digite sua Senha para prosseguir:");

        if (dataEscolhida !== null) {
            // Aqui você pode realizar a lógica desejada com a data inserida
            //alert("Você escolheu a data: " + dataEscolhida);
			var parametro = "PROCURA_USUARIO";
			$.ajax({
				url: 'bd/verifica_usuario.php',
				method: 'POST',
				data: { 
					parametro	: parametro,
					valorVerificar : dataEscolhida
				}, 
				success: function(response) {
					var resultado = response.trim();
					console.log('.'+resultado+'.');
					
					if(resultado > '0'){
						document.getElementById("retorno_id_usuario").value = resultado;
						
						var data_inicial = document.getElementById("data_inicial").value;
						var data_final = document.getElementById("data_final").value;
						
						if((data_inicial != '')&&(data_final != '')){
							document.getElementById("imprimir_contas_pagar").innerHTML = "<div class='alert alert-warning' role='alert'>Aguarde, carregando registros! <img src='../../../imagens/loading.gif' style='width:50px; heigth:50px'></img></div>";
							$.post('processa/div_lista.php?DataInicial='+data_inicial+'&DataFinal='+data_final, function(retorna){
								$("#imprimir_contas_pagar").html(retorna);
							});
						}
					}else{
						
					}
				},
				error: function(xhr, status, error) {
				  console.error('Erro na atualização dos dados:', error);
				}
			});
		}
	}

	function validaRegistro(id) {
		var retorno_id_usuario = document.getElementById("retorno_id_usuario").value;
		var parametro = "VALIDAR";
		$.ajax({
			url: 'bd/update_papeleta.php',
			method: 'POST',
			data: { 
				parametro	: parametro,
				IdPagar 	: id,
				IdValidador : retorno_id_usuario
			}, 
			success: function(response) {
				//alert(response);
				var td_opcoes = document.getElementById("td_opcoes_"+id);
				td_opcoes.style.backgroundColor = "#37bf2a";
				$('#valida_'+id).hide();
				$('#recusa_'+id).show();
			},
			error: function(xhr, status, error) {
			  console.error('Erro na atualização dos dados:', error);
			}
		});
	}

	async function recusaRegistro(id) {
		const visualizaModalRecusar = new bootstrap.Modal(document.getElementById("modalRecusarRegistro"));
		visualizaModalRecusar.show();
		
		document.getElementById("id_recusar_modal").value = id;
		
		const dados = await fetch('bd/visualizar_papeleta.php?id=' + id);
		const resposta = await dados.json();
		console.log(resposta);
		var observacao_papeleta = resposta['dados'].observacao_papeleta;
		document.getElementById("observacao_papeleta").value  =  observacao_papeleta;
	}

	function salvarRecusa() {
		var id_recusar_modal = document.getElementById("id_recusar_modal").value;
		var observacao_papeleta = document.getElementById("observacao_papeleta").value;
		var retorno_id_usuario = document.getElementById("retorno_id_usuario").value;
		
		var update_list = confirm("Deseja mesmo recusar o pagamento para este registro.?");
		if (update_list === true) {
			var parametro = "RECUSAR";
			$.ajax({
				url: 'bd/update_papeleta.php',
				method: 'POST',
				data: { 
					parametro	: parametro,
					ObsPapeleta	: observacao_papeleta,
					IdPagar 	: id_recusar_modal,
					IdValidador : retorno_id_usuario
				}, 
				success: function(response) {
					//alert(response);
					var td_opcoes = document.getElementById("td_opcoes_"+id_recusar_modal);
					td_opcoes.style.backgroundColor = "#eb4034";
					$('#recusa_'+id_recusar_modal).hide();
					$('#valida_'+id_recusar_modal).show();
				},
				error: function(xhr, status, error) {
				  console.error('Erro na atualização dos dados:', error);
				}
			});
		}
		else{
			
		}
	}

	async function visualizaRegistro(id){
		const visualizaModalPagar = new bootstrap.Modal(document.getElementById("modalVisualizaRegistro"));
		visualizaModalPagar.show();

		document.getElementById("conteudo_modal_visualizar").innerHTML = "<div class='alert alert-warning' role='alert'>Aguarde, carregando registros! <img src='../../../../imagens/loading.gif' style='width:50px; heigth:50px'></img></div>";
		$.post('processa/div_modal_visualizar.php?Id='+id, function(retorna){
			$("#conteudo_modal_visualizar").html(retorna);
		});
	}

	function salvarRecusa() {
		var data_inicial = document.getElementById("data_inicial").value;
		var data_final = document.getElementById("data_final").value;
		
		var update_list = confirm("Deseja mesmo validar todos registros?");
		if (update_list === true) {
			
		}
	}
	

	function validarTodos() {
		var retorno_id_usuario = document.getElementById("retorno_id_usuario").value;
		var data_inicial = document.getElementById("data_inicial").value;
		var data_final = document.getElementById("data_final").value;
		
		var valid_list = confirm("Deseja mesmo validar todos os registros aberto?");
		if (valid_list === true) {
			console.log(retorno_id_usuario);
			
			var parametro = "VALIDAR_TODOS";
			$.ajax({
				url: 'bd/update_papeleta.php',
				method: 'POST',
				data: { 
					parametro	: parametro,
					IdValidador : retorno_id_usuario,
					DataInicial	: data_inicial,
					DataFinal	: data_final
				}, 
				success: function(response) {
					alert('Registro verificados.!!!');
					
					var data_inicial = document.getElementById("data_inicial").value;
					var data_final = document.getElementById("data_final").value;
					
					if((data_inicial != '')&&(data_final != '')){
						document.getElementById("imprimir_contas_pagar").innerHTML = "<div class='alert alert-warning' role='alert'>Aguarde, carregando registros! <img src='../../../imagens/loading.gif' style='width:50px; heigth:50px'></img></div>";
						$.post('processa/div_lista.php?DataInicial='+data_inicial+'&DataFinal='+data_final, function(retorna){
							$("#imprimir_contas_pagar").html(retorna);
						});
					}
				},
				error: function(xhr, status, error) {
				  console.error('Erro na atualização dos dados:', error);
				}
			});
		}
	}
	
	

		</script>

	</body>
</html>


