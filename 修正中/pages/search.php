<?php
//データベース接続情報を格納
include('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $searchTerm = $_POST['keywords'];
  $keywords = explode(' ', $searchTerm);

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
    . " LEFT JOIN game_imageload_tb git ON gt.id = git.games_no"
    . " LEFT JOIN type_tb tt ON gt.id = tt.type_no"
    . " LEFT JOIN category_tb ct ON gt.id = ct.category_no"
    . " WHERE git.delete_ku = '0' "
    . " AND tt.delete_ku = '0' "
    . " AND ct.delete_ku = '0' "
    . " AND gt.delete_ku = '0'";

  foreach ($keywords as $word) {
    $sql .= " AND (gt.game_title LIKE ? OR gt.game_description LIKE ? OR tt.type_name LIKE ? OR ct.category_name LIKE ?)";
    $params[] = '%' . $word . '%';
    $params[] = '%' . $word . '%';
    $params[] = '%' . $word . '%';
    $params[] = '%' . $word . '%';
  }

  try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
    die();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
  <link rel="stylesheet" href="css/search.css">
  <link rel="icon" href="images/納豆ゲーム.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Zen+Maru+Gothic:wght@400;700;900&display=swap"
    rel="stylesheet">
  <title>ゲームサイド</title>
</head>
<script src="JS/main.js"></script>

<body>
  <div class="gtranslate_wrapper"></div>
  <script>window.gtranslateSettings = { "default_language": "ja", "native_language_names": true, "wrapper_selector": ".gtranslate_wrapper", "flag_style": "3d" }</script>
  <script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>
  <div id="topNav">
    <div class="topbar">
      <a href="test2add.php">ゲーム管理</a>
    </div>
  </div>
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
    </header>
    <main>
      <!-- ビデオゲーム -->
      <h2>検索結果</h2>
      <?php if (!empty($results)): ?>
        <?php foreach ($results as $row): ?>
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
                <a href="gameLoad.php?category_name=<?php echo urldecode($row['category_name']); ?>">
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
      <?php endif; ?>
    </main>

</body>

</html>