<?php
    ob_start();
    session_start();

    if(isset($_SESSION['username'])) {
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
	<title>Reset Password</title>
</head>
<body class="bg">
    
    <form action="" method="post">
        <div class="container-reset">
            <h3>Step 1</h3>
            <input name="email" type="text" placeholder="Email" class="email">             
            <input name="phonenumber" type="text" placeholder="Phone Number" class="phone">
            <input name="reset_password" type="submit" value="Next"/>
            <input name="back" type="submit" value="Back" style="background-color: #000; color:#fff"/>
        </div>
    </form>

    <?php
        if(isset($_POST['back'])) {
            header('Location: register.php');
        }


        if(isset($_POST['reset_password'])) {
            $email = $_POST['email'];
            $phonenumber = $_POST['phonenumber'];

            if($email == '' && $phonenumber == '') {
                ?>
                    <div class="error">
                        <h2>Please enter your email or mobile</h2>
                    </div>
                <?php
                exit;
            } else if($email != '' && $phonenumber != '') {
                ?>
                    <div class="error">
                        <h2>Can Only Enter One Option.Please enter again !</h2>
                    </div>
                <?php
                exit;
            } 
            
            if($email != '') {
                $sql = "SELECT * FROM user WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $show = mysqli_fetch_assoc($result);
            } else if($phonenumber != '') {
                $sql = "SELECT * FROM user WHERE phonenumber = '$phonenumber'";
                $result = mysqli_query($conn, $sql);
                $show = mysqli_fetch_assoc($result);
            } 

            if(!isset($show['email']) || !isset($show['phonenumber'])) {
                ?>
                    <div class="error">
                        <h2>This Account Does Not Exist. Please enter again !</h2>
                    </div>
                <?php
                exit;
            }

            $username = $show['username'];
            $email = $show['email'];

            $otp = "";    
            $time = date("Y-m-d H:i:s");

            $chars = "0123456789";
            $size = strlen($chars);
            for($i = 0; $i < 6; $i++) {
                $otp .= $chars[rand( 0, $size - 1)];
            }

            $sql = "UPDATE user SET OTP = '$otp', time_otp = '$time' WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

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
                $nguoigui = 'huyremoving9031@gmail.com';
                $matkhau = '#ChoHuy77@';
                $tennguoigui = 'Huy';
                $mail->Username = $nguoigui; 
                $mail->Password = $matkhau;   
                $mail->SMTPSecure = 'ssl'; 
                $mail->Port = 465;   
                $mail->setFrom($nguoigui, $tennguoigui);
                $to = "$email";
                $to_name = "User";

                $mail->addAddress($to, $to_name); 
                $mail->isHTML(true);  
                $mail->Subject = 'Mail From PHP';
                $noidungthu = "<b>Hello!</b><br>Your OTP: $otp</br>";
                $mail->Body = $noidungthu;
                $mail->smtpConnect(array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                ));
                $mail->send();
                ?>
                    <div class="error">
                        <h2>Send Mail Success</h2>
                    </div>
                <?php 
            } catch (Exception $e) {
                ?>
                    <div class="error">
                        <h2>Send Email failed</h2>
                    </div>
                <?php 
                 $mail->ErrorInfo;
            }

            ?>
            <form action="" method="post" >
                <div class="step2">
                    <h3>Step 2</h3>
                    <input name="username" type="hidden" value="<?php echo $username; ?>"/>
                    <input name="otp" type="text" placeholder="OTP">
                    <input name="pass1" type="password" placeholder="Password New">
                    <input name="pass2" type="password" placeholder="Password New Repeat">
                    <input name="input_otp" type="submit" value="Next"/>
                </div>
            </form>
            <?php
        }

        if(isset($_POST['input_otp'])) {
            $username = $_POST['username'];
            $otp = $_POST['otp'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];

            if($otp == '' || $pass1 == '' || $pass2 == '') {
                ?>
                <div class="error">
                    <h2>Please enter your new password !</h2>
                </div>
            <?php
                exit;
            } 

            $pass1 = md5($pass1);
            $pass2 = md5($pass2);

            if($pass1 != $pass2) { 
                ?>
                    <div class="error">
                        <h2>Passwords do not match. Please enter again</h2>
                    </div>
                <?php
                exit;
            }

            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);

            $time = date("Y-m-d H:i:s");
            if($show['OTP'] != $otp) {
                ?>
                    <div class="error">
                        <h2>OTP is wrong. Please enter again !</h2>
                    </div>
                <?php
                exit;
            } else if($time > date("Y-m-d H:i:s", strtotime('+1 minute', strtotime($show['time_otp'])))) {
                ?>
                    <div class="error">
                        <h2>Time Expires !</h2>
                    </div>
                <?php
                exit;
            }

            $sql = "UPDATE user SET password = '$pass1' WHERE username = '$username'";
            mysqli_query($conn, $sql);

            ?>
                <div class="error">
                    <h2>Successfull !</h2>
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