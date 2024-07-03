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

    <form action="buy_phone_card.php" method="post">
        <?php
            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);

           $money_vietnam =  number_format($show['balance'], 0, ',', '.') . ' VND <br>';
        ?>


        <div class = "container-deposite">
            <div class="deopiste-tittle">
                <h1>Buy Phone Card</h1>
                <span style="font-weight: 600;">Balance: <?= $money_vietnam ?> VND</span>
            </div>

            <div class="deposite-details">
                <select name="carrier_including">
                    <option value="viettel">Viettel</option>
                    <option value="mobifone">Mobifone</option>
                    <option value="vinaphone">Vinaphone</option>
                </select>
                <label for="10">10.000 VND</label>
                <input aria-label="quantity" class="input-qty" max="5" min="0" name="10" type="number" value="0">
                <label for="20">20.000 VND</label>
                <input aria-label="quantity" class="input-qty" max="5" min="0" name="20" type="number" value="0">
                <label for="50">50.000 VND</label>
                <input aria-label="quantity" class="input-qty" max="5" min="0" name="50" type="number" value="0">
                <label for="100">100.000 VND</label>
                <input aria-label="quantity" class="input-qty" max="5" min="0" name="100" type="number" value="0">
                <input name="buy" type="submit" value="Buy"/>
            </div>       
        </div>


    </form>
    <?php
        if(isset($_POST['buy'])) {
            $carrier_including = $_POST['carrier_including'];
            $card_10 = $_POST['10'];
            $card_20 = $_POST['20'];
            $card_50 = $_POST['50'];
            $card_100 = $_POST['100'];
            $money = 10000*$card_10 + 20000*$card_20 + 50000*$card_50 + 100000*$card_100;
            $code = '';

            if($money > $show['balance']) {
                ?>
                    <div class="error1">
                        <h2>Not Enough Money To Pay</h2>
                    </div>
                <?php 
                exit;
            } 
            if($card_10 == 0 && $card_20 == 0 && $card_10 == 0 && $card_100 == 0) {
                ?>
                    <div class="error1">
                        <h2>Not Full</h2>
                    </div>
                <?php 
                exit;
            }

            $sql = "UPDATE user SET balance = balance - $money WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            
            if($carrier_including == 'viettel') {
                $code_network = '11111';
            } else if($carrier_including == 'mobifone') {
                $code_network = '22222';
            } else if($carrier_including == 'vinaphone') {
                $code_network = '33333';
            }

            if($card_10 > 0) {
                for($i=0; $i<$card_10; $i++) {
                    $code .= '10.000 VND: ' . code($code_network) . '<br>';
                }
            }
            if($card_20 > 0) {
                for($i=0; $i<$card_20; $i++) {
                    $code .= '20.000 VND: ' . code($code_network) . '<br>';
                }
            }
            if($card_50 > 0) {
                for($i=0; $i<$card_50; $i++) {
                    $code .= '50.000 VND: ' . code($code_network) . '<br>';
                }
            }
            if($card_100 > 0) {
                for($i=0; $i<$card_100; $i++) {
                    $code .= '100.000 VND: ' . code($code_network) . '<br>';
                }
            }

            $tcode = date('HisYmd') . $username;
            $time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO history VALUES('$tcode', '$username', '', 'Buy Phone Card', '$money', '0', '', '$time', 'Success', '$code')";
            $result = mysqli_query($conn, $sql);
            ?>
                <div class="error1">
                    <h2>Code: <?= $code ?> </h2>
                </div>
            <?php
        }

        function code($code_network) {
            $code_random = "";    
            $chars = "0123456789";
            $size = strlen($chars);
            for($i = 0; $i < 5; $i++) {
                $code_random .= $chars[rand( 0, $size - 1)];
            }

            return $code_network . $code_random;
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>