<?php

// セッションIDの取得(なければ新規で作成&設定)
$session_cookie_name = 'session_id';
$session_id = $_COOKIE[$session_cookie_name] ?? base64_encode(random_bytes(64));

if (!isset($_COOKIE[$session_cookie_name])) {
  setcookie($session_cookie_name, $session_id);
  }

// 接続 (redisコンテナの6379番ポートに接続)
$redis = new Redis();
$redis->connect('redis', 6379);

// redisにセッション変数を保存しておくキーを決めておきます。
$redis_session_key = "session-" . $session_id;

// 既にセッション変数(の配列)が何かしら格納されていればそれを，なければ空の配列を $session_values変数に保存。
$session_values = $redis->exists($redis_session_key)
  ? json_decode($redis->get($redis_session_key), true)
    : [];

// カウント数を取得。なければ初期値の0
$count = isset($session_values["count"])
  ? intval($session_values["count"])
    : 0;

// 前回のアクセス日時を取得
$previous_access = $session_values["access_datetime"] ?? null;

// 現在の日時を取得
$current_datetime = date("Y年m月d日 H:i:s");

// カウント数に1を足す
$count++;

// セッションに保存
$session_values["count"] = strval($count);
$session_values["access_datetime"] = $current_datetime;

$redis->set($redis_session_key, json_encode($session_values));

// 表示
print("このセッションでの " . strval($count) . "回目のアクセスです！<br>");

if ($previous_access !== null) {
  print("前回のアクセス日時： " . $previous_access);
  } else {
    print("前回のアクセスはありません。");
    }
