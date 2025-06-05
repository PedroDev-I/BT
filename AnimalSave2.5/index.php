<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link para o Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link para o CSS-->
    <link rel="stylesheet" href="assets/css/index.css?v=1.6">



    <title>Página Inicial</title>
</head>
<body>

    <!-- Navbar-->

<nav class="navbar navbar-expand-lg" id="navbar-top">
  <div class="container-fluid">
    <a href=""><img src="assets/img/ícones/logo.png" alt="logo" style="border-radius: 50%; width: 100px;"></a>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#sobre">Sobre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#servicos">Serviços</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#depoimentos">Depoimentos</a>
        </li>
        
        <!-- Adicionando o item de Agendamentos se o usuário estiver logado -->
        <?php if (isset($_SESSION['id_cliente'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="pages/agendamentos.php">Agendamentos</a>
          </li>
        <?php endif; ?>

      </ul>
     <!-- Verifica se o usuário está logado -->
     <div class="login-box">
        <?php if (isset($_SESSION['id_cliente'])): ?>
          <!-- Menu Dropdown para o nome do cliente -->
          <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: white;">
              <?php echo $_SESSION['nome']; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><a class="dropdown-item" href="pages/perfil_cliente.php">Perfil</a></li>
              <li><a class="dropdown-item" href="actions/logout.php">Sair</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="pages/login.php"><button type="submit">Entrar</button></a>
          <a href="pages/registrar.php"><button type="submit">Registrar</button></a>
        <?php endif; ?>
  </div>
</nav>
<!-- Section Home -->
<section class="home">
    <h1>Banho e Tosa <br>
    Cuidados Especiais para o Seu <br>
    </h1>
    <p class="h1-sub" style="color: yellow; font-size: 4rem; font-family: 'Delius';">Melhor Aumigo</p>

    <p class="desc">Na AnimalSave, oferecemos banho e tosa com cuidado, qualidade e agilidade. <br> Traga seu pet para uma experiência de bem-estar completa. Agende agora!</p>

    <a href="https://web.whatsapp.com/"><button class="bt-cont" type="submit">Entre em contato</button></a>
</section>
<!-- Section Sobre -->
<section class="sobre" id="sobre">
  <div class="sobre-container">
    <div class="sobre-texto">
      <h5>Sobre nós</h5>
      <h3>Cuidando do Seu Pet <br> com Carinho e Dedicação</h3>
      <p>
        A AnimalSave é especializada em banho e tosa, oferecendo um serviço cuidadoso e dedicado para o seu pet. Nossa equipe é apaixonada pelo que faz e está comprometida em garantir saúde, higiene e conforto para o seu melhor amigo.
      </p>
    </div>
    <div class="sobre-imagem">
      <img src="assets/img/home/dogsobr.png" alt="imagem de banho" style="width: 250px;">
      <img src="assets/img/home/Catbanho.png" alt="gato baho" style="width: 250px; margin-left: 100px;">
    </div>
  </div>
</section>

<!-- Section Serviços -->
<section class="servicos" id="servicos">
  <div class="servicos-container">
    <div class="servicos-texto">
        <h1>Nossos Serviços</h1>
    </div>
    <div class="servicos-caixa">
      <div class="texto-esquerda">
        <h4 class="banho">Banho e Tosa Profissional</h4>
        <p class="banho">Deixe seu pet ainda mais feliz com nosso serviço de banho e tosa,<br> realizado por profissionais apaixonados por animais.</p>
       
        <h4 class="tosa">Higiene Bucal para Pets</h4>
        <p class="tosa">Mantenha o sorriso do seu pet saudável com nossa higiene bucal especializada, prevenindo doenças e garantindo o bem-estar.</p>

      </div>

      <div class="imagem-centralizada">
        <img src="assets/img/home/dogandcat.png" alt="cachorro e gato banhado" style="width: 400px;">
      </div>

      <div class="texto-direita">
       
        <h4 class="higiene">Cuidados Especiais para Pets</h4>
        <p class="higiene">Além de banho e tosa, oferecemos cuidados personalizados para cada tipo de pet, proporcionando conforto e saúde.</p>

        <h4 class="carinho">Tratamento de Pelos e Pelagem</h4>
        <p class="carinho">Cuidamos da pelagem do seu pet com produtos de qualidade, garantindo pelos brilhantes, sedosos e sem nós!</p>
      </div>
    </div>
  </div>
</section>

<!-- Section Mid-->

<section class="mid-site" id="mid-site">
  <div class="overlay">
    <div class="content">
      <h2>Agende o Melhor Cuidado para Seu Pet Agora Mesmo!</h2>
      <p class="desc">Banho e tosa com carinho, segurança e qualidade para deixar seu pet ainda mais feliz e bem cuidado.</p>
      <a href="pages/agendamentos.php"><button type="submit">Agende agora</button></a>
    </div>
  </div>
</section>

<!-- Section Depoimentos -->

<section class="depoimentos" id="depoimentos">
<div class="container py-5">
  <h2 class="text-center mb-5">O que estão falando do nosso PetShop?</h2>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="testimonial-card">
        <img src="assets/img/home/depoimentos/donopet.jpg" alt="Pedro" class="testimonial-img">
        <h5>Pedro</h5>
        <div class="stars">★★★★★</div>
        <p>Excelente serviço! Além do banho, fizeram uma tosa higiênica impecável. O ambiente é muito limpo e organizado, me senti super segura deixando meu pet lá.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-card">
        <img src="assets/img/home/depoimentos/dogukulele.jpg" alt="Isaac" class="testimonial-img">
        <h5>Isaac</h5>
        <div class="stars">★★★★★</div>
        <p>Meu cãozinho sempre teve medo de banho, mas aqui ele ficou super tranquilo. A equipe é maravilhosa e o cheirinho que eles colocaram nele é incrível! Viramos clientes fiéis.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-card">
        <img src="assets/img/home/depoimentos/gatolind.jpg" alt="Lucas" class="testimonial-img">
        <h5>Lucas</h5>
        <div class="stars">★★★★★</div>
        <p>Levei meu gato para o banho e fiquei impressionado com o carinho e paciência da equipe. Ele voltou calmo e com o pelo macio e brilhante. Nota 10</p>
      </div>
    </div>
  </div>
</div>

</section>

<footer class="roda-pe" id="roda-pe">
  <div class="roda-pe-container">
    <div class="roda-pe-localizacao">
      <h5>Localização</h5>
      <p>Rua Exemplo, 123 - Bairro Feliz, Cidade - Estado</p>
      <p><strong>Telefone:</strong> (11) 12345-6789</p>
    </div>
    <div class="roda-pe-descricao">
      <h5>Sobre AnimalSave</h5>
      <p>AnimalSave oferece serviços de banho e tosa para pets com todo o carinho, cuidado e qualidade. Garantimos uma experiência segura e confortável para o seu melhor amigo!</p>
    </div>
    <div class="roda-pe-horario">
      <h5>Horário de Funcionamento</h5>
      <p>Segunda a Sexta: 08h - 18h</p>
      <p>Sábado: 09h - 15h</p>
      <p>Domingo: Fechado</p>
    </div>
    <div class="roda-pe-maps">
      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d403.43218990107175!2d-48.1191905315842!3d-15.850528612151736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2sbr!4v1747960968258!5m2!1spt-PT!2sbr" width="280" height="180" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
  <div class="roda-pe-footer">
    <p>&copy; 2025 AnimalSave - Todos os direitos reservados</p>
  </div>
</footer>

    <!--Link para o Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    
    <!--Link para o JavaScript-->
    <script src="assets/js/script_homepage.js"></script>
  </body>
</html>