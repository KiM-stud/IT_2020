<?php
  session_start();
  if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
  {
    header('Location:account.php');
    exit();
  }
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

  <title>
    Logowanie
  </title>
</head>

<body style=" background: linear-gradient(to right, lightgreen,khaki);">
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
    </div>
  </nav>
  <div class="container" style="text-align: center; margin-top: 3vmin;">
  <?php
    if((isset($_SESSION['zgry'])) && ($_SESSION['zgry']==true))
    {
      echo "<h3>Zaloguj się aby grać w najlepsze gry!</h3>";
      uset($_SESSION['zgry']);
    }
  ?>
  </div>
  <div class="container" style="text-align: center; margin-top: 3vmin;">
    <form action="logincheck.php" method="post">
        Login:<br /><input type="text" name="login" placeholder="Twój login" required><br>
        Hasło:<br /><input type="password" name="haslo" placeholder="Twoje hasło" required><br><br>
        <input type="submit" value="Zaloguj się">
        <br>
    </form>
  <?php
  if(isset($_SESSION['blad'])){
    echo $_SESSION['blad'];
    unset($_SESSION['blad']);
  }
  ?>
  <p>Nie masz konta? <a href="register.php" target="_self">Zarejestruj się!</a></p>
  </div>
  
</body>
</html>
