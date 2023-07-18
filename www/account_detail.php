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
        if(isset($_POST['admin_view_account_detail'])) {
            $username = $_POST['username'];

            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $show = mysqli_fetch_assoc($result);

            ?>
            <a href="admin.php">Home</a>
            <br>
            Phone Number: <?php echo $show['phonenumber']; ?>
            <br>
            Full Name: <?php echo $show['fullname']; ?>
            <br>
            Birthday: <?php echo $show['birthday']; ?>
            <br>
            Address: <?php echo $show['address']; ?>
            <br>
            Balance: <?php echo $show['balance']; ?>
            <br>
            <?php

            if(($show['identity_card_1'] == '' || $show['identity_card_2'] == '') && $show['active'] == 0 && $show['disable_admin'] == 0 && $show['disable_auto'] == 0) {
            ?>
                <form action="process_admin.php" method="post">
                    <input name="username" type="hidden" value="<?php echo $show['username']; ?>"/>
                    <input name="verification" type="submit" value="Verification" onclick="return confirm('Are you sure?')"/>
                    <br>
                    <input name="cancel" type="submit" value="Cancel" onclick="return confirm('Are you sure?')"/>
                    <br>
                    <input name="request" type="submit" value="Request For Additional Information" value="Verification" onclick="return confirm('Are you sure?')"/>
                </form> 
            <?php
            } else if($show['disable_auto'] == 1) {
                ?>
                <form action="process_admin.php" method="post">
                    <input name="username" type="hidden" value="<?php echo $show['username']; ?>"/>
                    <input name="unlock" type="submit" value="Unlock" onclick="return confirm('Are you sure?')"/>
                    <br>
                </form> 
                <?php
            }

            $sql = "SELECT * FROM history WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
    
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <form action="transaction_detail.php" method="post">
                                <input name="admin_view_transaction_detail" type="submit" value="Transaction Detail"/>
                                <input name="tcode" type="hidden" value="<?php echo $row['tcode']; ?>"/>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
    ?>
</body>
</html>