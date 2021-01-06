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
            if($rezultat=$polaczenie->query(sprintf("select * from gracze where
            login='%s'" 
            ,mysqli_real_escape_string($polaczenie,$_SESSION['user']))))
            {
                $wiersz=$rezultat->fetch_assoc();
                switch($nr)
                {
                    case 1:
                        $highscore=$wiersz['snakepkt'];
                        if($score>$highscore)
                        {   
                            if($polaczenie->query(sprintf("update gracze set snakepkt='%s' where login='%s'" 
                            ,mysqli_real_escape_string($polaczenie,$score)
                            ,mysqli_real_escape_string($polaczenie,$_SESSION['user']))))
                            {
                            
                            }
                            else{
                                throw new Exception(msqli_connect_errno());
                            }
                        }
                        break;
                    case 2:
                        $highscore=$wiersz['tetrispkt'];
                        if($score>$highscore)
                        {
                            if($polaczenie->query(sprintf("update gracze set tetrispkt='%s' where login='%s'" 
                            ,mysqli_real_escape_string($polaczenie,$score)
                            ,mysqli_real_escape_string($polaczenie,$_SESSION['user']))))
                            {
                                
                            }
                            else{
                                throw new Exception(msqli_connect_errno());
                            }
                        }
                        break;
                }
            }
            else{
                throw new Exception(msqli_connect_errno());
            }   
            $polaczenie->close();
            unset($_REQUEST['pkt']);
            unset($_REQUEST['nr']);
            header('Location: index.php');
          }
      }
      catch(Exception $e)
      {
          echo '<span style="color: red;">Błąd serwera, prosimy o rejestracje w innym terminie</span>';
      }
  }
?>