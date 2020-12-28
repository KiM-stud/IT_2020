<?php
    session_start();
    if(!(isset($_SESSION['log'])&&($_SESSION['log']==true)))
    { 
        header('Location: index.php');
        exit();
    }
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $polaczenie=new mysqli($host,$db_user,$db_password,$db_name);
        $login=$_SESSION['user'];
        if($polaczenie->connect_errno!=0)
        {
            throw new Exception(msqli_connect_errno());
        }
        else
        {
            $sql="delete from gracze where login='$login'";
            $polaczenie->query($sql);
            $polaczenie->close();
            session_unset();
            header('Location: index.php');
        }
    }
    catch(Exception $e)
    {
        echo '<span style="color: red;">Błąd serwera, prosimy o rejestracje w innym terminie</span>';
    }
    
?>