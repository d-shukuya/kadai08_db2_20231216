<?php
// 1. funcs.php を呼び出す
include('./funcs.php');

// 2. 変数定義
$bookId = $_GET['book_id'];
$dogEarId = $_GET['dog_ear_id'];

// 2. DB接続
$pdo = db_conn();

// 3．データ登録
// 3-1. SQL文
$stmt = $pdo->prepare(
    'DELETE FROM
        gs_bm_dog_ear
    WHERE
        id = :dog_ear_id'
);

// 3-2. バインド変数を定義
$stmt->bindValue(':dog_ear_id', $dogEarId, PDO::PARAM_INT);

// 3-3. 登録
$status = $stmt->execute();

// 4. 登録後の処理
if ($status == false) {
    sql_error($stmt);
} else {
    redirect("./book_detail.php?book_id=$bookId");
}
