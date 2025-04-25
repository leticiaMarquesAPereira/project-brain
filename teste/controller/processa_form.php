<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$erro = false;

if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
  // Define os tipos de arquivo permitidos
  $allowed_extensions = ['png', 'jpg', 'jpeg', 'pdf'];
  $allowed_mime_types = ['image/png', 'image/jpeg', 'application/pdf'];

  // Obtém informações do arquivo
  $file_name = $_FILES['anexo']['name'];
  $file_tmp = $_FILES['anexo']['tmp_name'];
  $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
  $file_mime_type = mime_content_type($file_tmp);
  
  // Verifica se o arquivo temporário ainda existe
  if (!file_exists($file_tmp)) {
    echo "Erro: O arquivo temporario nao existe mais.";
    $_POST['acao'] = null;
    $erro = true;
  }

  // Verifica a extensão e o MIME type do arquivo
  if (!in_array($file_extension, $allowed_extensions) || !in_array($file_mime_type, $allowed_mime_types)) {
    echo "Tipo de arquivo nao permitido. Apenas PNG, JPG e PDF sao aceitos.";
    $_POST['acao'] = null;
    $erro = true;
  }
}

// Inclui os arquivos necessários após a validação do anexo
require_once __DIR__ . '/../model/Pessoa.php';
require_once __DIR__ . '/../model/PessoaDAO.php';
require_once __DIR__ . '/../model/dbconex.php';

if (!$dbConn) {
  die("Erro ao conectar ao banco de dados: " . oci_error());
}

// Inicia a transação
oci_execute($dbConn, OCI_NO_AUTO_COMMIT);

$anexo = '';
$caminhoAnexo = null;

if (isset($_POST['acao'])) {
  $acao = $_POST['acao'];
  switch ($acao) {
    case 'criar':

      $nome = $_POST['nome'];
      $cpf = $_POST['cpf'];
      $sexo = $_POST['sexo'];
      $msg = $_POST['msg'];
      $senha = $_POST['senha'];

      $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

      if ($senhaHash === false) {
        throw new Exception("Falha ao criptografar senha");
      }

      $pessoa = new Pessoa($nome, $cpf, $anexo, $sexo, $msg, $data_cad, $tipo_usuario ,$senhaHash);
      $pessoaDAO = new PessoaDAO($dbConn);

      // var_dump($pessoa);

      if (preg_match('/\d/', $nome) || preg_match('/[^\p{L}\s]/u', $nome)) {
        echo "Nome invalido. O nome nao pode conter numeros ou caracteres especiais.";
        $erro = true;
        break;
      }

      if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
        echo "CPF invalido. O CPF deve conter exatamente 11 digitos numericos.";
        $erro = true;
        break;
      }

      if ($pessoa->cpfExiste($cpf, $dbConn)) {
        echo "Nao foi possivel criar sua conta. Esse CPF ja foi cadastrado.";
        $erro = true;
        break;
      }
      // Agora, envia o anexo (se houver)
      if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
        // Caminho da pasta onde os anexos serão salvos
        $pastaAnexos = "../anexos/";

        // Verifica se a pasta existe, se não, cria a pasta
        if (!file_exists($pastaAnexos)) {
          mkdir($pastaAnexos, 0777, true);
        }

        // Gera um nome único para o arquivo
        $nomeArquivo = uniqid() . '_' . basename($file_name);
        $caminhoCompleto = $pastaAnexos . $nomeArquivo;
        $anexo = $nomeArquivo;

        $pessoa->setAnexo($anexo);

        // Move o arquivo para a pasta de anexos
        if (!move_uploaded_file($file_tmp, $caminhoCompleto)) {
          echo "Erro ao salvar o anexo.";
          $erro = true;
          break;
        } else {
          $caminhoAnexo = $caminhoCompleto; // Salva o caminho do arquivo
        }
      }
      if (!$pessoaDAO->inserir($pessoa)) {
        echo "Erro ao inserir dados.";
        $erro = true;
        break;
      }
      // Redireciona o usuário para evitar reenvio do formulário
      if (!$erro) {
        header("Location: " . $_SERVER['PHP_SELF']);
        oci_commit($dbConn); // Confirma a transação se tudo estiver correto
        require_once "../view/form_cadastro.php";
        exit();
      } else {
          oci_rollback($dbConn); // Reverte a transação em caso de erro
          // Remove o arquivo enviado em caso de erro
          if ($caminhoAnexo && file_exists($caminhoAnexo)) {
            unlink($caminhoAnexo);
          }
        }
      break;

    case 'excluir':
      $cpf = $_POST['cpf'];

      $pessoa = new Pessoa($nome, $cpf, $anexo, $sexo, $msg, $data, $tipo_usuario, $senha_hash);
      $pessoaDAO = new PessoaDAO($dbConn);

      if (!($pessoa->cpfExiste($cpf, $dbConn))) {
        echo "Nao foi possivel acessar sua conta. Esse CPF nao existe.";
        $erro = true;
        break;
      }

      // Excluir a pessoa do banco de dados
      if (!$pessoaDAO->excluir($cpf)) {
        echo "Erro ao excluir pessoa.";
        $erro = true;
        break;
      }

      // Redireciona o usuário para evitar reenvio do formulário
      if (!$erro) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
      }
      break;

    case 'alterar':
      $nome = $_POST['nome'];
      $cpf = $_POST['cpf'];
      $msg = $_POST['msg'];

      $pessoa = new Pessoa($nome, $cpf, null, null, $msg, null, null, null);
      $pessoaDAO = new PessoaDAO($dbConn);

      if (!($pessoa->cpfExiste($cpf, $dbConn))) {
        echo "Nao foi possivel acessar sua conta. Esse CPF nao existe.";
        $erro = true;
        break;
      }

      if (!$pessoaDAO->atualizar($pessoa)) {
        echo "Erro ao atualizar dados.";
        $erro = true;
        break;
      }

      // Redireciona o usuário para evitar reenvio do formulário
      if (!$erro) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
      }
      break;
  }
}
// function validarSenha($senha) {
//   $erros = [];
  
//   // Verifica comprimento mínimo
//   if (strlen($senha) < 8) {
//       $erros[] = "A senha deve ter pelo menos 8 caracteres";
//   }
  
//   // Verifica letra minúscula
//   if (!preg_match('/[a-z]/', $senha)) {
//       $erros[] = "A senha deve ter pelo menos 1 letra minúscula";
//   }
  
//   // Verifica letra maiúscula
//   if (!preg_match('/[A-Z]/', $senha)) {
//       $erros[] = "A senha deve ter pelo menos 1 letra maiúscula";
//   }
  
//   // Verifica número
//   if (!preg_match('/\d/', $senha)) {
//       $erros[] = "A senha deve ter pelo menos 1 número";
//   }
  
//   // Verifica símbolo
//   if (!preg_match('/[^\w\d\s]/', $senha)) {
//       $erros[] = "A senha deve ter pelo menos 1 símbolo especial";
//   }
  
//   return $erros;
// }

require_once "../view/form_cadastro.php";