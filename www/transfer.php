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


    <form action="" method="post" enctype="multipart/form-data">
        <?php    
            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);

            $money_vietnam = number_format($show['balance'], 0, ',', '.') . ' VND <br>';
        ?>

        <div class="container-trainfer">
            <div class="deopiste-tittle">
                <h1>Transfer</h1>
                <span style="font-weight: 600;">Balance: <?= $money_vietnam ?> VND</span>
            </div>
            <div class="deposite-details">
                <input name="phonenumber" type="text" placeholder="Phone Number" id="phonenumber" onkeyup="GetDetail(this.value)" value="">
                <input type="text" name="fullname" id="fullname" placeholder='Full Name' value="">
                <input name="money" type="text" placeholder="Money">
                <input name="text" type="text" placeholder="Text">
                <input name="otp" type="text" placeholder="OTP">
                <a href="otp.php"  target="_blank"><input type="button" value="Get OTP"/></a>
                
                <label for="fee_select">Fee</label>
                <select name="fee_select">
                    <option value="sender">Sender</option>
                    <option value="receiver">Receiver</option>
                </select>
                <input name="transfer" type="submit" value="Transfer">
            </div>
        </div>
    </form>
    
    <?php
        if(isset($_POST['transfer'])) {
            $phonenumber = $_POST['phonenumber'];
            $fullname = $_POST['fullname'];
            $money = $_POST['money'];
            $text = $_POST['text'];
            $otp = $_POST['otp'];
            $fee_select = $_POST['fee_select'];

            if(!isset($phonenumber) || !isset($fullname) || !isset($money) || !isset($fee_select) || !isset($otp)) {
                ?>
                    <div class="error1">
                        <h2>Not full</h2>
                    </div>
                <?php 
                exit;
            }

            $tcode = date('HisYmd') . $username;
            $time = date("Y-m-d H:i:s");

            //////sql///////
            $sql = "SELECT * FROM user WHERE phonenumber = '$phonenumber'";
            $result = mysqli_query($conn, $sql);
            $show_receiver = mysqli_fetch_assoc($result);

            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show_username = mysqli_fetch_assoc($result);

            $receiver = $show_receiver['username'];
            /////////////////

            ////////dieu kien/////////
            if(!isset($show_receiver['phonenumber'])) {
                ?>
                    <div class="error1">
                        <h2>This Account Does Not Exist</h2>
                    </div>
                <?php 
                exit;
            }
            if($username == $receiver) {
                ?>
                    <div class="error1">
                        <h2>Can Not Transfer Money To Yourself'</h2>
                    </div>
                <?php 
                exit;
            }
            /////////////////

            //////xu ly chap nhan otp hay ko//////
            if($show_username['OTP'] != $otp) {
                ?>
                    <div class="error1">
                        <h2>OTP Wrong</h2>
                    </div>
                <?php 
                exit;
            } else if($time > date("Y-m-d H:i:s", strtotime('+1 minute', strtotime($show_username['time_otp'])))) {
                ?>
                    <div class="error1">
                        <h2>Time Expires</h2>
                    </div>
                <?php 
                exit;
            }

            //////tren 5tr//////
            $fee = $money*(5/100);
            $amount = $money + $fee;
 
            if($amount > $show_username['balance']) {
                ?>
                    <div class="error1">
                        <h2>Not Enough Money To Transfer'</h2>
                    </div>
                <?php 
                exit;
            }
            if($amount > 5000000) {
                $sql = "INSERT INTO history VALUES('$tcode', '$username', '$receiver', 'Transfer', '$amount', '$fee', '$fee_select', '$time', 'Pending', '$text')";
                $result = mysqli_query($conn, $sql);

                $text = $text . '<br>' . 'From: ' . $username;
                $sql = "UPDATE history SET message = '$text' WHERE tcode = '$tcode'";
                $result = mysqli_query($conn, $sql);

                $sql = "INSERT INTO history VALUES('$tcode', '$receiver', '$receiver', 'Receive', '$amount', '$fee', '$fee_select', '$time', 'Pending', '$text')";
                $result = mysqli_query($conn, $sql);


                ?>
                    <div class="error1">
                        <h2>Pending Approval</h2>
                    </div>
                <?php 

                $receiver_email = $show_receiver['email'];
            
                $sql = "SELECT * FROM history WHERE receiver = '$receiver' ORDER BY time DESC LIMIT 1";
                $result = mysqli_query($conn, $sql);
                $show_history = mysqli_fetch_assoc($result);

                $tcode = $show_history['tcode'];
                $sender = $show_history['username']; 
                $type = $show_history['type']; 
                $amount = number_format($show_history['amount'], 0, ',', '.') . ' VND';
                $fee = number_format($show_history['fee'], 0, ',', '.') . ' VND';
                $fee_of = $show_history['fee_of']; 
                $time = $show_history['time']; 
                $status = $show_history['status']; 
                $message = $show_history['message']; 

                require "PHPMailer-master/src/PHPMailer.php";  
                require "PHPMailer-master/src/SMTP.php"; 
                require 'PHPMailer-master/src/Exception.php'; 
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);  
                try {
                    $mail->SMTPDebug = 0;  
                    $mail->isSMTP();
                    $mail->CharSet  = "utf-8";
                    $mail->Host = 'smtp.gmail.com';  
                    $mail->SMTPAuth = true; 
                    $nguoigui = 'doraemondevops@gmail.com';
                    $matkhau = 'nkgr fdoh frdb etjp';
                    $tennguoigui = 'Huy';
                    $mail->Username = $nguoigui; 
                    $mail->Password = $matkhau;   
                    $mail->SMTPSecure = 'ssl'; 
                    $mail->Port = 465;   
                    $mail->setFrom($nguoigui, $tennguoigui);
                    $to = "$receiver_email";
                    $to_name = "User";

                    $mail->addAddress($to, $to_name); 
                    $mail->isHTML(true);  
                    $mail->Subject = 'Mail From PHP';
                    $noidungthu = "TCode: $tcode'<br>'
                                    Sender: $sender'<br>'
                                    Type: $type'<br>'
                                    Amount: $amount'<br>'
                                    Fee: $fee'<br>'
                                    Fee Of: $fee_of'<br>'
                                    Time: $time'<br>'
                                    Status: $status'<br>'
                                    Message: $message'<br>'";
                    $mail->Body = $noidungthu;
                    $mail->smtpConnect(array(
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                            "allow_self_signed" => true
                        )
                    ));
                    $mail->send();
                } catch (Exception $e) {
                    ?>
                        <div class="error1">
                            <h2>Error Send Mail<br></h2>
                        </div>
                    <?php 
                    $mail->ErrorInfo;
                }
                    exit;
                } 
            ////////////
            
            //////tru fee//////
            if($fee_select == 'sender') {
                $sql = "UPDATE user SET balance = balance - $fee WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);
            } else if($fee_select == 'receiver') {
                $sql = "UPDATE user SET balance = balance - $fee WHERE username = '$receiver'";
                $result = mysqli_query($conn, $sql);
            }
            ////////////

            /////update balance///////
            $sql = "UPDATE user SET balance = balance - $money WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            $sql = "UPDATE user SET balance = balance + $money WHERE username = '$receiver'";
            $result = mysqli_query($conn, $sql);
            ////////////

            //////history//////
            $sql = "INSERT INTO history VALUES('$tcode', '$username', '$receiver', 'Transfer', '$amount', '$fee', '$fee_select', '$time', 'Success', '$text')";
            $result = mysqli_query($conn, $sql);

            $text = $text . '<br>' . 'From: ' . $username;
            $sql = "UPDATE history SET message = '$text' WHERE tcode = '$tcode'";
            $result = mysqli_query($conn, $sql);

            $sql = "INSERT INTO history VALUES('$tcode', '$receiver', '$receiver', 'Receive', '$amount', '$fee', '$fee_select', '$time', 'Success', '$text')";
            $result = mysqli_query($conn, $sql);
            ////////////

            //////gui mail cho nguoi nhan//////
            $receiver_email = $show_receiver['email'];
        
            $sql = "SELECT * FROM history WHERE receiver = '$receiver' ORDER BY time DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $show_history = mysqli_fetch_assoc($result);

            $tcode = $show_history['tcode'];
            $sender = $show_history['username']; 
            $type = $show_history['type']; 
            $amount = number_format($show_history['amount'], 0, ',', '.') . ' VND';
            $fee = number_format($show_history['fee'], 0, ',', '.') . ' VND';
            $fee_of = $show_history['fee_of']; 
            $time = $show_history['time']; 
            $status = $show_history['status']; 
            $message = $show_history['message']; 

            require "PHPMailer-master/src/PHPMailer.php";  
            require "PHPMailer-master/src/SMTP.php"; 
            require 'PHPMailer-master/src/Exception.php'; 
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);  
            try {
                $mail->SMTPDebug = 0;  
                $mail->isSMTP();
                $mail->CharSet  = "utf-8";
                $mail->Host = 'smtp.gmail.com';  
                $mail->SMTPAuth = true; 
                $nguoigui = 'doraemondevops@gmail.com';
                $matkhau = 'nkgr fdoh frdb etjp';
                $tennguoigui = 'Huy';
                $mail->Username = $nguoigui; 
                $mail->Password = $matkhau;   
                $mail->SMTPSecure = 'ssl'; 
                $mail->Port = 465;   
                $mail->setFrom($nguoigui, $tennguoigui);
                $to = "$receiver_email";
                $to_name = "User";

                $mail->addAddress($to, $to_name); 
                $mail->isHTML(true);  
                $mail->Subject = 'Mail From PHP';
                $noidungthu = "TCode: $tcode'<br>'
                                    Sender: $sender'<br>'
                                    Type: $type'<br>'
                                    Amount: $amount'<br>'
                                    Fee: $fee'<br>'
                                    Fee Of: $fee_of'<br>'
                                    Time: $time'<br>'
                                    Status: $status'<br>'
                                    Message: $message'<br>'";
                $mail->Body = $noidungthu;
                $mail->smtpConnect(array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                ));
                $mail->send();
            } catch (Exception $e) {
                ?>
                    <div class="error1">
                        <h2>Error Send Mail<br></h2>
                    </div>
                <?php 
                $mail->ErrorInfo;
            }
            ////////////
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>