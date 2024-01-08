<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>


<?php

//データベース接続情報を格納
include("config.php");

$txt_email = "";
$txt_pass = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //POSTデータの取得

  $txt_email = $_POST['txt_email'];
  $txt_pass = $_POST['txt_pass'];

  $err = "";

  if ($txt_email == "") {
    $err .= "【メールアドレス】";
  }
  if ($txt_pass == "") {
    $err .= "【パスワード】";
  }

  if ($err == "") {
    try {
      $dbh = new PDO($dsn, $user, $password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "select"
        . " gut.user_id"
        . ",gut.user_name"
        . ",gut.user_password"
        . " from game_user_tb gut"
        . " where gut.user_email = :user_email"
        . " and tum.delete_ku = '0'";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':user_email', $txt_email, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch();
      if (password_verify($txt_pass, $result['password'])) {



        session_start();
        $_SESSION['seesion'] = session_id();
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['user_kj'] = $result['user_kj'];

        header("Location:testIndex.html");
      } else {
        echo "ログイン認証に失敗しました";
      }
    } catch (PDOException $e) {
      echo ($e->getMessage());
      die();
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <link rel="stylesheet" href="sign_up.css">
</head>

<body>
  <h2 class="title">ログイン</h2>
  <form method="post" action="login.php" class="shell">
    <input type="text" name="user_name" id=username" placeholder="ユーザー名">
    <input type="password" name="password" id="password" placeholder="パスワード">
    <button type="submit">ログイン</button>
  </form>
</body>

</html>