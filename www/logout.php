<?php
    ob_start();
    session_start();

    session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/style.css"> 
	<title>Logout</title>
</head>
<body class="logout1">
    <div class="container-logout">
        <h1>Logout success. Thanks you !</h1>
        <form action="" method="post">
            <input name="return" type="submit" value="Return Login" class="out"/>
        </form>
    </div>

    <?php

        if(isset($_POST['back'])) {
            header('Location: index.php');
        }

        if(isset($_POST['return'])) {
            header('Location: login.php');
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>