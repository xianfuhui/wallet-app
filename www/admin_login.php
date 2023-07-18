<?php
    ob_start();
    session_start();

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
    <title>Login</title>
</head>

<body>
    <form action="" method="post">
        <div class="admin-login">
            <h1>Login</h1>
            <input name="username" type="text" placeholder="Username">
            <input name="password" type="password" placeholder="Password">
            <input name="login" type="submit" value="Login" />      
        </div>
    </form>
    <?php
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username == '' || $password == '') {
            ?>
                <div class="error">
                    <h2>Not Full</h2>
                </div>
            <?php
            exit;
        }
        if($username != 'root') {
            ?>
                <div class="error">
                    <h2>Username Wrong</h2>
                </div>
            <?php
            exit;
        }
        if($password != 'root') {
            ?>
                <div class="error">
                    <h2>Password Wrong</h2>
                </div>
            <?php
            exit;
        }
        
        $_SESSION['admin'] = 'admin';
        header('Location: admin.php');
    }
    ?>
</body>

</html>