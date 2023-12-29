<?php
include('config.php');

try {
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  // ゲームデータの取得
  $sql = "SELECT"
    . " gt.id"
    . ",gt.game_title"
    . ",gt.game_date"
    . ",gt.game_description"
    . ",gt.insert_at"
    . ",git.folder_name"
    . ",git.file_name"
    . ",tt.type_name"
    . ",ct.category_name"
    . " FROM game_tb gt"
    . " LEFT JOIN game_imageload_tb git"
    . " ON gt.id = git.games_no"
    . " LEFT JOIN type_tb tt"
    . " ON gt.id = tt.type_no"
    . " LEFT JOIN category_tb ct"
    . " ON gt.id = ct.category_no";

  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  $count = $stmt->rowCount();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $gamedata[] = $row;
  }
} catch (PDOException $e) {
  echo $e->getMessage();
  die();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>ゲーム一覧</h2>
    <?php foreach ($gamedata as $row) : ?>
      <div class="card">
        <div class="game-image">
            <?php
            $imgFilePath = "uploads/" . $row['folder_name'] . "/" . $row['file_name'];
            if (isset($row['file_name']) && file_exists($imgFilePath)) {
              echo "<img src='" . $imgFilePath . "' width='200'>";
            }
            ?>
        </div>
        <div class="game-info">
          <div class="game-title">
            <?php echo $row['game_title']; ?>
          </div>
          <div class="game-type">ゲーム類型： <a href="gameLoad.php?type_name=<?php echo urldecode($row['type_name']); ?>">
            <?php echo $row['type_name']; ?>
            </a></div>
          <div class="game-category">カテゴリ:
            <a href="gameLoad.php?category_name=<?php echo $row['category_name']; ?>">
                <?php echo $row['category_name']; ?>
            
            </a>
          </div>
          <div class="game-date">
            <?php echo $row['game_date']; ?>
          </div>
          <div class="game-description">
            <?php echo $row['game_description']; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
</body>
</html>