<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. 変数定義
$bookId = $_POST["book_id"];

// 2. DB接続
$pdo = db_conn();

// 3．データ登録
// 3-1. SQL文
$stmt = $pdo->prepare(
  "INSERT INTO
    gs_bm_dog_ear(id, book_id, page_number, line_number, content, created_date, update_date)
  VALUES(
    NULL, :bookId, '', '', '', sysdate(), sysdate()
  )"
);

// 2-2. バインド変数を定義
$stmt->bindValue(':bookId', $bookId, PDO::PARAM_INT);

// 2-3. 登録
$status = $stmt->execute();

// 3. 登録後の処理
if ($status == false) {
  sql_error($stmt);
} else {
  redirect("./book_detail.php?book_id=$bookId");
}
