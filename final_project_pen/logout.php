<?php
    session_start(); 
    unset($_SESSION["userId"]);
    // unset($_SESSION["firstName"]);

    session_destroy();

    header("Location: Auth.html");
?>