<?php
// 1. funcs.php を呼び出す
include('./funcs.php');

// 2. 変数定義
$bookId = $_GET['book_id'];

// 3. DB接続
$booksPdo = db_conn();
$dogEarPdo = db_conn();

// 4．データ登録
// 4-1. booksの削除
$booksStmt = $booksPdo->prepare(
    'DELETE FROM
        gs_bm_books
    WHERE
        id = :book_id'
);

$booksStmt->bindValue(':book_id', $bookId, PDO::PARAM_INT);
$booksStatus = $booksStmt->execute();

if ($booksStatus == false) {
    sql_error($booksStmt);
}

// 4-2. dog_earの削除
$dogEarStmt = $dogEarPdo->prepare(
    'DELETE FROM
        gs_bm_dog_ear
    WHERE
        book_id = :book_id'
);

$dogEarStmt->bindValue(':book_id', $bookId, PDO::PARAM_INT);
$dogEarStatus = $dogEarStmt->execute();

if ($dogEarStatus == false) {
    sql_error($dogEarStmt);
}


// 5. TOPへ戻る
redirect('../');
exit();
