<?php
  session_start();
?>

<!doctype html>
<html lang="en" >

<head >
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  <title>
    MO Games
  </title>
</head>

<body>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
    crossorigin="anonymous"></script>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="#" class="navbar-brand"><b>MO Games</b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle " href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Gry
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="snake.php">Snake</a>
            <a class="dropdown-item" href="tetris.php">Tetris</a>
          </div>
        </li>
      </ul>
      <?php if(isset($_SESSION['zalogowany'])): ?>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="navbar-text">Zalogowany jako <?php echo $_SESSION['user'];?> !</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="account.php">Twoje konto</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="logout.php">Wyloguj</a>
        </li>
      </ul>
      <?php else: ?>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="login.php">Logowanie</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="register.php">Rejestracja</a>
        </li>
      </ul>
      <?php endif ?>
    </div>
  </nav>
  <div class="container" style="text-align: center; margin-top: 10vmin;">
    <h1 id="gry">
      Dostępne gry:
    </h1>
    <div id="karuzela" class="carousel slide container" data-ride="carousel"
      style="width: 50vmin; height: auto; position: relative;">
      <ol class="carousel-indicators">
        <li data-target="#karuzela" data-slide-to="0" class="active"></li>
        <li data-target="#karuzela" data-slide-to="1"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <a href="snake.php"><img src="snakelogo.png" class="thumbnail d-block w-100"></a>
        </div>
        <div class="carousel-item">
          <a href="tetris.php"><img src="tetrislogo.png" class="thumbnail d-block w-100"></a>
        </div>
      </div>
      <a class="carousel-control-prev" href="#karuzela" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Poprzedni</span>
      </a>
      <a class="carousel-control-next" href="#karuzela" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Następny</span>
      </a>
    </div>
  </div>
</body>
<footer class="bg-primary text-white text-center text-lg-start ">
  <!-- Grid container -->
  <div class="container p-4">
    <!--Grid row-->
    <div class="row">
        <p>
          Projekt wykonanany w ramach przedmiotu IT, którego celem było stworzenie strony internetowej.
           Tematem było stworzenie strony z grami z działającym systemem logowania oraz rankingiem graczy. Wykonany przez sekcję 313, w której skład wchodzą: Dominik Oklejewicz i Mateusz Dera w roku akademickim 2020/2021.
        </p>
    </div>
  </div>
  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
    © 2020 Copyright: Dominik Oklejewicz i Mateusz Dera
  </div>
  <!-- Copyright -->
</footer>
</html>
