<?php
    ob_start();
    session_start();

    if(isset($_SESSION['username']) == false) {
        header('Location: login.php');
        exit;
    }

    include('admin/db.php');
    $username = $_SESSION['username'];

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $show = mysqli_fetch_assoc($result);

    if($show['first_login'] == true) {
        $_SESSION['username_first'] = $username;
        header('Location: index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/style.css">
    <title>First Login</title>
</head>
<body>
    <form action="" method="post">
        <input name="pass1" type="password" placeholder="Password New">
        <br>
        <input name="pass2" type="password" placeholder="Password New Repeat">
        <br>
        <input name="change_password_first_login" type="submit" value="Change"/>
        <br>
        <input name="logout" type="submit" value="Logout"/>
    </form>

    <?php
        if(isset($_POST['change_password_first_login'])) {
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];

            if($pass1 == '' || $pass2 == '') {
                echo "Not Full";
                exit;
            }
            if($pass1 != $pass2) {
                echo "Password 1 != Password 2";
                exit;
            }

            $pass1 = md5($pass1);
            $pass2 = md5($pass2);

            $sql = "UPDATE user SET password = '$pass1', first_login = true WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            $_SESSION['username_first'] = $username;
            header('Location: index.php');
        } 

        if(isset($_POST['logout'])) {
            header('Location: logout.php');
            exit;
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>