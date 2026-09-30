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

// アクセス情報取得
$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$accessTime = date('Y-m-d H:i:s');

// DBへ保存
$sql = "INSERT INTO access_log(ip_address, user_agent, access_time)
        VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ip, $userAgent, $accessTime]);

// 新しい順に取得
$sql = "SELECT * FROM access_log
        ORDER BY access_time DESC";
$stmt = $pdo->query($sql);

header('Content-Type: text/plain; charset=UTF-8');

echo "アクセスログ一覧\n";
echo "==============================\n\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . "\n";
    echo "日時: " . $row['access_time'] . "\n";
    echo "IP: " . $row['ip_address'] . "\n";
    echo "UserAgent: " . $row['user_agent'] . "\n";
    echo "------------------------------\n";
}
