<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>現在日時表示</title>
</head>
<body>
    <h1>現在の日時</h1>

    <?php
    // 日本時間を設定
    $timezone = new DateTimeZone('Asia/Tokyo');

    // 現在日時を取得
    $now = new DateTime('now', $timezone);

    #now = new \DateTime('now', new \DateTimeZone('Asia/Tokyo'));
    #echo $now->format('Y年m月d日 H時i分s秒');

    // 表示
    echo $now->format('Y年m月d日 H時i分s秒');
    ?>

</body>
</html>
