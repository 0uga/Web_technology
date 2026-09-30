<?php

$redis = new Redis();

// Redisに接続
$redis->connect('redis', 6379);

// アクセス数を1増やす
$count = $redis->incr('access_count');

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>アクセスカウンタ</title>
</head>
<body>

<h1>アクセスカウンタ</h1>

<p>このページへのアクセス数：<?= $count ?> 回</p>

</body>
</html>
