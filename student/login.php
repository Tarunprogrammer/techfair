<?php
session_start();
include("../config/db.php");

if(isset($_POST['login'])){
    $roll = strtoupper(mysqli_real_escape_string($conn, $_POST['roll'])); // Normalize to uppercase
    $dept = $_POST['dept'];
    $isValid = true;
    $errorMessage = "";

    // --- CSE ROLL NUMBER VALIDATION ---
    if($dept == "CSE") {
        /* Regex Explanation:
           ^2[23]      : Starts with 22 or 23
           U51A05      : Fixed middle part for CSE
           (0[1-9]|[1-8][0-9]|9[0-9]) : Ends with 01 to 99
        */
        if (!preg_match('/^(22|23)U51A05(0[1-9]|[1-9][0-9])$/', $roll)) {
            $isValid = false;
            $errorMessage = "Invalid Roll Number for CSE! Range must be 22/23U51A0501 to 0599.";
        }
    }

    if(!$isValid) {
        echo "<script>alert('$errorMessage');</script>";
    } else {
        // Check if this roll number ALREADY exists in the students table
        $check = mysqli_query($conn, "SELECT * FROM students WHERE roll_number='$roll'");
        
        if(mysqli_num_rows($check) > 0){
            echo "<script>alert('You have already cast your vote!'); window.location='result.php';</script>";
        } else {
            // Store in session (Temporary memory)
            $_SESSION['temp_roll'] = $roll;
            $_SESSION['temp_dept'] = $dept;
            header("Location: projects.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Secure Voting</title>
    <style>
        :root { --primary: #00d2ff; --secondary: #3a7bd5; --dark: #121212; --glass: rgba(255, 255, 255, 0.08); }
        
        body { 
            margin: 0; 
            font-family: 'Poppins', sans-serif; 
            background: radial-gradient(circle at top right, #1e1e2f, #121212); 
            color: white; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            overflow: hidden;
        }

        /* Animated background glow */
        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--primary);
            filter: blur(150px);
            opacity: 0.1;
            z-index: -1;
            animation: move 10s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-50%, -50%); }
            to { transform: translate(50%, 50%); }
        }

        .login-card { 
            background: var(--glass); 
            backdrop-filter: blur(20px); 
            padding: 45px; 
            border-radius: 24px; 
            border: 1px solid rgba(255,255,255,0.1); 
            box-shadow: 0 40px 100px rgba(0,0,0,0.6); 
            width: 100%;
            max-width: 400px; 
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { 
            font-weight: 600; 
            letter-spacing: 1px; 
            margin-bottom: 5px; 
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p { color: #94a3b8; font-size: 0.85rem; margin-bottom: 30px; }

        .input-group { position: relative; margin-bottom: 20px; }

        input, select { 
            width: 100%; 
            padding: 15px; 
            border-radius: 12px; 
            border: 1px solid rgba(255,255,255,0.1); 
            background: rgba(0,0,0,0.2); 
            color: white; 
            outline: none; 
            transition: 0.3s; 
            box-sizing: border-box; 
            font-size: 0.95rem;
        }

        input:focus, select:focus { 
            border-color: var(--primary); 
            background: rgba(0,0,0,0.4);
            box-shadow: 0 0 15px rgba(0,210,255,0.2); 
        }

        /* Special Styling for the Select Dropdown Arrow */
        select option { background: #1e1e2f; color: white; }

        button { 
            width: 100%; 
            padding: 15px; 
            margin-top: 10px; 
            border-radius: 12px; 
            border: none; 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: #121212; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer; 
            transition: 0.4s; 
        }

        button:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 10px 25px rgba(0,210,255,0.4); 
            filter: brightness(1.1);
        }

        .note { font-size: 0.75rem; color: #64748b; margin-top: 15px; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>VOTER ACCESS</h2>
        <p>Enter your credentials to proceed</p>
        
        <form method="post">
            <div class="input-group">
                <input type="text" name="roll" placeholder="Roll Number (e.g. 23U51A0501)" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <select name="dept" required>
                    <option value="" disabled selected>Select Department</option>
                    <option value="CSE">CSE</option>
                    <option value="ECE">ECE</option>
                    <option value="CSD">CSD</option>
                    <option value="IT">IT</option>
                </select>
            </div>
            
            <button name="login">Verify & Proceed</button>
        </form>

    </div>
</body>
</html>