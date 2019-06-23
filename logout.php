<?php
session_start();
if(session_destroy()) // Destroying All Sessions
{

  unset($_SESSION);
  setcookie("id", "", (time() - 60*60));
  $_COOKIE["id"] = "";
  header("Location: index.php"); // Redirecting To Home Page
}
?>
