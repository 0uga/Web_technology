<?php

// APCuが有効か確認
if (!function_exists('apcu_fetch')) {
    die('APCuが有効になっていません');
}

$key = 'access_counter';

// 初回アクセス時
if (!apcu_exists($key)) {
    apcu_store($key, 0);
}

// カウントアップ
$count = apcu_inc($key);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>APCuアクセスカウンタ</title>
</head>
<body>
    <h1>APCuアクセスカウンタ</h1>

    <p>アクセス数: <?= htmlspecialchars($count) ?></p>

</body>
