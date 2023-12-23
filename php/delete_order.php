<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. 変数定義
$bookId = $_POST['book_id'];

// 3. DB接続
$pdo = db_conn();

// 4．データ登録
$stmt = $pdo->prepare(
    'DELETE FROM
        gs_bm_order
    WHERE
        book_id = :book_id'
);
$stmt->bindValue(':book_id', $bookId, PDO::PARAM_INT);

// 4-3. 登録
$status = $stmt->execute();

// 5. 登録後の処理
// 5-1. 失敗時の処理
if ($status == false) {
    sql_error($stmt);
}

// 5-2. 成功時の処理
exit();
