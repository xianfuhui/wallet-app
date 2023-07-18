<?php
    include("admin/db.php");
    $user_id = $_REQUEST['phonenumber'];

    if ($user_id !== "") {
      $query = mysqli_query($conn, "SELECT fullname FROM user WHERE username = '$user_id'");

      $row = mysqli_fetch_array($query);

      $fullname = $row["fullname"];
    }

    $result = array("$fullname");

    $myJSON = json_encode($result);
    echo $myJSON;
?>