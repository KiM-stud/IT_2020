<?php
  session_start();
  if(!(isset($_SESSION['log'])&&($_SESSION['log']==true)))
  { 
    header('Location: login.php');
    exit();
  }
  if(isset($_REQUEST['snakepkt']))
  {
      require_once "connect.php";
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
          $polaczenie=new mysqli($host,$db_user,$db_password,$db_name);
          $login=$_SESSION['user'];
          $score=$_REQUEST['snakepkt'];
          if($polaczenie->connect_errno!=0)
          {
              throw new Exception(msqli_connect_errno());
          }
          else
          {
              $sql="update gracze set snakepkt='$score' where login='$login'";
              $polaczenie->query($sql);
              $polaczenie->close();
              unset($_REQUEST['snakepkt']);
              header('Location: index.php');
          }
      }
      catch(Exception $e)
      {
          echo '<span style="color: red;">Błąd serwera, prosimy o rejestracje w innym terminie</span>';
      }
  }
?>