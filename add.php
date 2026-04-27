<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if(isset($_POST['add'])) {
        $text = $_POST['text'];
        $title = $_POST['title'];
        $sql = "INSERT INTO tasks (title, description) VALUES ('$title', '$text')";
    }

    ?>
</body>
</html>