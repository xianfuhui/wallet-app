<?php
    ob_start();
    session_start();

    if (isset($_SESSION['username'])) {
        header('Location: first_login.php');
        exit;
    }

    include('admin/db.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/style.css">
    <title>Login</title>
</head>

<body  class="loginPage">
    
    <form action="" method="post">
        <div class="main">
            <div class="container">
                <div class="loginBG">
                    <img src="./images/loginBG.jpg" alt="">
                </div>
                <div class="container-login">
                    <h1>Login</h1>
                    <input name="username" type="text" placeholder="Username">
                    <input name="password" type="password" placeholder="Password">
                    <input name="login" type="submit" value="Login" class="btn-login"/>
                    Don't have an account. <a href="./register.php">Register here</a> 
                </div>
            </div>

            <div class="text">
                <h1>E-WALLET</h1>
                <h1>make it more efficent</h1>
                <p>
                E-wallet stands for electronic wallet. It is a type of electronic card which is used for transactions made online through a computer or a smartphone. The utility of e-wallet is same as a credit or debit card. An e-wallet needs to be linked with the individual's bank account to make payments.
                </p>
                <button>Get Started</button>
            </div>

        </div>
    </form>

    <?php
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username == '' || $password == '') {
            ?>
                <div class="error">
                    <h2>Please complete the form</h2>
                </div>
            <?php
            exit;
        }

        $password = md5($password);

        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        $show = mysqli_fetch_assoc($result);

        $time = date("Y-m-d H:i:s");
        if ($count == 0) {
            ?>
                <div class="error">
                    <h2>Username Or Password Is Wrong. Please enter again</h2>
                </div>
            <?php
            exit;
        }
        if ($show['count_password_wrong'] == 3 && $time < date("Y-m-d H:i:s", strtotime('+1 minute', strtotime($show['time_count_password_wrong'])))) {
            ?>
                <div class="error">
                    <h2>This Account Entered Invalid Password 3 Times So It Was Locked For 1 Minute</h2>
                </div>
            <?php
            exit;
        }
        if ($username == $show['username'] && $password != $show['password']) {
            $sql = "UPDATE user SET count_password_wrong = count_password_wrong + 1, time_count_password_wrong = '$time' WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $check = mysqli_fetch_assoc($result);

            if ($check['count_password_wrong'] == 6) {
                $sql = "UPDATE user SET disable_auto = true, time_disable_auto = '$time' WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);

                ?>
                    <div class="error">
                        <h2>Account Locked Due To Wrong Login Too Many Times</h2>
                    </div>
                <?php
                exit;
            } else if ($check['count_password_wrong'] == 3) {
                ?>
                    <div class="error">
                        <h2>This Account Entered Invalid Password 3 Times So It Was Locked For 1 Minute</h2>
                    </div>
                <?php
                exit;
            }

            ?>
                <div class="error">
                    <h2>Password Is Wrong</h2>
                </div>
            <?php
            exit;
        }
        if ($show['disable_auto'] == true) {
            ?>
                <div class="error">
                    <h2>Account Locked Due To Wrong Login Too Many Times</h2>
                </div>
            <?php
            exit;
        }
        if ($show['disable_admin'] == true) {
            ?>
                <div class="error">
                    <h2>This account has been disabled, please contact the hotline 18001008</h2>
                </div>
            <?php
            exit;
        }

        $sql = "UPDATE user SET count_password_wrong = 0, time_count_password_wrong = null WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        $_SESSION['username'] = $username;
        header('Location: first_login.php');
    }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>