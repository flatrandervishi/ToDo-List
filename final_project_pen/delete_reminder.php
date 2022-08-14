
<?php
    session_start();

    if(!$_SESSION["userId"]){
        header("Location: Auth.html");
        die();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "final_project_2";

    $conn = new mysqli($servername, $username, $password, $dbName);
    if($conn->connect_error){
        //ka ndodhur nje error gjate lidhjes me databaze
        echo "Could not connect ot the database!";
        header("Location:Auth.html");
        die();
    }
    
    $userId = $_SESSION["userId"];
    $reminderId = $_POST["id"];          

    $deleteReminder = $conn->prepare("DELETE FROM `reminders` WHERE `id` = ? AND `user_id` = ?");
    $deleteReminder->bind_param("ii", $reminderId, $userId);
    $deleteReminder->execute();

    header("Location: ToDo.php");
?>