<?php
    session_start();
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
        $sql="select * from gracze where login='$login' and haslo='$haslo'";
        if($rezultat=@$polaczenie->query($sql))
        {
            $ilosc=$rezultat->num_rows;
            if($ilosc>0)
            {
               $wiersz=$rezultat->fetch_assoc();
               $_SESSION['user']=$wiersz['login'];
               unset($_SESSION['blad']);
               header('Location:account.php');
               $rezultat->close();
               
            }
            else
            {
                $_SESSION['blad']='<span style="color: red;">Nieprawidlowy login lub haslo</span>';
                header('Location:login.php');
            }
        }
        $polaczenie->close();
    }
        
?>