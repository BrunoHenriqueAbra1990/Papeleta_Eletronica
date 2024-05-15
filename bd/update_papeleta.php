<?php
	include "../../../conectar.php";
	include_once "../links_includes/gravar_log.php";
	header('Content-Type: text/html; charset=utf-8');
	
	date_default_timezone_set('America/Sao_Paulo');
	$data_time	= date("d/m/Y H:i");
	$data_local		= date('Y-m-d H:i',time());
	$date_time 		= date("Y-m-d H:i:s");
	$date	 		= date("Y-m-d");
	
	$dados 			= filter_input_array(INPUT_POST, FILTER_DEFAULT);

	if($dados['parametro'] == 'VALIDAR'){
		$IdPagar = $dados['IdPagar'];
		$IdValidador = $dados['IdValidador'];
		
		$sql = "UPDATE pagar SET 
				validacao_papeleta = '1', 
				usuario_validacao_papeleta = '$IdValidador',
				data_validacao_papeleta = '$date'
			WHERE id_pagar = $IdPagar ";  
		$query = $con_softline->query($sql);

		$registro =  mysqli_error($con_softline);
		if(!empty($registro)){
			$retorna = "Erro: Registro não cadastrado com sucesso!";
		}
		else{
			$retorna = "Registro Válidado com Sucesso.!!!";
		}
		$mensagem =  "[$IdPagar]; [VALIDAR]; [UPDATE_PAPELETA];";
	}
	
	if($dados['parametro'] == 'RECUSAR'){
		$IdPagar = $dados['IdPagar'];
		$ObsPapeleta = $dados['ObsPapeleta'];
		$IdValidador = $dados['IdValidador'];
		
		$sql = "UPDATE pagar SET 
				validacao_papeleta = '2', 
				observacao_papeleta = '$ObsPapeleta', 
				usuario_validacao_papeleta = '$IdValidador',
				data_validacao_papeleta = '$date'
			WHERE id_pagar = $IdPagar ";  
		$query = $con_softline->query($sql);

		$registro =  mysqli_error($con_softline);
		if(!empty($registro)){
			$retorna = "Erro: Registro não cadastrado com sucesso!";
		}
		else{
			$retorna = "Registro Recusado com Sucesso.!!!";
		}
		$mensagem =  "[$IdPagar]; [RECUSAR-$ObsPapeleta]; [UPDATE_PAPELETA];";
	}
	
	if($dados['parametro'] == 'UPDATE'){
		$IdPagar = $dados['IdPagar'];
		$ObsPapeleta = $dados['ObsPapeleta'];
		$IdValidador = $dados['IdValidador'];
		
		$sql = "UPDATE pagar SET  
				observacao_papeleta = '$ObsPapeleta', 
				usuario_validacao_papeleta = '$IdValidador',
				data_validacao_papeleta = '$date'
			WHERE id_pagar = $IdPagar ";  
		$query = $con_softline->query($sql);

		$registro =  mysqli_error($con_softline);
		if(!empty($registro)){
			$retorna = "Erro: Registro não cadastrado com sucesso!";
		}
		else{
			$retorna = "Registro Recusado com Sucesso.!!!";
		}
		$mensagem =  "[$IdPagar]; [UPDATE-$ObsPapeleta]; [UPDATE_PAPELETA];";
	}
	
	if($dados['parametro'] == 'VALIDAR_TODOS'){
		$DataInicial 	= $dados['DataInicial'];
		$DataFinal 		= $dados['DataFinal'];
		$IdValidador 	= $dados['IdValidador'];
		
		$sql = "UPDATE pagar SET 
				validacao_papeleta = '1', 
				usuario_validacao_papeleta = '$IdValidador',
				data_validacao_papeleta = '$date'
			WHERE data_vencimento >= '$DataInicial' and data_vencimento <= '$DataFinal' 
			and validacao_papeleta = '0' and bloco_upload > '0' and (tipo_lancamento <= '560' or tipo_lancamento = '681') ";  
		$query = $con_softline->query($sql);

		$registro =  mysqli_error($con_softline);
		if(!empty($registro)){
			$retorna = "Erro: Registro não cadastrado com sucesso!";
		}
		else{
			$retorna = "Registro Válidado com Sucesso.!!!";
		}
		$mensagem =  "[$DataInicial $DataFinal]; [VALIDAR_TODOS]; [UPDATE_PAPELETA];";
	}
	
	
	logMsg( "$mensagem", 'info' );
echo $retorna;