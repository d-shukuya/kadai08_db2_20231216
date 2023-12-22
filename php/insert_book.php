<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. DB接続
$pdo = db_conn();

// 3．データ登録
// 3-1. SQL文
$stmt = $pdo->prepare(
    'INSERT INTO
        gs_bm_books(id, name, url, content, created_date, update_date)
    VALUES
        (NULL, :name, :url, "", sysdate(), sysdate())'
);

// 3-2. バインド変数を定義
$stmt->bindValue(':name', $_POST["name"], PDO::PARAM_STR);
$stmt->bindValue(':url', $_POST["url"], PDO::PARAM_STR);

// 3-3. 登録
$status = $stmt->execute();

// 4. 登録後の処理
// 4-1. 失敗時の処理
if ($status == false) {
    sql_error($stmt);
}

// 4-2. 成功時の処理
// 4-2-1. 画像ファイルの保管
$id12 = str_pad($pdo->lastInsertId(), 12, "0", STR_PAD_LEFT);
$bookCoverDir = '../book_cover';
if ($_FILES["book_cover_img"]["error"] == UPLOAD_ERR_OK) {
    // 既存のファイルを削除
    $files = glob("$bookCoverDir/$id12.*");
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    // 新しいファイルを保管
    $tmp_name = $_FILES["book_cover_img"]["tmp_name"];
    $name = basename($_FILES["book_cover_img"]["name"]);
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $new_name = "$id12.$extension";
    move_uploaded_file($tmp_name, "$bookCoverDir/$new_name");
} else {
    echo "Failed to save img file";
}

// 4-2-2. index.php に遷移
redirect('../');
