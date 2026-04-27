<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>projekt php</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="title">
        <input type="text" name="text">
        <button type="submit" name="add">Add</button>
    </form>
    <form action="" method="POST">
        <button type="submit" name="delete">Delete</button>
    </form>
    <?php
    include "database.php";

    ?>
    <a href="add.php">Pridať novú položku</a>

    
    
</body>
</html>