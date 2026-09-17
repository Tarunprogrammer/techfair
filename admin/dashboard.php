<?php
session_start();
// Security Check
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
include("../config/db.php");

// Handle Project Addition
if(isset($_POST['add'])){
    $p = mysqli_real_escape_string($conn, $_POST['project']);
    $t = mysqli_real_escape_string($conn, $_POST['team']);
    mysqli_query($conn, "INSERT INTO projects(project_name, team_name, votes) VALUES('$p','$t', 0)");
    header("Location: admin_dashboard.php"); // Refresh to clear post data
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Admin Control</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        :root {
            --primary: #00f2fe;
            --secondary: #4facfe;
            --accent: #f093fb;
            --danger: #ff4b2b;
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            background: #0f172a;
            color: #f8fafc;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background Gradients */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(147, 51, 234, 0.15) 0%, transparent 50%);
            z-index: -1;
            animation: pulseBg 10s ease-in-out infinite alternate;
        }

        @keyframes pulseBg {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .dashboard {
            width: 100%;
            max-width: 1000px;
            padding: 40px 20px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 2.5rem;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .logout-btn {
            padding: 10px 20px;
            background: var(--glass);
            border: 1px solid var(--border);
            color: #94a3b8;
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: var(--danger);
            color: white;
            border-color: transparent;
        }

        /* Powerful Card Styling */
        .card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            padding: 30px;
            border-radius: 24px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        h3 {
            margin-bottom: 20px;
            font-size: 1.25rem;
            color: var(--primary);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Form Inputs */
        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .form-group { grid-template-columns: 1fr; }
        }

        input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
            padding: 14px 20px;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.2);
            background: rgba(0, 0, 0, 0.4);
        }

        button {
            padding: 14px 30px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            border-radius: 12px;
            color: #0f172a;
            font-weight: 700;
            cursor: pointer;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.4);
            filter: brightness(1.1);
        }

        /* Project Table List */
        .project-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            margin-bottom: 12px;
            border-radius: 16px;
            transition: 0.3s;
            animation: slideIn 0.5s ease-out forwards;
        }

        .project-row:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(10px);
            border-color: var(--primary);
        }

        .p-info b {
            display: block;
            font-size: 1.1rem;
            color: #fff;
        }

        .p-info span {
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .vote-count {
            background: linear-gradient(135deg, rgba(0, 242, 254, 0.1), rgba(79, 70, 229, 0.1));
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="header">
        <h2>Nexus Admin</h2>
        <a href="logout.php" class="logout-btn">Secure Logout</a>
    </div>

    <!-- Add Project Section -->
    <div class="card">
        <h3>Deploy New Project</h3>
        <form method="post" class="form-group">
            <input name="project" placeholder="Project Title" required>
            <input name="team" placeholder="Lead Team Name" required>
            <button name="add">Add Project</button>
        </form>
    </div>

    <!-- Project List Section -->
    <div class="card">
        <h3>Real-time Metrics</h3>
        <div class="list-container">
            <?php
            $data = mysqli_query($conn, "SELECT * FROM projects ORDER BY votes DESC");
            if(mysqli_num_rows($data) > 0) {
                while($r = mysqli_fetch_assoc($data)){
                    echo "
                    <div class='project-row'>
                        <div class='p-info'>
                            <b>{$r['project_name']}</b>
                            <span>Team: {$r['team_name']}</span>
                        </div>
                        <div class='vote-count'>
                            {$r['votes']} VOTES
                        </div>
                    </div>";
                }
            } else {
                echo "<p style='color:#94a3b8; text-align:center;'>No projects deployed yet.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>