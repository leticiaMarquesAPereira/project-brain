<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../model/Pessoa.php';
require_once __DIR__ . '/../model/dbconex.php';
require_once __DIR__ . '/../model/LoginDAO.php';

if (!$dbConn) {
  die("Erro ao conectar ao banco de dados");
}

if (isset($_POST['acao'])) {
  $acao = $_POST['acao'];

  switch ($acao) {
    case 'logar':
      $cpf = $_POST['cpf'];
      $senhaDigitada = $_POST['senha'];

      if (empty($cpf) || empty($senhaDigitada)) {
        $_SESSION['erro_login'] = "CPF e senha são obrigatórios!";
        header("Location: ../view/login.php");
        exit();
      }

      $cpf = preg_replace('/[^0-9]/', '', $cpf);

      if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
        $_SESSION['erro_login'] = "CPF inválido. Deve conter 11 dígitos numéricos.";
        header("Location: ../view/login.php");
        exit();
      }

      $pessoa = new Pessoa(null, $cpf, null, null, null, null, null, $senhaDigitada);
      $loginDAO = new LoginDAO($dbConn);
      $usuario = $loginDAO->Logar($pessoa);

      if ($usuario) {
        $loginDAO->registrarAcesso($usuario['cpf']);

        // 🔽 PEGA OS DADOS COMPLETOS COM A FOTO
        $dadosCompletos = $loginDAO->buscarDadosUsuario($usuario['cpf']);
    
        // 🔁 Atualiza sessão com os dados completos (inclusive foto)
        print_r($dadosCompletos);
        $_SESSION['usuario'] = $usuario;
    
        header("Location: ../view/perfil.php");
        exit();
      } else {
        $_SESSION['erro_login'] = "CPF ou senha incorretos!";
        header("Location: ../view/login.php?erro=1");
        exit();
      }
      break;

    case 'atualizarperf':
      if (!isset($_SESSION['usuario'])) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não logado.']);
        exit();
      }

      $cpfUsuario = $_SESSION['usuario']['cpf'];
      $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
      $recado = isset($_POST['recado']) ? trim($_POST['recado']) : '';
      $foto = null;

      if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['png', 'jpg', 'jpeg'];
        $allowed_mime_types = ['image/png', 'image/jpeg'];

        $file_name = $_FILES['foto_perfil']['name'];
        $file_tmp = $_FILES['foto_perfil']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_mime_type = mime_content_type($file_tmp);

        // Verifica se o arquivo temporário ainda existe
        if (!file_exists($file_tmp)) {
          echo json_encode(['status' => 'erro', 'mensagem' => 'O arquivo temporário não existe mais.']);
          exit();
        }

        // Verifica tipo e extensão
        if (!in_array($file_extension, $allowed_extensions) || !in_array($file_mime_type, $allowed_mime_types)) {
          echo json_encode(['status' => 'erro', 'mensagem' => 'Tipo de imagem inválido. Apenas PNG e JPG são permitidos.']);
          exit();
        }

        $uploadDir = '../fotos_perfil/';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }

        $nomeArquivo = 'perfil_' . $cpfUsuario . '.' . $file_extension;
        $caminhoCompleto = $uploadDir . $nomeArquivo;

        if (!move_uploaded_file($file_tmp, $caminhoCompleto)) {
          echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar a imagem de perfil.']);
          exit();
        }

        $foto = $nomeArquivo;
      }

      $loginDAO = new LoginDAO($dbConn);
      $resultado = $loginDAO->atualizarPerfilUsuario($cpfUsuario, $nome, $recado, $foto);

      if ($resultado) {
        // Atualiza sessão com novos dados
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['recado'] = $recado;
        if ($foto !== null) {
          $_SESSION['usuario']['foto_perfil'] = $foto;
        }

        header("Location: ../view/perfil.php");
      } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao atualizar perfil.']);
      }

      exit();

  }
}
