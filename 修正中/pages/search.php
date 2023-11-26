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
    . " LEFT JOIN game_imageload_tb git"
    . " ON gt.id = git.games_no"
    . " LEFT JOIN type_tb tt"
    . " ON gt.id = tt.type_no"
    . " LEFT JOIN category_tb ct"
    . " ON gt.id = ct.category_no"
    . "and git.delete_ku = '0' "
    . "where gt.delete_ku = '0'";

  $params = [];

  foreach ($keywords as $word) {
    $sql .= " AND (tgt.game_title LIKE ? OR tgt.game_description LIKE ? OR tt.type_name LIKE ? OR ct.category_name LIKE ? OR ct.category_name LIKE ?)";
    $params[] = '%' . $word . '%';
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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>検索結果</title>
</head>

<body>
  <h1>検索結果</h1>
  <table border="1">
    <tr>
      <th>id</th>
      <th>画像</th>
      <th>ゲーム名</th>
      <th>ゲーム類型</th>
      <th>カテゴリ</th>
      <th>発表日</th>
      <th>紹介</th>
    </tr>
    <?php if (!empty($results)): ?>
      <?php foreach ($results as $row): ?>
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
            <?php
            $gameTypeUrls = [
              'ビデオゲーム' => 'videogame.php',
              'PCゲーム' => 'pcgame.php',
              'モバイルゲーム' => 'mobilegame.php',
            ];
            $gameType = $row['game_types']; //データベースからゲーム類型を取得
            $gameTypeUrl = isset($gameTypeUrls[$gameType]) ? $gameTypeUrls[$gameType] : '#';
            ?>
            <a href="<?php echo $gameTypeUrl; ?>">
              <?php echo $gameType; ?>
            </a>
          </td>
          <td>
            <?php
            $gameCategory = $row['game_category'];
            $gameCategoryUrl = isset($gameCategoryUrl[$gameCategory]) ? $gameCategoryUrl[$gameCategory] : '#';
            ?>
            <a href="<?php echo $gameCategoryUrl; ?>">
              <?php echo $gameCategory; ?>
            </a>
          </td>
          <td>
            <?php echo $row['game_date']; ?>
          </td>
          <td>
            <?php echo $row['game_description']; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>
</body>

</html>