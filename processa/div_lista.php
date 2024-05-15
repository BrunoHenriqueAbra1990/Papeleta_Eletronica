<?php 
	$diretorio = "../../../"; 
	include_once "../../../conectar.php"; 
	$DataInicial = $_GET['DataInicial'];
	$DataFinal = $_GET['DataFinal'];
	
	$string = md5("1234");
	//echo "$string";
?>



			<table style="width: 100%;">
				<thead>
					<tr>
						<th>Nº Doc.</th>
						<th>Série</th>
						<th>Nota</th>
						<th>Lançamento</th>
						<th>Emissão</th>
						<th>Vencimento</th>
						<th>Fornecedor</th>
						<th>Histórico</th>
						<th>Valor</th>
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
				<td colspan='9' style='text-align:left'><b><?php echo $nome_fornecedor; ?></b></td>
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
			$id_pagar = $dados_pagar_detalhado['id_pagar'];
			$n_documento = $dados_pagar_detalhado['n_documento'];
			$serie_documento = $dados_pagar_detalhado['serie_documento'];
			$n_nota = $dados_pagar_detalhado['n_nota'];
			
			$validacao_papeleta = $dados_pagar_detalhado['validacao_papeleta'];
			if($validacao_papeleta == '1'){
				$bgcolor = "style='background-color:#37bf2a'";
			}
			elseif($validacao_papeleta == '2'){
				$bgcolor = "style='background-color:#eb4034'";
			}
			else{
				$bgcolor = "style='background-color:#d9dde1'";
			}
?>
				<tr>
					<td><?php echo $n_documento; ?></td>
					<td><?php echo $serie_documento; ?></td>
					<td><?php echo $n_nota; ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_lancamento']))); ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_emissao']))); ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_vencimento']))); ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['nome_fornecedor']; ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['historico']; ?></td>
					<td class='dinheiro_novo'><?php echo  number_format( $dados_pagar_detalhado['valor_pagar'], 2, ',', '.'); ?></td>
					<td <?php echo $bgcolor; ?> class="td_botoes" id="td_opcoes_<?php echo $id_pagar;?>">
						<!--<input type='checkbox' class='dias' name='id_pagar[<?php echo $id_pagar;?>]' value='<?php echo $id_pagar; ?>'>&nbsp;-->
						<a onclick='validaRegistro(<?php echo $id_pagar;?>)' id="valida_<?php echo $id_pagar;?>"	class='fa-regular fa-circle-check fa-2x' style='color:#37bf2a; background-color:transparent' title='Validar pelo Responsável' ></a>&nbsp;
						<a onclick='visualizaRegistro(<?php echo $id_pagar;?>)' id="visualiza_<?php echo $id_pagar;?>" class='far fa-eye fa-2x' style='color:blue; background-color:transparent' title='Visualização' ></a>
						<a onclick='recusaRegistro(<?php echo $id_pagar;?>)' id="recusa_<?php echo $id_pagar;?>" class='fa-regular fa-circle-xmark fa-2x' style='color:#eb4034; background-color:transparent' title='Validar pelo Responsável' ></a>
					</td>
				</tr>
<?php
			$sub_pagar += $dados_pagar_detalhado['valor_pagar'];
			
			$cont_final ++;
			$cont_grupo ++;
		}
?>
			<tr>
				<td class='dinheiro_novo'><b>Subtotal</b></td>
				<td colspan='7'></td>
				<td style='text-align:center'><b><?php echo number_format( $sub_pagar, 2, ',', '.'); ?></b></td>
			</tr>
			<tr>
				<td colspan='9'>-</td>
			</tr>
			
	
<?php
		$pagar_final += $sub_pagar;
		$pagar_grupo += $sub_pagar;
	}
?>
			<tr>
				<td colspan='3' class=''><b>Total de Registros: <?php echo $cont_grupo; ?></b></td>
				<td colspan='5' class='dinheiro_novo'><b>Total Grupo</b></td>
				<td style='text-align:center'><b><?php echo number_format( $pagar_grupo, 2, ',', '.'); ?></b></td>
			</tr>
		
			<tr>
				<td colspan='9' class='dinheiro_novo'></td>
			</tr>

			<tr>
				<td colspan='9' class='' style='text-align:center'><b>Incorporadora</b></td>
			</tr>

<?php
	$sql_pagar = "Select cod_fornecedor, nome_fornecedor from pagar where 
		data_vencimento >= '$DataInicial' and data_vencimento <= '$DataFinal' 
		and cod_empresa = '90' and bloco_upload > '0' and tipo_lancamento <= '560'
	group by cod_fornecedor order by nome_fornecedor";
	$result_pagar = mysqli_query($con_softline, $sql_pagar);
	while($dados_pagar = mysqli_fetch_assoc($result_pagar)){
		$cod_fornecedor = $dados_pagar['cod_fornecedor'];
		$nome_fornecedor = $dados_pagar['nome_fornecedor'];
		$substr_empresa = substr( $nome_fornecedor, 0, 19);
		
?>
			<tr>
				<td><b><?php echo $cod_fornecedor; ?></b></td>
				<td colspan='9' style='text-align:left'><b><?php echo $nome_fornecedor; ?></b></td>
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
			
			$validacao_papeleta = $dados_pagar_detalhado['validacao_papeleta'];
			if($validacao_papeleta == '1'){
				$bgcolor = "style='background-color:#37bf2a'";
			}
			elseif($validacao_papeleta == '2'){
				$bgcolor = "style='background-color:#eb4034'";
			}
			else{
				$bgcolor = "style='background-color:#d9dde1'";
			}
			
?>
				<tr>
					<td><?php echo $n_documento; ?></td>
					<td><?php echo $serie_documento; ?></td>
					<td><?php echo $n_nota; ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_lancamento']))); ?></td>
					<td> <?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_emissao']))); ?></td>
					<td><?php echo implode('/',array_reverse(explode('-',$dados_pagar_detalhado['data_vencimento']))); ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['nome_fornecedor']; ?></td>
					<td style='text-align:left'><?php echo $dados_pagar_detalhado['historico']; ?></td>
					<td class='dinheiro_novo'><?php echo  number_format( $dados_pagar_detalhado['valor_pagar'], 2, ',', '.'); ?></td>
					<td <?php echo $bgcolor; ?> class="td_botoes" id="td_opcoes_<?php echo $id_pagar;?>">
						<!--<input type='checkbox' class='dias' name='id_pagar[<?php echo $id_pagar;?>]' value='<?php echo $id_pagar; ?>'>&nbsp;-->
						<a onclick='validaRegistro(<?php echo $id_pagar;?>)' id="valida_<?php echo $id_pagar;?>"	class='fa-regular fa-circle-check fa-2x' style='color:#37bf2a; background-color:transparent' title='Validar pelo Responsável' ></a>&nbsp;
						<a onclick='visualizaRegistro(<?php echo $id_pagar;?>)' id="visualiza_<?php echo $id_pagar;?>" class='far fa-eye fa-2x' style='color:blue; background-color:transparent' title='Visualização' ></a>
						<a onclick='recusaRegistro(<?php echo $id_pagar;?>)' id="recusa_<?php echo $id_pagar;?>" class='fa-regular fa-circle-xmark fa-2x' style='color:#eb4034; background-color:transparent' title='Validar pelo Responsável' ></a>
					</td>
				</tr>
<?php
			$sub_pagar += $dados_pagar_detalhado['valor_pagar'];
			$cont_final ++;
			$cont_gos ++;
		}
		
?>
			<tr>
				<td class='dinheiro_novo'><b>Subtotal</b></td>
				<td colspan='7' class=''></td>
				<td style='text-align:center'><b><?php echo number_format( $sub_pagar, 2, ',', '.'); ?></b></td>
			</tr>
			<tr>
				<td colspan='9' class=''></td>
			</tr>
			
<?php
		
		$pagar_gos += $sub_pagar;
		$pagar_final += $sub_pagar;
	}
?>
			<tr>
				<td colspan='3' class=''><b>Total de Registros: <?php echo $cont_gos ; ?></b></td>
				<td colspan='5' class='dinheiro_novo'><b>Total Incorporadora</b></td>
				<td style='text-align:center'><b><?php echo number_format( $pagar_gos, 2, ',', '.'); ?></b></td>
			</tr>
		
			<tr>
				<td colspan='9' class='dinheiro_novo'></td>
			</tr>
	
		
			<tr>
				<td colspan='4' class=''><b>Total Geral de Registros: <?php echo $cont_final; ?></b></td>
				<td colspan='4' class='dinheiro_novo'><b>Total Geral</b></td>
				<td style='text-align:center'><b><?php echo number_format( $pagar_final, 2, ',', '.'); ?></b></td>
			</tr>

				</tbody>
			<table>
			
	<button class="button button1" onclick='validarTodos()' > Validar Registros </button>