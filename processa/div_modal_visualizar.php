<?php
	/*
		if(!isset($_SESSION)) { session_start(); }
		$fk_secao_usuario 	= $_SESSION['fk_secao_usuario'];
		$nivel 				= $_SESSION['nivel'];
		$usuario 			= $_SESSION['nome'];
	*/
	$date_time 			= date("Y-m-d");
	
	include "../../../conectar.php"; 
	
	$Id	= $_GET['Id'];
	
	$sql = "SELECT * FROM pagar where id_pagar = '$Id'  ";
	$query = $con_softline->query($sql);
	while ($dado = $query->fetch_assoc()) {
		
		if($dado['validacao_papeleta'] == '1'){
			$validacao_papeleta = 'VALIDADO';
		}elseif($dado['validacao_papeleta'] == '2'){
			$validacao_papeleta = 'RECUSADO';
		}else{
			$validacao_papeleta = '';
		}
		
?>
	<input type="hidden" value="<?php echo $Id;?>" id="id_visualiza_registro" name="id_visualiza_registro" />
	<div class="form-row contorno_novo" id="">
		<div class="col-md-2">
			<label for="">Cód. Fornecedor</label>
			<input type="text" class="col-md-12" name="" id="" value="<?php echo $dado['cod_fornecedor'];?>" readonly />
		</div>

		<div class="col-md-4">
			<label for="">Fornecedor</label>
			<input type="text" class="col-md-12" name="" id="" value="<?php echo $dado['nome_fornecedor'];?>" readonly />
		</div>

		<div class="col-md-2">
			<label for="">Número Doc.</label>
			<input type="text" class="col-md-12" name="" id="" value="<?php echo $dado['n_documento'];?>"  readonly />
		</div>
		
		<div class="col-md-2">
			<label for="">Série</label>
			<input type="text" class="col-md-12" name="" id="" value="<?php echo $dado['serie_documento'];?>"  readonly />
		</div>
		
		<div class="col-md-2"></div>
		
		<div class="col-md-2">
			<label for="">Data de Emissão</label>
			<input type="date" class="col-md-12" name="" id="" value="<?php echo $dado['data_emissao'];?>" readonly />
		</div>
		
		<div class="col-md-2">
			<label for="">Data de Vencimento</label>
			<input type="date" class="col-md-12" name="" id="" value="<?php echo $dado['data_vencimento'];?>" readonly />
		</div>
		
		<div class="col-md-2">
			<label for="">Data de Pagamento</label>
			<input type="date" class="col-md-12" name="" id="" value="<?php echo $dado['data_pagamento'];?>" readonly />
		</div>
		
		<div class="col-md-2">
			<label for="">Valor a Pagar</label>
			<input type="text" class="col-md-12 dinheiro" name="" id="" placeholder="" value="<?php echo number_format( $dado['valor_pagar'], 2, ',', '.');?>" readonly />
		</div>	
		
		<div class="col-md-2">
			<label for="">Valor Pago</label>
			<input type="text" class="col-md-12 dinheiro" name="" id="" placeholder="" value="<?php echo number_format( $dado['valor_pagar'], 2, ',', '.');?>" readonly />
		</div>
		
		<div class="col-md-2"></div>
		
		<div class="col-md-2">
			<label for=""></label>
			<input type="text" class="col-md-12" name="" id="" value="<?php echo $validacao_papeleta;?>" readonly />
		</div>

		<div class="col-md-10">
			<label for="">Observação Papeleta</label>
			<input type="text" class="col-md-12" name="edit_observacao_papeleta" id="edit_observacao_papeleta" value="<?php echo $dado['observacao_papeleta'];?>" />
		</div>
	</div>
	
	
<script>
	$('#edit_observacao_papeleta').blur(function() {
		var observacao_papeleta = document.getElementById("edit_observacao_papeleta").value;
		var id_visualiza_registro = document.getElementById("id_visualiza_registro").value;
		var parametro = "UPDATE";
		
		console.log('.'+parametro+' . '+id_visualiza_registro+' . '+observacao_papeleta+'.');
		
		$.ajax({
			url: 'bd/update_papeleta.php',
			method: 'POST',
			data: { 
				parametro	: parametro,
				ObsPapeleta	: observacao_papeleta,
				IdPagar 	: id_visualiza_registro
			}, 
			success: function(response) {
				//alert(response);
			},
			error: function(xhr, status, error) {
			  console.error('Erro na atualização dos dados:', error);
			}
		});
	});
</script>
	
	
<?php
	}
?>