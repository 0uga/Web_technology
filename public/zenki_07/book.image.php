<?php

if (isset($_GET['image'])) {

    $colorCode = $_GET['color'] ?? '000000';
    $colorCode = ltrim($colorCode, '#');

    $r = hexdec(substr($colorCode, 0, 2));
    $g = hexdec(substr($colorCode, 2, 2));
    $b = hexdec(substr($colorCode, 4, 2));

    $img = imagecreatetruecolor(300, 300);

    $color = imagecolorallocate($img, $r, $g, $b);
    imagefill($img, 0, 0, $color);

    header('Content-Type: image/png');
    imagepng($img);

    imagedestroy($img);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>GDカラー画像生成</title>
</head>
<body>

<h1>GDカラー画像生成</h1>

<form method="get" target="_blank">
    <input type="hidden" name="image" value="1">

    <label>
        色を選択：
        <input type="color" name="color" value="#ff0000">
    </label>

    <button type="submit">画像生成</button>
</form>

</body>
