<?php

// Incluir a conexao com o banco de dados
include_once "../../../conectar.php";

// Receber o id
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


// Acessa o IF quando a variavel ID possui valor
if (!empty($id)) {
      $sql_entrada = $con_softline->query("SELECT * FROM pagar WHERE id_pagar = $id ");
      $row_entrada = mysqli_fetch_array($sql_entrada);

      if($row_entrada > 0){
        //$row_usuario = $row_entrada->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_entrada];
      } else {
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum registro encontrado!</div>"];
    }
    
} else {
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum registro encontrado!</div>"];
}

echo json_encode($retorna);
