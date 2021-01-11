<?php
  session_start();
  if(!isset($_SESSION['zalogowany'])){
    header('Location:login.php');
    exit();
  }
  if(isset($_POST['newlogin'])){
    //walidacja i testy danych:
    $ok=true;
    //poprawnosc loginu:
    $user=$_SESSION['user'];
    $login=$_POST['newlogin'];
    //sprawdzenie dlugosci loginu
    if((strlen($login)<3) || (strlen($login)>20)){
      $ok=false;
      $_SESSION['e_newlogin']="Login musi posiadać od 3 do 20 znaków!";
    }
    //sprawdzenie znaków loginu
    if(ctype_alnum($login)==false){
      $ok=false;
      $_SESSION['e_newlogin']="Login może składać się tylko z liter i cyfr (bez polskich znaków)!";
    }
    
    $haslo=$_POST["newpass"];
    $_SESSION['fnewlogin']=$login;
    $_SESSION['fnewpass']=$haslo;
    
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
        //czy login jest zarezerwowany?
        $rezultat = $polaczenie->query("SELECT id FROM gracze WHERE login='$login'");
        if(!$rezultat) throw new Exception($polaczenie->error);
        $ile_takich_loginow = $rezultat->num_rows;
        if($ile_takich_loginow>0){
          $ok=false;
          $_SESSION['e_newlogin']="Istnieje już konto o podanym loginie! 
          Wybierz inny.";
        }
        $rezultat=$polaczenie->query("select * from gracze where login='$user'");
        $wiersz=$rezultat->fetch_assoc();
        $id=$wiersz['id'];
        if(!password_verify($haslo,$wiersz['haslo']))
        {
            $ok=false;
            $_SESSION['e_newpass']="Błędne hasło!";
        }
        if($ok==true){
            if($polaczenie->query("update gracze set login='$login' where id='$id'")){
                echo '<script>window.opener.location.reload()</script>';
                $_SESSION['user']=$login;
                echo "<script> window.close();</script>";
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
<html lang="en" >

<head>
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
    
  </nav>
  <br>
  <form method='post' style="text-align: center; margin-top: 3vmin;" >
    <span id="zmiana">Nowy login:</span><br/> <input type="text" placeholder="Nowy login" name="newlogin" value="<?php 
          if(isset($_SESSION['fnewlogin']))
          {
            echo $_SESSION['fnewlogin'];
            unset($_SESSION['fnewlogin']);
          }
          ?>"/><br/>
    <?php
      //login error
      if(isset($_SESSION['e_newlogin'])){
        echo '<div class="error">'.$_SESSION['e_newlogin'].'</div>';
        unset($_SESSION['e_newlogin']);
      }
    ?>
    <br>
      <span id="zmiana">Potwierdź hasło:</span><br/> <input type="password" placeholder="Hasło" name="newpass"  value="<?php 
          if(isset($_SESSION['fnewpass']))
          {
            echo $_SESSION['fnewpass'];
            unset($_SESSION['fnewpass']);
          }
          ?>"/><br/>
    <?php
      //login error
      if(isset($_SESSION['e_newpass'])){
        echo '<div class="error">'.$_SESSION['e_newpass'].'</div>';
        unset($_SESSION['e_newpass']);
      }
    ?>
    <br>
    <input class="btn btn-outline-primary" id="zmiana" type="submit" value="Zmień login">
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
