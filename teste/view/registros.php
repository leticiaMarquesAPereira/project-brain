<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style.css">
  <title>Pessoas registradas</title>
</head>

<?
require_once '../controller/processa_acesso.php';
verificarAcesso(['a']);
?>

<body>
  <div class="espaco"></div>
  <header class="container">
    <div class="criando">
      <h1>PESSOAS REGISTRADAS</h1>
    </div>
    <?php
    include __DIR__ . "/navegacao.html";
    ?>
  </header>
  <div class="formprincipal">
    <form action="registros.php" method="POST">
      <input type="hidden" name="acao" value="exibir">
      <div>
        <div class="nomeRegistro">
          <div class="dropdown">
            <p>Nome</p>
            <input type="text" placeholder="Pesquise pelo nome" name="nome" class="barraRegistro"
              value="<?= htmlspecialchars($nome_busca) ?>">
          </div>
        </div>
        <div class="sexo">
          <div class="dropdown">
            <p>Sexo</p>
          </div>
          <select name="sexo" class="dropdown-conteudo">
            <?php
            require_once __DIR__ . '/../model/Pessoa.php';
            require_once __DIR__ . '/../model/dbconex.php';
            require_once __DIR__ . '/../controller/processa_regis.php';

            $termo_busca = $_POST['nome'];
            $sexo_busca = $_POST['sexo'];
            $recado_busca = $_POST['recado'];
            $data_busca = $_POST['data'];

            $pessoa = new Pessoa(null, null, null, null, null, null);
            $arrSex = $pessoa->arrSex;
            ?>
            <option value="">Todos</option>
            <?
            foreach ($pessoa->arrSex as $sex => $sex_name) {
              echo "<option value='$sex'>$sex_name</option>";
            }
            ?>
          </select>
        </div>
      </div>
      <div style="margin-top: 8px;">
        <div class="recadoRegistro">
          <div class="dropdown">
            <p>Recado</p>
            <input type="text" placeholder="Pesquise pelo recado" name="recado" class="msg"
              value="<?= htmlspecialchars($recado_busca) ?>">
          </div>
        </div>
        <div class="dataRegistro">
          <div class="dropdown">
            <p>Data</p>
            <input type="date" placeholder="Pesquise pelo registro" name="data" class="barraData"
              value="<?= htmlspecialchars($data_busca) ?>">
          </div>
          <div class="botoesRegistro">
            <input type="reset" class="redefinirRegistro" name="resetarform" value="Redefinir">
            <input type="submit" class="enviarRegistro" name="enviarform" value="Pesquisar">
          </div>
        </div>
      </div>

    </form>
  </div>

  <?php

  if (!empty($resultados)): ?>
    <div class="resultado-contagem">
      <?php if ($totalRegistros == 1): ?>
        <p>Foi encontrado <strong><?= $totalRegistros ?></strong> registro</p>
      <?php elseif ($totalRegistros > 1): ?>
        <p>Foram encontrados <strong><?= $totalRegistros ?></strong> registros</p>
      <?php endif; ?>
    </div>
    <div class="registros-container">
      <?php foreach ($resultados as $registro): ?>
        <div class="registro-card">
          <div class="campo-nome">
            <p>NOME:</p>
            <p><?= htmlspecialchars($registro->nome) ?></p>
          </div>
          <div class="campo-sexo">
            <p>SEXO:</p>
            <p><?= ($registro->sexo == 'm' ? 'Masculino' : ($registro->sexo == 'f' ? 'Feminino' : 'Nao especificado')) ?>
            </p>
          </div>
          <div class="campo-recado">
            <p>RECADO:</p>
            <p><?= htmlspecialchars($registro->recado) ?></p>
          </div>
          <div class="campo-data">
            <p>DATA:</p>
            <p><?= $registro->data_cad ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php elseif (isset($resultados) && empty($resultados)): ?>
    <div class="nenhum"><p>Nenhum registro encontrado</p></div>
  <?php endif; ?>
  <?
  include __DIR__ . "/rodape.php";
  ?>
  <script>
    // Sistema completo de gerenciamento de tema
    document.addEventListener('DOMContentLoaded', function () {
      console.log('Sistema de tema inicializado');

      // Elementos DOM
      const themeToggle = document.getElementById('theme-toggle');
      const html = document.documentElement;

      // Aplica o tema inicial
      function applyTheme() {
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light') || 'dark';

        html.setAttribute('data-theme', theme);
        updateButtonIcon(theme);
        console.log('Tema aplicado:', theme);
      }

      // Alterna entre temas
      function toggleTheme() {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateButtonIcon(newTheme);
        console.log('Tema alterado para:', newTheme);
      }

      // Atualiza o ícone do botão conforme o tema
      function updateButtonIcon(theme) {
        const sunIcon = document.querySelector('.sun-icon');
        const moonIcon = document.querySelector('.moon-icon');

        if (sunIcon && moonIcon) {
          sunIcon.style.display = theme === 'light' ? 'none' : 'block';
          moonIcon.style.display = theme === 'dark' ? 'none' : 'block';
        }
      }

      // Configuração inicial
      applyTheme();

      // Adiciona evento ao botão
      if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
      } else {
        console.error('Botão de alternância de tema não encontrado!');
      }
    });
  </script>
</body>

</html>