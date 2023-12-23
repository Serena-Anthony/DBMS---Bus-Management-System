<?php 
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    session_unset();
    session_destroy();
    header("Location: login.php");
}
else if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    session_unset();
    session_destroy();
    header("Location: login.php");
}
else
{
    header("Location: login.php");
}