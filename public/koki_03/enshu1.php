<?php
// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

// エラーメッセージを格納する変数
$error_message = '';

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {

  // 入力されたメールアドレスが既に登録されているか確認する
  $check_sth = $dbh->prepare("SELECT * FROM users WHERE email = :email");
  $check_sth->execute([
    ':email' => $_POST['email'],
  ]);

  // 登録済みのメールアドレスが見つかった場合
  if ($check_sth->fetch()) {
    $error_message = '既にこのメールアドレスは登録されています';
  } else {

    // 登録されていなければ会員情報を登録する
    $insert_sth = $dbh->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $insert_sth->execute([
      ':name' => $_POST['name'],
      ':email' => $_POST['email'],
      ':password' => $_POST['password'],
    ]);

    // 処理が終わったら完了画面にリダイレクト
    header("HTTP/1.1 303 See Other");
    header("Location: ./signup_finish.php");
    return;
  }
}
?>

<h1>会員登録</h1>

<!-- エラーメッセージを表示 -->
<?php if (!empty($error_message)): ?>
  <p style="color: red;">
    <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
  </p>
<?php endif; ?>

<!-- 登録フォーム -->
<form method="POST">
  <label>
    名前:
    <input type="text" name="name">
  </label>
  <br>

  <label>
    メールアドレス:
    <input type="email" name="email">
  </label>
  <br>

  <label>
    パスワード:
    <input type="password" name="password" minlength="6" autocomplete="new-password">
  </label>
  <br>

  <button type="submit">決定</button>
</form>
