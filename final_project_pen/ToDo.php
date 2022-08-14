<?php
    //protect the profile
    session_start();

    if(!isset($_SESSION["userId"])){
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/todo.css" />
    <title>ToDo-Done</title>
  
</head>
<!-- This is the navigation bar -->
<body>
    <div class="nav-bar">
        <form action="logout.php">
            <input type="submit" class="active" value="Log Out">
        </form>
        <?php
        $userId = $_SESSION["userId"];
        $getUser = $conn->prepare("SELECT `first_name`,`last_name` FROM `users` WHERE `id` = ?");
        $getUser->bind_param("i", $userId);
        $getUser->execute();
        $user = $getUser->get_result()->fetch_assoc();

        $first_name = $user["first_name"];
        $last_name = $user["last_name"];
        // $last_name = $conn->prepare("SELECT `last_name` FROM `users` WHERE `id` = ?");

        echo '<h1>User: '. $first_name.' '. $last_name. '</h1>'
        ?>
    </div>
<!-- This is the ToDo List -->
    <div class="content">
        <div class="wrapper">
                <div class="title"><h1>To Do</h1></div>
                <button onclick="showForm()" class="add">+Add</button>

            <?php
                $userId = $_SESSION["userId"];
                $getAllToDo = $conn->prepare("SELECT * FROM `reminders` WHERE `done` = 0 AND `user_id` = ? ORDER BY `deadline` ASC");
                $getAllToDo->bind_param("i", $userId);
                $getAllToDo->execute();
                $allToDo = $getAllToDo->get_result();

                while($row = $allToDo->fetch_assoc()) {
                    $reminderTitle = $row["title"];
                     $time = new DateTime($row["deadline"]);
                     $reminderDate = $time->format('j.n.Y');
                     $reminderTime = $time->format('H:i');
                     $reminderDescription = $row["description"];
                     $reminderId = $row["id"];   

                    echo ' <div class="card">' . 
                            '<div class="toDo-details">' .
                                '<button style="visibility: hidden;" id="message" onclick="showPopUp(\'' . $reminderTitle . '\', \'' . $reminderDescription . '\')">Show Details</button>' .
                                '<h2 class="reminder_title">' . $reminderTitle . '</h2>' .
                                '<button id="message" onclick="showPopUp(\'' . $reminderTitle . '\', \'' . $reminderDescription . '\')">Show Details</button>' .
                            '</div>' .
                            '<div class="koha">' .
                                '<div class="dita"><h4> Data ' .  $reminderDate . '</h4></div>' .
                                '<div class="data"><h4> Ora ' .  $reminderTime . '</h4></div>' .
                            '</div>' .
                            '<div class="buttons">' .
                                '<form method="post"  action="delete_reminder.php"> <input type="hidden" name="id" value="' . $reminderId . '"> <button onclick="deleteCard()" class="delete" type="submit">Delete</button></form>' .
                                '<form method="post"  action="done_reminder.php"> <input type="hidden" name="id" value="' . $reminderId . '"> <button onclick="doneCard()" class="done" type="submit">Done</button></form>' .
                            '</div>' .
                        '</div>';
                }
            ?>
        </div>
    </div>
    <!-- This is the Done List -->
    <div class="content2">
        <div class="wrapper">
            <div class="title">

                <h1>Done</h1>
      
            </div>
            <button class="add" style="visibility: hidden;">+Add</button>

            <?php
                $userId = $_SESSION["userId"];
                $getAllToDo = $conn->prepare("SELECT * FROM `reminders` WHERE `done` = 1 AND `user_id` = ? ORDER BY `deadline` ASC");
                $getAllToDo->bind_param("i", $userId);
                $getAllToDo->execute();
                $allToDo = $getAllToDo->get_result();

                while($row = $allToDo->fetch_assoc()) {
                    $reminderTitle = $row["title"];
                     $time = new DateTime($row["deadline"]);
                     $reminderDate = $time->format('j.n.Y');
                     $reminderTime = $time->format('H:i');
                     $reminderDescription = $row["description"];
                     $reminderId = $row["id"];   

                    echo ' <div class="card">' . 
                            '<div class="toDo-details">' .
                            '<button style="visibility: hidden;" id="message" onclick="showPopUp(\'' . $reminderTitle . '\', \'' . $reminderDescription . '\')">Show Details</button>' .
                                '<h2 class="reminder_title">' . $reminderTitle . '</h2>' .
                                '<button id="message" onclick="showPopUp(\'' . $reminderTitle . '\', \'' . $reminderDescription . '\')">Show Details</button>' .
                            '</div>' .
                            '<div class="koha">' .
                                '<div class="dita"><h4> Data ' . $reminderDate . '</h4></div>' .
                                '<div class="data"><h4> Ora ' . $reminderTime . '</h4></div>' .
                            '</div>' .
                            '<div class="buttons buttons_done">' .
                                '<form method="post" action="delete_reminder.php"> <input type="hidden" name="id" value="' . $reminderId . '"><button onclick="deleteCard()" class="delete" type="submit">Delete</button></form>' .
                            '</div>' .
                        '</div>';
                }
            ?>
        </div>
    </div>


<!-- This is the form pop up -->
    <div id="form-cont" class="form-cont" style="display: none;">
        <form action="create_reminder.php" class="forma" method="post">
            <span id="x" onclick="hideForm()">X</span>
            <h1>Create a new ToDo</h1>
            <input type="text" placeholder="Title" name="title">
            <input type="datetime-local" name="date">
            <textarea placeholder="Description" rows="10" name="details"></textarea>
            <input class="button" type="submit" value="Add" style="width: 50%;" name="create_todo">
        </form>
    </div>
    <!-- This is the popup message -->
    <div id="popupi" class="popup" style="display: none;">
        <div class="forma2 message">
            <span id="x2" onclick="hidePopUp()">X</span>
            <h1 id="titleeee"></h1>
            <h2 id="description-popup"></h2>
        </div>
    </div>


    <script>
        function showForm() {
            document.getElementById('form-cont').style.display = "flex";
        }
        function hideForm() {
            document.getElementById('form-cont').style.display = "none";
        }
        function showPopUp(title, desc) {
            document.getElementById('description-popup').innerHTML = desc;
            document.getElementById('titleeee').innerHTML = title;
            document.getElementById('popupi').style.display = "flex";
        }
        function hidePopUp() {
            document.getElementById('popupi').style.display = "none"
        }
        function deleteTask(id) {

        }
        function done(id) {

        }
    </script>
</body>

</html>