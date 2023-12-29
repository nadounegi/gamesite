<?php
include("config.php");

$txt_user = "";
$txt_email = "";
$txt_pass = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //POSTデータ取得
  $txt_user = $_POST['txt_user'];
  $txt_email = $_POST['txt_email'];
  $txt_pass = $_POST['txt_pass'];

  $err = "";
  if ($txt_user == "") {
    $err .= "ユーザー名";
  }
  if ($txt_email == "") {
    $err .= "メール";
  }
  if ($txt_pass == "") {
    $err .= "パスワード";
  }

  if ($err == "") {
    $sql = "insert into game_user_tb("
      . "user_name"
      . ",user_password"
      . ",user_email"
      . ",delete_ku"
      . ",insert_at"
      . ",update_at"
      . ")values("
      . ":user_name"
      . ",:user_password"
      . ",:user_email"
      . ",'0'"
      . ",now()"
      . ",now()"
      . ");";

    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':user_name', $txt_user, PDO::PARAM_STR);
    $stmt->bindValue(':user_password', $txt_pass, PDO::PARAM_STR);
    $stmt->bindValue(':user_email', $txt_email, PDO::PARAM_STR);
    if (!$stmt->execute()) {
      return "データの書き込みに失敗しました。";
    } else {
      header("Location: login.php");

    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録</title>
  <link rel="stylesheet" href="css/sign_up.css">
</head>

<body>
  <div class="top-header">
    <div class="left-Header">
      <div class="logo">
        <a href="#"><img src="images/納豆ゲーム(已去底).png" alt=""></a>
      </div>
      <div class="left-nav">
        <nav>
          <input type="radio" name="tab" id="videogame" checked>
          <input type="radio" name="tab" id="pcgame">
          <input type="radio" name="tab" id="mobilegame">
          <label for="videogame" class="videogame">
            <a href="#"><img src="images/videogameicon.png" aria-hidden="true" alt="">ビデオゲーム</a>
          </label>
          <label for="pcgame" class="pcgame">
            <a href="#"><img src="images/pcgame.png" aria-hidden="true" alt="">PCゲーム</a>
          </label>
          <label for="mobilegame" class="mobilegame">
            <a href="#"><img src="images/MobileGame.png" aria-hidden="true" alt="">モバイルゲーム</a>
          </label>
          <div class="tab"></div>
        </nav>
      </div>
    </div>
    <div class="right-header">
      <form action="search.php" method="POST" id="search">
        <input type="text" size=50 placeholder="ゲーム名、又はカテゴリなど  を入力してください" name="keywords">
        <button type="submit"><img src="images/検索用の虫眼鏡アイコン.png" alt=""></button>
      </form>
    </div>
    <div class="anniu">
      <p>新規登録/SignUp</p>
    </div>
    <div class="register">
      <p>
        <label>ユーザー名：</label>
        <input type="text" name="txt_user">
      </p>
      <p>
        <label>メールアドレス：</label>
        <input type="text" name="txt_email">
      </p>
      <p>
        <label>パスワード：</label>
        <input type="password" name="txt_pass">
      </p>
      <input type="submit" name="submit" value="新規登録する" id="toroku">
    </div>
    </form>

</body>

</html>