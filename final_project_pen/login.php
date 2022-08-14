<?php
    session_start();

    if(isset($_POST["email"]) && isset($_POST["password"])){

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

        $email = $_POST["email"];
        $password = $_POST["password"];

        $findUser = $conn->prepare("SELECT * FROM `users` WHERE `email` = ? AND `password` = ?");
        $findUser->bind_param("ss", $email, $password);
        $findUser->execute();
        $userResult = $findUser->get_result();

        if($userResult->num_rows == 0){
            session_destroy();
            header("Location: Auth.html");
            die();
        }

        $user = $userResult->fetch_assoc();

        $_SESSION["userId"] = $user["id"];
        //$_SESSION["firstName"] = $user["first_name"];

        header("Location: ToDo.php");  
   
    }
?>