<?php
    ob_start();
    session_start();

    if(isset($_SESSION['admin']) == false) {
        header('Location: admin_login.php');
        exit;
    }

    include("admin/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if(isset($_POST['verification'])) {
            $username = $_POST['username'];

            $sql = "UPDATE user SET active = true WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
        } 

        if(isset($_POST['cancel'])) {
            $username = $_POST['username'];
            
            $time = date("Y-m-d H:i:s");
            $sql = "UPDATE user SET disable_admin = true, time_disable_admin = '$time' WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
        } 

        if(isset($_POST['request'])) {
            $username = $_POST['username'];

            $sql = "UPDATE user SET identity_card_1 = '', identity_card_2 = '', active = false WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
        } 

        if(isset($_POST['unlock'])) {
            $username = $_POST['username'];

            $sql = "UPDATE user SET disable_auto = false, count_password_wrong = 0, time_count_password_wrong = null WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
        }

        if(isset($_POST['approval'])) {
            $tcode = $_POST['tcode'];

            $sql = "SELECT * FROM history WHERE tcode = '$tcode'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);

            $username = $show['username'];
            $amount = $show['amount'];
            $fee = $show['fee'];
            $fee_of = $show['fee_of'];

            if($show['receiver'] != '') {
                $receiver = $show['receiver'];

                $sql = "SELECT * FROM user WHERE username = '$receiver'";
                $result = mysqli_query($conn, $sql);
                $show_receiver = mysqli_fetch_assoc($result);

                //////tru fee//////
                if($fee_of == 'sender') {
                    $sql = "UPDATE user SET balance = balance - $fee WHERE username = '$username'";
                    $result = mysqli_query($conn, $sql);
                } else if($fee_of == 'receiver') {
                    $sql = "UPDATE user SET balance = balance - $fee WHERE username = '$receiver'";
                    $result = mysqli_query($conn, $sql);
                }
                ////////////

                /////update balance///////
                $sql = "UPDATE user SET balance = balance - ($amount - $fee) WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);

                $sql = "UPDATE user SET balance = balance + ($amount - $fee) WHERE username = '$receiver'";
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
                    $nguoigui = 'huyremoving9031@gmail.com';
                    $matkhau = '#ChoHuy77@';
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
                    echo 'Error Send Mail<br>: ', $mail->ErrorInfo;
                }
                ////////////

                $sql = "UPDATE history SET status = 'Success' WHERE tcode = '$tcode'";
                $result = mysqli_query($conn, $sql);

                header('Location: admin.php');
                exit;
            }

            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);
            
            $sql = "UPDATE user SET balance = balance - $amount WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            $sql = "UPDATE history SET status = 'Success' WHERE tcode = '$tcode'";
            $result = mysqli_query($conn, $sql);
        }

        header('Location: admin.php');
    ?>
</body>
</html>