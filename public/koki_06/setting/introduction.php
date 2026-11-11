<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
    header('HTTP/1.1 302 Found');
    header('Location: /login.php');
    return;
}

$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$select_sth = $dbh->prepare('SELECT id, introduction FROM users WHERE id = :id');
$select_sth->execute([':id' => $_SESSION['login_user_id']]);
$user = $select_sth->fetch();

if (!$user) {
    header('HTTP/1.1 404 Not Found');
    print('会員情報が見つかりません');
    return;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $introduction = $_POST['introduction'] ?? '';
    if (!is_string($introduction)) {
        $introduction = '';
    }

    $length = mb_strlen($introduction, 'UTF-8');
    if ($length > 1000) {
        $error = '自己紹介文は1000文字以内で入力してください。';
    } else {
        $update_sth = $dbh->prepare('UPDATE users SET introduction = :introduction WHERE id = :id');
        $update_sth->execute([
            ':id' => $user['id'],
            ':introduction' => $introduction,
        ]);

        header('HTTP/1.1 302 Found');
        header('Location: ./introduction.php');
        return;
    }
}

$value = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['introduction'] ?? '')
    : ($user['introduction'] ?? '');
if (!is_string($value)) {
    $value = '';
}
?>

<h1>自己紹介文設定/変更</h1>
<?php if ($error !== null): ?>
    <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<form method="POST">
    <div style="margin: 1em 0;">
        <label for="introduction">自己紹介文（最大1000文字）</label><br>
        <textarea id="introduction" name="introduction" maxlength="1000" rows="8" cols="60"><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <button type="submit">保存</button>
</form>
<hr>

