<?php
    ob_start();
    session_start();

    if(isset($_SESSION['username']) == false) {
        header('Location: login.php');
        exit;
    }

    if(isset($_SESSION['username_first']) == false) {
        header('Location: first_login.php');
        exit;
    }

    include("admin/db.php");
    $username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/style.css"> 
    <link rel="stylesheet" href="/www/font/fontawesome-free-6.1.1-web/css/all.min.css">
    <link rel="stylesheet" href="./font/fontawesome-free-6.1.1-web/css/all.css">
	<title>Home Page</title>
</head>
<body>
<div class="header">
        <div class="container-index">
            <div class="icon">
                <i class="fa-solid fa-wallet"></i> 
                <p>WALLET</p>
            </div>
            <ul class="navbar">
                 <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="change_password.php">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="deposit.php">Deposit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="withdraw.php">Withdraw</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transfer.php">Transfer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="buy_phone_card.php">Buy Phone Card</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="history.php">History</a>
                </li>
                <li class="nav-item nav-logout">
                    <a class="nav-link" href="logout.php">Logout
                       <i class="fa-solid fa-right-from-bracket"></i> 
                    </a>                 
                </li>
            </ul>
        </div>
    </div>
    <form action="change_password.php" method="post">
        <div class="step2">
            <h1 style="text-align: center; padding-bottom:20px;">Change Password</h1>
            <input name="pass_old" type="password" placeholder="Password Current">
            <input name="pass1" type="password" placeholder="Password New">
            <input name="pass2" type="password" placeholder="Password New Repeat">
            <input name="change_password_index" type="submit" value="Change"/>
        </div>
        
    </form>
    <?php
        if(isset($_POST['change_password_index'])) {
            $pass_old = $_POST['pass_old'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];

            if($pass_old == '' || $pass1 == '' || $pass2 == '') {
                ?>
                    <div class="error1">
                        <h2>Not full</h2>
                    </div>
                <?php 
                exit;
            }

            $pass_old = md5($pass_old);
            $pass1 = md5($pass1);
            $pass2 = md5($pass2);

            $sql = "SELECT * FROM user WHERE password = '$pass_old' and username = '$username'";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);

            if($count == 0) {
                ?>
                    <div class="error1">
                        <h2>Password Wrong</h2>
                    </div>
                <?php 
                exit;
            } else if($pass1 != $pass2) { 
                ?>
                    <div class="error1">
                        <h2>Password does not match. Please enter again</h2>
                    </div>
                <?php 
                exit;
            } 

            $sql = "UPDATE user SET password = '$pass1' WHERE username = '$username'";
            mysqli_query($conn, $sql);

            ?>
                <div class="error1">
                    <h2>Change password succes</h2>
                </div>
            <?php 
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>