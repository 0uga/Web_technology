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

$select_sth = $dbh->prepare('SELECT id, birthday FROM users WHERE id = :id');
$select_sth->execute([':id' => $_SESSION['login_user_id']]);
$user = $select_sth->fetch();

if (!$user) {
    header('HTTP/1.1 404 Not Found');
    print('会員情報が見つかりません');
    return;
}

$error = null;
$value = $user['birthday'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = $_POST['birthday'] ?? '';
    if (!is_string($value)) {
        $value = '';
    }

    if ($value !== '') {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        $date_errors = DateTimeImmutable::getLastErrors();
        if (!$date || ($date_errors !== false && ($date_errors['warning_count'] > 0 || $date_errors['error_count'] > 0))
            || $date->format('Y-m-d') !== $value
            || $date > new DateTimeImmutable('today')) {
            $error = '有効な過去の日付を入力してください。';
        }
    }

    if ($error === null) {
        $update_sth = $dbh->prepare('UPDATE users SET birthday = :birthday WHERE id = :id');
        $update_sth->execute([
            ':id' => $user['id'],
            ':birthday' => $value === '' ? null : $value,
        ]);
        header('HTTP/1.1 302 Found');
        header('Location: ./birthday.php');
        return;
    }
}
?>

<h1>生年月日設定/変更</h1>
<?php if ($error !== null): ?>
    <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<form method="POST">
    <div style="margin: 1em 0;">
        <label for="birthday">生年月日</label><br>
        <input id="birthday" type="date" name="birthday" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" max="<?= (new DateTimeImmutable('today'))->format('Y-m-d') ?>">
    </div>
    <button type="submit">保存</button>
</form>
<p>未入力で保存すると、生年月日を削除できます。</p>
<hr>

