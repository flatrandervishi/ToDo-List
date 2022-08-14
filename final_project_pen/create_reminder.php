<?php
    session_start();

    if(isset($_POST["title"]) && 
    isset($_POST["date"]) &&
    isset($_POST["details"])){

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
            header("Location: Auth.html");
            die();
        }

        $title = $_POST["title"];
        $description = $_POST["details"];
        $time = date("Y-m-d H:i:s", strtotime($_POST["date"]));

        $createReminder = $conn->prepare("INSERT INTO `reminders` (`title`, `description`, `deadline`, `user_id`) VALUES (?, ?, ?, ?)");
        $createReminder->bind_param("sssi", $title, $description, $time, $_SESSION["userId"]);

        $createReminder->execute();
        
        header("Location: ToDo.php");
    }

?>