<?php
  session_start();

  if(isset($_POST['email'])){
    //walidacja i testy danych:
    $ok=true;
    //poprawnosc loginu:
    $login=$_POST['login'];
    //sprawdzenie dlugosci loginu
    if((strlen($login)<3) || (strlen($login)>20)){
      $ok=false;
      $_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
    }
    //sprawdzenie znaków loginu
    if(ctype_alnum($login)==false){
      $ok=false;
      $_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)!";
    }
    //sprawdzanie poprawnosci adresu email - sanityzacja
    $email=$_POST['email'];
    $emailB = filter_var($email,FILTER_SANITIZE_EMAIL); 
    if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false) || ($emailB != $email)){
      $ok=false;
      $_SESSION['e_email']="Niepoprawny adres email!";
    }
    //walidacja hasła
    $haslo1=$_POST["haslo"];
    $haslo2=$_POST["haslo2"];
    if((strlen($haslo1)<8)||(strlen($haslo1)>20)){
      $ok=false;
      $_SESSION['e_haslo']="Podane hasło musi posiadać od 8 do 20 znaków!";
    }
    if($haslo1!=$haslo2){
      $ok=false;
      $_SESSION['e_haslo']="Podane hasła się nie są identyczne!";
    }
    $haslo_hash=password_hash($haslo1, PASSWORD_DEFAULT);
    //checkbox
    if(!isset($_POST["regulamin"])){
      $ok=false;
      $_SESSION['e_regulamin']="Proszę zaakceptować regulamin!";
    }
    //bot or not
    $secret_key_recaptcha="6LeuVRcaAAAAAKeJ_c6NwQkuyRc4h6ah_pvrjXv3";
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key_recaptcha.'&response='.$_POST['g-recaptcha-response']);
    $odpowiedz = json_decode($sprawdz);
    if($odpowiedz->success==false){
      $ok=false;
      $_SESSION['e_bot']="Zaznacz reCAPTCHE!";
    }
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
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        //czy email istnieje?
        $rezultat = $polaczenie->query("SELECT id FROM gracze WHERE email='$email'");
        if(!$rezultat) throw new Exception($polaczenie->error);
        $ile_takich_maili = $rezultat->num_rows;
        if($ile_takich_maili>0){
          $ok=false;
          $_SESSION['e_email']="Istnieje już konto o podanym adresie email!";
        }
        //czy login jest zarezerwowany?
        $rezultat = $polaczenie->query("SELECT id FROM gracze WHERE login='$login'");
        if(!$rezultat) throw new Exception($polaczenie->error);
        $ile_takich_loginow = $rezultat->num_rows;
        if($ile_takich_loginow>0){
          $ok=false;
          $_SESSION['e_login']="Istnieje już konto o podanym loginie! 
          Wybierz inny.";
        }
        if($ok==true){
          //Pomyslne dodanie uzytkownika:
          if($polaczenie->query("INSERT INTO gracze VALUES (NULL,'$login','$haslo_hash','$email',0,0)")){
            $_SESSION['udanarejestracja']=true;
            header('Location:witamy.php');
          }
          else{
            throw new Exception($polaczenie->error);
          }
        }
        $polaczenie->close();
      }
    }
    
    catch(Exception $e)
    {
      $_SESSION['e_e']="Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!";
      //echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
      echo '<br/>Informacja developerska:'.$e;
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

  <style>
      .error{
        color:red;
        margin-top:10px;
        margin-bottom:10px;
      }
      .g-recaptcha{
        margin-top:10px;
        margin-bottom:10px;
        margin-left: auto;
        margin-right: auto;
        width: 20em
      }
  </style>
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
        <li class="nav-item active">
          <a class="nav-link" href="login.php">Logowanie</a>
        </li>
      </ul>
    </div>
  </nav>
  <form method='post' style="text-align: center; margin-top: 3vmin;">
    Login: <br/> <input type="text" placeholder="Twój login" name="login" value="<?php 
          if(isset($_SESSION['flogin']))
          {
            echo $_SESSION['flogin'];
            unset($_SESSION['flogin']);
          }
          ?>"/><br/>
    <?php
      //login error
      if(isset($_SESSION['e_login'])){
        echo '<div class="error">'.$_SESSION['e_login'].'</div>';
        unset($_SESSION['e_login']);
      }
    ?>
    Email: <br/> <input type="text" placeholder="Twój email" name="email" value="<?php 
          if(isset($_SESSION['femail']))
          {
            echo $_SESSION['femail'];
            unset($_SESSION['femail']);
          }
          ?>"/><br/>
    <?php
      //email error
      if(isset($_SESSION['e_email'])){
        echo '<div class="error">'.$_SESSION['e_email'].'</div>';
        unset($_SESSION['e_email']);
      }
    ?>
    Hasło: <br/> <input type="password" placeholder="Twoje hasło" name="haslo" value="<?php 
          if(isset($_SESSION['fhaslo1']))
          {
            echo $_SESSION['fhaslo1'];
            unset($_SESSION['fhaslo1']);
          }
          ?>"/><br/>
    <?php
      //haslo error
      if(isset($_SESSION['e_haslo'])){
        echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
        unset($_SESSION['e_haslo']);
      }
    ?>
    Powtórz hasło: <br/> <input type="password" placeholder="Twoje hasło" name="haslo2" value="<?php 
          if(isset($_SESSION['fhaslo2']))
          {
            echo $_SESSION['fhaslo2'];
            unset($_SESSION['fhaslo2']);
          }
          ?>"/><br/>
    <label>
      <input type="checkbox" name="regulamin" <?php
          if(isset($_SESSION['fregulamin']))
          {
            echo "checked";
            unset($_SESSION['fregulamin']);
          }
        ?>> Akceptuję regulamin
    </label>
    <?php
      //checkbox error
      if(isset($_SESSION['e_regulamin'])){
        echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
        unset($_SESSION['e_regulamin']);
      }
    ?>
      <!-- trzeba jakos ogarnac polozenie recaptchy: -->
    <div class="g-recaptcha" data-sitekey="6LeuVRcaAAAAAFlGpgTvYmR_5va8o8UuhF-pgtZi"></div>
    <?php
      //recaptcha error
      if(isset($_SESSION['e_bot'])){
        echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
        unset($_SESSION['e_bot']);
      }
    ?> 
    <br/>
    <input type="submit" value="Zarejestruj się">
    <?php
      //exception error
      if(isset($_SESSION['e_e'])){
        echo '<div class="error">'.$_SESSION['e_e'].'</div>';
        unset($_SESSION['e_e']);
      }
    ?> 

  
  </form>
  
  
</body>
</html>
