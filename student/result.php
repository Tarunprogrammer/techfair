<?php
include("../config/db.php");
$result = mysqli_query($conn, "SELECT * FROM projects ORDER BY votes DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #121212; color: white; font-family: sans-serif; padding: 40px; }
        .table-container { max-width: 800px; margin: auto; background: #1e1e2f; border-radius: 15px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
        table { width: 100%; border-collapse: collapse; }
        th { background: #3a7bd5; padding: 20px; text-align: left; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        tr:hover { background: rgba(255,255,255,0.02); }
        .rank-circle { background: #00d2ff; color: #000; padding: 5px 12px; border-radius: 50%; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">🏆 Project Leaderboard</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Rank</th>
                <th>Project Name</th>
                <th>Votes</th>
            </tr>
            <?php $rank=1; while($row=mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><span class="rank-circle"><?php echo $rank++; ?></span></td>
                <td><?php echo $row['project_name']; ?></td>
                <td style="color: #00d2ff; font-weight: bold;"><?php echo $row['votes']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>