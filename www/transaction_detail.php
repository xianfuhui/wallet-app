<?php
    ob_start();
    session_start();

    include("admin/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css"> 
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
    
    
    <?php
        if(isset($_POST['user_view'])) {
            $tcode = $_POST['tcode'];

            $sql = "SELECT * FROM history WHERE tcode = '$tcode'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);
            

            ?>  
                <h1 class="id">Id: <?= $show['username'] ?> </h1>
                <div class="trans-details">

                    <span><p>TCode: </p> <?= $show['tcode'] ?>  </span>
                    <span><p>Username: </p> <?= $show['username'] ?>  </span>
                    <span><p>Receiver: </p> <?= $show['receiver'] ?>  </span>
                    <span><p>Type: </p> <?= $show['type'] ?>  </span>
                    <span><p>Amount: </p> <?= number_format($show['amount'], 0, ',', '.') ?>  VND </span>
                    <span><p>Fee: </p> <?= number_format($show['fee'], 0, ',', '.') ?> VND </span>
                    <span><p>Fee of: </p> <?= $show['fee_of'] ?>  </span>
                    <span><p>Time: </p> <?= $show['time'] ?>  </span>
                    <span><p>Status: </p> <?= $show['status'] ?>  </span>
                    <span><p>Message: </p> <?= $show['message'] ?>  </span>
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