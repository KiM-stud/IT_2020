<?php
  session_start();
  if(!(isset($_SESSION['zalogowany'])&&($_SESSION['zalogowany']==true)))
  { 
    header('Location: login.php');
    exit();
  }
  if(isset($_REQUEST['pkt']))
  {
      require_once "connect.php";
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
          $polaczenie=new mysqli($host,$db_user,$db_password,$db_name);
          $login=$_SESSION['user'];
          $score=$_REQUEST['pkt'];
          $nr=$_REQUEST['nr'];
          if($polaczenie->connect_errno!=0)
          {
              throw new Exception(msqli_connect_errno());
          }
          else
          {
            $sql="select * from gracze where login='$login'";
            $rezultat=$polaczenie->query($sql);
            $wiersz=$rezultat->fetch_assoc();
            switch($nr)
            {
                case 1:
                    
                    $highscore=$wiersz['snakepkt'];
                    if($score>$highscore)
                    {
                        $sql2="update gracze set snakepkt='$score' where login='$login'";
                        $polaczenie->query($sql2);
                    }
                    break;
                case 2:
                    $highscore=$wiersz['tetrispkt'];
                    if($score>$highscore)
                    {
                        $sql2="update gracze set tetrispkt='$score' where login='$login'";
                        $polaczenie->query($sql2);
                    }
                    break;
            }
            $polaczenie->query($sql2);
            $polaczenie->close();
            unset($_REQUEST['pkt']);
            header('Location: index.php');
          }
      }
      catch(Exception $e)
      {
          echo '<span style="color: red;">Błąd serwera, prosimy o rejestracje w innym terminie</span>';
      }
  }
?>