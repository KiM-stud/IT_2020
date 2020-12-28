<?php
    session_start();
    
    if((!isset($_POST['login'])) || (!isset($_POST['haslo']))){
        header('Location:login.php');
        exit();
    }
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try{    
        $polaczenie=new mysqli($host,$db_user,$db_password,$db_name);

        if($polaczenie->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            //walidacja i sanityzacja przeslanych danych:
            $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        
            if($rezultat=@$polaczenie->query(sprintf("select * from gracze where
            login='%s'" ,mysqli_real_escape_string($polaczenie,$login))))
            {
                $ilosc=$rezultat->num_rows;
                if($ilosc>0)
                {
                    $wiersz=$rezultat->fetch_assoc();
                    if(password_verify($haslo,$wiersz['haslo'])){
                        $_SESSION['zalogowany']=true;
                        
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
                else
                {
                    $_SESSION['blad']='<span style="color: red;">Nieprawidlowy login lub haslo!</span>';
                    header('Location:login.php');
                }
            }
            $polaczenie->close();

        
        }
    }
    catch(Exception $e){
        $_SESSION['blad']='<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>'.'<br />Informacja developerska: '.$e;
        header('Location:login.php');
    }
        
?>