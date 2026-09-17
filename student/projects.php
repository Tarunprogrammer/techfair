<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['temp_roll'])) { header("Location: login.php"); }

$projects = mysqli_query($conn, "SELECT * FROM projects");
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #121212; color: white; font-family: sans-serif; padding: 50px; }
        .container { max-width: 1000px; margin: auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .card { background: #1e1e2f; padding: 25px; border-radius: 15px; border-left: 5px solid #00d2ff; transition: 0.3s; }
        .card:hover { transform: translateY(-10px); background: #252545; }
        .vote-link { display: inline-block; margin-top: 15px; color: #00d2ff; text-decoration: none; font-weight: bold; border: 1px solid #00d2ff; padding: 8px 20px; border-radius: 5px; }
        .vote-link:hover { background: #00d2ff; color: #000; }
    </style>
</head>
<body>
    <h1 style="text-align:center; font-weight:300;">Select Your Favorite Project</h1>
    <div class="container">
        <?php while($row=mysqli_fetch_assoc($projects)){ ?>
        <div class="card">
            <h3><?php echo $row['project_name']; ?></h3>
            <p style="opacity:0.7;">Team: <?php echo $row['team_name']; ?></p>
            <a href="vote_final.php?pid=<?php echo $row['id']; ?>" class="vote-link" 
               onclick="return confirm('Finalize your vote for <?php echo $row['project_name']; ?>?')">
               Cast Vote
            </a>
        </div>
        <?php } ?>
    </div>
</body>
</html>