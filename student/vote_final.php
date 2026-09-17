<?php
session_start();
include("../config/db.php");

// If they try to access this without a session or project ID, kick them out
if(!isset($_SESSION['temp_roll']) || !isset($_GET['pid'])){
    header("Location: login.php");
    exit();
}

$roll = $_SESSION['temp_roll'];
$dept = $_SESSION['temp_dept'];
$pid = mysqli_real_escape_string($conn, $_GET['pid']);

// --- DATABASE TRANSACTION START ---

// 1. Officially register the student now
$insertStudent = mysqli_query($conn, "INSERT INTO students(roll_number, department) VALUES('$roll', '$dept')");

if($insertStudent) {
    // 2. Log the detailed vote in vote_table
    mysqli_query($conn, "INSERT INTO vote_table(student_id, project_id) VALUES('$roll', '$pid')");

    // 3. Increment the project's vote count
    mysqli_query($conn, "UPDATE projects SET votes = votes + 1 WHERE id = $pid");

    // 4. Clear the session so they can't refresh and vote again
    session_destroy();

    header("Location: result.php?status=voted");
} else {
    echo "An error occurred. You might have already voted.";
}
?>