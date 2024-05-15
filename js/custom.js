	
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
	
	