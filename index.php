<?php 
	$diretorio = "../../"; 
	include_once "../../conectar.php"; 
	$DataInicial = $_GET['DataInicial'];
	$DataFinal = $_GET['DataFinal'];
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Noromix - Papeleta Eletrônica</title>
		<link rel="shortcut icon" href="<?php echo $diretorio; ?>favicon.ico" />
		<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />

		<meta http-equiv="Content-Language" content="pt-br">
		<link href="<?php echo $diretorio; ?>Font-Awesome-6.x/css/all.css" rel="stylesheet">
		<!-- DATA TABLES -->
		<style type="text/css">
			@import "<?php echo $diretorio; ?>DataTables/DataTables-1.10.18/css/bootstrap2.css";
		</style>
		<link href="links_includes/estilos.css" rel="stylesheet" type="text/css">
	</head>

	<body>
    <h1>Tela de autorização da Papeleta Eletrônica</h1>
      <div class="col-md-12 form-row" style="font-size:14px;">
        &nbsp;&nbsp;&nbsp;&nbsp;
        <b style='color:red'>Atenção: &nbsp;</b> 
          É imprescindível que os faturamentos, tanto de Notas de Serviço quanto de Notas de Produtos, sejam enviados para o email: &nbsp;
        <b style='color:red'> notafiscal@escritoriovotuporanga.com.br</b>
      </div>
      
      <div id="conteudo"> 
	  <div id='imprimir_contas_pagar' class=' '>
			Registros das contas a Pagar Detalhado

			<table>
				<thead>
					<tr>
						<th>Nº Documento | Série</th>
						<th>Nota</th>
						<th>Lançamento | Emissão</th>
						<th>Lançamento | Emissão</th>
						<th>Vencimento</th>
						<th>Fornecedor</th>
						<th>Histórico</th>
						<th>Valor Pagar</th>
					</tr>
				</thead>
				<tbody>

<?php
	$pagar_final = 0;
	$pagar_grupo = 0;
	$pagar_gos = 0;
	
	$cont_final = 0;
	$cont_grupo = 0;
	$cont_gos = 0;
	
	$sql_pagar = "Select cod_fornecedor, nome_fornecedor from pagar where 
		data_vencimento >= '$DataInicial' and data_vencimento <= '$DataFinal' 
		and cod_empresa != '90' and bloco_upload > '0' and (tipo_lancamento <= '560' or tipo_lancamento = '681')
	group by cod_fornecedor order by nome_fornecedor";
	$result_pagar = mysqli_query($con_softline, $sql_pagar);
	while($dados_pagar = mysqli_fetch_assoc($result_pagar)){
		$cod_fornecedor = $dados_pagar['cod_fornecedor'];
		$nome_fornecedor = $dados_pagar['nome_fornecedor'];
		$substr_empresa = substr( $nome_fornecedor, 0, 19);
		
?>
			<tr>
				<td><b><?php echo $cod_fornecedor; ?></b></td>
				<td colspan='6' style='text-align:left'><b><?php echo $nome_fornecedor; ?></b></td>
			</tr>
<?php
		$sub_pagar = 0;
		$sql_pagar_detalhado = "
			Select * from pagar 
			LEFT JOIN tipo_lancamento ON cod_lancamento = tipo_lancamento
			where 
			data_vencimento >= '$DataInicial' and data_vencimento <= '$DataFinal' 
			and ((data_pagamento >= '$DataInicial' and data_pagamento <= '$DataFinal')
			or (data_pagamento = '0000-00-00') or (data_pagamento is null))
			and cod_fornecedor = '$cod_fornecedor' 
			and (tipo_lancamento <= '560' or tipo_lancamento = '681')
			and bloco_upload > '0' and excluido = '0' and cod_empresa != '90'
			order by nome_fornecedor";
			
		$result_pagar_detalhado = mysqli_query($con_softline, $sql_pagar_detalhado);
		while($dados_pagar_detalhado = mysqli_fetch_assoc($result_pagar_detalhado)){
			$n_documento = $dados_pagar_detalhado['n_documento'];
			$serie_documento = $dados_pagar_detalhado['serie_documento'];
			$n_nota = $dados_pagar_detalhado['n_nota'];
?>
				<tr>
					<td><?php echo $n_documento; ?> | <?php echo $serie_documento; ?></td>
					<td><?php echo $n_nota; ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_lancamento']))); ?> | </td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_emissao']))); ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_vencimento']))); ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['nome_fornecedor']; ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['historico']; ?></td>
					<td class='dinheiro_novo'><?php echo  number_format( $dados_pagar_detalhado['valor_pagar'], 2, ',', '.'); ?></td>
				</tr>
<?php
			$sub_pagar += $dados_pagar_detalhado['valor_pagar'];
			
			$cont_final ++;
			$cont_grupo ++;
		}
?>
			<tr>
				<td class='dinheiro_novo'><b>Subtotal</b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style='text-align:center'><b><?php echo number_format( $sub_pagar, 2, ',', '.'); ?></b></td>
			</tr>
			<tr>
				<td>-</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>-</td>
			</tr>
			
	
<?php
		$pagar_final += $sub_pagar;
		$pagar_grupo += $sub_pagar;
	}
?>
			<tr>
				<td colspan='2' class=''><b>Total de Registros: <?php echo $cont_grupo; ?></b></td>
				<td colspan='4' class='dinheiro_novo'><b>Total Grupo</b></td>
				<td style='text-align:center'><b><?php echo number_format( $pagar_grupo, 2, ',', '.'); ?></b></td>
			</tr>
		
			<tr>
				<td colspan='7' class='dinheiro_novo'></td>
			</tr>

			<tr>
				<td colspan='7' class='' style='text-align:center'><b>Incorporadora</b></td>
			</tr>

<?php
	$sql_pagar = "Select cod_fornecedor, nome_fornecedor from pagar where 
		data_vencimento >= '$DataInicial' and data_vencimento <= '$DataFinal' 
		and cod_empresa ='90' and bloco_upload > '0' and tipo_lancamento <= '560'
	group by cod_fornecedor order by nome_fornecedor";
	$result_pagar = mysqli_query($con_softline, $sql_pagar);
	while($dados_pagar = mysqli_fetch_assoc($result_pagar)){
		$cod_fornecedor = $dados_pagar['cod_fornecedor'];
		$nome_fornecedor = $dados_pagar['nome_fornecedor'];
		$substr_empresa = substr( $nome_fornecedor, 0, 19);
		
?>
			<tr>
				<td><b><?php echo $cod_fornecedor; ?></b></td>
				<td colspan='6' style='text-align:left'><b><?php echo $nome_fornecedor; ?></b></td>
			</tr>
<?php
		
		$sub_pagar = 0;
		$sql_pagar_detalhado = "
			Select * from pagar 
			LEFT JOIN tipo_lancamento ON cod_lancamento = tipo_lancamento
			where 
			data_vencimento >= '$DataInicial' and data_vencimento <= '$DataFinal' 
			and ((data_pagamento >= '$DataInicial' and data_pagamento <= '$DataFinal')
			or (data_pagamento = '0000-00-00') or (data_pagamento is null))
			and cod_fornecedor = '$cod_fornecedor' 
			and ((fornecedores = '1')or(impostos = '1')or(parcelamento_faz_prev = '1')
				or(recursos_humanos = '1')or(emprestimos_vencimentos = '1')or(inss_fgts = '1')or(pagamento_antecipado = '1'))
			and bloco_upload > '0' and excluido = '0' and cod_empresa = '90'
			order by nome_fornecedor";
			
		$result_pagar_detalhado = mysqli_query($con_softline, $sql_pagar_detalhado);
		while($dados_pagar_detalhado = mysqli_fetch_assoc($result_pagar_detalhado)){
			$n_documento = $dados_pagar_detalhado['n_documento'];
			$serie_documento = $dados_pagar_detalhado['serie_documento'];
			$n_nota = $dados_pagar_detalhado['n_nota'];
?>
				<tr>
					<td><?php echo $n_documento; ?> | <?php echo $serie_documento; ?></td>
					<td><?php echo $n_nota; ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_lancamento']))); ?> | <?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_emissao']))); ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_vencimento']))); ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['nome_fornecedor']; ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['historico']; ?></td>
					<td class='dinheiro_novo'><?php echo  number_format( $dados_pagar_detalhado['valor_pagar'], 2, ',', '.'); ?></td>
				</tr>
<?php
			$sub_pagar += $dados_pagar_detalhado['valor_pagar'];
			$cont_final ++;
			$cont_gos ++;
		}
		
?>
			<tr>
				<td colspan='6' class='dinheiro_novo'><b>Subtotal</b></td>
				<td style='text-align:center'><b><?php echo number_format( $sub_pagar, 2, ',', '.'); ?></b></td>
			</tr>
			<tr>
				<td colspan='7' ></td>
			</tr>
			
<?php
		
		$pagar_gos += $sub_pagar;
		$pagar_final += $sub_pagar;
	}
?>
			<tr>
				<td colspan='2' class=''><b>Total de Registros: <?php echo $cont_gos ; ?></b></td>
				<td colspan='4' class='dinheiro_novo'><b>Total Incorporadora</b></td>
				<td style='text-align:center'><b><?php echo number_format( $pagar_gos, 2, ',', '.'); ?></b></td>
			</tr>
		
			<tr>
				<td colspan='7' class='dinheiro_novo'></td>
			</tr>
	
		
			<tr>
				<td colspan='2' class=''><b>Total Geral de Registros: <?php echo $cont_final; ?></b></td>
				<td colspan='4' class='dinheiro_novo'><b>Total Geral</b></td>
				<td style='text-align:center'><b><?php echo number_format( $pagar_final, 2, ',', '.'); ?></b></td>
			</tr>

				</tbody>
			<table>
		</div>

      </div>
	  <script  src="js/custom.js"></script>
  </body>
</html>




