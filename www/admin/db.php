<?php
    $servername = "mysql-server";
    $username = "root";
    $password = "root";
    $database = "database";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>