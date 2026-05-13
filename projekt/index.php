<?php


session_start();


if (isset($_SESSION["user_id"])) {
    header("Location: tasks.php");
    exit();
}

include "db.php";

$chyba = "";


if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    
    $username = mysqli_real_escape_string($conn, $username);

    $sql    = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        
        if ($password === $user["password"]) {
            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            header("Location: tasks.php");
            exit();
        } else {
            $chyba = "Zlé heslo!";
        }
    } else {
        $chyba = "Používateľ neexistuje!";
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlásenie – To-Do App</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #0f0e11;
            --card:    #1a1820;
            --border:  #2e2b36;
            --accent:  #c8f04a;
            --text:    #f0eef5;
            --muted:   #7a7585;
            --danger:  #f05a5a;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        
        body::before, body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            pointer-events: none;
        }
        body::before {
            width: 400px; height: 400px;
            background: var(--accent);
            top: -100px; right: -100px;
        }
        body::after {
            width: 300px; height: 300px;
            background: #a06af0;
            bottom: -80px; left: -80px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 4px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 36px;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
            margin-bottom: 20px;
        }
        input:focus {
            border-color: var(--accent);
        }

        .btn {
            width: 100%;
            padding: 13px;
            background: var(--accent);
            color: #0f0e11;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            margin-top: 4px;
        }
        .btn:hover  { opacity: 0.88; }
        .btn:active { transform: scale(0.98); }

        .chyba {
            background: rgba(240, 90, 90, 0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.88rem;
            margin-bottom: 20px;
        }

        .hint {
            margin-top: 24px;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
        }
        .hint span {
            color: var(--accent);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"> DoIt</div>
        <p class="subtitle">Prihlás sa a spravuj svoje úlohy</p>

        <?php if ($chyba): ?>
            <div class="chyba"><?= $chyba ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Používateľské meno</label>
            <input type="text" id="username" name="username" placeholder="napr. Admin" required>

            <label for="password">Heslo</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>

            <button type="submit" name="login" class="btn">Prihlásiť sa →</button>
        </form>

        <p class="hint">Nemáš účet? <a href="register.php">Registruj sa</a></p>
    </div>
</body>
</html>
