<?php

// Redisに接続
$redis = new Redis();
$redis->connect('redis', 6379);

// 投稿された場合
if (isset($_POST['message'])) {

    $message = $_POST['message'];

    // Redisに保存
    // 同じキーを使うので、以前の投稿は上書きされる
    $redis->set('bbs_message', $message);
}

// Redisから投稿を取得
$message = $redis->get('bbs_message');

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
<h2>現在の投稿</h2>

<?php if ($message !== false): ?>
    <p>
    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php else: ?>
    <p>まだ投稿はありません。</p>
<?php endif; ?>
</body>
</html>
