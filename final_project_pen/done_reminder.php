
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

    $updateReminder = $conn->prepare("UPDATE `reminders` SET `done` = 1 WHERE `id` = ? AND `user_id`= ?");
    $updateReminder->bind_param("ii", $reminderId, $userId);
    $updateReminder->execute();

    header("Location: ToDo.php");
?>