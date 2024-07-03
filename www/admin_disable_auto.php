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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/style.css"> 
	<title>Admin</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand">Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_waiting.php">Waiting List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_active.php">Active List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_disable_auto.php">Disable Auto List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_disable_admin.php">Disable Admin List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_approval.php">Money Approval List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php
        $sql = "SELECT * FROM user WHERE disable_auto = true ORDER BY time_register_upload_image DESC";
        $result = mysqli_query($conn, $sql);

        row($result);

        function row($result) {
            if(mysqli_num_rows($result) == 0) {
                echo 'No Record Found';
                exit;
            }

            ?>
            <table border="1">
            <tr>
                <td>Username</td>
                <td>Time Disable Auto</td>
                <td>View</td>
            </tr>     
            <?php
                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['time_count_password_wrong']; ?></td>
                        <form action="admin_account_detail.php" method="post">
                            <td><input name="admin_view_account_detail" type="submit" value="Account Detail"/></td>
                            <input name="username" type="hidden" value="<?php echo $row['username']; ?>"/>
                        </form>
                    </tr>
                    <?php
                }
            ?>
            </table>
            <?php
        }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script> 
</body>
</html>