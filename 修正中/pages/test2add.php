<?php

//データベース接続情報を格納
include("../pages/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //新しい番号を保存するための変数
    $gameid = "";
    //POSTデータの取得
    $txt_game_title = $_POST['txt_game_title'];
    $txt_game_date = $_POST['txt_game_date'];
    $txt_game_description = $_POST['txt_game_description'];
    $txt_type_name = $_POST['txt_type_name'];
    $txt_category_name = $_POST['txt_category_name'];

    $err = "";

    if ($txt_game_title == "") {
        $err .= "ゲーム名";
    }
    if ($txt_game_date == "") {
        $err .= "発表日";
    }
    if ($txt_game_description == "") {
        $err .= "内容";
    }
    if ($txt_type_name == "") {
        $err .= "ゲーム類型";
    }
    if ($txt_category_name == "") {
        $err .= "カテゴリー";
    }

    $sql = "insert into game_tb("
        . "category_no"
        . ",type_no"
        . ",game_title"
        . ",game_date"
        . ",game_description"
        . ",delete_ku"
        . ",insert_at"
        . ",update_at"
        . ") values ("
        . ":category_no"
        . ",:type_no"
        . ",:game_title"
        . ",:game_date"
        . ",:game_description"
        . ",'0'"
        . ",now()"
        . ",now()"
        . ");";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':category_no', $gameid, PDO::PARAM_INT);
        $stmt->bindValue(':type_no', $gameid, PDO::PARAM_INT);
        $stmt->bindValue(':game_title', $txt_game_title, PDO::PARAM_STR);
        $stmt->bindValue(':game_date', $txt_game_date, PDO::PARAM_STR);
        $stmt->bindValue(':game_description', $txt_game_description, PDO::PARAM_STR);
        $stmt->execute();
        //$dbh->lastInsertId()は、最後に登録した番号を取得
        $gameid = $dbh->lastInsertId();
    } catch (Exception $e) {
        echo "データの書き込みに失敗しました。" . $e->getMessage();
    }

    $sql = "insert into category_tb("
        . "category_no"
        . ",category_name"
        . ",delete_ku"
        . ",insert_at"
        . ",update_at"
        . ") values ("
        . ":category_no"
        . ",:category_name"
        . ",'0'"
        . ",now()"
        . ",now()"
        . ");";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':category_no', $gameid, PDO::PARAM_INT);
        $stmt->bindValue(':category_name', $txt_category_name, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $e) {
        echo "データの書き込みに失敗しました。" . $e->getMessage();
    }

    $sql = "insert into type_tb("
        . "type_no"
        . ",type_name"
        . ",delete_ku"
        . ",insert_at"
        . ",update_at"
        . ") values ("
        . ":type_no"
        . ",:type_name"
        . ",'0'"
        . ",now()"
        . ",now()"
        . ");";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':type_no', $gameid, PDO::PARAM_INT);
        $stmt->bindValue(':type_name', $txt_type_name, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $e) {
        echo "データの書き込みに失敗しました。" . $e->getMessage();
    }

    if (isset($gameid)) {
        //フォルダ名を日付＋ランダム文字列（４桁）で生成する//
        if (isset($_FILES['up_file'])) {
            //ASCIIコードによる文字変換は65がA、90がZとなります。
            $folder = date(ymd) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90));
            mkdir('uploads/' . $folder);
        }

        $save = 'uploads/' . $folder . '/' . basename($_FILES['up_file']['name']);

        //move_uploaded_fileで、一時ファイルを保存先ディレクトリに移動させる
        if (move_uploaded_file($_FILES['up_file']['tmp_name'], $save)) {
            $sql = "insert into game_imageload_tb("
                . "games_no"
                . ",folder_name"
                . ",file_name"
                . ",delete_ku"
                . ",insert_at"
                . ",update_at"
                . ") values ("
                . ":games_no"
                . ",:folder_name"
                . ",:file_name"
                . ",'0'"
                . ",now()"
                . ",now()"
                . ");";
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':games_no', $gameid, PDO::PARAM_INT);
                $stmt->bindValue(':folder_name', $folder, PDO::PARAM_STR);
                $stmt->bindValue(':file_name', basename($_FILES['up_file']['name'], PDO::PARAM_STR));
                $stmt->execute();
            } catch (Exception $e) {
                echo "データの書き込みに失敗しました。" . $e->getMessage();
            }

            header("Location:../pages/test2index.php");

        } else {
            echo 'アップロード失敗！';
        }
    }
} else {
    echo $err . "を修正してください。";
}
?>

<html>

<body>
    <div class="container">
        <h1>ゲーム追加</h1>
        <form action="../pages/test2add.php" method="POST" enctype="multipart/form-data">
            <p>ゲーム名</p>
            <input type="text" name="txt_game_title" /><br>
            <p>ゲーム類型</p>
            <input type="text" name="txt_type_name" /><br>
            <p>カテゴリー</p>
            <input type="text" name="txt_category_name" /><br>
            <p>発表日</p>
            <input type="text" name="txt_game_date" /><br>
            <p>内容</p>
            <textarea name="txt_game_description" style="width:300px;height:150px;"></textarea><br>
            <p>画像</p>
            <input type="file" name="up_file"><br><br>
            <input type="submit" value="投稿">
        </form>
    </div>
</body>

</html>