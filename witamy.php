<?php
  session_start();
//tylko zalogowany user
  /*
  if(!isset($_SESSION['udanarejestracja'])){
    header('Location:index.php');
    exit();
  }
  else{
    unset($_SESSION['udanarejestracja']);
  }
  */
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  <title>
    Logowanie
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
        <li class="nav-item ">
          <a class="nav-link" href="index.php">Strona Główna <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Gry
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="snake.php">Snake</a>
            <a class="dropdown-item" href="tetris.php">Tetris</a>
          </div>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="login.php">Logowanie</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="text" style="text-align: center; margin-top: 3vmin;">
    <p>Dziękujemy za rejestrację w serwisie. Możesz zalogować się na konto!</p>
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
