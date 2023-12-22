<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. 変数定義
$type = $_POST['type'];
$book_id = $_POST['book_id'];
$order = $_POST['order'];

// 3. DB接続
$pdo = db_conn();

// 4．データ登録
$stmt;
if ($type == 'books') {
    $stmt = $pdo->prepare(
        'UPDATE
            gs_bm_order
        SET
            `order` = :order
        WHERE
            `type` = :type'
    );
    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    $stmt->bindValue(':order', $order, PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare(
        'UPDATE
            gs_bm_order
        SET
            `order` = :order
        WHERE
            book_id = :book_id'
    );
    $stmt->bindValue(':order', $order, PDO::PARAM_STR);
    $stmt->bindValue(':book_id', $book_id, PDO::PARAM_INT);
}

// 4-3. 登録
$status = $stmt->execute();

// 5. 登録後の処理
// 5-1. 失敗時の処理
if ($status == false) {
    sql_error($stmt);
}

// 5-2. 成功時の処理
exit();
