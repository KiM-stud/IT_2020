<?php
  session_start();
  if(!isset($_SESSION['zalogowany'])){
    header('Location:login.php');
    exit();
  }
  if(isset($_POST['prehaslo'])){
    //walidacja i testy danych:
    $ok=true;
    //poprawnosc loginu:
    $user=$_SESSION['user'];
    $haslo=$_POST['prehaslo'];
    $newhaslo1=$_POST['newhaslo1'];
    $newhaslo2=$_POST['newhaslo2'];
    if((strlen($newhaslo1)<8)||(strlen($newhaslo1)>20)){
        $ok=false;
        $_SESSION['e_newhaslo']="Podane hasło musi posiadać od 8 do 20 znaków!";
    }
    if($newhaslo1!=$newhaslo2){
    $ok=false;
    $_SESSION['e_newhaslo']="Podane hasła się nie są identyczne!";
    }
    $haslo_hash=password_hash($newhaslo1, PASSWORD_DEFAULT);
    
    
    $_SESSION['fprehaslo']=$haslo;
    $_SESSION['fnewhaslo1']=$newhaslo1;
    $_SESSION['fnewhaslo2']=$newhaslo2;
    
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
        
        $rezultat=$polaczenie->query("select * from gracze where login='$user'");
        $wiersz=$rezultat->fetch_assoc();
        $id=$wiersz['id'];
        if(!password_verify($haslo,$wiersz['haslo']))
        {
            $ok=false;
            $_SESSION['e_prehaslo']="Błędne hasło!";
        }
        if($ok==true){
            if($polaczenie->query("update gracze set haslo='$haslo_hash' where id='$id'")){
              echo '<script>window.opener.location.reload()</script>';
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
<html lang="en">

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
  <span id="zmiana">Obecne hasło:</span> <br/> <input type="password" placeholder="Twoje hasło" name="prehaslo" value="<?php 
          if(isset($_SESSION['fprehaslo']))
          {
            echo $_SESSION['fprehaslo'];
            unset($_SESSION['fprehaslo']);
          }
          ?>"/><br/>
    <?php
      //haslo error
      if(isset($_SESSION['e_prehaslo'])){
        echo '<div class="error">'.$_SESSION['e_prehaslo'].'</div>';
        unset($_SESSION['e_prehaslo']);
      }
    ?>
    <span id="zmiana">Nowe hasło:</span> <br/> <input type="password" placeholder="Nowe hasło" name="newhaslo1" value="<?php 
          if(isset($_SESSION['fnewhaslo1']))
          {
            echo $_SESSION['fnewhaslo1'];
            unset($_SESSION['fnewhaslo1']);
          }
          ?>"/><br/>
    <?php
      //haslo error
      if(isset($_SESSION['e_newhaslo'])){
        echo '<div class="error">'.$_SESSION['e_newhaslo'].'</div>';
        unset($_SESSION['e_newhaslo']);
      }
    ?>
    <span id="zmiana">Potwierdź nowe hasło:</span> <br/> <input type="password" placeholder="Potwierdź nowe hasło" name="newhaslo2" value="<?php 
          if(isset($_SESSION['fnewhaslo2']))
          {
            echo $_SESSION['fnewhaslo2'];
            unset($_SESSION['fnewhaslo2']);
          }
          ?>"/><br/>
    <br>
    <input class="btn btn-outline-primary" id="zmiana" type="submit" value="Zmień hasło">
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
