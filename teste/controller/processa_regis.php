<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . '/../model/Pessoa.php';
require_once __DIR__ . '/../model/PessoaDAO.php';
require_once __DIR__ . '/../model/dbconex.php';

if (!$dbConn) {
  die("Erro ao conectar ao banco de dados");
}

$resultados = [];
$totalRegistros = 0;

if (isset($_POST['acao'])) {
  $acao = $_POST['acao'];

  switch ($acao) {
    case 'exibir':
      $nome = $_POST['nome'];
      $sexo = $_POST['sexo'];
      $msg = $_POST['recado'];
      $data = $_POST['data'];

      $pessoa = new Pessoa($nome, null, null, $sexo, $msg, $data);
      $pessoaDAO = new PessoaDAO($dbConn);
      
      $resultados = $pessoaDAO->exibir($pessoa);    
      $totalRegistros = is_array($resultados) ? count($resultados) : 0;
      
      break;
  }
}

require_once __DIR__ . '/../view/registros.php';