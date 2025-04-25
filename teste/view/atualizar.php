<!DOCTYPE html>
<html lang="PT-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style.css">
  <title>Atualizar Cadastro</title>
</head>

<?
require_once '../controller/processa_acesso.php';
verificarAcesso(['a']);
?>

<body>
  <div class="espaco"></div>
  <header class="container">
    <div class="atualizando">
      <h1>ATUALIZAR CADASTRO</h1>
    </div>
    <?php
    include "../view/navegacao.html";
    ?>
  </header>
  <section class="sectionform">
    <div class="formprincipal">
      <form action="../controller/processa_form.php" method="POST">
        <input type="hidden" name="acao" value="alterar">
        <p>Nome</p>
        <input type="text" name="nome" class="barra" placeholder="Digite o novo nome">
        <div class="textebuttom">
          <div class="areatexto">
            <p>Mensagem</p>
            <textarea name="msg" class="msg" placeholder="Digite a nova mensagem"></textarea>
          </div>
          <div class="cpfatualizar">
            <p>CPF</p>
            <input type="text" name="cpf" class="barra2atualizar" maxlength="11" minlength="11" pattern="\d{11}"
              placeholder="Digite seu CPF">
            <div class="botoesformatualizar">
              <input type="reset" class="redefiniratualizar" name="resetarform">
              <input type="submit" class="enviaratualizar" name="enviarform" value="Enviar">
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>

  <section>
    <div class="textomargin">
      <h2>NOSSOS PRINCIPIOS</h2>
      <div class="texto">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam, distinctio nihil aut earum quam illum
          harum velit eum cum aliquam tenetur unde, a tempora! Quidem inventore facilis maiores sint iusto ut
          consectetur
          odio tempora vel debitis maxime laudantium voluptas alias, voluptates quisquam exercitationem laborum dolores
          quo. At vero cum quam?<br /> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit non iure et
          corrupti
          nulla hic nesciunt dignissimos. Enim delectus distinctio dolorum corporis ut ad! Illo quam similique rerum
          explicabo, fugit voluptatibus iure quisquam repellat deleniti accusamus fuga recusandae et non voluptas quo
          ab. Aut, ipsum repellat facilis placeat qui nostrum a laboriosam similique laudantium dolorum illum ipsa
          debitis eveniet libero.</p>
      </div>
      <div class="texto2">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam, distinctio nihil aut earum quam illum
          harum velit eum cum aliquam tenetur unde, a tempora! Quidem inventore facilis maiores sint iusto ut
          consectetur
          odio tempora vel debitis maxime laudantium voluptas alias, voluptates quisquam exercitationem laborum dolores
          quo. At vero cum quam?<br /> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit non iure et
          corrupti
          nulla hic nesciunt dignissimos. Enim delectus distinctio dolorum corporis ut ad! Illo quam similique rerum
          explicabo, fugit voluptatibus iure quisquam repellat deleniti accusamus fuga recusandae et non voluptas quo
          ab. Aut, ipsum repellat facilis placeat qui nostrum a laboriosam similique laudantium dolorum illum ipsa
          debitis eveniet libero.</p>
      </div>
    </div>
  </section>

  <?php
  include "../view/rodape.php";
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