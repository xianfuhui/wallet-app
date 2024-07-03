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

    $otp = "";    
    $time = date("Y-m-d H:i:s");

    $chars = "0123456789";
    $size = strlen($chars);
    for($i = 0; $i < 6; $i++) {
        $otp .= $chars[rand( 0, $size - 1)];
    }

    $sql = "UPDATE user SET OTP = '$otp', time_otp = '$time' WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
   
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $show = mysqli_fetch_assoc($result);

    if(!isset($show['phonenumber'])) {
        echo 'This Account Does Not Exist';
        exit;
    }

    $email = $show['email'];

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
        echo 'Send Mail Success<br>';
    } catch (Exception $e) {
        echo 'Error Send Mail<br>: ', $mail->ErrorInfo;
    }
?>