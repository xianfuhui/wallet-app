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
    <title>Document</title>
</head>
<body>
    <?php
        if(isset($_POST['upfile'])) {
            $file_parts = explode('.',$_FILES['identity_card_1']['name']);
            $file_ext = strtolower(end($file_parts));
            $identity_card_1 = "images/" . $username . "_1." . $file_ext;
            move_uploaded_file($_FILES['identity_card_1']['tmp_name'], $identity_card_1);
        
            $file_parts = explode('.',$_FILES['identity_card_2']['name']);
            $file_ext = strtolower(end($file_parts));
            $identity_card_2 = "images/" . $username . "_2." . $file_ext;
            move_uploaded_file($_FILES['identity_card_2']['tmp_name'], $identity_card_2);

            $sql = "UPDATE user SET identity_card_1 = '$identity_card_1', identity_card_2 = '$identity_card_2' WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            header('Location: index.php');
            exit;
        }
    ?>
</body>
</html>