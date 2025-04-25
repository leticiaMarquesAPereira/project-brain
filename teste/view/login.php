<!DOCTYPE html>
<html lang="PT-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style.css">
  <title>Login</title>
</head>

<?php
session_start();
?>

<body>
  <header class="logebuttom">
    <p>LOG IN</p>
    <button id="theme-toggle-login" class="theme-switcher" aria-label="Alternar tema">
      <span class="theme-icon-container">
        <img src="../img/sol.png" alt="Modo claro" class="theme-icon sun-icon">
        <img src="../img/lua-cheia.png" alt="Modo escuro" class="theme-icon moon-icon">
      </span>
    </button>
  </header>
  <main>
    <div class="formulariocentralizar">
      <div class="formprincipallogin">
        <form action="../controller/processa_login.php" method="POST">
          <input type="hidden" name="acao" value="logar">
          <div class="cpfesenha">
            <div class="cpflogin">
              <p>CPF</p>
              <input type="text" name="cpf" class="barralogin" maxlength="11" minlength="11" pattern="\d{11}"
                placeholder="Digite seu CPF">
            </div>
            <div class="password-container">
              <p>Senha</p>
              <input type="password" id="senha" placeholder="Digite sua senha" name="senha" class="senhalog" />
              <button type="button" id="toggle-senha" class="senha-toggle">
                <img src="/servicos/teste/img/olho-fechado.png" id="icone-senha" alt="Mostrar senha" class="olho" />
              </button>
            </div>
          </div>
          <div class="botoesformlogin">
            <input type="reset" class="redefinirlogin" name="resetarform">
            <input type="submit" class="enviarlogin" name="enviarform" value="Logar">
          </div>
          <?php if (isset($_SESSION['erro_login'])): ?>
            <div class="alert alert-danger">
              <?= $_SESSION['erro_login']; ?>
              <?php unset($_SESSION['erro_login']); ?> <!-- Limpa a mensagem após exibir -->
            </div>
          <?php endif;
          ?>
        </form>
      </div>
      <div class="divsemsenha">
        <p>Sem conta? <a href="/servicos/teste/controller/index.php" style="border-bottom: 1px solid white;"
            class="cadastrese">Cadastre-se!</a></p>
        <p><a href="#" style="border-bottom: 1px solid white;" class="minhasenha">Esqueci minha senha</a></p>
      </div>
    </div>
    <section>
      <div class="textomargin">
        <h2>NOSSOS PRINCIPIOS</h2>
        <div class="texto">
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam, distinctio nihil aut earum quam illum
            harum velit eum cum aliquam tenetur unde, a tempora! Quidem inventore facilis maiores sint iusto ut
            consectetur
            odio tempora vel debitis maxime laudantium voluptas alias, voluptates quisquam exercitationem laborum
            dolores
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
            odio tempora vel debitis maxime laudantium voluptas alias, voluptates quisquam exercitationem laborum
            dolores
            quo. At vero cum quam?<br /> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit non iure et
            corrupti
            nulla hic nesciunt dignissimos. Enim delectus distinctio dolorum corporis ut ad! Illo quam similique rerum
            explicabo, fugit voluptatibus iure quisquam repellat deleniti accusamus fuga recusandae et non voluptas quo
            ab. Aut, ipsum repellat facilis placeat qui nostrum a laboriosam similique laudantium dolorum illum ipsa
            debitis eveniet libero.</p>
        </div>
      </div>
    </section>
  </main>
  <footer>

  </footer>
  <script>
        // Sistema completo de gerenciamento de tema
        document.addEventListener('DOMContentLoaded', function () {
      console.log('Sistema de tema inicializado');

      // Elementos DOM
      const themeToggle = document.getElementById('theme-toggle-login');
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
    const toggleSenha = document.getElementById('toggle-senha');
    const senhaInput = document.getElementById('senha');
    const iconeSenha = document.getElementById('icone-senha');

    toggleSenha.addEventListener('click', function () {
      const isPassword = senhaInput.type === 'password';

      // Alterna entre 'password' e 'text'
      senhaInput.type = isPassword ? 'text' : 'password';

      // Troca a imagem
      iconeSenha.src = isPassword ? '../img/olho-aberto.png' : '../img/olho-fechado.png';
    });
  </script>
</body>

</html>