<?php
  session_start();
//tylko zalogowany user
  if(!isset($_SESSION['zalogowany'])){
    header('Location:login.php');
    exit();
  }
  require_once "connect.php";
  mysqli_report(MYSQLI_REPORT_STRICT);
              try{    
                  $polaczenie=new mysqli($host,$db_user,$db_password,$db_name);
                  if($polaczenie->connect_errno!=0)
                  {
                      throw new Exception(mysqli_connect_errno());
                  }
                  else
                  {
                      if($rezultat=$polaczenie->query(sprintf("select * from gracze where
                      login='%s'" ,mysqli_real_escape_string($polaczenie,$_SESSION['user']))))
                      {
                          $ilosc=$rezultat->num_rows;
                          if($ilosc>0)
                          {
                            $wiersz=$rezultat->fetch_assoc();
                            $_SESSION['email'] = $wiersz['email'];
                            $_SESSION['snakescore'] = $wiersz['snakepkt'];
                            $_SESSION['tetrisscore'] = $wiersz['tetrispkt'];
                            if ($polaczenie->query("CREATE VIEW Rankingi AS SELECT ROW_NUMBER() OVER(ORDER BY snakepkt DESC) AS row_number_snake,ROW_NUMBER() OVER(ORDER BY tetrispkt DESC) AS row_number_tetris, login FROM `gracze`") === TRUE) {
                              if($rezultat=$polaczenie->query(sprintf("SELECT * FROM Rankingi WHERE login='%s'"
                              ,mysqli_real_escape_string($polaczenie,$_SESSION['user'])))){
                              $wiersz=$rezultat->fetch_assoc();
                              $_SESSION['snakerank'] = $wiersz['row_number_snake'];
                              $_SESSION['tetrisrank']= $wiersz['row_number_tetris'];
                              $polaczenie->query("DROP VIEW Rankingi");
                              }
                              else
                              {
                                $_SESSION['blad']='<span style="color: red;">Problem połączenia z bazą.</span>';
                              }
                            }
                            else{
                              if($rezultat=$polaczenie->query(sprintf("SELECT * FROM Rankingi WHERE login='%s'"
                              ,mysqli_real_escape_string($polaczenie,$_SESSION['user'])))){
                              $wiersz=$rezultat->fetch_assoc();
                              $_SESSION['snakerank'] = $wiersz['row_number_snake'];
                              $_SESSION['tetrisrank']= $wiersz['row_number_tetris'];
                              $polaczenie->query("DROP VIEW Rankingi");
                              }
                              else
                              {
                                $_SESSION['blad']='<span style="color: red;">Problem połączenia z bazą.</span>';
                              }
                            }
                          }
                          else
                          {
                              $_SESSION['blad']='<span style="color: red;">Problem połączenia z bazą.</span>';
                          }
                      }
                      $polaczenie->close();
                  }
              }
              catch(Exception $e){
                  $_SESSION['blad']='<span style="color:red;">Błąd serwera!</span>'.'<br />Informacja developerska: '.$e;
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
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="navbar-text">Zalogowany jako <?php echo $_SESSION['user'];?> !</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="logout.php">Wyloguj</a>
        </li>
      </ul>
    </div>
  </nav>
  <style>
    .grid-container {
      display: grid;
      grid-template-columns: 200px 900px ;
      background-color: linear-gradient(to right, lightgreen, khaki);
      padding: 10px;
      justify-content: center;
    }

    .grid-item {
      background-color: rgba(255, 255, 255, 0.8);
      border: 1px solid rgba(0, 0, 0, 0.8);
      padding: 20px;
      font-size: 30px;
      text-align: center;
      font-size:large;
    }
  </style>
  <style>
    table{
      max-width: 100%;
    }
    table, td, th {
      font-family: arial, sans-serif;
      border: 1px solid black;
      border-collapse: collapse;
      font-size: medium;
      padding: 20px;
    }
     td {
      padding: 5px;
      text-align: left;
    }
  </style>

  <div class="container p-3 my-3 bg-primary rounded-lg ">
    <h3 style="text-align:center;margin-left: -5%; color: white;">
    <?php
    echo "<p>Witaj ".$_SESSION['user']."!</p>";
    ?>
    </h3>
    
  <div class="grid-container">
    <div class="grid-item">
      <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Profil</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Twoje rekordy</a>
        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Ustawienia profilu</a>
        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Usuń profil</a>
      </div>
    </div>
    <div class="grid-item">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          Twoje dane:</br></br>
        <table style="margin-left: auto; margin-right: auto;">
          <tr>
            <th>Nazwa użytkownika:</th>
            <td><?php echo $_SESSION['user'];?></td>
          </tr>
          <tr>
            <th>Adres email:</th>
            
            <td><?php
              echo $_SESSION['email'];
              unset($_SESSION['email']);
            ?></td>
          </tr>
        </table>
        <?php
          if(isset($_SESSION['blad'])){
            echo $_SESSION['blad'];
          }
        ?>
        </div>
        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
          Twoje rekordy:</br></br>
          <table style="margin-left: auto; margin-right: auto;">
          <tr>
            <th>Gra:</th>
            <th>Najwięcej zdobytych punktów:</th>
            <th>Miejsce w rankingu:</th>
          </tr>
          <tr>
            <td>Snake</td>
            <td style="text-align:center"><?php
             echo $_SESSION['snakescore'];
             unset($_SESSION['snakescore']);
            ?></td>
            <td style="text-align:center"><?php
             echo $_SESSION['snakerank'];
             unset($_SESSION['snakerank']);
            ?></td>
          </tr>
          <tr>
            <td>Tetris</td>
            <td style="text-align:center"><?php
             echo $_SESSION['tetrisscore'];
             unset($_SESSION['tetrisscore']);
            ?></td>
            <td style="text-align:center"><?php
             echo $_SESSION['tetrisrank'];
             unset($_SESSION['tetrisrank']);
            ?></td>
          </tr>
        </table>
        <?php
          if(isset($_SESSION['blad'])){
            echo $_SESSION['blad'];
            unset($_SESSION['blad']);
          }
        ?>
        </div>
        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
          Zmiana danych:</br>
          <br><br>
          <table style="margin-left: auto; margin-right: auto;">
            <tr>
              <th><a href="changelogin.php" style="text-decoration:none; color:black;">Zmiana loginu</a></th>
              <th><a href="changemail.php" style="text-decoration:none; color:black;">Zmiana emaila</a></th>
              <th><a href="changepassword.php" style="text-decoration:none; color:black;">Zmiana hasła</a></th>
            </tr>
          </table>
        </div>
        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
          <a class="nav-link" style="color: red" href="deleteaccount.php" onclick="return confirm_delete()">Usuń konto</a>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  function confirm_delete() 
  {
    return confirm('Jesteś pewny, że chcesz usunąć konto?');
  }
  </script>
</body>
</html>
