<?php
require('config.php');

// データベース接続
$dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));

function dd($values)
{
  echo "<pre>", print_r($values, true), "</pre>";
  die();
}


function selectAll($table, $jyoho = [], $setting = '', $setting2 = '')
{
  global $dbh;
  try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM $table";
    $params = [];
    if (!empty($jyoho)) {
      $sql .= " WHERE ";
      $clauses = [];
      foreach ($jyoho as $key => $value) {
        $clauses[] = "$key = ?";
        $params[] = $value;
      }
      $sql .= implode(' AND ', $clauses);
    }
    $sql .= ' ' . $setting;

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
function insert($table, $jyoho)
{
  global $dbh;
  try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $length = count($jyoho);
    $i = 1;
    $j = 1;

    $sql = "INSERT INTO $table(";
    foreach ($jyoho as $key => $value) {
      if ($length == $i) {
        $sql .= "$key";
      } else {
        $sql .= ",$key";
      }
      $i += 1;
    }
    $sql .= ")VALUES(";
    foreach ($jyoho as $key => $value) {
      if ($length == $j) {
        $sql .= ":$key";
      } else {
        $sql .= ",:$key";
      }
      $j += 1;
    }
    $sql .= ");";
    $stmt = $dbh->prepare($sql);
    foreach ($jyoho as $key => $value) {
      $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
    }
    $stmt->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}


function delete($table, $delete)
{
  global $db;
  global $dbh;
  try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "Delete from $db.$table";
    $deleteParts = [];
    $params = [];

    foreach ($delete as $key => $value) {
      $jyohoArray[] = "$key = :$key";
      $params[":$key"] = $value;
    }
    $sql .= implode(',', $deleteParts);
    $sql .= "Where" . implode('AND', $jyohoArray);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

$game_imageload = selectAll('tt_game_imageload_tb');
$game_data = selectAll('tt_game_tb');