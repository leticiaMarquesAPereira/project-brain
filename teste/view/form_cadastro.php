<!DOCTYPE html>
<html lang="PT-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style.css">
  <title>Formulario Teste</title>
</head>

<body>
  <div class="espaco"></div>
  <header class="container">
    <div class="criando">
      <h1>CRIANDO CADASTRO</h1>
    </div>
    <?php
    include __DIR__ . "/navegacao.html";
    $pessoa = new Pessoa(null, null, null, null, null, null, null, null);
    $arrSex = $pessoa->arrSex;
    ?>
  </header>
  <section class="sectionform">
    <div class="formprincipal">
      <form action="../controller/processa_form.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="criar">
        <div class="nomesenha">
          <div class="nomecad">
            <p>Nome</p>
            <input type="text" name="nome" class="barra" placeholder="Digite apenas letras">
          </div>
          <div class="senhacad">
            <p>Senha</p>
            <input type="password" name="senha" class="barrasenha" id="senha" placeholder="Digite sua senha"/>
            <button type="button" id="toggle-senha" class="senha-toggle">
              <img src="/servicos/teste/img/olho-fechado.png" id="icone-senha" alt="Mostrar senha" class="olho"/>
            </button>
          </div>
        </div>
        <div class="cpf">
          <p>CPF</p>
          <input type="text" name="cpf" class="barra2" maxlength="11" minlength="11" pattern="\d{11}"
            placeholder="Digite apenas numeros">
        </div>
        <div class="sexo">
          <div class="dropdown">
            <p>Sexo </p>
          </div>
          <select name="sexo" class="dropdown-conteudo">
            <?php
            foreach ($pessoa->arrSex as $sex => $sex_name) {
              echo "<option value='$sex'>$sex_name</option>";
            }
            ?>
          </select>
        </div>
        <div class="textebuttom">
          <div class="areatexto">
            <p>Mensagem</p>
            <textarea name="msg" class="msg" placeholder="Nos escreva um recado gentil!"></textarea>
          </div>
          <div class="filebuttom">
            <p>Anexo</p>
            <label for="anexo" class="custom-file-button">Escolher arquivo</label>
            <i class="filespermitidos">*somente arquivos .pdf, .png e .jpg*</i>
            <span id="fileStatus">Nenhum arquivo selecionado</span>
            <div class="botoesform">
              <input type="reset" class="redefinir" name="resetarform">
              <input type="submit" class="enviar" name="enviarform">
              <input type="file" name="anexo" id="anexo" class="anexos">
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
  include __DIR__ . "/rodape.php";
  ?>
  <script>
    document.getElementById('anexo').addEventListener('change', function () {
      const fileStatus = document.getElementById('fileStatus');
      const curFiles = this.files;

      if (curFiles.length === 0) {
        fileStatus.textContent = "Nenhum arquivo selecionado";
      } else {
        fileStatus.textContent = curFiles[0].name;
      }
    });
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