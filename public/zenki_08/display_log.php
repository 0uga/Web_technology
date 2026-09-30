<?php

date_default_timezone_set('Asia/Tokyo');

$host = 'mysql';
$dbname = 'example_db';
$user = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("接続失敗: " . $e->getMessage());
}

// created_atの降順で取得
$sql = "SELECT * FROM hogehoge ORDER BY created_at DESC";
$stmt = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アクセスログ一覧</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #eeeeee;
        }
    </style>
</head>
<body>

<h1>アクセスログ一覧</h1>

<table>
    <tr>        
        <th>送信日時</th>
        <th>送信内容</th>
    </tr>

    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
    <tr>
        <td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') ?></td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
