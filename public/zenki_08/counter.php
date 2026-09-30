<?php
$pdo = new PDO("mysql:host=mysql;dbname=example_db", "root","");


$stmt = $pdo->query("SELECT count FROM access_counter WHERE id = 1");

$row = $stmt->fetch();

$count = $row["count"] + 1;

$stmt = $pdo->prepare(
    "UPDATE access_counter
     SET count = ?
     WHERE id = 1"
);

$stmt->execute([$count]);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>アクセスカウンタ</title>
</head>
<body>

<h1>現在のアクセス数：<?= $count ?></h1>

</body>
</html>
