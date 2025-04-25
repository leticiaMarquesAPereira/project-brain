<!DOCTYPE html>
<html lang="PT-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style.css">
  <title>Dashboard principal</title>
</head>

<body>
  <div class="espaco"></div>
  <header class="container">
    <div class="criando">
      <h1>SOBRE MIM</h1>
    </div>
    <? include __DIR__ . "/navegacao_dash.html"; ?>
  </header>
  <section class="sectionform">
    <div class="fundocinza">
      <div class="formprincipaldash">
        <div class="formum">
          <p>Desenvolvido em PHP e inicialmente implementado com banco de dados Oracle, esse sisteminha inclui a CRIACAO,
            EXCLUSAO e ATUALIZACAO do seu perfil. A criacao do perfil e feita usando um nome, CPF, sexo, recado e anexo.
            Fique a vontade para testar!</p>
          <img src="/servicos/teste/img/seta-curva.png" alt="seta apontando pro lado" class="imagem-seta">  
        </div>
        <div class="formdois">
          <ul>
            <li><a href="#"><b>CRIAR</b> conta</a></li>
            <li><a href="#"><b>EXCLUIR</b> conta</a></li>
            <li><a href="#"><b>ALTERAR</b> conta</a></li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section class="centraltexto">
    <div class="textomargindash">
      <h2>POR QUE CRIEI ESSE SISTEMA?</h2>
      <div class="texto-cerebro">
        <div class="texto">
          <p>Apesar de estar na faculdade de ADS ha quase 3 anos, nao houve pratica o suficiente durante o curso na
            minha
            percepcao. Agora que consegui meu primeiro estagio (viva!) estou me sentindo motivada a praticar e aprender
            o
            maximo que eu puder!</p>
        </div>
        <div class="div-cerebro">
          <img src="/servicos/teste/img/cerebro.png" alt="cerebro verde" class="cerebro">
        </div>
      </div>
    </div>
  </section>
  <?php
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