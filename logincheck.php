<?php
    session_start();
    
    if((!isset($_POST['login'])) || (!isset($_POST['haslo']))){
        header('Location:login.php');
        exit();
    }
    require_once "connect.php";

    $polaczenie=@new mysqli($host,$db_user,$db_password,$db_name);

    if($polaczenie->connect_errno!=0)
    {
        echo "Error ".$polaczenie->connect_errno;
    }
    else
    {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
        //walidacja i sanityzacja przeslanych danych:
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
        if($rezultat=@$polaczenie->query(sprintf("select * from gracze where
        login='%s' and haslo='%s'" 
        ,mysqli_real_escape_string($polaczenie,$login)
        ,mysqli_real_escape_string($polaczenie,$haslo))))
        {
            $ilosc=$rezultat->num_rows;
            if($ilosc>0)
            {
                $_SESSION['zalogowany']=true;
               $wiersz=$rezultat->fetch_assoc();
               $_SESSION['user']=$wiersz['login'];
               $_SESSION['id'] = $wiersz['id'];
               unset($_SESSION['blad']);
               header('Location:account.php');
               $rezultat->close();
               
            }
            else
            {
                $_SESSION['blad']='<span style="color: red;">Nieprawidlowy login lub haslo!</span>';
                header('Location:login.php');
            }
        }
        $polaczenie->close();
    }
        
?>