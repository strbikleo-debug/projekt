<?php

session_start();


if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include "db.php";

$user_id  = $_SESSION["user_id"];
$username = $_SESSION["username"];
$sprava   = "";

// PRIDANIE TASKU
if (isset($_POST["add"])) {
    $title       = mysqli_real_escape_string($conn, $_POST["title"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    if ($title !== "") {
        $sql = "INSERT INTO tasks (user_id, title, description, status)
                VALUES ('$user_id', '$title', '$description', 'pending')";
        mysqli_query($conn, $sql);
        $sprava = "ok";
    }
}


if (isset($_POST["delete"])) {
    $task_id = (int) $_POST["task_id"];
    $sql     = "DELETE FROM tasks WHERE id = '$task_id' AND user_id = '$user_id'";
    mysqli_query($conn, $sql);
}


if (isset($_POST["toggle"])) {
    $task_id = (int) $_POST["task_id"];
    
    $res  = mysqli_query($conn, "SELECT status FROM tasks WHERE id = '$task_id' AND user_id = '$user_id'");
    $row  = mysqli_fetch_assoc($res);
    $novy = ($row["status"] === "pending") ? "done" : "pending";
    mysqli_query($conn, "UPDATE tasks SET status = '$novy' WHERE id = '$task_id' AND user_id = '$user_id'");
}

$sql    = "SELECT * FROM tasks WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$tasky  = mysqli_fetch_all($result, MYSQLI_ASSOC);

$pending = array_filter($tasky, fn($t) => $t["status"] === "pending");
$done    = array_filter($tasky, fn($t) => $t["status"] === "done");
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje úlohy – DoIt</title>
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
            --done-bg: #151f10;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 0;
        }

        
        body::before {
            content: "";
            position: fixed;
            width: 500px; height: 500px;
            background: var(--accent);
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.05;
            top: -200px; right: -200px;
            pointer-events: none;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 32px;
            border-bottom: 1px solid var(--border);
            background: var(--card);
        }
        .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--accent);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
            color: var(--muted);
            font-size: 0.9rem;
        }
        .user-info strong { color: var(--text); }

        .btn-logout {
            padding: 8px 16px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        
        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .heading {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            margin-bottom: 6px;
        }
        .subheading {
            color: var(--muted);
            margin-bottom: 32px;
            font-size: 0.95rem;
        }
        .subheading span {
            color: var(--accent);
            font-weight: 600;
        }

        
        .notif {
            background: rgba(200, 240, 74, 0.08);
            border: 1px solid rgba(200, 240, 74, 0.3);
            color: var(--accent);
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 24px;
            animation: fadeUp 0.4s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        
        .add-form {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 36px;
        }
        .add-form h2 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .form-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        input[type="text"],
        input[type="text"]:focus {
            outline: none;
        }

        .input-title {
            flex: 1;
            min-width: 160px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 11px 14px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .input-title:focus { border-color: var(--accent); outline: none; }

        .input-desc {
            flex: 2;
            min-width: 180px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 11px 14px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .input-desc:focus { border-color: var(--accent); outline: none; }

        .btn-add {
            padding: 11px 22px;
            background: var(--accent);
            color: #0f0e11;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            white-space: nowrap;
        }
        .btn-add:hover  { opacity: 0.88; }
        .btn-add:active { transform: scale(0.97); }

        
        .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title .count {
            background: var(--border);
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        
        .task-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 32px;
        }

        .task-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: border-color 0.2s;
            animation: fadeUp 0.35s ease;
        }
        .task-card:hover { border-color: #3e3a4a; }

        .task-card.done {
            background: var(--done-bg);
            border-color: #1f2e14;
        }
        .task-card.done .task-title {
            text-decoration: line-through;
            color: var(--muted);
        }
        .task-card.done .task-desc {
            color: #3a4030;
        }

        .task-body { flex: 1; }
        .task-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 4px;
        }
        .task-desc {
            font-size: 0.87rem;
            color: var(--muted);
            margin-bottom: 6px;
        }
        .task-date {
            font-size: 0.75rem;
            color: #4a4555;
        }

        .task-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
            align-items: center;
        }

        .btn-toggle {
            padding: 6px 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .task-card.done .btn-toggle:hover {
            border-color: var(--muted);
            color: var(--text);
        }

        .btn-delete {
            padding: 6px 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-delete:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        .empty {
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
            padding: 32px 0;
            border: 1px dashed var(--border);
            border-radius: 14px;
        }
    </style>
</head>
<body>


<div class="navbar">
    <div class="logo"> DoIt</div>
    <div class="user-info">
        Prihlásený ako <strong><?= htmlspecialchars($username) ?></strong>
        <form method="POST" action="logout.php" style="display:inline;">
            <button type="submit" class="btn-logout">Odhlásiť sa</button>
        </form>
    </div>
</div>


<div class="container">

    <div class="heading">Dobré ráno, <?= htmlspecialchars($username) ?> </div>
    <p class="subheading">
        Máš <span><?= count($pending) ?></span> nedokončených úloh
        a <span><?= count($done) ?></span> hotových.
    </p>

    <?php if ($sprava === "ok"): ?>
        <div class="notif">✅ Úloha bola úspešne pridaná!</div>
    <?php endif; ?>

    <!-- FORMULAR -->
    <div class="add-form">
        <h2>+ Nová úloha</h2>
        <form method="POST">
            <div class="form-row">
                <input
                    class="input-title"
                    type="text"
                    name="title"
                    placeholder="Názov úlohy *"
                    required
                >
                <input
                    class="input-desc"
                    type="text"
                    name="description"
                    placeholder="Popis (voliteľné)"
                >
                <button type="submit" name="add" class="btn-add">Pridať</button>
            </div>
        </form>
    </div>

    
    <div class="section-title">
        Nedokončené <span class="count"><?= count($pending) ?></span>
    </div>
    <div class="task-list">
        <?php if (empty($pending)): ?>
            <div class="empty"> Všetko hotové! Pridaj novú úlohu vyššie.</div>
        <?php else: ?>
            <?php foreach ($pending as $task): ?>
                <div class="task-card">
                    <div class="task-body">
                        <div class="task-title"><?= htmlspecialchars($task["title"]) ?></div>
                        <?php if ($task["description"]): ?>
                            <div class="task-desc"><?= htmlspecialchars($task["description"]) ?></div>
                        <?php endif; ?>
                        <div class="task-date"><?= $task["created_at"] ?></div>
                    </div>
                    <div class="task-actions">
                        
                        <form method="POST">
                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                            <button type="submit" name="toggle" class="btn-toggle">✓ Hotovo</button>
                        </form>
                        
                        <form method="POST">
                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                            <button type="submit" name="delete" class="btn-delete">✕ Zmazať</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    
    <?php if (!empty($done)): ?>
        <div class="section-title">
            Hotové <span class="count"><?= count($done) ?></span>
        </div>
        <div class="task-list">
            <?php foreach ($done as $task): ?>
                <div class="task-card done">
                    <div class="task-body">
                        <div class="task-title"><?= htmlspecialchars($task["title"]) ?></div>
                        <?php if ($task["description"]): ?>
                            <div class="task-desc"><?= htmlspecialchars($task["description"]) ?></div>
                        <?php endif; ?>
                        <div class="task-date"><?= $task["created_at"] ?></div>
                    </div>
                    <div class="task-actions">
                        
                        <form method="POST">
                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                            <button type="submit" name="toggle" class="btn-toggle">↩ Vrátiť</button>
                        </form>
                       
                        <form method="POST">
                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                            <button type="submit" name="delete" class="btn-delete">✕ Zmazať</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
