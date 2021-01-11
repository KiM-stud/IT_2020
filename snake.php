<?php
  session_start();
  if(!isset($_SESSION['zalogowany'])){
    $_SESSION['zgry']=true;
    header('Location:login.php');
    exit();
  }
?>
<!doctype html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    Snake - MO Games
  </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <li class="nav-item">
          <a class="nav-link" href="index.php">Strona Główna <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Gry
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item active" href="snake.php">Snake</a>
            <a class="dropdown-item" href="tetris.php">Tetris</a>
          </div>
        </li>
      </ul>
      </ul>
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
    </div>
  </nav>
  <!--scrolling quick fix for now-->
  <style>
    html, body {
      overflow: hidden;
    }
    .grid-container-big{
      display: grid;
      padding: 10px;
      justify-content: center;
    }
  .grid-container {
      display: grid;
      grid-template-columns: 4fr 6fr/*400px 565px*/ ;
      padding: 10px;
      justify-content: center;
    }

    .grid-item {
      /* border: 1px solid rgba(0, 0, 0, 0.8);*/
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      font-size: 30px;
      text-align: center;
      font-size:large;
    }

    .error{
        color:red;
        margin-top:10px;
        margin-bottom:10px;
      }
  </style>
  <style>
    table{
      width: 100%;
      
    }
    table, th, td {
      font-family: arial, sans-serif;
      border: 1px solid black;
      border-collapse: collapse;
      font-size: medium;
    }
    th, td {
      padding: 5px;
    }
    th{
    color: white;
    background-color: 	#047cfc;
    }
    tr:nth-child(odd) {
      background-color: #dddddd;
    }
  </style>
  <div class="grid-container-big">
  <div class="container-fluid p-3 my-3 bg-primary rounded-lg" style="padding-top:5%; width:auto;height:auto;">
    <div class="grid-container">
      <div class="grid-item">
        <b>Ranking - top10:</b>
        <br><br>
        <table border="1">
        <tr>
          <th>Miejsce</th>
          <th>Nazwa użytkownika</th>
          <th>Rekord</th>
        </tr>
          <?php
            require_once "connect.php";
            mysqli_report(MYSQLI_REPORT_STRICT);
            try
            {
                $polaczenie=new mysqli($host,$db_user,$db_password,$db_name);
                if($polaczenie->connect_errno!=0)
                {
                    throw new Exception(msqli_connect_errno());
                }
                else
                {  
                $lp=1;
                if($rezultat=$polaczenie->query("select * from gracze order by `gracze`.`snakepkt` DESC")){
                while( ($wiersz=mysqli_fetch_array($rezultat) ) && ($lp<11) ){//( $wiersz=$rezultat->fetch_assoc() )
                    ?>
                    <tr>
                        <td><?php echo $lp ?></td>
                        <td><?php echo $wiersz['login'] ?></td>
                        <td><?php echo $wiersz['snakepkt'] ?></td>
                    </tr>
                    <?php 
                  $lp=$lp+1;
                  }
                  if($rezultat=$polaczenie->query(sprintf("select * from gracze where
                      login='%s'" ,mysqli_real_escape_string($polaczenie,$_SESSION['user']))))
                      {
                          $ilosc=$rezultat->num_rows;
                          if($ilosc>0)
                          {
                            $wiersz=$rezultat->fetch_assoc();
                            $_SESSION['snakescore'] = $wiersz['snakepkt'];
                          }
                          else
                          {
                            throw new Exception(msqli_connect_errno());
                          }
                      }
                      else
                      {
                        throw new Exception(msqli_connect_errno());
                      }
                }
                else
                {
                  throw new Exception(msqli_connect_errno());
                }
                }
            }
            catch(Exception $e)
            {
              $_SESSION['e_e'] = 'Błąd serwera - nie można zaktualizować rankingu';
            }
            $polaczenie->close();
            ?>
            </table>
            </br>
            <p style="color:black"> 
                Twój najlepszy wynik: <?php echo $_SESSION['snakescore']; unset($_SESSION['snakescore']); ?>
            </p>

      </div>
      <div class="grid-item">
        <div style="position: relative; " class="container">
          <canvas id="snakegame" style="background-color:wheat; width: 582.8px; height: 582.8px; margin-left: -0%;"></canvas>
          <div class="container" style="display: inline-block; margin-left: -0%;">
            Score : <div id="score" style="display: inline-block;">0</div>
          </div>
          <script type="text/javascript" src="snakegame.js"></script>
        </div>
      </div>
    </div>
  </div>

    <?php
      //exception error
      if(isset($_SESSION['e_e'])){
        echo '<div class="error">'.$_SESSION['e_e'].'</div>';
        unset($_SESSION['e_e']);
      }
    ?> 

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
