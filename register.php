<?php
  session_start();
  if(isset($_POST['email']))
  {
    //udawana walidacja
    $isOkay=true;

    //sprawdzenie loginu
    $login=$_POST['login'];
    if(strlen($login)<3|| strlen($login)>20)
    {
      $isOkay=false;
      $_SESSION['errlogin']="Login musi posiadać od 3 do 20 znaków";
    }

    if(!ctype_alnum($login))
    {
      $isOkay=false;
      $_SESSION['errlogin']="Login musi składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    //sprawdzenie email
    $email=$_POST['email'];
    $email2=filter_var($email,FILTER_SANITIZE_EMAIL);
    if((filter_var($email2,FILTER_VALIDATE_EMAIL)==false)||($email2!=$email))
    {
      $isOkay=false;
      $_SESSION['erremail']="Podaj poprawny adres email";
    }

    //sprawdzenie hasla
    $haslo1=$_POST['haslo1'];
    $haslo2=$_POST['haslo2'];
    if(strlen($haslo1)<8|| strlen($login)>20)
    {
      $isOkay=false;
      $_SESSION['errhaslo']="Hasło musi posiadać od 8 do 20 znaków";
    }
    if($haslo1!=$haslo2)
    {
      $isOkay=false;
      $_SESSION['errhaslo']="Podane hasła nie są identyczne";
    }
    $haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT);

    //sprawdzenie checkboxa
    if(!isset($_POST['regulamin']))
    {
      $isOkay=false;
      $_SESSION['errregulamin']="Potwierdź akceptacje regulaminu";
    }
    //bot or not XD
    $secret="6Ldn9RcaAAAAAHuDKaC6BYekZtuURnwHMWkykx0p";
    $check=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $odp=json_decode($check);
    if(!($odp->success))
    {
      $isOkay=false;
      $_SESSION['errbot']="Potwierdź, że nie jesteś botem";
    }
    //zapamietanie wprowadzonych danych
    $_SESSION['flogin']=$login;
    $_SESSION['femail']=$email;
    $_SESSION['fhaslo1']=$haslo1;
    $_SESSION['fhaslo2']=$haslo2;
    if(isset($_POST['regulamin']))
      $_SESSION['fregulamin']=true;


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
        //sprawdzenie czy email juz jest w bazie
        $rezultat=$polaczenie->query("select id from gracze where email='$email'");
        if(!$rezultat)
          throw new Exception($polaczenie->error);
        $imail=$rezultat->num_rows;
        if($imail>0)
        {
          $isOkay=false;
          $_SESSION['erremail']="Istnieje juz konto przypisane do tego emaila";
        }
        //sprawdzenie czy login juz istnieje
        $rezultat=$polaczenie->query("select id from gracze where login='$login'");
        if(!$rezultat)
          throw new Exception($polaczenie->error);
        $ilogin=$rezultat->num_rows;
        if($ilogin>0)
        {
          $isOkay=false;
          $_SESSION['errlogin']="Istnieje juz konto z takim loginem, wybierz inny";
        }
        if($isOkay)
        {
          if($polaczenie->query("insert into gracze values(NULL,'$login','$haslo_hash','$email','0','0')"))
          {
            $_SESSION['nicereg']=true;
            header('Location: login.php');
          }
          else
          {
            throw new Exception($polaczenie->error);
          }
        }
        $polaczenie->close();
      }
    }
    catch(Exception $e)
    {
      echo '<span style="color: red;">Błąd serwera, prosimy o rejestracje w innym terminie</span>';
    }
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
    Rejestracja
  </title>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body style=" background: linear-gradient(to right, lightgreen,khaki);">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
    crossorigin="anonymous"></script>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="#" class="navbar-brand">MO Games</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item ">
          <a class="nav-link" href="index.php">Strona Główna <span class="sr-only">(current)</span></a>
        </li>
        <?php
          if(isset($_SESSION['log'])&&($_SESSION['log']==true))
          {
            header('Location: account.php');
            exit();
          }
          else
          {
            echo<<<END
            <li class="nav-item">
              <a class="nav-link" href="login.php">Logowanie</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="register.php">Rejestracja</a>
            </li>
            END;
          }
        ?>
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
  <div class="container">
    <form method="post">
        Login:<br><input type="text" name="login" placeholder="Twój login" required 
        value="<?php 
          if(isset($_SESSION['flogin']))
          {
            echo $_SESSION['flogin'];
            unset($_SESSION['flogin']);
          }
          ?>"><br>
        <?php
          if(isset($_SESSION['errlogin']))
          {
            echo '<div style="color: red;">'.$_SESSION['errlogin'].'</div>';
            unset($_SESSION['errlogin']);
          }
        ?>
        Email:<br><input type="text" name="email" placeholder="Email" required
        value="<?php 
          if(isset($_SESSION['femail']))
          {
            echo $_SESSION['femail'];
            unset($_SESSION['femail']);
          }
          ?>"><br>
        <?php
          if(isset($_SESSION['erremail']))
          {
            echo '<div style="color: red;">'.$_SESSION['erremail'].'</div>';
            unset($_SESSION['erremail']);
          }
        ?>
        Hasło:<br><input type="password" name="haslo1" placeholder="Twoje hasło" required
        value="<?php 
          if(isset($_SESSION['fhaslo1']))
          {
            echo $_SESSION['fhaslo1'];
            unset($_SESSION['fhaslo1']);
          }
          ?>"><br>
        <?php
          if(isset($_SESSION['errhaslo']))
          {
            echo '<div style="color: red;">'.$_SESSION['errhaslo'].'</div>';
            unset($_SESSION['errhaslo']);
          }
        ?>
        Powtorz hasło:<br><input type="password" name="haslo2" placeholder="Twoje hasło" required
        value="<?php 
          if(isset($_SESSION['fhaslo2']))
          {
            echo $_SESSION['fhaslo2'];
            unset($_SESSION['fhaslo2']);
          }
          ?>"><br>
        <label>
        <input type="checkbox" name="regulamin"
        <?php
          if(isset($_SESSION['fregulamin']))
          {
            echo "checked";
            unset($_SESSION['fregulamin']);
          }
        ?>
        > Akceptuję regulamin 
        </label>
        <?php
          if(isset($_SESSION['errregulamin']))
          {
            echo '<div style="color: red;">'.$_SESSION['errregulamin'].'</div>';
            unset($_SESSION['errregulamin']);
          }
        ?>
        <div class="g-recaptcha" data-sitekey="6Ldn9RcaAAAAAGq-j6jm2OyI6LLtdkBn5cXKYEWV"></div>
        <?php
          if(isset($_SESSION['errbot']))
          {
            echo '<div style="color: red;">'.$_SESSION['errbot'].'</div>';
            unset($_SESSION['errbot']);
          }
        ?>    
        <input type="submit" value="Zarejestruj się">
    </form>
  <?php
  if(isset($_SESSION['blad']))
    {
      echo $_SESSION['blad'];
      unset($_SESSION['blad']);
    }
  ?>

  </div>
</body>
</html>