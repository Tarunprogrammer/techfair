<?php
session_start();
include("../config/db.php");

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = md5($_POST['password']);

    $check = mysqli_query($conn,
        "SELECT * FROM admin 
         WHERE username='$user' AND password='$pass'"
    );

    if(mysqli_num_rows($check) == 1){
        $_SESSION['admin'] = $user;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid Admin Credentials');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>
*{
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    margin:0;
    min-height:100vh;
    background: linear-gradient(135deg,#141e30,#243b55);
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    width:330px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    padding:30px;
    border-radius:16px;
    color:white;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

.login-box h2{
    text-align:center;
    margin-bottom:25px;
    letter-spacing:1px;
}

input{
    width:100%;
    padding:12px;
    margin:12px 0;
    border:none;
    border-radius:8px;
    outline:none;
    font-size:15px;
}

button{
    width:100%;
    padding:12px;
    background:#00e676;
    border:none;
    border-radius:8px;
    font-weight:bold;
    font-size:16px;
    cursor:pointer;
    margin-top:10px;
}

button:hover{
    background:#00c853;
}
</style>

</head>
<body>

<form method="post" class="login-box">
    <h2>Admin Login</h2>
    <input name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
</form>

</body>
</html>
