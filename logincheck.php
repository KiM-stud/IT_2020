<?php
    session_start();
    if((!isset($_POST['login']))||(!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }
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
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            $login=htmlentities($login,ENT_QUOTES,"UTF-8");
            if($rezultat=$polaczenie->query(sprintf("select * from gracze where login='%s'",
            mysqli_real_escape_string($polaczenie,$login))))
            {
                $ilosc=$rezultat->num_rows;
                if($ilosc>0)
                {
                    $wiersz=$rezultat->fetch_assoc();
                    if(password_verify($haslo,$wiersz['haslo']))
                    {
                        $_SESSION['log']=true;
                        $_SESSION['id']=$wiersz['id'];
                        $_SESSION['user']=$wiersz['login'];
                        unset($_SESSION['blad']);
                        $rezultat->close();
                        header('Location:account.php');
                    }
                    else
                    {
                        $_SESSION['blad']='<span style="color: red;">Nieprawidlowe haslo</span>';
                        header('Location:login.php');
                    }
                
                }
                else
                {
                    $_SESSION['blad']='<span style="color: red;">Nieprawidlowy login lub haslo</span>';
                    header('Location:login.php');
                }
            }
            else 
            {
                throw new Exception($polaczenie->error);
            }
            $polaczenie->close();
        }
    }
    catch(Exception $e)
    {
        echo '<span style="color: red;">Błąd serwera, prosimy o rejestracje w innym terminie</span>';
    }
?>