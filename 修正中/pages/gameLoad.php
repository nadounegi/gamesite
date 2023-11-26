<?php
include("config.php");

$type = isset($_GET['type_name']) ? urldecode($_GET['type_name']) : null;
$category = isset($_GET['category_name']) ? ($_GET['category_name']) : null;

try {
  $dbh = new PDO($dsn, $username, $password, $options);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if ($type !== null) {
    $sql = "SELECT"
      . "tgt.id"
      . ",gt.game_title"
      . ",gt.game_date"
      . ",gt.game_description"
      . ",gt.insert_at"
      . ",git.folder_name"
      . ",git.file_name"
      . ",tt.type_name"
      . ",ct.category_name"
      . "FROM game_tb gt"
      . "LEFT JOIN game_imageload_tb git"
      . "ON gt.id = git.games_no"
      . "LEFT JOIN type_tb tt"
      . "ON gt.id = tt.type_no"
      . "LEFT JOIN category_tb ct"
      . "ON gt.id = ct.category_no"
      . "WHERE type_name = :type_name";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':type_name', $type_name, PDO::PARAM_STR);
  } elseif ($category_name !== null) {
    $sql = "SELECT"
      . "tgt.id"
      . ",gt.game_title"
      . ",gt.game_date"
      . ",gt.game_description"
      . ",gt.insert_at"
      . ",git.folder_name"
      . ",git.file_name"
      . ",tt.type_name"
      . ",ct.category_name"
      . "FROM game_tb gt"
      . "LEFT JOIN game_imageload_tb git"
      . "ON gt.id = git.games_no"
      . "LEFT JOIN type_tb tt"
      . "ON gt.id = tt.type_no"
      . "LEFT JOIN category_tb ct"
      . "ON gt.id = ct.category_no"
      . "WHERE category_name = :category_name";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':category_name', $category_name, PDO::PARAM_STR);
  } else {
    echo "選択されたゲームタイプまたはカテゴリーがありません";
    exit;
  }
  $stmt->execute();
  $gamedata = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo $e->getMessage();
  die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css"> -->
  <link rel="stylesheet" href="css/t.css">
  <title>Document</title>
</head>

<body>
  <div id="h_inner">
    <header>
      <h1 class="logo"><a href="index.php"><img src="images/納豆ゲーム(已去底).png" alt=""></a></h1>
      <div class="separator_t"></div>
      <nav>
        <ul id="gnav">
          <li>
            <a href="gameLoad.php?category_name='ビデオゲーム'">Video Games</a>
          </li>
          <li>
            <a href="gameLoad.php?category_name='PCゲーム'">PC Games</a>
          </li>
          <li>
            <a href="gameLoad.php?category_name='モバイルゲーム'">Mobile Games</a>
          </li>
          <div class="separator_r"></div>
        </ul>
      </nav>
      <form action="search.php" method="POST">
        <input type="text" size=50 placeholder="ゲーム名、又はカテゴリを入力してください" name="keywords">
        <input type="submit" value="検索">
      </form>
  </div>
  </header>
  <main>
    <a href=" .php">ゲーム追加</a>
    <table border=1>
      <tr>
        <th>id</th>
        <th>画像</th>
        <th>ゲーム名</th>
        <th>ゲーム類型</th>
        <th>カテゴリ</th>
        <th>発表日</th>
        <th>紹介</th>
      </tr>
      <?php foreach ($gamedata as $row): ?>
        <tr>
          <td>
            <?php echo $row['id']; ?>
          </td>
          <td>
            <?php
            $imgFilePath = "uploads/" . $row['folder_name'] . "/" . $row['file_name'];
            if (isset($row['file_name']) && file_exists($imgFilePath)) {
              echo "<img src='" . $imgFilePath . "' width='200'>";
            }
            ?>
          </td>
          <td>
            <?php echo $row['game_title']; ?>
          </td>
          <td>
            <a href="gameLoad.php?type_name=<?php echo urldecode($row['type_name']); ?>">
              <?php echo $row['type_name']; ?>
            </a>
          </td>
          <td>
            <a href="gameLoad.php?category_name=<?php echo urldecode($row['category_name']); ?>">
              <?php echo $row['category_name']; ?>
          </td>
          <td>
            <?php echo $row['game_date']; ?>
          </td>
          <td>
            <?php echo $row['game_description']; ?>
          </td>
        </tr>
      <?php endforeach; ?>

    </table>
  </main>
</body>