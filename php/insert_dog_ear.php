<?php
// 1. DB接続
try {
    $pdo = new PDO('mysql:dbname=gs_bm_db;charset=utf8;host=localhost', 'root', '');
} catch (PDOException $e) {
    exit('DBConnectError:' . $e->getMessage());
}

// 2．データ登録
// 2-1. SQL文
$stmt = $pdo->prepare("
  INSERT INTO
    gs_bm_dog_ear(id, book_id, page_number, line_number, content, created_date, update_date)
  VALUES(
    NULL, :bookId, '', '', '', sysdate(), sysdate()
  )");

// 2-2. バインド変数を定義
$stmt->bindValue(':bookId', (int)$_POST["book_id"], PDO::PARAM_INT);

// 2-3. 登録
$status = $stmt->execute();

// 3. 登録後の処理
if ($status == false) {
    $error = $stmt->errorInfo();
    exit('ErrorMessage:' . $error[2]);
} else {
    header("Location: ./" . $_POST["book_id"]);
}