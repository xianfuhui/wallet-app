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
        $sql = "SELECT * FROM history WHERE username = '$username' ORDER BY time DESC";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 0) {
            echo 'No Record Found';
            exit;
        }

        ?>  
            <table border="2" class="history-table">
                <tr class="row1"> 
                    <td>Type</td>
                    <td>Amount</td>
                    <td>Time</td>
                    <td>Status</td>
                    <td>View</td>
                </tr>   
        <?php
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?= $row['type']; ?></td>
                    <td><?= number_format($row['amount'], 0, ',', '.') . ' VND'; ?></td>
                    <td><?= $row['time']; ?></td>
                    <td class="status"><?= $row['status']; ?></td>
                    <form action="transaction_detail.php" method="post">
                        <td>
                            <input name="user_view" type="submit" value="Transaction Detail"/>
                        </td>
                        <input name="tcode" type="hidden" value="<?= $row['tcode']; ?>"/>
                    </form>
                </tr>   
                <?php
            }
        ?>
            </table>
        <?php
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>