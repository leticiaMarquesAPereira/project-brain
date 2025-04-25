<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$modoEdicao = isset($_GET['editar']) && $_GET['editar'] == '1';

require_once __DIR__ . '/../model/Login.php';
require_once __DIR__ . '/../model/Pessoa.php';

$usuario = $_SESSION['usuario'];

Login::naoLogado();

$pessoa = new Pessoa(null, null, null, null, null, null, null, null);
$arrSex = $pessoa->arrSex;
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style.css">
  <title>Perfil</title>
</head>

<body>
  <header class="navegacaoperfil">
    <h1>PERFIL DE USUARIO</h1>
    <div class="botaoperfil">
      <a href="/servicos/teste/controller/processa_logout.php">LOG OUT</a>
    </div>
  </header>

  <main>
    <?php if ($modoEdicao): ?>
      <form method="post" action="../controller/processa_login.php" enctype="multipart/form-data">
        <div class="informacoes modo-edicao">
          <div class="nomecpfimg">
            <div class="nomecpf">
              <div>
                <p class="pnome">Nome</p>
                <input type="text" name="nome" class="barranomeperfil" value="<?= htmlspecialchars($usuario['nome']) ?>">
              </div>
              <div>
                <p class="pcpf">CPF</p>
                <p class="barracpfperfil"><span id="cpf-mascarado"></span></p>
                <input type="hidden" name="cpf" value="<?= htmlspecialchars($usuario['cpf']) ?>">
              </div>
            </div>
            <div class="imagemperfil">
              <?php
              $fotoPerfil = $usuario['foto_perfil'];
              $caminhoFoto = (!empty($fotoPerfil)) ? "/servicos/teste/fotos_perfil/{$fotoPerfil}" : "/servicos/teste/fotos_perfil/perfil-padrao.png";
              ?>
              <img src="<?= $caminhoFoto ?>" alt="Foto de perfil" width="90" height="90" style="border-radius: 50%;">
              <div style="margin-top: 10px;">
                <input type="file" name="foto_perfil">
              </div>
            </div>
          </div>

          <div class="msgsexo">
            <div class="sexoanexosenha">
              <div>
                <p class="psexo">Sexo</p>
                <p class="barrasexoperfil">
                  <?= isset($arrSex[$usuario['sexo']]) ? $arrSex[$usuario['sexo']] : 'Não informado' ?>
                </p>
              </div>
              <div>
                <p class="panexo">Anexo</p>
                <a href="../anexos/<?= htmlspecialchars($usuario['anexo']) ?>" class="barraanexoperfil" target="_blank">Visualizar anexo</a>
              </div>
              <div>
                <p class="psenha">Senha</p>
                <p class="barrasenhaperfil">********</p>
              </div>
            </div>
            <div>
              <p class="pmsg">Mensagem</p>
              <textarea name="recado" id="recado" rows="1" oninput="autoResize(this)"><?= htmlspecialchars($usuario['recado']) ?></textarea>
            </div>
          </div>
        </div>
        <div class="botoesperfil">
          <input type="hidden" name="acao" value="atualizarperf">
          <input type="submit" class="atualizarperfil" name="atualizaperfil" value="Salvar">
        </div>
      </form>

    <?php else: ?>
      <div class="informacoes">
        <div class="nomecpfimg">
          <div class="nomecpf">
            <div>
              <p class="pnome">Nome</p>
              <input type="text" class="barranomeperfil" value="<?= htmlspecialchars($usuario['nome']) ?>" readonly>
            </div>
            <div>
              <p class="pcpf">CPF</p>
              <p class="barracpfperfil"><span id="cpf-mascarado"></span></p>
            </div>
          </div>
          <div class="imagemperfil">
            <?php
            $fotoPerfil = $usuario['foto_perfil'];
            $caminhoFoto = (!empty($fotoPerfil)) ? "/servicos/teste/fotos_perfil/{$fotoPerfil}" : "/servicos/teste/fotos_perfil/perfil-padrao.png";
            ?>
            <img src="<?= $caminhoFoto ?>" alt="Foto de perfil" width="90" height="90" style="border-radius: 50%;" class="foto-perfil">
          </div>
        </div>

        <div class="msgsexo">
          <div class="sexoanexosenha">
            <div>
              <p class="psexo">Sexo</p>
              <p class="barrasexoperfil">
                <?= isset($arrSex[$usuario['sexo']]) ? $arrSex[$usuario['sexo']] : 'Não informado' ?>
              </p>
            </div>
            <div>
              <p class="panexo">Anexo</p>
              <a href="../anexos/<?= htmlspecialchars($usuario['anexo']) ?>" class="barraanexoperfil" target="_blank">Visualizar anexo</a>
            </div>
            <div>
              <p class="psenha">Senha</p>
              <p class="barrasenhaperfil">********</p>
            </div>
          </div>
          <div>
            <p class="pmsg">Mensagem</p>
            <textarea name="recado" id="recado" rows="1" readonly oninput="autoResize(this)"><?= htmlspecialchars($usuario['recado']) ?></textarea>
          </div>
        </div>
      </div>
      <div class="botoesperfil">
        <form method="get">
          <input type="submit" class="atualizarperfil" value="Atualizar">
          <input type="hidden" name="editar" value="1">
        </form>
      </div>
    <?php endif; ?>

    <form method="post" action="excluir.php">
      <input type="hidden" name="acao" value="excluirperf">
      <input type="submit" class="excluirperfil" name="excluiperfil" value="Excluir">
    </form>
  </main>

  <script>
    const cpfOriginal = "<?= $usuario['cpf'] ?>";

    function mascararCPF(cpf) {
      if (cpf.length <= 3) return cpf;
      const visivel = cpf.slice(0, 3);
      const oculto = '*'.repeat(cpf.length - 3);
      return visivel + oculto;
    }

    function autoResize(textarea) {
      textarea.style.height = "auto";
      const newHeight = Math.min(textarea.scrollHeight, 250);
      textarea.style.height = newHeight + "px";
    }

    window.addEventListener('DOMContentLoaded', () => {
      const textarea = document.getElementById('recado');
      if (textarea) autoResize(textarea);
    });

    document.getElementById('cpf-mascarado').textContent = mascararCPF(cpfOriginal);
  </script>
  <?
  echo "<pre>";
print_r($_SESSION['usuario']);
echo "</pre>";
?>
</body>
</html>
