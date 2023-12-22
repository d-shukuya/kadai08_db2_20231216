<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. DB接続
$pdo = db_conn();

// 3．データ登録
// 3-1. SQL文
$stmt = $pdo->prepare(
    'DELETE FROM
        gs_bm_books
    WHERE
        id = :book_id'
);

// 3-2. バインド変数を定義
$stmt->bindValue(':book_id', $_GET["book_id"], PDO::PARAM_INT);

// 3-3. 登録
$status = $stmt->execute();

// 4. 登録後の処理
// 4-1. 失敗時の処理
if ($status == false) {
    sql_error($stmt);
}

// 4-2. 成功時の処理
redirect('../');
exit();
