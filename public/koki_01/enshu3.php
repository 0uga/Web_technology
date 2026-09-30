<?php

// Redisに接続
$redis = new Redis();
$redis->connect('redis', 6379);

// Redisから投稿データを取得
$json = $redis->get('bbs_messages');

if ($json !== false) {
    // JSONを配列に変換
    $messages = json_decode($json, true);
} else {
    // まだ投稿がない場合
    $messages = [];
}

// 投稿された場合
if (isset($_POST['message'])) {

    $message = $_POST['message'];

    // 配列に投稿を追加
    $messages[] = $message;

    // 配列をJSONに変換
    $json = json_encode($messages);

    // Redisに保存
    $redis->set('bbs_messages', $json);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
</head>

<body>
<h1>掲示板</h1>
<form method="POST">
    <textarea name="message"></textarea>
    <br>
    <button type="submit">投稿</button>
</form>
<hr>
<h2>投稿一覧</h2>

<?php foreach ($messages as $message): ?>
    <p>
    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endforeach; ?>

</body>
</html>
