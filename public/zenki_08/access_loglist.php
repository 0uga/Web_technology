
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
} catch (PDOException $e) {
    die("接続失敗: " . $e->getMessage());
}

$perPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$total = $pdo->query(
    "SELECT COUNT(*) FROM access_log"
)->fetchColumn();

$totalPages = ceil($total / $perPage);

$offset = ($page - 1) * $perPage;

$sql = "
SELECT *
FROM access_log
ORDER BY access_time DESC
LIMIT $perPage OFFSET $offset
";

$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アクセスログ</title>
</head>

<body style="
font-family:Arial;
background:#f5f5f5;
margin:30px;
">

<h1 style="
text-align:center;
">
アクセスログ
</h1>

<div style="
background:white;
padding:15px;
margin-bottom:20px;
border-radius:10px;
">
総アクセス数：<?= $total ?>
</div>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>

<div style="
background:white;
padding:15px;
margin-bottom:15px;
border-left:5px solid #3498db;
border-radius:10px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
">

<b>ID:</b> <?= $row['id'] ?><br>

<b>日時:</b>
<?= htmlspecialchars($row['access_time']) ?><br>

<b>IP:</b>
<?= htmlspecialchars($row['ip_address']) ?><br>

<b>UserAgent:</b><br>

<div style="
margin-top:5px;
padding:10px;
background:#f8f8f8;
word-break:break-all;
">
<?= htmlspecialchars($row['user_agent']) ?>
</div>

</div>

<?php endwhile; ?>

<div style="
text-align:center;
margin-top:20px;
">

<?php for($i=1; $i<=$totalPages; $i++): ?>

<?php if($i == $page): ?>

<span style="
padding:10px 15px;
background:#3498db;
color:white;
border-radius:5px;
margin:2px;
">
<?= $i ?>
</span>

<?php else: ?>

<a
href="?page=<?= $i ?>"
style="
padding:10px 15px;
background:white;
border:1px solid #ccc;
text-decoration:none;
border-radius:5px;
margin:2px;
display:inline-block;
"
>
<?= $i ?>
</a>

<?php endif; ?>

<?php endfor; ?>

</div>

</body>
</html>
