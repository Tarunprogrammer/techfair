<?php
include("../config/db.php");

$pid = $_GET['pid'];

mysqli_query($conn,"UPDATE projects SET votes=votes+1 WHERE id=$pid");

header("Location: result.php");
?>
