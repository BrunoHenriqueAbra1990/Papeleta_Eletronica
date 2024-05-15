<?php
	include "../../../conectar.php";
	include_once "../links_includes/gravar_log.php";
	header('Content-Type: text/html; charset=utf-8');
	
	date_default_timezone_set('America/Sao_Paulo');
	$data_time	= date("d/m/Y H:i");
	$data_local		= date('Y-m-d H:i',time());
	$date_time 		= date("Y-m-d H:i:s");
	
	$dados 			= filter_input_array(INPUT_POST, FILTER_DEFAULT);

$retorna = "0";

	if($dados['parametro'] == 'PROCURA_USUARIO'){
		$valorVerificar = $dados['valorVerificar'];
		$string = md5("$valorVerificar");
		
		$verifica = "SELECT id_usuario, gerencia_papeleta FROM usuarios WHERE senha_papeleta = '$string' LIMIT 1";
		$resultado = mysqli_query($con, $verifica);
		$row = mysqli_fetch_assoc($resultado);
		if ($row > 0){
			if($row['gerencia_papeleta'] == '1'){
				$retorna = $row['id_usuario'];
			}
			else{
				$retorna = "0";
			}
		}
		else{
			$retorna = "0";
		}
	}
echo $retorna;