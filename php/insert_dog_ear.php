<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. 変数定義
$bookId = $_POST["book_id"];

// 3．データ登録
// 3-1. dogEar の登録
$pdo = db_conn();
$stmt = $pdo->prepare(
  "INSERT INTO
    gs_bm_dog_ear(id, book_id, page_number, line_number, content, created_date, update_date)
  VALUES(
    NULL, :bookId, '', '', '', sysdate(), sysdate()
  )"
);
$stmt->bindValue(':bookId', $bookId, PDO::PARAM_INT);

$status = $stmt->execute();
if ($status == false) {
  sql_error($stmt);
} else {
  $id = $pdo->lastInsertId();
}

// 3-2. dogEarOrder の更新
$orderAry = json_decode($_POST['dog_ear_order'], true);
$orderAry[] = (int)$id;
$orderJSON = json_encode($orderAry);

// update_order.php へ POST
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/gs_code/kadai/kadai08_db2_20231216/php/update_order.php');
curl_setopt($ch, CURLOPT_POST, true);
$data = array(
    'type' => 'dog_ear',
    'book_id' => $bookId,
    'order' => $orderJSON,
);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$res = curl_exec($ch);
if($res === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo 'Operation completed without any errors';
}

curl_close($ch);

// 4. index.php に遷移
redirect("./book_detail.php?book_id=$bookId");