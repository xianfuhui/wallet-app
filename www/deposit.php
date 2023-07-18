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

    if(isset($_SESSION['vertified']) == false) {
        echo 
        "<script>
            window.location.href = 'index.php';
            alert('This feature is only available for verified accounts');
        </script>";
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
    <link rel="stylesheet" href="/www/font/fontawesome-free-6.1.1-web/css/all.min.css">
    <link rel="stylesheet" href="./font/fontawesome-free-6.1.1-web/css/all.css">
	<link rel="stylesheet" href="/style.css"> 
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

    <form action="deposit.php" method="post">
        <?php
            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);
            
            $money_vietnam =  number_format($show['balance'], 0, ',', '.');
        ?>
        <div class="container-deposite">
            <div class="deopiste-tittle">
                <h1>Deposite</h1>
                <span style="font-weight: 600;">Balance: <?= $money_vietnam ?> VND</span>
            </div>
            <div class="deposite-details">
                <input name="money" type="text" placeholder="Vietnamdong"/>
                <input name="card_number" type="text" placeholder="Card Number"/>
                <input name="expiration" type="date" placeholder="Expiration"/>
                <input name="cvv" type="text" placeholder="CVV"/>
                <input name="deposit" type="submit" value="Deposit"/>
            </div>  
        </div>
            
    </form>


    <?php
        if(isset($_POST['deposit'])) {
            $money = $_POST['money'];
            $card_number = $_POST['card_number'];
            $expiration = $_POST['expiration'];
            $cvv = $_POST['cvv'];

            if($money == '' || $card_number == '' || $card_number == '' || $cvv == '') {
                ?>
                    <div class="error1">
                        <h2>Not full</h2>
                    </div>
                <?php 
                exit;
            }

            $tcode = date('HisYmd') . $username;
            $time = date("Y-m-d H:i:s");

            $sql = "SELECT * FROM credit_card WHERE card_number = '$card_number'";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);
            $show = mysqli_fetch_assoc($result);

            if(strlen($card_number) != 6) {
                ?>
                    <div class="error1">
                        <h2>Card have to 6 Characters</h2>
                    </div>
                <?php 
                exit;
            } else if($count != 1) {
                ?>
                    <div class="error1">
                        <h2>This Card Is Not Supported</h2>
                    </div>
                <?php 
                exit;
            } else if(date("Y-m-d") > $show['expiration']) {
                ?>
                    <div class="error1">
                        <h2>This Card Has Expired</h2>
                    </div>
                <?php 
                exit;
            } else if($show['expiration'] != $expiration) {
                ?>
                    <div class="error1">
                        <h2>Expiration Is Wrong</h2>
                    </div>
                <?php 
                exit;
            } else if($show['cvv'] != $cvv) {
                ?>
                    <div class="error1">
                        <h2>CVV Is Wrong</h2>
                    </div>
                <?php
                exit;
            } else if($show['type'] == 2 && $money > 1000000) {
                ?>
                    <div class="error1">
                        <h2>The Number of Times To Top Up Must Not Exceed 1.000.000 VND</h2>
                    </div>
                <?php 
                exit;
            } else if($show['type'] == 3) {
                ?>
                    <div class="error1">
                        <h2>Card Is Out Of Money</h2>
                    </div>
                <?php
                exit;
            } 
            
            $sql = "INSERT INTO history VALUES('$tcode', '$username', '', 'Despoit', '$money', '0', '', '$time', 'Success', '')";
            $result = mysqli_query($conn, $sql);

            $sql = "UPDATE user SET balance = balance + '$money' WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            header('Location: deposit.php');
        } 
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>