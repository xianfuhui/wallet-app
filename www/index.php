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

    <?php
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        $show = mysqli_fetch_assoc($result);

        $money_vietnam = number_format($show['balance'], 0, ',', '.');

        ?>
            <div class="profile">
                <div class="tittle">
                    <h1>Profile</h1>
                    <span style="font-weight: 600;">Balance: <?= $money_vietnam ?> VND</span>
                </div>
                <div class = "profile-body">           
                    <span> <i class="fa-solid fa-file-signature"></i> <p>Full Name:</p>  <?= $show['fullname'] ?> </span>
                    <span> <i class="fa-solid fa-at"></i> <p>Email:</p> <?=  $show['email'] ?> </span>
                    <span> <i class="fa-solid fa-phone"></i> <p>Phone number:</p> <?= $show['phonenumber'] ?> </span>
                    <span> <i class="fa-solid fa-cake-candles"></i> <p>Birthday:    </p> <?= $show['birthday'] ?> </span>
                    <span> <i class="fa-solid fa-location-crosshairs"></i> <p>Address</p> <?= $show['address'] ?> </span>
                </div>
            </div>
            
        <?php
        
        if($show['active'] == 1) {
            ?>
                <div class="veri" >
                    <h2>Status: Verified</h2>
                </div>         
            <?php
            $_SESSION['vertified'] = 'yes';
        } else if($show['identity_card_1'] == "" || $show['identity_card_2'] == "") { 
            echo '<br>';
            ?>
            <div class="veri" >
                <h2>Status</h2>
                <form method="post" action="process.php" enctype="multipart/form-data">
                    <input name="username" type="hidden" value="<?php echo $username; ?>">
                    <input type="file" name="identity_card_1">
                    <br>
                    <input type="file" name="identity_card_2">
                    <br>
                    <input name="upfile" type="submit" value="Upfile">
                    <br>
                </form>
            </div>
            <?php
        } else {
            ?>
                <div class="veri" >
                    <h2>Status: Waiting For Update</h2>
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