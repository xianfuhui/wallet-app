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
	<title>Register</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="container-register">
            <h1>Register</h1>
            <input name="phonenumber" type="text" placeholder="Phone Number">
            <input name="email" type="text" placeholder="Email">
            <input name="fullname" type="text" placeholder="Full Name">
            <input name="birthday" type="date">
            <input name="address" type="text" placeholder="Address">

            <label for="identity_card_1">Front Identity Card</label>
            <input name="identity_card_1" type="file">
            <label for="identity_card_2">Back Identity Card</label>
            <input name="identity_card_2" type="file">

            <div class="submit">
                <input name="register" type="submit" value="Register" class="btn-register">
                <input name="login" type="submit" value="Login" class="btn-login">
            </div>     
            <input name="reset_password" type="submit" value="Reset Password" class="btn-reset">
        </div>
    </form>


    <?php 

        if(isset($_POST['login'])) {
            header('Location: login.php');
        }

        if(isset($_POST['register'])) {
            //register
            $username = $_POST['phonenumber'];
            $phonenumber = $_POST['phonenumber'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $birthday = $_POST['birthday'];
            $address = $_POST['address'];

            if($phonenumber=="" || $email=="" || $fullname=="" || $birthday=="" || $address=="" ) {
                ?>
                    <div class="error">
                        <h2>Please complete the form</h2>
                    </div>
                <?php
                exit;
            } else if(strlen($phonenumber) < 9) {
                ?>
                    <div class="error">
                        <h2>Invalid Phone Number</h2>
                    </div>
                <?php
                exit;
            } else if(!isset($_FILES['identity_card_1']) || $_FILES['identity_card_1']['error'] == UPLOAD_ERR_NO_FILE) {
                ?>
                    <div class="error">
                        <h2>Error No File Identity Card 1</h2>
                    </div>
                <?php
                exit; 
            } else if(!isset($_FILES['identity_card_2']) || $_FILES['identity_card_2']['error'] == UPLOAD_ERR_NO_FILE) {
                ?>
                    <div class="error">
                        <h2>Error No File Identity Card 2</h2>
                    </div>
                <?php
                exit;   
            }

            //process_image
            $file_parts = explode('.',$_FILES['identity_card_1']['name']);
            $file_ext = strtolower(end($file_parts));
            $identity_card_1 = "images/" . $username . "_1." . $file_ext;
            move_uploaded_file($_FILES['identity_card_1']['tmp_name'], $identity_card_1);
        
            $file_parts = explode('.',$_FILES['identity_card_2']['name']);
            $file_ext = strtolower(end($file_parts));
            $identity_card_2 = "images/" . $username . "_2." . $file_ext;
            move_uploaded_file($_FILES['identity_card_2']['tmp_name'], $identity_card_2);

            //password random
            $password1 = "";    
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $size = strlen($chars);
            for($i = 0; $i < 6; $i++) {
                $password1 .= $chars[rand( 0, $size - 1)];
            }

            $password = md5($password1);

            //1.2 phone and email
            $sql = "SELECT * FROM user WHERE phonenumber = '$phonenumber'";
            $result = mysqli_query($conn, $sql);
            $check1 = mysqli_num_rows($result);

            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $check2 = mysqli_num_rows($result);

            if($check1 > 0 || $check2 > 0) {
                ?>
                    <div class="error">
                        <h2>Email Or Phone Exists</h2>
                    </div>
                <?php 
                exit;
            }

            $time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO user VALUES('$username', '$password', '$phonenumber', '$email', '$fullname', '$birthday', '$address', '$identity_card_1', '$identity_card_2', '0', false, '0', '', false, false, false, '$time', NULL, NULL, NULL, NULL, NULL)";
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
                $noidungthu = "<b>Hello!</b><br>Your Username: $username</br><br>Your Password: $password1</br>";
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
                        <h2>Send email success</h2>
                    </div>
                <?php 
            } catch (Exception $e) {
                ?>
                    <div class="error">
                        <h2>Send email failed</h2>
                    </div>
                <?php  
                $mail->ErrorInfo;
            }

            ?>
                <div class="error">
                    <h2>Your Username: <?=$username?></h2>
                    <h2>Your Password: <?=$password1?></h2>

                </div>
            <?php 
        }

        if(isset($_POST['reset_password'])) {
            header('Location: reset_password.php');
            exit;
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>