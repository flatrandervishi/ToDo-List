<?php
    if(isset($_POST["firstName"]) &&
    isset($_POST["lastName"]) &&
    isset($_POST["email"]) &&
    isset($_POST["password"])){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbName = "final_project_2";

        $connection = new mysqli($servername, $username, $password, $dbName);

        if($connection->connect_error){
            //ka ndodhur nje error gjate lidhjes me databaze
            header("Location: Auth.html");
            die();
        }
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $userPassword = $_POST["password"];
        
        //check a ka naj user me email te njejte ose username te njejte
        $checkDuplicate = $connection->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $checkDuplicate->bind_param("s", $email);

        $checkDuplicate->execute();
        $duplicateResult = $checkDuplicate->get_result();

        if($duplicateResult->num_rows > 0){
            header("Location: Auth.html");
            die();
        }

        $stmt = $connection->prepare("INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $userPassword);

        $stmt->execute();

	    $stmt->close();
        $connection->close();

        header("Location: Auth.html");
    }
?>