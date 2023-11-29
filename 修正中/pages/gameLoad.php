<?php
include("config.php");

$type = isset($_GET['type_name']) ? urldecode($_GET['type_name']) : null;
$category = isset($_GET['category_name']) ? ($_GET['category_name']) : null;

try {
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if ($type !== null) {
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
      . " FROM game_tb gt "
      . " LEFT JOIN game_imageload_tb git ON gt.id = git.games_no "
      . " LEFT JOIN type_tb tt ON gt.id = tt.type_no "
      . " LEFT JOIN category_tb ct ON gt.id = ct.category_no "
      . " WHERE tt.type_name = :type_name";  // Added space and parameter placeholder
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':type_name', $type, PDO::PARAM_STR);
  } elseif ($category !== null) {
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
      . " FROM game_tb gt "
      . " LEFT JOIN game_imageload_tb git ON gt.id = git.games_no "
      . " LEFT JOIN type_tb tt ON gt.id = tt.type_no "
      . " LEFT JOIN category_tb ct ON gt.id = ct.category_no "
      . " WHERE ct.category_name = :category_name";  // Added space and parameter placeholder

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':category_name', $category, PDO::PARAM_STR);
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
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
  <link rel="stylesheet" href="css/gameload.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Zen+Maru+Gothic:wght@400;700;900&display=swap"
    rel="stylesheet">
  <title>Document</title>
</head>

<body>
  <div id="h_inner">
    <header>
      <h1 class="logo"><a href="test2index.php"><img src="images/納豆ゲーム(已去底).png" alt=""></a></h1>
      <div class="separator_t"></div>
      <nav>
        <ul id="gnav">
          <li>
            <a href="gameLoad.php?type_name=ビデオゲーム">ビデオゲーム</a>
          </li>
          <li>
            <a href="gameLoad.php?type_name=PCゲーム">PCゲーム</a>
          </li>
          <li>
            <a href="gameLoad.php?type_name=モバイルゲーム">モバイルゲーム</a>
          </li>
          <div class=" separator_r">
          </div>
        </ul>
      </nav>
      <form action="search.php" method="POST">
        <input type="text" size=50 placeholder="ゲーム名、又はカテゴリを入力してください" name="keywords">
        <button type="submit">検索</button>
      </form>
    </header>
    <main>
      <?php foreach ($gamedata as $row): ?>

        <?php
        if (isset($_GET['type_name'])) {
          // Display game type title
          echo '<h2>' . urldecode($_GET['type_name']) . '</h2>';
        } elseif (isset($_GET['category_name'])) {
          // Display category title
          echo '<h2>' . urldecode($_GET['category_name']) . '</h2>';
        } else {
          echo "選択されたゲームタイプまたはカテゴリーがありません";
          exit;
        }
        ?>
      <?php endforeach ?>
      <?php foreach ($gamedata as $row): ?>
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
            <div class="game-type">ゲーム類型： <a href="gameLoad.php?type_name=<?php echo urlencode($row['type_name']); ?>">
                <?php echo $row['type_name']; ?>
              </a></div>
            <div class="game-category">カテゴリ:
              <a href="gameLoad.php?category_name=<?php echo urlencode($row['category_name']); ?>">
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
    </main>
</body>

</html>